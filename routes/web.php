<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\NewsUpdateController;
use App\Http\Controllers\TickerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PressReleaseController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'guest'], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('login', 'loginView')->name('login');
        Route::post('login', 'login')->name('login');
        Route::get('register', 'registerView')->name('register');
        Route::post('register', 'register')->name('register');
    });
});

Route::group(['middleware' => 'auth'], function () {
    Route::resources([
        'press-release' => PressReleaseController::class,
        'news-update' => NewsUpdateController::class,
        'permission' => PermissionController::class,
        'ticker' => TickerController::class,
        'role' => RoleController::class,
        'user' => UserController::class,
    ]);

    Route::put('/press-release/{id}/toggle', [PressReleaseController::class, 'toggleStatus'])->name('pressRelease.toggle');
    Route::put('/tickers/{id}/toggle', [TickerController::class, 'toggleStatus'])->name('ticker.toggle');
    Route::put('/news-update/{id}/toggle', [NewsUpdateController::class, 'toggleStatus'])->name('newsUpdate.toggle');
    Route::get('/news-update/{id}/popup-toggle', [NewsUpdateController::class, 'popupToggle'])->name('newsUpdate.popupToggle');
    Route::put('/news-update/{id}/popup-update', [NewsUpdateController::class, 'popupUpdate'])->name('newsUpdate.popupUpdate');
    Route::get('/popup-image', [NewsUpdateController::class, 'popupImage'])->name('newsUpdate.popupImage');
    Route::post('/popup_image_upload', [NewsUpdateController::class, 'popupImageUpload'])->name('newsUpdate.popupImageUpload');

    Route::get('logout', [AuthController::class, 'logut'])->name('logout');
});

Route::fallback(function () {
    return abort(401, "User can't perform this action.");
});
