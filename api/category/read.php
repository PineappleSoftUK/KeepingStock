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
include_once __DIR__ . '/../class/category.php';

$category = new Category($db);

// query categorys and return an SQLiteResult object
$result = $category->read();
$numOfColumns = $result->numColumns();

// check if records are returned
if($numOfColumns>0){
  // categorys array
  $categorys_arr=array();
  $categorys_arr["records"]=array();

  // retrieve table contents
  while ($row = $result->fetchArray(SQLITE3_ASSOC)){
    // extract row (this will make $row['name'] to just $name only)
    extract($row);

    $category_item=array(
      "id" => $id,
      "name" => $name,
      "description" => html_entity_decode($description),
    );

    array_push($categorys_arr["records"], $category_item);
  }

  // set response code - 200 OK
  http_response_code(200);

  // show categorys data in json format
  echo json_encode($categorys_arr);

} else {
  // set response code - 404 Not found
  http_response_code(404);

  // tell the user no results found
  echo json_encode(
      array("message" => "No results found.")
  );
}
