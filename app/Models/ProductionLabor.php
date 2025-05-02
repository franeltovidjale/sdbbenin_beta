<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionLabor extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'production_id',
        'workers_count',
        'worker_price',
        'additional_labor',  
        'additional_cost'
    ];
    
    public function production()
    {
        return $this->belongsTo(Production::class);
    }
}
