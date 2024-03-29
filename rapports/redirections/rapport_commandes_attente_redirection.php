<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 

ini_set('display_errors', '1');
include('../../sec_connectEDLL.inc.php');
include('../../phpmailer_email_functions.inc.php');

//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Vérification de la connexion
if ($con->connect_error) {
    die("Erreur de connexion à la base de données : " . $con->connect_error);
}

$lab_pkey 	  = 21;//TR
$today    	  = date("Y-m-d");
$time_start   = microtime(true); 
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

	
$rptQuery2="SELECT accounts.user_id as user_id, accounts.account_num, accounts.company, labs.lab_name, orders.order_num as order_num, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_product_name,orders.redo_reason_id, orders.order_patient_last, orders.tray_num, accounts.company, orders.order_status, orders.redo_order_num, orders.prescript_lab from orders, accounts, labs
WHERE prescript_lab='$lab_pkey'
AND orders.user_id = accounts.user_id
AND orders.lab = labs.primary_key
AND orders.order_date_processed >'2016-03-01'
AND orders.lab<>26
AND orders.order_product_type <> 'frame_stock_tray'
AND orders.order_status!='cancelled' 
AND orders.order_status!='basket'
AND orders.order_status <>'in transit'
AND orders.order_status <>'filled'
AND orders.order_status <>'verifying'
AND orders.order_status <>'basket'
AND orders.order_num <> -1
GROUP BY order_num";
	
//if($Debug == 'yes')
//echo '<br>Query3: <br>'. $rptQuery2 . '<br>';mysqli_query($con,$rptQuery2)

$rptResult=	$con->query($rptQuery2);	//or die  ('I cannot select items because 1: ' . mysqli_error($con). '<br>'. $rptQuery2);
$ordersnum=mysqli_num_rows($rptResult);
	
if ($ordersnum!=0){
$count=0;
$message="";
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

$message.="<body><table table=\"table\" border=\"1\">
<thead align=\"center\">
<th align=\"center\">Order Number</th>
<th align=\"center\">Main Lab</th>
<th align=\"center\">Manufacturer</th>
<th align=\"center\">Customer</th>
<th align=\"center\">Order Date</th>
<th align=\"center\">Redo reason</th>
<th align=\"center\">Product</th>
<th align=\"center\">Patient</th>
<th align=\"center\">Redo of</th>
</thead>";
				
while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	
$CompteEntrepot = 'no';//Par default
//Nouveau pour  g�rer les comptes entrepots 2015-03-10
switch($listItem["user_id"]){//N'inclue pas les compte SAFE
	//ENTREPOTS FRANCOPHONES
	//Trois-Rivi�res
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
	
	//Sherbrooke
	case 'sherbrooke'		:  		$CompteEntrepot = 'yes';  break;
	case 'entrepotsherbrookeframe'	: $CompteEntrepot = 'yes';  break;
	//Vaudreuil
	case 'vaudreuil' 	 :  	 	 $CompteEntrepot= 'yes';  break;	
	case 'vaudreuilsafe':  		 	 $CompteEntrepot= 'yes';  break;//Vaudreuil
	//Sorel
	case 'sorel' 	 :  	 		 $CompteEntrepot= 'yes';  break;	
	case 'sorelsafe':  		 	 	$CompteEntrepot= 'yes';  break;
	
	//Griffe
	case '88666' 	 :  	 		 $CompteEntrepot= 'yes';  break;	
	
	//ENTREPOTS ANGLOPHONES
	//Edmundston
	case 'edmundston' 	 :  	 	 $CompteEntrepot= 'yes';  break;	
	case 'edmundstonsafe':  		 $CompteEntrepot= 'yes';  break;
	//Halifax	
	case 'warehousehal' 	 :  	 $CompteEntrepot= 'yes';  break;
	case 'warehousehalframes':   	 $CompteEntrepot= 'yes';  break;	
	
	//Moncton
	case 'moncton' 	 :  	 		 $CompteEntrepot= 'yes';  break;	
	case 'monctonsafe':  		 	 $CompteEntrepot= 'yes';  break;

	//fredricton
	case 'fredericton' 	 :  	 		 $CompteEntrepot= 'yes';  break;	
	case 'frederictonsafe':  		 	 $CompteEntrepot= 'yes';  break;

	//st -john
	case 'stjohn' 	 :  	 		 $CompteEntrepot= 'yes';  break;	
	case 'stjohnsafe':  		 	 $CompteEntrepot= 'yes';  break;
}	
	
			
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
		case 'on hold':					$list_order_status = "On Hold";					break;
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
		case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
		case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		default:						$list_order_status = "UNKNOWN";
	}
			
	//echo 'Prescript laB:'. 	 $listItem[prescript_lab];
	
	if ($listItem[prescript_lab] <> ''){
		$queryManufacturer  = "SELECT lab_name FROM labs WHERE primary_key = " . $listItem[prescript_lab];
		//echo '<br>'. $queryManufacturer; mysqli_query($con,$queryManufacturer)		or die  ('I cannot select items because 2: ' . mysqli_error($con));
		$resultManufacturer = $con->query($queryManufacturer);
		$DataManufacturer   = mysqli_fetch_array($resultManufacturer,MYSQLI_ASSOC);
		$Manufacturer       = $DataManufacturer[lab_name];
	}
	
	$queryRedoReason  = "SELECT redo_reason_en FROM redo_reasons WHERE redo_reason_id  = $listItem[redo_reason_id]"; 
	//mysqli_query($con,$queryRedoReason)		or die  ('I cannot select items because 3: ' . mysqli_error($con));
	$resultRedoReason = $con->query($queryRedoReason);
	$DataRedoReason   = mysqli_fetch_array($resultRedoReason,MYSQLI_ASSOC);
	
	if ($listItem[redo_order_num] <> ''){
		$queryLab  = "SELECT lab_name FROM labs WHERE primary_key  = (SELECT prescript_lab FROM orders WHERE order_num = $listItem[redo_order_num])"; 
		//echo  '<br>'. $queryLab; mysqli_query($con,$queryLab)		or die  ('I cannot select items because 4: ' . mysqli_error($con) . '<br>'. $queryLab);
		$resultLab = $con->query($queryLab);
		$DataLab   = mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
	}
	
	$message.="
	<tr bgcolor=\"$bgcolor\">
		<td align=\"center\">$listItem[order_num]</td>
		<td align=\"center\">$listItem[lab_name]</td>
		<td align=\"center\">$DataLab[lab_name]</td>
		<td align=\"center\">$listItem[company]</td>
		<td align=\"center\">$order_date</td>
		<td align=\"center\">$DataRedoReason[redo_reason_en]</td>
		<td align=\"center\">$listItem[order_product_name]</td>
		<td align=\"center\">$listItem[order_patient_first] $listItem[order_patient_last]</td>
		<td align=\"center\">$listItem[redo_order_num]</td>	";		

		
		
	$message .="</tr>";
}//END WHILE
			
