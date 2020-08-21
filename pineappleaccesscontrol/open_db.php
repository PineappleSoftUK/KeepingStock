<?php
/*
* A script that checks for a valid database and opens or creates it along with the necessary tables
*/

//Check if this is being called by the software
if(!$includes) {
  echo "You cannot open this file, it is part of the system and must be called by another file";
  exit();
}

//Security for includes
$includes = TRUE;

//Open or create database file
class ConstructDB extends SQLite3
{
  function __construct()
  {
    $this->open(__DIR__ . '/pac.db');
  }
}

$db = new ConstructDB();

//Check for faults table and if needed create the set of tables
$tableCheck = $db->query("SELECT name FROM sqlite_master WHERE name='users'");

if ($tableCheck->fetchArray() === false)
{
  //Users table
  $db->exec('CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT, username VARCHAR(255), hash VARCHAR(255), userType VARCHAR(255))');
  
  //Populate user table
  include __DIR__ . '/add_base_user.php';
}
?>