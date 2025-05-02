<?php

namespace App\Http\Controllers\Articles;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Models\ArticleMovement;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ArticleMovementController extends Controller
{
    
     /**
     * Afficher la liste des mouvements
     */
    public function index()
    {
        // Récupérer les mouvements en attente
        $pendingMovements = ArticleMovement::where('status', 'en attente')
            ->with('article')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'pending_page');
        
        // Récupérer les mouvements validés et rejetés récents
        $historyMovements = ArticleMovement::where('status', '!=', 'en attente')
            ->with('article')
            ->orderBy('updated_at', 'desc')
            ->paginate(10, ['*'], 'history_page');
        
        // Récupérer tous les articles disponibles pour le formulaire
        $articles = Article::orderBy('name')
            ->get();
        
        return view('articles.input-output', compact('pendingMovements', 'historyMovements', 'articles'));
    }
    
    /**
     * Enregistrer un nouveau mouvement
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'article_id' => 'required|exists:articles,id',
                'type' => 'required|in:entrée,sortie',
                'quantity' => 'required|numeric|min:0.01',
                'unit_price' => 'required|numeric|min:0',
                'notes' => 'nullable|string'
            ], [
                'article_id.required' => 'L\'article est obligatoire',
                'article_id.exists' => 'Cet article n\'existe pas',
                'type.required' => 'Le type de mouvement est obligatoire',
                'type.in' => 'Le type de mouvement doit être "entrée" ou "sortie"',
                'quantity.required' => 'La quantité est obligatoire',
                'quantity.numeric' => 'La quantité doit être un nombre',
                'quantity.min' => 'La quantité doit être positive',
                'unit_price.required' => 'Le prix unitaire est obligatoire',
                'unit_price.numeric' => 'Le prix unitaire doit être un nombre',
                'unit_price.min' => 'Le prix unitaire doit être positif ou nul'
            ]);
            
            if ($validator->fails()) {
                throw new \Illuminate\Validation\ValidationException($validator);
            }
            
            // Vérifier si c'est une sortie et si le stock est suffisant
            if ($request->type === 'sortie') {
                $article = Article::findOrFail($request->article_id);
                
                // Calculer le stock virtuel (stock réel + entrées en attente - sorties en attente)
                $pendingEntries = ArticleMovement::where('article_id', $article->id)
                    ->where('status', 'en attente')
                    ->where('type', 'entrée')
                    ->sum('quantity');
                
                $pendingSorties = ArticleMovement::where('article_id', $article->id)
                    ->where('status', 'en attente')
                    ->where('type', 'sortie')
                    ->sum('quantity');
                
                $virtualStock = $article->stock_quantity + $pendingEntries - $pendingSorties;
                
                if ($virtualStock < $request->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stock virtuel insuffisant. Stock actuel: ' . $article->stock_quantity . 
                        ', Entrées en attente: ' . $pendingEntries . 
                        ', Sorties en attente: ' . $pendingSorties . 
                        ', Stock disponible: ' . $virtualStock
                    ], 422);
                }
            }
            
            // Créer le mouvement
            $movement = ArticleMovement::create([
                'article_id' => $request->article_id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'notes' => $request->notes,
                'status' => 'en attente'
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Mouvement enregistré avec succès',
                    'movement' => $movement->load('article')
                ]);
            }
            
            return redirect()->route('stock.movements.index')->with('success', 'Mouvement enregistré avec succès');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'enregistrement du mouvement: ' . $e->getMessage(),
                    'errors' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : null
                ], 422);
            }
            
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement du mouvement')->withErrors($e->errors() ?? [])->withInput();
        }
    }
    
    /**
     * Mettre à jour un mouvement
     */
    public function update(Request $request, ArticleMovement $movement)
    {
        try {
            // Vérifier si le mouvement est déjà validé ou rejeté
            if ($movement->status !== 'en attente') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce mouvement a déjà été ' . $movement->status
                ], 422);
            }
            
            $validator = Validator::make($request->all(), [
                'article_id' => 'required|exists:articles,id',
                'type' => 'required|in:entrée,sortie',
                'quantity' => 'required|numeric|min:0.01',
                'unit_price' => 'required|numeric|min:0',
                'notes' => 'nullable|string'
            ]);
            
            if ($validator->fails()) {
                throw new \Illuminate\Validation\ValidationException($validator);
            }
            
            // Vérifier si c'est une sortie et si le stock est suffisant
            if ($request->type === 'sortie') {
                $article = Article::findOrFail($request->article_id);
                
                // Calculer le stock virtuel (stock réel + entrées en attente - sorties en attente, en excluant le mouvement actuel)
                $pendingEntries = ArticleMovement::where('article_id', $article->id)
                    ->where('status', 'en attente')
                    ->where('type', 'entrée')
                    ->where('id', '!=', $movement->id)
                    ->sum('quantity');
                
                $pendingSorties = ArticleMovement::where('article_id', $article->id)
                    ->where('status', 'en attente')
                    ->where('type', 'sortie')
                    ->where('id', '!=', $movement->id)
                    ->sum('quantity');
                
                $virtualStock = $article->stock_quantity + $pendingEntries - $pendingSorties;
                
                if ($virtualStock < $request->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stock virtuel insuffisant pour cette sortie'
                    ], 422);
                }
            }
            
            // Mettre à jour le mouvement
            $movement->update([
                'article_id' => $request->article_id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'notes' => $request->notes
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Mouvement mis à jour avec succès',
                    'movement' => $movement->load('article')
                ]);
            }
            
            return redirect()->route('stock.movements.index')->with('success', 'Mouvement mis à jour avec succès');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du mouvement: ' . $e->getMessage(),
                    'errors' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : null
                ], 422);
            }
            
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour du mouvement')->withErrors($e->errors() ?? [])->withInput();
        }
    }
    
    /**
     * Supprimer un mouvement
     */
    public function destroy(ArticleMovement $movement, Request $request)
    {
        try {
            // Vérifier si le mouvement est déjà validé ou rejeté
            if ($movement->status !== 'en attente') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce mouvement a déjà été ' . $movement->status . ' et ne peut pas être supprimé'
                ], 422);
            }
            
            $movement->delete();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Mouvement supprimé avec succès'
                ]);
            }
            
            return redirect()->route('stock.movements.index')->with('success', 'Mouvement supprimé avec succès');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du mouvement: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Erreur lors de la suppression du mouvement');
        }
    }
    
    /**
     * Valider un ou plusieurs mouvements
     */
    public function validateArticleMovement(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $movementIds = $request->movement_ids;
            
            if (empty($movementIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun mouvement sélectionné'
                ], 422);
            }
            
            $movements = ArticleMovement::whereIn('id', $movementIds)
                ->where('status', 'en attente')
                ->with('article')
                ->get();
            
            if ($movements->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun mouvement en attente trouvé parmi les sélectionnés'
                ], 422);
            }
            
            // Vérifier le stock pour toutes les sorties
            $stockErrors = [];
            $articlesUpdates = [];
            
            foreach ($movements as $movement) {
                $article = $movement->article;
                
                if ($movement->type === 'sortie' && $article->stock_quantity < $movement->quantity) {
                    $stockErrors[] = "Stock insuffisant pour l'article '{$article->name}'. Disponible: {$article->stock_quantity}, Requis: {$movement->quantity}";
                    continue;
                }
                
                // Préparer les mises à jour de stock
                if (!isset($articlesUpdates[$article->id])) {
                    $articlesUpdates[$article->id] = [
                        'article' => $article,
                        'delta' => 0
                    ];
                }
                
                if ($movement->type === 'entrée') {
                    $articlesUpdates[$article->id]['delta'] += $movement->quantity;
                } else {
                    $articlesUpdates[$article->id]['delta'] -= $movement->quantity;
                }
            }
            
            if (!empty($stockErrors)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de stock détectées',
                    'errors' => $stockErrors
                ], 422);
            }
            
            // Appliquer les mises à jour de stock
            foreach ($articlesUpdates as $update) {
                $article = $update['article'];
                $article->stock_quantity += $update['delta'];
                $article->save();
            }
            
            // Mettre à jour le statut des mouvements
            foreach ($movements as $movement) {
                $movement->status = 'validé';
                $movement->save();
            }
            
            DB::commit();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => count($movements) . ' mouvement(s) validé(s) avec succès'
                ]);
            }
            
            return redirect()->route('stock.movements.index')->with('success', count($movements) . ' mouvement(s) validé(s) avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la validation des mouvements: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Erreur lors de la validation des mouvements');
        }
    }
    
    /**
     * Rejeter un ou plusieurs mouvements
     */
    public function reject(Request $request)
    {
        try {
            $movementIds = $request->movement_ids;
            
            if (empty($movementIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun mouvement sélectionné'
                ], 422);
            }
            
            $count = ArticleMovement::whereIn('id', $movementIds)
                ->where('status', 'en attente')
                ->update(['status' => 'rejeté']);
            
            if ($count === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun mouvement en attente trouvé parmi les sélectionnés'
                ], 422);
            }
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $count . ' mouvement(s) rejeté(s) avec succès'
                ]);
            }
            
            return redirect()->route('stock.movements.index')->with('success', $count . ' mouvement(s) rejeté(s) avec succès');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du rejet des mouvements: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Erreur lors du rejet des mouvements');
        }
    }
}
