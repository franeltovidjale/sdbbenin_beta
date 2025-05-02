<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Production;
use App\Models\ProductionOutput;
use App\Models\ProductionSale;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Créer un tableau pour stocker les totaux
        $stockTotals = [];
        
        // Calculer les entrées (productions terminées)
        $completedProductions = Production::where('status', 'terminé')->get();
        
        foreach ($completedProductions as $production) {
            $typeId = $production->type_id;
            
            $outputs = ProductionOutput::where('production_id', $production->id)->get();
            
            foreach ($outputs as $output) {
                $cartonType = $output->carton_type;
                $quantity = $output->carton_count;
                
                if (!isset($stockTotals[$typeId])) {
                    $stockTotals[$typeId] = [
                        'petit' => 0,
                        'grand' => 0
                    ];
                }
                
                $stockTotals[$typeId][$cartonType] += $quantity;
            }
        }
        
        // Calculer les sorties (ventes validées)
        $validatedSales = ProductionSale::where('status', 'validé')->get();
        
        foreach ($validatedSales as $sale) {
            $typeId = $sale->type_id;
            $cartonType = $sale->carton_type;
            $quantity = $sale->quantity;
            
            if (!isset($stockTotals[$typeId])) {
                $stockTotals[$typeId] = [
                    'petit' => 0,
                    'grand' => 0
                ];
            }
            
            $stockTotals[$typeId][$cartonType] -= $quantity;
        }
        
        // Créer les enregistrements de stock
        foreach ($stockTotals as $typeId => $sizes) {
            foreach ($sizes as $cartonType => $quantity) {
                DB::table('production_stocks')->insert([
                    'type_id' => $typeId,
                    'carton_type' => $cartonType,
                    'quantity' => max(0, $quantity), // Éviter les stocks négatifs
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Vider la table
        DB::table('production_stocks')->truncate();
    }
};