<?php
require __DIR__ . '/../app/config/config.php';

$pdo = getDB();

$hashes = [
    'admin@barbershop.de' => password_hash('Admin1234!',    PASSWORD_BCRYPT, ['cost'=>12]),
    'alex@barbershop.de'  => password_hash('Mitarbeiter1!', PASSWORD_BCRYPT, ['cost'=>12]),
    'kunde@beispiel.de'   => password_hash('Kunde1234!',    PASSWORD_BCRYPT, ['cost'=>12]),
];

foreach ($hashes as $email => $hash) {
    $stmt = $pdo->prepare("UPDATE benutzer SET passwort_hash = :hash WHERE email = :email");
    $stmt->execute([':hash' => $hash, ':email' => $email]);
    echo "✓ $email aktualisiert<br>";
}

echo '<br><strong>Fertig! Diese Datei jetzt löschen.</strong>';