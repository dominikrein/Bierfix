-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 24. Nov 2019 um 18:07
-- Server-Version: 10.4.8-MariaDB
-- PHP-Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `bierfix`
--
CREATE DATABASE IF NOT EXISTS `bierfix` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `bierfix`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `artikel`
--

DROP TABLE IF EXISTS `artikel`;
CREATE TABLE IF NOT EXISTS `artikel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bezeichnung` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `details` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `typ` int(11) NOT NULL,
  `preis` decimal(10,0) NOT NULL,
  `farbe` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `artikel_fk0` (`typ`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `artikel_typen`
--

DROP TABLE IF EXISTS `artikel_typen`;
CREATE TABLE IF NOT EXISTS `artikel_typen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bezeichnung` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bedienungen`
--

DROP TABLE IF EXISTS `bedienungen`;
CREATE TABLE IF NOT EXISTS `bedienungen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mac` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bestellte_artikel`
--

DROP TABLE IF EXISTS `bestellte_artikel`;
CREATE TABLE IF NOT EXISTS `bestellte_artikel` (
  `bestellung_id` int(11) NOT NULL,
  `artikel_id` int(11) NOT NULL,
  `bestellte_anzahl` int(11) NOT NULL,
  KEY `bestellte_artikel_fk0` (`bestellung_id`),
  KEY `bestellte_artikel_fk1` (`artikel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bestellungen`
--

DROP TABLE IF EXISTS `bestellungen`;
CREATE TABLE IF NOT EXISTS `bestellungen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tischnummer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bediener_id` int(11) NOT NULL,
  `zeitstempel` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bestellungen_fk0` (`bediener_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `einstellungen`
--

DROP TABLE IF EXISTS `einstellungen`;
CREATE TABLE IF NOT EXISTS `einstellungen` (
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `artikel`
--
ALTER TABLE `artikel`
  ADD CONSTRAINT `artikel_fk0` FOREIGN KEY (`typ`) REFERENCES `artikel_typen` (`id`);

--
-- Constraints der Tabelle `bestellte_artikel`
--
ALTER TABLE `bestellte_artikel`
  ADD CONSTRAINT `bestellte_artikel_fk0` FOREIGN KEY (`bestellung_id`) REFERENCES `bestellungen` (`id`),
  ADD CONSTRAINT `bestellte_artikel_fk1` FOREIGN KEY (`artikel_id`) REFERENCES `artikel` (`id`);

--
-- Constraints der Tabelle `bestellungen`
--
ALTER TABLE `bestellungen`
  ADD CONSTRAINT `bestellungen_fk0` FOREIGN KEY (`bediener_id`) REFERENCES `bedienungen` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
