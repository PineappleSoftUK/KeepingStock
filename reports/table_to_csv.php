<?php
/**
 * Accepts a table name via HTTP Post 
 * Outputs a csv file download with SQLite table contents
 */

include("../secure.php");
include("../open_db.php");

// Set table name, the supplied table name will be checked against a whitelist
// First populate the whitelist
$validTables = array();

$tablesquery = $db->query("SELECT name FROM sqlite_master WHERE type='table';");

while ($tableName = $tablesquery->fetchArray(SQLITE3_ASSOC)) {
    array_push($validTables, $tableName['name']);
}

$hash = $_POST['table'];

// Check the value is in the whitelist
if (array_search($hash, $validTables)) {
  $table = $hash;
} else {
  echo "Error: Invalid table name supplied";
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