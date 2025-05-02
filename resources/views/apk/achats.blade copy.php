<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestion de Stock</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('apk.components.topbar')
        @include('apk.components.navbar')

        <div class="content-wrapper bg-light">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Gestion du Stock</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card card-primary">
                                <div class="card-header bg-primary">
                                    <h3 class="card-title">
                                        <i class="fas fa-boxes me-2"></i>
                                        Mouvement de Stock
                                    </h3>
                                </div>

                                <div class="card-body">
                                    <form id="stockForm" method="POST">
                                        @csrf

                                        <!-- Type d'opération -->
                                        <div class="form-group mb-4">
                                            <label class="form-label fw-bold">Type d'opération</label>
                                            <div class="d-flex justify-content-center gap-3 mb-2">
                                                <button type="button" class="btn btn-outline-success operation-btn flex-grow-1 py-3" data-type="entree">
                                                    <i class="fas fa-plus-circle me-2"></i>
                                                    Achat
                                                </button>
                                                <button type="button" class="btn btn-outline-danger operation-btn flex-grow-1 py-3" data-type="sortie">
                                                    <i class="fas fa-minus-circle me-2"></i>
                                                    Vente
                                                </button>
                                            </div>
                                            <input type="hidden" name="operation_type" id="operationType">
                                        </div>

                                        <!-- Sélection de l'article -->
                                        <div class="form-group mb-4">
                                            <label for="article" class="form-label fw-bold">
                                                Article <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select form-control" name="article_id" id="article" required>
                                                <option value="">Sélectionner un article</option>
                                                @foreach($articles as $article)
                                                    <option value="{{ $article->id }}" 
                                                            data-stock="{{ $article->quantite }}">
                                                        {{ $article->name }} (Stock: {{ $article->quantite }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="mt-2">
                                                <span id="stockActuel" class="badge bg-info d-none"></span>
                                            </div>
                                        </div>

                                        <!-- Quantité -->
                                        <div class="form-group mb-4">
                                            <label for="articleQte" class="form-label fw-bold">
                                                Quantité <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" 
                                                   class="form-control"
                                                   id="articleQte"
                                                   name="articleQte"
                                                   min="1"
                                                   required>
                                            <div class="invalid-feedback" id="quantiteError"></div>
                                        </div>

                                        <!-- Commentaire -->
                                        <div class="form-group mb-4">
                                            <label for="comment" class="form-label fw-bold">
                                                Motif du mouvement
                                                <small class="text-muted">(facultatif)</small>
                                            </label>
                                            <textarea class="form-control"
                                                    id="comment"
                                                    name="comment"
                                                    rows="3"
                                                    placeholder="Saisissez un motif (optionnel)"></textarea>
                                        </div>

                                        <!-- Bouton de validation -->
                                        <div class="d-grid">
                                            <button type="submit" 
                                                    class="btn btn-lg"
                                                    id="submitButton" 
                                                    disabled>
                                                Valider l'opération
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        @include('apk.components.footer')
    </div>

    <style>
    .operation-btn {
        transition: all 0.3s ease;
        font-size: 1.1rem;
    }

    .operation-btn.active {
        transform: scale(1.02);
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .operation-btn.btn-outline-success:hover,
    .operation-btn.btn-outline-success.active {
        background-color: #28a745;
        color: white;
    }

    .operation-btn.btn-outline-danger:hover,
    .operation-btn.btn-outline-danger.active {
        background-color: #dc3545;
        color: white;
    }

    .badge {
        font-size: 0.9rem;
        padding: 8px 12px;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #80bdff;
    }

    #submitButton {
        padding: 12px 20px;
        font-size: 1.1rem;
    }

    #submitButton:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('stockForm');
        const operationBtns = document.querySelectorAll('.operation-btn');
        const operationType = document.getElementById('operationType');
        const articleSelect = document.getElementById('article');
        const quantiteInput = document.getElementById('articleQte');
        const submitButton = document.getElementById('submitButton');
        const stockActuel = document.getElementById('stockActuel');
        
        let selectedOperation = null;

        // Gestion des boutons d'opération
        operationBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                operationBtns.forEach(b => {
                    b.classList.remove('active');
                    b.classList.remove('btn-success', 'btn-danger');
                    b.classList.add(b.dataset.type === 'entree' ? 'btn-outline-success' : 'btn-outline-danger');
                });

                this.classList.add('active');
                this.classList.remove(this.dataset.type === 'entree' ? 'btn-outline-success' : 'btn-outline-danger');
                this.classList.add(this.dataset.type === 'entree' ? 'btn-success' : 'btn-danger');
                
                selectedOperation = this.dataset.type;
                operationType.value = selectedOperation;

                updateSubmitButtonStyle();
                validateForm();
            });
        });

        // Gestion de l'affichage du stock actuel
        articleSelect.addEventListener('change', function() {
            const selectedOption = this.selectedOptions[0];
            const stockValue = selectedOption?.dataset.stock;
            
            if (stockValue) {
                stockActuel.textContent = `Stock actuel : ${stockValue} unités`;
                stockActuel.classList.remove('d-none');
            } else {
                stockActuel.classList.add('d-none');
            }
            validateForm();
        });

        // Validation
        quantiteInput.addEventListener('input', validateForm);

        function validateForm() {
            const article = articleSelect.selectedOptions[0];
            const quantite = parseInt(quantiteInput.value);
            const stockActuel = article ? parseInt(article.dataset.stock) : 0;
            let isValid = true;

            // Réinitialisation des erreurs
            quantiteInput.classList.remove('is-invalid');

            // Validation de la quantité pour les sorties
            if (selectedOperation === 'sortie' && quantite > stockActuel) {
                quantiteInput.classList.add('is-invalid');
                document.getElementById('quantiteError').textContent = 
                    `La quantité ne peut pas dépasser le stock actuel (${stockActuel})`;
                isValid = false;
            }

            // Validation globale
            submitButton.disabled = !(
                selectedOperation &&
                articleSelect.value &&
                quantiteInput.value > 0 &&
                isValid
            );

            updateSubmitButtonStyle();
        }

        function updateSubmitButtonStyle() {
            if (selectedOperation) {
                submitButton.className = `btn btn-lg ${selectedOperation === 'entree' ? 'btn-success' : 'btn-danger'}`;
                submitButton.innerHTML = `
                    <i class="fas ${selectedOperation === 'entree' ? 'fa-plus-circle' : 'fa-minus-circle'} me-2"></i>
                    Valider ${selectedOperation === 'entree' ? 'l\'entrée' : 'la sortie'}
                `;
            }
        }

        // Soumission du formulaire
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const formData = new FormData(this);
                const url = selectedOperation === 'entree' ? '/stock/input' : '/stock/output';
                
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Une erreur est survenue');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: error.message
                });
            }
        });
    });
    </script>
</body>
</html>