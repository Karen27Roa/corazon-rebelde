<?php

namespace Tests\Feature;

use App\Models\ListaEspera;
use App\Services\ListaEsperaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreaDatosDelSalon;
use Tests\TestCase;

/**
 * Lista de espera inteligente: cuando se libera un horario se avisa por WhatsApp
 * a las clientas que lo esperaban.
 */
class ListaEsperaTest extends TestCase
{
    use RefreshDatabase, CreaDatosDelSalon;

    private $servicio;
    private $dana;
    private $cita;      // la cita que ocupa el horario de las 3 PM con Dana
    private $duena;     // la clienta dueña de esa cita

    protected function setUp(): void
    {
        parent::setUp();
        $this->simularNotificaciones();
        $this->travelTo(now()->setDate(2026, 11, 25)->setTime(10, 0));

        $this->servicio = $this->crearServicio();
        $this->dana     = $this->crearEspecialista('Dana');
        $this->duena    = $this->crearClienta();
        $this->cita     = $this->crearCita($this->duena, $this->dana, $this->servicio, '2026-11-27 15:00');
    }

    private function anotar(array $datos = [])
    {
        return ListaEspera::create($datos + [
            'user_id'         => $this->crearClienta()->id,
            'servicio_id'     => $this->servicio->id,
            'especialista_id' => $this->dana->id,
            'fecha'           => '2026-11-27',
            'hora'            => '15:00',
            'estado'          => ListaEspera::ESPERANDO,
        ]);
    }

    public function test_una_clienta_se_puede_anotar_en_la_lista_de_espera(): void
    {
        $clienta = $this->crearClienta();

        $this->actingAs($clienta)->post(route('lista-espera.store'), [
            'servicio_id'     => $this->servicio->id,
            'especialista_id' => $this->dana->id,
            'fecha'           => '2026-11-27',
            'hora'            => '15:00',
            'telefono'        => '3009998877',
        ])->assertRedirect(route('lista-espera.index'));

        $this->assertDatabaseHas('lista_espera', ['user_id' => $clienta->id, 'estado' => 'esperando']);
    }

    public function test_si_el_horario_esta_libre_la_manda_a_agendar_directamente(): void
    {
        $this->actingAs($this->crearClienta())->post(route('lista-espera.store'), [
            'servicio_id'     => $this->servicio->id,
            'especialista_id' => $this->dana->id,
            'fecha'           => '2026-11-27',
            'hora'            => '16:00',
            'telefono'        => '3009998877',
        ])->assertRedirect();

        $this->assertSame(0, ListaEspera::count());
    }

    public function test_no_se_puede_anotar_a_una_hora_que_ya_paso(): void
    {
        $this->actingAs($this->crearClienta())->post(route('lista-espera.store'), [
            'servicio_id'     => $this->servicio->id,
            'especialista_id' => $this->dana->id,
            'fecha'           => '2026-11-25',
            'hora'            => '08:00',
            'telefono'        => '3009998877',
        ])->assertSessionHasErrors('hora');

        $this->assertSame(0, ListaEspera::count());
    }

    public function test_no_se_puede_anotar_dos_veces_al_mismo_horario(): void
    {
        $clienta = $this->crearClienta();
        $datos = [
            'servicio_id' => $this->servicio->id, 'especialista_id' => $this->dana->id,
            'fecha' => '2026-11-27', 'hora' => '15:00', 'telefono' => '3009998877',
        ];

        $this->actingAs($clienta)->post(route('lista-espera.store'), $datos);
        $this->actingAs($clienta)->post(route('lista-espera.store'), $datos)->assertSessionHasErrors('fecha');

        $this->assertSame(1, ListaEspera::count());
    }

    public function test_al_cancelar_una_cita_se_avisa_a_quien_esperaba_ese_horario(): void
    {
        $solicitud = $this->anotar(['telefono' => '3005554433']);

        $this->actingAs($this->duena)->patch(route('citas.cancelar', $this->cita));

        $this->assertSame(1, $this->whatsappsEnviadosA('3005554433'));
        $this->assertSame(ListaEspera::NOTIFICADA, $solicitud->fresh()->estado);
        $this->assertNotNull($solicitud->fresh()->notificada_at);
    }

    public function test_el_enlace_corto_del_whatsapp_abre_el_formulario_lleno(): void
    {
        $clienta = $this->crearClienta();
        $solicitud = $this->anotar(['user_id' => $clienta->id, 'hora' => null, 'especialista_id' => null]);

        $this->actingAs($this->duena)->patch(route('citas.cancelar', $this->cita));

        $enviado = \Illuminate\Support\Facades\Http::recorded()
            ->first(fn ($r) => $r[0]['numero'] === '57' . $clienta->telefono)[0]['mensaje'];
        $this->assertStringContainsString(route('lista-espera.cupo', $solicitud), $enviado);

        $this->actingAs($clienta)->get(route('lista-espera.cupo', $solicitud))
            ->assertRedirect(route('citas.create', [
                'servicio_id'     => $this->servicio->id,
                'especialista_id' => $this->dana->id,
                'fecha'           => '2026-11-27',
                'hora'            => '15:00',
            ]));
    }

