<!-- resources/views/productions/create.blade.php -->
@extends('layouts.app')

@section('title', 'Créer une production')

@section('breadcrumb')
    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
    <a href="{{ route('production.index') }}" class="hover:text-blue-700 transition-colors">Productions</a>
    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
    <span class="text-gray-700">Créer</span>
@endsection

@section('page-title', 'Créer une nouvelle production')
@section('page-subtitle', 'Remplissez le formulaire pour créer une nouvelle production')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

* {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

/* Styles pour les champs de formulaire */
.form-input-focus:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-select-focus:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Animation pour les erreurs */
.error-shake {
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 20%, 40%, 60%, 80% {
        transform: translateX(-2px);
    }
    10%, 30%, 50%, 70%, 90% {
        transform: translateX(2px);
    }
}

/* Responsive optimizations */
@media (max-width: 640px) {
    .form-container {
        margin: 0.5rem !important;
        padding: 1rem !important;
    }
    
    .form-title {
        font-size: 1.25rem !important;
        margin-bottom: 1rem !important;
    }
    
    .btn-mobile {
        width: 100% !important;
        justify-content: center !important;
        margin-bottom: 0.75rem !important;
    }
    
    .field-group {
        margin-bottom: 1rem !important;
    }
    
    .status-info {
        padding: 0.75rem !important;
        font-size: 0.875rem !important;
    }
}

@media (max-width: 375px) {
    .form-container {
        margin: 0.25rem !important;
        padding: 0.75rem !important;
    }
    
    .field-group {
        margin-bottom: 0.875rem !important;
    }
    
    .btn-text {
        font-size: 0.875rem !important;
    }
}

/* Amélioration de l'UX */
.form-field {
    transition: all 0.2s ease;
}

.form-field:focus-within label {
    color: #3b82f6;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6, #1e40af);
    transition: all 0.2s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-secondary {
    transition: all 0.2s ease;
}

.btn-secondary:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.status-badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.8;
    }
}
</style>

