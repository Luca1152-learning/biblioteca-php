<?php

include_once __DIR__ . '/AbstractController.php';
include_once __DIR__ . '/../models/BookModel.php';
include_once __DIR__ . '/../models/PublisherModel.php';
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
                   (SELECT GROUP_CONCAT(a.author_id ORDER BY ba.is_main_author DESC, b.book_id)
                    FROM books_authors ba
                    JOIN authors a ON ba.author_id = a.author_id
                    WHERE ba.book_id = b.book_id) authors_ids,
                    (SELECT GROUP_CONCAT(a.name ORDER BY ba.is_main_author DESC, b.book_id)
                    FROM books_authors ba
                    JOIN authors a ON ba.author_id = a.author_id
                    WHERE ba.book_id = b.book_id) authors_names,
                    (SELECT GROUP_CONCAT(c.category_id ORDER BY bc.votes DESC)
                    FROM books_categories bc
                    JOIN categories c on bc.category_id = c.category_id
                    WHERE bc.book_id = b.book_id) categories_ids,
                   (SELECT GROUP_CONCAT(c.name ORDER BY bc.votes DESC)
                    FROM books_categories bc
                    JOIN categories c on bc.category_id = c.category_id
                    WHERE bc.book_id = b.book_id) categories_names,
                   (SELECT GROUP_CONCAT(bc.votes ORDER BY bc.votes DESC)
                    FROM books_categories bc
                    JOIN categories c on bc.category_id = c.category_id
                    WHERE bc.book_id = b.book_id) categories_votes,
                   (SELECT COUNT(*)
                    FROM copies c
                    JOIN borrows b ON c.copy_id = b.copy_id
                    WHERE c.book_id = b.book_id AND (borrow_date IS NULL OR 
                        (borrow_date IS NOT NULL AND return_date IS NULL))
                   ) borrowed_books_count
            FROM books b
            LEFT JOIN publishers p ON b.publisher_id = p.publisher_id;
        ");
        $query->execute();

        // Prepare the result
        $book = new BookModel();
        $query->store_result();
        $query->bind_result(
            $book->book_id, $book->title, $book->description, $book->cover_url, $publisher_id,
            $publisher_name, $book->publication_year, $book->pages_count, $book->total_copies_count,
            $authors_ids_string, $authors_names_string, $categories_ids_string, $categories_names_string,
            $categories_votes_string, $borrowed_books_count
        );

        // Fetch all rows
        while ($query->fetch()) {
            // Set publisher
            $book->publisher = new PublisherModel();
            $book->publisher->publisher_id = $publisher_id;
            $book->publisher->name = $publisher_name;

            // Set authors
            $authors_ids = [];
            if ($authors_ids_string != null) {
                $authors_ids = explode(",", $authors_ids_string);
            }
            $authors_names = [];
            if ($authors_names_string != null) {
                $authors_names = explode(",", $authors_names_string);
            }
            $book->authors = [];
            foreach ($authors_names as $index => $author_name) {
                array_push($book->authors, (object)["author_id" => intval($authors_ids[$index]), "name" => $author_name]);
            }

            // Set categories
            $categories_ids = [];
            if ($categories_ids_string != null) {
                $categories_ids = explode(",", $categories_ids_string);
            }
            $categories_names = [];
            if ($categories_names_string != null) {
                $categories_names = explode(",", $categories_names_string);
            }
            $categories_votes = [];
            if ($categories_votes_string != null) {
                $categories_votes = explode(",", $categories_votes_string);
            }
            $book->categories = [];
            foreach ($categories_names as $index => $category_name) {
                array_push($book->categories, (object)[
                    "category_id" => intval($categories_ids[$index]),
                    "name" => $category_name,
                    "votes" => $categories_votes[$index]]
                );
            }

            // Set available copies count
            $book->available_copies_count = $book->total_copies_count - $borrowed_books_count;

            // unserialize(serialize(book)) = deep copy
            array_push($books_array, unserialize(serialize($book)));
        }

        $query->close();

        return $books_array;
    }

    public function get_by_id(int $id)
    {
        try {
            $filtered_book = array_filter($this->get_all(), function ($it) use ($id) {
                return $it->book_id === $id;
            });
            $first_element = reset($filtered_book);
            return $first_element;
        } catch (Exception $e) {
            return null;
        }
    }

    public function delete($id)
    {
        // Query
        $query = $this->db->prepare("
            DELETE FROM books
            WHERE book_id = ?;
        ");
        $query->bind_param("i", $id);
        $query->execute();
        $query->close();
    }

    public function insert($data)
    {
        // Adauga cartea
        $query = $this->db->prepare("
            INSERT INTO books(title, description, cover_url, publisher_id, first_publication_year, pages_count)
            VALUES (?, ?, ?, ?, ?, ?);
        ");
        $query->bind_param(
            "sssiii",
            $data["title"], $data["description"], $data["cover_url"],
            $data["publisher"]["publisher_id"], $data["publication_year"], $data["pages_count"]
        );
        $query->execute();
        $query->close();

        // Gaseste ID-ul cartii nou adaugate
        $query = $this->db->prepare("
            SELECT book_id
            FROM books
            WHERE title = ?;
        ");
        $query->bind_param("s", $data["title"]);
        $query->execute();
        $query->store_result();
        $query->bind_result($book_id);
        $query->fetch();
        $query->close();

        // Adauga-i autorii
        foreach ($data["authors"] as $index => $author) {
            $query = $this->db->prepare("
                INSERT INTO books_authors(book_id, author_id, is_main_author)
                VALUES (?, ?, ?);
            ");
            $is_main_author = $index == 0;
            $query->bind_param("iib", $book_id, $author["author_id"], $is_main_author);
            $query->execute();
            $query->close();
        }

        // Adauga-i categoriile
        foreach ($data["categories"] as $index => $category) {
            $query = $this->db->prepare("
                INSERT INTO books_categories(book_id, category_id, votes)
                VALUES (?, ?, ?);
            ");
            $votes = count($data["categories"]) - $index;
            $query->bind_param("iii", $book_id, $category["category_id"], $votes);
            $query->execute();
            $query->close();
        }
    }

    public function update($data)
    {
        // Update book information
        $query = $this->db->prepare("
            UPDATE books
            SET title=?, description=?, cover_url=?, publisher_id=?, first_publication_year=?, pages_count=?
            WHERE book_id = ?
        ");
        $query->bind_param(
            "sssiiii",
            $data["title"], $data["description"], $data["cover_url"],
            $data["publisher"]["publisher_id"], $data["publication_year"], $data["pages_count"], $data["book_id"]
        );
        $query->execute();
        $query->close();

        // Delete all book's existing authors
        $query = $this->db->prepare("
            DELETE FROM books_authors
            WHERE book_id = ?;
        ");
        $query->bind_param("i", $data["book_id"]);
        $query->execute();
        $query->close();

        // Add new authors
        foreach ($data["authors"] as $index => $author) {
            $query = $this->db->prepare("
                INSERT INTO books_authors(book_id, author_id, is_main_author)
                VALUES (?, ?, ?);
            ");
            $is_main_author = $index == 0;
            $query->bind_param("iib", $data["book_id"], $author["author_id"], $is_main_author);
            $query->execute();
            $query->close();
        }

        // Delete all book's existing categories
        $query = $this->db->prepare("
            DELETE FROM books_categories
            WHERE book_id = ?;
        ");
        $query->bind_param("i", $data["book_id"]);
        $query->execute();
        $query->close();

        // Add new categories
        foreach ($data["categories"] as $index => $category) {
            $query = $this->db->prepare("
                INSERT INTO books_categories(book_id, category_id, votes)
                VALUES (?, ?, ?);
            ");
            $votes = count($data["categories"]) - $index;
            $query->bind_param("iii", $data["book_id"], $category["category_id"], $votes);
            $query->execute();
            $query->close();
        }
    }
}

?>