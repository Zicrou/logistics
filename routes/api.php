<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CheckPointController;
use App\Http\Controllers\Api\V1\DocumentController;
use App\Http\Controllers\Api\V1\DriverController;
use App\Http\Controllers\Api\V1\LocationPointController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\ShipmentController;
use App\Http\Controllers\Api\V1\VehicleController;
use App\Http\Controllers\Api\V1\TransportController;
use App\Models\CheckPoints;
use App\Models\Transport;

Route::prefix('v1')->group(function () {

    // Public
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // Protected
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);

        Route::apiResource('shipments', ShipmentController::class)->except('show');
        Route::apiResource('vehicles', VehicleController::class)->except('show');
        Route::apiResource('drivers', DriverController::class)->except('show');
        Route::apiResource('documents', DocumentController::class)->except('show');
        Route::apiResource('transports', TransportController::class)->except('show');
        Route::apiResource('location_points', LocationPointController::class)->except('show');
        Route::apiResource('checkPoints', CheckPointController::class)->except('show');
        Route::apiResource('payments', PaymentController::class)->except('show');
    });
});
