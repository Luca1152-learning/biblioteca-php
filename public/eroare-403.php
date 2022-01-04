<?php
include_once __DIR__ . '/../src/components/header.php';
include_once __DIR__ . '/../src/components/footer.php';

create_header("Lib - Eroare 404");
?>
    <main class="section">
        <p class="is-size-4 has-text-black has-text-weight-semibold pb-2">
            Eroare 403! Nu ai acces la această pagină.
        </p>
        <p>Apasă <a href="/">aici</a> pentru a te întoarce la prima pagină.</p>
    </main>
<?php
create_footer();
?>