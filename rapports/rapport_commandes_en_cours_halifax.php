<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$time_start = microtime(true);
$today      = date("Y-m-d");
//$today=date("2014-08-22");

if ($_REQUEST['email'] == 'no'){
	$SendEmail = 'no';
}elseif($_REQUEST['email'] == 'admin'){
	$SendEmail = 'no';
	$SendAdmin = 'yes';
}else{
	$SendEmail = 'yes';
}
if ($_REQUEST[debug]=='yes'){
	$Debug = 'yes';
}

$rptQuery   = "SELECT order_num, user_id, tray_num, order_date_processed, order_product_name, order_patient_first, order_patient_last, patient_ref_num, order_status, prescript_lab, lab_name
FROM orders, labs
WHERE orders.prescript_lab = labs.primary_key
AND user_id IN ('warehousehal')
AND order_status NOT IN ('cancelled', 'filled','basket','pre-basket')
AND order_num <> -1
ORDER BY  order_date_processed";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery . '<br>';	

$rptResult = mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 1: ' . mysqli_error($con));
$ordersnum = mysqli_num_rows($rptResult);
	
if ($ordersnum!=0){
$count   = 0;
$message = "";		
$message="<html>
<head>
	<meta charset=\"utf-8\">
	<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
	<!-- Bootstrap core CSS -->
	<link href=\"http://www.direct-lens.com/bootstrap/css/bootstrap.min.css\" rel=\"stylesheet\">
	<!-- Custom styles for this template -->
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src=\"https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js\"></script>
	<script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
	<![endif]-->
</head>";

$message.="<body>
<table class=\"table\">";

$message.="<tr><td align=\"center\">&nbsp;</td></tr><tr><td align=\"center\">&nbsp;</td></tr><tr><td colspan=\"8\" align=\"center\"><strong>SHIPPED ORDERS</strong></td></tr>";


$rptQueryShipped  = "SELECT order_num, user_id, tray_num, order_date_processed, order_product_name, order_patient_first, order_patient_last, patient_ref_num, order_status, prescript_lab, lab_name
FROM orders, labs
WHERE orders.prescript_lab = labs.primary_key
AND user_id IN ('warehousehal')
AND order_status  IN ('filled')
AND order_date_shipped = '$today'
ORDER BY  order_date_processed";

if($Debug == 'yes')
echo '<br>QueryShipped: <br>'. $rptQueryShipped . '<br>';	

$rptResultShipped = mysqli_query($con,$rptQueryShipped)		or die  ('I cannot select items because 1: ' . mysqli_error($con));
$NbrResult        = mysqli_num_rows($rptResultShipped);
if ($NbrResult  > 0){
	while ($DataShipped=mysqli_fetch_array($rptResultShipped,MYSQLI_ASSOC)){
		$message.="
		<tr>
			<td bgcolor=\"#99E29D\" align=\"center\"><font size=\"-1\">$DataShipped[order_num]</font></td>
			<td bgcolor=\"#99E29D\" align=\"center\"><font size=\"-1\">$DataShipped[tray_num]</font></td>
			<td bgcolor=\"#99E29D\" align=\"center\"><font size=\"-1\">$DataShipped[order_date_processed]</font></td>
			<td bgcolor=\"#99E29D\" align=\"center\"><font size=\"-1\">&nbsp;</font></td>
			<td bgcolor=\"#99E29D\" align=\"center\"><font size=\"-1\">$DataShipped[order_patient_first]&nbsp;$DataShipped[order_patient_last]</font></td>
			<td bgcolor=\"#99E29D\"d align=\"center\"><font size=\"-1\">$DataShipped[order_product_name]</font></td>
			<td bgcolor=\"#99E29D\" align=\"center\"><font size=\"-1\">Shipped</font></td>
			<td bgcolor=\"#99E29D\" align=\"center\"><font size=\"-1\">&nbsp;</font></td>
			<td bgcolor=\"#99E29D\" align=\"center\"><font size=\"-1\">$DataShipped[lab_name]</font></td>
		</tr>";
		
	}//End While
	
	$message.="
		<tr>
			<td align=\"center\"><b>TOTAL:</b></td>
			<td colspan=\"2\" align=\"center\"><b>$NbrResult Orders Shipped Today</b></td>
		</tr>";
		
}//End IF


$message .="<tr bgcolor=\"CCCCCC\">
	<td align=\"center\"><font size=\"-1\">Order Number</font></th>
	<td align=\"center\"><font size=\"-1\">Tray</th>
	<td align=\"center\" width=\"150\"><font size=\"-1\">Order Date</font></th>
	<td align=\"center\" width=\"150\"><font size=\"-1\">Est. Date</font></th>
	<td align=\"center\"><font size=\"-1\">Patien</font>t</th>
	<td align=\"center\"><font size=\"-1\">Product</font></th>
	<td align=\"center\"><font size=\"-1\">Order Status</font></th>
	<td align=\"center\" width=\"150\"><font size=\"-1\">Since</font></th>
	<td align=\"center\"><font size=\"-1\">Prescription Lab</font></th>
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
			case 'in edging':				$list_order_status = "In Edging";				break;
			case 'on hold':					$list_order_status = "On Hold";					break;
			case 'job started':				$list_order_status = "In Production";			break;
			case 'in coating':				$list_order_status = "In Coating";				break;
			case 'profilo':					$list_order_status = "Profilo";					break;
			case 'interlab':				$list_order_status = "In Coating";				break;
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
			case 'waiting for frame knr':	$list_order_status = "Waiting for Frame KNR";	break;	
			case 'waiting for lens':		$list_order_status = "Waiting for Lens";		break;
			case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
			case 're-do':					$list_order_status = "Re-do";					break;
			case 'in transit':				$list_order_status = "In Transit";				break;
			case 'filled':					$list_order_status = "Shipped";					break;
			case 'cancelled':				$list_order_status = "Cancelled";				break;
			case 'verifying':				$list_order_status = "Verifying";				break;
			case 'scanned shape to swiss': 	$list_order_status = "Scanned shape to Swiss"; 	break;
			case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
			case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
			default:  				  	    $list_order_status = 'UNKNOWN';	                break;					
	}
	
	$queryEstShipDate   = "SELECT est_ship_date FROM est_ship_date WHERE order_num = $listItem[order_num]";	
	echo '<br>'. $queryEstShipDate ;
	$resultEstShiPDate  = mysqli_query($con,$queryEstShipDate)		or die  ('I cannot select items because 2b: ' . mysqli_error($con));
	$NbrResult          = mysqli_num_rows($resultEstShiPDate);
	if ($NbrResult > 0){
		$DataEstShipDate    = mysqli_fetch_array($resultEstShiPDate,MYSQLI_ASSOC);	
		$EstimateShipDate   = $DataEstShipDate[est_ship_date];
	}
	$queryLastUpdate    = "SELECT * FROM status_history WHERE  order_num = $listItem[order_num] and order_status = '$listItem[order_status]'";
	$resultLastUpdate   = mysqli_query($con,$queryLastUpdate)		or die  ('I cannot select items because 2c: ' . mysqli_error($con));
	$NbrResult          = mysqli_num_rows($resultLastUpdate);
	if ($NbrResult >0){
	$DataLastUpdate     = mysqli_fetch_array($resultLastUpdate,MYSQLI_ASSOC); 	
	$StatusLastUpdate   = substr($DataLastUpdate[update_time],0,10);		
	}
	$message.="
	<tr bgcolor=\"$bgcolor\">
		<td align=\"center\"><font size=\"-1\">$listItem[order_num]</font></td>
		<td align=\"center\"><font size=\"-1\">$listItem[tray_num]</font></td>
		<td align=\"center\"><font size=\"-1\">$listItem[order_date_processed]</font></td>
		<td align=\"center\"><font size=\"-1\">$EstimateShipDate</font></td>
		<td align=\"center\"><font size=\"-1\">$listItem[order_patient_first]&nbsp;$listItem[order_patient_last]</font></td>
		<td align=\"center\"><font size=\"-1\">$listItem[order_product_name]</font></td>
		<td align=\"center\"><font size=\"-1\">$list_order_status</font></td>
		<td align=\"center\"><font size=\"-1\">$StatusLastUpdate</font></td>
		<td align=\"center\"><font size=\"-1\">$listItem[lab_name]</font></td>
	</tr>";
	
	if ($list_order_status == 'Re-do'){
		//Si la command est au status redo interne, on affiche le status de la  reprise interne	
		$queryRepriseInterne  = "SELECT * FROM ORDERS WHERE redo_order_num = $listItem[order_num]" ;	
		$resultRepriseInterne = mysqli_query($con,$queryRepriseInterne)		or die  ('I cannot select items because 3: ' . mysqli_error($con));
		$nbrResult = mysqli_num_rows($resultRepriseInterne);
		
	
	if ($nbrResult > 0)	
	{
		$DataRepriseInterne   = mysqli_fetch_array($resultRepriseInterne,MYSQLI_ASSOC);
		$Redo_Order_Num 	  = $DataRepriseInterne[order_num];
		$queryEstShipDate     = "SELECT est_ship_date FROM est_ship_date WHERE order_num = $Redo_Order_Num";		
		$resultEstShiPDate    = mysqli_query($con,$queryEstShipDate)		or die  ('I cannot select items because 4: ' . mysqli_error($con));
		$DataEstShipDate      = mysqli_fetch_array($resultEstShiPDate,MYSQLI_ASSOC);	
		$EstimateShipDate     = $DataEstShipDate[est_ship_date];
	}

		switch($DataRepriseInterne["order_status"]){		
			case 'processing':				$list_order_status = "Confirmed";				break;
			case 'order imported':			$list_order_status = "Order Imported";			break;
			case 'on hold':					$list_order_status = "On Hold";					break;
			case 'in edging':				$list_order_status = "In Edging";				break;
			case 'job started':				$list_order_status = "In Production";			break;
			case 'in coating':				$list_order_status = "In Coating";				break;
			case 'profilo':					$list_order_status = "Profilo";					break;
			case 'interlab':				$list_order_status = "In Coating";				break;
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
			case 'waiting for frame swiss':	$list_order_status = "Waiting for Frame Swiss";		break;
			case 'waiting for frame knr':	$list_order_statusRedo = "Waiting for frame KNR";	break;	
			case 'waiting for lens':		$list_order_status = "Waiting for Lens";		break;
			case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
			case 're-do':					$list_order_status = "Re-do";					break;
			case 'in transit':				$list_order_status = "In Transit";				break;
			case 'filled':					$list_order_status = "Shipped";					break;
			case 'cancelled':				$list_order_status = "Cancelled";				break;
			case 'verifying':				$list_order_status = "Verifying";				break;
			case 'scanned shape to swiss': 	$list_order_status = "Scanned shape to Swiss"; 		break;
			default:  				  	    $list_order_status = 'UNKNOWN';	                break;						
		}
	
			switch($DataRepriseInterne[lab_name]){
				
			}
	if (($Redo_Order_Num <> '') && 	($DataRepriseInterne[order_status] <> ''))	{
		$queryLastUpdate    = "SELECT * FROM status_history WHERE  order_num = $Redo_Order_Num and order_status = '$DataRepriseInterne[order_status]'";
		$resultLastUpdate   = mysqli_query($con,$queryLastUpdate)		or die  ('I cannot select items because 2a: ' . mysqli_error($con));
		$NbrResult          = mysqli_num_rows($resultLastUpdate);
		if ($NbrResult   > 0){
			$DataLastUpdate     = mysqli_fetch_array($resultLastUpdate,MYSQLI_ASSOC); 	
			$StatusLastUpdate   = substr($DataLastUpdate[update_time],0,10);
		}
	}//End IF
		$message.="
		<tr>
			<td align=\"center\"><font size=\"-1\"><strong>$Redo_Order_Num</strong></font></td>
			<td align=\"center\"><font size=\"-1\"><strong>$DataRepriseInterne[tray_num]</strong></font></td>
			<td align=\"center\"><font size=\"-1\"><strong>$DataRepriseInterne[order_date_processed]</strong></font></td>
			<td align=\"center\"><font size=\"-1\"><strong>$EstimateShipDate</strong></font></td>
			<td align=\"center\"><font size=\"-1\"><strong>$DataRepriseInterne[order_patient_first]&nbsp;$DataRepriseInterne[order_patient_last]</font></strong></td>
			<td align=\"center\"><font size=\"-1\"><strong>$DataRepriseInterne[order_product_name]</strong></font></td>
			<td align=\"center\"><font size=\"-1\"><strong>$list_order_status</strong></font></td>
			<td align=\"center\"><font size=\"-1\"><strong>$StatusLastUpdate</strong></font></td>
			<td align=\"center\"><font size=\"-1\"><strong>$DataRepriseInterne[lab_name]</strong></font></td>
		</tr>";
		
					
	
	}else{

	}//End IF there is an internal redo
	
		
}//END WHILE

