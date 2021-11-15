<?php
    include 'commons.php';
    include 'database.php';

    connect_database();

    page_header("Lib - Descriere proiect");
    echo '
    <main class="section">
        <div class="block">
            <h2 class="subtitle">Descriere proiect</h2>
            <p>Lib este un website de administrare a unei biblioteci.</p>
        </div>
        <div class="block">
            <h2 class="subtitle">Diagramă entiate-relație</h2>
            <img src="/images/diagrama-entitate-relatie.jpg" alt="Diagramă entiate relație">
        </div>
    </main>
    ';
    page_footer();
?>