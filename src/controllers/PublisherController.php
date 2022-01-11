<?php

include_once __DIR__ . '/AbstractController.php';
include_once __DIR__ . '/../models/PublisherModel.php';
include_once __DIR__ . '/../utils/database/Database.php';

class PublisherController implements AbstractController
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = (new Database())->get_handle();
    }

    public function get_by_id(int $id)
    {
        $filtered_publisher = array_filter($this->get_all(), function ($it) use ($id) {
            return $it->publisher_id === $id;
        });
        return reset($filtered_publisher);
    }

    public function get_all()
    {
        $publishers_array = [];

        // Query
        $query = $this->db->prepare("
            SELECT publisher_id, name
            FROM publishers;
        ");
        $query->execute();

        // Prepare the result
        $publisher = new PublisherModel();
        $query->store_result();
        $query->bind_result($publisher->publisher_id, $publisher->name);

        // Fetch all rows
        while ($query->fetch()) {
            // unserialize(serialize(book)) = deep copy
            array_push($publishers_array, unserialize(serialize($publisher)));
        }

        $query->close();

        return $publishers_array;
    }

    public function delete($id)
    {
        // Query
        $query = $this->db->prepare("
            DELETE FROM publishers
            WHERE publisher_id = ?;
        ");
        $query->bind_param("i", $id);
        $query->execute();
        $query->close();
    }
}

?>