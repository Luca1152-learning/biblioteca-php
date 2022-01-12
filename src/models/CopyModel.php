<?php

class CopyModel
{
    public ?int $copy_id;
    public $book; // BookModel-like, with book_id and title
    public ?string $date_added;
    public ?string $comments;
}

?>
