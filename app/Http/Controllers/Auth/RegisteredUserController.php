<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Payment;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Mail\OtpVerification;
use App\Models\InvitationCode;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */



    public function store(Request $request)
{
    Log::info('Début de l\'inscription', [
        'request_data' => $request->except(['password', 'password_confirmation']),
        'is_ajax' => $request->ajax(),
        'wants_json' => $request->wantsJson()
    ]);
    
    // Validation des données de base
    $request->validate([
        'firstname' => ['required', 'string', 'max:255'],
        'lastname' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'invitation_code' => ['nullable', 'string'], // Code d'invitation optionnel
    ]);

    Log::info('Validation des données réussie');
    
    // Vérifier si le code d'invitation est fourni et valide
    $isAdmin = false;
    $adminCount = User::where('is_admin', true)->count();
    
    if ($request->has('invitation_code') && !empty($request->invitation_code)) {
        Log::info('Vérification du code d\'invitation', ['code' => $request->invitation_code]);
        
        $inviteCode = InvitationCode::where('code', $request->invitation_code)
                        ->where('expires_at', '>', now())
                        ->where('is_used', false)
                        ->first();
                        
        if ($inviteCode && $inviteCode->role === 'admin') {
            // Vérifier si la limite d'admins est atteinte
            if ($adminCount >= 3) {
                Log::warning('Tentative de création d\'un admin au-delà de la limite', [
                    'admin_count' => $adminCount,
                    'limit' => 3
                ]);
                
                throw ValidationException::withMessages([
                    'invitation_code' => 'Le nombre maximum d\'administrateurs est atteint.'
                ]);
            }
            $isAdmin = true;
            Log::info('Code d\'invitation admin valide', ['admin_status' => $isAdmin]);
        } elseif ($request->invitation_code) {
            Log::warning('Code d\'invitation invalide', ['code' => $request->invitation_code]);
            
            throw ValidationException::withMessages([
                'invitation_code' => 'Code d\'invitation invalide ou expiré.'
            ]);
        }
    }

    // Générer un OTP à 6 chiffres
    $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $otpExpires = now()->addMinutes(15);
    
    Log::info('OTP généré', [
        'otp' => $otp,
        'expires_at' => $otpExpires->format('Y-m-d H:i:s')
    ]);
    
    // Stocker temporairement les données d'inscription
    $registrationData = $request->all();
    $registrationData['is_admin'] = $isAdmin;
    $registrationData['otp'] = $otp;
    $registrationData['otp_expires_at'] = $otpExpires;
    
    // Stocker les données dans la session
    session(['registration_data' => $registrationData]);
    
    Log::info('Données stockées en session', [
        'session_id' => session()->getId(),
        'session_keys' => array_keys(session()->all()),
        'has_registration_data' => session()->has('registration_data')
    ]);
    
    // Envoyer l'email avec OTP
    try {
        Mail::to($request->email)->send(new OtpVerification($otp, $registrationData));
        Log::info('Email OTP envoyé avec succès', ['email' => $request->email]);
    } catch (\Exception $e) {
        Log::error('Erreur lors de l\'envoi de l\'email OTP', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage()
            ]);
        }
        
        return back()->withErrors([
            'email' => 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage()
        ])->withInput($request->except(['password', 'password_confirmation']));
    }
    
    // Avant la redirection
    Log::info('Préparation de la redirection vers la page OTP', [
        'route_name' => 'verification.otp',
        'url_complet' => route('verification.otp')
    ]);
    
    if ($request->ajax() || $request->wantsJson()) {
        Log::info('Envoi de la réponse JSON');
        return response()->json([
            'success' => true,
            'redirect' => route('verification.otp')
        ]);
    }
    
    Log::info('Redirection HTTP standard');
    return redirect()->route('verification.otp');
}



}
