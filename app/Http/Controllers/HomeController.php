<?php

namespace App\Http\Controllers;

use App\Tweet;
use App\User;
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
        $u_id= auth()->user()->id;
        $global_array= array(); //will contain id of who_to_follow users
        $tweetIDs = auth()->user()->following()->pluck('id');
        $tweetIDs->push(auth()->user()->id);
        $users_id= auth()->user()->following()->pluck('id'); //id of users i follow

        foreach ($users_id as $id){
            $userr= User::find($id);
            $id_array= $userr->following->pluck('id');
            foreach ($id_array as $i){
                array_push($global_array,$i);
            }
        }
        $global_array= array_unique($global_array);
        if (in_array(auth()->user()->id, $global_array)){
            unset($global_array[array_search(auth()->user()->id,$global_array)]);
        }
        //below is selecting users from global_array but discarding already following
        $w_t_f= DB::table('users')->whereIn('id',$global_array)->
        whereNotIn('id',$users_id);
        $w_t_f= $w_t_f->inRandomOrder()->take('3')->get();
        if($w_t_f->count() < 4)
        {
            $w_t_f= User::whereNotIn('id',$users_id)->where('id','!=',auth()->user()->id)->take(3)->get();
        }
        return view('notifications',[
            'who_to_follow'=>$w_t_f,
        ]);
    }

}
