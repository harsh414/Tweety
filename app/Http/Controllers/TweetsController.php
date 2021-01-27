<?php

namespace App\Http\Controllers;

use App\Tweet;
use Illuminate\Http\Request;

class TweetsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'body'=> 'required | max:300 ',
    ]);
        $tweet= new Tweet();
        $tweet->user_id= auth()->user()->id;
        $tweet->body=$request->body;
        $tweet->save();
        return redirect('/home');
    }
}
