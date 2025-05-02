
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mon Profil - KMJ APPLE STORE</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    
</head>
<style>
    /* Styles généraux */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f9;
    }

    .content-wrapper {
        padding: 20px;
    }

    /* Style des onglets */
    .nav-tabs {
        display: flex;
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 20px;
    }

    .nav-tabs .nav-item {
        flex: 1;
    }

    .nav-tabs .nav-link {
        position: relative;
        text-align: center;
        color: #6c757d;
        font-weight: 500;
        padding: 12px 16px;
        border: none;
        background: transparent;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .nav-tabs .nav-link i {
        margin-right: 8px;
    }

    .nav-tabs .nav-link.active {
        color: #4A90E2;
        font-weight: 600;
    }

    .nav-tabs .nav-link.active:after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 3px;
        background: #4A90E2;
    }

    .nav-tabs .nav-link:hover {
        color: #4A90E2;
    }

    /* Style des onglets en responsive */
    @media (max-width: 768px) {
        .nav-tabs .nav-link {
            padding: 12px 8px;
        }
        
        .nav-tabs .nav-link i {
            margin-right: 4px;
        }
    }

    /* Style du contenu des onglets */
    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Avatar de profil */
    .profile-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .profile-avatar {
        position: relative;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: #4A90E2;
        color: white;
        font-size: 36px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        border: 3px solid #fff;
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .profile-avatar .change-photo {
        position: absolute;
        bottom: 0;
        right: 0;
        background: #343a40;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2px solid white;
        transition: all 0.2s;
    }

    .profile-avatar .change-photo:hover {
        background: #4A90E2;
        transform: scale(1.1);
    }

    .profile-name {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .profile-email {
        color: #6c757d;
        margin-bottom: 10px;
    }

    .profile-role {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 10px;
    }

    .role-admin {
        background-color: #E3F2FD;
        color: #2962FF;
    }

    .role-user {
        background-color: #FFF3E0;
        color: #FF6D00;
    }

    .profile-metadata {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 15px;
        font-size: 14px;
        color: #6c757d;
    }

    .profile-metadata div {
        display: flex;
        align-items: center;
    }

    .profile-metadata i {
        margin-right: 5px;
    }

    /* Style des formulaires */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 500;
        margin-bottom: 8px;
        color: #343a40;
    }

    .form-control-wrapper {
        position: relative;
    }

    .form-control-wrapper i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px 10px 40px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 16px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #4A90E2;
        box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
        outline: none;
    }

    .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #adb5bd;
        cursor: pointer;
    }

    .toggle-password:hover {
        color: #4A90E2;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 14px;
        margin-top: 5px;
    }

    /* Style des boutons */
    .btn {
        padding: 10px 20px;
        font-weight: 500;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }

    .btn-primary {
        background-color: #4A90E2;
        color: white;
    }

    .btn-primary:hover {
        background-color: #3A73B5;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .btn:active {
        transform: scale(0.98);
    }

    /* Style des alertes */
    .alert {
        padding: 12px 16px;
        margin-bottom: 20px;
        border-radius: 4px;
        border-left: 4px solid;
    }

    .alert-success {
        background-color: #d4edda;
        border-color: #28a745;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        border-color: #dc3545;
        color: #721c24;
    }

    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffc107;
        color: #856404;
    }

    /* Style de la section de suppression */
    .delete-account-section {
        background-color: #f8d7da;
        border-radius: 4px;
        padding: 20px;
        margin-top: 30px;
    }

    .delete-account-section h3 {
        color: #721c24;
        margin-bottom: 10px;
    }

    .delete-account-section p {
        color: #721c24;
        margin-bottom: 15px;
        font-size: 14px;
    }

    /* Style responsive */
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Grid layout pour les formulaires */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-grid-full {
        grid-column: 1 / -1;
    }

    /* Style des cards */
    .card {
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .card-header {
        padding: 15px 20px;
        border-bottom: 1px solid #e9ecef;
        font-weight: 600;
        color: #343a40;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
    }

    .card-header i {
        margin-right: 10px;
        color: #4A90E2;
    }

    .card-body {
        padding: 20px;
    }

    /* Verification Badge */
    .verification-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 13px;
        margin-left: 10px;
    }

    .verified {
        background-color: #d4edda;
        color: #155724;
    }

    .not-verified {
        background-color: #fff3cd;
        color: #856404;
    }

    .verification-badge i {
        margin-right: 5px;
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
                            <h1 class="m-0">Mon Profil</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Tableau de bord</a></li>
                                <li class="breadcrumb-item active">Profil</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <!-- Message de statut -->
                    @if (session('status'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
                        </div>
                    @endif

                    <!-- En-tête du profil -->
                    <div class="profile-header">
                        <div class="profile-avatar">
                            @if(Auth::user()->profile_photo_path)
                                <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="Photo de profil">
                            @else
                                {{ substr(Auth::user()->firstname ?? '', 0, 1) }}{{ substr(Auth::user()->lastname ?? '', 0, 1) }}
                            @endif
                            <label for="profile-photo-input" class="change-photo" title="Changer la photo">
                                <i class="fas fa-camera"></i>
                                <input type="file" id="profile-photo-input" class="d-none" accept="image/*">
                            </label>
                        </div>

                        <div class="profile-name">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</div>
                        <div class="profile-email">{{ Auth::user()->email }}</div>
                        
                        <div class="profile-role {{ Auth::user()->role == 'admin' ? 'role-admin' : 'role-user' }}">
                            <i class="fas {{ Auth::user()->role == 'admin' ? 'fa-shield-alt' : 'fa-user' }} mr-1"></i>
                            {{ Auth::user()->role == 'admin' ? 'Administrateur' : 'Utilisateur' }}
                        </div>
                        
                        <div class="profile-metadata">
                            <div>
                                <i class="fas fa-calendar-alt"></i>
                                Membre depuis {{ Auth::user()->created_at->format('d M Y') }}
                            </div>
                           
                        </div>
                    </div>

                    <!-- Navigation par onglets -->
                    <ul style="list-style: none !important" class="nav-tabs">
                        <li class="nav-item">
                            <button class="nav-link active" data-tab="profile-info">
                                <i class="fas fa-user"></i> Informations personnelles
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-tab="security">
                                <i class="fas fa-lock"></i> Mot de passe 
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-tab="account">
                                <i class="fas fa-cog"></i> Paramètres du compte
                            </button>
                        </li>
                    </ul>

                    <!-- Contenu des onglets -->
                    <div class="tab-content active" id="profile-info">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-user-edit"></i> Mettre à jour vos informations
                            </div>
                            <div class="card-body">
                                <form id="update-profile-form" action="{{ route('profile.update') }}" method="POST" class="form-grid">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <!-- Prénom -->
                                    <div class="form-group">
                                        <label for="firstname">Prénom</label>
                                        <div class="form-control-wrapper">
                                            <i class="fas fa-user"></i>
                                            <input id="firstname" name="firstname" type="text" value="{{ Auth::user()->firstname }}" required class="form-control @error('firstname') is-invalid @enderror">
                                        </div>
                                        @error('firstname')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <!-- Nom -->
                                    <div class="form-group">
                                        <label for="lastname">Nom</label>
                                        <div class="form-control-wrapper">
                                            <i class="fas fa-user"></i>
                                            <input id="lastname" name="lastname" type="text" value="{{ Auth::user()->lastname }}" required class="form-control @error('lastname') is-invalid @enderror">
                                        </div>
                                        @error('lastname')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <!-- Email -->
                                    <div class="form-group form-grid-full">
                                        <label for="email">Adresse email</label>
                                        <div class="form-control-wrapper">
                                            <i class="fas fa-envelope"></i>
                                            <input id="email" name="email" type="email" value="{{ Auth::user()->email }}" required class="form-control @error('email') is-invalid @enderror">
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        
                                        @if (Auth::user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! Auth::user()->hasVerifiedEmail())
                                            <div class="mt-2">
                                                <div class="verification-badge not-verified">
                                                    <i class="fas fa-exclamation-circle"></i> Email non vérifié
                                                    <button type="button" id="resend-verification" class="btn btn-link p-0 ml-2 text-warning">
                                                        Renvoyer le lien de vérification
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="form-group form-grid-full text-right">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i> Enregistrer les modifications
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-content" id="security">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-key"></i> Changer votre mot de passe
                            </div>
                            <div class="card-body">
                                <form id="update-password-form" action="{{ route('password.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="form-group">
                                        <label for="current_password">Mot de passe actuel</label>
                                        <div class="form-control-wrapper">
                                            <i class="fas fa-lock"></i>
                                            <input id="current_password" name="current_password" type="password" required class="form-control @error('current_password') is-invalid @enderror">
                                            <button type="button" class="toggle-password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label for="password">Nouveau mot de passe</label>
                                            <div class="form-control-wrapper">
                                                <i class="fas fa-lock"></i>
                                                <input id="password" name="password" type="password" required class="form-control @error('password') is-invalid @enderror">
                                                <button type="button" class="toggle-password">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="password_confirmation">Confirmer le mot de passe</label>
                                            <div class="form-control-wrapper">
                                                <i class="fas fa-lock"></i>
                                                <input id="password_confirmation" name="password_confirmation" type="password" required class="form-control @error('password_confirmation') is-invalid @enderror">
                                                <button type="button" class="toggle-password">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            @error('password_confirmation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i> Mettre à jour le mot de passe
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-content" id="account">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-cog"></i> Paramètres du compte
                            </div>
                            <div class="card-body">
                                <p>Cette section vous permet de gérer les paramètres avancés de votre compte.</p>
                                
                                <div class="delete-account-section">
                                    <h3><i class="fas fa-exclamation-triangle mr-2"></i> Supprimer le compte</h3>
                                    <p>Une fois que votre compte est supprimé, toutes ses ressources et données seront définitivement effacées. Avant de supprimer votre compte, veuillez télécharger toutes les données ou informations que vous souhaitez conserver.</p>
                                    
                                    <button type="button" class="btn btn-danger" id="delete-account-btn">
                                        <i class="fas fa-trash-alt mr-1"></i> Supprimer mon compte
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        
        @include('apk.components.footer')
    </div>

    <!-- Modal de suppression du compte -->
    <div class="modal fade" id="delete-account-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger"><i class="fas fa-exclamation-triangle mr-2"></i> Supprimer votre compte</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="bg-danger text-white rounded-circle d-inline-flex justify-content-center align-items-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-exclamation-triangle fa-3x"></i>
                        </div>
                        <p class="mt-3">Cette action est définitive et irréversible. Toutes vos données seront supprimées.</p>
                    </div>
                    
                    <form id="delete-account-form" action="{{ route('profile.destroy') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        
                        <div class="form-group">
                            <label for="delete_password">Veuillez entrer votre mot de passe pour confirmer</label>
                            <div class="form-control-wrapper">
                                <i class="fas fa-lock"></i>
                                <input id="delete_password" name="password" type="password" required class="form-control">
                                <button type="button" class="toggle-password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete-btn">
                        <i class="fas fa-trash-alt mr-1"></i> Supprimer définitivement
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion des onglets
            const tabButtons = document.querySelectorAll('.nav-link');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    
                    // Désactiver tous les onglets
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));
                    
                    // Activer l'onglet sélectionné
                    this.classList.add('active');
                    document.getElementById(tabId).classList.add('active');
                });
            });
            
            // Toggle visibilité du mot de passe
            const togglePasswordBtns = document.querySelectorAll('.toggle-password');
            togglePasswordBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('input');
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    
                    // Toggle icône
                    const eyeIcon = this.querySelector('i');
                    eyeIcon.classList.toggle('fa-eye');
                    eyeIcon.classList.toggle('fa-eye-slash');
                });
            });
            
            // Modal de suppression du compte
            const deleteAccountBtn = document.getElementById('delete-account-btn');
            const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
            const deleteAccountForm = document.getElementById('delete-account-form');
            
            if (deleteAccountBtn) {
                deleteAccountBtn.addEventListener('click', function() {
                    $('#delete-account-modal').modal('show');
                });
            }
            
            if (confirmDeleteBtn && deleteAccountForm) {
                confirmDeleteBtn.addEventListener('click', function() {
                    deleteAccountForm.submit();
                });
            }
            
            // Renvoyer l'email de vérification
            const resendVerificationBtn = document.getElementById('resend-verification');
            if (resendVerificationBtn) {
                resendVerificationBtn.addEventListener('click', async function() {
                    try {
                        this.disabled = true;
                        this.textContent = 'Envoi en cours...';
                        
                        const response = await fetch('{{ route("verification.send") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (data.status === 'verification-link-sent') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Email envoyé!',
                                text: 'Un nouveau lien de vérification a été envoyé à votre adresse email.'
                            });
                        } else {
                            throw new Error('Une erreur est survenue');
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: error.message || 'Une erreur est survenue lors de l\'envoi du lien de vérification.'
                        });
                    } finally {
                        this.disabled = false;
                        this.textContent = 'Renvoyer le lien de vérification';
                    }
                });
            }

            // Gestionnaire de changement de photo de profil
            const profilePhotoInput = document.getElementById('profile-photo-input');
            if (profilePhotoInput) {
                profilePhotoInput.addEventListener('change', function(e) {
                    if (e.target.files.length > 0) {
                        const file = e.target.files[0];
                        if (!file.type.match('image.*')) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Type de fichier non supporté',
                                text: 'Veuillez sélectionner une image.'
                            });
                            return;
                        }
                        
                        if (file.size > 1024 * 1024) { // 1MB
                            Swal.fire({
                                icon: 'error',
                                title: 'Fichier trop volumineux',
                                text: 'La taille de l\'image ne doit pas dépasser 1MB.'
                            });
                            return;
                        }
                        
                        // Créer un objet FormData pour l'upload
                        const formData = new FormData();
                        formData.append('photo', file);
                        formData.append('_method', 'PATCH');
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                        
                        // Afficher une prévisualisation temporaire
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const avatar = document.querySelector('.profile-avatar');
                            avatar.innerHTML = `<img src="${e.target.result}" alt="Photo de profil">
                                               <label for="profile-photo-input" class="change-photo" title="Changer la photo">
                                                 <i class="fas fa-camera"></i>
                                                 <input type="file" id="profile-photo-input" class="d-none" accept="image/*">
                                               </label>`;
                        };
                        reader.readAsDataURL(file);
                        
                        // Envoyer la requête pour mettre à jour la photo
                        Swal.fire({
                            title: 'Mise à jour en cours',
                            text: 'Veuillez patienter pendant le téléchargement...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        fetch('{{ route("profile.photo") }}', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Photo mise à jour!',
                                    text: 'Votre photo de profil a été mise à jour avec succès.'
                                });
                            } else {
                                throw new Error(data.message || 'Une erreur est survenue');
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: error.message || 'Une erreur est survenue lors de la mise à jour de la photo.'
                            });
                        });
                    }
                });
            }
        });
    </script>
</body>
</html> 

<style>
    .nav-tabs .nav-link.active {
    color: white !important;
    font-weight: 600;
}
</style>