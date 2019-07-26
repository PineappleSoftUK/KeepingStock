<?php
/**
 * Initialise the db: opens existing db or creates new. 
 * Also creates a set of tables if they don't yet exist.
 */
 
 //Firstly create new db if needed...
class ConstructDB extends SQLite3
{
  function __construct()
  {
    $this->open('keepingstock.db');
  }
}

$db = new ConstructDB();

//Now create the empty tables if needed...

//SKU Table
$db->exec('CREATE TABLE IF NOT EXISTS sku (id INTEGER PRIMARY KEY AUTOINCREMENT, description VARCHAR(255))');

//Purchase Table
$db->exec('CREATE TABLE IF NOT EXISTS purchase (id INTEGER PRIMARY KEY AUTOINCREMENT, sku INTEGER, date DATE, quantity INTEGER, cost REAL)');

?>