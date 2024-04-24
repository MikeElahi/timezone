<?php

use App\Http\Controllers\ListAllTimeZonesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['message' => 'Hello, world!'];
});

Route::get('/timezones', ListAllTimeZonesController::class);
