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
include_once __DIR__ . '/../class/category.php';

$category = new Category($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
  !empty($data->name) &&
  !empty($data->description)
){

  // set category property values
  $category->name = $data->name;
  $category->description = $data->description;
  $category->created = date('Y-m-d H:i:s');

  // create the category
  if($category->create()){

    // set response code - 201 created
    http_response_code(201);

    // tell the user
    echo json_encode(array("message" => "Category was created."));

  } else {

    // set response code - 503 service unavailable
    http_response_code(503);

    // tell the user
    echo json_encode(array("message" => "Unable to create category."));
    }

} else {

  // set response code - 400 bad request
  http_response_code(400);

  // tell the user
  echo json_encode(array("message" => "Unable to create category. Data is incomplete."));
}
?>
