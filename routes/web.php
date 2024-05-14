<?php

use App\Http\Controllers\Dashboard\AboutSystemController;
use App\Http\Controllers\Dashboard\AllUrlController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\User\ChangePasswordController;
use App\Http\Controllers\Dashboard\User\UserController;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\UrlRedirectController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'frontend.homepage')->name('home');
Route::post('/shorten', [UrlController::class, 'create'])->name('su_create');
Route::get('/+{url:keyword}', [UrlController::class, 'showDetail'])->name('su_detail');
Route::get('/delete/{url:keyword}', [UrlController::class, 'delete'])->name('su_delete');

Route::namespace('Dashboard')->prefix('admin')->group(function () {
    Route::middleware('auth')->group(function () {
        // Dashboard (My URLs)
        Route::get('/', [DashboardController::class, 'view'])->name('dashboard');
        Route::get('/delete/{url:keyword}', [DashboardController::class, 'delete'])->name('dboard.url.delete');
        Route::get('/edit/{url:keyword}', [DashboardController::class, 'edit'])->name('dboard.url.edit.show');
        Route::post('/edit/{url:keyword}', [DashboardController::class, 'update'])->name('dboard.url.edit.store');

        // All URLs
        Route::get('/allurl', [AllUrlController::class, 'view'])->name('dashboard.allurl');
        Route::get('/allurl/delete/{url:keyword}', [AllUrlController::class, 'delete'])->name('dashboard.allurl.su_delete');
        Route::get('/allurl/u/guest', [AllUrlController::class, 'guestLinkView'])->name('dashboard.allurl.u-guest');
        Route::get('/allurl/u/{user:name}', [AllUrlController::class, 'userLinkView'])->name('dashboard.allurl.u-user');

        // User
        Route::namespace('User')->prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'view'])->name('user.index');
            Route::get('{user:name}/edit', [UserController::class, 'edit'])->name('user.edit');
            Route::post('{user:name}/edit', [UserController::class, 'update'])->name('user.update');

            Route::get('{user:name}/changepassword', [ChangePasswordController::class, 'view'])->name('user.change-password');
            Route::post('{user:name}/changepassword', [ChangePasswordController::class, 'update'])->name('user.password.store');
        });

        // About Page
        Route::get('/about', [AboutSystemController::class, 'view'])->name('dashboard.about');
    });
});

Route::get('/{url:keyword}', UrlRedirectController::class);
