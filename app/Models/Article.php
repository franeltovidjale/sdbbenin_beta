<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'unit_price',
        'stock_quantity',
        'in_stock'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'in_stock' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(Categorie::class, 'category_id');
    }

    // Mutateur pour mettre à jour in_stock automatiquement
    public function setStockQuantityAttribute($value)
    {
        $this->attributes['stock_quantity'] = $value;
        $this->attributes['in_stock'] = $value > 0;
    }

    public function productionArticles()
    {
        return $this->hasMany(ProductionArticle::class);
    }
}