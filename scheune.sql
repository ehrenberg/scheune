-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 20. Okt 2014 um 23:01
-- Server Version: 5.6.17
-- PHP-Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `scheune`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `abstimmung`
--

CREATE TABLE IF NOT EXISTS `abstimmung` (
  `ID` int(5) NOT NULL AUTO_INCREMENT,
  `Bezeichnung` varchar(100) NOT NULL,
  `ErstelltAm` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `GueltigBis` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `abstimmung`
--

INSERT INTO `abstimmung` (`ID`, `Bezeichnung`, `ErstelltAm`, `GueltigBis`) VALUES
(2, 'Wer ist der Beste?', '2014-10-20 20:15:46', '2014-10-24 00:00:00'),
(3, 'Wer ist der Beste 2 ?', '2014-10-20 20:27:51', '2014-10-20 20:27:51');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `abstimmung_ip`
--

CREATE TABLE IF NOT EXISTS `abstimmung_ip` (
  `ID` int(5) NOT NULL AUTO_INCREMENT,
  `Abstimmung_ID` int(5) NOT NULL,
  `IP` varchar(15) NOT NULL,
  `Pos` tinyint(1) NOT NULL,
  `Neg` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Titel_ID` (`Abstimmung_ID`),
  KEY `Abstimmung_ID` (`Abstimmung_ID`),
  KEY `Abstimmung_ID_2` (`Abstimmung_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Daten für Tabelle `abstimmung_ip`
--

INSERT INTO `abstimmung_ip` (`ID`, `Abstimmung_ID`, `IP`, `Pos`, `Neg`) VALUES
(18, 2, '::1', 1, 0),
(19, 2, '::1', 0, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `abstimmung_titel`
--

CREATE TABLE IF NOT EXISTS `abstimmung_titel` (
  `ID` int(5) NOT NULL AUTO_INCREMENT,
  `Abstimmung_ID` int(5) NOT NULL,
  `Stimmen` int(10) NOT NULL,
  `Name` varchar(150) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Abstimmung_ID` (`Abstimmung_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=82 ;

--
-- Daten für Tabelle `abstimmung_titel`
--

INSERT INTO `abstimmung_titel` (`ID`, `Abstimmung_ID`, `Stimmen`, `Name`) VALUES
(77, 2, 0, 'Titel 1'),
(78, 2, 19, 'Titel 2'),
(79, 2, 0, 'Titel 3'),
(80, 2, 0, 'Titel 4'),
(81, 2, 0, 'Titel 5');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=71 ;

--
-- Daten für Tabelle `termine`
--

INSERT INTO `termine` (`ID`, `Text`, `Von`, `Bis`) VALUES
(69, 'Text 19.10.2014', '2014-10-19 21:30:00', '2014-10-19 23:30:00'),
(68, 'Text 18.10.2014', '2014-10-18 20:00:00', '2014-10-18 22:00:00'),
(67, 'Text 17.10.2014', '2014-10-17 21:00:00', '2014-10-17 22:30:00'),
(66, 'Text 16.10.2014', '2014-10-16 22:00:00', '2014-10-16 23:00:00'),
(65, 'Text 15.10.2014', '2014-10-15 18:00:00', '2014-10-15 23:00:00'),
(64, 'Text 14.10.2014', '2014-10-14 19:00:00', '2014-10-14 22:00:00'),
(63, 'Text 13.10.2014', '2014-10-13 20:30:00', '2014-10-13 21:30:00'),
(62, 'Text 26.10.2014', '2014-10-26 23:00:00', '2014-10-27 03:00:00'),
(61, 'Text 25.10.2014', '2014-10-25 18:00:00', '2014-10-25 22:00:00'),
(60, 'Text 24.10.2014', '2014-10-24 23:00:00', '2014-10-25 00:00:00'),
(59, 'Text 23.10.2014', '2014-10-23 19:00:00', '2014-10-23 23:00:00'),
(58, 'Text 22.10.2014', '2014-10-22 20:00:00', '2014-10-22 22:00:00'),
(57, 'Text 21.10.2014', '2014-10-21 21:00:00', '2014-10-21 23:00:00'),
(56, 'Text 20.10.2014', '2014-10-20 20:30:00', '2014-10-20 21:00:00'),
(70, 'Test Doppelt', '2014-10-23 08:00:00', '2014-10-23 10:00:00');

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `abstimmung_ip`
--
ALTER TABLE `abstimmung_ip`
  ADD CONSTRAINT `abstimmung_ip_ibfk_1` FOREIGN KEY (`Abstimmung_ID`) REFERENCES `abstimmung` (`ID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `abstimmung_titel`
--
ALTER TABLE `abstimmung_titel`
  ADD CONSTRAINT `abstimmung_titel_ibfk_1` FOREIGN KEY (`Abstimmung_ID`) REFERENCES `abstimmung` (`ID`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
