<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../../sec_connectEDLL.inc.php");
include('../../phpmailer_email_functions.inc.php');
require_once('../../class.ses.php');
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

$delais    = 6;
$tomorrow  = mktime(0,0,0,date("m"),date("d")-$delais,date("Y"));
$date1     = date("Y-m-d", $tomorrow);
$date2     = date("Y-m-d");
echo '<br>Du: '. $date1 .'&nbsp;&nbsp;Au '. $date2.'<br><br>';




//Redos externes
$message="
<table class=\"table\" border=\"1\">
<tr bgcolor=\"CCCCCC\">
	<td align=\"center\"># Redo</td>
	<td align=\"center\"># Original</td>
	<td align=\"center\">Cabaret</td>
	<td align=\"center\" width=\"150\">Date</td>
	<td align=\"center\">Patient</td>
	<td align=\"center\">Produit</td>
	<td align=\"center\">Status</td>
	<td align=\"center\" width=\"150\">Depuis</td>
	<td align=\"center\">Raison</td>
</tr>";


$queryRedoExterne = "SELECT order_num, user_id, tray_num, order_date_processed, order_product_name, order_patient_first, order_patient_last, patient_ref_num, order_status, prescript_lab,  redo_order_num, redo_reason_id
FROM orders
WHERE user_id IN ('entrepotifc','entrepotsafe')   AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num is not null
AND order_date_processed BETWEEN '$date1' and '$date2'
AND redo_reason_id is not null
AND redo_reason_id<>0
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
	
	
	if ($DataRedoExterne[redo_reason_id]<>'0'){
		$queryRaisonRedo   	= "SELECT redo_reason_fr FROM redo_reasons WHERE redo_reason_id = $DataRedoExterne[redo_reason_id]";		
		//echo '<br>'.$queryRaisonRedo. '<br>';
		$resultRaisonRedo   = mysqli_query($con,$queryRaisonRedo)		or die  ('I cannot select items because 2a: ' . mysqli_error($con));
		$DataRaisonRedo     = mysqli_fetch_array($resultRaisonRedo,MYSQLI_ASSOC);	
		$RaisonReprise      = $DataRaisonRedo[redo_reason_fr];	
	}else{
		$RaisonReprise      = "";	
	}
	
	$queryLastUpdate    = "SELECT * FROM status_history WHERE  order_num = $DataRedoExterne[order_num] and order_status = '$DataRedoExterne[order_status]'";
	$resultLastUpdate   = mysqli_query($con,$queryLastUpdate)		or die  ('I cannot select items because 2: ' . mysqli_error($con) . $queryLastUpdate);
	$DataLastUpdate     = mysqli_fetch_array($resultLastUpdate,MYSQLI_ASSOC);	
	$StatusLastUpdate   = substr($DataLastUpdate[update_time],0,10);		
	
	$message.="
	<tr>
	<td align=\"center\">$DataRedoExterne[order_num]</td>
	<td align=\"center\">$DataRedoExterne[redo_order_num]</td>
	<td align=\"center\">$DataRedoExterne[tray_num]</td>
	<td align=\"center\">$DataRedoExterne[order_date_processed]</td>
	<td align=\"center\">$DataRedoExterne[order_patient_first]&nbsp;$DataRedoExterne[order_patient_last]</td>
	<td align=\"center\">$DataRedoExterne[order_product_name]</td>
	<td align=\"center\">$list_order_status</td>
	<td align=\"center\">$StatusLastUpdate</td>
	<td align=\"center\">$RaisonReprise</td>
	</tr>";	
}
$message.="</table><br><br>";
	
$message.="</table>";
$to_address = array('rapports@direct-lens.com','trois-rivieres@entrepotdelalunette.com');
//$to_address = array('rapports@direct-lens.com');
$from_address = 'donotreply@entrepotdelalunette.com';
$subject      = "Redo Interne Entrepot de le lunette Trois-Rivieres: $date1-$date2";
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
	
	if($response){ 
		echo '<div class="alert alert-success" role="alert"><strong>Email Sent Sucessfully to:'. $EmailEnvoyerA.'</strong></div>';
	}else{
		echo '<div class="alert alert-danger" role="alert"><strong>Error while sending the email to: '. $EmailEnvoyerA.'</strong></div>';
	}		

//Logger l'exécution du script
$time_end 		 = microtime(true);
$time     		 = $time_end - $time_start;
$today    		 = date("Y-m-d");// current date
$heure_execution = date("H:i:s");
$CronQuery       = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
					VALUES('Rapport reprises interne Trois-Rivieres', '$time','$today','$heure_execution','rapport_reprise_interne_trois-rivieres.php')"; 					
$cronResult      = mysqli_query($con,$CronQuery) or die ( "Query failed: " . mysqli_error($con)); 

echo $message;
?>