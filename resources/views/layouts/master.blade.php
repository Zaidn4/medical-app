<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MedApp') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 flex h-screen overflow-hidden">
    @include('layouts.partials.sidebar')
    
    <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
        @include('layouts.partials.header')
        
        <main class="w-full grow p-6">
            {{ $slot }}
        </main>
        
        @include('layouts.partials.footer')
    </div>
</body>
</html>