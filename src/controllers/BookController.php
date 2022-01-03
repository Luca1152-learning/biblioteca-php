<?php

include_once __DIR__ . '/../models/BookModel.php';
include_once __DIR__ . '/../utils/database/Database.php';

class BookController
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = (new Database())->get_handle();
    }

    public function get_all_books()
    {
        $books_array = [];

        // Query
        $query = $this->db->prepare("
                SELECT book_id, title, cover_url, b.publisher_id, p.name, first_publication_year, pages_count
                FROM books b
                JOIN publishers p ON b.publisher_id = p.publisher_id;
            ");
        $query->execute();

        // Prepare the result
        $book = new BookModel();
        $query->store_result();
        $query->bind_result(
            $book->book_id, $book->title, $book->cover_url, $book->publisher_id,
            $book->publisher_name, $book->publication_year, $book->pages_count
        );

        // Fetch all rows
        while ($query->fetch()) {
            // unserialize(serialize(book)) = deep copy
            array_push($books_array, unserialize(serialize($book)));
        }

        $query->close();

        return $books_array;
    }
}

?>