$message.="<tr><td colspan=\"9\">Number of Orders: $ordersnum</td></tr>";




	
$message.="</table>";
$to_address = array('rapports@direct-lens.com','halifax@opticalwarehouse.ca');



//$to_address = array('rapports@direct-lens.com');

$curTime      = date("m-d-Y");	
$from_address = 'donotreply@entrepotdelalunette.com';
$subject      = "Optical Warehouse Halifax : Orders in Progress";

//SEND EMAIL
	if ($SendEmail == 'yes'){
		$response = office365_mail($to_address, $from_address, $subject, null, $message);
	}
		
	if($SendAdmin == 'yes'){
		$to_address = array('rapports@direct-lens.com');
		$response   = office365_mail($to_address, $from_address, $subject, null, $message);	
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

		// Générer le contenu HTML du rapport

		// Créez un nom de fichier unique avec un horodatage
		$date = new DateTime();
		$timestamp = $date->format('Y-m-d_H-i-s');
		$nomFichier = ' r_commande_en_cours_halifax'. $timestamp;
	
		// Enregistrez le contenu HTML dans un fichier
		$cheminFichierHtml = 'C:/All_Rapports_EDLL/general/Commande/en_cours/halifax/' . $nomFichier . '.html';

		file_put_contents($cheminFichierHtml, $message);

		echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';
	
	if($response){ 
		//log_email("REPORT: Entrepot de le lunette Drummondville: commandes en cours",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
		echo '<div class="alert alert-success" role="alert"><strong>Email Sent Sucessfully to:'. $EmailEnvoyerA.'</strong></div>';
	}else{
		//log_email("REPORT: Entrepot de le lunette Drummondville: commandes en cours",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
		echo '<div class="alert alert-danger" role="alert"><strong>Error while sending the email to: '. $EmailEnvoyerA.'</strong></div>';
	}	
}//End if Query gives results
		
$time_end = microtime(true);
$time     = $time_end - $time_start;
$today    = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery       = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
VALUES('Rapport commandes en cours Halifax 2.0', '$time','$today','$timeplus3heures','rapport_commandes_en_cours_halifax.php')"; 					
$cronResult      = mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con)); 


echo $message;
/*
function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysqli_query($con,$queryMail)		or die  ('I cannot Send email because 5: ' . mysqli_error($con));	
}*/
?>