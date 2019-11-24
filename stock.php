<?php
include("secure.php");
include("open_db.php");
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  </head>
  <body>
    <div class="topnav" id="myTopnav">
      <a href="index.php" class="active">Home</a>
      <a href="sale.php">Sale</a>
      <a href="purchase.php">Purchase</a>
      <a href="stock.php" class="active">Stock</a>
      <a href="settings.php">Settings</a>
      <a href="javascript:void(0);" class="icon" onclick="myFunction()">
        <i class="fa fa-bars"></i>
      </a>
    </div>
    <h1>Keeping Stock</h1>
    
    <a class="button" href="sale.php">Sale</a>
    <a class="button" href="purchase.php">Purchase</a>
    
    <br>
    
    <form>
      <input type="text" id="search" name="search" placeholder="Search...">
    </form>
    
    <div style="overflow-x:auto;">    
      <table style="width:100%">
        <thead>
        <tr>
          <th>SKU</th>
          <th>Description</th>
          <th>Postage</th>
          <th>Edit/Delete</th>
        </tr>
        </thead>
        <tbody id="maintable">

  <?php
  //SQLite query to populate table rows. Joins the SKU table and purchase table to link all purchases to their sku. The iD from each table has an alias (the purchase table 'id' is set to 'purchase_id' and so on.)
  $res = $db->query('SELECT sku.id AS sku_id, sku.description AS sku_desc, sku.postage, postage.description AS post_desc FROM sku INNER JOIN postage on sku.postage = postage.id');

  while ($row = $res->fetchArray()) {
    //PARANTHESES REMAIN OPEN FOR USE IN HTML BELOW
  ?>

        <tr>
          <td><?php echo $row['sku_id'];?></td>
          <td><?php echo $row['sku_desc'];?></td>
          <td><?php echo $row['post_desc'];?></td>
          <td><a href="editsku.php?item=<?php echo $row['sku_id'];?>"><i class="fa fa-edit"></i></a></td>
        </tr>


  <?php
  } //End of query!  
  ?>
      </tbody>
      </table>
    </div>
    
    
    <script>
      /* Search script*/
      $(document).ready(function(){
        $("#search").on("keyup", function() {
          var value = $(this).val().toLowerCase();
          $("#maintable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
          });
        });
      });
    
    </script>
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