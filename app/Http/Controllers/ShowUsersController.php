<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShowUsersController extends Controller
{
    //
    /**
     * Middleware pour s'assurer que seuls les administrateurs ont accès
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Afficher la liste des utilisateurs
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('apk.usersliste', compact('users'));
    }

    /**
     * Bloquer un utilisateur
     */
    public function block(User $user)
    {
        // Vérifier si l'utilisateur n'est pas en train de se bloquer lui-même
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Vous ne pouvez pas vous bloquer vous-même.');
        }

        $user->is_active = false;
        $user->save();

        return redirect()->route('users.index')
            ->with('success', "L'utilisateur {$user->name} a été bloqué avec succès.");
    }

    /**
     * Activer un utilisateur
     */
    public function activate(User $user)
    {
        $user->is_active = true;
        $user->save();

        return redirect()->route('users.index')
            ->with('success', "L'utilisateur {$user->name} a été activé avec succès.");
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy(User $user)
    {
        // Vérifier si l'utilisateur n'est pas en train de se supprimer lui-même
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "L'utilisateur {$name} a été supprimé avec succès.");
    }
}
