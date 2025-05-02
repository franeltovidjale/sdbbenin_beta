<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inscription - Système de Gestion de Stock</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#F7F9FB',  // Couleur neutre
                            100: '#DCE3EF',
                            200: '#B9C7DF',
                            300: '#96ABCF',
                            400: '#738FBF',
                            500: '#4A90E2',  // Couleur secondaire
                            600: '#3A73B5',
                            700: '#2A5689',
                            800: '#1A365D',  // Couleur principale
                            900: '#0F1F36',
                        },
                        accent: {
                            50: '#FFF8F0',
                            100: '#FFECD8',
                            200: '#FFD9B1',
                            300: '#FFC78A',
                            400: '#FFB362',
                            500: '#FF9F43',  // Couleur d'accent
                            600: '#FF8A22',
                            700: '#FF7600',
                            800: '#D96200',
                            900: '#B34F00',
                        },
                        text: {
                            primary: '#333333',  // Texte principal
                            secondary: '#6B7280', // Texte secondaire
                            light: '#9CA3AF',     // Texte clair
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        'card': '0 4px 20px rgba(26, 54, 93, 0.08)',
                        'input': '0 2px 5px rgba(26, 54, 93, 0.05)',
                        'button': '0 4px 10px rgba(74, 144, 226, 0.3)',
                    },
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        .animate-gradient {
            background-size: 400%;
            -webkit-animation: gradient 8s ease infinite;
            animation: gradient 8s ease infinite;
        }
        
        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }
        
        .form-input-focus:focus {
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.25);
        }
        
        .slide-in {
            animation: slideIn 0.5s ease forwards;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(74, 144, 226, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(74, 144, 226, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(74, 144, 226, 0);
            }
        }
        
        /* Animation légère sur les éléments interactifs */
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(26, 54, 93, 0.1);
        }
    </style>
