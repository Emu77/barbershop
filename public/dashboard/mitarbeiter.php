<?php
require __DIR__ . '/../../app/config/config.php';
require __DIR__ . '/../../app/auth.php';
auth_required(['mitarbeiter', 'admin']);

$title = "Dashboard – BarberShop";
$heute = date('Y-m-d');

// Status-Badge Farben
$statusBadge = [
    'anfrage'       => 'warning',
    'bestaetigt'    => 'success',
    'abgeschlossen' => 'secondary',
    'storniert'     => 'danger',
];

try {
    $pdo = getDB();

    // Heutigen Mitarbeiter-Datensatz laden (falls verknüpft)
    $stmtMa = $pdo->prepare("SELECT id, anzeigename FROM mitarbeiter WHERE benutzer_id = :uid LIMIT 1");
    $stmtMa->execute([':uid' => $_SESSION['benutzer_id']]);
    $meinMitarbeiter = $stmtMa->fetch();

    // Termine heute – alle (Admin) oder nur eigene (Mitarbeiter)
    if ($_SESSION['rolle'] === 'admin' || !$meinMitarbeiter) {
        $stmtHeute = $pdo->prepare("
            SELECT t.id, t.kunde_name, t.kunde_telefon, t.termin_uhrzeit, t.notiz, t.status,
                   l.name AS leistung, l.dauer_min,
                   m.anzeigename AS mitarbeiter
            FROM   termine t
            JOIN   leistungen  l ON l.id = t.leistung_id
            JOIN   mitarbeiter m ON m.id = t.mitarbeiter_id
            WHERE  t.termin_datum = :heute
              AND  t.status NOT IN ('storniert')
            ORDER  BY t.termin_uhrzeit ASC
        ");
        $stmtHeute->execute([':heute' => $heute]);
    } else {
        $stmtHeute = $pdo->prepare("
            SELECT t.id, t.kunde_name, t.kunde_telefon, t.termin_uhrzeit, t.notiz, t.status,
                   l.name AS leistung, l.dauer_min,
                   m.anzeigename AS mitarbeiter
            FROM   termine t
            JOIN   leistungen  l ON l.id = t.leistung_id
            JOIN   mitarbeiter m ON m.id = t.mitarbeiter_id
            WHERE  t.termin_datum    = :heute
              AND  t.mitarbeiter_id  = :mid
              AND  t.status NOT IN ('storniert')
            ORDER  BY t.termin_uhrzeit ASC
        ");
        $stmtHeute->execute([':heute' => $heute, ':mid' => $meinMitarbeiter['id']]);
    }
    $termineHeute = $stmtHeute->fetchAll();

    // Offene Anfragen (status = 'anfrage')
    $stmtAnfragen = $pdo->prepare("
        SELECT t.id, t.kunde_name, t.kunde_telefon, t.termin_datum, t.termin_uhrzeit,
               l.name AS leistung, m.anzeigename AS mitarbeiter
        FROM   termine t
        JOIN   leistungen  l ON l.id = t.leistung_id
        JOIN   mitarbeiter m ON m.id = t.mitarbeiter_id
        WHERE  t.status = 'anfrage'
        ORDER  BY t.termin_datum ASC, t.termin_uhrzeit ASC
        LIMIT 20
    ");
    $stmtAnfragen->execute();
    $offeneAnfragen = $stmtAnfragen->fetchAll();

} catch (PDOException $e) {
    $termineHeute   = [];
    $offeneAnfragen = [];
    $dbFehler = htmlspecialchars($e->getMessage());
}

// ── Status-Aktion (bestätigen / stornieren) ─────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['termin_id'], $_POST['neuer_status'])) {
    $erlaubteStatus = ['bestaetigt', 'abgeschlossen', 'storniert'];
    $neuerStatus    = $_POST['neuer_status'];
    $terminId       = (int)$_POST['termin_id'];

    if (in_array($neuerStatus, $erlaubteStatus, true) && $terminId > 0) {
        try {
            $upd = $pdo->prepare("UPDATE termine SET status = :status WHERE id = :id");
            $upd->execute([':status' => $neuerStatus, ':id' => $terminId]);
        } catch (PDOException) {}
    }
    header('Location: ' . BASE_URL . '/dashboard/mitarbeiter.php');
    exit;
}

require __DIR__ . '/../../app/views/partials/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="h3 mb-0">
      <?= $_SESSION['rolle'] === 'admin' ? 'Admin-Dashboard' : 'Mein Dashboard' ?>
    </h1>
    <p class="text-muted mb-0">
      Hallo, <?= htmlspecialchars($_SESSION['vorname']) ?>! –
      <?= date('l, d. F Y', strtotime($heute)) ?>
    </p>
  </div>
  <a href="<?= BASE_URL ?>/logout.php" class="btn btn-outline-secondary btn-sm">Abmelden</a>
</div>

<?php if (isset($dbFehler)): ?>
  <div class="alert alert-danger">Datenbankfehler: <?= $dbFehler ?></div>
<?php endif; ?>

<!-- ── Heutige Termine ────────────────────────────────────────────────── -->
<h2 class="h5 mb-3">
  Termine heute
  <span class="badge bg-warning text-dark ms-1"><?= count($termineHeute) ?></span>
</h2>

<?php if (empty($termineHeute)): ?>
  <div class="alert alert-light border">Heute keine Termine eingetragen.</div>
<?php else: ?>
  <div class="card shadow-sm mb-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
          <tr>
            <th>Uhrzeit</th>
            <th>Kunde</th>
            <th>Telefon</th>
            <th>Leistung</th>
            <?php if ($_SESSION['rolle'] === 'admin'): ?>
              <th>Mitarbeiter</th>
            <?php endif; ?>
            <th>Status</th>
            <th>Aktion</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($termineHeute as $t): ?>
            <tr>
              <td class="fw-semibold"><?= substr($t['termin_uhrzeit'], 0, 5) ?> Uhr</td>
              <td><?= htmlspecialchars($t['kunde_name']) ?></td>
              <td><a href="tel:<?= htmlspecialchars($t['kunde_telefon']) ?>"><?= htmlspecialchars($t['kunde_telefon']) ?></a></td>
              <td><?= htmlspecialchars($t['leistung']) ?> <span class="text-muted small">(<?= $t['dauer_min'] ?> Min.)</span></td>
              <?php if ($_SESSION['rolle'] === 'admin'): ?>
                <td><?= htmlspecialchars($t['mitarbeiter']) ?></td>
              <?php endif; ?>
              <td>
                <span class="badge bg-<?= $statusBadge[$t['status']] ?? 'secondary' ?>">
                  <?= ucfirst($t['status']) ?>
                </span>
              </td>
              <td>
                <form method="post" class="d-flex gap-1">
                  <input type="hidden" name="termin_id" value="<?= $t['id'] ?>">
                  <?php if ($t['status'] === 'anfrage'): ?>
                    <button name="neuer_status" value="bestaetigt"
                            class="btn btn-success btn-sm">✓ Bestätigen</button>
                  <?php endif; ?>
                  <?php if ($t['status'] === 'bestaetigt'): ?>
                    <button name="neuer_status" value="abgeschlossen"
                            class="btn btn-secondary btn-sm">✓ Erledigt</button>
                  <?php endif; ?>
                  <?php if (in_array($t['status'], ['anfrage','bestaetigt'])): ?>
                    <button name="neuer_status" value="storniert"
                            class="btn btn-outline-danger btn-sm"
                            onclick="return confirm('Termin stornieren?')">✕</button>
                  <?php endif; ?>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>

<!-- ── Offene Anfragen ────────────────────────────────────────────────── -->
<h2 class="h5 mb-3">
  Offene Anfragen
  <span class="badge bg-warning text-dark ms-1"><?= count($offeneAnfragen) ?></span>
</h2>

<?php if (empty($offeneAnfragen)): ?>
  <div class="alert alert-light border">Keine offenen Anfragen.</div>
<?php else: ?>
  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
          <tr>
            <th>Datum</th>
            <th>Uhrzeit</th>
            <th>Kunde</th>
            <th>Telefon</th>
            <th>Leistung</th>
            <th>Mitarbeiter</th>
            <th>Aktion</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($offeneAnfragen as $t): ?>
            <tr>
              <td><?= date('d.m.Y', strtotime($t['termin_datum'])) ?></td>
              <td><?= substr($t['termin_uhrzeit'], 0, 5) ?> Uhr</td>
              <td><?= htmlspecialchars($t['kunde_name']) ?></td>
              <td><a href="tel:<?= htmlspecialchars($t['kunde_telefon']) ?>"><?= htmlspecialchars($t['kunde_telefon']) ?></a></td>
              <td><?= htmlspecialchars($t['leistung']) ?></td>
              <td><?= htmlspecialchars($t['mitarbeiter']) ?></td>
              <td>
                <form method="post" class="d-flex gap-1">
                  <input type="hidden" name="termin_id" value="<?= $t['id'] ?>">
                  <button name="neuer_status" value="bestaetigt"
                          class="btn btn-success btn-sm">✓ Bestätigen</button>
                  <button name="neuer_status" value="storniert"
                          class="btn btn-outline-danger btn-sm"
                          onclick="return confirm('Termin stornieren?')">✕</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>

<?php require __DIR__ . '/../../app/views/partials/footer.php'; ?>
