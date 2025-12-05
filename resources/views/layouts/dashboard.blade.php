<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Farm Manager')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
    <style>
        /* Ajustement pour le sidebar fixe sur desktop */
        @media (min-width: 1024px) {
            body {
                padding-left: 256px;
            }
        }
        
        /* Animations et transitions */
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        /* Scrollbar personnalisée */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #10b981 0%, #059669 100%);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #059669 0%, #047857 100%);
        }
        
        /* Animation pour les messages */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-animate {
            animation: slideIn 0.3s ease-out;
        }
        
        /* Gradient background subtil */
        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #f9fafb 50%, #f0f9ff 100%);
            background-attachment: fixed;
        }
        
        /* Effet de glassmorphism pour les cartes */
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50">
    @auth
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Navbar -->
        @include('partials.navbar')
    @endauth

    <!-- Contenu principal -->
    <main class="pt-16 lg:pt-16 px-4 sm:px-6 lg:px-8 py-8">
        <!-- Messages de succès -->
        @if(session('success'))
            <div class="mb-6 alert-animate">
                <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600 text-lg"></i>
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-green-800 font-semibold">{{ session('success') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-green-600 hover:text-green-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Messages d'erreur -->
        @if($errors->any())
            <div class="mb-6 alert-animate">
                <div class="p-4 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-red-800 font-semibold mb-2">Erreurs de validation :</p>
                            <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-red-600 hover:text-red-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Contenu de la page -->
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12 shadow-sm">
        <div class="px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center space-x-2 mb-4 md:mb-0">
                    <div class="w-8 h-8 bg-gradient-to-br from-green-600 to-green-700 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tractor text-white text-sm"></i>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">Farm Manager</span>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-sm text-gray-500">
                        &copy; {{ date('Y') }} Farm Manager. Tous droits réservés.
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        Gestion complète de votre exploitation agro-pastorale
                    </p>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
    
    <!-- Script pour le menu mobile -->
    <script>
        // Toggle sidebar mobile
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const closeSidebarButton = document.getElementById('close-sidebar');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', () => {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
            });
        }

        if (closeSidebarButton) {
            closeSidebarButton.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            });
        }
    </script>
</body>
</html>

