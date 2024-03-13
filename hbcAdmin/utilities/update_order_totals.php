<?php

include("../../../sec_connect.inc.php");
include("../../includes/calc_functions.inc.php");


$date="2009-12-13";

$orderQuery="SELECT * from orders WHERE order_date_processed>'$date' ORDER by order_num desc";

$orderResult=mysql_query($orderQuery)
	or die  ('I cannot select items because: ' . mysql_error());

$itemcount=mysql_num_rows($orderResult);
print $itemcount."<br>";
print "<table width=\"100%\">";
print "<tr><td>Order Number</td><td>Order Date</td><td>Order Total</td><td>Extra Products Total</td><td>Updated Order Total</td></tr>";
while ($orderData=mysql_fetch_array($orderResult)){
	$e_products=getExtraProdTotal($orderData[order_num]);
	
	$gTotal=calculateTotal($orderData[order_num]);
	//addOrderTotal($orderData[order_num],$gTotal);
	
	print "<tr><td>".$orderData[order_num]."</td><td>".$orderData[order_date_processed]."</td><td>".$orderData[order_total]."</td><td>".$e_products."</td><td>".$gTotal."</td></tr>";
}
print "</table>";
print "DONE";
?>
