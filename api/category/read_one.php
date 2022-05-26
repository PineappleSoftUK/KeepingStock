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
include_once __DIR__ . '/../class/category.php';

$category = new Category($db);

// set ID property of record to read
$category->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of category to be edited
$category->readOne();

if($category->name!=null){
  // create array
  $category_arr = array(
      "id" =>  $category->id,
      "name" => $category->name,
      "description" => $category->description,
  );

  // set response code - 200 OK
  http_response_code(200);

  // make it json format
  echo json_encode($category_arr);

} else {

  // set response code - 404 Not found
  http_response_code(404);

  // tell the user category does not exist
  echo json_encode(array("message" => "Category does not exist."));
}
?>
