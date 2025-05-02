<?php

namespace App\Http\Controllers\Productions;

use App\Models\Production;
use Illuminate\Http\Request;
use App\Models\ProductionSale;
use App\Models\TypeProduction;
use App\Models\ProductionStock;
use App\Models\ProductionOutput;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductionSaleController extends Controller
{
    /**
     * Afficher la liste des ventes
     */
    public function index()
    {
        // Récupérer les ventes en attente
        $pendingSales = ProductionSale::where('status', 'en attente')
            ->with('type')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'pending_page');
        
        // Récupérer les ventes validées et rejetées récentes
        $historySales = ProductionSale::where('status', '!=', 'en attente')
            ->with('type')
            ->orderBy('updated_at', 'desc')
            ->paginate(10, ['*'], 'history_page');
        
        // Récupérer tous les types de production
        $types = TypeProduction::orderBy('name')
            ->get();
        
        // Calculer le stock disponible par type et taille de carton
        $stockData = $this->calculateAvailableStock();
        
        return view('productions.sales', compact('pendingSales', 'historySales', 'types', 'stockData'));
    }


    
    /**
     * Calculer le stock disponible par type et taille de carton
     */
    // private function calculateAvailableStock()
    // {
    //     // Structure pour stocker les résultats
    //     $stockData = [];
        
    //     // Récupérer tous les types de production
    //     $types = TypeProduction::all();
        
    //     foreach ($types as $type) {
    //         $typeId = $type->id;
    //         $stockData[$typeId] = [
    //             'name' => $type->name,
    //             'petit' => [
    //                 'produced' => 0,
    //                 'sold' => 0,
    //                 'pending' => 0,
    //                 'available' => 0
    //             ],
    //             'grand' => [
    //                 'produced' => 0,
    //                 'sold' => 0,
    //                 'pending' => 0,
    //                 'available' => 0
    //             ]
    //         ];
            
    //         // Productions terminées pour ce type
    //         $completedProductions = Production::where('type_id', $typeId)
    //             ->where('status', 'terminé')
    //             ->pluck('id');
            
    //         // Calculer le nombre total de cartons produits par taille
    //         $outputsByType = ProductionOutput::whereIn('production_id', $completedProductions)
    //             ->select('carton_type', DB::raw('SUM(carton_count) as total'))
    //             ->groupBy('carton_type')
    //             ->get();
            
    //         foreach ($outputsByType as $output) {
    //             $stockData[$typeId][$output->carton_type]['produced'] = $output->total;
    //         }
            
    //         // Calculer le nombre total de cartons vendus par taille (ventes validées)
    //         $salesByType = ProductionSale::where('type_id', $typeId)
    //             ->where('status', 'validé')
    //             ->select('carton_type', DB::raw('SUM(quantity) as total'))
    //             ->groupBy('carton_type')
    //             ->get();
            
    //         foreach ($salesByType as $sale) {
    //             $stockData[$typeId][$sale->carton_type]['sold'] = $sale->total;
    //         }
            
    //         // Calculer le nombre total de cartons en attente de validation
    //         $pendingByType = ProductionSale::where('type_id', $typeId)
    //             ->where('status', 'en attente')
    //             ->select('carton_type', DB::raw('SUM(quantity) as total'))
    //             ->groupBy('carton_type')
    //             ->get();
            
    //         foreach ($pendingByType as $pending) {
    //             $stockData[$typeId][$pending->carton_type]['pending'] = $pending->total;
    //         }
            
    //         // Calculer le stock disponible (produit - vendu)
    //         $stockData[$typeId]['petit']['available'] = $stockData[$typeId]['petit']['produced'] - $stockData[$typeId]['petit']['sold'];
    //         $stockData[$typeId]['grand']['available'] = $stockData[$typeId]['grand']['produced'] - $stockData[$typeId]['grand']['sold'];
    //     }
        
    //     return $stockData;
    // }
    
    /**
     * Enregistrer une nouvelle vente
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type_id' => 'required|exists:type_productions,id',
                'carton_type' => 'required|in:petit,grand',
                'quantity' => 'required|numeric|min:0.01',
                'unit_price' => 'required|numeric|min:0',
                'client_name' => 'required|string|max:255',
                'client_firstname' => 'required|string|max:255',
                'client_phone' => 'nullable|string|max:50',
                'client_email' => 'nullable|email|max:255',
                'notes' => 'nullable|string'
            ], [
                'type_id.required' => 'Le type de production est obligatoire',
                'type_id.exists' => 'Ce type de production n\'existe pas',
                'carton_type.required' => 'Le type de carton est obligatoire',
                'carton_type.in' => 'Le type de carton doit être "petit" ou "grand"',
                'quantity.required' => 'La quantité est obligatoire',
                'quantity.numeric' => 'La quantité doit être un nombre',
                'quantity.min' => 'La quantité doit être positive',
                'unit_price.required' => 'Le prix unitaire est obligatoire',
                'unit_price.numeric' => 'Le prix unitaire doit être un nombre',
                'unit_price.min' => 'Le prix unitaire doit être positif ou nul',
                'client_name.required' => 'Le nom du client est obligatoire',
                'client_firstname.required' => 'Le prénom du client est obligatoire',
                'client_email.email' => 'L\'email doit être une adresse email valide'
            ]);
            
            if ($validator->fails()) {
                throw new \Illuminate\Validation\ValidationException($validator);
            }
            
            // Vérifier la disponibilité du stock
            $stockData = $this->calculateAvailableStock();
            $typeId = $request->type_id;
            $cartonType = $request->carton_type;
            $quantity = $request->quantity;
            
            if (!isset($stockData[$typeId])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Type de production invalide'
                ], 422);
            }
            
            $availableStock = $stockData[$typeId][$cartonType]['available'];
            
            if ($quantity > $availableStock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuffisant. Disponible: ' . $availableStock . ' cartons ' . $cartonType . 's'
                ], 422);
            }
            
            // Créer la vente
            $sale = ProductionSale::create([
                'type_id' => $typeId,
                'carton_type' => $cartonType,
                'quantity' => $quantity,
                'unit_price' => $request->unit_price,
                'client_name' => $request->client_name,
                'client_firstname' => $request->client_firstname,
                'client_phone' => $request->client_phone,
                'client_email' => $request->client_email,
                'notes' => $request->notes,
                'status' => 'en attente'
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vente enregistrée avec succès',
                    'sale' => $sale->load('type')
                ]);
            }
            
            return redirect()->route('productions.sales.index')->with('success', 'Vente enregistrée avec succès');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'enregistrement de la vente: ' . $e->getMessage(),
                    'errors' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : null
                ], 422);
            }
            
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement de la vente')->withErrors($e->errors() ?? [])->withInput();
        }
    }
    
    /**
     * Mettre à jour une vente
     */
    public function update(Request $request, ProductionSale $sale)
    {
        try {
            // Vérifier si la vente est déjà validée ou rejetée
            if ($sale->status !== 'en attente') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette vente a déjà été ' . $sale->status
                ], 422);
            }
            
            $validator = Validator::make($request->all(), [
                'type_id' => 'required|exists:type_productions,id',
                'carton_type' => 'required|in:petit,grand',
                'quantity' => 'required|numeric|min:0.01',
                'unit_price' => 'required|numeric|min:0',
                'client_name' => 'required|string|max:255',
                'client_firstname' => 'required|string|max:255',
                'client_phone' => 'nullable|string|max:50',
                'client_email' => 'nullable|email|max:255',
                'notes' => 'nullable|string'
            ]);
            
            if ($validator->fails()) {
                throw new \Illuminate\Validation\ValidationException($validator);
            }
            
            // Vérifier la disponibilité du stock si le type ou la quantité a changé
            if ($sale->type_id != $request->type_id || 
                $sale->carton_type != $request->carton_type || 
                $sale->quantity != $request->quantity) {
                
                $stockData = $this->calculateAvailableStock();
                $typeId = $request->type_id;
                $cartonType = $request->carton_type;
                $quantity = $request->quantity;
                
                if (!isset($stockData[$typeId])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Type de production invalide'
                    ], 422);
                }
                
                // Pour la modification, nous devons exclure la quantité actuelle de la vente
                $availableStock = $stockData[$typeId][$cartonType]['available'];
                
                // Ajouter la quantité actuelle de cette vente si c'est le même type et carton
                if ($sale->type_id == $typeId && $sale->carton_type == $cartonType) {
                    $availableStock += $sale->quantity;
                }
                
                if ($quantity > $availableStock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stock insuffisant. Disponible: ' . $availableStock . ' cartons ' . $cartonType . 's'
                    ], 422);
                }
            }
            
            // Mettre à jour la vente
            $sale->update([
                'type_id' => $request->type_id,
                'carton_type' => $request->carton_type,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'client_name' => $request->client_name,
                'client_firstname' => $request->client_firstname,
                'client_phone' => $request->client_phone,
                'client_email' => $request->client_email,
                'notes' => $request->notes
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vente mise à jour avec succès',
                    'sale' => $sale->load('type')
                ]);
            }
            
            return redirect()->route('productions.sales.index')->with('success', 'Vente mise à jour avec succès');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour de la vente: ' . $e->getMessage(),
                    'errors' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : null
                ], 422);
            }
            
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour de la vente')->withErrors($e->errors() ?? [])->withInput();
        }
    }
    
    /**
     * Supprimer une vente
     */
    public function destroy(ProductionSale $sale, Request $request)
    {
        try {
            // Vérifier si la vente est déjà validée ou rejetée
            if ($sale->status !== 'en attente') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette vente a déjà été ' . $sale->status . ' et ne peut pas être supprimée'
                ], 422);
            }
            
            $sale->delete();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vente supprimée avec succès'
                ]);
            }
            
            return redirect()->route('productions.sales.index')->with('success', 'Vente supprimée avec succès');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de la vente: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Erreur lors de la suppression de la vente');
        }
    }
    
    /**
     * Valider une ou plusieurs ventes
     */
    // public function validateProductionSale(Request $request)
    // {
    //     try {
    //         $saleIds = $request->sale_ids;
            
    //         if (empty($saleIds)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Aucune vente sélectionnée'
    //             ], 422);
    //         }
            
    //         // Récupérer les ventes en attente
    //         $sales = ProductionSale::whereIn('id', $saleIds)
    //             ->where('status', 'en attente')
    //             ->with('type')
    //             ->get();
            
    //         if ($sales->isEmpty()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Aucune vente en attente trouvée parmi les sélectionnées'
    //             ], 422);
    //         }
            
    //         // Vérifier la disponibilité du stock pour toutes les ventes
    //         $stockData = $this->calculateAvailableStock();
    //         $stockErrors = [];
            
    //         // Regrouper les ventes par type et taille pour vérifier le stock total
    //         $salesByTypeAndSize = [];
            
    //         foreach ($sales as $sale) {
    //             $typeId = $sale->type_id;
    //             $cartonType = $sale->carton_type;
                
    //             if (!isset($salesByTypeAndSize[$typeId])) {
    //                 $salesByTypeAndSize[$typeId] = [
    //                     'petit' => 0,
    //                     'grand' => 0
    //                 ];
    //             }
                
    //             $salesByTypeAndSize[$typeId][$cartonType] += $sale->quantity;
    //         }
            
    //         // Vérifier le stock pour chaque groupe
    //         foreach ($salesByTypeAndSize as $typeId => $sizes) {
    //             foreach ($sizes as $cartonType => $quantity) {
    //                 if ($quantity > 0) {
    //                     $available = $stockData[$typeId][$cartonType]['available'];
                        
    //                     if ($quantity > $available) {
    //                         $typeName = $stockData[$typeId]['name'];
    //                         $stockErrors[] = "Stock insuffisant pour le type '{$typeName}', carton '{$cartonType}'. Disponible: {$available}, Requis: {$quantity}";
    //                     }
    //                 }
    //             }
    //         }
            
    //         if (!empty($stockErrors)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Erreurs de stock détectées',
    //                 'errors' => $stockErrors
    //             ], 422);
    //         }
            
    //         // Tout est bon, valider les ventes
    //         foreach ($sales as $sale) {
    //             $sale->status = 'validé';
    //             $sale->save();
    //         }
            
    //         if ($request->ajax() || $request->wantsJson()) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => count($sales) . ' vente(s) validée(s) avec succès'
    //             ]);
    //         }
            
    //         return redirect()->route('productions.sales.index')->with('success', count($sales) . ' vente(s) validée(s) avec succès');
    //     } catch (\Exception $e) {
    //         if ($request->ajax() || $request->wantsJson()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Erreur lors de la validation des ventes: ' . $e->getMessage()
    //             ], 500);
    //         }
            
    //         return redirect()->back()->with('error', 'Erreur lors de la validation des ventes');
    //     }
    // }

    /**
 * Calculer le stock disponible par type et taille de carton
 */
