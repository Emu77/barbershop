<?php
$title = "Login – Barbershop";
$base = "/barbershop/public";
require __DIR__ . "/../app/views/partials/header.php";
?>

<div class="row justify-content-center">
  <div class="col-md-7 col-lg-5">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h1 class="h4 mb-2">Login</h1>
        <p class="text-muted mb-4">Melde dich an, um Termine und Einstellungen zu verwalten.</p>

        <form method="post" action="#">
          <div class="mb-3">
            <label class="form-label">E-Mail</label>
            <input type="email" name="email" class="form-control" placeholder="name@barbershop.de" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Passwort</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
              <label class="form-check-label" for="remember">Angemeldet bleiben</label>
            </div>
            <a class="small text-decoration-none" href="#">Passwort vergessen?</a>
          </div>

          <button class="btn btn-primary w-100" type="submit">Anmelden</button>

          <div class="text-muted small mt-3">
            Demo-Hinweis: Später prüfen wir Benutzer + Rollen (Admin/Mitarbeiter).
          </div>
        </form>
      </div>
    </div>

    <div class="text-center mt-3">
      <a class="text-decoration-none" href="<?= $base ?>/index.php">← Zurück zur Startseite</a>
    </div>
  </div>
</div>

<?php require __DIR__ . "/../app/views/partials/footer.php"; ?>