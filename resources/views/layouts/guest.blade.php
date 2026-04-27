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
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('images/auth-bg.jpg') }}');">
            
            <div class="absolute inset-0 bg-blue-900 opacity-40 z-0"></div>

            <div class="z-10">
                <a href="/">
                    <h1 class="text-4xl font-extrabold text-indigo-600">{{ __('Cabinet Médical') }}</h1>
                </a>
            </div>

            <div class="z-10 w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-xl overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
            
        </div>
    </body>
</html>