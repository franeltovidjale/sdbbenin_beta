<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionOutput extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'production_id',
        'carton_type', // 'petit' ou 'grand'
        'carton_count'
    ];
    
    public function production()
    {
        return $this->belongsTo(Production::class);
    }
}
