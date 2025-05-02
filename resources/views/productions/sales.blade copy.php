@extends('layouts.app')

@section('title', 'Gestion des ventes de production')

@section('breadcrumb')
    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
    <a href="{{ route('productions.sales.index') }}" class="text-gray-700">Ventes de production</a>
@endsection

@section('page-title', 'Ventes de production')
@section('page-subtitle', 'Gestion des sorties de cartons produits')

{{-- <style>
    /* Correction des décalages sur desktop */
    @media (min-width: 1024px) {
        .container-fluid {
            max-width: 100%;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        
        /* Empêcher les tableaux de déborder */
        .overflow-x-auto {
            max-width: 100%;
        }
        
        table {
            width: 100%;
            table-layout: fixed;
        }
        
        /* Limiter la largeur des cellules avec contenu long */
        td, th {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        /* Permettre aux actions de prendre moins d'espace */
        td:last-child, th:last-child {
            width: 100px;
        }
    }

    /* Amélioration des conteneurs pour éviter les décalages */
    .content-container {
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
    }

    /* Correction des inputs pour éviter les débordements */
    input, select, textarea {
        box-sizing: border-box;
        max-width: 100%;
    }

    /* Éviter que les inputs numériques ne débordent avec les flèches de spinner */
    input[type="number"] {
        padding-right: 1.5rem;
    }
</style> --}}

<style>
    /* Base responsive styles for all devices */
.content-container {
    width: 100%;
    max-width: 100%;
    overflow-x: hidden;
    padding: 0.5rem;
}

/* Global form elements responsive fixes */
input, select, textarea {
    box-sizing: border-box;
    max-width: 100%;
    width: 100%;
}

/* Better table handling on all devices */
.overflow-x-auto {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

table {
    width: 100%;
    table-layout: fixed;
}

td, th {
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem !important;
}

/* Mobile-first approach - Styles for small devices (phones) */
@media screen and (max-width: 639px) {
    /* Enhance touch targets for mobile */
    button, input, select {
        min-height: 44px; /* Recommended minimum touch target size */
    }
    
    /* Adjust spacing in cards and containers */
    .p-6 {
        padding: 1rem !important;
    }
    
    .space-y-6 > * + * {
        margin-top: 1.25rem !important;
    }
    
    /* Adjust grid for small screens */
    .grid {
        grid-template-columns: 1fr !important;
        gap: 0.75rem !important;
    }
    
    /* Fix table display on mobile */
    td:last-child, th:last-child {
        width: 80px !important;
    }
    
    /* Adjust buttons and actions on mobile */
    .flex-col, .md\:flex-row {
        display: flex;
        flex-direction: column;
    }
    
    .md\:items-center {
        align-items: stretch;
    }
    
    .md\:flex-row .space-x-2 {
        display: flex;
        flex-direction: row;
        margin-top: 0.5rem;
        width: 100%;
    }
    
    .md\:flex-row .space-x-2 > button {
        flex: 1;
        font-size: 0.75rem;
        padding: 0.5rem 0.25rem;
    }
    
    .md\:flex-row .space-x-2 > button i {
        margin-right: 0.25rem;
    }
    
    /* Make sure content doesn't overflow on small screens */
    .rounded-lg {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    /* Optimize stock display cards */
    .bg-gray-50.p-4.rounded-lg {
        padding: 0.75rem;
    }
}

/* Tablet styles */
@media screen and (min-width: 640px) and (max-width: 1023px) {
    .content-container {
        padding: 0.75rem;
    }
    
    .p-6 {
        padding: 1.25rem !important;
    }
    
    /* Adjust grid for medium screens */
    .md\:grid-cols-2 {
        grid-template-columns: repeat(2, 1fr) !important;
    }
    
    .lg\:grid-cols-3 {
        grid-template-columns: repeat(2, 1fr) !important;
    }
    
    /* Fix table display on tablets */
    td, th {
        max-width: 120px;
        font-size: 0.8125rem;
    }
    
    /* More compact buttons on tablets */
    .md\:flex-row .space-x-2 > button {
        font-size: 0.875rem;
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
}

/* Desktop styles - refine existing desktop styles */
@media (min-width: 1024px) {
    .content-container {
        padding: 0;
    }
    
    .container-fluid {
        max-width: 100%;
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }
    
    /* Better table handling on desktop */
    .overflow-x-auto {
        max-width: 100%;
        overflow-x: auto;
    }
    
    /* Ensure modals appear properly centered on large screens */
    .sm\:max-w-lg {
        max-width: 32rem;
    }
    
    /* Proper table column widths for desktop */
    td, th {
        max-width: 200px;
    }
    
    /* Actions column fixed width */
    td:last-child, th:last-child {
        width: 100px;
    }
    
    /* Better grid spacing for forms */
    .gap-4.xl\:gap-6 {
        gap: 1.5rem;
    }
}

/* Large desktop/4K screens */
@media (min-width: 1536px) {
    .container-fluid {
        max-width: 1536px;
        margin-left: auto;
        margin-right: auto;
    }
    
    /* Better table handling on large screens */
    td, th {
        max-width: 250px; /* Allow more text on larger screens */
        padding: 0.75rem 1rem !important;
    }
    
    /* Increase overall font size on large screens */
    html {
        font-size: 17px;
    }
}

/* Fix for total display on all screens */
.bg-gray-100.p-3.md\:p-4.rounded-lg {
    width: 100% !important;
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
}

/* Consistent button styling across breakpoints */
button[type="submit"], 
#validateSalesBtn, 
#rejectSalesBtn,
#saveSaleButton,
#cancelSaleEditButton {
    white-space: nowrap;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Fix for modal on all screens */
#editSaleModal .sm\:max-w-lg {
    max-width: min(95vw, 32rem);
}

/* Improve checkbox visibility */
input[type="checkbox"] {
    min-width: 1rem;
    min-height: 1rem;
}

/* Fix for Firefox number input */
input[type="number"] {
    -moz-appearance: textfield;
    padding-right: 0.5rem;
}

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Fix spacing issues in tables */
.whitespace-nowrap {
    white-space: nowrap;
}

/* Print media query for better printing */
@media print {
    .bg-white, .bg-gray-50, .bg-gray-100 {
        background-color: white !important;
        color: black !important;
    }
    
    .shadow-sm {
        box-shadow: none !important;
    }
    
    button, .focus\:ring-2, .focus\:ring-offset-2 {
        display: none !important;
    }
}
</style>

@section('content')
<div class="space-y-6 content-container">
    <!-- Stock disponible -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Stock disponible par type de production</h3>
        
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
                        <div>En attente : {{ $typeData['petit']['pending'] }} petits, {{ $typeData['grand']['pending'] }} grands</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Formulaire d'ajout de vente -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Enregistrer une vente</h3>
        
        <div class="bg-gray-50 rounded-lg p-4">
            <form id="addSaleForm" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 xl:gap-6">
                    {{-- <div class="grid grid-cols-1 md:grid-cols-3 gap-6"> --}}
                    <div>
                        <label for="type_id" class="block text-sm font-medium text-gray-700 mb-1">Type de production <span class="text-red-500">*</span></label>
                        <select id="type_id" name="type_id" class="form-select-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                            <option value="">-- Sélectionner un type --</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}">
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        <div id="type_id_error" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    
                    <div>
                        <label for="carton_type" class="block text-sm font-medium text-gray-700 mb-1">Type de carton <span class="text-red-500">*</span></label>
                        <select id="carton_type" name="carton_type" class="form-select-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                            <option value="">-- Sélectionner un type --</option>
                            <option value="petit">Petit</option>
                            <option value="grand">Grand</option>
                        </select>
                        <div id="carton_type_error" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    
                    <div>
                        <label for="stock_display" class="block text-sm font-medium text-gray-700 mb-1">Stock disponible</label>
                        <input type="text" id="stock_display" class="form-input-focus w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-100" readonly>
                        <div class="text-xs text-gray-500 mt-1">Stock disponible pour le type et la taille sélectionnés</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantité <span class="text-red-500">*</span></label>
                        <input type="number" id="quantity" name="quantity" min="0.01" step="0.01" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                        <div id="quantity_error" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    
                    <div>
                        <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire <span class="text-red-500">*</span></label>
                        <input type="number" id="unit_price" name="unit_price" min="0" step="0.01" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                        <div id="unit_price_error" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="client_name" class="block text-sm font-medium text-gray-700 mb-1">Nom du client <span class="text-red-500">*</span></label>
                        <input type="text" id="client_name" name="client_name" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                        <div id="client_name_error" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    
                    <div>
                        <label for="client_firstname" class="block text-sm font-medium text-gray-700 mb-1">Prénom du client <span class="text-red-500">*</span></label>
                        <input type="text" id="client_firstname" name="client_firstname" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                        <div id="client_firstname_error" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="client_phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                        <input type="text" id="client_phone" name="client_phone" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <div id="client_phone_error" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    
                    <div>
                        <label for="client_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="client_email" name="client_email" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <div id="client_email_error" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                </div>
                
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea id="notes" name="notes" rows="2" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="Informations complémentaires sur cette vente..."></textarea>
                    <div id="notes_error" class="text-red-500 text-xs mt-1 hidden"></div>
                </div>
                
                <!-- Affichage du total -->
                {{-- <div class="bg-gray-100 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-gray-700">Total :</span>
                        <span id="total_display" class="text-lg font-bold text-gray-900">0,00</span>
                    </div>
                </div> --}}
                <div class="bg-gray-100 p-3 md:p-4 rounded-lg w-full">
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-gray-700">Total :</span>
                        <span id="total_display" class="text-base md:text-lg font-bold text-gray-900">0,00</span>
                    </div>
                </div>
                
                <div>
                    <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-plus-circle mr-2"></i> Enregistrer la vente
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Ventes en attente de validation -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Ventes en attente</h3>
            
            <div class="mt-3 md:mt-0 flex space-x-2">
                <button id="validateSalesBtn" class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-check-circle mr-2"></i> Valider la sélection
                </button>
                <button id="rejectSalesBtn" class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-times-circle mr-2"></i> Rejeter la sélection
                </button>
            </div>
        </div>
        
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="overflow-x-auto max-w-full">
                <table class="w-full divide-y divide-gray-200 table-fixed">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left">
                                <div class="flex items-center">
                                    <input id="selectAllPending" type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                </div>
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carton</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qté</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix Unit.</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="pendingSalesBody">
                        @forelse($pendingSales as $sale)
                        <tr data-id="{{ $sale->id }}">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <input type="checkbox" name="sale_ids[]" value="{{ $sale->id }}" class="sale-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $sale->type->name }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ ucfirst($sale->carton_type) }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($sale->quantity, 2, ',', ' ') }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($sale->unit_price, 2, ',', ' ') }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($sale->quantity * $sale->unit_price, 2, ',', ' ') }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $sale->client_name }} {{ $sale->client_firstname }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    @if($sale->client_phone)
                                        <div>{{ $sale->client_phone }}</div>
                                    @endif
                                    @if($sale->client_email)
                                        <div>{{ $sale->client_email }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $sale->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                <button class="edit-sale text-blue-600 hover:text-blue-900 mx-1" 
                                        data-id="{{ $sale->id }}"
                                        data-type-id="{{ $sale->type_id }}"
                                        data-carton-type="{{ $sale->carton_type }}"
                                        data-quantity="{{ $sale->quantity }}"
                                        data-unit-price="{{ $sale->unit_price }}"
                                        data-client-name="{{ $sale->client_name }}"
                                        data-client-firstname="{{ $sale->client_firstname }}"
                                        data-client-phone="{{ $sale->client_phone }}"
                                        data-client-email="{{ $sale->client_email }}"
                                        data-notes="{{ $sale->notes }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="delete-sale text-red-600 hover:text-red-900 mx-1" 
                                        data-id="{{ $sale->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-4 py-3 text-center text-gray-500">
                                Aucune vente en attente de validation
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination pour les ventes en attente -->
            <div class="px-4 py-3 bg-white border-t border-gray-200">
                {{ $pendingSales->appends(['history_page' => request()->history_page])->links() }}
            </div>
        </div>
    </div>
    
    <!-- Historique des ventes validées/rejetées -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Historique des ventes</h3>
        
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carton</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qté</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix Unit.</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Validation</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($historySales as $sale)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $sale->type->name }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ ucfirst($sale->carton_type) }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($sale->quantity, 2, ',', ' ') }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($sale->unit_price, 2, ',', ' ') }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($sale->quantity * $sale->unit_price, 2, ',', ' ') }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $sale->client_name }} {{ $sale->client_firstname }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    @if($sale->client_phone)
                                        <div>{{ $sale->client_phone }}</div>
                                    @endif
                                    @if($sale->client_email)
                                        <div>{{ $sale->client_email }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sale->status === 'validé' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($sale->status) }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $sale->updated_at->format('d/m/Y H:i') }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-4 py-3 text-center text-gray-500">
                                Aucune vente dans l'historique
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination pour l'historique -->
            <div class="px-4 py-3 bg-white border-t border-gray-200">
                {{ $historySales->appends(['pending_page' => request()->pending_page])->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal d'édition -->
<div id="editSaleModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Modifier la vente</h3>
                <form id="editSaleForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="edit_sale_id" name="id">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_type_id" class="block text-sm font-medium text-gray-700 mb-1">Type de production <span class="text-red-500">*</span></label>
                            <select id="edit_type_id" name="type_id" class="form-select-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                                <option value="">-- Sélectionner un type --</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="edit_type_id_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <div>
                            <label for="edit_carton_type" class="block text-sm font-medium text-gray-700 mb-1">Type de carton <span class="text-red-500">*</span></label>
                            <select id="edit_carton_type" name="carton_type" class="form-select-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                                <option value="">-- Sélectionner un type --</option>
                                <option value="petit">Petit</option>
                                <option value="grand">Grand</option>
                            </select>
                            <div id="edit_carton_type_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="edit_stock_display" class="block text-sm font-medium text-gray-700 mb-1">Stock disponible</label>
                        <input type="text" id="edit_stock_display" class="form-input-focus w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-100" readonly>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantité <span class="text-red-500">*</span></label>
                            <input type="number" id="edit_quantity" name="quantity" min="0.01" step="0.01" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                            <div id="edit_quantity_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <div>
                            <label for="edit_unit_price" class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire <span class="text-red-500">*</span></label>
                            <input type="number" id="edit_unit_price" name="unit_price" min="0" step="0.01" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                            <div id="edit_unit_price_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_client_name" class="block text-sm font-medium text-gray-700 mb-1">Nom du client <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_client_name" name="client_name" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                            <div id="edit_client_name_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <div>
                            <label for="edit_client_firstname" class="block text-sm font-medium text-gray-700 mb-1">Prénom du client <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_client_firstname" name="client_firstname" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                            <div id="edit_client_firstname_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_client_phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                            <input type="text" id="edit_client_phone" name="client_phone" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <div id="edit_client_phone_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <div>
                            <label for="edit_client_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="edit_client_email" name="client_email" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <div id="edit_client_email_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="edit_notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea id="edit_notes" name="notes" rows="2" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="Informations complémentaires sur cette vente..."></textarea>
                        <div id="edit_notes_error" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    
                    <!-- Affichage du total -->
                    {{-- <div class="bg-gray-100 p-4 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-700">Total :</span>
                            <span id="edit_total_display" class="text-lg font-bold text-gray-900">0,00</span>
                        </div>
                    </div> --}}

                    <div class="bg-gray-100 p-3 md:p-4 rounded-lg w-full">
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-700">Total :</span>
                            <span id="total_display" class="text-base md:text-lg font-bold text-gray-900">0,00</span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="saveSaleButton" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Enregistrer
                </button>
                <button type="button" id="cancelSaleEditButton" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ==================== DONNÉES DU STOCK ====================
    
    // Créer un objet pour stocker les données de stock
    const stockData = @json($stockData);
    
    // ==================== FONCTIONS UTILITAIRES ====================
    
    // Réinitialiser les erreurs de formulaire
    function resetFormErrors(prefix = '') {
        const errorElements = document.querySelectorAll(`[id$="_error"]`);
        errorElements.forEach(el => {
            if (!prefix || el.id.startsWith(prefix)) {
                el.textContent = '';
                el.classList.add('hidden');
            }
        });
    }
    
    // Afficher un message d'erreur
    function showError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }
    }
    
    // Formater un nombre en devise
    function formatCurrency(value) {
        return parseFloat(value).toFixed(2).replace('.', ',');
    }
    
    // Mettre à jour l'affichage du stock disponible
    function updateStockDisplay(typeId, cartonType, displayElement) {
        if (typeId && cartonType && stockData[typeId]) {
            const available = stockData[typeId][cartonType]['available'];
            displayElement.value = `${available} carton(s) ${cartonType}(s) disponible(s)`;
            
            if (available <= 0) {
                displayElement.classList.add('text-red-600', 'font-bold');
            } else {
                displayElement.classList.remove('text-red-600', 'font-bold');
            }
        } else {
            displayElement.value = '';
            displayElement.classList.remove('text-red-600', 'font-bold');
        }
    }
    
    // Calculer et mettre à jour le total
    function updateTotal(quantityElement, priceElement, displayElement) {
        const quantity = parseFloat(quantityElement.value) || 0;
        const price = parseFloat(priceElement.value) || 0;
        const total = quantity * price;
        
        displayElement.textContent = formatCurrency(total);
    }
    
    // ==================== GESTION DU FORMULAIRE D'AJOUT ====================
    
    // Affichage du stock quand on sélectionne un type et un carton
    const typeSelect = document.getElementById('type_id');
    const cartonTypeSelect = document.getElementById('carton_type');
    const stockDisplay = document.getElementById('stock_display');
    
    function updateAddFormStock() {
        const typeId = typeSelect.value;
        const cartonType = cartonTypeSelect.value;
        updateStockDisplay(typeId, cartonType, stockDisplay);
    }
    
    if (typeSelect && cartonTypeSelect && stockDisplay) {
        typeSelect.addEventListener('change', updateAddFormStock);
        cartonTypeSelect.addEventListener('change', updateAddFormStock);
    }
    
    // Calculer le total à chaque changement
    const quantityInput = document.getElementById('quantity');
    const unitPriceInput = document.getElementById('unit_price');
    const totalDisplay = document.getElementById('total_display');
    
    function updateAddFormTotal() {
        updateTotal(quantityInput, unitPriceInput, totalDisplay);
    }
    
    if (quantityInput && unitPriceInput && totalDisplay) {
        quantityInput.addEventListener('input', updateAddFormTotal);
        unitPriceInput.addEventListener('input', updateAddFormTotal);
    }
    
    // Formulaire d'ajout de vente
    const addSaleForm = document.getElementById('addSaleForm');
    if (addSaleForm) {
        addSaleForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Réinitialiser les erreurs
            resetFormErrors();
            
            // Vérification de la disponibilité du stock
            const typeId = typeSelect.value;
            const cartonType = cartonTypeSelect.value;
            const quantity = parseFloat(quantityInput.value);
            
            if (typeId && cartonType && stockData[typeId]) {
                const available = stockData[typeId][cartonType]['available'];
                
                if (quantity > available) {
                    showError('quantity_error', `Stock insuffisant. Disponible: ${available} carton(s) ${cartonType}(s)`);
                    return;
                }
            }
            
            // Envoi de la requête AJAX
            const formData = new FormData(addSaleForm);
            
            fetch('{{ route("productions.sales.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Réinitialiser le formulaire
                    addSaleForm.reset();
                    totalDisplay.textContent = '0,00';
                    
                    // Recharger la page pour afficher la nouvelle vente
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: data.message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    // Afficher les erreurs
                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            showError(`${key}_error`, data.errors[key][0]);
                        });
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: data.message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue lors de la communication avec le serveur',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            });
        });
    }
    
    // ==================== GESTION DU FORMULAIRE D'ÉDITION ====================
    
    // Affichage du stock dans le formulaire d'édition
    const editTypeSelect = document.getElementById('edit_type_id');
    const editCartonTypeSelect = document.getElementById('edit_carton_type');
    const editStockDisplay = document.getElementById('edit_stock_display');
    
    function updateEditFormStock() {
        const typeId = editTypeSelect.value;
        const cartonType = editCartonTypeSelect.value;
        updateStockDisplay(typeId, cartonType, editStockDisplay);
    }
    
    if (editTypeSelect && editCartonTypeSelect && editStockDisplay) {
        editTypeSelect.addEventListener('change', updateEditFormStock);
        editCartonTypeSelect.addEventListener('change', updateEditFormStock);
    }
    
    // Calculer le total à chaque changement dans le formulaire d'édition
    const editQuantityInput = document.getElementById('edit_quantity');
    const editUnitPriceInput = document.getElementById('edit_unit_price');
    const editTotalDisplay = document.getElementById('edit_total_display');
    
    function updateEditFormTotal() {
        updateTotal(editQuantityInput, editUnitPriceInput, editTotalDisplay);
    }
    
    if (editQuantityInput && editUnitPriceInput && editTotalDisplay) {
        editQuantityInput.addEventListener('input', updateEditFormTotal);
        editUnitPriceInput.addEventListener('input', updateEditFormTotal);
    }
    
    // Édition d'une vente
    document.addEventListener('click', function(e) {
        if (e.target.closest('.edit-sale')) {
            const button = e.target.closest('.edit-sale');
            const id = button.getAttribute('data-id');
            const typeId = button.getAttribute('data-type-id');
            const cartonType = button.getAttribute('data-carton-type');
            const quantity = button.getAttribute('data-quantity');
            const unitPrice = button.getAttribute('data-unit-price');
            const clientName = button.getAttribute('data-client-name');
            const clientFirstname = button.getAttribute('data-client-firstname');
            const clientPhone = button.getAttribute('data-client-phone');
            const clientEmail = button.getAttribute('data-client-email');
            const notes = button.getAttribute('data-notes');
            
            // Remplir le formulaire d'édition
            document.getElementById('edit_sale_id').value = id;
            document.getElementById('edit_type_id').value = typeId;
            document.getElementById('edit_carton_type').value = cartonType;
            document.getElementById('edit_quantity').value = quantity;
            document.getElementById('edit_unit_price').value = unitPrice;
            document.getElementById('edit_client_name').value = clientName;
            document.getElementById('edit_client_firstname').value = clientFirstname;
            document.getElementById('edit_client_phone').value = clientPhone || '';
            document.getElementById('edit_client_email').value = clientEmail || '';
            document.getElementById('edit_notes').value = notes || '';
            
            // Mettre à jour l'affichage du stock et du total
            updateEditFormStock();
            updateEditFormTotal();
            
            // Afficher la modal
            document.getElementById('editSaleModal').classList.remove('hidden');
        }
    });
    
    // Fermer la modal d'édition
    document.getElementById('cancelSaleEditButton').addEventListener('click', function() {
        document.getElementById('editSaleModal').classList.add('hidden');
    });
    
    // Enregistrer les modifications d'une vente
    document.getElementById('saveSaleButton').addEventListener('click', function() {
        // Réinitialiser les erreurs
        resetFormErrors('edit_');
        
        // Vérification de la disponibilité du stock
        const typeId = editTypeSelect.value;
        const cartonType = editCartonTypeSelect.value;
        const quantity = parseFloat(editQuantityInput.value);
        const saleId = document.getElementById('edit_sale_id').value;
        
        if (typeId && cartonType && stockData[typeId]) {
            let available = stockData[typeId][cartonType]['available'];
            
            // Trouver la vente actuelle dans le DOM pour voir si on modifie le même type/carton
            const currentRow = document.querySelector(`#pendingSalesBody tr[data-id="${saleId}"]`);
            if (currentRow) {
                const button = currentRow.querySelector('.edit-sale');
                const currentTypeId = button.getAttribute('data-type-id');
                const currentCartonType = button.getAttribute('data-carton-type');
                const currentQuantity = parseFloat(button.getAttribute('data-quantity'));
                
                // Si on modifie le même type et carton, ajouter la quantité actuelle au stock disponible
                if (currentTypeId === typeId && currentCartonType === cartonType) {
                    available += currentQuantity;
                }
            }
            
            if (quantity > available) {
                showError('edit_quantity_error', `Stock insuffisant. Disponible: ${available} carton(s) ${cartonType}(s)`);
                return;
            }
        }
        
        // Récupérer les données du formulaire
        const formData = new FormData(document.getElementById('editSaleForm'));
        
        // Envoi de la requête AJAX
        fetch(`{{ url('productions/sales') }}/${saleId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'X-HTTP-Method-Override': 'PUT'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Fermer la modal
                document.getElementById('editSaleModal').classList.add('hidden');
                
                // Recharger la page pour mettre à jour la liste
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: data.message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                }).then(() => {
                    window.location.reload();
                });
            } else {
                // Afficher les erreurs
                if (data.errors) {
                    Object.keys(data.errors).forEach(key => {
                        showError(`edit_${key}_error`, data.errors[key][0]);
                    });
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: data.message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Une erreur est survenue lors de la communication avec le serveur',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        });
    });
    
    // Suppression d'une vente
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-sale')) {
            const button = e.target.closest('.delete-sale');
            const id = button.getAttribute('data-id');
            
            Swal.fire({
                title: 'Confirmation',
                text: 'Êtes-vous sûr de vouloir supprimer cette vente ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Supprimer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Envoi de la requête AJAX
                    fetch(`{{ url('productions/sales') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Recharger la page pour mettre à jour la liste
                            Swal.fire({
                                icon: 'success',
                                title: 'Succès',
                                text: data.message,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: data.message,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: 'Une erreur est survenue lors de la communication avec le serveur',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    });
                }
            });
        }
    });
    
    // ==================== GESTION DES VALIDATIONS/REJETS ====================
    
    // Sélectionner/désélectionner toutes les ventes en attente
    const selectAllCheckbox = document.getElementById('selectAllPending');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input.sale-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            
            updateValidationButtons();
        });
    }
    
    // Mettre à jour le status des boutons de validation/rejet
    function updateValidationButtons() {
        const checkboxes = document.querySelectorAll('input.sale-checkbox:checked');
        const validateBtn = document.getElementById('validateSalesBtn');
        const rejectBtn = document.getElementById('rejectSalesBtn');
        
        const hasSelection = checkboxes.length > 0;
        
        validateBtn.disabled = !hasSelection;
        rejectBtn.disabled = !hasSelection;
        
        if (hasSelection) {
            validateBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            rejectBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            validateBtn.classList.add('opacity-50', 'cursor-not-allowed');
            rejectBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
    
    // Écouter les changements sur les cases à cocher individuelles
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('sale-checkbox')) {
            updateValidationButtons();
            
            // Mettre à jour l'état du "Tout sélectionner"
            const allCheckboxes = document.querySelectorAll('input.sale-checkbox');
            const checkedCheckboxes = document.querySelectorAll('input.sale-checkbox:checked');
            
            const selectAllCheckbox = document.getElementById('selectAllPending');
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = allCheckboxes.length === checkedCheckboxes.length;
                selectAllCheckbox.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < allCheckboxes.length;
            }
        }
    });
    
    // Initialiser l'état des boutons au chargement
    updateValidationButtons();
    
    // Validation des ventes sélectionnées
    document.getElementById('validateSalesBtn').addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('input.sale-checkbox:checked');
        
        if (checkedBoxes.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: 'Veuillez sélectionner au moins une vente à valider',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            return;
        }
        
        const saleIds = Array.from(checkedBoxes).map(checkbox => checkbox.value);
        
        Swal.fire({
            title: 'Confirmation',
            text: `Êtes-vous sûr de vouloir valider ${saleIds.length} vente(s) ? Cette action déduira le stock des cartons concernés.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Valider',
            cancelButtonText: 'Annuler',
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280'
        }).then((result) => {
            if (result.isConfirmed) {
                // Envoi de la requête AJAX
                fetch('{{ route("productions.sales.validate") }}', {
                    method: 'POST',
                    body: JSON.stringify({ sale_ids: saleIds }),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Recharger la page pour mettre à jour la liste
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: data.message,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        // Si erreurs spécifiques de stock
                        if (data.errors && data.errors.length > 0) {
                            let errorMessage = data.message + '<ul class="mt-2 text-left">';
                            data.errors.forEach(error => {
                                errorMessage += `<li>- ${error}</li>`;
                            });
                            errorMessage += '</ul>';
                            
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                html: errorMessage,
                                confirmButtonColor: '#3085d6'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: data.message,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Une erreur est survenue lors de la communication avec le serveur',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
            }
        });
    });
    
    // Rejet des ventes sélectionnées
    document.getElementById('rejectSalesBtn').addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('input.sale-checkbox:checked');
        
        if (checkedBoxes.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: 'Veuillez sélectionner au moins une vente à rejeter',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            return;
        }
        
        const saleIds = Array.from(checkedBoxes).map(checkbox => checkbox.value);
        
        Swal.fire({
            title: 'Confirmation',
            text: `Êtes-vous sûr de vouloir rejeter ${saleIds.length} vente(s) ? Cette action n'affectera pas le stock des cartons.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Rejeter',
            cancelButtonText: 'Annuler',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280'
        }).then((result) => {
            if (result.isConfirmed) {
                // Envoi de la requête AJAX
                fetch('{{ route("productions.sales.reject") }}', {
                    method: 'POST',
                    body: JSON.stringify({ sale_ids: saleIds }),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Recharger la page pour mettre à jour la liste
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: data.message,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: data.message,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Une erreur est survenue lors de la communication avec le serveur',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
            }
        });
    });
});
</script>


