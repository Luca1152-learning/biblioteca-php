<?php
include_once __DIR__ . '/../models/BookModel.php';

class BookView
{
    private BookModel $book;

    public function __construct($book)
    {
        $this->book = $book;
    }

    public function render()
    { ?>
        <div class="tile is-3 is-parent">
            <a href="#">
                <div class="tile is-parent is-vertical">
                    <figure class="is-child image">
                        <img src="<?php echo $this->book->cover_url ?>" alt="<?php echo $this->book->title ?> cover">
                    </figure>
                    <p class="title is-6 is-child mt-2 p-1"><?php echo $this->book->title ?></p>
                </div>
            </a>
        </div>
    <?php }
}

?>
