<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categorie extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    protected $table = 'categories';

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'category_id');
    }
    
}
