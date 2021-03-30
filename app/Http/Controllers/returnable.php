<?php


namespace App\Http\Controllers;


use App\Tweet;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait returnable
{

    public function returnAllTweets(Request $request)  //tweets in the profile page
    {
        $output = '';
        $id = $request->get('id');

        $user = User::find($id);

        //if auth user has retweeted his own tweet then it should not
        //display twice as tweet and retweet
        $retweets_id= $user->retweets()->pluck('retweet_id');
        $tweets = $user->tweets()->whereNotIn('id',$retweets_id)->get();
        $retweets= DB::table('retweets')
            ->where('retweets.r_u_id','=',$id)
            ->leftJoin('tweets','retweets.retweet_id','=','tweets.id')
            ->get();

        //$mergedCollection = $tweets->concat($retweets)->shuffle();
        $merged= $tweets->concat($retweets);
        foreach ($merged as $tweet) {
        $output .= '
        <div class="rounded-lg border border-gray-300 shadow py-4 px-8 mt-3">';
            if($tweet->retweet_id) {
                $output .= '
            <div class="flex mb-2">
            <svg viewBox="0 0 24 24" style="height: 18px;width: 18px" class="r-m0bqgq r-4qtqp9 r-yyyyoo r-z80fyv r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-19wmn03"><g><path d="M23.615 15.477c-.47-.47-1.23-.47-1.697 0l-1.326 1.326V7.4c0-2.178-1.772-3.95-3.95-3.95h-5.2c-.663 0-1.2.538-1.2 1.2s.537 1.2 1.2 1.2h5.2c.854 0 1.55.695 1.55 1.55v9.403l-1.326-1.326c-.47-.47-1.23-.47-1.697 0s-.47 1.23 0 1.697l3.374 3.375c.234.233.542.35.85.35s.613-.116.848-.35l3.375-3.376c.467-.47.467-1.23-.002-1.697zM12.562 18.5h-5.2c-.854 0-1.55-.695-1.55-1.55V7.547l1.326 1.326c.234.235.542.352.848.352s.614-.117.85-.352c.468-.47.468-1.23 0-1.697L5.46 3.8c-.47-.468-1.23-.468-1.697 0L.388 7.177c-.47.47-.47 1.23 0 1.697s1.23.47 1.697 0L3.41 7.547v9.403c0 2.178 1.773 3.95 3.95 3.95h5.2c.664 0 1.2-.538 1.2-1.2s-.535-1.2-1.198-1.2z"></path></g></svg>
            <div class="text-sm ml-3 font-weight-bold" style="color: #ac3973">You Retweeted</div>
            </div>
            ';
            }


            $output.='<div class="flex p-2">
                <div class="flex-shrink-0 mr-2">';
                $t_u_id= $tweet->user_id;
                $u= User::find($t_u_id);
                $output.='<img src="' . $u->url . '" alt="" class="rounded-full mr-2" style="height: 50px;width: 50px;">
                </div>

                <div class="">
                    <h5 class="lg:my-2 font-bold">';
                    if($tweet->retweet_id) {
                        $r_u_id = $tweet->user_id;  //user_id of original tweet holder
                        $u = User::find($r_u_id);
                        $output .='
                        '.$u->name.'
                            ';
                    }else {
                        $output .= '
                         ' . $tweet->user->name . '
                         ';
                    }
                    $output.='
                    </h5>
                    <p class="text-sm">
                        ' . $tweet->body . '
                    </p>
                </div>
            </div>';


            $output.='<div class="lg:ml-7 mt-3 flex justify-content-around" id="refLikes'.$tweet->id.'">';
            $id=$tweet->id;
            $t= Tweet::find($id);
            if($t->ifLikedBy(auth()->user(),$t)){
                $output.='
            <div class="flex">
            <span onclick="likeUpdate('.$id.')">
            <img src="'.asset('images/download.png').'" style="height: 20px;width: 20px" alt="">
            </span> &nbsp;
            '.$t->num_likes($t)->count().'
            </div>';
                }else{
                    $output.='
            <div class="flex">
            <span onclick="likeUpdate('.$id.')">
            <img src="https://static.thenounproject.com/png/734918-200.png" style="height: 20px;width: 20px" alt="">
            </span> &nbsp;
            '.$t->num_likes($t)->count().'
            </div>
            ';
            }
            $output.='
        <div class="flex">';
            if($t->isRetweeted(auth()->user(),$t)) {
                $output .= '
            <div onclick="tweetUpdate(' . $t->id . ')" data-toggle="tooltip" title="Undo Retweet"><svg viewBox="0 0 24 24" fill="red" stroke="light-green" style="height: 18px;width: 18px;" class="r-m0bqgq r-4qtqp9 r-yyyyoo r-z80fyv r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-19wmn03"><g><path d="M23.615 15.477c-.47-.47-1.23-.47-1.697 0l-1.326 1.326V7.4c0-2.178-1.772-3.95-3.95-3.95h-5.2c-.663 0-1.2.538-1.2 1.2s.537 1.2 1.2 1.2h5.2c.854 0 1.55.695 1.55 1.55v9.403l-1.326-1.326c-.47-.47-1.23-.47-1.697 0s-.47 1.23 0 1.697l3.374 3.375c.234.233.542.35.85.35s.613-.116.848-.35l3.375-3.376c.467-.47.467-1.23-.002-1.697zM12.562 18.5h-5.2c-.854 0-1.55-.695-1.55-1.55V7.547l1.326 1.326c.234.235.542.352.848.352s.614-.117.85-.352c.468-.47.468-1.23 0-1.697L5.46 3.8c-.47-.468-1.23-.468-1.697 0L.388 7.177c-.47.47-.47 1.23 0 1.697s1.23.47 1.697 0L3.41 7.547v9.403c0 2.178 1.773 3.95 3.95 3.95h5.2c.664 0 1.2-.538 1.2-1.2s-.535-1.2-1.198-1.2z"></path></g></svg></div>
            <span class="pb-2 ml-2" style="color: red">' . $t->retweets()->count() . '</span>';
            }else{
                $output.='
            <div onclick="tweetUpdate(' . $t->id . ')"  data-toggle="tooltip"  title="Retweet"><svg viewBox="0 0 24 24" fill="" style="height: 18px;width: 18px;" class="r-m0bqgq r-4qtqp9 r-yyyyoo r-z80fyv r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-19wmn03"><g><path d="M23.615 15.477c-.47-.47-1.23-.47-1.697 0l-1.326 1.326V7.4c0-2.178-1.772-3.95-3.95-3.95h-5.2c-.663 0-1.2.538-1.2 1.2s.537 1.2 1.2 1.2h5.2c.854 0 1.55.695 1.55 1.55v9.403l-1.326-1.326c-.47-.47-1.23-.47-1.697 0s-.47 1.23 0 1.697l3.374 3.375c.234.233.542.35.85.35s.613-.116.848-.35l3.375-3.376c.467-.47.467-1.23-.002-1.697zM12.562 18.5h-5.2c-.854 0-1.55-.695-1.55-1.55V7.547l1.326 1.326c.234.235.542.352.848.352s.614-.117.85-.352c.468-.47.468-1.23 0-1.697L5.46 3.8c-.47-.468-1.23-.468-1.697 0L.388 7.177c-.47.47-.47 1.23 0 1.697s1.23.47 1.697 0L3.41 7.547v9.403c0 2.178 1.773 3.95 3.95 3.95h5.2c.664 0 1.2-.538 1.2-1.2s-.535-1.2-1.198-1.2z"></path></g></svg></div>
            <span class="pb-2 ml-2" style="color: black">' . $t->retweets()->count() . '</span>
                ';
            }
            $output.='
        </div>
        ';

            $output.='</div>';

        $output.='</div>';
        }
        $data = array(
            'table_data' => $output,
        );
        echo json_encode($data);
    }


    public function returnAllMedia(Request $request)  //media in the profile page
    {
//        left incompllete :(
        $output = '';
        $id = $request->get('id');

        $user = User::find($id);
        $tweets = DB::table('tweets')->where('user_id',$id)
                        ->where('mediaformat','!=','')
                        ->where('mediaurl','!=','')->get();

        foreach ($tweets as $tweet) {
            $output .= '
        <div class="rounded-lg border border-gray-300 shadow py-4 px-8 mt-3">
        <div class="flex p-2">
            <div class="flex-shrink-0 mr-2">
            <img src="' . $user->url . '" alt="" class="rounded-full mr-2" style="height: 50px;width: 50px;">
            </div>
            <div class="flex flex-col">
            <div class="flex">
            <h5 class="lg:my-2 font-bold flex">
            <a href="profile/'.$tweet->user_id.'"><img src="" style="height: 50px;width: 50px;" alt="" class="rounded-full mr-2">
            </a>
            ';

            if($tweet->user->isVip){
                $output.='
            <img src="" class="ml-2" style="height: 15px; width: 15px" alt="jrsf">';
            }
            $output.='
            </h5>
            </div>

         </div>
         </div>';
        }


        $data = array(
            'table_data' => $output,
        );
        echo json_encode($data);
    }



    public function returnAllLikes(Request $request)  //likes in the profile page
    {

        $output= '';
        $id= $request->get('id');
        $query= DB::select("SELECT * FROM
        likes JOIN tweets
        ON likes.user_id=$id AND tweets.id=likes.tweet_id
        WHERE isLiked=1;");


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
        </div>';



            $output.='<div class="lg:ml-7" id="refLikes'.$tweet->id.'">';
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
            $output.='</div>';
            $output.='</div>';
        }
        $data = array(
            'table_data' => $output,
        );
        echo json_encode($data);
    }
}
