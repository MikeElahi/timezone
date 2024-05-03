<?php

use App\Http\Controllers\ListAllTimeZonesController;
use App\Http\Controllers\TimeZoneCompatibilityController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['message' => 'Hello, world!'];
});

Route::get('/timezones', ListAllTimeZonesController::class);
Route::post('/timezones/compatibility', TimeZoneCompatibilityController::class);
