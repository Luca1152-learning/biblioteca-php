<?php
include_once __DIR__ . '/../src/components/header.php';
include_once __DIR__ . '/../src/components/footer.php';
include_once __DIR__ . '/../src/controllers/BookController.php';
include_once __DIR__ . '/../src/controllers/AuthorController.php';
include_once __DIR__ . '/../src/views/BookView.php';

if (!isset($_GET["id"]) or !is_numeric($_GET["id"])) {
    SecurityHelper::redirect_to_404();
}
$book_controller = new BookController();
$author_controller = new AuthorController();

$author_id = $_GET["id"];
$author = $author_controller->get_by_id($author_id);
// There's no author with the given id
if ($author == null) {
    SecurityHelper::redirect_to_404();
}
$author_name = $author->name;
$books = $book_controller->get_all();
$books_by_author = array_filter($books, function ($book) use ($author_id) {
    foreach ($book->authors as $author) {
        if ($author->author_id == $author_id) {
            return true;
        }
    }
    return false;
});

create_header("Lib - Cărți de " . $author_name);
?>
    <main class="section">
        <p class="is-size-4 has-text-black has-text-weight-semibold pb-2">
            Cărți scrise de <span
                    class="has-text-primary"><?php echo htmlspecialchars($author_name, ENT_QUOTES); ?></span>
        </p>
        <div class="tile is-ancestor">
            <?php
            if (count($books_by_author) === 0) {
                ?><p class="ml-3">Acest autor nu a publicat nicio carte.</p><?php
            } else {
                foreach ($books_by_author as $book) {
                    (new BookView())->render_tile($book);
                }
            }
            ?>
        </div>
    </main>

<?php
create_footer();
?>