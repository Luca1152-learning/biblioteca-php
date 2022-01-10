<?php
include_once __DIR__ . '/../src/components/header.php';
include_once __DIR__ . '/../src/components/footer.php';

SecurityHelper::assert_is_admin();

create_header("Lib - Statistici");
?>
    <main class="section">
        <p class="is-size-4 has-text-black has-text-weight-semibold pb-2">
            Statistici site
        </p>
        <p>TODO</p>
    </main>
<?php
create_footer();
?>