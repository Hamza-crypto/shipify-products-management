<?php

namespace App\Http\Controllers;
use App\Models\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Bin;

class BinController extends Controller {

	public function index() {

		$bins = Bin::with('gateway')->get();
		return view('pages.bin.index', compact('bins'));
	}

	public function create() {
        $gateways = Gateway::all();
		return view('pages.bin.add', compact('gateways'));
	}

	public function store(Request $request) {

        $validated = $request->validate([
            'number' => 'required',
            'min_amount' => 'required',
            'max_amount' => 'required',
            'gateway_id' => 'required',
        ]);

		Bin::create(
            $request->all()
		);

		Session::flash('success', __('Successfully Added'));
		return redirect()->back();
	}

    public function edit(Bin $bin)
    {
        $gateways = Gateway::all();
        return view('pages.bin.edit', compact('bin','gateways'));
    }


    public function update(Request $request, Bin $bin)
    {

        $validated = $request->validate([
            'number' => 'required'
        ]);

        BIN::updateOrCreate(
            ['id' => $bin->id],
            [
                'number' => $request->number,
                'min_amount' => $request->min_amount,
                'max_amount' => $request->max_amount,
                'gateway_id' => $request->gateway_id,

            ]);

        Session::flash('success', __('Successfully updated'));
        return back();
    }

	public function destroy(Bin $bin) {
        $bin->delete();
		Session::flash('danger', __('Successfully Deleted'));
		return redirect()->route('bins.index');
	}
}
