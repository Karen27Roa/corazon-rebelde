<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva contraseña | {{ config('app.name') }}</title>
    @include('auth.partials.estilos-morados')
</head>
<body>
<div class="cr-pantalla">
    @include('auth.partials.panel-morado', [
        'icono' => '🔑',
        'chip1' => '✨ Casi lista',
        'chip2' => '🔒 Nueva clave',
        'chip3' => '💅 Agenda tu cita',
        'titulo' => 'Un nuevo <span>comienzo</span><br>para ti',
        'texto' => 'Crea tu nueva contraseña y vuelve a consentirte con nosotras.',
    ])

    <main class="cr-lado">
        @include('auth.partials.destellos')

        <div class="cr-caja">
            @include('auth.partials.marca')

            <h1 class="cr-titulo">Crea tu nueva contraseña <span class="cr-corazon-titulo">💜</span></h1>
            <p class="cr-texto">Elige una contraseña segura que puedas recordar fácilmente.</p>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <label for="email" class="cr-label">Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                       class="cr-input @error('email') cr-input-error @enderror" required autocomplete="username">
                @error('email')
                    <p class="cr-error">{{ $message }}</p>
                @enderror

                <label for="password" class="cr-label">Nueva contraseña</label>
                <input id="password" type="password" name="password" placeholder="Mínimo 8 caracteres"
                       class="cr-input @error('password') cr-input-error @enderror" required autofocus autocomplete="new-password">
                @error('password')
                    <p class="cr-error">{{ $message }}</p>
                @enderror

                <label for="password_confirmation" class="cr-label">Confirmar contraseña</label>
                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Repite tu contraseña"
                       class="cr-input @error('password_confirmation') cr-input-error @enderror" required autocomplete="new-password">
                @error('password_confirmation')
                    <p class="cr-error">{{ $message }}</p>
                @enderror

                <button type="submit" class="cr-boton">Guardar contraseña →</button>
            </form>

            <a href="{{ route('login') }}" class="cr-enlace">← Volver a iniciar sesión</a>
        </div>
    </main>
</div>
</body>
</html>
