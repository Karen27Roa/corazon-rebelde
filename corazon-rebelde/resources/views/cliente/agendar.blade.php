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
            background: linear-gradient(135deg, #d4cff7 0%, #c9c2ee 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .card {
            background: white;
            border-radius: 24px;
            padding: 44px 40px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(180,170,230,0.5);
        }

        .logo-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 24px;
        }

        h2 {
            text-align: center;
            color: #6b5ca5;
            font-size: 22px;
            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            color: #6b5ca5;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        select, input, textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e8e4f8;
            border-radius: 12px;
            font-size: 14px;
            outline: none;
            background: #faf9ff;
            color: #2c2c2c;
            transition: all 0.3s;
        }

        select:focus, input:focus, textarea:focus {
            border-color: #c9c2ee;
            box-shadow: 0 0 0 4px rgba(201,194,238,0.2);
        }

        textarea {
            resize: vertical;
            height: 80px;
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
            margin-top: 10px;
            transition: all 0.3s;
            box-shadow: 0 6px 20px rgba(201,194,238,0.5);
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 16px;
            color: #6b5ca5;
            font-size: 14px;
            text-decoration: none;
        }

        .back:hover {
            text-decoration: underline;
        }

        .error {
            background: #ffe0e0;
            color: #c0392b;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 13px;
        }

        .hora-ocupada-msg {
            display: none;
            background: #ffe0e0;
            color: #c0392b;
            padding: 10px 14px;
            border-radius: 8px;
            margin-top: 8px;
            font-size: 13px;
        }

        select option:disabled {
            color: #bbb;
            background: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo-wrap">
            <x-logo size="lg" />
        </div>

        <h2>✦ Agenda tu cita ✦</h2>

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
                <input
                    type="date"
                    name="fecha"
                    id="fecha"
                    min="{{ date('Y-m-d') }}"
                    required
                >
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

    <script>
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