private function calculateAvailableStock()
{
    // Structure pour stocker les résultats
    $stockData = [];
    
    // Récupérer tous les types de production
    $types = TypeProduction::all();
    
    foreach ($types as $type) {
        $typeId = $type->id;
        $stockData[$typeId] = [
            'name' => $type->name,
            'petit' => [
                'produced' => 0,
                'sold' => 0,
                'pending' => 0,
                'available' => 0
            ],
            'grand' => [
                'produced' => 0,
                'sold' => 0,
                'pending' => 0,
                'available' => 0
            ]
        ];
        
        // Récupérer le stock actuel pour ce type
        $petitStock = ProductionStock::getStockQuantity($typeId, 'petit');
        $grandStock = ProductionStock::getStockQuantity($typeId, 'grand');
        
        $stockData[$typeId]['petit']['produced'] = $petitStock;
        $stockData[$typeId]['petit']['available'] = $petitStock;
        
        $stockData[$typeId]['grand']['produced'] = $grandStock;
        $stockData[$typeId]['grand']['available'] = $grandStock;
        
        // Calculer le nombre total de cartons en attente de validation
        $pendingByType = ProductionSale::where('type_id', $typeId)
            ->where('status', 'en attente')
            ->select('carton_type', DB::raw('SUM(quantity) as total'))
            ->groupBy('carton_type')
            ->get();
        
        foreach ($pendingByType as $pending) {
            $stockData[$typeId][$pending->carton_type]['pending'] = $pending->total;
        }
        
        // Calculer le nombre total de cartons vendus par taille (ventes validées)
        $salesByType = ProductionSale::where('type_id', $typeId)
            ->where('status', 'validé')
            ->select('carton_type', DB::raw('SUM(quantity) as total'))
            ->groupBy('carton_type')
            ->get();
        
        foreach ($salesByType as $sale) {
            $stockData[$typeId][$sale->carton_type]['sold'] = $sale->total;
        }
    }
    
    return $stockData;
}

