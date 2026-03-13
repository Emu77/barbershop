-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 13. Mrz 2026 um 12:00
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `barbershop`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `benutzer`
--

CREATE TABLE `benutzer` (
  `id` int(10) UNSIGNED NOT NULL,
  `rolle_id` tinyint(3) UNSIGNED NOT NULL DEFAULT 3,
  `vorname` varchar(80) NOT NULL,
  `nachname` varchar(80) NOT NULL,
  `email` varchar(180) NOT NULL,
  `passwort_hash` varchar(255) NOT NULL,
  `erstellt_am` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `benutzer`
--

INSERT INTO `benutzer` (`id`, `rolle_id`, `vorname`, `nachname`, `email`, `passwort_hash`, `erstellt_am`) VALUES
(1, 3, 'emu', 'kronas', 'emu@kronisoft.net', '$2y$12$hPj9ybHm9.D/H1BeHkR0zuyPd2BtT.jCpPk56z8wGLIIAA5HzlaQG', '2026-03-12 09:21:12');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `leistungen`
--

CREATE TABLE `leistungen` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `beschreibung` text DEFAULT NULL,
  `preis` decimal(8,2) NOT NULL,
  `dauer_min` smallint(6) NOT NULL,
  `aktiv` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `leistungen`
--

INSERT INTO `leistungen` (`id`, `name`, `beschreibung`, `preis`, `dauer_min`, `aktiv`) VALUES
(1, 'Bart-Rasur', 'Konturen, Pflege, Hot Towel.', 25.00, 20, 1),
(2, 'Kopfrasur', 'Präzise, glatt, professionell.', 30.00, 30, 1),
(3, 'Kombi', 'Bart + Kopf in einem Termin.', 50.00, 50, 1),
(4, 'Konturen', 'Saubere Linien & Nacken.', 15.00, 10, 1),
(5, 'Bartpflege', 'Öl, Balm, Styling.', 10.00, 10, 1),
(6, 'Augenbrauen', 'Trim & Form.', 8.00, 5, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mitarbeiter`
--

CREATE TABLE `mitarbeiter` (
  `id` int(10) UNSIGNED NOT NULL,
  `benutzer_id` int(10) UNSIGNED DEFAULT NULL,
  `anzeigename` varchar(80) NOT NULL,
  `rolle_beschr` varchar(120) DEFAULT NULL,
  `arbeitszeiten` varchar(120) DEFAULT NULL,
  `aktiv` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `mitarbeiter`
--

INSERT INTO `mitarbeiter` (`id`, `benutzer_id`, `anzeigename`, `rolle_beschr`, `arbeitszeiten`, `aktiv`) VALUES
(1, NULL, 'Alex', 'Bart-Spezialist', 'Mo–Fr 10–18 Uhr', 1),
(2, NULL, 'Sam', 'Kopfrasur & Fade', 'Di–Sa 12–20 Uhr', 1),
(3, NULL, 'Mika', 'Kombi & Pflege', 'Mo–Do 09–17 Uhr', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rollen`
--

CREATE TABLE `rollen` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `bezeichnung` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `rollen`
--

INSERT INTO `rollen` (`id`, `bezeichnung`) VALUES
(1, 'admin'),
(3, 'kunde'),
(2, 'mitarbeiter');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `termine`
--

CREATE TABLE `termine` (
  `id` int(10) UNSIGNED NOT NULL,
  `benutzer_id` int(10) UNSIGNED DEFAULT NULL,
  `kunde_name` varchar(160) NOT NULL,
  `kunde_telefon` varchar(30) NOT NULL,
  `mitarbeiter_id` int(10) UNSIGNED NOT NULL,
  `leistung_id` int(10) UNSIGNED NOT NULL,
  `termin_datum` date NOT NULL,
  `termin_uhrzeit` time NOT NULL,
  `notiz` text DEFAULT NULL,
  `status` enum('anfrage','bestaetigt','abgeschlossen','storniert') NOT NULL DEFAULT 'anfrage',
  `erstellt_am` datetime NOT NULL DEFAULT current_timestamp(),
  `aktualisiert_am` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `termine`
--

INSERT INTO `termine` (`id`, `benutzer_id`, `kunde_name`, `kunde_telefon`, `mitarbeiter_id`, `leistung_id`, `termin_datum`, `termin_uhrzeit`, `notiz`, `status`, `erstellt_am`, `aktualisiert_am`) VALUES
(1, 1, 'emu kronas', '017663268334', 2, 3, '2026-03-13', '12:00:00', '', 'anfrage', '2026-03-12 10:38:22', '2026-03-12 10:38:22');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_email` (`email`),
  ADD KEY `fk_benutzer_rolle` (`rolle_id`);

--
-- Indizes für die Tabelle `leistungen`
--
ALTER TABLE `leistungen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_name` (`name`);

--
-- Indizes für die Tabelle `mitarbeiter`
--
ALTER TABLE `mitarbeiter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mitarbeiter_benutzer` (`benutzer_id`);

--
-- Indizes für die Tabelle `rollen`
--
ALTER TABLE `rollen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_bezeichnung` (`bezeichnung`);

--
-- Indizes für die Tabelle `termine`
--
ALTER TABLE `termine`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_mitarbeiter_slot` (`mitarbeiter_id`,`termin_datum`,`termin_uhrzeit`),
  ADD KEY `fk_termin_benutzer` (`benutzer_id`),
  ADD KEY `fk_termin_leistung` (`leistung_id`),
  ADD KEY `idx_datum_mitarbeiter` (`termin_datum`,`mitarbeiter_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `leistungen`
--
ALTER TABLE `leistungen`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `mitarbeiter`
--
ALTER TABLE `mitarbeiter`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `rollen`
--
ALTER TABLE `rollen`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `termine`
--
ALTER TABLE `termine`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  ADD CONSTRAINT `fk_benutzer_rolle` FOREIGN KEY (`rolle_id`) REFERENCES `rollen` (`id`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `mitarbeiter`
--
ALTER TABLE `mitarbeiter`
  ADD CONSTRAINT `fk_mitarbeiter_benutzer` FOREIGN KEY (`benutzer_id`) REFERENCES `benutzer` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints der Tabelle `termine`
--
ALTER TABLE `termine`
  ADD CONSTRAINT `fk_termin_benutzer` FOREIGN KEY (`benutzer_id`) REFERENCES `benutzer` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_termin_leistung` FOREIGN KEY (`leistung_id`) REFERENCES `leistungen` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_termin_mitarbeiter` FOREIGN KEY (`mitarbeiter_id`) REFERENCES `mitarbeiter` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
