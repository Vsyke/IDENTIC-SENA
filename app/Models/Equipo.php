<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    // Campos que permitimos llenar masivamente
    protected $fillable = [
        'tipo',
        'marca_serie',
        'user_id',
    ];

    // Relación inversa: Un equipo pertenece a un Usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}