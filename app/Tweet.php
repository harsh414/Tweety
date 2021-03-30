<?php

namespace App;
use App\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Tweet extends Model
{
    use Likeable;
//    A tweet belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes() // return all likes of the tweet
    {
        return $this->hasMany(Like::class);
    }

    public function num_likes($tweet)
    {
        return $tweet->likes()->where('isLiked',true);
    }

    public function retweets() //return all retweets of current tweet
    {
        return $this->hasMany(Retweet::class,'retweet_id');
    }

    public function isRetweeted(User $user,Tweet $tweet)  //for profile tweets of auth user
    {
        return !!$user->retweets()->where('retweet_id','=',$tweet->id)->count();
    }





}
