<?php

namespace Tests\Feature;

use App\Services\MetricasService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreaDatosDelSalon;
use Tests\TestCase;

/**
 * Indicadores del panel de administración.
 */
class DashboardKpisTest extends TestCase
{
    use RefreshDatabase, CreaDatosDelSalon;

    protected function setUp(): void
    {
        parent::setUp();
        $this->simularNotificaciones();
        $this->travelTo(now()->setDate(2026, 11, 25)->setTime(10, 0));
    }

    public function test_el_admin_ve_el_dashboard_con_indicadores(): void
    {
        $this->actingAs($this->crearAdmin())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Indicadores del negocio')
            ->assertSee('Tasa de cancelación');
    }

    public function test_una_clienta_no_puede_ver_el_dashboard(): void
    {
        $this->actingAs($this->crearClienta())
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_calcula_ingresos_y_tasas_correctamente(): void
    {
        $dana = $this->crearEspecialista();
        $manicure = $this->crearServicio('Manicure', 30000);
        $acrilicas = $this->crearServicio('Acrílicas', 90000);

        // Noviembre: 2 completadas, 1 cancelada, 1 pendiente futura
        $this->crearCita($this->crearClienta(), $dana, $manicure, '2026-11-10 10:00', ['estado' => 'completada']);
        $this->crearCita($this->crearClienta(), $dana, $acrilicas, '2026-11-12 15:00', ['estado' => 'completada']);
        $this->crearCita($this->crearClienta(), $dana, $manicure, '2026-11-14 11:00', ['estado' => 'cancelada']);
        $this->crearCita($this->crearClienta(), $dana, $manicure, '2026-11-28 11:00');

        $kpis = (new MetricasService)->kpis();

        $this->assertSame(4, $kpis['citasMes']);
        $this->assertEquals(120000, $kpis['ingresosMes']);
        $this->assertEquals(60000, $kpis['ticketPromedio']);
        $this->assertEquals(25.0, $kpis['tasaCancelacion']);   // 1 de 4
        $this->assertEquals(100.0, $kpis['tasaAsistencia']);   // 2 pasadas, 2 completadas
        $this->assertSame(1, $kpis['proximas7Dias']);
    }

    public function test_servicios_mas_pedidos_no_cuenta_canceladas(): void
    {
        $dana = $this->crearEspecialista();
        $manicure = $this->crearServicio('Manicure');
        $pedicure = $this->crearServicio('Pedicure');

        $this->crearCita($this->crearClienta(), $dana, $manicure, '2026-11-10 10:00');
        $this->crearCita($this->crearClienta(), $dana, $manicure, '2026-11-10 11:00');
        $this->crearCita($this->crearClienta(), $dana, $pedicure, '2026-11-10 12:00');
        $this->crearCita($this->crearClienta(), $dana, $pedicure, '2026-11-10 13:00', ['estado' => 'cancelada']);
        $this->crearCita($this->crearClienta(), $dana, $pedicure, '2026-11-10 14:00', ['estado' => 'cancelada']);

        $top = (new MetricasService)->serviciosMasPedidos(5);

        $this->assertSame(['Manicure', 'Pedicure'], $top['labels']);
        $this->assertSame([2, 1], $top['valores']);
    }
}
