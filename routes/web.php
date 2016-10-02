<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('auth/facebook', 'Auth\SocialController@redirectToProvider');
Route::get('auth/facebook/callback', 'Auth\SocialController@handleProviderCallback');

Route::get('/home', 'HomeController@index');

Route::get('/create', 'FitnessController@create');
Route::post('/create', 'FitnessController@store');

Route::get('/get/all', 'FitnessController@getAll');

Route::get('/get/best/trainings', 'FitnessController@getBestEfficiency');