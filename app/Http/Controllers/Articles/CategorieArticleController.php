<?php

namespace App\Http\Controllers\Articles;

use App\Models\Article;
use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategorieArticleController extends Controller
{
    public function index()
    {
        // Solution alternative sans withCount avec pagination à 10 éléments
        $categories = Categorie::orderBy('created_at', 'desc')->paginate(2);
        
        // Calculer manuellement le nombre d'articles pour chaque catégorie
        foreach ($categories as $categorie) {
            $categorie->articles_count = Article::where('category_id', $categorie->id)->count();
        }
        
        return view('articles.categorie-article', compact('categories'));
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

            // Si c'est une requête AJAX, retourner une réponse JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Catégorie créée avec succès'
                ]);
            }

            // Sinon, redirection avec un message flash
            return redirect()->route('categories.index')->with('success', 'Catégorie créée avec succès.');
        } catch (\Exception $e) {
            // Si c'est une requête AJAX, retourner une réponse JSON d'erreur
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de la catégorie: ' . $e->getMessage(),
                    'errors' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : null
                ], 422);
            }

            // Sinon, redirection avec un message d'erreur
            return redirect()->back()->with('error', 'Erreur lors de la création de la catégorie.')->withErrors($e->errors() ?? [])->withInput();
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

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Catégorie modifiée avec succès'
                ]);
            }

            return redirect()->route('categories.index')->with('success', 'Catégorie modifiée avec succès.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la modification de la catégorie: ' . $e->getMessage(),
                    'errors' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : null
                ], 422);
            }

            return redirect()->back()->with('error', 'Erreur lors de la modification de la catégorie.')->withErrors($e->errors() ?? [])->withInput();
        }
    }

    public function destroy(Categorie $categorie, Request $request)
    {
        try {
            $categorie->delete();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Catégorie supprimée avec succès'
                ]);
            }

            return redirect()->route('categories.index')->with('success', 'Catégorie supprimée avec succès.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de la catégorie: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de la suppression de la catégorie.');
        }
    }
}