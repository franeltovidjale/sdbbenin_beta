<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ajouter un Article - Système de Gestion de Stock</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#F7F9FB',  // Couleur neutre
                            100: '#DCE3EF',
                            200: '#B9C7DF',
                            300: '#96ABCF',
                            400: '#738FBF',
                            500: '#4A90E2',  // Couleur secondaire
                            600: '#3A73B5',
                            700: '#2A5689',
                            800: '#1A365D',  // Couleur principale
                            900: '#0F1F36',
                        },
                        accent: {
                            50: '#FFF8F0',
                            100: '#FFECD8',
                            200: '#FFD9B1',
                            300: '#FFC78A',
                            400: '#FFB362',
                            500: '#FF9F43',  // Couleur d'accent
                            600: '#FF8A22',
                            700: '#FF7600',
                            800: '#D96200',
                            900: '#B34F00',
                        },
                        success: {
                            50: '#F0FDF4',
                            100: '#DCFCE7',
                            200: '#BBF7D0',
                            300: '#86EFAC',
                            400: '#4ADE80',
                            500: '#22C55E',
                            600: '#16A34A',
                            700: '#15803D',
                            800: '#166534',
                            900: '#14532D',
                        },
                        text: {
                            primary: '#333333',  // Texte principal
                            secondary: '#6B7280', // Texte secondaire
                            light: '#9CA3AF',     // Texte clair
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        'card': '0 4px 20px rgba(26, 54, 93, 0.08)',
                        'input': '0 2px 5px rgba(26, 54, 93, 0.05)',
                        'button': '0 4px 10px rgba(74, 144, 226, 0.3)',
                        'success': '0 4px 10px rgba(34, 197, 94, 0.3)',
                    },
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        .animate-gradient {
            background-size: 400%;
            -webkit-animation: gradient 8s ease infinite;
            animation: gradient 8s ease infinite;
        }
        
        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }
        
        .card-hover-effect {
            transition: all 0.3s ease;
        }
        
        .card-hover-effect:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(26, 54, 93, 0.12);
        }
        
        .form-input-focus:focus {
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.25);
        }
        
        /* Animation pour les boutons */
        .btn-hover-effect {
            transition: all 0.2s ease;
        }
        
        .btn-hover-effect:hover {
            transform: translateY(-2px);
        }
        
        /* Styles pour les images */
        .image-preview-container {
            margin-top: 1rem;
            min-height: 100px;
        }
        
        .image-preview-wrapper {
            height: 150px;
            overflow: hidden;
            border-radius: 0.75rem;
            border: 1px solid #DCE3EF;
            background-color: #F7F9FB;
            transition: all 0.3s ease;
        }
        
        .image-preview-wrapper:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(26, 54, 93, 0.1);
        }
        
        .image-preview-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .image-remove-btn {
            top: 0.5rem;
            right: 0.5rem;
            opacity: 0.9;
            border-radius: 50%;
            width: 1.75rem;
            height: 1.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            z-index: 10;
        }
        
        .image-remove-btn:hover {
            opacity: 1;
            transform: scale(1.1);
            background-color: #FF9F43;
            border-color: #FF9F43;
        }
        
        /* Animation pour les images */
        .slide-in {
            animation: slideIn 0.5s ease forwards;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Suppression des flèches pour les inputs de type number */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
        
        /* Styles pour les champs invalides */
        .is-invalid {
            border-color: #FF9F43 !important;
        }
        
        .error-message {
            display: none;
            color: #FF9F43;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        @media (max-width: 768px) {
            .image-preview-wrapper {
                height: 120px;
            }
        }
    </style>
</head>
<body class="bg-primary-50 font-sans text-text-primary">
    <div class="wrapper">
        @include('apk.components.topbar')
        @include('apk.components.navbar')
        
        <div class="content-wrapper bg-primary-50 p-4">
            <div class="content-header mb-6">
                <div class="container-fluid">
                    <div class="flex flex-wrap justify-between items-center">
                        <div class="mb-4 md:mb-0">
                            <h1 class="text-2xl font-bold text-primary-800 flex items-center">
                                <i class="fas fa-box-open mr-3 text-primary-500"></i>
                                Ajouter un article
                            </h1>
                            <p class="text-text-secondary mt-1">Créez un nouvel article dans votre inventaire</p>
                        </div>
                        <div>
                            <nav class="flex" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                    <li class="inline-flex items-center">
                                        <a href="#" class="inline-flex items-center text-sm font-medium text-primary-500 hover:text-primary-700">
                                            <i class="fas fa-home mr-2"></i>
                                            Accueil
                                        </a>
                                    </li>
                                    <li>
                                        <div class="flex items-center">
                                            <i class="fas fa-chevron-right text-text-light text-sm mx-2"></i>
                                            <a href="{{ route('adminlistarticles') }}" class="text-sm font-medium text-primary-500 hover:text-primary-700">
                                                Liste des Articles
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="flex items-center">
                                            <i class="fas fa-chevron-right text-text-light text-sm mx-2"></i>
                                            <span class="text-sm font-medium text-text-secondary">Ajouter un article</span>
                                        </div>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        
            <section class="content">
                <form id="productForm" method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Informations générales -->
                        <div class="lg:col-span-2">
                            <div class="bg-white rounded-xl shadow-card overflow-hidden card-hover-effect">
                                <div class="h-2 bg-gradient-to-r from-primary-500 to-primary-800 animate-gradient"></div>
                                <div class="p-6">
                                    <h2 class="text-lg font-semibold text-primary-800 mb-6 flex items-center">
                                        <i class="fas fa-info-circle mr-3 text-primary-500"></i>
                                        Informations Générales
                                    </h2>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Nom de l'article -->
                                        <div>
                                            <label for="inputName" class="block text-sm font-medium text-text-primary mb-2">
                                                Nom de l'article <span class="text-accent-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-box text-text-light"></i>
                                                </div>
                                                <input 
                                                    name="name" 
                                                    type="text" 
                                                    id="inputName" 
                                                    placeholder="Nom de l'article" 
                                                    class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                                >
                                            </div>
                                            <span class="text-accent-600 text-sm mt-1 hidden error-message" id="name-error"></span>
                                        </div>
                                        
                                        <!-- Catégorie -->
                                        <div>
                                            <label for="productAttributes" class="block text-sm font-medium text-text-primary mb-2">
                                                Catégorie <span class="text-accent-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-tags text-text-light"></i>
                                                </div>
                                                <select 
                                                    name="category_id" 
                                                    id="productAttributes" 
                                                    class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition appearance-none bg-right bg-no-repeat"
                                                    style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%236B7280\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3e%3cpolyline points=\'6 9 12 15 18 9\'%3e%3c/polyline%3e%3c/svg%3e'); background-position: right 0.75rem center; background-size: 1em;"
                                                >
                                                    <option value="" disabled selected>Sélectionner une catégorie</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="text-accent-600 text-sm mt-1 hidden error-message" id="category_id-error"></span>
                                        </div>
                                        
                                        <!-- Prix Achat -->
                                        <div>
                                            <label for="buy_price" class="block text-sm font-medium text-text-primary mb-2">
                                                Prix d'achat <span class="text-accent-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-tag text-text-light"></i>
                                                </div>
                                                <input 
                                                    name="buy_price" 
                                                    type="number" 
                                                    id="buy_price" 
                                                    placeholder="0.00" 
                                                    class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                                >
                                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <span class="text-text-light">FCFA</span>
                                                </div>
                                            </div>
                                            <span class="text-accent-600 text-sm mt-1 hidden error-message" id="buy_price-error"></span>
                                        </div>
                                        
                                        <!-- Prix de vente -->
                                        <div>
                                            <label for="inputNormalPrice" class="block text-sm font-medium text-text-primary mb-2">
                                                Prix de vente <span class="text-accent-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-money-bill text-text-light"></i>
                                                </div>
                                                <input 
                                                    name="normal_price" 
                                                    type="number" 
                                                    id="inputNormalPrice" 
                                                    placeholder="0.00" 
                                                    class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                                >
                                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <span class="text-text-light">FCFA</span>
                                                </div>
                                            </div>
                                            <span class="text-accent-600 text-sm mt-1 hidden error-message" id="normal_price-error"></span>
                                        </div>
                                        
                                        <!-- Numéro de Série -->
                                        <div>
                                            <label for="inputNumberSerie" class="block text-sm font-medium text-text-primary mb-2">
                                                Numéro de Série
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-barcode text-text-light"></i>
                                                </div>
                                                <input 
                                                    name="number_serie" 
                                                    type="text" 
                                                    id="inputNumberSerie" 
                                                    placeholder="S/N" 
                                                    class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                                >
                                            </div>
                                            <span class="text-accent-600 text-sm mt-1 hidden error-message" id="number_serie-error"></span>
                                        </div>
                                        
                                        <!-- Couleur -->
                                        <div>
                                            <label for="color" class="block text-sm font-medium text-text-primary mb-2">
                                                Couleur
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-palette text-text-light"></i>
                                                </div>
                                                <input 
                                                    name="color" 
                                                    type="text" 
                                                    id="color" 
                                                    placeholder="Noir, Blanc, etc." 
                                                    class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                                >
                                            </div>
                                            <span class="text-accent-600 text-sm mt-1 hidden error-message" id="color-error"></span>
                                        </div><!-- Mémoire Interne -->
                                        <div>
                                            <label for="memory" class="block text-sm font-medium text-text-primary mb-2">
                                                Mémoire Interne
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-memory text-text-light"></i>
                                                </div>
                                                <input 
                                                    name="memory" 
                                                    type="text" 
                                                    id="memory" 
                                                    placeholder="32GB, 64GB, etc." 
                                                    class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                                >
                                            </div>
                                            <span class="text-accent-600 text-sm mt-1 hidden error-message" id="memory-error"></span>
                                        </div>
                                        
                                        <!-- Batterie -->
                                        <div>
                                            <label for="batterie" class="block text-sm font-medium text-text-primary mb-2">
                                                Batterie (%)
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-battery-half text-text-light"></i>
                                                </div>
                                                <input 
                                                    name="batterie" 
                                                    type="number" 
                                                    id="batterie" 
                                                    placeholder="0-100" 
                                                    min="0" 
                                                    max="100" 
                                                    class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                                >
                                            </div>
                                            <span class="text-accent-600 text-sm mt-1 hidden error-message" id="batterie-error"></span>
                                        </div>
                                        
                                        <!-- Quantité -->
                                        <div>
                                            <label for="quantite" class="block text-sm font-medium text-text-primary mb-2">
                                                Quantité <span class="text-accent-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-cubes text-text-light"></i>
                                                </div>
                                                <input 
                                                    name="quantite" 
                                                    type="number" 
                                                    id="quantite" 
                                                    placeholder="0" 
                                                    min="0" 
                                                    class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                                >
                                            </div>
                                            <span class="text-accent-600 text-sm mt-1 hidden error-message" id="quantite-error"></span>
                                        </div>
                                        
                                        <!-- Dealeur -->
                                        <div>
                                            <label for="dealeur" class="block text-sm font-medium text-text-primary mb-2">
                                                Fournisseur
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-user-tie text-text-light"></i>
                                                </div>
                                                <input 
                                                    name="dealeur" 
                                                    type="text" 
                                                    id="dealeur" 
                                                    placeholder="Nom du fournisseur" 
                                                    class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                                >
                                            </div>
                                            <span class="text-accent-600 text-sm mt-1 hidden error-message" id="dealeur-error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informations complémentaires et images -->
                        <div class="lg:col-span-1 space-y-6">
                            <!-- Informations Complémentaires -->
                            <div class="bg-white rounded-xl shadow-card overflow-hidden card-hover-effect">
                                <div class="h-2 bg-gradient-to-r from-primary-500 to-primary-800 animate-gradient"></div>
                                <div class="p-6">
                                    <h2 class="text-lg font-semibold text-primary-800 mb-6 flex items-center">
                                        <i class="fas fa-clipboard-list mr-3 text-primary-500"></i>
                                        Informations Complémentaires
                                    </h2>
                                    
                                    <!-- Panne -->
                                    <div class="mb-4">
                                        <label for="panne" class="block text-sm font-medium text-text-primary mb-2">
                                            Panne
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-tools text-text-light"></i>
                                            </div>
                                            <input 
                                                name="panne" 
                                                type="text" 
                                                id="panne" 
                                                placeholder="Description de la panne" 
                                                class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                            >
                                        </div>
                                        <span class="text-accent-600 text-sm mt-1 hidden error-message" id="panne-error"></span>
                                    </div>
                                    
                                    <!-- Technicien -->
                                    <div class="mb-4">
                                        <label for="technicien" class="block text-sm font-medium text-text-primary mb-2">
                                            Technicien
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-user-cog text-text-light"></i>
                                            </div>
                                            <input 
                                                name="technicien" 
                                                type="text" 
                                                id="technicien" 
                                                placeholder="Nom du technicien" 
                                                class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                            >
                                        </div>
                                        <span class="text-accent-600 text-sm mt-1 hidden error-message" id="technicien-error"></span>
                                    </div>
                                    
                                    <!-- Note -->
                                    <div>
                                        <label for="note" class="block text-sm font-medium text-text-primary mb-2">
                                            Note
                                        </label>
                                        <div class="relative">
                                            <div class="absolute top-3 left-3 pointer-events-none">
                                                <i class="fas fa-sticky-note text-text-light"></i>
                                            </div>
                                            <textarea 
                                                name="note" 
                                                id="note" 
                                                rows="3" 
                                                placeholder="Informations supplémentaires..." 
                                                class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition resize-none"
                                            ></textarea>
                                        </div>
                                        <span class="text-accent-600 text-sm mt-1 hidden error-message" id="note-error"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Images du produit -->
                            <div class="bg-white rounded-xl shadow-card overflow-hidden card-hover-effect">
                                <div class="h-2 bg-gradient-to-r from-primary-500 to-primary-800 animate-gradient"></div>
                                <div class="p-6">
                                    <div class="flex justify-between items-center mb-4">
                                        <h2 class="text-lg font-semibold text-primary-800 flex items-center">
                                            <i class="fas fa-images mr-3 text-primary-500"></i>
                                            Images de l'article
                                        </h2>
                                        <span id="imageCounter" class="bg-primary-100 text-primary-700 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                            0/5 images
                                        </span>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="customFiles" class="block text-sm font-medium text-text-primary mb-2">
                                            Choisissez jusqu'à 5 images
                                        </label>
                                        <div class="relative">
                                            <div class="flex items-center w-full">
                                                <label for="customFiles" class="flex items-center justify-center w-full h-12 px-4 py-2 border border-primary-100 border-dashed rounded-lg cursor-pointer bg-primary-50 hover:bg-primary-100 transition">
                                                    <i class="fas fa-cloud-upload-alt text-primary-500 mr-2"></i>
                                                    <span class="text-text-secondary text-sm file-name-display">Sélectionner des images...</span>
                                                    <input 
                                                        type="file" 
                                                        class="hidden" 
                                                        id="customFiles" 
                                                        name="images[]" 
                                                        multiple 
                                                        accept="image/*" 
                                                        onchange="previewImages(event)"
                                                    >
                                                </label>
                                            </div>
                                        </div>
                                        <p class="mt-2 text-xs text-text-secondary">
                                            <i class="fas fa-info-circle mr-1"></i> Formats acceptés: JPG, PNG, GIF (max 2MB)
                                        </p>
                                        <span class="text-accent-600 text-sm mt-1 hidden error-message" id="images-error"></span>
                                    </div>
                                    
                                    <div class="image-preview-container">
                                        <div class="grid grid-cols-2 gap-3" id="imagePreviewContainer">
                                            <!-- Les images sélectionnées seront affichées ici -->
                                        </div>
                                    </div>
                                    
                                    <!-- Champs cachés pour la gestion des images -->
                                    <input type="hidden" id="fileCount" name="fileCount" value="0">
                                    <input type="hidden" id="remove_images" name="remove_images" value="[]">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 mt-6">
                        <a 
                            href="{{ route('adminlistarticles') }}" 
                            class="btn-hover-effect inline-flex items-center justify-center py-3 px-6 border border-primary-200 rounded-lg text-primary-600 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 transition"
                        >
                            <i class="fas fa-arrow-left mr-2"></i> Annuler
                        </a>
                        <button 
                            type="submit" 
                            class="btn-hover-effect inline-flex items-center justify-center py-3 px-6 border border-transparent rounded-lg shadow-success text-white bg-success-600 hover:bg-success-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-success-400 transition"
                        >
                            <i class="fas fa-save mr-2"></i> Créer l'article
                        </button>
                    </div>
                </form>
            </section>
        </div>
        
        @include('apk.components.footer')
    </div>

    <script>
        let selectedFiles = []; // Variable globale pour conserver les fichiers sélectionnés

        function previewImages(event) {
            const files = event.target.files;
            const previewContainer = document.getElementById('imagePreviewContainer');
            const existingImagesCount = previewContainer.querySelectorAll('.image-preview-item').length;
            const maxImages = 5;

            // Vérifier si des fichiers ont été sélectionnés
            if (!files || files.length === 0) {
                return; // Sortir de la fonction si aucun fichier n'est sélectionné
            }

            if (files.length + existingImagesCount > maxImages) {
                Swal.fire({
                    title: 'Limite atteinte!',
                    text: 'Vous pouvez sélectionner jusqu\'à 5 images uniquement.',
                    icon: 'warning',
                    confirmButtonColor: '#4A90E2',
                    customClass: {
                        popup: 'rounded-xl',
                        title: 'text-primary-800',
                        content: 'text-text-secondary'
                    }
                });
                event.target.value = '';
                return;
            }

            // Mise à jour du label de la sélection de fichiers
            const fileNameDisplay = document.querySelector('.file-name-display');
            if (fileNameDisplay) {
                fileNameDisplay.textContent = files.length > 1 ? `${files.length} fichiers sélectionnés` : files[0].name;
            }
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (!file || !file.type || !file.type.startsWith('image/')) continue;

                const reader = new FileReader();
                reader.onload = function(e) {
                    const colDiv = document.createElement('div');
                    colDiv.classList.add('col-span-1', 'image-preview-item', 'slide-in');

                    const imgContainer = document.createElement('div');
                    imgContainer.classList.add('image-preview-wrapper', 'relative');
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('rounded-lg', 'shadow-sm');
                    img.alt = `Image ${existingImagesCount + i + 1}`;
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.classList.add('absolute', 'image-remove-btn', 'bg-white', 'text-accent-500', 'border', 'border-accent-500', 'shadow-sm');
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                    removeBtn.onclick = function() {
                        colDiv.remove();
                        updateImageCounter();
                    };
                    
                    imgContainer.appendChild(img);
                    imgContainer.appendChild(removeBtn);
                    colDiv.appendChild(imgContainer);
                    previewContainer.appendChild(colDiv);
                    
                    updateImageCounter();
                };
                reader.readAsDataURL(file);
            }
            
            // Réinitialiser l'input file pour permettre la sélection répétée du même fichier
            // Mais d'abord, nous devons stocker les fichiers quelque part
            storeFilesInFormData(files);
            
            // Maintenant, on peut réinitialiser la valeur de l'input
            event.target.value = '';
        }

        // Fonction pour stocker les fichiers dans FormData
        function storeFilesInFormData(newFiles) {
            // Ajouter les nouveaux fichiers à notre tableau
            for (let i = 0; i < newFiles.length; i++) {
                selectedFiles.push(newFiles[i]);
            }
            
            // Mettre à jour un champ caché pour indiquer combien de fichiers nous avons
            const fileCountInput = document.getElementById('fileCount');
            if (fileCountInput) {
                fileCountInput.value = selectedFiles.length;
            }
        }

        // Mettre à jour le compteur d'images et vérifier les limites
        function updateImageCounter() {
            const count = document.querySelectorAll('.image-preview-item').length;
            const counterElement = document.getElementById('imageCounter');
            if (counterElement) {
                counterElement.textContent = `${count}/5 images`;
            }
            
            // Désactiver l'input si la limite est atteinte
            const fileInput = document.getElementById('customFiles');
            if (fileInput) {
                fileInput.disabled = count >= 5;
                
                // Mettre à jour le style du label
                const fileLabel = document.querySelector('.file-name-display');
                if (fileLabel) {
                    if (count >= 5) {
                        fileLabel.textContent = 'Limite de 5 images atteinte';
                        fileLabel.parentElement.classList.add('opacity-50', 'cursor-not-allowed');
                        fileLabel.parentElement.classList.remove('hover:bg-primary-100');
                    } else {
                        fileLabel.textContent = 'Sélectionner des images...';
                        fileLabel.parentElement.classList.remove('opacity-50', 'cursor-not-allowed');
                        fileLabel.parentElement.classList.add('hover:bg-primary-100');
                    }
                }
            }
        }

        // Fonction pour préparer le formulaire avant l'envoi
        function prepareFormForSubmission() {
            // Créer un nouvel objet FormData à partir du formulaire
            const form = document.getElementById('productForm');
            const formData = new FormData(form);
            
            // Ajouter tous les fichiers stockés
            for (let i = 0; i < selectedFiles.length; i++) {
                formData.append('images[]', selectedFiles[i]);
            }
            
            return formData;
        }

        // Fonction pour gérer la suppression d'une image existante
        function handleImageDeletion(imageElement, imageIndex) {
            const removeImagesInput = document.getElementById('remove_images');
            let removeIndexes = [];
            
            if (removeImagesInput.value) {
                removeIndexes = JSON.parse(removeImagesInput.value);
            }
            
            removeIndexes.push(imageIndex);
            removeImagesInput.value = JSON.stringify(removeIndexes);
            
            // Masquer visuellement l'image
            imageElement.closest('.image-preview-item').remove();
            
            // Mettre à jour le compteur
            updateImageCounter();
        }

        function displayImageError(error) {
            const errorContainer = document.getElementById('images-error');
            if (errorContainer) {
                errorContainer.textContent = error;
                errorContainer.style.display = 'block';
            }
            
            // Ajoutez une classe d'erreur au conteneur de fichier
            const fileInput = document.getElementById('customFiles');
            if (fileInput) {
                fileInput.parentElement.classList.add('border-accent-500');
            }
        }

        function clearErrors() {
            // Masquer tous les messages d'erreur
            const errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach(el => {
                el.textContent = '';
                el.style.display = 'none';
            });
            
            // Retirer la classe is-invalid de tous les champs
            const formInputs = document.querySelectorAll('.form-input-focus');
            formInputs.forEach(input => {
                input.classList.remove('is-invalid');
            });
            
            // Réinitialiser le style du sélecteur de fichier
            const fileInput = document.getElementById('customFiles');
            if (fileInput && fileInput.parentElement) {
                fileInput.parentElement.classList.remove('border-accent-500');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialiser le compteur d'images
            updateImageCounter();
            
            // Configurer l'écouteur d'événements sur le formulaire
            const productForm = document.getElementById('productForm');
            if (productForm) {
                productForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    clearErrors();
                    
                    // Utiliser notre fonction personnalisée pour préparer les données
                    const formData = prepareFormForSubmission();
                    
                    try {
                        const response = await fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();
                        
                        if (!response.ok) {
                            if (data.errors) {
                                Object.keys(data.errors).forEach(field => {
                                    // Gestion spéciale pour les erreurs d'images
                                    if (field === 'images' || field.startsWith('images.')) {
                                        displayImageError(data.errors[field][0]);
                                    } else {
                                        const errorElement = document.getElementById(`${field}-error`);
                                        const inputElement = document.querySelector(`[name="${field}"]`);
                                        
                                        if (errorElement && inputElement) {
                                            errorElement.textContent = data.errors[field][0];
                                            errorElement.style.display = 'block';
                                            inputElement.classList.add('is-invalid');
                                        }
                                    }
                                });
                                
                                Swal.fire({
                                    title: 'Erreur de validation',
                                    text: 'Veuillez corriger les erreurs dans le formulaire',
                                    icon: 'error',
                                    confirmButtonColor: '#4A90E2',
                                    customClass: {
                                        popup: 'rounded-xl',
                                        title: 'text-primary-800',
                                        content: 'text-text-secondary'
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: 'Erreur',
                                    text: data.message || 'Une erreur est survenue',
                                    icon: 'error',
                                    confirmButtonColor: '#4A90E2',
                                    customClass: {
                                        popup: 'rounded-xl',
                                        title: 'text-primary-800',
                                        content: 'text-text-secondary'
                                    }
                                });
                            }
                            return;
                        }

                        Swal.fire({
                            title: 'Succès!',
                            text: data.message || 'Article créé avec succès',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'rounded-xl',
                                title: 'text-primary-800',
                                content: 'text-text-secondary'
                            }
                        }).then(() => {
                            // Redirection
                            const redirectUrl = document.querySelector('input[name="redirect_url"]');
                            if (redirectUrl && redirectUrl.value) {
                                window.location.href = redirectUrl.value;
                            } else {
                                const backLink = document.querySelector('a[href*="adminlistarticles"]');
                                if (backLink) {
                                    window.location.href = backLink.getAttribute('href');
                                } else {
                                    // Fallback si aucun lien n'est trouvé
                                    window.location.href = '/glsam/admin/liste-articles';
                                }
                            }
                        });

                    } catch (error) {
                        console.error('Erreur:', error);
                        Swal.fire({
                            title: 'Erreur!',
                            text: 'Une erreur est survenue lors de la création de l\'article',
                            icon: 'error',
                            confirmButtonColor: '#4A90E2',
                            customClass: {
                                popup: 'rounded-xl',
                                title: 'text-primary-800',
                                content: 'text-text-secondary'
                            }
                        });
                    }
                });
            }
            
            // Initialiser les gestionnaires d'événements pour les images existantes
            document.querySelectorAll('.existing-image .image-remove-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const imageElement = this.closest('.image-preview-item');
                    const imageIndex = imageElement.dataset.index;
                    if (imageIndex !== undefined) {
                        handleImageDeletion(imageElement, parseInt(imageIndex));
                    }
                });
            });
        });
    </script>
</body>
</html>