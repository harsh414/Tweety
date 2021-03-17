<?php

namespace App\Http\Controllers;

use App\Tweet;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class TweetsController extends Controller
{
    public function index()
    {

//        user tweets as well as tweets associated with the
//        users the auth user is following
        $tweetIDs= auth()->user()->following()->pluck('id');


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
        $User= $User->inRandomOrder()->take(5)->get();


        $tweets= Tweet::whereIn('user_id',$tweetIDs)
            ->orWhere('user_id',auth()->user()->id)->latest()->get();

        return view('tweets.index',[
            'tweets'=>$tweets,
            'who_to_follow'=>$User
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'body'=> 'required | max:300 ',
            'file'=>'max:10000',
    ]);
        $tweet= new Tweet();
        $tweet->user_id= auth()->user()->id;
        $tweet->body=$request->body;
        if($request->hasFile('upload')){
            $image = $request->file('upload');
            $path = $image->store('tweetMedia', 's3');
            $array = explode('.', $path);
            $extension = end($array);
            $url = Storage::disk('s3')->url($path);
            $tweet->mediaurl= $url;
            $tweet->mediaformat = $extension;
        }

        $tweet->save();
        return redirect('/tweets');
    }


    public function returnAll()
    {
        $output='sndfis';
        $data = array(
            'table_data'  => $output,
        );

        echo json_encode($data);
    }


    public function likeOrDislike(Request $request)
    {
        $output='';
        $tweet_id = $request->get('t_id');
        $tweet= Tweet::find($tweet_id);
        if($tweet->ifLikedBy(auth()->user(),$tweet)){
            $user= auth()->user();
            $tweet->dislike($user);
        }else{
            $user= auth()->user();
            $tweet->like(auth()->user());
        }

        if($tweet->ifLikedBy(auth()->user(),$tweet)){
            $id=$tweet->id;
            $output.='
        <div class="flex">
        <span onclick="likeUpdate('.$id.')">
        <img src="'.asset('images/download.png').'" style="height: 20px;width: 20px" alt="">
        </span> &nbsp;
        '.$tweet->num_likes($tweet)->count().'
        </div>';
        }else{
            $id=$tweet->id;
            $output.='
        <div class="flex">
        <span onclick="likeUpdate('.$id.')">
        <img src="https://static.thenounproject.com/png/734918-200.png" style="height: 20px;width: 20px" alt="">
        </span> &nbsp;
        '.$tweet->num_likes($tweet)->count().'
        </div>
            ';
        }


        $data = array(
            'likeData'=>$output,
        );

        echo json_encode($data);

    }
}
