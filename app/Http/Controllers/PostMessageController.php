<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\PostMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PostMessageController extends Controller
{

    public function index()
    {
        $post_messages = PostMessage::with('group')->get();

//        $messages = [];
//        $startTime = Carbon::now()->format('H:I:S');
//
//
//        $counter = 0;
//
//
//        foreach ($post_messages->toArray() as $post_message)
//        {
//            var_dump($post_message);
//            $messages[]= $post_message;
//
//            $endTime = Carbon::parse($post_message['post_at']);
//
//            dd($post_message['post_at']->for);
//
//            $totalDuration =  $startTime->diff($endTime)->format('H:I:S')." Minutes";
//
//
//            $messages[$counter]['remaining_time'] =$totalDuration;
//            $counter++;
//        }

        return view('pages.post_message.index', compact('post_messages'));

    }


    public function create()
    {
        $groups = Group::all();
        return view('pages.post_message.add', compact('groups'));
    }


    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'body' => 'required',
            'group' => 'not_in:0'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->all());
        }


        PostMessage::create([
            'group_id' => $request->group,
            'post_at' => $request->datesingle,
            'body' => $request->body,
            'status' => 'pending',

        ]);
        Session::flash('success', 'Successfully Added');
        return back();
    }


    public function show(PostMessage $postMessage)
    {
        //
    }

    public function edit(PostMessage $postMessage)
    {
        $groups = Group::all();

        return view('pages.post_message.edit', compact('postMessage', 'groups'));
    }


    public function update(Request $request, PostMessage $postMessage)
    {
        //
    }

    public function destroy(PostMessage $postMessage)
    {
        $postMessage->delete();
        Session::flash('error', 'Successfully deleted');
        return back();
    }
}
