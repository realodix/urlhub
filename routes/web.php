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

Route::view('/', 'welcome');
Route::post('/create', 'UrlController@create');
Route::get('/{link}', 'UrlController@url_redirection')->where('link', '[a-zA-Z0-9]+');
Route::get('view/{link}', 'UrlController@view')->where('link', '[a-zA-Z0-9]+');
