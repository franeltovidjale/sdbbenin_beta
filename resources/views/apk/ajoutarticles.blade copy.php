
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Section à ajouter dans la balise <head> de votre page -->
<style>
    /* Styles pour l'aperçu des images */
    .image-preview-container {
        margin-top: 1rem;
        min-height: 100px;
    }
    
    .image-preview-wrapper {
        height: 150px;
        overflow: hidden;
        border-radius: 0.375rem;
        border: 1px solid #ddd;
        background-color: #f8f9fa;
        transition: all 0.2s ease;
    }
    
    .image-preview-wrapper:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .image-preview-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .image-remove-btn {
        top: 5px;
        right: 5px;
        opacity: 0.8;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        padding: 0;
        line-height: 24px;
        text-align: center;
        z-index: 10;
    }
    
    .image-remove-btn:hover {
        opacity: 1;
        transform: scale(1.1);
    }
    
    .custom-file-label.disabled {
        background-color: #e9ecef;
        cursor: not-allowed;
    }
    
    #imageCounter {
        display: inline-block;
        margin-left: 10px;
        font-size: 0.875rem;
        color: #6c757d;
    }
    </style>

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
                        <h1 class="m-0">Ajouter un article</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <form id="productForm" method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-8 col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Informations Générales</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Nom de l'article -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputName">Nom de l'article <span class="text-danger">*</span></label>
                                            <input name="name" type="text" id="inputName" class="form-control" placeholder="Nom de l'article">
                                            <span class="text-danger error-message" id="name-error"></span>
                                        </div>
                                    </div>

                                    <!-- Catégorie -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="productAttributes">Catégorie <span class="text-danger">*</span></label>
                                            <select class="form-control" id="productAttributes" name="category_id">
                                                <option value="" disabled selected>Sélectionner une catégorie</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-message" id="category_id-error"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Prix Achat -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="buy_price">Prix Achat <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                </div>
                                                <input name="buy_price" type="number" id="buy_price" class="form-control" placeholder="0.00">
                                            </div>
                                            <span class="text-danger error-message" id="buy_price-error"></span>
                                        </div>
                                    </div>

                                    <!-- Prix de vente -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputNormalPrice">Prix de vente <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-money-bill"></i></span>
                                                </div>
                                                <input name="normal_price" type="number" id="inputNormalPrice" class="form-control" placeholder="0.00">
                                            </div>
                                            <span class="text-danger error-message" id="normal_price-error"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Numéro de Série -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputNumberSerie">Numéro de Série</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                </div>
                                                <input name="number_serie" type="text" id="inputNumberSerie" class="form-control" placeholder="S/N">
                                            </div>
                                            <span class="text-danger error-message" id="number_serie-error"></span>
                                        </div>
                                    </div>

                                    <!-- Couleur -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="color">Couleur</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-palette"></i></span>
                                                </div>
                                                <input name="color" type="text" id="color" class="form-control" placeholder="Noir, Blanc, etc.">
                                            </div>
                                            <span class="text-danger error-message" id="color-error"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Mémoire Interne -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="memory">Mémoire Interne</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-memory"></i></span>
                                                </div>
                                                <input name="memory" type="text" id="memory" class="form-control" placeholder="32GB, 64GB, etc.">
                                            </div>
                                            <span class="text-danger error-message" id="memory-error"></span>
                                        </div>
                                    </div>

                                    <!-- Batterie -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="batterie">Batterie (%)</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-battery-half"></i></span>
                                                </div>
                                                <input name="batterie" type="number" id="batterie" class="form-control" placeholder="0-100" min="0" max="100">
                                            </div>
                                            <span class="text-danger error-message" id="batterie-error"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Quantité -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="quantite">Quantité <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-cubes"></i></span>
                                                </div>
                                                <input name="quantite" type="number" id="quantite" class="form-control" placeholder="0" min="0">
                                            </div>
                                            <span class="text-danger error-message" id="quantite-error"></span>
                                        </div>
                                    </div>

                                    <!-- Dealeur -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dealeur">Fournisseur</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                                </div>
                                                <input name="dealeur" type="text" id="dealeur" class="form-control" placeholder="Nom du fournisseur">
                                            </div>
                                            <span class="text-danger error-message" id="dealeur-error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-12">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Informations Complémentaires</h3>
                            </div>
                            <div class="card-body">
                                <!-- Panne -->
                                <div class="form-group">
                                    <label for="panne">Panne</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-tools"></i></span>
                                        </div>
                                        <input name="panne" type="text" id="panne" class="form-control" placeholder="Description de la panne">
                                    </div>
                                    <span class="text-danger error-message" id="panne-error"></span>
                                </div>

                                <!-- Technicien -->
                                <div class="form-group">
                                    <label for="technicien">Technicien</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
                                        </div>
                                        <input name="technicien" type="text" id="technicien" class="form-control" placeholder="Nom du technicien">
                                    </div>
                                    <span class="text-danger error-message" id="technicien-error"></span>
                                </div>

                                <!-- Note -->
                                <div class="form-group">
                                    <label for="note">Note</label>
                                    <textarea name="note" id="note" class="form-control" rows="3" placeholder="Informations supplémentaires..."></textarea>
                                    <span class="text-danger error-message" id="note-error"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Card pour les images -->
                        {{-- <div class="card">
                            <div class="card-header bg-info">
                                <h3 class="card-title">Images du produit</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="customFiles">Choisissez jusqu'à 5 images</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFiles" name="images[]" multiple accept="image/*" onchange="previewImages(event)">
                                        <label class="custom-file-label" for="customFiles">Sélectionner des images...</label>
                                    </div>
                                    <div class="text-muted mt-1"><small>Formats acceptés: JPG, PNG, GIF (max 2MB)</small></div>
                                    <span class="text-danger error-message" id="images-error"></span>
                                </div>
                                
                                <div class="image-preview-container">
                                    <div class="row" id="imagePreviewContainer"></div>
                                </div>
                            </div>
                        </div> --}}

                        <!-- Section à remplacer dans votre code HTML pour l'upload d'images -->
                        <div class="card">
                            <div class="card-header bg-info">
                                <h3 class="card-title">Images du produit</h3>
                                <span id="imageCounter" class="float-right">0/5 images</span>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="customFiles">Choisissez jusqu'à 5 images</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFiles" name="images[]" multiple accept="image/*" onchange="previewImages(event)">
                                        <label class="custom-file-label" for="customFiles">Sélectionner des images...</label>
                                    </div>
                                    <div class="text-muted mt-1"><small>Formats acceptés: JPG, PNG, GIF (max 2MB)</small></div>
                                    <span class="text-danger error-message" id="images-error"></span>
                                </div>
                                
                                <div class="image-preview-container">
                                    <div class="row" id="imagePreviewContainer">
                                        <!-- Les images sélectionnées seront affichées ici -->
                                    </div>
                                </div>
                                
                                <!-- Champs cachés pour la gestion des images -->
                                <input type="hidden" id="fileCount" name="fileCount" value="0">
                                <input type="hidden" id="remove_images" name="remove_images" value="[]">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Pour la page d'édition, voici comment afficher les images existantes -->
