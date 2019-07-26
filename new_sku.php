<?php
if (isset($_POST['submit'])) {
  
  include("open_db.php");
  
  //Process form and add to db...
  $description = $_POST['itemdesc'];

  $stmt = $db->prepare('INSERT INTO sku (description) VALUES (:description)');
  $stmt->bindValue(':description', $description, SQLITE3_TEXT);
  $result = $stmt->execute();
  
  $idnumber = $db->lastInsertRowID();
  
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
	<title>Keeping Stock</title>
  </head>
  <body>
    <h1>Keeping Stock</h1>
    <h2>New SKU</h2>
    
    <p><b>SKU <?php echo $idnumber; ?></b> successfully added.</p>
    <p>Would you like to <a href="new_sku.php">add another?</a> or return to <a href="purchase.php">add a purchase</a>?</p>
    
  </body>
</html>

<?php
  
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
	<title>Keeping Stock</title>
  </head>
  <body>
    <h1>Keeping Stock</h1>
    <h2>New SKU</h2>
    
    <form action="new_sku.php" method="post">
      Item Description:
      <input type="text" name="itemdesc" placeholder="Item description...">
      <input type="submit" name="submit" value="Submit">
    </form>
    
  </body>
</html>