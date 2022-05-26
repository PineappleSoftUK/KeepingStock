<?php
/**
 * Product
 *
 * @author  PineappleSoft
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Product{

  // database connection and table name
  private $conn;
  private $table_name = "products";

  // object properties
  public $id;
  public $name;
  public $description;
  public $price;
  public $category_id;
  public $category_name;
  public $created;

  // constructor, $db as database connection
  public function __construct($db){
      $this->conn = $db;
  }

  /**
   * Read
   *
   * Reads and returns all items in database
   *
   * @return array
   */
  function read(){
    // select all query
    $query = <<<SQL
    SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
    FROM $this->table_name p
    LEFT JOIN categories c
    ON p.category_id = c.id
    ORDER BY p.created DESC
    SQL;

    // prepare and execute query statement
    $stmt = $this->conn->prepare($query);
    $result = $stmt->execute();

    return $result;
  }

  /**
   * Create
   *
   * Insert a new item into database
   *
   * @return boolean
   */
  function create(){
    // query to insert record
    $query = <<<SQL
    INSERT INTO $this->table_name (name, price, description, category_id, created)
    VALUES (:name, :price, :description, :category_id, :created)
    SQL;

    // prepare query
    $stmt = $this->conn->prepare($query);

    // sanitize inputs
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->price=htmlspecialchars(strip_tags($this->price));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->category_id=htmlspecialchars(strip_tags($this->category_id));
    $this->created=htmlspecialchars(strip_tags($this->created));

    // bind values
    $stmt->bindValue(":name", $this->name);
    $stmt->bindValue(":price", $this->price);
    $stmt->bindValue(":description", $this->description);
    $stmt->bindValue(":category_id", $this->category_id);
    $stmt->bindValue(":created", $this->created);

    // execute query
    if($stmt->execute()){
        return true;
    }

    return false;

  }

  /**
   * Read One
   *
   * Reads one item from database and returns
   *
   * @return array
   */
  function readOne(){

    // query to read single record
    $query = <<<SQL
    SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
    FROM $this->table_name p
    LEFT JOIN categories c
    ON p.category_id = c.id
    WHERE p.id = :productID
    LIMIT 0,1
    SQL;

    // prepare query statement
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':productID', $this->id);

    $result = $stmt->execute();

    // get retrieved row
    $row = $result->fetchArray(SQLITE3_ASSOC);

    // check if records are returned
    if(!empty($row)){

      // set values to object properties
      $this->name = $row['name'];
      $this->price = $row['price'];
      $this->description = $row['description'];
      $this->category_id = $row['category_id'];
      $this->category_name = $row['category_name'];
    }
  }

  /**
   * Update
   *
   * Updates item in database
   *
   * @return boolean
   */
  function update(){

    // update query
    $query = <<<SQL
    UPDATE $this->table_name
    SET
      name = :name,
      price = :price,
      description = :description,
      category_id = :category_id
    WHERE id = :id
    SQL;

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->price=htmlspecialchars(strip_tags($this->price));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->category_id=htmlspecialchars(strip_tags($this->category_id));
    $this->id=htmlspecialchars(strip_tags($this->id));

    // bind new values
    $stmt->bindValue(':name', $this->name);
    $stmt->bindValue(':price', $this->price);
    $stmt->bindValue(':description', $this->description);
    $stmt->bindValue(':category_id', $this->category_id);
    $stmt->bindValue(':id', $this->id);

    // execute the query
    if($stmt->execute()){
        return true;
    }

    return false;
  }


  /**
   * Delete
   *
   * Deletes the selected item from database
   *
   * @return boolean
   */
  function delete(){

    // delete query
    $query = <<<SQL
    DELETE FROM $this->table_name
    WHERE id = :id
    SQL;

    // prepare query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));

    // bind id of record to delete
    $stmt->bindValue(':id', $this->id);

    // execute query
    if($stmt->execute()){
        return true;
    }

    return false;
  }

  /**
   * Search
   *
   * Reads database and returns the items matching search key
   *
   * @param string $keywords The search term
   * @return array
   */
  function search($keywords){

    // select all query
    $query = <<<SQL
    SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
    FROM $this->table_name p
    LEFT JOIN categories c
    ON p.category_id = c.id
    WHERE p.name LIKE :productName OR p.description LIKE :productDescription OR c.name LIKE :categoryName
    ORDER BY p.created DESC
    SQL;

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // sanitize
    $keywords=htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";

    // bind
    $stmt->bindValue(':productName', $keywords);
    $stmt->bindValue(':productDescription', $keywords);
    $stmt->bindValue(':categoryName', $keywords);

    //Return results
    $result = $stmt->execute();

    return $result;

  }

  /**
   * Read Paging
   *
   * Reads database and returns the items along with pagination
   *
   * @param int $from_record_num   Record to start from
   * @param int $records_per_page  Number of records to return
   * @return array
   */
  public function readPaging($from_record_num, $records_per_page){

    // select query
    $query = <<<SQL
    SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
    FROM $this->table_name p
    LEFT JOIN categories c
    ON p.category_id = c.id
    ORDER BY p.created DESC
    LIMIT :fromNumber, :quantity
    SQL;

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // bind variable values
    $stmt->bindValue(':fromNumber', $from_record_num);
    $stmt->bindValue(':quantity', $records_per_page);

    // execute query
    $result = $stmt->execute();

    // return values from database
    return $result;
  }

  /**
   * Count
   *
   * A helper function used as part of pagination
   *
   * @return array
   */
  public function count(){
    $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";

    $stmt = $this->conn->prepare($query);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    return $row['total_rows'];
  }

}
?>
