@extends('layouts.master')

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 text-gray-900 text-lg font-medium">
            {{ __("Bienvenue ! Vous êtes connecté") }}, {{ auth()->user()->name }}.
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        @if(auth()->user()->isDoctor())
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500 hover:scale-105 transition-transform">
                <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">{{ __('Total Patients') }}</div>
                <div class="mt-2 text-3xl font-extrabold text-gray-900">{{ $stats['total_patients'] ?? 0 }}</div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 hover:scale-105 transition-transform">
                <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">{{ __('Total Rendez-vous') }}</div>
                <div class="mt-2 text-3xl font-extrabold text-gray-900">{{ $stats['total_appointments'] ?? 0 }}</div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:scale-105 transition-transform">
                <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">{{ __('Rendez-vous Aujourd\'hui') }}</div>
                <div class="mt-2 text-3xl font-extrabold text-gray-900">{{ $stats['today_appointments'] ?? 0 }}</div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500 hover:scale-105 transition-transform">
                <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">{{ __('En attente') }}</div>
                <div class="mt-2 text-3xl font-extrabold text-gray-900">{{ $stats['pending_appointments'] ?? 0 }}</div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500 hover:scale-105 transition-transform">
                <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">{{ __('Mes Rendez-vous') }}</div>
                <div class="mt-2 text-3xl font-extrabold text-gray-900">{{ $stats['my_appointments'] ?? 0 }}</div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:scale-105 transition-transform">
                <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">{{ __('Rendez-vous à venir') }}</div>
                <div class="mt-2 text-3xl font-extrabold text-gray-900">{{ $stats['upcoming_appointments'] ?? 0 }}</div>
            </div>
        @endif
    </div>
@endsection