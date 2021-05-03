<?php


namespace App;


use Illuminate\Support\Facades\DB;

trait Likeable
{

    public function ifLikedBy(User $user,Tweet $tweet)
    {
        return !!$user->likes()->where('likes.tweet_id', $tweet->id)
            ->where('likes.isliked', true)->count();
    }

    public function like($user = null,  $liked = true)   // for liking a tweet
    {
        $this->likes()->updateOrCreate(
            [
                'user_id' => $user ? $user->id : auth()->id,
            ],[
                'isLiked' => $liked,
            ]
        );
    }

    public function dislike($user = null)
    {
        return $this->like($user, false);
    }
}
