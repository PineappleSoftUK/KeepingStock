<?php
include("open_db.php");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
	<title>Keeping Stock</title>
  </head>
  <body>
    <h1>Keeping Stock</h1>
    
    <a href="sale.php">Sale</a>
    <a href="purchase.php">Purchase</a>
    
    <form>
      <input type="text" name="search" placeholder="Search...">
    </form>
    
    <table style="width:100%">
      <tr>
        <th>SKU</th>
        <th>Description</th>
        <th>Date</th> 
        <th>Quantity</th>
        <th>Cost (each)</th>
      </tr>
      
<?php
//SQLite query to populate table rows
$res = $db->query('SELECT sku, date, quantity, cost, description FROM purchase INNER JOIN sku on purchase.sku = sku.id');

while ($row = $res->fetchArray()) {
    //echo "{$row['id']} {$row['name']} {$row['price']} \n";
  //PARANTHESES REMAIN OPEN FOR USE IN HTML BELOW
?>

      
      <tr>
        <td><?php echo $row['sku'];?></td> 
        <td><?php echo $row['description'];?></td>
        <td><?php echo $row['date'];?></td>
        <td><?php echo $row['quantity'];?></td>
        <td><?php echo $row['cost'];?></td>
      </tr>
      

<?php
} //End of query!  
?>
    </table>
    
  </body>
</html>