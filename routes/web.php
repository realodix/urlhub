<?php

use App\Http\Controllers\Dashboard\AllUrlController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\User\ChangePasswordController;
use App\Http\Controllers\Dashboard\User\UserController;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\UrlRedirectController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'frontend.homepage')->name('home');
Route::post('/shorten', [UrlController::class, 'create'])->name('short_url.create');
Route::get('/+{keyword}', [UrlController::class, 'showShortenedUrlDetails'])->name('short_url.stats');
Route::get('/delete/{url_hashId}', [UrlController::class, 'delete'])->name('short_url.delete');
Route::get('/duplicate/{keyword}', [UrlController::class, 'duplicate'])->middleware('auth')->name('short_url.duplicate');

Route::namespace('Dashboard')->prefix('admin')->group(function () {
    Route::middleware('auth')->group(function () {
        // Dashboard (My URLs)
        Route::get('/', [DashboardController::class, 'view'])->name('dashboard');
        Route::get('/delete/{url_hashId}', [DashboardController::class, 'delete'])->name('dashboard.url_delete');
        Route::get('/duplicate/{keyword}', [DashboardController::class, 'duplicate'])->name('dashboard.duplicate');
        Route::get('/edit/{keyword}', [DashboardController::class, 'edit'])->name('dashboard.short_url.edit');
        Route::post('/edit/{url_hashId}', [DashboardController::class, 'update'])->name('dashboard.short_url.edit.post');

        // All URLs
        Route::get('/allurl', [AllUrlController::class, 'view'])->name('dashboard.allurl');
        Route::get('/allurl/delete/{url_hashId}', [AllUrlController::class, 'delete'])->name('dashboard.allurl.delete');

        // User
        Route::namespace('User')->prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'view'])->name('user.index');
            Route::get('{user}/edit', [UserController::class, 'edit'])->name('user.edit');
            Route::post('{user_hashId}/edit', [UserController::class, 'update'])->name('user.update');

            Route::get('{user}/changepassword', [ChangePasswordController::class, 'view'])->name('user.change-password');
            Route::post('{user_hashId}/changepassword', [ChangePasswordController::class, 'update'])->name('user.change-password.post');
        });
    });
});

Route::get('/{keyword}', UrlRedirectController::class);
