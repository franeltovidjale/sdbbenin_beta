@extends('layouts.app')

@section('title', 'Gestion des entrées/sorties d\'articles')

@section('breadcrumb')
    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
    <a href="{{ route('stock.movements.index') }}" class="text-gray-700">Entrées/Sorties d'articles</a>
@endsection

@section('page-title', 'Entrées/Sorties d\'articles')
@section('page-subtitle', 'Gestion des mouvements de stock avant validation')

@section('content')
<div class="space-y-6">
    <!-- Formulaire d'ajout de mouvement -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Enregistrer un mouvement</h3>
        
        <div class="bg-gray-50 rounded-lg p-4">
            <form id="addMovementForm" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="article_id" class="block text-sm font-medium text-gray-700 mb-1">Article <span class="text-red-500">*</span></label>
                        <select id="article_id" name="article_id" class="form-select-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                            <option value="">-- Sélectionner un article --</option>
                            @foreach($articles as $article)
                                <option value="{{ $article->id }}" data-stock="{{ $article->stock_quantity }}" data-price="{{ $article->unit_price }}">
                                    {{ $article->name }} (Stock: {{ $article->stock_quantity }})
                                </option>
                            @endforeach
                        </select>
                        <div id="article_id_error" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type de mouvement <span class="text-red-500">*</span></label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="type" value="entrée" class="form-radio h-5 w-5 text-blue-500" checked>
                                <span class="ml-2 text-gray-700">Entrée</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="type" value="sortie" class="form-radio h-5 w-5 text-red-500">
                                <span class="ml-2 text-gray-700">Sortie</span>
                            </label>
                        </div>
                        <div id="type_error" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantité <span class="text-red-500">*</span></label>
                        <input type="number" id="quantity" name="quantity" min="0.01" step="0.01" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                        <div id="quantity_error" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                    
                    <div>
                        <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire <span class="text-red-500">*</span></label>
                        <input style="cursor: not-allowed;" type="number" id="unit_price" name="unit_price" min="0" step="0.01" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required readonly>
                        <div id="unit_price_error" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                </div>
                
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea id="notes" name="notes" rows="2" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="Informations complémentaires sur ce mouvement..."></textarea>
                    <div id="notes_error" class="text-red-500 text-xs mt-1 hidden"></div>
                </div>
                
                <div>
                    <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-plus-circle mr-2"></i> Enregistrer le mouvement
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Mouvements en attente de validation -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Mouvements en attente</h3>
            
            <div class="mt-3 md:mt-0 flex space-x-2">
                <button id="validateMovementsBtn" class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-check-circle mr-2"></i> Valider la sélection
                </button>
                <button id="rejectMovementsBtn" class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-times-circle mr-2"></i> Rejeter la sélection
                </button>
            </div>
        </div>
        
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left">
                                <div class="flex items-center">
                                    <input id="selectAllPending" type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                </div>
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Article</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix Unit.</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="pendingMovementsBody">
                        @forelse($pendingMovements as $movement)
                        <tr data-id="{{ $movement->id }}">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <input type="checkbox" name="movement_ids[]" value="{{ $movement->id }}" class="movement-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $movement->article->name }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm {{ $movement->type === 'entrée' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ ucfirst($movement->type) }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($movement->quantity, 2, ',', ' ') }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($movement->unit_price, 2, ',', ' ') }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900 line-clamp-2" title="{{ $movement->notes }}">
                                    {{ $movement->notes ?: '-' }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $movement->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                <button class="edit-movement text-blue-600 hover:text-blue-900 mx-1" 
                                data-id="{{ $movement->id }}"
                                data-article-id="{{ $movement->article_id }}"
                                data-type="{{ $movement->type }}"
                                data-quantity="{{ $movement->quantity }}"
                                data-unit-price="{{ $movement->unit_price }}"
                                data-notes="{{ $movement->notes }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="delete-movement text-red-600 hover:text-red-900 mx-1" 
                                data-id="{{ $movement->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-3 text-center text-gray-500">
                        Aucun mouvement en attente de validation
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination pour les mouvements en attente -->
    <div class="px-4 py-3 bg-white border-t border-gray-200">
        {{ $pendingMovements->appends(['history_page' => request()->history_page])->links() }}
    </div>
</div>
</div>

<!-- Historique des mouvements validés/rejetés -->
<div class="bg-white rounded-lg shadow-sm p-6">
<h3 class="text-lg font-bold text-gray-800 mb-4">Historique des mouvements</h3>

<div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Article</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix Unit.</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Validation</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($historyMovements as $movement)
                <tr>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $movement->article->name }}</div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-sm {{ $movement->type === 'entrée' ? 'text-green-600' : 'text-red-600' }}">
                            {{ ucfirst($movement->type) }}
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ number_format($movement->quantity, 2, ',', ' ') }}</div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ number_format($movement->unit_price, 2, ',', ' ') }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm text-gray-900 line-clamp-2" title="{{ $movement->notes }}">
                            {{ $movement->notes ?: '-' }}
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $movement->status === 'validé' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($movement->status) }}
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $movement->updated_at->format('d/m/Y H:i') }}</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-3 text-center text-gray-500">
                        Aucun mouvement dans l'historique
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination pour l'historique -->
    <div class="px-4 py-3 bg-white border-t border-gray-200">
        {{ $historyMovements->appends(['pending_page' => request()->pending_page])->links() }}
    </div>
