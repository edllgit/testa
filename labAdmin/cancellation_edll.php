<?php
include("../Connections/sec_connect.inc.php");
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
$time_start = microtime(true);
$today             = date("Y-m-d");
$OrderNum          = $_REQUEST[order_num]; 
$OrderNumOptipro   = $_REQUEST[order_num_optipro]; 
//$today = date("2015-07-13");


echo '<br>Order Num:'. $OrderNum.'<br>';
echo '<br>Order Num Optipro:'. $OrderNumOptipro.'<br>';

$rptQuery   = "SELECT * FROM orders WHERE order_num  =  $OrderNum AND order_status ='on hold' ORDER BY  order_date_processed";

//echo $rptQuery;





if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery . '<br>';	

$rptResult = mysql_query($rptQuery)		or die  ('I cannot select items because 1: ' . mysql_error());
$nbrResult = mysql_num_rows($rptResult);

if($nbrResult==1){
	
	//Un seul résultat, on poursuit la cancellation
	$provient_de = 'Cancellation par courriel';
	$update_type = 'Cancellation par courriel';
	//1 enregistrer le status dans l'historique de status
	$queryHistory = "INSERT INTO status_history 
	(provient_de,order_num, order_status, update_type)
	 VALUES ('$provient_de',$OrderNum,'cancelled','$update_type')";
	$resultHistory = mysql_query($queryHistory)		or die  ('I cannot select items because 1: ' . mysql_error());
	//echo '<br>'.$queryHistory ;
	 
	//2 mettre le status a jour
	$queryUpdateStatus="UPDATE orders
	SET order_status='cancelled' 
	WHERE order_num = $OrderNum	AND order_status='on hold'";
	$resultStatus = mysql_query($queryUpdateStatus)		or die  ('I cannot select items because 1: ' . mysql_error());
    //echo '<br>'.$queryUpdateStatus ;
	
	echo '<br>La commande ' .$OrderNum.' est maintenant au status: cancell&eacute;e.';	
}else{
	echo '<br>Une erreur est survenue pendant la tentative de cancellation de la commande '. $OrderNum;
}
exit();
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
				
