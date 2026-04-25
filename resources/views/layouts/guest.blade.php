<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Cabinet Médical') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-50 to-indigo-100">
            
            <div class="mb-6 text-center">
                <h1 class="text-4xl font-extrabold text-indigo-600">{{ __('Cabinet Médical') }}</h1>
                <p class="text-gray-500 mt-2 font-medium">{{ __('Gestion des Rendez-vous') }}</p>
            </div>

            <div class="w-full sm:max-w-md mt-2 px-8 py-10 bg-white shadow-xl overflow-hidden sm:rounded-2xl border border-gray-100">
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-sm text-gray-500">
                &copy; {{ date('Y') }} Cabinet Médical. Tous droits réservés.
            </div>
        </div>

    </body>
</html>