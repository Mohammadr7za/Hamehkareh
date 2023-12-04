<?php

namespace App\DataTables;
use App\Traits\DataTableTrait;

use App\Models\Coupon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CouponDataTable extends DataTable
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
            ->editColumn('code', function($coupon){
                return '<a class="btn-link btn-link-hover" href='.route('coupon.create', ['id' => $coupon->id]).'>'.$coupon->code.'</a>';
            })
            ->editColumn('status' , function ($coupon){
                $disabled = $coupon->trashed() ? 'disabled': '';
                return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                    <div class="custom-switch-inner">
                        <input type="checkbox" class="custom-control-input bg-primary change_status" '.$disabled.' data-type="coupon_status" '.($coupon->status ? "checked" : "").' value="'.$coupon->id.'" id="'.$coupon->id.'" data-id="'.$coupon->id.'">
                        <label class="custom-control-label" for="'.$coupon->id.'" data-on-label="" data-off-label=""></label>
                    </div>
                </div>';
            })
            ->editColumn('discount' , function ($coupon){
                $discount = getPriceFormat($coupon->discount);
                if($coupon->discount_type == 'percentage'){
                    $discount = $coupon->discount .'%';
                }
                return $discount;
            })
            ->addColumn('action', function($coupon){
                return view('coupon.action',compact('coupon'))->render();
            })
            ->addIndexColumn()
            ->rawColumns(['code','action','status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Coupon $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Coupon $model)
    {
        if(auth()->user()->hasAnyRole(['admin']))
        {
            $model = $model->withTrashed();
        }
        
        return $model->list()->newQuery();
    }


    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('DT_RowIndex')
                  ->searchable(false)
                  ->title(__('messages.srno'))
                  ->orderable(false)
                  ->width(60),
            Column::make('code')
                ->title(__('messages.code')),
            Column::make('discount')
                ->title(__('messages.discount')),
            Column::make('discount_type')
                ->title(__('messages.discount_type')),
            Column::make('expire_date')
                ->title(__('messages.expire_date')),
            Column::make('status')
                ->title(__('messages.status')),           
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
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
        return 'Coupan_' . date('YmdHis');
    }
}
