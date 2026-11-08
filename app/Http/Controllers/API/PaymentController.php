<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\BookingHandymanMapping;
use App\Models\Wallet;
use App\Models\AppSetting;
use App\Models\PaymentHistory;
use App\Http\Resources\API\PaymentResource;
use App\Http\Resources\API\PaymentHistoryResource;
use App\Http\Resources\API\GetCashPaymentHistoryResource;
use App\Jobs\SendPaymentReceiptSms;
use App\Services\ZarinpalPaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Braintree;

class PaymentController extends Controller
{
    public function savePayment(Request $request)
    {
        $data = $request->all();
        $data['datetime'] = isset($request->datetime) ? date('Y-m-d H:i:s', strtotime($request->datetime)) : date('Y-m-d H:i:s');
        $booking = Booking::find($request->booking_id);

        $data['customer_id'] = $booking->customer_id;
        $booking->updateBookingPrice();
        $data['total_amount'] = $booking->total_amount;
        $data['discount'] = $booking->discount;
        $result = Payment::create($data);
        if (!empty($result) && $result->payment_status == 'advanced_paid') {
            $booking->advance_paid_amount = $request->advance_payment_amount;
            $booking->status = 'pending';
        }
        $booking->payment_id = $result->id;
        // $booking->total_amount = $result->total_amount;
        $booking->update();
        $isSuccess = true;
        if ($request->payment_type == 'wallet') {
            $wallet = Wallet::where('user_id', $booking->customer_id)->first();
            if ($wallet !== null) {
                $wallet_amount = $wallet->amount;
                if ($wallet_amount >= $request->total_amount) {
                    $wallet->amount = $wallet->amount - $request->total_amount;
                    $wallet->update();
                    $activity_data = [
                        'activity_type' => 'paid_for_booking',
                        'wallet' => $wallet,
                        'booking_id' => $request->booking_id,
                        'booking_amount' => $request->total_amount,
                    ];

                    saveWalletHistory($activity_data);
                } else {
                    $message = __('messages.wallent_balance_error');
                }
            }
        } else {
            $message = __('messages.payment_completed');
            $activity_data = [
                'activity_type' => 'payment_message_status',
                'payment_status' => str_replace("_", " ", ucfirst($data['payment_status'])),
                'booking_id' => $booking->id,
                'booking' => $booking,
            ];
            saveBookingActivity($activity_data);
            if ($result->payment_status == 'failed') {
                $isSuccess = false;
            }
        }

        return comman_message_response($message, 200, $isSuccess);
    }

