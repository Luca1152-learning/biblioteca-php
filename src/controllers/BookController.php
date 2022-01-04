<?php

include_once __DIR__ . '/AbstractController.php';
include_once __DIR__ . '/../models/BookModel.php';
include_once __DIR__ . '/../utils/database/Database.php';

class BookController implements AbstractController
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = (new Database())->get_handle();
    }

    public function get_all()
    {
        $books_array = [];

        // Query
        $query = $this->db->prepare("
            SELECT b.book_id, title, description, cover_url, b.publisher_id, p.name, first_publication_year, 
                   pages_count,
                   (SELECT COUNT(*)
                    FROM copies c
                    WHERE c.book_id = b.book_id) copies_count,
                   (SELECT GROUP_CONCAT(name)
                    FROM books_authors ba
                    JOIN authors a ON ba.author_id = a.author_id
                    WHERE ba.book_id = b.book_id) authors, 
                   (SELECT GROUP_CONCAT(c.name)
                    FROM books_categories bc
                    JOIN categories c on bc.category_id = c.category_id
                    WHERE bc.book_id = b.book_id) categories
            FROM books b
            LEFT JOIN publishers p ON b.publisher_id = p.publisher_id;
        ");
        $query->execute();

        // Prepare the result
        $book = new BookModel();
        $query->store_result();
        $query->bind_result(
            $book->book_id, $book->title, $book->description, $book->cover_url, $book->publisher_id,
            $book->publisher_name, $book->publication_year, $book->pages_count, $book->available_copies_count,
            $authors_string, $categories_string
        );

        // Fetch all rows
        while ($query->fetch()) {
            $book->authors = explode(",", $authors_string);
            $book->categories = explode(",", $categories_string);

            // unserialize(serialize(book)) = deep copy
            array_push($books_array, unserialize(serialize($book)));
        }

        $query->close();

        return $books_array;
    }

    public function get_by_id(int $id)
    {
        $filtered_book = array_filter($this->get_all(), function ($it) use ($id) {
            return $it->book_id == $id;
        });
        $first_element = reset($filtered_book);
        return $first_element;
    }
}

?>