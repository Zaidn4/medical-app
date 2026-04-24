@extends('layouts.master')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Rendez-vous</h1>
        <button onclick="openAddModal()" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
            + Nouveau Rendez-vous
        </button>    
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow-md rounded my-6">
        <table class="text-left w-full border-collapse">
            <thead>
                <tr>
                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200">Date</th>
                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200">Patient</th>
                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200">Médecin</th>
                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200">Service</th>
                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200">Statut</th>
                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 border-b border-gray-200">{{ $appointment->appointment_date->format('d/m/Y H:i') }}</td>
                    <td class="py-4 px-6 border-b border-gray-200">{{ $appointment->patient->name }}</td>
                    <td class="py-4 px-6 border-b border-gray-200">{{ $appointment->doctor->name }}</td>
                    <td class="py-4 px-6 border-b border-gray-200">{{ $appointment->service->name }}</td>
                    <td class="py-4 px-6 border-b border-gray-200">{{ ucfirst($appointment->status) }}</td>
                    <td class="py-4 px-6 border-b border-gray-200 flex gap-4">
                        <a href="{{ route('appointments.edit', $appointment) }}" class="text-blue-500 hover:text-blue-700 font-medium">Modifier</a>
                        
                        <button type="button" onclick="openDeleteModal({{ $appointment->id }})" class="text-red-600 hover:text-red-900 font-medium">
                            Supprimer
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">
            {{ $appointments->links() }}
        </div>
    </div>
</div>

<div id="addModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50 transition-opacity">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Ajout Rapide : Rendez-vous</h2>
        
        <form method="POST" action="{{ route('appointments.store') }}">
            @csrf

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                    <ul class="list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="mb-4">
                <label for="patient_id" class="block text-sm font-medium text-gray-700">Patient</label>
                <select name="patient_id" id="patient_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="doctor_id" class="block text-sm font-medium text-gray-700">Médecin</label>
                <select name="doctor_id" id="doctor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="service_id" class="block text-sm font-medium text-gray-700">Service</label>
                <select name="service_id" id="service_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }} ({{ $service->duration_minutes }} min)</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="appointment_date" class="block text-sm font-medium text-gray-700">Date et Heure</label>
                <input type="datetime-local" name="appointment_date" id="appointment_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="pending">En attente</option>
                    <option value="confirmed">Confirmé</option>
                    <option value="canceled">Annulé</option>
                </select>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50 transition-opacity">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <h2 class="text-xl font-bold mb-4 text-red-600">Confirmer la suppression</h2>
        <p class="text-gray-700 mb-6">Êtes-vous sûr de vouloir supprimer ce rendez-vous ? Cette action est irréversible.</p>
        
        <div class="flex justify-end space-x-3">
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none">Annuler</button>
            
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none">Supprimer définitivement</button>
            </form>
        </div>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
    }

    function openDeleteModal(appointmentId) {
        const form = document.getElementById('deleteForm');
        form.action = `/appointments/${appointmentId}`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    window.onclick = function(event) {
        const addModal = document.getElementById('addModal');
        const deleteModal = document.getElementById('deleteModal');
        if (event.target === addModal) {
            closeAddModal();
        }
        if (event.target === deleteModal) {
            closeDeleteModal();
        }
    }

    @if ($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            openAddModal();
        });
    @endif
</script>
@endsection