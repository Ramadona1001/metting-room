<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\MeetingRoomController;
use App\Http\Controllers\PublicBookingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Booking Routes (No Authentication)
|--------------------------------------------------------------------------
*/

Route::get('/book/{token}', [PublicBookingController::class, 'show'])->name('public.booking.show');
Route::post('/book/{token}', [PublicBookingController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('public.booking.store');

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Companies - multi-delete MUST be before resource
    Route::delete('/companies/multi-delete', [CompanyController::class, 'multiDelete'])
        ->name('companies.multi-delete');
    Route::resource('companies', CompanyController::class);
    Route::patch('/companies/{company}/toggle-status', [CompanyController::class, 'toggleStatus'])
        ->name('companies.toggle-status');

    // Departments - multi-delete MUST be before resource
    Route::delete('/departments/multi-delete', [DepartmentController::class, 'multiDelete'])
        ->name('departments.multi-delete');
    Route::resource('departments', DepartmentController::class);

    // Meeting Rooms - multi-delete MUST be before resource
    Route::delete('/meeting-rooms/multi-delete', [MeetingRoomController::class, 'multiDelete'])
        ->name('meeting-rooms.multi-delete');
    Route::resource('meeting-rooms', MeetingRoomController::class);
    Route::patch('/meeting-rooms/{meetingRoom}/toggle-status', [MeetingRoomController::class, 'toggleStatus'])
        ->name('meeting-rooms.toggle-status');
    Route::get('/meeting-rooms/{meetingRoom}/qr-code', [MeetingRoomController::class, 'showQrCode'])
        ->name('meeting-rooms.qr-code');
    Route::post('/meeting-rooms/{meetingRoom}/regenerate-qr', [MeetingRoomController::class, 'regenerateQrToken'])
        ->name('meeting-rooms.regenerate-qr');

    // Bookings - multi-cancel MUST be before show
    Route::patch('/bookings/multi-cancel', [BookingController::class, 'multiCancel'])->name('bookings.multi-cancel');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

Route::get('/', function () {
    return redirect()->route('login');
});
