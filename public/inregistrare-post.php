<?php
include_once __DIR__ . '/../src/utils/models/UserModel.php';
include_once __DIR__ . '/../src/utils/controllers/UserController.php';

session_start();
$userController = new UserController();

function sign_up()
{
    global $userController;

    // Check all fields were received
    if (empty($_POST) || empty($_POST["first_name"]) || empty($_POST["last_name"]) ||
        empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["r_password"])) {
        // Redirect to sign up page
        header('Location: /inregistrare.php');
        exit();
    }

    $user = new UserModel($_POST["email"], $_POST["password"], $_POST["first_name"], $_POST["last_name"]);
    if ($userController->sign_up($user)) {
        // Redirect to index page
        header('Location: /');
        exit();
    } else {
        // Redirect to sign up page
        header('Location: /inregistrare.php');
        exit();
    }
}

sign_up();
?>