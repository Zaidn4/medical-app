<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['patient', 'doctor', 'service'])->latest()->paginate(10);

        $patients = User::where('role', 'patient')->get();
        $doctors  = User::where('role', 'doctor')->get();
        $services = Service::all();

        return view('appointments.index', compact(
            'appointments', 
            'patients', 
            'doctors', 
            'services'
        ));
    }

    public function create()
    {
        $patients = User::where('role', 'patient')->get();
        $doctors = User::where('role', 'doctor')->get();
        $services = Service::all();
        return view('appointments.create', compact('patients', 'doctors', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'status' => 'required|in:pending,confirmed,canceled',
            'notes' => 'nullable|string',
        ]);

        Appointment::create($validated);

        return redirect()->route('appointments.index')->with('success', 'Rendez-vous créé.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor', 'service']);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $patients = User::where('role', 'patient')->get();
        $doctors = User::where('role', 'doctor')->get();
        $services = Service::all();
        
        return view('appointments.edit', compact('appointment', 'patients', 'doctors', 'services'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'status' => 'required|in:pending,confirmed,canceled',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.index')->with('success', 'Rendez-vous mis à jour.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Rendez-vous supprimé.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $appointmentsQuery = Appointment::with(['patient', 'doctor', 'service'])->latest();

        if (!empty($query)) {
            $appointmentsQuery->whereHas('patient', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orWhereHas('doctor', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orWhereHas('service', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orWhere('status', 'like', "%{$query}%");
        }

        $appointments = $appointmentsQuery->take(30)->get();

        $formattedAppointments = $appointments->map(function ($app) {
            return [
                'id' => $app->id,
                'date' => $app->appointment_date->format('d/m/Y H:i'),
                'patient' => $app->patient->name,
                'doctor' => $app->doctor->name,
                'service' => $app->service->name,
                'status' => ucfirst($app->status),
                'edit_url' => route('appointments.edit', $app->id)
            ];
        });

        return response()->json($formattedAppointments);
    }
}