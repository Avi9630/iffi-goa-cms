<?php

use App\Http\Controllers\InternationalCinemaBasicDetailController;
use App\Http\Controllers\InternationalCinemaController;
use App\Http\Controllers\InternationalMediaController;
use App\Http\Controllers\MasterClassTopicController;
use App\Http\Controllers\MasterClassDateController;
use App\Http\Controllers\IndianPanoramaController;
use App\Http\Controllers\LatestUpdateController;
use App\Http\Controllers\PressReleaseController;
use App\Http\Controllers\MasterClassController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\JuryDetailController;
use App\Http\Controllers\NewsUpdateController;
use App\Http\Controllers\ModeratorController;
use App\Http\Controllers\PeacockController;
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\TickerController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CubeController;
use App\Http\Controllers\JuryDetailController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
        'ic-basic-detail' => InternationalCinemaBasicDetailController::class,
        'international-cinema' => InternationalCinemaController::class,
        'international-media' => InternationalMediaController::class,
        'master-class-topic' => MasterClassTopicController::class,
        'master-class-date' => MasterClassDateController::class,
        'indian-panorama' => IndianPanoramaController::class,
        'press-release' => PressReleaseController::class,
        'latest-update' => LatestUpdateController::class,
        'master-class' => MasterClassController::class,
        'permission' => PermissionController::class,
        'moderator' => ModeratorController::class,
        'speaker' => SpeakerController::class,
        'peacock' => PeacockController::class,
        'ticker' => TickerController::class,
        'photo' => PhotoController::class,
        'role' => RoleController::class,
        'user' => UserController::class,
        'cube' => CubeController::class,
        // 'jury-detail' => JuryDetailController::class,
    ]);

    Route::resource('news-update', NewsUpdateController::class)->except(['show']);
    Route::resource('jury-detail', JuryDetailController::class); //->except(['show']);

    Route::controller(IndianPanoramaController::class)
        ->prefix('ip')
        ->name('indianPanorama.')
        ->group(function () {
            Route::put('{id}/toggle', 'toggleStatus')->name('toggle');
            Route::get('search', [IndianPanoramaController::class, 'search'])->name('search');
            Route::post('upload-csv', [IndianPanoramaController::class, 'uploadCSV'])->name('uploadCSV');
        });

    Route::controller(InternationalCinemaController::class)
        ->prefix('ic')
        ->name('internationalCinema.')
        ->group(function () {
            Route::put('{id}/toggle', 'toggleStatus')->name('toggle');
            Route::get('{id}/add-basic-detail', 'addBasicDetail')->name('addBasicDetail');
            Route::post('{id}/store-basic-detail', 'storeBasicDetail')->name('storeBasicDetail');
            Route::post('upload-csv', [InternationalCinemaController::class, 'uploadCSV'])->name('uploadCSV');
            Route::get('search', [InternationalCinemaController::class, 'search'])->name('search');
            Route::get('full-search', [InternationalCinemaController::class, 'fullSearch'])->name('fullSearch');
        });

    Route::controller(NewsUpdateController::class)
        ->prefix('news-update')
        ->name('newsUpdate.')
        ->group(function () {
            Route::post('popup-image-upload', 'popupImageUpload')->name('popupImageUpload');
            Route::get('{id}/popup-toggle', 'popupToggle')->name('popupToggle');
            Route::put('{id}/popup-update', 'popupUpdate')->name('popupUpdate');
            Route::put('{id}/toggle', 'toggleStatus')->name('toggle');
            Route::get('search', 'newsSearch')->name('search');
        });

    Route::get('/popup-image', [NewsUpdateController::class, 'popupImage'])->name('newsUpdate.popupImage');

    Route::controller(PhotoController::class)
        ->prefix('photo')
        ->name('photo.')
        ->group(function () {
            Route::put('{id}/highlight', 'highlightToggle')->name('highlightToggle');
            Route::put('{id}/activeToggle', 'activeToggle')->name('activeToggle');
            Route::put('{id}/toggle', 'toggleStatus')->name('toggle');
        });

    Route::get('/photo-search', [PhotoController::class, 'search'])->name('photo.search');

    // MASTER-CLASS
    Route::get('/master-class-topic/{id}/add', [MasterClassDateController::class, 'addTopic'])->name('masterClassTopic.addTopic');
    Route::get('/master-class/{id}/add-detail', [MasterClassTopicController::class, 'addDetail'])->name('masterClass.addDetail');
    Route::get('/master-class/{id}/add-speaker', [MasterClassTopicController::class, 'addSpeaker'])->name('masterClass.addSpeaker');
    Route::get('/master-class/{id}/add-moderator', [MasterClassTopicController::class, 'addModerator'])->name('masterClass.addModerator');
    Route::put('/master-class-topic/{id}/toggle', [MasterClassTopicController::class, 'toggleStatus'])->name('masterClassTopic.toggleStatus');

    Route::put('/ic-basic-detail/{id}/toggle', [InternationalCinemaBasicDetailController::class, 'toggleStatus'])->name('icBasicDetail.toggle');
    Route::get('/search', [InternationalCinemaBasicDetailController::class, 'search'])->name('icBasicDetail.search');

    Route::put('/jury-detail/{id}/toggle', [JuryDetailController::class, 'toggle'])->name('juryDetail.toggle');
    // Route::put('/jury-detail-search', [JuryDetailController::class, 'search'])->name('juryDetail.search');
    Route::get('/jury-detail-search', [JuryDetailController::class, 'search'])->name('juryDetail.search');

    Route::put('/peacock/{id}/toggle', [PeacockController::class, 'toggleStatus'])->name('peacock.toggle');
    Route::get('/peacock.search', [PeacockController::class, 'search'])->name('peacock.search');

    Route::put('/moderator/{id}/toggle', [ModeratorController::class, 'toggleStatus'])->name('moderator.toggleStatus');

    Route::put('/master-class/{id}/toggle', [MasterClassController::class, 'toggleStatus'])->name('masterClass.toggleStatus');

    Route::put('/international-media/{id}/toggle', [InternationalMediaController::class, 'toggleStatus'])->name('internationalMedia.toggle');

    Route::put('/cube/{id}/toggle', [CubeController::class, 'toggleStatus'])->name('cube.toggleStatus');

    Route::put('/press-release/{id}/toggle', [PressReleaseController::class, 'toggleStatus'])->name('pressRelease.toggle');

    Route::put('/latest-update/{id}/toggle', [LatestUpdateController::class, 'toggleStatus'])->name('latestUpdate.toggle');

    Route::put('/tickers/{id}/toggle', [TickerController::class, 'toggleStatus'])->name('ticker.toggle');

    Route::get('get_images_by_folder/{path}', [CommonController::class, 'getImageByFolder'])
        ->where('path', '.*')
        ->name('getImageByFolder');

    Route::get('/download-sample-csv/{fileName}', [CommonController::class, 'downloadSampleCsv'])->name('downloadSampleCsv');

    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('logout', [AuthController::class, 'logut'])->name('logout');
});

Route::fallback(function () {
    return abort(401, "User can't perform this action.");
});
