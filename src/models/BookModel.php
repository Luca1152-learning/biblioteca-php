<?php

class BookModel
{
    public ?int $book_id;
    public ?string $title;
    public ?string $description;
    public ?array $authors;
    public ?int $available_copies_count;
    public ?array $categories;
    public ?string $cover_url;
    public $publisher; // Of type PublisherModel
    public ?int $publication_year;
    public ?int $pages_count;
}

?>
