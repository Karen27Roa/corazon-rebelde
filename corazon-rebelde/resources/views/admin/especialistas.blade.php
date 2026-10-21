<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Especialistas - Corazón Rebelde</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Georgia', serif; background: #f5f3ff; min-height: 100vh; }
        nav {
            background: linear-gradient(135deg, #6b5ca5, #8b7bc4);
            padding: 16px 32px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 4px 15px rgba(107,92,165,0.4);
        }
        nav img { width: 130px; }
        .nav-links { display: flex; align-items: center; gap: 20px; }
        .nav-links a { color: white; text-decoration: none; font-size: 14px; font-weight: bold; }
        .btn-logout { background: white; color: #6b5ca5; border: none; padding: 8px 18px; border-radius: 20px; font-weight: bold; cursor: pointer; }
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .card { background: white; border-radius: 20px; padding: 32px; box-shadow: 0 4px 20px rgba(201,194,238,0.3); margin-bottom: 28px; }
        .card h2 { color: #6b5ca5; font-size: 18px; margin-bottom: 20px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; color: #6b5ca5; font-size: 13px; font-weight: bold; margin-bottom: 6px; }
        input { width: 100%; padding: 12px 16px; border: 2px solid #e8e4f8; border-radius: 12px; font-size: 14px; outline: none; background: #faf9ff; transition: all 0.3s; }
        input:focus { border-color: #c9c2ee; box-shadow: 0 0 0 4px rgba(201,194,238,0.2); }
        .btn { background: linear-gradient(135deg, #c9c2ee, #a89fd4); color: white; border: none; padding: 12px 28px; border-radius: 25px; font-size: 15px; font-weight: bold; cursor: pointer; transition: all 0.3s; }
        .btn:hover { transform: translateY(-2px); }
        .especialista-card { background: #faf9ff; border-radius: 16px; padding: 20px 24px; margin-bottom: 14px; display: flex; align-items: center; justify-content: space-between; border: 2px solid #e8e4f8; }
        .esp-info h3 { color: #6b5ca5; font-size: 16px; }
        .esp-info p { color: #888; font-size: 13px; margin-top: 4px; }
        .avatar { width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #c9c2ee, #a89fd4); display: flex; align-items: center; justify-content: center; color: white; font-size: 20px; margin-right: 16px; overflow: hidden; flex-shrink: 0; }
        .avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
        .esp-left { display: flex; align-items: center; }
        .alert-success { background: #d1f5e0; color: #276749; padding: 14px 20px; border-radius: 12px; margin-bottom: 20px; font-size: 14px; }
        .empty { text-align: center; padding: 30px; color: #aaa; }
        h1 { color: #6b5ca5; font-size: 24px; margin-bottom: 28px; }
        input[type="file"] { padding: 8px; background: white; cursor: pointer; }
        .esp-actions { display: flex; gap: 8px; align-items: center; flex-shrink: 0; }
        .btn-edit { background: linear-gradient(135deg, #c9c2ee, #a89fd4); color: white; border: none; padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: bold; cursor: pointer; transition: all 0.3s; }
        .btn-edit:hover { transform: translateY(-1px); opacity: 0.9; }
        .btn-delete { background: linear-gradient(135deg, #f87171, #ef4444); color: white; border: none; padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: bold; cursor: pointer; transition: all 0.3s; }
        .btn-delete:hover { transform: translateY(-1px); opacity: 0.9; }
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 999; justify-content: center; align-items: center; }
        .modal-overlay.active { display: flex; }
        .modal-box { background: white; padding: 2rem; border-radius: 20px; width: 520px; max-width: 95%; box-shadow: 0 20px 60px rgba(107,92,165,0.25); animation: fadeIn 0.2s ease; }
        @keyframes fadeIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .modal-box h3 { color: #6b5ca5; font-size: 18px; margin-bottom: 1.2rem; }
        .modal-box .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .modal-box .form-group { margin-bottom: 0; }
        .modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 1.4rem; }
        .btn-cancel { background: #f3f0fb; color: #6b5ca5; border: 2px solid #e8e4f8; padding: 9px 20px; border-radius: 20px; font-size: 14px; font-weight: bold; cursor: pointer; transition: all 0.2s; }
        .btn-cancel:hover { background: #e8e4f8; }
        .btn-save { background: linear-gradient(135deg, #c9c2ee, #a89fd4); color: white; border: none; padding: 9px 22px; border-radius: 20px; font-size: 14px; font-weight: bold; cursor: pointer; transition: all 0.3s; }
        .btn-save:hover { transform: translateY(-1px); }
        small { color: #aaa; font-weight: normal; }
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

    <h1>👩 Gestionar Especialistas</h1>

    <div class="card">
        <h2>✦ Agregar nueva especialista</h2>
        <form method="POST" action="{{ route('admin.especialistas.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre completo</label>
                    <input type="text" name="name" placeholder="Nombre de la especialista" required>
                </div>
                <div class="form-group">
                    <label>Correo electrónico</label>
                    <input type="email" name="email" placeholder="correo@email.com" required>
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" placeholder="Mínimo 8 caracteres" required>
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" placeholder="Número de teléfono">
                </div>
                <div class="form-group">
                    <label>Foto de perfil</label>
                    <input type="file" name="foto" accept="image/*">
                </div>
            </div>
            <button type="submit" class="btn">✦ Agregar especialista</button>
        </form>
    </div>

    <div class="card">
        <h2>✦ Especialistas registradas</h2>
        @if($especialistas->isEmpty())
            <div class="empty">No hay especialistas registradas aún</div>
        @else
            @foreach($especialistas as $especialista)
            <div class="especialista-card">
                <div class="esp-left">
                    <div class="avatar">
                        @if($especialista->foto)
                            <img src="{{ asset('storage/' . $especialista->foto) }}" alt="{{ $especialista->user->name }}">
                        @else
                            💅
                        @endif
                    </div>
                    <div class="esp-info">
                        <h3>{{ $especialista->user->name }}</h3>
                        <p>📧 {{ $especialista->user->email }}</p>
                        @if($especialista->telefono)
                            <p>📱 {{ $especialista->telefono }}</p>
                        @endif
                    </div>
                </div>
                <div class="esp-actions">
                    <button class="btn-edit" onclick="abrirModal(
                        {{ $especialista->id }},
                        '{{ addslashes($especialista->user->name) }}',
                        '{{ $especialista->user->email }}',
                        '{{ $especialista->telefono }}'
                    )">✏️ Editar</button>
                    <form action="{{ route('admin.especialistas.destroy', $especialista) }}"
                          method="POST"
                          onsubmit="return confirm('¿Segura que deseas eliminar a {{ $especialista->user->name }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">🗑️ Eliminar</button>
                    </form>
                </div>
            </div>
            @endforeach
        @endif
    </div>

</div>

<div class="modal-overlay" id="modalEditar">
    <div class="modal-box">
        <h3>✏️ Editar Especialista</h3>
        <form id="formEditar" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre completo</label>
                    <input type="text" name="name" id="edit_name" required>
                </div>
                <div class="form-group">
                    <label>Correo electrónico</label>
                    <input type="email" name="email" id="edit_email" required>
                </div>
                <div class="form-group">
                    <label>Nueva contraseña <small>(opcional)</small></label>
                    <input type="password" name="password" placeholder="Mínimo 8 caracteres" minlength="8">
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" id="edit_telefono">
                </div>
            </div>
            <div class="form-group" style="margin-top:14px;">
                <label>Foto de perfil <small>(opcional)</small></label>
                <input type="file" name="foto" accept="image/*">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn-save">💾 Guardar cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModal(id, nombre, email, telefono) {
        document.getElementById('edit_name').value     = nombre;
        document.getElementById('edit_email').value    = email;
        document.getElementById('edit_telefono').value = telefono || '';
        document.getElementById('formEditar').action   = '/admin/especialistas/' + id;
        document.getElementById('modalEditar').classList.add('active');
    }
    function cerrarModal() {
        document.getElementById('modalEditar').classList.remove('active');
    }
    document.getElementById('modalEditar').addEventListener('click', function(e) {
        if (e.target === this) cerrarModal();
    });
</script>
</body>
</html>