<?php
include_once __DIR__ . '/../src/components/header.php';
include_once __DIR__ . '/../src/components/footer.php';
include_once __DIR__ . '/../src/controllers/UserController.php';
include_once __DIR__ . '/../src/controllers/AuthorController.php';
include_once __DIR__ . '/../src/controllers/BookController.php';
include_once __DIR__ . '/../src/controllers/CategoryController.php';
include_once __DIR__ . '/../src/controllers/PublisherController.php';
include_once __DIR__ . '/../src/controllers/CopyController.php';
include_once __DIR__ . '/../src/controllers/BorrowController.php';
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
    $user_controller = new UserController();

    // ********************** TABEL **********************
    if ($action === "vezi") {
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
                "label" => "Dat?? ??nregistrare",
                "centered" => true,
                "type" => "date"
            )
        );

        $metadata = array(
            "page_title" => "List?? utilizatori",
            "columns" => $columns,
            "modify_url" => "/dashboard.php?meniu=utilizatori&actiune=modifica&id=",
            "source" => "utilizatori",
            "crud" => array(
                "url" => "/admin_crud_post.php",
                "delete" => array(
                    "confirm_message" => "E??ti sigur c?? vrei s?? ??tergi utilizatorul selectat?"
                )
            )
        );
        $users = $user_controller->get_all();
        $filtered_users = array_filter($users, function ($it) {
            return $it->role !== "administrator";
        });
        $table_view->render_table($filtered_users, $metadata);
    } // ********************** UPDATE **********************
    else if ($action === "modifica") {
        $id = $_GET["id"];
        $fields = array(
            "last_name" => array(
                "label" => "Nume",
                "type" => "text",
            ),
            "first_name" => array(
                "label" => "Prenume",
                "type" => "text",
            ),
            "email" => array(
                "label" => "Email",
                "type" => "text",
            ),
            "role" => array(
                "label" => "Rol",
                "type" => "text-autocomplete",
                "field_name" => "name",
                "required" => true
            ),
        );
        $metadata = array(
            "page_title" => "Editeaz?? utilizator",
            "fields" => $fields,
            "source" => "utilizatori",
            "crud" => array(
                "url" => "/admin_crud_post.php",
                "after_update_url" => "/dashboard.php?meniu=utilizatori&actiune=vezi"
            )
        );

        // Work-around to make the interface work with the current View structure
        $instance = $user_controller->get_by_id($id);
        $instance->role = array(
            "name" => $instance->role
        );

        $data = array(
            "instance" => $instance,
            "all" => array(
                "role" => [array("name" => "user"), array("name" => "bibliotecar"), array("name" => "administrator")]
            )
        );
        $edit_view->render($data, $metadata);
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

    // ********************** TABEL **********************
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
            "total_copies_count" => array(
                "label" => "Copii",
                "type" => "number",
                "centered" => true,
            ),
        );
        $metadata = array(
            "page_title" => "List?? c??r??i",
            "new_button_label" => "Adaug?? carte",
            "columns" => $columns,
            "modify_url" => "/dashboard.php?meniu=carti&actiune=modifica&id=",
            "new_url" => "/dashboard.php?meniu=carti&actiune=adauga",
            "source" => "carti",
            "crud" => array(
                "url" => "/librarian_crud_post.php",
                "delete" => array(
                    "confirm_message" => "E??ti sigur c?? vrei s?? ??tergi cartea selectat???"
                )
            )
        );
        $table_view->render_table($book_controller->get_all(), $metadata);
    } // ********************** UPDATE **********************
    else if ($action === "modifica") {
        $id = $_GET["id"];
        $fields = array(
            "title" => array(
                "label" => "Titlu",
                "type" => "text",
                "required" => true,
            ),
            "authors" => array(
                "label" => "Autori",
                "add_label" => "Adaug?? autor",
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
                "add_label" => "Adaug?? categorie",
                "type" => "list",
                "field_name" => "name",
            ),
            "cover_url" => array(
                "label" => "URL Copert??",
                "type" => "text"
            ),
            "publication_year" => array(
                "label" => "Anul public??rii",
                "type" => "number",
                "min_value_number" => 1,
            ),
            "pages_count" => array(
                "label" => "Num??r pagini",
                "type" => "number",
                "min_value_number" => 1,
            ),
        );
        $metadata = array(
            "page_title" => "Editeaz?? carte",
            "fields" => $fields,
            "source" => "carti",
            "crud" => array(
                "url" => "/librarian_crud_post.php",
                "after_update_url" => "/dashboard.php?meniu=carti&actiune=vezi"
            )
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
    } // ********************** INSERT **********************
    else if ($action === "adauga") {
        $fields = array(
            "title" => array(
                "label" => "Titlu",
                "type" => "text",
                "required" => true,
            ),
            "authors" => array(
                "label" => "Autori",
                "add_label" => "Adaug?? autor",
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
                "add_label" => "Adaug?? categorie",
                "type" => "list",
                "field_name" => "name",
            ),
            "cover_url" => array(
                "label" => "URL Copert??",
                "type" => "text"
            ),
            "publication_year" => array(
                "label" => "Anul public??rii",
                "type" => "number",
                "min_value_number" => 1,
            ),
            "pages_count" => array(
                "label" => "Num??r pagini",
                "type" => "number",
                "min_value_number" => 1,
            ),
        );
        $metadata = array(
            "page_title" => "Adaug?? carte",
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

    // ********************** TABEL **********************
    if ($action === "vezi") {
        $metadata = array(
            "source" => "autori",
            "page_title" => "List?? autori",
            "new_button_label" => "Adaug?? autor",
            "columns" => $columns,
            "new_url" => "/dashboard.php?meniu=autori&actiune=adauga",
            "modify_url" => "/dashboard.php?meniu=autori&actiune=modifica&id=",
            "crud" => array(
                "url" => "/librarian_crud_post.php",
                "delete" => array(
                    "confirm_message" => "E??ti sigur c?? vrei s?? ??tergi autorul selectat? C??r??ile acestui autor vor r??m??ne in bibliotec??, dar nu ??l vor mai avea drept autor."
                )
            )
        );
        $table_view->render_table($author_controller->get_all(), $metadata);
    } // ********************** UPDATE **********************
    else if ($action === "modifica") {
        $id = $_GET["id"];
        $fields = array(
            "name" => array(
                "label" => "Nume",
                "type" => "text",
                "required" => true,
            )
        );
        $metadata = array(
            "page_title" => "Modific?? autor",
            "fields" => $fields,
            "source" => "autori",
            "crud" => array(
                "url" => "/librarian_crud_post.php",
                "after_update_url" => "/dashboard.php?meniu=autori&actiune=vezi"
            )
        );
        $data = array(
            "instance" => $author_controller->get_by_id($id)
        );
        $edit_view->render($data, $metadata);
    } // ********************** INSERT **********************
    else if ($action === "adauga") {
        $fields = array(
            "name" => array(
                "label" => "Nume",
                "type" => "text",
                "required" => true,
            )
        );
        $metadata = array(
            "page_title" => "Adaug?? autor",
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

    // ********************** TABEL **********************
    if ($action === "vezi") {
        $metadata = array(
            "page_title" => "List?? publisheri",
            "new_button_label" => "Adaug?? publisher",
            "columns" => $columns,
            "modify_url" => "/dashboard.php?meniu=publisheri&actiune=modifica&id=",
            "new_url" => "/dashboard.php?meniu=publisheri&actiune=adauga",
            "source" => "publisheri",
            "crud" => array(
                "url" => "/librarian_crud_post.php",
                "delete" => array(
                    "confirm_message" => "E??ti sigur c?? vrei s?? ??tergi publisherul selectat?"
                )
            )

        );
        $table_view->render_table($publisher_controller->get_all(), $metadata);
    } // ********************** INSERT **********************
    else if ($action === "adauga") {
        $fields = array(
            "name" => array(
                "label" => "Nume",
                "type" => "text",
                "required" => true,
            )
        );
        $metadata = array(
            "page_title" => "Adaug?? publisher",
            "fields" => $fields,
            "source" => "publisheri",
            "crud" => array(
                "url" => "/librarian_crud_post.php",
                "after_add_url" => "/dashboard.php?meniu=publisheri&actiune=vezi"
            )
        );
        $data = array(
            "instance" => new stdClass()
        );
        $add_view->render($data, $metadata);
    } // ********************** UPDATE **********************
    else if ($action === "modifica") {
        $id = $_GET["id"];
        $fields = array(
            "name" => array(
                "label" => "Nume",
                "type" => "text",
                "required" => true,
            )
        );
        $metadata = array(
            "page_title" => "Modific?? publisher",
            "fields" => $fields,
            "source" => "publisheri",
            "crud" => array(
                "url" => "/librarian_crud_post.php",
                "after_update_url" => "/dashboard.php?meniu=publisheri&actiune=vezi"
            )
        );
        $data = array(
            "instance" => $publisher_controller->get_by_id($id)
        );
        $edit_view->render($data, $metadata);
    } else {
        SecurityHelper::redirect_to_404();
    }
} //---------------------------------------- CATEGORII ----------------------------------------
else if ($menu === "categorii") {
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

    // ********************** TABEL **********************
    if ($action === "vezi") {
        $metadata = array(
            "page_title" => "List?? categorii",
            "new_button_label" => "Adaug?? categorie",
            "columns" => $columns,
            "modify_url" => "/dashboard.php?meniu=categorii&actiune=modifica&id=",
            "new_url" => "/dashboard.php?meniu=categorii&actiune=adauga",
            "source" => "categorii",
            "crud" => array(
                "url" => "/librarian_crud_post.php",
                "delete" => array(
                    "confirm_message" => "E??ti sigur c?? vrei s?? ??tergi categoria selectat???"
                )
            )
        );
        $table_view->render_table($category_controller->get_all(), $metadata);
    } // ********************** INSERT **********************
    else if ($action === "adauga") {
        $fields = array(
            "name" => array(
                "label" => "Nume",
                "type" => "text",
                "required" => true,
            )
        );
        $metadata = array(
            "page_title" => "Adaug?? categorie",
            "fields" => $fields,
            "source" => "categorii",
            "crud" => array(
                "url" => "/librarian_crud_post.php",
                "after_add_url" => "/dashboard.php?meniu=categorii&actiune=vezi"
            )
        );
        $data = array(
            "instance" => new stdClass()
        );
        $add_view->render($data, $metadata);
    } // ********************** UPDATE **********************
    else if ($action === "modifica") {
        $id = $_GET["id"];
        $fields = array(
            "name" => array(
                "label" => "Nume",
                "type" => "text",
                "required" => true,
            )
        );
        $metadata = array(
            "page_title" => "Editeaz?? categorie",
            "fields" => $fields,
            "source" => "categorii",
            "crud" => array(
                "url" => "/librarian_crud_post.php",
                "after_update_url" => "/dashboard.php?meniu=categorii&actiune=vezi"
            )
        );
        $data = array(
            "instance" => $category_controller->get_by_id($id)
        );
        $edit_view->render($data, $metadata);
    } else {
        SecurityHelper::redirect_to_404();
    }
} //---------------------------------------- COPII ----------------------------------------
else if ($menu === "copii") {
    $copy_controller = new CopyController();
    $book_controller = new BookController();

    // ********************** TABEL **********************
    if ($action === "vezi") {
        $columns = array(
            "copy_id" => array(
                "label" => "ID",
                "width" => 60
            ),
            "book.title" => array(
                "label" => "Nume"
            ),
            "date_added" => array(
                "label" => "Ad??ugat?? la",
                "type" => "date"
            ),
            "comments" => array(
                "label" => "Comentarii"
            ),
        );

        $metadata = array(
            "page_title" => "List?? copii",
            "new_button_label" => "Adaug?? copii",
            "columns" => $columns,
            "modify_url" => "/dashboard.php?meniu=copii&actiune=modifica&id=",
            "new_url" => "/dashboard.php?meniu=copii&actiune=adauga",
            "source" => "copii",
            "crud" => array(
                "url" => "/librarian_crud_post.php",
                "delete" => array(
                    "confirm_message" => "E??ti sigur c?? vrei s?? ??tergi copia selectat???"
                )
            )
        );
        $table_view->render_table($copy_controller->get_all(), $metadata);
    } // ********************** INSERT **********************
    else if ($action === "adauga") {
        $fields = array(
            "book" => array(
                "label" => "Carte",
                "type" => "text-autocomplete",
                "field_name" => "title",
                "required" => true
            ),
            "number" => array(
                "label" => "Num??r copii",
                "type" => "number",
                "min_value_number" => 1,
                "required" => true
            )
        );
        $metadata = array(
            "page_title" => "Adaug?? copii",
            "fields" => $fields,
            "source" => "copii",
            "crud" => array(
                "url" => "/librarian_crud_post.php",
                "after_add_url" => "/dashboard.php?meniu=copii&actiune=vezi"
            )
        );
        $data = array(
            "instance" => array(
                "book" => array(
                    "book_id" => null,
                    "title" => ""
                ),
                "number" => 1,
            ),
            "all" => array(
                "book" => $book_controller->get_all()
            )
        );
        $add_view->render($data, $metadata);
    } // ********************** UPDATE **********************
    else if ($action === "modifica") {
        $id = $_GET["id"];
        $fields = array(
            "comments" => array(
                "label" => "Comentarii",
                "type" => "text",
            ),
        );
        $metadata = array(
            "page_title" => "Editeaz?? copie",
            "fields" => $fields,
            "source" => "copii",
            "crud" => array(
                "url" => "/librarian_crud_post.php",
                "after_update_url" => "/dashboard.php?meniu=copii&actiune=vezi"
            )
        );
        $data = array(
            "instance" => $copy_controller->get_by_id($id)
        );
        $edit_view->render($data, $metadata);
    } else {
        SecurityHelper::redirect_to_404();
    }
}  //---------------------------------------- IMPRUMUTURI ----------------------------------------
else if ($menu === "imprumuturi") {
    $borrow_controller = new BorrowController();

    // ********************** TABEL **********************
    if ($action === "vezi") {
        $columns = array(
            "borrow_id" => array(
                "label" => "ID",
                "width" => 60
            ),
            "user_full_name" => array(
                "label" => "Utilizator",
            ),
            "book_title" => array(
                "label" => "Carte"
            ),
            "borrow_date" => array(
                "label" => "??mprumutat?? la",
                "type" => "date",
            ),
            "return_due_date" => array(
                "label" => "De returnat la",
                "type" => "date",
            ),
            "return_date" => array(
                "label" => "Returnat?? la",
                "type" => "date",
            ),
        );

        $metadata = array(
            "page_title" => "List?? ??mprumuturi",
            "columns" => $columns,
            "modify_url" => "/dashboard.php?meniu=imprumuturi&actiune=modifica&id=",
            "source" => "imprumuturi",
            "crud" => array(
                "url" => "/librarian_crud_post.php",
                "delete" => array(
                    "confirm_message" => "E??ti sigur c?? vrei s?? ??tergi ??mprumutul selectat?"
                )
            )
        );
        $table_view->render_table($borrow_controller->get_all(), $metadata);
    } // ********************** UPDATE **********************
    else if ($action === "modifica") {
        $id = $_GET["id"];
        $fields = array(
            "borrow_date" => array(
                "label" => "??mprumutat?? la",
                "type" => "date",
                "required" => true,
            ),
            "return_due_date" => array(
                "label" => "De returnat la",
                "type" => "date",
                "required" => true,
            ),
            "return_date" => array(
                "label" => "Returnat?? la",
                "type" => "date"
            )
        );
        $metadata = array(
            "page_title" => "Editeaz?? ??mprumut",
            "fields" => $fields,
            "source" => "imprumuturi",
            "crud" => array(
                "url" => "/librarian_crud_post.php",
                "after_update_url" => "/dashboard.php?meniu=imprumuturi&actiune=vezi"
            )
        );
        $data = array(
            "instance" => $borrow_controller->get_by_id($id)
        );
        $edit_view->render($data, $metadata);
    } else {
        SecurityHelper::redirect_to_404();
    }
} else {
    SecurityHelper::redirect_to_404();
}
create_footer();
?>