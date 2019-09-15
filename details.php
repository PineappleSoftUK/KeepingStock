<?php
include("open_db.php");

//The following will clean up the 'item id' variable passed un the URI...
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
        
    //Firstly get the latest settings:
    $res = $db->query('SELECT * FROM settings');
    while ($row = $res->fetchArray()) {
      $packagingfee = $row['packagingfee'];
      $margin = $row['margin'];
      $ebaypercentage = $row['ebaypercentage'];
      $paypalpercentage = $row['paypalpercentage'];
      $paypalflatfee = $row['paypalflatfee'];
    }
    
    
    //Next, Grab the details from the various tables. Joins the SKU table and purchase table to link all purchases to their sku. The iD from each table has an alias (the purchase table 'id' is set to 'purchase_id' and so on.)
        
    $stmt = $db->prepare('SELECT sku.id AS sku_id, purchase.id AS purchase_id, sku, postage, date, variant, quantity, cost, sku.description, postage.description AS postage_desc, price FROM purchase INNER JOIN sku on purchase.sku = sku.id INNER JOIN postage on sku.postage = postage.id WHERE purchase.id = :itemnumber');
    $stmt->bindValue(':itemnumber', $itemId);
    $result = $stmt->execute();
    $res = $result->fetchArray();
    
    
    //Now do the maths for the various fees
    
    //+++++++++++++++++TODO!!+++++++++++++++
    
    //1. Purchase Price + Postage Cost + Packaging + Margin
    $math1 = $res['cost'] + $res['price'] + $packagingfee + $margin;
        
    //2. Calculate fees based on above
    $math2 = ($math1 * $ebaypercentage) + ($math1 * $paypalpercentage) + $paypalflatfee;
    
    //3. The sum of the above (1. + 2.)
    $math3 = $math1 + $math2;
    
    //4. Fees of the above (3.)
    $math4 = ($math3 * $ebaypercentage) + ($math3 * $paypalpercentage) + $paypalflatfee;
    
    //5. The sum of the above (3. + 4.) -- This is now the amount to list for to acheive margin
    $math5 = $math1 + $math4;
        
    //Now display the results as html...
    ?>

    <p>SKU <?php echo $res['sku'];?> - <?php echo $res['description'];?></p>
    
    <p>Item <?php echo $res['purchase_id'];?> - Variant: <?php echo $res['variant'];?></p>      
    
    <p>Purchased on <?php echo $res['date'];?></p>
    
    <p><?php echo $res['quantity'];?> in stock</p>
    
    <br>
    
    <p>Cost <?php echo money_format('%.2n',$res['cost']);?> per unit</p>
    
    <p><?php echo $res['postage_desc'];?> - <?php echo money_format('%.2n',$res['price']);?></p>
    
    <p>Packaging: <?php echo money_format('%.2n',$packagingfee);?></p>
    
    <p>Selling fees: <?php echo money_format('%.2n',$math4); ?></p>
    
    <p><b>To achieve <?php echo $margin * 100;?>% margin sell for: <?php echo money_format('%.2n',$math5); ?></b></p>
    
    
  </body>
</html>