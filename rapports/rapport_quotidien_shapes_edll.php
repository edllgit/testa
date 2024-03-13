<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$time_start = microtime(true);
if ($_REQUEST[ladate]<>'')
	$today      = $_REQUEST[ladate];
else
$today      = date("Y-m-d");

//$today=date("2022-05-18");

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

/*
$rptQuery   = "SELECT * FROM orders
WHERE prescript_lab IN (10,25,69,73)
AND order_date_processed='$today'
AND order_status NOT IN ('cancelled', 'on hold','basket')
ORDER BY prescript_lab, shape_name_bk desc";
*/

$rptQuery   = "SELECT * FROM orders
WHERE prescript_lab IN (10,25,69,73)
AND order_date_processed ='$today'
AND order_status NOT IN ('cancelled', 'on hold','basket')
ORDER BY prescript_lab, shape_name_bk desc";

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

$message.="<body><table class=\"table\">
<tr><td colspan=\"5\">Ce rapport inclus toutes les commandes envoyées vers Swiss, HKO et GKB durant la journée</td></tr>";
$message.="<tr bgcolor=\"CCCCCC\">
	<td align=\"center\" width=\"150\">Date confirmation</td>
	<td align=\"center\">EDLL Order #</td>
	<td align=\"center\">Prescript Lab</td>
	<td align=\"center\">Nom du fichier de trace</td>
	<td align=\"center\">MyUpload</td>
	<td align=\"center\">Trace envoyé à qui</td>
	<td align=\"center\" width=\"150\">Resultat Shapes</td>
	<td align=\"center\">Date</td>
	<td align=\"center\">Status</td>
</tr>";
				
while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){ 			
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$QueryConfirmation   = "SELECT update_time FROM status_history
	WHERE order_num = $listItem[order_num]
	AND order_status='processing'";
	$resultConfirmation = mysqli_query($con,$QueryConfirmation)		or die  ('I cannot select items because 1: ' . mysqli_error($con));
	$DataConfirmation   = mysqli_fetch_array($resultConfirmation,MYSQLI_ASSOC);
		
	$message.="
	<tr>
		<td align=\"center\">$DataConfirmation[update_time]</td>
		<td align=\"center\">$listItem[order_num]</td>
		<td align=\"center\">$listItem[prescript_lab]</td>";
		
		if ($listItem[shape_copied_ftp]=='0000-00-00 00:00:00'){
			$message.="<td align=\"center\"><b>$listItem[shape_name_bk]</b></td>";
		}else{
			$message.="<td align=\"center\">$listItem[shape_name_bk]</td>";
		}
		$message.="
		<td align=\"center\">$listItem[myupload]</td>
		<td align=\"center\">$listItem[shape_sent_to_who]</td>
		<td align=\"center\">$listItem[result_copy_ftp]</td>
		<td align=\"center\">$listItem[shape_copied_ftp]</td>
		<td align=\"center\">$listItem[order_status]</td>
	</tr>";

		
}//END WHILE

$message.="</table>";
$to_address = array('rapports@direct-lens.com');



$curTime	  = date("m-d-Y");	
$from_address ='donotreply@entrepotdelalunette.com';
$subject      = "Rapport des Shapes EDLL: $today";

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


		// Générer le contenu HTML du rapport


		// Créez un nom de fichier unique avec un horodatage
		$date = new DateTime();
		$timestamp = $date->format('Y-m-d_H-i-s');

		$nomFichier = 'r_quotidien_shape_Edll_'. $timestamp;
	
		// Enregistrez le contenu HTML dans un fichier
		$cheminFichierHtml = 'C:/All_Rapports_EDLL/MONTURE/' . $nomFichier . '.html';
		file_put_contents($cheminFichierHtml, $message);

	
		echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';
	
	if($response){ 
		echo '<div class="alert alert-success" role="alert"><strong>Email Sent Sucessfully to:'. $EmailEnvoyerA.'</strong></div>';
	}else{
		echo '<div class="alert alert-danger" role="alert"><strong>Error while sending the email to: '. $EmailEnvoyerA.'</strong></div>';
	}	

	

echo $message;
?>