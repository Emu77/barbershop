<?php
<<<<<<< HEAD
$title = "Leistungen – Barbershop";
$base = "/barbershop/public";
=======
require __DIR__ . '/../app/config/config.php';
$title = "Leistungen – Barbershop";
>>>>>>> 0b8fdd1 (Initial upload from Windows path)
require __DIR__ . "/../app/views/partials/header.php";

$services = [
  ["Bart-Rasur", "Konturen, Pflege, Hot Towel.", 25, 20],
  ["Kopfrasur", "Präzise, glatt, professionell.", 30, 30],
  ["Kombi", "Bart + Kopf in einem Termin.", 50, 50],
  ["Konturen", "Saubere Linien & Nacken.", 15, 10],
  ["Bartpflege", "Öl, Balm, Styling.", 10, 10],
  ["Augenbrauen", "Trim & Form.", 8, 5],
];
?>

<div class="d-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0">Leistungen</h1>
<<<<<<< HEAD
  <a class="btn btn-outline-primary" href="<?= $base ?>/booking.php">Termin buchen</a>
=======
  <a class="btn btn-outline-primary" href="<?= BASE_URL ?>/booking.php">Termin buchen</a>
>>>>>>> 0b8fdd1 (Initial upload from Windows path)
</div>

<div class="row g-3">
  <?php foreach ($services as [$name,$desc,$price,$mins]): ?>
    <div class="col-md-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h2 class="h5 mb-2"><?= htmlspecialchars($name) ?></h2>
          <p class="text-muted mb-3"><?= htmlspecialchars($desc) ?></p>
          <div class="d-flex justify-content-between small">
            <span class="fw-semibold"><?= number_format($price, 0) ?> €</span>
            <span class="text-muted"><?= (int)$mins ?> Min</span>
          </div>
        </div>
        <div class="card-footer bg-white border-0 pt-0">
<<<<<<< HEAD
          <a class="btn btn-primary w-100" href="<?= $base ?>/booking.php">Diesen Service buchen</a>
=======
          <a class="btn btn-primary w-100" href="<?= BASE_URL ?>/booking.php">Diesen Service buchen</a>
>>>>>>> 0b8fdd1 (Initial upload from Windows path)
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php require __DIR__ . "/../app/views/partials/footer.php"; ?>