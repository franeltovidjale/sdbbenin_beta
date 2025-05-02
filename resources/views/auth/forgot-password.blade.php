{{-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Roboto, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f5;
        }

        .container {
            background-color: #ffffff;
            padding: 3rem;
            border-radius: 20px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
            transition: transform 0.3s ease;
            margin: 2rem;
        }
        .custom{
            list-style: none;
            color: red;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .description {
            color: #333;
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 2.5rem;
            padding: 1.5rem;
            background: #fafafa;
            border-radius: 12px;
            border: 1px solid #eee;
        }

        .form-group {
            margin-bottom: 2rem;
            position: relative;
        }

        .form-label {
            display: block;
            font-weight: 500;
            font-size: 0.9rem;
            color: #222;
            margin-bottom: 0.8rem;
            letter-spacing: 0.3px;
        }

        .form-input {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 2px solid #eee;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #ffffff;
        }

        .form-input:focus {
            outline: none;
            border-color: #000;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
        }

        .button-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 2.5rem;
        }

        .submit-button {
            background: #0066cc;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-button:hover {
            background: #0052a3;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            body {
                background: #ffffff;
                padding: 0;
                align-items: flex-start; /* Aligne le contenu en haut sur mobile */
            }

            .container {
                max-width: 100%;
                margin: 0;
                padding: 1.5rem;
                min-height: 100vh;
                border-radius: 0;
                box-shadow: none;
                display: flex;
                flex-direction: column;
            }

            .description {
                margin-bottom: 2rem;
                padding: 1rem;
                font-size: 0.9rem;
            }

            .form-group {
                margin-bottom: 1.5rem;
            }

            .form-input {
                padding: 0.875rem 1rem;
                font-size: 1rem;
            }

            .button-container {
                margin-top: auto; /* Pousse le bouton vers le bas */
                padding-bottom: 2rem;
            }

            .submit-button {
                width: 100%;
                padding: 1rem;
                text-align: center;
                font-size: 1rem;
            }

            /* Ajustements pour les très petits écrans */
            @media screen and (max-height: 500px) {
                .container {
                    padding-top: 1rem;
                }

                .description {
                    margin-bottom: 1rem;
                }

                .button-container {
                    padding-bottom: 1rem;
                }
            }

            /* Ajustements pour éviter le zoom sur les inputs iOS */
            @media screen and (max-width: 320px) {
                .form-input {
                    font-size: 16px;
                }
            }
        }

        /* Ajustements pour les écrans de taille moyenne */
        @media screen and (min-width: 769px) and (max-width: 1024px) {
            .container {
                max-width: 600px;
                margin: 1rem;
            }
        }

        /* Gestion de l'orientation paysage sur mobile */
        @media screen and (max-width: 768px) and (orientation: landscape) {
            .container {
                min-height: auto;
                margin: 1rem;
            }

            .button-container {
                margin-top: 2rem;
                padding-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <p class="description">
            Entrez simplement votre adresse e-mail et nous vous enverrons un lien pour le réinitialiser.
        </p>
       
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label for="email"  class="form-label">Adresse e-mail</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-input"
                    placeholder="votremail@exemple.com"
                    required 
                    autofocus
                >
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 custom" />

            <div class="button-container">
                <button type="submit" class="submit-button">
                    Réinitialiser
                </button>
            </div>
        </form>
    </div>
</body>
</html>