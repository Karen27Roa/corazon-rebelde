<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar contraseña | {{ config('app.name') }}</title>
    @include('auth.partials.estilos-morados')
</head>
<body>
<div class="cr-pantalla">
    @include('auth.partials.panel-morado')

    <main class="cr-lado">
        @include('auth.partials.destellos')

        <div class="cr-caja">
            @include('auth.partials.marca')

            <h1 class="cr-titulo">¿Olvidaste tu contraseña? <span class="cr-corazon-titulo">💜</span></h1>
            <p class="cr-texto">No te preocupes. Escribe el correo con el que te registraste y te enviaremos un enlace para crear una nueva.</p>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                @if (session('status'))
                    <div class="cr-alerta cr-alerta-ok">✅ {{ session('status') }}</div>
                @endif

                <label for="email" class="cr-label">Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="cr-input @error('email') cr-input-error @enderror"
                       placeholder="tucorreo@ejemplo.com" required autofocus>
                @error('email')
                    <p class="cr-error">{{ $message }}</p>
                @enderror

                <button type="submit" class="cr-boton">Enviar enlace →</button>
            </form>

            <div class="cr-separador">✦ ¿Ya la recordaste? ✦</div>
            <a href="{{ route('login') }}" class="cr-boton-borde">Iniciar sesión 💜</a>

            <a href="{{ url('/') }}" class="cr-enlace">← Volver al inicio</a>
        </div>
    </main>
</div>
</body>
</html>
