<?php
include("secure.php");
//Var for the default value of the SKU field in the purchase form. If a new SKU number is generated this variable will be set in the if statement that follows.
$newSKU = "";

//Check to see if a new sku was generated and add the sku to the db
if (isset($_POST['itemdesc'])) {
  
  include("open_db.php");
  
  //Process form and add to db...
  $description = $_POST['itemdesc'];
  $postage = intval($_POST['postage']);
  
  $stmt = $db->prepare('INSERT INTO sku (description, postage) VALUES (:description, :postage)');
  $stmt->bindValue(':description', $description);
  $stmt->bindValue(':postage', $postage);
  $result = $stmt->execute();
  
  $newSKU = $db->lastInsertRowID();
  
}

//Check to see if a new purchase is being added and if so, add to the db
if (isset($_POST['sku'])) {
  
  include("open_db.php");
  
  //Process form and add to db...
  $sku = $_POST['sku'];
  $variant = $_POST['variant'];
  $date = $_POST['date'];
  $quantity = $_POST['quantity'];
  $cost = $_POST['cost'];

  $stmt = $db->prepare('INSERT INTO purchase (sku, variant, date, quantity, cost) VALUES (:sku, :variant, :date, :quantity, :cost)');
  $stmt->bindValue(':sku', $sku);
  $stmt->bindValue(':variant', $variant);
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
      <a href="purchase.php" class="active">Purchase</a>
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
    <h2>New Purchase</h2>
    
    <p>Purchase successfully added with reference <b><?php echo $idnumber; ?></b></p>
    <p>Would you like to <a href="purchase.php">add another?</a> or return to <a href="index.php">home</a>?</p>
    
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
      <a href="purchase.php" class="active">Purchase</a>
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
    <h2>Purchase</h2>
    
    <?php
    //A new sku was generated, echo the results:
    if (isset($_POST['itemdesc'])) {
      echo "<p>New item successfully added. SKU number <b>" . $newSKU . "</b> was generated</p>";
    }    
    ?>
    
    <form method="post">
      SKU:
      <input type="text" name="sku" value="<?php echo $newSKU; ?>" placeholder="SKU...">
      <a href="new_sku.php">New SKU</a><br>
      
      <br><br>
      
      Colour/Type/Variant (Optional):
      <input type="text" name="variant" placeholder="Variant (optional)..."><br>
      
      Date:
      <input type="date" name="date" value="<?php echo date("Y-m-d"); ?>"><br>
      
      Quantity:
      <input type="number" name="quantity"><br>
      
      Cost (each):
      <input type="number" name="cost" step="0.01"><br>
      <input type="reset">
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