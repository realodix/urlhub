<?php

use Illuminate\Http\Request;

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

Route::post('/custom-link-avail-check', 'GeneralUrlController@checkCustomLinkAvailability');

Route::namespace('Backend')->group(function () {
    Route::get('allurl/getdata', 'AllUrlController@getData');
    Route::get('user/getdata', 'User\UserController@getData');
});
