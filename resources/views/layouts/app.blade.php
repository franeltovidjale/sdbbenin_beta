<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tableau de bord')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            50: '#f0f4f8',
                            100: '#d9e2ec',
                            200: '#bcccdc',
                            300: '#9fb3c8',
                            400: '#829ab1',
                            500: '#627d98',
                            600: '#486581',
                            700: '#334e68',
                            800: '#243b53',
                            900: '#102a43',
                            950: '#0d2235',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Animations personnalisées */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
        
        .animate-slideIn {
            animation: slideIn 0.3s ease-in-out;
        }
        
        /* Style pour la scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #d9e2ec;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #486581;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #334e68;
        }
        
        /* Style pour les liens actifs et hover */
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        
        /* Modification: utiliser border-left au lieu de pseudo-élément pour éviter les décalages */
        .nav-link {
            border-left: 0px solid #4299e1;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover,
        .nav-link.active {
            border-left: 4px solid #4299e1;
        }
        
        .submenu-active {
            background-color: rgba(79, 129, 189, 0.2);
        }
        
        /* Style pour l'effet de ripple sur les boutons sans décalage */
        .ripple {
            position: relative;
            overflow: hidden;
        }
        
        .ripple-effect {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            pointer-events: none;
            transform: scale(0);
            animation: ripple-effect 0.6s linear;
            transform-origin: center;
        }
        
        @keyframes ripple-effect {
            0% {
                transform: scale(0);
                opacity: 1;
            }
            100% {
                transform: scale(2.5);
                opacity: 0;
            }
        }
        
        /* Prévenir le décalage lors des transitions */
        .no-shift {
            backface-visibility: hidden;
            transform: translateZ(0);
            -webkit-font-smoothing: subpixel-antialiased;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Navbar (Sidebar) -->
        <div id="sidebar" class="w-64 h-screen bg-gradient-to-b from-navy-800 to-navy-950 text-white fixed left-0 top-0 z-30 transform transition-transform duration-300 ease-in-out lg:translate-x-0 shadow-xl no-shift">
            <!-- Brand/Logo -->
            <div class="px-6 py-5 border-b border-navy-700 bg-navy-900 flex items-center justify-center">
                <div class="text-2xl font-bold animate-fadeIn">SDB Benin</div>
            </div>
            
            <!-- Menu Items -->
            <nav class="mt-4 px-2 overflow-y-auto h-[calc(100%-10rem)]">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="nav-link flex items-center py-3 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 group mb-2 no-shift">
                    <i class="fas fa-chart-line w-6 text-blue-300 group-hover:text-white transition-colors"></i>
                    <span class="ml-3">Tableau de bord</span>
                </a>
                
                <!-- Articles -->
                <div class="mb-2">
                    <div id="articlesMenuHeader" class="nav-link flex items-center justify-between py-3 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 cursor-pointer group no-shift" onclick="toggleSubmenu('articlesMenuSubmenu', 'articlesMenuHeader', 'articlesMenuArrow')">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt w-6 text-blue-300 group-hover:text-white transition-colors"></i>
                            <span class="ml-3">Articles</span>
                        </div>
                        <i id="articlesMenuArrow" class="fas fa-chevron-down text-sm transition-transform duration-300"></i>
                    </div>
                   

                    <!-- Sous-menu Articles avec lien vers Catégories corrigé -->
                    <div id="articlesMenuSubmenu" class="hidden pl-12 mt-1 mb-1 overflow-hidden transition-all duration-300 max-h-0">
                        <a href="{{ route('articles.index') }}" class="flex items-center py-2 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 group my-1 no-shift">
                            <i class="fas fa-list-ul w-5 text-blue-300 group-hover:text-white transition-colors"></i>
                            <span class="ml-2">Liste</span>
                        </a>
                        <a href="{{ route('articles.create') }}" class="flex items-center py-2 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 group my-1 no-shift">
                            <i class="fas fa-file-circle-plus w-5 text-blue-300 group-hover:text-white transition-colors"></i>
                            <span class="ml-2">Ajouter</span>
                        </a>
                        <a href="{{ route('categories.index') }}" class="flex items-center py-2 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 group my-1 no-shift">
                            <i class="fas fa-folder-tree w-5 text-blue-300 group-hover:text-white transition-colors"></i>
                            <span class="ml-2">Catégories</span>
                        </a>
                    </div>
                </div>

                 <!-- Gestion des ventes de production -->
                 <div class="mb-2">
                    <div id="salesMenuHeader" class="nav-link flex items-center justify-between py-3 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 cursor-pointer group no-shift" onclick="toggleSubmenu('salesMenuSubmenu', 'salesMenuHeader', 'salesMenuArrow')">
                        <div class="flex items-center">
                            <i class="fas fa-cash-register w-6 text-blue-300 group-hover:text-white transition-colors"></i>
                            <span class="ml-3">Gestion des ventes</span>
                        </div>
                        <i id="salesMenuArrow" class="fas fa-chevron-down text-sm transition-transform duration-300"></i>
                    </div>
                    <div id="salesMenuSubmenu" class="hidden pl-12 mt-1 mb-1 overflow-hidden transition-all duration-300 max-h-0">
                        <a href="{{ route('productions.sales.index') }}" class="flex items-center py-2 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 group my-1 no-shift">
                            <i class="fas fa-box w-5 text-blue-300 group-hover:text-white transition-colors"></i>
                            <span class="ml-2">Ventes</span>
                        </a>
                        <a href="{{ route('productions.sales.reports') }}" class="flex items-center py-2 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 group my-1 no-shift">
                            <i class="fas fa-file-invoice-dollar w-5 text-blue-300 group-hover:text-white transition-colors"></i>
                            <span class="ml-2">Bilan  </span>
                        </a>
                        
                    </div>
                </div>
                
                
                <div class="mb-2">
                    <div id="productionMenuHeader" class="nav-link flex items-center justify-between py-3 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 cursor-pointer group no-shift" onclick="toggleSubmenu('productionMenuSubmenu', 'productionMenuHeader', 'productionMenuArrow')">
                        <div class="flex items-center">
                            <i class="fas fa-box-open w-6 text-blue-300 group-hover:text-white transition-colors"></i>
                            <span class="ml-3">Production</span>
                        </div>
                        <i id="productionMenuArrow" class="fas fa-chevron-down text-sm transition-transform duration-300"></i>
                    </div>
                    <div id="productionMenuSubmenu" class="hidden pl-12 mt-1 mb-1 overflow-hidden transition-all duration-300 max-h-0">
                        <a href="{{ route('production.index') }}" class="flex items-center py-2 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 group my-1 no-shift">
                            <i class="fas fa-grip w-5 text-blue-300 group-hover:text-white transition-colors"></i>
                            <span class="ml-2">Vue d'ensemble</span>
                        </a>
                        <a href="{{ route('production.create') }}" class="flex items-center py-2 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 group my-1 no-shift">
                            <i class="fas fa-square-plus w-5 text-blue-300 group-hover:text-white transition-colors"></i>
                            <span class="ml-2">Ajouter</span>
                        </a>
                        <a href="{{ route('types.index') }}" class="flex items-center py-2 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 group my-1 no-shift">
                            <i class="fas fa-sitemap w-5 text-blue-300 group-hover:text-white transition-colors"></i>
                            <span class="ml-2">Types de production</span>
                        </a>
                        <a href="{{ route('production.stats') }}" class="flex items-center py-2 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 group my-1 no-shift">
                            <i class="fas fa-chart-column w-5 text-blue-300 group-hover:text-white transition-colors"></i>
                            <span class="ml-2">Statistiques</span>
                        </a>
                    </div>
                </div>

                <!-- Entrées / Sorties -->
                <div class="mb-2">
                    <div id="stockMenuHeader" class="nav-link flex items-center justify-between py-3 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 cursor-pointer group no-shift" onclick="toggleSubmenu('stockMenuSubmenu', 'stockMenuHeader', 'stockMenuArrow')">
                        <div class="flex items-center">
                            <i class="fas fa-dolly w-6 text-blue-300 group-hover:text-white transition-colors"></i>
                            <span class="ml-3">Entreés/Sorties</span>
                        </div>
                        <i id="stockMenuArrow" class="fas fa-chevron-down text-sm transition-transform duration-300"></i>
                    </div>
                    <div id="stockMenuSubmenu" class="hidden pl-12 mt-1 mb-1 overflow-hidden transition-all duration-300 max-h-0">
                        <a href="{{ route('stock.movements.index') }}" class="flex items-center py-2 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 group my-1 no-shift">
                            <i class="fas fa-boxes-stacked w-5 text-blue-300 group-hover:text-white transition-colors"></i>
                            <span class="ml-2">Articles</span>
                        </a>
                        
                    </div>
                </div>

                 <!-- Gestion des ventes de production -->
                 {{-- <div class="mb-2">
                    <div id="salesMenuHeader" class="nav-link flex items-center justify-between py-3 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 cursor-pointer group no-shift" onclick="toggleSubmenu('salesMenuSubmenu', 'salesMenuHeader', 'salesMenuArrow')">
                        <div class="flex items-center">
                            <i class="fas fa-cash-register w-6 text-blue-300 group-hover:text-white transition-colors"></i>
                            <span class="ml-3">Gestion des ventes</span>
                        </div>
                        <i id="salesMenuArrow" class="fas fa-chevron-down text-sm transition-transform duration-300"></i>
                    </div>
                    <div id="salesMenuSubmenu" class="hidden pl-12 mt-1 mb-1 overflow-hidden transition-all duration-300 max-h-0">
                        <a href="{{ route('productions.sales.index') }}" class="flex items-center py-2 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 group my-1 no-shift">
                            <i class="fas fa-box w-5 text-blue-300 group-hover:text-white transition-colors"></i>
                            <span class="ml-2">Ventes de Carton</span>
                        </a>
                        <a href="{{ route('productions.sales.reports') }}" class="flex items-center py-2 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 group my-1 no-shift">
                            <i class="fas fa-file-invoice-dollar w-5 text-blue-300 group-hover:text-white transition-colors"></i>
                            <span class="ml-2">Bilan des Ventes </span>
                        </a>
                        
                    </div>
                </div> --}}

               

                
                <!-- Personnel -->
                <div class="mb-2">
                    <div id="personnelMenuHeader" class="nav-link flex items-center justify-between py-3 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 cursor-pointer group no-shift" onclick="toggleSubmenu('personnelMenuSubmenu', 'personnelMenuHeader', 'personnelMenuArrow')">
                        <div class="flex items-center">
                            <i class="fas fa-user-group w-6 text-blue-300 group-hover:text-white transition-colors"></i>
                            <span class="ml-3">Personnel</span>
                        </div>
                        <i id="personnelMenuArrow" class="fas fa-chevron-down text-sm transition-transform duration-300"></i>
                    </div>
                    <div id="personnelMenuSubmenu" class="hidden pl-12 mt-1 mb-1 overflow-hidden transition-all duration-300 max-h-0">
                        <a href="" class="flex items-center py-2 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 group my-1 no-shift">
                            <i class="fas fa-people-group w-5 text-blue-300 group-hover:text-white transition-colors"></i>
                            <span class="ml-2">Employés</span>
                        </a>
                        <a href="" class="flex items-center py-2 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 group my-1 no-shift">
                            <i class="fas fa-diagram-project w-5 text-blue-300 group-hover:text-white transition-colors"></i>
                            <span class="ml-2">Départements</span>
                        </a>
                    </div>
                </div>
                
                <!-- Clients -->
                <a href="" class="nav-link flex items-center py-3 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 group mb-2 no-shift">
                    <i class="fas fa-handshake w-6 text-blue-300 group-hover:text-white transition-colors"></i>
                    <span class="ml-3">Clients</span>
                </a>
                
               
                
                <!-- Mot de passe -->
                <a href="" class="nav-link flex items-center py-3 px-4 rounded-md transition-all duration-200 hover:bg-navy-700 group mb-2 no-shift">
                    <i class="fas fa-lock w-6 text-blue-300 group-hover:text-white transition-colors"></i>
                    <span class="ml-3">Mot de passe</span>
                </a>
            </nav>
            
            <!-- Sign Out -->
            <div class="absolute bottom-0 w-full border-t border-navy-700 bg-navy-900">
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                   class="nav-link flex items-center py-4 px-6 transition-all duration-200 hover:bg-navy-800 group no-shift">
                    <i class="fas fa-right-from-bracket w-6 text-blue-300 group-hover:text-white transition-colors"></i>
                    <span class="ml-3">Déconnexion</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
        
        <div class="flex-1 flex flex-col ml-0 lg:ml-64 transition-all duration-300 no-shift">
            <!-- Topbar -->
            <header class="bg-white shadow-md z-20 relative">
                <div class="h-1 bg-gradient-to-r from-blue-400 to-blue-600"></div>
                <div class="flex items-center justify-between px-6 py-3">
                    <!-- Left side: Toggle button for mobile and breadcrumb -->
                    <div class="flex items-center">
                        <!-- Mobile menu toggle -->
                        <button id="sidebarToggle" class="block lg:hidden text-gray-600 hover:text-blue-700 focus:outline-none mr-4 rounded-full p-2 hover:bg-gray-100 transition-all duration-200 no-shift">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        
                        <!-- Breadcrumb -->
                        <nav class="text-gray-600 flex items-center">
                            <a href="{{ route('dashboard') }}" class="hover:text-blue-700 transition-colors font-medium">Tableau de bord</a>
                            
                        </nav>
                    </div>
                    
                    <!-- Right side: Notifications and User profile -->
                    <div class="flex items-center space-x-1 md:space-x-4">
                        
                        <!-- Notifications - Correction du problème de décalage -->
                        <div class="relative">
                            <button class="text-gray-600 hover:text-blue-700 focus:outline-none relative p-2 rounded-full hover:bg-gray-100 transition-all duration-200 no-shift" onclick="toggleDropdown('notificationsDropdown')">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 font-bold flex items-center justify-center text-xs animate-pulse">3</span>
                            </button>
                            <!-- Notifications dropdown -->
                            <div id="notificationsDropdown" class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-xl py-2 z-50 hidden animate-fadeIn">
                                <div class="flex items-center justify-between px-4 py-2 border-b">
                                    <h3 class="font-semibold text-gray-700">Notifications</h3>
                                    <a href="#" class="text-xs text-blue-600 hover:text-blue-800 font-medium">Marquer tout comme lu</a>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition-colors border-b">
                                        <div class="flex">
                                            <div class="flex-shrink-0 h-9 w-9 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-envelope text-blue-500"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">Nouveau message</p>
                                                <p class="text-xs text-gray-500 mt-1">Il y a 10 minutes</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition-colors border-b">
                                        <div class="flex">
                                            <div class="flex-shrink-0 h-9 w-9 bg-green-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-check text-green-500"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">Tâche complétée</p>
                                                <p class="text-xs text-gray-500 mt-1">Il y a 1 heure</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition-colors">
                                        <div class="flex">
                                            <div class="flex-shrink-0 h-9 w-9 bg-red-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-triangle-exclamation text-red-500"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">Alerte système</p>
                                                <p class="text-xs text-gray-500 mt-1">Il y a 3 heures</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="border-t">
                                    <a href="#" class="block px-4 py-2 text-sm text-center text-blue-600 hover:text-blue-800 font-medium">
                                        Voir toutes les notifications
                                    </a>
                                </div>
                            </div>
                        </div>
                      
                        <!-- User Profile - Correction du problème de décalage -->
                        <div class="relative">
                            <button class="flex items-center space-x-2 focus:outline-none rounded-full hover:bg-gray-100 p-1 transition-all duration-200 no-shift" onclick="toggleDropdown('userDropdown')">
                                <div class="w-8 h-8 rounded-full bg-navy-700 flex items-center justify-center text-white overflow-hidden border-2 border-white shadow-sm">
                                    <i class="fas fa-user text-sm"></i>
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'Thomas Martin' }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->role ?? 'Administrateur' }}</p>
                                </div>
                                <i class="fas fa-chevron-down text-xs text-gray-500 hidden md:block"></i>
                            </button>
                            <!-- User dropdown -->
                            <div id="userDropdown" class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-xl py-2 z-50 hidden animate-fadeIn">
                                <div class="px-4 py-3 border-b">
                                    <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name ?? 'Thomas Martin' }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email ?? 'thomas.martin@exemple.com' }}</p>
                                </div>
                                <div class="py-2">
                                    <a href="" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-700 transition-colors">
                                        <i class="fas fa-circle-user w-5 text-gray-400"></i>
                                        <span class="ml-2">Mon profil</span>
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-700 transition-colors">
                                        <i class="fas fa-gear w-5 text-gray-400"></i>
                                        <span class="ml-2">Préférences</span>
                                    </a>
                                    <a href="" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-700 transition-colors">
                                        <i class="fas fa-screwdriver-wrench w-5 text-gray-400"></i>
                                        <span class="ml-2">Paramètres</span>
                                    </a>
                                </div>
                                <div class="border-t py-2">
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-top').submit();" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-red-700 transition-colors">
                                        <i class="fas fa-right-from-bracket w-5 text-gray-400"></i>
                                        <span class="ml-2">Déconnexion</span>
                                    </a>
                                    <form id="logout-form-top" action="{{ route('logout') }}" method="POST" class="hidden">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <!-- Page header -->
                <div class="mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-navy-800">@yield('page-title', 'Tableau de bord')</h1>
                            <p class="text-gray-600 mt-1">@yield('page-subtitle', 'Vue d\'ensemble')</p>
                        </div>
                        <div class="mt-4 md:mt-0 flex space-x-3">
                            
                            <button class="px-4 py-2 bg-gradient-to-r  from-blue-500 to-blue-600 rounded-lg shadow-sm text-sm font-bold text-white hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 no-shift" disabled>
                                {{-- <i class="fas fa-plus mr-2"></i> --}}
                                 SDB BENIN
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Content goes here -->
                <div class="animate-fadeIn">
                    @yield('content')
                </div>
            </main>
            
            <!-- Footer -->
            <footer class="bg-white py-4 px-6 border-t">
                <div class="container mx-auto">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between text-gray-600 text-sm">
                        <div>
                            &copy; {{ date('Y') }} SDB Benin. Tous droits réservés.
                        </div>
                        <div class="mt-2 md:mt-0">
                            <a href="#" class="text-gray-600 hover:text-blue-600 transition-colors mr-4">Mentions légales</a>
                            <a href="#" class="text-gray-600 hover:text-blue-600 transition-colors mr-4">Confidentialité</a>
                            <a href="#" class="text-gray-600 hover:text-blue-600 transition-colors">Contact</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    <!-- Overlay for mobile when sidebar is open -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden lg:hidden" onclick="toggleSidebar()"></div>
    
    <!-- JavaScript -->
    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }
        
        // Toggle Dropdown - Version améliorée sans décalage
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            
            // Fermer d'abord tous les autres dropdowns
            document.querySelectorAll('[id$="Dropdown"]').forEach(el => {
                if (el.id !== id && !el.classList.contains('hidden')) {
                    el.classList.add('hidden');
                }
            });
            
            // Ensuite basculer l'état du dropdown actuel (séparé pour éviter les décalages)
            dropdown.classList.toggle('hidden');
        }
        
        // Toggle Submenu - Version améliorée sans décalage
        function toggleSubmenu(submenuId, headerId, arrowId) {
            const submenu = document.getElementById(submenuId);
            const header = document.getElementById(headerId);
            const arrow = document.getElementById(arrowId);
            
            // Gérer l'ouverture ou la fermeture du submenu actuel
            if (submenu.classList.contains('hidden')) {
                // Ouvrir le submenu
                submenu.classList.remove('hidden');
                setTimeout(() => {
                    submenu.style.maxHeight = submenu.scrollHeight + "px";
                    header.classList.add('submenu-active');
                    arrow.classList.add('rotate-180');
                }, 10); // Petit délai pour éviter les décalages
                
                // Sauvegarder l'état dans localStorage
                localStorage.setItem(submenuId, 'open');
            } else {
                // Fermer le submenu
                submenu.style.maxHeight = "0px";
                arrow.classList.remove('rotate-180');
                header.classList.remove('submenu-active');
                setTimeout(() => {
                    submenu.classList.add('hidden');
                }, 300);
                
                // Supprimer l'état du localStorage
                localStorage.removeItem(submenuId);
            }
            
            // Fermer les autres submenus
            document.querySelectorAll('[id$="Submenu"]').forEach(el => {
                if (el.id !== submenuId && !el.classList.contains('hidden')) {
                    const associatedHeader = document.getElementById(el.id.replace('Submenu', 'Header'));
                    const associatedArrow = document.getElementById(el.id.replace('Submenu', 'Arrow'));
                    
                    el.style.maxHeight = "0px";
                    associatedArrow.classList.remove('rotate-180');
                    associatedHeader.classList.remove('submenu-active');
                    setTimeout(() => {
                        el.classList.add('hidden');
                    }, 300);
                    
                    // Supprimer l'état du localStorage pour les autres submenus
                    localStorage.removeItem(el.id);
                }
            });
        }

        // Nouvelle fonction pour restaurer l'état des submenus
        function restoreSubmenuStates() {
            const submenus = document.querySelectorAll('[id$="Submenu"]');
            
            submenus.forEach(submenu => {
                const submenuId = submenu.id;
                const headerId = submenuId.replace('Submenu', 'Header');
                const arrowId = submenuId.replace('Submenu', 'Arrow');
                
                const header = document.getElementById(headerId);
                const arrow = document.getElementById(arrowId);
                
                // Vérifier si le submenu était ouvert
                if (localStorage.getItem(submenuId) === 'open') {
                    submenu.classList.remove('hidden');
                    submenu.style.maxHeight = submenu.scrollHeight + "px";
                    header.classList.add('submenu-active');
                    arrow.classList.add('rotate-180');
                }
            });
        }
        
        // Ripple effect pour les boutons - Version améliorée pour éviter les décalages
        document.addEventListener('DOMContentLoaded', function() {
            // Restaurer l'état des submenus
            restoreSubmenuStates();
            document.querySelectorAll('.ripple').forEach(button => {
                button.addEventListener('click', function(e) {
                    // Prévenir les effets ripple multiples
                    const existingRipples = button.querySelectorAll('.ripple-effect');
                    existingRipples.forEach(r => r.remove());
                    
                    const circle = document.createElement('span');
                    const diameter = Math.max(button.clientWidth, button.clientHeight);
                    
                    circle.style.width = circle.style.height = `${diameter}px`;
                    const rect = button.getBoundingClientRect();
                    
                    circle.style.left = `${e.clientX - rect.left - (diameter / 2)}px`;
                    circle.style.top = `${e.clientY - rect.top - (diameter / 2)}px`;
                    circle.classList.add('ripple-effect');
                    
                    button.appendChild(circle);
                    
                    // Supprimer après la fin de l'animation
                    setTimeout(() => {
                        circle.remove();
                    }, 600);
                });
            });
        });
        
        // Event listener for sidebar toggle button
        document.getElementById('sidebarToggle').addEventListener('click', toggleSidebar);
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[id$="Dropdown"]');
            const dropdownButtons = document.querySelectorAll('[onclick^="toggleDropdown"]');
            
            let isClickInsideDropdown = false;
            
            dropdownButtons.forEach(button => {
                if (button.contains(event.target)) {
                    isClickInsideDropdown = true;
                }
            });
            
            dropdowns.forEach(dropdown => {
                if (dropdown.contains(event.target)) {
                    isClickInsideDropdown = true;
                }
            });
            
            if (!isClickInsideDropdown) {
                dropdowns.forEach(dropdown => {
                    if (!dropdown.classList.contains('hidden')) {
                        dropdown.classList.add('hidden');
                    }
                });
            }
        });
        
        // Set active navigation item based on current URL - Version améliorée
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            
            document.querySelectorAll('.nav-link').forEach(link => {
                if (link.getAttribute('href') && link.getAttribute('href') !== '#' && currentPath.includes(link.getAttribute('href'))) {
                    link.classList.add('active');
                    link.classList.add('bg-navy-700');
                    
                    // Si c'est dans un sous-menu, ouvrir le menu parent sans décalage
                    const parentSubmenu = link.closest('[id$="Submenu"]');
                    if (parentSubmenu) {
                        const parentId = parentSubmenu.id;
                        const parentHeader = document.getElementById(parentId.replace('Submenu', 'Header'));
                        const parentArrow = document.getElementById(parentId.replace('Submenu', 'Arrow'));
                        
                        // Ouverture sans animation pour éviter les décalages initiaux
                        parentSubmenu.classList.remove('hidden');
                        parentSubmenu.style.maxHeight = parentSubmenu.scrollHeight + "px";
                        parentHeader.classList.add('submenu-active');
                        parentArrow.classList.add('rotate-180');
                    }
                }
            });
        });
        
        // Initialize - For mobile view, hide sidebar by default
        if (window.innerWidth < 1024) {
            document.getElementById('sidebar').classList.add('-translate-x-full');
        }
    </script>
    
</body>
</html>