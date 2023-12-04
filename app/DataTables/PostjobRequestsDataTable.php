<?php

namespace App\DataTables;
use App\Traits\DataTableTrait;
use App\Models\PostJobRequest;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;


class PostjobRequestsDataTable extends DataTable
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
            ->editColumn('title', function($post_job){
                return '<a class="btn-link btn-link-hover"  href='.route('postjobrequest.service',$post_job->id).'>'.$post_job->title.'</a>';
            })
            ->editColumn('provider_id' , function ($post_job){
                return ($post_job->provider_id != null && isset($post_job->provider)) ? $post_job->provider->display_name : '-';
            })
            ->editColumn('customer_id' , function ($post_job){
                return ($post_job->customer_id != null && isset($post_job->customer)) ? $post_job->customer->display_name : '-';
            })
            ->filterColumn('customer_id',function($query,$keyword){
                $query->whereHas('customer',function ($q) use($keyword){
                    $q->where('display_name','like','%'.$keyword.'%');
                });
            })
            ->editColumn('price' , function ($post_job){
                return getPriceFormat($post_job->price);
            })
            ->editColumn('status' , function ($post_job){
                $disabled = $post_job->deleted_at ? 'disabled': '';
                return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                    <div class="custom-switch-inner">
                        <input type="checkbox" class="custom-control-input  change_status" '.$disabled.' data-type="service_status" '.($post_job->status ? "checked" : "").' value="'.$post_job->id.'" id="'.$post_job->id.'" data-id="'.$post_job->id.'" >
                        <label class="custom-control-label" for="'.$post_job->id.'" data-on-label="" data-off-label=""></label>
                    </div>
                </div>';
            })
            ->addColumn('action', function($post_job){
                return view('postrequest.action',compact('post_job'))->render();
            })
            ->addIndexColumn()
            ->rawColumns(['title','action','status']);
            //->addColumn('action', 'posjobrequests.action');
    }

    // /**
    //  * Get query source of dataTable.
    //  *
    //  * @param \App\Models\posjobrequest $model
    //  * @return \Illuminate\Database\Eloquent\Builder
    //  */
    public function query(PostJobRequest $model)

    {
        $model = $model->myPostJob();
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
    //                 ->setTableId('posjobrequests-table')
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
            Column::make('title'),
            Column::make('provider_id')
                ->title(__('messages.provider')),
                Column::make('customer_id')
                ->title(__('messages.customer')),
            Column::make('status')
                ->title(__('messages.status')),
            Column::make('price')
                ->title(__('messages.price')),
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
        return 'posjobrequests_' . date('YmdHis');
    }
}

