<?php

/*
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start  = microtime(true);

//$today="2011-05-17";
$hier     = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
$hier     = date("Y/m/d", $hier);		
$rptQuery = 
"	SELECT accounts.user_id as user_id, accounts.account_num, accounts.company, labs.lab_name, orders.order_num as order_num, orders.po_num, orders.special_instructions, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.prescript_lab, orders.order_patient_last, orders.tray_num, accounts.company, orders.order_status from orders
	LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
	LEFT JOIN (labs) ON (orders.lab = labs.primary_key) 
	WHERE orders.order_date_processed='$hier' and orders.special_instructions != ''
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')
	order by orders.lab, orders.order_status";

	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$ordersnum=mysqli_num_rows($rptResult);
	
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
				<td align=\"center\">Tray Num</td>
                <td align=\"center\">Main Lab</td>
				 <td align=\"center\">Special Instruction</td>
				<td align=\"center\">Lab qui fabrique</td>
               
                <td align=\"center\">Order Status</td>
				</tr>";
				
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "Surfacing";				break;
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
						case 'waiting for frame swiss':	$list_order_status = "Waiting for Frame Swiss";	break;
						case 'waiting for frame knr':	$list_order_status = "Waiting for Frame KNR";	break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'verifying':				$list_order_status = "Verifying";				break;
						case 'scanned shape to swiss': 	$list_order_status = "Scanned shape to Swiss"; 	break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
						default:						$list_order_status = "UNKNOWN";
	}


			$message.="<tr bgcolor=\"$bgcolor\">
			<td align=\"center\">$listItem[order_num]</td>
			<td align=\"center\">$listItem[tray_num]</td>
            <td align=\"center\">$listItem[lab_name]</td>";
			
			$queryLab  = "SELECT lab_name from labs WHERE primary_key = " . $listItem["prescript_lab"];
			$ResultLab = mysqli_query($con,$queryLab)		or die  ('I cannot select items because: ' . mysqli_error($con));
			$DataLab   = mysqli_fetch_array($ResultLab,MYSQLI_ASSOC);

               $message.="
                <td align=\"center\">$listItem[special_instructions]</td>
				<td align=\"center\">". $DataLab[lab_name] . "</td>
                <td align=\"center\">$list_order_status</td>";
              $message.="</tr>";
		}//END WHILE
		
		
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";


echo '<br>'. $message;
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
//$send_to_address=array('dbeaulieu@direct-lens.com';
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Special Instructions Report";
$response=office365_mail($to_address, $from_address, $subject, null, $message);

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

}	

$time_end	= microtime(true);
$time 		= $time_end - $time_start;
$today 		= date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$ip   		= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$ip2   		= $_SERVER['HTTP_X_FORWARDED_FOR'];
$ips		= $ip  . ' ' .$ip2 ;
$CronQuery  = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page, ip) 
VALUES('Rapport Special Instructions 2.0', '$time','$today','$timeplus3heures','rapport_special_instructions.php','$ips') "  ; 					
$cronResult = mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));


*/
?>