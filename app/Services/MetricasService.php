<?php

namespace App\Services;

use App\Models\Cita;
use App\Models\ListaEspera;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Calcula los indicadores (KPIs) del negocio para el panel de administración.
 *
 * Los cálculos se hacen con colecciones de PHP para que funcionen igual
 * en MySQL (XAMPP) y en SQLite (pruebas automáticas).
 */
class MetricasService
{
    private const MESES = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    private const DIAS  = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];

    private Collection $citas;

    public function __construct()
    {
        $this->citas = Cita::with(['servicio', 'especialista.user'])->get();
    }

    /** Todo lo que necesita el dashboard, en un solo arreglo. */
    public function resumen(): array
    {
        return [
            'kpis'              => $this->kpis(),
            'citasPorMes'       => $this->citasPorMes(6),
            'serviciosTop'      => $this->serviciosMasPedidos(5),
            'porEspecialista'   => $this->citasPorEspecialista(),
            'horasPico'         => $this->horasPico(),
            'diasSemana'        => $this->citasPorDiaSemana(),
        ];
    }

    public function kpis(): array
    {
        $inicioMes   = now()->startOfMonth();
        $inicioMesAnt = now()->subMonthNoOverflow()->startOfMonth();
        $finMesAnt   = now()->subMonthNoOverflow()->endOfMonth();

        $delMes    = $this->citas->filter(fn ($c) => $this->fecha($c)->between($inicioMes, now()->endOfMonth()));
        $mesAnt    = $this->citas->filter(fn ($c) => $this->fecha($c)->between($inicioMesAnt, $finMesAnt));

        $ingresosMes = $delMes->where('estado', 'completada')->sum(fn ($c) => (float) ($c->servicio->precio ?? 0));
        $ingresosAnt = $mesAnt->where('estado', 'completada')->sum(fn ($c) => (float) ($c->servicio->precio ?? 0));

        // Citas que ya pasaron y no fueron canceladas: base para medir asistencia
        $pasadas = $this->citas->filter(fn ($c) => $c->estado !== 'cancelada' && $c->inicio->isPast());

        return [
            'citasMes'           => $delMes->count(),
            'variacionCitas'     => $this->variacion($delMes->count(), $mesAnt->count()),
            'ingresosMes'        => $ingresosMes,
            'variacionIngresos'  => $this->variacion($ingresosMes, $ingresosAnt),
            'ticketPromedio'     => $delMes->where('estado', 'completada')->count()
                                        ? round($ingresosMes / $delMes->where('estado', 'completada')->count())
                                        : 0,
            'tasaCancelacion'    => $this->porcentaje($this->citas->where('estado', 'cancelada')->count(), $this->citas->count()),
            'tasaAsistencia'     => $this->porcentaje($pasadas->where('estado', 'completada')->count(), $pasadas->count()),
            'clientasNuevasMes'  => User::where('role', 'cliente')->where('created_at', '>=', $inicioMes)->count(),
            'clientasRecurrentes'=> $this->porcentaje(
                                        $this->citas->groupBy('user_id')->filter(fn ($g) => $g->count() > 1)->count(),
                                        $this->citas->pluck('user_id')->unique()->count()
                                    ),
            'proximas7Dias'      => $this->citas->filter(fn ($c) => $c->estado === 'pendiente'
                                        && $c->inicio->between(now(), now()->addDays(7)))->count(),
            'recordatoriosMes'   => $this->citas->filter(fn ($c) => $c->recordatorio_enviado_at
                                        && $c->recordatorio_enviado_at->gte($inicioMes))->count(),
            'enListaEspera'      => ListaEspera::activas()->count(),
            'cuposRecuperados'   => ListaEspera::where('estado', ListaEspera::AGENDADA)
                                        ->whereNotNull('notificada_at')->count(),
        ];
    }

    /** Citas de los últimos N meses, separadas por estado. */
    public function citasPorMes(int $meses): array
    {
        $resultado = ['labels' => [], 'completadas' => [], 'pendientes' => [], 'canceladas' => []];

        for ($i = $meses - 1; $i >= 0; $i--) {
            $mes = now()->startOfMonth()->subMonthsNoOverflow($i);
            $delMes = $this->citas->filter(fn ($c) => $this->fecha($c)->isSameMonth($mes));

            $resultado['labels'][]      = self::MESES[$mes->month - 1] . ' ' . $mes->format('y');
            $resultado['completadas'][] = $delMes->where('estado', 'completada')->count();
            $resultado['pendientes'][]  = $delMes->where('estado', 'pendiente')->count();
            $resultado['canceladas'][]  = $delMes->where('estado', 'cancelada')->count();
        }

        return $resultado;
    }

    public function serviciosMasPedidos(int $limite): array
    {
        $top = $this->citas->where('estado', '!=', 'cancelada')
            ->groupBy(fn ($c) => $c->servicio->nombre ?? 'Sin servicio')
            ->map->count()
            ->sortDesc()
            ->take($limite);

        return ['labels' => $top->keys()->all(), 'valores' => $top->values()->all()];
    }

    public function citasPorEspecialista(): array
    {
        $grupo = $this->citas->where('estado', '!=', 'cancelada')
            ->groupBy(fn ($c) => $c->especialista->user->name ?? 'Sin especialista')
            ->map->count()
            ->sortDesc();

        return ['labels' => $grupo->keys()->all(), 'valores' => $grupo->values()->all()];
    }

    /** Cantidad de citas por hora del día (7 AM a 9 PM). */
    public function horasPico(): array
    {
        $conteo = $this->citas->where('estado', '!=', 'cancelada')
            ->countBy(fn ($c) => (int) Carbon::parse($c->hora)->format('G'));

        $labels = $valores = [];
        for ($h = 7; $h <= 21; $h++) {
            $labels[]  = Carbon::createFromTime($h)->format('g A');
            $valores[] = $conteo->get($h, 0);
        }

        return ['labels' => $labels, 'valores' => $valores];
    }

    public function citasPorDiaSemana(): array
    {
        $conteo = $this->citas->where('estado', '!=', 'cancelada')
            ->countBy(fn ($c) => $this->fecha($c)->dayOfWeekIso); // 1 = lunes

        return [
            'labels'  => self::DIAS,
            'valores' => collect(range(1, 7))->map(fn ($d) => $conteo->get($d, 0))->all(),
        ];
    }

    // ── Ayudas ──

    private function fecha(Cita $cita): Carbon
    {
        return Carbon::parse($cita->fecha)->startOfDay();
    }

    private function porcentaje(int|float $parte, int|float $total): float
    {
        return $total > 0 ? round($parte * 100 / $total, 1) : 0.0;
    }

    /** Variación porcentual frente al período anterior (null si no hay con qué comparar). */
    private function variacion(int|float $actual, int|float $anterior): ?float
    {
        if ($anterior == 0) {
            return null;
        }

        return round(($actual - $anterior) * 100 / $anterior, 1);
    }
}
