<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AppointmentController as ApiAppointmentController;

Route::get('/appointments', [ApiAppointmentController::class, 'index']);
Route::post('/appointments', [ApiAppointmentController::class, 'store']);