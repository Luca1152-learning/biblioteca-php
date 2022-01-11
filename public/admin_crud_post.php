<?php
include_once __DIR__ . '/../src/utils/security/SecurityHelper.php';
include_once __DIR__ . '/../src/controllers/AuthorController.php';

session_start();

if (!SecurityHelper::is_admin()) {
    http_response_code(403);
    exit();
}

// Receive JSON input
$data = json_decode(file_get_contents('php://input'), true);
$source = $data["source"];
$action = $data["action"];

if ($source === "TODO") {
    // TODO
}

echo json_encode($data);


?>