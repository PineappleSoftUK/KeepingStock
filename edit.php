<?php
include("secure.php");
include("open_db.php");

if (!isset($_POST['submit'])) {
  //The following will clean up the 'item id' variable passed un the URI...
  $itemId = filter_input(INPUT_GET, 'item', FILTER_SANITIZE_SPECIAL_CHARS);
  if (!isset($itemId)) {
    echo "Invalid item ID";
    exit();
  }
}


if (isset($_POST['submit'])) {
  
  $sku = $_POST['sku'];
  $variant = $_POST['variant'];
  $date = $_POST['date'];
  $quantity = $_POST['quantity'];
  $cost = $_POST['cost'];
  $id = $_POST['purchaseid'];
  
  $stmt = $db->prepare("UPDATE purchase SET sku = :sku, variant = :variant, date = :date, quantity = :quantity, cost = :cost WHERE id = :id");
  $stmt->bindValue(':sku', $sku);
  $stmt->bindValue(':variant', $variant);
  $stmt->bindValue(':date', $date);
  $stmt->bindValue(':quantity', $quantity);
  $stmt->bindValue(':cost', $cost);
  $stmt->bindValue(':id', $id);
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
      <a href="settings.php">Settings</a>
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
    <h2>Edit</h2>
    
    <p>Sucessfully updated.</p>
    <p>Would you like to return to <a href="index.php">home</a>? 
      
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
      <a href="settings.php">Settings</a>
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
    <h2>Edit</h2>
    
    <a href="delete.php?type=purchase&item=<?php echo $itemId;?>">Delete record</a>
        
    <form action="edit.php" method="post">
    <br>  
      
      <?php
      $stmt = $db->prepare('SELECT * FROM purchase WHERE id = :itemnumber');
      $stmt->bindValue(':itemnumber', $itemId);
      $result = $stmt->execute();
      $res = $result->fetchArray();
      ?>
            
      Purchase ID: <?php echo $res['id'];?><br>
      <br><br>
      
      SKU:
      <input type="number" step="0.01" name="sku" value="<?php echo $res['sku'];?>"><br>
      
      Variant:
      <input type="text" name="variant" value="<?php echo $res['variant'];?>"><br>
      
      Date:
      <input type="date" name="date" value="<?php echo $res['date'];?>"><br>
      
      Quantity:
      <input type="number" step="1" name="quantity" value="<?php echo $res['quantity'];?>"><br>
      
      Cost:
      <input type="number" step="0.01" name="cost" value="<?php echo $res['cost'];?>"><br>
      
      <input type="hidden" id="purchaseid" name="purchaseid" value="<?php echo $res['id'];?>">
      
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
