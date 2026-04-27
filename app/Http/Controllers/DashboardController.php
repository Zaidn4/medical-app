<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $stats = [];

        if ($user->isDoctor()) {
            $stats['total_patients'] = User::where('role', 'patient')->count();
            $stats['total_appointments'] = Appointment::count();
            $stats['today_appointments'] = Appointment::whereDate('appointment_date', today())->count();
            $stats['pending_appointments'] = Appointment::where('status', 'pending')->count();
        } else {
            $stats['my_appointments'] = Appointment::where('patient_id', $user->id)->count();
            $stats['upcoming_appointments'] = Appointment::where('patient_id', $user->id)
                ->where('appointment_date', '>=', now())
                ->where('status', 'confirmed')
                ->count();
        }

        return view('dashboard', compact('stats'));
    }
}