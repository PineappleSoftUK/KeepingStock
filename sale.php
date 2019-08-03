<?php
if (isset($_POST['submit'])) {
  
  include("open_db.php");
  
  //Process form and update db...
  $item = $_POST['item'];
  $quantity = $_POST['quantity'];
  
  //Statement to reduce quantity of item by value provided.
  $stmt = $db->prepare('UPDATE purchase SET quantity = quantity - :quantity WHERE id = :item');
  $stmt->bindValue(':quantity', $quantity);
  $stmt->bindValue(':item', $item);
  $result = $stmt->execute();
  
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
	<title>Keeping Stock</title>
  </head>
  <body>
    <h1>Keeping Stock</h1>
    <h2>Sale</h2>
    
    <p><b>Item <?php echo $item; ?></b> successfully updated.</p>
    <p>Would you like to <a href="sale.php">process another sale?</a> or return to <a href="index.php">home</a>?</p>
    
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
    <h2>Sale</h2>
    
    <form action="sale.php" method="post">
      Item ID:
      <input type="number" name="item"><br>
      Quantity:
      <input type="number" name="quantity"><br>
      <input type="submit" name="submit" value="Submit">
    </form>
    
  </body>
</html>