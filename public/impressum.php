<?php
$title = "Impressum – Barbershop";
$base = "/barbershop/public";
require __DIR__ . "/../app/views/partials/header.php";
?>

<h1 class="h3 mb-4">Impressum</h1>

<div class="card shadow-sm">
  <div class="card-body">
    <p class="text-muted mb-4">
      Bitte ersetze die Platzhalter (Name, Adresse, Kontakt, Registerdaten) durch deine echten Angaben.
    </p>

    <h2 class="h5">Angaben gemäß § 5 TMG</h2>
    <p>
      <strong>Barbershop Mustername</strong><br>
      Musterstraße 1<br>
      12345 Berlin<br>
      Deutschland
    </p>

    <h2 class="h5 mt-4">Kontakt</h2>
    <p>
      Telefon: +49 (0) 30 1234567<br>
      E-Mail: kontakt@barbershop.de
    </p>

    <h2 class="h5 mt-4">Vertreten durch</h2>
    <p>Max Mustermann</p>

    <h2 class="h5 mt-4">Umsatzsteuer-ID</h2>
    <p class="mb-0">
      Umsatzsteuer-Identifikationsnummer gemäß § 27 a Umsatzsteuergesetz:<br>
      DE123456789
    </p>

    <hr class="my-4">

    <h2 class="h5">Haftung für Inhalte</h2>
    <p class="text-muted">
      Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen
      verantwortlich. Nach §§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht verpflichtet, übermittelte oder
      gespeicherte fremde Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige Tätigkeit
      hinweisen. Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den allgemeinen Gesetzen
      bleiben hiervon unberührt.
    </p>

    <h2 class="h5">Haftung für Links</h2>
    <p class="text-muted">
      Unser Angebot enthält Links zu externen Websites Dritter, auf deren Inhalte wir keinen Einfluss haben.
      Deshalb können wir für diese fremden Inhalte auch keine Gewähr übernehmen. Für die Inhalte der verlinkten Seiten ist
      stets der jeweilige Anbieter oder Betreiber der Seiten verantwortlich.
    </p>

    <h2 class="h5">Urheberrecht</h2>
    <p class="text-muted mb-0">
      Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht.
      Beiträge Dritter sind als solche gekennzeichnet. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der
      Verwertung außerhalb der Grenzen des Urheberrechts bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw.
      Erstellers.
    </p>
  </div>
</div>

<?php require __DIR__ . "/../app/views/partials/footer.php"; ?>