<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

//Tweets routes
Route::group(['middleware'=>['auth']],function(){
    Route::get('/getstarted', 'TweetsController@getstarted')->name('getstarted');
    Route::get('/getstarted/action','ProfileController@action')->name('livesearch.action');
    Route::get('/tweets', 'TweetsController@index')->name('tweets');
    Route::get('/tweets/{id}', 'TweetsController@showTweet')->name('tweetshow');

    Route::post('tweetNew','TweetsController@store')->name('tweet.store');
    Route::post('tweets/likeOrDislike', 'TweetsController@likeOrDislike');
    Route::post('tweets/retweet', 'TweetsController@retweet');
    Route::post('tweets/{id}/likeOrDislike', 'TweetsController@likeOrDislike');
    Route::post('tweets/{id}/retweet', 'TweetsController@retweet');


    //Profile
    Route::get('profile/{user}','ProfileController@index')->name('profile');
    Route::post('profile/{id}/likeOrDislike', 'TweetsController@likeOrDislike');
    Route::post('profile/{id}/retweet', 'TweetsController@retweet');
    Route::post('profile/{id}/status', 'TweetsController@status');


    //return tweets media and likes of user
    Route::post('/profile/{id}/tweets','ProfileController@returnAllTweets')->name('show');
    Route::post('/profile/{id}/media','ProfileController@returnAllMedia');
    Route::post('/profile/{id}/likes','ProfileController@returnAllLikes');

    Route::put('/profile/{id}','ProfileController@update')->name('updateProfile'); //update Profile


    //follow unfollow from different pages of tweety
    Route::post('/profile/{id}/follow','ProfileController@follow')->name('follow');
    Route::post('/profile/{id}/unfollow','ProfileController@unfollow')->name('unfollow');
    Route::post('/getstarted/{id}/follow','ProfileController@follow');
    Route::post('/getstarted/{id}/unfollow','ProfileController@unfollow');

});



//Notifications part
Route::get('/notifications','HomeController@notifications')->name('notifications');


