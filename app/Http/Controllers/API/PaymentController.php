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

    // I Wrote this route for connect to zarinpal
    public function addPayment(Request $request)
    {
        try {
            $data = $request->all();
            $data['datetime'] = date('Y-m-d H:i:s');
            $booking = Booking::find($request->booking_id);

            $data['customer_id'] = $booking->customer_id;
            $booking->updateBookingPrice();
            $data['total_amount'] = $booking->total_amount;
            $data['discount'] = $booking->discount;


            if ($booking->payment()->where('payment_status', 'paid')->count() > 0) {
                return comman_message_response("این درخواست رزرو قبلا پرداخت شده است", 400, false);
            }

            $payment = Payment::create($data);
            if (!empty($payment) && $payment->payment_status == 'advanced_paid') {
                $booking->advance_paid_amount = $request->advance_payment_amount;
                $booking->status = 'pending';
            }
            $booking->payment_id = $payment->id;
            // $booking->total_amount = $result->total_amount;
            $booking->update();
            $isSuccess = true;
            $customer = User::where('id', $booking->customer_id)->firstOrFail();
            if ($request->payment_type == 'wallet') {
                $wallet = Wallet::where('user_id', $booking->customer_id)->first();
                if ($wallet !== null) {
                    $wallet_amount = $wallet->amount;
                    if ($wallet_amount >= $booking->total_amount) {
                        $wallet->amount = $wallet->amount - $booking->total_amount;
                        $wallet->update();
                        $activity_data = [
                            'activity_type' => 'paid_for_booking',
                            'wallet' => $wallet,
                            'booking_id' => $booking->id,
                            'booking_amount' => $booking->total_amount,
                        ];

                        saveWalletHistory($activity_data);

                        return comman_message_response("پرداخت توسط کیف پول کاربر انجام گرفت");
                    } else {
                        $message = __('messages.wallent_balance_error');
                        return comman_message_response($message, 410, false);
                    }
                }
            } else {
                $activity_data = [
                    'activity_type' => 'payment_message_status',
                    'payment_status' => 'pending',
                    'booking_id' => $booking->id,
                    'booking' => $booking,
                ];
                saveBookingActivity($activity_data);


                $callBackUrl = route('payment.verification', ['payment' => $booking->payment_id]);

                $description = json_encode([
                    'payment_id' => $payment->id,
                    'booking_id' => $booking->id,
                    'customer_id' => $booking->customer_id
                ]);
                $response = zarinpal()
                    ->merchantId(env('ZARINPAL_MERCHANT_ID')) // تعیین مرچنت کد در حین اجرا - اختیاری
                    ->amount($payment->total_amount) // مبلغ تراکنش
                    ->request()
                    ->description($description) // توضیحات تراکنش
                    ->callbackUrl($callBackUrl) // آدرس برگشت پس از پرداخت
                    ->mobile($customer->contact_number) // شماره موبایل مشتری - اختیاری
                    ->email($customer->email ?? '') // ایمیل مشتری - اختیاری
                    ->send();

                if (!$response->success()) {
                    $payment->payment_status = 'failed';
                    $payment->other_transaction_detail = $payment->other_transaction_detail . '\\n failed occurred at: ' . date('Y-m-d H:i:s');
                    $payment->save();
                    return comman_message_response("خطا در اتصال به درگاه پرداخت", 200, false, [
                        'error' => $response->error()->message(),
                    ]);
                } else {
                    $payment->payment_status = 'pending';
                    $payment->other_transaction_detail = $payment->other_transaction_detail . '\\n pending to pay at: ' . date('Y-m-d H:i:s');
                    // ذخیره اطلاعات در دیتابیس
// $response->authority();

// هدایت مشتری به درگاه پرداخت
                    $res = $response->redirect();
                    $targetUrl = $res->getTargetUrl();


                    $payment->save();


                    return comman_message_response("لینک اتصال به درگاه پرداخت", 200, true, [
                        'url' => $targetUrl,
                    ]);
                }
            }

            return comman_message_response('خطا', 400, false);
        } catch (\Exception $exception) {
            return comman_message_response($exception->getMessage(), 400, false);
        }
    }

   public function paymentVerification(Payment $payment)
    {
        try {
            $message = __('messages.payment_completed');

            $authority = request()->query('Authority'); // دریافت کوئری استرینگ ارسال شده توسط زرین پال
            $status = request()->query('Status'); // دریافت کوئری استرینگ ارسال شده توسط زرین پال

            $response = zarinpal()
                ->merchantId(env('ZARINPAL_MERCHANT_ID')) // تعیین مرچنت کد در حین اجرا - اختیاری
                ->amount($payment->total_amount)
                ->verification()
                ->authority($authority)
                ->send();

$booking = $payment->booking()->first();
            if (!$response->success()) {
                $activity_data = [
                    'activity_type' => 'payment_message_status',
                    'payment_status' => 'failed',
                    'booking_id' => $payment->booking_id,
                    'booking' => $payment->booking()->first(),
                ];
                saveBookingActivity($activity_data);
                $message = $response->error()->message();
                $success = false;
                $payment->other_transaction_detail = $payment->other_transaction_detail . "\\n authority: " . $authority . " at: " . date('Y-m-d H:i:s');
                $payment->payment_status = "failed";
            } else {
                $success = true;
                // دریافت هش شماره کارتی که مشتری برای پرداخت استفاده کرده است
// $response->cardHash();

// دریافت شماره کارتی که مشتری برای پرداخت استفاده کرده است (بصورت ماسک شده)
// $response->cardPan();

// پرداخت موفقیت آمیز بود
// دریافت شماره پیگیری تراکنش و انجام امور مربوط به دیتابیس
                $referenceId = $response->referenceId();

                $activity_data = [
                    'activity_type' => 'payment_message_status',
                    'payment_status' => 'paid',
                    'booking_id' => $payment->booking_id,
                    'cardPan' => $response->cardPan(),
                    'cardHash' => $response->cardHash(),
                    'booking' => $payment->booking()->first(),
                    'userType' => 'user'
                ];

                $payment->other_transaction_detail = $payment->other_transaction_detail . "\\n refrencedID: " . $referenceId;
                $payment->payment_status = "paid";
                $booking->status = "paid";
                $booking->save();
                saveBookingActivity($activity_data);

            }

            $payment->save();

            $code = $payment->id;
            return view('payment.callback', compact('message', 'code', 'success'));


        } catch (\Exception $exception) {
            dd($exception);
            abort(403);
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
