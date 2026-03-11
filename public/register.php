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

$title   = "Registrieren – BarberShop";
$success = false;
$fehler  = [];

// ── Formular verarbeiten ────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $fehler[] = "Ungültiges Sicherheits-Token. Bitte Seite neu laden.";
    } else {
        $vorname  = trim($_POST['vorname']   ?? '');
        $nachname = trim($_POST['nachname']  ?? '');
        $email    = trim($_POST['email']     ?? '');
        $passwort = trim($_POST['passwort']  ?? '');
        $confirm  = trim($_POST['passwort2'] ?? '');

        // Validierung
        if ($vorname === '')  $fehler[] = "Vorname ist erforderlich.";
        if ($nachname === '') $fehler[] = "Nachname ist erforderlich.";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $fehler[] = "Ungültige E-Mail-Adresse.";
        if (strlen($passwort) < 8) $fehler[] = "Passwort muss mindestens 8 Zeichen lang sein.";
        if ($passwort !== $confirm)  $fehler[] = "Passwörter stimmen nicht überein.";

        if (empty($fehler)) {
            try {
                $pdo = getDB();

                // E-Mail bereits vergeben?
                $check = $pdo->prepare("SELECT id FROM benutzer WHERE email = :email LIMIT 1");
                $check->execute([':email' => $email]);
                if ($check->fetch()) {
                    $fehler[] = "Diese E-Mail-Adresse ist bereits registriert.";
                } else {
                    $hash = password_hash($passwort, PASSWORD_BCRYPT, ['cost' => 12]);
                    $stmt = $pdo->prepare("
                        INSERT INTO benutzer (rolle_id, vorname, nachname, email, passwort_hash)
                        VALUES (3, :vorname, :nachname, :email, :hash)
                    ");
                    $stmt->execute([
                        ':vorname'  => $vorname,
                        ':nachname' => $nachname,
                        ':email'    => $email,
                        ':hash'     => $hash,
                    ]);

                    $success = true;
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    $_POST = [];
                }
            } catch (PDOException $e) {
                $fehler[] = "Datenbankfehler: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}

require __DIR__ . "/../app/views/partials/header.php";
?>

<div class="row justify-content-center">
  <div class="col-md-8 col-lg-6">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h1 class="h4 mb-1">Konto erstellen</h1>
        <p class="text-muted mb-4">Registriere dich, um Termine zu buchen und zu verwalten.</p>

        <?php if ($success): ?>
          <div class="alert alert-success">
            <strong>Registrierung erfolgreich!</strong>
            Du kannst dich jetzt <a href="<?= BASE_URL ?>/login.php" class="alert-link">anmelden</a>.
          </div>
        <?php endif; ?>

        <?php if (!empty($fehler)): ?>
          <div class="alert alert-danger">
            <ul class="mb-0">
              <?php foreach ($fehler as $f): ?>
                <li><?= htmlspecialchars($f) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="post" action="register.php">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
          <div class="row g-3">

            <div class="col-md-6">
              <label class="form-label">Vorname <span class="text-danger">*</span></label>
              <input class="form-control" name="vorname"
                     value="<?= htmlspecialchars($_POST['vorname'] ?? '') ?>"
                     placeholder="Max" required autofocus>
            </div>

            <div class="col-md-6">
              <label class="form-label">Nachname <span class="text-danger">*</span></label>
              <input class="form-control" name="nachname"
                     value="<?= htmlspecialchars($_POST['nachname'] ?? '') ?>"
                     placeholder="Mustermann" required>
            </div>

            <div class="col-12">
              <label class="form-label">E-Mail <span class="text-danger">*</span></label>
              <input type="email" class="form-control" name="email"
                     value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                     placeholder="name@beispiel.de" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Passwort <span class="text-danger">*</span></label>
              <input type="password" class="form-control" name="passwort"
                     placeholder="Mindestens 8 Zeichen" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Passwort wiederholen <span class="text-danger">*</span></label>
              <input type="password" class="form-control" name="passwort2"
                     placeholder="••••••••" required>
            </div>

            <div class="col-12">
              <button class="btn btn-warning fw-semibold w-100" type="submit">
                Konto erstellen
              </button>
            </div>

          </div>
        </form>
        <?php endif; ?>

      </div>
    </div>

    <div class="text-center mt-3 small">
      Bereits registriert?
      <a href="<?= BASE_URL ?>/login.php" class="text-decoration-none fw-semibold">Jetzt anmelden</a>
    </div>
  </div>
</div>

<?php require __DIR__ . "/../app/views/partials/footer.php"; ?>
