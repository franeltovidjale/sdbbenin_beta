<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'type',
        'quantity',
        'unit_price',
        'notes',
        'status'
    ];

    /**
     * Get the article associated with the movement.
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}