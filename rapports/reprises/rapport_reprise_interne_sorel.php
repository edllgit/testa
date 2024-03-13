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
$today      = date("Y-m-d");

//$today = date("2018-03-13");

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

$rptQuery   = "SELECT order_num, user_id, tray_num, order_date_processed, order_product_name, order_patient_first, order_patient_last, patient_ref_num, order_status, prescript_lab,  redo_order_num
FROM orders
WHERE user_id IN ('redoifc','redosafety')
AND order_date_processed = '$today'
ORDER BY  order_date_processed";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery . '<br>';	

$rptResult = mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 1: ' . mysqli_error($con));
$ordersnum = mysqli_num_rows($rptResult);
	

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
<table class=\"table\">
<tr bgcolor=\"CCCCCC\">
	<td align=\"center\">Order Number</td>
	<td align=\"center\">Tray</td>
	<td align=\"center\" width=\"150\">Order Date</td>
	<td align=\"center\" width=\"150\">Est. Date</td>
	<td align=\"center\">Patient</td>
	<td align=\"center\">Product</td>
	<td align=\"center\">Order Status</td>
	<td align=\"center\" width=\"150\">Since</td>
</tr>";
				
while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){		
$RedoOrderNum = $listItem[redo_order_num];		
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	

	$queryEstShipDate   = "SELECT est_ship_date FROM est_ship_date WHERE order_num = $listItem[redo_order_num]";		
	$resultEstShiPDate  = mysqli_query($con,$queryEstShipDate)		or die  ('I cannot select items because 2: ' . mysqli_error($con));
	$DataEstShipDate    = mysqli_fetch_array($resultEstShiPDate,MYSQLI_ASSOC);	
	$EstimateShipDate   = $DataEstShipDate[est_ship_date];
		
	
	$queryredo  = "SELECT order_num, user_id, tray_num, order_date_processed, order_product_name, order_patient_first, order_patient_last, patient_ref_num, order_status, prescript_lab, redo_order_num 
	FROM orders 
	WHERE user_id IN ('sorel','sorelsafe') AND order_num = $RedoOrderNum";
	$resultRedo  = mysqli_query($con,$queryredo)		or die  ('I cannot select items because 2: ' . mysqli_error($con));
	$DataRedo    = mysqli_fetch_array($resultRedo,MYSQLI_ASSOC);	
	
	switch($DataRedo["order_status"]){		
		case 'processing':			$list_order_status = "Commande Transmise";		break;
		case 'in coating':			$list_order_status = "Traitement AR";			break;
		case 'profilo':			    $list_order_status = "Profilo";			        break;
		case 'interlab':			$list_order_status = "Traitement AR";			break;
		case 'in edging':			$list_order_status = "Au Taillage";	   		    break;
		case 'in transit':			$list_order_status = "En Transit";				break;
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
		case 'waiting for frame swiss':	$list_order_status = "Attente de monture Swiss";	break;
		case 'waiting for frame knr':	$list_order_status = "Attente de monture KNR";		break;
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
	
	$queryLastUpdate    = "SELECT * FROM status_history WHERE  order_num = $listItem[redo_order_num] and order_status = '$DataRedo[order_status]'";
	$resultLastUpdate   = mysqli_query($con,$queryLastUpdate)		or die  ('I cannot select items because 2: ' . mysqli_error($con) . $queryLastUpdate);
	$DataLastUpdate     = mysqli_fetch_array($resultLastUpdate,MYSQLI_ASSOC);	
	$StatusLastUpdate   = substr($DataLastUpdate[update_time],0,10);		
		
	if (($DataRedo[user_id] == 'sorel') || ($DataRedo[user_id] == 'sorelsafe'))
	{
	$message.="
	<tr>
		<td align=\"center\">$DataRedo[order_num]</td>
		<td align=\"center\">$DataRedo[tray_num]</td>
		<td align=\"center\">$DataRedo[order_date_processed]</td>
		<td align=\"center\">$EstimateShipDate</td>
		<td align=\"center\">$DataRedo[order_patient_first]&nbsp;$DataRedo[order_patient_last]</td>
		<td align=\"center\">$DataRedo[order_product_name]</td>
		<td align=\"center\">$list_order_status</td>
		<td align=\"center\">$StatusLastUpdate</td>
	</tr>";
	}
	
	
}//END WHILE
$message.="</table>";


//Redos externes
$message.="
<table class=\"table\" border=\"1\">
<tr><td colspan=\"8\">Autres Redos (le numéro de commande est celui de l'original, les autres information sont celles de la reprise)</td></tr>
<tr bgcolor=\"CCCCCC\">
	<td align=\"center\"># Original</td>
	<td align=\"center\">Cabaret</td>
	<td align=\"center\" width=\"150\">Date</td>
	<td align=\"center\" width=\"150\">Date Est.</td>
	<td align=\"center\">Patient</td>
	<td align=\"center\">Produit</td>
	<td align=\"center\">Status</td>
	<td align=\"center\" width=\"150\">Depuis</td>
</tr>";


$queryRedoExterne = "SELECT order_num, user_id, tray_num, order_date_processed, order_product_name, order_patient_first, order_patient_last, patient_ref_num, order_status, prescript_lab,  redo_order_num
FROM orders
WHERE user_id IN ('sorel')   AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num is not null
AND order_date_processed = '$today'
ORDER BY  order_date_processed";

     $resultRedoExterne  = mysqli_query($con,$queryRedoExterne)		or die  ('I cannot select items because 2: ' . mysqli_error($con));
while ($DataRedoExterne  = mysqli_fetch_array($resultRedoExterne,MYSQLI_ASSOC)){
	
	
switch($DataRedoExterne["order_status"]){		
		case 'processing':			$list_order_status = "Commande Transmise";		break;
		case 'in coating':			$list_order_status = "Traitement AR";			break;
		case 'profilo':			    $list_order_status = "Profilo";			        break;
		case 'in edging':			$list_order_status = "Au Taillage";	   		    break;
		case 'in transit':			$list_order_status = "En Transit";				break;
		case 'in mounting':			$list_order_status = "Au Taillage";				break;
		case 'order imported':		$list_order_status = "Commande en cours";		break;
		case 'information in hand':	$list_order_status = "Info Transmise";   		break;
		case 'interlab vot':	    $list_order_status = "Envoi pour AR";   		break;
		case 'on hold':				$list_order_status = "En Attente";				break;	
		case 'order completed':		$list_order_status = "Production Terminée";   	break;
		case 'delay issue 0':		$list_order_status = "Délai 0";				    break;
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
		case 'waiting for frame knr':	$list_order_status = "Attente de monture KNR";		break;
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
	
	
	$queryEstShipDate   = "SELECT est_ship_date FROM est_ship_date WHERE order_num = $DataRedoExterne[order_num]";		
	$resultEstShiPDate  = mysqli_query($con,$queryEstShipDate)		or die  ('I cannot select items because 2: ' . mysqli_error($con));
	$DataEstShipDate    = mysqli_fetch_array($resultEstShiPDate,MYSQLI_ASSOC);	
	$EstimateShipDate   = $DataEstShipDate[est_ship_date];	
	
	
	$queryLastUpdate    = "SELECT * FROM status_history WHERE  order_num = $DataRedoExterne[order_num] and order_status = '$DataRedoExterne[order_status]'";
	$resultLastUpdate   = mysqli_query($con,$queryLastUpdate)		or die  ('I cannot select items because 2: ' . mysqli_error($con) . $queryLastUpdate);
	$DataLastUpdate     = mysqli_fetch_array($resultLastUpdate,MYSQLI_ASSOC);	
	$StatusLastUpdate   = substr($DataLastUpdate[update_time],0,10);		
	
	$message.="
	<tr>
	<td align=\"center\">$DataRedoExterne[redo_order_num]</td>
	<td align=\"center\">$DataRedoExterne[tray_num]</td>
	<td align=\"center\">$DataRedoExterne[order_date_processed]</td>
	<td align=\"center\">$DataEstShipDate[est_ship_date]</td>
	<td align=\"center\">$DataRedoExterne[order_patient_first]&nbsp;$DataRedoExterne[order_patient_last]</td>
	<td align=\"center\">$DataRedoExterne[order_product_name]</td>
	<td align=\"center\">$list_order_status</td>
	<td align=\"center\">$StatusLastUpdate</td>
	</tr>";	
}
$message.="</table><br><br>";



//Partie GTC qui appartient a la succursale
$queryGTC   = "SELECT order_num, user_id, tray_num, order_date_processed, order_product_name, order_patient_first, order_patient_last, patient_ref_num, order_status, prescript_lab,  redo_order_num
FROM orders
WHERE user_id IN ('garantieatoutcasser') AND internal_note like '%Ancien compte:Sorel%'
AND order_date_processed = '$today'
ORDER BY  order_date_processed";
//echo '<br>'.$queryGTC .'<br>';
$resultGTC      = mysqli_query($con,$queryGTC)	or die ( "Query failed: " . mysqli_error($con)); 

$NbrResult = mysqli_num_rows($resultGTC);
if ($NbrResult>0){
$message.="
   <table  class=\"table\" border=\"1\">
   <tr>VOS GTC</tr>
   <tr bgcolor=\"CCCCCC\">
	<td align=\"center\"># Original</td>
	<td align=\"center\">Cabaret</td>
	<td align=\"center\" width=\"150\">Date</td>
	<td align=\"center\" width=\"150\">Date Est.</td>
	<td align=\"center\">Patient</td>
	<td align=\"center\">Produit</td>
	<td align=\"center\">Status</td>
</tr>";

	while ($DataGTC=mysql_fetch_array($resultGTC,MYSQLI_ASSOC)){
		
	$queryEstShipDateGTC   = "SELECT est_ship_date FROM est_ship_date WHERE order_num = $DataGTC[order_num]";		
	$resultEstShiPDateGTC  = mysqli_query($queryEstShipDateGTC)		or die  ('I cannot select items because 2: ' . mysqli_error($con));
	$DataEstShipDateGTC    = mysqli_fetch_array($resultEstShiPDateGTC,MYSQLI_ASSOC);	
	$EstimateShipDateGTC   = $DataEstShipDateGTC[est_ship_date];			
		
		
switch($DataGTC["order_status"]){		
		case 'processing':			$list_order_statusGTC = "Commande Transmise";		break;
		case 'in coating':			$list_order_statusGTC = "Traitement AR";			break;
		case 'profilo':			    $list_order_statusGTC = "Profilo";			        break;
		case 'in edging':			$list_order_statusGTC = "Au Taillage";	   		    break;
		case 'in transit':			$list_order_statusGTC = "En Transit";				break;
		case 'in mounting':			$list_order_statusGTC = "Au Taillage";				break;
		case 'order imported':		$list_order_statusGTC = "Commande en cours";		break;
		case 'information in hand':	$list_order_statusGTC = "Info Transmise";   		break;
		case 'interlab vot':	    $list_order_statusGTC = "Envoi pour AR";	   		break;
		case 'on hold':				$list_order_statusGTC = "En Attente";				break;	
		case 'order completed':		$list_order_statusGTC = "Production Terminée";   	break;
		case 'delay issue 0':		$list_order_statusGTC = "Délai 0";				    break;
		case 'delay issue 1':		$list_order_statusGTC = "Délai 1";					break;
		case 'delay issue 2':		$list_order_statusGTC = "Délai 2";					break;
		case 'delay issue 3':		$list_order_statusGTC = "Délai 3";					break;
		case 'delay issue 4':		$list_order_statusGTC = "Délai 4";					break;
		case 'delay issue 5':		$list_order_statusGTC = "Délai 5";					break;
		case 'delay issue 6':		$list_order_statusGTC = "Délai 6";					break;
		case 'filled':				$list_order_statusGTC = "Expédiée";    				break;
		case 'cancelled':			$list_order_statusGTC = "Annulée";					break;
		case 'waiting for frame':	$list_order_statusGTC = "Attente de monture";		break;
		case 'waiting for frame swiss':	$list_order_statusGTC = "Attente de monture Swiss";		break;
		case 'waiting for frame knr':	$list_order_statusGTC = "Attente de monture KNR";		break;
		case 'waiting for lens':	$list_order_statusGTC = "Attente de verres";		break;	
		case 'waiting for shape':	$list_order_statusGTC = "Attente de forme";			break;
		case 're-do':				$list_order_statusGTC = "Reprise Interne";			break;
		case 'verifying':			$list_order_statusGTC = "Inspection";				break;		
		case 'scanned shape to swiss': 	$list_order_statusGTC = "Scanned shape to Swiss"; 		break;
		case 'job started':			$list_order_statusGTC = "Surfaçage";				break;	
		case 'interlab tr':			$list_order_statusGTC = "Interlab Trois-Rivieres"; break;	
		case 'waiting for frame store':	$list_order_statusGTC = "Attente de monture Magasin";		break;
		case 'waiting for frame ho/supplier':	$list_order_statusGTC = "Attente de monture Siege Social/Fournisseur";		break;
		default:  				    $list_order_statusGTC = 'INCONNU';	                break;					
	}	
		
		
	$message.="
	<tr>
	<td align=\"center\">$DataGTC[redo_order_num]</td>
	<td align=\"center\">$DataGTC[tray_num]</td>
	<td align=\"center\">$DataGTC[order_date_processed]</td>
	<td align=\"center\">$DataEstShipDateGTC[est_ship_date]</td>
	<td align=\"center\">$DataGTC[order_patient_first]&nbsp;$DataGTC[order_patient_last]</td>
	<td align=\"center\">$DataGTC[order_product_name]</td>
	<td align=\"center\">$list_order_statusGTC</td>
	</tr>";		

	}
}



//Fin de la partie GTC
	
$message.="</table>";
$to_address = array('rapports@direct-lens.com','sorel-tracy@entrepotdelalunette.com');



//$to_address = array('rapports@direct-lens.com');
$from_address = 'donotreply@entrepotdelalunette.com';
$subject      = "Redo Interne Entrepot de le lunette Sorel: $today";
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
		$nomFichier = 'r_reprise_interne_sorel'. $timestamp;
	
		// Enregistrez le contenu HTML dans un fichier
		$cheminFichierHtml = 'C:/All_Rapports_EDLL/general/REPRISE/Quotidien_&_Others/sorel/' . $nomFichier . '.html';

		file_put_contents($cheminFichierHtml, $message);

	
		echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';
	
	if($response){ 
		echo '<div class="alert alert-success" role="alert"><strong>Email Sent Sucessfully to:'. $EmailEnvoyerA.'</strong></div>';
	}else{
		echo '<div class="alert alert-danger" role="alert"><strong>Error while sending the email to: '. $EmailEnvoyerA.'</strong></div>';
	}	
	
echo $message;	
	
//Logger l'exécution du script	
$time_end 		 = microtime(true);
$time     		 = $time_end - $time_start;
$today    		 = date("Y-m-d");
$heure_execution = date("H:i:s");
$CronQuery       = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
					VALUES('Rapport reprises interne Sorel 2.0', '$time','$today','$heure_execution','rapport_reprise_interne_sorel.php')"  ; 					
$cronResult      = mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con)); 



?>