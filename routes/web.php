<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

//Tweets routes
Route::group(['middleware'=>['auth']],function(){
    Route::get('tweets', 'TweetsController@index')->name('tweets');
    Route::post('tweetNew','TweetsController@store')->name('tweet.store');

});


//Profile
Route::get('profile/{user}','ProfileController@index')->name('profile');
Route::post('/profile/{id}/tweets','ProfileController@returnAllTweets')->name('show');

Route::put('/profile/{id}','ProfileController@update')->name('updateProfile'); //update Profile
Route::get('tweets/try',function (){
    return view('try');
});
Route::post('/tweets/try',function (){
    Storage::delete("profileimages/W3KfxCpkrinCG3dLT86wAQxDFCTbqNRrtlXu5UDJ.png");
});
