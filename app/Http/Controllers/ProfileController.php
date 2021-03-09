<?php

namespace App\Http\Controllers;

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


    public function returnAllLikes(Request $request)
    {
//        i think this should work :)
//                SELECT * FROM
//        likes JOIN tweets
//        ON likes.user_id=$id AND tweets.id=likes.tweet_id
//        WHERE isLiked=1;
        $output= '';
        $id= $request->get('id');
        $query= DB::select("SELECT * FROM
        likes JOIN tweets
        ON likes.user_id=$id AND tweets.id=likes.tweet_id
        WHERE isLiked=1;");
//        $output = (count($query));



//        $tweets= $user->tweets;
        foreach ($query as $query) {
            $tweet_id= $query->tweet_id;
            $tweet= Tweet::find($tweet_id);
            $output .= '
        <div class="rounded-lg border border-gray-300 shadow py-4 px-8 mt-3">';
            $output.='
        <div class="flex p-2">
            <div class="flex-shrink-0 mr-2">
                <img src="'.$tweet->user->url.'" alt="" class="rounded-full mr-2" style="height: 50px;width: 50px;">
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


        <div class="flex lg:ml-10 mt-2">
            <img src="'.asset('images/download.png').'" style="height: 20px;width: 20px" alt="">
            &nbsp;
            '.$tweet->num_likes($tweet)->count().'
        </div>
        </div>
        ';
        }
        $data = array(
            'table_data' => $output,
        );
        echo json_encode($data);
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
        return back();
    }

    public function unfollow($id)
    {
        $user= User::find($id);
        auth()->user()->unfollow($user);
        return back();
    }
}