<!-- Ajoutez ce code dans la page d'édition, après la div "imagePreviewContainer" -->
<script>
    // Code à ajouter pour afficher les images existantes dans la page d'édition
    document.addEventListener('DOMContentLoaded', function() {
        // Cette fonction n'est applicable que sur la page d'édition
        if (document.querySelector('.edit-article-page')) {
            const imagePaths = @json($article->image_paths ?? []);
            const container = document.getElementById('imagePreviewContainer');
            
            imagePaths.forEach((path, index) => {
                const colDiv = document.createElement('div');
                colDiv.classList.add('col-6', 'col-md-4', 'mb-3', 'image-preview-item', 'existing-image');
                colDiv.dataset.index = index;  // Stocker l'index pour faciliter la suppression
                
                const imgContainer = document.createElement('div');
                imgContainer.classList.add('image-preview-wrapper', 'position-relative');
                
                const img = document.createElement('img');
                img.src = `/storage/${path}`; // Ajustez le chemin selon votre configuration
                img.classList.add('img-fluid', 'rounded', 'shadow-sm');
                img.alt = `Image ${index + 1}`;
                
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'position-absolute', 'image-remove-btn');
                removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                removeBtn.onclick = function() {
                    handleImageDeletion(colDiv, index);
                };
                
                imgContainer.appendChild(img);
                imgContainer.appendChild(removeBtn);
                colDiv.appendChild(imgContainer);
                container.appendChild(colDiv);
            });
            
            // Mettre à jour le compteur d'images
            updateImageCounter();
        }
    });
    </script>

                <div class="row mt-3 mb-5">
                    <div class="col-12">
                        <a href="{{ route('adminlistarticles') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-success float-right">
                            <i class="fas fa-save mr-1"></i> Créer l'article
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </div>

    @include('apk.components.footer')
