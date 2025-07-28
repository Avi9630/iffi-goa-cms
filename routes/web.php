<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TickerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'guest'], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('login',     'loginView')->name('login');
        Route::post('login',    'login')->name('login');
        Route::get('register',     'registerView')->name('register');
        Route::post('register',    'register')->name('register');
    });
});

Route::resource('ticker', TickerController::class);
Route::put('/tickers/{id}/toggle', [TickerController::class, 'toggleStatus'])->name('tickers.toggle');

Route::get('logout', [AuthController::class, 'logut'])->name('logout');
