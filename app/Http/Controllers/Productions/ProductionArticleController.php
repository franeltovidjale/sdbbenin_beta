<?php
// namespace App\Http\Controllers\Productions;

// use App\Http\Controllers\Controller;
// use App\Models\Article;
// use App\Models\Production;
// use App\Models\ProductionArticle;
// use Illuminate\Http\Request;

// class ProductionArticleController extends Controller
// {
//     /**
//      * Ajouter un article à une production
//      */
//     public function store(Request $request)
//     {
//         $validated = $request->validate([
//             'production_id' => 'required|exists:productions,id',
//             'article_id' => 'required|exists:articles,id',
//             'quantity' => 'required|numeric|min:0.01',
//             'unit_price' => 'required|numeric|min:0.01',
//         ]);
        
//         // Vérifier que la production est toujours en cours
//         $production = Production::findOrFail($validated['production_id']);
//         if ($production->status !== 'en cours') {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Impossible de modifier une production terminée'
//             ], 422);
//         }
        
//         // Vérifier la disponibilité du stock
//         $article = Article::findOrFail($validated['article_id']);
//         if ($article->stock_quantity < $validated['quantity']) {
//             return response()->json([
//                 'success' => false,
//                 'message' => "Stock insuffisant. Disponible: {$article->stock_quantity}, Requis: {$validated['quantity']}"
//             ], 422);
//         }
        
//         $productionArticle = ProductionArticle::create($validated);
        
//         // Ajouter le nom de l'article pour l'affichage
//         $productionArticle->article_name = $article->name;
        
//         return response()->json([
//             'success' => true,
//             'message' => 'Article ajouté avec succès',
//             'article' => $productionArticle
//         ]);
//     }
    
//     /**
//      * Mettre à jour un article dans une production
//      */
//     public function update(Request $request, $id)
//     {
//         $validated = $request->validate([
//             'article_id' => 'required|exists:articles,id',
//             'quantity' => 'required|numeric|min:0.01',
//             'unit_price' => 'required|numeric|min:0.01',
//         ]);
        
//         $productionArticle = ProductionArticle::findOrFail($id);
        
//         // Vérifier que la production est toujours en cours
//         $production = Production::findOrFail($productionArticle->production_id);
//         if ($production->status !== 'en cours') {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Impossible de modifier une production terminée'
//             ], 422);
//         }
        
//         // Si l'article est modifié ou la quantité augmentée, vérifier le stock
//         if ($validated['article_id'] != $productionArticle->article_id || 
//             $validated['quantity'] > $productionArticle->quantity) {
                
//             $article = Article::findOrFail($validated['article_id']);
            
//             // Calculer la quantité supplémentaire requise
//             $additionalQuantity = ($validated['article_id'] == $productionArticle->article_id) 
//                 ? ($validated['quantity'] - $productionArticle->quantity)
//                 : $validated['quantity'];
                
//             if ($article->stock_quantity < $additionalQuantity) {
//                 return response()->json([
//                     'success' => false,
//                     'message' => "Stock insuffisant. Disponible: {$article->stock_quantity}, Requis en plus: {$additionalQuantity}"
//                 ], 422);
//             }
//         }
        
//         $productionArticle->update($validated);
        
//         // Recharger l'article pour avoir les informations à jour
//         $productionArticle->load('article');
        
//         // Ajouter le nom de l'article pour l'affichage
//         $productionArticle->article_name = $productionArticle->article->name;
        
//         return response()->json([
//             'success' => true,
//             'message' => 'Article modifié avec succès',
//             'article' => $productionArticle
//         ]);
//     }
    
//     /**
//      * Supprimer un article d'une production
//      */
//     public function destroy($id)
//     {
//         $productionArticle = ProductionArticle::findOrFail($id);
        
//         // Vérifier que la production est toujours en cours
//         $production = Production::findOrFail($productionArticle->production_id);
//         if ($production->status !== 'en cours') {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Impossible de modifier une production terminée'
//             ], 422);
//         }
        
//         $productionArticle->delete();
        
//         return response()->json([
//             'success' => true,
//             'message' => 'Article supprimé avec succès'
//         ]);
//     }
// }





namespace App\Http\Controllers\Productions;

use App\Http\Controllers\Controller;
use App\Models\Production;
use App\Models\ProductionArticle;
use App\Models\Article;
use Illuminate\Http\Request;

class ProductionArticleController extends Controller
{
    /**
     * Ajouter un article à une production
     */
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'production_id' => 'required|exists:productions,id',
    //         'article_id' => 'required|exists:articles,id',
    //         'quantity' => 'required|numeric|min:0.01',
            
    //     ]);
        
    //     // Vérifier que la production est toujours en cours
    //     $production = Production::findOrFail($validated['production_id']);
    //     if ($production->status !== 'en cours') {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Impossible de modifier une production terminée'
    //         ], 422);
    //     }
        
    //     // Vérifier la disponibilité du stock
    //     $article = Article::findOrFail($validated['article_id']);
    //     if ($validated['quantity'] > $article->stock_quantity) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Stock insuffisant',
    //             'errors' => [
    //                 'quantity' => ['Stock insuffisant. Disponible: ' . $article->stock_quantity]
    //             ]
    //         ], 422);
    //     }
        
    //     $productionArticle = ProductionArticle::create($validated);
        
