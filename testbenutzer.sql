-- ============================================================
--  Testbenutzer für die Barbershop-Datenbank
--  Passwörter mit PHP password_hash() (PASSWORD_BCRYPT) erstellt
--
--  Zugangsdaten:
--    admin@barbershop.de       →  Passwort: Admin1234!
--    alex@barbershop.de        →  Passwort: Mitarbeiter1!
--    kunde@beispiel.de         →  Passwort: Kunde1234!
-- ============================================================

INSERT INTO `benutzer` (`rolle_id`, `vorname`, `nachname`, `email`, `passwort_hash`) VALUES

-- Admin (rolle_id = 1)
(1, 'Admin', 'User',
 'admin@barbershop.de',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),

-- Mitarbeiter (rolle_id = 2)  →  verknüpft mit mitarbeiter.id = 1 (Alex)
(2, 'Alex', 'Müller',
 'alex@barbershop.de',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),

-- Kunde (rolle_id = 3)
(3, 'Max', 'Mustermann',
 'kunde@beispiel.de',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Mitarbeiter Alex mit dem Benutzer verknüpfen (passe die ID ggf. an)
-- UPDATE mitarbeiter SET benutzer_id = (SELECT id FROM benutzer WHERE email = 'alex@barbershop.de') WHERE anzeigename = 'Alex';

-- ============================================================
--  HINWEIS: Die obigen Hashes sind Platzhalter.
--  Echte Hashes mit folgendem PHP-Snippet erzeugen:
--
--  echo password_hash('DeinPasswort', PASSWORD_BCRYPT, ['cost' => 12]);
-- ============================================================
