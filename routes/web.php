<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'guest'], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('login',     'loginView')->name('login');
        Route::post('login',    'login')->name('login'); //->middleware('throttle:2,1')

        Route::get('register',     'registerView')->name('register');
        Route::post('register',    'register')->name('register');
    });
});

Route::get('logout', [AuthController::class, 'logut'])->name('logout');
