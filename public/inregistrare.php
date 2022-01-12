<?php
include_once '../src/components/header.php';
include_once '../src/components/footer.php';

create_header("Lib - Biblioteca ta");
?>

    <main class="section">
        <form method="POST" action="/inregistrare-post.php"
              style="width: 50%; position: relative; margin-left: auto; margin-right: auto">
            <h1 class="title">Înregistrare</h1>

            <div class="field is-grouped" style="gap: 2rem;">
                <label class="label">
                    Nume
                    <div class="control has-icons-left has-icons-right">
                        <input class="input" type="text" name="first_name" placeholder="Numele tău..." value=""
                               required>
                    </div>
                </label>

                <label class="label">
                    Prenume
                    <div class="control has-icons-left has-icons-right">
                        <input class="input" type="text" name="last_name" placeholder="Prenumele tău..." value=""
                               required>
                    </div>
                </label>
            </div>

            <div class="field">
                <label class="label">
                    E-mail
                    <div class="control has-icons-left has-icons-right">
                        <input class="input" type="email" name="email" placeholder="Adresa ta de mail..." value=""
                               required>
                    </div>
                </label>
            </div>

            <div class="field" style="margin-top: 1.5rem;">
                <label class="label">Parolă
                    <div class="control has-icons-left has-icons-right">
                        <input class="input" type="password" name="password" placeholder="Parola contului..." required>
                    </div>
                </label>
            </div>

            <div class="field" style="margin-top: 1.5rem;">
                <label class="label">Repetă parola
                    <div class="control has-icons-left has-icons-right">
                        <input class="input" type="password" name="r_password" placeholder="Aceeași parolă..." required>
                    </div>
                </label>
            </div>

            <div class="field is-grouped"
                 style="display: flex; align-items: center; justify-content:space-between; 100%; margin-top: 1.5rem;">
                <div>
                    Ai deja cont? Conectează-te <a href="/conectare.php">aici</a>.
                </div>
                <div class="control">
                    <input type="submit" value="Înregistrare" name="submit" class="button is-primary is-rounded">
                </div>
            </div>
        </form>
    </main>

<?php
create_footer();
?>