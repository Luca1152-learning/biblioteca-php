<?php
include_once __DIR__ . '/../src/components/header.php';
include_once __DIR__ . '/../src/components/footer.php';
include_once __DIR__ . '/../src/controllers/UserController.php';
include_once __DIR__ . '/../src/views/dashboard/TableView.php';

if (!SecurityHelper::is_librarian() && !SecurityHelper::is_admin()) {
    SecurityHelper::redirect_to_403();
}
if (!isset($_GET) || !isset($_GET["meniu"]) || !isset($_GET["actiune"])) {
    SecurityHelper::redirect_to_404();
}

$menu = $_GET["meniu"];
if ($menu === "utilizatori") {
    SecurityHelper::assert_is_admin();
}

$action = $_GET["actiune"];

$table_view = new TableView();
$user_controller = new UserController();

create_header("Lib - Dashboard");

if ($menu === "utilizatori") {
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
        "sign_up_date" => array(
            "label" => "Dată înregistrare",
            "centered" => true,
            "type" => "date"
        )
    );

    if ($action === "vezi") {
        $table_view->render_table("Listă utilizatori", "", $user_controller->get_all(), $columns);
    }
}

create_footer();
?>