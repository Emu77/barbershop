<?php
$title = "Barbershop";
$base = "/barbershop/public";
require __DIR__ . "/../app/views/partials/header.php";
?>

<section class="p-5 rounded-4 hero">
  <h1 class="display-5 fw-bold mb-3">Bart- & Kopfrasur</h1>
  <p class="lead mb-4">
    Sauberer Schnitt, klare Konturen, entspannte Atmosphäre. Buche deinen Termin in wenigen Klicks.
  </p>
  <a class="btn btn-primary btn-lg" href="<?= $base ?>/booking.php">Jetzt Termin buchen</a>
  <a class="btn btn-outline-light btn-lg ms-2" href="<?= $base ?>/services.php">Leistungen ansehen</a>
</section>

<section class="mt-5">
  <h2 class="h3 mb-3">Beliebte Leistungen</h2>

  <div class="row g-3">
    <div class="col-md-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h3 class="h5">Bart-Rasur</h3>
          <p class="text-muted mb-0">Konturen, Pflege, Hot Towel.</p>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h3 class="h5">Kopfrasur</h3>
          <p class="text-muted mb-0">Präzise, glatt, professionell.</p>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h3 class="h5">Kombi</h3>
          <p class="text-muted mb-0">Bart + Kopf in einem Termin.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . "/../app/views/partials/footer.php"; ?>