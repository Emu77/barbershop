<?php
<<<<<<< HEAD
$title = "Team – Barbershop";
$base = "/barbershop/public";
=======
require __DIR__ . '/../app/config/config.php';
$title = "Team – Barbershop";
>>>>>>> 0b8fdd1 (Initial upload from Windows path)
require __DIR__ . "/../app/views/partials/header.php";

$team = [
  ["Alex", "Bart-Spezialist", "Mo–Fr 10–18 Uhr"],
  ["Sam", "Kopfrasur & Fade", "Di–Sa 12–20 Uhr"],
  ["Mika", "Kombi & Pflege", "Mo–Do 09–17 Uhr"],
];
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0">Team</h1>
  <a class="btn btn-outline-primary" href="<?= $base ?>/booking.php">Termin buchen</a>
</div>

<div class="row g-3">
  <?php foreach ($team as [$name,$role,$hours]): ?>
    <div class="col-md-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-secondary-subtle" style="width:56px;height:56px;"></div>
            <div>
              <h2 class="h5 mb-0"><?= htmlspecialchars($name) ?></h2>
              <div class="text-muted"><?= htmlspecialchars($role) ?></div>
            </div>
          </div>
          <hr>
          <div class="small text-muted">Arbeitszeiten</div>
          <div class="fw-semibold"><?= htmlspecialchars($hours) ?></div>
        </div>
        <div class="card-footer bg-white border-0 pt-0">
          <a class="btn btn-primary w-100" href="<?= $base ?>/booking.php">Bei <?= htmlspecialchars($name) ?> buchen</a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php require __DIR__ . "/../app/views/partials/footer.php"; ?>