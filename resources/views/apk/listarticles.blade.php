
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste des articles</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<style>
/* Styles généraux */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Cartes et Conteneurs */
.card {
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid rgba(0,0,0,.125);
}

/* Conteneur responsif du tableau */
.table-responsive-custom {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    margin-bottom: 1rem;
    border: 1px solid #ddd;
    border-radius: 5px;
}

/* Tableau principal */
.table-responsive-custom table {
    width: 100%;
    min-width: 1000px;
    border-collapse: collapse;
}

/* Entête du tableau sticky */
.projects thead tr {
    position: sticky;
    top: 0;
    background: #f4f6f9;
    z-index: 2;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.projects th {
    font-weight: 600;
    color: #495057;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
}

/* Style des colonnes */
.projects th,
.projects td {
    padding: 12px 15px;
    border: 1px solid #e9ecef;
    vertical-align: middle;
}

/* Première colonne sticky */
.projects th:first-child,
.projects td:first-child {
    position: sticky;
    left: 0;
    background: inherit;
    z-index: 1;
}

/* Styles pour les lignes du tableau */
.projects tr:nth-child(even) {
    background-color: #f8f9fa;
}

.projects tr:hover {
    background-color: #f1f3f5;
}

/* Styles pour les badges */
.price-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    display: inline-block;
    font-weight: 600;
    font-size: 0.875rem;
}

.bg-info {
    background-color: #17a2b8;
    color: white;
}

.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    display: inline-block;
    font-weight: 600;
    font-size: 0.875rem;
}

.badge-success {
    background-color: #28a745;
    color: white;
}

.badge-danger {
    background-color: #dc3545;
    color: white;
}

/* Boutons d'action */
.project-actions .btn-sm {
    padding: 0.25rem 0.5rem;
    border-radius: 0.2rem;
    transition: all 0.2s;
    margin: 0.125rem;
}

.project-actions .btn-sm:hover {
    transform: translateY(-2px);
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0069d9;
    border-color: #0062cc;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
}

.btn-info {
    background-color: #17a2b8;
    border-color: #17a2b8;
}

.btn-info:hover {
    background-color: #138496;
    border-color: #117a8b;
}

/* Styles de recherche */
.search-container {
    position: relative;
    max-width: 300px;
}

.search-container .form-control {
    padding-left: 2.5rem;
    border-radius: 1.5rem;
    transition: all 0.3s;
}

.search-container .form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    border-color: #80bdff;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

/* Styles pour miniatures d'images */
.article-thumbnail {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
    cursor: pointer;
    border: 2px solid #ddd;
    transition: all 0.2s;
}

.article-thumbnail:hover {
    transform: scale(1.1);
    border-color: #007bff;
}

.thumbnail-container {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
    align-items: center;
}

.image-count-badge {
    background-color: #6c757d;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    margin-left: 5px;
}

/* Gallery modal styles */
.image-gallery-modal .modal-body {
    padding: 0;
}

.gallery-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 15px;
}

