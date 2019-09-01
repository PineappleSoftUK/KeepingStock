<?php
include("open_db.php");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
	<title>Keeping Stock</title>
    
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  </head>
  <body>
    <h1>Keeping Stock</h1>
    
    <a href="sale.php">Sale</a>
    <a href="purchase.php">Purchase</a>
    
    <form>
      <input type="text" id="search" name="search" placeholder="Search...">
    </form>
    
    <table style="width:100%">
      <thead>
      <tr>
        <th>SKU</th>
        <th>Purchase ID</th>
        <th>Description</th>
        <th>Variant</th>
        <th>Date</th> 
        <th>Quantity</th>
        <th>Cost (each)</th>
      </tr>
      </thead>
      <tbody id="maintable">
      
<?php
//SQLite query to populate table rows. Joins the SKU table and purchase table to link all purchases to their sku. The iD from each table has an alias (the purchase table 'id' is set to 'purchase_id' and so on.)
$res = $db->query('SELECT sku.id AS sku_id, purchase.id AS purchase_id, sku, date, variant, quantity, cost, description FROM purchase INNER JOIN sku on purchase.sku = sku.id');

while ($row = $res->fetchArray()) {
  //PARANTHESES REMAIN OPEN FOR USE IN HTML BELOW
?>
      
      <tr>
        <td><?php echo $row['sku'];?></td>
        <td><a href="details.php?item=<?php echo $row['purchase_id'];?>"><?php echo $row['purchase_id'];?></a></td>
        <td><?php echo $row['description'];?></td>
        <td><?php echo $row['variant'];?></td>
        <td><?php echo $row['date'];?></td>
        <td><?php echo $row['quantity'];?></td>
        <td><?php echo money_format('%.2n',$row['cost']);?></td>
      </tr>
      

<?php
} //End of query!  
?>
    </tbody>
    </table>
    
    
    <script>
    $(document).ready(function(){
      $("#search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#maintable tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
    });
    </script>
    
  </body>
</html>