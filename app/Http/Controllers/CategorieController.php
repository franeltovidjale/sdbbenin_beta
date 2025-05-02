<?php


namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    
    public function index()
    {
        // Solution alternative sans withCount
        $categories = Categorie::orderBy('created_at', 'desc')->get();
        
        // Calculer manuellement le nombre d'articles pour chaque catégorie
        foreach ($categories as $categorie) {
            $categorie->articles_count = \App\Models\Article::where('category_id', $categorie->id)->count();
        }
        
        return view('apk.ajoutcategorie', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories',
            ], [
                'name.required' => 'Le nom de la catégorie est obligatoire',
                'name.unique' => 'Cette catégorie existe déjà'
            ]);

            $categorie = Categorie::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Catégorie créée avec succès'
            ]);
        } catch (\Exception $e) {
           

             // Redirection avec un message de succès
             return redirect()->route('categories.index')->with('success', 'Catégorie créée avec succès.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $id,
            ], [
                'name.required' => 'Le nom de la catégorie est obligatoire',
                'name.unique' => 'Cette catégorie existe déjà'
            ]);

            $categorie = Categorie::findOrFail($id);
            $categorie->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Catégorie modifiée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification de la catégorie: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Categorie $categorie)
    {
        try {
            $categorie->delete();
            return response()->json([
                'success' => true,
                'message' => 'Catégorie supprimée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la catégorie: ' . $e->getMessage()
            ], 500);
        }
    }
}