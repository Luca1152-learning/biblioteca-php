<?php
include '../src/utils/database/database.php';
$db = new Database();

session_start();

function sign_up(mysqli $db)
{
    // Check all fields were received
    if (empty($_POST) || empty($_POST["first_name"]) || empty($_POST["last_name"]) ||
        empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["r_password"])) {
        // Redirect to sign up page
        header('Location: /inregistrare.php');
        exit();
    }

    $hashed_password = hash("sha256", $_POST["password"]);

    try {
        $query = $db->prepare("
                INSERT INTO users(first_name, last_name, email, password)
                VALUES (?, ?, ?, ?)
            ");
        $query->bind_param(
            "ssss",
            $_POST["first_name"], $_POST["last_name"], $_POST["email"], $hashed_password
        );
        $query->execute();
        $query->close();

        $_SESSION["first_name"] = $_POST["first_name"];
        $_SESSION["last_name"] = $_POST["last_name"];
        $_SESSION["email"] = $_POST["last_name"];
        $_SESSION["role"] = "user";
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    // Redirect to index page
    header('Location: /');
    exit();
}

sign_up(Database::$db);
?>