<div class="w-full max-w-none sm:max-w-2xl lg:max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-sm form-container p-4 sm:p-6 lg:p-8">
        <!-- En-tête du formulaire -->
        <div class="mb-6 sm:mb-8">
            <h2 class="form-title text-xl sm:text-2xl font-bold text-gray-900 mb-2">
                <i class="fas fa-plus-circle text-blue-600 mr-2"></i>
                Nouvelle production
            </h2>
            <p class="text-sm sm:text-base text-gray-600">
                Complétez les informations ci-dessous pour créer une nouvelle production
            </p>
        </div>
        
        <form action="{{ route('production.store') }}" method="POST" id="productionForm" class="space-y-4 sm:space-y-6">
            @csrf
            
            <!-- Nom de la production -->
            <div class="form-field field-group">
                <label for="production_name" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tag text-gray-400 mr-1"></i>
                    Nom de la production 
                    <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="production_name" 
                       id="production_name" 
                       placeholder="Ex: Production de cartons petit format..."
                       class="form-input-focus w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm sm:text-base" 
                       required>
                @error('production_name')
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>
            
            <!-- Grille responsive pour les autres champs -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <!-- Type de production -->
                <div class="form-field field-group">
                    <label for="type_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-list text-gray-400 mr-1"></i>
                        Type de production 
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="type_id" 
                            id="type_id" 
                            class="form-select-focus w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm sm:text-base" 
                            required>
                        <option value="">-- Sélectionner un type --</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('type_id')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Date de production -->
                <div class="form-field field-group">
                    <label for="production_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar text-gray-400 mr-1"></i>
                        Date de production 
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="production_date" 
                           id="production_date"
                           min="2024-01-01"
                           class="form-input-focus w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm sm:text-base"
                           required>
                    @error('production_date')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
            
            <!-- Grille pour quantité et statut -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <!-- Quantité de production -->
                <div class="form-field field-group">
                    <label for="qte_production" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-boxes text-gray-400 mr-1"></i>
                        Quantité de production 
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="qte_production" 
                           id="qte_production" 
                           step="0.01" 
                           min="0" 
                           placeholder="Ex: 1000"
                           class="form-input-focus w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm sm:text-base" 
                           required>
                    @error('qte_production')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Statut (information) -->
                <div class="form-field field-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-info-circle text-gray-400 mr-1"></i>
                        Statut de la production
                    </label>
                    <!-- Statut caché -->
                    <input type="hidden" name="status" id="status" value="en cours">
                    
                    <div class="status-info px-3 sm:px-4 py-3 sm:py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl">
                        <div class="flex items-center mb-2">
                            <span class="status-badge inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-medium bg-blue-100 text-blue-800">
                                <span class="mr-1.5 h-2 w-2 bg-blue-500 rounded-full"></span>
                                En cours
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                            Le statut est automatiquement défini à "En cours" pour toute nouvelle production. 
                            Il sera mis à jour une fois la production terminée.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Boutons d'action responsive -->
            <div class="pt-4 sm:pt-6 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0 sm:space-x-4">
                    <!-- Bouton Annuler -->
                    <a href="{{ route('production.index') }}" 
                       class="btn-secondary btn-mobile w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center text-sm sm:text-base font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> 
                        <span class="btn-text">Annuler</span>
                    </a>
                    
                    <!-- Bouton Créer -->
                    <button type="submit" 
                            class="btn-primary btn-mobile w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center text-sm sm:text-base font-medium shadow-lg">
                        <i class="fas fa-save mr-2"></i> 
                        <span class="btn-text">Créer la production</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Aide contextuelle (visible seulement sur desktop) -->
    <div class="hidden lg:block mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
        <h3 class="text-sm font-medium text-blue-900 mb-2 flex items-center">
            <i class="fas fa-lightbulb mr-2"></i>
            Conseils
        </h3>
        <ul class="text-xs text-blue-800 space-y-1">
            <li>• Utilisez des noms descriptifs pour vos productions</li>
            <li>• La quantité peut être décimale (ex: 1000.5)</li>
            <li>• Le statut sera automatiquement mis à jour lors de la finalisation</li>
        </ul>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('productionForm');
    
    if (form) {
        // Animation au focus des champs
        const formFields = form.querySelectorAll('input, select');
        formFields.forEach(field => {
            field.addEventListener('focus', function() {
                this.parentElement.classList.add('scale-[1.02]');
            });
            
            field.addEventListener('blur', function() {
                this.parentElement.classList.remove('scale-[1.02]');
            });
        });
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Vérifier si tous les champs requis sont remplis
            const requiredFields = form.querySelectorAll('[required]');
            let allFieldsFilled = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    allFieldsFilled = false;
                    field.classList.add('border-red-500', 'error-shake');
                    field.parentElement.classList.add('error-shake');
                    
                    // Retirer l'animation après 500ms
                    setTimeout(() => {
                        field.classList.remove('error-shake');
                        field.parentElement.classList.remove('error-shake');
                    }, 500);
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            
            if (!allFieldsFilled) {
                Swal.fire({
                    icon: 'error',
                    title: 'Champs manquants',
                    text: 'Veuillez remplir tous les champs obligatoires',
                    toast: window.innerWidth < 640,
                    position: window.innerWidth < 640 ? 'top-end' : 'center',
                    showConfirmButton: window.innerWidth >= 640,
                    timer: window.innerWidth < 640 ? 3000 : undefined,
                    customClass: {
                        popup: 'rounded-xl',
                        title: 'text-gray-800'
                    }
                });
                return;
            }
            
            // Afficher un loader sur le bouton
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Création en cours...';
            submitBtn.disabled = true;
            
            // Envoi du formulaire en AJAX
            const formData = new FormData(form);
            
            fetch(form.action, {
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Production créée !',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false,
                        toast: window.innerWidth < 640,
                        position: window.innerWidth < 640 ? 'top-end' : 'center',
                        customClass: {
                            popup: 'rounded-xl',
                            title: 'text-gray-800'
                        }
                    }).then(() => {
                        window.location.href = "{{ route('production.index') }}";
                    });
                } else {
                    throw new Error(data.message || 'Une erreur est survenue');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: error.message || 'Une erreur est survenue lors de la communication avec le serveur',
                    toast: window.innerWidth < 640,
                    position: window.innerWidth < 640 ? 'top-end' : 'center',
                    showConfirmButton: window.innerWidth >= 640,
                    timer: window.innerWidth < 640 ? 4000 : undefined,
                    customClass: {
                        popup: 'rounded-xl',
                        title: 'text-gray-800'
                    }
                });
            })
            .finally(() => {
                // Restaurer le bouton
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    
    // Définir la date du jour par défaut
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('production_date').value = today;
    
    // Amélioration UX: auto-focus sur le premier champ sur desktop
    if (window.innerWidth >= 768) {
        document.getElementById('production_name').focus();
    }
});
</script>

@endsection