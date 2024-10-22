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
        $tweets = DB::table('tweets')
            ->where('user_id',$id)
            ->orderBy('created_at',"DESC")->get();

        if($tweets->count()==0){
            $output.='
            <div class="rounded-lg border border-gray-300 shadow py-4 px-8 mt-3" style="word-break: break-word">
            <span class="font-bold text-center">No Recent Tweets...</span>
            <div class="mt-2.5 font-bold text-2xl">Who to follow</div>
            <a href="'.route('getstarted').'">
            <button type="submit" class="bg-blue-400 shadow rounded-lg px-4 py-2 mt-2 text-white lg:mr-1" style="width: 30vw;">
                Explore
            </button>
            </a>
            ';
        }
        foreach ($tweets as $tweet) {
        $output .= '
        <div class="rounded-lg border border-gray-300 shadow py-4 px-8 mt-3" style="word-break: break-word">';

            $output.='<div class="flex p-2">
                <div class="flex-shrink-0 mr-2">';
                $t_u_id= $tweet->user_id;
                $u= User::find($t_u_id);
                $output.='<a href="'.$u->id.'" style="text-decoration:none;color:black"><img src="' . $u->url . '" alt="" class="rounded-full mr-2" style="height: 50px;width: 50px;"></a>
                </div>

                <div class="flex flex-col">
                    <div class="flex">
                    <h5 class="lg:my-2 font-bold flex">';
                        $output .= '
                        <a href="'.$u->id.'" style="text-decoration:none;color:black">
                         ' . $u->name . '
                         </a>
                         ';
                        if($u->isVip){
                            $output.='
                        <svg viewBox="0 0 24 24" style="height:18px;width:18px" fill="#d24dff" aria-label="Verified account" class="r-13gxpu9 r-4qtqp9 r-yyyyoo r-1xvli5t r-9cviqr r-dnmrzs r-bnwqim r-1plcrui r-lrvibr"><g><path d="M22.5 12.5c0-1.58-.875-2.95-2.148-3.6.154-.435.238-.905.238-1.4 0-2.21-1.71-3.998-3.818-3.998-.47 0-.92.084-1.336.25C14.818 2.415 13.51 1.5 12 1.5s-2.816.917-3.437 2.25c-.415-.165-.866-.25-1.336-.25-2.11 0-3.818 1.79-3.818 4 0 .494.083.964.237 1.4-1.272.65-2.147 2.018-2.147 3.6 0 1.495.782 2.798 1.942 3.486-.02.17-.032.34-.032.514 0 2.21 1.708 4 3.818 4 .47 0 .92-.086 1.335-.25.62 1.334 1.926 2.25 3.437 2.25 1.512 0 2.818-.916 3.437-2.25.415.163.865.248 1.336.248 2.11 0 3.818-1.79 3.818-4 0-.174-.012-.344-.033-.513 1.158-.687 1.943-1.99 1.943-3.484zm-6.616-3.334l-4.334 6.5c-.145.217-.382.334-.625.334-.143 0-.288-.04-.416-.126l-.115-.094-2.415-2.415c-.293-.293-.293-.768 0-1.06s.768-.294 1.06 0l1.77 1.767 3.825-5.74c.23-.345.696-.436 1.04-.207.346.23.44.696.21 1.04z"></path></g></svg>
                        ';
                        }
                    $output.='
                    </h5>';
                    $date= explode(' ',$tweet->created_at);
                    $date = date("j F, Y", strtotime($date[0]));
                    $output.='
                    <h5 class="lg:my-3 ml-3 text-xs"> '.$date.' </h5>
                    </div>
                    <p class="text-sm">
                        ' . $tweet->body . '
                    ';
                    if($tweet->mediaurl != "") {
                        if ($tweet->mediaformat == 'png' || $tweet->mediaformat == 'jpg' || $tweet->mediaformat=='gif') {
                            $output .= '
                        <div style="width: auto;max-height: 300px;box-shadow: 4px 5px 7px 3px lightgray" class="mt-4 mb-3 lg:ml-6 lg:mr-6">
                            <img src="'.$tweet->mediaurl.'" style="width:100vw; max-height: 300px;" class="rounded"/>
                        </div>';
                        } elseif ($tweet->mediaformat == 'mp4') {
                            $output .= '
                        <div style="width: auto;max-height: 300px;box-shadow: 4px 5px 7px 3px lightgray;" class="mt-4 mb-3 lg:ml-6 lg:mr-6">
                            <video style="width:auto; max-height: 300px;border: 4px inset lightgray;" controls>
                                <source src="'.$tweet->mediaurl.'" type="video/mp4">
                            </video>
                        </div>';
                        }
                    }
                    $output.='
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


    public function returnAllRetweets(Request $request)  //tweets in the profile page
    {
        $output = '';
        $id = $request->get('id');

        $user = User::find($id);

        $retweets = DB::table('retweets as r')
            ->join('tweets as t','r.retweet_id','=','t.id')
            ->where('r.r_u_id',"=",$id)
            ->orderBy('t.created_at', 'desc')
            ->get();

        if($retweets->count()==0){
            $output.='
            <div class="rounded-lg border border-gray-300 shadow py-4 px-8 mt-3" style="word-break: break-word">
            <span class="font-bold text-center">No Recent ReTweets...</span>
            <div class="mt-2.5 font-bold text-2xl">Who to follow</div>
            <button type="submit" class="bg-blue-400 shadow rounded-lg px-4 py-2 mt-2 text-white lg:mr-1" style="width: 30vw;">
                <a href="'.route('getstarted').'">
                Explore
                </a>
            </button>
            ';
        }
        foreach ($retweets as $tweet) {
            $output .='
        <div class="rounded-lg border border-gray-300 shadow py-4 px-8 mt-3" style="word-break: break-word">';
            if($user==auth()->user()){
                $output.='
                <div class="flex mb-3">
                <svg viewBox="0 0 24 24" style="height: 18px;width: 18px" class="r-m0bqgq r-4qtqp9 r-yyyyoo r-z80fyv r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-19wmn03"><g><path d="M23.615 15.477c-.47-.47-1.23-.47-1.697 0l-1.326 1.326V7.4c0-2.178-1.772-3.95-3.95-3.95h-5.2c-.663 0-1.2.538-1.2 1.2s.537 1.2 1.2 1.2h5.2c.854 0 1.55.695 1.55 1.55v9.403l-1.326-1.326c-.47-.47-1.23-.47-1.697 0s-.47 1.23 0 1.697l3.374 3.375c.234.233.542.35.85.35s.613-.116.848-.35l3.375-3.376c.467-.47.467-1.23-.002-1.697zM12.562 18.5h-5.2c-.854 0-1.55-.695-1.55-1.55V7.547l1.326 1.326c.234.235.542.352.848.352s.614-.117.85-.352c.468-.47.468-1.23 0-1.697L5.46 3.8c-.47-.468-1.23-.468-1.697 0L.388 7.177c-.47.47-.47 1.23 0 1.697s1.23.47 1.697 0L3.41 7.547v9.403c0 2.178 1.773 3.95 3.95 3.95h5.2c.664 0 1.2-.538 1.2-1.2s-.535-1.2-1.198-1.2z"></path></g></svg>
                <div class="text-sm ml-3 font-weight-bold" style="color: #993366">
                You retweeted
                </div>
                </div>
                ';
            }else{
                $output.='
                <div class="flex mb-3">
                <svg viewBox="0 0 24 24" style="height: 18px;width: 18px" class="r-m0bqgq r-4qtqp9 r-yyyyoo r-z80fyv r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-19wmn03"><g><path d="M23.615 15.477c-.47-.47-1.23-.47-1.697 0l-1.326 1.326V7.4c0-2.178-1.772-3.95-3.95-3.95h-5.2c-.663 0-1.2.538-1.2 1.2s.537 1.2 1.2 1.2h5.2c.854 0 1.55.695 1.55 1.55v9.403l-1.326-1.326c-.47-.47-1.23-.47-1.697 0s-.47 1.23 0 1.697l3.374 3.375c.234.233.542.35.85.35s.613-.116.848-.35l3.375-3.376c.467-.47.467-1.23-.002-1.697zM12.562 18.5h-5.2c-.854 0-1.55-.695-1.55-1.55V7.547l1.326 1.326c.234.235.542.352.848.352s.614-.117.85-.352c.468-.47.468-1.23 0-1.697L5.46 3.8c-.47-.468-1.23-.468-1.697 0L.388 7.177c-.47.47-.47 1.23 0 1.697s1.23.47 1.697 0L3.41 7.547v9.403c0 2.178 1.773 3.95 3.95 3.95h5.2c.664 0 1.2-.538 1.2-1.2s-.535-1.2-1.198-1.2z"></path></g></svg>
                <div class="text-sm ml-3 font-weight-bold" style="color: #993366">
                '.$user->name.' retweeted
                </div>
                </div>
                ';
            }
            $output.='<div class="flex p-2">
                <div class="flex-shrink-0 mr-2">';
            $t_u_id= $tweet->user_id;
            $u= User::find($t_u_id);
            $output.='<a href="'.$u->id.'" style="text-decoration:none;color:black"><img src="' . $u->url . '" alt="" class="rounded-full mr-2" style="height: 50px;width: 50px;"></a>
                </div>

                <div class="flex flex-col">
                    <div class="flex">
                    <h5 class="lg:my-2 font-bold flex">';
            $output .= '
                        <a href="'.$u->id.'" style="text-decoration:none;color:black">
                         ' . $u->name . '
                         </a>
                         ';
            if($u->isVip){
                $output.='
                        <svg viewBox="0 0 24 24" style="height:18px;width:18px" fill="#d24dff" aria-label="Verified account" class="r-13gxpu9 r-4qtqp9 r-yyyyoo r-1xvli5t r-9cviqr r-dnmrzs r-bnwqim r-1plcrui r-lrvibr"><g><path d="M22.5 12.5c0-1.58-.875-2.95-2.148-3.6.154-.435.238-.905.238-1.4 0-2.21-1.71-3.998-3.818-3.998-.47 0-.92.084-1.336.25C14.818 2.415 13.51 1.5 12 1.5s-2.816.917-3.437 2.25c-.415-.165-.866-.25-1.336-.25-2.11 0-3.818 1.79-3.818 4 0 .494.083.964.237 1.4-1.272.65-2.147 2.018-2.147 3.6 0 1.495.782 2.798 1.942 3.486-.02.17-.032.34-.032.514 0 2.21 1.708 4 3.818 4 .47 0 .92-.086 1.335-.25.62 1.334 1.926 2.25 3.437 2.25 1.512 0 2.818-.916 3.437-2.25.415.163.865.248 1.336.248 2.11 0 3.818-1.79 3.818-4 0-.174-.012-.344-.033-.513 1.158-.687 1.943-1.99 1.943-3.484zm-6.616-3.334l-4.334 6.5c-.145.217-.382.334-.625.334-.143 0-.288-.04-.416-.126l-.115-.094-2.415-2.415c-.293-.293-.293-.768 0-1.06s.768-.294 1.06 0l1.77 1.767 3.825-5.74c.23-.345.696-.436 1.04-.207.346.23.44.696.21 1.04z"></path></g></svg>
                        ';
            }
            $output.='
                    </h5>';
            $date= explode(' ',$tweet->created_at);
            $date = date("j F, Y", strtotime($date[0]));
            $output.='
                    <h5 class="lg:my-3 ml-3 text-xs"> '.$date.' </h5>
                    </div>
                    <p class="text-sm">
                        ' . $tweet->body . '
                    ';
            if($tweet->mediaurl != "") {
                if ($tweet->mediaformat == 'png' || $tweet->mediaformat == 'jpg' || $tweet->mediaformat=='gif') {
                    $output .= '
                        <div style="width: auto;max-height: 300px;box-shadow: 4px 5px 7px 3px lightgray" class="mt-4 mb-3 lg:ml-6 lg:mr-6">
                            <img src="'.$tweet->mediaurl.'" style="width:100vw; max-height: 300px;" class="rounded"/>
                        </div>';
                } elseif ($tweet->mediaformat == 'mp4') {
                    $output .= '
                        <div style="width: auto;max-height: 300px;box-shadow: 4px 5px 7px 3px lightgray;" class="mt-4 mb-3 lg:ml-6 lg:mr-6">
                            <video style="width:auto; max-height: 300px;border: 4px inset lightgray;" controls>
                                <source src="'.$tweet->mediaurl.'" type="video/mp4">
                            </video>
                        </div>';
                }
            }
            $output.='
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

        $user = User::findOrFail($id);
        $tweets= $user->tweets()->where('mediaformat','!=','')->where('mediaurl','!=','')->get();

        if($tweets->count()==0){
            $output.='
            <div class="rounded-lg border border-gray-300 shadow py-4 px-8 mt-3" style="word-break: break-word">
            <span class="font-bold text-center">Nothing here to display</span>
            <div class="mt-2.5 font-bold text-2xl">Who to follow</div>
            <a href="'.route('getstarted').'">
            <button type="submit" class="bg-blue-400 shadow rounded-lg px-4 py-2 mt-2 text-white lg:mr-1" style="width: 30vw;">
                Explore
            </button>
            </a>
            ';
        }
        foreach ($tweets as $tweet) {
            $output .= '
        <div class="rounded-lg border border-gray-300 shadow py-4 px-8 mt-3" style="word-break: break-word">
        <div class="flex p-2">
            <div class="flex-shrink-0 mr-2">';
            $user_of_tweet= User::findOrFail($tweet->user_id);
            $output.='
            <img src="'.$user_of_tweet->url.'" alt="" class="rounded-full mr-2" style="height: 50px;width: 50px;">
            </div>

            <div class="flex flex-col">
                <div class="flex">
                        <h5 class="lg:my-2 flex font-bold">
                        <a href="'.$tweet->user_id.'" style="text-decoration:none;color:black">
                            ' . $user_of_tweet->name . '
                         </a>';
                        if($user_of_tweet->isVip){
                            $output.='
                                        <svg viewBox="0 0 24 24" style="height:18px;width:18px" fill="#d24dff" aria-label="Verified account" class="r-13gxpu9 r-4qtqp9 r-yyyyoo r-1xvli5t r-9cviqr r-dnmrzs r-bnwqim r-1plcrui r-lrvibr"><g><path d="M22.5 12.5c0-1.58-.875-2.95-2.148-3.6.154-.435.238-.905.238-1.4 0-2.21-1.71-3.998-3.818-3.998-.47 0-.92.084-1.336.25C14.818 2.415 13.51 1.5 12 1.5s-2.816.917-3.437 2.25c-.415-.165-.866-.25-1.336-.25-2.11 0-3.818 1.79-3.818 4 0 .494.083.964.237 1.4-1.272.65-2.147 2.018-2.147 3.6 0 1.495.782 2.798 1.942 3.486-.02.17-.032.34-.032.514 0 2.21 1.708 4 3.818 4 .47 0 .92-.086 1.335-.25.62 1.334 1.926 2.25 3.437 2.25 1.512 0 2.818-.916 3.437-2.25.415.163.865.248 1.336.248 2.11 0 3.818-1.79 3.818-4 0-.174-.012-.344-.033-.513 1.158-.687 1.943-1.99 1.943-3.484zm-6.616-3.334l-4.334 6.5c-.145.217-.382.334-.625.334-.143 0-.288-.04-.416-.126l-.115-.094-2.415-2.415c-.293-.293-.293-.768 0-1.06s.768-.294 1.06 0l1.77 1.767 3.825-5.74c.23-.345.696-.436 1.04-.207.346.23.44.696.21 1.04z"></path></g></svg>
                        ';
                        }
                        $output.='
                        </h5>
                        <h5 class="lg:my-3 ml-3 text-xs"> '.$tweet->created_at->format('d M Y').' </h5>
                </div>
                <p class="text-sm">
                 '. $tweet->body . '
                 ';
                if($tweet->mediaurl != "") {
                    if ($tweet->mediaformat == 'png' || $tweet->mediaformat == 'jpg' || $tweet->mediaformat=='gif') {
                        $output .= '
                                <div style="width: auto;max-height: 300px;box-shadow: 4px 5px 7px 3px lightgray" class="mt-4 mb-3 lg:ml-6 lg:mr-6">
                                    <img src="'.$tweet->mediaurl.'" style="width:100vw; max-height: 300px;" class="rounded"/>
                                </div>';
                    } elseif ($tweet->mediaformat == 'mp4') {
                        $output .= '
                                <div style="width: auto;max-height: 300px;box-shadow: 4px 5px 7px 3px lightgray;" class="mt-4 mb-3 lg:ml-6 lg:mr-6">
                                    <video style="width:auto; max-height: 300px;border: 4px inset lightgray;" controls>
                                        <source src="'.$tweet->mediaurl.'" type="video/mp4">
                                    </video>
                                </div>';
                    }
                }
                $output.='
                </p>
            </div>
         </div>';

          $output.='<div class="lg:ml-7 mt-3 flex justify-content-around" id="refLikes'.$tweet->id.'">';
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
            <div onclick="tweetUpdate(' . $tweet->id . ')" data-toggle="tooltip" title="Undo Retweet"><svg viewBox="0 0 24 24" fill="red" stroke="light-green" style="height: 18px;width: 18px;" class="r-m0bqgq r-4qtqp9 r-yyyyoo r-z80fyv r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-19wmn03"><g><path d="M23.615 15.477c-.47-.47-1.23-.47-1.697 0l-1.326 1.326V7.4c0-2.178-1.772-3.95-3.95-3.95h-5.2c-.663 0-1.2.538-1.2 1.2s.537 1.2 1.2 1.2h5.2c.854 0 1.55.695 1.55 1.55v9.403l-1.326-1.326c-.47-.47-1.23-.47-1.697 0s-.47 1.23 0 1.697l3.374 3.375c.234.233.542.35.85.35s.613-.116.848-.35l3.375-3.376c.467-.47.467-1.23-.002-1.697zM12.562 18.5h-5.2c-.854 0-1.55-.695-1.55-1.55V7.547l1.326 1.326c.234.235.542.352.848.352s.614-.117.85-.352c.468-.47.468-1.23 0-1.697L5.46 3.8c-.47-.468-1.23-.468-1.697 0L.388 7.177c-.47.47-.47 1.23 0 1.697s1.23.47 1.697 0L3.41 7.547v9.403c0 2.178 1.773 3.95 3.95 3.95h5.2c.664 0 1.2-.538 1.2-1.2s-.535-1.2-1.198-1.2z"></path></g></svg></div>
            <span class="pb-2 ml-2" style="color: red">' . $tweet->retweets()->count() . '</span>';
            }else{
                $output.='
            <div onclick="tweetUpdate(' . $tweet->id . ')"  data-toggle="tooltip"  title="Retweet"><svg viewBox="0 0 24 24" fill="" style="height: 18px;width: 18px;" class="r-m0bqgq r-4qtqp9 r-yyyyoo r-z80fyv r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-19wmn03"><g><path d="M23.615 15.477c-.47-.47-1.23-.47-1.697 0l-1.326 1.326V7.4c0-2.178-1.772-3.95-3.95-3.95h-5.2c-.663 0-1.2.538-1.2 1.2s.537 1.2 1.2 1.2h5.2c.854 0 1.55.695 1.55 1.55v9.403l-1.326-1.326c-.47-.47-1.23-.47-1.697 0s-.47 1.23 0 1.697l3.374 3.375c.234.233.542.35.85.35s.613-.116.848-.35l3.375-3.376c.467-.47.467-1.23-.002-1.697zM12.562 18.5h-5.2c-.854 0-1.55-.695-1.55-1.55V7.547l1.326 1.326c.234.235.542.352.848.352s.614-.117.85-.352c.468-.47.468-1.23 0-1.697L5.46 3.8c-.47-.468-1.23-.468-1.697 0L.388 7.177c-.47.47-.47 1.23 0 1.697s1.23.47 1.697 0L3.41 7.547v9.403c0 2.178 1.773 3.95 3.95 3.95h5.2c.664 0 1.2-.538 1.2-1.2s-.535-1.2-1.198-1.2z"></path></g></svg></div>
            <span class="pb-2 ml-2" style="color: black">' . $tweet->retweets()->count() . '</span>
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



    public function returnAllLikes(Request $request)  //likes in the profile page
    {

        $output= '';
        $id= $request->get('id');
        $query= DB::select("SELECT * FROM
        likes JOIN tweets
        ON likes.user_id=$id AND tweets.id=likes.tweet_id
        WHERE likes.isliked=true;");

        if(count($query)==0){
            $output.='
            <div class="rounded-lg border border-gray-300 shadow py-4 px-8 mt-3" style="word-break: break-word">
            <span class="font-bold text-center">No Recent Likes...</span>
            <div class="mt-2.5 font-bold text-2xl">Who to follow</div>
            <a href="'.route('getstarted').'">
            <button type="submit" class="bg-blue-400 shadow rounded-lg px-4 py-2 mt-2 text-white lg:mr-1" style="width: 30vw;">
                Explore
            </button>
            </a>
            ';
        }
        foreach ($query as $query) {
            $tweet_id= $query->tweet_id;
            $tweet= Tweet::findOrFail($tweet_id);
            $output .= '
        <div class="rounded-lg border border-gray-300 shadow py-4 px-8 mt-3" style="word-break: break-word">';
            $output.='
        <div class="flex p-2">
            <div class="flex-shrink-0 mr-2">
            <a href="'.$tweet->user->id.'" style="text-decoration:none;color:black">
                <img src="'.$tweet->user->url.'" alt="" class="rounded-full mr-2" style="height: 50px;width: 50px;">
             </a>
            </div>

            <div class="flex flex-col">
                <div class="flex">
                        <h5 class="lg:my-2 flex font-bold">
                        <a href="'.$tweet->user->id.'" style="text-decoration:none;color:black">
                            ' . $tweet->user->name . '
                         </a>';
                        if($tweet->user->isVip){
                            $output.='
                                    <svg viewBox="0 0 24 24" style="height:18px;width:18px" fill="#d24dff" aria-label="Verified account" class="r-13gxpu9 r-4qtqp9 r-yyyyoo r-1xvli5t r-9cviqr r-dnmrzs r-bnwqim r-1plcrui r-lrvibr"><g><path d="M22.5 12.5c0-1.58-.875-2.95-2.148-3.6.154-.435.238-.905.238-1.4 0-2.21-1.71-3.998-3.818-3.998-.47 0-.92.084-1.336.25C14.818 2.415 13.51 1.5 12 1.5s-2.816.917-3.437 2.25c-.415-.165-.866-.25-1.336-.25-2.11 0-3.818 1.79-3.818 4 0 .494.083.964.237 1.4-1.272.65-2.147 2.018-2.147 3.6 0 1.495.782 2.798 1.942 3.486-.02.17-.032.34-.032.514 0 2.21 1.708 4 3.818 4 .47 0 .92-.086 1.335-.25.62 1.334 1.926 2.25 3.437 2.25 1.512 0 2.818-.916 3.437-2.25.415.163.865.248 1.336.248 2.11 0 3.818-1.79 3.818-4 0-.174-.012-.344-.033-.513 1.158-.687 1.943-1.99 1.943-3.484zm-6.616-3.334l-4.334 6.5c-.145.217-.382.334-.625.334-.143 0-.288-.04-.416-.126l-.115-.094-2.415-2.415c-.293-.293-.293-.768 0-1.06s.768-.294 1.06 0l1.77 1.767 3.825-5.74c.23-.345.696-.436 1.04-.207.346.23.44.696.21 1.04z"></path></g></svg>
                        ';
                        }
                        $output.='
                        </h5>
                        <h5 class="lg:my-3 ml-3 text-xs"> '.$tweet->created_at->format('d M Y').' </h5>
                </div>
                <p class="text-sm">
                ' . $tweet->body . '
                ';
                if($tweet->mediaurl != "") {
                    if ($tweet->mediaformat == 'png' || $tweet->mediaformat == 'jpg'  || $tweet->mediaformat=='gif') {
                        $output .= '
                            <div style="width: auto;max-height: 300px;box-shadow: 4px 5px 7px 3px lightgray" class="mt-4 mb-3 lg:ml-6 lg:mr-6">
                                <img src="'.$tweet->mediaurl.'" style="width:100vw; max-height: 300px;" class="rounded"/>
                            </div>';
                    } elseif ($tweet->mediaformat == 'mp4') {
                        $output .= '
                            <div style="width: auto;max-height: 300px;box-shadow: 4px 5px 7px 3px lightgray;" class="mt-4 mb-3 lg:ml-6 lg:mr-6">
                                <video style="width:auto; max-height: 300px;border: 4px inset lightgray;" controls>
                                    <source src="'.$tweet->mediaurl.'" type="video/mp4">
                                </video>
                            </div>';
                    }
                }
                $output.='
                </p>
            </div>
        </div>';



            $output.='<div class="lg:ml-7 mt-3 flex justify-content-around" id="refLikes'.$tweet->id.'">';
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
            <div onclick="tweetUpdate(' . $tweet->id . ')" data-toggle="tooltip" title="Undo Retweet"><svg viewBox="0 0 24 24" fill="red" stroke="light-green" style="height: 18px;width: 18px;" class="r-m0bqgq r-4qtqp9 r-yyyyoo r-z80fyv r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-19wmn03"><g><path d="M23.615 15.477c-.47-.47-1.23-.47-1.697 0l-1.326 1.326V7.4c0-2.178-1.772-3.95-3.95-3.95h-5.2c-.663 0-1.2.538-1.2 1.2s.537 1.2 1.2 1.2h5.2c.854 0 1.55.695 1.55 1.55v9.403l-1.326-1.326c-.47-.47-1.23-.47-1.697 0s-.47 1.23 0 1.697l3.374 3.375c.234.233.542.35.85.35s.613-.116.848-.35l3.375-3.376c.467-.47.467-1.23-.002-1.697zM12.562 18.5h-5.2c-.854 0-1.55-.695-1.55-1.55V7.547l1.326 1.326c.234.235.542.352.848.352s.614-.117.85-.352c.468-.47.468-1.23 0-1.697L5.46 3.8c-.47-.468-1.23-.468-1.697 0L.388 7.177c-.47.47-.47 1.23 0 1.697s1.23.47 1.697 0L3.41 7.547v9.403c0 2.178 1.773 3.95 3.95 3.95h5.2c.664 0 1.2-.538 1.2-1.2s-.535-1.2-1.198-1.2z"></path></g></svg></div>
            <span class="pb-2 ml-2" style="color: red">' . $tweet->retweets()->count() . '</span>';
            }else{
                $output.='
            <div onclick="tweetUpdate(' . $tweet->id . ')"  data-toggle="tooltip"  title="Retweet"><svg viewBox="0 0 24 24" fill="" style="height: 18px;width: 18px;" class="r-m0bqgq r-4qtqp9 r-yyyyoo r-z80fyv r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-19wmn03"><g><path d="M23.615 15.477c-.47-.47-1.23-.47-1.697 0l-1.326 1.326V7.4c0-2.178-1.772-3.95-3.95-3.95h-5.2c-.663 0-1.2.538-1.2 1.2s.537 1.2 1.2 1.2h5.2c.854 0 1.55.695 1.55 1.55v9.403l-1.326-1.326c-.47-.47-1.23-.47-1.697 0s-.47 1.23 0 1.697l3.374 3.375c.234.233.542.35.85.35s.613-.116.848-.35l3.375-3.376c.467-.47.467-1.23-.002-1.697zM12.562 18.5h-5.2c-.854 0-1.55-.695-1.55-1.55V7.547l1.326 1.326c.234.235.542.352.848.352s.614-.117.85-.352c.468-.47.468-1.23 0-1.697L5.46 3.8c-.47-.468-1.23-.468-1.697 0L.388 7.177c-.47.47-.47 1.23 0 1.697s1.23.47 1.697 0L3.41 7.547v9.403c0 2.178 1.773 3.95 3.95 3.95h5.2c.664 0 1.2-.538 1.2-1.2s-.535-1.2-1.198-1.2z"></path></g></svg></div>
            <span class="pb-2 ml-2" style="color: black">' . $tweet->retweets()->count() . '</span>
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
}
