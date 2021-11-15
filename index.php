<?php
    include 'commons.php';
    include 'database.php';

    connect_database();

    page_header("Lib - Biblioteca ta");
    echo '
    <main class="section">
        <div class="container">
            Descrierea proiectului se găsește <strong><a href="/descriere-proiect.php">aici</a></strong>.
        </div>
    </main>
    ';
    page_footer();
?>