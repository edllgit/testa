<?php
/*
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../../../connexion_hbc.inc.php");
include('../../../phpmailer_email_functions.inc.php');
require_once('../../../class.ses.php');
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

//TODO METTRE A JOUR EN AJOUTANT LES COMPTES DE REPRISES HBC au lieu de redo et redo safety
$rptQuery   = "SELECT order_num, user_id, tray_num, order_date_processed, order_product_name, order_patient_first, order_patient_last, patient_ref_num, order_status, prescript_lab,  redo_order_num
FROM orders WHERE user_id IN ('redo_hbc')
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
	WHERE user_id IN ('88431') AND order_num = $RedoOrderNum";
	$resultRedo  = mysqli_query($con,$queryredo)		or die  ('I cannot select items because 2: ' . mysqli_error($con));
	$DataRedo    = mysqli_fetch_array($resultRedo,MYSQLI_ASSOC);	
	
	switch($DataRedo["order_status"]){		
		case 'processing':			$list_order_status = "Confirmed";				break;
		case 'in coating':			$list_order_status = "In Coating";				break;
		case 'in edging':			$list_order_status = "In Edging";	   		    break;
		case 'in transit':			$list_order_status = "In Transit";				break;
		case 'in mounting':			$list_order_status = "In Mounting";				break;
		case 'order imported':		$list_order_status = "Order Imported";			break;
		case 'interlab vot':	    $list_order_status = "Interlab";   				break;
		case 'on hold':				$list_order_status = "On Hold";					break;	
		case 'order completed':		$list_order_status = "Order Completed";   		break;
		case 'delay issue 0':		$list_order_status = "Delay: Issue #0";			break;
		case 'delay issue 1':		$list_order_status = "Delay: Issue #1";			break;
		case 'delay issue 2':		$list_order_status = "Delay: Issue #2";			break;
		case 'delay issue 3':		$list_order_status = "Delay: Issue #3";			break;
		case 'delay issue 4':		$list_order_status = "Delay: Issue #4";			break;
		case 'delay issue 5':		$list_order_status = "Delay: Issue #5";			break;
		case 'delay issue 6':		$list_order_status = "Delay: Issue #6";			break;
		case 'filled':				$list_order_status = "Shipped";    				break;
		case 'cancelled':			$list_order_status = "Cancelled";				break;
		case 'waiting for frame':	$list_order_status = "Waiting for Frame";		break;
		case 'waiting for frame swiss':	$list_order_status = "Waiting for Frame Swiss";		break;
		case 'waiting for lens':	$list_order_status = "Waiting for Lens";		break;	
		case 'waiting for shape':	$list_order_status = "Waiting for Shape";		break;
		case 're-do':				$list_order_status = "Internal Redo";			break;
		case 'verifying':			$list_order_status = "Verifying";				break;	
		case 'scanned shape to swiss': 	$list_order_status = "Scanned shape to Swiss"; 		break;
		case 'job started':			$list_order_status = "Job Started";				break;	
		case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
		case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		default:  				    $list_order_status = 'UNKNOWN';	                break;						
	}	
	
	$queryLastUpdate    = "SELECT * FROM status_history WHERE  order_num = $listItem[redo_order_num] and order_status = '$DataRedo[order_status]'";
	$resultLastUpdate   = mysqli_query($con,$queryLastUpdate)		or die  ('I cannot select items because 2: ' . mysqli_error($con) . $queryLastUpdate);
	$DataLastUpdate     = mysqli_fetch_array($resultLastUpdate,MYSQLI_ASSOC);	
	$StatusLastUpdate   = substr($DataLastUpdate[update_time],0,10);		
		
	if($DataRedo[user_id] == '88431') 
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
<tr><td colspan=\"8\">Other Redos (the order number belongs to the original order, Other information belongs tro the redo)</td></tr>
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
WHERE user_id IN ('88431')   AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num is not null
AND order_date_processed = '$today'
ORDER BY  order_date_processed";

     $resultRedoExterne  = mysqli_query($con,$queryRedoExterne)		or die  ('I cannot select items because 2: ' . mysqli_error($con));
while ($DataRedoExterne  = mysqli_fetch_array($resultRedoExterne,MYSQLI_ASSOC)){
	
	
switch($DataRedoExterne["order_status"]){		
		case 'processing':			$list_order_status = "Confirmed";				break;
		case 'in coating':			$list_order_status = "In Coating";				break;
		case 'in edging':			$list_order_status = "In Edging";	   		    break;
		case 'in transit':			$list_order_status = "In Transit";				break;
		case 'in mounting':			$list_order_status = "In Mounting";				break;
		case 'order imported':		$list_order_status = "Order Imported";			break;
		case 'interlab vot':	    $list_order_status = "Interlab";   				break;
		case 'on hold':				$list_order_status = "On Hold";					break;	
		case 'order completed':		$list_order_status = "Order Completed";   		break;
		case 'delay issue 0':		$list_order_status = "Delay: Issue #0";			break;
		case 'delay issue 1':		$list_order_status = "Delay: Issue #1";			break;
		case 'delay issue 2':		$list_order_status = "Delay: Issue #2";			break;
		case 'delay issue 3':		$list_order_status = "Delay: Issue #3";			break;
		case 'delay issue 4':		$list_order_status = "Delay: Issue #4";			break;
		case 'delay issue 5':		$list_order_status = "Delay: Issue #5";			break;
		case 'delay issue 6':		$list_order_status = "Delay: Issue #6";			break;
		case 'filled':				$list_order_status = "Shipped";    				break;
		case 'cancelled':			$list_order_status = "Cancelled";				break;
		case 'waiting for frame':	$list_order_status = "Waiting for Frame";		break;
		case 'waiting for frame swiss':	$list_order_status = "Waiting for Frame Swiss";		break;
		case 'waiting for lens':	$list_order_status = "Waiting for Lens";		break;	
		case 'waiting for shape':	$list_order_status = "Waiting for Shape";		break;
		case 're-do':				$list_order_status = "Internal Redo";			break;
		case 'verifying':			$list_order_status = "Verifying";				break;	
		case 'scanned shape to swiss': 	$list_order_status = "Scanned shape to Swiss"; 		break;
		case 'job started':			$list_order_status = "Job Started";				break;	
		case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
		case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		default:  				    $list_order_status = 'UNKNOWN';	                break;					
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

//TODO CHANGER LE USER ID DANS CETTE REQUETE

//Partie GTC qui appartient a la succursale
$queryGTC   = "SELECT order_num, user_id, tray_num, order_date_processed, order_product_name, order_patient_first, order_patient_last, patient_ref_num, order_status, prescript_lab,  redo_order_num
FROM orders
WHERE user_id IN ('garantieatoutcasser') AND internal_note like '%Old Account:88431%'
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

	while ($DataGTC=mysqli_fetch_array($resultGTC,MYSQLI_ASSOC)){
		
	$queryEstShipDateGTC   = "SELECT est_ship_date FROM est_ship_date WHERE order_num = $DataGTC[order_num]";		
	$resultEstShiPDateGTC  = mysqli_query($con,$queryEstShipDateGTC)		or die  ('I cannot select items because 2: ' . mysqli_error($con));
	$DataEstShipDateGTC    = mysqli_fetch_array($resultEstShiPDateGTC,MYSQLI_ASSOC);	
	$EstimateShipDateGTC   = $DataEstShipDateGTC[est_ship_date];		
		
		
switch($DataGTC["order_status"]){		
		case 'processing':			$list_order_statusGTC = "Confirmed";				break;
		case 'in coating':			$list_order_statusGTC = "In Coating";				break;
		case 'in edging':			$list_order_statusGTC = "In Edging";	   		    break;
		case 'in transit':			$list_order_statusGTC = "In Transit";				break;
		case 'in mounting':			$list_order_statusGTC = "In Mounting";				break;
		case 'order imported':		$list_order_statusGTC = "Order Imported";			break;
		case 'interlab vot':	    $list_order_statusGTC = "Interlab";   				break;
		case 'on hold':				$list_order_statusGTC = "On Hold";					break;	
		case 'order completed':		$list_order_statusGTC = "Order Completed";   		break;
		case 'delay issue 0':		$list_order_statusGTC = "Delay: Issue #0";			break;
		case 'delay issue 1':		$list_order_statusGTC = "Delay: Issue #1";			break;
		case 'delay issue 2':		$list_order_statusGTC = "Delay: Issue #2";			break;
		case 'delay issue 3':		$list_order_statusGTC = "Delay: Issue #3";			break;
		case 'delay issue 4':		$list_order_statusGTC = "Delay: Issue #4";			break;
		case 'delay issue 5':		$list_order_statusGTC = "Delay: Issue #5";			break;
		case 'delay issue 6':		$list_order_statusGTC = "Delay: Issue #6";			break;
		case 'filled':				$list_order_statusGTC = "Shipped";    				break;
		case 'cancelled':			$list_order_statusGTC = "Cancelled";				break;
		case 'waiting for frame':	$list_order_statusGTC = "Waiting for Frame";		break;
		case 'waiting for frame swiss':	$list_order_statusGTC = "Waiting for Frame Swiss";		break;
		case 'waiting for lens':	$list_order_statusGTC = "Waiting for Lens";			break;	
		case 'waiting for shape':	$list_order_statusGTC = "Waiting for Shape";		break;
		case 're-do':				$list_order_statusGTC = "Internal Redo";			break;
		case 'verifying':			$list_order_statusGTC = "Verifying";				break;	
		case 'scanned shape to swiss': 	$list_order_statusGTC = "Scanned shape to Swiss"; 		break;
		case 'job started':			$list_order_statusGTC = "Job Started";				break;	
		case 'waiting for frame store':		$list_order_statusGTC = "Waiting for Frame Store";		break;
		case 'waiting for frame ho/supplier':		$list_order_statusGTC = "Waiting for Frame Head Office/Supplier";		break;
		default:  				    $list_order_statusGTC = 'UNKNOWN';	                break;						
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
$to_address = array('rapports@direct-lens.com');
//$to_address = array('rapports@direct-lens.com');//TODO ENLEVER APRES LES TESTS
$from_address = 'donotreply@entrepotdelalunette.com';
$subject      = "Internal Redos Store #88431-Calgary DTN: $today";
//SEND EMAIL
	if ($SendEmail == 'yes'){
		$response = office365_mail($to_address, $from_address, $subject, null, $message);
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


echo $message;
*/
?>