    //     // Récupérer l'article avec ses relations pour la réponse
    //     $productionArticle = ProductionArticle::with('article')->find($productionArticle->id);
        
    //     // Préparer la réponse avec le nom de l'article
    //     $response = [
    //         'id' => $productionArticle->id,
    //         'production_id' => $productionArticle->production_id,
    //         'article_id' => $productionArticle->article_id,
    //         'quantity' => $productionArticle->quantity,
    //         'unit_price' => $productionArticle->unit_price,
    //         'article_name' => $productionArticle->article->name ?? 'Article inconnu'
    //     ];
        
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Article ajouté avec succès',
    //         'article' => $response
    //     ]);
    // }

    /**
 * Ajouter un article à une production
 */
public function store(Request $request)
{
    $validated = $request->validate([
        'production_id' => 'required|exists:productions,id',
        'article_id' => 'required|exists:articles,id',
        'quantity' => 'required|numeric|min:0.01',
        // On ne valide plus unit_price ici
    ]);
    
    // Vérifier que la production est toujours en cours
    $production = Production::findOrFail($validated['production_id']);
    if ($production->status !== 'en cours') {
        return response()->json([
            'success' => false,
            'message' => 'Impossible de modifier une production terminée'
        ], 422);
    }
    
    // Récupérer l'article pour obtenir son prix
    $article = Article::findOrFail($validated['article_id']);
    
    // Ajouter le prix unitaire aux données validées
    $validated['unit_price'] = $article->unit_price;
    
    // Vérifier la disponibilité du stock
    if ($validated['quantity'] > $article->stock_quantity) {
        return response()->json([
            'success' => false,
            'message' => 'Stock insuffisant',
            'errors' => [
                'quantity' => ['Stock insuffisant. Disponible: ' . $article->stock_quantity]
            ]
        ], 422);
    }
    
    $productionArticle = ProductionArticle::create($validated);
    
    // Récupérer l'article avec ses relations pour la réponse
    $productionArticle = ProductionArticle::with('article')->find($productionArticle->id);
    
    // Préparer la réponse avec le nom de l'article
    $response = [
        'id' => $productionArticle->id,
        'production_id' => $productionArticle->production_id,
        'article_id' => $productionArticle->article_id,
        'quantity' => $productionArticle->quantity,
        'unit_price' => $productionArticle->unit_price,
        'article_name' => $productionArticle->article->name ?? 'Article inconnu'
    ];
    
    return response()->json([
        'success' => true,
        'message' => 'Article ajouté avec succès',
        'article' => $response
    ]);
}
    
  /**
 * Mettre à jour un article dans une production
 */
public function update(Request $request, $id)
{
    try {
        $validated = $request->validate([
            'article_id' => 'required|exists:articles,id',
            'quantity' => 'required|numeric|min:0.01',
            // unit_price n'est plus validé ici
        ]);
        
        $productionArticle = ProductionArticle::findOrFail($id);
        
        // Vérifier que la production est toujours en cours
        $production = Production::findOrFail($productionArticle->production_id);
        if ($production->status !== 'en cours') {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de modifier une production terminée'
            ], 422);
        }
        
        // Récupérer l'article pour obtenir son prix
        $article = Article::findOrFail($validated['article_id']);
        
        // Ajouter le prix unitaire aux données validées
        $validated['unit_price'] = $article->unit_price;
        
        // Vérifier la disponibilité du stock seulement si l'article a changé ou si la quantité a augmenté
        if ($validated['article_id'] != $productionArticle->article_id || $validated['quantity'] > $productionArticle->quantity) {
            $article = Article::findOrFail($validated['article_id']);
            $additionalQuantity = $validated['article_id'] != $productionArticle->article_id 
                ? $validated['quantity'] 
                : $validated['quantity'] - $productionArticle->quantity;
            
            if ($additionalQuantity > 0 && $additionalQuantity > $article->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuffisant',
                    'errors' => [
                        'quantity' => ['Stock insuffisant. Disponible: ' . $article->stock_quantity]
                    ]
                ], 422);
            }
        }
        
        $productionArticle->update($validated);
        
        // Récupérer l'article avec ses relations pour la réponse
        $productionArticle = ProductionArticle::with('article')->find($id);
        
        // Préparer la réponse avec le nom de l'article
        $response = [
            'id' => $productionArticle->id,
            'production_id' => $productionArticle->production_id,
            'article_id' => $productionArticle->article_id,
            'quantity' => $productionArticle->quantity,
            'unit_price' => $productionArticle->unit_price,
            'article_name' => $productionArticle->article->name ?? 'Article inconnu'
        ];
        
        return response()->json([
            'success' => true,
            'message' => 'Article modifié avec succès',
            'article' => $response
        ]);
    } catch (\Exception $e) {
        \Log::error('Erreur lors de la mise à jour de l\'article: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Une erreur est survenue: ' . $e->getMessage()
        ], 500);
    }
}
    
    /**
     * Supprimer un article d'une production
     */
    public function destroy($id)
    {
        $productionArticle = ProductionArticle::findOrFail($id);
        
        // Vérifier que la production est toujours en cours
        $production = Production::findOrFail($productionArticle->production_id);
        if ($production->status !== 'en cours') {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de modifier une production terminée'
            ], 422);
        }
        
        $productionArticle->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Article supprimé avec succès'
        ]);
    }
}