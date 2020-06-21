<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::view('/', 'frontend.welcome')->name('home');
Route::post('/create', 'UrlController@create')->name('createshortlink');
Route::post('/validate-custom-key', 'UrlController@customKeyValidation');
Route::get('/+{keyword}', 'UrlController@view')->name('short_url.stats');
Route::get('/duplicate/{keyword}', 'UrlController@duplicate')->middleware('auth')->name('duplicate');

Route::namespace('Dashboard')->prefix('admin')->group(function () {
    Route::middleware('auth')->group(function () {
        // Dashboard (My URLs)
        Route::get('/', 'DashboardController@view')->name('dashboard');
        Route::get('/myurl/getdata', 'DashboardController@dataTable');
        Route::get('/delete/{url_hashId}', 'DashboardController@delete')->name('dashboard.delete');
        Route::get('/duplicate/{keyword}', 'DashboardController@duplicate')->name('dashboard.duplicate');
        Route::get('/edit/{keyword}', 'DashboardController@edit')->name('short_url.edit');
        Route::post('/edit/{url_hashId}', 'DashboardController@update')->name('short_url.edit.post');

        // Statistics
        Route::get('/statistics', 'StatisticsController@view')->name('dashboard.stat');

        // All URLs
        Route::get('/allurl', 'AllUrlController@view')->name('dashboard.allurl');
        Route::get('/allurl/getdata', 'AllUrlController@dataTable');
        Route::get('/allurl/delete/{url_hashId}', 'AllUrlController@delete')->name('dashboard.allurl.delete');

        // User
        Route::namespace('User')->prefix('user')->group(function () {
            Route::get('/', 'UserController@view')->name('user.index');
            Route::get('/user/getdata', 'UserController@getData');

            Route::get('{user}/edit', 'UserController@edit')->name('user.edit');
            Route::post('{user_hashId}/edit', 'UserController@update')->name('user.update');

            Route::get('{user}/changepassword', 'ChangePasswordController@view')->name('user.change-password');
            Route::post('{user_hashId}/changepassword', 'ChangePasswordController@update')->name('user.change-password.post');
        });
    });
});

Route::get('/{keyword}', 'UrlRedirectController');