while ($listItem=mysql_fetch_array($rptResult)){	
$RedoOrderNum = $listItem[redo_order_num];		
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	

	$queryEstShipDate   = "SELECT est_ship_date FROM est_ship_date WHERE order_num = $listItem[redo_order_num]";		
	$resultEstShiPDate  = mysql_query($queryEstShipDate)		or die  ('I cannot select items because 2: ' . mysql_error());
	$DataEstShipDate    = mysql_fetch_array($resultEstShiPDate);	
	$EstimateShipDate   = $DataEstShipDate[est_ship_date];
		
	
	$queryredo  = "SELECT order_num, user_id, tray_num, order_date_processed, order_product_name, order_patient_first, order_patient_last, patient_ref_num, order_status, prescript_lab, redo_order_num 
	FROM orders 
	WHERE user_id IN ('entrepotdr','safedr') AND order_num = $RedoOrderNum";
	$resultRedo  = mysql_query($queryredo)		or die  ('I cannot select items because 2: ' . mysql_error());
	$DataRedo    = mysql_fetch_array($resultRedo);	
	
	switch($DataRedo["order_status"]){		
		case 'processing':			$list_order_status = "Commande Transmise";		break;
		case 'in coating':			$list_order_status = "Traitement AR";			break;
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
		case 'waiting for frame swiss':	$list_order_status = "Attente de monture Swiss";		break;
		case 'waiting for lens':	$list_order_status = "Attente de verres";		break;	
		case 'waiting for shape':	$list_order_status = "Attente de forme";		break;
		case 're-do':				$list_order_status = "Reprise Interne";			break;	
		case 'job started':			$list_order_status = "Surfaçage";				break;
		case 'verifying':			$list_order_status = "Inspection";				break;	
		case 'scanned shape to swiss':$list_order_status = "Scanned shape to Swiss";break;
		case 'waiting for frame store':	$list_order_status = "Attente de monture Store";		break;
		case 'waiting for frame ho/supplier':	$list_order_status = "Attente de monture Head Office/Supplier";		break;
		
		default:  				    $list_order_status = 'ERREUR';	                break;					
	}	
	
	$queryLastUpdate    = "SELECT * FROM status_history WHERE  order_num = $listItem[redo_order_num] and order_status = '$DataRedo[order_status]'";
	$resultLastUpdate   = mysql_query($queryLastUpdate)		or die  ('I cannot select items because 2: ' . mysql_error() . $queryLastUpdate);
	$DataLastUpdate     = mysql_fetch_array($resultLastUpdate);	
	$StatusLastUpdate   = substr($DataLastUpdate[update_time],0,10);		
		
	if (($DataRedo[user_id] == 'entrepotdr') || ($DataRedo[user_id] == 'safedr'))
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
WHERE user_id IN ('entrepotdr')  AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num is not null
AND order_date_processed = '$today'
ORDER BY  order_date_processed";

$resultRedoExterne  = mysql_query($queryRedoExterne)		or die  ('I cannot select items because 2: ' . mysql_error());
while ($DataRedoExterne  = mysql_fetch_array($resultRedoExterne)){
	
	
switch($DataRedoExterne["order_status"]){		
		case 'processing':			$list_order_status = "Commande Transmise";		break;
		case 'in coating':			$list_order_status = "Traitement AR";			break;
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
		case 'waiting for frame swiss':	$list_order_status = "Attente de monture Swiss";		break;
		case 'waiting for lens':	$list_order_status = "Attente de verres";		break;	
		case 'waiting for shape':	$list_order_status = "Attente de forme";		break;
		case 're-do':				$list_order_status = "Reprise Interne";			break;
		case 'verifying':			$list_order_status = "Inspection";				break;		
		case 'job started':			$list_order_status = "Surfaçage";				break;
		case 'waiting for frame store':	$list_order_status = "Attente de monture Magasin";		break;
		case 'waiting for frame ho/supplier':	$list_order_status = "Attente de monture Siege Social/Fournisseur";		break;	
		default:  				    $list_order_status = 'ERREUR';	                break;	
	}		
	
	

	$queryEstShipDate   = "SELECT est_ship_date FROM est_ship_date WHERE order_num = $DataRedoExterne[order_num]";		
	$resultEstShiPDate  = mysql_query($queryEstShipDate)		or die  ('I cannot select items because 2: ' . mysql_error());
	$DataEstShipDate    = mysql_fetch_array($resultEstShiPDate);	
	$EstimateShipDate   = $DataEstShipDate[est_ship_date];	
	
	
	$queryLastUpdate    = "SELECT * FROM status_history WHERE  order_num = $DataRedoExterne[order_num] and order_status = '$DataRedoExterne[order_status]'";
	$resultLastUpdate   = mysql_query($queryLastUpdate)		or die  ('I cannot select items because 2: ' . mysql_error() . $queryLastUpdate);
	$DataLastUpdate     = mysql_fetch_array($resultLastUpdate);	
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


	
$message.="</table>";
$to_address = array('rapports@direct-lens.com');	
$from_address = 'donotreply@entrepotdelalunette.com';
$subject      = "Redo Interne Entrepot de le lunette DR: $today";
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
		log_email("REPORT: Entrepot de le lunette Drummondville: commandes en cours",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
		echo '<div class="alert alert-success" role="alert"><strong>Email Sent Sucessfully to:'. $EmailEnvoyerA.'</strong></div>';
	}else{
		log_email("REPORT: Entrepot de le lunette Drummondville: commandes en cours",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
		//echo '<div class="alert alert-danger" role="alert"><strong>Error while sending the email to: '. $EmailEnvoyerA.'</strong></div>';
	}	

		
$time_end = microtime(true);
$time     = $time_end - $time_start;
$today    = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery       = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) VALUES('Entrepot lunette Drummondville Orders In production', '$time','$today','$timeplus3heures','cron_daily_eyelation_report.php') "  ; 					
$cronResult      = mysql_query($CronQuery)			or die ( "Query failed: " . mysql_error()); 


echo $message;
function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because 5: ' . mysql_error());	
}
?>