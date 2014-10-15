-- phpMyAdmin SQL Dump
-- version 4.0.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 15. Okt 2014 um 19:31
-- Server Version: 5.1.41-3ubuntu12
-- PHP-Version: 5.3.2-1ubuntu4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `usr_web307_1`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `termine`
--

CREATE TABLE IF NOT EXISTS `termine` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `Text` varchar(250) COLLATE latin1_general_ci DEFAULT NULL,
  `Von` datetime NOT NULL,
  `Bis` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=12 ;

--
-- Daten für Tabelle `termine`
--

INSERT INTO `termine` (`ID`, `Text`, `Von`, `Bis`) VALUES
(1, 'Scheune LoveLine', '2014-08-18 20:00:00', '2014-08-18 22:30:00'),
(2, 'Wunschhits', '2014-08-20 17:00:00', '2014-08-20 20:00:00'),
(3, 'Jin''s BluesRockLounge', '2014-08-20 20:00:00', '2014-08-20 22:00:00'),
(4, 'Rock in#s WE', '2014-08-22 15:00:00', '2014-08-22 19:00:00'),
(5, 'Scheune Nachtschicht', '2014-08-22 22:00:00', '2014-08-23 01:00:00'),
(6, 'Mukke-Bingo', '2014-08-23 15:00:00', '2014-08-23 18:00:00'),
(8, 'Scheune FunNight', '2014-08-23 22:00:00', '2014-08-24 02:00:00'),
(9, 'Album der Woche', '2014-08-24 18:00:00', '2014-08-24 19:00:00'),
(10, 'Test_Davor', '2014-08-17 13:00:00', '2014-08-17 15:00:00'),
(11, 'TestDanach', '2014-08-24 18:00:00', '2014-08-24 20:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
