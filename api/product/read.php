<?php
/**
 * Read
 *
 * @author  PineappleSoft
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// required headers
//header("Access-Control-Allow-Origin: *"); //Uncomment/edit if APP and API are on different domains
header("Content-Type: application/json; charset=UTF-8");

// include database and classes
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../class/product.php';

$product = new Product($db);

// query products and return an SQLiteResult object
$result = $product->read();
$numOfColumns = $result->numColumns();

// check if records are returned
if($numOfColumns>0){
  // products array
  $products_arr=array();
  $products_arr["records"]=array();

  // retrieve table contents
  while ($row = $result->fetchArray(SQLITE3_ASSOC)){
    // extract row (this will make $row['name'] to just $name only)
    extract($row);

    $product_item=array(
      "id" => $id,
      "name" => $name,
      "description" => html_entity_decode($description),
      "price" => $price,
      "category_id" => $category_id,
      "category_name" => $category_name
    );

    array_push($products_arr["records"], $product_item);
  }

  // set response code - 200 OK
  http_response_code(200);

  // show products data in json format
  echo json_encode($products_arr);

} else {
  // set response code - 404 Not found
  http_response_code(404);

  // tell the user no results found
  echo json_encode(
      array("message" => "No results found.")
  );
}
