<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestProviderRequest;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RequestProviderController extends Controller
{
    public function __construct()
    {
        // Middleware only applied to these methods
        $this->middleware('throttle:30', [
            'only' => [
                'store', // Could add bunch of more methods too
                'index' // Could add bunch of more methods too
            ]
        ]);
    }


    public function index()
    {
        $locale = app()->getLocale();
        return view('frontend.request_provider', compact('locale'));
    }

    public function store(RequestProviderRequest $request)
    {
        $input = $request->all();
        $contactNumber = $input['contact_number'];
        $password = Str::random(15);
        $input['display_name'] = $input['first_name'] . " " . $input['last_name'];
        $input['user_type'] = 'provider';
        $input['password'] = Hash::make($password);

        $input['status'] = 0;
        $user = User::withTrashed()
            ->where(function ($query) use ($contactNumber) {
                $query->where('contact_number', $contactNumber);
            })
            ->first();
        if ($user) {
            if ($user->deleted_at == null) {
                $message = trans('messages.login_form');
                $response = [
                    'message' => $message,
                ];
                session()->flash("flash_message", $message);
            } else {
                $message = trans('messages.deactivate');
                $response = [
                    'message' => $message,
                    'Isdeactivate' => 1,
                ];

                session()->flash("flash_message", $message);
            }

        } else {
            $user = User::create($input);
            $user->assignRole($input['user_type']);
            session()->flash("flash_message", 'درخواست شما با موفقیت ثبت شد');
        }

        if ($user->user_type == 'provider' || $user->user_type == 'user') {
            $wallet = array(
                'title' => $user->display_name,
                'user_id' => $user->id,
                'amount' => 0
            );
            $result = Wallet::create($wallet);
        }

        return redirect()->route('requestProvider');
    }
}
