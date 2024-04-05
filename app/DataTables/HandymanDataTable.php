<?php

namespace App\DataTables;
use App\Traits\DataTableTrait;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class HandymanDataTable extends DataTable
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
            ->editColumn('display_name', function($handyman){
                return '<a class="btn-link btn-link-hover" href='.route('handyman.create', ['id' => $handyman->id]).'>'.$handyman->display_name.'</a>';
            })
            ->editColumn('status', function($handyman) {
                if($handyman->status == 0){
                    $status = '<a class="btn-sm text-white btn-success"  href='.route('handyman.approve',$handyman->id).'>Accept</a>';
                }else{
                    $status = '<span class="badge badge-active">'.__('messages.active').'</span>';
                }
                return $status;
            })
            ->editColumn('provider_id', function($handyman) {
                if($handyman->provider_id != null && isset($handyman->providers)){
                    return $handyman->providers->display_name;
                }
                else{
                    $provider = User::where('user_type', 'provider')->get();
                    return view('handyman.select_provider', compact('handyman', 'provider'));
                }
                // return ($handyman->provider_id != null && isset($handyman->providers)) ? $handyman->providers->display_name : '-';
            })
            ->editColumn('address', function($handyman) {
                return ($handyman->address != null && isset($handyman->address)) ? $handyman->address : '-';
            })
            ->filterColumn('provider_id',function($query,$keyword){
                $query->whereHas('providers',function ($q) use($keyword){
                    $q->where('display_name','like','%'.$keyword.'%');
                });
            })
            ->addColumn('action', function($handyman){
                return view('handyman.action',compact('handyman'))->render();
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

        $model = $model->where('user_type','handyman');
        if(auth()->user()->hasAnyRole(['admin', 'manager'])){
            $model = $model->withTrashed();
        }
        if(auth()->user()->hasRole('provider')) {
            $model->where('provider_id', auth()->user()->id);
        }
        if($this->list_status == null){
            $model = $model->where('status',1)->whereNotNull('provider_id');
        }
        if($this->list_status == 'unassigned'){
            $model = $model->where('status',0)->orWhere('provider_id',NULL)->where('user_type','handyman');
        }
        return $model->list();

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
            Column::make('display_name')
                ->title(__('messages.name')),
            Column::make('provider_id')
                ->title(__('messages.provider'))
                ->width(215),
            Column::make('contact_number')
                ->title(__('messages.contact_number')),
            Column::make('address')
                ->title(__('messages.address')),
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
