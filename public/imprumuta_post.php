<?php
include_once __DIR__ . '/../src/utils/security/SecurityHelper.php';
include_once __DIR__ . '/../src/controllers/BorrowController.php';

session_start();

if (!SecurityHelper::is_logged_in()) {
    http_response_code(403);
    exit();
}

// Receive JSON input
$data = json_decode(file_get_contents('php://input'), true);
$data["user_id"] = $_SESSION["user_id"];

$borrow_controller = new BorrowController();
try {
    $borrow_controller->insert($data);
} catch (Exception $e) {
    http_response_code(403);
    exit();
}

?>