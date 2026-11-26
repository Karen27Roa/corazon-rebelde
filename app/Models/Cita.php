<?php

namespace App\Models;

use App\Observers\CitaObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(CitaObserver::class)]
class Cita extends Model
{
    protected $fillable = [
        'user_id', 'servicio_id', 'especialista_id',
        'fecha', 'hora', 'notas', 'estado', 'google_event_id',
        'recordatorio_enviado_at',
    ];

    protected $casts = [
        'recordatorio_enviado_at' => 'datetime',
    ];

    /** Horas que ofrece el salón (cada 30 minutos). */
    public const HORAS = [
        '07:00', '07:30', '08:00', '08:30', '09:00', '09:30',
        '10:00', '10:30', '11:00', '11:30', '12:00', '12:30',
        '13:00', '13:30', '14:00', '14:30', '15:00', '15:30',
        '16:00', '16:30', '17:00', '17:30', '18:00', '18:30',
        '19:00', '19:30', '20:00', '20:30', '21:00', '21:30',
    ];

    /** Anticipación mínima para agendar una cita (en minutos). */
    public const MINUTOS_ANTICIPACION = 30;

    /** Desde qué momento se puede agendar: ahora + la anticipación mínima. */
    public static function limiteReserva(): Carbon
    {
        return now()->addMinutes(self::MINUTOS_ANTICIPACION);
    }

    /** ¿Esa fecha y hora todavía se pueden agendar? */
    public static function horarioDisponibleEnElTiempo(string $fecha, string $hora): bool
    {
        return Carbon::parse(Carbon::parse($fecha)->format('Y-m-d') . ' ' . $hora)->gte(self::limiteReserva());
    }

    /** Primer día que se puede elegir: hoy, o mañana si ya pasó la última hora de hoy. */
    public static function fechaMinima(): string
    {
        $ultimaDeHoy = Carbon::parse(today()->toDateString() . ' ' . self::HORAS[array_key_last(self::HORAS)]);

        return self::limiteReserva()->gt($ultimaDeHoy)
            ? today()->addDay()->toDateString()
            : today()->toDateString();
    }

    public function user()         { return $this->belongsTo(User::class); }
    public function servicio()     { return $this->belongsTo(Servicio::class); }
    public function especialista() { return $this->belongsTo(Especialista::class); }

    // Fecha y hora de inicio como objeto Carbon
    public function getInicioAttribute(): Carbon
    {
        return Carbon::parse(Carbon::parse($this->fecha)->format('Y-m-d') . ' ' . $this->hora);
    }
}