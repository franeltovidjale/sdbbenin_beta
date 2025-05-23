<!-- resources/views/clients/show.blade.php -->
@extends('layouts.app')

@section('title', 'Détails client - ' . $clientMetrics->client_firstname . ' ' . $clientMetrics->client_name)

@section('breadcrumb')
<i class="fas fa-chevron-right mx-2 text-gray-400"></i>
<a href="{{ route('clients.index') }}" class="hover:text-blue-700 transition-colors">Clients</a>
<i class="fas fa-chevron-right mx-2 text-gray-400"></i>
<span class="text-gray-700">{{ $clientMetrics->client_firstname }} {{ $clientMetrics->client_name }}</span>
@endsection

@section('page-title', $clientMetrics->client_firstname . ' ' . $clientMetrics->client_name)
@section('page-subtitle', 'Profil détaillé et historique des commandes')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

* {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

/* Animations et transitions */
.card-hover {
    transition: all 0.2s ease-in-out;
}

.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.1);
}

.stat-card {
    background: linear-gradient(145deg, #ffffff 0%, #f9fafb 100%);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.loyalty-progress {
    background: linear-gradient(90deg, #fbbf24 0%, #10b981 100%);
}

.progress-bar {
    background: linear-gradient(90deg, #3b82f6 0%, #1e40af 100%);
}

.progress-bar-green {
    background: linear-gradient(90deg, #10b981 0%, #059669 100%);
}

/* Badges améliorés */
.badge-vip {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: white;
    box-shadow: 0 2px 4px rgba(251, 191, 36, 0.3);
}

.badge-premium {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
}

.badge-fidele {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
}

.badge-standard {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: white;
    box-shadow: 0 2px 4px rgba(107, 114, 128, 0.3);
}

/* Table responsive pour historique */
.history-table-container {
    overflow-x: auto;
    border-radius: 0.75rem;
    scrollbar-width: thin;
    scrollbar-color: rgba(59, 130, 246, 0.4) rgba(243, 244, 246, 0.3);
}

.history-table-container::-webkit-scrollbar {
    height: 6px;
}

.history-table-container::-webkit-scrollbar-track {
    background: rgba(243, 244, 246, 0.3);
    border-radius: 3px;
}

.history-table-container::-webkit-scrollbar-thumb {
    background: rgba(59, 130, 246, 0.4);
    border-radius: 3px;
}

.history-table-container::-webkit-scrollbar-thumb:hover {
    background: rgba(59, 130, 246, 0.6);
}

.history-table {
    min-width: 900px;
    width: 100%;
}

/* Responsive design */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr !important;
    }
    
    .info-grid {
        grid-template-columns: 1fr !important;
    }
    
    .mobile-stat {
        text-align: center;
        padding: 1rem;
    }
}

@media (max-width: 640px) {
    .client-header {
        flex-direction: column !important;
        text-align: center;
    }
    
    .client-avatar {
        margin-right: 0 !important;
        margin-bottom: 1rem;
    }
    
    .stat-value {
        font-size: 1.25rem !important;
    }
}

/* Graphiques et visualisations */
.chart-container {
    position: relative;
    min-height: 200px;
}

.metric-icon {
    background: linear-gradient(135deg, #3b82f6, #1e40af);
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.contact-item {
    background: rgba(59, 130, 246, 0.05);
    border: 1px solid rgba(59, 130, 246, 0.1);
    transition: all 0.2s ease;
}

.contact-item:hover {
    background: rgba(59, 130, 246, 0.1);
    border-color: rgba(59, 130, 246, 0.2);
}
</style>

<div class="space-y-4 sm:space-y-6">
    <!-- En-tête client avec avatar et infos principales -->
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 lg:p-8">
        <div class="flex flex-col sm:flex-row items-center sm:items-start client-header">
            <div class="flex-shrink-0 h-16 w-16 sm:h-20 sm:w-20 lg:h-24 lg:w-24 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center client-avatar mr-0 sm:mr-6 lg:mr-8 shadow-lg">
                <span class="text-xl sm:text-lg lg:text-lg font-bold text-white">
                    {{ substr($clientMetrics->client_firstname, 0, 1) }}{{ substr($clientMetrics->client_name, 0, 1) }}
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h1 class="text-xl sm:text-xl lg:text-xl font-bold text-gray-900 mb-2">
                            {{ $clientMetrics->client_firstname }} {{ $clientMetrics->client_name }}
                        </h1>
                        <p class="text-sm sm:text-base text-gray-600 mb-3">
                            Client depuis {{ \Carbon\Carbon::parse($clientMetrics->first_order_date)->diffForHumans() }}
                        </p>
                        
                        <!-- Score de fidélité prominant -->
                        <div class="flex items-center space-x-3">
                            <div class="flex-grow bg-gray-200 rounded-full h-3 max-w-xs">
                                <div class="loyalty-progress h-3 rounded-full" 
                                     style="width: {{ min($clientMetrics->loyalty_score, 100) }}%"></div>
                            </div>
                            <span class="text-lg sm:text-xl font-bold text-gray-900">{{ $clientMetrics->loyalty_score }}/100</span>
                        </div>
                    </div>
                    
                    <!-- Badge de statut -->
                    <div class="flex flex-col items-center sm:items-end">
                        @if($clientMetrics->loyalty_score >= 80)
                            <div class="badge-vip px-4 py-2 rounded-full text-sm font-bold mb-2">⭐ Client VIP</div>
                        @elseif($clientMetrics->loyalty_score >= 60)
                            <div class="badge-premium px-4 py-2 rounded-full text-sm font-bold mb-2">💎 Client Premium</div>
                        @elseif($clientMetrics->loyalty_score >= 40)
                            <div class="badge-fidele px-4 py-2 rounded-full text-sm font-bold mb-2">✅ Client Fidèle</div>
                        @else
                            <div class="badge-standard px-4 py-2 rounded-full text-sm font-bold mb-2">👤 Client Standard</div>
                        @endif
                        
                        <div class="text-right text-sm text-gray-500">
                            <div>Dernière commande:</div>
                            <div class="font-medium">{{ \Carbon\Carbon::parse($clientMetrics->last_order_date)->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 stats-grid">
        <div class="stat-card rounded-xl p-4 sm:p-6 card-hover">
            <div class="flex items-center">
                <div class="metric-icon rounded-xl p-3 flex-shrink-0">
                    <i class="fas fa-shopping-cart text-lg sm:text-xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-gray-600 text-sm font-medium">Commandes</h3>
                    <p class="text-base sm:text-base font-bold text-gray-900 stat-value">{{ $clientMetrics->total_orders }}</p>
                </div>
            </div>
            <div class="mt-4 text-blue-600 text-sm font-medium">
                <i class="fas fa-calendar-alt mr-1"></i> Total validées
            </div>
        </div>
        
        <div class="stat-card rounded-xl p-4 sm:p-6 card-hover">
            <div class="flex items-center">
                <div class="metric-icon rounded-xl p-3 flex-shrink-0">
                    <i class="fas fa-boxes text-lg sm:text-xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-gray-600 text-sm font-medium">Cartons</h3>
                    <p class="text-base sm:text-base font-bold text-gray-900 stat-value">{{ $clientMetrics->total_quantity }}</p>
                </div>
            </div>
            <div class="mt-4 text-green-600 text-sm font-medium flex items-center gap-2">
                <span class="inline-flex items-center">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-1"></span>
                    {{ $clientMetrics->petit_quantity }} petits
                </span>
                <span class="inline-flex items-center">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                    {{ $clientMetrics->grand_quantity }} grands
                </span>
            </div>
        </div>
        
        <div class="stat-card rounded-xl p-4 sm:p-6 card-hover">
            <div class="flex items-center">
                <div class="metric-icon rounded-xl p-3 flex-shrink-0">
                    <i class="fas fa-coins text-lg sm:text-xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-gray-600 text-sm font-medium">Total dépensé</h3>
                    <p class="text-base sm:text-base font-bold text-gray-900 stat-value">{{ $clientMetrics->total_amount_formatted }}</p>
                </div>
            </div>
            <div class="mt-4 text-purple-600 text-sm font-medium">
                <i class="fas fa-chart-line mr-1"></i> FCFA
            </div>
        </div>
        
        <div class="stat-card rounded-xl p-4 sm:p-6 card-hover">
            <div class="flex items-center">
                <div class="metric-icon rounded-xl p-3 flex-shrink-0">
                    <i class="fas fa-calculator text-lg sm:text-xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-gray-600 text-sm font-medium">Panier moyen</h3>
                    <p class="text-base sm:text-base font-bold text-gray-900 stat-value">{{ $clientMetrics->average_order_formatted }}</p>
                </div>
            </div>
            <div class="mt-4 text-amber-600 text-sm font-medium">
                <i class="fas fa-trending-up mr-1"></i> Par commande
            </div>
        </div>
    </div>

    <!-- Informations détaillées -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 info-grid">
        <!-- Informations de contact -->
        <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center">
                <i class="fas fa-address-card mr-3 text-blue-600"></i>
                Informations de contact
            </h3>
            
            <div class="space-y-3 sm:space-y-4">
                @if($clientMetrics->client_phone)
                <div class="contact-item flex items-center p-3 sm:p-4 rounded-lg">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-phone text-blue-600"></i>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <div class="text-sm text-gray-600">Téléphone</div>
                        <div class="text-base sm:text-lg font-medium text-gray-900">{{ $clientMetrics->client_phone }}</div>
                    </div>
                    <a href="tel:{{ $clientMetrics->client_phone }}" class="ml-auto p-2 rounded-full hover:bg-blue-100 transition-colors">
                        <i class="fas fa-external-link-alt text-blue-600"></i>
                    </a>
                </div>
                @endif
                
                @if($clientMetrics->client_email)
                <div class="contact-item flex items-center p-3 sm:p-4 rounded-lg">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fas fa-envelope text-green-600"></i>
                    </div>
                    <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                        <div class="text-sm text-gray-600">Email</div>
                        <div class="text-base sm:text-lg font-medium text-gray-900 truncate">{{ $clientMetrics->client_email }}</div>
                    </div>
                    <a href="mailto:{{ $clientMetrics->client_email }}" class="ml-auto p-2 rounded-full hover:bg-green-100 transition-colors">
                        <i class="fas fa-external-link-alt text-green-600"></i>
                    </a>
                </div>
                @endif
                
                <div class="contact-item flex items-center p-3 sm:p-4 rounded-lg">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-calendar-plus text-purple-600"></i>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <div class="text-sm text-gray-600">Première commande</div>
                        <div class="text-base sm:text-lg font-medium text-gray-900">{{ \Carbon\Carbon::parse($clientMetrics->first_order_date)->format('d/m/Y') }}</div>
                    </div>
                </div>
                
                <div class="contact-item flex items-center p-3 sm:p-4 rounded-lg">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                        <i class="fas fa-clock text-amber-600"></i>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <div class="text-sm text-gray-600">Dernière commande</div>
                        <div class="text-base sm:text-lg font-medium text-gray-900">{{ \Carbon\Carbon::parse($clientMetrics->last_order_date)->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Répartition par type de carton -->
        <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center">
                <i class="fas fa-chart-pie mr-3 text-green-600"></i>
                Répartition par type de carton
            </h3>
            
            <!-- Préférence générale -->
            <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-gradient-to-r from-blue-50 to-green-50 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Préférence générale</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $clientMetrics->preference_color }}-100 text-{{ $clientMetrics->preference_color }}-800">
                        {{ $clientMetrics->preference_label }}
                    </span>
                </div>
                <div class="text-xs text-gray-600">
                    Score de diversification: {{ $clientMetrics->diversification_score }}/1.0
                    @if($clientMetrics->diversification_score >= 0.8)
                        (Très diversifié)
                    @elseif($clientMetrics->diversification_score >= 0.5)
                        (Moyennement diversifié)
                    @else
                        (Peu diversifié)
                    @endif
                </div>
            </div>
            
            <!-- Petits cartons -->
            <div class="mb-4 sm:mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-blue-600 flex items-center">
                        <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                        Petits cartons
                    </span>
                    <span class="text-lg font-bold text-gray-900">{{ $clientMetrics->petit_percentage }}%</span>
                </div>
                <div class="bg-gray-200 rounded-full h-3 mb-2">
                    <div class="progress-bar h-3 rounded-full transition-all duration-500 ease-out" 
                         style="width: {{ $clientMetrics->petit_percentage }}%"></div>
                </div>
                <div class="flex justify-between text-xs sm:text-sm text-gray-600">
                    <span>{{ $clientMetrics->petit_quantity }} cartons</span>
                    <span class="font-medium">{{ $clientMetrics->petit_amount_formatted }}</span>
                </div>
            </div>
            
            <!-- Grands cartons -->
            <div class="mb-4 sm:mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-green-600 flex items-center">
                        <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                        Grands cartons
                    </span>
                    <span class="text-lg font-bold text-gray-900">{{ $clientMetrics->grand_percentage }}%</span>
                </div>
                <div class="bg-gray-200 rounded-full h-3 mb-2">
                    <div class="progress-bar-green h-3 rounded-full transition-all duration-500 ease-out" 
                         style="width: {{ $clientMetrics->grand_percentage }}%"></div>
                </div>
                <div class="flex justify-between text-xs sm:text-sm text-gray-600">
                    <span>{{ $clientMetrics->grand_quantity }} cartons</span>
                    <span class="font-medium">{{ $clientMetrics->grand_amount_formatted }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Historique des commandes -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-history mr-3 text-indigo-600"></i>
                        Historique des commandes
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">{{ $orders->total() }} commandes au total</p>
                </div>
                
                <!-- Indicateur de scroll pour mobile -->
                <div class="mt-3 sm:mt-0 lg:hidden bg-blue-50 px-3 py-1 rounded-full">
                    <span class="text-xs text-blue-700">
                        <i class="fas fa-arrows-alt-h mr-1"></i>
                        Faites défiler horizontalement
                    </span>
                </div>
            </div>
        </div>
        
        <div class="history-table-container">
            <table class="history-table divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[150px]">
                            Type production
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                            Type carton
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[100px]">
                            Quantité
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                            Prix unitaire
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                            Total
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[200px]">
                            Notes
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }}
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium">{{ $order->type->name ?? 'Non défini' }}</div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                         {{ $order->carton_type === 'petit' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ $order->carton_type === 'petit' ? '🟦' : '🟩' }} {{ ucfirst($order->carton_type) }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->quantity }}</div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ number_format($order->unit_price, 0, ',', ' ') }}</div>
                            <div class="text-xs text-gray-500">FCFA</div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ number_format($order->quantity * $order->unit_price, 0, ',', ' ') }}
                            </div>
                            <div class="text-xs text-gray-500">FCFA</div>
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs" title="{{ $order->notes }}">
                                {{ $order->notes ?: 'Aucune note' }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune commande</h3>
                                <p class="text-gray-500">Ce client n'a pas encore passé de commande.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
                <div class="text-xs sm:text-sm text-gray-700 order-2 sm:order-1">
                    Affichage de {{ $orders->firstItem() }} à {{ $orders->lastItem() }} sur {{ $orders->total() }} commandes
                </div>
                <div class="order-1 sm:order-2">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Actions -->
    <div class="flex justify-start">
        <a href="{{ route('clients.index') }}" 
           class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-medium">
            <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
        </a>
    </div>
</div>

<script>
// Animations au chargement
document.addEventListener('DOMContentLoaded', function() {
    // Animation des barres de progression
    const progressBars = document.querySelectorAll('.progress-bar, .progress-bar-green, .loyalty-progress');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 300);
    });

    // Animation des cartes au scroll
    const cards = document.querySelectorAll('.card-hover, .stat-card');
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
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

    // Amélioration tactile mobile
    if ('ontouchstart' in window) {
        const touchableElements = document.querySelectorAll('button, a, .contact-item');
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