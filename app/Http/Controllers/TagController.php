<?php

namespace App\Http\Controllers;

use App\Models\Gateway;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TagController extends Controller
{

    public function index()
    {
        $tags = Auth::user()->tags;
        return view('pages.tag.index', compact('tags'));
    }


    public function create()
    {
        return view('pages.tag.add');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
        ]);

        Auth::user()->tags()->create($request->all());

        Session::flash('success', __('Successfully Added'));
        return redirect()->back();
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        Session::flash('success', __('Successfully Deleted'));
        return redirect()->route('tags.index');
    }
}
