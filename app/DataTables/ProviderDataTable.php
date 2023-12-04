<?php

namespace App\DataTables;
use App\Traits\DataTableTrait;
use Carbon\Carbon;
use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProviderDataTable extends DataTable
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
            ->editColumn('display_name', function($provider){
                return '<a class="btn-link btn-link-hover" href='.route('provider.show', $provider->id).'>'.$provider->display_name.'</a>';
            })
            ->editColumn('status', function($provider) {
                if($provider->status == '0'){
                    // $status = '<span class="badge badge-inactive">'.__('messages.inactive').'</span>';
                    $status = '<a class="btn-sm text-white btn-success"  href='.route('provider.approve',$provider->id).'><i class="fa fa-check"></i>Approve</a>';
                }else{
                    $status = '<span class="badge badge-active">'.__('messages.active').'</span>';
                }
                return $status;
            })
            ->editColumn('providertype_id', function($provider) {
                return ($provider->providertype_id != null && isset($provider->providertype)) ? $provider->providertype->name : '-';
            })
            ->editColumn('address', function($provider) {
                return ($provider->address != null && isset($provider->address)) ? $provider->address : '-';
            })
            ->editColumn('created_at', function($provider) {
                $carbonDate = Carbon::parse($provider->created_at);

                // Format the Carbon instance to display only the date part
                $formattedDate = $carbonDate->toDateString();

                return $formattedDate;
            })
               
            ->filterColumn('providertype_id',function($query,$keyword){
                $query->whereHas('providertype',function ($q) use($keyword){
                    $q->where('name','like','%'.$keyword.'%');
                });
            })
            ->addColumn('action', function($provider){
                return view('provider.action',compact('provider'))->render();
            })
            ->addIndexColumn()
            ->rawColumns(['display_name','action','status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        $model = $model->where('user_type','provider');
        if(auth()->user()->hasAnyRole(['admin'])){
            $model = $model->withTrashed();
        }
        if($this->list_status == 'pending'){
            $model = $model->where('status',0);
        }else{
            $model = $model->where('status',1);
        }
        if($this->list_status == 'subscribe'){
            $model = $model->where('status',1)->where('is_subscribe',1);
        } 
        $model = $model->orderBy('created_at', 'desc');
        return $model->list();
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
            Column::make('DT_RowIndex')
                ->searchable(false)
                ->title(__('messages.srno'))
                ->orderable(false),
            Column::make('display_name')
                ->title(__('messages.name'))
                ->addClass('p-name-width'),
            Column::make('email')
                ->title(__('messages.email')),
            Column::make('created_at')
                ->title(__('messages.joining_date')),
            Column::make('providertype_id')
                ->title(__('messages.providertype')),
            Column::make('contact_number')
                ->title(__('messages.contact_number')),
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
        return 'Provider_' . date('YmdHis');
    }
}

  