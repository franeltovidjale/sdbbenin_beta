<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function create()
    {
        $categories = Categorie::all();
        return view('apk.ajoutarticles', compact('categories'));
    }

    public function store(Request $request)
    {     
        try {         
            $validator = Validator::make($request->all(), [             
                'name' => 'required|string|unique:articles|max:255',  
                'buy_price' => 'required|numeric|min:0',
                'normal_price' => [
                    'required',
                    'numeric', 
                    'min:0',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($value <= $request->buy_price) {
                            $fail('Le prix de vente doit être supérieur au prix d\'achat.');
                        }
                    },
                ],
                'category_id' => 'required|exists:categories,id',
                'quantite' => 'required|integer|min:0',
                'images' => 'nullable|array|max:5', // Maximum 5 images
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Validation pour chaque image
            ], [
                'name.required' => 'Le nom de l\'article est obligatoire',
                'name.unique' => 'Le nom de l\'article existe déjà',
                'name.max' => 'Le nom de l\'article ne peut pas dépasser 255 caractères',
                'buy_price.required' => 'Le prix d\'achat est obligatoire',
                'buy_price.numeric' => 'Le prix d\'achat doit être un nombre',
                'buy_price.min' => 'Le prix d\'achat ne peut pas être négatif',
                'normal_price.required' => 'Le prix de vente est obligatoire',
                'normal_price.numeric' => 'Le prix de vente doit être un nombre',
                'normal_price.min' => 'Le prix de vente ne peut pas être négatif',
                'category_id.required' => 'La catégorie est obligatoire',
                'category_id.exists' => 'La catégorie sélectionnée n\'existe pas',
                'quantite.required' => 'La quantité est obligatoire',
                'quantite.integer' => 'La quantité doit être un nombre entier',
                'quantite.min' => 'La quantité ne peut pas être négative',
                'images.max' => 'Vous ne pouvez pas télécharger plus de 5 images',
                'images.*.image' => 'Les fichiers doivent être des images',
                'images.*.mimes' => 'Les images doivent être au format : jpeg, png, jpg ou gif',
                'images.*.max' => 'Les images ne doivent pas dépasser 2Mo'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Créer l'article
            $article = Article::create([
                'name' => $request->name,
                'buy_price' => $request->buy_price,
                'normal_price' => $request->normal_price,
                'category_id' => $request->category_id,
                'quantite' => $request->quantite,
                'color' => $request->color,
                'number_serie' => $request->number_serie,
                'memory' => $request->memory,
                'panne' => $request->panne,
                'technicien' => $request->technicien,
                'batterie' => $request->batterie,
                'note' => $request->note,
                'dealeur' => $request->dealeur,
                'in_stock' => $request->quantite > 0,
                'image_paths' => [] // Initialiser un tableau vide pour les chemins d'images
            ]);

            // Traitement des images
            if ($request->hasFile('images')) {
                $imagePaths = [];
                foreach ($request->file('images') as $key => $image) {
                    $path = $image->store('articles_images', 'public');
                    $imagePaths[] = $path;
                }
                
                // Mettre à jour l'article avec les chemins d'images
                $article->update([
                    'image_paths' => $imagePaths
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Article créé avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'article: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la création de l\'article'
            ], 500);
        }
    }

    public function update(Request $request, Article $article)
    {
        try {         
            $validator = Validator::make($request->all(), [             
                'name' => 'required|string|max:255|unique:articles,name,'.$article->id,  
                'buy_price' => 'required|numeric|min:0',
                'normal_price' => [
                    'required',
                    'numeric',
                    'min:0',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($value <= $request->buy_price) {
                            $fail('Le prix de vente doit être supérieur au prix d\'achat.');
                        }
                    },
                ],
                'category_id' => 'required|exists:categories,id',
                'quantite' => 'required|integer|min:0',
                'images' => 'nullable|array|max:5',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ], [
                'name.required' => 'Le nom de l\'article est obligatoire',
                'name.max' => 'Le nom de l\'article ne peut pas dépasser 255 caractères',
                'buy_price.required' => 'Le prix d\'achat est obligatoire',
                'buy_price.numeric' => 'Le prix d\'achat doit être un nombre',
                'buy_price.min' => 'Le prix d\'achat ne peut pas être négatif',
                'normal_price.required' => 'Le prix de vente est obligatoire',
                'normal_price.numeric' => 'Le prix de vente doit être un nombre',
                'normal_price.min' => 'Le prix de vente ne peut pas être négatif',
                'category_id.required' => 'La catégorie est obligatoire',
                'category_id.exists' => 'La catégorie sélectionnée n\'existe pas',
                'quantite.required' => 'La quantité est obligatoire',
                'quantite.integer' => 'La quantité doit être un nombre entier',
                'quantite.min' => 'La quantité ne peut pas être négative',
                'images.max' => 'Vous ne pouvez pas télécharger plus de 5 images',
                'images.*.image' => 'Les fichiers doivent être des images',
                'images.*.mimes' => 'Les images doivent être au format : jpeg, png, jpg ou gif',
                'images.*.max' => 'Les images ne doivent pas dépasser 2Mo'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Préparer les données pour la mise à jour
            $updateData = [
                'name' => $request->name,
                'buy_price' => $request->buy_price,
                'normal_price' => $request->normal_price,
                'category_id' => $request->category_id,
                'quantite' => $request->quantite,
                'color' => $request->color,
                'number_serie' => $request->number_serie,
                'memory' => $request->memory,
                'panne' => $request->panne,
                'technicien' => $request->technicien,
                'batterie' => $request->batterie,
                'note' => $request->note,
                'dealeur' => $request->dealeur,
                'in_stock' => $request->quantite > 0
            ];

            // Traitement des nouvelles images si présentes
            if ($request->hasFile('images')) {
                // Supprimer les anciennes images si demandé
                if ($request->has('remove_all_images') && $request->remove_all_images) {
                    $this->deleteArticleImages($article);
                    $imagePaths = [];
                } else {
                    // Garder les anciennes images et ajouter les nouvelles
                    $imagePaths = $article->image_paths ?: [];
                }

                // Ajouter les nouvelles images
                foreach ($request->file('images') as $image) {
                    $path = $image->store('articles_images', 'public');
                    $imagePaths[] = $path;
                }

                // Limiter à 5 images maximum
                $imagePaths = array_slice($imagePaths, 0, 5);
                
                $updateData['image_paths'] = $imagePaths;
            }

            // Supprimer des images spécifiques si demandé
            if ($request->has('remove_images') && is_array($request->remove_images)) {
                $currentPaths = $article->image_paths ?: [];
                $pathsToKeep = [];
                
                foreach ($currentPaths as $index => $path) {
                    if (!in_array($index, $request->remove_images)) {
                        $pathsToKeep[] = $path;
                    } else {
                        // Supprimer le fichier du stockage
                        if (Storage::disk('public')->exists($path)) {
                            Storage::disk('public')->delete($path);
                        }
                    }
                }
                
                $updateData['image_paths'] = $pathsToKeep;
            }

            // Mettre à jour l'article
            $article->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Article modifié avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la modification de l\'article: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la modification de l\'article'
            ], 500);
        }
    }

    private function deleteArticleImages(Article $article)
    {
        if ($article->image_paths && is_array($article->image_paths)) {
            foreach ($article->image_paths as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }
    }

    public function edit(Article $article)
    {
        $categories = Categorie::all();
        
        return view('apk.editarticles', compact('article', 'categories'));
    }

    public function removeImage(Request $request, Article $article)
    {
        try {
            $index = $request->index;
            
            if (!isset($article->image_paths[$index])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image introuvable'
                ], 404);
            }
            
            $imagePath = $article->image_paths[$index];
            
            // Supprimer l'image du stockage
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            
            // Supprimer l'image du tableau des chemins
            $imagePaths = $article->image_paths;
            unset($imagePaths[$index]);
            
            // Réindexer le tableau
            $imagePaths = array_values($imagePaths);
            
            // Mettre à jour l'article
            $article->update([
                'image_paths' => $imagePaths
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Image supprimée avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression de l\'image'
            ], 500);
        }
    }

    public function deleteImage($path)
    {
        try {
            $path = urldecode($path);
            
            // Supprimer l'image du stockage
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                return response()->json([
                    'success' => true,
                    'message' => 'Image supprimée avec succès'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Image introuvable'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression de l\'image'
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
                ->orWhere('normal_price', 'LIKE', "%{$term}%")
                ->orWhere('quantite', 'LIKE', "%{$term}%")
                ->orWhere('number_serie', 'LIKE', "%{$term}%")  // Ajout de la recherche par numéro de série
                ->orWhere('color', 'LIKE', "%{$term}%")        // Ajout de la recherche par couleur
                ->with('category')
                ->latest()
                ->paginate(10);

            return response()->json([
                'success' => true,
                'articles' => $articles->map(function($article) {
                    return [
                        'id' => $article->id,
                        'name' => $article->name,
                        'buy_price' => $article->buy_price,
                        'normal_price' => $article->normal_price,
                        'quantite' => $article->quantite,
                        'color' => $article->color,
                        'number_serie' => $article->number_serie,
                        'memory' => $article->memory,
                        'panne' => $article->panne,
                        'technicien' => $article->technicien,
                        'batterie' => $article->batterie,
                        'note' => $article->note,
                        'dealeur' => $article->dealeur,
                        'category' => ['name' => $article->category->name],
                        'created_at' => $article->created_at,
                        'image_paths' => $article->image_paths,
                        'has_images' => !empty($article->image_paths),
                        'thumbnail' => !empty($article->image_paths) ? Storage::url($article->image_paths[0]) : null
                    ];
                }),
                'pagination' => [
                    'total' => $articles->total(),
                    'per_page' => $articles->perPage(),
                    'current_page' => $articles->currentPage(),
                    'last_page' => $articles->lastPage(),
                    'from' => $articles->firstItem(),
                    'to' => $articles->lastItem(),
                    'links' => $articles->links('vendor.pagination.custom')->render()
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

    public function destroy(Article $article)
    {
        try {
            // Supprimer les images de l'article
            $this->deleteArticleImages($article);

            // Supprimer l'article
            $article->delete();

            return response()->json([
                'success' => true,
                'message' => 'Article supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'article: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function showAllArticle()
    {
        $articles = Article::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('apk.listarticles', compact('articles'));
    }

    public function show(Article $article)
    {
        return view('apk.showarticle', compact('article'));
    }

    public function index()
    {
        $articles = Article::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('apk.listarticles', compact('articles'));
    }
}