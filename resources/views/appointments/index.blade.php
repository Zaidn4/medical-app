@extends('layouts.master')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">{{ __('Rendez-vous') }}</h1>
        <button onclick="openAddModal()" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
            {{ __('+ Nouveau Rendez-vous') }}
        </button>    
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="mb-4">
        <input type="text" id="searchInput" placeholder="{{ __('Rechercher un patient, médecin, service ou statut...') }}" 
               class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
    </div>

    <div class="bg-white shadow-md rounded my-6">
        <table class="text-left w-full border-collapse">
            <thead>
                <tr>
                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200">{{ __('Date') }}</th>
                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200">{{ __('Patient') }}</th>
                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200">{{ __('Médecin') }}</th>
                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200">{{ __('Service') }}</th>
                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200">{{ __('Statut') }}</th>
                    <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody id="appointmentsTableBody">
                @foreach($appointments as $appointment)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-6 border-b border-gray-200">{{ $appointment->appointment_date->format('d/m/Y H:i') }}</td>
                    <td class="py-4 px-6 border-b border-gray-200">{{ $appointment->patient->name }}</td>
                    <td class="py-4 px-6 border-b border-gray-200">{{ $appointment->doctor->name }}</td>
                    <td class="py-4 px-6 border-b border-gray-200">{{ $appointment->service->name }}</td>
                    <td class="py-4 px-6 border-b border-gray-200">{{ __(ucfirst($appointment->status)) }}</td>
                    <td class="py-4 px-6 border-b border-gray-200 flex gap-4">
                    <button type="button" 
                        onclick="openEditModal(this)" 
                        data-id="{{ $appointment->id }}"
                        data-patient="{{ $appointment->patient_id }}"
                        data-doctor="{{ $appointment->doctor_id }}"
                        data-service="{{ $appointment->service_id }}"
                        data-date="{{ $appointment->appointment_date->format('Y-m-d\TH:i') }}"
                        data-status="{{ $appointment->status }}"
                        class="text-blue-500 hover:text-blue-700 font-medium">
                        {{ __('Modifier') }}
                    </button>                        

                        <button type="button" onclick="openDeleteModal({{ $appointment->id }})" class="text-red-600 hover:text-red-900 font-medium">
                            {{ __('Supprimer') }}
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4" id="paginationLinks">
            {{ $appointments->links() }}
        </div>
    </div>
</div>

{{-- Modal Ajout --}}
<div id="addModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50 transition-opacity">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">{{ __('Ajout Rapide : Rendez-vous') }}</h2>
        
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
            
            @if(auth()->user()->isDoctor())
                <div class="mb-4">
                    <label for="patient_id" class="block text-sm font-medium text-gray-700">{{ __('Patient') }}</label>
                    <select name="patient_id" id="patient_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <input type="hidden" name="patient_id" value="{{ auth()->id() }}">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Patient') }}</label>
                    <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 px-3 py-2 text-gray-600 shadow-sm">
                        {{ auth()->user()->name }} ({{ __('Vous') }})
                    </div>
                </div>
            @endif

            <div class="mb-4">
                <label for="doctor_id" class="block text-sm font-medium text-gray-700">{{ __('Médecin') }}</label>
                <select name="doctor_id" id="doctor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="service_id" class="block text-sm font-medium text-gray-700">{{ __('Service') }}</label>
                <select name="service_id" id="service_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }} ({{ $service->duration_minutes }} {{ __('min') }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="appointment_date" class="block text-sm font-medium text-gray-700">{{ __('Date et Heure') }}</label>
                <input type="datetime-local" name="appointment_date" id="appointment_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Statut') }}</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="pending">{{ __('En attente') }}</option>
                    <option value="confirmed">{{ __('Confirmé') }}</option>
                    <option value="canceled">{{ __('Annulé') }}</option>
                </select>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none">{{ __('Annuler') }}</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none">{{ __('Enregistrer') }}</button>
            </div>
        </form>
    </div>
</div>


