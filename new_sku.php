<?php
include("open_db.php");

if (isset($_POST['submit'])) {
  
  //Process form and add to db...
  $description = $_POST['itemdesc'];
  //$postage = intval($_POST['postage']);
  $postage = 1;
  

  $stmt = $db->prepare('INSERT INTO sku (description, postage) VALUES (:description, :postage)');
  $stmt->bindValue(':description', $description);
  $stmt->bindValue(':postage', $postage);
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
      <input type="text" name="itemdesc" placeholder="Item description..."><br>
      
      <select name="postage">
        
        
<?php        
//SQLite query to populate options list for postage rates.)
$res = $db->query('SELECT * FROM postage');

while ($row = $res->fetchArray()) {
  //PARANTHESES REMAIN OPEN FOR USE IN HTML BELOW
?>
      
        
      <option value="<?php echo $row['id'];?>"><?php echo $row['description'];?></option>
      

<?php
} //End of query! 
?>
        
        
              
      </select> <br>
      
      <input type="submit" name="submit" value="Submit">
    </form>
    
  </body>
</html>