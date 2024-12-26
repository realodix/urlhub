<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\User\ChangePasswordController;
use App\Http\Controllers\Dashboard\User\UserController;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\UrlRedirectController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'frontend.homepage')->name('home');
Route::post('/shorten', [UrlController::class, 'create'])->name('link.create');
Route::get('/+{url:keyword}', [UrlController::class, 'showDetail'])->name('link_detail');
Route::get('/delete/{url:keyword}', [UrlController::class, 'delete'])->name('link_detail.delete');

Route::prefix('admin')->middleware(['auth', 'auth.session'])->group(function () {
    // Dashboard (My URLs)
    Route::get('/', [DashboardController::class, 'view'])->name('dashboard');
    Route::get('/links/{url:keyword}/delete', [UrlController::class, 'delete'])->name('link.delete');
    Route::get('/links/{url:keyword}/edit', [UrlController::class, 'edit'])->name('link.edit');
    Route::post('/links/{url:keyword}/edit', [UrlController::class, 'update'])->name('link.update');

    // All URLs
    Route::get('/links', [DashboardController::class, 'allUrlView'])->name('dboard.allurl');
    Route::get('/links/u/guest', [DashboardController::class, 'guestLinkView'])->name('dboard.allurl.u-guest');
    Route::get('/links/u/{user:name}', [DashboardController::class, 'userLinkView'])->name('dboard.allurl.u-user');

    // User
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'view'])->name('user.index');
        Route::get('/{user:name}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::post('/{user:name}/edit', [UserController::class, 'update'])->name('user.update');

        Route::get('/{user:name}/changepassword', [ChangePasswordController::class, 'view'])->name('user.password.show');
        Route::post('/{user:name}/changepassword', [ChangePasswordController::class, 'update'])->name('user.password.store');
    });

    // About Page
    Route::get('/about', [DashboardController::class, 'aboutView'])->name('dboard.about');
});

Route::get('/{url:keyword}', UrlRedirectController::class);
