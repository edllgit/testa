<?php
include("../Connections/sec_connect.inc.php");
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
$time_start = microtime(true);

//$datedebut  =  "2012-11-01";
//$datefin    =  "2012-11-30";


$labsItem=mysql_fetch_array($result);//GET LAB INFO
mysql_free_result($result);		
//Commande shipp�s durant le mois export�	
$rptQuery="SELECT * FROM orders WHERE transfered_to_acomba = 'no' AND order_date_shipped between '$datedebut' AND '$datefin'  AND order_total > 0";
echo $rptQuery;
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
	
	if ($ordersnum!=0){
		$count=0;
		$message="";
		
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">Order Number</td>
                <td align=\"center\">Main Lab</td>
                <td align=\"center\">Order Date</td>
                <td align=\"center\">Date Shipped</td>
                <td align=\"center\">Patient</td>
				<td align=\"center\">Patient Ref No</td>
                <td align=\"center\">Tray Number</td>
                <td align=\"center\">Order Status</td>
				<td align=\"center\">House Account</td>
			    <td align=\"center\">Transfer to Acomba</td>
			    <td align=\"center\">Date transfer</td>
				</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";
				
				switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "In Production";			break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'profilo':					$list_order_status = "Profilo";					break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'in edging':				$list_order_status = "In Edging";				break;
						case 'order completed':			$list_order_status = "Order Completed";			break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";		break;
						case 'waiting for lens':		$list_order_status = "Waiting for Lens";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Re-do";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'verifying':				$list_order_status = "Verifying";				break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		}

	
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$order_date=mysql_result($new_result,0,0);
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
			$ship_date=mysql_result($new_result,0,0);

			
			switch($listItem[user_id]){
						case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "In Production";			break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'profilo':					$list_order_status = "Profilo";					break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'in edging':				$list_order_status = "In Edging";				break;
						case 'order completed':			$list_order_status = "Order Completed";			break;
						case 'verifying':				$list_order_status = "Verifying";				break;
			}
			
			
			
			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$listItem[order_num]</td>
                <td align=\"center\">$listItem[lab_name]</td><td align=\"center\">$order_date</td>";
				if($ship_date!=0)
                	$message.="<td align=\"center\">$ship_date</td>";
				else
                	$message.="<td align=\"center\">&nbsp;</td>";
               $message.="
                <td align=\"center\">$listItem[order_patient_first] $listItem[order_patient_last]</td>
                <td align=\"center\">$listItem[patient_ref_num]</td>
                <td align=\"center\">$listItem[tray_num]</td>
                <td align=\"center\">$list_order_status</td>
				
				<td align=\"center\">$listItem[transfered_to_acomba]</td>
				<td align=\"center\">$listItem[date_transfer_acomba]</td>";
              $message.="</tr>";
		}//END WHILE
		mysql_free_result($rptResult);		
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
		
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Rapport de validation Acomba:  $datedebut - $datefin ";
$response=office365_mail($to_address, $from_address, $subject, null, $message);
echo "success";
}
		
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "Execution time:  $time seconds\n";
$today = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) VALUES('Email redirection report DR', '$time','$today','$timeplus3heures','cron_send_redirection_report_dr.php') "  ; 					
$cronResult=mysql_query($CronQuery)			or die ( "Query failed: " . mysql_error());
?>