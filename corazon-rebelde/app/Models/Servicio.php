<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'activo',
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function especialistas()
    {
        return $this->belongsToMany(Especialista::class, 'especialista_servicio');
    }

    public function galeria()
    {
        return $this->hasMany(Galeria::class);
    }
}