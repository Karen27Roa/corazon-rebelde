<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Corazón Rebelde</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #d4cff7 0%, #c9c2ee 50%, #b8b0e0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Georgia', serif;
            overflow-x: hidden;
            position: relative;
            padding: 30px 20px;
        }

        /* Destellos de fondo */
        .destello {
            position: fixed;
            pointer-events: none;
            animation: brillar 3s infinite;
        }
        .destello::before {
            content: '✦';
            color: white;
            opacity: 0.6;
            font-size: 20px;
        }
        .d1 { top: 8%; left: 10%; animation-delay: 0s; }
        .d2 { top: 15%; right: 12%; animation-delay: 0.5s; }
        .d3 { top: 40%; left: 5%; animation-delay: 1s; }
        .d4 { top: 60%; right: 8%; animation-delay: 1.5s; }
        .d5 { bottom: 20%; left: 15%; animation-delay: 0.8s; }
        .d6 { bottom: 10%; right: 20%; animation-delay: 0.3s; }
        .d7 { top: 25%; left: 30%; animation-delay: 2s; }
        .d8 { bottom: 35%; right: 30%; animation-delay: 1.2s; }
        .d9 { top: 70%; left: 40%; animation-delay: 0.6s; }
        .d10 { top: 5%; right: 40%; animation-delay: 1.8s; }

        @keyframes brillar {
            0%, 100% { opacity: 0.2; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.4); }
        }

        /* Burbujas decorativas */
        .burbuja {
            position: fixed;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            pointer-events: none;
        }
        .b1 { width: 300px; height: 300px; top: -100px; left: -80px; }
        .b2 { width: 200px; height: 200px; bottom: -60px; right: -60px; }
        .b3 { width: 150px; height: 150px; top: 40%; right: -50px; }

        .card {
            background: rgba(255,255,255,0.95);
            border-radius: 24px;
            padding: 44px 40px;
            width: 100%;
            max-width: 430px;
            box-shadow: 0 20px 60px rgba(180,170,230,0.5);
            text-align: center;
            position: relative;
            z-index: 10;
            backdrop-filter: blur(10px);
        }

        .logo-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 22px;
        }

        .form-group {
            margin-bottom: 16px;
            text-align: left;
        }

        label {
            display: block;
            color: #6b5ca5;
            font-size: 13px;
            margin-bottom: 6px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        label small {
            color: #9b8ec4;
            font-weight: normal;
        }

        input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e8e4f8;
            border-radius: 12px;
            font-size: 14px;
            outline: none;
            transition: all 0.3s;
            background: #faf9ff;
            color: #2c2c2c;
        }

        input:focus {
            border-color: #c9c2ee;
            background: white;
            box-shadow: 0 0 0 4px rgba(201,194,238,0.2);
        }

        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #c9c2ee, #a89fd4);
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 12px;
            transition: all 0.3s;
            letter-spacing: 1px;
            box-shadow: 0 6px 20px rgba(201,194,238,0.5);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(201,194,238,0.6);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0 16px;
            gap: 10px;
        }
        .divider hr {
            flex: 1;
            border: none;
            border-top: 1px solid #e8e4f8;
        }
        .divider span {
            color: #c9c2ee;
            font-size: 12px;
        }

        .link {
            display: inline-block;
            margin-top: 4px;
            color: #6b5ca5;
            font-size: 14px;
            text-decoration: none;
            font-weight: bold;
        }
        .link:hover { text-decoration: underline; }

        .error {
            background: #ffe0e0;
            color: #c0392b;
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 13px;
            text-align: left;
        }

        .sparkle-title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #6b5ca5;
            font-size: 20px;
            margin-bottom: 26px;
        }
    </style>
</head>
<body>

    <!-- Destellos -->
    <div class="destello d1"></div>
    <div class="destello d2"></div>
    <div class="destello d3"></div>
    <div class="destello d4"></div>
    <div class="destello d5"></div>
    <div class="destello d6"></div>
    <div class="destello d7"></div>
    <div class="destello d8"></div>
    <div class="destello d9"></div>
    <div class="destello d10"></div>

    <!-- Burbujas -->
    <div class="burbuja b1"></div>
    <div class="burbuja b2"></div>
    <div class="burbuja b3"></div>

    <div class="card">
        <div class="logo-wrap">
            <x-logo size="lg" />
        </div>

        <div class="sparkle-title">
            ✦ Crea tu cuenta ✦
        </div>

        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label>👤 Nombre completo</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Tu nombre">
            </div>
            <div class="form-group">
                <label>📧 Correo electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="tucorreo@email.com">
            </div>
            <div class="form-group">
                <label>📱 WhatsApp <small>(para enviarte la confirmación de tu cita)</small></label>
                <input type="tel" name="telefono" value="{{ old('telefono') }}" required inputmode="numeric" placeholder="3001234567">
            </div>
            <div class="form-group">
                <label>🔒 Contraseña</label>
                <input type="password" name="password" required placeholder="Mínimo 8 caracteres">
            </div>
            <div class="form-group">
                <label>🔒 Confirmar contraseña</label>
                <input type="password" name="password_confirmation" required placeholder="Repite tu contraseña">
            </div>
            <button type="submit" class="btn">✦ Registrarse ✦</button>
        </form>

        <div class="divider">
            <hr><span>✦</span><hr>
        </div>

        <a href="{{ route('login') }}" class="link">¿Ya tienes cuenta? Inicia sesión aquí 💜</a>
    </div>

</body>
</html>