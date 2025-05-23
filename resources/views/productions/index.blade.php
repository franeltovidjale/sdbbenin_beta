<!-- resources/views/productions/index.blade.php -->
@extends('layouts.app')

@section('title', 'Productions')

@section('breadcrumb')
    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
    <span class="text-gray-700">Productions</span>
@endsection

@section('page-title', 'Gestion des productions')
@section('page-subtitle', 'Vue d\'ensemble des productions')

@section('content')
    <div class="mb-6 bg-white rounded-lg shadow-sm p-4">
        <!-- Boutons d'action et filtres -->
        <div class="flex flex-wrap justify-between items-center mb-4">
            <div class="w-full md:w-auto mb-3 md:mb-0">
                <a href="{{ route('production.create') }}" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 flex items-center justify-center no-shift inline-flex">
                    <i class="fas fa-plus-circle mr-2"></i> Nouvelle production
                </a>
            </div>
            
            <div class="w-full md:w-auto">
                <form action="{{ route('production.search') }}" method="GET" class="flex flex-wrap gap-2">
                    <div class="relative">
                        <input type="text" name="q" placeholder="Rechercher..." value="{{ $keyword ?? '' }}" class="pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 w-full md:w-48">
                        <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                    </div>
                    
                    <select name="type_id" class="py-2 px-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                        <option value="">Tous les types</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ (isset($type_id) && $type_id == $type->id) ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    
                    <select name="status" class="py-2 px-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                        <option value="">Tous les statuts</option>
                        <option value="en cours" {{ (isset($status) && $status == 'en cours') ? 'selected' : '' }}>En cours</option>
                        <option value="terminé" {{ (isset($status) && $status == 'terminé') ? 'selected' : '' }}>Terminé</option>
                    </select>
                    
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all duration-200 flex items-center no-shift">
                        <i class="fas fa-filter mr-2"></i> Filtrer
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Grille de productions -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @forelse($productions as $production)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden flex flex-col h-full">
                    <div class="p-4 flex-grow">
                        <!-- En-tête de la carte avec statut -->
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                @if($production->status == 'en cours')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <span class="mr-1 h-2 w-2 bg-blue-500 rounded-full"></span>
                                        En cours
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="mr-1 h-2 w-2 bg-green-500 rounded-full"></span>
                                        Terminé
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Menu à trois points -->
                            @if($production->status == 'en cours')
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false" class="p-1 rounded-full hover:bg-gray-100 focus:outline-none">
                                    <i class="fas fa-ellipsis-v text-gray-500"></i>
                                </button>
                                <div x-show="open" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10" style="display: none;">
                                    <div class="py-1">
                                        <a href="{{ route('production.edit', $production->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-edit mr-2 text-blue-500"></i> Modifier
                                        </a>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 view-production" data-id="{{ $production->id }}">
                                            <i class="fas fa-eye mr-2 text-green-500"></i> Voir la production
                                        </a>
                                        
                                        <button class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 delete-production" data-id="{{ $production->id }}" data-name="{{ $production->production_name }}">
                                            <i class="fas fa-trash mr-2 text-red-500"></i> Supprimer
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Contenu de la carte -->
                        <h3 class="text-lg font-semibold text-gray-900 truncate mb-1" title="{{ $production->production_name }}">
                            {{ $production->production_name }}
                        </h3>
                        
                        <div class="text-sm text-gray-600 mb-3 truncate" title="{{ $production->type->name ?? 'Aucun type' }}">
                            <i class="fas fa-tag mr-1 text-blue-500"></i> {{ $production->type->name ?? 'Aucun type' }}
                        </div>
                        
                        <div class="text-sm text-gray-600 mb-1">
                            <i class="fas fa-calendar-alt mr-1 text-blue-500"></i> {{ date('d/m/Y', strtotime($production->production_date)) }}
                        </div>
                        
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-cubes mr-1 text-blue-500"></i> Quantité: {{ number_format($production->qte_production, 2, ',', ' ') }}
                        </div>
                    </div>
                    
                    <!-- Pied de carte -->
                    <div class="mt-auto border-t border-gray-200">
                        <a href="{{ route('production.show', $production->id) }}" class="block w-full text-center py-2 text-blue-600 hover:bg-blue-50 transition-colors">
                            Voir la production
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center">
                    <div class="text-gray-400 mb-3">
                        <i class="fas fa-box-open text-5xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700">Aucune production trouvée</h3>
                    <p class="text-gray-500 mt-1">Commencez par créer une nouvelle production</p>
                    <a href="{{ route('production.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all duration-200">
                        <i class="fas fa-plus-circle mr-2"></i> Nouvelle production
                    </a>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $productions->links() }}
        </div>
    </div>
