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
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
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
            box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.2);
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
                box-shadow: 0 0 0 0 rgba(14, 165, 233, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(14, 165, 233, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(14, 165, 233, 0);
            }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Alert Container for errors/messages -->
    <div id="alert-container" class="fixed top-4 right-4 z-50 w-full max-w-sm"></div>

    <div class="min-h-screen flex justify-center items-center p-4">
        <div class="w-full max-w-3xl">
            <!-- Logo et titre -->
            <div class="text-center mb-8">
                <div class="inline-block p-4 rounded-full bg-blue-50 mb-4">
                    <svg class="w-12 h-12 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">Créer un compte</h1>
                <p class="text-gray-500 mt-2">Système de Gestion de Stock</p>
            </div>

            <!-- Card d'inscription -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="h-2 bg-gradient-to-r from-primary-400 to-primary-600 animate-gradient"></div>
                
                <div class="p-8">
                    <form id="register-form" action="{{ route('register') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-5">
                            <!-- Formulaire principal -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Prénom -->
                                <div>
                                    <label for="firstname" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <input
                                            id="firstname"
                                            name="firstname"
                                            type="text"
                                            required
                                            class="form-input-focus block w-full pl-10 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                            placeholder="John"
                                        />
                                    </div>
                                    <div class="text-red-500 text-sm mt-1 hidden" id="firstname-error"></div>
                                </div>
                                
                                <!-- Nom -->
                                <div>
                                    <label for="lastname" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <input
                                            id="lastname"
                                            name="lastname"
                                            type="text"
                                            required
                                            class="form-input-focus block w-full pl-10 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                            placeholder="Doe"
                                        />
                                    </div>
                                    <div class="text-red-500 text-sm mt-1 hidden" id="lastname-error"></div>
                                </div>
                                
                                <!-- Email -->
                                <div class="md:col-span-2">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400"></i>
                                        </div>
                                        <input
                                            id="email"
                                            name="email"
                                            type="email"
                                            required
                                            class="form-input-focus block w-full pl-10 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                            placeholder="vous@exemple.com"
                                        />
                                    </div>
                                    <div class="text-red-500 text-sm mt-1 hidden" id="email-error"></div>
                                </div>
                                
                                {{-- <!-- Pays -->
                                <div>
                                    <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-globe text-gray-400"></i>
                                        </div>
                                        <select
                                            id="country"
                                            name="country"
                                            required
                                            class="form-input-focus block w-full pl-10 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                        >
                                            <option value="" disabled selected>Sélectionnez un pays</option>
                                            <option value="BJ">Bénin</option>
                                            <option value="CI">Côte d'Ivoire</option>
                                            <option value="SN">Sénégal</option>
                                            <option value="TG">Togo</option>
                                            <option value="CM">Cameroun</option>
                                            <option value="ML">Mali</option>
                                            <option value="BF">Burkina Faso</option>
                                            <option value="NE">Niger</option>
                                            <option value="GH">Ghana</option>
                                        </select>
                                    </div>
                                    <div class="text-red-500 text-sm mt-1 hidden" id="country-error"></div>
                                </div>
                                
                                <!-- Localité -->
                                <div>
                                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Localité</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                                        </div>
                                        <input
                                            id="location"
                                            name="location"
                                            type="text"
                                            required
                                            class="form-input-focus block w-full pl-10 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                            placeholder="Votre localité"
                                        />
                                    </div>
                                    <div class="text-red-500 text-sm mt-1 hidden" id="location-error"></div>
                                </div> --}}
                                
                                <!-- Mot de passe -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-gray-400"></i>
                                        </div>
                                        <input
                                            id="password"
                                            name="password"
                                            type="password"
                                            required
                                            class="form-input-focus block w-full pl-10 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                            placeholder="••••••••"
                                        />
                                        <button
                                            type="button"
                                            class="toggle-password absolute inset-y-0 right-0 pr-3 flex items-center"
                                        >
                                            <i class="fas fa-eye text-gray-400 hover:text-gray-600 cursor-pointer"></i>
                                        </button>
                                    </div>
                                    <div class="text-red-500 text-sm mt-1 hidden" id="password-error"></div>
                                </div>
                                
                                <!-- Confirmation mot de passe -->
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmation du mot de passe</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-gray-400"></i>
                                        </div>
                                        <input
                                            id="password_confirmation"
                                            name="password_confirmation"
                                            type="password"
                                            required
                                            class="form-input-focus block w-full pl-10 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                            placeholder="••••••••"
                                        />
                                        <button
                                            type="button"
                                            class="toggle-password absolute inset-y-0 right-0 pr-3 flex items-center"
                                        >
                                            <i class="fas fa-eye text-gray-400 hover:text-gray-600 cursor-pointer"></i>
                                        </button>
                                    </div>
                                    <div class="text-red-500 text-sm mt-1 hidden" id="password_confirmation-error"></div>
                                </div>
                                
                                <!-- Code d'invitation (optionnel) -->
                                <div class="md:col-span-2">
                                    <div class="flex items-center justify-between mb-1">
                                        <label for="invitation_code" class="block text-sm font-medium text-gray-700">Code d'invitation (optionnel)</label>
                                        <span class="text-xs text-gray-500">Pour devenir administrateur</span>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-ticket-alt text-gray-400"></i>
                                        </div>
                                        <input
                                            id="invitation_code"
                                            name="invitation_code"
                                            type="text"
                                            class="form-input-focus block w-full pl-10 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                            placeholder="Code d'invitation pour accès administrateur"
                                        />
                                    </div>
                                    <div class="text-red-500 text-sm mt-1 hidden" id="invitation_code-error"></div>
                                </div>
                            </div>
                                                        
                            <!-- Bouton d'inscription -->
                            <div class="mt-8">
                                <button
                                    type="submit"
                                    id="register-button"
                                    class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition transform hover:scale-[1.02] active:scale-[0.98]"
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
                <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-center">
                    <p class="text-sm text-gray-600">
                        Vous avez déjà un compte? 
                        <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500 transition">
                            Connectez-vous
                        </a>
                    </p>
                </div>
            </div>
            
            <!-- Badges de sécurité -->
            <div class="mt-6 flex items-center justify-center space-x-4">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-shield-alt text-green-500 mr-1"></i>
                    <span>Inscription sécurisée</span>
                </div>
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-lock text-green-500 mr-1"></i>
                    <span>Données cryptées</span>
                </div>
            </div>
        </div>
    </div>

    <!-- OTP Modal -->
    <div id="otp-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md m-4 slide-in">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Vérification par email</h3>
                    <button type="button" id="close-modal" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="text-center mb-6">
                    <div class="bg-blue-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-envelope-open-text text-primary-500 text-3xl"></i>
                    </div>
                    <p class="text-gray-600">Nous avons envoyé un code de vérification à votre adresse email.</p>
                </div>
                
                <form id="otp-form" class="space-y-4">
                    <div class="space-y-2">
                        <label for="otp" class="block text-sm font-medium text-gray-700">Code de vérification</label>
                        <div class="flex justify-center space-x-2">
                            <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border rounded-lg pulse-animation" required>
                            <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border rounded-lg" required>
                            <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border rounded-lg" required>
                            <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border rounded-lg" required>
                            <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border rounded-lg" required>
                            <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border rounded-lg" required>
                        </div>
                        <input type="hidden" id="full-otp" name="otp">
                        <div id="otp-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    
                    <button type="submit" id="verify-button" class="w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none">
                        Vérifier
                    </button>
                    
                    <div class="text-center mt-4">
                        <p class="text-sm text-gray-600">
                            Vous n'avez pas reçu de code? 
                            <button type="button" id="resend-otp" class="text-primary-600 hover:text-primary-500 font-medium">
                                Renvoyer le code
                            </button>
                        </p>
                        <div id="resend-countdown" class="text-xs text-gray-500 mt-2 hidden">
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
                        buttonText.textContent = 'S\'inscrire';
                        buttonLoader.classList.add('hidden');
                        registerButton.disabled = false;
                        
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
                        buttonText.textContent = 'S\'inscrire';
                        buttonLoader.classList.add('hidden');
                        registerButton.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Une erreur s\'est produite. Veuillez réessayer.', 'error');
                    
                    // Reset button state
                    buttonText.textContent = 'S\'inscrire';
                    buttonLoader.classList.add('hidden');
                    registerButton.disabled = false;
                });
            });
            
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
                
                // Send OTP for verification
                // fetch('{{ route("verification.verifyOtp") }}', {
                //     // fetch('{{ route("verification.verifyOtp") }}', {
                //     method: 'POST',
                //     headers: {
                //         'Content-Type': 'application/json',
                //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                //     },
                //     body: JSON.stringify({ otp: otp })
                // })
                // .then(response => response.json())
                // .then(data => {
                //     if (data.success) {
                //         // Show success message
                //         showAlert('Vérification réussie! Redirection...', 'success');
                        
                //         // Close modal
                //         otpModal.classList.add('hidden');
                        
                //         // Redirect after a short delay
                //         setTimeout(() => {
                //             window.location.href = data.redirect || '/dashboard';
                //         }, 1500);
                //     } else {
                //         document.getElementById('otp-error').textContent = data.message || 'Code OTP invalide';
                //         document.getElementById('otp-error').classList.remove('hidden');
                        
                //         // Reset button
                //         document.getElementById('verify-button').textContent = 'Vérifier';
                //         document.getElementById('verify-button').disabled = false;
                        
                //         // Clear OTP inputs
                //         otpInputs.forEach(input => {
                //             input.value = '';
                //         });
                //         otpInputs[0].focus();
                //     }
                // })
                // .catch(error => {
                //     console.error('Error:', error);
                //     document.getElementById('otp-error').textContent = 'Une erreur s\'est produite';
                //     document.getElementById('otp-error').classList.remove('hidden');
                    
                //     // Reset button
                //     document.getElementById('verify-button').textContent = 'Vérifier';
                //     document.getElementById('verify-button').disabled = false;
                // });

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
        document.getElementById('verify-button').textContent = 'Vérifier';
        document.getElementById('verify-button').disabled = false;
        
        // Clear OTP inputs
        otpInputs.forEach(input => {
            input.value = '';
        });
        otpInputs[0].focus();
    }
})

            });
            
            // Resend OTP
            resendOtp.addEventListener('click', function() {
                if (this.classList.contains('disabled')) return;
                
                fetch('{{ route("verification.resendOtp") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('Un nouveau code a été envoyé à votre email', 'success');
                        startResendCountdown();
                    } else {
                        showAlert(data.message || 'Erreur lors de l\'envoi du code', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Une erreur s\'est produite lors de l\'envoi du code', 'error');
                });
            });
            
            // Start countdown for resend OTP
            function startResendCountdown() {
                let seconds = 60;
                resendOtp.classList.add('disabled', 'text-gray-400', 'cursor-not-allowed');
                resendOtp.classList.remove('text-primary-600', 'hover:text-primary-500');
                resendCountdown.classList.remove('hidden');
                
                const interval = setInterval(() => {
                    seconds--;
                    countdownSpan.textContent = seconds;
                    
                    if (seconds <= 0) {
                        clearInterval(interval);
                        resendOtp.classList.remove('disabled', 'text-gray-400', 'cursor-not-allowed');
                        resendOtp.classList.add('text-primary-600', 'hover:text-primary-500');
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
                    type === 'success' ? 'bg-green-50 text-green-800 border-l-4 border-green-500' : 'bg-red-50 text-red-800 border-l-4 border-red-500'
                }`;
                
                alert.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas ${type === 'success' ? 'fa-check-circle text-green-500' : 'fa-exclamation-circle text-red-500'}"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">${message}</p>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button type="button" class="inline-flex rounded-md p-1.5 ${
                                    type === 'success' ? 'text-green-500 hover:bg-green-100' : 'text-red-500 hover:bg-red-100'
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
                    alert.classList.replace('translate-y-0', '-translate-y-3');
                    alert.classList.replace('opacity-100', 'opacity-0');
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                });
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.classList.replace('translate-y-0', '-translate-y-3');
                        alert.classList.replace('opacity-100', 'opacity-0');
                        setTimeout(() => {
                            alert.remove();
                        }, 300);
                    }
                }, 5000);
            }
        });
    </script>
</body>
</html>