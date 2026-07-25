<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\AttendanceController;

// Public route for login
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Members API
    Route::get('/members', [MemberController::class, 'index']);
    Route::post('/subscriptions/update', [MemberController::class, 'updateSubscription']);

    // Attendance API
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn']);
});

// Hardware IoT NFC routes
Route::prefix('v1/nfc')->group(function () {
    Route::post('/scan', [\App\Http\Controllers\Api\NfcController::class, 'scan']);
    Route::post('/ping', [\App\Http\Controllers\Api\NfcController::class, 'ping']);
});