</div>
</div>
</div>

<!-- Modal d'édition -->
<div id="editMovementModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
<div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
<div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
<span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
<div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Modifier le mouvement</h3>
        <form id="editMovementForm" class="space-y-4">
            @csrf
            <input type="hidden" id="edit_movement_id" name="id">
            
            <div>
                <label for="edit_article_id" class="block text-sm font-medium text-gray-700 mb-1">Article <span class="text-red-500">*</span></label>
                <select id="edit_article_id" name="article_id" class="form-select-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                    <option value="">-- Sélectionner un article --</option>
                    @foreach($articles as $article)
                        <option value="{{ $article->id }}" data-stock="{{ $article->stock_quantity }}">
                            {{ $article->name }} (Stock: {{ $article->stock_quantity }})
                        </option>
                    @endforeach
                </select>
                <div id="edit_article_id_error" class="text-red-500 text-xs mt-1 hidden"></div>
            </div>
            
            <div>
                <label for="edit_type" class="block text-sm font-medium text-gray-700 mb-1">Type de mouvement <span class="text-red-500">*</span></label>
                <div class="flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" id="edit_type_entree" name="type" value="entrée" class="form-radio h-5 w-5 text-blue-500">
                        <span class="ml-2 text-gray-700">Entrée</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" id="edit_type_sortie" name="type" value="sortie" class="form-radio h-5 w-5 text-red-500">
                        <span class="ml-2 text-gray-700">Sortie</span>
                    </label>
                </div>
                <div id="edit_type_error" class="text-red-500 text-xs mt-1 hidden"></div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="edit_quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantité <span class="text-red-500">*</span></label>
                    <input type="number" id="edit_quantity" name="quantity" min="0.01" step="0.01" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                    <div id="edit_quantity_error" class="text-red-500 text-xs mt-1 hidden"></div>
                </div>
                <div>
                    <label for="edit_unit_price" class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire <span class="text-red-500">*</span></label>
                    <input type="number" id="edit_unit_price" name="unit_price" min="0" step="0.01" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                    <div id="edit_unit_price_error" class="text-red-500 text-xs mt-1 hidden"></div>
                </div>
            </div>
            
            <div>
                <label for="edit_notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea id="edit_notes" name="notes" rows="2" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="Informations complémentaires sur ce mouvement..."></textarea>
                <div id="edit_notes_error" class="text-red-500 text-xs mt-1 hidden"></div>
            </div>
        </form>
    </div>
    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
        <button type="button" id="saveMovementButton" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
            Enregistrer
        </button>
        <button type="button" id="cancelMovementEditButton" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
            Annuler
        </button>
    </div>
</div>
</div>
</div>
@endsection


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
// ==================== FONCTIONS UTILITAIRES ====================

// Réinitialiser les erreurs de formulaire
function resetFormErrors(prefix = '') {
const errorElements = document.querySelectorAll(`[id$="_error"]`);
errorElements.forEach(el => {
    if (!prefix || el.id.startsWith(prefix)) {
        el.textContent = '';
        el.classList.add('hidden');
    }
});
}

// Afficher un message d'erreur
function showError(elementId, message) {
const errorElement = document.getElementById(elementId);
if (errorElement) {
    errorElement.textContent = message;
    errorElement.classList.remove('hidden');
}
}

// ==================== GESTION DES MOUVEMENTS ====================

// Auto-remplissage du prix unitaire selon l'article sélectionné
document.getElementById('article_id').addEventListener('change', function() {
if (this.selectedIndex > 0) {
    const selectedOption = this.options[this.selectedIndex];
    const price = selectedOption.getAttribute('data-price');
    document.getElementById('unit_price').value = price || '';
} else {
    document.getElementById('unit_price').value = '';
}
});

