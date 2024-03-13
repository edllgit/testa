<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../../sec_connectEDLL.inc.php");
include('../../phpmailer_email_functions.inc.php');
require_once('../../class.ses.php');

//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$time_start = microtime(true);
$lab_pkey   = 60;//CSC ID
$today      = date("Y-m-d");
//$today=date("2018-03-10");


		
$rptQuery="SELECT accounts.user_id as user_id, accounts.account_num, accounts.company, labs.lab_name, orders.order_num as order_num, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.tray_num, accounts.company, orders.order_status, orders.order_product_name from orders

	LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
	LEFT JOIN (labs) ON (orders.lab = labs.primary_key) 
	WHERE prescript_lab='$lab_pkey' AND orders.lab!='$lab_pkey' 
	AND orders.order_date_processed='$today'
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')
	OR
	prescript_lab='$lab_pkey' 
	AND  orders.order_status='processing'
	GROUP BY order_num";
	
	$rptResult=mysqli_query($con,$rptQuery) or die  ('I cannot select items because: ' . mysqli_error($con));
	$ordersnum = mysqli_num_rows($rptResult);
	echo '<br>'. $rptQuery.'<br>';
	echo '<br>'. $ordersnum.'<br>';

if ($ordersnum > 0)
{	
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
				<td align=\"center\">Product</td>
				<td align=\"center\">Patient</td>
                <td align=\"center\">Tray Number</td>
                <td align=\"center\">Order Status</td>
				</tr>";
				
				
				
		echo '<br>Avant le while';		
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
		$CompteEntrepot = 'no';//Par default
//Nouveau pour  gérer les comptes entrepots 2015-03-10
switch($listItem["user_id"]){//N'inclue pas les compte SAFE
	//ENTREPOTS FRANCOPHONES
	//Trois-Rivières
	case 'entrepotifc'		:  		$CompteEntrepot = 'yes';  break;
	case 'entrepotframes'	:  		$CompteEntrepot = 'yes';  break;
	//Drummondville		
	case 'entrepotdr'		:  		$CompteEntrepot = 'yes';  break;
	case 'entrepotdrframes' :  		$CompteEntrepot = 'yes';  break;	
	//Laval
	case 'laval'			 : 	    $CompteEntrepot = 'yes';  break;
	case 'entrepotlavalframe':  	$CompteEntrepot = 'yes';  break;
	//Terrebonne	
	case 'terrebonne'			  :  $CompteEntrepot = 'yes';  break;
	case 'entrepotterrebonneframe':  $CompteEntrepot= 'yes';  break;
	//Quebec
	case 'quebec'			  :  	 $CompteEntrepot= 'yes';  break;
	case 'entrepotquebecframe':  	 $CompteEntrepot= 'yes';  break;
	
	//Giffe
	case '88666'			  :  	 $CompteEntrepot= 'yes';  break;

	//ENTREPOTS ANGLOPHONES
	//Saint-Catharines	
	case 'warehousestc' 	 :  	 $CompteEntrepot= 'yes';  break;	
	case 'warehousestcframes':  	 $CompteEntrepot= 'yes';  break;
	//Halifax	
	case 'warehousehal' 	 :  	 $CompteEntrepot= 'yes';  break;
	case 'warehousehalframes':   	 $CompteEntrepot= 'yes';  break;	
}	
	
			
			echo '<br>Dans le while';	
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
						case 'scanned shape to swiss': 	$list_order_status = "Scanned shape to Swiss"; 	break;
						case "on hold":					$$list_order_status= "On Hold";			        break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
						default:                        $list_order_status = "UNKNOWN";             	break;
		}



			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$listItem[order_num]</td>
                <td align=\"center\">$listItem[lab_name]</td>
				<td align=\"center\">$listItem[order_date_processed]</td>
				<td align=\"center\">$listItem[order_product_name]</td>
                <td align=\"center\">$listItem[order_patient_first] $listItem[order_patient_last]</td>
                <td align=\"center\">$listItem[tray_num]</td>
                <td align=\"center\">$list_order_status</td>";
				
		if ($CompteEntrepot == 'yes'){
			$queryEdgingType  = "SELECT job_type FROM extra_product_orders WHERE order_num =  $listItem[order_num] AND category=\"Edging\"";
			$resultEdgingType = mysqli_query($con,$queryEdgingType)		or die  ('I cannot select items because: ' . mysqli_error($con));
			$DataEdgingType   = mysqli_fetch_array($resultEdgingType,MYSQLI_ASSOC);
			$EdgingType       = $DataEdgingType[job_type];
			$message         .="<td>$EdgingType</td>";
		}else{
			$message         .="<td>&nbsp;</td>";	
		}
				
              $message.="</tr>";
		}//END WHILE
		echo '<br>Sort du while';		
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";

		
	echo $message;	
		
		
		
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');



//$send_to_address = array('rapports@direct-lens.com');
echo "<br>".var_dump($send_to_address);
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Orders of the day - CSC";
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



		// Créez un nom de fichier unique avec un horodatage
		$date = new DateTime();
		$timestamp = $date->format('Y-m-d_H-i-s');

		$nomFichier = 'r_redirection_csc_'. $timestamp;
	
		// Enregistrez le contenu HTML dans un fichier
		$cheminFichierHtml = 'C:/All_Rapports_EDLL/general/Redirection/' . $nomFichier . '.html';
		file_put_contents($cheminFichierHtml, $message);

	
		echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';
	
	if($response){ 
		echo 'Reussi';
    }else{
		echo 'Echec';
	}	
	



}//End If There are orders
		
//Logger l'exécution du script	
$time_end 		 = microtime(true);
$time 			 = $time_end - $time_start;
$today 			 = date("Y-m-d");
$heure_execution = date("H:i:s");
$CronQuery		 = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page)
					VALUES('Email redirection CSS 2.0', '$time','$today','$heure_execution','rapport_redirection_csc.php')"; 					
$cronResult		 = mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));

?>