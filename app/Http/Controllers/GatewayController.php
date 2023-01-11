<?php

namespace App\Http\Controllers;


use App\Models\Gateway;
use App\Models\Order;
use App\Models\Screenshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GatewayController extends Controller
{
    public function index()
    {

        $gateways = Gateway::all();
        return view('pages.gateway.index', compact('gateways'));
    }

    public function create()
    {
        return view('pages.gateway.add');
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'api_key' => 'required',
            'api_secret' => 'required',
        ]);


        Gateway::create($request->all());

        Session::flash('success', __('Successfully Added'));
        return redirect()->back();
    }

    public function edit(Gateway $gateway)
    {
        return view('pages.gateway.edit', compact('gateway',));
    }


    public function update(Request $request, Gateway $gateway)
    {
        $this->validate($request, [
            'api_key' => 'required',
            'api_secret' => 'required',]);

        Gateway::updateOrCreate(
            ['id' => $gateway->id],
            [
                'title' => $request->title,
                'api_key' => $request->api_key,
                'api_secret' => $request->api_secret,
            ]);

        Session::flash('success', __('Successfully updated'));
        return back();
    }

    public function destroy(Gateway $gateway)
    {
        $gateway->delete();
        Session::flash('success', __('Successfully Deleted'));
        return redirect()->route('gateways.index');
    }
}
