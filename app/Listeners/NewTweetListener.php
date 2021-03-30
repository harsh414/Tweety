<?php

namespace App\Listeners;

use App\Notifications\NewTweetNotification;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class NewTweetListener
{

    public function handle($event)
    {
        $auth_user= $event->user;
        // jo auth()->user() ko follow kar rhe unke pass notification jaega ki naya tweet kia
        $users_id= DB::table('follows')->where('following_user_id','=',$auth_user->id)->pluck('user_id');
        $who_to_notify_about_newtweet= User::whereIn('id',$users_id)->get();
        Notification::send($who_to_notify_about_newtweet, new NewTweetNotification($event->tweet,$auth_user));




    }
}
