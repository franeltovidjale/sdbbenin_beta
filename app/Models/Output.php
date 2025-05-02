<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Output extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'articleQte',
        'comment'
    ];

    // Ajout de la relation avec Article
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    // app/Models/Output.php
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
