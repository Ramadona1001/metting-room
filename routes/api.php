<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\BookingController;
use App\Http\Controllers\Api\Admin\CompanyController;
use App\Http\Controllers\Api\Admin\DepartmentController;
use App\Http\Controllers\Api\Admin\MeetingRoomController;
use App\Http\Controllers\Api\PublicBookingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication)
|--------------------------------------------------------------------------
*/

Route::prefix('public')->group(function () {
    Route::get('/rooms/{token}', [PublicBookingController::class, 'getRoomByToken']);
    Route::post('/rooms/{token}/book', [PublicBookingController::class, 'createBooking'])
        ->middleware('throttle:10,1'); // Rate limit: 10 requests per minute
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Authenticated)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    // Companies
    Route::apiResource('companies', CompanyController::class);
    Route::patch('/companies/{company}/toggle-status', [CompanyController::class, 'toggleStatus']);

    // Departments
    Route::apiResource('departments', DepartmentController::class);

    // Meeting Rooms
    Route::apiResource('meeting-rooms', MeetingRoomController::class);
    Route::patch('/meeting-rooms/{meetingRoom}/toggle-status', [MeetingRoomController::class, 'toggleStatus']);
    Route::get('/meeting-rooms/{meetingRoom}/qr-code', [MeetingRoomController::class, 'getQrCode']);
    Route::post('/meeting-rooms/{meetingRoom}/regenerate-qr', [MeetingRoomController::class, 'regenerateQrToken']);

    // Bookings
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::get('/bookings/statistics', [BookingController::class, 'statistics']);
    Route::get('/bookings/{booking}', [BookingController::class, 'show']);
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);
});

