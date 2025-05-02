

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Modification d'une Vente - Système de Gestion de Stock</title>
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
                                <i class="fas fa-edit mr-3 text-primary-500"></i>
                                Modifier une vente
                            </h1>
                            <p class="text-text-secondary mt-1">Mettez à jour les informations de votre transaction</p>
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
                                            <a href="{{ route('sales.index') }}" class="text-sm font-medium text-primary-500 hover:text-primary-700">
                                                Rapport des Ventes
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="flex items-center">
                                            <i class="fas fa-chevron-right text-text-light text-sm mx-2"></i>
                                            <span class="text-sm font-medium text-text-secondary">Modifier la vente</span>
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
                                <div class="flex items-center mb-6">
                                    <div class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center mr-4">
                                        <i class="fas fa-shopping-cart text-xl text-primary-700"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-base font-semibold text-primary-800">
                                            Vente n° {{ $sale->id }}
                                        </h2>
                                        <p class="text-text-secondary text-sm">
                                            Créée le {{ $sale->created_at->format('d/m/Y à H:i') }}
                                        </p>
                                    </div>
                                </div>
                                
                                <form id="salesform" action="{{ route('sales.update', $sale->id) }}" method="POST" class="space-y-6">
                                    @csrf
                                    @method('PUT')

                                    <!-- Article -->
                                    <div>
                                        <label for="article_id" class="block text-sm font-medium text-text-primary mb-2">Article</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                {{-- <i class="fas fa-box text-text-light"></i> --}}
                                            </div>
                                            <select 
                                                id="article_id" 
                                                name="article_id" 
                                                required
                                                class="form-input-focus custom-select block w-full pl-10 py-3 pr-10 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                            >
                                                <option value="" >Sélectionner un article</option>
                                                {{-- @foreach($articles as $article)
                                                <option value="{{ $article->id }}" {{ $article->id == $sale->article_id ? 'selected' : '' }}>
                                                    {{ $article->name }}
                                                </option>
                                                @endforeach --}}
                                                @foreach($articles as $article)
                                                    <option value="{{ $article->id }}" data-stock="{{ $article->quantite }}" {{ $article->id == $sale->article_id ? 'selected' : '' }}>
                                                        {{ $article->name }} (Stock: {{ $article->quantite }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <!-- Juste après le select d'article -->
                                                <div class="mt-3" id="stockActuelContainer">
                                                    <div id="stockActuel" class="text-sm bg-primary-100 text-primary-800 py-2 px-4 rounded-lg inline-flex items-center">
                                                        <i class="fas fa-cubes mr-2"></i>
                                                        <span><span id="articleName" class="font-medium">{{ $sale->article->name }}</span> - Stock actuel : <span class="font-semibold">{{ $sale->article->quantite + $sale->articleQte }}</span> unités</span>
                                                    </div>
                                                </div>
                                        </div>
                                        <div id="article_id-error" class="text-accent-600 text-sm mt-1 hidden error-message"></div>
                                    </div>

                                    <!-- Quantité -->
                                    <div>
                                        <label for="articleQte" class="block text-sm font-medium text-text-primary mb-2">Quantité</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-hashtag text-text-light"></i>
                                            </div>
                                            <input 
                                                type="number" 
                                                id="articleQte" 
                                                name="articleQte" 
                                                value="{{ $sale->articleQte ?? 0 }}" 
                                                required
                                                min="1"
                                                class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                            >
                                        </div>
                                        <div id="articleQte-error" class="text-accent-600 text-sm mt-1 hidden error-message"></div>
                                    </div>

                                    <!-- Commentaire -->
                                    <div>
                                        <label for="comment" class="block text-sm font-medium text-text-primary mb-2">Commentaire</label>
                                        <div class="relative">
                                            <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                                <i class="fas fa-comment text-text-light"></i>
                                            </div>
                                            <textarea 
                                                id="comment" 
                                                name="comment" 
                                                rows="4"
                                                class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition resize-none"
                                            >{{ $sale->comment }}</textarea>
                                        </div>
                                        <div id="comment-error" class="text-accent-600 text-sm mt-1 hidden error-message"></div>
                                    </div>

                                    <!-- Informations de vente -->
                                    <div class="bg-primary-50 rounded-lg p-4 border border-primary-100">
                                        <h3 class="text-sm font-medium text-primary-800 mb-3">Informations de vente</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-xs text-text-secondary">Prix unitaire</p>
                                                <p class="text-sm font-medium">{{ number_format($sale->article->normal_price, 0, ',', ' ') }} F</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-text-secondary">Total</p>
                                                <p id="total-price" class="text-sm font-medium">
                                                    {{ number_format($sale->article->normal_price * $sale->articleQte, 0, ',', ' ') }} F
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-text-secondary">Bénéfice</p>
                                                <p id="benefice-price" class="text-sm font-medium text-success-600">
                                                    {{ number_format(($sale->article->normal_price - $sale->article->buy_price) * $sale->articleQte, 0, ',', ' ') }} F
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-text-secondary">Date de vente</p>
                                                <p class="text-sm font-medium">{{ $sale->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                            <!-- Ajouter cette div dans la grille des informations de vente -->
                                            <div>
                                                <p class="text-xs text-text-secondary">Stock disponible</p>
                                                <p class="text-sm font-medium" id="stockInfo">{{ $sale->article->quantite + $sale->articleQte }} unités</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Boutons d'action -->
                                    <div class="flex justify-end space-x-4 pt-4">
                                        <a href="{{ route('sales.index') }}" class="btn-hover-effect inline-flex items-center justify-center py-3 px-6 border border-primary-200 rounded-lg text-primary-600 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 transition">
                                            <i class="fas fa-arrow-left mr-2"></i> Annuler
                                        </a>
                                        <button type="submit" class="btn-hover-effect inline-flex items-center justify-center py-3 px-6 border border-transparent rounded-lg shadow-button text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 transition">
                                            <i class="fas fa-save mr-2"></i> Enregistrer
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Conseils et aide -->
                        <div class="bg-white rounded-xl shadow-card overflow-hidden mt-6 p-5">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-primary-100 rounded-full p-3">
                                    <i class="fas fa-info-circle text-primary-700 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-base font-medium text-primary-800 mb-1">Conseil de modification</h4>
                                    <p class="text-sm text-text-secondary">La modification d'une vente mettra à jour le stock et les statistiques automatiquement. Les calculs de bénéfice seront également ajustés en fonction des nouveaux paramètres.</p>
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
        document.getElementById('salesform').addEventListener('submit', async function(e) {
            e.preventDefault();
            clearErrors();
            
            const formData = new FormData(this);
            
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
                            const errorElement = document.getElementById(`${field}-error`);
                            const inputElement = document.querySelector(`[name="${field}"]`);
                            
                            if (errorElement && inputElement) {
                                errorElement.textContent = data.errors[field][0];
                                errorElement.classList.remove('hidden');
                                inputElement.classList.add('border-accent-500');
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
                    text: 'Vente modifiée avec succès',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'rounded-xl',
                        title: 'text-primary-800',
                        content: 'text-text-secondary'
                    }
                }).then(() => {
                    window.location.href = '{{ route("sales.index") }}';
                });

            } catch (error) {
                console.error('Erreur:', error);
                Swal.fire({
                    title: 'Erreur!',
                    text: 'Une erreur est survenue lors de la modification de la vente',
                    icon: 'error',
                    confirmButtonColor: '#4A90E2',
                    customClass: {
                        popup: 'rounded-xl',
                        title: 'text-primary-800'
                    }
                });
            }
        });

        // Ajouter ceci au script existant
document.addEventListener('DOMContentLoaded', function() {
    const articleSelect = document.getElementById('article_id');
    const quantiteInput = document.getElementById('articleQte');
    const stockActuel = document.getElementById('stockActuel');
    const currentQte = {{ $sale->articleQte }};
    
    // Fonction pour mettre à jour l'affichage du stock
    function updateStockDisplay() {
        const selectedOption = articleSelect.selectedOptions[0];
        if (selectedOption) {
            const stockValue = parseInt(selectedOption.dataset.stock || 0);
            const articleName = selectedOption.textContent.split('(Stock')[0].trim();
            
            // Vérifier si c'est l'article original (ajouter la quantité originale au stock disponible)
            const isOriginalArticle = selectedOption.value == {{ $sale->article_id }};
            const adjustedStock = isOriginalArticle ? stockValue + currentQte : stockValue;
            
            document.getElementById('articleName').textContent = articleName;
            stockActuel.querySelector('span.font-semibold').textContent = adjustedStock;
            stockActuel.classList.remove('hidden');
        }
    }
    
    // Initialiser l'affichage du stock
    updateStockDisplay();
    
    // Mettre à jour lorsque l'article change
    articleSelect.addEventListener('change', updateStockDisplay);
});

// Ajouter à votre script existant
document.addEventListener('DOMContentLoaded', function() {
    const articleSelect = document.getElementById('article_id');
    const quantiteInput = document.getElementById('articleQte');
    
    // Fonction pour mettre à jour les informations de prix
    function updatePriceInfo() {
        const selectedOption = articleSelect.selectedOptions[0];
        const qte = parseInt(quantiteInput.value) || 0;
        
        if (selectedOption && qte > 0) {
            // Récupérer les prix depuis les attributs data
            const articleId = selectedOption.value;
            // Obtenir les données de l'article sélectionné
            fetch(`/articles/${articleId}`)
                .then(response => response.json())
                .then(article => {
                    const normalPrice = article.normal_price;
                    const buyPrice = article.buy_price;
                    const total = normalPrice * qte;
                    const benefice = (normalPrice - buyPrice) * qte;
                    
                    // Mettre à jour l'affichage
                    document.querySelector('p:contains("Total")').nextElementSibling.textContent = 
                        formatNumber(total) + ' F';
                    document.querySelector('p:contains("Bénéfice")').nextElementSibling.textContent = 
                        formatNumber(benefice) + ' F';
                })
                .catch(error => console.error('Erreur:', error));
        }
    }
    
    // Formater les nombres avec séparateurs de milliers
    function formatNumber(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }
    
    quantiteInput.addEventListener('input', updatePriceInfo);
    articleSelect.addEventListener('change', updatePriceInfo);
});

        function clearErrors() {
            const errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach(el => {
                el.textContent = '';
                el.classList.add('hidden');
            });
            
            const formInputs = document.querySelectorAll('.form-input-focus');
            formInputs.forEach(input => {
                input.classList.remove('border-accent-500');
            });
        }
    </script>
</body>
</html>