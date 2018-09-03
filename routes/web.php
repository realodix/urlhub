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

Route::middleware('auth')->group(function () {
    Route::get('/changepassword', 'UserController@showChangePasswordForm');
    Route::post('/changepassword', 'UserController@changePassword')->name('changePassword');
    /*
     * Backend Routes
     * Namespaces indicate folder structure
     */
    Route::namespace('backend')->group(function () {
        Route::get('/admin', 'DashboardController@index')->name('admin');
        Route::get('/admin/allurl', 'AllUrlController@index')->name('admin.allurl');
    });
});

Route::get('/+{link}', 'UrlController@view');
Route::get('/{link}', 'UrlController@url_redirection');
Route::post('/create', 'UrlController@create')->middleware('checkurl');
