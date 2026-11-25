<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería - Corazón Rebelde</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Georgia', serif; background: #f5f3ff; min-height: 100vh; }
        nav {
            background: linear-gradient(135deg, #6b5ca5, #8b7bc4);
            padding: 16px 32px;
            display: flex; align-items: center; justify-content: space-between;
        }
        nav img { width: 130px; }
        .nav-links { display: flex; align-items: center; gap: 20px; }
        .nav-links a { color: white; text-decoration: none; font-size: 14px; font-weight: bold; }
        .btn-logout {
            background: white; color: #6b5ca5; border: none;
            padding: 8px 18px; border-radius: 20px; font-weight: bold; cursor: pointer;
        }
        .container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
        h1 { color: #6b5ca5; font-size: 24px; margin-bottom: 28px; }
        .card {
            background: white; border-radius: 20px; padding: 32px;
            box-shadow: 0 4px 20px rgba(201,194,238,0.3); margin-bottom: 28px;
        }
        .card h2 { color: #6b5ca5; font-size: 18px; margin-bottom: 20px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; color: #6b5ca5; font-size: 13px; font-weight: bold; margin-bottom: 6px; }
        input, select {
            width: 100%; padding: 12px 16px;
            border: 2px solid #e8e4f8; border-radius: 12px;
            font-size: 14px; outline: none; background: #faf9ff; transition: all 0.3s;
        }
        input:focus, select:focus { border-color: #c9c2ee; }
        .btn {
            background: linear-gradient(135deg, #c9c2ee, #a89fd4);
            color: white; border: none; padding: 12px 28px;
            border-radius: 25px; font-size: 15px; font-weight: bold; cursor: pointer; transition: all 0.3s;
        }
        .btn:hover { transform: translateY(-2px); }
        .galeria-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .galeria-item {
            border-radius: 16px; overflow: hidden;
            box-shadow: 0 4px 15px rgba(201,194,238,0.3); background: white;
        }
        .galeria-item img { width: 100%; height: 200px; object-fit: cover; }
        .galeria-item-info { padding: 14px 16px; }
        .galeria-item-info p { color: #6b5ca5; font-size: 13px; font-weight: bold; }
        .galeria-item-info span { color: #aaa; font-size: 12px; }
        .galeria-actions { display: flex; gap: 8px; padding: 0 16px 14px; }
        .btn-editar {
            flex: 1; background: #e8e4f8; color: #6b5ca5; border: none;
            padding: 8px; border-radius: 10px; font-size: 12px; font-weight: bold; cursor: pointer;
        }
        .btn-eliminar {
            flex: 1; background: #ffe0e0; color: #c0392b; border: none;
            padding: 8px; border-radius: 10px; font-size: 12px; font-weight: bold; cursor: pointer;
        }
        .alert-success {
            background: #d1f5e0; color: #276749;
            padding: 14px 20px; border-radius: 12px; margin-bottom: 20px; font-size: 14px;
        }
        .empty { text-align: center; padding: 30px; color: #aaa; }
        .modal-overlay {
            display: none; position: fixed; top: 0; left: 0;
            width: 100%; height: 100%; background: rgba(0,0,0,0.5);
            z-index: 1000; align-items: center; justify-content: center;
        }
        .modal-overlay.active { display: flex; }
        .modal {
            background: white; border-radius: 20px; padding: 32px;
            width: 100%; max-width: 460px; box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        .modal h3 { color: #6b5ca5; font-size: 18px; margin-bottom: 20px; }
        .modal-actions { display: flex; gap: 12px; margin-top: 20px; }
        .btn-cancelar {
            flex: 1; background: #f0eeff; color: #6b5ca5; border: none;
            padding: 12px; border-radius: 25px; font-weight: bold; cursor: pointer;
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
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <h1>🖼️ Galería de trabajos</h1>

    <div class="card">
        <h2>✦ Subir nueva imagen</h2>
        <form method="POST" action="{{ route('admin.galeria.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Servicio relacionado</label>
                <select name="servicio_id" required>
                    <option value="">Selecciona un servicio</option>
                    @foreach($servicios as $servicio)
                        <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Imagen</label>
                <input type="file" name="imagen" accept="image/*" required>
            </div>
            <div class="form-group">
                <label>Descripción (opcional)</label>
                <input type="text" name="descripcion" placeholder="Descripción del trabajo">
            </div>
            <button type="submit" class="btn">✦ Subir imagen</button>
        </form>
    </div>

    <div class="card">
        <h2>✦ Imágenes subidas</h2>
        @if($imagenes->isEmpty())
            <div class="empty">No hay imágenes en la galería aún</div>
        @else
        <div class="galeria-grid">
            @foreach($imagenes as $imagen)
            <div class="galeria-item">
                <img src="{{ asset('storage/' . $imagen->imagen) }}" alt="{{ $imagen->descripcion }}">
                <div class="galeria-item-info">
                    <p>{{ $imagen->servicio->nombre }}</p>
                    <span>{{ $imagen->descripcion ?? 'Sin descripción' }}</span>
                </div>
                <div class="galeria-actions">
                    <button class="btn-editar" onclick="abrirModal({{ $imagen->id }}, '{{ addslashes($imagen->descripcion) }}', {{ $imagen->servicio_id }})">
                        ✏️ Editar
                    </button>
                    <form method="POST" action="{{ route('admin.galeria.destroy', $imagen->id) }}" style="flex:1"
                          onsubmit="return confirm('¿Segura que quieres eliminar esta imagen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-eliminar" style="width:100%">🗑️ Eliminar</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<div class="modal-overlay" id="modalEditar">
    <div class="modal">
        <h3>✏️ Editar imagen</h3>
        <form method="POST" id="formEditar">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Servicio relacionado</label>
                <select name="servicio_id" id="editServicio" required>
                    @foreach($servicios as $servicio)
                        <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <input type="text" name="descripcion" id="editDescripcion" placeholder="Descripción del trabajo">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn" style="flex:1">✦ Guardar cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirModal(id, descripcion, servicioId) {
    document.getElementById('modalEditar').classList.add('active');
    document.getElementById('formEditar').action = '/admin/galeria/' + id;
    document.getElementById('editDescripcion').value = descripcion || '';
    document.getElementById('editServicio').value = servicioId;
}
function cerrarModal() {
    document.getElementById('modalEditar').classList.remove('active');
}
</script>
</body>
</html>