<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'carton_type',
        'quantity',
        'unit_price',
        'client_name',
        'client_firstname',
        'client_phone',
        'client_email',
        'notes',
        'status'
    ];

    /**
     * Get the production type associated with the sale.
     */
    public function type()
    {
        return $this->belongsTo(TypeProduction::class, 'type_id');
    }
}