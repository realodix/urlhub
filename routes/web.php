<?php

Auth::routes();

Route::view('/', 'frontend.welcome');
Route::post('/create', 'GeneralUrlController@create')->name('createshortlink');
Route::get('/{short_url}', 'GeneralUrlController@urlRedirection')->where('short_url', '[A-Za-z0-9]{6}+');

Route::namespace('Frontend')->group(function () {
    Route::get('/+{short_url}', 'UrlController@view')->name('short_url.statics');
});

Route::namespace('Backend')->group(function () {
    Route::middleware('auth')->prefix('admin')->group(function () {
        // Dashboard (My URLs)
        Route::get('/', 'DashboardController@index')->name('admin');
        Route::get('/delete/{id}', 'DashboardController@delete')->name('admin.delete');

        // All URLs
        Route::get('/allurl', 'AllUrlController@index')->name('admin.allurl');
        Route::get('/allurl/delete/{id}', 'AllUrlController@delete')->name('admin.allurl.delete');

        // User
        Route::namespace('User')->prefix('user')->group(function () {
            Route::get('/', 'UserController@index')->name('user.index');

            Route::get('{user}/edit', 'ProfileController@view')->name('user.edit');
            Route::post('{user}/edit', 'ProfileController@update')->name('user.update');

            Route::get('{user}/changepassword', 'ChangePasswordController@view')->name('user.change-password');
            Route::post('{user}/changepassword', 'ChangePasswordController@update')->name('user.change-password.post');
        });
    });
});
