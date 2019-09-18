<?php
include("secure.php");
include("open_db.php");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
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
      <a href="settings.php">Settings</a>
      <a href="javascript:void(0);" class="icon" onclick="myFunction()">
        <i class="fa fa-bars"></i>
      </a>
    </div>
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