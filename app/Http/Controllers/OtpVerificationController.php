<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\OtpVerification;
use App\Models\InvitationCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;

class OtpVerificationController extends Controller
{
    public function show()
    {
        // Vérifier si les données d'inscription sont présentes en session
        if (!session()->has('registration_data')) {
            return redirect()->route('register')
                ->with('error', 'Veuillez compléter le formulaire d\'inscription');
        }
        
        return view('auth.otp-verification');
    }
    
    public function verify(Request $request)
    {
        Log::info('Vérification OTP initiée', [
            'request_data' => $request->all(),
            'session_id' => session()->getId()
        ]);
        
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);
        
        // Récupérer les données d'inscription de la session
        $registrationData = session('registration_data');
        
        if (!$registrationData) {
            Log::error('Données d\'inscription non trouvées en session');
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session expirée. Veuillez vous réinscrire.'
                ]);
            }
            
            return redirect()->route('register')
                ->with('error', 'Session expirée. Veuillez vous réinscrire.');
        }
        
        // Vérifier si l'OTP est correct et non expiré
        if ($request->otp !== $registrationData['otp'] || now()->isAfter($registrationData['otp_expires_at'])) {
            Log::warning('OTP invalide ou expiré', [
                'submitted_otp' => $request->otp,
                'stored_otp' => $registrationData['otp'],
                'expires_at' => $registrationData['otp_expires_at']
            ]);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Code OTP invalide ou expiré'
                ]);
            }
            
            return back()->withErrors(['otp' => 'Code OTP invalide ou expiré']);
        }
        
        Log::info('OTP validé, création de l\'utilisateur');
        
        // Créer l'utilisateur maintenant que l'OTP est validé
        $user = User::create([
            'firstname' => $registrationData['firstname'],
            'lastname' => $registrationData['lastname'],
            'name' => $registrationData['firstname'] . ' ' . $registrationData['lastname'],
            'email' => $registrationData['email'],
            'password' => Hash::make($registrationData['password']),
            'is_admin' => $registrationData['is_admin'],
            'email_verified_at' => now(), // L'email est vérifié par l'OTP
        ]);
        
        // Marquer le code d'invitation comme utilisé si présent
        if (!empty($registrationData['invitation_code'])) {
            $inviteCode = InvitationCode::where('code', $registrationData['invitation_code'])->first();
            if ($inviteCode) {
                $inviteCode->is_used = true;
                $inviteCode->used_by = $user->id;
                $inviteCode->used_at = now();
                $inviteCode->save();
                
                Log::info('Code d\'invitation marqué comme utilisé', [
                    'code' => $registrationData['invitation_code'],
                    'user_id' => $user->id
                ]);
            }
        }
        
        // Effacer les données de session
        session()->forget('registration_data');
        
        // Déclencher l'événement Registered
        event(new Registered($user));
        
        // Connecter l'utilisateur
        Auth::login($user);
        
        Log::info('Utilisateur créé et connecté avec succès', [
            'user_id' => $user->id,
            'is_admin' => $user->is_admin
        ]);
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Compte créé avec succès',
                'redirect' => route('achatvente')
            ]);
        }
        
        return redirect()->route('achatvente')
            ->with('success', 'Compte créé avec succès. Bienvenue!');
    }
    
    public function resend(Request $request)
    {
        // Récupérer les données d'inscription
        $registrationData = session('registration_data');
        
        if (!$registrationData) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session expirée. Veuillez vous réinscrire.'
                ]);
            }
            
            return redirect()->route('register')
                ->with('error', 'Session expirée. Veuillez vous réinscrire.');
        }
        
        // Générer un nouvel OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $otpExpires = now()->addMinutes(15);
        
        // Mettre à jour les données de session
        $registrationData['otp'] = $otp;
        $registrationData['otp_expires_at'] = $otpExpires;
        session(['registration_data' => $registrationData]);
        
        // Envoyer l'email avec le nouvel OTP
        try {
            Mail::to($registrationData['email'])->send(new OtpVerification($otp, $registrationData));
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Un nouveau code a été envoyé à votre email.'
                ]);
            }
            
            return back()->with('success', 'Un nouveau code a été envoyé à votre email.');
        } catch (\Exception $e) {
            Log::error('Erreur lors du renvoi de l\'email OTP', [
                'message' => $e->getMessage()
            ]);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage()
                ]);
            }
            
            return back()->withErrors([
                'email' => 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage()
            ]);
        }
    }
}