<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login');
});
Route::get('/forgot_password', function () {
    return view('forgot_password');
});
Route::get('/reset_password', function () {
    return view('reset_password');
});
Route::get('/dashboard', function () {
    return view('dashboard');
});