@endsection


<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Suppression d'une production
        document.querySelectorAll('.delete-production').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                
                Swal.fire({
                    title: 'Confirmer la suppression ?',
                    html: `Êtes-vous sûr de vouloir supprimer la production <strong>${name}</strong> ?<br>Cette action est irréversible.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Supprimer',
                    cancelButtonText: 'Annuler',
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    customClass: {
                        popup: 'rounded-xl',
                        title: 'text-navy-800',
                        htmlContainer: 'text-gray-600'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Envoyer la requête de suppression
                        fetch(`/productions/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Succès!',
                                    text: data.message,
                                    timer: 1500,
                                    showConfirmButton: false,
                                    customClass: {
                                        popup: 'rounded-xl',
                                        title: 'text-navy-800'
                                    }
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur',
                                    text: data.message,
                                    customClass: {
                                        popup: 'rounded-xl',
                                        title: 'text-navy-800'
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: 'Une erreur est survenue lors de la communication avec le serveur',
                                customClass: {
                                    popup: 'rounded-xl',
                                    title: 'text-navy-800'
                                }
                            });
                        });
                    }
                });
            });
        });
        
        // Marquer une production comme terminée
        document.querySelectorAll('.complete-production').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                
                Swal.fire({
                    title: 'Confirmer le changement de statut ?',
                    html: `Êtes-vous sûr de vouloir marquer la production <strong>${name}</strong> comme terminée ?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Confirmer',
                    cancelButtonText: 'Annuler',
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    customClass: {
                        popup: 'rounded-xl',
                        title: 'text-navy-800',
                        htmlContainer: 'text-gray-600'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Envoyer la requête pour changer le statut
                        fetch(`/productions/${id}/complete`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Succès!',
                                    text: data.message,
                                    timer: 1500,
                                    showConfirmButton: false,
                                    customClass: {
                                        popup: 'rounded-xl',
                                        title: 'text-navy-800'
                                    }
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur',
                                    text: data.message,
                                    customClass: {
                                        popup: 'rounded-xl',
                                        title: 'text-navy-800'
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: 'Une erreur est survenue lors de la communication avec le serveur',
                                customClass: {
                                    popup: 'rounded-xl',
                                    title: 'text-navy-800'
                                }
                            });
                        });
                    }
                });
            });
        });
        
        // Voir détails d'une production
        document.querySelectorAll('.view-production').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                
                // Rediriger vers la page détaillée de la production ou afficher une modal
                window.location.href = `/productions/${id}`;
                
                // Ou afficher une modal (exemple) :
                /*
                Swal.fire({
                    title: 'Détails de la production',
                    html: 'Chargement...',
                    showConfirmButton: false,
                    customClass: {
                        popup: 'rounded-xl',
                        title: 'text-navy-800'
                    },
                    didOpen: () => {
                        fetch(`/productions/${id}/details`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Mettre à jour le contenu de la modal avec les détails
                            Swal.update({
                                html: `
                                    <div class="text-left">
                                        <p><strong>Nom:</strong> ${data.production_name}</p>
                                        <p><strong>Type:</strong> ${data.type.name}</p>
                                        <p><strong>Date:</strong> ${data.production_date}</p>
                                        <p><strong>Quantité:</strong> ${data.qte_production}</p>
                                        <p><strong>Statut:</strong> ${data.status}</p>
                                    </div>
                                `,
                                showConfirmButton: true,
                                confirmButtonText: 'Fermer'
                            });
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                            Swal.update({
                                html: 'Erreur lors du chargement des détails',
                                showConfirmButton: true,
                                confirmButtonText: 'Fermer'
                            });
                        });
                    }
                });
                */
            });
        });
    });
</script>
