<?php

declare(strict_types=1);

use App\Http\Controllers\CourierController;
use App\Http\Controllers\CourierLocationController;
use Illuminate\Support\Facades\Route;

// Courier
Route::apiResource('couriers', CourierController::class);

// Courier location
Route::post('courier-locations', [CourierLocationController::class, 'store'])
    ->middleware('throttle:120,1')
    ->name('courier-locations.store');

// Admin panel
Route::group(['prefix' => 'admin'], function () {
    //TODO!
});