// Formulaire d'ajout de mouvement
const addMovementForm = document.getElementById('addMovementForm');
if (addMovementForm) {
addMovementForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Réinitialiser les erreurs
    resetFormErrors();
    
    // Vérification de la disponibilité du stock pour les sorties
    const articleSelect = document.getElementById('article_id');
    const quantity = parseFloat(document.getElementById('quantity').value);
    const type = document.querySelector('input[name="type"]:checked').value;
    
    if (type === 'sortie' && articleSelect.selectedIndex > 0) {
        const selectedOption = articleSelect.options[articleSelect.selectedIndex];
        const availableStock = parseFloat(selectedOption.getAttribute('data-stock'));
        
        if (quantity > availableStock) {
            showError('quantity_error', `Attention: Stock actuel insuffisant. Disponible: ${availableStock}`);
            // Ne pas bloquer la soumission pour les sorties, juste un avertissement
        }
    }
    
    // Envoi de la requête AJAX
    const formData = new FormData(addMovementForm);
    
    fetch('{{ route("stock.movements.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Réinitialiser le formulaire
            addMovementForm.reset();
            
            // Recharger la page pour afficher le nouveau mouvement
            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: data.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }).then(() => {
                window.location.reload();
            });
        } else {
            // Afficher les erreurs
            if (data.errors) {
                Object.keys(data.errors).forEach(key => {
                    showError(`${key}_error`, data.errors[key][0]);
                });
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: data.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Une erreur est survenue lors de la communication avec le serveur',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    });
});
}

// Édition d'un mouvement
document.addEventListener('click', function(e) {
if (e.target.closest('.edit-movement')) {
    const button = e.target.closest('.edit-movement');
    const id = button.getAttribute('data-id');
    const articleId = button.getAttribute('data-article-id');
    const type = button.getAttribute('data-type');
    const quantity = button.getAttribute('data-quantity');
    const unitPrice = button.getAttribute('data-unit-price');
    const notes = button.getAttribute('data-notes');
    
    // Remplir le formulaire d'édition
    document.getElementById('edit_movement_id').value = id;
    document.getElementById('edit_article_id').value = articleId;
    
    if (type === 'entrée') {
        document.getElementById('edit_type_entree').checked = true;
    } else {
        document.getElementById('edit_type_sortie').checked = true;
    }
    
    document.getElementById('edit_quantity').value = quantity;
    document.getElementById('edit_unit_price').value = unitPrice;
    document.getElementById('edit_notes').value = notes;
    
    // Afficher la modal
    document.getElementById('editMovementModal').classList.remove('hidden');
}
});

// Fermer la modal d'édition
document.getElementById('cancelMovementEditButton').addEventListener('click', function() {
document.getElementById('editMovementModal').classList.add('hidden');
});

// Enregistrer les modifications d'un mouvement
document.getElementById('saveMovementButton').addEventListener('click', function() {
// Réinitialiser les erreurs
resetFormErrors('edit_');

// Récupérer les données du formulaire
const formData = new FormData(document.getElementById('editMovementForm'));
const id = document.getElementById('edit_movement_id').value;

// Envoi de la requête AJAX
fetch(`{{ url('stock/movements') }}/${id}`, {
    method: 'POST',
    body: formData,
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'X-Requested-With': 'XMLHttpRequest',
        'X-HTTP-Method-Override': 'PUT'
    }
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        // Fermer la modal
        document.getElementById('editMovementModal').classList.add('hidden');
        
        // Recharger la page pour afficher les modifications
        Swal.fire({
            icon: 'success',
            title: 'Succès',
            text: data.message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        }).then(() => {
            window.location.reload();
        });
    } else {
        // Afficher les erreurs
        if (data.errors) {
            Object.keys(data.errors).forEach(key => {
                showError(`edit_${key}_error`, data.errors[key][0]);
            });
        }
        
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: data.message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    }
})
.catch(error => {
    console.error('Erreur:', error);
    Swal.fire({
        icon: 'error',
        title: 'Erreur',
        text: 'Une erreur est survenue lors de la communication avec le serveur',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
});
});

