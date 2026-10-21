<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function enviarPlantilla(?string $telefono, string $plantilla, array $parametros): void
    {
        // Si no hay teléfono o no hay token configurado, se salta WhatsApp sin dar error
        if (!$telefono || !config('services.whatsapp.token')) {
            return;
        }

        try {
            $response = Http::withToken(config('services.whatsapp.token'))
                ->post('https://graph.facebook.com/v20.0/' . config('services.whatsapp.phone_id') . '/messages', [
                    'messaging_product' => 'whatsapp',
                    'to'   => $this->normalizar($telefono),
                    'type' => 'template',
                    'template' => [
                        'name'     => $plantilla,
                        'language' => ['code' => 'es'],
                        'components' => [[
                            'type' => 'body',
                            'parameters' => array_map(
                                fn ($p) => ['type' => 'text', 'text' => (string) $p],
                                $parametros
                            ),
                        ]],
                    ],
                ]);

            if ($response->failed()) {
                Log::error('WhatsApp error: ' . $response->body());
            }
        } catch (\Throwable $e) {
            Log::error('WhatsApp excepción: ' . $e->getMessage());
        }
    }

    // Deja solo números y agrega 57 (Colombia) si vienen 10 dígitos
    private function normalizar(string $telefono): string
    {
        $n = preg_replace('/\D/', '', $telefono);
        return strlen($n) === 10 ? '57' . $n : $n;
    }
}