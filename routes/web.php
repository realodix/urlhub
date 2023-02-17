<?php

use App\Http\Controllers\Dashboard\AllUrlController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\User\ChangePasswordController;
use App\Http\Controllers\Dashboard\User\UserController;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\UrlRedirectController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'frontend.homepage')->name('home');
Route::post('/shorten', [UrlController::class, 'create'])->name('su_create');
Route::get('/+{keyword}', [UrlController::class, 'showDetail'])->name('su_detail');
Route::get('/delete/{hash_id}', [UrlController::class, 'delete'])->name('su_delete');
Route::get('/duplicate/{keyword}', [UrlController::class, 'duplicate'])->middleware('auth')->name('su_duplicate');

Route::namespace('Dashboard')->prefix('admin')->group(function () {
    Route::middleware('auth')->group(function () {
        // Dashboard (My URLs)
        Route::get('/', [DashboardController::class, 'view'])->name('dashboard');
        Route::get('/delete/{hash_id}', [DashboardController::class, 'delete'])->name('dashboard.su_delete');
        Route::get('/duplicate/{keyword}', [DashboardController::class, 'duplicate'])->name('dashboard.su_duplicate');
        Route::get('/edit/{keyword}', [DashboardController::class, 'edit'])->name('dashboard.su_edit');
        Route::post('/edit/{hash_id}', [DashboardController::class, 'update'])->name('dashboard.su_edit.post');

        // All URLs
        Route::get('/allurl', [AllUrlController::class, 'view'])->name('dashboard.allurl');
        Route::get('/allurl/delete/{hash_id}', [AllUrlController::class, 'delete'])->name('dashboard.allurl.su_delete');

        // User
        Route::namespace('User')->prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'view'])->name('user.index');
            Route::get('{user}/edit', [UserController::class, 'edit'])->name('user.edit');
            Route::post('{hash_id}/edit', [UserController::class, 'update'])->name('user.update');

            Route::get('{user}/changepassword', [ChangePasswordController::class, 'view'])->name('user.change-password');
            Route::post('{hash_id}/changepassword', [ChangePasswordController::class, 'update'])->name('user.change-password.post');
        });
    });
});

Route::get('/{keyword}', UrlRedirectController::class);
