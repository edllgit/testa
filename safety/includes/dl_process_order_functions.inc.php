<?php 

function getUserEmail($user_id){
	include "../sec_connectEDLL.inc.php";
	$query="select email from accounts WHERE user_id='$user_id'";
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$email=$listItem[email];

	return $email;
}

function getNewOrderNum(){
	include "../sec_connectEDLL.inc.php";
	$query="SELECT * FROM last_order_num WHERE primary_key='1'";
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$order_num=$listItem[last_order_num]+1;
	$query="UPDATE last_order_num SET last_order_num='$order_num' WHERE primary_key='1'";
	$result=mysqli_query($con,$query) or die ('Could not update because: ' . mysqli_error($con));

	$_SESSION['PrescrData']['myordnum']=$order_num; ///////////// pt 10/21/10 for xml uploader

	return $order_num;
}

function addOrderNumShiptoOrder($userId,$orderNum,$totalShipping,$order_shipping_method,$po_num){
include "../sec_connectEDLL.inc.php";
$order_date_processed=date("Y-m-d");
$order_status="processing";

$query="UPDATE orders SET order_status='$order_status',
						  order_date_processed='$order_date_processed',
						  order_num='$orderNum',
						  po_num='$po_num',
						  order_shipping_cost='$totalShipping',
						  order_shipping_method='$order_shipping_method'  
						  WHERE user_id='$userId' AND order_status='basket' AND order_product_type!='exclusive'";
	$result=mysqli_query($con,$query) or die ('Could not update because: ' . mysqli_error($con));
	//echo $query;
	
}


function addOrderNumShiptoFrameOrder($userId,$orderNum,$totalShipping,$order_shipping_method,$po_num){
include "../sec_connectEDLL.inc.php";
$order_date_processed=date("Y-m-d");
$order_status="processing";

$query="UPDATE orders SET order_status='$order_status',
						  order_date_processed='$order_date_processed',
						  order_num='$orderNum',
						  po_num='$po_num',
						  order_shipping_cost='$totalShipping',
						  order_shipping_method='$order_shipping_method'  
						  WHERE user_id='$userId' AND order_status='basket' AND order_product_type='frame_stock_tray'";
	$result=mysqli_query($con,$query) or die ('Could not update because: ' . mysqli_error($con));
	//echo $query;
	
}


