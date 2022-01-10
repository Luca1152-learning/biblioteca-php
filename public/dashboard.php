<?php
include_once __DIR__ . '/../src/components/header.php';
include_once __DIR__ . '/../src/components/footer.php';
include_once __DIR__ . '/../src/controllers/UserController.php';
include_once __DIR__ . '/../src/controllers/AuthorController.php';
include_once __DIR__ . '/../src/controllers/BookController.php';
include_once __DIR__ . '/../src/controllers/CategoryController.php';
include_once __DIR__ . '/../src/views/dashboard/TableView.php';
include_once __DIR__ . '/../src/views/dashboard/EditView.php';

if (!SecurityHelper::is_librarian() && !SecurityHelper::is_admin()) {
    SecurityHelper::redirect_to_403();
}
if (!isset($_GET) || !isset($_GET["meniu"]) || !isset($_GET["actiune"])) {
    SecurityHelper::redirect_to_404();
}
$menu = $_GET["meniu"];
$action = $_GET["actiune"];
if ($action === "modifica" && !isset($_GET["id"])) {
    SecurityHelper::redirect_to_404();
}

if ($menu === "utilizatori") {
    SecurityHelper::assert_is_admin();
}

$table_view = new TableView();
$edit_view = new EditView();

create_header("Lib - Dashboard");
if ($menu === "utilizatori") {
    // Users
    $user_controller = new UserController();
    $columns = array(
        "user_id" => array(
            "label" => "ID"
        ),
        "last_name" => array(
            "label" => "Nume",
        ),
        "first_name" => array(
            "label" => "Prenume"
        ),
        "email" => array(
            "label" => "Email"
        ),
        "role" => array(
            "label" => "Rol"
        ),
        "sign_up_date" => array(
            "label" => "Dată înregistrare",
            "centered" => true,
            "type" => "date"
        )
    );

    if ($action === "vezi") {
        $metadata = array(
            "page_title" => "Listă utilizatori",
            "columns" => $columns,
            "modify_url" => "/dashboard.php?meniu=utilizatori&actiune=modifica&id=",
            "delete_url" => "/dashboard.php?meniu=utilizatori&actiune=sterge"
        );
        $table_view->render_table($user_controller->get_all(), $metadata);
    } else {
        SecurityHelper::redirect_to_404();
    }
} else if ($menu === "carti") {
    // Books
    $book_controller = new BookController();
    $author_controller = new AuthorController();
    $category_controller = new CategoryController();

    if ($action === "vezi") {
        $columns = array(
            "book_id" => array(
                "label" => "ID",
                "width" => 60,
            ),
            "title" => array(
                "label" => "Titlu"
            ),
            "authors" => array(
                "label" => "Autor",
                "type" => "list"
            ),
            "categories" => array(
                "label" => "Categorii",
                "type" => "list"
            ),
        );
        $metadata = array(
            "page_title" => "Listă cărți",
            "new_button_label" => "Adaugă carte",
            "columns" => $columns,
            "modify_url" => "/dashboard.php?meniu=carti&actiune=modifica&id=",
            "delete_url" => "/dashboard.php?meniu=carti&actiune=sterge",
        );
        $table_view->render_table($book_controller->get_all(), $metadata);
    } else if ($action === "modifica") {
        $id = $_GET["id"];
        $fields = array(
            "title" => array(
                "label" => "Titlu",
                "type" => "text",
                "required" => true,
            ),
            "authors" => array(
                "label" => "Autori",
                "add_label" => "Adaugă autor",
                "type" => "list",
                "field_name" => "name",
            ),
            "description" => array(
                "label" => "Descriere",
                "type" => "textarea"
            ),
            "categories" => array(
                "label" => "Categorii",
                "add_label" => "Adaugă categorie",
                "type" => "list",
                "field_name" => "name",
            ),
            "cover_url" => array(
                "label" => "URL Copertă",
                "type" => "text"
            ),
            "pages_count" => array(
                "label" => "Număr pagini",
                "type" => "number",
                "required" => true
            ),
        );
        $metadata = array(
            "page_title" => "Editează carte",
            "fields" => $fields
        );

        $data = array(
            "instance" => $book_controller->get_by_id($id),
            "all" => array(
                "authors" => $author_controller->get_all(),
                "categories" => $category_controller->get_all()
            )
        );
        $edit_view->render_table($data, $metadata);
    } else {
        SecurityHelper::redirect_to_404();
    }
} else if ($menu === "autori") {
    // Authors
    $author_controller = new AuthorController();
    $columns = array(
        "author_id" => array(
            "label" => "ID",
            "width" => 60
        ),
        "name" => array(
            "label" => "Nume"
        )
    );

    if ($action === "vezi") {
        $metadata = array(
            "page_title" => "Listă autori",
            "new_button_label" => "Adaugă autor",
            "columns" => $columns,
            "modify_url" => "/dashboard.php?meniu=autori&actiune=modifica&id=",
            "delete_url" => "/dashboard.php?meniu=autori&actiune=sterge",
        );
        $table_view->render_table($author_controller->get_all(), $metadata);
    } else {
        SecurityHelper::redirect_to_404();
    }
} else {
    SecurityHelper::redirect_to_404();
}
create_footer();
?>