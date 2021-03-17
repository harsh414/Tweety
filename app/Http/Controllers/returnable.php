<?php


namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;

trait returnable
{

    public function returnAllTweets(Request $request)
    {
        $output = '';
        $id = $request->get('id');

        $user = User::find($id);
        $tweets = $user->tweets;
        foreach ($tweets as $tweet) {
            $output .= '
        <div class="rounded-lg border border-gray-300 shadow py-4 px-8 mt-3">
        <div class="flex p-2">
            <div class="flex-shrink-0 mr-2">
                <img src="' . $user->url . '" alt="" class="rounded-full mr-2" style="height: 50px;width: 50px;">
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
