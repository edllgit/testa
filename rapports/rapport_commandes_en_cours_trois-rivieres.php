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
AND user_id IN ('entrepotifc')
AND order_status NOT IN ('cancelled', 'filled','basket','pre-basket')
AND order_num <> -1
ORDER BY  order_date_processed";

if($Debug == 'yes')
echo '<br>rptQuery: <br>'. $rptQuery . '<br>';	

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
AND user_id IN ('entrepotifc','entrepotsafe')
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
			<td bgcolor=\"#99E29D\" align=\"center\">$DataShipped[order_num]</td>
			<td bgcolor=\"#99E29D\" align=\"center\">$DataShipped[tray_num]</td>
			<td bgcolor=\"#99E29D\" align=\"center\">$DataShipped[order_date_processed]</td>
			<td bgcolor=\"#99E29D\" align=\"center\">&nbsp;</td>
			<td bgcolor=\"#99E29D\" align=\"center\">$DataShipped[order_patient_first]&nbsp;$DataShipped[order_patient_last]</td>
			<td bgcolor=\"#99E29D\" align=\"center\">$DataShipped[order_product_name]</td>
			<td bgcolor=\"#99E29D\" align=\"center\">Shipped</td>
			<td bgcolor=\"#99E29D\" align=\"center\">&nbsp;</td>
			<td bgcolor=\"#99E29D\" align=\"center\">$DataShipped[lab_name]</td>
		</tr>";
		
	}//End While
	
	$message.="
		<tr>
			<td align=\"center\"><b>TOTAL:</b></td>
			<td colspan=\"2\" align=\"center\"><b>$NbrResult Orders Shipped Today</b></td>
		</tr>";
		
}//End IF

$message.="<tr bgcolor=\"CCCCCC\">
	<td align=\"center\">Order Number</td>
	<td align=\"center\">Tray</td>
	<td align=\"center\" width=\"150\">Order Date</td>
	<td align=\"center\" width=\"150\">Est. Date</td>
	<td align=\"center\">Patient</td>
	<td align=\"center\">Product</td>
	<td align=\"center\">Order Status</td>
	<td align=\"center\" width=\"150\">Since</td>
	<td align=\"center\">Prescription Lab</td>
</tr>";
				
