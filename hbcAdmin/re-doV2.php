<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.php'>here</a> to login.";
	exit();
}
//Aller chercher la clé primaire 
if ($_REQUEST['pkey']<>''){
	$pkey      = $_REQUEST['pkey'];	
}elseif ($_POST['pkey']<>''){
	$pkey      = $_POST['pkey'];	
}

//Aller chercher le numéro de commande
if ($_REQUEST['order_num']<>''){
	$order_num = $_REQUEST['order_num'];
}elseif ($_POST['order_num']<>''){
	$pkey      = $_POST['pkey'];	
}



echo '<br>Page: Re-doV2.php !';
echo "<br>Primary Key: ".$pkey;
echo "<br>Order Num: ".$order_num;
echo "<br><br>";



require('../Connections/sec_connect.inc.php');
require_once('../includes/dl_process_order_functions.inc.php');
require_once('../includes/getlang.php');

$new_order_num=getNewOrderNum();

$query  = "SELECT * FROM orders WHERE primary_key='$pkey' AND order_num='$order_num'";
$result = mysql_query($query)		or die  ('I cannot select items because: ' . mysql_error());
		
$listItem=mysql_fetch_assoc($result);

$query="insert into orders (";

foreach ($listItem as $key => $value) {//ADD table field names
	switch($key)
		{
			case 'primary_key':			$query.="primary_key";		break;
			default: 					$query.=", $key";			break;
					}
	}
	
$query.=") values (";

foreach ($listItem as $key => $value) {//ADD VALUES

	switch($key)
		{
			case 'primary_key':									$query.="''";							break;
			case 'transfered_to_acomba':						$query.=",'no'";						break;
			case 'order_num_optipro':						    $query.=",''";						    break;
			case 'date_transfer_acomba':						$query.=",'0000-00-00 00:00:00'";		break;
			case 'date_transfer_acomba_dln_customer':			$query.=",'0000-00-00 00:00:00'";		break;
			case 'date_transfer_acomba_dln_fournisseur':		$query.=",'0000-00-00 00:00:00'";		break;
			case 'date_transfer_acomba_illinois_customer':		$query.=",'0000-00-00 00:00:00'";		break;
			case 'date_transfer_acomba_illinois_fournisseur':	$query.=",'0000-00-00 00:00:00'";		break; 	 	 
			case 'order_num':									$query.=", '$new_order_num'";			break;
			case 'prescript_lab':								$query.=", '0'";						break;
			case 'coupon_dsc':									$query.=", '0.00'";						break;
			case 'redo_order_num':								$query.=", '$order_num'";				break;
			case 'order_status':								$query.=", 'on hold'";					break;
			case 'myupload':								    $query.=", ''";						    break;
			case 'shape_name_bk':								$query.=", ''";						    break;
			case 'shape_has_been_transfered':					$query.=", 'no'";						break;
			case 'redo_reason_id':								$query.=", '0'";						break;
			case 'redo_rebate_detail':							$query.=", ''";							break;
			case 'transfered_acomba_dln_customer':				$query.=",'no'";						break;
			case 'transfered_acomba_dln_fournisseur':			$query.=",'no'";						break;
			case 'transfered_acomba_illinois_customer':			$query.=",'no'";						break;
			case 'transfered_acomba_illinois_fournisseur':		$query.=",'no'";						break; 	 
			case 'authorized_by':								$query.=",''";						    break; 
			case 'tray_num':								    $query.=",''";						    break; 	 	
			case 'frame_sent_swiss':                            $query.=",''";                          break;
			case 'order_date_processed':	
				$order_date_processed=date("Y-m-d");
				$query.=", '$order_date_processed'";
				break;
			case 'order_date_shipped':	
				$order_date_shipped="0000-00-00";
				$query.=", '$order_date_shipped'";
				break;
			case 'order_item_date':				
				$order_item_date=date("Y-m-d");
				$query.=", '$order_item_date'";		
				break;
					
			default:
				$sl_value=addslashes($value);
				$query.=", '$sl_value'";										
				break;
					}
}

$query.=")";

//echo $query;

$result=mysql_query($query)
		or die ( "Query failed: " . mysql_error() );
		
		//ADD EXTRA PRODUCTS
		
	$next_increment = 0;//GET THE PRIMARY KEY OF THE JUST CREATED ORDER
	$query="SHOW TABLE STATUS LIKE 'orders'";
	$Result=mysql_query($query) or die ( "Query failed: " . mysql_error() );

	$row = mysql_fetch_array($Result);
	$next_increment=$row['Auto_increment'];
	$lastPkey=$next_increment-1;

$query="SELECT * FROM extra_product_orders WHERE order_id='$pkey'";
$result=mysql_query($query)		or die  ('I cannot select items because: ' . mysql_error());

while ($listItem=mysql_fetch_assoc($result)){
	$query="INSERT into extra_product_orders (";
	foreach ($listItem as $key => $value) {//ADD table field names
		switch($key)
			{
			case 'ep_order_id':				$query.="ep_order_id";				break;
			default: 								$query.=", $key";						break;
			}
	}
	$query.=") values (";
	foreach ($listItem as $key => $value) {//ADD VALUES
		switch($key)
			{
			case 'ep_order_id':				$query.="''";								break;
			case 'order_id':					$query.=", '$lastPkey'";					break;
			case 'order_num':				$query.=", '$new_order_num'";		break;
			default:
				$sl_value=addslashes($value);
				$query.=", '$sl_value'";										
				break;
			}
	}
	$query.=")";

//echo "<br><br>EXTRA PRODUCTS: ".$query;

$result2=mysql_query($query)		or die ( "Query failed: " . mysql_error() );
}
//exit();


//Insertion dans Swiss_edging_barcode de cette reprise
$queryEdgingSwiss = "INSERT INTO swiss_edging_barcodes (order_num) VALUES ($new_order_num)";
$resultEdgingSwiss=mysql_query($queryEdgingSwiss)	or die ('Could not update because: '  . mysql_error());

//Mettre le montant de la monture a 0$ puisque c'est une reprise
$queryUpdateFrame = "UPDATE extra_product_orders SET price  = 0 WHERE order_id = $lastPkey AND  category= 'Frame'";
$resultUpdateFrame = mysql_query($queryUpdateFrame)		or die  ('I cannot select items because: ' . mysql_error());

header("Location:re-doEditV2.php?pkey=$lastPkey");

?>