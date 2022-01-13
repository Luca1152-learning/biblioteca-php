<?php
include_once __DIR__ . '/../src/components/header.php';
include_once __DIR__ . '/../src/components/footer.php';
include_once __DIR__ . '/../src/controllers/BookController.php';
include_once __DIR__ . '/../src/controllers/CategoryController.php';
include_once __DIR__ . '/../src/views/BookView.php';

if (!isset($_GET["id"]) or !is_numeric($_GET["id"])) {
    SecurityHelper::redirect_to_404();
}

$book_controller = new BookController();
$category_controller = new CategoryController();

$category_id = $_GET["id"];
$category = $category_controller->get_by_id($category_id);
// There's no book with the given id
if ($category == null) {
    SecurityHelper::redirect_to_404();
}
$category_name = $category->name;
$books = $book_controller->get_all();
$books_by_category = array_filter($books, function ($book) use ($category_id) {
    foreach ($book->categories as $category) {
        if ($category->category_id == $category_id) {
            return true;
        }
    }
    return false;
});

create_header("Lib - Cărți din categoria " . $category_name);
?>
    <main class="section">
        <p class="is-size-4 has-text-black has-text-weight-semibold pb-2">
            Cărți din categoria <span
                    class="has-text-primary"><?php echo htmlspecialchars($category_name, ENT_QUOTES); ?></span>
        </p>
        <div class="tile is-ancestor">
            <?php
            foreach ($books_by_category as $book) {
                (new BookView())->render_tile($book);
            }
            ?>
        </div>
    </main>

<?php
create_footer();
?>