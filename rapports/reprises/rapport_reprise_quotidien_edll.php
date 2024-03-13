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
$today	    = date("Y-m-d");
$today2	    = date("Y-m-d");

/*
$today    = date("2019-04-26");
$today2   = date("2019-04-26");
*/

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

//N'INCLUE PLUS LES REDO DE NOS COMPTE DE REDO A NOUS (redoifc,St.Catharines)	
$rptQuery="SELECT * 
FROM orders
WHERE lab IN (66,67)  
AND orders.order_date_processed BETWEEN '$today' AND '$today2' 
AND redo_order_num IS NOT NULL
AND orders.order_status NOT IN ('cancelled','basket','pre-basket','on hold')
AND user_id NOT IN ('redoifc','St.Catharines')
GROUP BY order_num order by prescript_lab";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery . '<br>';

$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);
	

	$count=0;
	$message="";	
	$message="
	<html>
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
	
	$message.="
	<body>
	<h3>Redo des entrepots ($today  $today2)</h3>
	<table border=\"1\" class=\"table\">
	<thead>
		<th>Account</th>
		<th>Date</th>
		<th>Order Num</th>
		<th>Redo Of</th>
		<th>Total</th>
		<th>Product</th>
		<th>Manufacturer 1st order</th>
		<th>Redo Reason</th>
		<th>Authorized By</th>
		<th>Detail</th>
	</thead>";
					
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
		
		
	$queryRedoReason  = "SELECT  redo_reason_en FROM redo_reasons WHERE redo_Reason_id =  $listItem[redo_reason_id]";
	$ResultRedoReason = mysqli_query($con,$queryRedoReason)			or die ( "Query failed: " . mysqli_error($con));
	$DataRedoReason   = mysqli_fetch_array($ResultRedoReason,MYSQLI_ASSOC);
							
		$count++;
		if (($count%2)==0)
			$bgcolor="#E5E5E5";
		else 
			$bgcolor="#FFFFFF";
		
		if ($listItem[extra_product] == '0')
		$listItem[extra_product]="";
		
		$queryManufacturer  = "SELECT lab_name FROM labs WHERE primary_key =  (SELECT prescript_lab FROM orders WHERE order_num = $listItem[redo_order_num])";
		$resultManufacturer = mysqli_query($con,$queryManufacturer)			or die ( "Query failed: " . mysqli_error($con));
		$DataManufacturer   = mysqli_fetch_array($resultManufacturer,MYSQLI_ASSOC);
		$Manufacturer       = $DataManufacturer[lab_name];
		if ($Manufacturer == 'Direct-Lens Exclusive #1')
		$Manufacturer    = 'Swiss';
		if ($Manufacturer == 'Direct-Lens Exclusive #2')
		$Manufacturer    = 'Central Lab';
		
		
		$queryCompany       = "SELECT company FROM accounts WHERE user_id = '$listItem[user_id]'";
		$resultCompany 		= mysqli_query($con,$queryCompany)			or die ( "Query failed: " . mysqli_error($con));
		$DataCompany   		= mysqli_fetch_array($resultCompany,MYSQLI_ASSOC);
		$Company       		= $DataCompany[company];
		
		$queryAuthorizedBy  = "SELECT redo_approved_by FROM status_history WHERE  order_num =  $listItem[order_num] AND order_status = 'processing'";
		$resultAuthorizedBy	= mysqli_query($con,$queryAuthorizedBy)			or die ( "Query failed: " . mysqli_error($con));
		$DataAuthorizedBy   = mysqli_fetch_array($resultAuthorizedBy,MYSQLI_ASSOC);
		$redo_approved_by  	= $DataAuthorizedBy[redo_approved_by];
		
		
		$message.="
		<tr>
			<td>$Company</td>
			<td>$listItem[order_date_processed]</td>
			<td>$listItem[order_num]</td>
			<td>$listItem[redo_order_num]</td>
			<td>$listItem[order_total]</td>
			<td>$listItem[order_product_name]</td>
			<td>$Manufacturer</td>
			<td>$DataRedoReason[redo_reason_en]</td>
			<td>$redo_approved_by</td>
			<td>$listItem[extra_product]&nbsp;&nbsp;$listItem[special_instructions]&nbsp;</td>
		</tr>";
	}//END WHILE
	
	$message.="<tr><td colspan=\"10\">Number of Orders: $ordersnum</td></tr></table><br>";
	
	

	
//PARTIE DES REDOS INTERNE
$rptQuery="SELECT * 
FROM orders
WHERE lab IN (66,67)  
AND orders.order_date_processed BETWEEN '$today' AND '$today2' 
AND redo_order_num IS NOT NULL
AND (orders.order_status!='cancelled' AND orders.order_status!='basket')
AND user_id  IN ('redoifc','St.Catharines')
GROUP BY order_num order by prescript_lab";

if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery . '<br>';

