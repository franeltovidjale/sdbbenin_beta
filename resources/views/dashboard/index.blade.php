{{-- <!-- resources/views/dashboard/index.blade.php -->
@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('breadcrumb')
    <!-- Pas de breadcrumb sur la page d'accueil -->
@endsection

@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Bienvenue dans votre espace d\'administration')

@section('content')
    <!-- Statistiques en cartes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500 hover:shadow-md transition-all duration-300">
            <div class="flex items-center">
                <div class="rounded-full bg-blue-50 p-3">
                    <i class="fas fa-users text-blue-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm font-medium">Utilisateurs</h3>
                    <p class="text-2xl font-bold text-gray-800">1 254</p>
                </div>
            </div>
            <div class="mt-3 text-green-500 text-sm font-medium flex items-center">
                <i class="fas fa-arrow-up mr-1"></i> +12% ce mois
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-navy-500 hover:shadow-md transition-all duration-300">
            <div class="flex items-center">
                <div class="rounded-full bg-navy-50 p-3">
                    <i class="fas fa-shopping-cart text-navy-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm font-medium">Commandes</h3>
                    <p class="text-2xl font-bold text-gray-800">458</p>
                </div>
            </div>
            <div class="mt-3 text-green-500 text-sm font-medium flex items-center">
                <i class="fas fa-arrow-up mr-1"></i> +5% cette semaine
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500 hover:shadow-md transition-all duration-300">
            <div class="flex items-center">
                <div class="rounded-full bg-green-50 p-3">
                    <i class="fas fa-euro-sign text-green-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm font-medium">Revenus</h3>
                    <p class="text-2xl font-bold text-gray-800">24 568 €</p>
                </div>
            </div>
            <div class="mt-3 text-red-500 text-sm font-medium flex items-center">
                <i class="fas fa-arrow-down mr-1"></i> -2% cette semaine
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-amber-500 hover:shadow-md transition-all duration-300">
            <div class="flex items-center">
                <div class="rounded-full bg-amber-50 p-3">
                    <i class="fas fa-comments text-amber-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm font-medium">Messages</h3>
                    <p class="text-2xl font-bold text-gray-800">32</p>
                </div>
            </div>
            <div class="mt-3 text-gray-500 text-sm font-medium flex items-center">
                <i class="fas fa-equals mr-1"></i> Stable
            </div>
        </div>
    </div>
    
    <!-- Graphiques et tableaux -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Graphique de gauche -->
        <div class="bg-white rounded-lg shadow-sm p-6 lg:col-span-2 hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-navy-800">Aperçu des ventes</h2>
                <div class="flex space-x-2">
                    <button class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-md text-sm font-medium hover:bg-blue-100 transition-colors">Jour</button>
                    <button class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded-md text-sm font-medium hover:bg-gray-200 transition-colors">Semaine</button>
                    <button class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded-md text-sm font-medium hover:bg-gray-200 transition-colors">Mois</button>
                </div>
            </div>
            <div class="relative h-72">
                <!-- Ici, vous intégreriez un graphique avec une bibliothèque comme Chart.js -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="inline-flex justify-center items-center w-16 h-16 rounded-full bg-blue-50 mb-4">
                            <i class="fas fa-chart-line text-blue-500 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 text-sm">Graphique des ventes (à intégrer avec Chart.js)</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Liste d'activités récentes -->
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-all duration-300">
            <h2 class="text-lg font-bold text-navy-800 mb-6">Activités récentes</h2>
            <div class="space-y-5">
                <div class="flex items-start">
                    <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 flex-shrink-0">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-800">Nouvel utilisateur inscrit</p>
                        <p class="text-xs text-gray-500 mt-1">Il y a 25 minutes</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-500 flex-shrink-0">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-800">Nouvelle commande #12345</p>
                        <p class="text-xs text-gray-500 mt-1">Il y a 42 minutes</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-500 flex-shrink-0">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-800">Stock faible pour "Produit X"</p>
                        <p class="text-xs text-gray-500 mt-1">Il y a 2 heures</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-500 flex-shrink-0">
                        <i class="fas fa-times"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-800">Commande #12333 annulée</p>
                        <p class="text-xs text-gray-500 mt-1">Il y a 3 heures</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="w-10 h-10 rounded-full bg-navy-50 flex items-center justify-center text-navy-500 flex-shrink-0">
                        <i class="fas fa-comment"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-800">Nouveau commentaire sur l'article</p>
                        <p class="text-xs text-gray-500 mt-1">Il y a 5 heures</p>
                    </div>
                </div>
            </div>
            <div class="mt-6 text-center">
                <a href="#" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">
                    Voir toutes les activités
                    <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Commandes récentes -->
    <div class="mt-6 bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-all duration-300">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-bold text-navy-800">Commandes récentes</h2>
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <input type="text" placeholder="Rechercher..." class="w-48 pl-9 pr-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
                <button class="p-1.5 bg-gray-100 rounded-lg text-gray-600 hover:bg-gray-200 transition-colors">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Client
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Montant
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-navy-700">#12345</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-navy-100 flex items-center justify-center">
                                    <span class="text-xs font-medium text-navy-800">JD</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Jean Dupont</div>
                                    <div class="text-xs text-gray-500">jean@exemple.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">27/04/2025</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">142,50 €</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Livré
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex space-x-2">
                                <button class="p-1 rounded-md hover:bg-blue-50 text-blue-600 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="p-1 rounded-md hover:bg-blue-50 text-blue-600 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="p-1 rounded-md hover:bg-red-50 text-red-600 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-navy-700">#12344</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-xs font-medium text-blue-800">ML</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Marie Leroy</div>
                                    <div class="text-xs text-gray-500">marie@exemple.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">26/04/2025</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">89,99 €</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                En cours
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex space-x-2">
                                <button class="p-1 rounded-md hover:bg-blue-50 text-blue-600 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="p-1 rounded-md hover:bg-blue-50 text-blue-600 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="p-1 rounded-md hover:bg-red-50 text-red-600 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-navy-700">#12343</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-800">PB</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Pierre Blanc</div>
                                    <div class="text-xs text-gray-500">pierre@exemple.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">26/04/2025</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">214,75 €</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Annulée
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex space-x-2">
                                <button class="p-1 rounded-md hover:bg-blue-50 text-blue-600 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="p-1 rounded-md hover:bg-blue-50 text-blue-600 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="p-1 rounded-md hover:bg-red-50 text-red-600 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center">
            <div class="text-sm text-gray-500 mb-3 sm:mb-0">
                Affichage de 3 sur 25 entrées
            </div>
            <div class="flex">
                <button class="p-2 bg-white text-gray-600 rounded-l-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-chevron-left text-xs"></i>
                </button>
                <button class="p-2 px-3.5 bg-blue-600 text-white border border-blue-600">1</button>
                <button class="p-2 px-3.5 bg-white text-gray-600 border border-gray-300 hover:bg-gray-50 transition-colors">2</button>
                <button class="p-2 px-3.5 bg-white text-gray-600 border border-gray-300 hover:bg-gray-50 transition-colors">3</button>
                <button class="p-2 bg-white text-gray-600 rounded-r-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-chevron-right text-xs"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Cartes récapitulatives -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Tâches en cours -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-all duration-300">
            <div class="px-6 py-4 bg-gradient-to-r from-navy-700 to-blue-500 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold">Tâches en cours</h3>
                    <div class="bg-white bg-opacity-30 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fas fa-tasks text-white"></i>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Mise à jour du système</span>
                            <span class="text-sm text-gray-500">70%</span>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full">
                            <div class="h-2 bg-blue-500 rounded-full" style="width: 70%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Migration des données</span>
                            <span class="text-sm text-gray-500">45%</span>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full">
                            <div class="h-2 bg-blue-500 rounded-full" style="width: 45%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Déploiement serveurs</span>
                            <span class="text-sm text-gray-500">90%</span>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full">
                            <div class="h-2 bg-green-500 rounded-full" style="width: 90%"></div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 pt-4 border-t border-gray-100">
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center">
                        <span>Voir toutes les tâches</span>
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Messages récents -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-all duration-300">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-cyan-500 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold">Messages récents</h3>
                    <div class="bg-white bg-opacity-30 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fas fa-envelope text-white"></i>
                    </div>
                </div>
            </div>
            <div class="divide-y divide-gray-100">
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-navy-100 flex items-center justify-center text-navy-700 font-semibold">JD</div>
                        </div>
                        <div class="ml-3 flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">Jean Dupont</p>
                                <p class="text-xs text-gray-500">10:23</p>
                            </div>
                            <p class="text-sm text-gray-600 mt-1 line-clamp-2">Bonjour, pourriez-vous vérifier les dernières mises à jour du serveur principal ?</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-semibold">ML</div>
                        </div>
                        <div class="ml-3 flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">Marie Leroy</p>
                                <p class="text-xs text-gray-500">Hier</p>
                            </div>
                            <p class="text-sm text-gray-600 mt-1 line-clamp-2">Concernant le dossier #2453, pouvons-nous planifier une réunion cette semaine ?</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center">
                        <span>Voir tous les messages</span>
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Événements à venir -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-all duration-300">
            <div class="px-6 py-4 bg-gradient-to-r from-violet-500 to-purple-600 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold">Événements à venir</h3>
                    <div class="bg-white bg-opacity-30 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fas fa-calendar text-white"></i>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex">
                        <div class="flex-shrink-0 w-12 text-center">
                            <div class="text-xl font-bold text-navy-800">12</div>
                            <div class="text-xs text-gray-500">MAI</div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-semibold text-gray-800">Réunion d'équipe</h4>
                            <p class="text-xs text-gray-500 mt-1">10:00 - 11:30 | Salle de conférence</p>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="flex-shrink-0 w-12 text-center">
                            <div class="text-xl font-bold text-navy-800">15</div>
                            <div class="text-xs text-gray-500">MAI</div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-semibold text-gray-800">Présentation client</h4>
                            <p class="text-xs text-gray-500 mt-1">14:30 - 16:00 | Visioconférence</p>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="flex-shrink-0 w-12 text-center">
                            <div class="text-xl font-bold text-navy-800">20</div>
                            <div class="text-xs text-gray-500">MAI</div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-semibold text-gray-800">Formation technique</h4>
                            <p class="text-xs text-gray-500 mt-1">09:00 - 17:00 | Centre de formation</p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 pt-4 border-t border-gray-100">
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center">
                        <span>Voir tous les événements</span>
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection --}}


