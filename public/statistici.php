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
        <img src="/sign_ups_graph.php" alt="Grafic înregistrări">
        <img src="/borrows_graph.php" alt="Grafic împrumuturi">
    </main>
<?php
create_footer();

$stats_helper = new StatsHelper();
echo $stats_helper->get_views_count_today() . ' ' . $stats_helper->get_unique_visitors_today();
?>