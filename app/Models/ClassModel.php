<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'instructor',
        'scheduled_at'
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'class_id');
    }

    public function qrCode()
    {
        return $this->hasOne(QrCode::class, 'class_id');
    }
}