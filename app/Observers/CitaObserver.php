<?php

namespace App\Observers;

use App\Models\Cita;
use App\Services\ListaEsperaService;

/**
 * Patrón Observer: reacciona a los cambios de una cita sin importar desde dónde se hagan
 * (la clienta, el enlace del correo o el panel de administración).
 *
 * Si un horario queda libre, le avisa a la lista de espera.
 */
class CitaObserver
{
    public function __construct(private ListaEsperaService $listaEspera) {}

    /** La clienta agendó: sus solicitudes de lista de espera para ese día quedan atendidas. */
    public function created(Cita $cita): void
    {
        $this->listaEspera->marcarAgendada($cita);
    }

    public function updated(Cita $cita): void
    {
        $estabaActiva = $cita->getOriginal('estado') !== 'cancelada';

        // 1. Se canceló la cita → su horario queda libre
        if ($cita->wasChanged('estado') && $cita->estado === 'cancelada' && $estabaActiva) {
            $this->listaEspera->horarioLiberado($cita);
            return;
        }

        // 2. Se reagendó a otro día, hora o especialista → el horario anterior queda libre
        if ($estabaActiva && $cita->wasChanged(['fecha', 'hora', 'especialista_id'])) {
            $this->listaEspera->horarioLiberado($this->horarioAnterior($cita));
        }
    }

    /** Se eliminó una cita que no estaba cancelada → su horario queda libre */
    public function deleted(Cita $cita): void
    {
        if ($cita->estado !== 'cancelada') {
            $this->listaEspera->horarioLiberado($cita);
        }
    }

    /** Copia en memoria de la cita con los datos que tenía antes de reagendarse. */
    private function horarioAnterior(Cita $cita): Cita
    {
        return (new Cita)->forceFill([
            'user_id'         => $cita->user_id,
            'servicio_id'     => $cita->getOriginal('servicio_id'),
            'especialista_id' => $cita->getOriginal('especialista_id'),
            'fecha'           => $cita->getOriginal('fecha'),
            'hora'            => $cita->getOriginal('hora'),
            'estado'          => 'cancelada',
        ]);
    }
}
