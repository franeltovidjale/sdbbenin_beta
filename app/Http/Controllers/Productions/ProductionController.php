<?php

namespace App\Http\Controllers\Productions;

use App\Models\Article;
use App\Models\Production;
use Illuminate\Http\Request;
use App\Models\TypeProduction;
use App\Models\ProductionLabor;
use App\Models\ProductionStock;
use App\Models\ProductionOutput;
use App\Models\ProductionArticle;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductionController extends Controller
{
    /**
     * Afficher la liste des productions
     */
    public function index()
    {
        $productions = Production::with('type')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $types = TypeProduction::all();
        
        return view('productions.index', compact('productions', 'types'));
    }
    
    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $types = TypeProduction::all();
        return view('productions.create', compact('types'));
    }
    
    /**
     * Enregistrer une nouvelle production
     */
    public function store(Request $request)
    {
        try {
            // Forcer le statut à "en cours" quel que soit la valeur soumise
            $request->merge(['status' => 'en cours']);
            
            $validated = $request->validate([
                'production_name' => 'required|string|max:255',
                'type_id' => 'required|exists:type_productions,id',
                'production_date' => 'required|date',
                'qte_production' => 'required|numeric|min:0',
                'status' => 'in:en cours',
            ], [
                'production_name.required' => 'Le nom de la production est obligatoire',
                'type_id.required' => 'Le type de production est obligatoire',
                'type_id.exists' => 'Ce type de production n\'existe pas',
                'production_date.required' => 'La date de production est obligatoire',
                'production_date.date' => 'La date de production doit être une date valide',
                'qte_production.required' => 'La quantité de production est obligatoire',
                'qte_production.numeric' => 'La quantité de production doit être un nombre',
                'qte_production.min' => 'La quantité de production doit être positive',
            ]);

            $production = Production::create($validated);

            // Si c'est une requête AJAX, retourner une réponse JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Production créée avec succès',
                    'production' => $production
                ]);
            }

            // Sinon, redirection avec un message flash
            return redirect()->route('production.index')->with('success', 'Production créée avec succès.');
        } catch (\Exception $e) {
            // Si c'est une requête AJAX, retourner une réponse JSON d'erreur
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de la production: ' . $e->getMessage(),
                    'errors' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : null
                ], 422);
            }

            // Sinon, redirection avec un message d'erreur
            return redirect()->back()->with('error', 'Erreur lors de la création de la production.')->withErrors($e->errors() ?? [])->withInput();
        }
    }
    
    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Production $production)
    {
        $types = TypeProduction::all();
        return view('productions.edit', compact('production', 'types'));
    }
    
    /**
     * Mettre à jour une production
     */
    public function update(Request $request, Production $production)
    {
        try {
            // Conserver le statut existant, ne pas permettre de le modifier
            $request->merge(['status' => $production->status]);
            
            $validated = $request->validate([
                'production_name' => 'required|string|max:255',
                'type_id' => 'required|exists:type_productions,id',
                'production_date' => 'required|date',
                'qte_production' => 'required|numeric|min:0',
                'status' => 'in:en cours,terminé',
            ], [
                'production_name.required' => 'Le nom de la production est obligatoire',
                'type_id.required' => 'Le type de production est obligatoire',
                'type_id.exists' => 'Ce type de production n\'existe pas',
                'production_date.required' => 'La date de production est obligatoire',
                'production_date.date' => 'La date de production doit être une date valide',
                'qte_production.required' => 'La quantité de production est obligatoire',
                'qte_production.numeric' => 'La quantité de production doit être un nombre',
                'qte_production.min' => 'La quantité de production doit être positive',
            ]);

            $production->update($validated);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Production modifiée avec succès',
                    'production' => $production
                ]);
            }

            return redirect()->route('production.index')->with('success', 'Production modifiée avec succès.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la modification de la production: ' . $e->getMessage(),
                    'errors' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : null
                ], 422);
            }

            return redirect()->back()->with('error', 'Erreur lors de la modification de la production.')->withErrors($e->errors() ?? [])->withInput();
        }
    }
    
    /**
     * Supprimer une production
     */
    public function destroy(Production $production, Request $request)
    {
        try {
            $production->delete();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Production supprimée avec succès'
                ]);
            }

            return redirect()->route('production.index')->with('success', 'Production supprimée avec succès.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de la production: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de la suppression de la production.');
        }
    }

    // public function markAsCompleted(Production $production, Request $request)
    // {
    //     try {
    //         // Vérifier que la production est en cours
    //         if ($production->status !== 'en cours') {
    //             throw new \Exception('Cette production est déjà marquée comme terminée.');
    //         }
            
    //         // Mettre à jour le statut
    //         $production->status = 'terminé';
    //         $production->save();
            
    //         if ($request->ajax() || $request->wantsJson()) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Production marquée comme terminée avec succès',
    //                 'production' => $production
    //             ]);
    //         }

    //         return redirect()->route('production.index')->with('success', 'Production marquée comme terminée avec succès.');
    //     } catch (\Exception $e) {
    //         if ($request->ajax() || $request->wantsJson()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Erreur: ' . $e->getMessage()
    //             ], 422);
    //         }

    //         return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
    //     }
    // }
    
    /**
     * Rechercher des productions
     */
    public function search(Request $request)
    {
        $keyword = $request->get('q');
        $type_id = $request->get('type_id');
        $status = $request->get('status');
        
        $query = Production::with('type');
        
        if (!empty($keyword)) {
            $query->where('production_name', 'like', "%{$keyword}%");
        }
        
        if (!empty($type_id)) {
            $query->where('type_id', $type_id);
        }
        
        if (!empty($status)) {
            $query->where('status', $status);
        }
        
        $productions = $query->orderBy('created_at', 'desc')->paginate(10);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'productions' => $productions
            ]);
        }
        
        $types = TypeProduction::all();
        return view('productions.index', compact('productions', 'types', 'keyword', 'type_id', 'status'));
    }
    
    /**
     * Afficher les statistiques de production
     */
    public function stats()
    {
        // Récupérer les données pour les statistiques
        $totalProductions = Production::count();
        $totalTerminees = Production::where('status', 'terminé')->count();
        $totalEnCours = Production::where('status', 'en cours')->count();
        
        // Statistiques par type
        $statsByType = TypeProduction::withCount('productions')->get();
        
        // Quantités totales par type
        $qteByType = Production::selectRaw('type_id, sum(qte_production) as total_qte')
            ->groupBy('type_id')
            ->with('type')
            ->get();
        
        // Productions récentes
        $recentProductions = Production::with('type')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('productions.stats', compact(
            'totalProductions', 
            'totalTerminees', 
            'totalEnCours', 
            'statsByType', 
            'qteByType', 
            'recentProductions'
        ));
    }


    /**
 * Méthodes à ajouter au ProductionController pour gérer la page détaillée d'une production
 */

