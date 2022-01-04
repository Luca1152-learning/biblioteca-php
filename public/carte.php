<?php
include_once __DIR__ . '/../src/components/header.php';
include_once __DIR__ . '/../src/components/footer.php';
include_once __DIR__ . '/../src/controllers/BookController.php';
include_once __DIR__ . '/../src/views/BookView.php';

if (!isset($_GET["id"]) or !is_numeric($_GET["id"])) {
    exit(); // TODO more explicit fail
}

$book_id = $_GET["id"];
$book_controller = new BookController();
$book = $book_controller->get_by_id($book_id);

create_header("Lib - " . $book->title);

?>

    <main class="section">
        <div class="container">
            TODO
        </div>
    </main>

<?php
create_footer();
?>