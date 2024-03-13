<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true);   

//Partie 1: on génère le rapport et on l'envoie par email pour garder une trace. Ensuite on cancelle les commandes incluses dans ce rapport.
$today   = date("Y-m-d");
//$today = "2016-05-24";
	

//1 gérer les jobs ifc.ca, ensuite on fera safety

$rptQuery="SELECT * FROM orders 
WHERE 
lab IN (66,67) AND order_date_processed = '$today' AND order_status = 'processing' 
AND order_num_optipro <> '' AND coupon_dsc <> 0.01 AND redo_order_num is null
OR 
lab = 59  AND user_id IN ('entrepotsafe','safedr','lavalsafe','terrebonnesafe','levissafe','sherbrookesafe','chicoutimisafe','granbysafe')  
AND order_date_processed = '$today' AND order_num_optipro <> '' AND coupon_dsc <> 0.01 AND redo_order_num is null AND order_status='processing'
GROUP BY order_num";
	
echo '<br>'. $rptQuery;


$rptResult = mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));

$ordersnum = mysqli_num_rows($rptResult);
	echo '<br>Resultat:'. $ordersnum;
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
			    <td align=\"center\"># Optipro</td>
                <td align=\"center\"># Commande</td>
                <td align=\"center\">Date</td>
				<td align=\"center\">Produit</td>
                <td align=\"center\">Status</td>
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
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 'waiting for lens':		$list_order_status = "Waiting for Lens";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'verifying':				$list_order_status = "Verifying";				break;
						case "on hold":					$$list_order_status= "On Hold";			        break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame he/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
						default:                        $list_order_status = "";             	        break;
						
		}

			$new_result=mysqli_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			//$order_date=mysqli_result($new_result,0,0);

			$message.="<tr bgcolor=\"$bgcolor\">
						   <td align=\"center\">$listItem[order_num_optipro]</td>
						   <td align=\"center\">$listItem[order_num]</td>
						   <td align=\"center\">$order_date</td>
						   <td align=\"center\">$listItem[order_product_name]</td>
						   <td align=\"center\">$list_order_status</td>
					   </tr>";
			
		$todayDate = date("Y-m-d g:i a");// current date
		$currentTime = time($todayDate); //Change date into time
		$timeAfterOneHour = $currentTime;
		$datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);
		$queryStatusHistory="INSERT INTO status_history (order_status, order_num, update_time,update_type)VALUES ('cancelled',$listItem[order_num],        '$datecomplete','Optipro sans coupon valide')";
		echo '<br>QueryHistory: '.$queryStatusHistory;
		$resultStatusHistory=mysqli_query($con,$queryStatusHistory)		or die ('Could not update because: ' . mysqli_error($con));	
				  
		}//END WHILE
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');	

echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject= "Optipro Cleanup (". $ordersnum. "): " . $today;
if ($ordersnum > 0){
$response=office365_mail($to_address, $from_address, $subject, null, $message);
}
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

echo '<br><br>'. $message;		



if ($ordersnum > 0){
	//Partie 2: on fait la mise a jour en cancellant les commandes importées d'optipro, qui n'ont pas de coupon de 1 cent.
	$rptQuery="UPDATE  orders
	set order_status='cancelled' 
	WHERE 
	lab IN (66,67) AND order_date_processed = '$today' AND order_status = 'processing' 
	AND order_num_optipro <> '' AND coupon_dsc <> 0.01 AND redo_order_num is null
	OR 
	lab = 59  AND user_id IN ('entrepotsafe','safedr','lavalsafe','terrebonnesafe','levissafe','sherbrookesafe','chicoutimisafe','granbysafe') 
	AND order_date_processed = '$today' AND order_status='processing' AND order_num_optipro <> '' AND coupon_dsc <> 0.01 AND redo_order_num is null";
		
	echo '<br>'. $rptQuery;
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
}//End if the first report gave results


//Logger l'exécution du script
$time_end 		 = microtime(true);
$time  	  		 = $time_end - $time_start;
$today           = date("Y-m-d");
$heure_execution = date("H:i:s");
$CronQuery		 = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
					VALUES('Script cancellation Optipro non validees 2.0', '$time','$today','$heure_execution','script_cancellation_commandes_optipro_non_validees.php')"; 					
$cronResult		 = mysqli_query($con,$CronQuery) or die ( "Query failed: " . mysqli_error($con));	
?>