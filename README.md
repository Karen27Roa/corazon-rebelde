# Corazón Rebelde Nails Spa

[![Pruebas](https://github.com/Karen27Roa/corazon-rebelde/actions/workflows/pruebas.yml/badge.svg)](https://github.com/Karen27Roa/corazon-rebelde/actions/workflows/pruebas.yml)

Aplicación web para agendar citas del salón **Corazón Rebelde Nails Spa**, hecha con **Laravel** y **MySQL**.

La clienta se registra, agenda su cita y recibe la confirmación por **correo** y por **WhatsApp**. La administradora gestiona servicios, especialistas, citas, clientes y galería desde un panel con inicio de sesión.

## Qué hace

- Portada con galería y carta de servicios.
- Agendamiento de citas en línea (no permite dos citas a la misma hora con la misma especialista).
- Confirmación automática por WhatsApp y correo con los datos reales de la cita.
- Aviso por WhatsApp al número del negocio cuando se agenda una cita nueva.
- Panel de administración: dashboard, clientes, especialistas, servicios, citas y galería.
- **Recordatorio automático por WhatsApp** el día antes de cada cita.
- **Lista de espera inteligente**: si un horario está lleno, la clienta se anota; cuando alguien cancela o reagenda, el sistema avisa por WhatsApp a quienes esperaban ese horario, en orden de llegada.
- **Dashboard con indicadores (KPIs)**: ingresos, tasa de asistencia y de cancelación, ticket promedio, clientas recurrentes, horas pico, servicios más pedidos y carga por especialista.
- **Pruebas automáticas** (PHPUnit) que corren solas en GitHub con **GitHub Actions** cada vez que se sube código.

## Lo que necesitas instalar

- [PHP 8.2 o superior](https://www.php.net/) y [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/)
- [XAMPP](https://www.apachefriends.org/) (para MySQL)
- [Git](https://git-scm.com/)

## Cómo ponerla a funcionar

**1. Descargar el proyecto e instalar**

```bash
git clone https://github.com/Karen27Roa/corazon-rebelde.git
cd corazon-rebelde
composer install
npm install
```

**2. Crear el archivo de configuración**

```bash
copy .env.example .env
php artisan key:generate
```

(En Mac o Linux usa `cp` en lugar de `copy`.)

**3. Crear la base de datos**

Abre XAMPP, enciende **MySQL** y crea una base vacía llamada `corazon_rebelde` (en phpMyAdmin o con este comando):

```bash
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE corazon_rebelde CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

**4. Cargar las tablas y los datos iniciales**

El archivo `database/sql/corazon_rebelde.sql` ya trae todo (tablas, servicios, especialistas y la administradora). Impórtalo:

```bash
cmd /c "C:\xampp\mysql\bin\mysql.exe -u root corazon_rebelde < database\sql\corazon_rebelde.sql"
```

También se puede importar desde phpMyAdmin (pestaña **Importar**).

Después crea las tablas nuevas (lista de espera y recordatorios):

```bash
php artisan migrate
```

**5. Imágenes y estilos**

```bash
php artisan storage:link
npm run build
```

**6. Encender la aplicación**

```bash
php artisan serve
```

Abre **http://127.0.0.1:8000**

## Entrar como administradora

| Correo | Contraseña |
|--------|------------|
| `admin@corazonrebelde.com` | `Demo2026*` |

También hay un usuario de prueba para clientas: `cliente@demo.com` con la misma contraseña.

## Confirmaciones por WhatsApp (opcional)

Sin esto la aplicación funciona igual, solo que no se envían los mensajes.

Abre el `.env` y completa:

```env
WA_URL=http://127.0.0.1:3000
WA_TOKEN=elige_un_token_secreto
WA_NEGOCIO=57XXXXXXXXXX
```

- `WA_TOKEN`: cualquier clave que tú inventes. Debe ser la misma que uses en el paso siguiente.
- `WA_NEGOCIO`: el número del negocio que recibe el aviso de cada cita nueva (con 57 y sin +).

Luego, en **otra terminal**, enciende el servicio de WhatsApp:

```bash
cd wa-servicio
npm install
$env:WA_TOKEN="elige_un_token_secreto"
node index.js
```

La primera vez aparece un código QR: escanéalo con WhatsApp (**Dispositivos vinculados**). Cuando diga **"WhatsApp listo"**, ya está funcionando. Debe quedar encendido mientras se usa la página.

> Importante: la clienta debe tener su celular guardado en su perfil para recibir el mensaje.

## Correo y Google Calendar (opcional)

Para que lleguen los correos, completa en el `.env` los datos `MAIL_...` (por ejemplo con una cuenta de Gmail y su "contraseña de aplicación"). Para crear los eventos en Google Calendar, completa `GOOGLE_CALENDAR_ID` y `GOOGLE_CALENDAR_CREDENTIALS`. Si los dejas vacíos, la cita se agenda igual.

## Recordatorios automáticos (Scheduler)

Los recordatorios por WhatsApp los envía una tarea automática que se revisa cada 30 minutos.
En otra terminal, déjala encendida con:

```bash
php artisan schedule:work
```

Para probarla a mano en cualquier momento: `php artisan citas:recordatorios`.
En un servidor real se programa una tarea (cron) que ejecute `php artisan schedule:run` cada minuto.

## Datos de demostración para el dashboard (opcional)

Para que las gráficas tengan información en una presentación:

```bash
php artisan db:seed --class=DemoCitasSeeder
```

Crea clientas de prueba (`demo-clienta-N@demo.com`, sin celular, así que no se envían WhatsApp) con citas de los últimos 6 meses.
Para quitarlas, elimina en phpMyAdmin los usuarios cuyo correo empieza por `demo-clienta`.

## Pruebas automáticas

```bash
php artisan test
```

Cubren las reglas del negocio: que no se crucen dos citas, que solo la dueña cancele su cita, los recordatorios (no se envían dos veces, se reintentan si WhatsApp está apagado), la lista de espera (a quién se avisa y en qué orden) y el cálculo de los indicadores.
Las mismas pruebas corren solas en GitHub (pestaña **Actions**) cada vez que se hace `git push`.

## Ingeniería de software

| Decisión | Dónde | Para qué |
|----------|-------|----------|
| **Capa de servicios** | `app/Services` | La lógica de negocio (WhatsApp, lista de espera, métricas) queda separada de los controladores y se puede probar sola. |
| **Patrón Observer** | `app/Observers/CitaObserver.php` | Detecta cuándo se libera un horario (cancelación, reagendamiento o eliminación) sin importar desde qué pantalla se hizo, y avisa a la lista de espera. |
| **Tareas programadas** | `routes/console.php`, `app/Console/Commands` | Recordatorios cada 30 minutos y vencimiento diario de solicitudes viejas. |
| **Tolerancia a fallos** | `WhatsAppService`, `EnviarRecordatorios` | Si el servicio de WhatsApp está apagado, la cita se agenda igual y el recordatorio se reintenta después. |
| **Pruebas + integración continua** | `tests/`, `.github/workflows/pruebas.yml` | Cada cambio se verifica automáticamente antes de llegar a producción. |

## Cada vez que quieras usarla

1. Encender **MySQL** en XAMPP.
2. `php artisan serve`
3. (Si usas WhatsApp) encender `wa-servicio` con `node index.js`.
4. (Para los recordatorios) `php artisan schedule:work` en otra terminal.

## Si algo falla

| Problema | Solución |
|----------|----------|
| `Could not open input file: artisan` | Entra a la carpeta del proyecto (`cd corazon-rebelde`). |
| `Connection refused` o `Access denied` | Enciende MySQL en XAMPP y revisa `DB_USERNAME` y `DB_PASSWORD` en el `.env`. |
| Se ve sin estilos o `Vite manifest not found` | Corre `npm install` y `npm run build`. |
| No salen las imágenes | Corre `php artisan storage:link`. Si aun así no aparecen, súbelas desde el panel de administración. |
| Cambié el `.env` y no pasa nada | Corre `php artisan config:clear`. |
| `PortableVisibilityConverter not found` | Borra la carpeta `vendor` y corre `composer install` otra vez. |
| WhatsApp no envía | Revisa que `wa-servicio` esté encendido y que `WA_TOKEN` sea el mismo en el `.env` y en la terminal. |

## Autora

**Karen Yulied Bohorquez Roa** — Trabajo de grado, Corazón Rebelde Nails Spa.
