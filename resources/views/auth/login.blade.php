{{-- <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion - Système de Gestion de Stock</title>
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
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Alert Container for errors/messages -->
    <div id="alert-container" class="fixed top-4 right-4 z-50 w-full max-w-sm"></div>

    <div class="min-h-screen flex justify-center items-center p-4">
        <div class="w-full max-w-md">
            <!-- Logo et titre -->
            <div class="text-center mb-8">
                <div class="inline-block p-4 rounded-full bg-blue-50 mb-4">
                    <svg class="w-12 h-12 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">Connexion</h1>
                <p class="text-gray-500 mt-2">Système de Gestion de Stock</p>
            </div>

            <!-- Card de connexion -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="h-2 bg-gradient-to-r from-primary-400 to-primary-600 animate-gradient"></div>
                
                <div class="p-8">
                    <form id="login-form" action="{{ route('login') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-5">
                            <!-- Email -->
                            <div>
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
                            
                            <!-- Password -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                                    <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-500 transition">
                                        Mot de passe oublié?
                                    </a>
                                </div>
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
                                        id="toggle-password"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                    >
                                        <i class="fas fa-eye text-gray-400 hover:text-gray-600 cursor-pointer"></i>
                                    </button>
                                </div>
                                <div class="text-red-500 text-sm mt-1 hidden" id="password-error"></div>
                            </div>
                            
                            <!-- Se souvenir de moi -->
                            <div class="flex items-center">
                                <input
                                    id="remember-me"
                                    name="remember"
                                    type="checkbox"
                                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded transition"
                                />
                                <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                                    Se souvenir de moi
                                </label>
                            </div>
                            
                            <!-- Bouton de connexion -->
                            <div>
                                <button
                                    type="submit"
                                    id="login-button"
                                    class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition transform hover:scale-[1.02] active:scale-[0.98]"
                                >
                                    <span id="button-text">Se connecter</span>
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
                        Vous n'avez pas de compte? 
                        <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:text-primary-500 transition">
                            Inscrivez-vous
                        </a>
                    </p>
                </div>
            </div>
            
            <!-- Badges de sécurité -->
            <div class="mt-6 flex items-center justify-center space-x-4">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-shield-alt text-green-500 mr-1"></i>
                    <span>Connexion sécurisée</span>
                </div>
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-lock text-green-500 mr-1"></i>
                    <span>Données cryptées</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('login-form');
            const togglePasswordBtn = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('password');
            const loginButton = document.getElementById('login-button');
            const buttonText = document.getElementById('button-text');
            const buttonLoader = document.getElementById('button-loader');
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');
            const alertContainer = document.getElementById('alert-container');
            
            // Toggle password visibility
            togglePasswordBtn.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle eye icon
                const eyeIcon = togglePasswordBtn.querySelector('i');
                eyeIcon.classList.toggle('fa-eye');
                eyeIcon.classList.toggle('fa-eye-slash');
            });
            
            // Form submission
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Clear previous errors
                if (emailError) emailError.classList.add('hidden');
                if (passwordError) passwordError.classList.add('hidden');
                
                // Show loading state
                buttonText.textContent = 'Connexion en cours...';
                buttonLoader.classList.remove('hidden');
                loginButton.disabled = true;
                
                // Get CSRF token
                const csrfToken = document.querySelector('input[name="_token"]').value;
                console.log('CSRF token:', csrfToken);
                
                // Form data
                const formData = new FormData(loginForm);
                
                // Log form data (for debugging)
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }
                
                // Send request
                fetch(loginForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (response.redirected) {
                        console.log('Redirection detected to:', response.url);
                        window.location.href = response.url;
                        return { success: true, redirect: response.url };
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success alert
                        showAlert('Connexion réussie! Redirection...', 'success');
                        
                        // Redirect after a short delay
                        setTimeout(() => {
                            window.location.href = data.redirect || '/glsam/achats-ventes';
                        }, 1000);
                    } else {
                        // Show errors
                        if (data.errors) {
                            if (data.errors.email) {
                                if (emailError) {
                                    emailError.textContent = data.errors.email[0];
                                    emailError.classList.remove('hidden');
                                } else {
                                    showAlert(data.errors.email[0], 'error');
                                }
                            }
                            
                            if (data.errors.password) {
                                if (passwordError) {
                                    passwordError.textContent = data.errors.password[0];
                                    passwordError.classList.remove('hidden');
                                } else {
                                    showAlert(data.errors.password[0], 'error');
                                }
                            }
                        }
                        
                        // Show general error
                        if (data.message) {
                            showAlert(data.message, 'error');
                        }
                        
                        // Reset button state
                        buttonText.textContent = 'Se connecter';
                        buttonLoader.classList.add('hidden');
                        loginButton.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error details:', error);
                    
                    // Si l'erreur est liée au CSRF, l'afficher spécifiquement
                    if (error.message && error.message.includes('CSRF')) {
                        showAlert('Erreur de sécurité: Token CSRF invalide. Veuillez rafraîchir la page.', 'error');
                    } else {
                        showAlert('Une erreur s\'est produite. Veuillez réessayer.', 'error');
                    }
                    
                    // Reset button state
                    buttonText.textContent = 'Se connecter';
                    buttonLoader.classList.add('hidden');
                    loginButton.disabled = false;
                });
            });
            
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
</html> --}}



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion - Système de Gestion de Stock</title>
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
        <div class="w-full max-w-md">
            <!-- Logo et titre -->
            <div class="text-center mb-8">
                <div class="inline-block p-4 rounded-full bg-primary-100 mb-4 shadow-lg">
                    <svg class="w-12 h-12 text-primary-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-primary-800">Connexion</h1>
                <p class="text-text-secondary mt-2">Système de Gestion de Stock</p>
            </div>

            <!-- Card de connexion -->
            <div class="bg-white rounded-xl shadow-card overflow-hidden">
                <div class="h-2 bg-gradient-to-r from-primary-500 to-primary-800 animate-gradient"></div>
                
                <div class="p-8">
                    <form id="login-form" action="{{ route('login') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-5">
                            <!-- Email -->
                            <div>
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
                            
                            <!-- Password -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label for="password" class="block text-sm font-medium text-text-primary">Mot de passe</label>
                                    <a href="{{ route('password.request') }}" class="text-sm text-primary-500 hover:text-primary-600 transition">
                                        Mot de passe oublié?
                                    </a>
                                </div>
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
                                        id="toggle-password"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                    >
                                        <i class="fas fa-eye text-text-light hover:text-primary-500 cursor-pointer transition"></i>
                                    </button>
                                </div>
                                <div class="text-accent-600 text-sm mt-1 hidden" id="password-error"></div>
                            </div>
                            
                            <!-- Se souvenir de moi -->
                            <div class="flex items-center">
                                <input
                                    id="remember-me"
                                    name="remember"
                                    type="checkbox"
                                    class="h-4 w-4 text-primary-500 focus:ring-primary-400 border-primary-100 rounded transition"
                                />
                                <label for="remember-me" class="ml-2 block text-sm text-text-secondary">
                                    Se souvenir de moi
                                </label>
                            </div>
                            
                            <!-- Bouton de connexion -->
                            <div>
                                <button
                                    type="submit"
                                    id="login-button"
                                    class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-button text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 transition transform hover:scale-[1.02] active:scale-[0.98]"
                                >
                                    <span id="button-text">Se connecter</span>
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
                        Vous n'avez pas de compte? 
                        <a href="{{ route('register') }}" class="font-medium text-primary-500 hover:text-primary-600 transition">
                            Inscrivez-vous
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
                    <span>Connexion sécurisée</span>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('login-form');
            const togglePasswordBtn = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('password');
            const loginButton = document.getElementById('login-button');
            const buttonText = document.getElementById('button-text');
            const buttonLoader = document.getElementById('button-loader');
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');
            const alertContainer = document.getElementById('alert-container');
            
            // Toggle password visibility
            togglePasswordBtn.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle eye icon
                const eyeIcon = togglePasswordBtn.querySelector('i');
                eyeIcon.classList.toggle('fa-eye');
                eyeIcon.classList.toggle('fa-eye-slash');
            });
            
            // Form submission
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Clear previous errors
                if (emailError) emailError.classList.add('hidden');
                if (passwordError) passwordError.classList.add('hidden');
                
                // Show loading state
                buttonText.textContent = 'Connexion en cours...';
                buttonLoader.classList.remove('hidden');
                loginButton.disabled = true;
                loginButton.classList.add('opacity-90');
                
                // Get CSRF token
                const csrfToken = document.querySelector('input[name="_token"]').value;
                
                // Form data
                const formData = new FormData(loginForm);
                
                // Send request
                fetch(loginForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (response.redirected) {
                        window.location.href = response.url;
                        return { success: true, redirect: response.url };
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success alert
                        showAlert('Connexion réussie! Redirection...', 'success');
                        
                        // Redirect after a short delay
                        setTimeout(() => {
                            window.location.href = data.redirect || '/glsam/achats-ventes';
                        }, 1000);
                    } else {
                        // Show errors
                        if (data.errors) {
                            if (data.errors.email) {
                                if (emailError) {
                                    emailError.textContent = data.errors.email[0];
                                    emailError.classList.remove('hidden');
                                } else {
                                    showAlert(data.errors.email[0], 'error');
                                }
                            }
                            
                            if (data.errors.password) {
                                if (passwordError) {
                                    passwordError.textContent = data.errors.password[0];
                                    passwordError.classList.remove('hidden');
                                } else {
                                    showAlert(data.errors.password[0], 'error');
                                }
                            }
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
                    // Si l'erreur est liée au CSRF, l'afficher spécifiquement
                    if (error.message && error.message.includes('CSRF')) {
                        showAlert('Erreur de sécurité: Token CSRF invalide. Veuillez rafraîchir la page.', 'error');
                    } else {
                        showAlert('Une erreur s\'est produite. Veuillez réessayer.', 'error');
                    }
                    
                    // Reset button state
                    resetButtonState();
                });
            });
            
            function resetButtonState() {
                buttonText.textContent = 'Se connecter';
                buttonLoader.classList.add('hidden');
                loginButton.disabled = false;
                loginButton.classList.remove('opacity-90');
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