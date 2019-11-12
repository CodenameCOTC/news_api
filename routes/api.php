<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');
Route::get('user', 'UserController@getAuthenticatedUser')->middleware('jwt.verify');


/**
 * News Route
 */

Route::get('news', 'API\NewsController@index');
Route::get('news/{id}', 'API\NewsController@show');
Route::post('news', 'API\NewsController@store')->middleware('jwt.verify');
Route::put('news/{news}', 'API\NewsController@update')->middleware('jwt.verify');
Route::delete('news/{news}', 'API\NewsController@destroy')->middleware('jwt.verify');

