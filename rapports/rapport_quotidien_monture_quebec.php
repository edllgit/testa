<?php
//RAPPORT pour Québec seulement

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$time_start   = microtime(true); 
$yesterday	  = mktime(0,0,0,date("m"),date("d"),date("Y"));
$ladate       = date("Y-m-d", $yesterday);	

//A RECOMMENTER
//$ladate = "2020-11-21";//Hard coder une date	

$rptQuery="SELECT orders.*, ifc_ca_exclusive.price FROM orders, extra_product_orders , ifc_ca_exclusive
WHERE ifc_ca_exclusive.primary_key = orders.order_product_id  
AND orders.order_num = extra_product_orders.order_num 
AND extra_product_orders.category='Frame' 
AND order_from in('ifcclubca') 
AND order_date_processed BETWEEN '$ladate' AND '$ladate' 
AND user_ID in ('entrepotquebec','quebecsafe')
AND orders.code_source_monture NOT IN ('S')
AND order_product_type = 'exclusive' 

ORDER BY supplier,temple_model_num DESC";

echo $query;

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
		$message.="
		<tr>
			<td colspan=\"11\">Ce rapport contient toutes les montures vendues par la succursale de Québec</td>
		</tr>
		
		<tr bgcolor=\"CCCCCC\">
			<th align=\"center\">Date</th>
			<th align=\"center\">Compte</th>			
			<th align=\"center\">Numéro de commande</th>
			<th align=\"center\">Numéro de reprise</th>
			<th align=\"center\">Collection</th>
			<th align=\"center\">Model</th>
			<th align=\"center\">Couleur</th>
			<th align=\"center\">A</th>
			<th align=\"center\">Code Source</th>
		</tr>";
		$totalPrice      = 0;
		$totalOrderTotal = 0;	
	
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
		$queryFrame  = "SELECT order_num, frame_type, supplier, model, temple_model_num, color,ep_frame_a,ep_frame_dbl FROM extra_product_orders WHERE order_num = $listItem[order_num] AND category in ('Frame','Edging')";
		$resultFrame = mysqli_query($con,$queryFrame)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataFrame   = mysqli_fetch_array($resultFrame,MYSQLI_ASSOC);
			
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
						case 'waiting for frame swiss':	$list_order_status = "Waiting for Frame Swiss";	break;
						case 'waiting for frame knr':	$list_order_status = "Waiting for Frame KNR";	break;
						case 'waiting for lens':		$list_order_status = "Waiting for Lens";		break;
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

			
			
switch($listItem[user_id]){
	case 'chicoutimi':   $entrepot = 'Chicoutimi';     break; 
	case 'levis':        $entrepot = 'Levis';          break;
	case 'entrepotquebec': $entrepot = 'Quebec';       break; 
	case 'entrepotdr':   $entrepot = 'Drummondville';  break; 
	case 'granby':       $entrepot = 'Granby';         break;
	case 'sherbrooke':   $entrepot = 'Sherbrooke';     break; 
	case 'entrepotifc':  $entrepot = 'Trois-Rivieres'; break;
	case 'gatineau':     $entrepot = 'Gatineau';       break; 
	case 'laval':        $entrepot = 'Laval';          break; 
	case 'longueuil':    $entrepot = 'Longueuil';      break;
	case 'stjerome':     $entrepot = 'St-Jérôme';      break; 
	case 'terrebonne':   $entrepot = 'Terrebonne';     break; 
	case '88666':        $entrepot = '#88666 Griffe';     break; 
	//case 'montreal':     $entrepot = 'Montreal';     break; 	
	case 'warehousehal': $entrepot = 'Halifax';        break; 
	case 'redoifc':      $entrepot = 'Compte Reprise'; break; 
	case 'St.Catharines':$entrepot = 'Compte Reprise'; break; 
	default: $entrepot = "Inconnu";
}


			$message.="	<tr bgcolor=\"$bgcolor\">";

               				$message.="
							<td align=\"center\">$listItem[order_date_processed]</td>
							<td align=\"center\">$entrepot</td>
							<td align=\"center\">$listItem[order_num]</td>
							<td align=\"center\">$listItem[redo_order_num]</td>
							<td align=\"center\">$DataFrame[supplier]</td>
							<td align=\"center\">$DataFrame[temple_model_num]</td>
							<td align=\"center\">$DataFrame[color]</td>
							<td align=\"center\">$DataFrame[ep_frame_a]-$DataFrame[ep_frame_dbl]</td>
							<td align=\"center\">$listItem[code_source_monture]</td>
							";
              $message.="</tr>";
			  
		
		}//END WHILE
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Nombre de commande(s): $ordersnum</td></tr>
		</table>";
		
		echo $message;

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');



//$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Rapport des montures vendues Succursale de Québec: $ladate";
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

		$nomFichier = 'r_quotidien_monture_quebec_'. $timestamp;
	
		// Enregistrez le contenu HTML dans un fichier
		$cheminFichierHtml = 'C:/All_Rapports_EDLL/MONTURE/' . $nomFichier . '.html';
		file_put_contents($cheminFichierHtml, $message);

	
		echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';

}//END IF?	
?>