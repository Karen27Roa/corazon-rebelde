<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ListaEspera extends Model
{
    protected $table = 'lista_espera';

    // Estados posibles de una solicitud
    public const ESPERANDO = 'esperando';
    public const NOTIFICADA = 'notificada';
    public const AGENDADA = 'agendada';
    public const CANCELADA = 'cancelada';
    public const VENCIDA = 'vencida';

    protected $fillable = [
        'user_id', 'servicio_id', 'especialista_id',
        'fecha', 'hora', 'telefono', 'estado', 'notificada_at',
        'cupo_especialista_id', 'cupo_fecha', 'cupo_hora',
    ];

    protected $casts = [
        'notificada_at' => 'datetime',
    ];

    public function user()         { return $this->belongsTo(User::class); }
    public function servicio()     { return $this->belongsTo(Servicio::class); }
    public function especialista() { return $this->belongsTo(Especialista::class); }

    /** Solicitudes que todavía pueden recibir un aviso o agendar. */
    public function scopeActivas(Builder $query): Builder
    {
        return $query->whereIn('estado', [self::ESPERANDO, self::NOTIFICADA])
                     ->whereDate('fecha', '>=', today());
    }

    /** Hora en formato 07:30 (o null si le sirve cualquier hora). */
    public function getHoraCortaAttribute(): ?string
    {
        return $this->hora ? Carbon::parse($this->hora)->format('H:i') : null;
    }

    /** Texto amigable: "cualquier hora" o "07:30 AM". */
    public function getHoraTextoAttribute(): string
    {
        return $this->hora ? Carbon::parse($this->hora)->format('h:i A') : 'Cualquier hora';
    }
}
