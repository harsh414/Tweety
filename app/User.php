<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Tweet;
use App\Retweet;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tweets()
    {
        return $this->hasMany(Tweet::class);
    }

    public function retweets()
    {
        return $this->hasMany(Retweet::class,'r_u_id');
    }


    public function follow(User $user)  // used to follow a user
    {
        $this->following()->attach($user);
    }

    public function unfollow(User $user)  // used to unfollow a user
    {
        $this->following()->detach($user);
    }

    public function following()
    {
        return $this->
        belongsToMany(User::class,'follows','user_id','following_user_id');
    }



    public function isFollowing(User $user)
    {
        // !! represents the same as (bool)  typecast to bool
        return !! $this->following()->where('following_user_id',$user->id)->count();
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function hadRetweeted(Tweet $tweet)
    {
        $t_id= $tweet->id;
        return !!$this->retweets()->where('retweet_id',$t_id)->count();
    }

    public function allNotifications()
    {
        return $this->hasMany(Notification::class,'notifiable_id');
    }



}
