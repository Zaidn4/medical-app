<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Events\AppointmentCreated;

class AppointmentController extends Controller
{
    public function index()
    {
        $query = Appointment::with(['patient', 'doctor', 'service'])->latest();

        if (auth()->user()->isPatient()) {
            $query->where('patient_id', auth()->id());
        }

        $appointments = $query->paginate(10);

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
    if (!$request->user()->isDoctor()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }
    
    $validated = $request->validate([
        'patient_id' => 'required|exists:users,id',
        'doctor_id' => 'required|exists:users,id',
        'service_id' => 'required|exists:services,id',
        'appointment_date' => 'required|date',
        'status' => 'required|in:pending,confirmed,canceled',
        'notes' => 'nullable|string',
    ]);

    $appointment = Appointment::create($validated);

    \App\Events\AppointmentCreated::dispatch($appointment);

    return redirect()->route('appointments.index')->with('success', __('Rendez-vous créé.'));
}

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor', 'service']);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        // SECURITY: Prevent patients from editing other people's appointments
        if (auth()->user()->isPatient() && $appointment->patient_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        }

        $patients = User::where('role', 'patient')->get();
        $doctors = User::where('role', 'doctor')->get();
        $services = Service::all();
        
        return view('appointments.edit', compact('appointment', 'patients', 'doctors', 'services'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        if (auth()->user()->isPatient() && $appointment->patient_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        }

        $validated = $request->validate([
            'patient_id' => 'sometimes|required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'status' => 'required|in:pending,confirmed,canceled',
            'notes' => 'nullable|string',
        ]);

        if (auth()->user()->isPatient()) {
            $validated['patient_id'] = auth()->id();
        }

        $appointment->update($validated);

        return redirect()->route('appointments.index')->with('success', 'Rendez-vous mis à jour.');
    }

    public function destroy(Appointment $appointment)
    {
        if (auth()->user()->isPatient() && $appointment->patient_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        }

        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Rendez-vous supprimé.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $appointmentsQuery = Appointment::with(['patient', 'doctor', 'service'])->latest();

        // SECURITY 1: If the user is a patient, STRICTLY limit the query to their own ID
        if (auth()->user()->isPatient()) {
            $appointmentsQuery->where('patient_id', auth()->id());
        }

        // If a query exists, apply the search filters
        if (!empty($query)) {
            // SECURITY 2: Wrap all 'orWhere' clauses inside a where() closure. 
            // This translates to: WHERE patient_id = X AND (name LIKE Y OR status LIKE Y...)
            $appointmentsQuery->where(function ($q) use ($query) {
                $q->whereHas('patient', function ($subQ) use ($query) {
                    $subQ->where('name', 'like', "%{$query}%");
                })
                ->orWhereHas('doctor', function ($subQ) use ($query) {
                    $subQ->where('name', 'like', "%{$query}%");
                })
                ->orWhereHas('service', function ($subQ) use ($query) {
                    $subQ->where('name', 'like', "%{$query}%");
                })
                ->orWhere('status', 'like', "%{$query}%");
            });
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
                
                // Raw data needed for the Edit Modal
                'patient_id' => $app->patient_id,
                'doctor_id' => $app->doctor_id,
                'service_id' => $app->service_id,
                'raw_date' => $app->appointment_date->format('Y-m-d\TH:i'),
                'status_raw' => $app->status,
            ];
        });

        return response()->json($formattedAppointments);
    }
}