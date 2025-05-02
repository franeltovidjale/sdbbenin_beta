<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'carton_type',
        'quantity',
    ];

    /**
     * Get the production type associated with the stock.
     */
    public function type()
    {
        return $this->belongsTo(TypeProduction::class, 'type_id');
    }
    
    /**
     * Ajouter une quantité au stock
     */
    public static function addStock($typeId, $cartonType, $quantity)
    {
        $stock = self::firstOrCreate(
            ['type_id' => $typeId, 'carton_type' => $cartonType],
            ['quantity' => 0]
        );
        
        $stock->quantity += $quantity;
        $stock->save();
        
        return $stock;
    }
    
    /**
     * Retirer une quantité du stock
     */
    public static function removeStock($typeId, $cartonType, $quantity)
    {
        $stock = self::firstOrCreate(
            ['type_id' => $typeId, 'carton_type' => $cartonType],
            ['quantity' => 0]
        );
        
        if ($stock->quantity < $quantity) {
            throw new \Exception("Stock insuffisant pour le type $typeId, carton $cartonType. Disponible: {$stock->quantity}, Demandé: $quantity");
        }
        
        $stock->quantity -= $quantity;
        $stock->save();
        
        return $stock;
    }
    
    /**
     * Vérifier si le stock est suffisant
     */
    public static function hasEnoughStock($typeId, $cartonType, $quantity)
    {
        $stock = self::where('type_id', $typeId)
            ->where('carton_type', $cartonType)
            ->first();
            
        if (!$stock) {
            return false;
        }
        
        return $stock->quantity >= $quantity;
    }
    
    /**
     * Obtenir la quantité en stock
     */
    public static function getStockQuantity($typeId, $cartonType)
    {
        $stock = self::where('type_id', $typeId)
            ->where('carton_type', $cartonType)
            ->first();
            
        return $stock ? $stock->quantity : 0;
    }
}