<?php

namespace App;
use App\User;

use Illuminate\Database\Eloquent\Model;

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


}
