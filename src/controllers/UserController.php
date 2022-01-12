<?php
include_once __DIR__ . '/AbstractController.php';
include_once __DIR__ . '/../models/UserModel.php';
include_once __DIR__ . '/../utils/database/Database.php';

class UserController implements AbstractController
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = (new Database())->get_handle();
    }

    public function insert($data)
    {
        // TODO: Implement insert() method.
    }

    public function update($data)
    {
        // TODO: Implement update() method.
    }

    public function get_by_id(int $id)
    {
        $filtered_user = array_filter($this->get_all(), function ($it) use ($id) {
            return $it->user_id === $id;
        });
        return reset($filtered_user);
    }

    public function get_all()
    {
        $users_array = [];

        // Query
        $query = $this->db->prepare("
            SELECT user_id, email, role, first_name, last_name, sign_up_date, last_online_date
            FROM users;
        ");
        $query->execute();

        // Prepare the result
        $user = new UserModel();
        $query->store_result();
        $query->bind_result(
            $user->user_id, $user->email, $user->role, $user->first_name,
            $user->last_name, $user->sign_up_date, $user->last_online_date
        );

        // Fetch all rows
        while ($query->fetch()) {
            // unserialize(serialize(book)) = deep copy
            array_push($users_array, unserialize(serialize($user)));
        }

        $query->close();

        return $users_array;
    }

    public function sign_up(UserModel $user)
    {
        try {
            // Insert the user
            $query = $this->db->prepare("
                INSERT INTO users(email, password, first_name, last_name)
                VALUES (?, ?, ?, ?)
            ");
            $query->bind_param(
                "ssss",
                $user->email, $user->hashedPassword, $user->first_name, $user->last_name
            );
            $query->execute();
            $query->close();

            // If successful, log in (altering the session)
            return $this->sign_in($user);
        } catch (Exception $e) {
            return false;
        }
    }

    public function sign_in(UserModel $user)
    {
        try {
            $query = $this->db->prepare("
                SELECT user_id, email, role, first_name, last_name, sign_up_date, last_online_date
                FROM users
                WHERE email = ? AND password = ?;
            ");
            $query->bind_param(
                "ss",
                $user->email, $user->hashedPassword
            );
            $query->execute();
            $query->store_result();

            // Invalid authentication, as no rows were found
            if ($query->num_rows !== 1) {
                return false;
            }

            $query->bind_result(
                $_SESSION["user_id"], $_SESSION["user_email"], $_SESSION["user_role"],
                $_SESSION["user_first_name"], $_SESSION["user_last_name"], $_SESSION["user_sign_up_date"],
                $_SESSION["user_last_online_date"]
            );
            $query->fetch();

            $query->close();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function sign_out()
    {
        session_start();
        session_destroy();
    }

    public function delete($id)
    {
        // Query
        $query = $this->db->prepare("
            DELETE FROM users
            WHERE user_id = ?;
        ");
        $query->bind_param("i", $id);
        $query->execute();
        $query->close();
    }
}

?>
