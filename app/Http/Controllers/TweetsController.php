<?php

namespace App\Http\Controllers;

use App\Retweet;
use App\Tweet;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class TweetsController extends Controller
{
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

        $tweets= Tweet::whereIn('user_id',$users_id)
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
        $tweet = Tweet::find($tweet_id);
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
        $output.='
        <div class="flex">
            <span onclick="tweetUpdate('.$tweet->id.')"><svg viewBox="0 0 24 24" style="height: 18px;width: 18px;" class="r-m0bqgq r-4qtqp9 r-yyyyoo r-z80fyv r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-19wmn03"><g><path d="M23.615 15.477c-.47-.47-1.23-.47-1.697 0l-1.326 1.326V7.4c0-2.178-1.772-3.95-3.95-3.95h-5.2c-.663 0-1.2.538-1.2 1.2s.537 1.2 1.2 1.2h5.2c.854 0 1.55.695 1.55 1.55v9.403l-1.326-1.326c-.47-.47-1.23-.47-1.697 0s-.47 1.23 0 1.697l3.374 3.375c.234.233.542.35.85.35s.613-.116.848-.35l3.375-3.376c.467-.47.467-1.23-.002-1.697zM12.562 18.5h-5.2c-.854 0-1.55-.695-1.55-1.55V7.547l1.326 1.326c.234.235.542.352.848.352s.614-.117.85-.352c.468-.47.468-1.23 0-1.697L5.46 3.8c-.47-.468-1.23-.468-1.697 0L.388 7.177c-.47.47-.47 1.23 0 1.697s1.23.47 1.697 0L3.41 7.547v9.403c0 2.178 1.773 3.95 3.95 3.95h5.2c.664 0 1.2-.538 1.2-1.2s-.535-1.2-1.198-1.2z"></path></g></svg></span>
            <span class="pb-2 ml-2">'.$tweet->retweets()->count().'</span>
        </div>
        ';



        $data = array(
            'activityData'=>$output,
        );

        echo json_encode($data);

    }

    public function retweet(Request $request)
    {
        $auth_id= Auth::id();
        $tweet_id = $request->get('t_id');
        $tweet= Tweet::find($tweet_id);
        if(auth()->user()->hadRetweeted($tweet) == false){
            $retweet= new Retweet();
            $retweet->r_u_id= $auth_id;
            $retweet->retweet_id= $tweet_id;
            $retweet->save();
        }else{
            $retweet= Retweet::where('r_u_id',$auth_id)
                                ->where('retweet_id',$tweet_id);
            $retweet->delete();
        }

        $output='';


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
        $output.='
        <div class="flex">
            <div onclick="tweetUpdate('.$tweet->id.')"><svg viewBox="0 0 24 24" style="height: 18px;width: 18px;" class="r-m0bqgq r-4qtqp9 r-yyyyoo r-z80fyv r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-19wmn03"><g><path d="M23.615 15.477c-.47-.47-1.23-.47-1.697 0l-1.326 1.326V7.4c0-2.178-1.772-3.95-3.95-3.95h-5.2c-.663 0-1.2.538-1.2 1.2s.537 1.2 1.2 1.2h5.2c.854 0 1.55.695 1.55 1.55v9.403l-1.326-1.326c-.47-.47-1.23-.47-1.697 0s-.47 1.23 0 1.697l3.374 3.375c.234.233.542.35.85.35s.613-.116.848-.35l3.375-3.376c.467-.47.467-1.23-.002-1.697zM12.562 18.5h-5.2c-.854 0-1.55-.695-1.55-1.55V7.547l1.326 1.326c.234.235.542.352.848.352s.614-.117.85-.352c.468-.47.468-1.23 0-1.697L5.46 3.8c-.47-.468-1.23-.468-1.697 0L.388 7.177c-.47.47-.47 1.23 0 1.697s1.23.47 1.697 0L3.41 7.547v9.403c0 2.178 1.773 3.95 3.95 3.95h5.2c.664 0 1.2-.538 1.2-1.2s-.535-1.2-1.198-1.2z"></path></g></svg></div>
            <span class="pb-2 ml-2">'.$tweet->retweets()->count().'</span>
        </div>
        ';
        $data = array(
            'activityData'=>$output,
        );
        echo json_encode($data);
    }


}
