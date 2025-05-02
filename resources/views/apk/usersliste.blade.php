

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
    <title>Gestion des utilisateurs</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap JS -->

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
        
        .btn-action {
            transition: all 0.2s ease;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
        }
        
        .form-input-focus:focus {
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.25);
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
                                <i class="fas fa-users mr-3 text-primary-500"></i>
                                Gestion des utilisateurs
                            </h1>
                            <p class="text-text-secondary mt-1">Gérez les utilisateurs et leurs permissions</p>
                        </div>
                        <div>
                            <nav class="flex" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                    <li class="inline-flex items-center">
                                        <a href="{{ route('achatvente') }}" class="inline-flex items-center text-sm font-medium text-primary-500 hover:text-primary-700">
                                            <i class="fas fa-home mr-2"></i>
                                            Accueil
                                        </a>
                                    </li>
                                    <li>
                                        <div class="flex items-center">
                                            <i class="fas fa-chevron-right text-text-light text-sm mx-2"></i>
                                            <span class="text-sm font-medium text-text-secondary">Utilisateurs</span>
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
                    <div class="bg-white rounded-lg shadow-card overflow-hidden card-hover-effect">
                        <div class="h-2 bg-gradient-to-r from-primary-500 to-primary-800 animate-gradient"></div>
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="font-semibold text-lg text-primary-800">Liste des utilisateurs</h3>
                            <a href="{{ route('invitation-codes.index') }}" class="btn-action inline-flex items-center px-4 py-2 bg-primary-500 text-white rounded-lg shadow-button hover:bg-primary-600 transition">
                                <i class="fas fa-user-plus mr-2"></i>
                                Inviter un utilisateur
                            </a>
                        </div>
                        <div class="p-6">
                            @if(session('success'))
                                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        <p>{{ session('success') }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            @if(session('error'))
                                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <p>{{ session('error') }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white rounded-lg overflow-hidden">
                                    <thead class="bg-gray-100 text-gray-700">
                                        <tr>
                                            <th class="py-3 px-4 text-left font-medium">ID</th>
                                            <th class="py-3 px-4 text-left font-medium">Photo</th>
                                            <th class="py-3 px-4 text-left font-medium">Nom</th>
                                            <th class="py-3 px-4 text-left font-medium">Email</th>
                                            <th class="py-3 px-4 text-left font-medium">Rôle</th>
                                            <th class="py-3 px-4 text-left font-medium">Statut</th>
                                            <th class="py-3 px-4 text-left font-medium">Date d'inscription</th>
                                            <th class="py-3 px-4 text-left font-medium">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($users as $user)
                                        <tr class="hover:bg-gray-50">
                                            <td class="py-3 px-4">{{ $user->id }}</td>
                                            <td class="py-3 px-4 text-center">
                                                @if($user->profile_photo_path)
                                                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="" class="rounded-full" width="40" height="40">
                                                @else
                                                    <img src="{{ asset('assets/img/default-profile.jpg') }}" alt="" class="rounded-full" width="40" height="40">
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">{{ $user->lastname }}</td>
                                            <td class="py-3 px-4">{{ $user->email }}</td>
                                            <td class="py-3 px-4">
                                                @if($user->is_admin)
                                                    <span class="px-2 py-1 bg-primary-100 text-primary-800 rounded-full text-xs font-medium">Administrateur</span>
                                                @else
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-medium">Utilisateur</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">
                                                @if($user->is_active)
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Actif</span>
                                                @else
                                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Bloqué</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="py-3 px-4">
                                                @if($user->id !== auth()->id())
                                                    <div class="flex space-x-2">
                                                        @if($user->is_active)
                                                            <button type="button" class="btn-action p-2 bg-amber-50 text-amber-700 rounded hover:bg-amber-100 border border-amber-200" onclick="confirmToggleStatus({{ $user->id }}, 'block')">
                                                                <i class="fas fa-ban"></i>
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn-action p-2 bg-green-50 text-green-700 rounded hover:bg-green-100 border border-green-200" onclick="confirmToggleStatus({{ $user->id }}, 'activate')">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        @endif
                                                        <button type="button" class="btn-action p-2 bg-red-50 text-red-700 rounded hover:bg-red-100 border border-red-200" onclick="confirmDelete({{ $user->id }})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 text-sm italic">Votre compte</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6">
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Modal de confirmation pour le blocage/activation -->
        <div class="modal fade" id="confirmStatusModal" tabindex="-1" aria-labelledby="confirmStatusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmStatusModalLabel">Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="confirmStatusBody">
                        Êtes-vous sûr de vouloir modifier le statut de cet utilisateur ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <form id="toggleStatusForm" method="POST" action="">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-primary">Confirmer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmation pour la suppression -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmation de suppression</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Attention: Cette action est irréversible!
                        </div>
                        Êtes-vous sûr de vouloir supprimer définitivement cet utilisateur ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <form id="deleteUserForm" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Footer -->
        @include('apk.components.footer')
    </div>

    <script>
        function confirmToggleStatus(userId, action) {
            let modalBody = document.getElementById('confirmStatusBody');
            let form = document.getElementById('toggleStatusForm');
            
            if (action === 'block') {
                modalBody.textContent = "Êtes-vous sûr de vouloir bloquer cet utilisateur ? Il ne pourra plus se connecter à l'application.";
                form.action = "{{ url('') }}/" + userId + "/block";
            } else {
                modalBody.textContent = "Êtes-vous sûr de vouloir réactiver cet utilisateur ? Il pourra à nouveau se connecter à l'application.";
                form.action = "{{ url('') }}/" + userId + "/activate";
            }
            
            $('#confirmStatusModal').modal('show');
        }
        
        function confirmDelete(userId) {
            let form = document.getElementById('deleteUserForm');
            form.action = "{{ url('users') }}/" + userId;
            $('#confirmDeleteModal').modal('show');
        }
    </script>
</body>
</html>