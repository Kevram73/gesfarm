<!-- Navbar -->
<nav class="bg-white/95 backdrop-blur-md shadow-md border-b border-gray-200 fixed top-0 left-0 right-0 lg:left-64 z-30">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Bouton menu mobile -->
            <button id="mobile-menu-button" class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <!-- Recherche (optionnel) -->
            <div class="hidden md:flex flex-1 max-w-lg mx-4">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:bg-white transition-all sm:text-sm" placeholder="Rechercher...">
                </div>
            </div>

            <!-- Actions droite -->
            <div class="flex items-center space-x-4">
                @auth
                    <!-- Notifications -->
                    <button class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors group">
                        <i class="fas fa-bell text-lg group-hover:animate-pulse"></i>
                        <span class="absolute top-1 right-1 block h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white animate-pulse"></span>
                    </button>

                    <!-- Menu utilisateur -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors">
                            <div class="w-9 h-9 bg-gradient-to-br from-green-600 to-green-700 rounded-full flex items-center justify-center text-white font-semibold text-sm shadow-md">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst(strtolower(str_replace('_', ' ', Auth::user()->role))) }}</p>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </button>

                        <!-- Dropdown menu -->
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white/95 backdrop-blur-md rounded-lg shadow-xl py-2 z-50 border border-gray-200">
                            <a href="{{ route('home.profile') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors rounded mx-2">
                                <i class="fas fa-user mr-2 text-gray-400"></i> Mon profil
                            </a>
                            <a href="{{ route('home.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors rounded mx-2">
                                <i class="fas fa-cog mr-2 text-gray-400"></i> Paramètres
                            </a>
                            <hr class="my-2 border-gray-200">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors rounded mx-2">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-green-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                        Connexion
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

