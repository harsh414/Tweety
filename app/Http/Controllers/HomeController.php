<?php

namespace App\Http\Controllers;

use App\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function notifications()
    {
        $users =auth()->user()->following;
        $users_id= auth()->user()->following()->pluck('id'); // id of users i follow
        $User= DB::table('users');
        foreach ($users as $user) {
            $ids = $user->following()->pluck('id');   // id of users my follower follow
            $User = $User->whereIn('id',$ids,'or');
        }
        $User=$User->whereNotIn('id',$users_id,'and'); //exclude common followers
        $User= $User->where('id','!=',auth()->user()->id);
        $User=$User->inRandomOrder()->take(5)->get();
        return view('notifications',[
            'who_to_follow'=>$User,
        ]);
    }

}
