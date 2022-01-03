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
                <div class="tile is-parent is-vertical p-1">
                    <figure class="is-child image">
                        <img src="<?php echo $this->book->cover_url ?>" alt="<?php echo $this->book->title ?> cover">
                    </figure>
                    <p class="title is-5 is-child mt-2 pt-1 has-text-centered"><?php echo $this->book->title ?></p>
                    <p class="subtitle is-6 is-child has-text-centered has-text-grey-dark"><?php echo $this->book->authors[0] ?></p>
                </div>
            </a>
        </div>
    <?php }
}

?>
