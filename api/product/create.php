<?php
/**
 * Create
 *
 * @author  PineappleSoft
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// required headers
//header("Access-Control-Allow-Origin: *"); //Uncomment/edit if APP and API are on different domains
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Validate user
include_once __DIR__ . '/../users/validate_user.php';

// include database and classes
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../class/product.php';

$product = new Product($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
  !empty($data->name) &&
  !empty($data->price) &&
  !empty($data->description) &&
  !empty($data->category_id)
){

  // set product property values
  $product->name = $data->name;
  $product->price = $data->price;
  $product->description = $data->description;
  $product->category_id = $data->category_id;
  $product->created = date('Y-m-d H:i:s');

  // create the product
  if($product->create()){

    // set response code - 201 created
    http_response_code(201);

    // tell the user
    echo json_encode(array("message" => "Product was created."));

  } else {

    // set response code - 503 service unavailable
    http_response_code(503);

    // tell the user
    echo json_encode(array("message" => "Unable to create product."));
    }

} else {

  // set response code - 400 bad request
  http_response_code(400);

  // tell the user
  echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
}
?>
