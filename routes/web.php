<?php

Auth::routes();

Route::view('/', 'frontend.welcome');
Route::post('/create', 'GeneralUrlController@create')->name('createshortlink');
Route::post('/custom-link-avail-check', 'GeneralUrlController@checkCustomLinkAvailability');

Route::namespace('Frontend')->group(function () {
    Route::get('/+{short_url}', 'UrlController@view')->name('short_url.statics');
});

Route::namespace('Backend')->group(function () {
    Route::middleware('auth')->prefix('admin')->group(function () {
        // Dashboard (My URLs)
        Route::get('/', 'DashboardController@view')->name('admin');
        Route::get('/myurl/getdata', 'DashboardController@getData');
        Route::get('/delete/{id}', 'DashboardController@delete')->name('admin.delete');

        // All URLs
        Route::get('/allurl', 'AllUrlController@index')->name('admin.allurl');
        Route::get('/allurl/getdata', 'AllUrlController@getData');
        Route::get('/allurl/delete/{id}', 'AllUrlController@delete')->name('admin.allurl.delete');

        // User
        Route::namespace('User')->prefix('user')->group(function () {
            Route::get('/', 'UserController@index')->name('user.index');
            Route::get('/user/getdata', 'UserController@getData');

            Route::get('{user}/edit', 'UserController@edit')->name('user.edit');
            Route::post('{user}/edit', 'UserController@update')->name('user.update');

            Route::get('{user}/changepassword', 'ChangePasswordController@view')->name('user.change-password');
            Route::post('{user}/changepassword', 'ChangePasswordController@update')->name('user.change-password.post');
        });
    });
});

Route::get('/{short_url}', 'GeneralUrlController@urlRedirection');
