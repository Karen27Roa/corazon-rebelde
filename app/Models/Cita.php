<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = [
        'user_id', 'servicio_id', 'especialista_id',
        'fecha', 'hora', 'notas', 'estado', 'google_event_id',
    ];

    public function user()         { return $this->belongsTo(User::class); }
    public function servicio()     { return $this->belongsTo(Servicio::class); }
    public function especialista() { return $this->belongsTo(Especialista::class); }

    // Fecha y hora de inicio como objeto Carbon
    public function getInicioAttribute(): Carbon
    {
        return Carbon::parse(Carbon::parse($this->fecha)->format('Y-m-d') . ' ' . $this->hora);
    }
}