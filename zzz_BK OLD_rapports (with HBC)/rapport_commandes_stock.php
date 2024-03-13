<?php
/*
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start   = microtime(true);
$lab_pkey     = 3;//STC
$tomorrow     = mktime(0,0,0,date("m"),date("d")-3,date("Y"));
$datecomplete = date("Y/m/d", $tomorrow);
		
$rptQuery="SELECT accounts.user_id as user_id, accounts.account_num, accounts.company, labs.lab_name, orders.order_num as order_num, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first,orders.order_product_name, orders.order_patient_last, orders.tray_num, accounts.company, orders.order_status FROM orders
	LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
	LEFT JOIN (labs) ON (orders.lab = labs.primary_key) 
	WHERE prescript_lab='$lab_pkey'
	AND orders.order_product_name like '%stock%' 
	AND orders.order_date_processed <= '$datecomplete'
	AND orders.order_status NOT IN ('filled','on hold')
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')
	AND prescript_lab <> 10
	GROUP BY order_num ORDER BY user_id";
	
	echo '<br>'. $rptQuery;
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
                <td align=\"center\">Order Date</td>
				<td align=\"center\">Product</td>
				<td align=\"center\">Account</td>
				<td align=\"center\">Patient</td>
                <td align=\"center\">Tray Number</td>
                <td align=\"center\">Order Status</td>
				</tr>";
		
		
		$CompteurRejet = 0;		
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
						case 'interlab':				$list_order_status = "Interlab";				break;
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
						case 'waiting for lens':		$list_order_status = "Waiting for Lens";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'verifying':				$list_order_status = "Verifying";				break;
						case 'scanned shape to swiss': 	$list_order_status = "Scanned shape to Swiss"; 	break;
						case "on hold":					$$list_order_status= "On Hold";			        break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
						default:                        $list_order_status = "UNKNOWN";             	break;
		}


	
$afficherCommande = true;			  
switch($listItem[user_id]){
	case 'chicoutimi':      $afficherCommande = false;  	break;
	case 'chicoutimisafe':  $afficherCommande = false;  	break;
	case 'levis':      		$afficherCommande = false;  	break;
	case 'levissafe':  		$afficherCommande = false;  	break;
	case 'sherbrooke': 		$afficherCommande = false;  	break;
	case 'sherbrookesafe':  $afficherCommande = false;  	break;
}

	
     if ($afficherCommande == false){
		// echo '<br>'. $listItem[order_num] . ' ';
		 $now = time(); // or your date as well
		 $your_date = strtotime($listItem[order_date_processed]);
		 $datediff = $now - $your_date;
		 $datediff = floor($datediff/(60*60*24));
		 //echo $datediff;
		 if ($datediff > 3){
		 $afficherCommande = true;
		 }else{
		 	$CompteurRejet += 1;	 
		 }
	 }
	

	
			  if ($afficherCommande){
			  $message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$listItem[order_num]</td>
                <td align=\"center\">$listItem[order_date_processed]</td>";
				
               $message.="
			    <td align=\"center\">$listItem[order_product_name]</td>
				<td align=\"center\">$listItem[user_id]</td>
                <td align=\"center\">$listItem[order_patient_first] $listItem[order_patient_last]</td>
                <td align=\"center\">$listItem[tray_num]</td>
                <td align=\"center\">$list_order_status</td>";
              $message.="</tr>";
			  }//End IF
			  
			  
		}//END WHILE
		
		$ordersnum = $ordersnum -$CompteurRejet;
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";


echo '<br><br>'. $message;

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');	
//$send_to_address = array('rapports@direct-lens.com');	

echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject= "Stock orders - STC (No Swiss)";
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
		echo "Reussi";
    }else{
		echo "Echec";
	}	
}	

$time_end 		 = microtime(true);
$time     		 = $time_end - $time_start;
$today    		 = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery       = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
VALUES('Rapport commandes stock 2.0', '$time','$today','$timeplus3heures','rapport_commandes_stock.php')"; 					
$cronResult      = mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con)); 


*/
?>