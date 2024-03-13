<?php
include("../Connections/sec_connect.inc.php");

$today=date("Y-m-d");

$result=mysql_query("SELECT DAYOFWEEK('$today')");
$day_of_week_var=mysql_result($result,0,0)-1;

switch ($day_of_week_var)
	{
			case 1:		$days_to_add=-6;				break;//MONDAY
			case 2:		$days_to_add=-6;				break;
			case 3:		$days_to_add=-6;				break;
			case 4:		$days_to_add=-6;				break;
			case 5:		$days_to_add=-4;				break;
			case 6:		$days_to_add=-4;				break;
			case 0:		$days_to_add=-5;				break;//SUNDAY
			default:	$days_to_add=-6;				break;
		}

$result=mysql_query("SELECT DATE_ADD('$today',INTERVAL $days_to_add DAY)");
$late_date=mysql_result($result,0,0);

$query="SELECT notification_email,lab_email,primary_key,lab_name from labs";
$result=mysql_query($query)
				or die  ('I cannot select items because: ' . mysql_error());

while ($labsItem=mysql_fetch_array($result)){//LOOP THROUGH LABS
	
	$query2="SELECT order_num,order_status,order_date_processed,patient_ref_num,order_patient_last,order_patient_first from orders where order_date_processed<'$late_date' AND order_status!='filled' AND order_status!='basket'  AND order_status!='in transit' AND order_status!='cancelled' AND prescript_lab='$labsItem[primary_key]' ORDER BY order_date_processed desc";
	
	$result2=mysql_query($query2)
				or die  ('I cannot select items because: ' . mysql_error());	
	$ordersnum=mysql_num_rows($result2);
	if ($ordersnum!=0){
		$count=0;
		$message="";
		
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 9pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body><table width=\"600\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\"><td>ORDER NUMBER</td><td>PATIENT REF NUM</td><td>PATIENT NAME</td><td>ORDER STATUS</td><td>ORDER DATE</td></tr>";
		
		while ($ordersItem=mysql_fetch_array($result2)){//LOOP THROUGH ORDERS
			$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";
		
		$new_result=mysql_query("SELECT DATE_FORMAT('$ordersItem[order_date_processed]','%m-%d-%Y')");
		$formatted_date=mysql_result($new_result,0,0);
		
						switch($ordersItem[order_status])
					{
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "Surfacing";		    break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'profilo':					$order_status = "Profilo";			  	break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'central lab marking':		$order_status = "Central Lab Marking";	break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for frame swiss':		$order_status = "Waiting for Frame Swiss";	break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";			break;
						case 'verifying':				$order_status = "Verifying";			break;
						case 'in mounting hko':			$order_status = "In Mounting HKO";			break;
						case 'waiting for frame hko':	$order_status = "Waiting for Frame HKO";	break;
						case 'in edging hko':			$order_status = "In Edging HKO";			break;
						case 'in edging swiss':			$order_status = "In Edging Swiss";			break;
						case 'waiting for frame store':		$order_status = "Waiting for Frame Store";	break;
						case 'waiting for frame ho/supplier':		$order_status = "Waiting for Frame Head Office/Supplier";	break;
		}
					}
			
		$message.="<tr bgcolor=\"$bgcolor\"><td>$ordersItem[order_num]</td><td>$ordersItem[patient_ref_num]</td><td>$ordersItem[order_patient_first] $ordersItem[order_patient_last]</td><td>$order_status</td><td>$formatted_date</td></tr>";
		}//END WHILE ORDERS
		mysql_free_result($result2);
		$message.="</table></body></html>";
		
		//SEND EMAIL
		
		if ($labsItem[notification_email]==""){
			
			$lab_email=$labsItem[lab_email];
		}
		else{
			$lab_email=$labsItem[notification_email];
		}

		$send_to_address="orders@direct-lens.com,".$lab_email;
		echo "<br>".$send_to_address;

$curTime= date("m-d-Y"); 	
		
		
		
	}//END IF ORDERS NUM
}//END WHILE LAB
mysql_free_result($result);

//rediriger vers la page php qui fera fermer la fenetre
header("Location:close_page.php");
?>