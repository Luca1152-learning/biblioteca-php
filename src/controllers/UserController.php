<?php
include_once __DIR__ . '/../models/UserModel.php';
include_once __DIR__ . '/../utils/database/Database.php';

class UserController
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = (new Database())->get_handle();
    }

    public function sign_up(UserModel $user)
    {
        try {
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

            // Alter the session
            $_SESSION["user_email"] = $user->email;
            $_SESSION["user_role"] = "user";
            $_SESSION["user_first_name"] = $user->first_name;
            $_SESSION["user_last_name"] = $user->last_name;
            // TODO use RETURNING to get the other attributes as well?

            return true;
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
}

?>