/**
 * Valider une ou plusieurs ventes
 */
public function validateProductionSale(Request $request)
{
    try {
        $saleIds = $request->sale_ids;
        
        if (empty($saleIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune vente sélectionnée'
            ], 422);
        }
        
        // Récupérer les ventes en attente
        $sales = ProductionSale::whereIn('id', $saleIds)
            ->where('status', 'en attente')
            ->with('type')
            ->get();
        
        if ($sales->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune vente en attente trouvée parmi les sélectionnées'
            ], 422);
        }
        
        // Vérifier la disponibilité du stock pour toutes les ventes
        $stockErrors = [];
        
        // Regrouper les ventes par type et taille pour vérifier le stock total
        $salesByTypeAndSize = [];
        
        foreach ($sales as $sale) {
            $typeId = $sale->type_id;
            $cartonType = $sale->carton_type;
            
            if (!isset($salesByTypeAndSize[$typeId])) {
                $salesByTypeAndSize[$typeId] = [
                    'petit' => 0,
                    'grand' => 0
                ];
            }
            
            $salesByTypeAndSize[$typeId][$cartonType] += $sale->quantity;
        }
        
        // Vérifier le stock pour chaque groupe
        foreach ($salesByTypeAndSize as $typeId => $sizes) {
            foreach ($sizes as $cartonType => $quantity) {
                if ($quantity > 0) {
                    if (!ProductionStock::hasEnoughStock($typeId, $cartonType, $quantity)) {
                        $type = TypeProduction::find($typeId);
                        $available = ProductionStock::getStockQuantity($typeId, $cartonType);
                        $stockErrors[] = "Stock insuffisant pour le type '{$type->name}', carton '{$cartonType}'. Disponible: {$available}, Requis: {$quantity}";
                    }
                }
            }
        }
        
        if (!empty($stockErrors)) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de stock détectées',
                'errors' => $stockErrors
            ], 422);
        }
        
        DB::beginTransaction();
        
        try {
            // Tout est bon, valider les ventes et mettre à jour les stocks
            foreach ($sales as $sale) {
                // Mettre à jour le statut de la vente
                $sale->status = 'validé';
                $sale->save();
                
                // Diminuer le stock
                ProductionStock::removeStock(
                    $sale->type_id,
                    $sale->carton_type,
                    $sale->quantity
                );
            }
            
            DB::commit();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => count($sales) . ' vente(s) validée(s) avec succès'
                ]);
            }
            
            return redirect()->route('productions.sales.index')->with('success', count($sales) . ' vente(s) validée(s) avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    } catch (\Exception $e) {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la validation des ventes: ' . $e->getMessage()
            ], 500);
        }
        
        return redirect()->back()->with('error', 'Erreur lors de la validation des ventes');
    }
}
    
    /**
     * Rejeter une ou plusieurs ventes
     */
    public function reject(Request $request)
    {
        try {
            $saleIds = $request->sale_ids;
            
            if (empty($saleIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune vente sélectionnée'
                ], 422);
            }
            
            $count = ProductionSale::whereIn('id', $saleIds)
                ->where('status', 'en attente')
                ->update(['status' => 'rejeté']);
            
            if ($count === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune vente en attente trouvée parmi les sélectionnées'
                ], 422);
            }
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $count . ' vente(s) rejetée(s) avec succès'
                ]);
            }
            
            return redirect()->route('productions.sales.index')->with('success', $count . ' vente(s) rejetée(s) avec succès');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du rejet des ventes: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Erreur lors du rejet des ventes');
        }
    }

    /**
     * Afficher les bilans par type de production et type de carton
     */
    public function reports()
    {
        // Récupérer tous les types de production
        $types = TypeProduction::all();
        
        // Stock actuel
        $stockData = $this->calculateAvailableStock();
        
        // Statistiques de ventes par mois pour l'année en cours
        $currentYear = date('Y');
        $monthlySales = DB::table('production_sales')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                'type_id',
                'carton_type',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(quantity * unit_price) as total_amount')
            )
            ->whereYear('created_at', $currentYear)
            ->where('status', 'validé')
            ->groupBy('month', 'type_id', 'carton_type')
            ->orderBy('month')
            ->get();
        
        // Préparer les données pour le graphique
        $chartData = [
            'labels' => ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            'datasets' => []
        ];
        
        // Statistiques des clients
        $topClients = DB::table('production_sales')
            ->select(
                'client_name',
                'client_firstname',
                DB::raw('COUNT(*) as total_sales'),
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(quantity * unit_price) as total_amount')
            )
            ->where('status', 'validé')
            ->groupBy('client_name', 'client_firstname')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();
        
        return view('productions.productions-sales-reports', compact('types', 'stockData', 'monthlySales', 'chartData', 'topClients'));
    }
}