<?php

use Illuminate\Support\Facades\Route;
use \Bslm\Tahdig\Http\Controllers\Admin as C;

Route::group(['prefix' => 'admin/lunch', 'middleware' => 'web', 'namespace' => 'Bslm\Tahdig\Http\Controllers\Admin'], function () {
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/charge/{year}/{month}', [C\LunchController::class, 'charge']);

        Route::group(['prefix' => 'reservation'], function () {
            Route::get('/create', [C\LunchController::class, 'reservationCreate']);
            Route::post('/create', [C\LunchController::class, 'reservationCreateSubmit']);
            Route::get('/', [C\LunchController::class, 'reservation']);
            Route::get('/for-user/{id}', [C\LunchController::class, 'reserveForUser']);
            Route::get('/report/{booking_id}', [C\LunchController::class, 'reservationReport']);
            Route::get('/{booking_id}/{saloon_id}', [C\LunchController::class, 'reservationDetail']);
            Route::post('/toggleReserve', [C\LunchController::class, 'temporaryToggleReserve']);
        });

        Route::group(['prefix' => 'foods'], function () {
            Route::get('/', [C\LunchController::class, 'foods']);
            Route::get('/create', [C\LunchController::class, 'foodCreate']);
            Route::post('/create', [C\LunchController::class, 'foodCreateSubmit']);
            Route::get('/{id}', [C\LunchController::class, 'foodEdit']);
            Route::post('/edit', [C\LunchController::class, 'foodEditSubmit']);
            Route::get('/comments/{id}', [C\LunchController::class, 'foodComments']);
        });

        Route::group(['prefix' => 'restaurants'], function () {
            Route::get('/', [C\LunchController::class, 'restaurants']);
            Route::get('/create', [C\LunchController::class, 'restaurantCreate']);
            Route::post('/create', [C\LunchController::class, 'restaurantCreateSubmit']);
            Route::get('/{id}', [C\LunchController::class, 'restaurantEdit']);
            Route::post('/edit', [C\LunchController::class, 'restaurantEditSubmit']);
            Route::get('/comments/{restaurant}', [C\LunchController::class, 'restaurantComments'])->where('id', '[0-9]+');
        });
    });
});