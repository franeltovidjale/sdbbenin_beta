<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeProduction extends Model
{
    use HasFactory;
    
    protected $table = 'type_productions';
    
    protected $fillable = [
        'name',
    ];
    
    public function productions()
    {
        return $this->hasMany(Production::class, 'type_id');
    }
}