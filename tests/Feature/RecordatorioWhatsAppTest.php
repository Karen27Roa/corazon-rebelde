<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreaDatosDelSalon;
use Tests\TestCase;

/**
 * Comando citas:recordatorios (se ejecuta solo cada 30 minutos con el Scheduler).
 */
class RecordatorioWhatsAppTest extends TestCase
{
    use RefreshDatabase, CreaDatosDelSalon;

    protected function setUp(): void
    {
        parent::setUp();
        $this->simularNotificaciones();
        $this->travelTo(now()->setDate(2026, 11, 25)->setTime(18, 0));
    }

    public function test_envia_recordatorio_a_las_citas_de_las_proximas_24_horas(): void
    {
        $clienta = $this->crearClienta(['telefono' => '3001112233']);
        $cita = $this->crearCita($clienta, $this->crearEspecialista(), $this->crearServicio(), '2026-11-26 10:00');

        $this->artisan('citas:recordatorios')->assertSuccessful();

        $this->assertSame(1, $this->whatsappsEnviadosA('3001112233'));
        $this->assertNotNull($cita->fresh()->recordatorio_enviado_at);
    }

    public function test_no_envia_recordatorio_a_citas_lejanas(): void
    {
        $clienta = $this->crearClienta(['telefono' => '3001112233']);
        $this->crearCita($clienta, $this->crearEspecialista(), $this->crearServicio(), '2026-11-28 10:00');

        $this->artisan('citas:recordatorios')->assertSuccessful();

        $this->assertSame(0, $this->whatsappsEnviadosA('3001112233'));
    }

    public function test_no_envia_el_recordatorio_dos_veces(): void
    {
        $clienta = $this->crearClienta(['telefono' => '3001112233']);
        $this->crearCita($clienta, $this->crearEspecialista(), $this->crearServicio(), '2026-11-26 10:00');

        $this->artisan('citas:recordatorios');
        $this->artisan('citas:recordatorios');

        $this->assertSame(1, $this->whatsappsEnviadosA('3001112233'));
    }

    public function test_no_recuerda_citas_canceladas(): void
    {
        $clienta = $this->crearClienta(['telefono' => '3001112233']);
        $this->crearCita($clienta, $this->crearEspecialista(), $this->crearServicio(), '2026-11-26 10:00', ['estado' => 'cancelada']);

        $this->artisan('citas:recordatorios');

        $this->assertSame(0, $this->whatsappsEnviadosA('3001112233'));
    }

    public function test_no_recuerda_una_cita_que_se_acaba_de_agendar(): void
    {
        $clienta = $this->crearClienta(['telefono' => '3001112233']);
        $this->crearCita($clienta, $this->crearEspecialista(), $this->crearServicio(), '2026-11-26 10:00', [
            'created_at' => now()->subMinutes(30),
        ]);

        $this->artisan('citas:recordatorios');

        $this->assertSame(0, $this->whatsappsEnviadosA('3001112233'));
    }

    public function test_si_whatsapp_esta_apagado_lo_reintenta_en_la_siguiente_ejecucion(): void
    {
        $this->simularNotificaciones(503);
        $clienta = $this->crearClienta(['telefono' => '3001112233']);
        $cita = $this->crearCita($clienta, $this->crearEspecialista(), $this->crearServicio(), '2026-11-26 10:00');

        $this->artisan('citas:recordatorios')->assertSuccessful();

        $this->assertNull($cita->fresh()->recordatorio_enviado_at);
    }

    public function test_al_reagendar_se_programa_un_nuevo_recordatorio(): void
    {
        $clienta = $this->crearClienta(['telefono' => '3001112233']);
        $especialista = $this->crearEspecialista();
        $servicio = $this->crearServicio();
        $cita = $this->crearCita($clienta, $especialista, $servicio, '2026-11-26 10:00');
        $this->artisan('citas:recordatorios');

        $this->actingAs($clienta)->put(route('citas.update', $cita), [
            'servicio_id'     => $servicio->id,
            'especialista_id' => $especialista->id,
            'fecha'           => '2026-11-30',
            'hora'            => '11:00',
        ])->assertRedirect(route('citas.mias'));

        $this->assertNull($cita->fresh()->recordatorio_enviado_at);
    }
}
