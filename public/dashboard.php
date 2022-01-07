<?php
include_once __DIR__ . '/../src/components/header.php';
include_once __DIR__ . '/../src/components/footer.php';
include_once __DIR__ . '/../src/views/dashboard/UsersView.php';

if (!SecurityHelper::is_librarian() && !SecurityHelper::is_admin()) {
    SecurityHelper::redirect_to_403();
}
if (!isset($_GET) || !isset($_GET["meniu"])) {
    SecurityHelper::redirect_to_404();
}

$meniu = $_GET["meniu"];
if ($meniu === "utilizatori") {
    SecurityHelper::assert_is_admin();
}

$users_view = new UsersView();

create_header("Lib - Dashboard");

if ($meniu === "utilizatori") {
    $users_view->render_users_table();
}

create_footer();
?>