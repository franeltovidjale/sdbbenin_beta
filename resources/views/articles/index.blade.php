{{-- @extends('layouts.app')

@section('title', 'Liste des Articles')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gestion des Articles</h1>
        <a href="{{ route('articles.create') }}" class="btn btn-primary flex items-center">
            <i class="fas fa-plus mr-2"></i> Ajouter un Article
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg">
        <!-- Barre de recherche -->
        <div class="p-4 border-b border-gray-200">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Rechercher un article..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>

        <!-- Tableau des articles -->
        <div class="overflow-x-auto">
            <table class="w-full" id="articlesTable">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                        <th class="px-4 py-3 text-left">Nom</th>
                        <th class="px-4 py-3 text-left">Catégorie</th>
                        <th class="px-4 py-3 text-right">Prix Unitaire</th>
                        <th class="px-4 py-3 text-right">Stock</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="articlesTableBody">
                    @forelse($articles as $article)
                        <tr class="border-b hover:bg-gray-50 transition-colors" data-id="{{ $article->id }}">
                            <td class="px-4 py-3">{{ $article->name }}</td>
                            <td class="px-4 py-3">{{ $article->category->name }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format($article->unit_price, 2) }} €</td>
                            <td class="px-4 py-3 text-right">
                                <span class="{{ $article->in_stock ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $article->stock_quantity }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('articles.edit', $article) }}" 
                                       class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="text-red-500 hover:text-red-700 delete-article"
                                            data-id="{{ $article->id }}"
                                            data-name="{{ $article->name }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">
                                Aucun article trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4">
            {{ $articles->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Recherche en temps réel
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#articlesTableBody tr[data-id]');
        
        rows.forEach(row => {
            const name = row.cells[0].textContent.toLowerCase();
            const category = row.cells[1].textContent.toLowerCase();
            
            row.style.display = (name.includes(searchTerm) || category.includes(searchTerm)) 
                ? '' 
                : 'none';
        });
    });

    // Suppression d'article
    document.querySelectorAll('.delete-article').forEach(button => {
        button.addEventListener('click', function() {
            const articleId = this.getAttribute('data-id');
            const articleName = this.getAttribute('data-name');

            Swal.fire({
                title: 'Confirmer la suppression',
                html: `Voulez-vous vraiment supprimer l'article <strong>${articleName}</strong> ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/articles/${articleId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Supprimé!', 
                                data.message, 
                                'success'
                            ).then(() => {
                                // Supprimer la ligne du tableau
                                const row = document.querySelector(`tr[data-id="${articleId}"]`);
                                if (row) row.remove();
                            });
                        } else {
                            Swal.fire(
                                'Erreur', 
                                data.message, 
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        Swal.fire(
                            'Erreur', 
                            'Une erreur est survenue', 
                            'error'
                        );
                    });
                }
            });
        });
    });
});
</script>
@endsection --}}


@extends('layouts.app')

@section('title', 'Liste des Articles')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gestion des Articles</h1>
        <button id="addArticleBtn" class="btn btn-primary flex items-center">
            <i class="fas fa-plus mr-2"></i> Ajouter un Article
        </button>
    </div>

    <div class="bg-white shadow-md rounded-lg">
        <!-- Barre de recherche -->
        <div class="p-4 border-b border-gray-200">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Rechercher un article par nom, catégorie, prix ou stock..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>

        <!-- Tableau des articles -->
        <div class="overflow-x-auto">
            <table class="w-full" id="articlesTable">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                        <th class="px-4 py-3 text-left">Nom</th>
                        <th class="px-4 py-3 text-left">Catégorie</th>
                        <th class="px-4 py-3 text-right">Prix Unitaire</th>
                        <th class="px-4 py-3 text-right">Stock</th>
                        <th class="px-4 py-3 text-center">Statut</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="articlesTableBody">
                    @forelse($articles as $article)
                        <tr class="border-b hover:bg-gray-50 transition-colors" data-id="{{ $article->id }}">
                            <td class="px-4 py-3">{{ $article->name }}</td>
                            <td class="px-4 py-3">{{ $article->category->name }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format($article->unit_price, 2) }} €</td>
                            <td class="px-4 py-3 text-right">{{ $article->stock_quantity }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 rounded-full text-xs {{ $article->in_stock ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $article->in_stock ? 'En stock' : 'Rupture' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end space-x-2">
                                    <button class="text-blue-500 hover:text-blue-700 edit-article"
                                            data-id="{{ $article->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-500 hover:text-red-700 delete-article"
                                            data-id="{{ $article->id }}"
                                            data-name="{{ $article->name }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">
                                Aucun article trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4" id="pagination-container">
            {{ $articles->links() }}
        </div>
    </div>
</div>

<!-- Modal pour ajouter/éditer un article -->
<div id="articleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="border-b px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Ajouter un Article</h3>
            <button type="button" class="close-modal text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="articleForm" class="px-6 py-4">
            <input type="hidden" id="articleId" value="">
            @csrf
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom du produit</label>
                <input type="text" id="name" name="name" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <div class="text-red-500 text-sm mt-1 hidden" id="nameError"></div>
            </div>
            
            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                <select id="category_id" name="category_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Sélectionner une catégorie</option>
                    @foreach(App\Models\Categorie::all() as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <div class="text-red-500 text-sm mt-1 hidden" id="categoryError"></div>
            </div>
            
            <div class="mb-4">
                <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire (€)</label>
                <input type="number" id="unit_price" name="unit_price" required min="0" step="0.01"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <div class="text-red-500 text-sm mt-1 hidden" id="priceError"></div>
            </div>
            
            <div class="mb-4">
                <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantité en stock</label>
                <input type="number" id="stock_quantity" name="stock_quantity" required min="0" step="1"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <div class="text-red-500 text-sm mt-1 hidden" id="stockError"></div>
            </div>
            
            <div class="border-t pt-4 flex justify-end space-x-3">
                <button type="button" class="close-modal btn btn-secondary">
                    Annuler
                </button>
                <button type="submit" class="btn btn-primary">
                    <span id="submitButtonText">Enregistrer</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

{{-- @section('scripts') --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Éléments DOM
    const searchInput = document.getElementById('searchInput');
    const articleModal = document.getElementById('articleModal');
    const articleForm = document.getElementById('articleForm');
    const modalTitle = document.getElementById('modalTitle');
    const submitButtonText = document.getElementById('submitButtonText');
    const addArticleBtn = document.getElementById('addArticleBtn');
    const closeModalBtns = document.querySelectorAll('.close-modal');
    
    // Variables globales
    let isEditMode = false;
    let currentPage = 1;
    
    // Recherche AJAX
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const term = this.value.trim();
            fetchArticles(term);
        }, 500);
    });
    
    // Fonction pour récupérer les articles
    function fetchArticles(term = '', page = 1) {
        const url = `/articles/search?term=${encodeURIComponent(term)}&page=${page}`;
        
        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateArticlesTable(data.articles);
                updatePagination(data.pagination);
            } else {
                Swal.fire('Erreur', data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            Swal.fire('Erreur', 'Une erreur est survenue lors de la recherche', 'error');
        });
    }
    
    // Mise à jour du tableau d'articles
    function updateArticlesTable(articles) {
        const tableBody = document.getElementById('articlesTableBody');
        
        if (articles.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4 text-gray-500">
                        Aucun article trouvé
                    </td>
                </tr>
            `;
            return;
        }
        
        tableBody.innerHTML = '';
        
        articles.forEach(article => {
            const row = document.createElement('tr');
            row.className = 'border-b hover:bg-gray-50 transition-colors';
            row.setAttribute('data-id', article.id);
            
            row.innerHTML = `
                <td class="px-4 py-3">${article.name}</td>
                <td class="px-4 py-3">${article.category.name}</td>
                <td class="px-4 py-3 text-right">${parseFloat(article.unit_price).toFixed(2)} €</td>
                <td class="px-4 py-3 text-right">${article.stock_quantity}</td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-1 rounded-full text-xs ${article.in_stock ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                        ${article.in_stock ? 'En stock' : 'Rupture'}
                    </span>
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex justify-end space-x-2">
                        <button class="text-blue-500 hover:text-blue-700 edit-article"
                                data-id="${article.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-500 hover:text-red-700 delete-article"
                                data-id="${article.id}"
                                data-name="${article.name}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            
            tableBody.appendChild(row);
        });
        
        // Réattacher les event listeners
        attachEventListeners();
    }
    
    // Mise à jour de la pagination
    function updatePagination(pagination) {
        currentPage = pagination.current_page;
        
        if (pagination.total <= pagination.per_page) {
            document.getElementById('pagination-container').innerHTML = '';
            return;
        }
        
        let paginationHtml = '<div class="flex justify-center mt-4">';
        paginationHtml += '<nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">';
        
        // Bouton précédent
        paginationHtml += `
            <button class="pagination-btn relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 ${pagination.current_page === 1 ? 'opacity-50 cursor-not-allowed' : ''}"
                    data-page="${pagination.current_page - 1}" ${pagination.current_page === 1 ? 'disabled' : ''}>
                <span class="sr-only">Précédent</span>
                <i class="fas fa-chevron-left"></i>
            </button>
        `;
        
        // Pages numérotées
        for (let i = 1; i <= pagination.last_page; i++) {
            if (
                i === 1 || 
                i === pagination.last_page || 
                (i >= pagination.current_page - 1 && i <= pagination.current_page + 1)
            ) {
                paginationHtml += `
                    <button class="pagination-btn relative inline-flex items-center px-4 py-2 border border-gray-300 ${pagination.current_page === i ? 'bg-blue-50 border-blue-500 text-blue-600 z-10' : 'bg-white text-gray-500 hover:bg-gray-50'} text-sm font-medium"
                            data-page="${i}">
                        ${i}
                    </button>
                `;
            } else if (
                i === pagination.current_page - 2 || 
                i === pagination.current_page + 2
            ) {
                paginationHtml += `
                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                        ...
                    </span>
                `;
            }
        }
        
        // Bouton suivant
        paginationHtml += `
            <button class="pagination-btn relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 ${pagination.current_page === pagination.last_page ? 'opacity-50 cursor-not-allowed' : ''}"
                    data-page="${pagination.current_page + 1}" ${pagination.current_page === pagination.last_page ? 'disabled' : ''}>
                <span class="sr-only">Suivant</span>
                <i class="fas fa-chevron-right"></i>
            </button>
        `;
        
        paginationHtml += '</nav></div>';
        
        document.getElementById('pagination-container').innerHTML = paginationHtml;
        
        // Ajouter les event listeners aux boutons de pagination
        document.querySelectorAll('.pagination-btn').forEach(button => {
            if (!button.disabled) {
                button.addEventListener('click', function() {
                    const page = parseInt(this.getAttribute('data-page'));
                    fetchArticles(searchInput.value.trim(), page);
                });
            }
        });
    }
    
    // Montrer/cacher la modal
    function showModal(edit = false, articleId = null) {
        isEditMode = edit;
        modalTitle.textContent = edit ? 'Modifier l\'Article' : 'Ajouter un Article';
        submitButtonText.textContent = edit ? 'Mettre à jour' : 'Enregistrer';
        
        // Réinitialiser le formulaire
        articleForm.reset();
        document.getElementById('articleId').value = articleId || '';
        
        // Cacher tous les messages d'erreur
        document.querySelectorAll('.text-red-500').forEach(el => el.classList.add('hidden'));
        
        // Si en mode édition, charger les données de l'article
        if (edit && articleId) {
            const row = document.querySelector(`tr[data-id="${articleId}"]`);
            if (row) {
                document.getElementById('name').value = row.cells[0].textContent;
                
                // Trouver la catégorie correspondante
                const categoryName = row.cells[1].textContent;
                const categorySelect = document.getElementById('category_id');
                for (let i = 0; i < categorySelect.options.length; i++) {
                    if (categorySelect.options[i].textContent === categoryName) {
                        categorySelect.selectedIndex = i;
                        break;
                    }
                }
                
                document.getElementById('unit_price').value = parseFloat(row.cells[2].textContent).toFixed(2);
                document.getElementById('stock_quantity').value = row.cells[3].textContent;
            } else {
                // Si la ligne n'est pas trouvée, faire une requête AJAX pour obtenir les données
                fetch(`/articles/${articleId}/edit`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.article) {
                        document.getElementById('name').value = data.article.name;
                        document.getElementById('category_id').value = data.article.category_id;
                        document.getElementById('unit_price').value = parseFloat(data.article.unit_price).toFixed(2);
                        document.getElementById('stock_quantity').value = data.article.stock_quantity;
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    Swal.fire('Erreur', 'Impossible de charger les données de l\'article', 'error');
                });
            }
        }
        
        // Afficher la modal
        articleModal.classList.remove('hidden');
    }
    
    function hideModal() {
        articleModal.classList.add('hidden');
    }
    
    // Soumettre le formulaire
    articleForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const articleId = document.getElementById('articleId').value;
        
        let url = '/articles';
        let method = 'POST';
        
        if (isEditMode && articleId) {
            url = `/articles/${articleId}`;
            method = 'POST';
            formData.append('_method', 'PUT'); // Laravel method spoofing
        }
        
        fetch(url, {
            method: method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                hideModal();
                
                Swal.fire({
                    title: 'Succès!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Rafraîchir la liste des articles
                    fetchArticles(searchInput.value.trim(), currentPage);
                });
            } else {
                // Afficher les erreurs de validation
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorElement = document.getElementById(`${field}Error`);
                        if (errorElement) {
                            errorElement.textContent = data.errors[field][0];
                            errorElement.classList.remove('hidden');
                        }
                    });
                } else {
                    Swal.fire('Erreur', data.message, 'error');
                }
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            Swal.fire('Erreur', 'Une erreur est survenue', 'error');
        });
    });
    
    // Supprimer un article
    function deleteArticle(id, name) {
        Swal.fire({
            title: 'Confirmer la suppression',
            html: `Voulez-vous vraiment supprimer l'article <strong>${name}</strong> ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/articles/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Supprimé!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Rafraîchir la liste ou supprimer la ligne
                            fetchArticles(searchInput.value.trim(), currentPage);
                        });
                    } else {
                        Swal.fire('Erreur', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    Swal.fire('Erreur', 'Une erreur est survenue', 'error');
                });
            }
        });
    }
    
    // Attacher les event listeners
    function attachEventListeners() {
        // Boutons d'édition
        document.querySelectorAll('.edit-article').forEach(button => {
            button.addEventListener('click', function() {
                const articleId = this.getAttribute('data-id');
                showModal(true, articleId);
            });
        });
        
        // Boutons de suppression
        document.querySelectorAll('.delete-article').forEach(button => {
            button.addEventListener('click', function() {
                const articleId = this.getAttribute('data-id');
                const articleName = this.getAttribute('data-name');
                deleteArticle(articleId, articleName);
            });
        });
    }
    
    // Event listeners initiaux
    addArticleBtn.addEventListener('click', () => showModal(false));
    
    closeModalBtns.forEach(btn => {
        btn.addEventListener('click', hideModal);
    });
    
    // Cliquer en dehors de la modal pour la fermer
    articleModal.addEventListener('click', function(e) {
        if (e.target === this) {
            hideModal();
        }
    });
    
    // Charger les articles au démarrage
    fetchArticles();
    
    // Attacher les event listeners initiaux
    attachEventListeners();
});
</script>
{{-- @endsection --}}
