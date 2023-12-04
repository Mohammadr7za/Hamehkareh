<?php

namespace App\DataTables;

use App\Models\PostJobBid;
use App\Traits\DataTableTrait;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PostJobBidDataTable extends DataTable
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
            ->editColumn('post_request_id' , function ($post_job_bid){
                return ($post_job_bid->post_request_id != null && isset($post_job_bid->postrequest)) ? $post_job_bid->postrequest->title : '-';
            })
            ->editColumn('provider_id' , function ($post_job_bid){
                return ($post_job_bid->provider_id != null && isset($post_job_bid->provider)) ? $post_job_bid->provider->display_name : '-';
            })
            ->editColumn('customer_id', function ($post_job_bid){
                return ($post_job_bid->customer_id != null && isset($post_job_bid->customer)) ? $post_job_bid->customer->display_name : '-';
            })
            ->editColumn('price' , function ($post_job){
                return getPriceFormat($post_job->price);
            })
            ->editColumn('duration' , function ($post_job_bid){
                return ($post_job_bid->duration != null) ? $post_job_bid->duration : '-';
            })
            ->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\PostJobBid $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PostJobBid $model)
    {
        return $model->where('post_request_id', $this->id)->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    // public function html()
    // {
    //     return $this->builder()
    //                 ->setTableId('postjobbid-table')
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
            Column::make('post_request_id')
                    ->title(__('messages.postjob')),
            Column::make('provider_id')
                    ->title(__('messages.provider')),
            Column::make('customer_id')
                    ->title(__('messages.customer')),
            Column::make('price'),
            Column::make('duration')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'PostJobBid_' . date('YmdHis');
    }
}
