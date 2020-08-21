<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../secure.php");
include("../open_db.php");

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

//Get column names
$colNames = array();
$res = $db->query('PRAGMA table_info(postage);'); 

while ($row = $res->fetchArray()) {
    array_push($colNames, $row['name']);
}

// output the column headings
fputcsv($output, $colNames);

// fetch the data
$res = $db->query('SELECT * FROM postage');

// loop over the rows, outputting them
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
  fputcsv($output, $row);
}

?>