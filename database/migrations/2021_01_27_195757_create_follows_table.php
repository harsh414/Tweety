<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowsTable extends Migration
{

    public function up()
    {
//        pivot table
//                    for manyToMany following
        Schema::create('follows', function (Blueprint $table) {
            $table->primary(['user_id','following_user_id']);
            $table->unsignedBigInteger('user_id')->references('id')
            ->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('following_user_id')->references('id')
                ->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('follows');
    }
}
