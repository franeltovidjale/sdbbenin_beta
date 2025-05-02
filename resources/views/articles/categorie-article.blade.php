{{-- 

<!-- resources/views/articles/categorie-article.blade.php -->
@extends('layouts.app')

@section('title', 'Gestion des catégories')

@section('breadcrumb')
    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
    <a href="{{ route('articles.index') }}" class="hover:text-blue-700 transition-colors">Articles</a>
    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
    <span class="text-gray-700">Catégories</span>
@endsection

@section('page-title', 'Gestion des catégories')
@section('page-subtitle', 'Créer, modifier et supprimer des catégories d\'articles')

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
</style>

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Formulaire d'ajout/modification de catégorie -->
        <div class="bg-white rounded-lg shadow-sm p-6 md:col-span-1 h-fit">
            <h2 id="formTitle" class="text-lg font-bold text-navy-800 mb-4">
                <i class="fas fa-plus-circle mr-2 text-blue-500"></i> Créer une catégorie
            </h2>
            <form id="categoryForm" action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" id="categoryId" name="categoryId">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom de la catégorie</label>
                    <input type="text" name="name" id="name" placeholder="Entrez le nom de la catégorie" 
                           class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    <div id="nameError" class="text-red-500 text-xs mt-1 hidden"></div>
                </div>
                <div class="flex space-x-2">
                    <button type="submit" id="submitButton" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center no-shift w-full">
                        <i class="fas fa-save mr-2"></i> Créer
                    </button>
                    <button type="button" id="resetButton" style="display: none;" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center no-shift">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </button>
                </div>
            </form>
        </div>

        <!-- Liste des catégories -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden md:col-span-2">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-bold text-navy-800">Liste des catégories</h2>
                <div class="relative">
                    <input type="text" id="searchCategory" placeholder="Rechercher..." 
                           class="pl-9 pr-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="categoriesTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nom
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Articles
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date de création
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="categoriesTableBody">
                        @forelse ($categories as $categorie)
                            <tr class="hover:bg-gray-50 transition-colors" data-id="{{ $categorie->id }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $categorie->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $categorie->articles_count }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $categorie->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <button class="edit-category p-1 rounded-md hover:bg-blue-50 text-blue-600 transition-colors" 
                                                data-id="{{ $categorie->id }}" 
                                                data-name="{{ $categorie->name }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="delete-category p-1 rounded-md hover:bg-red-50 text-red-600 transition-colors" 
                                                data-id="{{ $categorie->id }}" 
                                                data-url="{{ route('categories.destroy', $categorie->id) }}" 
                                                data-name="{{ $categorie->name }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    Aucune catégorie trouvée
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $categories->links() }}
                
            </div>

            
        </div>

        
    </div>

    <!-- Modal d'édition -->
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Modifier la catégorie</h3>
                        <form id="editCategoryForm" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="editCategoryId">
                            <div>
                                <label for="editName" class="block text-sm font-medium text-gray-700 mb-1">Nom de la catégorie</label>
                                <input type="text" name="name" id="editName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <div id="editNameError" class="text-red-500 text-xs mt-1 hidden"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="saveEditButton" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Sauvegarder
                </button>
                <button type="button" id="cancelEditButton" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Supprimer la catégorie</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Êtes-vous sûr de vouloir supprimer cette catégorie ? Cette action est irréversible.</p>
                            <p class="text-sm text-gray-900 font-medium mt-2">Catégorie : <span id="deleteCategoryName"></span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="confirmDeleteButton" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Supprimer
                </button>
                <button type="button" id="cancelDeleteButton" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Fichier de script catégories.js à ajouter dans votre vue -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM chargé');
        
        // Vérification visuelle des éléments importants
        if (!document.querySelector('meta[name="csrf-token"]')) {
            console.error('CSRF token manquant!');
        }
        
        // Délégation d'événements pour les clics
        document.addEventListener('click', function(e) {
            // --- Gestion des boutons d'édition ---
            if (e.target.closest('.edit-category')) {
                console.log('Bouton édition cliqué');
                const button = e.target.closest('.edit-category');
                handleEditClick(button);
            }
            
            // --- Gestion des boutons de suppression ---
            if (e.target.closest('.delete-category')) {
                console.log('Bouton suppression cliqué');
                const button = e.target.closest('.delete-category');
                handleDeleteClick(button);
            }
            
            // --- Gestion des boutons de modal ---
            if (e.target.closest('#cancelEditButton')) {
                hideModal('editModal');
            }
            
            if (e.target.closest('#saveEditButton')) {
                handleSaveEdit();
            }
            
            if (e.target.closest('#cancelDeleteButton')) {
                hideModal('deleteModal');
            }
            
            if (e.target.closest('#confirmDeleteButton')) {
                handleConfirmDelete();
            }
        });
        
        // Gestion du formulaire d'ajout
        const addForm = document.getElementById('categoryForm');
        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                e.preventDefault();
                handleAddCategory(this);
            });
        }
        
        // Gestion de la recherche
        const searchInput = document.getElementById('searchCategory');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                filterCategories(this.value);
            });
        }
        
        // Fonctions de gestion des événements
        function handleEditClick(button) {
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            
            const editCategoryId = document.getElementById('editCategoryId');
            const editName = document.getElementById('editName');
            
            if (editCategoryId && editName) {
                editCategoryId.value = id;
                editName.value = name;
                
                showModal('editModal');
            }
        }
        
        function handleDeleteClick(button) {
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const url = button.getAttribute('data-url');
            
            const deleteCategoryName = document.getElementById('deleteCategoryName');
            const confirmDeleteButton = document.getElementById('confirmDeleteButton');
            
            if (deleteCategoryName && confirmDeleteButton) {
                deleteCategoryName.textContent = name;
                confirmDeleteButton.setAttribute('data-id', id);
                confirmDeleteButton.setAttribute('data-url', url);
                
                showModal('deleteModal');
            }
        }
        
        function handleSaveEdit() {
            const id = document.getElementById('editCategoryId').value;
            const form = document.getElementById('editCategoryForm');
            
            if (!form) return;
            
            const formData = new FormData(form);
            const url = `${window.location.origin}/categories/${id}`;
            
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
                    hideModal('editModal');
                    showSuccessAlert(data.message, true);
                } else {
                    const editNameError = document.getElementById('editNameError');
                    if (data.errors && data.errors.name && editNameError) {
                        editNameError.textContent = data.errors.name[0];
                        editNameError.classList.remove('hidden');
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
        
        function handleConfirmDelete() {
            const button = document.getElementById('confirmDeleteButton');
            if (!button) return;
            
            const url = button.getAttribute('data-url');
            const id = button.getAttribute('data-id');
            
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
                hideModal('deleteModal');
                
                if (data.success) {
                    showSuccessAlert(data.message, true);
                } else {
                    showErrorAlert(data.message || 'Une erreur est survenue');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showErrorAlert('Une erreur est survenue lors de la communication avec le serveur');
            });
        }
        
        async function handleAddCategory(form) {
            try {
                const formData = new FormData(form);
                
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                
                if (!response.ok) {
                    throw new Error(`Erreur HTTP: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (data.success) {
                    form.reset();
                    showSuccessAlert(data.message, true);
                } else {
                    const nameError = document.getElementById('nameError');
                    if (data.errors && data.errors.name && nameError) {
                        nameError.textContent = data.errors.name[0];
                        nameError.classList.remove('hidden');
                    } else {
                        throw new Error(data.message || 'Une erreur est survenue');
                    }
                }
            } catch (error) {
                console.error('Erreur:', error);
                showErrorAlert(error.message);
            }
        }
        
        function filterCategories(searchValue) {
            searchValue = searchValue.toLowerCase();
            const rows = document.querySelectorAll('#categoriesTableBody tr');
            
            rows.forEach(row => {
                if (!row.hasAttribute('data-id')) return; // Ignore la ligne "Aucune catégorie trouvée"
                
                const categoryName = row.querySelector('td:first-child div').textContent.toLowerCase();
                
                if (categoryName.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        // Fonctions utilitaires
        function showModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
            }
        }
        
        function hideModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
            }
        }
        
        function showSuccessAlert(message, reload = false) {
            Swal.fire({
                icon: 'success',
                title: 'Succès!',
                text: message,
                timer: 2000,
                showConfirmButton: false,
                customClass: {
                    popup: 'rounded-xl',
                    title: 'text-navy-800',
                    content: 'text-gray-600'
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
                    title: 'text-navy-800'
                }
            });
        }
    });
    </script>

@endsection --}}



<!-- resources/views/articles/categorie-article.blade.php -->
@extends('layouts.app')

@section('title', 'Gestion des catégories')

@section('breadcrumb')
    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
    <a href="{{ route('articles.index') }}" class="hover:text-blue-700 transition-colors">Articles</a>
    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
    <span class="text-gray-700">Catégories</span>
@endsection

@section('page-title', 'Gestion des catégories')
@section('page-subtitle', 'Créer, modifier et supprimer des catégories d\'articles')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
        <!-- Panneau latéral (formulaire) -->
        <div class="order-2 lg:order-1 lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-5 sticky top-4">
                <h2 id="formTitle" class="text-lg font-bold text-navy-800 mb-4">
                    <i class="fas fa-plus-circle mr-2 text-blue-500"></i> <span id="formTitleText">Créer une catégorie</span>
                </h2>
                <form id="categoryForm" action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" id="categoryId" name="categoryId">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom de la catégorie</label>
                        <input type="text" name="name" id="name" placeholder="Entrez le nom de la catégorie" 
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

        <!-- Liste des catégories -->
        <div class="order-1 lg:order-2 lg:col-span-2 bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <h2 class="text-lg font-bold text-navy-800">Liste des catégories</h2>
                <div class="relative w-full sm:w-auto">
                    <input type="text" id="searchCategory" placeholder="Rechercher..." 
                           class="pl-9 pr-3 py-1.5 text-sm border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="categoriesTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nom
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Articles
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                Date de création
                            </th>
                            <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="categoriesTableBody">
                        @forelse ($categories as $categorie)
                            <tr class="hover:bg-gray-50 transition-colors" data-id="{{ $categorie->id }}">
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $categorie->name }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">{{ $categorie->articles_count }}</div>
                                </td>
                                <td class="px-4 py-3 hidden md:table-cell">
                                    <div class="text-sm text-gray-900">{{ $categorie->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end space-x-2">
                                        <button class="edit-category p-1.5 rounded-md hover:bg-blue-50 text-blue-600 transition-colors" 
                                                data-id="{{ $categorie->id }}" 
                                                data-name="{{ $categorie->name }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="delete-category p-1.5 rounded-md hover:bg-red-50 text-red-600 transition-colors" 
                                                data-id="{{ $categorie->id }}" 
                                                data-url="{{ route('categories.destroy', $categorie->id) }}" 
                                                data-name="{{ $categorie->name }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRow">
                                <td colspan="4" class="px-4 py-4 text-center text-gray-500">
                                    Aucune catégorie trouvée
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $categories->links() }}
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

{{-- @section('scripts') --}}

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
   
    document.addEventListener('DOMContentLoaded', function() {
        // Variables globales
        const categoryForm = document.getElementById('categoryForm');
        const formTitle = document.getElementById('formTitleText');
        const submitButton = document.getElementById('submitButton');
        const submitButtonText = document.getElementById('submitButtonText');
        const resetButton = document.getElementById('resetButton');
        const searchInput = document.getElementById('searchCategory');
        const nameInput = document.getElementById('name');
        const nameError = document.getElementById('nameError');
        const categoryIdInput = document.getElementById('categoryId');
        let isEditMode = false;
        let originalFormAction = categoryForm.action;
        
        // Initialisation
        setupEventListeners();
        
        // Configuration des écouteurs d'événements
        function setupEventListeners() {
            // Formulaire d'ajout/modification
            categoryForm.addEventListener('submit', handleFormSubmit);
            resetButton.addEventListener('click', resetForm);
            
            // Recherche
            searchInput.addEventListener('input', handleSearch);
            
            // Délégation d'événements pour les actions de tableau
            document.addEventListener('click', function(e) {
                const editButton = e.target.closest('.edit-category');
                if (editButton) {
                    handleEdit(editButton);
                }
                
                const deleteButton = e.target.closest('.delete-category');
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
            const formData = new FormData(categoryForm);
            
            // Déterminer l'URL et la méthode en fonction du mode (création ou édition)
            let url = originalFormAction;
            let method = 'POST';
            
            if (isEditMode) {
                const categoryId = categoryIdInput.value;
                url = `${window.location.origin}/categories/${categoryId}/update`;
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
            categoryIdInput.value = id;
            nameInput.value = name;
            
            // Changer l'apparence du formulaire
            formTitle.textContent = 'Modifier la catégorie';
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
                html: `Êtes-vous sûr de vouloir supprimer la catégorie <strong>${name}</strong> ?<br>Cette action est irréversible.`,
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
                                const rows = document.querySelectorAll('#categoriesTableBody tr[data-id]');
                                if (rows.length === 0) {
                                    const emptyRow = document.createElement('tr');
                                    emptyRow.id = 'emptyRow';
                                    emptyRow.innerHTML = `<td colspan="4" class="px-4 py-4 text-center text-gray-500">Aucune catégorie trouvée</td>`;
                                    document.getElementById('categoriesTableBody').appendChild(emptyRow);
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
            const rows = document.querySelectorAll('#categoriesTableBody tr[data-id]');
            let hasVisibleRows = false;
            
            rows.forEach(row => {
                const categoryName = row.querySelector('td:first-child div').textContent.toLowerCase();
                const shouldShow = categoryName.includes(searchTerm);
                
                row.style.display = shouldShow ? '' : 'none';
                if (shouldShow) hasVisibleRows = true;
            });
            
            // Afficher ou masquer la ligne "Aucune catégorie trouvée"
            const emptyRow = document.getElementById('emptyRow');
            if (emptyRow) {
                emptyRow.style.display = hasVisibleRows ? 'none' : '';
            } else if (!hasVisibleRows) {
                const newEmptyRow = document.createElement('tr');
                newEmptyRow.id = 'emptyRow';
                newEmptyRow.innerHTML = `<td colspan="4" class="px-4 py-4 text-center text-gray-500">Aucune catégorie trouvée</td>`;
                document.getElementById('categoriesTableBody').appendChild(newEmptyRow);
            }
        }
        
        function resetForm() {
            // Réinitialiser les valeurs
            categoryForm.reset();
            categoryIdInput.value = '';
            nameError.classList.add('hidden');
            
            // Réinitialiser l'apparence
            formTitle.textContent = 'Créer une catégorie';
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
{{-- @endsection --}}