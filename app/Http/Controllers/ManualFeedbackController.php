<?php

namespace App\Http\Controllers;

use App\Models\Bin;
use App\Models\ManualFeedback;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ManualFeedbackController extends Controller
{
    public function index(){

        $user = Auth::user();
        if(in_array($user->role, ['admin', 'customer'])){
            $feedbacks = ManualFeedback::where('status', 'pending')->get();
        }
        else{
            $feedbacks = ManualFeedback::where('user_id', $user->id)->get();
        }

        return view('pages.feedback.index', compact('feedbacks','user'));
    }


    public function store(Request $request){

        dd($request->all());
        ManualFeedback::create(
           $request->all() + ['user_id' => Auth()->id(), 'status' => 'pending']
            );

        Session::flash('success', 'Successfully added');
        return redirect()->route('feedbacks.index');
    }

    public function update(Request $request, ManualFeedback $feedback){
        //dd( $request->all());

        ManualFeedback::updateOrCreate(
            ['id' => $feedback->id],
            [
                'assistant_note' => $request->input('assistant_note'),
                'status' => $request->input('status')
                ]
        );

        Session::flash('success', 'Successfully updated');
        return back();

    }
}
