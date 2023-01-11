<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{

    public function index()
    {
        $groups = Group::all();
        return view('pages.group.index', compact('groups'));
    }


    public function create()
    {
        return view('pages.group.add');
    }


    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->all());
        }


        Group::create($request->all());
        Session::flash('success', 'Successfully Added');
        return back();
    }


    public function show(Group $group)
    {
        //
    }

    public function edit(Group $group)
    {
        //
    }

    public function update(Request $request, Group $group)
    {
        //
    }


    public function destroy(Group $group)
    {
        $group->delete();
        Session::flash('error', 'Successfully deleted');
        return back();
    }
}
