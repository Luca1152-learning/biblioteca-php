<?php
include_once __DIR__ . '/../models/UserModel.php';
include_once __DIR__ . '/../database/Database.php';

class UserController
{
    private mysqli $db;

    public function __construct()
    {
        $db = new Database();
        $this->db = $db->get_handle();
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
            $_SESSION["email"] = $user->email;
            $_SESSION["role"] = "user";
            $_SESSION["first_name"] = $user->first_name;
            $_SESSION["last_name"] = $user->last_name;

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function sign_in(UserModel $user)
    {
        try {
            $query = $this->db->prepare("
                SELECT email, role, first_name, last_name
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
                $_SESSION["email"], $_SESSION["role"],
                $_SESSION["first_name"], $_SESSION["last_name"]
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
