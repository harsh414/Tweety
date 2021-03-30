<?php

namespace App\Http\Controllers;

use App\Events\FollowEvent;
use App\Tweet;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    use returnable;  //contains functions for returning allTweets media and likes of a profile

    public function index($id=null)
    {
        //        <!-- Whom to follow section -->
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

//        profile kiski hai
        $profile_user= User::find($id);
        $followers_of_this_profile = DB::table('follows')->where('following_user_id',$profile_user->id)->get();
        $count_followers= count( $followers_of_this_profile );



        $status_user= User::find($id);
        $status= auth()->user()->isFollowing($status_user);
        if($status) $status='Following';
        else $status='Follow';
        return view('profile.index',[
            'profile'=>$profile_user,
            'who_to_follow'=>$User,
            'followers'=>$count_followers,
            'status'=>$status,
        ]);
    }



//    for updating the profile
    public function update(Request $request,$id)
    {
        $request->validate([
            'name'=>'required',
        ]);
        $user= User::find($id);
        $name= $request->input('name');
        $bio= $request->input('bio');

        if($request->hasFile('file')) {
            $image = $request->file('file');
            $path = $image->store('profileimages', 's3');
            $url = Storage::disk('s3')->url($path);
        }

        $user->name= $name;
        $user->bio=$bio;
        if($request->file('file'))
        $user->url= $url;
        if($user->update())
            return back()->with('message','Profile Updated');
        else
            return back()->with('message','Failed to Update');
    }

    public function follow($id)
    {
        $user= User::find($id);
        auth()->user()->follow($user);
        event(new FollowEvent(auth()->user(),$user)); // if a follow b than b will
        // receive notificaion that a followed him
        return back();
    }

    public function unfollow($id)
    {
        $user= User::find($id);
        auth()->user()->unfollow($user);
        return back();
    }
}
