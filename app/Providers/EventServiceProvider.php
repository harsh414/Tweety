<?php

namespace App\Providers;

use App\Events\FollowEvent;
use App\Events\NewTweetEvent;
use App\Events\NewUserRegisteredEvent;
use App\Listeners\FollowListener;
use App\Listeners\NewTweetListener;
use App\Listeners\NewUserRegisteredListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        NewUserRegisteredEvent::class => [
            NewUserRegisteredListener::class,
        ],

        NewTweetEvent::class=>[
            NewTweetListener::class,
        ],

        FollowEvent::class=>[
            FollowListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