    public function addPayment(Request $request, ZarinpalPaymentService $gateway)
    {
        $request->validate(['booking_id' => 'required|integer|exists:bookings,id']);
        $payment = null;

        try {
            $payment = DB::transaction(function () use ($request) {
                $booking = Booking::whereKey($request->booking_id)->lockForUpdate()->firstOrFail();
                $user = $request->user();

                if (!$user || !$user->hasRole('user') || (int) $booking->customer_id !== (int) $user->id) {
                    abort(403, 'شما اجازه پرداخت این رزرو را ندارید.');
                }
                if ($booking->status !== 'completed') {
                    abort(422, 'پرداخت فقط پس از اتمام کار امکان‌پذیر است.');
                }

                $paid = Payment::where('booking_id', $booking->id)
                    ->where('payment_status', 'paid')->lockForUpdate()->first();
                if ($paid) {
                    abort(409, 'این رزرو قبلاً پرداخت شده است.');
                }

                $active = Payment::where('booking_id', $booking->id)
                    ->whereIn('payment_status', ['initiated', 'pending'])
                    ->where('created_at', '>=', now()->subMinutes(30))
                    ->latest('id')->lockForUpdate()->first();
                if ($active) {
                    return $active->setAttribute('reuse_only', true);
                }

                $booking->updateBookingPrice();
                $booking->refresh();
                $amount = (int) round($booking->total_amount);
                if ($amount < 1000) {
                    abort(422, 'مبلغ قابل پرداخت معتبر نیست.');
                }

                $handymanId = $booking->handymanAdded()->value('handyman_id');
                if (!$handymanId) {
                    abort(422, 'برای این رزرو متخصصی ثبت نشده است.');
                }

                $payment = Payment::create([
                    'customer_id' => $booking->customer_id,
                    'provider_id' => $booking->provider_id,
                    'handyman_id' => $handymanId,
                    'booking_id' => $booking->id,
                    'datetime' => now(),
                    'discount' => $booking->discount ?: 0,
                    'total_amount' => $amount,
                    'amount' => $amount,
                    'currency' => config('zarinpal.currency', 'IRT'),
                    'payment_type' => 'zarinpal',
                    'gateway' => 'zarinpal',
                    'payment_status' => 'initiated',
                    'requested_at' => now(),
                ]);

                $booking->forceFill(['payment_id' => $payment->id])->save();
                return $payment;
            });

            if ($payment->getAttribute('reuse_only')) {
                if ($payment->payment_status === 'pending' && $payment->gateway_url) {
                    return $this->paymentInitiatedResponse($payment);
                }
                return comman_message_response('درخواست پرداخت در حال ایجاد است. چند لحظه دیگر دوباره بررسی کنید.', 409, false);
            }

            $payment->load('customer');
            $response = $gateway->request(
                $payment,
                optional($payment->customer)->contact_number,
                optional($payment->customer)->email
            );

            if (!$response['success']) {
                $message = $response['message'];
                $payment->forceFill([
                    'payment_status' => 'failed',
                    'failure_code' => (string) $response['code'],
                    'failure_message' => $message,
                    'gateway_response' => $response['raw'],
                    'failed_at' => now(),
                ])->save();
                return comman_message_response('خطا در اتصال به درگاه پرداخت', 502, false);
            }

            $payment->forceFill([
                'authority' => $response['authority'],
                'gateway_url' => $response['gateway_url'],
                'gateway_response' => $response['raw'],
                'payment_status' => 'pending',
            ])->save();

            return $this->paymentInitiatedResponse($payment->fresh());
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $exception) {
            return comman_message_response($exception->getMessage(), $exception->getStatusCode(), false);
        } catch (\Throwable $exception) {
            if ($payment && $payment->exists && $payment->payment_status === 'initiated') {
                $payment->forceFill([
                    'payment_status' => 'failed',
                    'failure_message' => 'خطا در ارتباط با درگاه پرداخت.',
                    'failed_at' => now(),
                ])->save();
            }
            Log::error('Zarinpal payment initiation failed', [
                'booking_id' => $request->booking_id,
                'exception' => $exception,
            ]);
            return comman_message_response('امکان ایجاد پرداخت وجود ندارد. لطفاً دوباره تلاش کنید.', 500, false);
        }
    }

    public function addBookingPayment(Request $request, Booking $booking, ZarinpalPaymentService $gateway)
    {
        $request->merge(['booking_id' => $booking->id]);
        return $this->addPayment($request, $gateway);
    }

    public function paymentStatus(Request $request, Payment $payment)
    {
        if ((int) $payment->customer_id !== (int) $request->user()->id) {
            abort(403);
        }

        return comman_custom_response([
            'id' => $payment->uuid,
            'booking_id' => $payment->booking_id,
            'status' => $payment->payment_status,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'reference_id' => $payment->reference_id,
            'verified_at' => optional($payment->verified_at)->toIso8601String(),
        ]);
    }