// Suppression d'un mouvement
document.addEventListener('click', function(e) {
if (e.target.closest('.delete-movement')) {
    const button = e.target.closest('.delete-movement');
    const id = button.getAttribute('data-id');
    
    Swal.fire({
        title: 'Confirmation',
        text: 'Êtes-vous sûr de vouloir supprimer ce mouvement ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Supprimer',
        cancelButtonText: 'Annuler',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280'
    }).then((result) => {
        if (result.isConfirmed) {
            // Envoi de la requête AJAX
            fetch(`{{ url('stock/movements') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Recharger la page pour mettre à jour l'affichage
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: data.message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: data.message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: a3000
                    });
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue lors de la communication avec le serveur',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            });
        }
    });
}
});

// ==================== GESTION DES VALIDATIONS/REJETS ====================

// Sélectionner/désélectionner tous les mouvements en attente
const selectAllCheckbox = document.getElementById('selectAllPending');
if (selectAllCheckbox) {
selectAllCheckbox.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input.movement-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    
    updateValidationButtons();
});
}

// Mettre à jour le status des boutons de validation/rejet
function updateValidationButtons() {
const checkboxes = document.querySelectorAll('input.movement-checkbox:checked');
const validateBtn = document.getElementById('validateMovementsBtn');
const rejectBtn = document.getElementById('rejectMovementsBtn');

const hasSelection = checkboxes.length > 0;

validateBtn.disabled = !hasSelection;
rejectBtn.disabled = !hasSelection;

if (hasSelection) {
    validateBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    rejectBtn.classList.remove('opacity-50', 'cursor-not-allowed');
} else {
    validateBtn.classList.add('opacity-50', 'cursor-not-allowed');
    rejectBtn.classList.add('opacity-50', 'cursor-not-allowed');
}
}

// Écouter les changements sur les cases à cocher individuelles
document.addEventListener('change', function(e) {
if (e.target.classList.contains('movement-checkbox')) {
    updateValidationButtons();
    
    // Mettre à jour l'état du "Tout sélectionner"
    const allCheckboxes = document.querySelectorAll('input.movement-checkbox');
    const checkedCheckboxes = document.querySelectorAll('input.movement-checkbox:checked');
    
    const selectAllCheckbox = document.getElementById('selectAllPending');
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = allCheckboxes.length === checkedCheckboxes.length;
        selectAllCheckbox.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < allCheckboxes.length;
    }
}
});

// Initialiser l'état des boutons au chargement
updateValidationButtons();

// Validation des mouvements sélectionnés
document.getElementById('validateMovementsBtn').addEventListener('click', function() {
const checkedBoxes = document.querySelectorAll('input.movement-checkbox:checked');

if (checkedBoxes.length === 0) {
    Swal.fire({
        icon: 'warning',
        title: 'Attention',
        text: 'Veuillez sélectionner au moins un mouvement à valider',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
    return;
}

const movementIds = Array.from(checkedBoxes).map(checkbox => checkbox.value);

Swal.fire({
    title: 'Confirmation',
    text: `Êtes-vous sûr de vouloir valider ${movementIds.length} mouvement(s) ? Cette action modifiera le stock des articles concernés.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Valider',
    cancelButtonText: 'Annuler',
    confirmButtonColor: '#10b981',
    cancelButtonColor: '#6b7280'
}).then((result) => {
    if (result.isConfirmed) {
        // Envoi de la requête AJAX
        fetch('{{ route("stock.movements.validate") }}', {
            method: 'POST',
            body: JSON.stringify({ movement_ids: movementIds }),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Recharger la page pour mettre à jour l'affichage
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: data.message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                }).then(() => {
                    window.location.reload();
                });
            } else {
                // Si erreurs spécifiques de stock
                if (data.errors && data.errors.length > 0) {
                    let errorMessage = data.message + '<ul class="mt-2 text-left">';
                    data.errors.forEach(error => {
                        errorMessage += `<li>- ${error}</li>`;
                    });
                    errorMessage += '</ul>';
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        html: errorMessage,
                        confirmButtonColor: '#3085d6'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: data.message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Une erreur est survenue lors de la communication avec le serveur',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        });
    }
});
});

// Rejet des mouvements sélectionnés
document.getElementById('rejectMovementsBtn').addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('input.movement-checkbox:checked');
        
        if (checkedBoxes.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: 'Veuillez sélectionner au moins un mouvement à rejeter',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            return;
        }
        
        const movementIds = Array.from(checkedBoxes).map(checkbox => checkbox.value);
        
        Swal.fire({
            title: 'Confirmation',
            text: `Êtes-vous sûr de vouloir rejeter ${movementIds.length} mouvement(s) ? Cette action n'affectera pas le stock des articles.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Rejeter',
            cancelButtonText: 'Annuler',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280'
        }).then((result) => {
            if (result.isConfirmed) {
                // Envoi de la requête AJAX
                fetch('{{ route("stock.movements.reject") }}', {
                    method: 'POST',
                    body: JSON.stringify({ movement_ids: movementIds }),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Recharger la page pour mettre à jour l'affichage
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: data.message,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: data.message,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Une erreur est survenue lors de la communication avec le serveur',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
            }
        });
    });
});
</script>
