<?php
include_once __DIR__ . '/../src/utils/models/UserModel.php';
include_once __DIR__ . '/../src/utils/controllers/UserController.php';

$userController = new UserController();
session_start();

function sign_in()
{
    global $userController;

    // Check all fields were received
    if (empty($_POST["email"]) || empty($_POST["password"])) {
        // Redirect to log in page
        header('Location: /conectare.php');
        exit();
    }

    $user = new UserModel($_POST["email"], $_POST["password"]);
    if ($userController->sign_in($user)) {
        // Redirect to index page
        header('Location: /');
        exit();
    } else {
        // Redirect to log in page
        header('Location: /conectare.php');
        exit();
    }
}

sign_in();
?>