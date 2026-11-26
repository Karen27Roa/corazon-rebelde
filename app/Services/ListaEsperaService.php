<?php

namespace App\Services;

use App\Models\Cita;
use App\Models\ListaEspera;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Lista de espera inteligente.
 *
 * Cuando un horario se libera (cancelación, reagendamiento o eliminación de una cita),
 * busca a las clientas que esperaban un horario compatible y les avisa por WhatsApp
 * en orden de llegada. La primera que agende se queda con el horario: el sistema ya
 * impide dos citas a la misma hora con la misma especialista.
 */
class ListaEsperaService
{
    /** Máximo de clientas a las que se avisa por cada horario liberado. */
    public const MAX_AVISOS = 5;

    public function __construct(private CitaNotificador $notificador) {}

    /**
     * Se liberó el horario de $citaLiberada (fecha, hora y especialista).
     * Devuelve cuántas clientas fueron avisadas.
     */
    public function horarioLiberado(Cita $citaLiberada): int
    {
        try {
            if (! $this->horarioSigueLibre($citaLiberada)) {
                return 0;
            }

            $avisadas = 0;

            foreach ($this->candidatas($citaLiberada) as $solicitud) {
                // Se guarda el cupo ofrecido para que el enlace sea corto: /cupo/{id}
                $solicitud->update([
                    'cupo_especialista_id' => $citaLiberada->especialista_id,
                    'cupo_fecha'           => $citaLiberada->inicio->toDateString(),
                    'cupo_hora'            => $citaLiberada->inicio->format('H:i'),
                ]);

                $enlace = route('lista-espera.cupo', $solicitud);

                if ($this->notificador->horarioDisponible($solicitud, $citaLiberada, $enlace)) {
                    $solicitud->update([
                        'estado'        => ListaEspera::NOTIFICADA,
                        'notificada_at' => now(),
                    ]);
                    $avisadas++;
                }
            }

            return $avisadas;
        } catch (\Throwable $e) {
            // Nunca debe romper la cancelación de una cita
            Log::error('Lista de espera: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Clientas que esperan un horario compatible con la cita liberada,
     * ordenadas por antigüedad (la primera en anotarse va primero).
     */
    public function candidatas(Cita $citaLiberada): Collection
    {
        $hora = $citaLiberada->inicio->format('H:i');

        return ListaEspera::activas()
            ->whereDate('fecha', $citaLiberada->inicio->toDateString())
            ->where('user_id', '!=', $citaLiberada->user_id)
            ->where(fn ($q) => $q->whereNull('especialista_id')
                                 ->orWhere('especialista_id', $citaLiberada->especialista_id))
            ->orderBy('created_at')
            ->orderBy('id')
            ->with(['user', 'servicio'])
            ->get()
            // la hora se compara en PHP para que funcione igual en MySQL y en SQLite
            ->filter(fn (ListaEspera $s) => $s->hora === null || $s->hora_corta === $hora)
            // la especialista debe ofrecer el servicio que la clienta quiere
            ->filter(fn (ListaEspera $s) => $this->especialistaOfrece($citaLiberada->especialista_id, $s->servicio_id))
            ->take(self::MAX_AVISOS)
            ->values();
    }

    /** La clienta agendó una cita: sus solicitudes para ese día quedan atendidas. */
    public function marcarAgendada(Cita $cita): void
    {
        ListaEspera::activas()
            ->where('user_id', $cita->user_id)
            ->whereDate('fecha', $cita->inicio->toDateString())
            ->update(['estado' => ListaEspera::AGENDADA]);
    }

    /** Solicitudes de días que ya pasaron. Se ejecuta a diario desde el Scheduler. */
    public function marcarVencidas(): int
    {
        return ListaEspera::whereIn('estado', [ListaEspera::ESPERANDO, ListaEspera::NOTIFICADA])
            ->whereDate('fecha', '<', today())
            ->update(['estado' => ListaEspera::VENCIDA]);
    }

    /** ¿Hay alguna cita activa en ese horario? (por si el admin la reactivó, por ejemplo) */
    public function horarioSigueLibre(Cita $cita): bool
    {
        if ($cita->inicio->isPast()) {
            return false;
        }

        $hora = $cita->inicio->format('H:i');

        return ! Cita::where('especialista_id', $cita->especialista_id)
            ->whereDate('fecha', $cita->inicio->toDateString())
            ->where('estado', '!=', 'cancelada')
            ->when($cita->exists, fn ($q) => $q->where('id', '!=', $cita->id))
            ->get(['hora'])
            ->contains(fn ($c) => Carbon::parse($c->hora)->format('H:i') === $hora);
    }

    private function especialistaOfrece(int $especialistaId, int $servicioId): bool
    {
        $especialista = \App\Models\Especialista::find($especialistaId);

        // Si la especialista no tiene servicios asignados, se asume que hace todos
        if (! $especialista || $especialista->servicios()->count() === 0) {
            return true;
        }

        return $especialista->servicios()->where('servicios.id', $servicioId)->exists();
    }
}
