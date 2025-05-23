<!-- resources/views/clients/index.blade.php -->
@extends('layouts.app')

@section('title', 'Gestion des clients')

@section('breadcrumb')
<i class="fas fa-chevron-right mx-2 text-gray-400"></i>
<span class="text-gray-700">Clients</span>
@endsection

@section('page-title', 'Gestion des clients')
@section('page-subtitle', 'Analyse de fidélité et suivi des commandes')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

* {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

/* Animations et effets */
.card-hover {
    transition: all 0.2s ease-in-out;
}

.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    transition: transform 0.2s ease;
}

.card-hover:hover .stat-icon {
    transform: scale(1.05);
}

.loyalty-progress {
    background: linear-gradient(90deg, #fbbf24 0%, #10b981 100%);
}

.table-row {
    transition: all 0.15s ease;
}

.table-row:hover {
    background-color: #f9fafb;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.action-btn {
    transition: all 0.15s ease;
}

.action-btn:hover {
    transform: scale(1.05);
}

.filter-card {
    background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
}

/* Table responsive simplifiée */
.simple-table {
    width: 100%;
    min-width: 800px; /* Beaucoup plus petit */
}

.table-container {
    overflow-x: auto;
    border-radius: 0.75rem;
    scrollbar-width: thin;
    scrollbar-color: rgba(59, 130, 246, 0.4) rgba(243, 244, 246, 0.3);
}

.table-container::-webkit-scrollbar {
    height: 6px;
}

.table-container::-webkit-scrollbar-track {
    background: rgba(243, 244, 246, 0.3);
    border-radius: 3px;
}

.table-container::-webkit-scrollbar-thumb {
    background: rgba(59, 130, 246, 0.4);
    border-radius: 3px;
}

.table-container::-webkit-scrollbar-thumb:hover {
    background: rgba(59, 130, 246, 0.6);
}

/* Mobile optimizations */
@media (max-width: 640px) {
    .stat-card {
        padding: 0.75rem !important;
    }
    
    .stat-value {
        font-size: 1.125rem !important;
    }
    
    .mobile-card {
        margin: 0.25rem;
        padding: 1rem !important;
    }
}

@media (max-width: 768px) {
    .filter-grid {
        grid-template-columns: 1fr !important;
        gap: 0.75rem !important;
    }
    
    .sort-controls {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 0.5rem !important;
    }
}

@media (max-width: 375px) {
    .mobile-card {
        padding: 0.75rem !important;
    }
    
    .xs-text-sm {
        font-size: 0.75rem !important;
    }
}

/* Badge styles */
.badge-vip {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: white;
}

.badge-premium {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
}

.badge-fidele {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.badge-standard {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: white;
}
</style>

<div class="space-y-4 sm:space-y-6">
    <!-- Statistiques globales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
        <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border-l-4 border-blue-500 card-hover stat-card">
            <div class="flex items-center">
                <div class="rounded-xl bg-blue-50 p-2 sm:p-3 stat-icon flex-shrink-0">
                    <i class="fas fa-users text-blue-600 text-lg sm:text-xl"></i>
                </div>
                <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                    <h3 class="text-gray-600 text-xs sm:text-sm font-medium truncate">Total clients</h3>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 stat-value">{{ $globalStats->total_clients }}</p>
                </div>
            </div>
            <div class="mt-3 sm:mt-4 text-blue-600 text-xs sm:text-sm font-medium">
                <i class="fas fa-chart-line mr-1"></i> Base active
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border-l-4 border-green-500 card-hover stat-card">
            <div class="flex items-center">
                <div class="rounded-xl bg-green-50 p-2 sm:p-3 stat-icon flex-shrink-0">
                    <i class="fas fa-shopping-cart text-green-600 text-lg sm:text-xl"></i>
                </div>
                <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                    <h3 class="text-gray-600 text-xs sm:text-sm font-medium truncate">Commandes</h3>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 stat-value">{{ number_format($globalStats->total_orders) }}</p>
                </div>
            </div>
            <div class="mt-3 sm:mt-4 text-green-600 text-xs sm:text-sm font-medium">
                <i class="fas fa-arrow-up mr-1"></i> 
                <span class="hidden sm:inline">Moy: </span>{{ number_format($globalStats->average_order_value, 0, ',', ' ') }}
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border-l-4 border-purple-500 card-hover stat-card">
            <div class="flex items-center">
                <div class="rounded-xl bg-purple-50 p-2 sm:p-3 stat-icon flex-shrink-0">
                    <i class="fas fa-boxes text-purple-600 text-lg sm:text-xl"></i>
                </div>
                <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                    <h3 class="text-gray-600 text-xs sm:text-sm font-medium truncate">Cartons</h3>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 stat-value">{{ number_format($globalStats->total_cartons) }}</p>
                </div>
            </div>
            <div class="mt-3 sm:mt-4 text-purple-600 text-xs sm:text-sm font-medium flex items-center gap-2">
                <span class="inline-flex items-center">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-1"></span>
                    {{ $globalStats->petit_percentage }}%
                </span>
                <span class="inline-flex items-center">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                    {{ $globalStats->grand_percentage }}%
                </span>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border-l-4 border-amber-500 card-hover stat-card">
            <div class="flex items-center">
                <div class="rounded-xl bg-amber-50 p-2 sm:p-3 stat-icon flex-shrink-0">
                    <i class="fas fa-coins text-amber-600 text-lg sm:text-xl"></i>
                </div>
                <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                    <h3 class="text-gray-600 text-xs sm:text-sm font-medium truncate">CA Total</h3>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 stat-value">{{ number_format($globalStats->total_revenue / 1000000, 1) }}M</p>
                </div>
            </div>
            <div class="mt-3 sm:mt-4 text-amber-600 text-xs sm:text-sm font-medium">
                <i class="fas fa-chart-bar mr-1"></i> FCFA
            </div>
        </div>
    </div>

    <!-- Top 3 clients - Version condensée -->
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
        <div class="flex items-center mb-4 sm:mb-6">
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                <i class="fas fa-trophy text-white text-sm sm:text-base"></i>
            </div>
            <h2 class="text-lg sm:text-xl font-bold text-gray-900">Top 3 des meilleurs clients</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
            @foreach($topClients->take(3) as $index => $client)
            <div class="relative bg-gradient-to-br from-{{ $index === 0 ? 'amber' : ($index === 1 ? 'slate' : 'orange') }}-50 to-{{ $index === 0 ? 'amber' : ($index === 1 ? 'slate' : 'orange') }}-100 rounded-xl p-3 sm:p-4 border border-{{ $index === 0 ? 'amber' : ($index === 1 ? 'slate' : 'orange') }}-200 card-hover">
                <div class="absolute top-2 right-2 w-6 h-6 sm:w-8 sm:h-8 bg-{{ $index === 0 ? 'amber' : ($index === 1 ? 'slate' : 'orange') }}-500 rounded-full flex items-center justify-center text-white font-bold text-xs sm:text-sm">
                    {{ $index + 1 }}
                </div>
                <div class="pr-8 sm:pr-10">
                    <div class="flex items-center space-x-2 mb-2 sm:mb-3">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-{{ $index === 0 ? 'amber' : ($index === 1 ? 'slate' : 'orange') }}-500 flex items-center justify-center text-white font-bold text-xs sm:text-sm flex-shrink-0">
                            {{ substr($client->client_firstname, 0, 1) }}{{ substr($client->client_name, 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="font-semibold text-gray-900 text-xs sm:text-sm truncate">{{ $client->client_firstname }} {{ $client->client_name }}</h3>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <p class="text-lg sm:text-xl font-bold text-gray-900">{{ number_format($client->total_amount / 1000, 0) }}K</p>
                        <p class="text-xs text-gray-600">{{ $client->total_orders }} commandes</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Filtres et recherche - Version compacte -->
    <div class="filter-card rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100">
        <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Filtres et recherche</h2>
        <form method="GET" action="{{ route('clients.index') }}" class="space-y-4 sm:space-y-6">
            <!-- Recherche -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                <div class="relative">
                    <input type="text" id="search" name="search" value="{{ $searchTerm }}" 
                           placeholder="Nom, prénom, téléphone..." 
                           class="w-full pl-10 sm:pl-11 pr-4 py-2.5 sm:py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm sm:text-base">
                    <i class="fas fa-search absolute left-3 sm:left-4 top-3 sm:top-4 text-gray-400 text-sm"></i>
                </div>
            </div>
            
            <!-- Filtres -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 filter-grid">
                <div>
                    <label for="preferred_carton" class="block text-sm font-medium text-gray-700 mb-2">Préférence carton</label>
                    <select id="preferred_carton" name="preferred_carton" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm sm:text-base">
                        <option value="">Tous les types</option>
                        <option value="petit" {{ $preferredCarton === 'petit' ? 'selected' : '' }}>Petits cartons</option>
                        <option value="grand" {{ $preferredCarton === 'grand' ? 'selected' : '' }}>Grands cartons</option>
                        <option value="mixte" {{ $preferredCarton === 'mixte' ? 'selected' : '' }}>Clients mixtes</option>
                    </select>
                </div>
                
                <div>
                    <label for="min_amount" class="block text-sm font-medium text-gray-700 mb-2">Montant min. (FCFA)</label>
                    <input type="number" id="min_amount" name="min_amount" value="{{ $minAmount }}" 
                           placeholder="0" min="0" step="1000"
                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm sm:text-base">
                </div>
                
                <div>
                    <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-2">Statut client</label>
                    <select id="status_filter" name="status_filter" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm sm:text-base">
                        <option value="">Tous les statuts</option>
                        <option value="active">Très actif</option>
                        <option value="moderate">Actif</option>
                        <option value="low">Modéré</option>
                        <option value="inactive">Inactif</option>
                    </select>
                </div>
            </div>
            
            <!-- Boutons -->
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <button type="submit" class="flex-1 sm:flex-initial px-4 sm:px-6 py-2.5 sm:py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 font-medium text-sm sm:text-base">
                    <i class="fas fa-filter mr-2"></i> Filtrer
                </button>
                <a href="{{ route('clients.index') }}" class="flex-1 sm:flex-initial px-4 sm:px-6 py-2.5 sm:py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200 text-center font-medium text-sm sm:text-base">
                    <i class="fas fa-times mr-2"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau des clients - VERSION SIMPLIFIÉE -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-100">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0">
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900">Liste des clients</h2>
                    <p class="text-gray-600 text-xs sm:text-sm mt-1">{{ $clients->total() }} clients trouvés</p>
                </div>
            </div>
        </div>
        
        <!-- Version Desktop/Tablet du tableau - SIMPLIFIÉ -->
        <div class="hidden lg:block">
            <div class="table-container">
                <table class="simple-table divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[200px]">
                                Client
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                                Commandes
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[140px]">
                                Montant total
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                                Score fidélité
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[100px]">
                                Statut
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($clients as $client)
                        <tr class="table-row">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 xl:h-11 xl:w-11 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-sm font-medium text-blue-800">
                                            {{ substr($client->client_firstname, 0, 1) }}{{ substr($client->client_name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="ml-4 min-w-0 flex-1">
                                        <div class="text-sm font-medium text-gray-900 truncate">
                                            {{ $client->client_firstname }} {{ $client->client_name }}
                                        </div>
                                        <div class="text-xs text-gray-500 truncate">
                                            {{ $client->client_phone ?? $client->client_email ?? 'Pas de contact' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $client->total_orders }}</div>
                                <div class="text-xs text-gray-500">{{ $client->total_quantity }} cartons</div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($client->total_amount, 0, ',', ' ') }}</div>
                                <div class="text-xs text-gray-500">FCFA</div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-12 xl:w-16 bg-gray-200 rounded-full h-2">
                                        <div class="loyalty-progress h-2 rounded-full" 
                                             style="width: {{ min($client->loyalty_score, 100) }}%"></div>
                                    </div>
                                    <span class="ml-2 text-xs xl:text-sm font-medium text-gray-900">{{ $client->loyalty_score }}</span>
                                </div>
                                @if($client->loyalty_score >= 80)
                                    <div class="text-xs font-medium mt-1 px-2 py-0.5 rounded-full badge-vip">⭐ VIP</div>
                                @elseif($client->loyalty_score >= 60)
                                    <div class="text-xs font-medium mt-1 px-2 py-0.5 rounded-full badge-premium">💎 Premium</div>
                                @elseif($client->loyalty_score >= 40)
                                    <div class="text-xs font-medium mt-1 px-2 py-0.5 rounded-full badge-fidele">✅ Fidèle</div>
                                @else
                                    <div class="text-xs font-medium mt-1 px-2 py-0.5 rounded-full badge-standard">👤 Standard</div>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $client->status['color'] }}-100 text-{{ $client->status['color'] }}-700">
                                    {{ $client->status['label'] }}
                                </span>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $client->days_since_last }} j
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-1 xl:space-x-2">
                                    <a href="{{ route('clients.show', [$client->client_name, $client->client_firstname]) }}" 
                                       class="action-btn px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-all duration-200 text-xs font-medium"
                                       title="Voir détails complets">
                                        <i class="fas fa-eye mr-1"></i>Détails
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun client trouvé</h3>
                                    <p class="text-gray-500">Aucun client ne correspond aux critères de recherche.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Version Mobile/Tablet (cartes) - OPTIMISÉE -->
        <div class="lg:hidden">
            <div class="divide-y divide-gray-100">
                @forelse($clients as $client)
                <div class="mobile-card p-4 sm:p-5 hover:bg-gray-50 transition-colors duration-200">
                    <!-- En-tête client -->
                    <div class="flex items-start justify-between mb-3 sm:mb-4">
                        <div class="flex items-center min-w-0 flex-1 mr-3">
                            <div class="flex-shrink-0 h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-sm sm:text-lg font-medium text-blue-800">
                                    {{ substr($client->client_firstname, 0, 1) }}{{ substr($client->client_name, 0, 1) }}
                                </span>
                            </div>
                            <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                                <div class="text-base sm:text-lg font-medium text-gray-900 truncate xs-text-sm">
                                    {{ $client->client_firstname }} {{ $client->client_name }}
                                </div>
                                <div class="text-xs sm:text-sm text-gray-500 truncate">
                                    {{ $client->client_phone ?? $client->client_email ?? 'Pas de contact' }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Score de fidélité mobile -->
                        @if($client->loyalty_score >= 80)
                            <div class="text-xs font-medium px-2 py-1 rounded-full badge-vip flex-shrink-0">⭐ VIP</div>
                        @elseif($client->loyalty_score >= 60)
                            <div class="text-xs font-medium px-2 py-1 rounded-full badge-premium flex-shrink-0">💎 Premium</div>
                        @elseif($client->loyalty_score >= 40)
                            <div class="text-xs font-medium px-2 py-1 rounded-full badge-fidele flex-shrink-0">✅ Fidèle</div>
                        @else
                            <div class="text-xs font-medium px-2 py-1 rounded-full badge-standard flex-shrink-0">👤 Standard</div>
                        @endif
                    </div>

                    <!-- Métriques principales -->
                    <div class="grid grid-cols-3 gap-3 sm:gap-4 mb-3 sm:mb-4">
                        <div class="bg-blue-50 p-2.5 sm:p-3 rounded-xl text-center">
                            <div class="text-xs text-gray-600 mb-1">Commandes</div>
                            <div class="text-base sm:text-lg font-bold text-blue-600">{{ $client->total_orders }}</div>
                        </div>
                        
                        <div class="bg-green-50 p-2.5 sm:p-3 rounded-xl text-center">
                            <div class="text-xs text-gray-600 mb-1">Cartons</div>
                            <div class="text-base sm:text-lg font-bold text-green-600">{{ $client->total_quantity }}</div>
                        </div>
                        
                        <div class="bg-purple-50 p-2.5 sm:p-3 rounded-xl text-center">
                            <div class="text-xs text-gray-600 mb-1">Score</div>
                            <div class="text-base sm:text-lg font-bold text-purple-600">{{ $client->loyalty_score }}</div>
                        </div>
                    </div>

                    <!-- Montant total -->
                    <div class="mb-4 text-center p-3 bg-amber-50 rounded-xl">
                        <div class="text-xs text-gray-600 mb-1">Total dépensé</div>
                        <div class="text-lg sm:text-xl font-bold text-amber-600">{{ number_format($client->total_amount, 0, ',', ' ') }} FCFA</div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2 sm:gap-3">
                        <a href="{{ route('clients.show', ['name' => $client->client_name, 'firstname' => $client->client_firstname]) }}"
                           class="flex-1 px-3 sm:px-4 py-2 bg-blue-600 text-white text-xs sm:text-sm rounded-lg hover:bg-blue-700 transition-colors duration-200 text-center font-medium">
                            <i class="fas fa-eye mr-1"></i> Voir détails complets
                        </a>
                    </div>
                </div>
                @empty
                <div class="p-8 sm:p-12 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-users text-3xl sm:text-4xl text-gray-300 mb-3 sm:mb-4"></i>
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Aucun client trouvé</h3>
                        <p class="text-sm sm:text-base text-gray-500">Aucun client ne correspond aux critères de recherche.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Pagination -->
        @if($clients->hasPages())
        <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50/50 border-t border-gray-100">
            <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
                <div class="text-xs sm:text-sm text-gray-700 order-2 sm:order-1">
                    Affichage de {{ $clients->firstItem() }} à {{ $clients->lastItem() }} sur {{ $clients->total() }} résultats
                </div>
                <div class="order-1 sm:order-2">
                    {{ $clients->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page clients chargée avec {{ $clients->count() }} clients affichés');
    
    // Gestion des filtres
    const filterInputs = document.querySelectorAll('input, select');
    filterInputs.forEach(input => {
        if (input.type === 'number' || input.type === 'text') {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    this.form.submit();
                }
            });
        }
    });

    // Animation au scroll pour les cartes
    const cards = document.querySelectorAll('.card-hover, .mobile-card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    cards.forEach(card => {
        card.style.opacity = '0.8';
        card.style.transform = 'translateY(10px)';
        card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        observer.observe(card);
    });

    // Optimisation pour très petits écrans
    function adjustForSmallScreens() {
        const viewportWidth = window.innerWidth;
        
        if (viewportWidth < 376) {
            document.documentElement.style.setProperty('--mobile-padding', '0.5rem');
        } else {
            document.documentElement.style.setProperty('--mobile-padding', '1rem');
        }
    }

    adjustForSmallScreens();
    window.addEventListener('resize', adjustForSmallScreens);

    // Amélioration tactile sur mobile
    if ('ontouchstart' in window) {
        const touchableElements = document.querySelectorAll('button, a, .action-btn, .mobile-card');
        touchableElements.forEach(element => {
            element.addEventListener('touchstart', function() {
                this.style.transform = 'scale(0.98)';
            });
            
            element.addEventListener('touchend', function() {
                this.style.transform = 'scale(1)';
            });
        });
    }
});
</script>

@endsection