
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
    <title>Gestion des catégories</title>
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
                    },
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        .category-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.3rem 0.8rem;
            background-color: rgba(74, 144, 226, 0.15);
            color: #3A73B5;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            min-width: 2rem;
            min-height: 2rem;
        }
        
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
    </style>
</head>

<body class="bg-primary-50 font-sans text-text-primary">
    <div class="wrapper">
        <!-- Navbar -->
        @include('apk.components.topbar')

        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        @include('apk.components.navbar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper bg-primary-50 p-4">
            <!-- Content Header (Page header) -->
            <div class="content-header mb-6">
                <div class="container-fluid">
                    <div class="flex flex-wrap justify-between items-center">
                        <div class="mb-4 md:mb-0">
                            <h1 class="text-2xl font-bold text-primary-800 flex items-center">
                                <i class="fas fa-tag mr-3 text-primary-500"></i>
                                Gestion des catégories
                            </h1>
                            <p class="text-text-secondary mt-1">Créez et gérez les catégories de produits</p>
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
                                            <span class="text-sm font-medium text-text-secondary">Catégories</span>
                                        </div>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Formulaire de catégorie -->
                        <div class="md:col-span-1">
                            <div class="bg-white rounded-xl shadow-card overflow-hidden card-hover-effect">
                                <div class="h-2 bg-gradient-to-r from-primary-500 to-primary-800 animate-gradient"></div>
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold text-primary-800 mb-4" id="formTitle">
                                        <i class="fas fa-plus-circle mr-2 text-primary-500"></i> 
                                        Créer une catégorie
                                    </h3>
                                    <form id="productForm" action="{{ route('categories.store') }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="name" class="block text-sm font-medium text-text-primary mb-2">Nom de la catégorie</label>
                                            <input type="hidden" id="categoryId" name="category_id">
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-folder text-text-light"></i>
                                                </div>
                                                <input 
                                                    type="text" 
                                                    name="name" 
                                                    id="name" 
                                                    class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                                    placeholder="Entrez le nom de la catégorie">
                                            </div>
                                        </div>
                                        <div class="flex mt-6">
                                            <button type="submit" class="flex items-center justify-center py-3 px-4 border border-transparent rounded-lg shadow-button text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 transition transform hover:scale-[1.02] active:scale-[0.98]" id="submitButton">
                                                <i class="fas fa-save mr-2"></i> Créer
                                            </button>
                                            <button type="button" class="ml-3 flex items-center justify-center py-3 px-4 border border-primary-200 rounded-lg text-primary-600 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 transition transform hover:scale-[1.02] active:scale-[0.98]" id="resetButton" style="display: none;">
                                                <i class="fas fa-times mr-2"></i> Annuler
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Aide et informations -->
                            <div class="bg-white rounded-xl shadow-card overflow-hidden mt-6 p-5">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 bg-primary-100 rounded-full p-2">
                                        <i class="fas fa-info-circle text-primary-700 text-base"></i>
                                    </div>
                                    <div class="ml-2">
                                        <h4 class="text-base font-medium text-primary-800">Conseil d'utilisation</h4>
                                        <p class="text-sm text-text-secondary mt-1">Les catégories vous permettent d'organiser vos produits pour une meilleure gestion de stock et des rapports plus précis.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Liste des catégories -->
                        <div class="md:col-span-2">
                            <div class="bg-white rounded-xl shadow-card overflow-hidden card-hover-effect">
                                <div class="h-2 bg-gradient-to-r from-primary-500 to-primary-800 animate-gradient"></div>
                                <div class="p-5">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg font-semibold text-primary-800">
                                            <i class="fas fa-list mr-2 text-primary-500"></i> 
                                            Liste des catégories
                                        </h3>
                                        <span class="bg-primary-100 text-primary-700 text-sm font-medium px-3 py-1 rounded-full">
                                            Total: {{ count($categories) }}
                                        </span>
                                    </div>
                                    
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full bg-white">
                                            <thead>
                                                <tr class="bg-primary-50 text-left text-xs font-semibold text-primary-700 uppercase tracking-wider">
                                                    <th class="py-3 px-4 rounded-tl-lg">#</th>
                                                    <th class="py-3 px-4">Nom</th>
                                                    <th class="py-3 px-4">Articles</th>
                                                    <th class="py-3 px-4 rounded-tr-lg text-right">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-primary-100">
                                                @foreach ($categories as $index => $categorie)
                                                    <tr class="hover:bg-primary-50 transition-colors">
                                                        <td class="py-3 px-4">
                                                            <span class="bg-primary-100 text-primary-800 text-xs font-semibold px-2 py-1 rounded-md">
                                                                {{ $index + 1 }}
                                                            </span>
                                                        </td>
                                                        <td class="py-3 px-4 font-medium">{{ $categorie->name }}</td>
                                                        <td class="py-3 px-4">
                                                            <span class="category-count">{{ $categorie->articles_count }}</span>
                                                        </td>
                                                        <td class="py-3 px-4 text-right">
                                                            <button class="btn-action inline-flex items-center justify-center p-2 rounded-lg text-primary-500 bg-primary-50 hover:bg-primary-100 mr-2 edit-category" 
                                                                data-id="{{ $categorie->id }}"
                                                                data-name="{{ $categorie->name }}">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </button>
                                                            <button class="btn-action inline-flex items-center justify-center p-2 rounded-lg text-accent-500 bg-accent-50 hover:bg-accent-100 delete-category" 
                                                                data-id="{{ $categorie->id }}"
                                                                data-url="{{ route('categories.destroy', $categorie->id) }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                
                                                @if(count($categories) == 0)
                                                    <tr>
                                                        <td colspan="4" class="py-8 text-center text-text-secondary">
                                                            <div class="flex flex-col items-center">
                                                                <i class="fas fa-folder-open text-4xl text-primary-200 mb-3"></i>
                                                                <p>Aucune catégorie trouvée</p>
                                                                <p class="text-sm mt-1">Créez votre première catégorie en utilisant le formulaire</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        
        <!-- Main Footer -->
        @include('apk.components.footer')
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryNameInput = document.getElementById('name');
        const categoryIdInput = document.getElementById('categoryId');
        const submitButton = document.getElementById('submitButton');
        const resetButton = document.getElementById('resetButton');
        const formTitle = document.getElementById('formTitle');
        const form = document.getElementById('productForm');

        // Réinitialiser le formulaire
        function resetForm() {
            categoryNameInput.value = '';
            categoryIdInput.value = '';
            submitButton.innerHTML = '<i class="fas fa-save mr-2"></i> Créer';
            formTitle.innerHTML = '<i class="fas fa-plus-circle mr-2 text-primary-500"></i> Créer une catégorie';
            resetButton.style.display = 'none';
            form.action = "{{ route('categories.store') }}";
            
            // Supprimer la méthode PUT si elle existe
            const methodInput = document.querySelector('input[name="_method"]');
            if (methodInput) {
                methodInput.remove();
            }
        }

        // Bouton d'annulation
        resetButton.addEventListener('click', function() {
            resetForm();
        });

        // Gérer la soumission du formulaire
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const formData = new FormData(this);
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
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
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Une erreur est survenue');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: error.message,
                    customClass: {
                        popup: 'rounded-xl',
                        title: 'text-primary-800'
                    }
                });
            }
        });

        // Gérer les boutons de suppression
        document.querySelectorAll('.delete-category').forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();

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
                        const url = this.getAttribute('data-url');
                        const response = await fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Supprimé!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'rounded-xl',
                                    title: 'text-primary-800',
                                    content: 'text-text-secondary'
                                }
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            throw new Error(data.message);
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: error.message || 'Une erreur est survenue',
                            customClass: {
                                popup: 'rounded-xl',
                                title: 'text-primary-800'
                            }
                        });
                    }
                }
            });
        });

        // Gérer le clic sur le bouton modifier
        document.querySelectorAll('.edit-category').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const categoryId = this.getAttribute('data-id');
                const categoryName = this.getAttribute('data-name');

                categoryNameInput.value = categoryName;
                categoryIdInput.value = categoryId;
                submitButton.innerHTML = '<i class="fas fa-edit mr-2"></i> Modifier';
                formTitle.innerHTML = '<i class="fas fa-edit mr-2 text-primary-500"></i> Modifier la catégorie';
                resetButton.style.display = 'inline-flex';
                
                // Mettre à jour l'action du formulaire
                form.action = `/glsam/categories/${categoryId}/update`;
                
                // Ajouter la méthode PUT
                const methodInput = document.querySelector('input[name="_method"]');
                if (methodInput) {
                    methodInput.value = 'PUT';
                } else {
                    const newMethodInput = document.createElement('input');
                    newMethodInput.type = 'hidden';
                    newMethodInput.name = '_method';
                    newMethodInput.value = 'PUT';
                    form.appendChild(newMethodInput);
                }
                
                // Scroll to form
                document.querySelector('.form-input-focus').scrollIntoView({ behavior: 'smooth' });
                document.querySelector('.form-input-focus').focus();
            });
        });
    });
    </script>
</body>
</html>