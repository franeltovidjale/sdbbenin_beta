<?php

namespace App\Http\Controllers\Articles;

use App\Models\Article;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(1);
        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = Categorie::all();
        return view('articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:articles|max:255',
                'category_id' => 'required|exists:categories,id',
                'unit_price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0'
            ], [
                'name.required' => 'Le nom du produit est obligatoire',
                'name.unique' => 'Ce produit existe déjà',
                'name.max' => 'Le nom du produit ne peut pas dépasser 255 caractères',
                'category_id.required' => 'La catégorie est obligatoire',
                'category_id.exists' => 'La catégorie sélectionnée n\'existe pas',
                'unit_price.required' => 'Le prix unitaire est obligatoire',
                'unit_price.numeric' => 'Le prix unitaire doit être un nombre',
                'unit_price.min' => 'Le prix unitaire ne peut pas être négatif',
                'stock_quantity.required' => 'La quantité en stock est obligatoire',
                'stock_quantity.integer' => 'La quantité doit être un nombre entier',
                'stock_quantity.min' => 'La quantité ne peut pas être négative'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $article = Article::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'unit_price' => $request->unit_price,
                'stock_quantity' => $request->stock_quantity
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Produit créé avec succès',
                'article' => $article
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du produit: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la création du produit'
            ], 500);
        }
    }

    public function edit(Article $article)
    {
        $categories = Categorie::all();
        return view('articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:articles,name,'.$article->id,
                'category_id' => 'required|exists:categories,id',
                'unit_price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0'
            ], [
                'name.required' => 'Le nom du produit est obligatoire',
                'name.max' => 'Le nom du produit ne peut pas dépasser 255 caractères',
                'name.unique' => 'Ce nom de produit existe déjà',
                'category_id.required' => 'La catégorie est obligatoire',
                'category_id.exists' => 'La catégorie sélectionnée n\'existe pas',
                'unit_price.required' => 'Le prix unitaire est obligatoire',
                'unit_price.numeric' => 'Le prix unitaire doit être un nombre',
                'unit_price.min' => 'Le prix unitaire ne peut pas être négatif',
                'stock_quantity.required' => 'La quantité en stock est obligatoire',
                'stock_quantity.integer' => 'La quantité doit être un nombre entier',
                'stock_quantity.min' => 'La quantité ne peut pas être négative'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $article->update([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'unit_price' => $request->unit_price,
                'stock_quantity' => $request->stock_quantity
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Produit modifié avec succès',
                'article' => $article
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la modification du produit: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la modification du produit'
            ], 500);
        }
    }

    public function destroy(Article $article)
    {
        try {
            $article->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produit supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du produit: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du produit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $term = $request->get('term', '');
            
            $articles = Article::where('name', 'LIKE', "%{$term}%")
                ->orWhereHas('category', function($query) use ($term) {
                    $query->where('name', 'LIKE', "%{$term}%");
                })
                ->orWhere('unit_price', 'LIKE', "%{$term}%")
                ->orWhere('stock_quantity', 'LIKE', "%{$term}%")
                ->with('category')
                ->latest()
                ->paginate(10);

            return response()->json([
                'success' => true,
                'articles' => $articles->map(function($article) {
                    return [
                        'id' => $article->id,
                        'name' => $article->name,
                        'unit_price' => $article->unit_price,
                        'stock_quantity' => $article->stock_quantity,
                        'category' => ['name' => $article->category->name],
                        'created_at' => $article->created_at,
                        'in_stock' => $article->in_stock
                    ];
                }),
                'pagination' => [
                    'total' => $articles->total(),
                    'per_page' => $articles->perPage(),
                    'current_page' => $articles->currentPage(),
                    'last_page' => $articles->lastPage(),
                    'from' => $articles->firstItem(),
                    'to' => $articles->lastItem()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur de recherche: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la recherche'
            ], 500);
        }
    }
}
