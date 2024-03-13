<?php
function calculateEstShipDate($order_date_processed,$order_product_id){
	include "../sec_connectEDLL.inc.php";
	$delais    = 20;
	$tomorrow  = mktime(0,0,0,date("m"),date("d")+$delais,date("Y"));
	$date1     = date("Y-m-d", $tomorrow);
	return $date1;
}

function addThreeDaysToEstShipDate($est_ship_date){
	
	return $new_est_ship_date;
	
}
function addEstShipDate($est_ship_date,$orders_id,$order_num,$date_order_placed){
	include "../sec_connectEDLL.inc.php";
		$query="insert into est_ship_date (orders_id,order_num,est_ship_date,date_order_placed) values ('$orders_id', '$order_num', '$est_ship_date', '$date_order_placed')";

	$result=mysqli_query($con,$query) or die ("could not add date because ".mysqli_error($con));
	
}

function addNewEstShipDate($new_est_ship_date,$order_id,$order_num,$order_date_processed){
	include "../sec_connectEDLL.inc.php";
	$query="SELECT * FROM est_ship_date WHERE orders_id='$order_id'";
	$result=mysqli_query($con,$query) or die ('Could not select because: ' . mysqli_error($con));	
	$numCount=mysqli_num_rows($result);
	
	if ($numCount==0){
			$query="insert into est_ship_date (orders_id,order_num,est_ship_date,date_order_placed) values ('$order_id', '$order_num', '$new_est_ship_date', '$order_date_processed')";
			$result=mysqli_query($con,$query) or die ("could not add date because ".mysqli_error($con));
			}
	else{
			$query="UPDATE est_ship_date SET est_ship_date='$new_est_ship_date' WHERE order_num='$order_num'";
			$result=mysqli_query($con,$query) or die ("could not add date because ".mysqli_error($con));
			}
	
}

?>