</div>

<script>
 



function previewImages(event) {
    const files = event.target.files;
    const previewContainer = document.getElementById('imagePreviewContainer');
    const existingImagesCount = previewContainer.querySelectorAll('.image-preview-item').length;
    const maxImages = 5;

    // Vérifier si des fichiers ont été sélectionnés
    if (!files || files.length === 0) {
        return; // Sortir de la fonction si aucun fichier n'est sélectionné
    }

    if (files.length + existingImagesCount > maxImages) {
        Swal.fire({
            title: 'Limite atteinte!',
            text: 'Vous pouvez sélectionner jusqu\'à 5 images uniquement.',
            icon: 'warning'
        });
        event.target.value = '';
        return;
    }

    // Mise à jour du label de la sélection de fichiers
    const fileLabel = document.querySelector('.custom-file-label');
    if (fileLabel) {
        fileLabel.textContent = files.length > 1 ? `${files.length} fichiers sélectionnés` : files[0].name;
    }

    // Ne pas vider le conteneur - permettre l'accumulation des images
    // previewContainer.innerHTML = '';
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        if (!file || !file.type || !file.type.startsWith('image/')) continue;

        const reader = new FileReader();
        reader.onload = function(e) {
            const colDiv = document.createElement('div');
            colDiv.classList.add('col-6', 'col-md-4', 'mb-3', 'image-preview-item');

            const imgContainer = document.createElement('div');
            imgContainer.classList.add('image-preview-wrapper', 'position-relative');
            
            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('img-fluid', 'rounded', 'shadow-sm');
            img.alt = `Image ${existingImagesCount + i + 1}`;
            
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'position-absolute', 'image-remove-btn');
            removeBtn.innerHTML = '<i class="fas fa-times"></i>';
            removeBtn.onclick = function() {
                colDiv.remove();
                updateImageCounter();
            };
            
            imgContainer.appendChild(img);
            imgContainer.appendChild(removeBtn);
            colDiv.appendChild(imgContainer);
            previewContainer.appendChild(colDiv);
            
            updateImageCounter();
        };
        reader.readAsDataURL(file);
    }
    
    // Réinitialiser l'input file pour permettre la sélection répétée du même fichier
    // Mais d'abord, nous devons stocker les fichiers quelque part
    storeFilesInFormData(files);
    
    // Maintenant, on peut réinitialiser la valeur de l'input
    event.target.value = '';
}

// Fonction pour stocker les fichiers dans FormData
let selectedFiles = []; // Variable globale pour conserver les fichiers sélectionnés

function storeFilesInFormData(newFiles) {
    // Ajouter les nouveaux fichiers à notre tableau
    for (let i = 0; i < newFiles.length; i++) {
        selectedFiles.push(newFiles[i]);
    }
    
    // Mettre à jour un champ caché pour indiquer combien de fichiers nous avons
    const fileCountInput = document.getElementById('fileCount');
    if (fileCountInput) {
        fileCountInput.value = selectedFiles.length;
    }
}

