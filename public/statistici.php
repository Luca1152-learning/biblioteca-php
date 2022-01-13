<?php
include_once __DIR__ . '/../src/components/header.php';
include_once __DIR__ . '/../src/components/footer.php';

SecurityHelper::assert_is_admin();
$stats_helper = new StatsHelper();

create_header("Lib - Statistici");
?>
    <main class="section">
        <p class="is-size-4 has-text-black has-text-weight-semibold pb-2">
            Statistici site
        </p>
        <img src="/sign_ups_graph.php" alt="Grafic înregistrări">
        <img src="/borrows_graph.php" alt="Grafic împrumuturi">
        <table class="table">
            <thead>
            <tr>
                <th>Număr vizualizări</th>
                <th>Număr vizitatori unici</th>
                <th>Număr vizite</th>
            </tr>
            </thead>
            <tbody>
            <tr class="has-text-centered">
                <td><?php echo htmlspecialchars($stats_helper->get_views_count_today(), ENT_QUOTES); ?></td>
                <td><?php echo htmlspecialchars($stats_helper->get_unique_visitors_today(), ENT_QUOTES); ?></td>
                <td><?php echo htmlspecialchars($stats_helper->get_visits_count_today(), ENT_QUOTES); ?></td>
            </tr>
            </tbody>
        </table>
    </main>
<?php
create_footer();
?>