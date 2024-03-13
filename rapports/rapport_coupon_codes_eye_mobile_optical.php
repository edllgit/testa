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
<table width=\"20%\" class=\"table\" align=\"center\">";

$CompteurUnusedCoupon = 0;

$message.="<tr><td align=\"center\">&nbsp;</td></tr><tr><td align=\"center\">&nbsp;</td></tr><tr><td colspan=\"3\" align=\"center\"><strong>FSV COUPON CODES</strong></td></tr>";
$rptQueryCoupon  = "SELECT * FROM coupon_codes WHERE date>'2022-07-15' AND code like '%FSV%' ORDER BY date";

$message.="
		<tr>
			<th>Code</th>
			<th>Expiration Date</th>
		</tr>";

$resultCoupon = mysqli_query($con,$rptQueryCoupon)		or die  ('I cannot select items because 1: ' . mysqli_error($con));
$NbrResult        = mysqli_num_rows($resultCoupon);
if ($NbrResult  > 0){
	while ($DataCoupon=mysqli_fetch_array($resultCoupon,MYSQLI_ASSOC)){
		
		
	
		
$rptQueryCouponUsed  = "SELECT *  FROM coupon_use WHERE code ='$DataCoupon[code]'";
//echo '<br>'. $rptQueryCouponUsed ;
$resultCouponUsed = mysqli_query($con,$rptQueryCouponUsed)		or die  ('I cannot select items because 1: ' . mysqli_error($con));
$NbrResult        = mysqli_num_rows($resultCouponUsed);
//echo ' Nombre de resultat: '.$NbrResult;
if ($NbrResult>0){//Résultat trouvé =  Coupon a été utilisé.
	$StatusCoupon='Yes';
}else{
	$StatusCoupon='No';
	
}


$NumeroCommande = substr($DataCoupon[code],3,7);


$queryClientLierauCoupon  = "SELECT user_id FROM orders WHERE order_num= $NumeroCommande";
$resultClientLierauCoupon = mysqli_query($con,$queryClientLierauCoupon)		or die  ('I cannot select items because 1: ' . mysqli_error($con));
$DataClientLierauCoupon   = mysqli_fetch_array($resultClientLierauCoupon,MYSQLI_ASSOC);


//echo 'Numero de commande:'. $NumeroCommande.' Compte:'.$DataClientLierauCoupon[user_id].' <br>';

		if (($StatusCoupon=='No') && ($DataClientLierauCoupon[user_id]=='eyemobileoptical')){
			$CompteurUnusedCoupon  +=1;	
		$message.="
		<tr>
			<td>$DataCoupon[code]</td>
			<td>$DataCoupon[date]</td>
			
		</tr>";
		}
		
	}//End While
	
	$message.="
		<tr>
			<td><b>TOTAL:</b></td>
			<td colspan=\"2\"><b>$CompteurUnusedCoupon Unused coupons</b></td>
		</tr>";
		
}//End IF

	
$message.="</table>";
$to_address = array('rapports@direct-lens.com');	//LIVE



//$to_address = array('rapports@direct-lens.com');//TESTS
$curTime	  = date("m-d-Y");	
$from_address ='donotreply@entrepotdelalunette.com';
$subject      = "FSV Coupon codes Eye'm mobile Optical";                                 

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

		$nomFichier = 'r_coupon_eyes_mobile_optical_'. $timestamp;
	
		// Enregistrez le contenu HTML dans un fichier
		$cheminFichierHtml = 'C:/All_Rapports_EDLL/general/credit coupon/' . $nomFichier . '.html';
		file_put_contents($cheminFichierHtml, $message);

	
		echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';
	
	if($response){ 
		echo '<div class="alert alert-success" role="alert"><strong>Email Sent Sucessfully to:'. $EmailEnvoyerA.'</strong></div>';
	}else{
		echo '<div class="alert alert-danger" role="alert"><strong>Error while sending the email to: '. $EmailEnvoyerA.'</strong></div>';
	}	

echo $message;
?>