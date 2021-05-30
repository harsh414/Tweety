<?php

namespace App\Listeners;

use App\Notifications\NewRegistrationNotification;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class NewUserRegisteredListener
{

    public function handle($event)
    {
       //  yha pe add hoga kisko notify karna hai
        // we want to notify new registered user that registration is successfull
        $user= User::find($event->user->id);
        Notification::send($user,new NewRegistrationNotification($event->user));
    }
}
