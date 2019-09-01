<?php
include("open_db.php");

$itemId = filter_input(INPUT_GET, 'item', FILTER_SANITIZE_SPECIAL_CHARS);
if (!isset($itemId)) {
  echo "Invalid item ID";
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
	<title>Keeping Stock</title>
    
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <h1>Keeping Stock</h1>
     
    <?php
    //SQLite query to populate table rows. Joins the SKU table and purchase table to link all purchases to their sku. The iD from each table has an alias (the purchase table 'id' is set to 'purchase_id' and so on.)
    
    $stmt = $db->prepare('SELECT sku.id AS sku_id, purchase.id AS purchase_id, sku, postage, date, variant, quantity, cost, sku.description, postage.description AS postage_desc, price FROM purchase INNER JOIN sku on purchase.sku = sku.id INNER JOIN postage on sku.postage = postage.id WHERE purchase.id = :itemnumber');
    $stmt->bindValue(':itemnumber', $itemId);
    $result = $stmt->execute();
    $res = $result->fetchArray();
    ?>

    <p>SKU <?php echo $res['sku'];?> - <?php echo $res['description'];?></p>
    
    <p>Item <?php echo $res['purchase_id'];?> - Variant: <?php echo $res['variant'];?></p>      
    
    <p>Purchased on <?php echo $res['date'];?></p>
    
    <p><?php echo $res['quantity'];?> in stock</p>
    
    <br>
    
    <p>Cost <?php echo money_format('%.2n',$res['cost']);?> per unit</p>
    
    <p><?php echo $res['postage_desc'];?> - <?php echo money_format('%.2n',$res['price']);?></p>
    
    <p>Packaging: £0.10</p>
    
    <p>Selling fees: </p>
    
    <p><b>To achieve 50% margin sell for: </b></p>
    
    
  </body>
</html>