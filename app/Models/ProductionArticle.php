<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle pour les articles utilisés dans une production
 */
class ProductionArticle extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'production_id',
        'article_id',
        'quantity',
        'unit_price'
    ];
    
    public function production()
    {
        return $this->belongsTo(Production::class);
    }
    
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}