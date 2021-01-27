<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

//Tweets routes
Route::post('tweetNew','TweetsController@store')->name('tweet.store');