$message       .="<tr><td>Number of Orders: $ordersnum</td></tr></table>";
$to_address	    = array('rapports@direct-lens.com','dbeaulieu@direct-lens.com');



//$to_address	    = array('dbeaulieu@direct-lens.com');
$curTime        = date("m-d-Y");	
$from_address   = 'donotreply@entrepotdelalunette.com';
$subject		= "Redo waiting for redirection: ".$curTime;
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
		$nomFichier = 'r_commande_en_attente_redirection_'. $timestamp;
	
		// Enregistrez le contenu HTML dans un fichier
		$cheminFichierHtml = 'C:/All_Rapports_EDLL/general/Redirection/' . $nomFichier . '.html';

		file_put_contents($cheminFichierHtml, $message);

	
		echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';

	if($response){ 
		//log_email("REPORT: Rapport Redirection TR",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
		echo '<div class="alert alert-success" role="alert"><strong>Email Sent Sucessfully to:'. $EmailEnvoyerA.'</strong></div>';
	}else{
		//log_email("REPORT: Rapport Redirection TR",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
		echo '<div class="alert alert-danger" role="alert"><strong>Error while sending the email to: '. $EmailEnvoyerA.'</strong></div>';
	}	
}	

echo $message;		

echo 'envoie a '.var_dump($to_address); 


//Logger l'ex�cution du script
$time_end 		 = microtime(true);
$time 			 = $time_end - $time_start;
$today 			 = date("Y-m-d");// current date
$heure_execution = date("H:i:s");
$CronQuery		 = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
					VALUES('Rapport commandes en attente de redirection 2.0', '$time','$today','$heure_execution','rapport_commandes_attente_redirection.php')"; 
						//mysqli_query($con,$CronQuery)		or die ( "Query failed: " . mysqli_error($con));
$cronResult		 = $con->query($CronQuery);

?>

