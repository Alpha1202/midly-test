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

Route::group(['middleware' => ['web']], function () {
    // your routes here
    Route::get('fetchSpotifyData', 'App\Http\Controllers\AuthenticateUserController@fetchUserSpotifyData');
    Route::get('authenticateUser', 'App\Http\Controllers\AuthenticateUserController@authenticateUser');
});

Route::get('recentTracks', 'App\Http\Controllers\PlayedTracksController@fetchUsersRecentTracks');



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

