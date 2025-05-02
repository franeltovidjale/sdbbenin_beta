

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $article->name }} - Détails</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    @include('apk.components.topbar')
    @include('apk.components.navbar')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Détails de l'article</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('adminlistarticles') }}">Articles</a></li>
                            <li class="breadcrumb-item active">Détails</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="row">
                <!-- Images et galerie -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-info">
                            <h3 class="card-title">
                                <i class="fas fa-images mr-2"></i> Images
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            @if(isset($article->image_paths) && is_array($article->image_paths) && count($article->image_paths) > 0)
                                <div class="image-gallery">
                                    <div class="main-image-container">
                                        <img id="mainImage" src="{{ Storage::url($article->image_paths[0]) }}" class="img-fluid main-preview" alt="{{ $article->name }}">
                                    </div>
                                    
                                    @if(count($article->image_paths) > 1)
                                        <div class="thumbnails-container">
                                            @foreach($article->image_paths as $index => $path)
                                                <div class="thumbnail-item {{ $index === 0 ? 'active' : '' }}" onclick="changeMainImage('{{ Storage::url($path) }}', this)">
                                                    <img src="{{ Storage::url($path) }}" class="img-thumbnail" alt="Miniature {{ $index + 1 }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="no-image-container">
                                    <div class="text-center py-5">
                                        <i class="fas fa-image fa-4x text-muted mb-3"></i>
                                        <p class="text-muted">Aucune image disponible pour cet article</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Informations de l'article -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-2"></i> Informations générales
                            </h3>
                        </div>
                        <div class="card-body">
                            <h2 class="article-title">{{ strtoupper($article->name) }}</h2>
                            
                            <div class="article-info">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-tag"></i> Prix d'achat:</span>
                                            <span class="info-value">{{ number_format($article->buy_price, 0) }} FCFA</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-money-bill"></i> Prix de vente:</span>
                                            <span class="info-value price-badge bg-info">{{ number_format($article->normal_price, 0) }} FCFA</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-layer-group"></i> Catégorie:</span>
                                            <span class="info-value">{{ $article->category->name }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-cubes"></i> Stock:</span>
                                            <span class="info-value status-badge badge-{{ $article->quantite > 0 ? 'success' : 'danger' }}">
                                                {{ $article->quantite }} unité(s)
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($article->number_serie || $article->color || $article->memory)
                                <div class="specifications mb-3">
                                    <h5><i class="fas fa-cogs"></i> Spécifications</h5>
                                    <div class="row">
                                        @if($article->number_serie)
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <span class="info-label"><i class="fas fa-barcode"></i> Numéro de série:</span>
                                                <span class="info-value">{{ $article->number_serie }}</span>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($article->color)
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <span class="info-label"><i class="fas fa-palette"></i> Couleur:</span>
                                                <span class="info-value">{{ $article->color }}</span>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($article->memory)
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <span class="info-label"><i class="fas fa-memory"></i> Mémoire:</span>
                                                <span class="info-value">{{ $article->memory }}</span>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($article->batterie)
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <span class="info-label"><i class="fas fa-battery-half"></i> Batterie:</span>
                                                <span class="info-value">{{ $article->batterie }}%</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                
                                @if($article->panne || $article->technicien)
                                <div class="technical-info mb-3">
                                    <h5><i class="fas fa-tools"></i> Informations techniques</h5>
                                    <div class="row">
                                        @if($article->panne)
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <span class="info-label"><i class="fas fa-exclamation-triangle"></i> Panne:</span>
                                                <span class="info-value">{{ $article->panne }}</span>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($article->technicien)
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <span class="info-label"><i class="fas fa-user-cog"></i> Technicien:</span>
                                                <span class="info-value">{{ $article->technicien }}</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                
                                @if($article->dealeur)
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="info-item">
                                            <span class="info-label"><i class="fas fa-user-tie"></i> Fournisseur:</span>
                                            <span class="info-value">{{ $article->dealeur }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                @if($article->note)
                                <div class="note mb-3">
                                    <h5><i class="fas fa-sticky-note"></i> Notes</h5>
                                    <div class="note-content p-2 bg-light rounded">
                                        {{ $article->note }}
                                    </div>
                                </div>
                                @endif
                                
                                <div class="metadata mt-3 pt-3 border-top">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt"></i> Créé le: {{ $article->created_at->format('d/m/Y à H:i') }}
                                        @if($article->updated_at && $article->updated_at->ne($article->created_at))
                                        <br>
                                        <i class="fas fa-edit"></i> Dernière modification: {{ $article->updated_at->format('d/m/Y à H:i') }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body d-flex justify-content-between">
                            <a href="{{ route('adminlistarticles') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
                            </a>
                            <div>
                                @if(auth()->user()->is_admin)
                                <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-primary mx-2">
                                    <i class="fas fa-edit mr-1"></i> Modifier
                                </a>
                                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                    <i class="fas fa-trash mr-1"></i> Supprimer
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('apk.components.footer')
    
    <!-- Formulaire caché pour la suppression -->
    <form id="deleteForm" action="{{ route('articles.destroy', $article->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
// Fonction pour changer l'image principale
function changeMainImage(src, element) {
    document.getElementById('mainImage').src = src;
    
    // Supprimer la classe active de tous les thumbnails
    document.querySelectorAll('.thumbnail-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Ajouter la classe active au thumbnail cliqué
    element.classList.add('active');
}

// Fonction pour confirmer la suppression
function confirmDelete() {
    Swal.fire({
        title: 'Êtes-vous sûr?',
        text: "Cette action est irréversible!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer!',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm').submit();
        }
    });
}
</script>

<style>
/* Styles généraux */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.card {
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    margin-bottom: 1.5rem;
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-3px);
}

.card-header {
    border-top-left-radius: 0.5rem !important;
    border-top-right-radius: 0.5rem !important;
    font-weight: 600;
}

/* Styles d'information */
.article-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: #333;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 0.5rem;
}

.info-item {
    margin-bottom: 0.5rem;
}

.info-label {
    font-weight: 600;
    color: #6c757d;
    display: block;
}

.info-value {
    display: block;
    font-weight: 500;
}

.specifications h5,
.technical-info h5,
.note h5 {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    border-bottom: 1px dashed #dee2e6;
    padding-bottom: 0.25rem;
}

.note-content {
    border: 1px solid #dee2e6;
    padding: 1rem;
    background-color: #f8f9fa;
}

/* Styles pour la galerie d'images */
.image-gallery {
    display: flex;
    flex-direction: column;
}

.main-image-container {
    width: 100%;
    height: 300px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
}

.main-preview {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
}

.thumbnails-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 15px;
    background-color: #fff;
    border-top: 1px solid #dee2e6;
}

