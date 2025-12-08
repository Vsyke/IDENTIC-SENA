<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'capacidad',
        'ubicacion',
        'activo'
    ];

    public function fichas()
    {
        return $this->hasMany(Ficha::class);
    }
}
