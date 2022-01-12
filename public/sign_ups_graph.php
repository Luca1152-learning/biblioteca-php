<?php

include_once __DIR__ . '/../src/components/header.php';
require __DIR__ . '/../vendor/autoload.php';

use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;

SecurityHelper::assert_is_admin();

$db = (new Database())->get_handle();
$query = $db->prepare("
        SELECT sign_up_date, COUNT(*)
        FROM users u
        GROUP BY sign_up_date;
    ");
$query->execute();

$plot_legend = array();
$plot_values = array();

$query->bind_result($id, $nr);
while ($query->fetch()) {
    array_push($plot_legend, $id);
    array_push($plot_values, $nr);
}

// Create the Pie Graph.
$graph = new Graph\PieGraph(400, 450);
$graph->title->Set("Utilizatori noi per zile");
$graph->SetBox(true);

$p1 = new Plot\PiePlot($plot_values);
$p1->SetLegends($plot_legend);
$p1->ShowBorder();
$p1->SetColor('black');

$graph->Add($p1);
$graph->Stroke();

?>