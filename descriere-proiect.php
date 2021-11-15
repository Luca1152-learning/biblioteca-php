<?php
    include 'commons.php';
    include 'database.php';

    connect_database();

    page_header("Lib - Descriere proiect");
    echo '
    <main class="section">
        <div class="content">
            <h2 class="subtitle">Descriere proiect</h2>
            <p>Lib este un website de administrare a unei biblioteci.</p>
            <p>
                Acesta poate fi accesat de <strong>membri</strong> cât și de <strong>administratori</strong>.
                 "Entitățile" aplicației sunt cărțile. În funcție de tipul de utilizator,
                se poate interacționa cu aplicația după cum urmează:
            </p>
            <ul>
                <li>
                    <strong>Utilizator nelogat</strong>
                    <ul>
                        <li>Poate privi toate cărțile disponibile în bibliotecă</li>
                        <li>Poate efectua căutări după diverse filtre (titlu, autor, etc.)</li>
                    </ul>
                </li>
                <li>
                    <strong>Membru (logat)</strong>
                    <ul>
                        <li>Toate abilitățile unui utilizator nelogat</li>
                        <li>Poate vedea numărul de cărți disponibile în bibliotecă pentru un anumit titlu</li>
                        <li>Poate împrumuta și returna cărți</li>
                    </ul>
                </li>
                <li>
                    <strong>Administrator</strong>
                    <ul>
                        <li>Toate abilitățile unui utilizator nelogat si unui membru logat</li>
                        <li>Poate adăuga, modifica și șterge cărțile din bibliotecă</li>
                        <li>Poate adăuga comentarii cărților individuale (cum ar fi starea lor)</li>
                    </ul>
                </li>
            </ul>
            <p>A se nota că se va face distincție între o <strong>carte</strong> și o <strong>copie disponibilă</strong> 
            a cărții. Astfel că o carte (cu un anumit titlu, scrisă de un anumit autor, etc.) poate avea mai multe copii 
            în bibliotecă. Acest lucru poate fi util pentru a urmări exact cine are ce carte, cât și a avea anumite
            comentarii pentru fiecare copie (poate o carte e într-o stare mai proasta; trebuie să știm starea pentru 
            a putea penaliza membrii ce returnează cărțile într-o stare mai rea).</p>
        </div class="content">
        <div class="content">
            <h2 class="subtitle">Diagramă entiate-relație</h2>
            <img src="/images/diagrama-entitate-relatie.jpg" alt="Diagramă entiate relație">
        </div>
    </main>
    ';
    page_footer();
?>