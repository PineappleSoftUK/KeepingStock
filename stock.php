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
      <a href="index.php">Home</a>
      <a href="sale.php">Sale</a>
      <a href="purchase.php">Purchase</a>
      <a href="stock.php" class="active">Stock</a>
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
        
    <form>
      <input type="text" id="search" name="search" placeholder="Search...">
    </form>
    
    <div style="overflow-x:auto;">    
      <table id="mainTable" style="width:100%">
        <thead>
        <tr>
          <th onclick="sortTable(0)">SKU</th>
          <th onclick="sortTable(1)">Description</th>
          <th onclick="sortTable(2)">Postage</th>
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
    <script>
    /* Sort table by clicking TH */
    function sortTable(n) {
      var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
      table = document.getElementById("mainTable");
      switching = true;
      // Set the sorting direction to ascending:
      dir = "asc"; 
      /* Make a loop that will continue until
      no switching has been done: */
      while (switching) {
        // Start by saying: no switching is done:
        switching = false;
        rows = table.rows;
        /* Loop through all table rows (except the
        first, which contains table headers): */
        for (i = 1; i < (rows.length - 1); i++) {
          // Start by saying there should be no switching:
          shouldSwitch = false;
          /* Get the two elements you want to compare,
          one from current row and one from the next: */
          x = rows[i].getElementsByTagName("TD")[n];
          y = rows[i + 1].getElementsByTagName("TD")[n];
          /* Check if the two rows should switch place,
          based on the direction, asc or desc: */
          /* CURRENCY */
          if (n == 0) { /* The n here relates to the column numberm as per the TH above */
            if (dir == "asc") {
              if (Number(x.innerHTML.replace(/[£,]+/g,"")) > Number(y.innerHTML.replace(/[£,]+/g,""))) {
              shouldSwitch = true;
              break;
              }
            } else if (dir == "desc") {
              if (Number(x.innerHTML.replace(/[£,]+/g,"")) < Number(y.innerHTML.replace(/[£,]+/g,""))) {
              shouldSwitch = true;
              break;
              }
            }
          } else {
          /* TEXT */
            if (dir == "asc") {
              if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                // If so, mark as a switch and break the loop:
                shouldSwitch = true;
                break;
              }
            } else if (dir == "desc") {
              if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                // If so, mark as a switch and break the loop:
                shouldSwitch = true;
                break;
              }
            }
          }
        }
        if (shouldSwitch) {
          /* If a switch has been marked, make the switch
          and mark that a switch has been done: */
          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
          switching = true;
          // Each time a switch is done, increase this count by 1:
          switchcount ++; 
        } else {
          /* If no switching has been done AND the direction is "asc",
          set the direction to "desc" and run the while loop again. */
          if (switchcount == 0 && dir == "asc") {
            dir = "desc";
            switching = true;
          }
        }
      }
    }
    </script>

    
  </body>
</html>