// Mettre à jour le compteur d'images et vérifier les limites
function updateImageCounter() {
    const count = document.querySelectorAll('.image-preview-item').length;
    const counterElement = document.getElementById('imageCounter');
    if (counterElement) {
        counterElement.textContent = `${count}/5 images`;
    }
    
    // Désactiver l'input si la limite est atteinte
    const fileInput = document.getElementById('customFiles');
    if (fileInput) {
        fileInput.disabled = count >= 5;
        
        // Mettre à jour le style du label
        const fileLabel = document.querySelector('.custom-file-label');
        if (fileLabel) {
            if (count >= 5) {
                fileLabel.classList.add('disabled');
                fileLabel.textContent = 'Limite de 5 images atteinte';
            } else {
                fileLabel.classList.remove('disabled');
                fileLabel.textContent = 'Sélectionner des images...';
            }
        }
    }
}

// Fonction pour préparer le formulaire avant l'envoi
function prepareFormForSubmission() {
    // Créer un nouvel objet FormData à partir du formulaire
    const form = document.getElementById('productForm');
    const formData = new FormData(form);
    
    // Ajouter tous les fichiers stockés
    for (let i = 0; i < selectedFiles.length; i++) {
        formData.append('images[]', selectedFiles[i]);
    }
    
    return formData;
}

// Fonction pour gérer la suppression d'une image existante
function handleImageDeletion(imageElement, imageIndex) {
    const removeImagesInput = document.getElementById('remove_images');
    let removeIndexes = [];
    
    if (removeImagesInput.value) {
        removeIndexes = JSON.parse(removeImagesInput.value);
    }
    
    removeIndexes.push(imageIndex);
    removeImagesInput.value = JSON.stringify(removeIndexes);
    
    // Masquer visuellement l'image
    imageElement.closest('.image-preview-item').remove();
    
    // Mettre à jour le compteur
    updateImageCounter();
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialiser le compteur d'images
    updateImageCounter();
    
    // Ajouter un champ caché pour le nombre de fichiers si pas déjà présent
    if (!document.getElementById('fileCount')) {
        const fileCountInput = document.createElement('input');
        fileCountInput.type = 'hidden';
        fileCountInput.id = 'fileCount';
        fileCountInput.name = 'fileCount';
        fileCountInput.value = '0';
        document.getElementById('productForm').appendChild(fileCountInput);
    }
    
    // Ajouter un champ caché pour les indices d'images à supprimer
    if (!document.getElementById('remove_images')) {
        const removeImagesInput = document.createElement('input');
        removeImagesInput.type = 'hidden';
        removeImagesInput.id = 'remove_images';
        removeImagesInput.name = 'remove_images';
        removeImagesInput.value = '[]';
        document.getElementById('productForm').appendChild(removeImagesInput);
    }
    
    // Configurer l'écouteur d'événements sur le formulaire
    const productForm = document.getElementById('productForm');
    if (productForm) {
        productForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            clearErrors();
            
            // Utiliser notre fonction personnalisée pour préparer les données
            const formData = prepareFormForSubmission();
            
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
                            // Gestion spéciale pour les erreurs d'images
                            if (field === 'images' || field.startsWith('images.')) {
                                displayImageError(data.errors[field][0]);
                            } else {
                                const errorElement = document.getElementById(`${field}-error`);
                                const inputElement = document.querySelector(`[name="${field}"]`);
                                
                                if (errorElement && inputElement) {
                                    errorElement.textContent = data.errors[field][0];
                                    errorElement.style.display = 'block';
                                    inputElement.classList.add('is-invalid');
                                }
                            }
                        });
                        
                        Swal.fire({
                            title: 'Erreur de validation',
                            text: 'Veuillez corriger les erreurs dans le formulaire',
                            icon: 'error'
                        });
                    } else {
                        Swal.fire({
                            title: 'Erreur',
                            text: data.message || 'Une erreur est survenue',
                            icon: 'error'
                        });
                    }
                    return;
                }

                Swal.fire({
                    title: 'Succès!',
                    text: data.message || 'Article créé avec succès',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Redirection
                    const redirectUrl = document.querySelector('input[name="redirect_url"]');
                    if (redirectUrl && redirectUrl.value) {
                        window.location.href = redirectUrl.value;
                    } else {
                        const backLink = document.querySelector('a[href*="adminlistarticles"]');
                        if (backLink) {
                            window.location.href = backLink.getAttribute('href');
                        } else {
                            // Fallback si aucun lien n'est trouvé
                            window.location.href = '/admin/articles';
                        }
                    }
                });

            } catch (error) {
                console.error('Erreur:', error);
                Swal.fire({
                    title: 'Erreur!',
                    text: 'Une erreur est survenue lors de la création de l\'article',
                    icon: 'error'
                });
            }
        });
    }
    
    // Initialiser les gestionnaires d'événements pour les images existantes
    document.querySelectorAll('.existing-image .image-remove-btn').forEach(button => {
        button.addEventListener('click', function() {
            const imageElement = this.closest('.image-preview-item');
            const imageIndex = imageElement.dataset.index;
            if (imageIndex !== undefined) {
                handleImageDeletion(imageElement, parseInt(imageIndex));
            }
        });
    });
});

