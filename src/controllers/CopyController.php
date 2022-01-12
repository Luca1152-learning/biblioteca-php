<?php

include_once __DIR__ . '/AbstractController.php';
include_once __DIR__ . '/../models/CopyModel.php';
include_once __DIR__ . '/../utils/database/Database.php';

class CopyController implements AbstractController
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = (new Database())->get_handle();
    }

    public function get_by_id(int $id)
    {
        try {
            $filtered_copy = array_filter($this->get_all(), function ($it) use ($id) {
                return $it->copy_id === $id;
            });
            return reset($filtered_copy);
        } catch (Exception $e) {
            return null;
        }
    }

    public function get_all()
    {
        $copies_array = [];

        // Query
        $query = $this->db->prepare("
            SELECT copy_id, c.book_id, title, date_added, comments
            FROM copies c
            JOIN books b ON c.book_id = b.book_id;
        ");
        $query->execute();

        // Prepare the result
        $copy = new CopyModel();
        $query->store_result();
        $query->bind_result($copy->copy_id, $book_id, $book_title, $copy->date_added, $copy->comments);

        // Fetch all rows
        while ($query->fetch()) {
            $copy->book = array("book_id" => $book_id, "title" => $book_title);

            // unserialize(serialize(book)) = deep copy
            array_push($copies_array, unserialize(serialize($copy)));
        }

        $query->close();

        return $copies_array;
    }

    public function insert($data)
    {
        $number = 1;
        if (isset($data["number"])) {
            $number = $data["number"];
        }

        for ($i = 1; $i <= $number; $i++) {
            $query = $this->db->prepare("
                INSERT INTO copies(book_id)
                VALUES (?);
            ");
            $query->bind_param("s", $data["book"]["book_id"]);
            $query->execute();
            $query->close();
        }
    }

    public function update($data)
    {
        $query = $this->db->prepare("
            UPDATE copies
            SET comments=?
            WHERE copy_id = ?;
        ");
        $query->bind_param("si", $data["comments"], $data["copy_id"]);
        $query->execute();
        $query->close();
    }

    public function delete($id)
    {
        $query = $this->db->prepare("
            DELETE FROM copies
            WHERE copy_id = ?;
        ");
        $query->bind_param("i", $id);
        $query->execute();
        $query->close();
    }
}

?>