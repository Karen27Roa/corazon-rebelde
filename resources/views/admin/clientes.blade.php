<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Corazón Rebelde</title>
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
        .badge-citas {
            background: #ece8ff; color: #6b5ca5;
            padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;
        }
        .tabla-scroll { overflow-x: auto; }
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
        <h1>Clientes 💜</h1>
        <p>Personas registradas en Corazón Rebelde Nails Spa ({{ $clientes->count() }})</p>
    </div>

    <div class="tabla-scroll">
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Citas agendadas</th>
                    <th>Registro</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->name }}</td>
                    <td>{{ $cliente->email }}</td>
                    <td><span class="badge-citas">{{ $cliente->citas_count }}</span></td>
                    <td>{{ $cliente->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:#aaa; padding: 30px;">
                        Aún no hay clientes registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>