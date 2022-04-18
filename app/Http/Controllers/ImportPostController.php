<?php

namespace App\Http\Controllers;

use App\Jobs\ImportPostJob;
use Illuminate\Http\Request;

class ImportPostController extends Controller
{

    public function create(Request $request)
    {
        return view('dashboard.posts.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'import_post' => 'required|string'
        ]);

        if(!ImportPostJob::dispatch()){
            return redirect()->back()->with('type', 'error')->with('message', 'An error occrued while trying to schedule post import, please try again later');
        }

        return back()->with('type', 'success')->with('message', 'Your posts have been scheduled to be imported. 😉');
    }

    
}
