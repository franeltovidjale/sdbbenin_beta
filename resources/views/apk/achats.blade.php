



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestion du Stock - Système de Gestion de Stock</title>
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
                        danger: {
                            50: '#FEF2F2',
                            100: '#FEE2E2',
                            200: '#FECACA',
                            300: '#FCA5A5',
                            400: '#F87171',
                            500: '#EF4444',
                            600: '#DC2626',
                            700: '#B91C1C',
                            800: '#991B1B',
                            900: '#7F1D1D',
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
                        'danger': '0 4px 10px rgba(220, 38, 38, 0.3)',
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
        
        /* Animation pour les boutons d'opération */
        .operation-btn {
            transition: all 0.3s ease;
        }
        
        .operation-btn:hover {
            transform: translateY(-2px);
        }
        
        .operation-btn.active {
            transform: scale(1.02);
        }
        
        /* Styles pour select personnalisé */
        .custom-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236B7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        
        /* Animation de pulsation pour l'indicateur de stock */
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(74, 144, 226, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(74, 144, 226, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(74, 144, 226, 0);
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
                            <h1 class="text-lg font-bold text-primary-800 flex items-center">
                                <i class="fas  fa-boxes mr-3 text-primary-500"></i>
                                Gestion du Stock
                            </h1>
                            <p class="text-text-secondary mt-1">Enregistrez les mouvements d'entrée et de sortie du stock</p>
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
                                            <span class="text-sm font-medium text-text-secondary">Gestion du Stock</span>
                                        </div>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="max-w-3xl mx-auto">
                        <div class="bg-white rounded-xl shadow-card overflow-hidden card-hover-effect">
                            <div class="h-2 bg-gradient-to-r from-primary-500 to-primary-800 animate-gradient"></div>
                            
                            <div class="p-6">
                                <h2 class="text-lg font-semibold text-primary-800 mb-6 flex items-center">
                                    <i class="fas fa-exchange-alt mr-3 text-primary-500"></i>
                                    Mouvement de Stock
                                </h2>
                                
                                <form id="stockForm" method="POST" class="space-y-6">
                                    @csrf
                                    
                                    <!-- Type d'opération -->
                                    <div>
                                        <label class="block text-sm font-medium text-text-primary mb-3">Type d'opération</label>
                                        <div class="grid grid-cols-2 gap-4">
                                            <button 
                                                type="button" 
                                                class="operation-btn flex items-center justify-center py-2 px-2 border-2 border-success-500 text-success-700 bg-success-50 rounded-lg hover:bg-success-100 focus:outline-none focus:ring-2 focus:ring-success-500 transition"
                                                data-type="entree"
                                            >
                                                <i class="fas fa-plus-circle text-lg mr-3"></i>
                                                <span class="font-medium">Achat</span>
                                            </button>
                                            
                                            <button 
                                                type="button" 
                                                class="operation-btn flex items-center justify-center py-2 px-2 border-2 border-danger-500 text-danger-700 bg-danger-50 rounded-lg hover:bg-danger-100 focus:outline-none focus:ring-2 focus:ring-danger-500 transition"
                                                data-type="sortie"
                                            >
                                                <i class="fas fa-minus-circle text-lg mr-3"></i>
                                                <span class="font-medium">Vente</span>
                                            </button>
                                        </div>
                                        <input type="hidden" name="operation_type" id="operationType">
                                    </div>
                                    
                                    <!-- Sélection de l'article -->
                                    <div>
                                        <label for="article" class="block text-sm font-medium text-text-primary mb-2">
                                            Article <span class="text-danger-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-box text-text-light"></i>
                                            </div>
                                            <select 
                                                id="article" 
                                                name="article_id" 
                                                required
                                                class="form-input-focus custom-select block w-full pl-10 py-3 pr-10 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                            >
                                                <option value="">Sélectionner un article</option>
                                                @foreach($articles as $article)
                                                    <option value="{{ $article->id }}" data-stock="{{ $article->quantite }}" >
                                                        {{ $article->name }} (Stock: {{  $article->quantite }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <!-- Indicateur de stock -->
                                        {{-- <div class="mt-3" id="stockActuelContainer">
                                            <div id="stockActuel" class="hidden text-sm bg-primary-100 text-primary-800 py-2 px-4 rounded-lg inline-flex items-center">
                                                <i class="fas fa-cubes mr-2"></i>
                                                <span>Stock actuel : <span class="font-semibold">0</span> unités</span>
                                            </div>
                                        </div> --}}
                                        <div class="mt-3" id="stockActuelContainer">
                                            <div id="stockActuel" class="hidden text-sm bg-primary-100 text-primary-800 py-2 px-4 rounded-lg inline-flex items-center">
                                                <i class="fas fa-cubes mr-2"></i>
                                                <span><span id="articleName" class="font-medium"></span> - Stock : <span class="font-semibold">0</span> unités</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Quantité -->
                                    <div>
                                        <label for="articleQte" class="block text-sm font-medium text-text-primary mb-2">
                                            Quantité <span class="text-danger-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-hashtag text-text-light"></i>
                                            </div>
                                            <input 
                                                type="number" 
                                                id="articleQte" 
                                                name="articleQte" 
                                                min="1"
                                                required
                                                class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                                placeholder="Saisissez la quantité"
                                            >
                                        </div>
                                        <div id="quantiteError" class="text-danger-600 text-sm mt-1 hidden"></div>
                                    </div>
                                    
                                    <!-- Commentaire -->
                                    <div>
                                        <label for="comment" class="block text-sm font-medium text-text-primary mb-2">
                                            Motif du mouvement
                                            <span class="text-text-light text-xs ml-1">(facultatif)</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                                <i class="fas fa-comment text-text-light"></i>
                                            </div>
                                            <textarea 
                                                id="comment" 
                                                name="comment" 
                                                rows="3"
                                                class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition resize-none"
                                                placeholder="Saisissez un motif (optionnel)"
                                            ></textarea>
                                        </div>
                                    </div>
                                    
                                    <!-- Bouton de validation -->
                                    <div class="pt-4">
                                        <button 
                                            type="submit" 
                                            id="submitButton"
                                            disabled
                                            class="w-full btn-hover-effect inline-flex items-center justify-center py-3 px-6 border border-transparent rounded-lg text-white bg-primary-400 focus:outline-none transition opacity-70 cursor-not-allowed"
                                        >
                                            <i class="fas fa-check-circle mr-2"></i>
                                            Valider l'opération
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Aide et conseils -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div class="bg-white rounded-xl shadow-card overflow-hidden p-5">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 bg-success-100 rounded-full p-3">
                                        <i class="fas fa-info-circle text-success-700 text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-base font-medium text-success-800 mb-1">Entrée de stock</h4>
                                        <p class="text-sm text-text-secondary">Utilisez cette option pour enregistrer les achats et les réceptions de marchandises qui augmentent votre stock disponible.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-xl shadow-card overflow-hidden p-5">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 bg-danger-100 rounded-full p-3">
                                        <i class="fas fa-exclamation-circle text-danger-700 text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-base font-medium text-danger-800 mb-1">Sortie de stock</h4>
                                        <p class="text-sm text-text-secondary">Utilisez cette option pour enregistrer les ventes et autres sorties de marchandises. Les quantités sont limitées au stock disponible.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        @include('apk.components.footer')
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('stockForm');
        const operationBtns = document.querySelectorAll('.operation-btn');
        const operationType = document.getElementById('operationType');
        const articleSelect = document.getElementById('article');
        const quantiteInput = document.getElementById('articleQte');
        const submitButton = document.getElementById('submitButton');
        const stockActuel = document.getElementById('stockActuel');
        const quantiteError = document.getElementById('quantiteError');
        
        let selectedOperation = null;

        // Gestion des boutons d'opération
        operationBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                operationBtns.forEach(b => {
                    b.classList.remove('active');
                    if (b.dataset.type === 'entree') {
                        b.classList.remove('bg-success-500', 'text-white', 'border-success-500');
                        b.classList.add('bg-success-50', 'text-success-700', 'border-success-500');
                    } else {
                        b.classList.remove('bg-danger-500', 'text-white', 'border-danger-500');
                        b.classList.add('bg-danger-50', 'text-danger-700', 'border-danger-500');
                    }
                });

                this.classList.add('active');
                if (this.dataset.type === 'entree') {
                    this.classList.remove('bg-success-50', 'text-success-700');
                    this.classList.add('bg-success-500', 'text-white');
                } else {
                    this.classList.remove('bg-danger-50', 'text-danger-700');
                    this.classList.add('bg-danger-500', 'text-white');
                }
                
                selectedOperation = this.dataset.type;
                operationType.value = selectedOperation;

                updateSubmitButtonStyle();
                validateForm();
            });
        });

        // Gestion de l'affichage du stock actuel
        // articleSelect.addEventListener('change', function() {
        //     const selectedOption = this.selectedOptions[0];
        //     const stockValue = selectedOption?.dataset.stock;
            
        //     if (stockValue) {
        //         stockActuel.querySelector('span').innerHTML = '<span class="font-semibold">' + stockValue + '</span> unités';
        //         stockActuel.classList.remove('hidden');
                
        //         // Animation de pulsation pour attirer l'attention
        //         stockActuel.classList.add('pulse');
        //         setTimeout(() => {
        //             stockActuel.classList.remove('pulse');
        //         }, 2000);
        //     } else {
        //         stockActuel.classList.add('hidden');
        //     }
        //     validateForm();
        // });
        // Gestion de l'affichage du stock actuel
articleSelect.addEventListener('change', function() {
    const selectedOption = this.selectedOptions[0];
    const stockValue = selectedOption?.dataset.stock;
    
    if (stockValue) {
        const articleName = selectedOption.textContent.split('(Stock')[0].trim();
        document.getElementById('articleName').textContent = articleName;
        stockActuel.querySelector('span.font-semibold').textContent = stockValue;
        stockActuel.classList.remove('hidden');
        
        // Animation de pulsation pour attirer l'attention
        stockActuel.classList.add('pulse');
        setTimeout(() => {
            stockActuel.classList.remove('pulse');
        }, 2000);
    } else {
        stockActuel.classList.add('hidden');
    }
    validateForm();
});

        // Validation
        quantiteInput.addEventListener('input', validateForm);

        function validateForm() {
            const article = articleSelect.selectedOptions[0];
            const quantite = parseInt(quantiteInput.value);
            const stockActuelValue = article ? parseInt(article.dataset.stock) : 0;
            let isValid = true;

            // Réinitialisation des erreurs
            quantiteInput.classList.remove('border-danger-500');
            quantiteError.classList.add('hidden');

            // Validation de la quantité pour les sorties
            if (selectedOperation === 'sortie' && quantite > stockActuelValue) {
                quantiteInput.classList.add('border-danger-500');
                quantiteError.textContent = `La quantité ne peut pas dépasser le stock actuel (${stockActuelValue})`;
                quantiteError.classList.remove('hidden');
                isValid = false;
            }

            // Validation globale
            const validForm = (
                selectedOperation &&
                articleSelect.value &&
                quantiteInput.value > 0 &&
                isValid
            );
            
            if (validForm) {
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-70', 'cursor-not-allowed', 'bg-primary-400');
                updateSubmitButtonStyle();
            } else {
                submitButton.disabled = true;
                submitButton.classList.add('opacity-70', 'cursor-not-allowed');
                submitButton.classList.remove('bg-success-600', 'bg-danger-600', 'shadow-success', 'shadow-danger');
                submitButton.classList.add('bg-primary-400');
            }
        }

        function updateSubmitButtonStyle() {
            if (selectedOperation && !submitButton.disabled) {
                if (selectedOperation === 'entree') {
                    submitButton.className = 'w-full btn-hover-effect inline-flex items-center justify-center py-3 px-6 border border-transparent rounded-lg shadow-success text-white bg-success-600 hover:bg-success-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-success-400 transition';
                    submitButton.innerHTML = '<i class="fas fa-plus-circle mr-2"></i> Valider l\'achat';
                } else {
                    submitButton.className = 'w-full btn-hover-effect inline-flex items-center justify-center py-3 px-6 border border-transparent rounded-lg shadow-danger text-white bg-danger-600 hover:bg-danger-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-danger-400 transition';
                    submitButton.innerHTML = '<i class="fas fa-minus-circle mr-2"></i> Valider la vente';
                }
            }
        }

        // Soumission du formulaire
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const formData = new FormData(this);
                const url = selectedOperation === 'entree' ? '/stock/input' : '/stock/output';
                
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'rounded-xl',
                            title: 'text-primary-800',
                            content: 'text-text-secondary'
                        }
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Une erreur est survenue');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: error.message,
                    confirmButtonColor: '#4A90E2',
                    customClass: {
                        popup: 'rounded-xl',
                        title: 'text-primary-800',
                        content: 'text-text-secondary'
                    }
                });
            }
        });
    });
    </script>
</body>
</html>