<?php

use Illuminate\Support\Facades\Route;
use \Bslm\Tahdig\Http\Controllers\Admin as C;

//    Route::group(['middleware' => 'auth:sanctum'], function(){
        Route::group(['prefix' => ''], function() {
//            Route::get('/automateSettle', [C\LunchController::class, 'automateSettle']);

//            Route::get('/automate-settle', [C\LunchController::class, 'automateSettle']);
            Route::post('/automate-settle', [C\LunchController::class, 'automateSettle']);

        });
//    });

