<?php

use App\Http\Controllers\Dashboard\{UserController, CategoryController, OrderController, ProductController};
use Illuminate\Support\Facades\Route;

# User
Route::group(['middleware' => ['auth'], 'as' => 'dashboard.', 'prefix' => 'dashboard'], function () {
    Route::view('/', 'dashboard.index')
        ->name('index');
    Route::resource('categories', CategoryController::class)
        ->except(['show']);
    Route::resource('products', ProductController::class);
});

# Admin
Route::group(['middleware' => ['auth', 'admin'], 'as' => 'dashboard.', 'prefix' => 'dashboard'], function () {
    Route::resource('users', UserController::class);
    Route::resource('orders', OrderController::class);
});
