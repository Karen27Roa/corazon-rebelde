<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Corazón Rebelde</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            font-family: 'Georgia', serif;
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            background: #faf7ff;
            overflow-x: hidden;
        }

        /* =====================  PANEL IZQUIERDO  ===================== */
        .hero {
            position: relative; overflow: hidden;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 40px 30px; color: white; text-align: center;
            background: linear-gradient(145deg, #3f2470 0%, #6a3fb5 50%, #a76bf0 100%);
            background-size: 200% 200%; animation: mover 12s ease infinite, desdeIzq .9s ease both;
        }
        @keyframes mover { 0%,100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
        @keyframes desdeIzq { from { opacity: 0; transform: translateX(-40px); } to { opacity: 1; transform: none; } }

        .hero::before, .hero::after {
            content: ''; position: absolute; border-radius: 50%; background: rgba(255,255,255,.08);
            animation: respira 9s ease-in-out infinite;
        }
        .hero::before { width: 420px; height: 420px; top: -150px; left: -140px; }
        .hero::after  { width: 300px; height: 300px; bottom: -110px; right: -90px; animation-delay: 3s; }
        @keyframes respira { 0%,100% { transform: scale(1); } 50% { transform: scale(1.14); } }

        .estrella { position: absolute; color: #ffe680; text-shadow: 0 0 10px #ffd21f; animation: titila 2.4s ease-in-out infinite; z-index: 1; }
        .e1 { top: 9%;  left: 12%; font-size: 22px; }
        .e2 { top: 16%; right: 14%; font-size: 30px; animation-delay: .6s; }
        .e3 { bottom: 24%; left: 8%;  font-size: 18px; animation-delay: 1.1s; }
        .e4 { bottom: 12%; right: 12%; font-size: 24px; animation-delay: 1.6s; }
        @keyframes titila { 0%,100% { opacity: .2; transform: scale(.6) rotate(0); } 50% { opacity: 1; transform: scale(1.3) rotate(45deg); } }

        /* Uñas y corazones flotando */
        .fondo { position: absolute; inset: 0; pointer-events: none; overflow: hidden; z-index: 1; }
        .flota { position: absolute; bottom: -70px; opacity: 0; animation: subir linear infinite; }
        .flota > span { display: inline-block; animation: vaiven ease-in-out infinite; filter: drop-shadow(0 4px 8px rgba(0,0,0,.25)); }
        .combo { position: relative; display: inline-block; line-height: 1; }
        .combo .una { position: absolute; top: -.55em; right: -.45em; font-size: .62em; transform: rotate(18deg); }
        @keyframes subir { 0% { transform: translateY(0); opacity: 0; } 10% { opacity: .85; } 90% { opacity: .5; } 100% { transform: translateY(-110vh); opacity: 0; } }
        @keyframes vaiven { 0%,100% { transform: translateX(-16px) rotate(-16deg); } 50% { transform: translateX(16px) rotate(16deg); } }

        /* Escenario del osito */
        .stage { position: relative; z-index: 2; width: min(400px, 78%); transition: transform .15s ease-out; }
        .glow {
            position: absolute; inset: -14%; z-index: 0; border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,.92) 0%, rgba(255,230,250,.55) 42%, transparent 70%);
            animation: pulso 4s ease-in-out infinite;
        }
        @keyframes pulso { 0%,100% { transform: scale(1); opacity: .9; } 50% { transform: scale(1.08); opacity: 1; } }
        .stage img {
            position: relative; z-index: 1; width: 100%; display: block;
            filter: drop-shadow(0 22px 22px rgba(46,29,77,.45));
            animation: flotarOso 4.2s ease-in-out infinite; transform-origin: 50% 90%;
        }
        @keyframes flotarOso { 0%,100% { transform: translateY(0) rotate(-1.5deg); } 50% { transform: translateY(-14px) rotate(1.8deg); } }

        .chip {
            position: absolute; z-index: 3; background: white; color: #5b3a8f; font-weight: bold; font-size: 13px;
            padding: 9px 15px; border-radius: 30px; white-space: nowrap;
            box-shadow: 0 10px 26px rgba(46,29,77,.35); animation: rebote 3.2s ease-in-out infinite;
        }
        .c1 { top: 4%;  left: -10%; }
        .c2 { top: 40%; right: -16%; animation-delay: .8s; }
        .c3 { bottom: 8%; left: -12%; animation-delay: 1.6s; }
        @keyframes rebote { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-9px); } }

        .mensaje { position: relative; z-index: 2; margin-top: 26px; max-width: 440px; }
        .mensaje h1 { font-family: 'Dancing Script', 'Brush Script MT', cursive; font-size: clamp(2.1rem, 3.6vw, 3.1rem); line-height: 1.1; text-shadow: 0 4px 18px rgba(0,0,0,.3); }
        .mensaje h1 span {
            background: linear-gradient(100deg, #ffd21f, #fff3b0, #ffd21f);
            -webkit-background-clip: text; background-clip: text; color: transparent; -webkit-text-fill-color: transparent;
        }
        .mensaje p { margin-top: 12px; font-size: 15px; line-height: 1.6; opacity: .93; }

        /* =====================  PANEL DERECHO  ===================== */
        .panel {
            position: relative; overflow: hidden;
            display: flex; align-items: center; justify-content: center; padding: 40px 30px;
            background:
                radial-gradient(circle at 90% 8%, #f1e8ff 0%, transparent 40%),
                radial-gradient(circle at 5% 95%, #ffe3f3 0%, transparent 38%),
                #faf7ff;
            animation: desdeDer .9s ease both;
        }
        @keyframes desdeDer { from { opacity: 0; transform: translateX(40px); } to { opacity: 1; transform: none; } }

        .deco { position: absolute; pointer-events: none; font-size: 26px; opacity: .35; animation: vaiven 6s ease-in-out infinite; }
        .dc1 { top: 10%; right: 12%; } .dc2 { bottom: 12%; left: 10%; animation-delay: 1.5s; font-size: 34px; } .dc3 { top: 55%; right: 6%; animation-delay: 3s; }

        .form-box { position: relative; z-index: 2; width: 100%; max-width: 400px; text-align: center; }
        .logo-wrap { display: flex; justify-content: center; margin-bottom: 26px; animation: sube .7s ease both .2s; }
        @keyframes sube { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: none; } }

        .titulo { font-family: 'Dancing Script', cursive; font-size: 2.5rem; color: #5b3a8f; line-height: 1.1; animation: sube .7s ease both .3s; }
        .titulo b { color: #a76bf0; }
        .sub { color: #9b8ec4; font-size: 14px; margin: 8px 0 26px; animation: sube .7s ease both .35s; }

        .form-group { margin-bottom: 16px; text-align: left; animation: sube .7s ease both; }
        .form-group:nth-of-type(1) { animation-delay: .4s; }
        .form-group:nth-of-type(2) { animation-delay: .5s; }

        label { display: block; color: #6b5ca5; font-size: 13px; font-weight: bold; margin-bottom: 6px; }
        .campo { position: relative; }
        .campo .ico { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); font-size: 16px; pointer-events: none; }
        input[type="email"], input[type="password"], input[type="text"] {
            width: 100%; padding: 14px 46px 14px 44px; border: 2px solid #ece6fa; border-radius: 16px;
            font-size: 14px; outline: none; background: white; color: #2c2c2c; transition: all .3s;
            box-shadow: 0 4px 14px rgba(176,133,238,.08);
        }
        input:hover { border-color: #d8cff5; }
        input:focus { border-color: #b085ee; box-shadow: 0 0 0 4px rgba(176,133,238,.22), 0 8px 20px rgba(176,133,238,.15); transform: translateY(-1px); }
        .ojo {
            position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; font-size: 18px; padding: 6px; border-radius: 50%; transition: background .2s, transform .2s;
        }
        .ojo:hover { background: #f0eafc; transform: translateY(-50%) scale(1.15); }

        .fila { display: flex; align-items: center; justify-content: space-between; margin: 4px 0 6px; font-size: 13px; animation: sube .7s ease both .55s; }
        .fila label { display: flex; align-items: center; gap: 7px; margin: 0; font-weight: normal; color: #7b6a99; cursor: pointer; }
        .fila input[type="checkbox"] { width: 16px; height: 16px; accent-color: #8b5fd0; }
        .fila a { color: #8b5fd0; text-decoration: none; font-weight: bold; }
        .fila a:hover { text-decoration: underline; }

        .btn {
            position: relative; overflow: hidden; width: 100%; padding: 15px; margin-top: 12px;
            background: linear-gradient(135deg, #b085ee, #7a4cc4); color: white; border: none; border-radius: 30px;
            font-size: 16px; font-weight: bold; cursor: pointer; letter-spacing: .5px; font-family: inherit;
            box-shadow: 0 10px 28px rgba(122,76,196,.45); transition: transform .25s, box-shadow .25s;
            animation: sube .7s ease both .6s;
        }
        .btn::before {
            content: ''; position: absolute; top: 0; left: -80%; width: 50%; height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255,255,255,.7), transparent);
            transform: skewX(-20deg); animation: brillo 3s ease-in-out infinite;
        }
        @keyframes brillo { 0% { left: -80%; } 60%, 100% { left: 140%; } }
        .btn .flecha { display: inline-block; transition: transform .25s; }
        .btn:hover { transform: translateY(-3px); box-shadow: 0 16px 34px rgba(122,76,196,.55); }
        .btn:hover .flecha { transform: translateX(6px); }

        .divider { display: flex; align-items: center; gap: 12px; margin: 24px 0 16px; color: #c9a8f7; font-size: 13px; }
        .divider hr { flex: 1; border: none; border-top: 1px solid #ece6fa; }

        .btn-registro {
            display: block; padding: 13px; border: 2px solid #d8cff5; border-radius: 30px; color: #6b3fb0;
            font-weight: bold; text-decoration: none; font-size: 14px; transition: all .25s;
        }
        .btn-registro:hover { background: #f5efff; border-color: #b085ee; transform: translateY(-2px); }

        .volver { display: inline-block; margin-top: 18px; color: #9b8ec4; font-size: 13px; text-decoration: none; }
        .volver:hover { color: #6b5ca5; text-decoration: underline; }

        .error { background: #ffe9e9; color: #c0392b; padding: 11px 14px; border-radius: 14px; margin-bottom: 16px; font-size: 13px; text-align: left; animation: sacudir .5s ease; }
        @keyframes sacudir { 0%,100% { transform: translateX(0); } 20%,60% { transform: translateX(-6px); } 40%,80% { transform: translateX(6px); } }
        .estado { background: #d1f5e0; color: #276749; padding: 11px 14px; border-radius: 14px; margin-bottom: 16px; font-size: 13px; text-align: left; }

        /* =====================  CELULAR Y TABLET  ===================== */
        @media (max-width: 900px) {
            body { grid-template-columns: 1fr; }
            .hero { padding: 28px 20px 34px; }
            .stage { width: min(190px, 52%); }
            .chip { display: none; }
            .mensaje { margin-top: 14px; }
            .mensaje h1 { font-size: 1.9rem; }
            .mensaje p { font-size: 13px; }
            .panel { padding: 30px 20px 40px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
        }
    </style>
</head>
<body>

    {{-- ===================== PANEL IZQUIERDO ===================== --}}
    <section class="hero" id="hero">
        <span class="estrella e1">✦</span><span class="estrella e2">✦</span>
        <span class="estrella e3">✦</span><span class="estrella e4">✦</span>

        <div class="fondo" id="fondo"></div>

        <div class="stage" id="stage">
            <div class="glow"></div>
            <img src="{{ asset('mascota/osito-laptop.png') }}" alt="Osito de Corazón Rebelde con su laptop para agendar citas">
            <span class="chip c1">💅 Uñas perfectas</span>
            <span class="chip c2">📅 Agenda en segundos</span>
            <span class="chip c3">💜 Te esperamos</span>
        </div>

        <div class="mensaje">
            <h1>Tu momento de <span>brillar</span> empieza aquí</h1>
            <p>Elige a tu estilista favorita, reserva tu horario y recibe la confirmación en tu correo y WhatsApp.</p>
        </div>
    </section>

    {{-- ===================== PANEL DERECHO ===================== --}}
    <section class="panel">
        <span class="deco dc1">💜</span><span class="deco dc2">💅</span><span class="deco dc3">✦</span>

        <div class="form-box">
            <div class="logo-wrap"><x-logo size="lg" /></div>

            <h2 class="titulo">Bienvenida <b>de nuevo</b> 💜</h2>
            <p class="sub">Inicia sesión para agendar tu cita</p>

            @if(session('status'))
                <div class="estado">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <div class="campo">
                        <span class="ico">📧</span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="tucorreo@email.com">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="campo">
                        <span class="ico">🔒</span>
                        <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="Tu contraseña">
                        <button type="button" class="ojo" id="ojo" aria-label="Mostrar contraseña">👁️</button>
                    </div>
                </div>

                <div class="fila">
                    <label><input type="checkbox" name="remember"> Recordarme</label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                    @endif
                </div>

                <button type="submit" class="btn">Iniciar sesión <span class="flecha">→</span></button>
            </form>

            <div class="divider"><hr><span>✦ ¿Aún no tienes cuenta? ✦</span><hr></div>

            <a href="{{ route('register') }}" class="btn-registro">Crear mi cuenta 💜</a>

            <a href="{{ url('/') }}" class="volver">← Volver al inicio</a>
        </div>
    </section>

<script>
    /* Uñas y corazones flotando en el panel morado */
    (function () {
        const fondo = document.getElementById('fondo');
        const corazones = ['💜', '💗', '💖', '🤍'];
        for (let i = 0; i < 18; i++) {
            const flota = document.createElement('div');
            flota.className = 'flota';
            const interior = document.createElement('span');
            const t = Math.random();
            const c = corazones[Math.floor(Math.random() * corazones.length)];
            if (t < 0.45)      interior.textContent = '💅';
            else if (t < 0.7)  interior.innerHTML = `<span class="combo">${c}<span class="una">💅</span></span>`;
            else               interior.textContent = c;

            flota.style.left = Math.random() * 100 + '%';
            flota.style.fontSize = (16 + Math.random() * 24) + 'px';
            flota.style.animationDuration = (10 + Math.random() * 13) + 's';
            flota.style.animationDelay = (-Math.random() * 22) + 's';
            interior.style.animationDuration = (3 + Math.random() * 3) + 's';
            interior.style.animationDelay = (-Math.random() * 4) + 's';
            flota.appendChild(interior);
            fondo.appendChild(flota);
        }
    })();

    /* El osito sigue suavemente el mouse */
    (function () {
        const hero = document.getElementById('hero');
        const stage = document.getElementById('stage');
        if (matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        hero.addEventListener('mousemove', e => {
            const r = hero.getBoundingClientRect();
            const x = (e.clientX - r.left - r.width / 2) / 28;
            const y = (e.clientY - r.top - r.height / 2) / 28;
            stage.style.transform = `translate(${x}px, ${y}px)`;
        });
        hero.addEventListener('mouseleave', () => { stage.style.transform = ''; });
    })();

    /* Mostrar / ocultar contraseña */
    (function () {
        const campo = document.getElementById('password');
        const ojo = document.getElementById('ojo');
        ojo.addEventListener('click', () => {
            const visible = campo.type === 'text';
            campo.type = visible ? 'password' : 'text';
            ojo.textContent = visible ? '👁️' : '🙈';
            ojo.setAttribute('aria-label', visible ? 'Mostrar contraseña' : 'Ocultar contraseña');
        });
    })();
</script>
</body>
</html>