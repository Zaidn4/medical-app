<aside class="w-64 bg-gray-800 text-white min-h-screen flex flex-col">
    <div class="p-4 text-center text-2xl font-bold border-b border-gray-700">
        {{ __('Menu') }}
    </div>
    <nav class="flex-1 px-4 py-6 space-y-3">
        <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition-colors">
            {{ __('Tableau de Bord') }}
        </a>

        @if(auth()->user()->isDoctor())
            <a href="{{ route('appointments.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition-colors">
                {{ __('Gestion des Rendez-vous') }}
            </a>
            
            <a href="{{ route('patients.create') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition-colors text-indigo-300 hover:text-white">
                + {{ __('Ajouter un Patient') }}
            </a>
        @else
            <a href="{{ route('appointments.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition-colors">
                {{ __('Mes Rendez-vous') }}
            </a>
        @endif
    </nav>

    <div class="p-4 border-t border-gray-700">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left block px-4 py-2 rounded hover:bg-red-700 transition-colors text-red-400 hover:text-white">
                {{ __('Déconnexion') }}
            </button>
        </form>
    </div>
</aside>