-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-06-2020 a las 12:31:08
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `clontube`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `canal`
--

CREATE TABLE `canal` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidos` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sexo` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fnac` date NOT NULL,
  `nombre_canal` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `canal`
--

INSERT INTO `canal` (`id`, `email`, `roles`, `password`, `nombre`, `apellidos`, `sexo`, `fnac`, `nombre_canal`) VALUES
(1, 'aborrar@gmail.com', '[\"ROLE_USER\"]', '$argon2id$v=19$m=65536,t=4,p=1$Z3VQRlk2MDVSdDdqZjlzRQ$gVDaegdRrrCpKwUAV4rbQ42yx0kje3waAKiGqJPaqQU', 'Prueba', 'Canal A Borrar', 'H', '2020-01-01', 'Prueba');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentario`
--

CREATE TABLE `comentario` (
  `id` int(11) NOT NULL,
  `id_video_id` int(11) DEFAULT NULL,
  `canal_comentado_id` int(11) DEFAULT NULL,
  `canal_que_comenta_id` int(11) DEFAULT NULL,
  `contenido` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_comentario` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suscripcion`
--

CREATE TABLE `suscripcion` (
  `id` int(11) NOT NULL,
  `canal_al_que_suscribe_id` int(11) NOT NULL,
  `canal_que_suscribe_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `video`
--

CREATE TABLE `video` (
  `id` int(11) NOT NULL,
  `id_canal_id` int(11) NOT NULL,
  `id_categoria_id` int(11) NOT NULL,
  `titulo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duracion` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_publicacion` date NOT NULL,
  `mg` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `dislike` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `canal`
--
ALTER TABLE `canal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_E181FB59E7927C74` (`email`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_4B91E702791AECDB` (`id_video_id`),
  ADD KEY `IDX_4B91E702D2D578CC` (`canal_comentado_id`),
  ADD KEY `IDX_4B91E702F9912F5B` (`canal_que_comenta_id`);

--
-- Indices de la tabla `suscripcion`
--
ALTER TABLE `suscripcion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_497FA0CD9D2EB6` (`canal_al_que_suscribe_id`),
  ADD KEY `IDX_497FA0AC27494` (`canal_que_suscribe_id`);

--
-- Indices de la tabla `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CC7DA2C3800B7BB` (`id_canal_id`),
  ADD KEY `IDX_7CC7DA2C10560508` (`id_categoria_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `canal`
--
ALTER TABLE `canal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comentario`
--
ALTER TABLE `comentario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `suscripcion`
--
ALTER TABLE `suscripcion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `video`
--
ALTER TABLE `video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD CONSTRAINT `FK_4B91E702791AECDB` FOREIGN KEY (`id_video_id`) REFERENCES `video` (`id`),
  ADD CONSTRAINT `FK_4B91E702D2D578CC` FOREIGN KEY (`canal_comentado_id`) REFERENCES `canal` (`id`),
  ADD CONSTRAINT `FK_4B91E702F9912F5B` FOREIGN KEY (`canal_que_comenta_id`) REFERENCES `canal` (`id`);

--
-- Filtros para la tabla `suscripcion`
--
ALTER TABLE `suscripcion`
  ADD CONSTRAINT `FK_497FA0AC27494` FOREIGN KEY (`canal_que_suscribe_id`) REFERENCES `canal` (`id`),
  ADD CONSTRAINT `FK_497FA0CD9D2EB6` FOREIGN KEY (`canal_al_que_suscribe_id`) REFERENCES `canal` (`id`);

--
-- Filtros para la tabla `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `FK_7CC7DA2C10560508` FOREIGN KEY (`id_categoria_id`) REFERENCES `categoria` (`id`),
  ADD CONSTRAINT `FK_7CC7DA2C3800B7BB` FOREIGN KEY (`id_canal_id`) REFERENCES `canal` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
