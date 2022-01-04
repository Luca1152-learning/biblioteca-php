<?php
include_once __DIR__ . '/../src/components/header.php';
include_once __DIR__ . '/../src/components/footer.php';
include_once __DIR__ . '/../src/controllers/BookController.php';
include_once __DIR__ . '/../src/controllers/AuthorController.php';
include_once __DIR__ . '/../src/views/BookView.php';

if (!isset($_GET["id"]) or !is_numeric($_GET["id"])) {
    exit(); // TODO more explicit fail
}

$book_controller = new BookController();
$author_controller = new AuthorController();

$author_id = $_GET["id"];
$author = $author_controller->get_by_id($author_id);
$author_name = $author->name;
$books = $book_controller->get_all();
$books_by_author = array_filter($books, function ($book) use ($author_id) {
    foreach ($book->authors as $author) {
        if ($author->id = $author_id) {
            return true;
        }
    }
    return false;
});

create_header("Lib - Cărți de " . $author_name);
?>
    <main class="section">
        <p class="is-size-4 has-text-black has-text-weight-semibold pb-2">
            Cărți scrise de <span class="has-text-primary"><?php echo $author_name ?></span>
        </p>
        <div class="tile is-ancestor">
            <?php
            foreach ($books_by_author as $book) {
                (new BookView())->render_tile($book);
            }
            ?>
        </div>
    </main>

<?php
create_footer();
?>