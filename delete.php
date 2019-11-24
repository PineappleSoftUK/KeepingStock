<?php
include("secure.php");
include("open_db.php");

if (!isset($_POST['submit'])) {
  //The following will clean up the variables passed in the URI...
  $itemId = filter_input(INPUT_GET, 'item', FILTER_SANITIZE_SPECIAL_CHARS);
  $actionType = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_SPECIAL_CHARS);
  if (!isset($itemId) || !isset($actionType)) {
    echo "Invalid item ID or action";
    exit();
  }
}


if (isset($_POST['submit'])) {
  
  $id = $_POST['purchaseid'];
  $type = $_POST['type'];
  
  if ($type == "purchase") {
    $stmt = $db->prepare("DELETE FROM purchase WHERE id = :id");    
  } else {
    $stmt = $db->prepare("DELETE FROM sku WHERE id = :id");
  }
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
      <a href="javascript:void(0);" class="icon" onclick="myFunction()">
        <i class="fa fa-bars"></i>
      </a>
    </div>
    <h1>Keeping Stock</h1>
    <h2>Delete</h2>
    
    <p>Sucessfully deleted.</p>
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
      <a href="javascript:void(0);" class="icon" onclick="myFunction()">
        <i class="fa fa-bars"></i>
      </a>
    </div>
    <h1>Keeping Stock</h1>
    <h2>Confirm Delete</h2>
    
    <p>You are about to delete purchase number <?php echo $itemId;?>, are you sure?</p>
        
    <form action="delete.php" method="post">      
      <input type="hidden" id="purchaseid" name="purchaseid" value="<?php echo $itemId;?>">
      <input type="hidden" id="type" name="type" value="<?php echo $actionType;?>">
      
      <input type="submit" name="submit" value="Delete Forever">
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