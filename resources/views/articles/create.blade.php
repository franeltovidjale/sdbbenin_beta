{{-- @extends('layouts.app')

@section('title', 'Ajouter un Article')

@section('content')
<div class="container mx-auto px-4 py-1">
    <div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Ajouter un Article</h1>
        
        <form id="articleForm" action="{{ route('articles.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom de l'Article</label>
                    <input type="text" name="name" id="name" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="Entrez le nom de l'article" required>
                    <div id="nameError" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Catégorie</label>
                    <select name="category_id" id="category_id" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            required>
                        <option value="">Sélectionnez une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div id="categoryError" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="unit_price" class="block text-sm font-medium text-gray-700">Prix Unitaire</label>
                        <input type="number" name="unit_price" id="unit_price" step="0.01" min="0"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="0.00" required>
                        <div id="unitPriceError" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    
                    <div>
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Quantité en Stock</label>
                        <input type="number" name="stock_quantity" id="stock_quantity" min="0"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="0" required>
                        <div id="stockQuantityError" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('articles.index') }}" 
                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                    Annuler
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Créer l'Article
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('articleForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Réinitialiser les messages d'erreur
        ['name', 'category_id', 'unit_price', 'stock_quantity'].forEach(field => {
            const errorEl = document.getElementById(`${field}Error`);
            if (errorEl) errorEl.classList.add('hidden');
        });

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = "{{ route('articles.index') }}";
                });
            } else {
                // Afficher les erreurs de validation
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorEl = document.getElementById(`${field}Error`);
                        if (errorEl) {
                            errorEl.textContent = data.errors[field][0];
                            errorEl.classList.remove('hidden');
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: data.message || 'Une erreur est survenue'
                    });
                }
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Une erreur est survenue lors de la communication'
            });
        });
    });
});
</script>
@endsection --}}

@extends('layouts.app')

@section('title', 'Ajouter un Article')

@section('breadcrumb')
    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
    <a href="{{ route('articles.index') }}" class="hover:text-blue-700 transition-colors">Articles</a>
    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
    <span class="text-gray-700">Ajouter un Article</span>
@endsection

@section('page-title', 'Ajouter un Article')
@section('page-subtitle', 'Créer un nouvel article dans votre inventaire')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
    <!-- Panneau latéral (informations) -->
    <div class="order-2 lg:order-1 lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm p-5">
            <h2 class="text-lg font-bold text-navy-800 mb-4">
                <i class="fas fa-info-circle mr-2 text-blue-500"></i> Informations
            </h2>
            <div class="space-y-4 text-gray-600">
                <p>Remplissez le formulaire pour ajouter un nouvel article à votre inventaire.</p>
                <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
                    <h3 class="font-medium text-blue-800 mb-2">Conseils :</h3>
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        <li>Assurez-vous que le nom de l'article est unique</li>
                        <li>Sélectionnez la catégorie appropriée</li>
                        <li>Définissez un prix unitaire précis</li>
                        <li>Indiquez la quantité disponible en stock</li>
                    </ul>
                </div>
                <div class="flex justify-center mt-4">
                    <a href="{{ route('articles.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire d'ajout -->
    <div class="order-1 lg:order-2 lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-bold text-navy-800 mb-4">
                <i class="fas fa-plus-circle mr-2 text-blue-500"></i> Détails de l'article
            </h2>
            <form id="createArticleForm" class="space-y-4">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom du produit</label>
                    <input type="text" id="name" name="name" required
                           placeholder="Entrez le nom du produit"
                           class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    <div id="nameError" class="text-red-500 text-xs mt-1 hidden"></div>
                </div>
                
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                    <select id="category_id" name="category_id" required
                            class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div id="categoryError" class="text-red-500 text-xs mt-1 hidden"></div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire (€)</label>
                        <div class="relative">
                            <input type="number" id="unit_price" name="unit_price" required min="0" step="0.01"
                                   placeholder="0.00"
                                   class="form-input-focus w-full pl-4 pr-9 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <span class="absolute right-3 top-2 text-gray-500">€</span>
                        </div>
                        <div id="priceError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    
                    <div>
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantité en stock</label>
                        <input type="number" id="stock_quantity" name="stock_quantity" required min="0" step="1"
                               placeholder="0"
                               class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <div id="stockError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                </div>
                
                <div class="pt-4 border-t border-gray-200 mt-6">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('articles.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-times mr-2"></i> Annuler
                        </a>
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-save mr-2"></i> Enregistrer
                        </button>
                    </div>
                </div>
            </form>
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


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Éléments DOM
    const createForm = document.getElementById('createArticleForm');
    const nameInput = document.getElementById('name');
    const categoryInput = document.getElementById('category_id');
    const priceInput = document.getElementById('unit_price');
    const stockInput = document.getElementById('stock_quantity');
    
    // Réinitialiser les messages d'erreur
    function resetErrors() {
        document.querySelectorAll('.text-red-500').forEach(el => el.classList.add('hidden'));
    }
    
    // Soumettre le formulaire
    createForm.addEventListener('submit', function(e) {
        e.preventDefault();
        resetErrors();
        
        // Valider les champs
        let isValid = true;
        
        if (!nameInput.value.trim()) {
            document.getElementById('nameError').textContent = 'Le nom du produit est obligatoire';
            document.getElementById('nameError').classList.remove('hidden');
            isValid = false;
        }
        
        if (!categoryInput.value) {
            document.getElementById('categoryError').textContent = 'La catégorie est obligatoire';
            document.getElementById('categoryError').classList.remove('hidden');
            isValid = false;
        }
        
        if (!priceInput.value || parseFloat(priceInput.value) < 0) {
            document.getElementById('priceError').textContent = 'Le prix unitaire doit être positif';
            document.getElementById('priceError').classList.remove('hidden');
            isValid = false;
        }
        
        if (!stockInput.value || parseInt(stockInput.value) < 0) {
            document.getElementById('stockError').textContent = 'La quantité en stock doit être positive';
            document.getElementById('stockError').classList.remove('hidden');
            isValid = false;
        }
        
        if (!isValid) return;
        
        // Préparer les données
        const formData = new FormData(this);
        
        // Afficher un indicateur de chargement
        const submitButton = this.querySelector('button[type="submit"]');
        const originalContent = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Traitement...';
        submitButton.disabled = true;
        
        // Envoyer la requête
        fetch('{{ route('articles.store') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            // Restaurer le bouton
            submitButton.innerHTML = originalContent;
            submitButton.disabled = false;
            
            if (data.success) {
                Swal.fire({
                    title: 'Succès!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'rounded-xl',
                        title: 'text-navy-800',
                        htmlContainer: 'text-gray-600'
                    }
                }).then(() => {
                    // Rediriger vers la liste des articles
                    window.location.href = '{{ route('articles.index') }}';
                });
            } else {
                // Afficher les erreurs de validation
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorElement = document.getElementById(`${field.replace(/_/g, '')}Error`);
                        if (errorElement) {
                            errorElement.textContent = data.errors[field][0];
                            errorElement.classList.remove('hidden');
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Erreur',
                        text: data.message || 'Une erreur est survenue',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'rounded-xl',
                            title: 'text-navy-800',
                            htmlContainer: 'text-gray-600'
                        }
                    });
                }
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            submitButton.innerHTML = originalContent;
            submitButton.disabled = false;
            
            Swal.fire({
                title: 'Erreur',
                text: 'Une erreur est survenue lors de la communication avec le serveur',
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'rounded-xl',
                    title: 'text-navy-800',
                    htmlContainer: 'text-gray-600'
                }
            });
        });
    });
});
</script>
