<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Envía un WhatsApp de texto libre a través del servicio Node (whatsapp-web.js).
     *
     * Nunca lanza excepciones: si falla, deja el error en storage/logs/laravel.log
     * y devuelve false, para no tumbar el agendamiento de la cita.
     */
    public function enviar(?string $telefono, string $mensaje): bool
    {
        $numero = $this->normalizar($telefono);

        if (!$numero) {
            Log::warning('WhatsApp no enviado: teléfono vacío o inválido.');
            return false;
        }

        try {
            $respuesta = Http::withHeaders(['x-token' => config('services.whatsapp.token')])
                ->timeout(10)
                ->post(rtrim(config('services.whatsapp.url'), '/') . '/enviar', [
                    'numero'  => $numero,
                    'mensaje' => $mensaje,
                ]);

            if (!$respuesta->successful()) {
                Log::error("WhatsApp falló ({$numero}): " . $respuesta->body());
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error("Error enviando WhatsApp a {$numero}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deja el teléfono solo con dígitos y con indicativo: 573112340072
     * (el servicio Node lo necesita sin el signo +).
     */
    private function normalizar(?string $telefono): ?string
    {
        if (!$telefono) {
            return null;
        }

        $limpio = preg_replace('/\D/', '', $telefono);

        if ($limpio === '') {
            return null;
        }

        // Celular colombiano de 10 dígitos (3XXXXXXXXX)
        if (strlen($limpio) === 10 && str_starts_with($limpio, '3')) {
            return '57' . $limpio;
        }

        return $limpio;
    }
}