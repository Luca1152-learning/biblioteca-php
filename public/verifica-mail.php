<?php
include_once __DIR__ . '/../src/utils/security/SecurityHelper.php';
include_once __DIR__ . '/../src/controllers/UserController.php';

if (!isset($_GET) || !isset($_GET["cod"])) {
    SecurityHelper::redirect_to_404();
    exit();
}

$user_controller = new UserController();
$cod = $_GET["cod"];
$user_controller->confirm_mail($cod);

// Redirect to Index page
header('Location: /');


?>
