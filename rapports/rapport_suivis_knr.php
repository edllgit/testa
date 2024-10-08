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


$time_start = microtime(true);
$nbrResultat = 0;
$today       = date("Y-m-d");
$rptQuery    = "SELECT  orders.prescript_lab, orders.order_num, orders.order_product_name, accounts.main_lab, accounts.company, orders.order_date_processed,
orders.frame_sent_knr, orders.order_item_date,  orders.frame_sent_hko, orders.frame_sent_swiss, orders.user_id, orders.knr_ref_num,
orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first,
orders.order_patient_last, orders.tray_num,  orders.order_status, orders.internal_note from orders, accounts
	WHERE prescript_lab=73 
	AND order_status NOT IN ('cancelled','filled','basket')  
	AND orders.user_id = accounts.user_id
	ORDER BY order_date_processed asc";

	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 7: <br><br>' . mysqli_error($con));
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


		$message.="<body><table width=\"1025\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\"><b>Order num</b></td>
				<td align=\"center\"><b>Entrepot</b></td>
				<td align=\"center\"><b>Patient Ref</b></td>
				<td align=\"center\"><b>Tray Num</b></td>
				<td align=\"center\"><b>KNR Ref #</b></td>
				<td align=\"center\"><b>Order date</b></td>
				<td align=\"center\"><b>Frame sent</b></td>
                <td align=\"center\"><b>Status</b></td>
				<td align=\"center\"><b>Since</b></td>
                <td align=\"center\"><b>Product</b></td>
				<td align=\"center\"><b>Internal note</b></td>
                <td align=\"center\"><b>Redo Reason</b></td>
				<td align=\"center\"><b>Prod. Lab</b></td>
				</tr>";
				
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
		
	if ($listItem[frame_sent_knr] <> '0000-00-00 00:00:00'){
		$FrameSentOn = $listItem[frame_sent_knr];
		if ($listItem["order_status"]=='waiting for frame knr'){
			$listItem["order_status"]='Frame sent to KNR';
		}//END IF
	}	
	
	
	if ($listItem[frame_sent_knr] == '0000-00-00 00:00:00'){
		$FrameSentOn = '';
	}
	
	
	
	
		
	$today = date("Y-m-d");
	$nbrResultat  = $nbrResultat +1;
		
			
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
						case 'waiting for frame swiss': $list_order_status = "Waiting for Frame Swiss";	break;
						case 'waiting for frame knr':	$list_order_status = "Waiting for Frame KNR";	break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'basket':					$list_order_status = "Basket";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'verifying':				$list_order_status = "Verifying";				break;
						case 'scanned shape to swiss': 	$list_order_status = "Scanned shape to Swiss"; 	break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
						default:						$list_order_status = "UNKNOWN";
		}

    if ($listItem["main_lab"] <> '')
	{
		$queryLab = "SELECT lab_name from labs WHERE primary_key = " . $listItem["main_lab"];
		$ResultLab=mysqli_query($con,$queryLab)		or die  ('I cannot select items because 4: '. $queryLab . mysqli_error($con));
		$DataLab=mysqli_fetch_array($ResultLab,MYSQLI_ASSOC);
		$main_lab = $DataLab[lab_name];
	}
	
			$queryPLab = "SELECT lab_name from labs WHERE primary_key = " . $listItem["prescript_lab"];
			$ResultPLab=mysqli_query($con,$queryPLab)		or die  ('I cannot select items because 5s: ' . mysqli_error($con));
			$DataPLab=mysqli_fetch_array($ResultPLab,MYSQLI_ASSOC);
			$prescript_lab = $DataPLab[lab_name];
		
				
			$queryCompany  = "SELECT company FROM accounts WHERE user_id = (SELECT user_id FROM orders WHERE order_num = $listItem[order_num])";
			$resultCompany = mysqli_query($con,$queryCompany)		or die  ('I cannot select items because 53: ' . mysqli_error($con));
			$DataCompany   = mysqli_fetch_array($resultCompany,MYSQLI_ASSOC);
		
			$queryRedoReason  = "SELECT * FROM redo_reasons  WHERE  redo_reason_id  = (SELECT  redo_reason_id  FROM ORDERS WHERE order_num =  $listItem[order_num])";
		    $resultRedoReason = mysqli_query($con,$queryRedoReason)		or die  ('I cannot select items because 55: ' . mysqli_error($con));
			$DataRedoReason   = mysqli_fetch_array($resultRedoReason,MYSQLI_ASSOC);
			
			if ($DataRedoReason[redo_reason_id] <> 0)
			$RedoReason = $DataRedoReason[redo_reason_en];
			else
			$RedoReason = "";
		
			$queryStatus  = "SELECT max(update_time) as update_time FROM status_history  WHERE  order_num  =   $listItem[order_num] and order_status='$listItem[order_status]'";
			//echo '<br>queryStatus:'. $queryStatus;
			$resultStatus = mysqli_query($con,$queryStatus)		or die  ('I cannot select items because 5a: ' . mysqli_error($con));
			$DataStatus   = mysqli_fetch_array($resultStatus,MYSQLI_ASSOC);
		
		
			$message.="<tr bgcolor=\"$bgcolor\">
			<td align=\"center\">$listItem[order_num]</td>
			<td align=\"center\">$DataCompany[company]</td>
			<td align=\"center\">$listItem[order_patient_first]  $listItem[order_patient_last]</td>
			<td align=\"center\">$listItem[tray_num]</td>
			<td align=\"center\">$listItem[knr_ref_num]</td>
			<td align=\"center\">$listItem[order_date_processed]</td>
			<td align=\"center\">$FrameSentOn</td>
            <td align=\"center\">$listItem[order_status]</td>
            <td align=\"center\">$DataStatus[update_time]</td>
            <td align=\"center\">$listItem[order_product_name]</td>
			<td align=\"center\">$listItem[internal_note]</td>
            <td align=\"center\">$RedoReason</td>
			<td align=\"center\">$prescript_lab</td>";
				
              $message.="</tr>";
		}//END WHILE
		

			
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"12\"><b>Number of open Orders done by KNR: $nbrResultat</b></td></tr></table>";

}else{
	$message.="<div class=\"TextSize\">No Orders</div>";}//END ORDERSNUM CONDITIONAL

