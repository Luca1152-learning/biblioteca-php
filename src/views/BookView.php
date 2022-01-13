<?php
include_once __DIR__ . '/../models/BookModel.php';

class BookView
{
    private function render_borrow_button_for_book($book, $css_classes)
    {
        $filtered_borrows = null;
        if (SecurityHelper::is_logged_in()) {
            $filtered_borrows = array_filter($_SESSION["borrows"], function ($it) use ($book) {
                return $it->book_id == $book->book_id;
            });
        }
        if ($filtered_borrows == null) {
            // The book wasn't borrowed

            if ($book->available_copies_count > 0) {
                // The book is available ?>
                <b-button class="<?php echo $css_classes; ?> is-primary" style="align-self: center;"
                          onclick="
                                  function imprumuta(){
                                  fetch('/imprumuta_post.php', {
                                  method: 'POST',
                                  headers: {'Content-Type': 'application/json'},
                                  body: JSON.stringify({book_id: <?php echo htmlspecialchars(json_encode($book->book_id), ENT_QUOTES); ?>})
                                  }).then(response => {
                                  if (response.status !== 200) {
                                  if (response.status === 403){
                                  window.location.href = '/conectare.php';
                                  return;
                                  } else if (response.status === 405){
                                  window.location.href = '/eroare-verifica-mail.php';
                                  return;
                                  } else {
                                  location.reload(); // Refresh page
                                  throw response;
                                  }}

                                  // Refresh on success
                                  location.reload(); // Refresh page
                                  }).catch(console.log)}
                                  imprumuta()">
                    Împrumută
                </b-button>
            <?php } else {
                // The book isn't available ?>
                <b-button class="<?php echo $css_classes; ?> is-disabled" style="align-self: center;" disabled>
                    Indisponibilă
                </b-button>
            <?php }
        } else {
            // The book was borrowed
            $borrow = reset($filtered_borrows);

            if ($borrow->borrow_date == null) {
                // The book was reserved (not yet picked up) ?>
                <b-button class="<?php echo $css_classes; ?> is-disabled" style="align-self: center;" disabled>
                    Rezervată
                </b-button>
            <?php } else if ($borrow->return_date == null) {
                // The book was borrowed, but not returned ?>
                <b-button class="<?php echo $css_classes; ?> is-disabled" style="align-self: center;" disabled>
                    Împrumutată
                </b-button>
            <?php } else {
                // The book was borrowed in the past ?>
                <b-button class="<?php echo $css_classes; ?> is-disabled" style="align-self: center;" disabled>
                    Returnată
                </b-button>
            <?php }
        }
    }

    public function render_tile(BookModel $book)
    { ?>
        <div class="tile is-3 is-parent">
            <div class="tile is-parent is-vertical p-1">
                <a href="/carte.php?id=<?php echo htmlspecialchars($book->book_id, ENT_QUOTES); ?>">
                    <figure class="is-child image">
                        <img src="<?php echo htmlspecialchars($book->cover_url, ENT_QUOTES); ?>"
                             alt="<?php echo htmlspecialchars($book->title, ENT_QUOTES); ?> cover">
                    </figure>
                    <p class="is-size-5 has-text-black has-text-weight-medium is-child pt-1 has-text-centered"><?php echo htmlspecialchars($book->title, ENT_QUOTES); ?></p>
                    <p class="is-size-6 is-child has-text-centered has-text-grey-dark"><?php echo htmlspecialchars($book->authors[0]->name, ENT_QUOTES); ?></p>
                </a>
                <?php $this->render_borrow_button_for_book($book, "is-child button is-rounded mt-3"); ?>
            </div>
        </div>
    <?php }

    public function render_individual_book(BookModel $book)
    { ?>
        <div class="columns">
            <div class="column is-one-quarter is-flex is-flex-direction-column">
                <figure class="image">
                    <img src="<?php echo htmlspecialchars($book->cover_url, ENT_QUOTES); ?>"
                         alt="<?php echo htmlspecialchars($book->title, ENT_QUOTES); ?> cover">
                </figure>
                <?php $this->render_borrow_button_for_book($book, "button is-rounded mt-3 is-align-self-stretch"); ?>
            </div>
            <div class="column">
                <p class="is-size-4 has-text-black has-text-weight-bold">
                    <?php echo htmlspecialchars($book->title, ENT_QUOTES); ?>
                </p>
                <p class="is-size-5 has-text-grey-dark has-text-weight-medium mb-2">
                    de
                    <?php foreach ($book->authors as $index => $author) { ?>
                        <span>
                            <a href="/autor.php?id=<?php echo htmlspecialchars($author->author_id, ENT_QUOTES); ?>">
                                <?php echo htmlspecialchars($author->name, ENT_QUOTES); ?>
                            </a>
                            <?php if ($index != count($book->authors) - 1)
                                echo htmlspecialchars(", ", ENT_QUOTES);
                            ?>
                        </span>
                    <?php } ?>
                </p>
                <p class="is-size-6 has-text-black">
                    <?php echo htmlspecialchars(nl2br($book->description), ENT_QUOTES); ?>
                </p>
                <p class="is-size-7 has-text-weight-bold has-text-gray mt-2">
                    <?php echo htmlspecialchars($book->publisher->name . ', ', ENT_QUOTES); ?>
                    <?php echo htmlspecialchars($book->publication_year . ', ', ENT_QUOTES); ?>
                    <?php echo htmlspecialchars($book->pages_count . ' pagini', ENT_QUOTES); ?>
                </p>
            </div>
            <div class="column is-one-quarter">
                <p class="is-size-6 has-text-black has-text-weight-semibold">CATEGORII</p>
                <hr class="m-0 my-1">
                <?php
                foreach ($book->categories as $category) {
                    ?>
                    <p class="mb-2">
                        <a href="/categorie.php?id=<?php echo htmlspecialchars($category->category_id, ENT_QUOTES); ?>"
                           class="is-flex is-justify-content-space-between is-align-content-baseline">
                            <span class="is-align-self-baseline"><?php echo htmlspecialchars($category->name, ENT_QUOTES); ?></span>
                            <span class="has-text-grey-light is-size-7 is-align-self-baseline"
                                  style="white-space: nowrap;"><?php echo htmlspecialchars($category->votes, ENT_QUOTES); ?> voturi</span>
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
