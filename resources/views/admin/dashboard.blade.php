<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Corazón Rebelde</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/dashboard.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Georgia', serif; background: #f5f3ff; min-height: 100vh; }
        nav {
            background: linear-gradient(135deg, #6b5ca5, #8b7bc4);
            padding: 10px 32px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 4px 15px rgba(107,92,165,0.4);
            flex-wrap: wrap; gap: 10px;
        }
        .nav-links { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
        .nav-links a { color: white; text-decoration: none; font-size: 14px; font-weight: bold; }
        .nav-links a:hover { text-decoration: underline; }
        .btn-logout {
            background: white; color: #6b5ca5; border: none;
            padding: 8px 18px; border-radius: 20px; font-weight: bold; cursor: pointer;
        }
        .container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
        .bienvenida {
            background: white; border-radius: 20px; padding: 28px 32px;
            margin-bottom: 28px; box-shadow: 0 4px 20px rgba(201,194,238,0.3);
        }
        .bienvenida h1 { color: #6b5ca5; font-size: 22px; }
        .bienvenida p { color: #888; margin-top: 6px; font-size: 14px; }
        .stats {
            display: grid; grid-template-columns: repeat(4, 1fr);
            gap: 16px; margin-bottom: 32px;
        }
        .stat-card {
            background: white; border-radius: 16px; padding: 24px;
            text-align: center; box-shadow: 0 4px 15px rgba(201,194,238,0.2);
            border-top: 4px solid #c9c2ee;
        }
        /* 🆕 Tarjetas clicables */
        a.stat-card {
            display: block;
            text-decoration: none;
            color: inherit;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s;
        }
        a.stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(109, 82, 168, .25);
        }
        .stat-card .numero { font-size: 36px; font-weight: bold; color: #6b5ca5; }
        .stat-card .label { color: #888; font-size: 13px; margin-top: 6px; }
        .stat-card .icono { font-size: 28px; margin-bottom: 8px; }
        .section-title { color: #6b5ca5; font-size: 20px; margin-bottom: 16px; margin-top: 8px; }
        .accesos {
            display: grid; grid-template-columns: repeat(4, 1fr);
            gap: 16px; margin-bottom: 32px;
        }
        .acceso-card {
            background: white; border-radius: 16px; padding: 24px;
            text-align: center; box-shadow: 0 4px 15px rgba(201,194,238,0.2);
            text-decoration: none; transition: all 0.3s;
            border: 2px solid transparent;
        }
        .acceso-card:hover { border-color: #c9c2ee; transform: translateY(-3px); }
        .acceso-card .icono { font-size: 36px; margin-bottom: 12px; }
        .acceso-card h3 { color: #6b5ca5; font-size: 16px; }
        .acceso-card p { color: #aaa; font-size: 13px; margin-top: 6px; }
        table {
            width: 100%; border-collapse: collapse;
            background: white; border-radius: 16px;
            overflow: hidden; box-shadow: 0 4px 15px rgba(201,194,238,0.2);
        }
        th {
            background: linear-gradient(135deg, #c9c2ee, #b8b0e0);
            color: #2c2c2c; padding: 14px 18px; text-align: left; font-size: 13px;
        }
        td { padding: 14px 18px; font-size: 13px; color: #555; border-bottom: 1px solid #f0eeff; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #faf9ff; }
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .pendiente { background: #fff3cd; color: #856404; }
        .completada { background: #d1f5e0; color: #276749; }
        .cancelada { background: #ffe0e0; color: #c0392b; }
        .ver-todas {
            display: inline-block; margin-top: 16px;
            color: #6b5ca5; font-size: 13px; font-weight: bold;
            text-decoration: none;
        }
        .ver-todas:hover { text-decoration: underline; }

        /* ── 📊 Indicadores (KPIs) ── */
        .kpis { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
        .kpi {
            background: white; border-radius: 16px; padding: 20px 22px;
            box-shadow: 0 4px 15px rgba(201,194,238,0.2); border-left: 5px solid #8b7bc4;
        }
        .kpi .titulo { color: #888; font-size: 12px; text-transform: uppercase; letter-spacing: .5px; }
        .kpi .valor { color: #4a3a80; font-size: 28px; font-weight: bold; margin-top: 6px; }
        .kpi .detalle { font-size: 12px; margin-top: 6px; color: #999; }
        .kpi .sube { color: #276749; font-weight: bold; }
        .kpi .baja { color: #c0392b; font-weight: bold; }
        .kpi.verde { border-left-color: #48bb78; }
        .kpi.rojo { border-left-color: #f56565; }
        .kpi.amarillo { border-left-color: #ecc94b; }
        .kpi.azul { border-left-color: #4299e1; }

        .graficas { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 32px; }
        .grafica {
            background: white; border-radius: 16px; padding: 20px 22px;
            box-shadow: 0 4px 15px rgba(201,194,238,0.2);
        }
        .grafica h3 { color: #6b5ca5; font-size: 15px; margin-bottom: 4px; }
        .grafica p { color: #aaa; font-size: 12px; margin-bottom: 14px; }
        .grafica .lienzo { position: relative; height: 240px; }
        .grafica.ancha { grid-column: span 2; }
        .sin-datos { color: #bbb; text-align: center; padding: 80px 0; font-size: 13px; }

        .espera { background: #e0ecff; color: #1d4ed8; }
        .esperando { background: #fff3cd; color: #856404; }
        .notificada { background: #e0ecff; color: #1d4ed8; }

        @media (max-width: 900px) {
            .kpis { grid-template-columns: repeat(2, 1fr); }
            .graficas { grid-template-columns: 1fr; }
            .grafica.ancha { grid-column: auto; }
        }
        @media (max-width: 800px) {
            .stats, .accesos { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
<nav>
    <x-logo />
    <div class="nav-links">
        <a href="{{ route('admin.dashboard') }}">✦ Dashboard</a>
        <a href="{{ route('admin.servicios') }}">✦ Servicios</a>
        <a href="{{ route('admin.especialistas') }}">✦ Especialistas</a>
        <a href="{{ route('admin.clientes') }}">✦ Clientes</a>
        <a href="{{ route('admin.citas') }}">✦ Citas</a>
        <a href="{{ route('admin.galeria') }}">✦ Galería</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Cerrar sesión</button>
        </form>
    </div>
</nav>

<div class="container">
    <div class="bienvenida">
        <h1>Panel de Administración 💜</h1>
        <p>Bienvenida al centro de control de Corazón Rebelde Nails Spa</p>
    </div>

    <div class="stats">
        <a href="{{ route('admin.citas') }}" class="stat-card">
            <div class="icono">📅</div>
            <div class="numero">{{ $totalCitas }}</div>
            <div class="label">Total Citas</div>
        </a>
        <a href="{{ route('admin.clientes') }}" class="stat-card">
            <div class="icono">👥</div>
            <div class="numero">{{ $totalClientes }}</div>
            <div class="label">Clientes</div>
        </a>
        <a href="{{ route('admin.especialistas') }}" class="stat-card">
            <div class="icono">💅</div>
            <div class="numero">{{ $totalEspecialistas }}</div>
            <div class="label">Especialistas</div>
        </a>
        <a href="{{ route('admin.servicios') }}" class="stat-card">
            <div class="icono">✨</div>
            <div class="numero">{{ $totalServicios }}</div>
            <div class="label">Servicios</div>
        </a>
    </div>

    {{-- ═══════════ 📊 INDICADORES DEL NEGOCIO ═══════════ --}}
    @php
        $k = $resumen['kpis'];
        $flecha = function ($v) {
            if ($v === null) return '<span>Sin datos del mes anterior</span>';
            $clase = $v >= 0 ? 'sube' : 'baja';
            $signo = $v >= 0 ? '▲' : '▼';
            return "<span class=\"{$clase}\">{$signo} " . abs($v) . "%</span> vs. mes anterior";
        };
    @endphp

    <h2 class="section-title">✦ Indicadores del negocio</h2>
    <div class="kpis">
        <div class="kpi">
            <div class="titulo">📅 Citas este mes</div>
            <div class="valor">{{ $k['citasMes'] }}</div>
            <div class="detalle">{!! $flecha($k['variacionCitas']) !!}</div>
        </div>
        <div class="kpi verde">
            <div class="titulo">💰 Ingresos del mes</div>
            <div class="valor">${{ number_format($k['ingresosMes'], 0, ',', '.') }}</div>
            <div class="detalle">{!! $flecha($k['variacionIngresos']) !!}</div>
        </div>
        <div class="kpi verde">
            <div class="titulo">✅ Tasa de asistencia</div>
            <div class="valor">{{ $k['tasaAsistencia'] }}%</div>
            <div class="detalle">Citas pasadas que se completaron</div>
        </div>
        <div class="kpi rojo">
            <div class="titulo">❌ Tasa de cancelación</div>
            <div class="valor">{{ $k['tasaCancelacion'] }}%</div>
            <div class="detalle">Del total de citas registradas</div>
        </div>
        <div class="kpi">
            <div class="titulo">🧾 Ticket promedio</div>
            <div class="valor">${{ number_format($k['ticketPromedio'], 0, ',', '.') }}</div>
            <div class="detalle">Por cita completada este mes</div>
        </div>
        <div class="kpi azul">
            <div class="titulo">🔁 Clientas recurrentes</div>
            <div class="valor">{{ $k['clientasRecurrentes'] }}%</div>
            <div class="detalle">{{ $k['clientasNuevasMes'] }} clientas nuevas este mes</div>
        </div>
        <div class="kpi azul">
            <div class="titulo">📲 Recordatorios enviados</div>
            <div class="valor">{{ $k['recordatoriosMes'] }}</div>
            <div class="detalle">{{ $k['proximas7Dias'] }} citas en los próximos 7 días</div>
        </div>
        <div class="kpi amarillo">
            <div class="titulo">⏳ Lista de espera</div>
            <div class="valor">{{ $k['enListaEspera'] }}</div>
            <div class="detalle">{{ $k['cuposRecuperados'] }} cupos recuperados</div>
        </div>
    </div>

    <div class="graficas">
        <div class="grafica ancha">
            <h3>Citas por mes</h3>
            <p>Últimos 6 meses, según su estado</p>
            <div class="lienzo"><canvas id="gMeses"></canvas></div>
        </div>
        <div class="grafica">
            <h3>Servicios más pedidos</h3>
            <p>Top 5 (sin contar canceladas)</p>
            <div class="lienzo"><canvas id="gServicios"></canvas></div>
        </div>
        <div class="grafica">
            <h3>Citas por especialista</h3>
            <p>Carga de trabajo del equipo</p>
            <div class="lienzo"><canvas id="gEspecialistas"></canvas></div>
        </div>
        <div class="grafica">
            <h3>Horas pico</h3>
            <p>¿A qué hora llegan más clientas?</p>
            <div class="lienzo"><canvas id="gHoras"></canvas></div>
        </div>
        <div class="grafica">
            <h3>Días con más citas</h3>
            <p>Para planear turnos del equipo</p>
            <div class="lienzo"><canvas id="gDias"></canvas></div>
        </div>
    </div>

    {{-- ═══════════ ⏳ LISTA DE ESPERA ═══════════ --}}
    <h2 class="section-title">✦ Clientas en lista de espera</h2>
    <table style="margin-bottom: 32px;">
        <thead>
            <tr>
                <th>Clienta</th>
                <th>Servicio</th>
                <th>Especialista</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($listaEspera as $s)
            <tr>
                <td>{{ $s->user->name }}</td>
                <td>{{ $s->servicio->nombre }}</td>
                <td>{{ $s->especialista?->user->name ?? 'Cualquiera' }}</td>
                <td>{{ \Carbon\Carbon::parse($s->fecha)->format('d/m/Y') }}</td>
                <td>{{ $s->hora_texto }}</td>
                <td>
                    <span class="badge {{ $s->estado }}">
                        {{ $s->estado === 'notificada' ? '📲 Avisada' : '⏳ Esperando' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; color:#aaa; padding: 30px;">
                    No hay clientas esperando cupo 💜
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h2 class="section-title">✦ Accesos rápidos</h2>
    <div class="accesos">
        <a href="{{ route('admin.servicios') }}" class="acceso-card">
            <div class="icono">💅</div>
            <h3>Gestionar Servicios</h3>
            <p>Agregar, editar o eliminar servicios</p>
        </a>
        <a href="{{ route('admin.especialistas') }}" class="acceso-card">
            <div class="icono">👩</div>
            <h3>Gestionar Especialistas</h3>
            <p>Administrar el equipo de trabajo</p>
        </a>
        <a href="{{ route('admin.citas') }}" class="acceso-card">
            <div class="icono">📅</div>
            <h3>Gestionar Citas</h3>
            <p>Ver, filtrar y administrar citas</p>
        </a>
        <a href="{{ route('admin.galeria') }}" class="acceso-card">
            <div class="icono">🖼️</div>
            <h3>Galería</h3>
            <p>Subir y gestionar fotos de trabajos</p>
        </a>
    </div>

    <h2 class="section-title">✦ Citas recientes</h2>
    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Servicio</th>
                <th>Especialista</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($citasRecientes as $cita)
            <tr>
                <td>{{ $cita->user->name }}</td>
                <td>{{ $cita->servicio->nombre }}</td>
                <td>{{ $cita->especialista->user->name }}</td>
                <td>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td>
                <td><span class="badge {{ $cita->estado }}">{{ ucfirst($cita->estado) }}</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center; color:#aaa; padding: 30px;">
                    No hay citas registradas aún
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <a href="{{ route('admin.citas') }}" class="ver-todas">✦ Ver todas las citas →</a>
</div>

{{-- 📊 Gráficas con Chart.js (se instala con npm y se compila con Vite: funciona sin internet) --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const datos = @json($resumen);
    const morado = '#8b7bc4', lila = '#c9c2ee', verde = '#48bb78', amarillo = '#ecc94b', rojo = '#f56565';
    const paleta = ['#6b5ca5', '#8b7bc4', '#a78bfa', '#c4b5fd', '#ddd6fe', '#f0abfc'];

    if (window.Chart) {
        Chart.defaults.font.family = 'Georgia, serif';
        Chart.defaults.color = '#888';
        Chart.defaults.plugins.legend.labels.boxWidth = 12;

        const entero = { beginAtZero: true, ticks: { precision: 0 } };
        const sinVacios = (id, valores) => {
            if (valores.reduce((a, b) => a + b, 0) === 0) {
                document.getElementById(id).parentElement.innerHTML = '<div class="sin-datos">Todavía no hay datos suficientes</div>';
                return false;
            }
            return true;
        };

        new Chart(document.getElementById('gMeses'), {
            type: 'bar',
            data: {
                labels: datos.citasPorMes.labels,
                datasets: [
                    { label: 'Completadas', data: datos.citasPorMes.completadas, backgroundColor: verde, borderRadius: 6 },
                    { label: 'Pendientes',  data: datos.citasPorMes.pendientes,  backgroundColor: amarillo, borderRadius: 6 },
                    { label: 'Canceladas',  data: datos.citasPorMes.canceladas,  backgroundColor: rojo, borderRadius: 6 },
                ],
            },
            options: { maintainAspectRatio: false, scales: { x: { stacked: true }, y: { ...entero, stacked: true } } },
        });

        if (sinVacios('gServicios', datos.serviciosTop.valores)) {
            new Chart(document.getElementById('gServicios'), {
                type: 'doughnut',
                data: { labels: datos.serviciosTop.labels, datasets: [{ data: datos.serviciosTop.valores, backgroundColor: paleta }] },
                options: { maintainAspectRatio: false, plugins: { legend: { position: 'right' } } },
            });
        }

        if (sinVacios('gEspecialistas', datos.porEspecialista.valores)) {
            new Chart(document.getElementById('gEspecialistas'), {
                type: 'bar',
                data: { labels: datos.porEspecialista.labels, datasets: [{ label: 'Citas', data: datos.porEspecialista.valores, backgroundColor: morado, borderRadius: 8 }] },
                options: { maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: entero } },
            });
        }

        if (sinVacios('gHoras', datos.horasPico.valores)) {
            new Chart(document.getElementById('gHoras'), {
                type: 'line',
                data: { labels: datos.horasPico.labels, datasets: [{ label: 'Citas', data: datos.horasPico.valores, borderColor: morado, backgroundColor: 'rgba(201,194,238,.35)', fill: true, tension: .35, pointRadius: 3 }] },
                options: { maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: entero } },
            });
        }

        if (sinVacios('gDias', datos.diasSemana.valores)) {
            new Chart(document.getElementById('gDias'), {
                type: 'bar',
                data: { labels: datos.diasSemana.labels, datasets: [{ label: 'Citas', data: datos.diasSemana.valores, backgroundColor: lila, hoverBackgroundColor: morado, borderRadius: 8 }] },
                options: { maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: entero } },
            });
        }
    }
});
</script>
</body>
</html>
