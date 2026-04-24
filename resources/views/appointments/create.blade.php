@extends('layouts.master')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Ajouter un Rendez-vous</h1>

    <form action="{{ route('appointments.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Patient</label>
            <select name="patient_id" class="w-full border-gray-300 rounded" required>
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Médecin</label>
            <select name="doctor_id" class="w-full border-gray-300 rounded" required>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Service</label>
            <select name="service_id" class="w-full border-gray-300 rounded" required>
                @foreach($services as $service)
                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Date et Heure</label>
            <input type="datetime-local" name="appointment_date" class="w-full border-gray-300 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Statut</label>
            <select name="status" class="w-full border-gray-300 rounded" required>
                <option value="pending">En attente</option>
                <option value="confirmed">Confirmé</option>
                <option value="canceled">Annulé</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 mb-2">Notes</label>
            <textarea name="notes" class="w-full border-gray-300 rounded" rows="4"></textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Enregistrer</button>
        <a href="{{ route('appointments.index') }}" class="ml-4 text-gray-600">Annuler</a>
    </form>
</div>
@endsection