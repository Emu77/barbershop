<?php
require __DIR__ . '/../../app/config/config.php';
require __DIR__ . '/../../app/auth.php';
auth_required('kunde');

$title = "Meine Termine – BarberShop";

// Termine des eingeloggten Kunden laden
try {
    $pdo   = getDB();
    $stmt  = $pdo->prepare("
        SELECT t.id, t.termin_datum, t.termin_uhrzeit, t.notiz, t.status, t.erstellt_am,
               l.name AS leistung, l.preis, l.dauer_min,
               m.anzeigename AS mitarbeiter
        FROM termine t
        JOIN leistungen l ON l.id = t.leistung_id
        JOIN mitarbeiter m ON m.id = t.mitarbeiter_id
        WHERE t.benutzer_id = :uid
        ORDER BY t.termin_datum DESC, t.termin_uhrzeit DESC
    ");
    $stmt->execute([':uid' => $_SESSION['benutzer_id']]);
    $termine = $stmt->fetchAll();
} catch (PDOException $e) {
    $termine = [];
    $dbFehler = htmlspecialchars($e->getMessage());
}

// Status-Badge Farben
$statusBadge = [
    'anfrage'       => 'warning',
    'bestaetigt'    => 'success',
    'abgeschlossen' => 'secondary',
    'storniert'     => 'danger',
];

function statusText(string $status): string
{
    return match (strtolower($status)) {
        'anfrage'       => 'Wartet auf Bestätigung',
        'bestaetigt'    => 'Bestätigt',
        'abgeschlossen' => 'Abgeschlossen',
        'storniert'     => 'Storniert',
        default         => ucfirst($status),
    };
}

require __DIR__ . '/../../app/views/partials/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="h3 mb-0">Hallo, <?= htmlspecialchars($_SESSION['vorname']) ?>!</h1>
    <p class="text-muted mb-0">Deine gebuchten Termine im Überblick.</p>
  </div>
  <div class="d-flex gap-2">
    <a href="<?= BASE_URL ?>/booking.php" class="btn btn-warning btn-sm fw-semibold">+ Neuer Termin</a>
    <a href="<?= BASE_URL ?>/logout.php"  class="btn btn-outline-secondary btn-sm">Abmelden</a>
  </div>
</div>

<?php if (isset($dbFehler)): ?>
  <div class="alert alert-danger">Datenbankfehler: <?= $dbFehler ?></div>
<?php elseif (empty($termine)): ?>
  <div class="card shadow-sm">
    <div class="card-body text-center py-5">
      <p class="text-muted mb-3">Du hast noch keine Termine gebucht.</p>
      <a href="<?= BASE_URL ?>/booking.php" class="btn btn-warning fw-semibold">Jetzt Termin buchen</a>
    </div>
  </div>
<?php else: ?>
  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
          <tr>
            <th>Datum</th>
            <th>Uhrzeit</th>
            <th>Leistung</th>
            <th>Mitarbeiter</th>
            <th>Dauer</th>
            <th>Preis</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($termine as $t): ?>
            <tr>
              <td><?= date('d.m.Y', strtotime($t['termin_datum'])) ?></td>
              <td><?= substr($t['termin_uhrzeit'], 0, 5) ?> Uhr</td>
              <td><?= htmlspecialchars($t['leistung']) ?></td>
              <td><?= htmlspecialchars($t['mitarbeiter']) ?></td>
              <td><?= $t['dauer_min'] ?> Min.</td>
              <td><?= number_format($t['preis'], 2, ',', '.') ?> €</td>
              <td>
                <span class="badge bg-<?= $statusBadge[$t['status']] ?? 'secondary' ?>">
                  <?= htmlspecialchars(statusText($t['status'])) ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>

<?php require __DIR__ . '/../../app/views/partials/footer.php'; ?>
