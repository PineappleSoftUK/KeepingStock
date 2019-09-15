<?php
include("open_db.php");

if (isset($_POST['submit'])) {
  
  $packagingfee = $_POST['packagingfee'];
  $margin = $_POST['margin'];
  $ebaypercentage = $_POST['ebaypercentage'];
  $paypalpercentage = $_POST['paypalpercentage'];
  $paypalflatfee = $_POST['paypalflatfee'];
  
  $stmt = $db->prepare("UPDATE settings SET packagingfee = :packagingfee, margin = :margin, ebaypercentage = :ebaypercentage, paypalpercentage = :paypalpercentage, paypalflatfee = :paypalflatfee WHERE id = 1");
  $stmt->bindValue(':packagingfee', $packagingfee);
  $stmt->bindValue(':margin', $margin);
  $stmt->bindValue(':ebaypercentage', $ebaypercentage);
  $stmt->bindValue(':paypalpercentage', $paypalpercentage);
  $stmt->bindValue(':paypalflatfee', $paypalflatfee);
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
    <h2>Settings</h2>
    
    <p>The settings have now been updated.</p>
    <p>Would you like to <a href="settings.php">edit again?</a> or return to <a href="index.php">home</a>   
    
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
    <h2>Settings</h2>
    
    <form action="settings.php" method="post">
      
      
      <?php
      //SQLite query to pull all settings from the system and display as form
      $res = $db->query('SELECT * FROM settings');
      
      while ($row = $res->fetchArray()) {
        //PARANTHESES REMAIN OPEN FOR USE IN HTML BELOW
      ?>
      
      Packaging Fee:
      <input type="number" step="0.01" name="packagingfee" value="<?php echo $row['packagingfee'];?>"><br>
      
      Margin:
      <input type="number" step="0.01" name="margin" value="<?php echo $row['margin'];?>"><br>
      
      eBay Fees (Percentage as a decimal e.g 0.10 = 10%):
      <input type="number" step="0.01" name="ebaypercentage" value="<?php echo $row['ebaypercentage'];?>"><br>
      
      PayPal Fees (Percentage as a decimal e.g 0.10 = 10%):
      <input type="number" step="0.001" name="paypalpercentage" value="<?php echo $row['paypalpercentage'];?>"><br>
      
      PayPal Fees (Flat fee):
      <input type="number" step="0.01" name="paypalflatfee" value="<?php echo $row['paypalflatfee'];?>"><br>
      <?php
      } //End of query!  
      ?>
      
      <input type="submit" name="submit" value="Submit">
    </form>
    
  </body>
</html>