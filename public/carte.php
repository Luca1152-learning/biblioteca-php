<?php
include_once __DIR__ . '/../src/components/header.php';
include_once __DIR__ . '/../src/components/footer.php';
include_once __DIR__ . '/../src/controllers/BookController.php';
include_once __DIR__ . '/../src/views/BookView.php';

if (!isset($_GET["id"]) or !is_numeric($_GET["id"])) {
    SecurityHelper::redirect_to_404();
}

$book_id = $_GET["id"];
$book_controller = new BookController();
$book = $book_controller->get_by_id($book_id);
// There's no book with the given id
if ($book == null) {
    SecurityHelper::redirect_to_404();
}

create_header("Lib - " . $book->title);

?>

    <main class="section">
        <div class="container">
            <?php (new BookView())->render_individual_book($book); ?>
        </div>
    </main>

<?php
create_footer();
?>