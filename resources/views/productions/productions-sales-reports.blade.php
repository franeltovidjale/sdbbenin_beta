@extends('layouts.app')

@section('title', 'Bilans des ventes de production')

@section('breadcrumb')
    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
    <a href="{{ route('productions.sales.index') }}" class="hover:text-blue-700 transition-colors">Ventes de production</a>
    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
    <span class="text-gray-700">Bilans</span>
@endsection

@section('page-title', 'Bilans des ventes de production')
@section('page-subtitle', 'Statistiques et analyses des ventes par type de production et type de carton')

@section('content')
<div class="space-y-6">
    <!-- Synthèse du stock actuel -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Synthèse du stock actuel</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($stockData as $typeId => $typeData)
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-semibold text-gray-700 mb-2">{{ $typeData['name'] }}</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Petits cartons :</span>
                        <span class="text-sm font-medium">{{ $typeData['petit']['available'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Grands cartons :</span>
                        <span class="text-sm font-medium">{{ $typeData['grand']['available'] }}</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-2">
                        <div>Produits : {{ $typeData['petit']['produced'] }} petits, {{ $typeData['grand']['produced'] }} grands</div>
                        <div>Vendus : {{ $typeData['petit']['sold'] }} petits, {{ $typeData['grand']['sold'] }} grands</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Statistiques des ventes par mois -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Ventes mensuelles pour {{ date('Y') }}</h3>
        
        <div id="monthly-chart" class="h-96 w-full"></div>
    </div>
    
    <!-- Top clients -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Top clients</h3>
        
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre de ventes</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité totale</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topClients as $client)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $client->client_name }} {{ $client->client_firstname }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $client->total_sales }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($client->total_quantity, 2, ',', ' ') }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($client->total_amount, 2, ',', ' ') }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-center text-gray-500">
                                Aucune vente validée pour le moment
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Bilan détaillé par type de production -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Bilan détaillé par type de production</h3>
        
        <div class="space-y-6">
            @foreach($types as $type)
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-semibold text-gray-700 mb-2">{{ $type->name }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Petits cartons</h5>
                        <div class="space-y-1">
                            @php
                                $produced = $stockData[$type->id]['petit']['produced'] ?? 0;
                                $sold = $stockData[$type->id]['petit']['sold'] ?? 0;
                                $available = $stockData[$type->id]['petit']['available'] ?? 0;
                                $percent = $produced > 0 ? round(($sold / $produced) * 100) : 0;
                            @endphp
                            <div class="flex justify-between text-xs">
                                <span>Produits :</span>
                                <span>{{ $produced }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span>Vendus :</span>
                                <span>{{ $sold }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span>Disponibles :</span>
                                <span>{{ $available }}</span>
                            </div>
                            <div class="flex justify-between text-xs font-medium">
                                <span>Taux d'écoulement :</span>
                                <span>{{ $percent }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Grands cartons</h5>
                        <div class="space-y-1">
                            @php
                                $produced = $stockData[$type->id]['grand']['produced'] ?? 0;
                                $sold = $stockData[$type->id]['grand']['sold'] ?? 0;
                                $available = $stockData[$type->id]['grand']['available'] ?? 0;
                                $percent = $produced > 0 ? round(($sold / $produced) * 100) : 0;
                            @endphp
                            <div class="flex justify-between text-xs">
                                <span>Produits :</span>
                                <span>{{ $produced }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span>Vendus :</span>
                                <span>{{ $sold }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span>Disponibles :</span>
                                <span>{{ $available }}</span>
                            </div>
                            <div class="flex justify-between text-xs font-medium">
                                <span>Taux d'écoulement :</span>
                                <span>{{ $percent }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection


<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour le graphique des ventes mensuelles
    const monthlySales = @json($monthlySales);
    
    // Préparer les séries pour le graphique
    const series = [];
    const typeColors = {
        @foreach($types as $index => $type)
            {{ $type->id }}: {
                petit: '{{ $index % 2 == 0 ? '#3B82F6' : '#60A5FA' }}',
                grand: '{{ $index % 2 == 0 ? '#10B981' : '#34D399' }}'
            },
        @endforeach
    };
    
    // Préparer les données pour chaque type et taille de carton
    @foreach($types as $type)
        series.push({
            name: '{{ $type->name }} - Petits',
            data: Array(12).fill(0),
            color: typeColors[{{ $type->id }}].petit
        });
        
        series.push({
            name: '{{ $type->name }} - Grands',
            data: Array(12).fill(0),
            color: typeColors[{{ $type->id }}].grand
        });
    @endforeach
    
    // Remplir les données des séries
    monthlySales.forEach(sale => {
        const month = sale.month - 1; // Les mois sont indexés à partir de 0 dans JavaScript
        const typeId = sale.type_id;
        const cartonType = sale.carton_type;
        
        // Trouver l'index de la série correspondante
        @foreach($types as $index => $type)
            if (typeId == {{ $type->id }}) {
                if (cartonType === 'petit') {
                    series[{{ $index * 2 }}].data[month] += parseFloat(sale.total_quantity);
                } else {
                    series[{{ $index * 2 + 1 }}].data[month] += parseFloat(sale.total_quantity);
                }
            }
        @endforeach
    });
    
    // Options du graphique
    const options = {
        series: series,
        chart: {
            height: 350,
            type: 'bar',
            stacked: false,
            toolbar: {
                show: true
            },
            zoom: {
                enabled: true
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                legend: {
                    position: 'bottom',
                    offsetX: -10,
                    offsetY: 0
                }
            }
        }],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
            },
        },
        xaxis: {
            categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
        },
        yaxis: {
            title: {
                text: 'Quantité vendue'
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val.toFixed(2) + " cartons vendus"
                }
            }
        },
        legend: {
            position: 'right',
            offsetY: 40
        },
        title: {
            text: 'Ventes mensuelles par type de production et taille de carton',
            align: 'left'
        }
    };
    
    // Initialiser le graphique
    const chartElement = document.getElementById('monthly-chart');
    if (chartElement) {
        const chart = new ApexCharts(chartElement, options);
        chart.render();
    }
});
</script>
