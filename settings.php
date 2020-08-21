<?php
include("secure.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Keeping Stock</title>
    
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- Load an icon library to show a hamburger menu (bars) on small screens -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body>
    <div class="topnav" id="myTopnav">
      <a href="index.php">Home</a>
      <a href="sale.php">Sale</a>
      <a href="purchase.php">Purchase</a>
      <a href="stock.php">Stock</a>
      <a href="settings.php" class="active">Settings</a>
      <?php 
      if ($pac = "yes") {
        echo "<a href='pineappleaccesscontrol/logout.php' style='float:right;'>Log out</a>";
      }
      ?>
      <a href="javascript:void(0);" class="icon" onclick="myFunction()">
        <i class="fa fa-bars"></i>
      </a>
    </div>
    <h1>Keeping Stock</h1>
    <h2>Settings</h2>
    
    <p>The settings have now been updated.</p>
    <p>Would you like to <a href="settings.php">edit again?</a> or return to <a href="index.php">home</a> 
      
    <script>
    /* Toggle between adding and removing the "responsive" class to topnav when the user clicks on the icon */
    function myFunction() {
      var x = document.getElementById("myTopnav");
      if (x.className === "topnav") {
        x.className += " responsive";
      } else {
        x.className = "topnav";
      }
    }
    </script>
    
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Keeping Stock</title>
    
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- Load an icon library to show a hamburger menu (bars) on small screens -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body>
    <div class="topnav" id="myTopnav">
      <a href="index.php">Home</a>
      <a href="sale.php">Sale</a>
      <a href="purchase.php">Purchase</a>
      <a href="stock.php">Stock</a>
      <a href="settings.php" class="active">Settings</a>
      <?php 
      if ($pac = "yes") {
        echo "<a href='pineappleaccesscontrol/logout.php' style='float:right;'>Log out</a>";
      }
      ?>
      <a href="javascript:void(0);" class="icon" onclick="myFunction()">
        <i class="fa fa-bars"></i>
      </a>
    </div>
    <h1>Keeping Stock</h1>
    <p>Version 1.2.0 - Released 4 June 2020</p>
    <p>Includes PineappleAccessControl Version 1.0.2</p>
    <hr>
    <h2>Settings</h2>
        
    <a href="postage_editor.php">Click here to adjust postage rates</a>
    
    <form action="settings.php" method="post">
    <br>  
      
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
    
    <script>
      /* Toggle between adding and removing the "responsive" class to topnav when the user clicks on the icon */
      function myFunction() {
        var x = document.getElementById("myTopnav");
        if (x.className === "topnav") {
          x.className += " responsive";
        } else {
          x.className = "topnav";
        }
      }
    </script>
    
  </body>
</html>