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
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('production.store') }}" method="POST" id="productionForm">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom de la production -->
                    <div class="col-span-2">
                        <label for="production_name" class="block text-sm font-medium text-gray-700 mb-1">Nom de la production <span class="text-red-500">*</span></label>
                        <input type="text" name="production_name" id="production_name" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                        @error('production_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Type de production -->
                    <div>
                        <label for="type_id" class="block text-sm font-medium text-gray-700 mb-1">Type de production <span class="text-red-500">*</span></label>
                        <select name="type_id" id="type_id" class="form-select-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                            <option value="">-- Sélectionner un type --</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('type_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Date de production -->
                    <div>
                        <label for="production_date" class="block text-sm font-medium text-gray-700 mb-1">Date de production <span class="text-red-500">*</span></label>
                        {{-- <input type="date" name="production_date" id="production_date" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required> --}}
                        <input type="date" name="production_date" id="production_date"
                        min="2024-01-01"
                        class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                        required>
                        @error('production_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Quantité de production -->
                    <div>
                        <label for="qte_production" class="block text-sm font-medium text-gray-700 mb-1">Quantité de production <span class="text-red-500">*</span></label>
                        <input type="number" name="qte_production" id="qte_production" step="0.01" min="0" class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" required>
                        @error('qte_production')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Statut (caché, toujours "en cours" par défaut) -->
                    <input type="hidden" name="status" id="status" value="en cours">
                    
                    <!-- Information sur le statut -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                        <div class="px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <span class="mr-1 h-2 w-2 bg-blue-500 rounded-full"></span>
                                En cours
                            </span>
                            <p class="text-xs text-gray-500 mt-1">Le statut est automatiquement défini à "En cours" pour toute nouvelle production.
                                il sera mis à jour une fois la production terminée 
                            </p>
                        </div>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="col-span-2 flex justify-between mt-4">
                        <a href="{{ route('production.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-arrow-left mr-2"></i> Annuler
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-save mr-2"></i> Créer la production
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('productionForm');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Vérifier si tous les champs requis sont remplis
                const requiredFields = form.querySelectorAll('[required]');
                let allFieldsFilled = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        allFieldsFilled = false;
                        field.classList.add('border-red-500');
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });
                
                if (!allFieldsFilled) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Veuillez remplir tous les champs obligatoires',
                        customClass: {
                            popup: 'rounded-xl',
                            title: 'text-navy-800'
                        }
                    });
                    return;
                }
                
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
                            title: 'Succès!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'rounded-xl',
                                title: 'text-navy-800'
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
                        customClass: {
                            popup: 'rounded-xl',
                            title: 'text-navy-800'
                        }
                    });
                });
            });
        }
        
        // Définir la date du jour par défaut
        document.getElementById('production_date').valueAsDate = new Date();
    });
</script>

