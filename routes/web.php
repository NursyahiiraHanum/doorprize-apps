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

// Guest Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.perform');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout.perform');
    Route::get('/', [EventController::class, 'index'])->name('dashboard');

    // Attendance (Dapat diakses oleh User, Admin, & Super Admin)
    Route::middleware(['role:user,admin,super_admin'])->group(function () {
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance/scan', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
        Route::post('/attendance/bulk-reset', [AttendanceController::class, 'bulkDestroy'])->name('attendance.bulk-reset');
        Route::post('/attendance/demo-all', [AttendanceController::class, 'demoAttendAll'])->name('attendance.demo-all');
    });

    // Fitur Admin & Super Admin (Doorprize, Dashboard, Hadiah, Pemenang, Peserta)
    Route::middleware(['role:admin,super_admin'])->group(function () {
        
        // Doorprize Machine
        Route::get('/doorprize', [DoorprizeController::class, 'index'])->name('doorprize.index');
        Route::get('/doorprize/session-data', [DoorprizeController::class, 'getSessionData'])->name('doorprize.session-data');
        Route::post('/doorprize/store-winners', [DoorprizeController::class, 'storeWinners'])->name('doorprize.store-winners');
        Route::get('/doorprize/operator', [DoorprizeController::class, 'operatorView'])->name('doorprize.operator');
        Route::get('/doorprize/display', [DoorprizeController::class, 'displayView'])->name('doorprize.display');

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
    });

    // Fitur Khusus Super Admin & Settings
    Route::middleware(['role:super_admin'])->group(function () {
        Route::get('/settings', [UserController::class, 'settings'])->name('settings');
        Route::post('/settings/update', [UserController::class, 'updateSettings'])->name('settings.update');
        Route::post('/settings/delete-account', [UserController::class, 'deleteAccount'])->name('settings.delete-account');
        Route::post('/settings/users/store', [UserController::class, 'store'])->name('users.store');
        Route::delete('/settings/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});
