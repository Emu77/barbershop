<?php
require __DIR__ . '/../app/config/config.php';
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$title       = "Termin buchen – Barbershop";
$success      = false;
$errors       = [];
$eingeloggt   = !empty($_SESSION["benutzer_id"]);
$benutzer_id  = $eingeloggt ? (int)$_SESSION["benutzer_id"] : null;

// ── Stammdaten aus der Datenbank laden ─────────────────────────────────────
try {
    $pdo        = getDB();
    $leistungen = $pdo->query("SELECT id, name, preis, dauer_min FROM leistungen WHERE aktiv = 1 ORDER BY id")->fetchAll();
    $mitarbeiter = $pdo->query("SELECT id, anzeigename, rolle_beschr, arbeitszeiten FROM mitarbeiter WHERE aktiv = 1 ORDER BY id")->fetchAll();
} catch (PDOException $e) {
    $errors[] = "Datenbankfehler beim Laden der Daten: " . htmlspecialchars($e->getMessage());
    $leistungen  = [];
    $mitarbeiter = [];
}

// Schnelle Lookup-Maps für die Validierung (id => true)
$gueltigeLeistungen  = array_column($leistungen,  null, 'id');
$gueltigeMitarbeiter = array_column($mitarbeiter, null, 'id');

// ── Formular wurde abgeschickt ──────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF-Schutz
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $errors[] = "Ungültiges Sicherheits-Token. Bitte Seite neu laden.";
    } else {
        // Eingaben bereinigen
        $name          = trim($_POST['name']          ?? '');
        $telefon       = trim($_POST['telefon']       ?? '');
        $leistung_id   = (int)($_POST['leistung_id']  ?? 0);
        $mitarbeiter_id = (int)($_POST['mitarbeiter_id'] ?? 0);
        $datum         = trim($_POST['datum']         ?? '');
        $uhrzeit       = trim($_POST['uhrzeit']       ?? '');
        $notiz         = trim($_POST['notiz']         ?? '');
        // Eingeloggten Benutzer automatisch verknüpfen
        $benutzer_id   = !empty($_SESSION['benutzer_id']) ? (int)$_SESSION['benutzer_id'] : null;

        // Validierung
        if ($name === '')    $errors[] = "Name ist erforderlich.";
        if ($telefon === '') $errors[] = "Telefonnummer ist erforderlich.";
        if (!isset($gueltigeLeistungen[$leistung_id]))   $errors[] = "Bitte eine gültige Leistung wählen.";
        if (!isset($gueltigeMitarbeiter[$mitarbeiter_id])) $errors[] = "Bitte einen gültigen Mitarbeiter wählen.";
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $datum))   $errors[] = "Ungültiges Datum.";
        if (!preg_match('/^\d{2}:\d{2}$/', $uhrzeit))        $errors[] = "Ungültige Uhrzeit.";

        // Doppelbuchung prüfen
        if (empty($errors)) {
            $check = $pdo->prepare("
                SELECT id FROM termine
                WHERE mitarbeiter_id = :mid
                  AND termin_datum   = :datum
                  AND termin_uhrzeit = :uhrzeit
                LIMIT 1
            ");
            $check->execute([':mid' => $mitarbeiter_id, ':datum' => $datum, ':uhrzeit' => $uhrzeit]);
            if ($check->fetch()) {
                $errors[] = "Dieser Zeitslot ist bereits vergeben. Bitte wähle einen anderen Termin.";
            }
        }

        // In Datenbank speichern
        if (empty($errors)) {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO termine
                        (benutzer_id, kunde_name, kunde_telefon, mitarbeiter_id, leistung_id,
                         termin_datum, termin_uhrzeit, notiz, status)
                    VALUES
                        (:benutzer_id, :name, :telefon, :mitarbeiter_id, :leistung_id,
                         :datum, :uhrzeit, :notiz, 'anfrage')
                ");
                $stmt->execute([
                    ':benutzer_id'    => $benutzer_id,
                    ':name'           => $name,
                    ':telefon'        => $telefon,
                    ':mitarbeiter_id' => $mitarbeiter_id,
                    ':leistung_id'    => $leistung_id,
                    ':datum'          => $datum,
                    ':uhrzeit'        => $uhrzeit,
                    ':notiz'          => $notiz,
                ]);

                $success = true;
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                // Formulardaten nach Erfolg leeren
                $_POST = [];

            } catch (PDOException $e) {
                $errors[] = "Datenbankfehler: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}

require __DIR__ . "/../app/views/partials/header.php";
?>

<h1 class="h3 mb-4">Termin buchen</h1>

<?php if (!$eingeloggt): ?>
  <div class="alert alert-warning d-flex align-items-start gap-3">
    <span style="font-size:1.5rem">ℹ️</span>
    <div>
      <strong>Bitte melde dich an, bevor du einen Termin buchst.</strong><br>
      Nur so können wir den Termin deinem Konto zuordnen und du siehst ihn später in deinem Dashboard.
      <div class="mt-2">
        <a href="<?= BASE_URL ?>/login.php" class="btn btn-warning btn-sm fw-semibold me-2">Jetzt anmelden</a>
        <a href="<?= BASE_URL ?>/register.php" class="btn btn-outline-dark btn-sm">Neu hier? Registrieren</a>
      </div>
    </div>
  </div>
<?php else: ?>
  <div class="alert alert-success py-2">
    Eingeloggt als <strong><?= htmlspecialchars($_SESSION['vorname'] . ' ' . $_SESSION['nachname']) ?></strong>
    – dein Termin wird automatisch deinem Konto zugeordnet.
  </div>
<?php endif; ?>

<?php if ($success): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>✓ Termin erfolgreich angefragt!</strong>
    Wir melden uns zur Bestätigung per Telefon bei dir.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $err): ?>
        <li><?= htmlspecialchars($err) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="row g-4">

  <!-- ── Buchungsformular ── -->
  <div class="col-lg-7">
    <div class="card shadow-sm">
      <div class="card-body">
        <form method="post" action="booking.php">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
          <div class="row g-3">

            <!-- Name -->
            <div class="col-md-6">
              <label class="form-label">Name <span class="text-danger">*</span></label>
              <input class="form-control" name="name"
                     value="<?= htmlspecialchars($_POST['name'] ?? (isset($_SESSION['vorname']) ? $_SESSION['vorname'] . ' ' . $_SESSION['nachname'] : '')) ?>"
                     placeholder="Vor- und Nachname" required>
            </div>

            <!-- Telefon -->
            <div class="col-md-6">
              <label class="form-label">Telefon <span class="text-danger">*</span></label>
              <input class="form-control" name="telefon" placeholder="z. B. 0176 12345678"
                     value="<?= htmlspecialchars($_POST['telefon'] ?? '') ?>" required>
            </div>

            <!-- Leistung -->
            <div class="col-md-6">
              <label class="form-label">Leistung <span class="text-danger">*</span></label>
              <select class="form-select" name="leistung_id" required>
                <option value="" disabled <?= empty($_POST['leistung_id']) ? 'selected' : '' ?>>Bitte wählen…</option>
                <?php foreach ($leistungen as $l): ?>
                  <option value="<?= $l['id'] ?>"
                    <?= (((int)($_POST['leistung_id'] ?? 0)) === $l['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($l['name']) ?>
                    (<?= $l['dauer_min'] ?> Min. · <?= number_format($l['preis'], 2, ',', '.') ?> €)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Mitarbeiter -->
            <div class="col-md-6">
              <label class="form-label">Mitarbeiter <span class="text-danger">*</span></label>
              <select class="form-select" name="mitarbeiter_id" required>
                <option value="" disabled <?= empty($_POST['mitarbeiter_id']) ? 'selected' : '' ?>>Bitte wählen…</option>
                <?php foreach ($mitarbeiter as $m): ?>
                  <option value="<?= $m['id'] ?>"
                    <?= (((int)($_POST['mitarbeiter_id'] ?? 0)) === $m['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($m['anzeigename']) ?>
                    <?= $m['rolle_beschr'] ? '– ' . htmlspecialchars($m['rolle_beschr']) : '' ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Datum -->
            <div class="col-md-6">
              <label class="form-label">Datum <span class="text-danger">*</span></label>
              <input type="date" class="form-control" name="datum"
                     min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                     value="<?= htmlspecialchars($_POST['datum'] ?? '') ?>" required>
            </div>

            <!-- Uhrzeit -->
            <div class="col-md-6">
              <label class="form-label">Uhrzeit <span class="text-danger">*</span></label>
              <input type="time" class="form-control" name="uhrzeit"
                     min="10:00" max="19:30" step="1800"
                     value="<?= htmlspecialchars($_POST['uhrzeit'] ?? '') ?>" required>
            </div>

            <!-- Notiz -->
            <div class="col-12">
              <label class="form-label">Notiz (optional)</label>
              <textarea class="form-control" name="notiz" rows="3"
                        placeholder="Wünsche, Hinweise…"><?= htmlspecialchars($_POST['notiz'] ?? '') ?></textarea>
            </div>

            <!-- Absenden -->
            <div class="col-12">
              <button class="btn btn-warning fw-semibold w-100" type="submit">
                Termin anfragen
              </button>
              <p class="text-muted small mt-2 mb-0">
                <span class="text-danger">*</span> Pflichtfelder
              </p>
            </div>

          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ── Seitenleiste ── -->
  <div class="col-lg-5">
    <div class="card shadow-sm">
      <div class="card-body">
        <h2 class="h5">Kontakt & Öffnungszeiten</h2>
        <p class="text-muted mb-1">Musterstraße 1, 12345 Berlin</p>
        <p class="text-muted mb-0">Mo–Sa &nbsp;10:00 – 20:00 Uhr</p>
      </div>
    </div>

    <!-- Leistungsübersicht -->
    <?php if (!empty($leistungen)): ?>
    <div class="card shadow-sm mt-3">
      <div class="card-body">
        <h2 class="h5 mb-3">Unsere Leistungen</h2>
        <ul class="list-unstyled mb-0">
          <?php foreach ($leistungen as $l): ?>
            <li class="d-flex justify-content-between py-1 border-bottom">
              <span><?= htmlspecialchars($l['name']) ?></span>
              <span class="text-muted small">
                <?= $l['dauer_min'] ?> Min. &nbsp;|&nbsp;
                <?= number_format($l['preis'], 2, ',', '.') ?> €
              </span>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <?php endif; ?>

    <!-- Team -->
    <?php if (!empty($mitarbeiter)): ?>
    <div class="card shadow-sm mt-3">
      <div class="card-body">
        <h2 class="h5 mb-3">Unser Team</h2>
        <ul class="list-unstyled mb-0">
          <?php foreach ($mitarbeiter as $m): ?>
            <li class="py-1 border-bottom">
              <strong><?= htmlspecialchars($m['anzeigename']) ?></strong>
              <?php if ($m['rolle_beschr']): ?>
                <span class="text-muted small"> – <?= htmlspecialchars($m['rolle_beschr']) ?></span>
              <?php endif; ?>
              <?php if ($m['arbeitszeiten']): ?>
                <div class="text-muted small"><?= htmlspecialchars($m['arbeitszeiten']) ?></div>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <?php endif; ?>
  </div>

</div>

<?php require __DIR__ . "/../app/views/partials/footer.php"; ?>