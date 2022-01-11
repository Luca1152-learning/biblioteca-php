<?php
include_once __DIR__ . '/../src/components/header.php';
include_once __DIR__ . '/../src/components/footer.php';
include_once __DIR__ . '/../src/controllers/UserController.php';
include_once __DIR__ . '/../src/controllers/AuthorController.php';
include_once __DIR__ . '/../src/controllers/BookController.php';
include_once __DIR__ . '/../src/controllers/CategoryController.php';
include_once __DIR__ . '/../src/controllers/PublisherController.php';
include_once __DIR__ . '/../src/views/dashboard/TableView.php';
include_once __DIR__ . '/../src/views/dashboard/EditView.php';
include_once __DIR__ . '/../src/views/dashboard/AddView.php';

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
$add_view = new AddView();

create_header("Lib - Dashboard");
//---------------------------------------- UTILIZATORI ----------------------------------------
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
            "source" => "utilizatori",
            "crud" => array(
                "url" => "/admin_crud_post.php",
                "delete" => array(
                    "confirm_message" => "Ești sigur că vrei să ștergi utilizatorul selectat?"
                )
            )
        );
        $table_view->render_table($user_controller->get_all(), $metadata);
    } else {
        SecurityHelper::redirect_to_404();
    }
} //---------------------------------------- CARTI ----------------------------------------
else if ($menu === "carti") {
    // Books
    $book_controller = new BookController();
    $author_controller = new AuthorController();
    $category_controller = new CategoryController();
    $publisher_controller = new PublisherController();

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
            "new_url" => "/dashboard.php?meniu=carti&actiune=adauga",
            "source" => "carti",
            "crud" => array(
                "url" => "/librarian_crud_post.php",
                "delete" => array(
                    "confirm_message" => "Ești sigur că vrei să ștergi cartea selectată?"
                )
            )
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
            "publisher" => array(
                "label" => "Publisher",
                "type" => "text-autocomplete",
                "field_name" => "name",
                "required" => true
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
            "publication_year" => array(
                "label" => "Anul publicării",
                "type" => "number",
                "min_value_number" => 1,
            ),
            "pages_count" => array(
                "label" => "Număr pagini",
                "type" => "number",
                "min_value_number" => 1,
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
                "categories" => $category_controller->get_all(),
                "publisher" => $publisher_controller->get_all()
            )
        );
        $edit_view->render($data, $metadata);
    } else if ($action === "adauga") {
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
            "publisher" => array(
                "label" => "Publisher",
                "type" => "text-autocomplete",
                "field_name" => "name",
                "required" => true
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
            "publication_year" => array(
                "label" => "Anul publicării",
                "type" => "number",
                "min_value_number" => 1,
            ),
            "pages_count" => array(
                "label" => "Număr pagini",
                "type" => "number",
                "min_value_number" => 1,
            ),
        );
        $metadata = array(
            "page_title" => "Adaugă carte",
            "fields" => $fields,
            "source" => "carti",
            "crud" => array(
                "url" => "/librarian_crud_post.php",
                "after_add_url" => "/dashboard.php?meniu=carti&actiune=vezi"
            )
        );
        $data = array(
            "instance" => array(
                "title" => "",
                "authors" => [],
                "publisher" => array(
                    "publisher_id" => null,
                    "name" => ""
                ),
                "description" => "",
                "categories" => [],
                "cover_url" => "",
                "pages_count" => null,
                "publication_year" => null,
            ),
            "all" => array(
                "authors" => $author_controller->get_all(),
                "categories" => $category_controller->get_all(),
                "publisher" => $publisher_controller->get_all()
            )
        );
        $add_view->render($data, $metadata);
    } else {
        SecurityHelper::redirect_to_404();
    }
} //---------------------------------------- AUTORI ----------------------------------------
else if ($menu === "autori") {
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
            "source" => "autori",
            "page_title" => "Listă autori",
            "new_button_label" => "Adaugă autor",
            "columns" => $columns,
            "new_url" => "/dashboard.php?meniu=autori&actiune=adauga",
            "modify_url" => "/dashboard.php?meniu=autori&actiune=modifica&id=",
            "crud" => array(
                "url" => "/librarian_crud_post.php",
                "delete" => array(
                    "confirm_message" => "Ești sigur că vrei să ștergi autorul selectat? Cărțile acestui autor vor rămâne in bibliotecă, dar nu îl vor mai avea drept autor."
                )
            )
        );
        $table_view->render_table($author_controller->get_all(), $metadata);
    } else if ($action === "modifica") {
        $id = $_GET["id"];
        $fields = array(
            "name" => array(
                "label" => "Nume",
                "type" => "text",
                "required" => true,
            )
        );
        $metadata = array(
            "page_title" => "Editează autor",
            "fields" => $fields
        );
        $data = array(
            "instance" => $author_controller->get_by_id($id)
        );
        $edit_view->render($data, $metadata);
    } else if ($action === "adauga") {
        $fields = array(
            "name" => array(
                "label" => "Nume",
                "type" => "text",
                "required" => true,
            )
        );
        $metadata = array(
            "page_title" => "Adaugă autor",
            "fields" => $fields,
            "source" => "autori",
            "crud" => array(
                "url" => "/librarian_crud_post.php",
                "after_add_url" => "/dashboard.php?meniu=autori&actiune=vezi"
            )
        );
        $data = array(
            "instance" => new stdClass()
        );
        $add_view->render($data, $metadata);
    } else {
        SecurityHelper::redirect_to_404();
    }
} //---------------------------------------- PUBLISHERI ----------------------------------------
else if ($menu === "publisheri") {
    // Authors
    $publisher_controller = new PublisherController();
    $columns = array(
        "publisher_id" => array(
            "label" => "ID",
            "width" => 60
        ),
        "name" => array(
            "label" => "Nume"
        )
    );

    if ($action === "vezi") {
        $metadata = array(
            "page_title" => "Listă publisheri",
            "new_button_label" => "Adaugă publisher",
            "columns" => $columns,
            "modify_url" => "/dashboard.php?meniu=publisheri&actiune=modifica&id=",
            "source" => "publisheri",
            "crud" => array(
                "url" => "/librarian_crud_post.php",
                "delete" => array(
                    "confirm_message" => "Ești sigur că vrei să ștergi publisherul selectat?"
                )
            )
        );
        $table_view->render_table($publisher_controller->get_all(), $metadata);
    } else {
        SecurityHelper::redirect_to_404();
    }
} //---------------------------------------- CATEGORII ----------------------------------------
else if ($menu === "categorii") {
    // Authors
    $category_controller = new CategoryController();
    $columns = array(
        "category_id" => array(
            "label" => "ID",
            "width" => 60
        ),
        "name" => array(
            "label" => "Nume"
        )
    );

    if ($action === "vezi") {
        $metadata = array(
            "page_title" => "Listă categorii",
            "new_button_label" => "Adaugă categorie",
            "columns" => $columns,
            "modify_url" => "/dashboard.php?meniu=categorii&actiune=modifica&id=",
            "source" => "categorii",
            "crud" => array(
                "url" => "/librarian_crud_post.php",
                "delete" => array(
                    "confirm_message" => "Ești sigur că vrei să ștergi categoria selectată?"
                )
            )
        );
        $table_view->render_table($category_controller->get_all(), $metadata);
    } else {
        SecurityHelper::redirect_to_404();
    }
} else {
    SecurityHelper::redirect_to_404();
}
create_footer();
?>