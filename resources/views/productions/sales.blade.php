

@extends('layouts.app')

@section('title', 'Gestion des ventes de production')

@section('breadcrumb')
    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
    <a href="{{ route('productions.sales.index') }}" class="text-gray-700 hover:text-blue-700 transition-colors">Ventes de production</a>
@endsection

@section('page-title', 'Ventes de production')
@section('page-subtitle', 'Gestion des sorties de cartons produits')

<style>
    /* Intégration avec le thème navy existant */
    :root {
        --primary-blue-from: #3b82f6;
        --primary-blue-to: #2563eb;
        --navy-50: #f0f4f8;
        --navy-100: #d9e2ec;
        --navy-700: #334e68;
        --navy-800: #243b53;
        --navy-900: #102a43;
    }

    /* ==================== STYLES RESPONSIFS INTÉGRÉS ==================== */
    
    /* Configuration responsive héritée du layout */
    .sales-container {
        width: 100%;
        max-width: 100%;
    }

    /* Grilles adaptatives avec le thème navy */
    .form-grid {
        display: grid;
        gap: 1rem;
        grid-template-columns: 1fr;
    }

    .stock-grid {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    }

    /* Cartes de stock avec le thème navy */
    .stock-card {
        background: linear-gradient(135deg, var(--navy-50) 0%, #f9fafb 100%);
        border: 1px solid var(--navy-100);
        border-radius: 0.75rem;
        padding: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stock-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-blue-from), var(--primary-blue-to));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stock-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(36, 59, 83, 0.15);
        border-color: var(--primary-blue-from);
    }

    .stock-card:hover::before {
        opacity: 1;
    }

    /* Conteneurs de section avec le thème navy */
    .section-card {
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid #e5e7eb;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .section-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .section-header {
        background: linear-gradient(135deg, var(--navy-800) 0%, var(--navy-900) 100%);
        color: white;
        padding: 1.5rem;
        border-bottom: 3px solid var(--primary-blue-from);
    }

    /* Boutons avec le style du layout */
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-blue-from) 0%, var(--primary-blue-to) 100%);
        color: white;
        border: none;
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    /* Tableaux responsifs intégrés */
    .table-responsive {
        background: white;
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .table-container {
        position: relative;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table-container table {
        width: 100%;
        min-width: 800px;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-container th {
        background: linear-gradient(135deg, var(--navy-50) 0%, #f8fafc 100%);
        color: var(--navy-800);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1rem 0.75rem;
        border-bottom: 2px solid var(--navy-100);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table-container td {
        padding: 1rem 0.75rem;
        border-bottom: 1px solid #f3f4f6;
        transition: background-color 0.2s ease;
    }

    .table-container tbody tr:hover td {
        background-color: rgba(59, 130, 246, 0.05);
    }

    /* Vue cartes pour mobile */
    .card-view {
        display: none;
    }

    .sale-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        margin-bottom: 1rem;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .sale-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        border-color: var(--primary-blue-from);
    }

    .sale-card-header {
        background: linear-gradient(135deg, var(--navy-800) 0%, var(--navy-900) 100%);
        color: white;
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .sale-card-content {
        padding: 1rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        font-size: 0.875rem;
    }

    .sale-card-footer {
        background: #f8fafc;
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #e5e7eb;
        font-weight: 600;
    }

    /* Formulaires avec le thème navy */
    .form-section {
        background: linear-gradient(135deg, #f8fafc 0%, var(--navy-50) 100%);
        border-radius: 0.75rem;
        padding: 1.5rem;
        border: 1px solid var(--navy-100);
    }

    .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.75rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control:focus {
        border-color: var(--primary-blue-from);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    /* Badges de statut */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-validated {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        color: #166534;
        border: 1px solid #22c55e;
    }

    .status-rejected {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border: 1px solid #ef4444;
    }

    /* Indicateurs visuels */
    .scroll-indicator {
        display: none;
        text-align: center;
        padding: 0.75rem;
        font-size: 0.75rem;
        color: var(--navy-700);
        background: linear-gradient(135deg, var(--navy-50) 0%, #f8fafc 100%);
        border: 1px solid var(--navy-100);
        border-top: none;
        font-weight: 500;
    }

    /* Modal responsive avec thème navy */
    .modal-content {
        width: 95vw;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        margin: 1rem;
        border-radius: 0.75rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 1px solid var(--navy-100);
    }

    .modal-header {
        background: linear-gradient(135deg, var(--navy-800) 0%, var(--navy-900) 100%);
        color: white;
        padding: 1.5rem;
        border-bottom: 3px solid var(--primary-blue-from);
    }

    /* Total display spécial */
    .total-display {
        background: linear-gradient(135deg, var(--navy-50) 0%, #f0f9ff 100%);
        border: 2px solid var(--primary-blue-from);
        border-radius: 0.75rem;
        padding: 1rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .total-display::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    /* ==================== BREAKPOINTS RESPONSIVES ==================== */
    
    /* Mobile (0-639px) */
    @media screen and (max-width: 639px) {
        .table-view {
            display: none;
        }

        .card-view {
            display: block;
        }

        .scroll-indicator {
            display: block;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .stock-grid {
            grid-template-columns: 1fr;
        }

        .button-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .section-card {
            margin: 0 -0.5rem;
            border-radius: 0.5rem;
        }

        .stock-card, .sale-card {
            margin: 0 -0.25rem;
        }

        .total-display {
            margin-top: 1rem;
        }
    }

    /* Tablette (640px-1023px) */
    @media screen and (min-width: 640px) and (max-width: 1023px) {
        .form-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .stock-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .modal-content {
            width: 90vw;
            max-width: 700px;
        }

        .button-group {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
    }

    /* Desktop (1024px+) */
    @media screen and (min-width: 1024px) {
        .form-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .stock-grid {
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        }

        .table-view {
            display: block;
        }

        .card-view {
            display: none;
        }

        .button-group {
            display: flex;
            flex-direction: row;
            gap: 0.75rem;
        }

        .total-display {
            grid-column: span 1;
        }
    }

    /* Grands écrans (1280px+) */
    @media screen and (min-width: 1280px) {
        .stock-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    /* Très grands écrans (1536px+) */
    @media screen and (min-width: 1536px) {
        .sales-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .table-container th,
        .table-container td {
            padding: 1.25rem 1rem;
        }

        .section-card {
            border-radius: 1rem;
        }
    }

    /* Améliorations tactiles */
    @media (hover: none) and (pointer: coarse) {
        .btn-primary, .form-control, .sale-card {
            min-height: 48px;
        }

        .form-control {
            font-size: 16px; /* Évite le zoom sur iOS */
        }
    }

    /* Corrections spécifiques */
    input[type="number"] {
        -moz-appearance: textfield;
    }

    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Animations héritées du layout */
    .animate-fadeInUp {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Print styles */
    @media print {
        .button-group, .btn-primary, .btn-success, .btn-danger {
            display: none !important;
        }
        
        .table-container {
            overflow: visible !important;
        }
        
        .section-card, .stock-card, .sale-card {
            break-inside: avoid;
            box-shadow: none !important;
            border: 1px solid #ccc !important;
        }
    }
</style>

@section('content')
<div class="sales-container space-y-6 animate-fadeIn">
    <!-- Stock disponible -->
    <div class="section-card animate-fadeInUp">
        <div class="section-header">
            <h3 class="text-xl font-bold flex items-center">
                <i class="fas fa-chart-bar mr-3 text-blue-300"></i>
                Stock disponible par type de production
            </h3>
        </div>
        
        <div class="p-6">
            <div class="stock-grid">
                @foreach($stockData as $typeId => $typeData)
                <div class="stock-card group">
                    <h4 class="font-bold text-navy-800 mb-4 text-lg flex items-center">
                        <i class="fas fa-cube mr-2 text-blue-500"></i>
                        {{ $typeData['name'] }}
                    </h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-100">
                            <span class="text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-box-open mr-2 text-blue-400"></i>
                                Petits cartons
                            </span>
                            <span class="text-lg font-bold {{ $typeData['petit']['available'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $typeData['petit']['available'] }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-100">
                            <span class="text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-boxes mr-2 text-blue-400"></i>
                                Grands cartons
                            </span>
                            <span class="text-lg font-bold {{ $typeData['grand']['available'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $typeData['grand']['available'] }}
                            </span>
                        </div>
                        <div class="text-xs text-gray-600 pt-3 border-t border-gray-200 space-y-1 bg-gray-50 p-3 rounded-lg">
                            <div class="flex justify-between">
                                <span>Produits :</span>
                                <span class="font-medium">{{ $typeData['petit']['produced'] }} petits, {{ $typeData['grand']['produced'] }} grands</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Vendus :</span>
                                <span class="font-medium text-green-600">{{ $typeData['petit']['sold'] }} petits, {{ $typeData['grand']['sold'] }} grands</span>
                            </div>
                            <div class="flex justify-between">
                                <span>En attente :</span>
                                <span class="font-medium text-orange-600">{{ $typeData['petit']['pending'] }} petits, {{ $typeData['grand']['pending'] }} grands</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Formulaire d'ajout de vente -->
    <div class="section-card animate-fadeInUp">
        <div class="section-header">
            <h3 class="text-xl font-bold flex items-center">
                <i class="fas fa-plus-circle mr-3 text-blue-300"></i>
                Enregistrer une vente
            </h3>
        </div>
        
        <div class="p-6">
            <div class="form-section">
                <form id="addSaleForm" class="space-y-6">
                    @csrf
                    
                    <!-- Première ligne du formulaire -->
                    <div class="form-grid">
                        <div>
                            <label for="type_id" class="block text-sm font-bold text-navy-800 mb-2">
                                <i class="fas fa-tag mr-1 text-blue-500"></i>
                                Type de production <span class="text-red-500">*</span>
                            </label>
                            <select id="type_id" name="type_id" class="form-control w-full" required>
                                <option value="">-- Sélectionner un type --</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <div id="type_id_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <div>
                            <label for="carton_type" class="block text-sm font-bold text-navy-800 mb-2">
                                <i class="fas fa-cube mr-1 text-blue-500"></i>
                                Type de carton <span class="text-red-500">*</span>
                            </label>
                            <select id="carton_type" name="carton_type" class="form-control w-full" required>
                                <option value="">-- Sélectionner un type --</option>
                                <option value="petit">Petit</option>
                                <option value="grand">Grand</option>
                            </select>
                            <div id="carton_type_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <div>
                            <label for="stock_display" class="block text-sm font-bold text-navy-800 mb-2">
                                <i class="fas fa-warehouse mr-1 text-blue-500"></i>
                                Stock disponible
                            </label>
                            <input type="text" id="stock_display" class="form-control w-full bg-gray-100" readonly>
                            <div class="text-xs text-gray-500 mt-1">Stock disponible pour le type et la taille sélectionnés</div>
                        </div>
                    </div>
                    
                    <!-- Deuxième ligne du formulaire -->
                    <div class="form-grid">
                        <div>
                            <label for="quantity" class="block text-sm font-bold text-navy-800 mb-2">
                                <i class="fas fa-calculator mr-1 text-blue-500"></i>
                                Quantité <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="quantity" name="quantity" min="0.01" step="0.01" class="form-control w-full" required>
                            <div id="quantity_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <div>
                            <label for="unit_price" class="block text-sm font-bold text-navy-800 mb-2">
                                <i class="fas fa-euro-sign mr-1 text-blue-500"></i>
                                Prix unitaire <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="unit_price" name="unit_price" min="0" step="0.01" class="form-control w-full" required>
                            <div id="unit_price_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <div class="total-display">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-receipt mr-2 text-navy-700"></i>
                                <span class="text-sm font-bold text-navy-700 mr-3">Total :</span>
                                <span id="total_display" class="text-2xl font-bold text-navy-900">0,00 </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Troisième ligne du formulaire -->
                    <div class="form-grid">
                        <div>
                            <label for="client_name" class="block text-sm font-bold text-navy-800 mb-2">
                                <i class="fas fa-user mr-1 text-blue-500"></i>
                                Nom du client <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="client_name" name="client_name" class="form-control w-full" required>
                            <div id="client_name_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <div>
                            <label for="client_firstname" class="block text-sm font-bold text-navy-800 mb-2">
                                <i class="fas fa-user-circle mr-1 text-blue-500"></i>
                                Prénom du client <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="client_firstname" name="client_firstname" class="form-control w-full" required>
                            <div id="client_firstname_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <div>
                            <label for="client_phone" class="block text-sm font-bold text-navy-800 mb-2">
                                <i class="fas fa-phone mr-1 text-blue-500"></i>
                                Téléphone
                            </label>
                            <input type="text" id="client_phone" name="client_phone" class="form-control w-full">
                            <div id="client_phone_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                    </div>
                    
                    <!-- Quatrième ligne du formulaire -->
                    <div class="form-grid">
                        <div class="lg:col-span-2">
                            <label for="client_email" class="block text-sm font-bold text-navy-800 mb-2">
                                <i class="fas fa-envelope mr-1 text-blue-500"></i>
                                Email
                            </label>
                            <input type="email" id="client_email" name="client_email" class="form-control w-full">
                            <div id="client_email_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <div>
                            <label for="notes" class="block text-sm font-bold text-navy-800 mb-2">
                                <i class="fas fa-sticky-note mr-1 text-blue-500"></i>
                                Notes
                            </label>
                            <textarea id="notes" name="notes" rows="3" class="form-control w-full resize-y" placeholder="Informations complémentaires..."></textarea>
                            <div id="notes_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit" class="btn-primary w-full ripple text-lg py-4">
                            <i class="fas fa-save mr-3"></i> 
                            Enregistrer la vente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Ventes en attente de validation -->
    <div class="section-card animate-fadeInUp">
        <div class="section-header">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
                <h3 class="text-xl font-bold flex items-center mb-4 lg:mb-0">
                    <i class="fas fa-hourglass-half mr-3 text-yellow-300"></i>
                    Ventes en attente
                </h3>
                
                <div class="button-group">
                    <button id="validateSalesBtn" class="btn-primary btn-success ripple disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-check-circle mr-2"></i> 
                        <span class="hidden sm:inline">Valider</span>
                        <span class="sm:hidden">Valider les sélectionnées</span>
                    </button>
                    <button id="rejectSalesBtn" class="btn-primary btn-danger ripple disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-times-circle mr-2"></i> 
                        <span class="hidden sm:inline">Rejeter</span>
                        <span class="sm:hidden">Rejeter les sélectionnées</span>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Vue tableau pour desktop -->
            <div class="table-view">
                <div class="table-responsive">
                    <div class="table-container">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="text-left w-12">
                                        <input id="selectAllPending" type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    </th>
                                    <th class="text-left">Type</th>
                                    <th class="text-left">Carton</th>
                                    <th class="text-left">Quantité</th>
                                    <th class="text-left">Prix Unit.</th>
                                    <th class="text-left">Total</th>
                                    <th class="text-left">Client</th>
                                    <th class="text-left">Contact</th>
                                    <th class="text-left">Date</th>
                                    <th class="text-right w-24">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="pendingSalesBody">
                                @forelse($pendingSales as $sale)
                                <tr data-id="{{ $sale->id }}" class="hover:bg-blue-50 transition-colors">
                                    <td>
                                        <input type="checkbox" name="sale_ids[]" value="{{ $sale->id }}" class="sale-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    </td>
                                    <td>
                                        <div class="font-semibold text-navy-800">{{ $sale->type->name }}</div>
                                    </td>
                                    <td>
                                        <div class="text-gray-700">{{ ucfirst($sale->carton_type) }}</div>
                                    </td>
                                    <td>
                                        <div class="font-medium">{{ number_format($sale->quantity, 2, ',', ' ') }}</div>
                                    </td>
                                    <td>
                                        <div class="font-medium">{{ number_format($sale->unit_price, 2, ',', ' ') }} </div>
                                    </td>
                                    <td>
                                        <div class="font-bold text-navy-800">{{ number_format($sale->quantity * $sale->unit_price, 2, ',', ' ') }} </div>
                                    </td>
                                    <td>
                                        <div class="font-medium text-gray-800">{{ $sale->client_name }} {{ $sale->client_firstname }}</div>
                                    </td>
                                    <td>
                                        <div class="text-sm text-gray-600">
                                            @if($sale->client_phone)
                                                <div><i class="fas fa-phone mr-1"></i>{{ $sale->client_phone }}</div>
                                            @endif
                                            @if($sale->client_email)
                                                <div><i class="fas fa-envelope mr-1"></i>{{ $sale->client_email }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-sm text-gray-600">{{ $sale->created_at->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td class="text-right">
                                        <button class="edit-sale text-blue-600 hover:text-blue-800 mx-1 p-2 rounded-full hover:bg-blue-100 transition-all" 
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
                                        <button class="delete-sale text-red-600 hover:text-red-800 mx-1 p-2 rounded-full hover:bg-red-100 transition-all" 
                                                data-id="{{ $sale->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="px-4 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                                            <div class="text-lg font-medium">Aucune vente en attente de validation</div>
                                            <div class="text-sm">Les nouvelles ventes apparaîtront ici</div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="scroll-indicator">
                        <i class="fas fa-arrows-left-right mr-2"></i>
                        Faites défiler horizontalement pour voir plus de colonnes
                    </div>
                </div>
            </div>
            
            <!-- Vue cartes pour mobile -->
            <div class="card-view">
                @forelse($pendingSales as $sale)
                <div class="sale-card" data-id="{{ $sale->id }}">
                    <div class="sale-card-header">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="sale_ids[]" value="{{ $sale->id }}" class="sale-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <div>
                                <div class="font-bold text-white">{{ $sale->type->name }} - {{ ucfirst($sale->carton_type) }}</div>
                                <div class="text-sm text-blue-200">{{ $sale->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button class="edit-sale text-blue-200 hover:text-white p-2 rounded-full hover:bg-navy-700 transition-all" 
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
                            <button class="delete-sale text-red-300 hover:text-white p-2 rounded-full hover:bg-red-600 transition-all" 
                                    data-id="{{ $sale->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="sale-card-content">
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase">Quantité</div>
                            <div class="font-bold text-navy-800">{{ number_format($sale->quantity, 2, ',', ' ') }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase">Prix unitaire</div>
                            <div class="font-bold text-navy-800">{{ number_format($sale->unit_price, 2, ',', ' ') }} </div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase">Client</div>
                            <div class="font-bold text-navy-800">{{ $sale->client_name }} {{ $sale->client_firstname }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase">Contact</div>
                            <div class="text-sm">
                                @if($sale->client_phone)
                                    <div><i class="fas fa-phone mr-1 text-blue-500"></i>{{ $sale->client_phone }}</div>
                                @endif
                                @if($sale->client_email)
                                    <div><i class="fas fa-envelope mr-1 text-blue-500"></i>{{ $sale->client_email }}</div>
                                @endif
                                @if(!$sale->client_phone && !$sale->client_email)
                                    <span class="text-gray-400">-</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="sale-card-footer">
                        <div class="text-sm font-medium text-gray-600">Total</div>
                        <div class="text-xl font-bold text-navy-800">{{ number_format($sale->quantity * $sale->unit_price, 2, ',', ' ') }} </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-gray-500">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                        <div class="text-lg font-medium">Aucune vente en attente de validation</div>
                        <div class="text-sm">Les nouvelles ventes apparaîtront ici</div>
                    </div>
                </div>
                @endforelse
            </div>
            
            <!-- Pagination pour les ventes en attente -->
            @if($pendingSales->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $pendingSales->appends(['history_page' => request()->history_page])->links() }}
            </div>
            @endif
        </div>
    </div>
    
    <!-- Historique des ventes validées/rejetées -->
    <div class="section-card animate-fadeInUp">
        <div class="section-header">
            <h3 class="text-xl font-bold flex items-center">
                <i class="fas fa-history mr-3 text-blue-300"></i>
                Historique des ventes
            </h3>
        </div>
        
        <div class="p-6">
            <!-- Vue tableau pour desktop -->
            <div class="table-view">
                <div class="table-responsive">
                    <div class="table-container">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="text-left">Type</th>
                                    <th class="text-left">Carton</th>
                                    <th class="text-left">Quantité</th>
                                    <th class="text-left">Prix Unit.</th>
                                    <th class="text-left">Total</th>
                                    <th class="text-left">Client</th>
                                    <th class="text-left">Contact</th>
                                    <th class="text-left">Statut</th>
                                    <th class="text-left">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($historySales as $sale)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td>
                                        <div class="font-semibold text-navy-800">{{ $sale->type->name }}</div>
                                    </td>
                                    <td>
                                        <div class="text-gray-700">{{ ucfirst($sale->carton_type) }}</div>
                                    </td>
                                    <td>
                                        <div class="font-medium">{{ number_format($sale->quantity, 2, ',', ' ') }}</div>
                                    </td>
                                    <td>
                                        <div class="font-medium">{{ number_format($sale->unit_price, 2, ',', ' ') }} </div>
                                    </td>
                                    <td>
                                        <div class="font-bold text-navy-800">{{ number_format($sale->quantity * $sale->unit_price, 2, ',', ' ') }} </div>
                                    </td>
                                    <td>
                                        <div class="font-medium text-gray-800">{{ $sale->client_name }} {{ $sale->client_firstname }}</div>
                                    </td>
                                    <td>
                                        <div class="text-sm text-gray-600">
                                            @if($sale->client_phone)
                                                <div><i class="fas fa-phone mr-1"></i>{{ $sale->client_phone }}</div>
                                            @endif
                                            @if($sale->client_email)
                                                <div><i class="fas fa-envelope mr-1"></i>{{ $sale->client_email }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="status-badge {{ $sale->status === 'validé' ? 'status-validated' : 'status-rejected' }}">
                                            <i class="fas {{ $sale->status === 'validé' ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                            {{ ucfirst($sale->status) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-sm text-gray-600">{{ $sale->updated_at->format('d/m/Y H:i') }}</div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-history text-4xl mb-4 text-gray-300"></i>
                                            <div class="text-lg font-medium">Aucune vente dans l'historique</div>
                                            <div class="text-sm">L'historique des ventes apparaîtra ici</div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="scroll-indicator">
                        <i class="fas fa-arrows-left-right mr-2"></i>
                        Faites défiler horizontalement pour voir plus de colonnes
                    </div>
                </div>
            </div>
            
            <!-- Vue cartes pour mobile -->
            <div class="card-view">
                @forelse($historySales as $sale)
                <div class="sale-card">
                    <div class="sale-card-header">
                        <div>
                            <div class="font-bold text-white">{{ $sale->type->name }} - {{ ucfirst($sale->carton_type) }}</div>
                            <div class="text-sm text-blue-200">{{ $sale->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="status-badge {{ $sale->status === 'validé' ? 'status-validated' : 'status-rejected' }}">
                            <i class="fas {{ $sale->status === 'validé' ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                            {{ ucfirst($sale->status) }}
                        </div>
                    </div>
                    
                    <div class="sale-card-content">
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase">Quantité</div>
                            <div class="font-bold text-navy-800">{{ number_format($sale->quantity, 2, ',', ' ') }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase">Prix unitaire</div>
                            <div class="font-bold text-navy-800">{{ number_format($sale->unit_price, 2, ',', ' ') }} </div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase">Client</div>
                            <div class="font-bold text-navy-800">{{ $sale->client_name }} {{ $sale->client_firstname }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase">Contact</div>
                            <div class="text-sm">
                                @if($sale->client_phone)
                                    <div><i class="fas fa-phone mr-1 text-blue-500"></i>{{ $sale->client_phone }}</div>
                                @endif
                                @if($sale->client_email)
                                    <div><i class="fas fa-envelope mr-1 text-blue-500"></i>{{ $sale->client_email }}</div>
                                @endif
                                @if(!$sale->client_phone && !$sale->client_email)
                                    <span class="text-gray-400">-</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="sale-card-footer">
                        <div class="text-sm font-medium text-gray-600">Total</div>
                        <div class="text-xl font-bold text-navy-800">{{ number_format($sale->quantity * $sale->unit_price, 2, ',', ' ') }} </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-gray-500">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-history text-4xl mb-4 text-gray-300"></i>
                        <div class="text-lg font-medium">Aucune vente dans l'historique</div>
                        <div class="text-sm">L'historique des ventes apparaîtra ici</div>
                    </div>
                </div>
                @endforelse
            </div>
            
            <!-- Pagination pour l'historique -->
            @if($historySales->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $historySales->appends(['pending_page' => request()->pending_page])->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal d'édition -->
<div id="editSaleModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" aria-hidden="true"></div>
        <div class="modal-content inline-block align-middle bg-white text-left overflow-hidden shadow-xl transform transition-all">
            <div class="modal-header">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-edit mr-3 text-blue-300"></i>
                    Modifier la vente
                </h3>
            </div>
            <div class="p-6">
                <form id="editSaleForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="edit_sale_id" name="id">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_type_id" class="block text-sm font-bold text-navy-800 mb-2">
                                <i class="fas fa-tag mr-1 text-blue-500"></i>
                                Type de production <span class="text-red-500">*</span>
                            </label>
                            <select id="edit_type_id" name="type_id" class="form-control w-full" required>
                                <option value="">-- Sélectionner un type --</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <div id="edit_type_id_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <div>
                            <label for="edit_carton_type" class="block text-sm font-bold text-navy-800 mb-2">
                                <i class="fas fa-cube mr-1 text-blue-500"></i>
                                Type de carton <span class="text-red-500">*</span>
                            </label>
                            <select id="edit_carton_type" name="carton_type" class="form-control w-full" required>
                                <option value="">-- Sélectionner un type --</option>
                                <option value="petit">Petit</option>
                                <option value="grand">Grand</option>
                            </select>
                            <div id="edit_carton_type_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="edit_stock_display" class="block text-sm font-bold text-navy-800 mb-2">
                            <i class="fas fa-warehouse mr-1 text-blue-500"></i>
                            Stock disponible
                        </label>
                        <input type="text" id="edit_stock_display" class="form-control w-full bg-gray-100" readonly>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_quantity" class="block text-sm font-bold text-navy-800 mb-2">
                                <i class="fas fa-calculator mr-1 text-blue-500"></i>
                                Quantité <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="edit_quantity" name="quantity" min="0.01" step="0.01" class="form-control w-full" required>
                            <div id="edit_quantity_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <div>
                            <label for="edit_unit_price" class="block text-sm font-bold text-navy-800 mb-2">
                                <i class="fas fa-euro-sign mr-1 text-blue-500"></i>
                                Prix unitaire <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="edit_unit_price" name="unit_price" min="0" step="0.01" class="form-control w-full" required>
                            <div id="edit_unit_price_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_client_name" class="block text-sm font-bold text-navy-800 mb-2">
                                <i class="fas fa-user mr-1 text-blue-500"></i>
                                Nom du client <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="edit_client_name" name="client_name" class="form-control w-full" required>
                            <div id="edit_client_name_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <div>
                            <label for="edit_client_firstname" class="block text-sm font-bold text-navy-800 mb-2">
                                <i class="fas fa-user-circle mr-1 text-blue-500"></i>
                                Prénom du client <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="edit_client_firstname" name="client_firstname" class="form-control w-full" required>
                            <div id="edit_client_firstname_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_client_phone" class="block text-sm font-bold text-navy-800 mb-2">
                                <i class="fas fa-phone mr-1 text-blue-500"></i>
                                Téléphone
                            </label>
                            <input type="text" id="edit_client_phone" name="client_phone" class="form-control w-full">
                            <div id="edit_client_phone_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                        
                        <div>
                            <label for="edit_client_email" class="block text-sm font-bold text-navy-800 mb-2">
                                <i class="fas fa-envelope mr-1 text-blue-500"></i>
                                Email
                            </label>
                            <input type="email" id="edit_client_email" name="client_email" class="form-control w-full">
                            <div id="edit_client_email_error" class="text-red-500 text-xs mt-1 hidden"></div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="edit_notes" class="block text-sm font-bold text-navy-800 mb-2">
                            <i class="fas fa-sticky-note mr-1 text-blue-500"></i>
                            Notes
                        </label>
                        <textarea id="edit_notes" name="notes" rows="3" class="form-control w-full resize-y" placeholder="Informations complémentaires..."></textarea>
                        <div id="edit_notes_error" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    
                    <!-- Affichage du total -->
                    <div class="total-display">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-receipt mr-2 text-navy-700"></i>
                            <span class="text-sm font-bold text-navy-700 mr-3">Total :</span>
                            <span id="edit_total_display" class="text-2xl font-bold text-navy-900">0,00 </span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row-reverse space-y-3 sm:space-y-0 sm:space-x-reverse sm:space-x-3">
                <button type="button" id="saveSaleButton" class="btn-primary ripple">
                    <i class="fas fa-save mr-2"></i> Enregistrer
                </button>
                <button type="button" id="cancelSaleEditButton" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 font-medium">
                    <i class="fas fa-times mr-2"></i> Annuler
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

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
        return parseFloat(value).toFixed(2).replace('.', ',') + ' ';
    }
    
    // Mettre à jour l'affichage du stock disponible
    function updateStockDisplay(typeId, cartonType, displayElement) {
        if (typeId && cartonType && stockData[typeId]) {
            const available = stockData[typeId][cartonType]['available'];
            displayElement.value = `${available} carton(s) ${cartonType}(s) disponible(s)`;
            
            if (available <= 0) {
                displayElement.classList.add('text-red-600', 'font-bold', 'border-red-300');
            } else {
                displayElement.classList.remove('text-red-600', 'font-bold', 'border-red-300');
            }
        } else {
            displayElement.value = '';
            displayElement.classList.remove('text-red-600', 'font-bold', 'border-red-300');
        }
    }
    
    // Calculer et mettre à jour le total
    function updateTotal(quantityElement, priceElement, displayElement) {
        if (!quantityElement || !priceElement || !displayElement) {
            console.warn("Un ou plusieurs éléments requis pour updateTotal sont manquants");
            return;
        }
        
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
        if (!typeSelect || !cartonTypeSelect || !stockDisplay) return;
        
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
        if (!quantityInput || !unitPriceInput || !totalDisplay) return;
        
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
                    totalDisplay.textContent = '0,00 ';
                    
                    // Recharger la page pour afficher la nouvelle vente
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès !',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 2000,
                        toast: true,
                        position: 'top-end'
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
                        showConfirmButton: false,
                        timer: 3000,
                        toast: true,
                        position: 'top-end'
                    });
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue lors de la communication avec le serveur',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true,
                    position: 'top-end'
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
        if (!editTypeSelect || !editCartonTypeSelect || !editStockDisplay) return;
        
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
        if (!editQuantityInput || !editUnitPriceInput || !editTotalDisplay) {
            console.warn("Un ou plusieurs éléments requis pour updateEditFormTotal sont manquants");
            return;
        }
        
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
            
            // S'assurer que tous les éléments existent avant de continuer
            const editSaleId = document.getElementById('edit_sale_id');
            const editTypeId = document.getElementById('edit_type_id');
            const editCartonType = document.getElementById('edit_carton_type');
            const editQuantity = document.getElementById('edit_quantity');
            const editUnitPrice = document.getElementById('edit_unit_price');
            const editClientName = document.getElementById('edit_client_name');
            const editClientFirstname = document.getElementById('edit_client_firstname');
            const editClientPhone = document.getElementById('edit_client_phone');
            const editClientEmail = document.getElementById('edit_client_email');
            const editNotes = document.getElementById('edit_notes');
            
            if (!editSaleId || !editTypeId || !editCartonType || !editQuantity || !editUnitPrice || 
                !editClientName || !editClientFirstname) {
                console.error("Des éléments du formulaire d'édition sont manquants");
                return;
            }
            
            // Remplir le formulaire d'édition
            editSaleId.value = id;
            editTypeId.value = typeId;
            editCartonType.value = cartonType;
            editQuantity.value = quantity;
            editUnitPrice.value = unitPrice;
            editClientName.value = clientName;
            editClientFirstname.value = clientFirstname;
            
            if (editClientPhone) editClientPhone.value = clientPhone || '';
            if (editClientEmail) editClientEmail.value = clientEmail || '';
            if (editNotes) editNotes.value = notes || '';
            
            // Mettre à jour l'affichage du stock et du total
            updateEditFormStock();
            updateEditFormTotal();
            
            // Afficher la modal
            const editSaleModal = document.getElementById('editSaleModal');
            if (editSaleModal) {
                editSaleModal.classList.remove('hidden');
            }
        }
    });
    
    // Fermer la modal d'édition
    const cancelSaleEditButton = document.getElementById('cancelSaleEditButton');
    if (cancelSaleEditButton) {
        cancelSaleEditButton.addEventListener('click', function() {
            const editSaleModal = document.getElementById('editSaleModal');
            if (editSaleModal) {
                editSaleModal.classList.add('hidden');
            }
        });
    }
    
    // Fermer la modal en cliquant sur le fond
    const editSaleModal = document.getElementById('editSaleModal');
    if (editSaleModal) {
        editSaleModal.addEventListener('click', function(e) {
            if (e.target === editSaleModal) {
                editSaleModal.classList.add('hidden');
            }
        });
    }
    
    // Enregistrer les modifications d'une vente
    const saveSaleButton = document.getElementById('saveSaleButton');
    if (saveSaleButton) {
        saveSaleButton.addEventListener('click', function() {
            // Réinitialiser les erreurs
            resetFormErrors('edit_');
            
            // Vérification de la disponibilité du stock
            if (!editTypeSelect || !editCartonTypeSelect || !editQuantityInput) {
                console.error("Des éléments du formulaire d'édition sont manquants");
                return;
            }
            
            const typeId = editTypeSelect.value;
            const cartonType = editCartonTypeSelect.value;
            const quantity = parseFloat(editQuantityInput.value);
            const saleId = document.getElementById('edit_sale_id')?.value;
            
            if (!saleId) {
                console.error("ID de vente manquant");
                return;
            }
            
            if (typeId && cartonType && stockData[typeId]) {
                let available = stockData[typeId][cartonType]['available'];
                
                // Trouver la vente actuelle dans le DOM pour voir si on modifie le même type/carton
                const currentRow = document.querySelector(`#pendingSalesBody tr[data-id="${saleId}"], .card-view .sale-card[data-id="${saleId}"]`);
                if (currentRow) {
                    const button = currentRow.querySelector('.edit-sale');
                    if (button) {
                        const currentTypeId = button.getAttribute('data-type-id');
                        const currentCartonType = button.getAttribute('data-carton-type');
                        const currentQuantity = parseFloat(button.getAttribute('data-quantity') || 0);
                        
                        // Si on modifie le même type et carton, ajouter la quantité actuelle au stock disponible
                        if (currentTypeId === typeId && currentCartonType === cartonType) {
                            available += currentQuantity;
                        }
                    }
                }
                
                if (quantity > available) {
                    showError('edit_quantity_error', `Stock insuffisant. Disponible: ${available} carton(s) ${cartonType}(s)`);
                    return;
                }
            }
            
            // Récupérer les données du formulaire
            const editSaleForm = document.getElementById('editSaleForm');
            if (!editSaleForm) {
                console.error("Formulaire d'édition non trouvé");
                return;
            }
            
            const formData = new FormData(editSaleForm);
            
            // Envoi de la requête AJAX
            fetch(`{{ url('productions/sales') }}/${saleId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-HTTP-Method-Override': 'PUT'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Fermer la modal
                    const editSaleModal = document.getElementById('editSaleModal');
                    if (editSaleModal) {
                        editSaleModal.classList.add('hidden');
                    }
                    
                    // Recharger la page pour mettre à jour la liste
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès !',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 2000,
                        toast: true,
                        position: 'top-end'
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
                        showConfirmButton: false,
                        timer: 3000,
                        toast: true,
                        position: 'top-end'
                    });
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue lors de la communication avec le serveur',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true,
                    position: 'top-end'
                });
            });
        });
    }
    
    // Suppression d'une vente
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-sale')) {
            const button = e.target.closest('.delete-sale');
            const id = button.getAttribute('data-id');
            
            if (!id) return;
            
            Swal.fire({
                title: 'Confirmation de suppression',
                text: 'Êtes-vous sûr de vouloir supprimer cette vente ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Envoi de la requête AJAX
                    fetch(`{{ url('productions/sales') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Recharger la page pour mettre à jour la liste
                            Swal.fire({
                                icon: 'success',
                                title: 'Supprimé !',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 2000,
                                toast: true,
                                position: 'top-end'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 3000,
                                toast: true,
                                position: 'top-end'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: 'Une erreur est survenue lors de la communication avec le serveur',
                            showConfirmButton: false,
                            timer: 3000,
                            toast: true,
                            position: 'top-end'
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
        
        if (!validateBtn || !rejectBtn) return;
        
        const hasSelection = checkboxes.length > 0;
        
        validateBtn.disabled = !hasSelection;
        rejectBtn.disabled = !hasSelection;
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
                selectAllCheckbox.checked = allCheckboxes.length === checkedCheckboxes.length && allCheckboxes.length > 0;
                selectAllCheckbox.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < allCheckboxes.length;
            }
        }
    });
    
    // Initialiser l'état des boutons au chargement
    updateValidationButtons();
    
    // Validation des ventes sélectionnées
    const validateSalesBtn = document.getElementById('validateSalesBtn');
    if (validateSalesBtn) {
        validateSalesBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('input.sale-checkbox:checked');
            
            if (checkedBoxes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Attention',
                    text: 'Veuillez sélectionner au moins une vente à valider',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true,
                    position: 'top-end'
                });
                return;
            }
            
            const saleIds = Array.from(checkedBoxes).map(checkbox => checkbox.value);
            
            Swal.fire({
                title: 'Confirmation de validation',
                text: `Êtes-vous sûr de vouloir valider ${saleIds.length} vente(s) ? Cette action déduira le stock des cartons concernés.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Oui, valider',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Envoi de la requête AJAX
                    fetch('{{ route("productions.sales.validate") }}', {
                        method: 'POST',
                        body: JSON.stringify({ sale_ids: saleIds }),
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
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
                                title: 'Validé !',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 2000,
                                toast: true,
                                position: 'top-end'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            // Si erreurs spécifiques de stock
                            if (data.errors && data.errors.length > 0) {
                                let errorMessage = data.message + '<ul class="mt-2 text-left list-disc list-inside">';
                                data.errors.forEach(error => {
                                    errorMessage += `<li>${error}</li>`;
                                });
                                errorMessage += '</ul>';
                                
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur de validation',
                                    html: errorMessage,
                                    confirmButtonColor: '#3085d6'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur',
                                    text: data.message,
                                    showConfirmButton: false,
                                    timer: 3000,
                                    toast: true,
                                    position: 'top-end'
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
                            showConfirmButton: false,
                            timer: 3000,
                            toast: true,
                            position: 'top-end'
                        });
                    });
                }
            });
        });
    }
    
    // Rejet des ventes sélectionnées
    const rejectSalesBtn = document.getElementById('rejectSalesBtn');
    if (rejectSalesBtn) {
        rejectSalesBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('input.sale-checkbox:checked');
            
            if (checkedBoxes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Attention',
                    text: 'Veuillez sélectionner au moins une vente à rejeter',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true,
                    position: 'top-end'
                });
                return;
            }
            
            const saleIds = Array.from(checkedBoxes).map(checkbox => checkbox.value);
            
            Swal.fire({
                title: 'Confirmation de rejet',
                text: `Êtes-vous sûr de vouloir rejeter ${saleIds.length} vente(s) ? Cette action n'affectera pas le stock des cartons.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, rejeter',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Envoi de la requête AJAX
                    fetch('{{ route("productions.sales.reject") }}', {
                        method: 'POST',
                        body: JSON.stringify({ sale_ids: saleIds }),
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
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
                                title: 'Rejeté !',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 2000,
                                toast: true,
                                position: 'top-end'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 3000,
                                toast: true,
                                position: 'top-end'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: 'Une erreur est survenue lors de la communication avec le serveur',
                            showConfirmButton: false,
                            timer: 3000,
                            toast: true,
                            position: 'top-end'
                        });
                    });
                }
            });
        });
    }
});
</script>