    public function paymentVerification(Payment $payment, ZarinpalPaymentService $gateway)
    {
        $authority = (string) request()->query('Authority', '');
        $status = strtoupper((string) request()->query('Status', ''));

        if ($payment->payment_status === 'paid') {
            $this->dispatchPaymentReceipt($payment);
            return $this->callbackView($payment, true, 'پرداخت قبلاً با موفقیت تأیید شده است.');
        }
        if (!$authority || !$payment->authority || !hash_equals((string) $payment->authority, $authority)) {
            Log::warning('Zarinpal callback authority mismatch', ['payment_id' => $payment->id]);
            return $this->callbackView($payment, false, 'اطلاعات بازگشت درگاه معتبر نیست.');
        }
        if ($status !== 'OK') {
            $payment->forceFill([
                'payment_status' => 'cancelled',
                'failure_message' => 'پرداخت توسط کاربر لغو شد.',
                'failed_at' => now(),
            ])->save();
            return $this->callbackView($payment, false, 'پرداخت لغو شد.');
        }

        try {
            $response = $gateway->verify($payment, $authority);
            if (!$response['success']) {
                $message = $response['message'];
                $payment->forceFill([
                    'payment_status' => 'failed',
                    'failure_code' => (string) $response['code'],
                    'failure_message' => $message,
                    'gateway_response' => $response['raw'],
                    'failed_at' => now(),
                ])->save();
                return $this->callbackView($payment, false, 'پرداخت توسط زرین‌پال تأیید نشد.');
            }

            $payment = DB::transaction(function () use ($payment, $response) {
                $locked = Payment::whereKey($payment->id)->lockForUpdate()->firstOrFail();
                if ($locked->payment_status === 'paid') {
                    return $locked;
                }

                $locked->forceFill([
                    'payment_status' => 'paid',
                    'reference_id' => $response['reference_id'],
                    'txn_id' => $response['reference_id'],
                    'card_pan' => $response['card_pan'],
                    'card_hash' => $response['card_hash'],
                    'gateway_response' => $response['raw'],
                    'verified_at' => now(),
                    'failure_code' => null,
                    'failure_message' => null,
                ])->save();

                return $locked;
            });

            $this->dispatchPaymentReceipt($payment);
            return $this->callbackView($payment, true, 'پرداخت با موفقیت انجام شد.');
        } catch (\Throwable $exception) {
            Log::error('Zarinpal verification failed', [
                'payment_id' => $payment->id,
                'exception' => $exception,
            ]);
            return $this->callbackView($payment, false, 'خطا در تأیید پرداخت. لطفاً وضعیت را از برنامه بررسی کنید.');
        }
    }

    private function paymentInitiatedResponse(Payment $payment)
    {
        return comman_message_response('لینک اتصال به درگاه پرداخت', 200, true, [
            'payment_id' => $payment->uuid,
            'booking_id' => $payment->booking_id,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'url' => $payment->gateway_url,
            'gateway_url' => $payment->gateway_url,
            'expires_at' => $payment->created_at->copy()->addMinutes(30)->toIso8601String(),
        ]);
    }

    private function callbackView(Payment $payment, $success, $message)
    {
        $code = $payment->reference_id ?: $payment->uuid;
        $appUrl = $success
            ? config('services.zarinpal.mobile_success_url')
            : config('services.zarinpal.mobile_failed_url');

        return view('payment.callback', compact('message', 'code', 'success', 'payment', 'appUrl'));
    }

    private function dispatchPaymentReceipt(Payment $payment)
    {
        try {
            SendPaymentReceiptSms::dispatch($payment->id)->afterCommit();
        } catch (\Throwable $notificationException) {
            Log::error('Payment receipt notification dispatch failed', [
                'payment_id' => $payment->id,
                'exception' => $notificationException,
            ]);
        }
    }

