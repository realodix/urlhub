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
Route::get('/+{short_url}', 'UrlController@view')->name('short_url.statics');
Route::get('/{short_url}', 'UrlController@urlRedirection')->where('short_url', '[A-Za-z0-9]{6}+');
Route::post('/create', 'UrlController@create')->middleware('checkurl');

Route::get('url/{id}/delete', 'UrlController@delete')->name('url.delete');

Route::middleware('auth')->prefix('admin')->group(function () {
    // Namespaces indicate folder structure
    Route::namespace('Backend')->group(function () {
        // Dashboard
        Route::get('/', 'DashboardController@index')->name('admin');
        Route::get('/allurl', 'AllUrlController@index')->name('admin.allurl');

        // User
        Route::namespace('User')->prefix('user')->group(function () {
            Route::get('/', 'UserController@index')->name('user.index');

            Route::get('/profile', 'ProfileController@view')->name('viewProfile');
            Route::post('/profile', 'ProfileController@update')->name('updateProfile');

            Route::get('/changepassword', 'ChangePasswordController@view')->name('viewChangePassword');
            Route::post('/changepassword', 'ChangePasswordController@update')->name('changePassword');
        });
    });
});
