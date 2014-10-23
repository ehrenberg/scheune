-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 23. Okt 2014 um 15:59
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
  `ErstelltAm` datetime NOT NULL,
  `GueltigBis` datetime DEFAULT NULL,
  `Aktiv` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Daten für Tabelle `abstimmung`
--

INSERT INTO `abstimmung` (`ID`, `Bezeichnung`, `ErstelltAm`, `GueltigBis`, `Aktiv`) VALUES
(4, 'Test', '0000-00-00 00:00:00', '2014-10-23 16:00:00', 1),
(6, 'Test 2', '2014-10-22 12:04:13', '2014-10-31 15:00:00', 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=90 ;

--
-- Daten für Tabelle `abstimmung_titel`
--

INSERT INTO `abstimmung_titel` (`ID`, `Abstimmung_ID`, `Stimmen`, `Name`) VALUES
(84, 6, 100, 'Vorschlag 1'),
(85, 4, 10, 'Test Tester'),
(86, 4, 65, 'Chirons Tide'),
(87, 4, 1, 'Bla bla Band'),
(88, 4, 33, 'Wir sind#s'),
(89, 4, 0, 'HeyhoRocknRoll');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `abstimmung_vorschlaege`
--

CREATE TABLE IF NOT EXISTS `abstimmung_vorschlaege` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Text` varchar(100) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `IP` varchar(15) NOT NULL,
  `ErstelltAm` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Daten für Tabelle `abstimmung_vorschlaege`
--

INSERT INTO `abstimmung_vorschlaege` (`ID`, `Text`, `Name`, `IP`, `ErstelltAm`) VALUES
(11, 'Vorschlag 1', NULL, '127.0.0.1', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `ID` int(5) NOT NULL AUTO_INCREMENT,
  `playerText` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `settings`
--

INSERT INTO `settings` (`ID`, `playerText`) VALUES
(1, 'Jin''s Blues Rock Lounge');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `templates`
--

CREATE TABLE IF NOT EXISTS `templates` (
  `ID` int(5) NOT NULL AUTO_INCREMENT,
  `FileName` varchar(50) NOT NULL,
  `Name` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `templates`
--

INSERT INTO `templates` (`ID`, `FileName`, `Name`) VALUES
(1, 'impressum.tpl', 'impressum'),
(2, 'info.tpl', 'info'),
(3, 'start.tpl', 'start');

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
(18, 'WunschHits inner Scheune - Hörer machen Programm', '2014-10-23 13:00:00', '2014-10-23 16:00:00'),
(17, 'Rock in''s Wochenende mit Ivo', '2014-10-24 16:00:00', '2014-10-24 19:00:00'),
(16, 'Day Of Classic Rock', '2014-10-23 09:00:00', '2014-10-23 19:00:00'),
(15, 'WunschHits inner Scheune - Hörer machen Programm', '2014-10-22 16:00:00', '2014-10-22 19:00:00'),
(14, 'Die BluesRock - Lounge mit Jin', '2014-10-21 20:00:00', '2014-10-21 22:00:00'),
(13, 'Die Scheune im MorgenRock', '2014-10-20 09:00:00', '2014-10-20 11:00:00'),
(19, 'Album der Woche  ( FAUN ), im Anschluß BON JOVI in Concert', '2014-10-26 19:00:00', '2014-10-26 22:00:00'),
(20, 'DeutschRock - Nacht in der Scheune', '2014-10-22 21:00:00', '2014-10-23 00:00:00'),
(21, 'RockScheune NachtSchicht (u.a. ab 23.oo Uhr A.R.PELL in Concert)', '2014-10-24 21:00:00', '2014-10-25 02:00:00'),
(22, 'Die RockScheune FunNight mit Mich aund Ivo', '2014-10-25 22:00:00', '2014-10-26 03:00:00');

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
