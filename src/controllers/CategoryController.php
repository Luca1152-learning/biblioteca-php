<?php

include_once __DIR__ . '/AbstractController.php';
include_once __DIR__ . '/../models/CategoryModel.php';
include_once __DIR__ . '/../utils/database/Database.php';

class CategoryController implements AbstractController
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = (new Database())->get_handle();
    }

    public function get_by_id(int $id)
    {
        $filtered_category = array_filter($this->get_all(), function ($it) use ($id) {
            return $it->category_id === $id;
        });
        return reset($filtered_category);
    }

    public function get_all()
    {
        $categories_array = [];

        // Query
        $query = $this->db->prepare("
            SELECT category_id, name
            FROM categories;
        ");
        $query->execute();

        // Prepare the result
        $category = new CategoryModel();
        $query->store_result();
        $query->bind_result($category->category_id, $category->name);

        // Fetch all rows
        while ($query->fetch()) {
            // unserialize(serialize(book)) = deep copy
            array_push($categories_array, unserialize(serialize($category)));
        }

        $query->close();

        return $categories_array;
    }

    public function insert($data)
    {
        $query = $this->db->prepare("
            INSERT INTO categories(name)
            VALUES (?);
        ");
        $query->bind_param("s", $data["name"]);
        $query->execute();
        $query->close();
    }

    public function update($data)
    {
        $query = $this->db->prepare("
            UPDATE categories
            SET name=?
            WHERE category_id = ?;
        ");
        $query->bind_param("si", $data["name"], $data["category_id"]);
        $query->execute();
        $query->close();
    }

    public function delete($id)
    {
        // Query
        $query = $this->db->prepare("
            DELETE FROM categories
            WHERE category_id = ?;
        ");
        $query->bind_param("i", $id);
        $query->execute();
        $query->close();
    }
}

?>