.thumbnail-item {
    width: 60px;
    height: 60px;
    border: 2px solid #ddd;
    border-radius: 4px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.2s ease;
}

.thumbnail-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.thumbnail-item.active {
    border-color: #007bff;
}

.thumbnail-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image-container {
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
}

/* Badges */
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
.btn {
    border-radius: 0.25rem;
    font-weight: 500;
    padding: 0.375rem 0.75rem;
    transition: all 0.2s;
}

.btn:hover {
    transform: translateY(-2px);
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

/* Responsive */
@media (max-width: 767.98px) {
    .main-image-container {
        height: 250px;
    }
    
    .thumbnail-item {
        width: 50px;
        height: 50px;
    }
    
    .article-title {
        font-size: 1.25rem;
    }
    
    .card-body.d-flex {
        flex-direction: column;
        gap: 10px;
    }
    
    .card-body.d-flex .btn {
        width: 100%;
        margin: 5px 0 !important;
    }
}

/* Ultra petit écran (téléphone) */
@media (max-width: 480px) {
    .main-image-container {
        height: 200px;
    }
    
    .info-label, .info-value {
        font-size: 0.9rem;
    }
    
    .specifications h5,
    .technical-info h5,
    .note h5 {
        font-size: 0.95rem;
    }
}
</style>
</body>
</html>
</html>
</html>