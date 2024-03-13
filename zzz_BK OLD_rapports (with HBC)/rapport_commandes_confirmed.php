<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true);	
$today      = date("Y-m-d");
$rptQuery   = "SELECT  orders.order_num as order_num, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.tray_num,  orders.order_status, labs.lab_name from orders, labs
			   WHERE  orders.order_status='processing' and labs.primary_key = orders.lab
		       ORDER BY order_date_processed	";
echo $rptQuery;	
$result    = mysqli_query($con,$rptQuery)	or die  ('I cannot select items because: <br><br>' . mysqli_error($con));
$ordersnum = mysqli_num_rows($result);
	
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
	</style></head>
	<body>
		<table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
			<tr bgcolor=\"CCCCCC\">
				<td align=\"center\">Order Number</td>
				<td align=\"center\">Main Lab</td>
				<td align=\"center\">Order Date</td>
				<td align=\"center\">Patient</td>
				<td align=\"center\">Patient Ref No</td>
				<td align=\"center\">In Transit Since</td>
			</tr>";
					
	//Associative Array	
		while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){

		$count++;
		 if (($count%2)==0)
			$bgcolor="#E5E5E5";
		 else 
			$bgcolor="#FFFFFF";


		$queryUpd   = "SELECT max(update_time) as update_time from status_history WHERE order_status = 'in transit' and order_num =". $listItem[order_num] ;
		$rptUpd     = mysqli_query($con,$queryUpd)		or die  ('I cannot select items because: <br><br>' . mysqli_error($con));
		$DataUpd    = mysqli_fetch_array($rptUpd);
		$LastUpdate = $DataUpd['update_time'];
		
		
			$message.="
			<tr bgcolor=\"$bgcolor\">
				<td align=\"center\">$listItem[order_num]</td>
				<td align=\"center\">$listItem[lab_name]</td>
				<td align=\"center\">$listItem[order_date_processed]</td>
				<td align=\"center\">$listItem[order_patient_first] $listItem[order_patient_last]</td>
				<td align=\"center\">$listItem[patient_ref_num]</td>
				<td align=\"center\">$LastUpdate</td>
			</tr>";
		}//END WHILE	
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";

}else{
	$message.="<div class=\"TextSize\">No Orders</div>";}//END ORDERSNUM CONDITIONAL

//SEND EMAIL
echo $message;
$send_to_address = array('rapports@direct-lens.com');
//$send_to_address = array('rapports@direct-lens.com');	
echo "<br>".$send_to_address;
$curTime      =  date("m-d-Y");	
$to_address   =  $send_to_address;
$from_address = 'donotreply@entrepotdelalunette.com';
$subject      = "Liste des commandes confirmed: " . $curTime;
$response     = office365_mail($to_address, $from_address, $subject, null, $message);
//Log email
	$compteur = 0;
	foreach($to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
		else
			$EmailEnvoyerA = $EmailEnvoyerA . ',' . $value;
		$compteur += 1;	
	}
		
	if($response){ 
		echo 'Reussi';
    }else{
		echo 'Echec';
	}	
		
$time_end 		 = microtime(true);
$time     		 = $time_end - $time_start;
$today           = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery 		 = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page)
					VALUES('Rapport commandes Processing 2.0', '$time','$today','$timeplus3heures','rapport_commandes_confirmed.php')"; 					
$cronResult 	 = mysqli_query($CronQuery) or die ( "Query failed: " . mysqli_error($con));	
		

?>