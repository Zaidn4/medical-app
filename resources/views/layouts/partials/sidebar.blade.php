<aside class="w-64 bg-gray-800 text-white min-h-screen flex flex-col">
    <div class="p-4 text-center text-2xl font-bold border-b border-gray-700">
        Menu
    </div>
    <nav class="flex-1 px-4 py-6 space-y-3">
        <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition-colors">
            Tableau de Bord
            <a href="{{ route('appointments.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition-colors">
    Rendez-vous
</a>
        </a>
    </nav>
</aside>