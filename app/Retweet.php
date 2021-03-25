<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Support\Facades\DB;

class Retweet extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
