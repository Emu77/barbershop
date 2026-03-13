<?php
require __DIR__ . '/../app/config/config.php';
session_start();

// Bereits eingeloggt? → weiterleiten
if (!empty($_SESSION['benutzer_id'])) {
    header('Location: ' . BASE_URL . '/dashboard/');
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$title  = "Login – BarberShop";
$fehler = '';

// ── Login-Verarbeitung ──────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $fehler = "Ungültiges Sicherheits-Token. Bitte Seite neu laden.";
    } else {
        $email    = trim($_POST['email'] ?? '');
        $passwort = trim($_POST['passwort'] ?? '');

        if ($email === '' || $passwort === '') {
            $fehler = "Bitte E-Mail und Passwort eingeben.";
        } else {
            try {
                $pdo  = getDB();
                $stmt = $pdo->prepare("
                    SELECT b.id, b.vorname, b.nachname, b.email, b.passwort_hash, b.rolle_id,
                           r.bezeichnung AS rolle
                    FROM benutzer b
                    JOIN rollen r ON r.id = b.rolle_id
                    WHERE b.email = :email
                    LIMIT 1
                ");
                $stmt->execute([':email' => $email]);
                $benutzer = $stmt->fetch();

                if ($benutzer && password_verify($passwort, $benutzer['passwort_hash'])) {
                    // Session regenerieren gegen Session-Fixation
                    session_regenerate_id(true);

                    $_SESSION['benutzer_id'] = $benutzer['id'];
                    $_SESSION['vorname']     = $benutzer['vorname'];
                    $_SESSION['nachname']    = $benutzer['nachname'];
                    $_SESSION['email']       = $benutzer['email'];
                    $_SESSION['rolle']       = $benutzer['rolle'];       // admin | mitarbeiter | kunde
                    $_SESSION['rolle_id']    = $benutzer['rolle_id'];

                    header('Location: ' . BASE_URL . '/dashboard/');
                    exit;
                } else {
                    // Absichtlich gleiche Fehlermeldung (kein User-Enumeration)
                    $fehler = "E-Mail oder Passwort ist falsch.";
                }
            } catch (PDOException $e) {
                $fehler = "Datenbankfehler: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}

require __DIR__ . "/../app/views/partials/header.php";
?>

<div class="row justify-content-center">
  <div class="col-md-7 col-lg-5">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h1 class="h4 mb-2">Anmelden</h1>
        <p class="text-muted mb-4">Melde dich an, um deine Termine zu verwalten.</p>

        <?php if ($fehler !== ''): ?>
          <div class="alert alert-danger py-2"><?= htmlspecialchars($fehler) ?></div>
        <?php endif; ?>

        <form method="post" action="login.php">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

          <div class="mb-3">
            <label class="form-label">E-Mail</label>
            <input type="email" name="email" class="form-control"
                   placeholder="name@beispiel.de"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autofocus>
          </div>

          <div class="mb-3">
            <label class="form-label">Passwort</label>
            <input type="password" name="passwort" class="form-control" placeholder="••••••••" required>
          </div>

          <button class="btn btn-warning fw-semibold w-100 mt-1" type="submit">Anmelden</button>
        </form>
      </div>
    </div>

    <div class="text-center mt-3 small">
      Noch kein Konto?
      <a class="text-decoration-none fw-semibold" href="<?= BASE_URL ?>/register.php">Jetzt registrieren</a>
    </div>
    <div class="text-center mt-2">
      <a class="text-decoration-none text-muted small" href="<?= BASE_URL ?>/index.php">← Zurück zur Startseite</a>
    </div>
  </div>
</div>

<?php require __DIR__ . "/../app/views/partials/footer.php"; ?>
