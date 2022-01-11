<?php
include_once __DIR__ . '/../src/utils/security/SecurityHelper.php';
include_once __DIR__ . '/../src/controllers/AuthorController.php';

session_start();

if (!SecurityHelper::is_librarian()) {
    http_response_code(403);
    exit();
}


// Receive JSON input
$data = json_decode(file_get_contents('php://input'), true);
$source = $data["source"];
$action = $data["action"];

if ($source === "autori") {
    $author_controller = new AuthorController();
    $id = $data["id"];

    try {
        $author_controller->delete($id);
    } catch (Exception $e) {
        http_response_code(400); // Bad Request
        exit();
    }
}

echo json_encode($data);


?>