<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ContactUsController extends Controller
{

    public function __construct()
    {
        // Middleware only applied to these methods
        $this->middleware('throttle:30', [
            'only' => [
                'store' // Could add bunch of more methods too
            ]
        ]);

        $this->middleware('auth', [
            'except' => [
                'store' // Could add bunch of more methods too
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = [
            'status' => $request->status,
        ];
        $pageTitle = trans('messages.list_form_title', ['form' => trans('messages.contactus')]);
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('contactus.index', compact('pageTitle', 'auth_user', 'assets', 'filter'));
    }


    public function index_data(DataTables $datatable, Request $request)
    {
        $query = ContactUs::query()->orderByDesc('created_at');;
        $filter = $request->filter;

        if (isset($filter)) {
            if (isset($filter['column_status'])) {
                $query->where('status', $filter['column_status']);
            }
        }
        if (auth()->user()->hasAnyRole(['admin', 'manager'])) {
            $query->newQuery();
        }

        return $datatable->eloquent($query)
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-' . $row->id . '"  name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">';
            })
            ->editColumn('title', function ($query) {
                if (auth()->user()->can('plan edit')) {
                    $link = '<a class="btn-link btn-link-hover" href=' . route('plans.create', ['id' => $query->id]) . '>' . $query->title . '</a>';
                } else {
                    $link = $query->title;
                }
                return $link;
            })
            ->editColumn('status', function ($query) {
                return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                    <div class="custom-switch-inner">
                        <input type="checkbox" class="custom-control-input  change_status" data-type="plan_status" ' . ($query->status ? "checked" : "") . '   value="' . $query->id . '" id="' . $query->id . '" data-id="' . $query->id . '">
                        <label class="custom-control-label" for="' . $query->id . '" data-on-label="" data-off-label=""></label>
                    </div>
                </div>';
            })
            ->editColumn('amount', function ($query) {
                $price = !empty($query->amount) ? getPriceFormat($query->amount) : '-';
                return $price;
            })
            ->addColumn('action', function ($plan) {
                return view('contactus.action', compact('plan'))->render();
            })
            ->addIndexColumn()
            ->rawColumns(['title', 'action', 'status', 'check'])
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
                $branches = ContactUs::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = 'Bulk Plans Status Updated';
                break;

            case 'delete':
                ContactUs::whereIn('id', $ids)->delete();
                $message = 'Bulk Plans Deleted';
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
    public function create(Request $request)
    {
//        $id = $request->id;
//        $auth_user = authSession();
//
//        $plan = ContactUs::with('planlimit')->find($id);
//        $plan_type = StaticData::where('type','plan_type')->get();
//        $plan_limit = StaticData::where('type','plan_limit_type')->get();
//        $pageTitle = trans('messages.update_form_title',['form'=>trans('messages.plan')]);
//
//        if($plan == null){
//            $pageTitle = trans('messages.add_button_form',['form' => trans('messages.plan')]);
//            $plan = new Plans;
//        }
//
//        return view('plan.create', compact('pageTitle' ,'plan' ,'auth_user','plan_type','plan_limit' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactUsRequest $request)
    {
        $contactUsCreated = ContactUs::create($request->validated());
        $res = $contactUsCreated->id > 0;

        if ($res) {
            session()->flash("contact-us-created");
        }

        return redirect()->route('frontend.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $cotactus = ContactUs::find($id);
        $msg = __('messages.msg_fail_to_delete', ['item' => __('messages.contactus')]);

        if ($cotactus != '') {

            $cotactus->delete();
            $msg = __('messages.msg_deleted', ['name' => __('messages.contactus')]);
        }
        return comman_custom_response(['message' => $msg, 'status' => true]);
    }
}
