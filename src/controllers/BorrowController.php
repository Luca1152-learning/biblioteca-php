<?php

include_once __DIR__ . '/AbstractController.php';
include_once __DIR__ . '/../models/BorrowModel.php';
include_once __DIR__ . '/../utils/database/Database.php';

class BorrowController implements AbstractController
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = (new Database())->get_handle();
    }

    public function get_by_id(int $id)
    {
        try {
            $filtered_borrow = array_filter($this->get_all(), function ($it) use ($id) {
                return $it->borrow_id === $id;
            });
            return reset($filtered_borrow);
        } catch (Exception $e) {
            return null;
        }
    }

    public function get_all()
    {
        $borrows_array = [];

        // Query
        $query = $this->db->prepare("
            SELECT borrow_id, b.user_id, CONCAT(first_name, ' ', last_name) AS user_name, 
                   b.copy_id, title, borrow_date, return_due_date, return_date 
            FROM borrows b
            JOIN users u ON b.user_id = u.user_id
            JOIN copies c ON b.copy_id = c.copy_id
            JOIN books bk ON c.book_id = bk.book_id;
        ");
        $query->execute();

        // Prepare the result
        $borrow = new BorrowModel();
        $query->store_result();
        $query->bind_result(
            $borrow->borrow_id, $borrow->user_id, $borrow->user_full_name, $borrow->copy_id,
            $borrow->book_title, $borrow->borrow_date, $borrow->return_due_date, $borrow->return_date
        );

        // Fetch all rows
        while ($query->fetch()) {
            // unserialize(serialize(book)) = deep copy
            array_push($borrows_array, unserialize(serialize($borrow)));
        }

        $query->close();

        return $borrows_array;
    }

    public function insert($data)
    {
        // TODO
//        $query = $this->db->prepare("
//            INSERT INTO copies(book_id)
//            VALUES (?);
//        ");
//        $query->bind_param("s", $data["book"]["book_id"]);
//        $query->execute();
//        $query->close();
    }

    public function update($data)
    {
        $query = $this->db->prepare("
            UPDATE borrows
            SET borrow_date=?, return_due_date=?, return_date=?
            WHERE borrow_id = ?;
        ");
        $query->bind_param(
            "sssi",
            $data["borrow_date"], $data["return_due_date"], $data["return_date"],
            $data["borrow_id"]
        );
        $query->execute();
        $query->close();
    }

    public function delete($id)
    {
        $query = $this->db->prepare("
            DELETE FROM borrows
            WHERE borrow_id = ?;
        ");
        $query->bind_param("i", $id);
        $query->execute();
        $query->close();
    }
}

?>