<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Your existing styles */
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Vérification de votre compte</h1>
        </div>
        <div class="content">
            <p>Bonjour {{ $registration_data['firstname'] ?? 'Utilisateur' }} {{ $registration_data['lastname'] ?? '' }},</p>
            
            <p>Nous avons reçu une demande d'inscription sur notre système de gestion de stock. Pour finaliser votre inscription, veuillez utiliser le code de vérification ci-dessous :</p>
            
            <div class="otp-container">
                <div class="otp-code">{{ $otp }}</div>
            </div>
            
            <p>Ce code est valable pendant 15 minutes. Si vous n'avez pas fait cette demande, vous pouvez ignorer cet email.</p>
            
            <div class="note">
                <strong>Note de sécurité :</strong> Ne partagez jamais ce code avec qui que ce soit. Notre équipe ne vous demandera jamais ce code par téléphone ou par email.
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Système de Gestion de Stock. Tous droits réservés.
        </div>
    </div>
</body>
</html>