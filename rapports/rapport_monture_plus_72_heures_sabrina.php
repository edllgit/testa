<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$time_start = microtime(true);	


	$Userid =  " orders.user_id IN ('entrepotifc','entrepotsafe','entrepotdr','safedr','warehousehal','warehousehalsafe','laval','lavalsafe', 'terrebonne','terrebonnesafe',	 'sherbrooke','sherbrookesafe','chicoutimi','chicoutimisafe','levis','levissafe','longueuil','longueuilsafe','granby','granbysafe',	'stjerome','stjeromesafe','gatineau','gatineausafe',	'edmundston','edmundston','vaudreuil','vaudreuilsafe','sorel','sorelsafe','moncton','monctonsafe','fredericton','frederictonsafe','88666','stjohn','stjohnsafe','dartmouth','dartmouthsafe')";     
	$Partie = 'Tous les EDLL';	       
	
	//LIVE
	

	$send_to_address = array('rapports@direct-lens.com','monture@entrepotdelalunette.com','approvisionnement@entrepotdelalunette.com','fdjibrilla@entrepotdelalunette.com');


	//TEST
	//$send_to_address = array('rapports@direct-lens.com');	

	
	$time_start = microtime(true);
	$nbrResultat = 0;
	$today       = date("Y-m-d H:i:s");
	$rptQuery    = "SELECT  orders.prescript_lab, orders.code_source_monture,orders.order_num_optipro, orders.order_num, orders.order_product_name, accounts.main_lab, accounts.company, orders.order_date_processed, orders.order_item_date,  orders.frame_sent_hko, orders.frame_sent_swiss, orders.user_id, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.tray_num,  orders.order_status, orders.internal_note from orders, accounts  WHERE $Userid   AND lab in (66,67,59) AND accounts.user_id = orders.user_id  and orders.order_status IN ('waiting for frame','waiting for frame swiss','waiting for frame knr','waiting for frame store')  ORDER BY order_status asc, order_date_processed";
	echo '<br>'. $rptQuery;
		
		
		$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 7: <br><br>' . mysqli_error($con));
		$ordersnum=mysqli_num_rows($rptResult);
		
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
					<td align=\"center\"><b># Commande</b></td>
					<td align=\"center\"><b># Optipro</b></td>
					<td align=\"center\"><b>Entrepot</b></td>
					<td align=\"center\"><b>Patient</b></td>
					<td align=\"center\"><b>Cabaret</b></td>
					<td align=\"center\"><b>Date Commande</b></td>
					<td align=\"center\"><b>En attente de monture depuis</b></td>
					<td align=\"center\"><b>Produit</b></td>
					<td align=\"center\"><b>Laboratoire</b></td>
					<td align=\"center\"><b>Code Source</b></td>
					</tr>";
					
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
		
		$queryStatusUpdate="SELECT  max(status_history_id) as max_id  from status_history WHERE order_num = ".  $listItem[order_num]	;
		$resultStatusUpdate=mysqli_query($con,$queryStatusUpdate)		or die  ('I cannot select items because 2: <br><br>' . mysqli_error($con));
		$DataStatusHistory=mysqli_fetch_array($resultStatusUpdate,MYSQLI_ASSOC);		
		
		$queryStatus  = "SELECT update_time as MaxUpdateTime from status_history WHERE status_history_id =  " . $DataStatusHistory['max_id'];
		$resultStatus = mysqli_query($con,$queryStatus)		or die  ('I cannot select items because 2: <br><br>' . mysqli_error($con));
		$DataStatus   = mysqli_fetch_array($resultStatus,MYSQLI_ASSOC);	
		$DerniereMAJ  = $DataStatus[MaxUpdateTime];
		
		$queryDateDiff = "SELECT DATEDIFF('$today','$DerniereMAJ') as ladifference"; 
		$resultDateDiff=mysqli_query($con,$queryDateDiff)		or die  ('I cannot select items because 3: <br><br>' . mysqli_error($con));
		$DataDateDiff=mysqli_fetch_array($resultDateDiff,MYSQLI_ASSOC);
		$DataDateDiff[ladifference] = $DataDateDiff[ladifference] * 24;//Transformer le résultat en heures
	//	echo '<br>'. $today . ' '.$DerniereMAJ.' '  . 'Datediff:'. $DataDateDiff[ladifference] . ' heures';
		
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
			case 'basket':					$list_order_status = "Basket";					break;
			case 'cancelled':				$list_order_status = "Cancelled";				break;
			case 'verifying':				$list_order_status = "Verifying";				break;
			case 'scanned shape to swiss': 	$list_order_status = "Scanned shape to Swiss"; 		break;
			case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
			case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
			default:						$list_order_status = "UNKNOWN";
		}
			
		switch($listItem["user_id"]){
			case 'entrepotifc' :  case 'entrepotsafe' :     $Succursale = 'Trois-Rivieres';   				break;
			case 'entrepotdr' :   case 'safedr': 		    $Succursale = 'Drummondville';   			 	break;
			case 'warehousehal' : case 'warehousehalsafe' : $Succursale = 'Halifax';   	   					break;
			case 'laval' :        case 'lavalsafe' :        $Succursale = 'Laval';   		  				break;
			case 'saintemarie' :  case 'saintemariesafe' :  $Succursale = 'Sainte-Marie';     				break;
			case 'terrebonne' :   case 'terrebonnesafe' :   $Succursale = 'Terrebonne';       				break;
			case 'edmundston' :   case 'edmundstonsafe' :   $Succursale = 'Edmundston';       				break;
			case 'moncton' :   	  case 'monctonsafe' :  	$Succursale = 'Moncton';       					break;
			case 'sherbrooke' :   case 'sherbrookesafe' : 	$Succursale = 'Sherbrooke';       				break;
			case 'chicoutimi' :   case 'chicoutimisafe' :   $Succursale = 'Chicoutimi';       				break;
			case 'levis' :        case 'levissafe' : 		$Succursale = 'Lévis';   		   				break;
			case 'granby' :       case 'granbysafe' : 		$Succursale = 'Granby';   		   				break;
			case 'longueuil' :    case 'longueuilsafe' :    $Succursale = 'Longueuil';        			    break;
			case 'stjerome' :     case 'stjeromesafe' :     $Succursale = 'Saint-Jérome';        			break;
			case 'fredericton' :  case 'frederictonsafe' :  $Succursale = 'Fredeticton';       				break;
			case 'stjohn' :       case 'stjohnsafe' :       $Succursale = 'St-John';       				    break;
			case 'dartmouth' :    case 'dartmouthsafe' :    $Succursale = 'Dartmouth';       				break;
			case '88666' :        case '88666' :            $Succursale = 'Griffe lunetier #88666';       	break;
			case 'garantieatoutcasser' :       			   	$Succursale = 'Garantieatoutcasser';            break;
			case 'stemarie' :   case 'stemariesafe' :    	$Succursale = 'Sainte-Marie de Beauce';         break;
			case 'redoifc' :       							$Succursale = 'Compte de reprise Interne IFC';  break;
			case 'redosafety' :       						$Succursale = 'Compte de reprise Interne SAFE'; break;
			case 'St.Catharines' :       					$Succursale = 'Compte de reprise Interne Stc';  break;
			default:  										$Succursale = 'ERREUR';
		}
	
		if ($listItem["main_lab"] <> ''){
			$queryLab = "SELECT lab_name from labs WHERE primary_key = " . $listItem["main_lab"];
			$ResultLab=mysqli_query($con,$queryLab)		or die  ('I cannot select items because 4: '. $queryLab . mysqli_error($con));
			$DataLab=mysqli_fetch_array($con,$ResultLab);
			$main_lab = $DataLab[lab_name];
		}
		
		$queryPLab     = "SELECT lab_name from labs WHERE primary_key = " . $listItem["prescript_lab"];
		$ResultPLab	   = mysqli_query($con,$queryPLab)		or die  ('I cannot select items because 5: ' . mysqli_error($con));
		$DataPLab      = mysqli_fetch_array($ResultPLab,MYSQLI_ASSOC);
		$prescript_lab = $DataPLab[lab_name];
	
		$queryCompany  = "SELECT company FROM accounts WHERE user_id = (SELECT user_id FROM orders WHERE order_num = $listItem[order_num])";
		$resultCompany = mysqli_query($con,$queryCompany)		or die  ('I cannot select items because 5: ' . mysqli_error($con));
		$DataCompany   = mysqli_fetch_array($resultCompany,MYSQLI_ASSOC);
			
		$queryRedoReason  = "SELECT * FROM redo_reasons  WHERE  redo_reason_id  = (SELECT  redo_reason_id  FROM ORDERS WHERE order_num =  $listItem[order_num])";
		$resultRedoReason = mysqli_query($con,$queryRedoReason)		or die  ('I cannot select items because 5: ' . mysqli_error($con));
		$DataRedoReason   = mysqli_fetch_array($resultRedoReason,MYSQLI_ASSOC);
				
		if ($DataRedoReason[redo_reason_id] <> 0)
		$RedoReason = $DataRedoReason[redo_reason_en];
		else
		$RedoReason = "";
			
		$queryJobType = "SELECT job_type FROM extra_product_orders WHERE category='Frame' AND order_num = $listItem[order_num]";
		//echo '<br>'. $queryJobType;
		$resultJobType = mysqli_query($con,$queryJobType)		or die  ('I cannot select items because 5: ' . mysqli_error($con));
		$DataJobType   = mysqli_fetch_array($resultJobType,MYSQLI_ASSOC);
		

		//Si plus de 72 heures au statut waiting for frame, on affiche les détails de cette commande
		if ($DataDateDiff[ladifference] > 71){
		
			//if ($DataJobType[job_type]<>'remote edging'){
			$nbrResultat =  $nbrResultat+1;
			$message.="<tr bgcolor=\"$bgcolor\">
						   <td align=\"center\">$listItem[order_num]</td>
						   <td align=\"center\">$listItem[order_num_optipro]</td>
						   <td align=\"center\">$Succursale</td>
						   <td align=\"center\">$listItem[order_patient_first]  $listItem[order_patient_last]</td>
						   <td align=\"center\">$listItem[tray_num]</td>
						   <td align=\"center\">$listItem[order_date_processed]</td>
						   <td align=\"center\">$DataDateDiff[ladifference] heures</td>
						   <td align=\"center\">$listItem[order_product_name]</td>
						   <td align=\"center\">$prescript_lab</td>
						   <td align=\"center\">$listItem[code_source_monture]</td>
					   </tr>";
			//}//End iF not remote edging
		}//end IF > 71h
	}//END WHILE
				
	$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"10\"><b>Nombre de commande(s) en attente de monture depuis plus de 72 heures: $nbrResultat</b></td></tr></table>";
	
	//SEND EMAIL

	echo "<br>".$send_to_address;	
	echo '<br>'. $message;	
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$subject= "$Partie: Montures en attente depuis >72 heures";
	if ($nbrResultat > 0)
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	
	echo '<br>'; 
	var_dump($send_to_address);
	echo '<br>'; 
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

	$nomFichier = 'r_monture_plus_72h_Sabrina_'. $timestamp;

	// Enregistrez le contenu HTML dans un fichier
	$cheminFichierHtml = 'C:/All_Rapports_EDLL/MONTURE/' . $nomFichier . '.html';
	file_put_contents($cheminFichierHtml, $message);


	echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';
		
		if($response){ 
			echo 'Reussi';
		}else{
			echo 'Echec';
		}	

						

		
$time_end 		 	   = microtime(true);
$time 			 	   = $time_end - $time_start;
$today 			 	   = date("Y-m-d");// current date
$timeplus3heures 	   = date("H:i:s");
$ip   				   = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$ip2   				   = $_SERVER['HTTP_X_FORWARDED_FOR'];
$ips				   = $ip  . ' ' .$ip2 ;
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page, ip) 
VALUES('Rapport monture EDLL en attente > 72 heures 2.0', '$time','$today','$timeplus3heures','rapport_monture_attente_plus_72_heures_edll.php','$ips')"; 					
$cronResult = mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));	
?>