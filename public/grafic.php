<?php
require __DIR__ . '/../vendor/autoload.php';

use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;

// Database
$cleardb_url = parse_url("mysql://b9960eb5b31032:58fe0ff1@eu-cdbr-west-01.cleardb.com/heroku_b52a27f697a2c16?reconnect=true");
$cleardb_server = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db = substr($cleardb_url["path"], 1);
$db = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);

$query = $db->prepare("
    SELECT d.department_name, COUNT(*)
    FROM employees e
    JOIN departments d ON e.department_id = d.department_id
    GROUP BY d.department_name
    HAVING COUNT(*) > 6;
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
$graph->title->Set("Numar de angajati pe departamente");
$graph->SetBox(true);

$p1 = new Plot\PiePlot($plot_values);
$p1->SetLegends($plot_legend);
$p1->ShowBorder();
$p1->SetColor('black');

$graph->Add($p1);
$graph->Stroke();


?>