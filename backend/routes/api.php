<?php

declare(strict_types=1);

use App\Http\Controllers\CourierController;
use Illuminate\Support\Facades\Route;

// Courier
Route::apiResource('couriers', CourierController::class);
