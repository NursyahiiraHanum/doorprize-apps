<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PrizeController;
use App\Http\Controllers\DoorprizeController;
use App\Http\Controllers\WinnerController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;

// ─── Guest Routes (Tidak perlu login) ──────────────────────────────────────
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.perform');


// ─── Protected Routes (Wajib login) ────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Logout
    Route::get('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout.perform');

    // Doorprize display (videotron, tidak butuh login agar bisa ditampilkan di layar umum)
    Route::get('/doorprize/display', [DoorprizeController::class, 'displayView'])->name('doorprize.display');

    // Dashboard
    Route::get('/', [EventController::class, 'index'])->name('dashboard');
    

    

    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/scan', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
    Route::post('/attendance/bulk-reset', [AttendanceController::class, 'bulkDestroy'])->name('attendance.bulk-reset');
    Route::post('/attendance/demo-all', [AttendanceController::class, 'demoAttendAll'])->name('attendance.demo-all');

    // Doorprize Machine
    Route::get('/doorprize', [DoorprizeController::class, 'index'])->name('doorprize.index');
    Route::get('/doorprize/session-data', [DoorprizeController::class, 'getSessionData'])->name('doorprize.session-data');
    Route::post('/doorprize/store-winners', [DoorprizeController::class, 'storeWinners'])->name('doorprize.store-winners');
    Route::get('/doorprize/operator', [DoorprizeController::class, 'operatorView'])->name('doorprize.operator');

    // Prizes
    Route::resource('prizes', PrizeController::class);

    // Winners
    Route::get('/winners', [WinnerController::class, 'index'])->name('winners.index');
    Route::delete('/winners/reset', [WinnerController::class, 'reset'])->name('winners.reset');
    Route::get('/winners/export-excel', [WinnerController::class, 'exportExcel'])->name('winners.exportExcel');

    // Participants Master
    Route::post('/participants/import', [ParticipantController::class, 'import'])->name('participants.import');
    Route::post('/participants/generate-qr', [ParticipantController::class, 'generateQr'])->name('participants.generate-qr');
    Route::get('/participants/{participant}/print-qr', [ParticipantController::class, 'printSingleQr'])->name('participants.print-qr');
    Route::get('/participants/export-qr', [ParticipantController::class, 'exportQr'])->name('participants.export-qr');
    Route::resource('participants', ParticipantController::class);

    // Settings & User Management
    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
    Route::post('/settings/update', [UserController::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/delete-account', [UserController::class, 'deleteAccount'])->name('settings.delete-account');
    Route::post('/settings/users/store', [UserController::class, 'store'])->name('users.store');
    Route::delete('/settings/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});
