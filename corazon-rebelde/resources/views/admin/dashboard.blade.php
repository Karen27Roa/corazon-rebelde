<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Corazón Rebelde</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
        <div class="stat-card">
            <div class="icono">📅</div>
            <div class="numero">{{ $totalCitas }}</div>
            <div class="label">Total Citas</div>
        </div>
        <div class="stat-card">
            <div class="icono">👥</div>
            <div class="numero">{{ $totalClientes }}</div>
            <div class="label">Clientes</div>
        </div>
        <div class="stat-card">
            <div class="icono">💅</div>
            <div class="numero">{{ $totalEspecialistas }}</div>
            <div class="label">Especialistas</div>
        </div>
        <div class="stat-card">
            <div class="icono">✨</div>
            <div class="numero">{{ $totalServicios }}</div>
            <div class="label">Servicios</div>
        </div>
    </div>

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
</body>
</html>