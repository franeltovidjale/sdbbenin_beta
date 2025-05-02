<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;
    
    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'production_name',
        'type_id',
        'production_date',
        'qte_production',
        'status'
    ];
    
    /**
     * Définit la relation avec TypeProduction
     */
    public function type()
    {
        return $this->belongsTo(TypeProduction::class, 'type_id');
    }

    
}