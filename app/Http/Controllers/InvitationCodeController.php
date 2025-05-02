<?php

namespace App\Http\Controllers;

use App\Models\InvitationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvitationCodeController extends Controller
{
    public function index()
    {
        // Récupérer tous les codes d'invitation
        $invitationCodes = InvitationCode::latest()->get();
        
        return view('apk.invitation-codes', compact('invitationCodes'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|string|in:admin,user',
            'expiration_days' => 'required|integer|min:1|max:365',
        ]);
        
        // Générer un nouveau code
        $code = strtoupper(Str::random(12));
        
        // Créer le code d'invitation
        $invitationCode = InvitationCode::create([
            'code' => $code,
            'role' => $request->role,
            'is_used' => false,
            'expires_at' => now()->addDays($request->expiration_days),
        ]);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Code d\'invitation créé avec succès',
                'code' => $invitationCode
            ]);
        }
        
        return back()->with('success', 'Code d\'invitation créé avec succès');
    }
    
    public function destroy(InvitationCode $invitationCode)
    {
        try {
            $invitationCode->delete();
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Code d\'invitation supprimé avec succès'
                ]);
            }
            
            return back()->with('success', 'Code d\'invitation supprimé avec succès');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
                ]);
            }
            
            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }
}