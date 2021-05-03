<?php


namespace App;


use Illuminate\Support\Facades\DB;

trait Likeable
{

    public function ifLikedBy(User $user,Tweet $tweet)
    {
        $likeOrNot = DB::table('likes')->where('tweet_id', $tweet->id)
            ->where('user_id',$user->id)
            ->where('isliked', true)->get();
        if(count($likeOrNot)) return 1;
        else return 0;
//        return !!$user->likes()->where('likes.tweet_id', $tweet->id)
//            ->where('likes.isLiked', true)->count();
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
