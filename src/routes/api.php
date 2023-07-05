<?php

use Illuminate\Support\Facades\Route;
use \Bslm\Tahdig\Http\Controllers\Admin as C;

Route::get('/automate-settle', [C\LunchController::class, 'automateSettle']);

