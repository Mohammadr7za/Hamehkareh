<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkSampleRequest;
use App\Http\Resources\WorkSampleResource;
use App\Models\ContactUs;
use App\Models\WorkSample;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class WorkSampleController extends Controller
{
    use Upload;

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);

        $actionType = $request->action_type;

        $message = 'Bulk Action Updated';

        switch ($actionType) {
//            case 'change-status':
//                $branches = WorkSample::whereIn('id', $ids)->update(['status' => $request->status]);
//                $message = 'Bulk Plans Status Updated';
//                break;

            case 'delete':
                WorkSample::whereIn('id', $ids)->delete();
                $message = 'Bulk Plans Deleted';
                break;

            default:
                return response()->json(['status' => false, 'message' => 'Action Invalid']);
                break;
        }

        return response()->json(['status' => true, 'message' => 'Bulk Action Updated']);
    }

    public function index_data(DataTables $datatable, Request $request)
    {
        $query = WorkSample::query()->orderByDesc('created_at');
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
            ->editColumn('first_name', function ($query) {
                $link = $query->path;
                return $link;
            })
            ->editColumn('last_name', function ($query) {
                $link = $query->path;
                return $link;
            })
            ->editColumn('file', function ($query) {
                $link = $query->path;
                return $link;
            })
//            ->editColumn('status', function ($query) {
//                return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
//                    <div class="custom-switch-inner">
//                        <input type="checkbox" class="custom-control-input  change_status" data-type="plan_status" ' . ($query->status ? "checked" : "") . '   value="' . $query->id . '" id="' . $query->id . '" data-id="' . $query->id . '">
//                        <label class="custom-control-label" for="' . $query->id . '" data-on-label="" data-off-label=""></label>
//                    </div>
//                </div>';
//            })
            ->addColumn('action', function ($query) {
                return view('worksamples.action', compact('query'))->render();
            })
            ->addIndexColumn()
            ->rawColumns(['title', 'action', 'status', 'check'])
            ->toJson();
    }


    public function store(StoreWorkSampleRequest $request)
    {
        try {
            if (auth()->user()->hasAnyRole(['admin', 'manager', 'handyman'])) {
                if ($request->hasFile('file')) {
                    $path = $this->UploadFile($request->file('file'), 'work_samples');//use the method in the trait
                    WorkSample::create([
                        'path' => $path,
                        'type' => $request->file('file')->extension(),
                        'user_id' => auth()->user()->id
                    ]);
                    return comman_message_response("فایل با موفقیت بارگذاری شد");
                }
            }
        } catch (\Exception $exception) {
            return comman_message_response("$exception", 200, false);
        }

        return comman_message_response("خطا در بارگذاری فایل", 200, false);

    }

    public function getWorkSamplesByUserId($id)
    {
        try {
            if (auth()->user()->hasAnyRole(['admin', 'manager'])) {
                $files = WorkSample::where('user_id', $id)->get();
                return comman_message_response("", 200, true, WorkSampleResource::collection($files));
            }

            return comman_message_response("دسترسی ندارید", 200, false);
        } catch (\Exception $exception) {
            return comman_message_response("خطا", 200, false);
        }
    }

    public function index(Request $request)
    {
        $filter = [
            'status' => $request->status,
        ];
        $pageTitle = trans('messages.list_form_title', ['form' => trans('messages.worksamples')]);
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('worksamples.index', compact('pageTitle', 'auth_user', 'assets', 'filter'));
    }

    public function getWorkSamples()
    {
        try {
            $files = auth()->user()->workSamples()->get();
            return comman_message_response("", 200, true, WorkSampleResource::collection($files));
        } catch (\Exception $exception) {
            return comman_message_response("خطا", 200, false);
        }
    }

    public function destroy($id)
    {
        try {
            if (auth()->user()->hasAnyRole(['admin', 'manager'])) {
                $file = WorkSample::findOrFail($id);
            } else {
                $file = auth()->user()->workSamples()->findOrFail($id);
            }
            $this->deleteFile($file->path);
            $file->delete();
            return comman_message_response("با موققیت حذف شد");
        } catch (\Exception $exception) {
            return comman_message_response("خطا در حذف", 200, false);
        }
    }

    public function delete($id)
    {
        try {
            if (auth()->user()->hasAnyRole(['admin', 'manager'])) {
                $file = WorkSample::findOrFail($id);
            } else {
                $file = auth()->user()->workSamples()->findOrFail($id);
            }
            $this->deleteFile($file->path);
            $file->delete();
            $msg = __('messages.msg_fail_to_delete', ['item' => __('messages.worksamples')]);
            return comman_custom_response(['message' => $msg, 'status' => true]);
        } catch (\Exception $exception) {
            return comman_custom_response(['message' => $msg, 'status' => false]);
        }
    }
}
