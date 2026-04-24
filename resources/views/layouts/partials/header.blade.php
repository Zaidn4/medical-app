<header class="bg-white shadow border-b border-gray-200">
    <div class="flex items-center justify-between px-6 py-4">
        <div class="text-xl font-bold text-gray-800">
            Cabinet Médical
        </div>
        <div class="flex items-center space-x-6">
            
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="text-gray-600 hover:text-gray-900 font-medium flex items-center gap-1 uppercase">
                    {{ app()->getLocale() }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" @click.away="open = false" style="display: none;" class="absolute right-0 mt-2 w-32 bg-white rounded-md shadow-lg border border-gray-200 z-50">
                    <a href="{{ route('lang.switch', 'fr') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Français</a>
                    <a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">English</a>
                    <a href="{{ route('lang.switch', 'ar') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">العربية</a>
                    <a href="{{ route('lang.switch', 'es') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Español</a>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-600 hover:text-gray-900 font-medium">
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</header>