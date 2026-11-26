<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de espera - Corazón Rebelde</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Georgia', serif; min-height: 100vh;
            background:
                radial-gradient(circle at 8% 0%, #e9defc 0%, transparent 42%),
                radial-gradient(circle at 92% 25%, #ffe3f3 0%, transparent 38%),
                linear-gradient(160deg, #f5f3ff 0%, #ece6fb 100%);
            background-attachment: fixed;
        }
        nav {
            background: linear-gradient(135deg, #c9c2ee, #b4aae0);
            padding: 10px 32px; display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 10px; box-shadow: 0 6px 24px rgba(107,92,165,.3);
        }
        .nav-links { display: flex; align-items: center; gap: 22px; flex-wrap: wrap; }
        .nav-links a { color: white; text-decoration: none; font-size: 14px; font-weight: bold; }
        .nav-links a:hover { text-decoration: underline; }
        .btn-logout {
            background: white; color: #6b5ca5; border: none; padding: 10px 22px; border-radius: 25px;
            font-weight: bold; cursor: pointer; font-size: 14px; font-family: inherit;
        }
        .container { max-width: 1100px; margin: 0 auto 60px; padding: 0 20px; }

        .banner {
            margin-top: 40px; padding: 34px 40px; color: white; border-radius: 28px;
            background: linear-gradient(135deg, #3f2470 0%, #6a3fb5 55%, #a76bf0 100%);
            box-shadow: 0 20px 48px rgba(91,58,143,.35);
        }
        .banner h1 { font-family: 'Dancing Script', 'Brush Script MT', cursive; font-size: clamp(2.2rem, 4vw, 3rem); }
        .banner h1 span { color: #ffd21f; }
        .banner p { margin-top: 10px; font-size: 15px; line-height: 1.6; max-width: 640px; opacity: .95; }
        .pasos { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 18px; }
        .paso { background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.4); padding: 8px 14px; border-radius: 30px; font-size: 13px; font-weight: bold; }

        .alert { padding: 14px 20px; border-radius: 14px; margin-top: 24px; font-size: 14px; font-weight: bold; }
        .alert-success { background: #d1f5e0; color: #276749; }
        .alert-error   { background: #ffe0e0; color: #c0392b; }

        .cols { display: grid; grid-template-columns: 400px 1fr; gap: 28px; margin-top: 30px; align-items: start; }
        .card { background: white; border-radius: 24px; padding: 28px; box-shadow: 0 8px 28px rgba(201,194,238,.45); border: 2px solid #f1ebfc; }
        h2 { color: #6b5ca5; font-size: 21px; font-weight: normal; margin-bottom: 18px; }

        .form-group { margin-bottom: 16px; }
        label { display: block; color: #6b5ca5; font-size: 14px; font-weight: bold; margin-bottom: 6px; }
        select, input {
            width: 100%; padding: 12px 14px; border: 2px solid #e6dcf9; border-radius: 14px;
            font-size: 14px; font-family: inherit; background: #fcfbff; color: #333;
        }
        select:focus, input:focus { outline: none; border-color: #a78bfa; }
        small { display: block; color: #9b8ec4; font-size: 12px; margin-top: 5px; }
        .btn {
            width: 100%; padding: 14px; border: none; border-radius: 30px; cursor: pointer;
            background: linear-gradient(135deg, #8b5fd0, #c08bf0); color: white;
            font-size: 15px; font-weight: bold; font-family: inherit;
            box-shadow: 0 8px 24px rgba(139,95,208,.4); transition: transform .2s;
        }
        .btn:hover { transform: translateY(-2px); }

        .solicitud {
            display: flex; align-items: center; justify-content: space-between; gap: 16px;
            padding: 16px 18px; border-radius: 18px; border: 2px solid #f1ebfc; margin-bottom: 12px;
        }
        .solicitud h3 { color: #4a3a80; font-size: 16px; }
        .solicitud p { color: #777; font-size: 13px; margin-top: 4px; }
        .estado { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; white-space: nowrap; }
        .esperando  { background: #fff3cd; color: #856404; }
        .notificada { background: #e0ecff; color: #1d4ed8; }
        .agendada   { background: #d1f5e0; color: #276749; }
        .cancelada, .vencida { background: #f1f1f1; color: #888; }
        .acciones { display: flex; flex-direction: column; align-items: flex-end; gap: 8px; }
        .btn-salir {
            background: #ffe0e0; color: #c0392b; border: none; padding: 6px 14px; border-radius: 20px;
            font-size: 12px; font-weight: bold; cursor: pointer; font-family: inherit;
        }
        .vacio { text-align: center; color: #999; padding: 30px 10px; font-size: 14px; line-height: 1.6; }

        @media (max-width: 900px) { .cols { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<nav>
    <x-logo />
    <div class="nav-links">
        <a href="{{ route('cliente.dashboard') }}">✦ Inicio</a>
        <a href="{{ route('citas.create') }}">✦ Agendar cita</a>
        <a href="{{ route('citas.mias') }}">✦ Mis citas</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Cerrar sesión</button>
        </form>
    </div>
</nav>

<div class="container">
    <section class="banner">
        <h1>Lista de <span>espera</span> ⏳</h1>
        <p>¿El horario que quieres está lleno? Anótate y, si alguna clienta cancela o reagenda,
           te avisamos al instante por WhatsApp con un enlace para agendar.</p>
        <div class="pasos">
            <span class="paso">1. Elige el día que quieres</span>
            <span class="paso">2. Te avisamos si se libera un cupo</span>
            <span class="paso">3. La primera en agendar se lo queda 💜</span>
        </div>
    </section>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <div class="cols">
        {{-- ===== Formulario ===== --}}
        <div class="card">
            <h2>✦ Anotarme</h2>
            <form method="POST" action="{{ route('lista-espera.store') }}">
                @csrf
                <div class="form-group">
                    <label>💅 Servicio</label>
                    <select name="servicio_id" required>
                        <option value="">Selecciona un servicio</option>
                        @foreach($servicios as $servicio)
                            <option value="{{ $servicio->id }}" @selected(old('servicio_id', request('servicio_id')) == $servicio->id)>
                                {{ $servicio->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>👩 Especialista</label>
                    <select name="especialista_id">
                        <option value="">Cualquier especialista</option>
                        @foreach($especialistas as $especialista)
                            <option value="{{ $especialista->id }}" @selected(old('especialista_id', request('especialista_id')) == $especialista->id)>
                                {{ $especialista->user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>📅 Fecha</label>
                    <input type="date" name="fecha" min="{{ \App\Models\Cita::fechaMinima() }}" value="{{ old('fecha', request('fecha')) }}" required>
                </div>

                <div class="form-group">
                    <label>🕐 Hora</label>
                    <select name="hora">
                        <option value="">Cualquier hora de ese día</option>
                        @foreach($horas as $hora)
                            <option value="{{ $hora }}" @selected(old('hora', request('hora')) === $hora)>
                                {{ \Carbon\Carbon::createFromFormat('H:i', $hora)->format('h:i A') }}
                            </option>
                        @endforeach
                    </select>
                    <small>Entre más flexible seas, más rápido te llega un cupo.</small>
                </div>

                <div class="form-group">
                    <label>📱 Celular (WhatsApp)</label>
                    <input type="tel" name="telefono" value="{{ old('telefono', auth()->user()->telefono) }}"
                           placeholder="3001234567" maxlength="12" inputmode="numeric" required>
                </div>

                <button type="submit" class="btn">✦ Avisarme si se libera</button>
            </form>
        </div>

        {{-- ===== Mis solicitudes ===== --}}
        <div class="card">
            <h2>✦ Mis solicitudes</h2>

            @forelse($solicitudes as $s)
                <div class="solicitud">
                    <div>
                        <h3>{{ $s->servicio->nombre }}</h3>
                        <p>
                            📅 {{ \Carbon\Carbon::parse($s->fecha)->translatedFormat('d/m/Y') }}
                            &nbsp;·&nbsp; 🕐 {{ $s->hora_texto }}
                            &nbsp;·&nbsp; 👩 {{ $s->especialista?->user->name ?? 'Cualquier especialista' }}
                        </p>
                        @if($s->notificada_at)
                            <p>📲 Te avisamos el {{ $s->notificada_at->format('d/m/Y h:i A') }}</p>
                        @endif
                    </div>
                    <div class="acciones">
                        <span class="estado {{ $s->estado }}">
                            @switch($s->estado)
                                @case('esperando') ⏳ Esperando @break
                                @case('notificada') 📲 Te avisamos @break
                                @case('agendada') ✅ Agendaste @break
                                @case('cancelada') Cancelada @break
                                @default Vencida
                            @endswitch
                        </span>
                        @if(in_array($s->estado, ['esperando', 'notificada']))
                            <form method="POST" action="{{ route('lista-espera.destroy', $s) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-salir">Salir de la lista</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="vacio">
                    Todavía no estás en ninguna lista de espera.<br>
                    Si el horario que quieres está lleno, anótate aquí 💜
                </div>
            @endforelse
        </div>
    </div>
</div>
</body>
</html>
