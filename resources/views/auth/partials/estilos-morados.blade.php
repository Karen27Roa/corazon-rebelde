<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    /* ===== Colores de Corazón Rebelde (cámbialos aquí) ===== */
    :root {
        --cr-morado-oscuro: #4a2391;
        --cr-morado: #6a3fb5;
        --cr-morado-medio: #8b5cf6;
        --cr-lila: #b48cf0;
        --cr-lila-claro: #efe8fd;
        --cr-input: #eef1fb;
        --cr-dorado: #f2c14e;
        --cr-texto: #4b3a6b;
        --cr-gris: #8a7ca3;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; }
    body { font-family: 'Quicksand', system-ui, sans-serif; color: var(--cr-texto); background: #fff; }

    .cr-pantalla { min-height: 100vh; display: grid; grid-template-columns: 1fr 1fr; }

    /* ---------- Panel izquierdo morado ---------- */
    .cr-panel {
        position: relative; overflow: hidden;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 48px; text-align: center; color: #fff;
        background: linear-gradient(160deg, var(--cr-morado-oscuro) 0%, var(--cr-morado) 50%, var(--cr-morado-medio) 100%);
        background-size: 200% 200%;
        animation: cr-degradado 14s ease infinite;
    }
    .cr-circulo { position: absolute; border-radius: 50%; background: rgba(255,255,255,.07); }
    .cr-circulo.c1 { width: 360px; height: 360px; top: -120px; left: -120px; animation: cr-respirar 9s ease-in-out infinite; }
    .cr-circulo.c2 { width: 300px; height: 300px; bottom: -110px; right: -90px; animation: cr-respirar 11s ease-in-out infinite reverse; }

    .cr-escena { position: relative; width: 300px; height: 300px; margin-bottom: 36px; }
    .cr-aura {
        position: absolute; inset: 0; border-radius: 50%;
        background: radial-gradient(circle, rgba(196,160,255,.55) 0%, rgba(196,160,255,.15) 55%, transparent 70%);
        animation: cr-respirar 4s ease-in-out infinite;
    }
    .cr-candado {
        position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
        font-size: 120px; filter: drop-shadow(0 18px 30px rgba(40,10,90,.45));
        animation: cr-flotar 4s ease-in-out infinite;
    }
    .cr-chip {
        position: absolute; white-space: nowrap;
        background: #fff; color: var(--cr-morado-oscuro);
        font-size: 13px; font-weight: 700;
        padding: 8px 14px; border-radius: 999px;
        box-shadow: 0 8px 20px rgba(40,10,90,.25);
        animation: cr-flotar 5s ease-in-out infinite;
    }
    .cr-chip.k1 { top: 20px; left: -30px; }
    .cr-chip.k2 { top: 120px; right: -60px; animation-delay: -1.5s; }
    .cr-chip.k3 { bottom: 20px; left: -10px; animation-delay: -3s; }

    .cr-panel h2 {
        font-family: 'Dancing Script', cursive; font-weight: 700;
        font-size: 46px; line-height: 1.15; text-shadow: 0 4px 18px rgba(30,0,70,.35);
    }
    .cr-panel h2 span { color: var(--cr-dorado); }
    .cr-panel p { margin-top: 14px; max-width: 360px; font-size: 15px; opacity: .92; line-height: 1.6; }

    /* Emojis flotando */
    .cr-flota { position: absolute; bottom: -50px; animation: cr-subir linear infinite; opacity: 0; }
    .f1 { left: 8%;  font-size: 22px; animation-duration: 12s; }
    .f2 { left: 22%; font-size: 30px; animation-duration: 16s; animation-delay: -4s; }
    .f3 { left: 38%; font-size: 18px; animation-duration: 10s; animation-delay: -7s; }
    .f4 { left: 55%; font-size: 26px; animation-duration: 14s; animation-delay: -2s; }
    .f5 { left: 70%; font-size: 20px; animation-duration: 11s; animation-delay: -9s; }
    .f6 { left: 84%; font-size: 28px; animation-duration: 15s; animation-delay: -5s; }
    .f7 { left: 93%; font-size: 18px; animation-duration: 13s; animation-delay: -11s; }

    /* ---------- Lado derecho (formulario) ---------- */
    .cr-lado {
        position: relative; overflow: hidden;
        display: flex; align-items: center; justify-content: center; padding: 40px 24px;
        background: linear-gradient(180deg, #fbf9ff 0%, #f6f1ff 60%, #fdf2f8 100%);
    }
    .cr-destellos span { position: absolute; color: var(--cr-lila); animation: cr-titilar 3s ease-in-out infinite; }
    .cr-destellos .d1 { top: 14%; right: 12%; font-size: 22px; }
    .cr-destellos .d2 { top: 60%; right: 6%;  font-size: 16px; animation-delay: -1s; }
    .cr-destellos .d3 { bottom: 10%; left: 10%; font-size: 20px; animation-delay: -2s; }
    .cr-destellos .d4 { top: 22%; left: 8%; font-size: 14px; animation-delay: -.5s; }

    .cr-caja { position: relative; z-index: 1; width: 100%; max-width: 340px; text-align: center; animation: cr-entrar .8s cubic-bezier(.2,.8,.2,1) both; }

    /* Logo tipo insignia */
    .cr-marca {
        display: inline-flex; flex-direction: column; align-items: center; text-decoration: none;
        padding: 12px 28px 10px; border-radius: 14px;
        background: linear-gradient(135deg, var(--cr-morado-oscuro), var(--cr-morado));
        box-shadow: 0 12px 28px rgba(74,35,145,.35);
        margin-bottom: 22px; position: relative;
        animation: cr-latir 3s ease-in-out infinite;
    }
    .cr-marca::before, .cr-marca::after { content: "✦"; position: absolute; color: var(--cr-dorado); font-size: 12px; }
    .cr-marca::before { top: 4px; left: 8px; } .cr-marca::after { bottom: 4px; right: 8px; }
    .cr-marca .cr-nombre { font-family: 'Dancing Script', cursive; font-size: 32px; font-weight: 700; color: var(--cr-dorado); line-height: 1; }
    .cr-marca .cr-sub { font-family: 'Dancing Script', cursive; font-size: 17px; font-weight: 700; color: #f7d98a; }
    .cr-logo-img { max-width: 260px; max-height: 90px; object-fit: contain; margin-bottom: 22px; border-radius: 12px; box-shadow: 0 12px 28px rgba(74,35,145,.3); animation: cr-latir 3s ease-in-out infinite; }

    .cr-titulo {
        font-family: 'Dancing Script', cursive; font-weight: 700; font-size: 36px; line-height: 1.1;
        background: linear-gradient(90deg, var(--cr-morado-oscuro), var(--cr-morado-medio), var(--cr-lila), var(--cr-morado-oscuro));
        background-size: 300% auto; -webkit-background-clip: text; background-clip: text; color: transparent;
        animation: cr-brillo-texto 6s linear infinite;
    }
    .cr-corazon-titulo { display: inline-block; animation: cr-latir 1.4s ease-in-out infinite; }
    .cr-texto { font-size: 14px; color: var(--cr-gris); margin: 8px 0 24px; line-height: 1.55; }

    form { text-align: left; }
    form > * { animation: cr-entrar .6s ease both; }
    form > *:nth-child(2) { animation-delay: .15s; } form > *:nth-child(3) { animation-delay: .22s; }
    form > *:nth-child(4) { animation-delay: .3s; }  form > *:nth-child(5) { animation-delay: .38s; }
    form > *:nth-child(6) { animation-delay: .46s; } form > *:nth-child(7) { animation-delay: .54s; }
    form > *:nth-child(8) { animation-delay: .62s; } form > *:nth-child(9) { animation-delay: .7s; }

    .cr-label { display: block; font-size: 13px; font-weight: 700; color: var(--cr-morado-oscuro); margin: 16px 0 6px; }
    .cr-input {
        width: 100%; padding: 13px 16px; font: inherit; font-size: 14px; color: var(--cr-texto);
        background: var(--cr-input); border: 1.5px solid #ddd3f7; border-radius: 12px;
        transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .cr-input::placeholder { color: #a99cc4; }
    .cr-input:focus { outline: none; background: #fff; border-color: var(--cr-morado-medio); box-shadow: 0 0 0 4px rgba(139,92,246,.18); }
    .cr-input-error { border-color: #e11d48; animation: cr-temblar .4s ease; }
    .cr-error { color: #e11d48; font-size: 12px; margin-top: 6px; font-weight: 600; }

    .cr-boton {
        position: relative; overflow: hidden; width: 100%; margin-top: 24px; padding: 14px;
        font: inherit; font-size: 15px; font-weight: 700; color: #fff; cursor: pointer;
        border: none; border-radius: 999px;
        background: linear-gradient(90deg, var(--cr-lila) 0%, var(--cr-morado) 100%);
        box-shadow: 0 10px 24px rgba(106,63,181,.35);
        transition: transform .15s, box-shadow .15s;
    }
    .cr-boton:hover { transform: translateY(-2px); box-shadow: 0 14px 30px rgba(106,63,181,.45); }
    .cr-boton:active { transform: scale(.97); }
    .cr-boton::after {
        content: ""; position: absolute; top: 0; left: -75%; width: 50%; height: 100%;
        background: linear-gradient(120deg, transparent, rgba(255,255,255,.55), transparent);
        transform: skewX(-20deg); animation: cr-destello 3.2s ease-in-out infinite;
    }

    .cr-separador { display: flex; align-items: center; gap: 10px; margin: 22px 0 14px; color: var(--cr-lila); font-size: 12px; }
    .cr-separador::before, .cr-separador::after { content: ""; flex: 1; height: 1px; background: #e6dcfa; }

    .cr-boton-borde {
        display: block; width: 100%; padding: 12px; text-align: center; text-decoration: none;
        font-weight: 700; font-size: 14px; color: var(--cr-morado-oscuro);
        border: 1.5px solid #e2d6fb; border-radius: 999px; background: #fff;
        transition: background .2s, border-color .2s;
    }
    .cr-boton-borde:hover { background: var(--cr-lila-claro); border-color: var(--cr-lila); }

    .cr-enlace { display: inline-block; margin-top: 16px; font-size: 13px; color: var(--cr-gris); text-decoration: none; }
    .cr-enlace:hover { color: var(--cr-morado); }

    .cr-alerta { text-align: left; font-size: 13px; font-weight: 600; padding: 12px 14px; border-radius: 12px; margin-bottom: 6px; }
    .cr-alerta-ok { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; animation: cr-entrar .5s ease both; }

    /* ---------- Animaciones ---------- */
    @keyframes cr-degradado { 0%,100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
    @keyframes cr-respirar { 0%,100% { transform: scale(1); } 50% { transform: scale(1.08); } }
    @keyframes cr-flotar { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }
    @keyframes cr-subir { 0% { transform: translateY(0) rotate(0); opacity: 0; } 10% { opacity: .9; } 100% { transform: translateY(-115vh) rotate(30deg); opacity: 0; } }
    @keyframes cr-titilar { 0%,100% { opacity: .25; transform: scale(.8) rotate(0); } 50% { opacity: 1; transform: scale(1.15) rotate(20deg); } }
    @keyframes cr-entrar { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes cr-latir { 0%,100% { transform: scale(1); } 15% { transform: scale(1.06); } 30% { transform: scale(1); } 45% { transform: scale(1.04); } }
    @keyframes cr-brillo-texto { to { background-position: 300% center; } }
    @keyframes cr-destello { 0% { left: -75%; } 60%,100% { left: 130%; } }
    @keyframes cr-temblar { 0%,100% { transform: translateX(0); } 25% { transform: translateX(-6px); } 75% { transform: translateX(6px); } }

    /* ---------- Celular ---------- */
    @media (max-width: 900px) {
        .cr-pantalla { grid-template-columns: 1fr; }
        .cr-panel { display: none; }
        .cr-lado { min-height: 100vh; background: linear-gradient(180deg, var(--cr-morado-oscuro) 0%, var(--cr-morado) 38%, #f6f1ff 38.1%); }
        .cr-caja { background: #fff; padding: 32px 24px; border-radius: 24px; box-shadow: 0 20px 50px rgba(74,35,145,.25); }
    }

    @media (prefers-reduced-motion: reduce) { *, *::before, *::after { animation: none !important; } }
</style>
