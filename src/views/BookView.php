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
                    <p class="is-size-6 is-child has-text-centered has-text-grey-dark"><?php echo $book->authors[0]->name ?></p>
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

    public function render_individual_book(BookModel $book)
    { ?>
        <div class="columns">
            <div class="column is-one-quarter is-flex is-flex-direction-column">
                <figure class="image">
                    <img src="<?php echo $book->cover_url ?>" alt="<?php echo $book->title ?> cover">
                </figure>
                <?php if ($book->available_copies_count > 0) { // TODO: counts copies and not available copies ?>
                    <button class="button is-rounded is-primary mt-3 is-align-self-stretch" style="align-self: center;">
                        Împrumută
                    </button>
                <?php } else { ?>
                    <button class="button is-rounded mt-3 is-align-self-stretch" style="align-self: center;" disabled>
                        Indisponibilă
                    </button>
                <?php } ?>
            </div>
            <div class="column">
                <p class="is-size-4 has-text-black has-text-weight-bold">
                    <?php echo $book->title ?>
                </p>
                <p class="is-size-5 has-text-grey-dark has-text-weight-medium mb-2">
                    de
                    <?php foreach ($book->authors as $index => $author) { ?>
                        <span>
                            <a href="/autor.php?id=<?php echo $author->author_id; ?>">
                                <?php echo $author->name; ?>
                            </a>
                            <?php if ($index != count($book->authors) - 1)
                                echo ", ";
                            ?>
                        </span>
                    <?php } ?>
                </p>
                <p class="is-size-6 has-text-black">
                    <?php echo nl2br($book->description); ?>
                </p>
                <p class="is-size-7 has-text-weight-bold has-text-gray mt-2">
                    <?php echo $book->publisher->name, ', '; ?>
                    <?php echo $book->publication_year, ', '; ?>
                    <?php echo $book->pages_count, ' pagini' ?>
                </p>
            </div>
            <div class="column is-one-quarter">
                <p class="is-size-6 has-text-black has-text-weight-semibold">CATEGORII</p>
                <hr class="m-0 my-1">
                <?php
                foreach ($book->categories as $category) {
                    ?>
                    <p class="mb-2">
                        <a href="/categorie.php?id=<?php echo $category->category_id; ?>"
                           class="is-flex is-justify-content-space-between is-align-content-baseline">
                            <span class="is-align-self-baseline"><?php echo $category->name; ?></span>
                            <span class="has-text-grey-light is-size-7 is-align-self-baseline"
                                  style="white-space: nowrap;"><?php echo $category->votes; ?> voturi</span>
                        </a>
                    </p>
                    <?php
                }
                ?>
            </div>
        </div>
    <?php }
}

?>
