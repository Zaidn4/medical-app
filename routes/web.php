<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController; 
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EnsureUserIsDoctor; 
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('register');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/lang/{locale}', function ($locale) {
        if (in_array($locale, ['fr', 'en', 'ar', 'es'])) {
            session()->put('locale', $locale);
        }
        return redirect()->back();
    })->name('lang.switch');

    Route::get('/appointments/search', [AppointmentController::class, 'search'])->name('appointments.search');
    Route::resource('appointments', AppointmentController::class)->only(['index', 'show']);

    Route::middleware(EnsureUserIsDoctor::class)->group(function () {
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
        Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
        Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

        Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');
        Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
        
    });
});

require __DIR__.'/auth.php';