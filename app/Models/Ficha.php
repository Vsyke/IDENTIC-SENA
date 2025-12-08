<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ficha extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'programa',
        'cantidad_estudiantes',
        'aula_id',
        'activo'
    ];

    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }
}
