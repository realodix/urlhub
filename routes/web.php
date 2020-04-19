<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::view('/', 'frontend.welcome')->name('home');
Route::post('/create', 'UrlController@create')->name('createshortlink');
Route::post('/custom-link-avail-check', 'UrlController@checkExistingCustomUrl');

Route::namespace('Frontend')->group(function () {
    Route::get('/+{url_key}', 'UrlController@view')->name('short_url.stats');
    Route::get('/duplicate/{url_key}', 'UrlController@duplicate')->middleware('auth')->name('duplicate');
});

Route::namespace('Backend')->prefix('admin')->group(function () {
    Route::middleware('auth')->group(function () {
        // Dashboard (My URLs)
        Route::get('/', 'DashboardController@view')->name('dashboard');
        Route::get('/myurl/getdata', 'DashboardController@getData');
        Route::get('/delete/{url_hashId}', 'DashboardController@delete')->name('dashboard.delete');
        Route::get('/duplicate/{url_key}', 'DashboardController@duplicate')->name('dashboard.duplicate');
        Route::get('/edit/{url_key}', 'DashboardController@edit')->name('short_url.edit');
        Route::post('/edit/{url_hashId}', 'DashboardController@update')->name('short_url.edit.post');

        // Statistics
        Route::get('/statistics', 'StatisticsController@view')->name('dashboard.stat');

        // All URLs
        Route::get('/allurl', 'AllUrlController@index')->name('dashboard.allurl');
        Route::get('/allurl/getdata', 'AllUrlController@getData');
        Route::get('/allurl/delete/{url_hashId}', 'AllUrlController@delete')->name('dashboard.allurl.delete');

        // User
        Route::namespace('User')->prefix('user')->group(function () {
            Route::get('/', 'UserController@index')->name('user.index');
            Route::get('/user/getdata', 'UserController@getData');

            Route::get('{user}/edit', 'UserController@edit')->name('user.edit');
            Route::post('{user_hashId}/edit', 'UserController@update')->name('user.update');

            Route::get('{user}/changepassword', 'ChangePasswordController@view')->name('user.change-password');
            Route::post('{user_hashId}/changepassword', 'ChangePasswordController@update')->name('user.change-password.post');
        });
    });
});

Route::get('/{url_key}', 'UrlRedirectController');
