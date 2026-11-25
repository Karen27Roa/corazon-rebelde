<?php

namespace App\Services;

use App\Models\Cita;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Illuminate\Support\Facades\Log;

class GoogleCalendarService
{
    protected ?Calendar $calendar = null;
    protected ?string $calendarId = null;

    public function __construct()
    {
        $ruta = config('services.google_calendar.credentials');
        $this->calendarId = config('services.google_calendar.calendar_id');

        // Si faltan los datos de Google, la aplicación sigue funcionando
        // (se agenda la cita y se envían los correos), pero sin crear eventos.
        if (! $ruta || ! $this->calendarId || ! is_file(base_path($ruta))) {
            Log::warning('Google Calendar desactivado: falta GOOGLE_CALENDAR_ID o el archivo de credenciales.');
            return;
        }

        try {
            $client = new Client();
            $client->setApplicationName('Corazón Rebelde');
            $client->setAuthConfig(base_path($ruta));
            $client->setScopes([Calendar::CALENDAR]);

            $this->calendar = new Calendar($client);
        } catch (\Throwable $e) {
            Log::error('Google Calendar (configuración): ' . $e->getMessage());
            $this->calendar = null;
        }
    }

    /**
     * Crea el evento en Google Calendar y guarda su ID en la cita.
     * Nunca lanza excepciones: si falla, solo lo registra en el log.
     */
    public function crearEvento(Cita $cita): ?string
    {
        if (! $this->calendar) {
            return null;
        }

        try {
            $cita->loadMissing(['user', 'servicio', 'especialista.user']);

            $inicio = $cita->inicio;
            $minutos = $cita->servicio->duracion_minutos
                ?? $cita->servicio->duracion
                ?? 60;
            $fin = $inicio->copy()->addMinutes((int) $minutos);

            $descripcion = "Clienta: {$cita->user->name}\n"
                . "Teléfono: {$cita->user->telefono}\n"
                . "Servicio: {$cita->servicio->nombre}\n"
                . "Especialista: {$cita->especialista->user->name}"
                . ($cita->notas ? "\nNotas: {$cita->notas}" : '');

            $evento = new Event([
                'summary'     => "{$cita->servicio->nombre} - {$cita->user->name} ({$cita->especialista->user->name})",
                'description' => $descripcion,
                'start' => [
                    'dateTime' => $inicio->format('Y-m-d\TH:i:s'),
                    'timeZone' => 'America/Bogota',
                ],
                'end' => [
                    'dateTime' => $fin->format('Y-m-d\TH:i:s'),
                    'timeZone' => 'America/Bogota',
                ],
            ]);

            $eventId = $this->calendar->events->insert($this->calendarId, $evento)->getId();

            $cita->update(['google_event_id' => $eventId]);

            return $eventId;
        } catch (\Throwable $e) {
            Log::error('Google Calendar (crear): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Elimina el evento de la cita (si tiene) y limpia el google_event_id.
     */
    public function eliminarEvento(Cita $cita): void
    {
        if (! $this->calendar || ! $cita->google_event_id) {
            return;
        }

        try {
            $this->calendar->events->delete($this->calendarId, $cita->google_event_id);
        } catch (\Throwable $e) {
            // Si el evento ya no existe (404/410) o falla, solo se registra
            Log::error('Google Calendar (eliminar): ' . $e->getMessage());
        }

        $cita->update(['google_event_id' => null]);
    }
}