<?php
include("secure.php");
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
    <h2>Edit Postage Rates</h2>
    
    <p><a href="update_postage.php">Click here to set all Royal Mail rates to the latest prices</a></p>
    
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