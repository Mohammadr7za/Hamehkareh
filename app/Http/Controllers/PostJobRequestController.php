<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostJobRequest;
use App\DataTables\PostjobRequestsDataTable;
use App\DataTables\PostJobBidDataTable;
use Yajra\DataTables\DataTables;

class PostJobRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PostjobRequestsDataTable $dataTable,Request $request)
    {
        $filter = [
            'status' => $request->status,
        ];
        $pageTitle = trans('messages.list_form_title',['form' => trans('messages.postjob')] );
        $auth_user = authSession();
        $assets = ['datatable'];

        return $dataTable->render('postrequest.index', compact('pageTitle','auth_user','assets','filter'));

    }



    public function index_data(DataTables $datatable,Request $request)
    {
        $query = PostJobRequest::query();
        $filter = $request->filter;

        if (isset($filter)) {
            if (isset($filter['column_status'])) {
                $query->where('status', $filter['column_status']);
            }
        }
        if (auth()->user()->hasAnyRole(['admin'])) {
            $query->newQuery();
        }
        
        return $datatable->eloquent($query)
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" onclick="dataTableRowCheck('.$row->id.')">';
            })
            ->editColumn('title', function($query){
                return '<a class="btn-link btn-link-hover"  href='.route('postjobrequest.service',$query->id).'>'.$query->title.'</a>';
            })
            ->editColumn('provider_id' , function ($query){
                return ($query->provider_id != null && isset($query->provider)) ? $query->provider->display_name : '-';
            })
            ->editColumn('customer_id' , function ($query){
                return ($query->customer_id != null && isset($query->customer)) ? $query->customer->display_name : '-';
            })
            ->filterColumn('customer_id',function($query,$keyword){
                $query->whereHas('customer',function ($q) use($keyword){
                    $q->where('display_name','like','%'.$keyword.'%');
                });
            })
            ->editColumn('price' , function ($query){
                return getPriceFormat($query->price);
            })
            ->editColumn('status' , function ($query){
                return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                    <div class="custom-switch-inner">
                        <input type="checkbox" class="custom-control-input  change_status" data-type="service_status" '.($query->status ? "checked" : "").' value="'.$query->id.'" id="'.$query->id.'" data-id="'.$query->id.'" >
                        <label class="custom-control-label" for="'.$query->id.'" data-on-label="" data-off-label=""></label>
                    </div>
                </div>';
            })
            ->addColumn('action', function($post_job){
                return view('postrequest.action',compact('post_job'))->render();
            })
            ->addIndexColumn()
            ->rawColumns(['title','action','status','check'])
            ->toJson();
    }

    /* bulck action method */
    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);

        $actionType = $request->action_type;

        $message = 'Bulk Action Updated';
        switch ($actionType) {
            case 'change-status':
                $branches = PostJobRequest::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = 'Bulk PostJobRequest Status Updated';
                break;

            case 'delete':
                PostJobRequest::whereIn('id', $ids)->delete();
                $message = 'Bulk PostJobRequest Deleted';
                break;

            default:
                return response()->json(['status' => false, 'message' => 'Action Invalid']);
                break;
        }

        return response()->json(['status' => true, 'message' => 'Bulk Action Updated']);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['customer_id'] =  !empty($request->customer_id) ? $request->customer_id : auth()->user()->id; 

        $result = PostJobRequest::updateOrCreate(['id' => $request->id], $data);

        $activity_data = [
            'activity_type' => 'job_requested',
            'post_job_id' => $result->id,
            'post_job' => $result,
        ];

        saveRequestJobActivity($activity_data);
         if($result->postServiceMapping()->count() > 0)
        {
            $result->postServiceMapping()->delete();
        }

        if($request->service_id != null) {
            foreach($request->service_id as $service) {
                $post_services = [
                    'post_request_id'   => $result->id,
                    'service_id'   => $service,
                ];
                $result->postServiceMapping()->insert($post_services);
            }
        }
        if($request->status == 'accept'){
            $activity_data = [
                'activity_type' => 'user_accept_bid',
                'post_job_id' => $result->id,
                'post_job' => $result,
            ];
    
            saveRequestJobActivity($activity_data);
        }
        $message = __('messages.update_form',[ 'form' => __('messages.postrequest') ] );
		if($result->wasRecentlyCreated){
			$message = __('messages.save_form',[ 'form' => __('messages.postrequest') ] );
		}

        if($request->is('api/*')) {
            return comman_message_response($message);
		}

		return redirect(route('service.index'))->withSuccess($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, PostJobBidDataTable $dataTable)
    {
        $pageTitle = trans('messages.list_form_title',['form' => trans('messages.postbid')] );
        $auth_user = authSession();
        $asset = ['datatable'];
        return $dataTable->with('id', $id)->render('postrequest.view', compact('pageTitle', 'auth_user', 'asset'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(demoUserPermission()){
            if(request()->is('api/*')){
                return comman_message_response( __('messages.demo_permission_denied') );
            }
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $post_request = PostJobRequest::find($id);
        //$post_request->delete();
        $msg= __('messages.msg_fail_to_delete',['item' => __('messages.postrequest')] );
        
        if($post_request!='') {
            if($post_request->postServiceMapping()->count() > 0)
            {
                $post_request->postServiceMapping()->delete();
            }
            if($post_request->postBidList()->count() > 0)
            {
                $post_request->postBidList()->delete();
            }
            $post_request->delete();
            $msg= __('messages.msg_deleted',['name' => __('messages.postrequest')] );
        }
        if(request()->is('api/*')){
            return comman_custom_response(['message'=> $msg , 'status' => true]);
        }
        return redirect()->back()->withSuccess($msg);
    
    }
}
