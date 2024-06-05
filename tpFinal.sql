-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-06-2024 a las 21:08:09
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
-- Base de datos: `tpfinal`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `nombre`) VALUES
(1, 'Deportes'),
(2, 'Geografía'),
(3, 'Historia'),
(4, 'Ciencia'),
(5, 'Programación'),
(6, 'Cultura general');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pregunta`
--

CREATE TABLE `pregunta` (
  `id` int(11) NOT NULL,
  `texto` varchar(255) NOT NULL,
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pregunta`
--

INSERT INTO `pregunta` (`id`, `texto`, `id_categoria`) VALUES
(1, '¿Cuántos jugadores tiene un equipo de baloncesto en el campo?', 1),
(2, '¿Qué país ha ganado la mayor cantidad de Copas del Mundo de fútbol?', 1),
(3, '¿Cuál es el continente más grande del mundo?', 2),
(4, '¿Cuál es la capital de Francia?', 2),
(5, '¿En qué año comenzó la Segunda Guerra Mundial?', 3),
(6, '¿Qué antigua civilización construyó las pirámides de Giza?', 3),
(7, '¿Cuál es el elemento químico con el símbolo O ?', 4),
(8, '¿Qué científico formuló la teoría de la relatividad?', 4),
(9, '¿Qué compañía desarrolló el sistema operativo Windows?', 5),
(10, '¿Qué lenguaje de programación tiene un símbolo de elefante en su logo?', 5),
(11, '¿Cuál es la moneda oficial de la Unión Europea?', 6),
(12, '¿Qué bebida se obtiene al exprimir las uvas y fermentarlas?', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta`
--

CREATE TABLE `respuesta` (
  `texto` varchar(255) NOT NULL,
  `opcion` char(1) NOT NULL CHECK (`opcion` in ('A','B','C','D')),
  `es_correcta` tinyint(1) NOT NULL,
  `id_pregunta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `respuesta`
--

INSERT INTO `respuesta` (`texto`, `opcion`, `es_correcta`, `id_pregunta`) VALUES
('3', 'A', 0, 1),
('4', 'B', 0, 1),
('5', 'C', 1, 1),
('6', 'D', 0, 1),
('Brasil', 'A', 1, 2),
('Alemania', 'B', 0, 2),
('Argentina', 'C', 0, 2),
('Italia', 'D', 0, 2),
('África', 'A', 0, 3),
('Europa', 'B', 0, 3),
('Oceanía', 'C', 0, 3),
('Asia', 'D', 1, 3),
('Lyon', 'A', 0, 4),
('París', 'B', 1, 4),
('Marsella', 'C', 0, 4),
('Mónaco', 'D', 0, 4),
('1938', 'A', 0, 5),
('1941', 'B', 0, 5),
('1939', 'C', 1, 5),
('1945', 'D', 0, 5),
('Egipcia', 'A', 1, 6),
('Maya', 'B', 0, 6),
('Griega', 'C', 0, 6),
('Inca', 'D', 0, 6),
('Azufre', 'A', 0, 7),
('Oro', 'B', 0, 7),
('Paladio', 'C', 0, 7),
('Oxígeno', 'D', 1, 7),
('Isaac Newton', 'A', 0, 8),
('Albert Einstein', 'B', 1, 8),
('Marco Polo', 'C', 0, 8),
('Thomas Edison', 'D', 0, 8),
('Microsoft', 'A', 1, 9),
('Android', 'B', 0, 9),
('Linux', 'C', 0, 9),
('Apple', 'D', 0, 9),
('Java', 'A', 0, 10),
('Php', 'B', 1, 10),
('MySql', 'C', 0, 10),
('.Net', 'D', 0, 10),
('Franco', 'A', 0, 11),
('Yen', 'B', 0, 11),
('Euro', 'C', 1, 11),
('Dólar', 'D', 0, 11),
('Sidra', 'A', 0, 12),
('Cerveza', 'B', 0, 12),
('Licor', 'C', 0, 12),
('Vino', 'D', 1, 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `EMAIL_VALIDATED` tinyint(1) NOT NULL DEFAULT 0,
  `hash` varchar(255) NOT NULL,
  `profile_pic` longblob DEFAULT NULL,
  `birth_year` year(4) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`_id`, `username`, `password`, `email`, `name`, `surname`, `EMAIL_VALIDATED`, `hash`, `profile_pic`, `birth_year`, `gender`, `country`, `city`) VALUES
(17, 'julito', '1234', 'juuligarcia2208@gmail.com', 'Julian', 'Garcia', 1, '41f6fcf410fb86342af2e3311342c79c', NULL, NULL, NULL, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pregunta`
--
ALTER TABLE `pregunta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  ADD KEY `id_pregunta` (`id_pregunta`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pregunta`
--
ALTER TABLE `pregunta`
  ADD CONSTRAINT `pregunta_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id`);

--
-- Filtros para la tabla `respuesta`
--
ALTER TABLE `respuesta`
  ADD CONSTRAINT `respuesta_ibfk_1` FOREIGN KEY (`id_pregunta`) REFERENCES `pregunta` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
