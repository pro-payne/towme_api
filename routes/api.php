<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('login', 'Authentication\LoginController@login')->name('auth.login');
    Route::post('register', 'Authentication\RegisterController@register')->name('auth.register');
    Route::post('logout', 'Authentication\LoginController@logout')->name('auth.logout');
});

Route::group([
    'prefix' => 'account'
], function(){   
    Route::apiResource('/{account}/dashboard', 'DashboardController');
});

