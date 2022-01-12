<?php
include_once __DIR__ . '/../src/utils/security/SecurityHelper.php';
include_once __DIR__ . '/../src/controllers/BookController.php';
include_once __DIR__ . '/../src/controllers/AuthorController.php';
include_once __DIR__ . '/../src/controllers/PublisherController.php';
include_once __DIR__ . '/../src/controllers/CategoryController.php';

session_start();

if (!SecurityHelper::is_librarian()) {
    http_response_code(403);
    exit();
}

// Receive JSON input
$data = json_decode(file_get_contents('php://input'), true);
$source = $data["source"];
$action = $data["action"];

// Generalized function
function handle_crud_requests($controller, $data)
{
    global $action;

    if ($action === "adauga") {
        try {
            $controller->insert($data["data"]);
        } catch (Exception $e) {
            http_response_code(404); // Not Found
            exit();
        }
    } else if ($action === "modifica") {
        try {
            $controller->update($data["data"]);
        } catch (Exception $e) {
            http_response_code(400); // Bad Request
            exit();
        }
    } else if ($action === "sterge") {
        $id = $data["id"];

        try {
            $controller->delete($id);
        } catch (Exception $e) {
            http_response_code(400); // Bad Request
            exit();
        }
    } else {
        http_response_code(404); // Not Found
        exit();
    }
}

// Handle CRUD requests with appropriate controllers
if ($source === "carti") {
    $book_controller = new BookController();
    handle_crud_requests($book_controller, $data);
} else if ($source === "autori") {
    $author_controller = new AuthorController();
    handle_crud_requests($author_controller, $data);
} else if ($source === "publisheri") {
    $publisher_controller = new PublisherController();
    handle_crud_requests($publisher_controller, $data);
} else if ($source === "categorii") {
    $category_controller = new CategoryController();
    handle_crud_requests($category_controller, $data);
} else {
    http_response_code(404); // Not Found
    exit();
}

?>