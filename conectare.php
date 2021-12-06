<?php
    include 'protected/commons.php';
    include 'protected/database.php';

    connect_database();

    page_header("Lib - Biblioteca ta");
?>

    <main class="section">
        <form method="POST" action="/conectare-post.php"
              style="width: 50%; position: relative; margin-left: auto; margin-right: auto">
            <h1 class="title">Conectare</h1>
            <div class="field">
                <label class="label">
                    E-mail
                    <div class="control has-icons-left has-icons-right">
                        <input class="input" type="email" name="email" placeholder="Adresa ta de mail..." value="">
                    </div>
                </label>
            </div>

            <div class="field" style="margin-top: 1.5rem;">
                <label class="label">Parolă
                    <div class="control has-icons-left has-icons-right">
                        <input class="input" type="password" name="password" placeholder="Parola contului..." value="">
                    </div>
                </label>
            </div>

            <div class="field is-grouped"
                 style="display: flex; align-items: center; justify-content:space-between; 100%; margin-top: 1.5rem;">
                <div>
                    Nu ai cont? Înregistrează-te <a href="/inregistrare.php">aici</a>.
                </div>
                <div class="control">
                    <input type="submit" value="Conectare" class="button is-primary is-rounded">
                </div>
            </div>
        </form>
    </main>

<?php
    page_footer();
?>