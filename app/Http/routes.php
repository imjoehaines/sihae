<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'LandingPageController@display');

// authentication
Route::get('/login', 'Auth\AuthController@getLogin');
Route::post('/login', 'Auth\AuthController@postLogin');
Route::get('/logout', 'Auth\AuthController@getLogout');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/post/new', 'PostController@create');
    Route::post('/post/new', 'PostController@store');

    Route::get('/post/edit/{slug}', 'PostController@edit');
    Route::post('/post/edit/{slug}', 'PostController@update');

    Route::get('/settings', 'SettingsController@display');
    Route::post('/settings', 'SettingsController@store');
});

Route::get('/post/{slug}', 'PostController@show');
