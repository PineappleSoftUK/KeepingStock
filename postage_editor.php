<?php
include("open_db.php");

if (isset($_POST['submit'])) {
  
  
  foreach ($_POST['postagedesc'] as $key => $value) {
    $stmt = $db->prepare("UPDATE postage SET description = :description WHERE id = :id");
    $stmt->bindValue(':description', $value);
    $stmt->bindValue(':id', $key + 1);
    $result = $stmt->execute();
  }
  
  foreach ($_POST['postageprice'] as $key => $value) {
    $stmt = $db->prepare("UPDATE postage SET price = :price WHERE id = :id");
    $stmt->bindValue(':price', $value);
    $stmt->bindValue(':id', $key + 1);
    $result = $stmt->execute();
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
    <h2>Postage Editor</h2>
    
    <p>The postage rate have now been updated as below.</p>
    <p>Would you like to <a href="postage_editor.php">edit again?</a> or return to <a href="index.php">home</a>?</p>
    
    <?php
      //SQLite query to pull all postage rates from the system and display as form
      $res = $db->query('SELECT * FROM postage');
      
      while ($row = $res->fetchArray()) {
        //PARANTHESES REMAIN OPEN FOR USE IN HTML BELOW
      ?>
      
      <?php echo $row['id'] . ". ";?>
      <?php echo $row['description'] . " - £";?>
      <?php echo $row['price'];?><br>
    
    <?php
      } //End of query!  
    ?>    
    
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
    <h2>Edit Postage Rates</h2>
    
    <form action="postage_editor.php" method="post">
      
      
      <?php
      //SQLite query to pull all postage rates from the system and display as form
      $res = $db->query('SELECT * FROM postage');
      
      while ($row = $res->fetchArray()) {
        //PARANTHESES REMAIN OPEN FOR USE IN HTML BELOW
      ?>
      
      <?php echo $row['id'] . ". ";?>
      <input type="text" name="postagedesc[]" value="<?php echo $row['description'];?>">
      <input type="number" step="0.01" name="postageprice[]" value="<?php echo $row['price'];?>"><br>
      <?php
      } //End of query!  
      ?>

          
      
      <input type="submit" name="submit" value="Submit">
    </form>
    
  </body>
</html>