//SEND EMAIL

$send_to_address = array('rapports@direct-lens.com','kgawel@direct-lens.com');	



echo "<br>".$send_to_address;	
echo '<br>'. $message;	
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject= "KNR Orders Follow-up Report";
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
	
	
		// Générer le contenu HTML du rapport


		// Créez un nom de fichier unique avec un horodatage
		$date = new DateTime();
		$timestamp = $date->format('Y-m-d_H-i-s');

		$nomFichier = 'r_suivi_knr_'. $timestamp;
	
		// Enregistrez le contenu HTML dans un fichier
		$cheminFichierHtml = 'C:/All_Rapports_EDLL/Fournisseur/' . $nomFichier . '.html';
		file_put_contents($cheminFichierHtml, $message);

	
		echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';

	if($response){ 
		echo 'Reussi';
		//log_email("REPORT: Send EDLL Late Jobs (no status changed since 4 days)",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		echo 'Echec';
		//log_email("REPORT: Send EDLL Late Jobs (no status changed since 4 days)",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	
	
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "Execution time:  $time seconds\n";
$today = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$ip   				   = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$ip2   				   = $_SERVER['HTTP_X_FORWARDED_FOR'];
$ips				   = $ip  . ' ' .$ip2 ;
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page, ip) 
VALUES('Send late jobs report by email 2.0', '$time','$today','$timeplus3heures','cron_send_late_jobs.php','$ips') "  ; 					
$cronResult=mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));		
			
/*
function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because 6: ' . mysql_error());	
}
*/
?>