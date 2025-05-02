<?php

// namespace App\Http\Controllers\Productions;

// use App\Http\Controllers\Controller;
// use App\Models\Production;
// use App\Models\ProductionLabor;
// use Illuminate\Http\Request;

// class ProductionLaborController extends Controller
// {
//     /**
//      * Ajouter une main d'œuvre à une production
//      */
//     public function store(Request $request)
//     {
//         $validated = $request->validate([
//             'production_id' => 'required|exists:productions,id',
//             'workers_count' => 'required|integer|min:1',
//             'worker_price' => 'required|numeric|min:0.01',
//         ]);
        
//         // Vérifier que la production est toujours en cours
//         $production = Production::findOrFail($validated['production_id']);
//         if ($production->status !== 'en cours') {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Impossible de modifier une production terminée'
//             ], 422);
//         }
        
//         $labor = ProductionLabor::create($validated);
        
//         return response()->json([
//             'success' => true,
//             'message' => 'Main d\'œuvre ajoutée avec succès',
//             'labor' => $labor
//         ]);
//     }
    
//     /**
//      * Mettre à jour une main d'œuvre dans une production
//      */
//     public function update(Request $request, $id)
//     {
//         $validated = $request->validate([
//             'workers_count' => 'required|integer|min:1',
//             'worker_price' => 'required|numeric|min:0.01',
//         ]);
        
//         $labor = ProductionLabor::findOrFail($id);
        
//         // Vérifier que la production est toujours en cours
//         $production = Production::findOrFail($labor->production_id);
//         if ($production->status !== 'en cours') {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Impossible de modifier une production terminée'
//             ], 422);
//         }
        
//         $labor->update($validated);
        
//         return response()->json([
//             'success' => true,
//             'message' => 'Main d\'œuvre modifiée avec succès',
//             'labor' => $labor
//         ]);
//     }
    
//     /**
//      * Supprimer une main d'œuvre d'une production
//      */
//     public function destroy($id)
//     {
//         $labor = ProductionLabor::findOrFail($id);
        
//         // Vérifier que la production est toujours en cours
//         $production = Production::findOrFail($labor->production_id);
//         if ($production->status !== 'en cours') {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Impossible de modifier une production terminée'
//             ], 422);
//         }
        
//         $labor->delete();
        
//         return response()->json([
//             'success' => true,
//             'message' => 'Main d\'œuvre supprimée avec succès'
//         ]);
//     }
// }






namespace App\Http\Controllers\Productions;

use App\Http\Controllers\Controller;
use App\Models\Production;
use App\Models\ProductionLabor;
use Illuminate\Http\Request;

class ProductionLaborController extends Controller
{
    /**
     * Ajouter une main d'œuvre à une production
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'production_id' => 'required|exists:productions,id',
            'workers_count' => 'required|integer|min:1',
            'worker_price' => 'required|numeric|min:0.01',
            'additional_labor' => 'nullable|string',
            'additional_cost' => 'nullable|numeric|min:0',
        ]);
        
        // Vérifier que la production est toujours en cours
        $production = Production::findOrFail($validated['production_id']);
        if ($production->status !== 'en cours') {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de modifier une production terminée'
            ], 422);
        }
        
        $labor = ProductionLabor::create($validated);
        
        // S'assurer que les attributs sont correctement chargés
        $labor->refresh();
        
        return response()->json([
            'success' => true,
            'message' => 'Main d\'œuvre ajoutée avec succès',
            'labor' => $labor
        ]);
    }
    
    /**
     * Mettre à jour une main d'œuvre dans une production
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'workers_count' => 'required|integer|min:1',
                'worker_price' => 'required|numeric|min:0.01',
                'additional_labor' => 'nullable|string',
                'additional_cost' => 'nullable|numeric|min:0',
            ]);
            
            $labor = ProductionLabor::findOrFail($id);
            
            // Vérifier que la production est toujours en cours
            $production = Production::findOrFail($labor->production_id);
            if ($production->status !== 'en cours') {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de modifier une production terminée'
                ], 422);
            }
            
            $labor->update($validated);
            
            // S'assurer que les attributs sont correctement chargés
            $labor->refresh();
            
            return response()->json([
                'success' => true,
                'message' => 'Main d\'œuvre modifiée avec succès',
                'labor' => $labor
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour de la main d\'œuvre: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Supprimer une main d'œuvre d'une production
     */
    public function destroy($id)
    {
        $labor = ProductionLabor::findOrFail($id);
        
        // Vérifier que la production est toujours en cours
        $production = Production::findOrFail($labor->production_id);
        if ($production->status !== 'en cours') {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de modifier une production terminée'
            ], 422);
        }
        
        $labor->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Main d\'œuvre supprimée avec succès'
        ]);
    }
}
