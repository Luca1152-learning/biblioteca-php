<?php
    include 'protected/database.php';
    $db = connect_database();

    session_start();

    function log_in(mysqli $db)
    {
        // Check all fields were received
        if (empty($_POST["email"]) || empty($_POST["password"])) {
            // Redirect to log in page
            header('Location: /conectare.php');
            exit();
        }

        $hashed_password = hash("sha256", $_POST["password"]);

        try {
            // Query
            $query = $db->prepare("
                SELECT first_name, last_name, email, role
                FROM users
                WHERE email = ? AND password = ?;
            ");
            $query->bind_param(
                "ss",
                $_POST["email"], $hashed_password
            );
            $query->execute();
            $query->store_result();

            // Invalid authentication, as no rows were found
            if ($query->num_rows !== 1) {
                // Redirect to sign in page
                header('Location: /conectare.php');
                exit();
            }

            $query->bind_result(
                $_SESSION["first_name"], $_SESSION["last_name"],
                $_SESSION["email"], $_SESSION["role"]
            );
            $query->fetch();

            $query->close();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // Redirect to index page
        header('Location: /');
        exit();
    }

    log_in($db);
?>