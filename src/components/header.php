<?php
include_once __DIR__ . "/../utils/security/SecurityHelper.php";

session_start();

function create_header($title)
{ ?>
    <!DOCTYPE html>
    <html lang="ro" class="container is-max-desktop">

    <head>
        <meta charset="UTF-8">

        <!-- Buefy -->
        <link rel="stylesheet" href="/stylesheets/buefy/buefy-build.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@5.8.55/css/materialdesignicons.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">

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
                    <?php if (SecurityHelper::is_librarian() || SecurityHelper::is_admin()) { ?>
                        <div class="navbar-start">
                            <div class="navbar-item has-dropdown is-hoverable">
                                <p class="navbar-link">
                                    Dashboard
                                </p>
                                <div class="navbar-dropdown is-boxed">
                                    <?php if (SecurityHelper::is_admin()) { ?>
                                        <a class="navbar-item" href="/dashboard.php?meniu=utilizatori">Utilizatori</a>
                                    <?php } ?>
                                    <a class="navbar-item" href="/dashboard.php?meniu=carti">Cărți</a>
                                    <a class="navbar-item" href="/dashboard.php?meniu=autori">Autori</a>
                                    <a class="navbar-item" href="/dashboard.php?meniu=publisheri">Publisheri</a>
                                    <a class="navbar-item" href="/dashboard.php?meniu=categorii">Categorii</a>
                                    <a class="navbar-item" href="/dashboard.php?meniu=copii">Copii</a>
                                    <a class="navbar-item" href="/dashboard.php?meniu=imprumuturi">Împrumuturi</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="navbar-end">
                    <div class="navbar-item">
                        <div class="field is-grouped">
                            <p class="control">
                                <?php
                                // Utilizatorul e logat => afiseaza "Iesire [nume]"
                                if (SecurityHelper::is_logged_in()) { ?>
                                    <a href="/iesire.php" class="button is-text is-rounded">
                                        <?php echo 'Ieșire ' . $_SESSION["user_first_name"] ?>
                                    </a>
                                    <?php
                                } else {
                                    // Utiliatorul nu e logat => afiseaza butonul de inregistrare ?>
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
    <body>
    <div id="app">

    <?php
}

?>