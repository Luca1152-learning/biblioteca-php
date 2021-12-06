<?php
    include 'protected/commons.php';
    include 'protected/database.php';

    connect_database();

    page_header("Lib - Biblioteca ta");
?>

    <main class="section">
        <div class="container">
            Descrierea proiectului se găsește <strong><a href="/descriere-proiect.php">aici</a></strong>.
        </div>
    </main>

<?php
    page_footer();
?>