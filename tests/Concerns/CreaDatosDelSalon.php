<?php

namespace Tests\Concerns;

use App\Models\Cita;
use App\Models\Especialista;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

/**
 * Ayudas para crear datos de prueba del salón (clientas, especialistas, servicios y citas)
 * y para simular el servicio de WhatsApp sin enviar mensajes reales.
 */
trait CreaDatosDelSalon
{
    /** Simula el servicio de WhatsApp (Node) y el correo: nada sale de verdad. */
    protected function simularNotificaciones(int $estadoWhatsApp = 200): void
    {
        config([
            'services.whatsapp.url'     => 'http://wa.test',
            'services.whatsapp.token'   => 'token-de-prueba',
            'services.whatsapp.negocio' => null,
        ]);

        Http::swap(new \Illuminate\Http\Client\Factory());   // borra simulaciones anteriores
        Http::fake(['wa.test/*' => Http::response(['ok' => $estadoWhatsApp === 200], $estadoWhatsApp)]);
        Mail::fake();
    }

    protected function crearClienta(array $datos = []): User
    {
        static $n = 0;
        $n++;

        return User::create($datos + [
            'name'     => "Clienta {$n}",
            'email'    => "clienta{$n}@test.com",
            'password' => 'password',
            'role'     => 'cliente',
            'telefono' => '30000000' . str_pad((string) $n, 2, '0', STR_PAD_LEFT),
        ]);
    }

    protected function crearAdmin(): User
    {
        return User::create([
            'name' => 'Admin', 'email' => 'admin@test.com',
            'password' => 'password', 'role' => 'admin',
        ]);
    }

    protected function crearServicio(string $nombre = 'Manicure', float $precio = 30000): Servicio
    {
        return Servicio::create(['nombre' => $nombre, 'precio' => $precio, 'activo' => true]);
    }

    protected function crearEspecialista(string $nombre = 'Dana'): Especialista
    {
        $user = User::create([
            'name' => $nombre, 'email' => strtolower($nombre) . '@test.com',
            'password' => 'password', 'role' => 'especialista',
        ]);

        return Especialista::create(['user_id' => $user->id, 'telefono' => '3110000000']);
    }

    protected function crearCita(User $clienta, Especialista $especialista, Servicio $servicio, string $fechaHora, array $extra = []): Cita
    {
        $inicio = \Carbon\Carbon::parse($fechaHora);

        $cita = Cita::create($extra + [
            'user_id'         => $clienta->id,
            'servicio_id'     => $servicio->id,
            'especialista_id' => $especialista->id,
            'fecha'           => $inicio->toDateString(),
            'hora'            => $inicio->format('H:i'),
            'estado'          => 'pendiente',
        ]);

        // created_at controlado: por defecto la cita se agendó hace 2 días
        $cita->forceFill(['created_at' => $extra['created_at'] ?? now()->subDays(2)])->saveQuietly();

        return $cita;
    }

    /** Cuántos WhatsApp se enviaron al número indicado (con indicativo 57). */
    protected function whatsappsEnviadosA(string $celular): int
    {
        return Http::recorded(fn ($request) => str_ends_with($request->url(), '/enviar')
            && $request['numero'] === '57' . $celular)->count();
    }
}
