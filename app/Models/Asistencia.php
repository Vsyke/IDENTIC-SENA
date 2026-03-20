<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'asistencias';

    protected $fillable = [
        'user_id',
        'fecha',
        'entrada',
        'salida',
    ];

    /**
     * 🔥 ESTO ES CLAVE
     * Convierte automáticamente a Carbon
     */
    protected $casts = [
        'fecha'   => 'date',
        'entrada' => 'datetime',
        'salida'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
