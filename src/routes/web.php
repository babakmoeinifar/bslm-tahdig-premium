<?php
use Illuminate\Support\Facades\Route;
use \Bslm\Tahdig\Http\Controllers\User as C;

Route::group(['prefix' => 'lunch', 'middleware'=>'web', 'namespace' => 'Bslm\Tahdig\Http\Controllers\User'], function () {
    Route::get('/reserve', [C\LunchController::class, 'reserve']);
    Route::post('/reserve', [C\LunchController::class, 'reserveSubmitAjax']);
    Route::get('/reserve/{reservation}/delete', [C\LunchController::class, 'deleteReservation']);
    Route::get('/reserve/{reservation}/rate', [C\LunchController::class, 'rateForFood']);
    Route::post('/reserve/{reservation}/rate', [C\LunchController::class, 'rateForFoodSubmit']);
    Route::get('/history', [C\LunchController::class, 'history']);
});