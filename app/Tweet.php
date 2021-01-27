<?php

namespace App;
use App\User;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{

//    A tweet belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
