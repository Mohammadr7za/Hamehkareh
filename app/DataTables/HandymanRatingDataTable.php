<?php

namespace App\DataTables;

use App\Models\HandymanRating;
use App\Traits\DataTableTrait;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class HandymanRatingDataTable extends DataTable
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
            ->editColumn('handyman_id', function($handymanrating){
                return ($handymanrating != null && isset($handymanrating->handyman)) ? $handymanrating->handyman->first_name : '-';
            })
            ->editColumn('customer_id', function($handymanrating){
                return ($handymanrating != null && isset($handymanrating->customer)) ? $handymanrating->customer->first_name : '-';
            })
            ->editColumn('review', function($handymanrating){
                return ($handymanrating != null && isset($handymanrating->review)) ? $handymanrating->review : '-';
            })
            ->addColumn('action', function($handymanrating){
                return view('handymanrating.action', compact('handymanrating'))->render();
            })
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\HandymanRating $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(HandymanRating $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    // public function html()
    // {
    //     return $this->builder()
    //                 ->setTableId('handymanrating-table')
    //                 ->columns($this->getColumns())
    //                 ->minifiedAjax()
    //                 ->dom('Bfrtip')
    //                 ->orderBy(1)
    //                 ->buttons(
    //                     Button::make('create'),
    //                     Button::make('export'),
    //                     Button::make('print'),
    //                     Button::make('reset'),
    //                     Button::make('reload')
    //                 );
    // }

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
                    ->title(__('messages.no'))
                    ->orderable(false),
            Column::make('handyman_id')
                    ->title(__('messages.handyman')),
            Column::make('customer_id')
                    ->title(__('messages.customer')),
            Column::make('rating')
                    ->title(__('messages.rating')),
            Column::make('review')
                    ->title(__('messages.review')),
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
        return 'HandymanRating_' . date('YmdHis');
    }
}
