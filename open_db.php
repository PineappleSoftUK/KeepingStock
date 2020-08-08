<?php
/**
 * Initialise the db: opens existing db or creates new. 
 * Also creates a set of tables if they don't yet exist.
 */

//The following statement is needed to show GBP money symbol
setlocale(LC_MONETARY,'en_GB.UTF-8');
 
//Firstly create new db if needed...
class ConstructDB extends SQLite3
{
  function __construct()
  {
    $this->open('keepingstock.db');
  }
}

$db = new ConstructDB();



// Check to see if 'sku' table exists
$tableCheck = $db->query("SELECT name FROM sqlite_master WHERE name='sku'");

if ($tableCheck->fetchArray() === false){
  
  //SKU Table Doesnt Exist!
  //Set up the following tables...

  //SKU Table
  $db->exec('CREATE TABLE IF NOT EXISTS sku (id INTEGER PRIMARY KEY AUTOINCREMENT, description VARCHAR(255), postage INTEGER)');

  //Purchase/Individual Item Table
  $db->exec('CREATE TABLE IF NOT EXISTS purchase (id INTEGER PRIMARY KEY AUTOINCREMENT, sku INTEGER, variant VARCHAR(255), date DATE, quantity INTEGER, cost REAL)');

  //Postage Rates Table
  $db->exec('CREATE TABLE IF NOT EXISTS postage (id INTEGER PRIMARY KEY AUTOINCREMENT, description VARCHAR(255), price REAL)');
  
  //Settings table
  $db->exec('CREATE TABLE IF NOT EXISTS settings (id INTEGER PRIMARY KEY AUTOINCREMENT, packagingfee REAL, margin REAL, ebaypercentage REAL, paypalpercentage REAL, paypalflatfee REAL)');
  
  //Populate postage types
  
  $types = array(
  
  "1st class stamp" => 0.76,
  "2nd class stamp" => 0.65,
  "2nd class large letter 100g" => 0.88,
  "2nd class large letter 250g" => 1.40,
  "2nd class large letter 500g" => 1.83,
  "2nd class large letter 750g" => 2.48,
  "2nd class small parcel 1kg" => 3.10,
  "2nd class small parcel 2kg" => 3.10,
  "1st class large letter 100g" => 1.15,
  "1st class large letter 250g" => 1.64,
  "1st class large letter 500g" => 2.14,
  "1st class large letter 750g" => 2.95,
  "1st class small parcel 1kg" => 3.70,
  "1st class small parcel 2kg" => 5.57,
  "2nd class medium parcel 1kg" => 5.50,
  "2nd class medium parcel 2kg" => 5.20,
  "2nd class medium parcel 5kg" => 8.99,
  "2nd class medium parcel 10kg" => 20.25,
  "2nd class medium parcel 20kg" => 28.55,
  "1st class medium parcel 1kg" => 5.90,
  "1st class medium parcel 2kg" => 9.02,
  "1st class medium parcel 5kg" => 15.85,
  "1st class medium parcel 10kg" => 21.90,
  "1st class medium parcel 20kg" => 33.40,
  "Signed for - 1st class stamp" => 2.06,
  "Signed for - 2nd class stamp" => 1.95,
  "Signed for - 2nd class large letter 100g" => 2.18,
  "Signed for - 2nd class large letter 250g" => 2.70,
  "Signed for - 2nd class large letter 500g" => 3.13,
  "Signed for - 2nd class large letter 750g" => 3.78,
  "Signed for - 2nd class small parcel 1kg" => 4.10,
  "Signed for - 2nd class small parcel 2kg" => 4.10,
  "Signed for - 1st class large letter 100g" => 2.45,
  "Signed for - 1st class large letter 250g" => 2.94,
  "Signed for - 1st class large letter 500g" => 3.44,
  "Signed for - 1st class large letter 750g" => 4.25,
  "Signed for - 1st class small parcel 1kg" => 4.70,
  "Signed for - 1st class small parcel 2kg" => 6.57,
  "Signed for - 2nd class medium parcel 1kg" => 6.20,
  "Signed for - 2nd class medium parcel 2kg" => 6.20,
  "Signed for - 2nd class medium parcel 5kg" => 9.99,
  "Signed for - 2nd class medium parcel 10kg" => 21.25,
  "Signed for - 2nd class medium parcel 20kg" => 29.55,
  "Signed for - 1st class medium parcel 1kg" => 6.90,
  "Signed for - 1st class medium parcel 2kg" => 10.02,
  "Signed for - 1st class medium parcel 5kg" => 16.85,
  "Signed for - 1st class medium parcel 10kg" => 22.90,
  "Signed for - 1st class medium parcel 20kg" => 34.40
  );
  
  $stmt = $db->prepare('INSERT INTO postage (description, price) VALUES (:description, :price)');
  
  foreach ($types as $key => $value) {
    $stmt->bindValue(':description', $key);
    $stmt->bindValue(':price', $value);
    $result = $stmt->execute();
  }
  
  //Populate default settings
  
  $stmt = $db->prepare('INSERT INTO settings (packagingfee, margin, ebaypercentage, paypalpercentage, paypalflatfee) VALUES (:packagingfee, :margin, :ebaypercentage, :paypalpercentage, :paypalflatfee)');
  
  $stmt->bindValue(':packagingfee', 0.10);
  $stmt->bindValue(':margin', 0.50);
  $stmt->bindValue(':ebaypercentage', 0.10);
  $stmt->bindValue(':paypalpercentage', 0.034);
  $stmt->bindValue(':paypalflatfee', 0.20);
  $result = $stmt->execute();
    
} 

?>