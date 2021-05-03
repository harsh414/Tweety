<?php


namespace App;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait tweetActivity
{
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
        <div class="flex">';
            if($tweet->isRetweeted(auth()->user(),$tweet)) {
                $output .= '
            <span onclick="tweetUpdate(' . $tweet->id . ')" data-toggle="tooltip" title="Undo Retweet"><svg viewBox="0 0 24 24" stroke="light-green" fill="red" style="height: 18px;width: 18px;" class="r-m0bqgq r-4qtqp9 r-yyyyoo r-z80fyv r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-19wmn03"><g><path d="M23.615 15.477c-.47-.47-1.23-.47-1.697 0l-1.326 1.326V7.4c0-2.178-1.772-3.95-3.95-3.95h-5.2c-.663 0-1.2.538-1.2 1.2s.537 1.2 1.2 1.2h5.2c.854 0 1.55.695 1.55 1.55v9.403l-1.326-1.326c-.47-.47-1.23-.47-1.697 0s-.47 1.23 0 1.697l3.374 3.375c.234.233.542.35.85.35s.613-.116.848-.35l3.375-3.376c.467-.47.467-1.23-.002-1.697zM12.562 18.5h-5.2c-.854 0-1.55-.695-1.55-1.55V7.547l1.326 1.326c.234.235.542.352.848.352s.614-.117.85-.352c.468-.47.468-1.23 0-1.697L5.46 3.8c-.47-.468-1.23-.468-1.697 0L.388 7.177c-.47.47-.47 1.23 0 1.697s1.23.47 1.697 0L3.41 7.547v9.403c0 2.178 1.773 3.95 3.95 3.95h5.2c.664 0 1.2-.538 1.2-1.2s-.535-1.2-1.198-1.2z"></path></g></svg></span>
            <span class="pb-2 ml-2" style="color: red">' . $tweet->retweets()->count() . '</span>';
            }else {
                $output .= '
                <span onclick="tweetUpdate(' . $tweet->id . ')" data-toggle="tooltip"  title="Retweet"><svg viewBox="0 0 24 24" fill="" style="height: 18px;width: 18px;" class="r-m0bqgq r-4qtqp9 r-yyyyoo r-z80fyv r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-19wmn03"><g><path d="M23.615 15.477c-.47-.47-1.23-.47-1.697 0l-1.326 1.326V7.4c0-2.178-1.772-3.95-3.95-3.95h-5.2c-.663 0-1.2.538-1.2 1.2s.537 1.2 1.2 1.2h5.2c.854 0 1.55.695 1.55 1.55v9.403l-1.326-1.326c-.47-.47-1.23-.47-1.697 0s-.47 1.23 0 1.697l3.374 3.375c.234.233.542.35.85.35s.613-.116.848-.35l3.375-3.376c.467-.47.467-1.23-.002-1.697zM12.562 18.5h-5.2c-.854 0-1.55-.695-1.55-1.55V7.547l1.326 1.326c.234.235.542.352.848.352s.614-.117.85-.352c.468-.47.468-1.23 0-1.697L5.46 3.8c-.47-.468-1.23-.468-1.697 0L.388 7.177c-.47.47-.47 1.23 0 1.697s1.23.47 1.697 0L3.41 7.547v9.403c0 2.178 1.773 3.95 3.95 3.95h5.2c.664 0 1.2-.538 1.2-1.2s-.535-1.2-1.198-1.2z"></path></g></svg></span>
            <span class="pb-2 ml-2" style="color: black">' . $tweet->retweets()->count() . '</span>';
            }
            $output.='
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
        $output='';
        if(auth()->user()->hadRetweeted($tweet) == false){
            $retweet= new Retweet;
            $retweet->r_u_id= $auth_id;
            $retweet->retweet_id= $tweet_id;
            $retweet->save();
        }else{
            $retweet= Retweet::where('r_u_id',$auth_id)
                ->where('retweet_id',$tweet_id);
            $retweet->delete();
        }

//        if($tweet->ifLikedBy(auth()->user(),$tweet)){
//            $id=$tweet->id;
//            $output.='
//        <div class="flex">
//        <span onclick="likeUpdate('.$id.')">
//        <img src="'.asset('images/download.png').'" style="height: 20px;width: 20px" alt="">
//        </span> &nbsp;
//        '.$tweet->num_likes($tweet)->count().'
//        </div>';
//        }else{
//            $id=$tweet->id;
//            $output.='
//        <div class="flex">
//        <span onclick="likeUpdate('.$id.')">
//        <img src="https://static.thenounproject.com/png/734918-200.png" style="height: 20px;width: 20px" alt="">
//        </span> &nbsp;
//        '.$tweet->num_likes($tweet)->count().'
//        </div>
//            ';
//        }
//        $output.='
//        <div class="flex">';
//        if($tweet->isRetweeted(auth()->user(),$tweet)) {
//            $output .= '
//            <div onclick="tweetUpdate(' . $tweet->id . ')" data-toggle="tooltip" title="Undo Retweet"><svg viewBox="0 0 24 24" fill="red" stroke="light-green" style="height: 18px;width: 18px;" class="r-m0bqgq r-4qtqp9 r-yyyyoo r-z80fyv r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-19wmn03"><g><path d="M23.615 15.477c-.47-.47-1.23-.47-1.697 0l-1.326 1.326V7.4c0-2.178-1.772-3.95-3.95-3.95h-5.2c-.663 0-1.2.538-1.2 1.2s.537 1.2 1.2 1.2h5.2c.854 0 1.55.695 1.55 1.55v9.403l-1.326-1.326c-.47-.47-1.23-.47-1.697 0s-.47 1.23 0 1.697l3.374 3.375c.234.233.542.35.85.35s.613-.116.848-.35l3.375-3.376c.467-.47.467-1.23-.002-1.697zM12.562 18.5h-5.2c-.854 0-1.55-.695-1.55-1.55V7.547l1.326 1.326c.234.235.542.352.848.352s.614-.117.85-.352c.468-.47.468-1.23 0-1.697L5.46 3.8c-.47-.468-1.23-.468-1.697 0L.388 7.177c-.47.47-.47 1.23 0 1.697s1.23.47 1.697 0L3.41 7.547v9.403c0 2.178 1.773 3.95 3.95 3.95h5.2c.664 0 1.2-.538 1.2-1.2s-.535-1.2-1.198-1.2z"></path></g></svg></div>
//            <span class="pb-2 ml-2" style="color: red">' . $tweet->retweets()->count() . '</span>';
//        }else{
//            $output.='
//            <div onclick="tweetUpdate(' . $tweet->id . ')"  data-toggle="tooltip"  title="Retweet"><svg viewBox="0 0 24 24" fill="" style="height: 18px;width: 18px;" class="r-m0bqgq r-4qtqp9 r-yyyyoo r-z80fyv r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-19wmn03"><g><path d="M23.615 15.477c-.47-.47-1.23-.47-1.697 0l-1.326 1.326V7.4c0-2.178-1.772-3.95-3.95-3.95h-5.2c-.663 0-1.2.538-1.2 1.2s.537 1.2 1.2 1.2h5.2c.854 0 1.55.695 1.55 1.55v9.403l-1.326-1.326c-.47-.47-1.23-.47-1.697 0s-.47 1.23 0 1.697l3.374 3.375c.234.233.542.35.85.35s.613-.116.848-.35l3.375-3.376c.467-.47.467-1.23-.002-1.697zM12.562 18.5h-5.2c-.854 0-1.55-.695-1.55-1.55V7.547l1.326 1.326c.234.235.542.352.848.352s.614-.117.85-.352c.468-.47.468-1.23 0-1.697L5.46 3.8c-.47-.468-1.23-.468-1.697 0L.388 7.177c-.47.47-.47 1.23 0 1.697s1.23.47 1.697 0L3.41 7.547v9.403c0 2.178 1.773 3.95 3.95 3.95h5.2c.664 0 1.2-.538 1.2-1.2s-.535-1.2-1.198-1.2z"></path></g></svg></div>
//            <span class="pb-2 ml-2" style="color: black">' . $tweet->retweets()->count() . '</span>
//                ';
//        }
//        $output.='
//        </div>
//        ';
        $data = array(
            'activityData'=>$output,
        );
        echo json_encode($data);
    }



}
