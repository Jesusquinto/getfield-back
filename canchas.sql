-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-04-2019 a las 09:33:09
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `canchas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `canchas`
--

CREATE TABLE `canchas` (
  `id` int(11) NOT NULL,
  `establecimiento_id` int(11) NOT NULL,
  `dimensiones` text NOT NULL,
  `nombre` text NOT NULL,
  `imagen` text NOT NULL,
  `descripcion` text NOT NULL,
  `estado` text NOT NULL,
  `parametros` text NOT NULL,
  `valor_x_minuto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `canchas`
--

INSERT INTO `canchas` (`id`, `establecimiento_id`, `dimensiones`, `nombre`, `imagen`, `descripcion`, `estado`, `parametros`, `valor_x_minuto`) VALUES
(1, 1, '50x50', 'la macarena', 'img001.png', 'esta cancha esta en funcionamiento', '0', 'lolo', 100),
(2, 1, '60x60', 'la gran cancha', 'img007.png', 'esta cancha esta en funcionamiento', '0', 'mjmj', 150);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `establecimientos`
--

CREATE TABLE `establecimientos` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `descripcion` text NOT NULL,
  `logo_img` text NOT NULL,
  `estado` text NOT NULL,
  `horario` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user` varchar(50) NOT NULL,
  `password` varchar(65) NOT NULL,
  `valoracion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `establecimientos`
--

INSERT INTO `establecimientos` (`id`, `nombre`, `descripcion`, `logo_img`, `estado`, `horario`, `fecha_creacion`, `user`, `password`, `valoracion`) VALUES
(1, 'la bonbonera', '', 'img_01.pmg', '0', '{\r\n		\"dias\": [\"Monday\",\"Tuesday\", \"Wenesday\",\"Thursday\",\"Friday\"],\r\n		\"horarios\": {\"horainicial\": \"07:00\", \"horafinal\": \"22:30\"}\r\n \r\n}', '2019-03-29 00:45:33', 'usuario', '123', 10),
(2, 'tres canchas', '', 'img_03.pmg', '0', '{\r\n		\"dias\": [\"Monday\",\"Tuesday\", \"Wenesday\",\"Thursday\",\"Friday\"],\r\n		\"horarios\": {\"horainicial\": \"07:00\", \"horafinal\": \"22:30\"}\r\n \r\n}', '2019-03-29 07:01:02', 'usuario2', '358', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `cancha_id` int(11) NOT NULL,
  `horario` varchar(200) NOT NULL,
  `estado` text NOT NULL,
  `metodo_pago` text NOT NULL,
  `valor_a_pagar` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `fecha_cancelada` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `usuario_id`, `cancha_id`, `horario`, `estado`, `metodo_pago`, `valor_a_pagar`, `created_at`, `updated_at`, `fecha_cancelada`) VALUES
(1, 4, 1, ' {\"horainicial\": \"2019-03-28 14:38:00\", \"horafinal\": \"2019-03-28 15:38:00\"}', 'A', 'PAYPAL', 35000, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2019-03-27 19:47:01'),
(10, 4, 1, '{\"horainicial\": \"2019-03-27 21:38:00\", \"horafinal\": \"2019-03-27 22:38:00\"}', 'A', 'PAYPAL', 45000, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 4, 1, '{\"horainicial\":\"2019-04-01 15:00\",\"horafinal\":\"2019-04-01 22:00:00\"}', 'A', 'PAYPAL', 35000, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2019-03-29 06:52:51'),
(14, 4, 1, '{\"horainicial\":\"2019-04-02 15:00\",\"horafinal\":\"2019-04-02 22:00:00\"}', 'A', 'PAYPAL', 35000, '2019-03-29 01:58:47', '2019-03-29 01:58:47', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `descripcion` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`) VALUES
(1, 'SUPER ADMIN', NULL),
(2, 'ADMIN', NULL),
(3, 'USER', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarifas`
--

CREATE TABLE `tarifas` (
  `id` int(11) NOT NULL,
  `establecimiento_id` int(11) NOT NULL,
  `parametros` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `apellido` text NOT NULL,
  `usuario` text NOT NULL,
  `password` text,
  `estado` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `imagen` text NOT NULL,
  `rol_id` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `apellido`, `usuario`, `password`, `estado`, `email`, `imagen`, `rol_id`, `created_at`, `updated_at`) VALUES
(4, 'juancho', 'suan', 'juanchito', '$2y$10$NJ7IHAe8cetfpxKdZL5QbemB.6G8vl5jr9HeKlups8BkpPI9zmoY2', 'A', 'juanch@gmail.com', '', 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'Juan', 'Rosales', 'RosaAn', '$2y$10$NJ7IHAe8cetfpxKdZL5QbemB.6G8vl5jr9HeKlups8BkpPI9zmoY2', 'A', 'rosan@lol.com', '', 3, '2019-03-30 00:40:55', '2019-03-30 00:40:55'),
(35, 'Jesus', 'Quinto', 'iditroyer5', NULL, 'A', 'iditroyer5@gmail.com', 'https://lh3.googleusercontent.com/-qLQC-aoF0Mk/AAAAAAAAAAI/AAAAAAAAACI/3FNkaLtdKeU/photo.jpg', 3, '2019-04-02 01:21:22', '2019-04-02 01:21:22');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `canchas`
--
ALTER TABLE `canchas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_establecimiento` (`establecimiento_id`);

--
-- Indices de la tabla `establecimientos`
--
ALTER TABLE `establecimientos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cancha` (`cancha_id`),
  ADD KEY `id_usuario` (`usuario_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tarifas`
--
ALTER TABLE `tarifas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `canchas`
--
ALTER TABLE `canchas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `establecimientos`
--
ALTER TABLE `establecimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tarifas`
--
ALTER TABLE `tarifas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `canchas`
--
ALTER TABLE `canchas`
  ADD CONSTRAINT `canchas_ibfk_1` FOREIGN KEY (`establecimiento_id`) REFERENCES `establecimientos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`cancha_id`) REFERENCES `canchas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
