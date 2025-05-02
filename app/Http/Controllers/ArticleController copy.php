<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Article;
use App\Models\Categorie;
use Illuminate\Http\Request;
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
    //    $validator = Validator::make($request->all(), [             
    //        'name' => 'required|string|unique:articles|max:255',  
    //        'buy_price' => 'required|numeric|min:0',
    //        'normal_price' => [
    //            'required',
    //            'numeric', 
    //            'min:0',
    //            function ($attribute, $value, $fail) use ($request) {
    //                if ($value <= $request->buy_price) {
    //                    $fail('Le prix de vente doit être supérieur au prix d\'achat.');
    //                }
    //            },
    //        ],
    //        'category_id' => 'required|exists:categories,id',
    //        'quantite' => 'required|integer|min:0'
    //    ], [
    //     'name.required' => 'Le nom de l\'article est obligatoire',
    //     'name.unique' => 'Le nom de l\'article existe déja',
    //        'name.max' => 'Le nom de l\'article ne peut pas dépasser 255 caractères',
    //        'buy_price.required' => 'Le prix d\'achat est obligatoire',
    //        'buy_price.numeric' => 'Le prix d\'achat doit être un nombre',
    //        'buy_price.min' => 'Le prix d\'achat ne peut pas être négatif',
    //        'normal_price.required' => 'Le prix de vente est obligatoire',
    //        'normal_price.numeric' => 'Le prix de vente doit être un nombre',
    //        'normal_price.min' => 'Le prix de vente ne peut pas être négatif',
    //        'category_id.required' => 'La catégorie est obligatoire',
    //        'category_id.exists' => 'La catégorie sélectionnée n\'existe pas',
    //        'quantite.required' => 'La quantité est obligatoire',
    //        'quantite.integer' => 'La quantité doit être un nombre entier',
    //        'quantite.min' => 'La quantité ne peut pas être négative'
    //    ]);
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
        //'images' => 'required|array|min:1', // Exige au moins une image
        //'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Validation pour chaque image
    ], [
       // 'images.required' => 'Au moins une image est obligatoire',
       // 'images.min' => 'Vous devez télécharger au moins une image',
       // 'images.*.image' => 'Les fichiers doivent être des images',
       // 'images.*.mimes' => 'Les images doivent être au format : jpeg, png, jpg ou gif',
       // 'images.*.max' => 'Les images ne doivent pas dépasser 2Mo'
    ]);
    

       if ($validator->fails()) {
           return response()->json([
               'success' => false,
               'errors' => $validator->errors()
           ], 422);
       }

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
           'in_stock' => $request->quantite > 0

       ]);

       return response()->json([
           'success' => true,
           'message' => 'Article créé avec succès'
       ]);

   } catch (\Exception $e) {
       \Log::error('Erreur lors de la création de l\'article: ' . $e->getMessage());
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
            'name' => 'required|string|max:255',  
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
            'quantite' => 'required|integer|min:0'
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
            'quantite.min' => 'La quantité ne peut pas être négative'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $article->update([
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
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Article modifié avec succès'
        ]);

    } catch (\Exception $e) {
        \Log::error('Erreur lors de la modification de l\'article: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Une erreur est survenue lors de la modification de l\'article'
        ], 500);
    }
}

public function edit(Article $article)
{
    $categories = Categorie::all();
    
    return view('apk.editarticles', compact('article', 'categories'));
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
            ->with('category')
            ->latest()
            ->paginate(10); // Augmenté à 10 éléments par page pour une meilleure expérience utilisateur

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
                    'created_at' => $article->created_at
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
        \Log::error('Erreur de recherche: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Une erreur est survenue lors de la recherche'
        ], 500);
    }
}
    public function destroy(Article $article)
{
    try {
        // Vérifier si l'article a des images
        if ($article->image_paths && is_array($article->image_paths)) {
            // Supprimer chaque image
            foreach ($article->image_paths as $imagePath) {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }

        // Supprimer l'article
        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'Article supprimé avec succès'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la suppression de l\'article',
            'error' => $e->getMessage()
        ], 500);
    }
}


public function showAllArticle()
{
    $articles = Article::orderBy('name', 'asc')->paginate(10);
    return view('apk.listarticles', compact('articles'));
}


    // Boutique affichage 


}