.gallery-item {
    flex: 0 0 calc(33.333% - 10px);
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.gallery-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-item:hover img {
    transform: scale(1.05);
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 1rem;
    padding: 0;
    list-style: none;
}

.page-item {
    margin: 0 0.25rem;
}

.page-link {
    padding: 0.5rem 0.75rem;
    border-radius: 0.25rem;
    border: 1px solid #dee2e6;
    color: #007bff;
    background-color: #fff;
    transition: all 0.2s;
}

.page-link:hover {
    background-color: #e9ecef;
}

.page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.page-item.disabled .page-link {
    color: #6c757d;
    background-color: #fff;
    cursor: not-allowed;
}

/* Mode mobile (moins de colonnes visibles) */
@media screen and (max-width: 768px) {
    .projects th,
    .projects td {
        min-width: 120px;
    }

    /* Réorganisation des colonnes prioritaires */
    .mobile-hide {
        display: none;
    }

    /* Actions en colonne pour mieux s'afficher */
    .project-actions {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .project-actions .btn-sm {
        width: 100%;
        text-align: center;
    }
    
    /* Style de la barre de recherche en responsive */
    .search-container {
        width: 100%;
        max-width: none;
        margin-bottom: 10px;
    }
    
    .card-header .d-flex {
        flex-direction: column;
    }
    
    .card-header .d-flex .d-flex {
        width: 100%;
        justify-content: space-between !important;
    }
}

/* Ultra petit écran (téléphone) */
@media screen and (max-width: 480px) {
    .projects th,
    .projects td {
        font-size: 0.8rem;
        padding: 8px;
    }

    /* Réduction des boutons */
    .project-actions .btn-sm {
        font-size: 0.7rem;
        padding: 3px;
    }
    
    /* Ajuster la taille de la miniature */
    .article-thumbnail {
        width: 40px;
        height: 40px;
    }
}
</style>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('apk.components.topbar')
        @include('apk.components.navbar')
        
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Liste des articles</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Tableau de bord</a></li>
                                <li class="breadcrumb-item active">Articles</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title">
                                <i class="fas fa-box mr-2"></i> Articles
                                <span class="badge bg-secondary ml-2">{{ $articles->total() }}</span>
                            </h3>
                            <div class="d-flex align-items-center">
                                <div class="search-container mr-3">
                                    <i class="fas fa-search search-icon"></i>
                                    <input type="text" 
                                           id="searchArticle" 
                                           class="form-control" 
                                           placeholder="Rechercher...">
                                </div>
                                <a href="{{ route('ajoutarticle') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Ajouter 
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0 table-responsive-custom">
                        <table class="table table-striped projects">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 30%">Article</th>
                                    <th style="width: 15%" class="mobile-hide">Prix d'achat</th>
                                    <th style="width: 15%">Prix de vente</th>
                                    <th style="width: 10%">Catégorie</th>
                                    <th style="width: 10%">Stock</th>
                                    <th style="width: 10%" class="mobile-hide">Num. Série</th>
                                    <th style="width: 10%" class="mobile-hide">Images</th>
                                    <th style="width: 15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($articles as $article)
                                <tr>
                                    <td>{{ $articles->firstItem() + $loop->index }}</td>
                                    <td>
                                        <a href="{{ route('articles.show', $article->id) }}" class="text-bold">
                                            {{ strtoupper($article->name) }}
                                        </a>
                                        <br>
                                        <small class="text-muted">
                                            Créé le {{ $article->created_at->format('d.m.Y') }}
                                        </small>
                                        <br>
                                        @if($article->color)
                                            <span class="badge badge-light">{{ $article->color }}</span>
                                        @endif
                                        @if($article->memory)
                                            <span class="badge badge-light">{{ $article->memory }}</span>
                                        @endif
                                    </td>
                                    <td class="mobile-hide">
                                        <span class="price-badge bg-secondary">{{ number_format($article->buy_price, 0) }} FCFA</span>
                                    </td>
                                    <td>
                                        <span class="price-badge bg-info">{{ number_format($article->normal_price, 0) }} FCFA</span>
                                    </td>
                                    <td>{{ $article->category->name }}</td>
                                    <td>
                                        <span class="status-badge badge-{{ $article->quantite > 0 ? 'success' : 'danger' }}">
                                            {{ $article->quantite }}
                                        </span>
                                    </td>
                                    <td class="mobile-hide">
                                        {{ $article->number_serie ?: '-' }}
                                    </td>
                                    <td class="mobile-hide">
                                        <div class="thumbnail-container">
                                            @if(!empty($article->image_paths) && is_array($article->image_paths) && count($article->image_paths) > 0)
                                                <img src="{{ Storage::url($article->image_paths[0]) }}" 
                                                    class="article-thumbnail" 
                                                    alt="{{ $article->name }}"
                                                    onclick="showGallery({{ json_encode($article->image_paths) }}, '{{ $article->name }}')">
                                                
                                                @if(count($article->image_paths) > 1)
                                                    <span class="image-count-badge">{{ count($article->image_paths) }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted"><i class="fas fa-image"></i> Aucune</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="project-actions d-flex">
                                        <a href="{{ route('articles.show', $article->id) }}" class="btn btn-info btn-sm m-1" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->is_admin)
                                        <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-primary btn-sm m-1" title="Modifier">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('articles.destroy', $article->id) }}" method="POST" class="d-inline delete-article-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm m-1" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Aucun article trouvé</p>
                                            <a href="{{ route('ajoutarticle') }}" class="btn btn-primary btn-sm mt-2">
                                                <i class="fas fa-plus"></i> Ajouter un article
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        {{ $articles->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </section>
        </div>

        @include('apk.components.footer')
    </div>

    <!-- Modal pour la gallerie d'images -->
    <div class="modal fade image-gallery-modal" id="imageGalleryModal" tabindex="-1" aria-labelledby="imageGalleryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageGalleryModalLabel">Images de l'article</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="gallery-container" id="modalGalleryContainer">
                        <!-- Les images seront chargées ici dynamiquement -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchArticle');
    const tableBody = document.querySelector('.projects tbody');
    const paginationContainer = document.querySelector('.card-footer');
    let currentPage = 1;
    let debounceTimer;

    // Gestionnaire de recherche
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        currentPage = 1;
        debounceTimer = setTimeout(() => {
            const searchTerm = this.value.trim().toLowerCase();
            searchArticles(searchTerm, currentPage);
        }, 300);
    });

    // Gestionnaire de pagination
    document.addEventListener('click', function(e) {
        if (e.target.matches('.pagination a')) {
            e.preventDefault();
            const href = e.target.getAttribute('href');
            const page = new URL(href).searchParams.get('page');
            currentPage = page;
            searchArticles(searchInput.value.trim().toLowerCase(), page);
        }
    });

    // Gestionnaire de suppression d'articles
    attachDeleteListeners();

    async function searchArticles(searchTerm, page = 1) {
        try {
            const response = await fetch(`/articles/search?term=${encodeURIComponent(searchTerm)}&page=${page}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                updateTable(data.articles);
                updatePagination(data.pagination);
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Erreur de recherche:', error);
        }
    }

    function updateTable(articles) {
        tableBody.innerHTML = '';

        if (articles.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center">
                        <div class="py-4">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun article trouvé</p>
                            <a href="{{ route('ajoutarticle') }}" class="btn btn-primary btn-sm mt-2">
                                <i class="fas fa-plus"></i> Ajouter un article
                            </a>
                        </div>
                    </td>
                </tr>`;
            return;
        }

        articles.forEach((article, index) => {
            const createdAt = new Date(article.created_at).toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
            
            // Gestion des images
            let imageHtml = `<span class="text-muted"><i class="fas fa-image"></i> Aucune</span>`;
            if (article.image_paths && article.image_paths.length > 0) {
                imageHtml = `
                    <div class="thumbnail-container">
                        <img src="/storage/${article.image_paths[0]}" 
                            class="article-thumbnail" 
                            alt="${article.name}"
                            onclick="showGallery(${JSON.stringify(article.image_paths)}, '${article.name}')">
                        
                        ${article.image_paths.length > 1 ? 
                            `<span class="image-count-badge">${article.image_paths.length}</span>` : ''}
                    </div>`;
            }
            
            // Badges pour les caractéristiques supplémentaires
            let badgesHtml = '';
            if (article.color) {
                badgesHtml += `<span class="badge badge-light">${article.color}</span> `;
            }
            if (article.memory) {
                badgesHtml += `<span class="badge badge-light">${article.memory}</span>`;
            }

            tableBody.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        <a href="/articles/${article.id}" class="text-bold">${article.name.toUpperCase()}</a>
                        <br>
                        <small class="text-muted">Créé le ${createdAt}</small>
                        <br>
                        ${badgesHtml}
                    </td>
                    <td class="mobile-hide">
                        <span class="price-badge bg-secondary">${Number(article.buy_price).toLocaleString('fr-FR')} FCFA</span>
                    </td>
                    <td>
                        <span class="price-badge bg-info">${Number(article.normal_price).toLocaleString('fr-FR')} FCFA</span>
                    </td>
                    <td>${article.category.name}</td>
                    <td>
                        <span class="status-badge badge-${article.quantite > 0 ? 'success' : 'danger'}">
                            ${article.quantite}
                        </span>
                    </td>
                    <td class="mobile-hide">
                        ${article.number_serie || '-'}
                    </td>
                    <td class="mobile-hide">
                        ${imageHtml}
                    </td>
                    <td class="project-actions d-flex">
                        <a href="/articles/${article.id}" class="btn btn-info btn-sm m-1" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="/articles/${article.id}/edit" class="btn btn-primary btn-sm m-1" title="Modifier">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <form action="/articles/${article.id}" method="POST" class="d-inline delete-article-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm m-1" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>`;
        });

        attachDeleteListeners();
    }

    function updatePagination(paginationData) {
        if (paginationData.links) {
            paginationContainer.innerHTML = paginationData.links;
        } else {
            paginationContainer.innerHTML = '';
        }
    }

    function attachDeleteListeners() {
        document.querySelectorAll('.delete-article-form').forEach(form => {
            form.removeEventListener('submit', handleDelete);
            form.addEventListener('submit', handleDelete);
        });
    }
});

async function handleDelete(e) {
    e.preventDefault();
    
    const result = await Swal.fire({
        title: 'Êtes-vous sûr?',
        text: "Cette action est irréversible!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, supprimer!',
        cancelButtonText: 'Annuler'
    });

    if (result.isConfirmed) {
        try {
            const response = await fetch(this.action, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire(
                    'Supprimé!',
                    data.message,
                    'success'
                ).then(() => {
                    this.closest('tr').remove();
                });
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            Swal.fire(
                'Erreur!',
                error.message || 'Une erreur est survenue lors de la suppression',
                'error'
            );
        }
    }
}

// Fonction pour afficher la galerie d'images
function showGallery(images, articleName) {
    const modalTitle = document.getElementById('imageGalleryModalLabel');
    const galleryContainer = document.getElementById('modalGalleryContainer');
    
    // Mettre à jour le titre
    modalTitle.textContent = `Images de ${articleName}`;
    
    // Vider le conteneur
    galleryContainer.innerHTML = '';
    
    // Ajouter les images
    images.forEach(path => {
        const galleryItem = document.createElement('div');
        galleryItem.className = 'gallery-item';
        
        const img = document.createElement('img');
        img.src = `/storage/${path}`;
        img.alt = articleName;
        
        galleryItem.appendChild(img);
        galleryContainer.appendChild(galleryItem);
    });
    
    // Afficher la modal
    $('#imageGalleryModal').modal('show');
}
</script>
</body>
</html>