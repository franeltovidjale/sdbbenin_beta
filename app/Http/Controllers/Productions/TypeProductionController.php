<?php

namespace App\Http\Controllers\Productions;

use App\Models\Production;
use App\Models\TypeProduction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TypeProductionController extends Controller
{
    public function index()
    {
        // Pagination à 2 éléments comme dans l'exemple original
        $types = TypeProduction::orderBy('created_at', 'desc')->paginate(2);
        
        // Calculer manuellement le nombre de productions pour chaque type
        foreach ($types as $type) {
            $type->productions_count = Production::where('type_id', $type->id)->count();
        }
        
        return view('productions.type-production', compact('types'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:type_productions',
            ], [
                'name.required' => 'Le nom du type de production est obligatoire',
                'name.unique' => 'Ce type de production existe déjà'
            ]);

            $type = TypeProduction::create($validated);

            // Si c'est une requête AJAX, retourner une réponse JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Type de production créé avec succès'
                ]);
            }

            // Sinon, redirection avec un message flash
            return redirect()->route('types.index')->with('success', 'Type de production créé avec succès.');
        } catch (\Exception $e) {
            // Si c'est une requête AJAX, retourner une réponse JSON d'erreur
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création du type de production: ' . $e->getMessage(),
                    'errors' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : null
                ], 422);
            }

            // Sinon, redirection avec un message d'erreur
            return redirect()->back()->with('error', 'Erreur lors de la création du type de production.')->withErrors($e->errors() ?? [])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:type_productions,name,' . $id,
            ], [
                'name.required' => 'Le nom du type de production est obligatoire',
                'name.unique' => 'Ce type de production existe déjà'
            ]);

            $type = TypeProduction::findOrFail($id);
            $type->update($validated);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Type de production modifié avec succès'
                ]);
            }

            return redirect()->route('types.index')->with('success', 'Type de production modifié avec succès.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la modification du type de production: ' . $e->getMessage(),
                    'errors' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : null
                ], 422);
            }

            return redirect()->back()->with('error', 'Erreur lors de la modification du type de production.')->withErrors($e->errors() ?? [])->withInput();
        }
    }

    public function destroy(TypeProduction $typeProduction, Request $request)
    {
        try {
            $typeProduction->delete();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Type de production supprimé avec succès'
                ]);
            }

            return redirect()->route('types.index')->with('success', 'Type de production supprimé avec succès.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du type de production: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de la suppression du type de production.');
        }
    }
}