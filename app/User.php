<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Tweet;

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

    public function follow(User $user)
    {
        $this->following()->save($user);
    }

    public function following()
    {
        return $this->
        belongsToMany(User::class,'follows','user_id','following_user_id');
    }


//    public function AllTweets()
//    {
//        $user_follows = $this->following
//                        ->pluck('id');
//        $user_follows.push($this->id);
//        return Tweet::whereIn('user_id',$user_follows)->get();
//    }

}