{{-- Modal Modification --}}
<div id="editModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50 transition-opacity">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">{{ __('Modifier le Rendez-vous') }}</h2>
        
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')
            
            @if(auth()->user()->isDoctor())
                <div class="mb-4">
                    <label for="edit_patient_id" class="block text-sm font-medium text-gray-700">{{ __('Patient') }}</label>
                    <select name="patient_id" id="edit_patient_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <input type="hidden" name="patient_id" id="edit_patient_id" value="{{ auth()->id() }}">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Patient') }}</label>
                    <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 px-3 py-2 text-gray-600 shadow-sm">
                        {{ auth()->user()->name }} ({{ __('Vous') }})
                    </div>
                </div>
            @endif

            <div class="mb-4">
                <label for="edit_doctor_id" class="block text-sm font-medium text-gray-700">{{ __('Médecin') }}</label>
                <select name="doctor_id" id="edit_doctor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="edit_service_id" class="block text-sm font-medium text-gray-700">{{ __('Service') }}</label>
                <select name="service_id" id="edit_service_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="edit_appointment_date" class="block text-sm font-medium text-gray-700">{{ __('Date et Heure') }}</label>
                <input type="datetime-local" name="appointment_date" id="edit_appointment_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div class="mb-4">
                <label for="edit_status" class="block text-sm font-medium text-gray-700">{{ __('Statut') }}</label>
                <select name="status" id="edit_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="pending">{{ __('En attente') }}</option>
                    <option value="confirmed">{{ __('Confirmé') }}</option>
                    <option value="canceled">{{ __('Annulé') }}</option>
                </select>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none">{{ __('Annuler') }}</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none">{{ __('Mettre à jour') }}</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Suppression --}}
<div id="deleteModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50 transition-opacity">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <h2 class="text-xl font-bold mb-4 text-red-600">{{ __('Confirmer la suppression') }}</h2>
        <p class="text-gray-700 mb-6">{{ __('Êtes-vous sûr de vouloir supprimer ce rendez-vous ? Cette action est irréversible.') }}</p>
        
        <div class="flex justify-end space-x-3">
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none">{{ __('Annuler') }}</button>
            
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none">{{ __('Supprimer définitivement') }}</button>
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

    document.getElementById('searchInput').addEventListener('input', function(e) {
        let query = e.target.value;

        axios.get(`/appointments/search?query=${query}`)
            .then(response => {
                let rows = '';
                let appointments = response.data;

                if (appointments.length === 0) {
                    rows = `<tr><td colspan="6" class="py-4 px-6 text-center text-gray-500">Aucun rendez-vous trouvé.</td></tr>`;
                } else {
                    appointments.forEach(app => {
                        rows += `
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-6 border-b border-gray-200">${app.date}</td>
                            <td class="py-4 px-6 border-b border-gray-200">${app.patient}</td>
                            <td class="py-4 px-6 border-b border-gray-200">${app.doctor}</td>
                            <td class="py-4 px-6 border-b border-gray-200">${app.service}</td>
                            <td class="py-4 px-6 border-b border-gray-200">${app.status}</td>
                            <td class="py-4 px-6 border-b border-gray-200 flex gap-4">
                            <button type="button" 
                                onclick="openEditModal(this)" 
                                data-id="${app.id}"
                                data-patient="${app.patient_id}"
                                data-doctor="${app.doctor_id}"
                                data-service="${app.service_id}"
                                data-date="${app.raw_date}" 
                                data-status="${app.status_raw}"
                                class="text-blue-500 hover:text-blue-700 font-medium">
                                Modifier
                            </button>                                
                            <button type="button" onclick="openDeleteModal(${app.id})" class="text-red-600 hover:text-red-900 font-medium">
                                    Supprimer
                                </button>
                            </td>
                        </tr>
                        `;
                    });
                }

                document.getElementById('appointmentsTableBody').innerHTML = rows;

                const pagination = document.getElementById('paginationLinks');
                if (pagination) {
                    pagination.style.display = query.length > 0 ? 'none' : 'block';
                }
            })
            .catch(error => {
                console.error("Erreur lors de la recherche:", error);
            });
    });

    // Edit Modal Functions
    function openEditModal(button) {
        // 1. Get the data attributes from the clicked button
        const id = button.getAttribute('data-id');
        const patientId = button.getAttribute('data-patient');
        const doctorId = button.getAttribute('data-doctor');
        const serviceId = button.getAttribute('data-service');
        const date = button.getAttribute('data-date');
        const status = button.getAttribute('data-status');

        // 2. Set the form action URL dynamically
        const form = document.getElementById('editForm');
        form.action = `/appointments/${id}`;

        // 3. Pre-fill the form inputs
        if (document.getElementById('edit_patient_id')) {
            document.getElementById('edit_patient_id').value = patientId;
        }
        document.getElementById('edit_doctor_id').value = doctorId;
        document.getElementById('edit_service_id').value = serviceId;
        document.getElementById('edit_appointment_date').value = date;
        document.getElementById('edit_status').value = status;

        // 4. Show the modal
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Don't forget to add editModal to your window.onclick event listener so it closes when clicking outside!
    window.onclick = function(event) {
        const addModal = document.getElementById('addModal');
        const deleteModal = document.getElementById('deleteModal');
        const editModal = document.getElementById('editModal'); // NEW
        
        if (event.target === addModal) closeAddModal();
        if (event.target === deleteModal) closeDeleteModal();
        if (event.target === editModal) closeEditModal(); // NEW
    }
</script>
@endsection