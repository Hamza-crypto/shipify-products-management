<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index($tab = null, $user_id = null)
    {
        if (session('status') == 'password-updated') {
            $tab = 'password';
        }

        $user = Auth::user();
        $user_order_category = $user->order_category();
        $availability_status = $user->availability_status();

        return view('pages.profile.index', [
            'user' => $user,
            'tab' => $tab,
            'user_category' => $user_order_category,
            'status' => $availability_status,
        ]);
    }


    public function account(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->route('profile.index', 'account')->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        $user->update(['name' => $request->name]);

        Session::flash('account', __('Account information successfully updated.'));
        return back();
    }

    public function update_paxful_api(Request $request, User $user)
    {
        $user->metas()->updateOrCreate(
            ['meta_key' => 'paxful_api_key'],
            ['meta_value' => $request->api_key]);

        $user->metas()->updateOrCreate(
            ['meta_key' => 'paxful_api_secret'],
            ['meta_value' => $request->api_secret]);

        Session::flash('account', 'API Key updated successfully.');
        return redirect()->back();
    }

    public function update_availability_status(Request $request)
    {
        $user = Auth::user();
        $status = 0;

        if ($request->has('status')) {
            $status = 1;
        }

        $user->metas()->updateOrCreate(
            ['meta_key' => 'availability'],
            ['meta_value' => $status]);


        Session::flash('account', 'Status updated successfully.');
        return redirect()->back();
    }
}
