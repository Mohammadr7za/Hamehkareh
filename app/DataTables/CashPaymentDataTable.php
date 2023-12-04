<?php

namespace App\DataTables;
use App\Traits\DataTableTrait;

use App\Models\Payment;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CashPaymentDataTable extends DataTable
{
    use DataTableTrait;
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('id', function($payment) {
                if(isset($payment->booking) && $payment->booking !== null){
                    return '<a class="btn-link btn-link-hover" href='.route('booking.show', $payment->booking->id).'> #'.$payment->booking->id.'</a>';
                }
            })
            ->editColumn('booking_id', function($payment) {
                if($payment->customer_id != null && isset($payment->booking->service)){
                    return $payment->booking->service->name;
                }else{
                    return '-';
                }
            })
            ->filterColumn('booking_id',function($query,$keyword){
                $query->whereHas('booking.service',function ($q) use($keyword){
                    $q->where('name','like','%'.$keyword.'%');
                });
            })            
            ->editColumn('customer_id', function($payment) {
                return ($payment->customer_id != null && isset($payment->customer)) ? $payment->customer->display_name : '';
            })
            ->filterColumn('customer_id',function($query,$keyword){
                $query->whereHas('customer',function ($q) use($keyword){
                    $q->where('display_name','like','%'.$keyword.'%');
                });
            })
            ->editColumn('total_amount', function($payment) {
                return getPriceFormat($payment->total_amount);
            })
            ->editColumn('history', function($payment) {
                $action = '<a class=""  href='.route('cash.index',$payment->id).'>View</a>';
                return $action;
            })
            ->editColumn('status', function($payment) {
                return last_status($payment->id);
            })
            ->filterColumn('status',function($query,$keyword){
                $query->whereHas('paymentHistory',function ($q) use($keyword){
                    $q->where('status','like','%'.$keyword.'%');
                });
            })
            // ->addColumn('action', function($payment){
            //     return view('payment.action',compact('payment'))->render();
            // })
            ->editColumn('action', function($payment) {
               
                return set_admin_approved_cash($payment->id);
            })
            ->addIndexColumn()->rawColumns(['history','action','id','status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Payment $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Payment $model)
    {
        return $model->orderBy('id','desc')->where('payment_type','cash')->newQuery()->myPayment();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id')->searchable(false)->orderable(false)
                ->title(__('messages.booking_id')),
            Column::make('booking_id')
                ->title(__('messages.service')),
            Column::make('customer_id')
                ->title(__('messages.user')),
            Column::make('datetime')->title(__('messages.date_time')),
            Column::make('history')->searchable(false)->orderable(false)
                ->title(__('messages.history')),
            Column::make('status')->orderable(false)
                ->title(__('messages.status')),
            Column::make('total_amount')
                ->title(__('messages.booking_id')),         
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(90)
                  ->addClass('text-center')
                  ->title(__('messages.action')),  
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Payment_' . date('YmdHis');
    }
}
