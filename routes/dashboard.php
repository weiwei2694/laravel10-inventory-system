<?php

use App\Http\Controllers\Dashboard\{UserController, CategoryController};
use Illuminate\Support\Facades\Route;

# User
Route::group(['middleware' => ['auth'], 'as' => 'dashboard.', 'prefix' => 'dashboard'], function () {
    Route::view('/', 'dashboard.index')
        ->name('index');
});

# Admin
Route::group(['middleware' => ['auth', 'admin'], 'as' => 'dashboard.', 'prefix' => 'dashboard'], function () {
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class)
        ->except(['show']);
});
