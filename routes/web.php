<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::view('/', 'frontend.welcome');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/changepassword', 'UserController@showChangePasswordForm')->name('showChangePassword');
    Route::post('/changepassword', 'UserController@changePassword')->name('changePassword');

    // Namespaces indicate folder structure
    Route::namespace('Backend')->group(function () {
        Route::get('/', 'DashboardController@index')->name('admin');
        Route::get('/allurl', 'AllUrlController@index')->name('admin.allurl');
    });
});

Route::get('/+{short_url}', 'UrlController@view');
Route::get('/{short_url}', 'UrlController@urlRedirection');
Route::post('/create', 'UrlController@create')->middleware('checkurl');
