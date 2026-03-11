<?php
require __DIR__ . '/../../app/config/config.php';
require __DIR__ . '/../../app/auth.php';
auth_required();

// Weiterleitung je nach Rolle
switch ($_SESSION['rolle']) {
    case 'admin':
    case 'mitarbeiter':
        header('Location: ' . BASE_URL . '/dashboard/mitarbeiter.php');
        break;
    default:
        header('Location: ' . BASE_URL . '/dashboard/kunde.php');
        break;
}
exit;
