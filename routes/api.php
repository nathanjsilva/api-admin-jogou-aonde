<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//user
Route::post('/register', 'App\Http\Controllers\UserAdminController@store');
Route::post('/login', 'App\Http\Controllers\UserAdminController@login');

//products
Route::prefix('products')->group(function () {
    Route::post('/', 'App\Http\Controllers\ProductController@store');
    Route::get('/', 'App\Http\Controllers\ProductController@getAll');
    Route::put('/{productId}/categories', 'App\Http\Controllers\ProductController@updateType');

});

//categories
Route::prefix('categories')->group(function () {
    Route::post('/', 'App\Http\Controllers\CategoryController@store');
});


