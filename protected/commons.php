<?php
    function page_header($title)
    {
        echo '
        <!DOCTYPE html>
        <html lang="ro">
        
        <head>
            <!-- Bulma-->
            <link rel="stylesheet" href="/stylesheets/bulma.css">
            <meta name="viewport" content="width=device-width, initial-scale=1">
        
            <title>' . $title . '</title>
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
        ';
    }

    function page_footer()
    {
        echo '
        <footer class="section">
        
        </footer>
        </body>
        
        </html>
        ';
    }

?>