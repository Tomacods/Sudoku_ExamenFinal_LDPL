-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 14-12-2025 a las 12:57:54
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
(58, 1, 'facil', 23, '2025-11-21 07:37:51', 'victoria'),
(59, 9, 'facil', 25, '2025-11-22 22:03:41', 'victoria'),
(60, 9, 'medio', 26, '2025-11-22 22:04:21', 'derrota'),
(61, 9, 'medio', 42, '2025-11-22 22:05:24', 'victoria'),
(62, 9, 'dificil', 96, '2025-11-22 22:07:06', 'derrota'),
(63, 9, 'dificil', 66, '2025-11-22 22:09:10', 'victoria'),
(64, 9, 'dificil', 46, '2025-11-22 22:10:51', 'victoria'),
(65, 9, 'dificil', 103, '2025-11-22 22:12:45', 'victoria'),
(66, 1, 'facil', 11, '2025-11-25 06:31:59', 'victoria'),
(67, 1, 'facil', 27, '2025-11-25 07:40:57', 'victoria'),
(68, 1, 'medio', 25, '2025-11-25 07:41:26', 'victoria'),
(69, 1, 'medio', 27, '2025-11-25 07:42:04', 'derrota'),
(70, 1, 'medio', 18, '2025-11-25 07:42:36', 'victoria'),
(71, 1, 'medio', 22, '2025-11-25 07:43:08', 'victoria'),
(72, 1, 'dificil', 58, '2025-11-25 07:44:50', 'victoria'),
(73, 1, 'medio', 67, '2025-11-25 08:14:32', 'victoria'),
(74, 1, 'facil', 24, '2025-11-29 09:12:51', 'victoria'),
(75, 1, 'facil', 19, '2025-12-01 08:35:43', 'victoria'),
(76, 1, 'facil', 18, '2025-12-01 08:38:29', 'victoria'),
(77, 1, 'facil', 19, '2025-12-01 08:39:31', 'victoria'),
(78, 1, 'facil', 19, '2025-12-03 08:48:15', 'victoria'),
(79, 1, 'facil', 20, '2025-12-03 08:54:25', 'victoria'),
(80, 1, 'facil', 19, '2025-12-03 08:57:46', 'victoria'),
(81, 1, 'facil', 26, '2025-12-03 08:58:45', 'victoria'),
(82, 1, 'facil', 13, '2025-12-03 09:29:06', 'victoria'),
(83, 1, 'facil', 35, '2025-12-03 11:45:13', 'victoria'),
(84, 1, 'dificil', 1, '2025-12-03 11:52:29', 'derrota'),
(85, 1, 'facil', 11, '2025-12-04 09:21:12', 'derrota'),
(86, 1, 'facil', 18, '2025-12-04 09:22:03', 'victoria'),
(87, 1, 'facil', 1, '2025-12-04 09:26:44', 'derrota'),
(88, 1, 'facil', 39, '2025-12-04 09:27:37', 'derrota'),
(89, 1, 'facil', 137, '2025-12-04 09:33:12', 'derrota'),
(90, 1, 'facil', 2, '2025-12-04 09:41:59', 'derrota'),
(91, 1, 'facil', 1, '2025-12-04 09:42:12', 'derrota'),
(92, 1, 'facil', 17, '2025-12-04 09:42:32', 'victoria'),
(93, 1, 'facil', 6, '2025-12-04 09:42:46', 'derrota'),
(94, 1, 'facil', 48, '2025-12-04 09:43:28', 'derrota'),
(95, 1, 'facil', 1, '2025-12-04 09:51:15', 'derrota'),
(96, 1, 'facil', 39, '2025-12-04 09:51:57', 'victoria'),
(97, 1, 'facil', 0, '2025-12-04 09:52:04', 'derrota'),
(98, 1, 'facil', 21, '2025-12-04 09:52:42', 'victoria'),
(99, 1, 'facil', 14, '2025-12-04 09:57:46', 'victoria'),
(100, 1, 'facil', 79, '2025-12-04 10:37:47', 'victoria'),
(101, 1, 'facil', 8, '2025-12-04 10:38:15', 'derrota'),
(102, 1, 'facil', 21, '2025-12-05 09:59:54', 'victoria'),
(103, 1, 'facil', 532, '2025-12-05 10:18:49', 'derrota'),
(104, 10, 'dificil', 36, '2025-12-07 12:04:14', 'victoria'),
(105, 10, 'dificil', 33, '2025-12-07 12:05:05', 'victoria'),
(106, 10, 'medio', 20, '2025-12-07 12:05:34', 'victoria'),
(107, 10, 'facil', 16, '2025-12-07 12:05:57', 'victoria'),
(108, 10, 'facil', 35, '2025-12-07 12:07:15', 'victoria'),
(109, 1, 'facil', 25, '2025-12-08 09:52:15', 'victoria'),
(110, 1, 'medio', 1, '2025-12-08 09:53:18', 'derrota'),
(111, 1, 'facil', 4, '2025-12-08 12:06:10', 'derrota'),
(112, 1, 'facil', 23, '2025-12-10 21:10:40', 'victoria'),
(113, 1, 'facil', 1, '2025-12-10 21:10:55', 'derrota'),
(114, 12, 'facil', 22, '2025-12-12 20:28:50', 'victoria'),
(115, 12, 'medio', 18, '2025-12-12 20:29:13', 'victoria'),
(116, 12, 'dificil', 25, '2025-12-12 20:29:43', 'victoria'),
(117, 12, 'dificil', 15, '2025-12-12 20:30:21', 'victoria'),
(118, 12, 'medio', 18, '2025-12-12 20:30:44', 'victoria'),
(119, 12, 'facil', 14, '2025-12-12 20:31:03', 'victoria'),
(120, 13, 'facil', 21, '2025-12-12 20:33:55', 'victoria'),
(121, 13, 'medio', 28, '2025-12-12 20:34:32', 'derrota'),
(122, 13, 'dificil', 39, '2025-12-12 20:35:17', 'victoria');

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
(8, 'Federica', 'Roldan', 'federoldan229@gmail.com', 'fede', '$2y$10$I/p27bzlm2fbs5GV2Vf95O8znjx1TWvdtVJDrozO619twN9kuth5.', '2025-11-20 07:51:47'),
(9, 'Antonella ', 'Olavarria', 'Olavarriatone200@gmail.com', 'Tone', '$2y$10$lTyM2OohUeK77/U9dbZjoOn47Ks24dvKygx1khXfLLPMiFwyfa.P6', '2025-11-22 22:02:58'),
(10, 'lucas', 'benavides', 'lucasbenax@gmail.com', 'xaneb', '$2y$10$uzRsMdzf7E/Qpj.He7y1Ne4ZAI40tEdhG6Fh0vV/ZA7miOz3DlDxa', '2025-12-07 12:03:14'),
(11, 'MARTA BEATRIZ', 'RIVERA', 'Vero_fmanantial@hotmail.com', 'vero', '$2y$10$/AoKOSveKZMQx2m3cbCg0.YzFFS5yf0HyxfFyafPPyTeODFuKMy/G', '2025-12-11 11:09:38'),
(12, 'Lucas', 'Gonzalez', 'lucaseg15@outlook.com', 'lucaseg', '$2y$10$pkvq0heoJYvePYTc43MHzuPh6NyYBhk3wT/VpJbTJL7EZKZyxolsu', '2025-12-12 20:28:12'),
(13, 'César', 'Millavanque', 'cesarmiuni@gmail.com', 'cesarmi99', '$2y$10$j7mm3PY5pCmIAVmfPeEln.FUth7MmztFhwKb1Hio2R1sFQR8NJf0G', '2025-12-12 20:33:19');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
