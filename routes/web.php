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
    Route::post('/getstarted/limitUsers', 'TweetsController@limitUsers')->name('limitUsers');
    Route::get('/getstarted/action','ProfileController@action')->name('livesearch.action');
    Route::get('/tweets', 'TweetsController@index')->name('tweets');
    Route::get('/tweets/{id}', 'TweetsController@showTweet')->name('tweetshow');

    Route::post('/tweetNew','TweetsController@store')->name('tweet.store');
    Route::post('/tweets/likeOrDislike', 'TweetsController@likeOrDislike');
    Route::post('/tweets/retweet', 'TweetsController@retweet');
    Route::post('/tweets/{id}/likeOrDislike', 'TweetsController@likeOrDislike');
    Route::post('/tweets/{id}/retweet', 'TweetsController@retweet');
    Route::post('/{tweets}/hoverUser', 'HoverableDataController@userdata')->name('hoverdata');
//    Route::post('{tweets}/{id}/hoverUser', 'HoverableDataController@userdata')->name('hoverdata');



    //Profile
    Route::get('/profile/{id}','ProfileController@index')->name('profile');
    Route::post('/profile/{id}/likeOrDislike', 'TweetsController@likeOrDislike');
    Route::post('/profile/{id}/retweet', 'TweetsController@retweet');
    Route::post('/profile/{id}/status', 'TweetsController@status');


    //return tweets retweets media and likes of user
    Route::post('/profile/{id}/tweets','ProfileController@returnAllTweets')->name('show');
    Route::post('/profile/{id}/getRetweets','ProfileController@returnAllRetweets');
    Route::post('/profile/{id}/media','ProfileController@returnAllMedia');
    Route::post('/profile/{id}/likes','ProfileController@returnAllLikes');

    Route::put('/profile/{id}','ProfileController@update')->name('updateProfile'); //update Profile


    //follow unfollow from different pages of tweety
//    {url} is a wildcard that contains the url request from where request is made
    Route::post('/{url}/{id}/follow','ProfileController@follow')->name('follow');
    Route::post('/{url}/{id}/unfollow','ProfileController@unfollow')->name('unfollow');


});



//Notifications part
Route::get('/notifications','HomeController@notifications')->name('notifications');


