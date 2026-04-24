@extends('layouts.master')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Rendez-vous</h1>
        <a href="{{ route('appointments.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Nouveau Rendez-vous</a>
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
                    <td class="py-4 px-6 border-b border-gray-200 flex gap-2">
                        <a href="{{ route('appointments.edit', $appointment) }}" class="text-blue-500 hover:text-blue-700">Modifier</a>
                        <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">Supprimer</button>
                        </form>
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
@endsection