<?php

include_once __DIR__ . '/../src/controllers/UserController.php';

$userController = new UserController();
$userController->sign_out();

// Redirect to index page
header("Location: /");
exit();

?>