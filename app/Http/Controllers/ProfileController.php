<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{

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
        $profile_user= User::where('id',$id)->get();
        $profile_user=$profile_user->first();

        $followers_of_this_profile = DB::table('follows')->where('following_user_id',$profile_user->id)->get();
        $count_followers= count( $followers_of_this_profile );



        return view('profile.index',[
            'profile'=>$profile_user,
            'who_to_follow'=>$User,
            'followers'=>$count_followers,
        ]);
    }




    public function returnAllTweets(Request $request)
    {
        $output= '';
        $id= $request->get('id');

        $user = User::where('id',$id)->get()->first();
        $tweets= $user->tweets;
        foreach ($tweets as $tweet) {
            $output .= '
        <div class="rounded-lg border border-gray-300 shadow py-4 px-8 mt-3">
        <div class="flex p-2">
            <div class="flex-shrink-0 mr-2">
                <img src="'.$user->url.'" alt="" class="rounded-full mr-2" style="height: 50px;width: 50px;">
            </div>

            <div class="">
                <h5 class="lg:my-2 font-bold">
                    ' . $tweet->user->name . '
                </h5>
                <p class="text-sm">
                    ' . $tweet->body . '
                </p>
            </div>
        </div>
        <div class="flex lg:ml-16">
            <div class="flex-1"><i class="fa fa-comment"></i> 1</div>
            <div class="flex-1"><i class="fa fa-heart"></i> 1</div>
            <div class="flex-1"><i class="fa fa-retweet"></i> 1</div>
            <div class="flex-1">1</div>
        </div>
        </div>
        ';
        }
//
        $data = array(
            'table_data' => $output,
        );
        echo json_encode($data);
    }

//    for updating the profile
    public function update(Request $request,$id)
    {
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
}
