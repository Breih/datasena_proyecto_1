-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:8111
-- Tiempo de generación: 03-06-2025 a las 06:04:11
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
-- Base de datos: `datasenn_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `tipo_documento` enum('CC','TI','CE','Otro') NOT NULL,
  `numero_documento` varchar(20) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `rol_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id` int(11) NOT NULL,
  `tipo_documento` enum('NIT') NOT NULL,
  `numero_documento` varchar(20) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `numero_telefono` varchar(15) NOT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `confirmacion_correo` varchar(100) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `actividad_economica` varchar(100) NOT NULL,
  `rol_id` int(11) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas`
--

CREATE TABLE `programas` (
  `id` int(11) NOT NULL,
  `nombre_programa` varchar(150) NOT NULL,
  `codigo_programa` varchar(20) NOT NULL,
  `nivel_formacion` varchar(50) NOT NULL,
  `estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `id` int(11) NOT NULL,
  `numero_identidad` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_reporte` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `tipo_documento` varchar(10) NOT NULL,
  `numero_identidad` varchar(20) NOT NULL,
  `residencia` varchar(150) NOT NULL,
  `tipo_sangre` varchar(5) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `estado` enum('activo','inactivo') NOT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_completo`, `tipo_documento`, `numero_identidad`, `residencia`, `tipo_sangre`, `correo`, `telefono`, `contrasena`, `estado`, `fecha_creacion`) VALUES
(1, 'alejo', 'cc', '123', '123', 'AB-', 'f@gmail.com', '123', '[HASH]', 'activo', '2025-05-22 07:36:41'),
(2, 'seoop', 'cc', '12345', '123', 'AB-', 'fe@gmail.com', '123', '[HASH]', 'activo', '2025-05-22 07:40:15'),
(3, 'qw', 'cc', '12345674', '123', 'O+', 'fe@gmail.com', '123', '[HASH]', 'inactivo', '2025-05-22 07:43:14'),
(4, 'asd', 'ti', '789456', '1233rr', 'A-', 'rer4@gmail.com', '1234455', '[HASH]', 'inactivo', '2025-05-22 07:44:46'),
(5, 'asd', 'ti', '78945633', '1233rr', 'A-', 'rer4@gmail.com', '1234455', '[HASH]', 'inactivo', '2025-05-22 07:46:57'),
(6, 'sdjk', 'cc', '211', 'pl', 'O+', 'leyvadilan36@gmail.com', '3113350701', '$2y$10$UxhNkO9ygeucjJx1L7QYqOYYafd17Zkwy4x1jmNloS.51IY19U2Ne', 'activo', '2025-05-22 19:54:35'),
(7, 'yilet', 'cc', '1111', '1111', 'O+', 'yilet@gmail.com', '123', '$2y$10$6yUzzFcnmf5pUelqrrVq2OAHPee/IRtFIf053CkrL77NPZVLVLVnu', 'activo', '2025-05-22 23:05:22'),
(8, 'valentina z', 'cc', '4352', 'casa alejo', 'O+', 'valentian@gmail.com', '31235552', '$2y$10$uCTV5N/8WVz4QIlMfP3CCOk0Rx3WN7K5MJxTBJ.bsDuPEThCgNbTK', 'activo', '2025-05-22 23:08:46'),
(9, 'JUAN JOSE', 'cc', '0808', 'rololandia', 'O+', 'leyva@ghh', '3113350701', '$2y$10$kwgrHju0t9BX/HHiaw1e..l9YzFU65aTRWlqmMwIru775jTbfqCgG', 'activo', '2025-05-27 20:43:49'),
(10, 'sofia', 'cc', '4567', 'PEREIRA', 'AB-', 'sofia@gmail.com', '3108980485', '$2y$10$z4rYz5lqwx3s68HvnuDbq.L8.EVlXFPdwBzU3JCVPQC/wH1IxQtTC', 'inactivo', '2025-05-28 00:56:03');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_documento` (`numero_documento`),
  ADD UNIQUE KEY `nickname` (`nickname`),
  ADD UNIQUE KEY `correo_electronico` (`correo_electronico`),
  ADD KEY `rol_id` (`rol_id`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_documento` (`numero_documento`),
  ADD UNIQUE KEY `nickname` (`nickname`),
  ADD UNIQUE KEY `correo_electronico` (`correo_electronico`),
  ADD KEY `rol_id` (`rol_id`);

--
-- Indices de la tabla `programas`
--
ALTER TABLE `programas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_programa` (`codigo_programa`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_identidad` (`numero_identidad`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `programas`
--
ALTER TABLE `programas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reportes`
--
ALTER TABLE `reportes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`);

--
-- Filtros para la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD CONSTRAINT `empresa_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
