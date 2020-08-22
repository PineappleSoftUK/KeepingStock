<?php
include("../secure.php");
include("../open_db.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Keeping Stock</title>
    
    <link rel="stylesheet" type="text/css" href="../style.css">
    <!-- Load an icon library to show a hamburger menu (bars) on small screens -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body>
    <div class="topnav" id="myTopnav">
      <a href="../index.php">Home</a>
      <a href="../sale.php">Sale</a>
      <a href="../purchase.php">Purchase</a>
      <a href="../stock.php">Stock</a>
      <a href="../settings.php" class="active">Settings</a>
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
    <h2>CSV Exports</h2>
    <form action="table_to_csv.php" method="post">
      <label for="tables">Select a table to export:</label>
      <select id="table" name="table">
        <?php 
        $tablesquery = $db->query("SELECT name FROM sqlite_master WHERE type='table';");

        while ($tableName = $tablesquery->fetchArray(SQLITE3_ASSOC)) {
          if ($tableName['name'] != "sqlite_sequence") {
            echo "<option value='" . $tableName['name'] . "'>" . $tableName['name'] . "</option>";
          }
        }
        ?>
      </select>
      <input type="submit">
    </form>
  </body>
</html>