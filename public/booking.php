<?php
$title = "Termin buchen – Barbershop";
$base = "/barbershop/public";
require __DIR__ . "/../app/views/partials/header.php";

$services = ["Bart-Rasur", "Kopfrasur", "Kombi", "Konturen", "Bartpflege", "Augenbrauen"];
$staff    = ["Alex", "Sam", "Mika"];
?>

<h1 class="h3 mb-4">Termin buchen</h1>

<div class="row g-4">
  <div class="col-lg-7">
    <div class="card shadow-sm">
      <div class="card-body">
        <form method="post" action="#">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Name</label>
              <input class="form-control" name="name" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Telefon</label>
              <input class="form-control" name="phone" placeholder="z. B. 0176..." required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Leistung</label>
              <select class="form-select" name="service" required>
                <option value="" selected disabled>Bitte wählen…</option>
                <?php foreach ($services as $s): ?>
                  <option><?= htmlspecialchars($s) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Mitarbeiter</label>
              <select class="form-select" name="staff" required>
                <option value="" selected disabled>Bitte wählen…</option>
                <?php foreach ($staff as $m): ?>
                  <option><?= htmlspecialchars($m) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Datum</label>
              <input type="date" class="form-control" name="date" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Uhrzeit</label>
              <input type="time" class="form-control" name="time" required>
            </div>

            <div class="col-12">
              <label class="form-label">Notiz (optional)</label>
              <textarea class="form-control" name="note" rows="3" placeholder="Wünsche, Hinweise…"></textarea>
            </div>

            <div class="col-12">
              <button class="btn btn-warning fw-semibold w-100" type="submit">Termin anfragen</button>
              <div class="text-muted small mt-2">
                Nächster Schritt: Verfügbarkeiten automatisch prüfen & speichern.
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="card shadow-sm">
      <div class="card-body">
        <h2 class="h5">Infos</h2>
        <div class="text-muted">Musterstraße 1, 12345 Berlin</div>
        <div class="text-muted">Mo–Sa 10–20 Uhr</div>
      </div>
    </div>

    <div class="card shadow-sm mt-3">
      <div class="card-body">
        <h2 class="h5">Tipp</h2>
        <p class="text-muted mb-0">
          Später zeigen wir dir hier freie Slots (abhängig von Mitarbeiter, Dauer, Überschneidungen).
        </p>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . "/../app/views/partials/footer.php"; ?>