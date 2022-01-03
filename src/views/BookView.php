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
                    <p class="is-size-5 has-text-black has-text-weight-medium is-child pt-1 has-text-centered"><?php echo $this->book->title ?></p>
                    <p class="is-size-6 is-child has-text-centered has-text-grey-dark"><?php echo $this->book->authors[0] ?></p>
                    <?php if ($this->book->available_copies_count > 0) { // TODO: counts copies and not available copies ?>
                        <button class="is-child button is-rounded is-primary is-small mt-3" style="align-self: center;">
                            Împrumută
                        </button>
                    <?php } else { ?>
                        <button class="is-child button is-rounded is-small mt-3" style="align-self: center;" disabled>
                            Indisponibilă
                        </button>
                    <?php } ?>
                </div>
            </a>
        </div>
    <?php }
}

?>
