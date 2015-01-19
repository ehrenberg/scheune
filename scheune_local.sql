-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 19. Jan 2015 um 16:17
-- Server Version: 5.6.21
-- PHP-Version: 5.6.3

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
`ID` int(5) NOT NULL,
  `Bezeichnung` varchar(100) NOT NULL,
  `ErstelltAm` datetime NOT NULL,
  `GueltigBis` datetime DEFAULT NULL,
  `Aktiv` tinyint(1) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `abstimmung`
--

INSERT INTO `abstimmung` (`ID`, `Bezeichnung`, `ErstelltAm`, `GueltigBis`, `Aktiv`) VALUES
(10, 'Top 20', '2015-01-19 14:37:57', '1970-01-01 01:00:00', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `abstimmung_ip`
--

CREATE TABLE IF NOT EXISTS `abstimmung_ip` (
`ID` int(5) NOT NULL,
  `Abstimmung_ID` int(5) NOT NULL,
  `IP` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `abstimmung_titel`
--

CREATE TABLE IF NOT EXISTS `abstimmung_titel` (
`ID` int(5) NOT NULL,
  `Abstimmung_ID` int(5) NOT NULL,
  `Stimmen` int(10) NOT NULL,
  `Name` varchar(150) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `abstimmung_titel`
--

INSERT INTO `abstimmung_titel` (`ID`, `Abstimmung_ID`, `Stimmen`, `Name`) VALUES
(1, 10, 0, 'Test Titel 1'),
(2, 10, 0, 'Test Titel 2');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `abstimmung_vorschlaege`
--

CREATE TABLE IF NOT EXISTS `abstimmung_vorschlaege` (
`ID` int(11) NOT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `Text` varchar(100) NOT NULL,
  `IP` varchar(15) NOT NULL,
  `ErstelltAm` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `member`
--

CREATE TABLE IF NOT EXISTS `member` (
`ID` int(5) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` char(128) NOT NULL,
  `Salt` char(128) NOT NULL,
  `Activation` varchar(40) NOT NULL,
  `Reset_Hash` varchar(50) NOT NULL,
  `Online_Since` int(11) NOT NULL,
  `Online_Last` int(11) NOT NULL,
  `IsAdmin` tinyint(1) NOT NULL,
  `PicAvail` tinyint(1) NOT NULL,
  `Active` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `member`
--

INSERT INTO `member` (`ID`, `Username`, `Email`, `Password`, `Salt`, `Activation`, `Reset_Hash`, `Online_Since`, `Online_Last`, `IsAdmin`, `PicAvail`, `Active`) VALUES
(1, 'Bastian', 'baschtel101@gmail.com', '531587bed25e773d7433692a6a15692e3a089c32280b95c9c5196dbefa46142c381957441652e4f3d90cae0efa56b64014eaaa6f4c3a17bfa7dfe499c70a5d5a', '48fa009b52bbbad0f5861acab7fccb27792050d88317aacf3009ce23865b88ec067f214298728e84e2a295b08b694efb415d88807e78bcc7017e6423156d77b0', '', '', 1421673621, 1421673607, 1, 0, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `plugin`
--

CREATE TABLE IF NOT EXISTS `plugin` (
`ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Active` tinyint(1) NOT NULL,
  `Prio` int(5) NOT NULL,
  `DateCreate` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `plugin`
--

INSERT INTO `plugin` (`ID`, `Name`, `Active`, `Prio`, `DateCreate`) VALUES
(1, 'member_shoutbox', 0, 0, '0000-00-00 00:00:00'),
(2, 'member_start', 0, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `playerText` varchar(50) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `templates`
--

CREATE TABLE IF NOT EXISTS `templates` (
`ID` int(5) NOT NULL,
  `FileName` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `Name` varchar(50) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `templates`
--

INSERT INTO `templates` (`ID`, `FileName`, `Name`) VALUES
(1, 'start.tpl', 'start'),
(3, 'info.tpl', 'info'),
(4, 'impressum.tpl', 'impressum');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `termine`
--

CREATE TABLE IF NOT EXISTS `termine` (
`ID` int(10) NOT NULL,
  `Text` varchar(250) COLLATE latin1_general_ci DEFAULT NULL,
  `Von` datetime NOT NULL,
  `Bis` datetime NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `termine`
--

INSERT INTO `termine` (`ID`, `Text`, `Von`, `Bis`) VALUES
(1, 'Zeit1', '2015-01-18 10:00:00', '2015-01-18 12:00:00'),
(2, 'Zeit2', '2015-01-19 12:00:00', '2015-01-19 14:00:00'),
(3, 'Zeit3', '2015-01-20 08:00:00', '2015-01-20 15:00:00'),
(4, 'Zeit4', '2015-01-21 07:00:00', '2015-01-21 16:00:00'),
(5, 'Zeit5', '2015-01-22 15:00:00', '2015-01-22 20:00:00'),
(6, 'Zeit 01', '2015-01-25 08:00:00', '2015-01-25 12:00:00'),
(7, 'Zeit 02', '2015-01-26 11:00:00', '2015-01-26 15:00:00'),
(8, 'Zeit 03', '2015-01-27 19:00:00', '2015-01-27 23:00:00'),
(9, 'Zeit 04', '2015-01-28 22:00:00', '2015-01-29 03:00:00'),
(10, 'Zeit 05', '2015-01-29 14:00:00', '2015-01-29 15:00:00');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `abstimmung`
--
ALTER TABLE `abstimmung`
 ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `abstimmung_ip`
--
ALTER TABLE `abstimmung_ip`
 ADD PRIMARY KEY (`ID`), ADD KEY `Titel_ID` (`Abstimmung_ID`), ADD KEY `Abstimmung_ID` (`Abstimmung_ID`);

--
-- Indizes für die Tabelle `abstimmung_titel`
--
ALTER TABLE `abstimmung_titel`
 ADD PRIMARY KEY (`ID`), ADD KEY `Abstimmung_ID` (`Abstimmung_ID`);

--
-- Indizes für die Tabelle `abstimmung_vorschlaege`
--
ALTER TABLE `abstimmung_vorschlaege`
 ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `member`
--
ALTER TABLE `member`
 ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `plugin`
--
ALTER TABLE `plugin`
 ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `templates`
--
ALTER TABLE `templates`
 ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `termine`
--
ALTER TABLE `termine`
 ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `abstimmung`
--
ALTER TABLE `abstimmung`
MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT für Tabelle `abstimmung_ip`
--
ALTER TABLE `abstimmung_ip`
MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `abstimmung_titel`
--
ALTER TABLE `abstimmung_titel`
MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT für Tabelle `abstimmung_vorschlaege`
--
ALTER TABLE `abstimmung_vorschlaege`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `member`
--
ALTER TABLE `member`
MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT für Tabelle `plugin`
--
ALTER TABLE `plugin`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT für Tabelle `templates`
--
ALTER TABLE `templates`
MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `termine`
--
ALTER TABLE `termine`
MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
