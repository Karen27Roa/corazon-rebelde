<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita - Corazón Rebelde</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #d4cff7, #c9c2ee, #e6d6fa, #d4cff7);
            background-size: 300% 300%;
            animation: fondo 14s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            overflow-x: hidden;
        }
        @keyframes fondo { 0%,100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }

        /* ---------- Uñas y corazones flotando ---------- */
        .fondo { position: fixed; inset: 0; pointer-events: none; overflow: hidden; z-index: 0; }
        .flota { position: absolute; bottom: -70px; opacity: 0; animation: subir linear infinite; }
        .flota > span { display: inline-block; animation: vaiven ease-in-out infinite; filter: drop-shadow(0 4px 6px rgba(107,92,165,.25)); }
        .combo { position: relative; display: inline-block; line-height: 1; }
        .combo .una { position: absolute; top: -.55em; right: -.45em; font-size: .62em; transform: rotate(18deg); }
        @keyframes subir {
            0%   { transform: translateY(0); opacity: 0; }
            10%  { opacity: .8; }
            90%  { opacity: .5; }
            100% { transform: translateY(-118vh); opacity: 0; }
        }
        @keyframes vaiven {
            0%, 100% { transform: translateX(-16px) rotate(-16deg); }
            50%      { transform: translateX(16px) rotate(16deg); }
        }

        /* ---------- Escena: osito + tarjeta ---------- */
        .escena {
            position: relative; z-index: 2;
            width: 100%; max-width: 500px;
        }

        .osito {
            position: absolute; left: -265px; top: 36px; width: 252px;
            z-index: 3; pointer-events: none;
        }
        .osito img {
            width: 100%; display: block;
            transform-origin: 50% 100%;
            animation: balanceo 3.6s ease-in-out infinite;
            filter: drop-shadow(0 14px 14px rgba(91,58,143,.35));
        }
        @keyframes balanceo {
            0%, 100% { transform: rotate(-1.5deg) translateY(0); }
            50%      { transform: rotate(2.5deg) translateY(-9px); }
        }

        .burbuja {
            position: absolute; top: -64px; left: 18px;
            background: white; color: #6b3fb0; font-weight: bold; font-size: 14px;
            padding: 11px 18px; border-radius: 20px; white-space: nowrap;
            box-shadow: 0 8px 22px rgba(107,92,165,.35);
            animation: rebote 2.6s ease-in-out infinite;
        }
        .burbuja::after {
            content: ''; position: absolute; bottom: -9px; left: 38px;
            border: 9px solid transparent; border-top-color: white; border-bottom: 0;
        }
        @keyframes rebote { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-7px); } }

        /* ---------- Tarjeta ---------- */
        .card {
            position: relative;
            background: rgba(255,255,255,.96);
            border-radius: 26px;
            padding: 40px 40px 36px;
            width: 100%;
            box-shadow: 0 24px 64px rgba(120,100,200,.45);
            animation: entrar .9s ease both;
        }
        .card::before {
            content: ''; position: absolute; inset: -3px; border-radius: 29px; z-index: -1;
            background: linear-gradient(120deg, #c9a8f7, #ffd6f2, #ffe066, #c9a8f7);
            background-size: 300% 300%; animation: borde 6s linear infinite; opacity: .85;
        }
        @keyframes borde { to { background-position: 300% 0; } }
        @keyframes entrar { from { opacity: 0; transform: translateY(30px) scale(.97); } to { opacity: 1; transform: none; } }

        .logo-wrap { display: flex; justify-content: center; margin-bottom: 22px; }

        h2 {
            position: relative;
            text-align: center; font-size: 23px; font-weight: normal; margin-bottom: 28px;
            background: linear-gradient(100deg, #5b3a8f 0%, #a76bf0 30%, #e07bd0 50%, #a76bf0 70%, #5b3a8f 100%);
            background-size: 250% 100%;
            -webkit-background-clip: text; background-clip: text;
            color: transparent; -webkit-text-fill-color: transparent;
            animation: brilloTexto 4s linear infinite;
        }
        @keyframes brilloTexto { to { background-position: -250% 0; } }

        /* Rastro animado que sale de la mano del osito hacia el título */
        .rastro {
            position: absolute; left: -48px; top: 50%; width: 150px; height: 5px;
            transform: translateY(-50%);
            background: repeating-linear-gradient(90deg, #a76bf0 0 9px, transparent 9px 20px);
            background-size: 40px 5px;
            animation: marcha 1s linear infinite;
            -webkit-mask-image: linear-gradient(90deg, transparent, #000 20%, #000 80%, transparent);
            mask-image: linear-gradient(90deg, transparent, #000 20%, #000 80%, transparent);
        }
        @keyframes marcha { to { background-position: 40px 0; } }

        .form-group { margin-bottom: 18px; animation: entrar .7s ease both; }
        .form-group:nth-of-type(1) { animation-delay: .25s; }
        .form-group:nth-of-type(2) { animation-delay: .35s; }
        .form-group:nth-of-type(3) { animation-delay: .45s; }
        .form-group:nth-of-type(4) { animation-delay: .55s; }

        label { display: block; color: #6b5ca5; font-size: 13px; font-weight: bold; margin-bottom: 6px; }

        select, input, textarea {
            width: 100%; padding: 12px 16px;
            border: 2px solid #e8e4f8; border-radius: 14px;
            font-size: 14px; outline: none; background: #faf9ff; color: #2c2c2c;
            transition: all .3s;
        }
        select:hover, input:hover { border-color: #d8cff5; transform: translateY(-1px); }
        select:focus, input:focus, textarea:focus {
            border-color: #b085ee; background: white;
            box-shadow: 0 0 0 4px rgba(176,133,238,.25);
        }
        textarea { resize: vertical; height: 80px; }

        .btn {
            position: relative; overflow: hidden;
            width: 100%; padding: 15px;
            background: linear-gradient(135deg, #b085ee, #8b5fd0);
            color: white; border: none; border-radius: 28px;
            font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 10px;
            transition: transform .25s, box-shadow .25s;
            box-shadow: 0 8px 24px rgba(139,95,208,.45);
            font-family: inherit;
        }
        .btn::before {
            content: ''; position: absolute; top: 0; left: -80%; width: 50%; height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255,255,255,.7), transparent);
            transform: skewX(-20deg); animation: brillo 3s ease-in-out infinite;
        }
        @keyframes brillo { 0% { left: -80%; } 60%, 100% { left: 140%; } }
        .btn:hover { transform: translateY(-3px) scale(1.02); box-shadow: 0 14px 32px rgba(139,95,208,.55); }
        .btn:disabled { opacity: .5; cursor: not-allowed; transform: none; }

        .back { display: block; text-align: center; margin-top: 16px; color: #6b5ca5; font-size: 14px; text-decoration: none; }
        .back:hover { text-decoration: underline; }

        .error { background: #ffe0e0; color: #c0392b; padding: 10px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; }

        .hora-ocupada-msg {
            display: none; background: #ffe0e0; color: #c0392b;
            padding: 10px 14px; border-radius: 8px; margin-top: 8px; font-size: 13px;
        }
        select option:disabled { color: #bbb; background: #f5f5f5; }

        /* ---------- Pantallas medianas y celular: osito arriba ---------- */
        @media (max-width: 1080px) {
            body { align-items: flex-start; padding-top: 24px; }
            .escena { display: flex; flex-direction: column; align-items: center; }
            .osito { position: relative; left: auto; top: auto; width: 170px; margin: 56px 0 -6px; }
            .burbuja { top: -50px; left: 50%; transform: translateX(-50%); font-size: 13px; }
            @keyframes rebote { 0%,100% { transform: translateX(-50%) translateY(0); } 50% { transform: translateX(-50%) translateY(-6px); } }
            .rastro { display: none; }
            .card { padding: 32px 24px 28px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
        }
    </style>
</head>
<body>

<div class="fondo" id="fondo"></div>

<div class="escena">

    {{-- Osito que señala el formulario --}}
    <div class="osito">
        <div class="burbuja">¡Aquí! Agenda tu cita 💜</div>
        <img src="{{ asset('mascota/osito.png') }}" alt="Osito de Corazón Rebelde señalando el formulario">
    </div>

    <div class="card">
        <div class="logo-wrap">
            <x-logo size="lg" />
        </div>

        <h2><span class="rastro"></span>✦ Agenda tu cita ✦</h2>

        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('citas.store') }}">
            @csrf

            <div class="form-group">
                <label>💅 Servicio</label>
                <select name="servicio_id" required>
                    <option value="">Selecciona un servicio</option>
                    @foreach($servicios as $servicio)
                        <option value="{{ $servicio->id }}">
                            {{ $servicio->nombre }} — ${{ number_format($servicio->precio, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>👩 Especialista</label>
                <select name="especialista_id" id="especialista_id" required>
                    <option value="">Selecciona una especialista</option>
                    @foreach($especialistas as $especialista)
                        <option value="{{ $especialista->id }}">
                            {{ $especialista->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>📅 Fecha</label>
                <input type="date" name="fecha" id="fecha" min="{{ date('Y-m-d') }}" required>
            </div>

            <div class="form-group">
                <label>🕐 Hora disponible</label>
                <small style="color:#9b8ec4; font-size:12px; display:block; margin-bottom:8px;">
                    🕙 Horario regular: 10:00 AM – 7:00 PM &nbsp;|&nbsp;
                    ⚡ Antes de las 10 AM o después de las 7 PM tiene recargo adicional
                </small>

                <select name="hora" id="hora" required>
                    <option value="">Primero selecciona una fecha y especialista</option>
                </select>

                <div class="hora-ocupada-msg" id="hora-error">
                    ⚠️ Esta hora ya está ocupada, por favor elige otra.
                </div>
            </div>

            <button type="submit" class="btn" id="btn-submit">✦ Agendar cita</button>
            <a href="/" class="back">← Volver al inicio</a>
        </form>
    </div>
</div>

<script>
    /* ---------- Uñas y corazones flotando ---------- */
    (function () {
        const fondo = document.getElementById('fondo');
        const corazones = ['💜', '💗', '💖', '🤍'];
        for (let i = 0; i < 22; i++) {
            const flota = document.createElement('div');
            flota.className = 'flota';
            const interior = document.createElement('span');
            const t = Math.random();
            const c = corazones[Math.floor(Math.random() * corazones.length)];

            if (t < 0.45)      interior.textContent = '💅';
            else if (t < 0.7)  interior.innerHTML = `<span class="combo">${c}<span class="una">💅</span></span>`;
            else               interior.textContent = c;

            flota.style.left = Math.random() * 100 + '%';
            flota.style.fontSize = (16 + Math.random() * 26) + 'px';
            flota.style.animationDuration = (11 + Math.random() * 14) + 's';
            flota.style.animationDelay = (-Math.random() * 25) + 's';
            interior.style.animationDuration = (3 + Math.random() * 3) + 's';
            interior.style.animationDelay = (-Math.random() * 4) + 's';
            flota.appendChild(interior);
            fondo.appendChild(flota);
        }
    })();

    /* ---------- Horas disponibles ---------- */
    const especialistaSelect = document.getElementById('especialista_id');
    const fechaInput = document.getElementById('fecha');
    const selectHora = document.getElementById('hora');
    const horaError = document.getElementById('hora-error');
    const btnSubmit = document.getElementById('btn-submit');

    const todasLasHoras = [
        '07:00', '07:30', '08:00', '08:30', '09:00', '09:30',
        '10:00', '10:30', '11:00', '11:30', '12:00', '12:30',
        '13:00', '13:30', '14:00', '14:30', '15:00', '15:30',
        '16:00', '16:30', '17:00', '17:30', '18:00', '18:30',
        '19:00', '19:30', '20:00', '20:30', '21:00', '21:30'
    ];

    function formatearHora(hora24) {
        const [h, m] = hora24.split(':');
        let hora = parseInt(h, 10);
        const minutos = m;
        const ampm = hora >= 12 ? 'PM' : 'AM';

        hora = hora % 12;
        if (hora === 0) hora = 12;

        let texto = `${hora}:${minutos} ${ampm}`;

        if (hora24 < '10:00' || hora24 > '19:00') {
            texto += ' ⚡ Recargo';
        }

        return texto;
    }

    function resetHoras(mensaje = 'Primero selecciona una fecha y especialista') {
        selectHora.innerHTML = `<option value="">${mensaje}</option>`;
        horaError.style.display = 'none';
        btnSubmit.disabled = false;
    }

    function validarHoraSeleccionada() {
        const opcionSeleccionada = selectHora.options[selectHora.selectedIndex];

        if (opcionSeleccionada && opcionSeleccionada.disabled) {
            horaError.style.display = 'block';
            btnSubmit.disabled = true;
        } else {
            horaError.style.display = 'none';
            btnSubmit.disabled = false;
        }
    }

    function cargarHoras() {
        const fecha = fechaInput.value;
        const especialistaId = especialistaSelect.value;

        if (!fecha || !especialistaId) {
            resetHoras('Primero selecciona una fecha y especialista');
            return;
        }

        selectHora.innerHTML = '<option value="">Cargando horas...</option>';

        const url = `/api/horas-ocupadas?fecha=${encodeURIComponent(fecha)}&especialista_id=${encodeURIComponent(especialistaId)}`;

        fetch(url)
            .then(res => res.json())
            .then(horasOcupadas => {
                selectHora.innerHTML = '<option value="">Selecciona una hora</option>';

                todasLasHoras.forEach(hora => {
                    const option = document.createElement('option');
                    option.value = hora;

                    if (horasOcupadas.includes(hora)) {
                        option.textContent = formatearHora(hora) + ' — Ocupada';
                        option.disabled = true;
                    } else {
                        option.textContent = formatearHora(hora);
                    }

                    selectHora.appendChild(option);
                });

                validarHoraSeleccionada();
            })
            .catch(() => {
                resetHoras('Error cargando horas');
            });
    }

    especialistaSelect.addEventListener('change', cargarHoras);
    fechaInput.addEventListener('change', cargarHoras);
    selectHora.addEventListener('change', validarHoraSeleccionada);
</script>
</body>
</html>