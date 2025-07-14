<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeliveryZoneController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DeliveryAssignmentController;
use App\Http\Controllers\AuthController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('delivery-zones', DeliveryZoneController::class);
    Route::post('orders', [OrderController::class, 'store']);
    Route::put('delivery-assignments/{assignment}/respond', [DeliveryAssignmentController::class, 'respond']);
});

Route::get('/test', function (Request $request) {
    return response()->json([
        'message' => 'This is a public route.',
    ]);
});
