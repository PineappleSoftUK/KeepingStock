<?php
/**
 * Read one
 *
 * @author  PineappleSoft
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// required headers
//header("Access-Control-Allow-Origin: *"); //Uncomment/edit if APP and API are on different domains
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and classes
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../class/product.php';

$product = new Product($db);

// set ID property of record to read
$product->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of product to be edited
$product->readOne();

if($product->name!=null){
  // create array
  $product_arr = array(
      "id" =>  $product->id,
      "name" => $product->name,
      "description" => $product->description,
      "price" => $product->price,
      "category_id" => $product->category_id,
      "category_name" => $product->category_name
  );

  // set response code - 200 OK
  http_response_code(200);

  // make it json format
  echo json_encode($product_arr);

} else {

  // set response code - 404 Not found
  http_response_code(404);

  // tell the user product does not exist
  echo json_encode(array("message" => "Product does not exist."));
}
?>
