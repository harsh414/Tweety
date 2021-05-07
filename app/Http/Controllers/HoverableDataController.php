<?php

namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HoverableDataController extends Controller
{
    public function userdata(Request $request)
    {
        $output="";
        $u_id= $request->get('id');
        $user= User::findOrFail($u_id);
        if(auth()->user()->isFollowing($user)){
            $s1="Unfollow";
            $s2="Following";
        }else{
            $s1="Follow";
            $s2="Follow";
        }
        $path = $request->get('location');

        $following =DB::table('follows')->where('following_user_id','=',$user->id)
            ->get()->count();
        $output.='
        <div class="flex justify-between">
            <div>
                <img src="'.$user->url.'" style="height: 50px;width: 50px;" alt="" class="rounded-full mr-2">
            </div>';
            if(auth()->user()->isFollowing($user)) {
                $output .= '
            <form action="'. 'tweets/' . $user->id . '/unfollow' . '" method="POST">';
            }else {
                $output .= '
            <form action="' . 'tweets/' . $user->id . '/follow' . '" method="POST">';
            }
            $output.='
            '.@csrf_field().'
            '.method_field("POST").'
            <button type="submit" title="'.$s1.'"
                        value="'.$s2.'" id="follow"
                        class="hover:bg-black rounded-lg rounded-lg p-2 font-bold text-blue-400"
                        style="border:2px solid lightskyblue;outline: none; border-radius: 20px !important;">
                    '.$s2.'
                </button>
             </form>
        </div>
        <div class="text-sm font-bold mt-2 pl-1 pr-1">
            <a href="profile/'.$user->id.'" style="text-decoration:none;color:black;">
            <u>'.$user->name.'</u>
            </a>
        </div>
        <div class="text-sm p-1 mt-2">
            '.$user->bio.'
        </div>
        <div class="flex text-sm mt-2 justify-items-start">
            <div><span class="text-sm font-bold">'.$user->following()->count(). '</span>&nbsp;Following</div>
            <div class="ml-2"><span class="text-sm font-bold">'.$following. '</span>&nbsp;Following</div>
        </div>
        ';

        $data= array(
            'data'=>$output,
        );
        echo json_encode($data);

    }
}
