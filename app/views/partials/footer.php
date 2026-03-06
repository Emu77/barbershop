<?php
// app/views/partials/footer.php
$base = "/barbershop/public";
?>
</main>

<footer class="bg-dark text-light py-4 mt-5">
  <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
    <div>© <?= date('Y') ?> BarberShop</div>
    <div class="small">
    <a class="text-decoration-none text-light" href="<?= $base ?>/impressum.php">Impressum</a>
    <span class="mx-2">|</span>
    <a class="text-decoration-none text-light" href="<?= $base ?>/datenschutz.php">Datenschutz</a>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $base ?>/assets/js/app.js"></script>
</body>
</html>