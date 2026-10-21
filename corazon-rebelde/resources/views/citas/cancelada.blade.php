<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cita cancelada - Corazón Rebelde</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Georgia', serif; background:#f5f3ff; display:flex; justify-content:center; padding:40px 16px; margin:0; }
        .card { background:#fff; max-width:460px; width:100%; border-radius:20px; padding:32px; text-align:center; box-shadow:0 4px 20px rgba(201,194,238,0.4); }
        h2 { color:#6b5ca5; margin-top:0; }
        p { color:#555; line-height:1.7; }
        a { color:#6b5ca5; font-weight:bold; }
    </style>
</head>
<body>
<div class="card">
    <h2>✅ Tu cita fue cancelada</h2>
    <p>Avisamos a tu estilista. Cuando quieras, puedes agendar una nueva cita. 💜</p>
    <a href="{{ url('/') }}">Volver al inicio</a>
</div>
</body>
</html>