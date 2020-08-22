<?php
/**
 * Accepts a table name via HTTP Post 
 * Outputs a csv file download with SQLite table contents
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../secure.php");
include("../open_db.php");

// Set table name
$validTables = array();

$tablesquery = $db->query("SELECT name FROM sqlite_master WHERE type='table';");

while ($table = $tablesquery->fetchArray(SQLITE3_ASSOC)) {
    array_push($validTables, $table['name']);
}

//$hash = $_POST['table'];
$hash = "postage";

if (array_search($hash, $validTables)) {
  $table = $hash;
} else {
  $table = "";
}

// This ensures the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=export.csv');

// Create the file
$output = fopen('php://output', 'w');

// Get column names
$colNames = array();
$res = $db->query('PRAGMA table_info(' . $table . ');'); 

while ($row = $res->fetchArray()) {
    array_push($colNames, $row['name']);
}

// Output the column headings
fputcsv($output, $colNames);

// Fetch table rows
$res = $db->query("SELECT * FROM " . $table);

// Write rows to file
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
  fputcsv($output, $row);
}

?>