<?php
include_once __DIR__ . '/../src/components/header.php';
include_once __DIR__ . '/../src/components/footer.php';

create_header("Lib - Eroare mail neverificat");
?>
    <main class="section">
        <p class="is-size-4 has-text-black has-text-weight-semibold pb-2">
            Eroare 403! Verifică-ți adresa de mail pentru a avea acces la această funcție.
        </p>
        <p>Apasă <a href="/">aici</a> pentru a te întoarce la prima pagină.</p>
    </main>
<?php
create_footer();
?>