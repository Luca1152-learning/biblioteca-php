<?php

session_start();

function create_header($title)
{ ?>
    <!DOCTYPE html>
    <html lang="ro" class="container is-max-desktop">

    <head>
        <!-- Bulma -->
        <link rel="stylesheet" href="/stylesheets/bulma.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Iconify -->
        <script src="https://code.iconify.design/2/2.1.0/iconify.min.js"></script>

        <title><?php echo $title ?></title>
    </head>

<body>
<header class="section" style="padding-bottom: 0;">
    <nav class="navbar is-transparent">
        <div class="navbar-brand">
            <a class="navbar-item" href="/">
                        <span class="iconify" data-icon="bx:bxs-book"
                              style="color: #07d1b2; width: 2.2rem; height: auto; padding-top: 4px; margin-right: 6px"></span>
                <h1 class="title is-4">Lib</h1>
            </a>
        </div>

        <div id="navbarExampleTransparentExample" class="navbar-menu">
            <div class="navbar-start">
                <a class="navbar-item" href="/carti.php">
                    Cărți
                </a>
                <div class="navbar-item has-dropdown is-hoverable">
                    <p class="navbar-link">
                        Despre
                    </p>
                    <div class="navbar-dropdown is-boxed">
                        <a class="navbar-item" href="/descriere-proiect.php">
                            Descriere proiect
                        </a>
                    </div>
                </div>
            </div>

            <div class="navbar-end">
                <div class="navbar-item">
                    <div class="field is-grouped">
                        <p class="control">
                            <?php
                            // Utilizatorul e logat => afiseaza "Iesire [nume]"
                            if (isset($_SESSION) && isset($_SESSION["user_id"])) { ?>
                                <a href="/iesire.php" class="button is-text is-rounded">
                                    <?php echo 'Ieșire ' . $_SESSION["user_first_name"] . ' ' . $_SESSION["user_last_name"] ?>
                                </a>
                                <?php
                                // Utiliatorul nu e logat => afiseaza butonul de inregistrare
                            } else { ?>
                                <a class="button is-primary is-rounded" href="/inregistrare.php">
                                    <span>Înregistrare</span>
                                </a>
                            <?php } ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
    <?php
}

?>