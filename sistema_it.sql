-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-06-2025 a las 18:59:51
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_it`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `backups`
--

CREATE TABLE `backups` (
  `id` int(11) NOT NULL,
  `equipo_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `frecuencia` enum('diario','semanal','mensual') NOT NULL,
  `estado` enum('exitoso','fallido') NOT NULL,
  `observaciones` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `cargo` varchar(100) NOT NULL,
  `gerencia` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `acceso_internet` tinyint(1) DEFAULT 1,
  `acceso_videoconf` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `nombre`, `apellido`, `cargo`, `gerencia`, `email`, `telefono`, `acceso_internet`, `acceso_videoconf`) VALUES
(1, 'Juan', 'Pérez', 'Analista de Sistemas', 'Tecnología', 'juan.perez@empresa.com', '3624001234', 1, 1),
(2, 'María', 'Gómez', 'Soporte Técnico', 'Tecnología', 'maria.gomez@empresa.com', '3624005678', 1, 0),
(3, 'Carlos', 'López', 'Jefe de Redes', 'Infraestructura', 'carlos.lopez@empresa.com', '3624009012', 1, 1),
(4, 'Laura', 'Martínez', 'Administrativa', 'Recursos Humanos', 'laura.martinez@empresa.com', '3624003456', 0, 0),
(5, 'Pedro', 'Rodríguez', 'Responsable de Backups', 'Seguridad Informática', 'pedro.rodriguez@empresa.com', '3624007890', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

CREATE TABLE `equipos` (
  `id` int(11) NOT NULL,
  `tipo` enum('PC','notebook','servidor') NOT NULL,
  `marca` varchar(100) NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `procesador` varchar(100) NOT NULL,
  `ram_gb` int(11) NOT NULL,
  `almacenamiento_gb` int(11) NOT NULL,
  `sistema_operativo` varchar(100) NOT NULL,
  `anio_fabricacion` int(11) DEFAULT NULL,
  `empleado_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `equipos`
--

INSERT INTO `equipos` (`id`, `tipo`, `marca`, `modelo`, `procesador`, `ram_gb`, `almacenamiento_gb`, `sistema_operativo`, `anio_fabricacion`, `empleado_id`) VALUES
(1, 'PC', 'Dell', 'OptiPlex 3080', 'Intel Core i5', 8, 256, 'Windows 10', NULL, NULL),
(2, 'notebook', 'HP', 'EliteBook 840', 'Intel Core i7', 16, 512, 'Windows 11', NULL, NULL),
(3, 'servidor', 'Lenovo', 'ThinkSystem ST50', 'Intel Xeon E', 32, 1024, 'Ubuntu Server 22.04', NULL, NULL),
(4, 'PC', 'dell', 'zi321', 'i3-2100', 8, 480, 'Windows 10', NULL, NULL),
(5, 'PC', 'dell', 'zi321', 'i3-2100', 8, 480, 'Windows 10', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimientos`
--

CREATE TABLE `mantenimientos` (
  `id_mantenimiento` int(11) NOT NULL,
  `id_equipos` int(11) NOT NULL,
  `anio_fabricacion` year(4) NOT NULL,
  `fecha_ultimo_mantenimiento` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha_proximo_mantenimiento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `mantenimientos`
--

INSERT INTO `mantenimientos` (`id_mantenimiento`, `id_equipos`, `anio_fabricacion`, `fecha_ultimo_mantenimiento`, `observaciones`, `fecha_proximo_mantenimiento`) VALUES
(1, 1, '2021', '2024-03-01', 'Limpieza interna completa', '2024-09-01'),
(2, 2, '2022', '2024-04-10', 'Cambio de batería y limpieza', '2024-10-10'),
(3, 3, '2023', '2024-05-15', 'Verificación de ventilación', '2024-11-15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimiento_preventivo`
--

CREATE TABLE `mantenimiento_preventivo` (
  `id` int(11) NOT NULL,
  `equipo_id` int(11) NOT NULL,
  `fecha_proximo` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `renovaciones`
--

CREATE TABLE `renovaciones` (
  `id` int(11) NOT NULL,
  `equipo_id` int(11) DEFAULT NULL,
  `fecha_renovacion` date DEFAULT NULL,
  `estado` enum('Pendiente','En Proceso','Completado') DEFAULT 'Pendiente',
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `backups`
--
ALTER TABLE `backups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipo_id` (`equipo_id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_empleado` (`empleado_id`);

--
-- Indices de la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  ADD PRIMARY KEY (`id_mantenimiento`),
  ADD KEY `id_equipos` (`id_equipos`);

--
-- Indices de la tabla `mantenimiento_preventivo`
--
ALTER TABLE `mantenimiento_preventivo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipo_id` (`equipo_id`);

--
-- Indices de la tabla `renovaciones`
--
ALTER TABLE `renovaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipo_id` (`equipo_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `backups`
--
ALTER TABLE `backups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  MODIFY `id_mantenimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mantenimiento_preventivo`
--
ALTER TABLE `mantenimiento_preventivo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `renovaciones`
--
ALTER TABLE `renovaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `backups`
--
ALTER TABLE `backups`
  ADD CONSTRAINT `backups_ibfk_1` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`);

--
-- Filtros para la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD CONSTRAINT `fk_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`);

--
-- Filtros para la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  ADD CONSTRAINT `mantenimientos_ibfk_1` FOREIGN KEY (`id_equipos`) REFERENCES `equipos` (`id`);

--
-- Filtros para la tabla `mantenimiento_preventivo`
--
ALTER TABLE `mantenimiento_preventivo`
  ADD CONSTRAINT `mantenimiento_preventivo_ibfk_1` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `renovaciones`
--
ALTER TABLE `renovaciones`
  ADD CONSTRAINT `renovaciones_ibfk_1` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
