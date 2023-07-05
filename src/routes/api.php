<?php

use Illuminate\Support\Facades\Route;
use \Bslm\Tahdig\Http\Controllers\Admin as C;

Route::group(['middleware' => 'web'], function () {
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/automate-settle', [C\LunchController::class, 'automateSettle']);
    });
});
