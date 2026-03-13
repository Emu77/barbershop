<?php
$current = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)); // z.B. index.php
if ($current === "" || $current === "public") $current = "index.php"; // Fallback
function navActive(string $file, string $current): string {
  return $file === $current ? " active" : "";
}
?>
<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title ?? "BarberShop") ?></title>

  <!-- Bootstrap (CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/index.php">BarberShop</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
        <a class="nav-link<?= navActive('index.php', $current) ?>" href="<?= BASE_URL ?>/index.php">Start</a>
        </li>
        <li class="nav-item">
        <a class="nav-link<?= navActive('services.php', $current) ?>" href="<?= BASE_URL ?>/services.php">Leistungen</a>
        </li>
        <li class="nav-item">
        <a class="nav-link<?= navActive('team.php', $current) ?>" href="<?= BASE_URL ?>/team.php">Team</a>
        </li>
        <li class="nav-item">
        <a class="nav-link<?= navActive('booking.php', $current) ?>" href="<?= BASE_URL ?>/booking.php">Termin buchen</a>
        </li>
        <?php if (!empty($_SESSION['benutzer_id'])): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <?= htmlspecialchars($_SESSION['vorname'] . ' ' . $_SESSION['nachname']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="<?= BASE_URL ?>/dashboard/kunde.php">
                📋 Meine Termine</a></li>
              <?php if (in_array($_SESSION['rolle'] ?? '', ['admin','mitarbeiter'])): ?>
              <li><a class="dropdown-item" href="<?= BASE_URL ?>/dashboard/mitarbeiter.php">
                🗓 Team-Dashboard</a></li>
              <?php endif; ?>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/logout.php">Abmelden</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link<?= navActive('register.php', $current) ?>" href="<?= BASE_URL ?>/register.php">Registrieren</a>
          </li>
          <li class="nav-item">
            <a class="nav-link<?= navActive('login.php', $current) ?>" href="<?= BASE_URL ?>/login.php">Login</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<main class="container my-4">