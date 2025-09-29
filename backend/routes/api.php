<?php

declare(strict_types=1);

use App\Http\Controllers\CourierController;
use App\Http\Controllers\CourierLocationController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

// Courier
Route::apiResource('couriers', CourierController::class);

// Courier location
Route::post('courier-locations', [CourierLocationController::class, 'store'])
    ->middleware(sprintf('throttle:%d,1', config('courier.throttle_limit')))
    ->name('courier-locations.store');

// Setting
Route::get('settings', [SettingController::class, 'index']);
Route::patch('settings', [SettingController::class, 'update']);