while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){ 
	
	echo '<br><br><br> Passe dans le while<br>';			
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	switch($listItem["order_status"]){		
		case 'processing':			$list_order_status = "Commande Transmise";		break;
		case 'in coating':			$list_order_status = "Traitement AR";			break;
		case 'profilo':			    $list_order_status = "Profilo";			        break;
		case 'interlab':			$list_order_status = "Traitement AR";			break;
		case 'in edging':			$list_order_status = "Au Taillage";	   		    break;
		case 'in transit':			$list_order_status = "En Transit";				break;
		case 'out for clip':		$list_order_status = "Parti pour clip";			break;	
		case 'in mounting':			$list_order_status = "Au Taillage";				break;
		case 'order imported':		$list_order_status = "Commande en cours";		break;
		case 'information in hand':	$list_order_status = "Info Transmise";   		break;
		case 'interlab vot':	    $list_order_status = "Envoi pour AR";   		break;
		case 'on hold':				$list_order_status = "En Attente";				break;	
		case 'order completed':		$list_order_status = "Production Terminée";   	break;
		case 'delay issue 0':		$list_order_status = "Délai 0";					break;
		case 'delay issue 1':		$list_order_status = "Délai 1";					break;
		case 'delay issue 2':		$list_order_status = "Délai 2";					break;
		case 'delay issue 3':		$list_order_status = "Délai 3";					break;
		case 'delay issue 4':		$list_order_status = "Délai 4";					break;
		case 'delay issue 5':		$list_order_status = "Délai 5";					break;
		case 'delay issue 6':		$list_order_status = "Délai 6";					break;
		case 'filled':				$list_order_status = "Expédiée";    			break;
		case 'cancelled':			$list_order_status = "Annulée";					break;
		case 'waiting for frame':	$list_order_status = "Attente de monture";		break;
		case 'waiting for frame swiss':	$list_order_status = "Attente de monture Swiss";		break;
		case 'waiting for frame knr':	$list_order_status = "Attente de monture KNR";	break;	
		case 'waiting for lens':	$list_order_status = "Attente de verres";		break;	
		case 'waiting for shape':	$list_order_status = "Attente de forme";		break;
		case 're-do':				$list_order_status = "Reprise Interne";			break;
		case 'verifying':			$list_order_status = "Inspection";				break;		
		case 'scanned shape to swiss': 	$list_order_status = "Scanned shape to Swiss"; 		break;
		case 'job started':			$list_order_status = "Surfaçage";				break;	
		case 'interlab tr':			$list_order_status = "Interlab Trois-Rivieres"; break;	
		case 'waiting for frame store':	$list_order_status = "Attente de monture Magasin";		break;
		case 'waiting for frame ho/supplier':	$list_order_status = "Attente de monture Siege Social/Fournisseur";		break;
		default:  				    $list_order_status = 'INCONNU';	                break;	
	}
	
	$queryEstShipDate   = "SELECT est_ship_date FROM est_ship_date WHERE order_num = $listItem[order_num]";		
	echo '<br>queryEstShipDate: '. $queryEstShipDate;
	$resultEstShiPDate  = mysqli_query($con,$queryEstShipDate)		or die  ('I cannot select items because 2: ' . $queryEstShipDate.'<br>'. mysqli_error($con));
	$DataEstShipDate    = mysqli_fetch_array($resultEstShiPDate,MYSQLI_ASSOC);	
	$EstimateShipDate   = $DataEstShipDate[est_ship_date];
		
	$queryLastUpdate    = "SELECT * FROM status_history WHERE  order_num = $listItem[order_num] and order_status = '$listItem[order_status]'";
	if($Debug == 'yes')
	echo '<br>queryLastUpdate1: '. $queryLastUpdate . '<br>';	
	$resultLastUpdate   = mysqli_query($con,$queryLastUpdate)		or die  ('I cannot select items because 3: ' . mysqli_error($con));
	$DataLastUpdate     = mysqli_fetch_array($resultLastUpdate,MYSQLI_ASSOC); 	
	$StatusLastUpdate   = substr($DataLastUpdate[update_time],0,10);		
				
	$message.="
	<tr>
		<td align=\"center\">$listItem[order_num]</td>
		<td align=\"center\">$listItem[tray_num]</td>
		<td align=\"center\">$listItem[order_date_processed]</td>
		<td align=\"center\">$EstimateShipDate</td>
		<td align=\"center\">$listItem[order_patient_first]&nbsp;$listItem[order_patient_last]</td>
		<td align=\"center\">$listItem[order_product_name]</td>
		<td align=\"center\">$list_order_status</td>
		<td align=\"center\">$StatusLastUpdate</td>
		<td align=\"center\">$listItem[lab_name]</td>
	</tr>";
	
	
	$IlyaUnRedoInterne = 'non';
	
	if ($list_order_status == 'Reprise Interne'){	
	
		//Si la command est au status redo interne, on affiche le status de la  reprise interne	
		$queryRepriseInterne  = "SELECT * FROM ORDERS WHERE redo_order_num = $listItem[order_num]" ;	
		if($Debug == 'yes')
		echo '<br>queryRepriseInterne: '. $queryRepriseInterne . '<br>';	
		$resultRepriseInterne = mysqli_query($con,$queryRepriseInterne)		or die  ('I cannot select items because 3: ' . mysqli_error($con));
		$nbrResult = mysqli_num_rows($resultRepriseInterne);
		
	
	if ($nbrResult > 0)	
	{
		$IlyaUnRedoInterne    = 'oui';
		$DataRepriseInterne   = mysqli_fetch_array($resultRepriseInterne,MYSQLI_ASSOC);
		$Redo_Order_Num 	  = $DataRepriseInterne[order_num];
		$queryEstShipDate     = "SELECT est_ship_date FROM est_ship_date WHERE order_num = $Redo_Order_Num";		
		echo 'queryEstShipDate:  <br>'. $queryEstShipDate;
		$resultEstShiPDate    = mysqli_query($con,$queryEstShipDate)		or die  ('I cannot select items because 4: '. '<br>'. $queryEstShipDate . '<br>' . mysqli_error($con));
		$DataEstShipDate      = mysqli_fetch_array($resultEstShiPDate,MYSQLI_ASSOC);	
		$EstimateShipDate     = $DataEstShipDate[est_ship_date];
	}

		switch($DataRepriseInterne["order_status"]){		
			case 'processing':			$list_order_statusRedo = "Commande Transmise";		break;
			case 'in coating':			$list_order_statusRedo = "Traitement AR";			break;
			case 'profilo':			    $list_order_statusRedo = "Profilo";			        break;
			case 'interlab':			$list_order_statusRedo = "Traitement AR";			break;
			case 'in edging':			$list_order_statusRedo = "Au Taillage";	   		    break;
			case 'in transit':			$list_order_statusRedo = "En Transit";				break;
			case 'out for clip':		$list_order_statusRedo = "Parti pour Clip";			break;
			case 'in mounting':			$list_order_statusRedo = "Au Taillage";				break;
			case 'order imported':		$list_order_statusRedo = "Commande en cours";		break;
			case 'information in hand':	$list_order_statusRedo = "Info Transmise";   		break;
			case 'interlab vot':	    $list_order_statusRedo = "Envoi pour AR";   		break;
			case 'on hold':				$list_order_statusRedo = "En Attente";				break;	
			case 'order completed':		$list_order_statusRedo = "Production Terminée";   	break;
			case 'delay issue 0':		$list_order_statusRedo = "Délai 0";					break;
			case 'delay issue 1':		$list_order_statusRedo = "Délai 1";					break;
			case 'delay issue 2':		$list_order_statusRedo = "Délai 2";					break;
			case 'delay issue 3':		$list_order_statusRedo = "Délai 3";					break;
			case 'delay issue 4':		$list_order_statusRedo = "Délai 4";					break;
			case 'delay issue 5':		$list_order_statusRedo = "Délai 5";					break;
			case 'delay issue 6':		$list_order_statusRedo = "Délai 6";					break;
			case 'filled':				$list_order_statusRedo = "Expédiée";    			break;
			case 'cancelled':			$list_order_statusRedo = "Annulée";					break;
			case 'waiting for frame':	$list_order_statusRedo = "Attente de monture";		break;
			case 'waiting for frame swiss':	$list_order_statusRedo = "Attente de monture Swiss";		break;
			case 'waiting for frame knr':	$list_order_statusRedo = "Attente de monture KNR";	break;	
			case 'waiting for frame hko':	$list_order_statusRedo = "Attente de monture Central Lab";	break;
			case 'waiting for lens':	$list_order_statusRedo = "Attente de verres";		break;	
			case 'waiting for shape':	$list_order_statusRedo = "Attente de forme";		break;
			case 're-do':				$list_order_statusRedo = "Reprise Interne";			break;
			case 'verifying':			$list_order_statusRedo = "Inspection";				break;		
			case 'job started':			$list_order_statusRedo = "Surfaçage";				break;	
			case 'interlab tr':			$list_order_statusRedo = "Interlab Trois-Rivieres"; break;	
			default:  				    $list_order_statusRedo = 'INCONNU';	                break;					
		}
	
			
			
	if ($Redo_Order_Num <> ''){
	$queryLastUpdate    = "SELECT * FROM status_history WHERE  order_num = $Redo_Order_Num and order_status = '$DataRepriseInterne[order_status]'";
	if($Debug == 'yes')
	echo '<br>queryLastUpdate 2: '. $queryLastUpdate . '<br>';	
	
	$resultLastUpdate   = mysqli_query($con,$queryLastUpdate)		or die  ('I cannot select items because 5: '  . '<br>'. $queryLastUpdate.'<br>'. mysqli_error($con));
	$NombreResultat     = mysqli_num_rows($resultRepriseInterne);
	}//End IF
	
	
	if  ($NombreResultat > 0){
		$DataLastUpdate     = mysqli_fetch_array($resultLastUpdate,MYSQLI_ASSOC); 	
		$StatusLastUpdate   = substr($DataLastUpdate[update_time],0,10);
	}
		$message.="
		<tr>
			<td align=\"center\"><strong>$Redo_Order_Num</strong></td>
			<td align=\"center\"><strong>$DataRepriseInterne[tray_num]</strong></td>
			<td align=\"center\"><strong>$DataRepriseInterne[order_date_processed]</strong></td>
			<td align=\"center\"><strong>$EstimateShipDate</strong></td>
			<td align=\"center\"><strong>$DataRepriseInterne[order_patient_first]&nbsp;$DataRepriseInterne[order_patient_last]</strong></td>
			<td align=\"center\"><strong>$DataRepriseInterne[order_product_name]</strong></td>
			<td align=\"center\"><strong>$list_order_statusRedo</strong></td>
			<td align=\"center\"><strong>$StatusLastUpdate</strong></td>
			<td align=\"center\"><strong>$DataRepriseInterne[lab_name]</strong></td>
		</tr>";
		
					
	
	}else{

	}//End IF there is an internal redo
	
		
	if ($IlyaUnRedoInterne == 'oui'){//S'il y a un 2ieme redo interne pour cette commande
		$query2iemeRedo  = "SELECT * FROM orders WHERE redo_order_num =  $Redo_Order_Num";
		if($Debug == 'yes')
		echo '<br>query2iemeRedo: <br>'. $query2iemeRedo . '<br>';
		$result2iemeRedo       = mysqli_query($con,$query2iemeRedo)		or die  ('I cannot select items because 6: ' . mysqli_error($con));
		$nbrResult2iemeredo    = mysqli_num_rows($result2iemeRedo);
		
		if ($nbrResult2iemeredo  > 0){//If there is a second redo
			$Data2iemeRedo         = mysqli_fetch_array($result2iemeRedo,MYSQLI_ASSOC);	
			if($Debug == 'yes')
			echo '<br>Data2iemeRedo: <br>'. $Data2iemeRedo . '<br>';
			$queryEstShipDate     = "SELECT est_ship_date FROM est_ship_date WHERE order_num = $Data2iemeRedo[order_num]";		
			$resultEstShiPDate    = mysqli_query($con,$queryEstShipDate)		or die  ('I cannot select items because 7: ' . mysqli_error($con));
			$DataEstShipDate      = mysqli_fetch_array($resultEstShiPDate,MYSQLI_ASSOC);	
			$EstimateShipDate2iemeredo     = $DataEstShipDate[est_ship_date];
			
			
			switch($Data2iemeRedo["order_status"]){		
				case 'processing':			$status2iemeredo = "Commande Transmise";	break;
				case 'in coating':			$status2iemeredo = "Traitement AR";			break;
				case 'profilo':			    $status2iemeredo = "Profilo";			    break;
				case 'interlab':			$status2iemeredo = "Traitement AR";			break;
				case 'in edging':			$status2iemeredo = "Au Taillage";	   		break;
				case 'in transit':			$status2iemeredo = "En Transit";			break;
				case 'out for clip':		$status2iemeredo = "Parti pour Clip";		break;
				case 'in mounting':			$status2iemeredo = "Au Taillage";			break;
				case 'order imported':		$status2iemeredo = "Commande en cours";		break;
				case 'information in hand':	$status2iemeredo = "Info Transmise";   		break;
				case 'interlab vot':	    $status2iemeredo = "Envoi pour AR";   		break;
				case 'on hold':				$status2iemeredo = "En Attente";			break;	
				case 'order completed':		$status2iemeredo = "Production Terminée";   break;
				case 'delay issue 0':		$status2iemeredo = "Délai 0";				break;
				case 'delay issue 1':		$status2iemeredo = "Délai 1";				break;
				case 'delay issue 2':		$status2iemeredo = "Délai 2";				break;
				case 'delay issue 3':		$status2iemeredo = "Délai 3";				break;
				case 'delay issue 4':		$status2iemeredo = "Délai 4";				break;
				case 'delay issue 5':		$status2iemeredo = "Délai 5";				break;
				case 'delay issue 6':		$status2iemeredo = "Délai 6";				break;
				case 'filled':				$status2iemeredo = "Expédiée";    			break;
				case 'cancelled':			$status2iemeredo = "Annulée";				break;
				case 'waiting for frame':	$status2iemeredo = "Attente de monture";	break;
				case 'waiting for frame swiss':	$status2iemeredo = "Attente de monture Swiss";	break;
				case 'waiting for frame knr':	$status2iemeredo = "Attente de monture KNR";	break;
				case 'waiting for frame hko': $status2iemeredo = "Attente de monture Central Lab";	break;
				case 'waiting for lens':	$status2iemeredo = "Attente de verres";		break;	
				case 'waiting for shape':	$status2iemeredo = "Attente de forme";		break;
				case 're-do':				$status2iemeredo = "Reprise Interne";		break;
				case 'verifying':			$status2iemeredo = "Inspection";			break;		
				case 'scanned shape to swiss': 	$list_order_status = "Scanned shape to Swiss"; 		break;
				case 'job started':			$status2iemeredo = "Surfaçage";				break;	
				case 'interlab tr':			$status2iemeredo = "Interlab Trois-Rivieres"; break;
				default:  				    $status2iemeredo = 'INCONNU';	            break;					
			}
			
				$queryLastUpdate    = "SELECT * FROM status_history WHERE  order_num = $Data2iemeRedo[order_num] and order_status = '$Data2iemeRedo[order_status]'";
				if($Debug == 'yes')
				echo '<br>queryLastUpdate: <br>'. $queryLastUpdate . '<br>';
				$resultLastUpdate   = mysqli_query($con,$queryLastUpdate)		or die  ('I cannot select items because 8: ' . mysqli_error($con));
				$DataLastUpdate     = mysqli_fetch_array($resultLastUpdate,MYSQLI_ASSOC); 	
				$StatusLastUpdate   = substr($DataLastUpdate[update_time],0,10);
				
				$message.="
				<tr>
					<td align=\"center\"><font color=\"#FF0004\"><strong>$Data2iemeRedo[order_num]</strong></font></td>
					<td align=\"center\"><font color=\"#FF0004\"><strong>$Data2iemeRedo[tray_num]</strong></font></td>
					<td align=\"center\"><font color=\"#FF0004\"><strong>$Data2iemeRedo[order_date_processed]</strong></font></td>
					<td align=\"center\"><font color=\"#FF0004\"><strong>$EstimateShipDate2iemeredo</strong></font></td>
					<td align=\"center\"><font color=\"#FF0004\"><strong>$Data2iemeRedo[order_patient_first]&nbsp;$DataRepriseInterne[order_patient_last]</strong></font></td>
					<td align=\"center\"><font color=\"#FF0004\"><strong>$Data2iemeRedo[order_product_name]</strong></font></td>
					<td align=\"center\"><font color=\"#FF0004\"><strong>$status2iemeredo</strong></font></td>
					<td align=\"center\"><font color=\"#FF0004\"><strong>$StatusLastUpdate</strong></font></td>
					<td align=\"center\"><font color=\"#FF0004\"><strong>$Data2iemeRedo[lab_name]</strong></font></td>
				</tr>";
			
			}	
	}//End if there is a 2nd Redo internal
		
}//END WHILE

