<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetweetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retweets', function (Blueprint $table) {
//            r_u_id is retweet user_id
            $table->primary(['r_u_id','retweet_id']);
            $table->foreignId('r_u_id');
            $table->foreignId('retweet_id');
            $table->timestamps();

            $table->foreign('r_u_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('retweet_id')
                ->references('id')->on('tweets')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retweets');
    }
}