/**
 * Afficher les détails d'une production
 */
public function show(Production $production)
{
    // Récupérer les articles utilisés dans cette production avec pagination
    $usedArticles = ProductionArticle::where('production_id', $production->id)
        ->with('article')
        ->paginate(5, ['*'], 'articles_page');
    
    // Récupérer les entrées de main d'œuvre pour cette production avec pagination
    $laborEntries = ProductionLabor::where('production_id', $production->id)
        ->paginate(5, ['*'], 'labor_page');
    
    // Récupérer les productions obtenues pour cette production avec pagination
    $outputEntries = ProductionOutput::where('production_id', $production->id)
        ->paginate(5, ['*'], 'output_page');
    
    // Récupérer tous les articles disponibles pour le formulaire
    $articles = Article::select('id', 'name', 'stock_quantity', 'unit_price')
        ->orderBy('name')
        ->get();
    
    return view('productions.show', compact(
        'production',
        'usedArticles',
        'laborEntries',
        'outputEntries',
        'articles'
    ));
}

/**
 * Vérifier et finaliser une production (marquer comme terminée et défalquer les stocks)
 */
// public function verify(Production $production, Request $request)
// {
//     // Vérifier que la production est en cours
//     if ($production->status !== 'en cours') {
//         return response()->json([
//             'success' => false,
//             'message' => 'Cette production est déjà marquée comme terminée'
//         ], 422);
//     }
    
