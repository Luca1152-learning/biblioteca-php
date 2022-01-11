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

// ---------------------------------------- CARTI ----------------------------------------
if ($source === "carti") {
    $book_controller = new BookController();

    if ($action === "adauga") {
        try {
            $book_controller->insert($data["data"]);
        } catch (Exception $e) {
            http_response_code(404); // Not Found
            exit();
        }
    } else if ($action === "sterge") {
        $id = $data["id"];

        try {
            $book_controller->delete($id);
        } catch (Exception $e) {
            http_response_code(400); // Bad Request
            exit();
        }
    } else {
        http_response_code(404); // Not Found
        exit();
    }
} // ---------------------------------------- AUTORI ----------------------------------------
else if ($source === "autori") {
    $author_controller = new AuthorController();

    if ($action === "adauga") {
        try {
            $author_controller->insert($data["data"]);
        } catch (Exception $e) {
            http_response_code(400); // Bad Request
            exit();
        }
    } else if ($action === "sterge") {
        $id = $data["id"];

        try {
            $author_controller->delete($id);
        } catch (Exception $e) {
            http_response_code(400); // Bad Request
            exit();
        }
    } else {
        http_response_code(404); // Not Found
        exit();
    }
} // ---------------------------------------- PUBLISHERI ----------------------------------------
else if ($source === "publisheri") {
    $publisher_controller = new PublisherController();

    if ($action === "sterge") {
        $id = $data["id"];

        try {
            $publisher_controller->delete($id);
        } catch (Exception $e) {
            http_response_code(400); // Bad Request
            exit();
        }
    } else {
        http_response_code(404); // Not Found
        exit();
    }
} // ---------------------------------------- CATEGORII ----------------------------------------
else if ($source === "categorii") {
    $category_controller = new CategoryController();

    if ($action === "sterge") {
        $id = $data["id"];

        try {
            $category_controller->delete($id);
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