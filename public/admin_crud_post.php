<?php
include_once __DIR__ . '/../src/utils/security/SecurityHelper.php';
include_once __DIR__ . '/../src/controllers/UserController.php';

session_start();

if (!SecurityHelper::is_admin()) {
    http_response_code(403);
    exit();
}

// Receive JSON input
$data = json_decode(file_get_contents('php://input'), true);
$source = $data["source"];
$action = $data["action"];

if ($source === "utilizatori") {
    $user_controller = new UserController();
    if ($action === "modifica") {
        try {
            $user_controller->update($data["data"]);
            exit();
        } catch (Exception $e) {
            http_response_code(400); // Bad Request
            exit();
        }
    } else if ($action === "sterge") {
        $id = $data["id"];

        try {
            $user_controller->delete($id);
        } catch (Exception $e) {
            http_response_code(400); // Bad Request
            exit();
        }
    } else {
        http_response_code(404); // Not Found
        exit();
    }
} else {
    http_response_code(404); // Not Found
    exit();
}

?>