<?php

namespace App\Services;

use App\Mail\CitaAgendadaMail;
use App\Models\Cita;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CitaNotificador
{
    public function __construct(
        private WhatsAppService $whatsapp,
        private GoogleCalendarService $calendar,
    ) {}

    public function citaAgendada(Cita $cita): void
    {
        $cita->loadMissing(['user', 'servicio', 'especialista.user']);

        // 1. Correos (clienta y estilista)
        try {
            Mail::to($cita->user->email)->send(new CitaAgendadaMail($cita, true));
            Mail::to($cita->especialista->user->email)->send(new CitaAgendadaMail($cita, false));
        } catch (\Throwable $e) {
            Log::error('Correo cita: ' . $e->getMessage());
        }

        // 2. Google Calendar de la estilista
        $this->calendar->crearEvento($cita);

        // 3. WhatsApp
        $fecha = $cita->inicio->format('d/m/Y');
        $hora  = $cita->inicio->format('h:i A');

        $this->whatsapp->enviarPlantilla(
            $cita->user->telefono,
            'cita_confirmada_clienta',
            [$cita->user->name, $cita->servicio->nombre, $fecha, $hora, $cita->especialista->user->name]
        );

        $this->whatsapp->enviarPlantilla(
            $cita->especialista->telefono,
            'cita_nueva_estilista',
            [$cita->especialista->user->name, $cita->user->name, $cita->servicio->nombre, $fecha, $hora]
        );
    }

    public function citaCancelada(Cita $cita): void
    {
        $cita->loadMissing(['user', 'servicio', 'especialista.user']);

        $this->calendar->eliminarEvento($cita);

        $this->whatsapp->enviarPlantilla(
            $cita->especialista->telefono,
            'cita_cancelada_estilista',
            [
                $cita->especialista->user->name,
                $cita->user->name,
                $cita->servicio->nombre,
                $cita->inicio->format('d/m/Y'),
                $cita->inicio->format('h:i A'),
            ]
        );
    }
}