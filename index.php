<?php
    // Sursa: https://www.doabledanny.com/Deploy-PHP-And-MySQL-to-Heroku
    // Get Heroku ClearDB connection information
    $cleardb_url = parse_url("mysql://b9960eb5b31032:58fe0ff1@eu-cdbr-west-01.cleardb.com/heroku_b52a27f697a2c16?reconnect=true");
    $cleardb_server = $cleardb_url["host"];
    $cleardb_username = $cleardb_url["user"];
    $cleardb_password = $cleardb_url["pass"];
    $cleardb_db = substr($cleardb_url["path"], 1);
    $active_group = 'default';
    $query_builder = TRUE;

    // Connect to DB
    $conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <!-- Bulma-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Lib</title>
</head>

<body>
<header class="section">
    <nav class="navbar is-transparent">
        <div class="navbar-brand">
            <a class="navbar-item" href="/">
                <h1 class="title is-4">Lib</h1>
            </a>
            <div class="navbar-burger" data-target="navbarExampleTransparentExample">
                <span></span>
                <span></span>
                <span></span>
            </div>
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
                            <a class="button is-primary is-rounded" href="/conectare.php">
                                <span>Conectare</span>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>

<main class="section">
    <div class="container">
        Descrierea proiectului se găsește <strong><a href="/descriere-proiect.php">aici</a></strong>.
    </div>
</main>

<footer class="section">

</footer>
</body>

</html>