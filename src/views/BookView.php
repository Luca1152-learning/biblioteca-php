<?php
include_once __DIR__ . '/../models/BookModel.php';

class BookView
{
    public function render_tile(BookModel $book)
    { ?>
        <div class="tile is-3 is-parent">
            <a href="/carte.php?id=<?php echo $book->book_id ?>">
                <div class="tile is-parent is-vertical p-1">
                    <figure class="is-child image">
                        <img src="<?php echo $book->cover_url ?>" alt="<?php echo $book->title ?> cover">
                    </figure>
                    <p class="is-size-5 has-text-black has-text-weight-medium is-child pt-1 has-text-centered"><?php echo $book->title ?></p>
                    <p class="is-size-6 is-child has-text-centered has-text-grey-dark"><?php echo $book->authors[0] ?></p>
                    <?php if ($book->available_copies_count > 0) { // TODO: counts copies and not available copies ?>
                        <button class="is-child button is-rounded is-primary mt-3" style="align-self: center;">
                            Împrumută
                        </button>
                    <?php } else { ?>
                        <button class="is-child button is-rounded mt-3" style="align-self: center;" disabled>
                            Indisponibilă
                        </button>
                    <?php } ?>
                </div>
            </a>
        </div>
    <?php }
}

?>