</head>
<body class="bg-primary-50 font-sans text-text-primary">
    <!-- Alert Container for errors/messages -->
    <div id="alert-container" class="fixed top-4 right-4 z-50 w-full max-w-sm"></div>

    <div class="min-h-screen flex justify-center items-center p-4">
        <div class="w-full max-w-3xl">
            <!-- Logo et titre -->
            <div class="text-center mb-8">
                <div class="inline-block p-4 rounded-full bg-primary-100 mb-4 shadow-lg">
                    <svg class="w-12 h-12 text-primary-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-primary-800">Créer un compte</h1>
                <p class="text-text-secondary mt-2">Système de Gestion de Stock</p>
            </div>

            <!-- Card d'inscription -->
            <div class="bg-white rounded-xl shadow-card overflow-hidden">
                <div class="h-2 bg-gradient-to-r from-primary-500 to-primary-800 animate-gradient"></div>
                
                <div class="p-8">
                    <form id="register-form" action="{{ route('register') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-5">
                            <!-- Formulaire principal -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Prénom -->
                                <div>
                                    <label for="firstname" class="block text-sm font-medium text-text-primary mb-1">Prénom</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-text-light"></i>
                                        </div>
                                        <input
                                            id="firstname"
                                            name="firstname"
                                            type="text"
                                            required
                                            class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                            placeholder="John"
                                        />
                                    </div>
                                    <div class="text-accent-600 text-sm mt-1 hidden" id="firstname-error"></div>
                                </div>
                                
                                <!-- Nom -->
                                <div>
                                    <label for="lastname" class="block text-sm font-medium text-text-primary mb-1">Nom</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-text-light"></i>
                                        </div>
                                        <input
                                            id="lastname"
                                            name="lastname"
                                            type="text"
                                            required
                                            class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                            placeholder="Doe"
                                        />
                                    </div>
                                    <div class="text-accent-600 text-sm mt-1 hidden" id="lastname-error"></div>
                                </div>
                                
                                <!-- Email -->
                                <div class="md:col-span-2">
                                    <label for="email" class="block text-sm font-medium text-text-primary mb-1">Adresse email</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-text-light"></i>
                                        </div>
                                        <input
                                            id="email"
                                            name="email"
                                            type="email"
                                            required
                                            class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                            placeholder="vous@exemple.com"
                                        />
                                    </div>
                                    <div class="text-accent-600 text-sm mt-1 hidden" id="email-error"></div>
                                </div>
                                
                                <!-- Mot de passe -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-text-primary mb-1">Mot de passe</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-text-light"></i>
                                        </div>
                                        <input
                                            id="password"
                                            name="password"
                                            type="password"
                                            required
                                            class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                            placeholder="••••••••"
                                        />
                                        <button
                                            type="button"
                                            class="toggle-password absolute inset-y-0 right-0 pr-3 flex items-center"
                                        >
                                            <i class="fas fa-eye text-text-light hover:text-primary-500 cursor-pointer transition"></i>
                                        </button>
                                    </div>
                                    <div class="text-accent-600 text-sm mt-1 hidden" id="password-error"></div>
                                </div>
                                
                                <!-- Confirmation mot de passe -->
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-text-primary mb-1">Confirmation du mot de passe</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-text-light"></i>
                                        </div>
                                        <input
                                            id="password_confirmation"
                                            name="password_confirmation"
                                            type="password"
                                            required
                                            class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                            placeholder="••••••••"
                                        />
                                        <button
                                            type="button"
                                            class="toggle-password absolute inset-y-0 right-0 pr-3 flex items-center"
                                        >
                                            <i class="fas fa-eye text-text-light hover:text-primary-500 cursor-pointer transition"></i>
                                        </button>
                                    </div>
                                    <div class="text-accent-600 text-sm mt-1 hidden" id="password_confirmation-error"></div>
                                </div>
                                
                                <!-- Code d'invitation (optionnel) -->
                                <div class="md:col-span-2">
                                    <div class="flex items-center justify-between mb-1">
                                        <label for="invitation_code" class="block text-sm font-medium text-text-primary">Code d'invitation (optionnel)</label>
                                        <span class="text-xs text-text-secondary">Pour devenir administrateur</span>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-ticket-alt text-text-light"></i>
                                        </div>
                                        <input
                                            id="invitation_code"
                                            name="invitation_code"
                                            type="text"
                                            class="form-input-focus block w-full pl-10 py-3 border border-primary-100 rounded-lg shadow-input focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                            placeholder="Code d'invitation pour accès administrateur"
                                        />
                                    </div>
                                    <div class="text-accent-600 text-sm mt-1 hidden" id="invitation_code-error"></div>
                                </div>
                            </div>
                                                        
                            <!-- Bouton d'inscription -->
                            <div class="mt-8">
                                <button
                                    type="submit"
                                    id="register-button"
                                    class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-button text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 transition transform hover:scale-[1.02] active:scale-[0.98]"
                                >
                                    <span id="button-text">S'inscrire</span>
                                    <span id="button-loader" class="hidden ml-2">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Footer de la card -->
                <div class="px-8 py-4 bg-primary-50 border-t border-primary-100 flex justify-center">
                    <p class="text-sm text-text-secondary">
                        Vous avez déjà un compte? 
                        <a href="{{ route('login') }}" class="font-medium text-primary-500 hover:text-primary-600 transition">
                            Connectez-vous
                        </a>
                    </p>
                </div>
            </div>
            
            <!-- Badges de sécurité -->
            <div class="mt-6 flex items-center justify-center space-x-6">
                <div class="flex items-center text-xs text-text-secondary hover-lift p-2 rounded-lg">
                    <div class="bg-primary-100 p-2 rounded-full mr-2">
                        <i class="fas fa-shield-alt text-primary-800"></i>
                    </div>
                    <span>Inscription sécurisée</span>
                </div>
                <div class="flex items-center text-xs text-text-secondary hover-lift p-2 rounded-lg">
                    <div class="bg-primary-100 p-2 rounded-full mr-2">
                        <i class="fas fa-lock text-primary-800"></i>
                    </div>
                    <span>Données cryptées</span>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="mt-8 text-center text-xs text-text-light">
                <p>&copy; 2025 Système de Gestion de Stock. Tous droits réservés.</p>
            </div>
        </div>
    </div>

    <!-- OTP Modal -->
    <div id="otp-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-xl shadow-card w-full max-w-md m-4 slide-in">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-primary-800">Vérification par email</h3>
                    <button type="button" id="close-modal" class="text-text-light hover:text-primary-500 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="text-center mb-6">
                    <div class="bg-primary-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-envelope-open-text text-primary-700 text-3xl"></i>
                    </div>
                    <p class="text-text-secondary">Nous avons envoyé un code de vérification à votre adresse email.</p>
                </div>
                
                <form id="otp-form" class="space-y-4">
                    <div class="space-y-2">
                        <label for="otp" class="block text-sm font-medium text-text-primary">Code de vérification</label>
                        <div class="flex justify-center space-x-2">
                            <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none pulse-animation shadow-input" required>
                            <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none shadow-input" required>
                            <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none shadow-input" required>
                            <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none shadow-input" required>
                            <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none shadow-input" required>
                            <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none shadow-input" required>
                        </div>
                        <input type="hidden" id="full-otp" name="otp">
                        <div id="otp-error" class="text-accent-600 text-sm mt-1 hidden"></div>
                    </div>
                    
                    <button type="submit" id="verify-button" class="w-full py-3 px-4 border border-transparent rounded-lg shadow-button text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 transition transform hover:scale-[1.02] active:scale-[0.98]">
                        Vérifier
                    </button>
                    
                    <div class="text-center mt-4">
                        <p class="text-sm text-text-secondary">
                            Vous n'avez pas reçu de code? 
                            <button type="button" id="resend-otp" class="text-primary-500 hover:text-primary-600 font-medium transition">
                                Renvoyer le code
                            </button>
                        </p>
                        <div id="resend-countdown" class="text-xs text-text-light mt-2 hidden">
                            Vous pourrez renvoyer un code dans <span id="countdown">60</span> secondes
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const registerForm = document.getElementById('register-form');
            const togglePasswordBtns = document.querySelectorAll('.toggle-password');
            const registerButton = document.getElementById('register-button');
            const buttonText = document.getElementById('button-text');
            const buttonLoader = document.getElementById('button-loader');
            const alertContainer = document.getElementById('alert-container');
            const otpModal = document.getElementById('otp-modal');
            const closeModal = document.getElementById('close-modal');
            const otpInputs = document.querySelectorAll('.otp-input');
            const fullOtpInput = document.getElementById('full-otp');
            const otpForm = document.getElementById('otp-form');
            const resendOtp = document.getElementById('resend-otp');
            const resendCountdown = document.getElementById('resend-countdown');
            const countdownSpan = document.getElementById('countdown');
            
            // Toggle password visibility
            togglePasswordBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('input');
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    
                    // Toggle eye icon
                    const eyeIcon = this.querySelector('i');
                    eyeIcon.classList.toggle('fa-eye');
                    eyeIcon.classList.toggle('fa-eye-slash');
                });
            });
            
            // OTP input handling
            otpInputs.forEach((input, index) => {
                input.addEventListener('keyup', function(e) {
                    // Move to next input
                    if (this.value.length === this.maxLength) {
                        if (index < otpInputs.length - 1) {
                            otpInputs[index + 1].focus();
                        } else {
                            this.blur();
                            // Compile full OTP
                            compileOtp();
                        }
                    }
                    
                    // Handle backspace
                    if (e.key === 'Backspace') {
                        if (index > 0 && this.value.length === 0) {
                            otpInputs[index - 1].focus();
                        }
                    }
                    
                    // Update pulse animation
                    otpInputs.forEach(inp => inp.classList.remove('pulse-animation'));
                    if (index < otpInputs.length - 1 && this.value.length === this.maxLength) {
                        otpInputs[index + 1].classList.add('pulse-animation');
                    }
                });
                
                input.addEventListener('keydown', function(e) {
                    // Allow only numbers
                    if (!/^\d$/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Tab') {
                        e.preventDefault();
                    }
                });
                
                input.addEventListener('focus', function() {
                    this.select();
                });
            });
            
            function compileOtp() {
                let otp = '';
                otpInputs.forEach(input => {
                    otp += input.value;
                });
                fullOtpInput.value = otp;
            }
            
            // Close OTP Modal
            closeModal.addEventListener('click', function() {
                otpModal.classList.add('hidden');
            });
            
            // Form submission
            registerForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Clear previous errors
                clearErrors();
                
                // Show loading state
                buttonText.textContent = 'Inscription en cours...';
                buttonLoader.classList.remove('hidden');
                registerButton.disabled = true;
                registerButton.classList.add('opacity-90');
                
                // Form submission
                const formData = new FormData(registerForm);
                
                fetch(registerForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show OTP Modal
                        otpModal.classList.remove('hidden');
                        otpInputs[0].focus();
                        
                        // Reset button state
                        resetButtonState();
                        
                        // Show confirmation message
                        showAlert('Un code de vérification a été envoyé à votre email', 'success');
                        
                        // Setup countdown for resend
                        startResendCountdown();
                    } else {
                        // Show errors
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                const errorDiv = document.getElementById(`${field}-error`);
                                if (errorDiv) {
                                    errorDiv.textContent = data.errors[field][0];
                                    errorDiv.classList.remove('hidden');
                                }
                            });
                        }
                        
                        // Show general error
                        if (data.message) {
                            showAlert(data.message, 'error');
                        }
                        
                        // Reset button state
                        resetButtonState();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Une erreur s\'est produite. Veuillez réessayer.', 'error');
                    
                    // Reset button state
                    resetButtonState();
                });
            });
            
            function resetButtonState() {
                buttonText.textContent = 'S\'inscrire';
                buttonLoader.classList.add('hidden');
                registerButton.disabled = false;
                registerButton.classList.remove('opacity-90');
            }
            
            // OTP form submission
            otpForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Compile OTP
                compileOtp();
                
                const otp = fullOtpInput.value;
                
                if (otp.length !== 6) {
                    document.getElementById('otp-error').textContent = 'Veuillez entrer un code à 6 chiffres';
                    document.getElementById('otp-error').classList.remove('hidden');
                    return;
                }
                
                document.getElementById('verify-button').textContent = 'Vérification...';
                document.getElementById('verify-button').disabled = true;
                document.getElementById('verify-button').classList.add('opacity-90');
                
                // Send OTP for verification
                const formData = new FormData();
                formData.append('otp', otp);

                fetch('{{ route("verification.verifyOtp") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    // Vérifiez si la réponse indique une redirection
                    if (response.redirected) {
                        window.location.href = response.url;
                        return null; // Arrêtez de traiter la réponse
                    }
                    return response.json();
                })
                .then(data => {
                    if (data === null) return; // La redirection a déjà été effectuée
                    
                    if (data.success) {
                        // Show success message
                        showAlert('Vérification réussie! Redirection...', 'success');
                        
                        // Close modal
                        otpModal.classList.add('hidden');
                        
                        // Redirect after a short delay
                        setTimeout(() => {
                            window.location.href = data.redirect || '/dashboard';
                        }, 1500);
                    } else {
                        document.getElementById('otp-error').textContent = data.message || 'Code OTP invalide';
                        document.getElementById('otp-error').classList.remove('hidden');
                        
                        // Reset button
                        resetVerifyButton();
                        
                        // Clear OTP inputs
                        otpInputs.forEach(input => {
                            input.value = '';
                        });
                        otpInputs[0].focus();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('otp-error').textContent = 'Une erreur s\'est produite';
                    document.getElementById('otp-error').classList.remove('hidden');
                    
                    // Reset button
                    resetVerifyButton();
                });
            });
            
            function resetVerifyButton() {
                document.getElementById('verify-button').textContent = 'Vérifier';
                document.getElementById('verify-button').disabled = false;
                document.getElementById('verify-button').classList.remove('opacity-90');
            }
            
            // Resend OTP
            // resendOtp.addEventListener('click', function() {
            //     if (this.classList.contains('disabled')) return;
                
            //     fetch('{{ route("verification.resendOtp") }}', {
            //         method: 'POST',
            //         headers: {
            //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            //         }
            //     })
            //     .then(response => response.json())
            //     .then(data => {
            //         if (data.success) {
            //             showAlert('Un nouveau code a été envoyé à votre email', 'success');
            //             startResendCountdown();
            //         } else {
            //             showAlert(data.message || 'Erreur lors de l\'envoi du code', 'error');
            //         }
            //     })
            //     .catch(error => {
            //         console.error('Error:', error);
            //         showAlert('Une erreur s\'est produite lors de l\'envoi du code', 'error');
            //     });
            // });
            resendOtp.addEventListener('click', function() {
    if (this.classList.contains('disabled')) return;
    
    // Indiquer visuellement que la demande est en cours
    resendOtp.textContent = 'Envoi en cours...';
    
    fetch('{{ route("verification.resendOtp") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        console.log('Réponse du serveur:', response);
        return response.json();
    })
    .then(data => {
        console.log('Données reçues:', data);
        if (data.success) {
            showAlert('Un nouveau code a été envoyé à votre email', 'success');
            startResendCountdown();
        } else {
            showAlert(data.message || 'Erreur lors de l\'envoi du code', 'error');
        }
        resendOtp.textContent = 'Renvoyer le code';
    })
    .catch(error => {
        console.error('Erreur complète:', error);
        showAlert('Une erreur s\'est produite lors de l\'envoi du code', 'error');
        resendOtp.textContent = 'Renvoyer le code';
    });
});
            
            // Start countdown for resend OTP
            function startResendCountdown() {
                let seconds = 60;
                resendOtp.classList.add('disabled', 'text-text-light', 'cursor-not-allowed');
                resendOtp.classList.remove('text-primary-500', 'hover:text-primary-600');
                resendCountdown.classList.remove('hidden');
                
                const interval = setInterval(() => {
                    seconds--;
                    countdownSpan.textContent = seconds;
                    
                    if (seconds <= 0) {
                        clearInterval(interval);
                        resendOtp.classList.remove('disabled', 'text-text-light', 'cursor-not-allowed');
                        resendOtp.classList.add('text-primary-500', 'hover:text-primary-600');
                        resendCountdown.classList.add('hidden');
                    }
                }, 1000);
            }
            
            // Function to clear form errors
            function clearErrors() {
                const errorDivs = document.querySelectorAll('[id$="-error"]');
                errorDivs.forEach(div => {
                    div.classList.add('hidden');
                });
            }
            
            // Function to show alerts
            function showAlert(message, type) {
                const alert = document.createElement('div');
                alert.className = `mb-4 p-4 rounded-lg shadow-md transform transition-all duration-300 translate-y-0 opacity-100 ${
                    type === 'success' 
                    ? 'bg-green-50 border-l-4 border-green-500' 
                    : 'bg-accent-50 border-l-4 border-accent-500'
                }`;
                
                alert.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas ${type === 'success' ? 'fa-check-circle text-green-500' : 'fa-exclamation-circle text-accent-500'}"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium ${type === 'success' ? 'text-green-800' : 'text-accent-800'}">${message}</p>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button type="button" class="inline-flex rounded-md p-1.5 ${
                                    type === 'success' ? 'text-green-500 hover:bg-green-100' : 'text-accent-500 hover:bg-accent-100'
                                } focus:outline-none">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                // Add to container
                alertContainer.appendChild(alert);
                
                // Add click event to close button
                alert.querySelector('button').addEventListener('click', () => {
                    dismissAlert(alert);
                });
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (alert.parentNode) {
                        dismissAlert(alert);
                    }
                }, 5000);
            }
            
            function dismissAlert(alert) {
                alert.classList.replace('translate-y-0', '-translate-y-3');
                alert.classList.replace('opacity-100', 'opacity-0');
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }
        });
    </script>
</body>
</html>