<?php
include("secure.php");
include("open_db.php");
$types = array(

  "1st class stamp" => 0.85,
  "2nd class stamp" => 0.66,
  "2nd class large letter 100g" => 0.96,
  "2nd class large letter 250g" => 1.53,
  "2nd class large letter 500g" => 1.99,
  "2nd class large letter 750g" => 2.70,
  "2nd class small parcel 1kg" => 3.20,
  "2nd class small parcel 2kg" => 3.20,
  "1st class large letter 100g" => 1.29,
  "1st class large letter 250g" => 1.83,
  "1st class large letter 500g" => 2.39,
  "1st class large letter 750g" => 3.30,
  "1st class small parcel 1kg" => 3.85,
  "1st class small parcel 2kg" => 5.57,
  "2nd class medium parcel 1kg" => 5.30,
  "2nd class medium parcel 2kg" => 5.30,
  "2nd class medium parcel 5kg" => 8.99,
  "2nd class medium parcel 10kg" => 20.25,
  "2nd class medium parcel 20kg" => 28.55,
  "1st class medium parcel 1kg" => 6.00,
  "1st class medium parcel 2kg" => 9.02,
  "1st class medium parcel 5kg" => 15.85,
  "1st class medium parcel 10kg" => 21.90,
  "1st class medium parcel 20kg" => 33.40,
  "Signed for - 1st class stamp" => 2.25,
  "Signed for - 2nd class stamp" => 2.06,
  "Signed for - 2nd class large letter 100g" => 2.36,
  "Signed for - 2nd class large letter 250g" => 2.93,
  "Signed for - 2nd class large letter 500g" => 3.39,
  "Signed for - 2nd class large letter 750g" => 4.10,
  "Signed for - 2nd class small parcel 1kg" => 4.20,
  "Signed for - 2nd class small parcel 2kg" => 4.20,
  "Signed for - 1st class large letter 100g" => 2.69,
  "Signed for - 1st class large letter 250g" => 3.23,
  "Signed for - 1st class large letter 500g" => 3.79,
  "Signed for - 1st class large letter 750g" => 4.70,
  "Signed for - 1st class small parcel 1kg" => 4.85,
  "Signed for - 1st class small parcel 2kg" => 6.57,
  "Signed for - 2nd class medium parcel 1kg" => 6.30,
  "Signed for - 2nd class medium parcel 2kg" => 6.30,
  "Signed for - 2nd class medium parcel 5kg" => 9.99,
  "Signed for - 2nd class medium parcel 10kg" => 21.25,
  "Signed for - 2nd class medium parcel 20kg" => 29.55,
  "Signed for - 1st class medium parcel 1kg" => 7.00,
  "Signed for - 1st class medium parcel 2kg" => 10.02,
  "Signed for - 1st class medium parcel 5kg" => 16.85,
  "Signed for - 1st class medium parcel 10kg" => 22.90,
  "Signed for - 1st class medium parcel 20kg" => 34.40
  );
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

    <?php
    //If not yet confirmed
    if (!isset($_POST['submit'])){
    ?>
    <p>Clicking update will reset all of the original Royal Mail postage rates to the latest prices valid from 20 March 2020, overwriting any changes you may have made to these rates previously. Are you sure you wish to continue?</p>
    <form action="update_postage.php" method="post">
      <input type="submit" name="submit" value="Update">
    </form>
    <?php
    } else {

      //If confirmed...
      $stmt = $db->prepare('UPDATE postage SET price= :price WHERE description= :description');

      foreach ($types as $key => $value) {
        $stmt->bindValue(':description', $key);
        $stmt->bindValue(':price', $value);
        $result = $stmt->execute();
      }
    ?>

    <p>The rates have been successfully updated.</p>

    <?php
    }
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
