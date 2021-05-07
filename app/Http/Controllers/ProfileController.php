<?php

namespace App\Http\Controllers;

use App\Events\FollowEvent;
use App\Tweet;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    use returnable; //contains functions for returning allTweets media and likes of a profile

    public function index($id=null)
    {
        //        <!-- Whom to follow section -->
        $u_id= $id;
        $global_array= array(); //will contain id of who_to_follow users
        $tweetIDs = auth()->user()->following()->pluck('id');
        $tweetIDs->push(auth()->user()->id);
        $users_id= auth()->user()->following()->pluck('id'); //id of users i follow

        foreach ($users_id as $id){
            $userr= User::findOrFail($id);
            $id_array= $userr->following->pluck('id');
            foreach ($id_array as $i){
                array_push($global_array,$i);
            }
        }
        $global_array= array_unique($global_array);
        if (in_array(auth()->user()->id, $global_array)){
            unset($global_array[array_search(auth()->user()->id,$global_array)]);
        }
//        dd($global_array);
        //below is selecting users from global_array but discarding already following
        $w_t_f= DB::table('users')->whereIn('id',$global_array)->
        whereNotIn('id',$users_id);
        $w_t_f= $w_t_f->inRandomOrder()->take('5')->get();
        if($w_t_f->count() < 4)
        {
            $w_t_f= User::whereNotIn('id',$users_id)->where('id','!=',auth()->user()->id)->take(5)->get();
        }

//        profile kiski hai

        $profile_user= User::findOrFail($u_id);
        $followers_of_this_profile = DB::table('follows')->where('following_user_id',$profile_user->id)->get();
        $count_followers= count( $followers_of_this_profile );



        $status_user= User::findOrFail($u_id);
        $isFoll = auth()->user()->isFollowing($status_user);
        (bool)$isFoll == 'true' ? $status="Following" : $status="Follow";
        return view('profile.index',[
            'profile'=>$profile_user,
            'who_to_follow'=>$w_t_f,
            'followers'=>$count_followers,
            'status'=>$status,
        ]);
    }



//    for updating the profile
    public function update(Request $request,$id)
    {
        $request->validate([
            'name'=>'required',
        ]);
        $user= User::findOrFail($id);
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

    public function follow($url='',$id)
    {
        $user= User::findOrFail($id);
        auth()->user()->follow($user);
        event(new FollowEvent(auth()->user(),$user)); // if a follow b than b will
        // receive notificaion that a followed him
        return back();
    }

    public function unfollow($url='',$id)
    {
        $user= User::findOrFail($id);
        auth()->user()->unfollow($user);
        return back();
    }

    public function action(Request $request)
    {
        if($request->ajax())
        {
            $query= $request->get('query');
            if($query!=''){
                $users= User::where('name','like','%'.$query.'%')
                                ->orWhere('email','like','%'.$query.'%')
                                ->get();
            }else{
                $users = User::inRandomOrder()->take(30)->get();
            }

            $output='';
            foreach ($users as $u) {
                $output .='
            <div class="text-center flex justify-content-around mt-6 lg:border shadow-inner p-4">
            <img src="' . $u->url . '" alt="" class="shadow pr-1 rounded-full lg:ml-2"
                 style="border:2px solid gray; height: 60px; width: 60px;border-right-color: white; border-bottom-color: white ">


            <div class="flex flex-col ml-3">
                <a href="profile/'.$u->id.'" style="text-decoration: none">
                <div class="font-weight-bold" style="color: gray;cursor: pointer">
                '.$u->name.'</div>
                </a>

                <div class="ml-2"><span class="font-weight-bold">'.count($u->tweets).'</span> Tweets
                    &nbsp; &nbsp;<span class="font-weight-bold">'.count($u->retweets).'</span> Retweets </div>
            </div>

            <div class="ml-6">';
                if(auth()->user()->isFollowing($u)){
                    $s1= "getstarted/".$u->id."/unfollow";
                    $s2="Following";
                    $title= "Unfollow";
                }else{
                    $s1= "getstarted/".$u->id."/follow";
                    $s2="Follow";
                    $title= "Follow";
                }
            $output.='
            <form action="'.$s1.'" method="POST">
                '.@csrf_field().'
                '.method_field("POST").'
                <input type="text" id="whom_to_follow_id" value="{{$user->id}}" style="display: none">
                <button type="submit" title=""
                        value="'.$s2.'" id="follow"
                        class="hover:bg-black rounded-lg rounded-lg p-2 font-bold text-blue-400"
                        style="border:2px solid lightskyblue;outline: none; border-radius: 20px !important;">
                        '.$s2.'
                </button>
            </form>
        </div>
         </div>
            ';
            }


            $num_rows= $users->count();
            $data= array(
                'table_data'=>$output,
            );
            echo json_encode($data);
        }
    }
}
