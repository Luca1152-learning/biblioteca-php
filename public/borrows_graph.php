<?php

include_once __DIR__ . '/../src/utils/database/Database.php';
include_once __DIR__ . '/../src/utils/security/SecurityHelper.php';
require __DIR__ . '/../vendor/autoload.php';

session_start();

use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;

SecurityHelper::assert_is_admin();

$db = (new Database())->get_handle();
$query = $db->prepare("
        SELECT DAY(borrow_date) zi, COUNT(*) nr
        FROM borrows b
        WHERE borrow_date IS NOT NULL AND 
              MONTH(borrow_date) = MONTH(CURRENT_DATE) AND YEAR(borrow_date) = YEAR(CURRENT_DATE)
        GROUP BY DAY(borrow_date)
        ORDER BY DAY(borrow_date)
    ");
$query->execute();

$values = array();
$query->bind_result($zi, $nr);
while ($query->fetch()) {
    $values[$zi] = $nr;
}

$plot_legend = array();
$plot_values = array();

for ($i = 1; $i <= date("t"); $i++) {
    array_push($plot_legend, $i);
    if (!isset($values[$i])) {
        array_push($plot_values, 0);
    } else {
        array_push($plot_values, $values[$i]);
    }
}

// Create the graph
$graph = new Graph\Graph(600, 200, 'auto');
$graph->SetShadow();
$graph->SetScale('textlin');
$graph->xaxis->SetTickLabels($plot_legend);
$current_month = date('M');
$graph->title->Set("Imprumuturi in luna {$current_month}");

// Create the bar plot
$b1 = new Plot\BarPlot($plot_values);
$b1->SetLegend('Număr împrumuturi');
$graph->Add($b1);

// Display the graph
$graph->Stroke();
?>