//     // Vérifier qu'il y a au moins un article utilisé
//     $usedArticles = ProductionArticle::where('production_id', $production->id)->count();
//     if ($usedArticles === 0) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Vous devez déclarer au moins un article utilisé avant de finaliser la production'
//         ], 422);
//     }
    
//     try {
//         DB::beginTransaction();
        
//         // Récupérer tous les articles utilisés dans cette production
//         $productionArticles = ProductionArticle::where('production_id', $production->id)
//             ->with('article')
//             ->get();
        
//         // Vérifier la disponibilité de tous les articles
//         foreach ($productionArticles as $productionArticle) {
//             $article = $productionArticle->article;
            
//             if ($article->stock_quantity < $productionArticle->quantity) {
//                 throw new \Exception("Stock insuffisant pour l'article '{$article->name}'. Disponible: {$article->stock_quantity}, Requis: {$productionArticle->quantity}");
//             }
            
//             // Défalquer les articles du stock
//             $article->stock_quantity -= $productionArticle->quantity;
//             $article->save();
//         }
        
//         // Marquer la production comme terminée
//         $production->status = 'terminé';
//         $production->save();
        
//         DB::commit();
        
//         return response()->json([
//             'success' => true,
//             'message' => 'Production finalisée avec succès'
//         ]);
//     } catch (\Exception $e) {
//         DB::rollBack();
        
//         return response()->json([
//             'success' => false,
//             'message' => $e->getMessage()
//         ], 422);
//     }
// }

/**
 * Vérifier et finaliser une production (marquer comme terminée et défalquer les stocks)
 */
public function verify(Production $production, Request $request)
{
    // Vérifier que la production est en cours
    if ($production->status !== 'en cours') {
        return response()->json([
            'success' => false,
            'message' => 'Cette production est déjà marquée comme terminée'
        ], 422);
    }
    
    // Vérifier qu'il y a au moins un article utilisé
    $usedArticles = ProductionArticle::where('production_id', $production->id)->count();
    if ($usedArticles === 0) {
        return response()->json([
            'success' => false,
            'message' => 'Vous devez déclarer au moins un article utilisé avant de finaliser la production'
        ], 422);
    }
    
    // Vérifier qu'il y a au moins une production obtenue
    $outputs = ProductionOutput::where('production_id', $production->id)->count();
    if ($outputs === 0) {
        return response()->json([
            'success' => false,
            'message' => 'Vous devez déclarer au moins une production obtenue avant de finaliser la production'
        ], 422);
    }
    
    try {
        DB::beginTransaction();
        
        // Récupérer tous les articles utilisés dans cette production
        $productionArticles = ProductionArticle::where('production_id', $production->id)
            ->with('article')
            ->get();
        
        // Vérifier la disponibilité de tous les articles
        foreach ($productionArticles as $productionArticle) {
            $article = $productionArticle->article;
            
            if ($article->stock_quantity < $productionArticle->quantity) {
                throw new \Exception("Stock insuffisant pour l'article '{$article->name}'. Disponible: {$article->stock_quantity}, Requis: {$productionArticle->quantity}");
            }
            
            // Défalquer les articles du stock
            $article->stock_quantity -= $productionArticle->quantity;
            $article->save();
        }
        
        // Récupérer tous les outputs de cette production
        $productionOutputs = ProductionOutput::where('production_id', $production->id)->get();
        
        // Mettre à jour les stocks de production
        foreach ($productionOutputs as $output) {
            ProductionStock::addStock(
                $production->type_id,
                $output->carton_type,
                $output->carton_count
            );
        }
        
        // Marquer la production comme terminée
        $production->status = 'terminé';
        $production->save();
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Production finalisée avec succès'
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 422);
    }
}

}