function addOrderNumShiptoOrderExclusive($userId,$totalShipping,$order_shipping_method,$po_num){
	include "../sec_connectEDLL.inc.php";
	$order_date_processed=date("Y-m-d");
	$order_status="processing";

	$query="SELECT  user_id,primary_key,order_product_name,order_product_id, coupon_dsc, warranty FROM orders WHERE user_id='$userId' AND order_status='basket' AND order_product_type='exclusive'";
	$result=mysqli_query($con,$query)	or die ('Could not update because: '  . mysqli_error($con));
		
	while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		
		$orderNum=getNewOrderNum();
		$primary_key=$listItem[primary_key];

	switch ($listItem[warranty]) {
		case "0":		$promo_points	 = "0"; 	break;
		case "1":		$promo_points	 = "0"; 	break;
		case "2":		$promo_points    = "10";  	break;
		default: 		$promo_points	 = "0";		break;
		}

if ($promo_points > 0){

$ladate = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
$datecomplete = date("Y/m/d", $ladate);

$query="SELECT  lnc_reward_points,company FROM accounts WHERE user_id  = '$listItem[user_id]'";
$acctResult=mysqli_query($con,$query)	or die ("Could not find account");
$Data=mysqli_fetch_array($acctResult,MYSQLI_ASSOC);

$nouveauTotal = $promo_points + $Data[lnc_reward_points];
$queryUpdate = "UPDATE accounts SET   lnc_reward_points = '$nouveauTotal' WHERE user_id = '$listItem[user_id]'";
$resultUpdate=mysqli_query($con,$queryUpdate) or die (mysqli_error($con));

//Insert in lnc_reward_history and update point in the customer's account
$queryInsert = "INSERT INTO lnc_reward_history (access_id, detail, amount, datetime,user_id) VALUES ('','Warranty: $listItem[warranty] year Order #  $orderNum ','$promo_points', '$datecomplete', '$listItem[user_id]')" ;
$resultinsert=mysqli_query($con,$queryInsert)		or die (mysqli_error($con) . $queryInsert);
}


$queryOptipoints="SELECT optipoints_bonus  FROM  ifc_ca_exclusive WHERE primary_key  = $listItem[order_product_id]";
$resultOptipoints=mysqli_query($con,$queryOptipoints)	or die ("Could not find account");
$DataOptiPoints=mysqli_fetch_array($resultOptipoints,MYSQLI_ASSOC);
$BonusPoints = $DataOptiPoints[optipoints_bonus];

if ($BonusPoints > 0 )
	{	
	$ladate = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
	$datecomplete = date("Y/m/d", $ladate);

	$query="select  lnc_reward_points,company from accounts WHERE user_id  = '$listItem[user_id]'";
	$acctResult=mysqli_query($con,$query)	or die ("Could not find account");
	$Data=mysqli_fetch_array($acctResult,MYSQLI_ASSOC);
	
	$nouveauTotal = $BonusPoints + $Data[lnc_reward_points];
	$queryUpdate = "UPDATE accounts SET   lnc_reward_points = '$nouveauTotal' WHERE user_id = '$listItem[user_id]'";
	$resultUpdate=mysqli_query($con,$queryUpdate)		or die (mysqli_error($con));
	
	//Insert in lnc_reward_history and update point in the customer's account
	$queryInsert = "INSERT INTO lnc_reward_history (access_id, detail, amount, datetime,user_id) VALUES ('','Optipoints bonus for product $listItem[order_product_name] Order #  $orderNum ','$BonusPoints', '$datecomplete', '$listItem[user_id]')" ;
	$resultinsert=mysqli_query($con,$queryInsert)		or die (mysqli_error($con) . $queryInsert);
	}


		
		//CALCULATE ESTIMATED SHIP DATE AND WRITE TO est_ship_date table
		$est_ship_date=calculateEstShipDate($order_date_processed,$listItem[order_product_id]);
		addEstShipDate($est_ship_date,$primary_key,$orderNum,$order_date_processed);
			
	$query2="UPDATE orders SET order_status='$order_status',order_date_processed='$order_date_processed',order_num='$orderNum',po_num='$po_num',order_shipping_cost='$totalShipping' ,order_shipping_method='$order_shipping_method'  WHERE primary_key='$primary_key' AND order_status='basket' AND order_product_type='exclusive'";
			
	$result2=mysqli_query($con,$query2)		or die ('Could not update because: ' . mysqli_error($con));
		
	
	//Code rajouté par Charles 2010-07-22
	$todayDate = date("Y-m-d g:i a");// current date
	$currentTime = time($todayDate); //Change date into time
	//Add one hour equavelent seconds 60*60
	$timeAfterOneHour = $currentTime+((60*60)*3);	
	$datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);
	$ip= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
	
	//Update status history with the customer ip address ad the ip
	$queryStatus="INSERT INTO status_history (order_num,order_status,update_time, update_type,update_ip) VALUES($orderNum,'processing','$datecomplete','manual','$ip') ";
	$resultStatus=mysqli_query($con,$queryStatus)	or die  ('I  cannot Insert into status history because: ' . mysqli_error($con));
		
		
		
	$e_query="UPDATE extra_product_orders SET order_num='$orderNum' WHERE order_id='$primary_key'";//SET order_num in extra_products_order table
	$e_result=mysqli_query($con,$e_query) or die ('Could not update because: ' . mysqli_error($con));
	
	
	$queryPaymentSafety  = "UPDATE payments_safety SET order_num='$orderNum' WHERE order_id='$primary_key'";//SET order_num in payments_safety table
	$resultPaymentSafety = mysqli_query($con,$queryPaymentSafety)		or die ('Could not update because: ' . mysqli_error($con));
		
	$gTotal=calculateTotal($orderNum);//ADD TOTAL TO ORDER TABLE
	addOrderTotal($orderNum,$gTotal);	
	if($_SESSION["Master_Order_ID_Paid"]){
		$addPmtMarker = add_Pmt_Marker($_SESSION["sessionUser_Id"], $orderNum, $gTotal);
		$addOrderRef = add_Order_Ref($_SESSION["Master_Order_ID"], $orderNum);
		
		$discAmount=bcmul(.02, $gTotal, 2);
		$subAmount2 = bcsub($gTotal, $discAmount, 2);
		$amount=bcadd($subAmount2, $shipCost, 2);
	
		$msg=sendPmtConfirmEmail($gTotal, $_SESSION['sessionUserData']['first_name'], $_SESSION['sessionUserData']['last_name'], $orderNum, $_SESSION['sessionUserData']['email']);//SEND PMT CONFIRMATION
	}
	
	
	//UPDATE IFC FRAMES INVENTORY
	include_once 'includes/ifc_inventory_func.inc.php';
	Automatic_ReOrder_Armourx($orderNum);
		
				
		
		}//END WHILE
//uploadfinish();

}//END FUNCTION


function add_Pmt_Marker($user_id, $order_num, $gTotal){/* Set payment marker to show order as PAID */
	include "../sec_connectEDLL.inc.php";
	$transData=$_SESSION["transData"];
	$today = date("Y-m-d");
	$discAmount=bcmul(.02, $gTotal, 2);
	$subAmount2 = bcsub($gTotal, $discAmount, 2);
	$amount=bcadd($subAmount2, $shipCost, 2);
	$query="INSERT into payments (user_id, order_num, pmt_date, pmt_type, pmt_amount, cctype, cclast4, transResultCode, transAuthCode, transApprovalCode, transTransID, order_paid_in_full) values ('$user_id', '$order_num', '$today', 'credit card', '$amount', '$transData[cc_type]', '$transData[cclast4]', '$transData[transResultCode]', '$transData[transAuthCode]', '$transData[transApprovalCode]', '$transData[transTransID]', 'y')";

	$result=mysqli_query($con,$query) or die ("could not add marker " . mysqli_error($con));
		
	return true;
}

function add_Order_Ref($Master_Order_ID, $order_num){/* Set Master Order ID for this order number */
	include "../sec_connectEDLL.inc.php";
	$query="INSERT into order_num_master_id_ref (ref_master_id, ref_order_num) values ('$Master_Order_ID', '$order_num')";
	

	$result=mysql_query($query)
		or die ("could not add order reference " . mysql_error());
		
	return true;
}
?>