function displayImageError(error) {
    const errorContainer = document.getElementById('images-error');
    if (errorContainer) {
        errorContainer.textContent = error;
        errorContainer.style.display = 'block';
    }
    
    // Ajoutez une classe d'erreur au conteneur de fichier
    const fileInput = document.getElementById('customFiles');
    if (fileInput) {
        fileInput.classList.add('is-invalid');
    }
}

function clearErrors() {
    // Masquer tous les messages d'erreur
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
    });
    
    // Retirer la classe is-invalid de tous les champs
    const formInputs = document.querySelectorAll('.form-control, .custom-file-input');
    formInputs.forEach(input => {
        input.classList.remove('is-invalid');
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
}

.card-header {
    border-top-left-radius: 0.5rem !important;
    border-top-right-radius: 0.5rem !important;
    font-weight: 600;
}

/* Styles pour les inputs */
.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.input-group-text {
    background-color: #f8f9fa;
}

/* Styles pour les images */
#imagePreviewContainer {
    margin-top: 1rem;
}

.image-preview-wrapper {
    height: 150px;
    overflow: hidden;
    border-radius: 0.375rem;
    border: 1px solid #ddd;
    background-color: #f8f9fa;
}

.image-preview-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-remove-btn {
    top: 5px;
    right: 5px;
    opacity: 0.8;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    padding: 0;
    line-height: 24px;
    text-align: center;
}

.image-remove-btn:hover {
    opacity: 1;
}

/* Styles d'erreur */
.error-message {
    display: none;
    margin-top: 0.25rem;
    font-size: 0.875rem;
}

.form-control.is-invalid {
    border-color: #dc3545;
    padding-right: calc(1.5em + 0.75rem);
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.form-control.is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.custom-file-input.is-invalid ~ .custom-file-label {
    border-color: #dc3545;
}

/* Suppression des flèches des inputs numériques */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}

/* Champs obligatoires */
.form-group label span.text-danger {
    font-weight: bold;
}

/* Responsive design */
@media (max-width: 767.98px) {
    .image-preview-wrapper {
        height: 120px;
    }
    
    .card-title {
        font-size: 1.1rem;
    }
    
    .btn {
        padding: 0.375rem 0.75rem;
    }
}

/* Animation de hover pour les cartes */
.card:hover {
    transform: translateY(-3px);
    transition: transform 0.3s ease;
}

/* Style du bouton d'enregistrement */
.btn-success {
    background-color: #28a745;
    border-color: #28a745;
    padding: 0.5rem 1.5rem;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
}



</style>
</body>
</html>