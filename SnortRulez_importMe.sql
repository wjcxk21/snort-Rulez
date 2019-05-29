-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 29-05-2016 a las 11:38:19
-- Versión del servidor: 5.5.44-0ubuntu0.14.04.1
-- Versión de PHP: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `SnortRulez`
--
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customRules`
--

CREATE TABLE IF NOT EXISTS `customRules` (
  `idCustomRule` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID of customRules',
  `rule` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'context of rule',
  `sid` int(11) NOT NULL COMMENT 'SID',
  PRIMARY KEY (`idCustomRule`),
  UNIQUE KEY `sid` (`sid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `easyRules`
--

CREATE TABLE IF NOT EXISTS `easyRules` (
  `idEasyRule` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID of easyRules',
  `rule` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'rule, without sid',
  `sid` int(11) NOT NULL COMMENT 'SID',
  PRIMARY KEY (`idEasyRule`),
  UNIQUE KEY `sid` (`sid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=59 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) NOT NULL,
  `time` varchar(30) NOT NULL,
  KEY `index_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `testRules`
--

CREATE TABLE IF NOT EXISTS `testRules` (
  `idTestRule` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID of testRules',
  `rule` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'rule entity',
  PRIMARY KEY (`idTestRule`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=77 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

-- CREATE TABLE IF NOT EXISTS `users` (
--   `idUser` int(11) NOT NULL AUTO_INCREMENT,
--   `nombre` varchar(50) NOT NULL,
--   `apellido1` varchar(50) NOT NULL,
--   `apellido2` varchar(50) NOT NULL,
--   `email` varchar(50) NOT NULL,
--   `telefono` char(9) NOT NULL,
--   `usuario` varchar(50) NOT NULL,
--   `password` char(128) NOT NULL,
--   PRIMARY KEY (`idUser`),
--   UNIQUE KEY `usuario` (`usuario`)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

CREATE TABLE IF NOT EXISTS `users` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `fname1` varchar(50) NOT NULL,
  `fname2` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telno` char(9) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` char(128) NOT NULL,
  PRIMARY KEY (`idUser`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;
--
-- Volcado de datos para la tabla `users`
--

-- INSERT INTO `users` (`idUser`, `nombre`, `apellido1`, `apellido2`, `email`, `telefono`, `usuario`, `password`) VALUES
-- (1, 'Nikola', 'Tesla', '', 'elfutur@es.mio', '963141592', 'ntesla', '9d573f9cc2df610703a9b7a495f40c5d27fbdf56a65f999b180a6da39823a332fd15c3c30bd1c91d226c6432fa837f523062dccac3dcbd59b2babfa5656a3f6d');

INSERT INTO `users` (`idUser`, `name`, `fname1`, `fname2`, `email`, `telno`, `username`, `password`) VALUES
(1, 'Nikola', 'Tesla', '', 'elfutur@es.mio', '963141592', 'ntesla', '9d573f9cc2df610703a9b7a495f40c5d27fbdf56a65f999b180a6da39823a332fd15c3c30bd1c91d226c6432fa837f523062dccac3dcbd59b2babfa5656a3f6d');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD CONSTRAINT `login_attempts_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`idUser`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
