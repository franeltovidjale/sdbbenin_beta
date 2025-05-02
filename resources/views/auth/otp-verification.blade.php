{{-- <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Vérification OTP</title>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-center mb-6">Vérification de votre email</h2>
        
        <p class="text-center text-gray-600 mb-6">Un code de vérification a été envoyé à votre adresse email.</p>
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if(session('status'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('status') }}</p>
            </div>
        @endif
        
        <form method="POST" action="{{ route('verification.verifyOtp') }}">
            @csrf
            
            <div class="mb-6">
                <label for="otp" class="block text-gray-700 text-sm font-bold mb-2">Code OTP</label>
                <input id="otp" type="text" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('otp') border-red-500 @enderror" 
                    name="otp" required autofocus>
                
                @error('otp')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <button type="submit" 
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150">
                    Vérifier
                </button>
            </div>
        </form>
        
        <!-- Formulaire de renvoi séparé -->
        <div class="text-center mt-4 border-t pt-4">
            <form method="POST" action="{{ route('verification.resendOtp') }}">
                @csrf
                <button type="submit" 
                    class="text-blue-500 hover:text-blue-700 font-medium transition duration-150 flex items-center justify-center mx-auto">
                    <i class="fas fa-redo-alt mr-2"></i> Renvoyer le code
                </button>
            </form>
        </div>
    </div>

    <!-- Script pour faire apparaître un message après renvoi (facultatif) -->
    @if(session('status'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Met en évidence le message de statut
            const statusMessage = document.querySelector('[role="alert"]');
            if (statusMessage) {
                statusMessage.scrollIntoView({ behavior: 'smooth' });
                statusMessage.classList.add('animate-pulse');
                setTimeout(() => {
                    statusMessage.classList.remove('animate-pulse');
                }, 2000);
            }
        });
    </script>
    @endif
</body>
</html> --}}