<!-- resources/views/productions/type-production.blade.php -->
@extends('layouts.app')

@section('title', 'Gestion des types de production')

@section('breadcrumb')
    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
    {{-- <a href="{{ route('productions.index') }}" class="hover:text-blue-700 transition-colors">Productions</a> --}}
    <a href="#" class="hover:text-blue-700 transition-colors">Productions</a>
    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
    <span class="text-gray-700">Types de production</span>
@endsection

@section('page-title', 'Gestion des types de production')
@section('page-subtitle', 'Créer, modifier et supprimer des types de production')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
        <!-- Panneau latéral (formulaire) -->
        <div class="order-2 lg:order-1 lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-5 sticky top-4">
                <h2 id="formTitle" class="text-lg font-bold text-navy-800 mb-4">
                    <i class="fas fa-plus-circle mr-2 text-blue-500"></i> <span id="formTitleText">Créer un type de production</span>
                </h2>
                <form id="typeForm" action="{{ route('types.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" id="typeId" name="typeId">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom du type de production</label>
                        <input type="text" name="name" id="name" placeholder="Entrez le nom du type de production" 
                               class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <div id="nameError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" id="submitButton" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center w-full">
                            <i class="fas fa-save mr-2"></i> <span id="submitButtonText">Créer</span>
                        </button>
                        <button type="button" id="resetButton" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center hidden">
                            <i class="fas fa-times mr-2"></i> Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des types de production -->
        <div class="order-1 lg:order-2 lg:col-span-2 bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <h2 class="text-lg font-bold text-navy-800">Liste des types de production</h2>
                <div class="relative w-full sm:w-auto">
                    <input type="text" id="searchType" placeholder="Rechercher..." 
                           class="pl-9 pr-3 py-1.5 text-sm border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="typesTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nom
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Productions
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                Date de création
                            </th>
                            <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="typesTableBody">
                        @forelse ($types as $type)
                            <tr class="hover:bg-gray-50 transition-colors" data-id="{{ $type->id }}">
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $type->name }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">{{ $type->productions_count }}</div>
                                </td>
                                <td class="px-4 py-3 hidden md:table-cell">
                                    <div class="text-sm text-gray-900">{{ $type->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end space-x-2">
                                        <button class="edit-type p-1.5 rounded-md hover:bg-blue-50 text-blue-600 transition-colors" 
                                                data-id="{{ $type->id }}" 
                                                data-name="{{ $type->name }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="delete-type p-1.5 rounded-md hover:bg-red-50 text-red-600 transition-colors" 
                                                data-id="{{ $type->id }}" 
                                                data-url="{{ route('types.destroy', $type->id) }}" 
                                                data-name="{{ $type->name }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRow">
                                <td colspan="4" class="px-4 py-4 text-center text-gray-500">
                                    Aucun type de production trouvé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $types->links() }}
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    /* Styles personnalisés pour SweetAlert2 */
    .swal2-popup.rounded-xl {
        border-radius: 1rem !important;
    }
    
    .swal2-title.text-navy-800 {
        color: #243b53 !important;
    }
    
    .swal2-html-container.text-gray-600 {
        color: #4b5563 !important;
    }
    
    /* Animation personnalisée pour SweetAlert2 */
    .swal2-popup {
        animation: swalAppear 0.3s ease-out;
    }
    
    @keyframes swalAppear {
        0% {
            opacity: 0;
            transform: scale(0.9);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    /* Styles pour les boutons SweetAlert2 */
    .swal2-styled.swal2-confirm {
        background-color: #4a90e2 !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    }
    
    .swal2-styled.swal2-cancel {
        background-color: #f3f4f6 !important;
        color: #374151 !important;
        border-radius: 0.5rem !important;
    }

    /* Pour les écrans mobiles, optimiser l'espace */
    @media (max-width: 640px) {
        .swal2-popup {
            padding: 1rem !important;
        }
        
        .swal2-title {
            font-size: 1.25rem !important;
        }
    }
</style>
@endsection


<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables globales
        const typeForm = document.getElementById('typeForm');
        const formTitle = document.getElementById('formTitleText');
        const submitButton = document.getElementById('submitButton');
        const submitButtonText = document.getElementById('submitButtonText');
        const resetButton = document.getElementById('resetButton');
        const searchInput = document.getElementById('searchType');
        const nameInput = document.getElementById('name');
        const nameError = document.getElementById('nameError');
        const typeIdInput = document.getElementById('typeId');
        let isEditMode = false;
        let originalFormAction = typeForm.action;
        
        // Initialisation
        setupEventListeners();
        
        // Configuration des écouteurs d'événements
        function setupEventListeners() {
            // Formulaire d'ajout/modification
            typeForm.addEventListener('submit', handleFormSubmit);
            resetButton.addEventListener('click', resetForm);
            
            // Recherche
            searchInput.addEventListener('input', handleSearch);
            
            // Délégation d'événements pour les actions de tableau
            document.addEventListener('click', function(e) {
                const editButton = e.target.closest('.edit-type');
                if (editButton) {
                    handleEdit(editButton);
                }
                
                const deleteButton = e.target.closest('.delete-type');
                if (deleteButton) {
                    handleDelete(deleteButton);
                }
            });
        }
        
        // Gestionnaires d'événements
        function handleFormSubmit(e) {
            e.preventDefault();
            
            // Réinitialiser les messages d'erreur
            nameError.classList.add('hidden');
            
            // Récupérer les données du formulaire
            const formData = new FormData(typeForm);
            
            // Déterminer l'URL et la méthode en fonction du mode (création ou édition)
            let url = originalFormAction;
            let method = 'POST';
            
            if (isEditMode) {
                const typeId = typeIdInput.value;
                url = `${window.location.origin}/types/${typeId}/update`;
                formData.append('_method', 'PUT'); // Pour simuler une requête PUT
            }
            
            // Envoyer la requête
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erreur HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showSuccessAlert(data.message, true);
                    resetForm();
                } else {
                    if (data.errors && data.errors.name) {
                        nameError.textContent = data.errors.name[0];
                        nameError.classList.remove('hidden');
                    } else {
                        showErrorAlert(data.message || 'Une erreur est survenue');
                    }
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showErrorAlert('Une erreur est survenue lors de la communication avec le serveur');
            });
        }
        
        function handleEdit(button) {
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            
            // Mettre à jour le formulaire
            typeIdInput.value = id;
            nameInput.value = name;
            
            // Changer l'apparence du formulaire
            formTitle.textContent = 'Modifier le type de production';
            submitButtonText.textContent = 'Mettre à jour';
            resetButton.classList.remove('hidden');
            
            // Changer l'état du formulaire
            isEditMode = true;
            
            // Faire défiler jusqu'au formulaire sur mobile
            if (window.innerWidth < 1024) {
                document.querySelector('.order-2').scrollIntoView({ behavior: 'smooth' });
            }
        }
        
        function handleDelete(button) {
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const url = button.getAttribute('data-url');
            
            Swal.fire({
                title: 'Confirmer la suppression ?',
                html: `Êtes-vous sûr de vouloir supprimer le type de production <strong>${name}</strong> ?<br>Cette action est irréversible.`,
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
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Erreur HTTP: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Supprimer la ligne du tableau ou recharger la page
                            const row = document.querySelector(`tr[data-id="${id}"]`);
                            if (row) {
                                row.remove();
                                
                                // Vérifier si le tableau est vide
                                const rows = document.querySelectorAll('#typesTableBody tr[data-id]');
                                if (rows.length === 0) {
                                    const emptyRow = document.createElement('tr');
                                    emptyRow.id = 'emptyRow';
                                    emptyRow.innerHTML = `<td colspan="4" class="px-4 py-4 text-center text-gray-500">Aucun type de production trouvé</td>`;
                                    document.getElementById('typesTableBody').appendChild(emptyRow);
                                }
                            }
                            
                            showSuccessAlert(data.message);
                        } else {
                            showErrorAlert(data.message || 'Une erreur est survenue');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        showErrorAlert('Une erreur est survenue lors de la communication avec le serveur');
                    });
                }
            });
        }
        
        function handleSearch() {
            const searchTerm = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll('#typesTableBody tr[data-id]');
            let hasVisibleRows = false;
            
            rows.forEach(row => {
                const typeName = row.querySelector('td:first-child div').textContent.toLowerCase();
                const shouldShow = typeName.includes(searchTerm);
                
                row.style.display = shouldShow ? '' : 'none';
                if (shouldShow) hasVisibleRows = true;
            });
            
            // Afficher ou masquer la ligne "Aucun type de production trouvé"
            const emptyRow = document.getElementById('emptyRow');
            if (emptyRow) {
                emptyRow.style.display = hasVisibleRows ? 'none' : '';
            } else if (!hasVisibleRows) {
                const newEmptyRow = document.createElement('tr');
                newEmptyRow.id = 'emptyRow';
                newEmptyRow.innerHTML = `<td colspan="4" class="px-4 py-4 text-center text-gray-500">Aucun type de production trouvé</td>`;
                document.getElementById('typesTableBody').appendChild(newEmptyRow);
            }
        }
        
        function resetForm() {
            // Réinitialiser les valeurs
            typeForm.reset();
            typeIdInput.value = '';
            nameError.classList.add('hidden');
            
            // Réinitialiser l'apparence
            formTitle.textContent = 'Créer un type de production';
            submitButtonText.textContent = 'Créer';
            resetButton.classList.add('hidden');
            
            // Réinitialiser l'état
            isEditMode = false;
        }
        
        // Fonctions utilitaires
        function showSuccessAlert(message, reload = false) {
            Swal.fire({
                icon: 'success',
                title: 'Succès!',
                text: message,
                timer: reload ? 1500 : 2000,
                showConfirmButton: false,
                customClass: {
                    popup: 'rounded-xl',
                    title: 'text-navy-800',
                    htmlContainer: 'text-gray-600'
                }
            }).then(() => {
                if (reload) {
                    window.location.reload();
                }
            });
        }
        
        function showErrorAlert(message) {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: message,
                customClass: {
                    popup: 'rounded-xl',
                    title: 'text-navy-800',
                    htmlContainer: 'text-gray-600'
                }
            });
        }
    });
</script>
