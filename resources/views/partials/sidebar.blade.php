<!-- Sidebar -->
<aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full lg:translate-x-0 bg-white/95 backdrop-blur-md border-r border-gray-200 shadow-lg">
    <div class="h-full flex flex-col">
        <!-- Logo -->
        <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-white">
            <a href="{{ route('home.index') }}" class="flex items-center space-x-2 group">
                <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-green-700 rounded-lg flex items-center justify-center shadow-md group-hover:shadow-lg transform group-hover:scale-105 transition-all">
                    <i class="fas fa-tractor text-white text-xl"></i>
                </div>
                <span class="text-xl font-bold bg-gradient-to-r from-green-600 to-green-700 bg-clip-text text-transparent">Farm Manager</span>
            </a>
            <button id="close-sidebar" class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Menu Navigation -->
        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1 px-3">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('home.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('home.index') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-home w-5 mr-3 {{ request()->routeIs('home.index') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Ferme -->
                <li>
                    <a href="{{ route('farm.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('farm.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-tractor w-5 mr-3 {{ request()->routeIs('farm.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Ma Ferme</span>
                    </a>
                </li>

                <!-- Section Cultures -->
                <li class="pt-4">
                    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Cultures</p>
                </li>
                <li>
                    <a href="{{ route('crops.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('crops.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-seedling w-5 mr-3 {{ request()->routeIs('crops.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Cultures</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('fields.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('fields.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-map w-5 mr-3 {{ request()->routeIs('fields.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Champs</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('field-crops.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('field-crops.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-calendar-check w-5 mr-3 {{ request()->routeIs('field-crops.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Cultures planifiées</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('harvests.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('harvests.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-wheat-awn w-5 mr-3 {{ request()->routeIs('harvests.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Récoltes</span>
                    </a>
                </li>

                <!-- Section Bétail -->
                <li class="pt-4">
                    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Bétail</p>
                </li>
                <li>
                    <a href="{{ route('livestock.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('livestock.index') || request()->routeIs('livestock.create') || request()->routeIs('livestock.edit') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-cow w-5 mr-3 {{ request()->routeIs('livestock.index') || request()->routeIs('livestock.create') || request()->routeIs('livestock.edit') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Bétail</span>
                    </a>
                </li>
                
                <!-- Section Avicole -->
                <li class="pt-4">
                    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Avicole</p>
                </li>
                <li>
                    <a href="{{ route('poultry.incubations.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('poultry.incubations.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-egg w-5 mr-3 {{ request()->routeIs('poultry.incubations.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Incubations</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('poultry.chicks.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('poultry.chicks.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-baby w-5 mr-3 {{ request()->routeIs('poultry.chicks.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Poussins</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('poultry.egg-production.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('poultry.egg-production.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-egg w-5 mr-3 {{ request()->routeIs('poultry.egg-production.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Production d'œufs</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('poultry.prophylaxis.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('poultry.prophylaxis.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-syringe w-5 mr-3 {{ request()->routeIs('poultry.prophylaxis.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Prophylaxie</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('poultry.feed.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('poultry.feed.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-wheat-awn w-5 mr-3 {{ request()->routeIs('poultry.feed.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Alimentation</span>
                    </a>
                </li>
                
                <!-- Section Ruminants -->
                <li class="pt-4">
                    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Ruminants</p>
                </li>
                <li>
                    <a href="{{ route('livestock.breedings.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('livestock.breedings.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-heart w-5 mr-3 {{ request()->routeIs('livestock.breedings.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Saillies</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('livestock.calvings.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('livestock.calvings.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-baby w-5 mr-3 {{ request()->routeIs('livestock.calvings.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Vêlages</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('livestock.milk-production.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('livestock.milk-production.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-fill-drip w-5 mr-3 {{ request()->routeIs('livestock.milk-production.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Production laitière</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('livestock.health-records.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('livestock.health-records.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-stethoscope w-5 mr-3 {{ request()->routeIs('livestock.health-records.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Fiches sanitaires</span>
                    </a>
                </li>

                <!-- Section Clients & Employés -->
                <li class="pt-4">
                    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Personnel</p>
                </li>
                <li>
                    <a href="{{ route('customers.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('customers.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-users w-5 mr-3 {{ request()->routeIs('customers.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Clients</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('employees.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('employees.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-user-tie w-5 mr-3 {{ request()->routeIs('employees.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Employés</span>
                    </a>
                </li>

                <!-- Section Équipements -->
                <li class="pt-4">
                    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Équipements</p>
                </li>
                <li>
                    <a href="{{ route('equipment.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('equipment.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-tools w-5 mr-3 {{ request()->routeIs('equipment.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Équipements</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('inventory.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('inventory.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-boxes w-5 mr-3 {{ request()->routeIs('inventory.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Inventaire</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('farm-tasks.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('farm-tasks.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-tasks w-5 mr-3 {{ request()->routeIs('farm-tasks.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Tâches</span>
                    </a>
                </li>

                <!-- Section Finances -->
                <li class="pt-4">
                    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Finances</p>
                </li>
                <li>
                    <a href="{{ route('payments.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('payments.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-money-bill-wave w-5 mr-3 {{ request()->routeIs('payments.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Paiements</span>
                    </a>
                </li>

                <!-- Section Configuration -->
                <li class="pt-4">
                    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Configuration</p>
                </li>
                <li>
                    <a href="{{ route('select-options.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('select-options.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-cog w-5 mr-3 {{ request()->routeIs('select-options.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                        <span>Options</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Footer Sidebar -->
        <div class="border-t border-gray-200 p-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white font-semibold">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name ?? 'Utilisateur' }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ ucfirst(strtolower(str_replace('_', ' ', Auth::user()->role ?? 'USER'))) }}</p>
                </div>
            </div>
        </div>
    </div>
</aside>

<!-- Overlay pour mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-gray-600 bg-opacity-75 z-30 lg:hidden hidden"></div>

