<?php

namespace App\DataTables;

use App\Models\BookingRating;
use App\Traits\DataTableTrait;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BookingRatingDataTable extends DataTable
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
            ->editColumn('customer_id', function($bookingrating){
                return ($bookingrating != null && isset($bookingrating->customer)) ? $bookingrating->customer->first_name : '-';
            })
            ->editColumn('service_id', function($bookingrating){
                return ($bookingrating != null && isset($bookingrating->service)) ? $bookingrating->service->name : '-';
            })
            ->editColumn('review', function($bookingrating){
                return ($bookingrating != null && isset($bookingrating->review)) ? $bookingrating->review : '-';
            })
            ->addColumn('action', function($bookingrating){
                return view('bookingrating.action', compact('bookingrating'))->render();
            })
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\BookingRating $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(BookingRating $model)
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
    //                 ->setTableId('bookingrating-table')
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
            Column::make('customer_id')
                    ->title(__('messages.customer')),
            Column::make('service_id')
                    ->title(__('messages.service')),
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
        return 'BookingRating_' . date('YmdHis');
    }
}
