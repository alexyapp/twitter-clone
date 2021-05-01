<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'API', 'middleware' => 'api', 'prefix' => 'v1'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register');
        Route::post('refresh', 'AuthController@refresh');

        Route::group(['middleware' => 'jwt.verify'], function () {
            Route::post('logout', 'AuthController@logout');

            Route::get('me', 'AuthController@me');
        });
    });

    Route::apiResource('tweets', 'TweetController');
});
