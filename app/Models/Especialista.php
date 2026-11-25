<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialista extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'telefono',
        'descripcion',
        'foto',
        'google_calendar_id',
    ];

    protected static function booted()
    {
        static::created(function ($especialista) {
            $especialista->servicios()->syncWithoutDetaching(Servicio::pluck('id'));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'especialista_servicio');
    }
}