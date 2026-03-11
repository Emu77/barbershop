<?php
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    define('BASE_URL', '/kronisoft/projekte/barbershop/public');
} else {
    define('BASE_URL', '/projekte/barbershop/public');
}

// Datenbank-Konfiguration
define('DB_HOST', 'localhost');
define('DB_NAME', 'barbershop');
define('DB_USER', 'root');       // ggf. anpassen
define('DB_PASS', '');           // ggf. anpassen
define('DB_CHARSET', 'utf8mb4');

/**
 * Gibt eine PDO-Datenbankverbindung zurück (Singleton).
 */
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST, DB_NAME, DB_CHARSET
        );
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    }
    return $pdo;
}