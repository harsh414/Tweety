<?php

namespace App\Http\Controllers;

use App\Events\NewTweetEvent;
use App\Retweet;
use App\Tweet;
use App\tweetActivity;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class TweetsController extends Controller
{
    use tweetActivity; //for likeDislike and retweet a tweet

    public function getstarted()
    {
        $num_users= User::all();
        $users= User::inRandomOrder()->paginate('8');
        return view('getstarted',[
            'users'=>$users,
        ]);
    }



    public function index()
    {
        $global_array= array(); // will contain id of who_to_follow users
        $tweetIDs = auth()->user()->following()->pluck('id');
        $tweetIDs->push(auth()->user()->id);
        //Whom to follow section
        $users= auth()->user()->following;
        $users_id= auth()->user()->following()->pluck('id'); // id of users i follow

//        ************testing
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
        $w_t_f= $w_t_f->inRandomOrder()->take('5')->get();
        if($w_t_f->count() < 4)
        {
            $w_t_f= User::whereNotIn('id',$users_id)->where('id','!=',auth()->user()->id)->take(5)->get();
        }
//
        $tweets=Tweet::whereIn('user_id',$users_id)
            ->orWhere('user_id',auth()->user()->id)->latest()->get(); //tweets collection

        $twodarray= array();
        foreach ($tweets as $tweet) {
//            agar ye tweet hua toh tweet id aur r_u_id insert kar do array mae
            $retweets_of_this_by_followers = $tweet->retweets()->
            whereIn('r_u_id', $tweetIDs)->
                inRandomOrder()->get();
            if (count($retweets_of_this_by_followers) != 0) {
//                echo $tweet->id." ".count($retweets_of_this_by_followers)."<br>";
                $r_u_idd = $retweets_of_this_by_followers[0]->r_u_id;
                $user = User::find($r_u_idd);
                $name= $user->name;
                $twodarray[$tweet->id]=$name;
            }
        }

        if(count($w_t_f)==0){
            $new_to_follow= User::inRandomOrder()->take(5)->get();
        }


        if(count($tweets)==0)  //if its new user then display some tweets of random users
        {
            $tweets= Tweet::inRandomOrder()->take(10)->get();
        }


        //****************************************************** // retweets testing ends
        return view('tweets.index',[
            'tweets'=>$tweets,
            'who_to_follow'=>$w_t_f,
            'twodarray'=>$twodarray,
        ]);
    }



    public function store(Request $request)
    {
        $request->validate([
            'body'=> 'required | max:305 ',
            'file'=>'max:10000',
        ]);
        $user= auth()->user();
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

        if($tweet->save()){
            event(new NewTweetEvent($tweet,$user));
        }
        return redirect('/tweets');

    }


    public function showTweet($tid)
    {
//        auth()->user()->notifications->where('id',$nid)->markAsRead();
        $global_array= array(); // will contain id of who_to_follow users
        $tweetIDs = auth()->user()->following()->pluck('id');
        $tweetIDs->push(auth()->user()->id);
        //Whom to follow section
        $users= auth()->user()->following;
        $users_id= auth()->user()->following()->pluck('id'); // id of users i follow
//        ************testing
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
        $w_t_f= $w_t_f->inRandomOrder()->take('5')->get();
        $tweet= Tweet::find($tid);
        return view('tweets/showTweet',[
            'who_to_follow'=>$w_t_f,
            'tweet'=>$tweet,
        ]);
    }



}