    public function paymentList(Request $request)
    {
        $payment = Payment::myPayment()->with('booking');
        if ($request->has('booking_id') && !empty($request->booking_id)) {
            $payment->where('booking_id', $request->booking_id);
        }
        if ($request->has('payment_type') && !empty($request->payment_type)) {

            if ($request->payment_type == 'cash') {
                $payment->where('payment_type', $request->payment_type);
            }
        }
        $per_page = config('constant.PER_PAGE_LIMIT');
        if ($request->has('per_page') && !empty($request->per_page)) {
            if (is_numeric($request->per_page)) {
                $per_page = $request->per_page;
            }
            if ($request->per_page === 'all') {
                $per_page = $payment->count();
            }
        }

        $payment = $payment->orderBy('id', 'desc')->paginate($per_page);
        $items = PaymentResource::collection($payment);

        $response = [
            'pagination' => [
                'total_items' => $items->total(),
                'per_page' => $items->perPage(),
                'currentPage' => $items->currentPage(),
                'totalPages' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem(),
                'next_page' => $items->nextPageUrl(),
                'previous_page' => $items->previousPageUrl(),
            ],
            'data' => $items,
        ];

        return comman_custom_response($response);
    }

    public function transferPayment(Request $request)
    {
        $admin = AppSetting::first();
        $data = $request->all();
        $auth_user = authSession();
        $user_id = $auth_user->id;

        date_default_timezone_set($admin->time_zone ?? 'UTC');
        $data['datetime'] = date('Y-m-d H:i:s');

        if ($data['action'] == config('constant.PAYMENT_HISTORY_ACTION.HANDYMAN_SEND_PROVIDER')) {
            $data['text'] = __('messages.payment_transfer',
                ['from' => get_user_name($data['sender_id']), 'to' => get_user_name($data['receiver_id']), 'amount' => getPriceFormat((float)$data['total_amount'])]);
        }
        if ($data['action'] == config('constant.PAYMENT_HISTORY_ACTION.PROVIDER_APPROVED_CASH')) {
            $data['text'] = __('messages.cash_approved', ['amount' => getPriceFormat((float)$data['total_amount']), 'name' => get_user_name($data['receiver_id'])]);
        }
        if ($data['action'] == config('constant.PAYMENT_HISTORY_ACTION.PROVIDER_SEND_ADMIN')) {
            $data['text'] = __('messages.payment_transfer', ['from' => get_user_name($data['sender_id']), 'to' => get_user_name(admin_id()),
                'amount' => getPriceFormat((float)$data['total_amount'])]);
        }
        $result = \App\Models\PaymentHistory::create($data);

        if ($data['action'] == 'provider_approved_cash' && $data['status'] == 'approved_by_provider') {
            $get_parent_history = \App\Models\PaymentHistory::where('id', $request->p_id)->first();
            $get_parent_history->status = 'approved_by_provider';
            $get_parent_history->update();

            $get_main_record = \App\Models\PaymentHistory::where('id', $request->parent_id)->first();
            $get_main_record->status = 'approved_by_provider';
            $get_main_record->update();
        }
        if ($data['action'] == 'provider_send_admin' && $data['status'] == 'pending_by_admin') {
            $get_parent_history = \App\Models\PaymentHistory::where('id', $request->p_id)->first();
            $get_parent_history->status = 'pending_by_admin';
            $get_parent_history->update();
        }
        if ($data['action'] == 'handyman_send_provider' && $data['status'] == 'pending_by_provider') {
            $get_parent_history = \App\Models\PaymentHistory::where('id', $request->p_id)->first();
            $get_parent_history->status = 'send_to_provider';
            $get_parent_history->update();
        }
        $message = trans('messages.transfer');
        if ($request->is('api/*')) {
            return comman_message_response($message);
        }
    }

    public function paymentHistory(Request $request)
    {
        $booking_id = $request->booking_id;
        $payment = PaymentHistory::where('booking_id', $booking_id);

        $per_page = config('constant.PER_PAGE_LIMIT');
        if ($request->has('per_page') && !empty($request->per_page)) {
            if (is_numeric($request->per_page)) {
                $per_page = $request->per_page;
            }
            if ($request->per_page === 'all') {
                $per_page = $payment->count();
            }
        }

        $payment = $payment->orderBy('id', 'desc')->paginate($per_page);
        $items = PaymentHistoryResource::collection($payment);

        $response = [
            'pagination' => [
                'total_items' => $items->total(),
                'per_page' => $items->perPage(),
                'currentPage' => $items->currentPage(),
                'totalPages' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem(),
                'next_page' => $items->nextPageUrl(),
                'previous_page' => $items->previousPageUrl(),
            ],
            'data' => $items,
        ];

        return comman_custom_response($response);

    }

