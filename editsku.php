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
  
  $description = $_POST['description'];
  $postage = $_POST['postage'];
  $id = $_POST['sku_id'];
  
  $stmt = $db->prepare("UPDATE sku SET description = :description, postage = :postage WHERE id = :id");
  $stmt->bindValue(':description', $description);
  $stmt->bindValue(':postage', $postage);
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
      <a href="stock.php" class="active">Stock</a>
      <a href="settings.php">Settings</a>
      <a href="javascript:void(0);" class="icon" onclick="myFunction()">
        <i class="fa fa-bars"></i>
      </a>
    </div>
    <h1>Keeping Stock</h1>
    <h2>Edit SKU</h2>
    
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
      <a href="stock.php" class="active">Stock</a>
      <a href="settings.php">Settings</a>
      <a href="javascript:void(0);" class="icon" onclick="myFunction()">
        <i class="fa fa-bars"></i>
      </a>
    </div>
    <h1>Keeping Stock</h1>
    <h2>Edit SKU</h2>
    
    <a href="delete.php?type=sku&item=<?php echo $itemId;?>">Delete record</a>
        
    <form action="editsku.php" method="post">
    <br>  
      
      <?php
      $stmt = $db->prepare('SELECT * FROM sku WHERE id = :itemnumber');
      $stmt->bindValue(':itemnumber', $itemId);
      $result = $stmt->execute();
      $res = $result->fetchArray();
      ?>
            
      SKU: <?php echo $res['id'];?><br>
      <br><br>
      
      Description:
      <input type="text" name="description" value="<?php echo $res['description'];?>"><br>
      
      Postage:
            <select name="postage">
        
        
<?php        
//SQLite query to populate options list for postage rates.)
$res2 = $db->query('SELECT * FROM postage');

while ($row = $res2->fetchArray()) {
  //PARANTHESES REMAIN OPEN FOR USE IN HTML BELOW
?>
      
        
      <option value="<?php echo $row['id'];?>"><?php echo $row['description'];?></option>
      

<?php
} //End of query! 
?>
        
        
              
      </select> <br> <br>
      
      <input type="hidden" id="sku_id" name="sku_id" value="<?php echo $res['id'];?>">
      
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