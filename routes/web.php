<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\User\ChangePasswordController;
use App\Http\Controllers\Dashboard\User\UserController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\LinkPasswordController;
use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'frontend.homepage')->name('home');
Route::post('/shorten', [LinkController::class, 'create'])->name('link.create');
Route::get('/+{url:keyword}', [LinkController::class, 'showDetail'])->name('link_detail');
Route::get('/delete/{url:keyword}', [LinkController::class, 'delete'])->name('link_detail.delete');

Route::prefix('admin')->middleware(['auth', 'auth.session'])->group(function () {
    // Dashboard (My URLs)
    Route::get('/', [DashboardController::class, 'view'])->name('dashboard');
    Route::get('/links/{url:keyword}/edit', [LinkController::class, 'edit'])->name('link.edit');
    Route::post('/links/{url:keyword}/edit', [LinkController::class, 'update'])->name('link.update');
    Route::get('/links/{url:keyword}/delete', [LinkController::class, 'delete'])->name('link.delete');
    Route::get('/links/{url:keyword}/password/create', [LinkPasswordController::class, 'create'])->name('link.password.create');
    Route::post('/links/{url:keyword}/password/store', [LinkPasswordController::class, 'store'])->name('link.password.store');
    Route::get('/links/{url:keyword}/password/edit', [LinkPasswordController::class, 'edit'])->name('link.password.edit');
    Route::post('/links/{url:keyword}/password/update', [LinkPasswordController::class, 'update'])->name('link.password.update');
    Route::get('/links/{url:keyword}/password/delete', [LinkPasswordController::class, 'delete'])->name('link.password.delete');
    Route::get('/overview', [DashboardController::class, 'overview'])->name('dboard.overview');
    Route::get('/{user:name}/overview', [DashboardController::class, 'overviewPerUser'])->name('user.overview');

    // All URLs
    Route::get('/links', [DashboardController::class, 'allUrlView'])->name('dboard.allurl');
    Route::get('/links/{user:name}', [DashboardController::class, 'userLinkView'])->name('dboard.allurl.u-user');

    // User
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'view'])->name('user.index');
        Route::get('/new', [UserController::class, 'create'])->name('user.new');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/{user:name}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::post('/{user:name}/edit', [UserController::class, 'update'])->name('user.update');
        Route::delete('/{user:name}/delete', [UserController::class, 'delete'])->name('user.delete');
        Route::get('/{user:name}/delete', [UserController::class, 'confirmDelete'])->name('user.delete.confirm');

        Route::get('/{user:name}/changepassword', [ChangePasswordController::class, 'view'])->name('user.password.show');
        Route::post('/{user:name}/changepassword', [ChangePasswordController::class, 'update'])->name('user.password.store');
    });

    Route::get('/settings', [SettingController::class, 'view'])->name('dboard.settings');
    Route::post('/settings', [SettingController::class, 'update'])->name('dboard.settings.update');

    // About Page
    Route::get('/about', [DashboardController::class, 'aboutView'])->name('dboard.about');
});

Route::get('/{url:keyword}/expired', [LinkController::class, 'expiredLink'])->name('link.expired');
Route::get('/{url:keyword}/password', [LinkController::class, 'password'])->name('link.password');
Route::post('/{url:keyword}/password', [LinkController::class, 'validatePassword'])->name('link.password.validate');
Route::get('/{url:keyword}', RedirectController::class);
