<?php

use App\Http\Controllers\Dashboard\{UserController, CategoryController, OrderController, OrderItemController, ProductController};
use Illuminate\Support\Facades\Route;

# User
Route::group([], function () {
    Route::view('/', 'dashboard.index')
        ->name('index');
    Route::resource('categories', CategoryController::class)
        ->except(['show']);
    Route::resource('products', ProductController::class);
});

# Admin
Route::middleware('admin')
    ->group(function () {
        Route::resource('users', UserController::class);

        Route::get('orders/{order}/order-items', [OrderController::class, 'orderItems'])
            ->name('orders.orderItems');
        Route::resource('orders', OrderController::class);

        Route::resource('order-items', OrderItemController::class);
    });
