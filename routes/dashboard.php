<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth'], 'as' => 'dashboard.', 'prefix' => 'dashboard'], function () {
    Route::view('/', 'dashboard.index')
        ->name('index');
});
