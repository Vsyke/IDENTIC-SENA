<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Estudiante extends Model
{
    use HasFactory;

    // Nombre de la tabla (importante si no sigue el plural en inglés)
    protected $table = 'estudiantes';

    protected $fillable = ['user_id', 'ficha_id'];

    /**
     * Relación inversa: El estudiante pertenece a un Usuario
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación: El estudiante pertenece a una Ficha
     */
    public function ficha(): BelongsTo
    {
        return $this->belongsTo(Ficha::class, 'ficha_id');
    }
}