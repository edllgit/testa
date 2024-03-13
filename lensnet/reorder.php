<?php
session_start();

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");
	
$pkey=$_POST['pkey'];
$order_num=$_POST['order_num'];

require('../Connections/sec_connect.inc.php');

$query="Select* from orders WHERE primary_key='$pkey' AND order_num='$order_num'";
$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
		
$listItem=mysql_fetch_assoc($result);

$query="insert into orders (";

foreach ($listItem as $key => $value) {//ADD table field names
	switch($key)
		{
			case 'primary_key':						$query.="primary_key";		break;
			default: 								$query.=", $key";			break;
					}
	}
	
$query.=") values (";

foreach ($listItem as $key => $value) {//ADD VALUES

	switch($key)
		{
			case 'primary_key':						$query.="''";				break;
			case 'order_status':					$query.=", 'basket'";		break;
			case 'order_date_processed':			$query.=", '0000-00-00'";	break;
			case 'order_item_date':				
				$order_item_date=date("Y-m-d");
				$query.=", '$order_item_date'";		
				break;
			case 'order_total':						$query.=", '0.00'";			break;
			case 'order_num':						$query.=", '-1'";			break;
			case 'order_total':						$query.=", '0.00'";			break;
			case 'coupon_dsc':						$query.=", '0.00'";			break;
			default:
				$sl_value=addslashes($value);
				$query.=", '$sl_value'";										
				break;
					}
}

$query.=")";

$result=mysql_query($query)
		or die ( "Query failed: " . mysql_error());
		
		//ADD EXTRA PRODUCTS
		
	$next_increment = 0;//GET THE PRIMARY KEY OF THE JUST CREATED ORDER
	$query="SHOW TABLE STATUS LIKE 'orders'";
	$Result=mysql_query($query) or die ( "Query failed: " . mysql_error());

	$row = mysql_fetch_array($Result);
	$next_increment=$row['Auto_increment'];

	$lastPkey=$next_increment-1;


$query="Select* from extra_product_orders WHERE order_id='$pkey'";
$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());

while ($listItem=mysql_fetch_assoc($result)){
	$query="insert into extra_product_orders (";
	foreach ($listItem as $key => $value) {//ADD table field names
		switch($key)
			{
			case 'ep_order_id':						$query.="ep_order_id";		break;
			default: 								$query.=", $key";			break;
			}
	}
	$query.=") values (";
	foreach ($listItem as $key => $value) {//ADD VALUES
		switch($key)
			{
			case 'ep_order_id':						$query.="''";				break;
			case 'order_id':						$query.=", '$lastPkey'";	break;
			case 'order_num':						$query.=", '-1'";			break;
			default:
				$sl_value=addslashes($value);
				$query.=", '$sl_value'";										
				break;
			}
	}
	$query.=")";
	
	$result2=mysql_query($query)
		or die ( "Query failed: " . mysql_error() );
}

header("Location:basket.php");

?>