    public function getCashPaymentHistory(Request $request)
    {
        $payment_id = $request->payment_id;
        $payment = PaymentHistory::where('payment_id', $payment_id)->with('booking');

        $per_page = config('constant.PER_PAGE_LIMIT');
        if ($request->has('per_page') && !empty($request->per_page)) {
            if (is_numeric($request->per_page)) {
                $per_page = $request->per_page;
            }
            if ($request->per_page === 'all') {
                $per_page = $payment->count();
            }
        }

        $payment = $payment->orderBy('id', 'desc')->paginate($per_page);
        $items = GetCashPaymentHistoryResource::collection($payment);

        $response = [
            'pagination' => [
                'total_items' => $items->total(),
                'per_page' => $items->perPage(),
                'currentPage' => $items->currentPage(),
                'totalPages' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem(),
                'next_page' => $items->nextPageUrl(),
                'previous_page' => $items->previousPageUrl(),
            ],
            'data' => $items,
        ];

        return comman_custom_response($response);

    }


    public function paymentDetail(Request $request)
    {
        $auth_user = authSession();
        $user_id = $auth_user->id;

        $get_all_payments = PaymentHistory::where('receiver_id', $user_id);
        if (!empty($request->status)) {
            $get_all_payments = $get_all_payments->where('status', $request->status);
        }

        if (!empty($request->from) && !empty($request->to)) {
            $get_all_payments = $get_all_payments->whereDate('datetime', '>=', $request->from)->whereDate('datetime', '<=', $request->to);
        }
        if (auth()->user()->hasAnyRole(['handyman'])) {
            $get_all_payments = $get_all_payments->where('action', 'handyman_approved_cash')->where('receiver_id', $user_id);
        }

        if (auth()->user()->hasAnyRole(['provider'])) {
            $get_all_payments = $get_all_payments->where('action', 'handyman_send_provider')->where('receiver_id', $user_id);
        }


        $per_page = config('constant.PER_PAGE_LIMIT');
        if ($request->has('per_page') && !empty($request->per_page)) {
            if (is_numeric($request->per_page)) {
                $per_page = $request->per_page;
            }
            if ($request->per_page === 'all') {
                $per_page = $get_all_payments->count();
            }
        }

        $get_all_payments = $get_all_payments->orderBy('id', 'desc')->paginate($per_page);


        $items = PaymentHistoryResource::collection($get_all_payments);

        $response = [
            'today_cash' => today_cash_total($user_id, $request->to, $request->from),
            'total_cash' => total_cash($user_id),
            'cash_detail' => $items
        ];

        return comman_custom_response($response);
    }

    public function getCashPayment(Request $request)
    {
        $payment = Payment::where('payment_type', 'cash');

        $per_page = config('constant.PER_PAGE_LIMIT');
        if ($request->has('per_page') && !empty($request->per_page)) {
            if (is_numeric($request->per_page)) {
                $per_page = $request->per_page;
            }
            if ($request->per_page === 'all') {
                $per_page = $payment->count();
            }
        }

        $payment = $payment->orderBy('id', 'desc')->paginate($per_page);
        $items = PaymentResource::collection($payment);

        $response = [
            'pagination' => [
                'total_items' => $items->total(),
                'per_page' => $items->perPage(),
                'currentPage' => $items->currentPage(),
                'totalPages' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem(),
                'next_page' => $items->nextPageUrl(),
                'previous_page' => $items->previousPageUrl(),
            ],
            'data' => $items,
        ];

        return comman_custom_response($response);
    }

}