$message.="<tr><td colspan=\"9\">Number of Orders: $ordersnum</td></tr>";



$message.="</table>";
$to_address = array('rapports@direct-lens.com','trois-rivieres@entrepotdelalunette.com');



//$to_address = array('rapports@direct-lens.com');
$curTime	  = date("m-d-Y");	
$from_address ='donotreply@entrepotdelalunette.com';
$subject      = "Entrepot de le lunette TR: commandes en cours";

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
		$nomFichier = 'r_commande_en_cours_trois-rivieres'. $timestamp;
	
		// Enregistrez le contenu HTML dans un fichier
		$cheminFichierHtml = 'C:/All_Rapports_EDLL/general/Commande/en_cours/trois-rivieres/' . $nomFichier . '.html';

		file_put_contents($cheminFichierHtml, $message);

	
		echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';

	if($response){ 
		//log_email("REPORT: Entrepot de le lunette Trois-Rivieres: commandes en cours",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
		echo '<div class="alert alert-success" role="alert"><strong>Email Sent Sucessfully to:'. $EmailEnvoyerA.'</strong></div>';
	}else{
		//log_email("REPORT: Entrepot de le lunette Trois-Rivieres: commandes en cours",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
		echo '<div class="alert alert-danger" role="alert"><strong>Error while sending the email to: '. $EmailEnvoyerA.'</strong></div>';
	}	
}//End if Query gives results
		
$time_end 		 = microtime(true);
$time     		 = $time_end - $time_start;
$today    		 = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery       = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page)
					VALUES('Rapport commandes en cours Trois-Rivieres 2.0', '$time','$today','$timeplus3heures','rapport_commandes_en_cours_trois-rivieres.php') "  ; 					
$cronResult      = mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con)); 

echo $message;
?>