<!-- resources/views/dashboard/index.blade.php -->
@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('breadcrumb')
    <!-- Pas de breadcrumb sur la page d'accueil -->
@endsection

@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Aperçu global de la production et des stocks')

@section('content')
    <!-- Statistiques en cartes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500 hover:shadow-md transition-all duration-300">
            <div class="flex items-center">
                <div class="rounded-full bg-blue-50 p-3">
                    <i class="fas fa-industry text-blue-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm font-medium">Productions</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalProductions }}</p>
                </div>
            </div>
            <div class="mt-3 text-green-500 text-sm font-medium flex items-center">
                <i class="fas fa-arrow-up mr-1"></i> {{ $productionPercentage }}% ce mois
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-navy-500 hover:shadow-md transition-all duration-300">
            <div class="flex items-center">
                <div class="rounded-full bg-navy-50 p-3">
                    <i class="fas fa-boxes text-navy-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm font-medium">Articles en stock</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalArticles }}</p>
                </div>
            </div>
            <div class="mt-3 text-{{ $articlesStatus['color'] }}-500 text-sm font-medium flex items-center">
                <i class="fas fa-{{ $articlesStatus['icon'] }} mr-1"></i> {{ $articlesStatus['message'] }}
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500 hover:shadow-md transition-all duration-300">
            <div class="flex items-center">
                <div class="rounded-full bg-green-50 p-3">
                    <i class="fas fa-cubes text-green-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm font-medium">Cartons produits</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalCartons }}</p>
                </div>
            </div>
            <div class="mt-3 text-green-500 text-sm font-medium flex items-center">
                <i class="fas fa-arrow-up mr-1"></i> {{ $cartonsPercentage }}% cette semaine
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-amber-500 hover:shadow-md transition-all duration-300">
            <div class="flex items-center">
                <div class="rounded-full bg-amber-50 p-3">
                    <i class="fas fa-exclamation-triangle text-amber-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm font-medium">Articles en alerte</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $lowStockCount }}</p>
                </div>
            </div>
            <div class="mt-3 text-amber-500 text-sm font-medium flex items-center">
                <i class="fas fa-arrow-{{ $lowStockTrend['direction'] }} mr-1"></i> {{ $lowStockTrend['message'] }}
            </div>
        </div>
    </div>
    
    <!-- Graphiques et tableaux -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Graphique de gauche -->
        <div class="bg-white rounded-lg shadow-sm p-6 lg:col-span-2 hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-navy-800">Production mensuelle</h2>
                <div class="flex space-x-2">
                    <button class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-md text-sm font-medium hover:bg-blue-100 transition-colors">Semaine</button>
                    <button class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded-md text-sm font-medium hover:bg-gray-200 transition-colors">Mois</button>
                    <button class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded-md text-sm font-medium hover:bg-gray-200 transition-colors">Année</button>
                </div>
            </div>
            <div class="relative h-72">
                <!-- Ici, vous intégreriez un graphique avec une bibliothèque comme Chart.js -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="inline-flex justify-center items-center w-16 h-16 rounded-full bg-blue-50 mb-4">
                            <i class="fas fa-chart-line text-blue-500 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 text-sm">Graphique de production mensuelle (à intégrer avec Chart.js)</p>
                        <p class="text-xs text-gray-400 mt-2">Affichant le nombre de productions et de cartons par semaine</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Alertes de stock -->
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-all duration-300">
            <h2 class="text-lg font-bold text-navy-800 mb-6">Articles en alerte de stock</h2>
            <div class="space-y-5">
                @forelse($lowStockArticles as $article)
                <div class="flex items-start">
                    <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-500 flex-shrink-0">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-800">{{ $article->name }}</p>
                        <div class="flex justify-between mt-1">
                            <p class="text-xs text-gray-500">Stock restant: <span class="font-medium text-amber-600">{{ $article->stock_quantity }}</span></p>
                            <p class="text-xs text-gray-500">Seuil min: {{ $lowStockThreshold }}</p>
                        </div>
                        <div class="mt-2 h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-1.5 bg-amber-500 rounded-full" style="width: {{ min(($article->stock_quantity / $lowStockThreshold) * 100, 100) }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <div class="inline-flex justify-center items-center w-12 h-12 rounded-full bg-green-50 mb-3">
                        <i class="fas fa-check text-green-500 text-lg"></i>
                    </div>
                    <p class="text-gray-500 text-sm">Aucun article en alerte de stock</p>
                </div>
                @endforelse
                
                @if(count($lowStockArticles) > 0)
                <div class="mt-2 pt-4 border-t border-gray-100">
                    <a href="{{ route('articles.index', ['filter' => 'low_stock']) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center">
                        <span>Voir tous les articles en alerte</span>
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Productions récentes -->
    <div class="mt-6 bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-all duration-300">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-bold text-navy-800">Productions récentes</h2>
            <a href="{{ route('production.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">
                Voir toutes les productions
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nom
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Quantité
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentProductions as $production)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-navy-700">#{{ $production->id }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $production->production_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $production->type->name ?? 'Non défini' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ date('d/m/Y', strtotime($production->production_date)) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ number_format($production->qte_production, 2, ',', ' ') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($production->status == 'en cours')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                En cours
                            </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Terminé
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex space-x-2">
                                <a href="{{ route('production.show', $production->id) }}" class="p-1 rounded-md hover:bg-blue-50 text-blue-600 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($production->status == 'en cours')
                                <a href="{{ route('production.edit', $production->id) }}" class="p-1 rounded-md hover:bg-blue-50 text-blue-600 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Aucune production récente trouvée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Cartes récapitulatives -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Articles fréquemment utilisés -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-all duration-300">
            <div class="px-6 py-4 bg-gradient-to-r from-navy-700 to-blue-500 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold">Articles fréquemment utilisés</h3>
                    <div class="bg-white bg-opacity-30 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fas fa-box text-white"></i>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($topArticles as $index => $article)
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $article->name }}</span>
                            <span class="text-sm text-gray-500">{{ $article->usage_count }} utilisations</span>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full">
                            <div class="h-2 {{ $index === 0 ? 'bg-blue-500' : ($index === 1 ? 'bg-blue-400' : 'bg-blue-300') }} rounded-full" style="width: {{ ($article->usage_count / $topArticles->first()->usage_count) * 100 }}%"></div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <p class="text-gray-500 text-sm">Aucune donnée disponible</p>
                    </div>
                    @endforelse
                </div>
                <div class="mt-5 pt-4 border-t border-gray-100">
                    <a href="{{ route('articles.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center">
                        <span>Voir tous les articles</span>
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Productions par type -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-all duration-300">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-cyan-500 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold">Productions par type</h3>
                    <div class="bg-white bg-opacity-30 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fas fa-chart-pie text-white"></i>
                    </div>
                </div>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($productionsByType as $type)
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $type->name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $type->productions_count }} productions
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center">
                    <p class="text-gray-500 text-sm">Aucun type de production défini</p>
                </div>
                @endforelse
                
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <a href="{{ route('types.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center">
                        <span>Voir tous les types</span>
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Productions terminées récemment -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-all duration-300">
            <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white">
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold">Productions terminées récemment</h3>
                    <div class="bg-white bg-opacity-30 rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fas fa-check-circle text-white"></i>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentCompletedProductions as $production)
                    <div class="flex">
                        <div class="flex-shrink-0 w-12 text-center">
                            <div class="text-xl font-bold text-navy-800">{{ date('d', strtotime($production->updated_at)) }}</div>
                            <div class="text-xs text-gray-500">{{ date('M', strtotime($production->updated_at)) }}</div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-semibold text-gray-800">{{ $production->production_name }}</h4>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $production->type->name ?? 'Type non défini' }} | 
                                {{ $production->total_cartons ?? '0' }} cartons produits
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <p class="text-gray-500 text-sm">Aucune production terminée récemment</p>
                    </div>
                    @endforelse
                </div>
                <div class="mt-5 pt-4 border-t border-gray-100">
                    <a href="{{ route('production.index', ['status' => 'terminé']) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center">
                        <span>Voir toutes les productions terminées</span>
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection