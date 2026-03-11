<?php
/**
 * Auth-Helper
 * Einbinden mit: require __DIR__ . '/../app/auth.php';
 * Optional Rolle prüfen: auth_required('admin') oder auth_required(['admin','mitarbeiter'])
 */

function auth_required(string|array $rollen = []): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['benutzer_id'])) {
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }

    if (!empty($rollen)) {
        $erlaubt = (array)$rollen;
        if (!in_array($_SESSION['rolle'], $erlaubt, true)) {
            http_response_code(403);
            // Einfache 403-Seite ausgeben
            echo '<!doctype html><html lang="de"><head><meta charset="utf-8">
                  <title>Kein Zugriff</title>
                  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
                  </head><body class="d-flex align-items-center justify-content-center" style="min-height:100vh">
                  <div class="text-center">
                    <h1 class="display-4">403</h1>
                    <p class="text-muted">Du hast keinen Zugriff auf diese Seite.</p>
                    <a href="' . BASE_URL . '/dashboard/" class="btn btn-warning">Zum Dashboard</a>
                  </div></body></html>';
            exit;
        }
    }
}
