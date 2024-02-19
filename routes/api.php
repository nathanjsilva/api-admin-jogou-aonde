<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', 'App\Http\Controllers\UserAdminController@store');
Route::post('/login', 'App\Http\Controllers\UserAdminController@login');

