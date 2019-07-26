<?php
if (isset($_POST['submit'])) {
  
  include("open_db.php");
  
  //Process form and add to db...
  $sku = $_POST['sku'];
  $date = $_POST['date'];
  $quantity = $_POST['quantity'];
  $cost = $_POST['cost'];

  $stmt = $db->prepare('INSERT INTO purchase (sku, date, quantity, cost) VALUES (:sku, :date, :quantity, :cost)');
  $stmt->bindValue(':sku', $sku);
  $stmt->bindValue(':date', $date);
  $stmt->bindValue(':quantity', $quantity);
  $stmt->bindValue(':cost', $cost);
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
    <h2>New Purchase</h2>
    
    <p>Purchase successfully added with reference <b><?php echo $idnumber; ?></b></p>
    <p>Would you like to <a href="purchase.php">add another?</a> or return to <a href="index.php">home</a>?</p>
    
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
    <h2>Purchase</h2>
    
    <form method="post">
      SKU:
      <input type="text" name="sku" placeholder="SKU...">
      <a href="new_sku.php">New SKU</a><br>
      
      Date:
      <input type="date" name="date"><br>
      
      Quantity:
      <input type="number" name="quantity"><br>
      
      Cost (each):
      <input type="number" name="cost" step="0.01"><br>
      <input type="reset">
      <input type="submit" name="submit" value="Submit">
    </form>
    
  </body>
</html>