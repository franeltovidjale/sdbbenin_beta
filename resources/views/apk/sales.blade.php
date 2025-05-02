
 <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rapport des Ventes</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
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
        
        .btn-action {
            transition: all 0.2s ease;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
        }
        
        .form-input-focus:focus {
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.25);
        }
        
        /* Indicateur de défilement horizontal pour mobile */
        @media (max-width: 768px) {
            .table-scroll-indicator {
                position: absolute;
                right: 10px;
                top: 50%;
                transform: translateY(-50%);
                color: rgba(74, 144, 226, 0.6);
                animation: bounce 1.5s infinite;
                font-size: 1.5rem;
                pointer-events: none;
                z-index: 10;
            }
            
            @keyframes bounce {
                0%, 100% { transform: translateX(0); }
                50% { transform: translateX(5px); }
            }
        }
        
        /* Animation pour les cartes de statistiques */
        .stats-card {
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(26, 54, 93, 0.15);
        }
        
        /* Style pour pagination */
        .pagination-custom .page-item.active .page-link {
            background-color: #4A90E2 !important;
            border-color: #4A90E2 !important;
        }
        
        .pagination-custom .page-link {
            color: #1A365D;
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
                                <i class="fas fa-chart-bar mr-3 text-primary-500"></i>
                                Rapport des Ventes
                            </h1>
                            <p class="text-text-secondary mt-1">Analyse et gestion de toutes vos transactions</p>
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
                                            <span class="text-sm font-medium text-text-secondary">Rapport des Ventes</span>
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
                    <!-- Cartes des statistiques -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white rounded-xl shadow-card overflow-hidden stats-card">
                            <div class="h-2 bg-gradient-to-r from-primary-500 to-primary-800 animate-gradient"></div>
                            <div class="p-6 flex items-center">
                                <div class="bg-primary-100 rounded-full p-4 mr-4">
                                    <i class="fas fa-shopping-cart text-2xl text-primary-700"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-text-secondary uppercase tracking-wider">Total des Ventes</h3>
                                    <p class="text-lg font-bold text-primary-800 mt-1">{{ number_format($totals['total_ventes'], 0, ',', ' ') }} F</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-xl shadow-card overflow-hidden stats-card">
                            <div class="h-2 bg-gradient-to-r from-success-500 to-success-700 animate-gradient"></div>
                            <div class="p-6 flex items-center">
                                <div class="bg-success-100 rounded-full p-4 mr-4">
                                    <i class="fas fa-chart-line text-2xl text-success-700"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-text-secondary uppercase tracking-wider">Total des Bénéfices</h3>
                                    <p class="text-lg font-bold text-success-700 mt-1">{{ number_format($totals['total_benefices'], 0, ',', ' ') }} F</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tableau principal -->
                    <div class="bg-white rounded-xl shadow-card overflow-hidden card-hover-effect">
                        <div class="h-2 bg-gradient-to-r from-primary-500 to-primary-800 animate-gradient"></div>
                        
                        <!-- En-tête et filtres -->
                        <div class="p-6 border-b border-primary-100">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                                <h2 class="text-lg font-semibold text-primary-800">
                                    <i class="fas fa-list mr-2 text-primary-500"></i> 
                                    Liste des Ventes
                                </h2>
                                
                                <!-- Boutons de période -->
                                <div class="flex flex-wrap gap-2 w-full md:w-auto">
                                    <div class="flex flex-wrap gap-2 mb-2 md:mb-0">
                                        <button type="button" class="inline-flex items-center px-4 py-2 border border-primary-300 rounded-lg text-primary-700 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition period-filter" data-period="today">
                                            <i class="fas fa-calendar-day mr-1"></i> Aujourd'hui
                                        </button>
                                        <button type="button" class="inline-flex items-center px-4 py-2 border border-primary-300 rounded-lg text-primary-700 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition period-filter" data-period="week">
                                            <i class="fas fa-calendar-week mr-1"></i> Cette semaine
                                        </button>
                                        <button type="button" class="inline-flex items-center px-4 py-2 border border-primary-300 rounded-lg text-primary-700 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition period-filter" data-period="month">
                                            <i class="fas fa-calendar-alt mr-1"></i> Ce mois
                                        </button>
                                        <button type="button" class="inline-flex items-center px-4 py-2 border border-primary-300 rounded-lg text-primary-700 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition period-filter" data-period="year">
                                            <i class="fas fa-calendar mr-1"></i> Cette année
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Filtre personnalisé par date -->
                            <div class="mt-4">
                                <form id="dateFilterForm" class="flex flex-col md:flex-row gap-3 items-end">
                                    <div class="w-full md:w-auto">
                                        <label for="start_date" class="block text-sm font-medium text-text-secondary mb-1">Date de début</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-calendar-alt text-text-light"></i>
                                            </div>
                                            <input 
                                                type="date" 
                                                class="form-input-focus block w-full pl-10 py-2 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition" 
                                                id="start_date" 
                                                name="start_date">
                                        </div>
                                    </div>
                                    <div class="w-full md:w-auto">
                                        <label for="end_date" class="block text-sm font-medium text-text-secondary mb-1">Date de fin</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-calendar-alt text-text-light"></i>
                                            </div>
                                            <input 
                                                type="date" 
                                                class="form-input-focus block w-full pl-10 py-2 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition" 
                                                id="end_date" 
                                                name="end_date">
                                        </div>
                                    </div>
                                    <button type="submit" class="inline-flex items-center justify-center py-2 px-4 border border-transparent rounded-lg shadow-button text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 transition transform hover:scale-[1.02] active:scale-[0.98] w-full md:w-auto">
                                        <i class="fas fa-filter mr-2"></i> Filtrer
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Corps du tableau -->
                        <div class="relative overflow-x-auto">
                            <div class="table-scroll-indicator md:hidden">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                            <table class="min-w-full divide-y divide-primary-100">
                                <thead>
                                    <tr class="bg-primary-50">
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Utilisateur</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Article</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Qté</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Prix Unit</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Total</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Bénéfice</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Motif</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-primary-700 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-primary-100">
                                    @if($sales->count() > 0)
                                        @foreach($sales as $sale)
                                            <tr class="hover:bg-primary-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-primary"> {{ $sale->user ? $sale->user->firstname . ' ' .$sale->user->lastname : 'N/A' }} </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-primary">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary-800">{{ $sale->article->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-primary">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                                        {{ $sale->articleQte }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-primary">{{ number_format($sale->article->normal_price, 0, ',', ' ') }} F</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary-800">{{ number_format($sale->total_price, 0, ',', ' ') }} F</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-success-600">{{ number_format($sale->benefice, 0, ',', ' ') }} F</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">{{ $sale->comment ?? '-' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-1">
                                                    <a href="{{ route('sales.edit', $sale->id) }}" class="btn-action inline-flex items-center justify-center p-2 rounded-lg text-primary-500 bg-primary-50 hover:bg-primary-100">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" data-output-id="{{ $sale->id }}" class="inline-block delete-sale-form delete-output-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-action inline-flex items-center justify-center p-2 rounded-lg text-accent-500 bg-accent-50 hover:bg-accent-100">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="8" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center">
                                                    <div class="bg-primary-50 rounded-full p-4 mb-4">
                                                        <i class="fas fa-search text-4xl text-primary-300"></i>
                                                    </div>
                                                    <h3 class="text-lg font-medium text-primary-800 mb-1">Aucune vente trouvée</h3>
                                                    <p class="text-text-secondary mb-6">Aucune vente n'a été enregistrée pour cette période.</p>
                                                    <a href="{{ route('achatvente') }}" class="inline-flex items-center justify-center py-2 px-4 border border-transparent rounded-lg shadow-button text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 transition transform hover:scale-[1.02] active:scale-[0.98]">
                                                        <i class="fas fa-plus mr-2"></i> Créer une vente
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination et bouton d'ajout -->
                        <div class="px-6 py-4 border-t border-primary-100">
                            <div class="flex flex-col md:flex-row justify-between items-center">
                                <div class="mb-4 md:mb-0 pagination-custom">
                                    {{ $sales->links() }}
                                </div>
                                <a href="{{ route('achatvente') }}" class="inline-flex items-center justify-center py-3 px-6 border border-transparent rounded-lg shadow-success text-white bg-success-600 hover:bg-success-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-success-400 transition transform hover:scale-[1.02] active:scale-[0.98]">
                                    <i class="fas fa-plus-circle mr-2"></i> Ajouter une nouvelle vente
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        
        <!-- Main Footer -->
        @include('apk.components.footer')
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion des filtres de période
        document.querySelectorAll('.period-filter').forEach(button => {
            button.addEventListener('click', function() {
                // Réinitialiser tous les boutons
                document.querySelectorAll('.period-filter').forEach(btn => {
                    btn.classList.remove('bg-primary-100', 'font-medium');
                });
                
                // Activer le bouton sélectionné
                this.classList.add('bg-primary-100', 'font-medium');
                
                const period = this.dataset.period;
                window.location.href = `{{ route('sales.index') }}?period=${period}`;
            });
        });
    
        // Gestion du filtre par date
        document.getElementById('dateFilterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            
            if(startDate && endDate) {
                window.location.href = `{{ route('sales.index') }}?start_date=${startDate}&end_date=${endDate}`;
            } else {
                // Afficher une alerte si les dates ne sont pas renseignées
                Swal.fire({
                    title: 'Attention',
                    text: 'Veuillez sélectionner une date de début et une date de fin',
                    icon: 'warning',
                    confirmButtonColor: '#4A90E2',
                    customClass: {
                        popup: 'rounded-xl',
                        title: 'text-primary-800',
                        content: 'text-text-secondary'
                    }
                });
            }
        });

        // Gestion de la suppression des ventes
        attachDeleteListeners();
        
        // Mise en évidence du filtre actif
        highlightActiveFilter();
    });

    function highlightActiveFilter() {
        // Récupérer les paramètres de l'URL
        const urlParams = new URLSearchParams(window.location.search);
        const period = urlParams.get('period');
        
        if (period) {
            const activeButton = document.querySelector(`.period-filter[data-period="${period}"]`);
            if (activeButton) {
                activeButton.classList.add('bg-primary-100', 'font-medium');
            }
        }
    }

    function attachDeleteListeners() {
        document.querySelectorAll('.delete-sale-form').forEach(form => {
            form.removeEventListener('submit', handleDelete);
            form.addEventListener('submit', handleDelete);
        });
    }

    async function handleDelete(e) {
        e.preventDefault();
        const form = e.target;
        const outputId = form.getAttribute('data-output-id');

        const result = await Swal.fire({
            title: 'Êtes-vous sûr?',
            text: "Cette action est irréversible!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4A90E2',
            cancelButtonColor: '#FF9F43',
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler',
            customClass: {
                popup: 'rounded-xl',
                title: 'text-primary-800',
                content: 'text-text-secondary'
            }
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`/salesdestroy/${outputId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    await Swal.fire({
                        title: 'Supprimé!',
                        text: data.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'rounded-xl',
                            title: 'text-primary-800',
                            content: 'text-text-secondary'
                        }
                    });
                    // Recharger la page après la suppression
                    window.location.href = window.location.href;
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Swal.fire({
                    title: 'Erreur!',
                    text: error.message || 'Une erreur est survenue lors de la suppression',
                    icon: 'error',
                    customClass: {
                        popup: 'rounded-xl',
                        title: 'text-primary-800'
                    }
                });
            }
        }
    }
    </script>
</body>
</html>