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

Route::get('/post/new', ['middleware' => 'auth', 'uses' => 'PostController@create']);
Route::post('/post/new', ['middleware' => 'auth', 'uses' => 'PostController@store']);
Route::get('/post/{slug}', 'PostController@show');

// authentication
Route::get('/login', 'Auth\AuthController@getLogin');
Route::post('/login', 'Auth\AuthController@postLogin');
Route::get('/logout', 'Auth\AuthController@getLogout');

Route::get('/settings', 'SettingsController@display');
Route::post('/settings', 'SettingsController@store');