    public function test_nadie_mas_puede_usar_el_enlace_de_otra_clienta(): void
    {
        $solicitud = $this->anotar();
        $this->actingAs($this->duena)->patch(route('citas.cancelar', $this->cita));

        $this->actingAs($this->crearClienta())
            ->get(route('lista-espera.cupo', $solicitud))
            ->assertForbidden();
    }

    public function test_avisa_a_quien_acepta_cualquier_hora_o_cualquier_especialista(): void
    {
        $cualquierHora = $this->anotar(['hora' => null]);
        $cualquiera    = $this->anotar(['especialista_id' => null]);

        $this->actingAs($this->duena)->patch(route('citas.cancelar', $this->cita));

        $this->assertSame(ListaEspera::NOTIFICADA, $cualquierHora->fresh()->estado);
        $this->assertSame(ListaEspera::NOTIFICADA, $cualquiera->fresh()->estado);
    }

    public function test_no_avisa_a_quien_espera_otro_dia_otra_hora_u_otra_especialista(): void
    {
        $otraEspecialista = $this->crearEspecialista('Laura');

        $otroDia  = $this->anotar(['fecha' => '2026-11-28']);
        $otraHora = $this->anotar(['hora' => '09:00']);
        $otraEsp  = $this->anotar(['especialista_id' => $otraEspecialista->id]);

        $this->actingAs($this->duena)->patch(route('citas.cancelar', $this->cita));

        foreach ([$otroDia, $otraHora, $otraEsp] as $s) {
            $this->assertSame(ListaEspera::ESPERANDO, $s->fresh()->estado);
        }
    }

    public function test_reagendar_una_cita_libera_el_horario_anterior(): void
    {
        $solicitud = $this->anotar();

        $this->actingAs($this->duena)->put(route('citas.update', $this->cita), [
            'servicio_id'     => $this->servicio->id,
            'especialista_id' => $this->dana->id,
            'fecha'           => '2026-11-29',
            'hora'            => '10:00',
        ]);

        $this->assertSame(ListaEspera::NOTIFICADA, $solicitud->fresh()->estado);
    }

    public function test_el_admin_cancelando_desde_el_panel_tambien_avisa(): void
    {
        $solicitud = $this->anotar();

        $this->actingAs($this->crearAdmin())
            ->patch(route('admin.citas.estado', $this->cita), ['estado' => 'cancelada']);

        $this->assertSame(ListaEspera::NOTIFICADA, $solicitud->fresh()->estado);
    }

    public function test_se_avisa_en_orden_de_llegada_y_con_un_maximo(): void
    {
        $solicitudes = collect(range(1, ListaEsperaService::MAX_AVISOS + 2))
            ->map(function ($i) {
                $s = $this->anotar();
                $s->forceFill(['created_at' => now()->subMinutes(100 - $i)])->saveQuietly();
                return $s;
            });

        $this->actingAs($this->duena)->patch(route('citas.cancelar', $this->cita));

        $avisadas = $solicitudes->filter(fn ($s) => $s->fresh()->estado === ListaEspera::NOTIFICADA);
        $this->assertCount(ListaEsperaService::MAX_AVISOS, $avisadas);
        // las dos últimas en anotarse siguen esperando
        $this->assertSame(ListaEspera::ESPERANDO, $solicitudes->last()->fresh()->estado);
    }

    public function test_cuando_la_clienta_agenda_su_solicitud_queda_atendida(): void
    {
        $clienta = $this->crearClienta();
        $solicitud = $this->anotar(['user_id' => $clienta->id, 'hora' => null]);

        $this->actingAs($clienta)->post(route('citas.store'), [
            'servicio_id'     => $this->servicio->id,
            'especialista_id' => $this->dana->id,
            'fecha'           => '2026-11-27',
            'hora'            => '17:00',
            'telefono'        => '3001234567',
        ]);

        $this->assertSame(ListaEspera::AGENDADA, $solicitud->fresh()->estado);
    }

    public function test_las_solicitudes_de_dias_pasados_quedan_vencidas(): void
    {
        $vieja = $this->anotar(['fecha' => '2026-11-20']);
        $vigente = $this->anotar();

        app(ListaEsperaService::class)->marcarVencidas();

        $this->assertSame(ListaEspera::VENCIDA, $vieja->fresh()->estado);
        $this->assertSame(ListaEspera::ESPERANDO, $vigente->fresh()->estado);
    }

    public function test_la_pagina_de_lista_de_espera_carga(): void
    {
        $this->anotar(['user_id' => $this->duena->id]);

        $this->actingAs($this->duena)->get(route('lista-espera.index'))
            ->assertOk()
            ->assertSee('Mis solicitudes');
    }
}
