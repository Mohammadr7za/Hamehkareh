<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PaymentHistory;
use App\Models\Payment;
use App\Models\AppSetting;
use App\Jobs\SendPaymentReceiptSms;
use App\Services\ZarinpalPaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorizePaymentMonitoring();
        $pageTitle = __('messages.list_form_title', ['form' => __('messages.payment')]);
        $assets = ['datatable'];
        return view('payment.index', compact('pageTitle', 'assets'));
    }

    public function cashIndex($id)
    {
        $pageTitle = __('messages.list_form_title', ['form' => __('messages.cash_history')]);
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('paymenthistory.index', compact('pageTitle', 'assets', 'auth_user', 'id'));
    }

    public function paymenthistory_index_data(DataTables $datatable, $id)
    {
        $query = PaymentHistory::where('payment_id', $id);

        if (auth()->user()->hasAnyRole(['admin'])) {
            $query->newquery();
        }

        return $datatable->eloquent($query)
            ->editColumn('booking_id', function ($payment) {
                return ($payment->booking_id != null && isset($payment->booking->service)) ? $payment->booking->id : '-';
            })
            ->filterColumn('booking_id', function ($query, $keyword) {
                $query->whereHas('booking.service', function ($q) use ($keyword) {
                    $q->where('id', 'like', '%' . $keyword . '%');
                });
            })
            ->editColumn('booking_name', function ($payment) {
                return ($payment->booking_id != null && isset($payment->booking->service)) ? $payment->booking->service->name : '-';
            })
            ->filterColumn('booking_name', function ($query, $keyword) {
                $query->whereHas('booking.service', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
            })
            ->editColumn('customer_id', function ($payment) {
                return ($payment->booking != null && isset($payment->booking->customer)) ? $payment->booking->customer->display_name : '-';
            })
            ->filterColumn('customer_id', function ($query, $keyword) {
                $query->whereHas('booking', function ($q) use ($keyword) {
                    $q->whereHas('customer', function ($c) use ($keyword) {
                        $c->where('display_name', 'like', '%' . $keyword . '%');
                    });
                });
            })
            ->addIndexColumn()
            ->toJson();
    }

    public function cashDatatable(Request $request)
    {
        $filter = [
            'status' => $request->status,
        ];
        $pageTitle = __('messages.list_form_title', ['form' => __('messages.cash_payment')]);
        $assets = ['datatable'];
        return view('payment.cash', compact('pageTitle', 'assets', 'filter'));
    }

    public function cash_index_data(DataTables $datatable, Request $request)
    {
        $query = Payment::query();
        $filter = $request->filter;

        if (isset($filter)) {
            if (isset($filter['column_status'])) {
                $query->where('status', $filter['column_status']);
            }
        }
        if (auth()->user()->hasAnyRole(['admin'])) {
            $query = $query->orderBy('id', 'desc')->where('payment_type', 'cash')->newQuery()->myPayment();
        }

        return $datatable->eloquent($query)
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-' . $row->id . '"  name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">';
            })
            ->editColumn('id', function ($payment) {
                if (isset($payment->booking) && $payment->booking !== null) {
                    return '<a class="btn-link btn-link-hover" href=' . route('booking.show', $payment->booking->id) . '> #' . $payment->booking->id . '</a>';
                }
            })->editColumn('datetime', function ($payment) {
                return jdate($payment->created_at);
            })
            ->editColumn('booking_id', function ($payment) {
                if ($payment->customer_id != null && isset($payment->booking->service)) {
                    return $payment->booking->service->name;
                } else {
                    return '-';
                }
            })
            ->filterColumn('booking_id', function ($query, $keyword) {
                $query->whereHas('booking.service', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
            })
            ->editColumn('customer_id', function ($payment) {
                return view('payment.user', compact('payment'));
            })
            ->filterColumn('customer_id', function ($query, $keyword) {
                $query->whereHas('customer', function ($q) use ($keyword) {
                    $q->where('display_name', 'like', '%' . $keyword . '%');
                });
            })
            ->editColumn('total_amount', function ($payment) {
                return getPriceFormat($payment->total_amount);
            })
            ->editColumn('history', function ($payment) {
                $action = '<a class="btn-link btn-link-hover"   href=' . route('cash.index', $payment->id) . '>View</a>';
                return $action;
            })
            ->editColumn('status', function ($payment) {
                return last_status($payment->id);
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->whereHas('paymentHistory', function ($q) use ($keyword) {
                    $q->where('status', 'like', '%' . $keyword . '%');
                });
            })
            // ->addColumn('action', function($payment){
            //     return view('payment.cashaction',compact('payment'))->render();
            // })
            ->editColumn('action', function ($payment) {
                $action = set_admin_approved_cash($payment->id) . ' ' . view('payment.cashaction', compact('payment'))->render();
                return $action;

            })
            ->addIndexColumn()->rawColumns(['check', 'history', 'action', 'id', 'status'])
            ->toJson();
    }


    public function index_data(DataTables $datatable, Request $request)
    {
        $this->authorizePaymentMonitoring();
        $query = Payment::query()->with([
            'booking.service', 'booking.provider', 'customer', 'provider', 'handyman',
            'booking.handymanAdded.handyman',
        ])->orderByDesc('created_at');

        $filters = (array) $request->input('filter', []);
        if (!empty($filters['status'])) {
            $query->where('payment_status', $filters['status']);
        }
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        foreach (['customer', 'provider', 'handyman'] as $relation) {
            if (!empty($filters[$relation])) {
                $term = $filters[$relation];
                $userFilter = function ($userQuery) use ($term) {
                    $userQuery->where(function ($nested) use ($term) {
                        $nested->where('display_name', 'like', '%' . $term . '%')
                            ->orWhere('email', 'like', '%' . $term . '%')
                            ->orWhere('contact_number', 'like', '%' . $term . '%');
                    });
                };
                $query->where(function ($nested) use ($relation, $userFilter) {
                    $nested->whereHas($relation, $userFilter);
                    if ($relation === 'provider') {
                        $nested->orWhereHas('booking.provider', $userFilter);
                    } elseif ($relation === 'handyman') {
                        $nested->orWhereHas('booking.handymanAdded.handyman', $userFilter);
                    }
                });
            }
        }

        $search = trim((string) $request->input('query'));
        if ($search !== '') {
            $query->where(function ($nested) use ($search) {
                $nested->where('id', $search)
                    ->orWhere('uuid', 'like', '%' . $search . '%')
                    ->orWhere('booking_id', 'like', '%' . $search . '%')
                    ->orWhere('authority', 'like', '%' . $search . '%')
                    ->orWhere('reference_id', 'like', '%' . $search . '%')
                    ->orWhereHas('customer', function ($user) use ($search) {
                        $user->where('display_name', 'like', '%' . $search . '%')
                            ->orWhere('contact_number', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('provider', function ($user) use ($search) {
                        $user->where('display_name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('handyman', function ($user) use ($search) {
                        $user->where('display_name', 'like', '%' . $search . '%');
                    });
            });
        }

        return $datatable->eloquent($query)
            ->editColumn('id', function ($payment) {
                return '<a class="btn-link btn-link-hover" href="' . route('payment.show', $payment) . '">#' . $payment->id . '</a>';
            })
            ->editColumn('booking_id', function ($payment) {
                return '<a class="btn-link btn-link-hover" href="' . route('booking.show', $payment->booking_id) . '">#' . $payment->booking_id . '</a>';
            })
            ->editColumn('customer_id', function ($payment) {
                return optional($payment->customer)->display_name ?: '-';
            })
            ->addColumn('provider_name', function ($payment) {
                return optional($payment->provider)->display_name
                    ?: optional(optional($payment->booking)->provider)->display_name
                    ?: '-';
            })
            ->addColumn('handyman_name', function ($payment) {
                if ($payment->handyman) {
                    return $payment->handyman->display_name;
                }
                return optional($payment->booking)->handymanAdded
                    ? $payment->booking->handymanAdded->pluck('handyman.display_name')->filter()->implode('، ')
                    : '-';
            })
            ->editColumn('payment_status', function ($query) {
                $classes = ['paid' => 'success', 'pending' => 'warning', 'initiated' => 'info', 'failed' => 'danger', 'cancelled' => 'secondary'];
                $class = $classes[$query->payment_status] ?? 'secondary';
                return '<span class="badge badge-' . $class . '">' . e($query->payment_status) . '</span>';
            })
            ->editColumn('total_amount', function ($query) {
                return number_format($query->amount ?: $query->total_amount) . ' ' . ($query->currency === 'IRR' ? 'ریال' : 'تومان');
            })
            ->editColumn('datetime', function ($payment) {
                return $payment->created_at ? jdate($payment->created_at) : '-';
            })
            ->addIndexColumn()
            ->rawColumns(['id', 'booking_id', 'payment_status'])
            ->toJson();
    }

    /* bulck action method */
    public function bulk_action(Request $request)
    {
        abort(405, 'رکوردهای مالی قابل حذف یا ویرایش گروهی نیستند.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        $this->authorizePaymentMonitoring();
        $payment->load([
            'booking.service', 'booking.provider', 'booking.handymanAdded.handyman',
            'customer', 'provider', 'handyman',
        ]);
        $pageTitle = 'جزئیات پرداخت #' . $payment->id;
        return view('payment.show', compact('payment', 'pageTitle'));
    }

    public function recheck(Payment $payment, ZarinpalPaymentService $gateway)
    {
        $this->authorizePaymentMonitoring();
        if ($payment->payment_status === 'paid') {
            try {
                SendPaymentReceiptSms::dispatch($payment->id)->afterCommit();
            } catch (\Throwable $notificationException) {
                Log::error('Admin payment receipt retry failed', ['payment_id' => $payment->id, 'exception' => $notificationException]);
            }
            return redirect()->back()->withSuccess('این پرداخت قبلاً تأیید شده است.');
        }
        if (!$payment->authority) {
            return redirect()->back()->withErrors('این پرداخت Authority معتبر برای استعلام ندارد.');
        }

        try {
            $response = $gateway->verify($payment, $payment->authority);
            if (!$response['success']) {
                $payment->forceFill([
                    'failure_code' => (string) $response['code'],
                    'failure_message' => $response['message'],
                    'gateway_response' => $response['raw'],
                ])->save();
                return redirect()->back()->withErrors('زرین‌پال این پرداخت را تأیید نکرد: ' . $response['message']);
            }

            $payment = DB::transaction(function () use ($payment, $response) {
                $locked = Payment::whereKey($payment->id)->lockForUpdate()->firstOrFail();
                if ($locked->payment_status !== 'paid') {
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
                }
                return $locked;
            });

            try {
                SendPaymentReceiptSms::dispatch($payment->id)->afterCommit();
            } catch (\Throwable $notificationException) {
                Log::error('Admin payment recheck notification failed', [
                    'payment_id' => $payment->id,
                    'exception' => $notificationException,
                ]);
            }
            return redirect()->back()->withSuccess('پرداخت با موفقیت از زرین‌پال تأیید شد.');
        } catch (\Throwable $exception) {
            Log::error('Admin Zarinpal recheck failed', ['payment_id' => $payment->id, 'exception' => $exception]);
            return redirect()->back()->withErrors('ارتباط با زرین‌پال برای استعلام پرداخت ناموفق بود.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort(405, 'رکوردهای مالی قابل حذف نیستند.');
    }

    private function authorizePaymentMonitoring()
    {
        abort_unless(auth()->user() && auth()->user()->hasAnyRole(['admin', 'manager']), 403);
    }

    public function cashApprove($id)
    {
        $admin = AppSetting::first();

        $paymentdata = Payment::where('id', $id)->first();
        $parent_payment_history = PaymentHistory::where('status', 'pending_by_admin')
            ->where('payment_id', $id)->first();


        $payment_history = [
            'payment_id' => $id,
            'booking_id' => $paymentdata->booking_id,
            'action' => config('constant.PAYMENT_HISTORY_ACTION.ADMIN_APPROVED_CASH'),
            'type' => $parent_payment_history->payment_type,
            'sender_id' => $parent_payment_history->sender_id,
            'receiver_id' => admin_id(),
            'total_amount' => $paymentdata->total_amount,
            'text' => __('messages.cash_approved', ['amount' => getPriceFormat((float)$paymentdata->total_amount), 'name' => get_user_name(admin_id())]),
            'status' => config('constant.PAYMENT_HISTORY_STATUS.APPROVED_ADMIN'),
            'parent_id' => $parent_payment_history->parent_id
        ];

        date_default_timezone_set($admin->time_zone ?? 'UTC');
        $payment_history['datetime'] = date('Y-m-d H:i:s');

        if (!empty($paymentdata->txn_id)) {
            $payment_history['txn_id'] = $paymentdata->txn_id;
        }
        if (!empty($paymentdata->other_transaction_detail)) {
            $payment_history['other_transaction_detail'] = $paymentdata->other_transaction_detail;
        }
        $res = PaymentHistory::create($payment_history);

        $parent_record = PaymentHistory::where('parent_id', $parent_payment_history->parent_id)->first();

        $parent_payment_history->status = 'approved_by_admin';
        $parent_payment_history->update();

        $parent_record->status = 'approved_by_admin';
        $parent_record->update();


        $paymentdata->payment_status = 'paid';
        $paymentdata->update();

        $msg = __('messages.approve_successfully');
        return redirect()->back()->withSuccess($msg);
    }

}
