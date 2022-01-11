<?php

include_once __DIR__ . '/AbstractController.php';
include_once __DIR__ . '/../models/AuthorModel.php';
include_once __DIR__ . '/../utils/database/Database.php';

class AuthorController implements AbstractController
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = (new Database())->get_handle();
    }

    public function get_by_id(int $id)
    {
        try {
            $filtered_author = array_filter($this->get_all(), function ($it) use ($id) {
                return $it->author_id === $id;
            });
            return reset($filtered_author);
        } catch (Exception $e) {
            return null;
        }
    }

    public function get_all()
    {
        $authors_array = [];

        // Query
        $query = $this->db->prepare("
            SELECT author_id, name
            FROM authors;
        ");
        $query->execute();

        // Prepare the result
        $author = new AuthorModel();
        $query->store_result();
        $query->bind_result($author->author_id, $author->name);

        // Fetch all rows
        while ($query->fetch()) {
            // unserialize(serialize(book)) = deep copy
            array_push($authors_array, unserialize(serialize($author)));
        }

        $query->close();

        return $authors_array;
    }

    public function insert($data)
    {

    }

    public function delete($id)
    {

    }

    public function update($new_data)
    {

    }
}

?>