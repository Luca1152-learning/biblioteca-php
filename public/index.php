<?php
include_once __DIR__ . '/../src/components/header.php';
include_once __DIR__ . '/../src/components/footer.php';
include_once __DIR__ . '/../src/controllers/BookController.php';
include_once __DIR__ . '/../src/views/BookView.php';

$book_controller = new BookController();
$books = $book_controller->get_all();


create_header("Lib - Toate cărțile");
?>
    <main class="section">
        <p class="is-size-4 has-text-black has-text-weight-semibold pb-2">
            Toate cărțile
        </p>
        <div class="tile is-ancestor" style="flex-wrap: wrap;">
            <?php
            foreach ($books as $book) {
                (new BookView())->render_tile($book);
            }
            ?>
        </div>
    </main>
<?php
create_footer();
?>