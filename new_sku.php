<?php
include("open_db.php");
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
    
    <?php  //On submit the data is sent to purchase.php for processing ?>
    <form action="purchase.php" method="post">
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