$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);

	$message.="
	<h3>REDOS INTERNES ($today $today2)</h3>
	<table border=\"1\" class=\"table\">
	<thead>
		<th>Account</th>
		<th>Date</th>
		<th>Order Num</th>
		<th>Redo Of</th>
		<th>Total</th>
		<th>Product</th>
		<th>Manufacturer 1st order</th>
		<th>Redo Reason</th>
		<th>Authorized By</th>
		<th>Detail</th>
	</thead>";
					
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
		
		
	$queryRedoReason  = "SELECT  redo_reason_en FROM redo_reasons WHERE redo_Reason_id =  $listItem[redo_reason_id]";
	$ResultRedoReason = mysqli_query($con,$queryRedoReason)			or die ( "Query failed: " . mysqli_error($con));
	$DataRedoReason   = mysqli_fetch_array($ResultRedoReason,MYSQLI_ASSOC);
							
		$count++;
		if (($count%2)==0)
			$bgcolor="#E5E5E5";
		else 
			$bgcolor="#FFFFFF";
		
		if ($listItem[extra_product] == '0')
		$listItem[extra_product]="";
		
		$queryManufacturer  = "SELECT lab_name FROM labs WHERE primary_key =  (SELECT prescript_lab FROM orders WHERE order_num = $listItem[redo_order_num])";
		$resultManufacturer = mysqli_query($con,$queryManufacturer)			or die ( "Query failed: " . mysqli_error($con));
		$DataManufacturer   = mysqli_fetch_array($resultManufacturer,MYSQLI_ASSOC);
		$Manufacturer       = $DataManufacturer[lab_name];
		if ($Manufacturer == 'Direct-Lens Exclusive #1')
			$Manufacturer    = 'Swiss';
		if ($Manufacturer == 'Direct-Lens Exclusive #2')
			$Manufacturer    = 'Central Lab';
		
		$queryCompany       = "SELECT company FROM accounts WHERE user_id = '$listItem[user_id]'";
		$resultCompany 		= mysqli_query($con,$queryCompany)			or die ( "Query failed: " . mysqli_error($con));
		$DataCompany   		= mysqli_fetch_array($resultCompany,MYSQLI_ASSOC);
		$Company       		= $DataCompany[company];
		
		$queryAuthorizedBy  = "SELECT redo_approved_by FROM status_history WHERE  order_num =  $listItem[order_num] AND order_status = 'processing'";
		$resultAuthorizedBy	= mysqli_query($con,$queryAuthorizedBy)			or die ( "Query failed: " . mysqli_error($con));
		$DataAuthorizedBy   = mysqli_fetch_array($resultAuthorizedBy,MYSQLI_ASSOC);
		$redo_approved_by  	= $DataAuthorizedBy[redo_approved_by];
		
		$message.="
		<tr>
			<td>$Company</td>
			<td>$listItem[order_date_processed]</td>
			<td>$listItem[order_num]</td>
			<td>$listItem[redo_order_num]</td>
			<td>$listItem[order_total]</td>
			<td>$listItem[order_product_name]</td>
			<td>$Manufacturer</td>
			<td>$DataRedoReason[redo_reason_en]</td>
			<td>$redo_approved_by</td>
			<td>$listItem[extra_product]&nbsp;&nbsp;$listItem[special_instructions]&nbsp;</td>
		</tr>";
	}//END WHILE
	
	$message.="<tr><td colspan=\"10\">Number of Orders: $ordersnum</td></tr></table>";	
		

		
	//$to_address = array('rapports@direct-lens.com');
	$to_address = array('rapports@direct-lens.com','ebaillargeon@entrepotdelalunette.com','abedard@entrepotdelalunette.com','kgawel@direct-lens.com','dbeaulieu@entrepotdelalunette.com');



	//$to_address = array('rapports@direct-lens.com');
	$from_address = 'donotreply@entrepotdelalunette.com';
	$subject      = "EDLL/COW Redos: (". $today.'  '. $today2 .')';
	
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

	$nomFichier = 'r_reprise_interne_quotidien_'. $timestamp;

	// Enregistrez le contenu HTML dans un fichier
	$cheminFichierHtml = 'C:/All_Rapports_EDLL/general/REPRISE/Quotidien_&_Others/' . $nomFichier . '.html';
	file_put_contents($cheminFichierHtml, $message);


	echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';

		if($response){ 
			echo '<div class="alert alert-success" role="alert"><strong>Email Sent Sucessfully to:'. $EmailEnvoyerA.'</strong></div>';
		}else{
			echo '<div class="alert alert-danger" role="alert"><strong>Error while sending the email to: '. $EmailEnvoyerA.'</strong></div>';
		}	

//Logger l'exécution du script
$time_end 		 = microtime(true);
$time     		 = $time_end - $time_start;
$heure_execution = date("H:i:s");
$CronQuery       = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
					VALUES('EDLL/COW Redos', '$time','$today','$heure_execution','rapport_reprise_quotidien_edll.php')"; 					
$cronResult      = mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));
echo $message;

?>