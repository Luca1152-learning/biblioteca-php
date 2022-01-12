<?php
include_once __DIR__ . '/AbstractController.php';
include_once __DIR__ . '/../models/UserModel.php';
include_once __DIR__ . '/../utils/database/Database.php';
include_once __DIR__ . '/BorrowController.php';

class UserController implements AbstractController
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = (new Database())->get_handle();
    }

    public function insert($data)
    {
        throw new Error("STUB");
    }

    private function get_user_role($id)
    {
        // Query
        $query = $this->db->prepare("
            SELECT role
            FROM users
            WHERE user_id = ?;
        ");
        $query->bind_param("i", $id);
        $query->execute();

        // Result
        $query->store_result();
        $query->bind_result($user_role);
        $query->fetch();
        $query->close();

        return $user_role;
    }

    public function update($data)
    {
        if ($this->get_user_role($data["user_id"]) === "administrator") {
            throw new Error("Can't update administrators!");
        }

        $query = $this->db->prepare("
            UPDATE users
            SET last_name=?, first_name=?, email=?, role=?
            WHERE user_id = ?;
        ");
        $role = $data["role"]["name"];
        $query->bind_param(
            "ssssi",
            $data["last_name"], $data["first_name"],
            $data["email"], $role, $data["user_id"]
        );
        $query->execute();
        $query->close();
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
            SELECT user_id, email, role, first_name, last_name, sign_up_date, verified_email, verify_email_url
            FROM users;
        ");
        $query->execute();

        // Prepare the result
        $user = new UserModel();
        $query->store_result();
        $query->bind_result(
            $user->user_id, $user->email, $user->role, $user->first_name,
            $user->last_name, $user->sign_up_date, $user->verified_email, $user->verify_email_url
        );

        // Fetch all rows
        while ($query->fetch()) {
            // unserialize(serialize(book)) = deep copy
            array_push($users_array, unserialize(serialize($user)));
        }

        $query->close();

        // Get all borrows
        $borrow_controller = new BorrowController();
        $borrows = $borrow_controller->get_all();
        // For each users, get its borrows
        foreach ($users_array as $user) {
            $user->borrows = array_filter($borrows, function ($borrow) use ($user) {
                return $borrow->user_id === $user->user_id;
            });
        }

        return $users_array;
    }

    public function sign_up(UserModel $user)
    {
        try {
            // Insert the user
            $query = $this->db->prepare("
                INSERT INTO users(email, password, first_name, last_name, verify_email_url)
                VALUES (?, ?, ?, ?, ?)
            ");
            $verify_email_url = hash("sha256", $user->email . time());
            $query->bind_param(
                "sssss",
                $user->email, $user->hashedPassword, $user->first_name, $user->last_name, $verify_email_url
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
                SELECT user_id, email, role, first_name, last_name, sign_up_date
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
                $_SESSION["user_first_name"], $_SESSION["user_last_name"], $_SESSION["user_sign_up_date"]
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
        if ($this->get_user_role($id) === "administrator") {
            throw new Error("Can't delete administrators!");
        }

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
