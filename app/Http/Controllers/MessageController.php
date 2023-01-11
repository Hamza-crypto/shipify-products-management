<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{

    public function index()
    {
        $messages = Message::all();
        return view('pages.message.index', compact('messages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.message.add');
    }


    public function store(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'body' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->all());
        }

        Message::create($request->all() + ['user_id' => Auth()->user()->id]);
        Session::flash('success', 'Successfully Added');
        return back();

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Message $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Message $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        return view('pages.message.edit', compact('message'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Message $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->all());
        }

       Message::updateOrCreate(
            ['id' => $message->id],
            [
                'user_id' => Auth()->user()->id,
                'type' => $request->type,
                'trigger_at' => $request->trigger_at,
                'body' => $request->body,
            ]
        );

        Session::flash('success', 'Successfully updated');
        return redirect()->route('messages.index');
    }


    public function destroy(Message $message)
    {
        $message->delete();
        Session::flash('error', 'Successfully deleted');
        return back();
    }
}
