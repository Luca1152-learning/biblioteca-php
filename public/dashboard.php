<?php
include_once __DIR__ . '/../src/components/header.php';
include_once __DIR__ . '/../src/components/footer.php';

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

create_header("Lib - Dashboard");
?>
    <main class="section">
        <p class="is-size-4 has-text-black has-text-weight-semibold pb-2">
            Dashboard
        </p>
        <p><?php print_r($_GET); ?></p>
    </main>
<?php
create_footer();
?>