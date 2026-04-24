<header class="flex items-center justify-between px-6 py-4 bg-white border-b">
    <div class="flex items-center">
        <h1 class="text-xl font-semibold text-gray-800">Panel</h1>
    </div>
    <div class="flex items-center gap-4">
        <div class="text-sm font-medium text-gray-700">
            {{ Auth::user()->name ?? 'Utilisateur' }}
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-red-600 hover:text-red-800 focus:outline-none">
                Déconnexion
            </button>
        </form>
    </div>
</header>