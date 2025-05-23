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
        <!-- GRAPHIQUE FONCTIONNEL avec Chart.js -->
        <div class="bg-white rounded-lg shadow-sm p-6 lg:col-span-2 hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-navy-800">Production des 12 dernières semaines</h2>
                <div class="flex space-x-2">
                    <button id="chartWeek" class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-md text-sm font-medium hover:bg-blue-100 transition-colors">Semaine</button>
                    <button id="chartMonth" class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded-md text-sm font-medium hover:bg-gray-200 transition-colors">Mois</button>
                    <button id="chartYear" class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded-md text-sm font-medium hover:bg-gray-200 transition-colors">Année</button>
                </div>
            </div>
            <div class="relative h-72">
                <!-- Canvas pour Chart.js -->
                <canvas id="productionChart" class="w-full h-full"></canvas>
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


<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données du graphique passées depuis PHP
    const chartData = @json($chartData);
    
    console.log('Données du graphique:', chartData); // Pour déboguer
    
    // Configuration du graphique
    const ctx = document.getElementById('productionChart').getContext('2d');
    let productionChart = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                title: {
                    display: false
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    cornerRadius: 6,
                    displayColors: true,
                    callbacks: {
                        title: function(context) {
                            return 'Semaine du ' + context[0].label;
                        },
                        label: function(context) {
                            if (context.datasetIndex === 0) {
                                return 'Productions: ' + context.parsed.y;
                            } else {
                                return 'Cartons: ' + context.parsed.y;
                            }
                        }
                    }
                }
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Semaines',
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    },
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Nombre de productions',
                        color: 'rgb(59, 130, 246)',
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    },
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        color: 'rgb(59, 130, 246)',
                        font: {
                            size: 11
                        },
                        beginAtZero: true,
                        precision: 0
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Cartons produits',
                        color: 'rgb(16, 185, 129)',
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                    ticks: {
                        color: 'rgb(16, 185, 129)',
                        font: {
                            size: 11
                        },
                        beginAtZero: true,
                        precision: 0
                    }
                }
            },
            elements: {
                point: {
                    radius: 4,
                    hoverRadius: 6,
                    borderWidth: 2,
                    hoverBorderWidth: 3
                },
                line: {
                    borderWidth: 3,
                    tension: 0.1
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        }
    });

    // Gestion des boutons de période
    document.getElementById('chartWeek').addEventListener('click', function() {
        setActiveButton(this);
        // Pour l'instant, le graphique reste le même (12 semaines)
        console.log('Vue semaine sélectionnée');
    });

    document.getElementById('chartMonth').addEventListener('click', function() {
        setActiveButton(this);
        console.log('Vue mois sélectionnée');
    });

    document.getElementById('chartYear').addEventListener('click', function() {
        setActiveButton(this);
        console.log('Vue année sélectionnée');
    });

    function setActiveButton(activeButton) {
        // Retirer la classe active de tous les boutons
        document.querySelectorAll('#chartWeek, #chartMonth, #chartYear').forEach(btn => {
            btn.className = 'px-3 py-1.5 bg-gray-100 text-gray-600 rounded-md text-sm font-medium hover:bg-gray-200 transition-colors';
        });
        
        // Ajouter la classe active au bouton cliqué
        activeButton.className = 'px-3 py-1.5 bg-blue-50 text-blue-600 rounded-md text-sm font-medium hover:bg-blue-100 transition-colors';
    }
});
</script>
