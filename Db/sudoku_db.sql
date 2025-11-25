-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 21-11-2025 a las 13:24:17
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
-- Base de datos: `sudoku_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partidas`
--

CREATE TABLE `partidas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nivel` enum('facil','medio','dificil') NOT NULL,
  `tiempo_segundos` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `resultado` enum('victoria','derrota') DEFAULT 'victoria'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `partidas`
--

INSERT INTO `partidas` (`id`, `usuario_id`, `nivel`, `tiempo_segundos`, `fecha`, `resultado`) VALUES
(1, 1, 'facil', 250, '2025-11-17 10:36:54', 'victoria'),
(2, 1, 'facil', 24, '2025-11-17 10:37:22', 'derrota'),
(3, 1, 'facil', 30, '2025-11-17 10:37:28', 'derrota'),
(4, 1, 'facil', 21, '2025-11-17 10:39:01', 'victoria'),
(5, 1, 'facil', 96, '2025-11-17 10:43:55', 'derrota'),
(6, 1, 'facil', 24, '2025-11-17 10:48:20', 'victoria'),
(7, 1, 'facil', 21, '2025-11-17 07:51:09', 'victoria'),
(8, 1, 'facil', 1, '2025-11-17 07:51:47', 'derrota'),
(9, 1, 'medio', 1, '2025-11-17 07:51:51', 'derrota'),
(10, 1, 'facil', 306, '2025-11-18 06:58:47', 'victoria'),
(11, 1, 'facil', 64, '2025-11-18 07:00:06', 'victoria'),
(12, 1, 'facil', 146, '2025-11-18 07:55:32', 'victoria'),
(13, 4, 'facil', 20, '2025-11-18 08:09:37', 'victoria'),
(14, 4, 'facil', 25, '2025-11-18 08:26:09', 'victoria'),
(15, 1, 'facil', 88, '2025-11-18 09:35:44', 'victoria'),
(16, 1, 'facil', 2, '2025-11-18 09:45:41', 'derrota'),
(17, 1, 'facil', 3, '2025-11-18 09:55:41', 'derrota'),
(18, 1, 'facil', 138, '2025-11-18 10:01:55', 'derrota'),
(19, 1, 'facil', 201, '2025-11-18 10:02:58', 'derrota'),
(20, 1, 'facil', 1, '2025-11-18 10:03:02', 'derrota'),
(21, 1, 'facil', 7, '2025-11-18 10:03:22', 'derrota'),
(22, 1, 'facil', 5, '2025-11-18 10:03:33', 'derrota'),
(23, 1, 'facil', 104, '2025-11-18 10:05:12', 'derrota'),
(24, 1, 'facil', 123, '2025-11-18 10:07:19', 'derrota'),
(25, 1, 'facil', 2, '2025-11-18 10:07:46', 'derrota'),
(26, 1, 'facil', 2, '2025-11-18 10:09:21', 'derrota'),
(27, 1, 'facil', 69, '2025-11-18 10:14:41', 'victoria'),
(28, 1, 'facil', 135, '2025-11-19 07:10:58', 'derrota'),
(29, 1, 'facil', 1, '2025-11-19 07:12:58', 'derrota'),
(30, 1, 'facil', 1, '2025-11-19 07:13:08', 'derrota'),
(31, 1, 'facil', 1, '2025-11-19 07:44:16', 'derrota'),
(32, 1, 'facil', 55, '2025-11-19 07:47:14', 'derrota'),
(33, 1, 'facil', 779, '2025-11-19 08:06:06', 'derrota'),
(34, 1, 'facil', 19, '2025-11-19 08:07:12', 'victoria'),
(35, 1, 'dificil', 213, '2025-11-20 06:49:30', 'derrota'),
(36, 1, 'dificil', 28, '2025-11-20 07:17:12', 'derrota'),
(37, 1, 'dificil', 93, '2025-11-20 07:46:59', 'victoria'),
(38, 1, 'dificil', 40, '2025-11-20 07:47:54', 'victoria'),
(39, 8, 'medio', 48, '2025-11-20 07:54:41', 'derrota'),
(40, 8, 'medio', 130, '2025-11-20 07:57:39', 'victoria'),
(41, 1, 'medio', 20, '2025-11-20 07:58:48', 'victoria'),
(42, 1, 'medio', 31, '2025-11-20 08:01:28', 'victoria'),
(43, 1, 'medio', 40, '2025-11-20 08:09:28', 'victoria'),
(44, 1, 'dificil', 27, '2025-11-21 06:55:19', 'derrota'),
(45, 1, 'facil', 16, '2025-11-21 06:56:32', 'victoria'),
(46, 1, 'facil', 18, '2025-11-21 06:58:51', 'victoria'),
(47, 1, 'medio', 44, '2025-11-21 07:05:55', 'victoria'),
(48, 1, 'facil', 2, '2025-11-21 07:06:02', 'derrota'),
(49, 1, 'facil', 21, '2025-11-21 07:06:29', 'victoria'),
(50, 1, 'facil', 18, '2025-11-21 07:13:08', 'victoria'),
(51, 1, 'facil', 34, '2025-11-21 07:14:13', 'victoria'),
(52, 1, 'facil', 19, '2025-11-21 07:20:28', 'victoria'),
(53, 1, 'facil', 3, '2025-11-21 07:25:40', 'derrota'),
(54, 1, 'facil', 16, '2025-11-21 07:26:04', 'victoria'),
(55, 1, 'facil', 22, '2025-11-21 07:29:27', 'victoria'),
(56, 1, 'facil', 25, '2025-11-21 07:35:00', 'victoria'),
(57, 1, 'facil', 23, '2025-11-21 07:36:35', 'victoria'),
(58, 1, 'facil', 23, '2025-11-21 07:37:51', 'victoria');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `email`, `usuario`, `password`, `created_at`) VALUES
(1, 'Tomas', 'Da silva', 'tomacods@gmail.com', 'tomaco', '$2y$10$7XG5FWiQBmIc99SHkhfFr.SN64Eb9juAtoZWRgqWyqIJ6Fm01zFf2', '2025-11-17 07:10:45'),
(4, 'Paola', 'Quiroga', 'paolaquiroga282@gmail.com', 'paola', '$2y$10$JBhL6AyCrMqZB60tQ79vBukSS0I/njeP1K8Mu3VLYwr5bhwhWUzca', '2025-11-17 09:15:52'),
(6, 'Tomas', 'Da silva', 'Tomasds15@gmail.com', 'tomas', '$2y$10$55plRkOl2DVUFD8XQ/GabOfb3.IwtkaZldq6Ia2obojpHWg4rv3iC', '2025-11-18 07:44:18'),
(7, 'Mora', 'Molina', 'mor4lina2003@gmail.com', 'mora', '$2y$10$HXelJvO6WSwd5FNUiCAPWeP.YZVYcj6/5dJ5CCvF4P4fF57m8JgSq', '2025-11-20 07:50:00'),
(8, 'Federica', 'Roldan', 'federoldan229@gmail.com', 'fede', '$2y$10$I/p27bzlm2fbs5GV2Vf95O8znjx1TWvdtVJDrozO619twN9kuth5.', '2025-11-20 07:51:47');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `partidas`
--
ALTER TABLE `partidas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `partidas`
--
ALTER TABLE `partidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `partidas`
--
ALTER TABLE `partidas`
  ADD CONSTRAINT `partidas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
