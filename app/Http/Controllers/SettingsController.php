<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $status = Settings::where('meta_key', 'open_status')->get()->toArray()[0]['meta_value'] ?? 'closed';
        $msg_title = Settings::where('meta_key', 'business_msg_title')->get()->toArray()[0]['meta_value'] ?? 'Business Message';
        $msg_desc = Settings::where('meta_key', 'business_msg')->get()->toArray()[0]['meta_value'] ?? 'Business Message Description';

        return view('pages.settings.index', compact('status','msg_title', 'msg_desc'));
    }

    public function update_settings(Request $request)
    {
         //dd($request->all());

        if ($request->business_msg_title != null) {
            Settings::updateOrCreate(
                ['meta_key' => 'business_msg_title'],
                ['meta_value' => $request->business_msg_title]);
        }
        else{
            Settings::updateOrCreate(
                ['meta_key' => 'business_msg_title'],
                ['meta_value' => '-']);
        }

        if ($request->business_msg != null) {
            Settings::updateOrCreate(
                ['meta_key' => 'business_msg'],
                ['meta_value' => $request->business_msg]);
        }
        else{
            Settings::updateOrCreate(
                ['meta_key' => 'business_msg'],
                ['meta_value' => '-']);
        }

        if ($request->has('open_status')) {
            Settings::updateOrCreate(
                ['meta_key' => 'open_status'],
                ['meta_value' => 1]);
        }
        else{
            Settings::updateOrCreate(
                ['meta_key' => 'open_status'],
                ['meta_value' => 0 ]);
        }


        return redirect()->route('settings.index');
    }
}
