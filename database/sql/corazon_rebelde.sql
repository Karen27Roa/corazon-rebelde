-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-11-2026 a las 01:26:29
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `corazon_rebelde`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `especialista_id` bigint(20) UNSIGNED NOT NULL,
  `servicio_id` bigint(20) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `estado` enum('pendiente','completada','cancelada') NOT NULL DEFAULT 'pendiente',
  `notas` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `google_event_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialistas`
--

CREATE TABLE `especialistas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `google_calendar_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `especialistas`
--

INSERT INTO `especialistas` (`id`, `user_id`, `telefono`, `descripcion`, `foto`, `created_at`, `updated_at`, `google_calendar_id`) VALUES
(10, 25, '3000000001', NULL, 'especialistas/WifhiA7vs0MEb8ky2O7fcAXfqW96iKFQin02vkKd.jpg', '2026-10-15 04:03:21', '2026-10-15 05:21:34', NULL),
(11, 26, '3000000002', NULL, 'especialistas/DcIGV3a9u0uy0ndD6eYUcooyHWS9bkoUPt1k3MUU.png', '2026-10-15 04:31:10', '2026-10-15 05:24:36', NULL),
(12, 27, '3000000003', NULL, 'especialistas/WM7itzwZKLJFbhYC024e8HC8zPQWTok6CNbzwsIP.png', '2026-10-15 04:51:36', '2026-10-15 05:23:48', NULL),
(13, 28, '3000000004', NULL, 'especialistas/REkZ6wjWMd4M560LT3VEMCn2Y7jPXMcv2p4CVaCB.png', '2026-10-15 04:53:37', '2026-10-15 05:21:15', NULL),
(14, 29, '3000000005', NULL, 'especialistas/snnbomlaicrUUePOxS8K5KtJ4kUIBqvQwFXtcNjP.png', '2026-10-15 04:59:24', '2026-10-29 03:14:12', NULL),
(15, 30, '3000000006', NULL, 'especialistas/nxmD8iEgJiRhaKYI7c1rWu6KrN14yoVFpuJS8QuK.jpg', '2026-10-15 05:22:41', '2026-10-15 05:22:41', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialista_servicio`
--

CREATE TABLE `especialista_servicio` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `especialista_id` bigint(20) UNSIGNED NOT NULL,
  `servicio_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `especialista_servicio`
--

INSERT INTO `especialista_servicio` (`id`, `especialista_id`, `servicio_id`, `created_at`, `updated_at`) VALUES
(31, 10, 10, NULL, NULL),
(32, 10, 11, NULL, NULL),
(33, 10, 12, NULL, NULL),
(34, 10, 13, NULL, NULL),
(35, 10, 14, NULL, NULL),
(36, 10, 15, NULL, NULL),
(37, 10, 16, NULL, NULL),
(38, 10, 17, NULL, NULL),
(39, 10, 18, NULL, NULL),
(40, 10, 19, NULL, NULL),
(41, 10, 20, NULL, NULL),
(42, 10, 21, NULL, NULL),
(43, 10, 22, NULL, NULL),
(44, 10, 23, NULL, NULL),
(45, 10, 24, NULL, NULL),
(46, 10, 25, NULL, NULL),
(47, 10, 26, NULL, NULL),
(48, 10, 27, NULL, NULL),
(49, 10, 28, NULL, NULL),
(50, 10, 29, NULL, NULL),
(51, 10, 30, NULL, NULL),
(52, 10, 31, NULL, NULL),
(53, 10, 32, NULL, NULL),
(54, 10, 33, NULL, NULL),
(55, 10, 34, NULL, NULL),
(56, 10, 35, NULL, NULL),
(59, 11, 10, NULL, NULL),
(60, 11, 11, NULL, NULL),
(61, 11, 12, NULL, NULL),
(62, 11, 13, NULL, NULL),
(63, 11, 14, NULL, NULL),
(64, 11, 15, NULL, NULL),
(65, 11, 16, NULL, NULL),
(66, 11, 17, NULL, NULL),
(67, 11, 18, NULL, NULL),
(68, 11, 19, NULL, NULL),
(69, 11, 20, NULL, NULL),
(70, 11, 21, NULL, NULL),
(71, 11, 22, NULL, NULL),
(72, 11, 23, NULL, NULL),
(73, 11, 24, NULL, NULL),
(74, 11, 25, NULL, NULL),
(75, 11, 26, NULL, NULL),
(76, 11, 27, NULL, NULL),
(77, 11, 28, NULL, NULL),
(78, 11, 29, NULL, NULL),
(79, 11, 30, NULL, NULL),
(80, 11, 31, NULL, NULL),
(81, 11, 32, NULL, NULL),
(82, 11, 33, NULL, NULL),
(83, 11, 34, NULL, NULL),
(84, 11, 35, NULL, NULL),
(87, 12, 10, NULL, NULL),
(88, 12, 11, NULL, NULL),
(89, 12, 12, NULL, NULL),
(90, 12, 13, NULL, NULL),
(91, 12, 14, NULL, NULL),
(92, 12, 15, NULL, NULL),
(93, 12, 16, NULL, NULL),
(94, 12, 17, NULL, NULL),
(95, 12, 18, NULL, NULL),
(96, 12, 19, NULL, NULL),
(97, 12, 20, NULL, NULL),
(98, 12, 21, NULL, NULL),
(99, 12, 22, NULL, NULL),
(100, 12, 23, NULL, NULL),
(101, 12, 24, NULL, NULL),
(102, 12, 25, NULL, NULL),
(103, 12, 26, NULL, NULL),
(104, 12, 27, NULL, NULL),
(105, 12, 28, NULL, NULL),
(106, 12, 29, NULL, NULL),
(107, 12, 30, NULL, NULL),
(108, 12, 31, NULL, NULL),
(109, 12, 32, NULL, NULL),
(110, 12, 33, NULL, NULL),
(111, 12, 34, NULL, NULL),
(112, 12, 35, NULL, NULL),
(115, 13, 10, NULL, NULL),
(116, 13, 11, NULL, NULL),
(117, 13, 12, NULL, NULL),
(118, 13, 13, NULL, NULL),
(119, 13, 14, NULL, NULL),
(120, 13, 15, NULL, NULL),
(121, 13, 16, NULL, NULL),
(122, 13, 17, NULL, NULL),
(123, 13, 18, NULL, NULL),
(124, 13, 19, NULL, NULL),
(125, 13, 20, NULL, NULL),
(126, 13, 21, NULL, NULL),
(127, 13, 22, NULL, NULL),
(128, 13, 23, NULL, NULL),
(129, 13, 24, NULL, NULL),
(130, 13, 25, NULL, NULL),
(131, 13, 26, NULL, NULL),
(132, 13, 27, NULL, NULL),
(133, 13, 28, NULL, NULL),
(134, 13, 29, NULL, NULL),
(135, 13, 30, NULL, NULL),
(136, 13, 31, NULL, NULL),
(137, 13, 32, NULL, NULL),
(138, 13, 33, NULL, NULL),
(139, 13, 34, NULL, NULL),
(140, 13, 35, NULL, NULL),
(143, 14, 10, NULL, NULL),
(144, 14, 11, NULL, NULL),
(145, 14, 12, NULL, NULL),
(146, 14, 13, NULL, NULL),
(147, 14, 14, NULL, NULL),
(148, 14, 15, NULL, NULL),
(149, 14, 16, NULL, NULL),
(150, 14, 17, NULL, NULL),
(151, 14, 18, NULL, NULL),
(152, 14, 19, NULL, NULL),
(153, 14, 20, NULL, NULL),
(154, 14, 21, NULL, NULL),
(155, 14, 22, NULL, NULL),
(156, 14, 23, NULL, NULL),
(157, 14, 24, NULL, NULL),
(158, 14, 25, NULL, NULL),
(159, 14, 26, NULL, NULL),
(160, 14, 27, NULL, NULL),
(161, 14, 28, NULL, NULL),
(162, 14, 29, NULL, NULL),
(163, 14, 30, NULL, NULL),
(164, 14, 31, NULL, NULL),
(165, 14, 32, NULL, NULL),
(166, 14, 33, NULL, NULL),
(167, 14, 34, NULL, NULL),
(168, 14, 35, NULL, NULL),
(171, 15, 10, NULL, NULL),
(172, 15, 11, NULL, NULL),
(173, 15, 12, NULL, NULL),
(174, 15, 13, NULL, NULL),
(175, 15, 14, NULL, NULL),
(176, 15, 15, NULL, NULL),
(177, 15, 16, NULL, NULL),
(178, 15, 17, NULL, NULL),
(179, 15, 18, NULL, NULL),
(180, 15, 19, NULL, NULL),
(181, 15, 20, NULL, NULL),
(182, 15, 21, NULL, NULL),
(183, 15, 22, NULL, NULL),
(184, 15, 23, NULL, NULL),
(185, 15, 24, NULL, NULL),
(186, 15, 25, NULL, NULL),
(187, 15, 26, NULL, NULL),
(188, 15, 27, NULL, NULL),
(189, 15, 28, NULL, NULL),
(190, 15, 29, NULL, NULL),
(191, 15, 30, NULL, NULL),
(192, 15, 31, NULL, NULL),
(193, 15, 32, NULL, NULL),
(194, 15, 33, NULL, NULL),
(195, 15, 34, NULL, NULL),
(196, 15, 35, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `galeria`
--

CREATE TABLE `galeria` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `servicio_id` bigint(20) UNSIGNED NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `galeria`
--

INSERT INTO `galeria` (`id`, `servicio_id`, `imagen`, `descripcion`, `created_at`, `updated_at`) VALUES
(9, 21, 'galeria/HptkQIcJCSOXkeAsoMaHyf4jXACKjhQ1JkQKs5Je.png', 'Las uñas acrílicas son la opción perfecta para lucir manos elegantes, fuertes y siempre impecables. Este sistema combina un polvo acrílico con un líquido especial que, al fusionarse, crea una capa resistente sobre la uña natural, permitiendo alargarla y darle la forma deseada.', '2026-04-09 21:57:59', '2026-04-09 21:58:16'),
(10, 23, 'galeria/hmFLgZFD9aGSQVEvyXZQUz41xRHvmYPySgX05iF0.png', 'Las uñas soft gel son la tendencia perfecta para quienes buscan un acabado natural, ligero y elegante. Este sistema utiliza tips de gel flexible que se adhieren a la uña natural, logrando una apariencia más delgada, cómoda y realista.', '2026-04-09 22:00:59', '2026-04-09 22:00:59'),
(13, 13, 'galeria/Q4dNqCyamfqodTmY7ul3AlYSPXvq0bsasL7gB3W4.jpg', 'La limpieza de pies es un servicio esencial enfocado en la higiene, salud y bienestar. Consiste en una limpieza profunda de uñas y piel, eliminación de impurezas, corte y limado, así como el cuidado de cutículas para mantener una apariencia ordenada y saludable.', '2026-04-09 22:08:05', '2026-04-09 22:08:05'),
(14, 12, 'galeria/QXkCDpOvnDhKjYH5lqzsgoH3YzMRQL5fELGHGwMn.png', 'La limpieza de manos es un servicio básico diseñado para mantener tus uñas y piel en perfecto estado de higiene y cuidado. Incluye limpieza profunda, corte y limado de uñas, así como el cuidado de cutículas para lograr una apariencia natural, ordenada y saludable.', '2026-04-09 22:10:34', '2026-04-09 22:10:34'),
(15, 32, 'galeria/JvRnFJuz9KcdQ3XVzCk28CdJwhbGHeABJXmIPFDp.jpg', 'Las piedras decorativas para uñas son el detalle perfecto para elevar cualquier diseño y darle un toque de brillo, elegancia y sofisticación. Se aplican cuidadosamente sobre la uña para crear estilos únicos que destacan en cualquier ocasión.', '2026-04-09 22:12:37', '2026-04-09 22:12:37'),
(16, 33, 'galeria/35Cyd8AFBjFii53v9TsJ3Pjs1HEdaiTye0r1cdM1.jpg', 'Los dijes para uñas son el complemento perfecto para crear diseños únicos, originales y llenos de personalidad. Estos pequeños accesorios decorativos se aplican sobre la uña para aportar un toque creativo, elegante o divertido, según tu estilo.', '2026-04-09 22:14:10', '2026-04-09 22:14:10'),
(17, 31, 'galeria/AUzHQQSFcAD9dI5dnOKO3W9xrgZLbYMvU1KRZceo.jpg', 'El retiro de esmalte semipermanente es un servicio especializado que elimina el producto de forma segura, cuidando la salud de tus uñas naturales. Se realiza mediante técnicas adecuadas que evitan el daño, debilitamiento o quiebre de la uña.', '2026-04-09 22:15:13', '2026-04-09 22:15:13'),
(18, 24, 'galeria/TejFcGVWmV2dddK98iyI6LZHFhw6WzFVyYsvSfKB.png', 'El retoque de alargamiento en acrílico es el servicio ideal para mantener tus uñas siempre perfectas y bien estructuradas. Con el crecimiento natural de la uña, se realiza un mantenimiento que rellena la zona de crecimiento, corrige imperfecciones y refuerza la estructura del acrílico.', '2026-04-09 22:17:26', '2026-04-09 22:17:26'),
(19, 15, 'galeria/7hvZqtkpdqMXsJzFvVSaDfNwWydKZkurrUFXaCIW.jpg', 'El esmaltado semipermanente para hombre es un servicio diseñado para quienes buscan un acabado limpio, natural y duradero. Consiste en la aplicación de un esmalte de larga duración que mantiene las uñas con una apariencia impecable por más tiempo.', '2026-04-09 22:18:29', '2026-04-09 22:18:29'),
(20, 34, 'galeria/SpiYqxqZMhlSzygDCivNSX9JZ8DRsIwwU7oJLQLk.webp', 'Los efectos para uñas son la opción perfecta para darle un toque único, moderno y creativo a tu manicure. A través de diferentes técnicas y productos, se logran acabados especiales como efecto espejo, nacarado, mate, glitter, cat eye, entre otros.', '2026-04-09 22:19:43', '2026-04-09 22:19:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `google_tokens`
--

CREATE TABLE `google_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `token` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_06_151307_create_especialistas_table', 2),
(5, '2026_04_06_151314_create_servicios_table', 2),
(6, '2026_04_06_151320_create_citas_table', 2),
(7, '2026_04_06_151326_create_galeria_table', 2),
(8, '2026_04_06_151333_create_especialista_servicio_table', 2),
(9, '2026_04_07_011803_add_role_to_users_table', 3),
(10, '2026_04_22_213531_create_google_tokens_table', 4),
(11, '2026_10_14_211557_add_campos_notificaciones', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(8,2) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `imagen`, `activo`, `created_at`, `updated_at`) VALUES
(10, 'Cambio de esmalte manos', 'Tradicional', 20000.00, NULL, 1, '2026-04-09 02:44:31', '2026-10-29 03:13:45'),
(11, 'Cambio de esmalte pies', 'Tradicional', 14000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(12, 'Limpieza manos', 'Tradicional', 17000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(13, 'Limpieza pies', 'Tradicional', 20000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(14, 'Semipermanente + Builder Gel', 'Semipermanente', 55000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(15, 'Semipermanente hombre', 'Semipermanente', 30000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(16, 'Pies semipermanente', 'Semipermanente', 50000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(17, 'Pies semipermanente hombre', 'Semipermanente', 35000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(18, 'Recubrimiento acrílico', 'Recubrimientos', 90000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(19, 'Recubrimiento poligel', 'Recubrimientos', 90000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(20, 'Dipping + semipermanente', 'Recubrimientos', 75000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(21, 'Acrílicas', 'Alargamientos', 125000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(22, 'Poligel', 'Alargamientos', 125000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(23, 'Soft Gel', 'Alargamientos', 87000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(24, 'Retoque de alargamiento acrílico', 'Retoques', 90000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(25, 'Retoque de alargamiento poligel', 'Retoques', 90000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(26, 'Retoque de recubrimiento acrílico', 'Retoques', 75000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(27, 'Retoque de recubrimiento poligel', 'Retoques', 75000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(28, 'Retoque soft gel', 'Retoques', 75000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(29, 'Retiro de alargamiento', 'Retiros', 25000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(30, 'Retiro de recubrimientos', 'Retiros', 20000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(31, 'Retiro de semipermanente', 'Retiros', 8000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(32, 'Piedras', 'Decoración', 2000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(33, 'Dijes', 'Decoración', 2000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(34, 'Efectos', 'Decoración', 5000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31'),
(35, 'Nail Art', 'Decoración - desde', 2000.00, NULL, 1, '2026-04-09 02:44:31', '2026-04-09 02:44:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','especialista','cliente') NOT NULL DEFAULT 'cliente',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `telefono`) VALUES
(21, 'administrador', 'admin@corazonrebelde.com', 'admin', NULL, '$2y$12$ektIODD7Vcr8YX.gxUyty.Q9hck4.rYJhPGqBFLcT97P.mQaS8Ncm', NULL, '2026-09-30 06:37:34', '2026-09-30 06:37:34', NULL),
(22, 'Cliente Demo', 'cliente@demo.com', 'cliente', NULL, '$2y$12$ektIODD7Vcr8YX.gxUyty.Q9hck4.rYJhPGqBFLcT97P.mQaS8Ncm', NULL, '2026-09-30 06:39:52', '2026-09-30 06:39:52', NULL),
(25, 'Angie Palomino', 'especialista1@demo.com', 'especialista', NULL, '$2y$12$ektIODD7Vcr8YX.gxUyty.Q9hck4.rYJhPGqBFLcT97P.mQaS8Ncm', NULL, '2026-10-15 04:03:21', '2026-10-15 05:22:58', NULL),
(26, 'Dana Palomino', 'especialista2@demo.com', 'especialista', NULL, '$2y$12$ektIODD7Vcr8YX.gxUyty.Q9hck4.rYJhPGqBFLcT97P.mQaS8Ncm', NULL, '2026-10-15 04:31:10', '2026-10-15 05:24:36', NULL),
(27, 'Tatiana', 'especialista3@demo.com', 'especialista', NULL, '$2y$12$ektIODD7Vcr8YX.gxUyty.Q9hck4.rYJhPGqBFLcT97P.mQaS8Ncm', NULL, '2026-10-15 04:51:36', '2026-10-15 05:23:48', NULL),
(28, 'Alejandra', 'especialista4@demo.com', 'especialista', NULL, '$2y$12$ektIODD7Vcr8YX.gxUyty.Q9hck4.rYJhPGqBFLcT97P.mQaS8Ncm', NULL, '2026-10-15 04:53:37', '2026-10-15 05:21:15', NULL),
(29, 'Karen', 'especialista5@demo.com', 'especialista', NULL, '$2y$12$ektIODD7Vcr8YX.gxUyty.Q9hck4.rYJhPGqBFLcT97P.mQaS8Ncm', NULL, '2026-10-15 04:59:24', '2026-10-29 03:14:12', NULL),
(30, 'Sofia', 'especialista6@demo.com', 'especialista', NULL, '$2y$12$ektIODD7Vcr8YX.gxUyty.Q9hck4.rYJhPGqBFLcT97P.mQaS8Ncm', NULL, '2026-10-15 05:22:41', '2026-10-15 05:22:41', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `citas_user_id_foreign` (`user_id`),
  ADD KEY `citas_especialista_id_foreign` (`especialista_id`),
  ADD KEY `citas_servicio_id_foreign` (`servicio_id`);

--
-- Indices de la tabla `especialistas`
--
ALTER TABLE `especialistas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `especialistas_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `especialista_servicio`
--
ALTER TABLE `especialista_servicio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `especialista_servicio_especialista_id_foreign` (`especialista_id`),
  ADD KEY `especialista_servicio_servicio_id_foreign` (`servicio_id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `galeria`
--
ALTER TABLE `galeria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `galeria_servicio_id_foreign` (`servicio_id`);

--
-- Indices de la tabla `google_tokens`
--
ALTER TABLE `google_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `especialistas`
--
ALTER TABLE `especialistas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `especialista_servicio`
--
ALTER TABLE `especialista_servicio`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `galeria`
--
ALTER TABLE `galeria`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `google_tokens`
--
ALTER TABLE `google_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_especialista_id_foreign` FOREIGN KEY (`especialista_id`) REFERENCES `especialistas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `citas_servicio_id_foreign` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `citas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `especialistas`
--
ALTER TABLE `especialistas`
  ADD CONSTRAINT `especialistas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `especialista_servicio`
--
ALTER TABLE `especialista_servicio`
  ADD CONSTRAINT `especialista_servicio_especialista_id_foreign` FOREIGN KEY (`especialista_id`) REFERENCES `especialistas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `especialista_servicio_servicio_id_foreign` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `galeria`
--
ALTER TABLE `galeria`
  ADD CONSTRAINT `galeria_servicio_id_foreign` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
