<?php

namespace Tests\Feature;

use App\Models\Cita;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreaDatosDelSalon;
use Tests\TestCase;

/**
 * Reglas de negocio del agendamiento de citas.
 */
class AgendamientoTest extends TestCase
{
    use RefreshDatabase, CreaDatosDelSalon;

    protected function setUp(): void
    {
        parent::setUp();
        $this->simularNotificaciones();
        $this->travelTo(now()->setDate(2026, 11, 25)->setTime(10, 0));
    }

    public function test_una_clienta_puede_agendar_y_recibe_whatsapp_de_confirmacion(): void
    {
        $clienta = $this->crearClienta();
        $servicio = $this->crearServicio();
        $especialista = $this->crearEspecialista();

        $this->actingAs($clienta)->post(route('citas.store'), [
            'servicio_id'     => $servicio->id,
            'especialista_id' => $especialista->id,
            'fecha'           => '2026-11-27',
            'hora'            => '15:00',
            'telefono'        => '300 123 4567',
        ])->assertRedirect(route('citas.mias'));

        $this->assertDatabaseHas('citas', ['user_id' => $clienta->id, 'estado' => 'pendiente']);
        $this->assertSame(1, $this->whatsappsEnviadosA('3001234567'));
    }

    public function test_no_permite_dos_citas_a_la_misma_hora_con_la_misma_especialista(): void
    {
        $servicio = $this->crearServicio();
        $especialista = $this->crearEspecialista();
        $this->crearCita($this->crearClienta(), $especialista, $servicio, '2026-11-27 15:00');

        $this->actingAs($this->crearClienta())->post(route('citas.store'), [
            'servicio_id'     => $servicio->id,
            'especialista_id' => $especialista->id,
            'fecha'           => '2026-11-27',
            'hora'            => '15:00',
            'telefono'        => '3001234567',
        ])->assertSessionHasErrors('hora');

        $this->assertSame(1, Cita::count());
    }

    public function test_si_permite_la_misma_hora_cuando_la_otra_cita_fue_cancelada(): void
    {
        $servicio = $this->crearServicio();
        $especialista = $this->crearEspecialista();
        $this->crearCita($this->crearClienta(), $especialista, $servicio, '2026-11-27 15:00', ['estado' => 'cancelada']);

        $this->actingAs($this->crearClienta())->post(route('citas.store'), [
            'servicio_id'     => $servicio->id,
            'especialista_id' => $especialista->id,
            'fecha'           => '2026-11-27',
            'hora'            => '15:00',
            'telefono'        => '3001234567',
        ])->assertSessionHasNoErrors();

        $this->assertSame(2, Cita::count());
    }

    public function test_una_clienta_no_puede_cancelar_la_cita_de_otra(): void
    {
        $cita = $this->crearCita($this->crearClienta(), $this->crearEspecialista(), $this->crearServicio(), '2026-11-27 15:00');

        $this->actingAs($this->crearClienta())
            ->patch(route('citas.cancelar', $cita))
            ->assertForbidden();

        $this->assertSame('pendiente', $cita->fresh()->estado);
    }

    public function test_no_permite_agendar_hoy_una_hora_que_ya_paso(): void
    {
        // son las 10:00 AM del 25/11
        $this->actingAs($this->crearClienta())->post(route('citas.store'), [
            'servicio_id'     => $this->crearServicio()->id,
            'especialista_id' => $this->crearEspecialista()->id,
            'fecha'           => '2026-11-25',
            'hora'            => '08:00',
            'telefono'        => '3001234567',
        ])->assertSessionHasErrors('hora');

        $this->assertSame(0, Cita::count());
    }

    public function test_exige_media_hora_de_anticipacion_pero_permite_horas_mas_tarde_hoy(): void
    {
        $servicio = $this->crearServicio();
        $especialista = $this->crearEspecialista();
        $datos = ['servicio_id' => $servicio->id, 'especialista_id' => $especialista->id,
                  'fecha' => '2026-11-25', 'telefono' => '3001234567'];

        // 10:00 → muy cerca
        $this->actingAs($this->crearClienta())->post(route('citas.store'), $datos + ['hora' => '10:00'])
            ->assertSessionHasErrors('hora');

        // 10:30 → sí (justo 30 minutos después)
        $this->actingAs($this->crearClienta())->post(route('citas.store'), $datos + ['hora' => '10:30'])
            ->assertSessionHasNoErrors();
    }

    public function test_despues_de_la_ultima_hora_el_primer_dia_disponible_es_manana(): void
    {
        $this->assertSame('2026-11-25', Cita::fechaMinima());   // 10:00 AM

        $this->travelTo(now()->setTime(21, 50));               // 9:50 PM
        $this->assertSame('2026-11-26', Cita::fechaMinima());
    }

    public function test_el_formulario_de_agendar_no_deja_elegir_hoy_si_ya_no_atienden(): void
    {
        $this->travelTo(now()->setTime(21, 50));

        $this->actingAs($this->crearClienta())->get(route('citas.create'))
            ->assertOk()
            ->assertSee('min="2026-11-26"', false);
    }

    public function test_no_permite_agendar_en_una_fecha_pasada(): void
    {
        $this->actingAs($this->crearClienta())->post(route('citas.store'), [
            'servicio_id'     => $this->crearServicio()->id,
            'especialista_id' => $this->crearEspecialista()->id,
            'fecha'           => '2026-11-20',
            'hora'            => '15:00',
            'telefono'        => '3001234567',
        ])->assertSessionHasErrors('fecha');
    }
}
