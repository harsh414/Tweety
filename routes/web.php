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
    Route::get('tweets/feed', function (){
        return view('try');
    });
    Route::post('tweetNew','TweetsController@store')->name('tweet.store');
    Route::post('tweets/likeOrDislike', 'TweetsController@likeOrDislike');

});


//Profile
Route::get('profile/{user}','ProfileController@index')->name('profile');

//return tweets media and likes of user
Route::post('/profile/{id}/tweets','ProfileController@returnAllTweets')->name('show');
Route::post('/profile/{id}/likes','ProfileController@returnAllLikes')->name('likes');



Route::put('/profile/{id}','ProfileController@update')->name('updateProfile'); //update Profile
Route::post('/profile/{id}/follow','ProfileController@follow')->name('follow'); //update Profile
Route::post('/profile/{id}/unfollow','ProfileController@unfollow')->name('unfollow'); //update Profile


