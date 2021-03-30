<?php

namespace App\Listeners;

use App\Notifications\NewFollowerNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class FollowListener
{

    public function handle($event)
    {
        Notification::send($event->user, new NewFollowerNotification($event->authuser));
    }
}
