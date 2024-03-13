<?php
/*
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
include("../connexion_hbc.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true);
$today      = date("Y-m-d");

//$today=date("2018-12-14");

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

$rptQuery   = "SELECT * FROM orders
WHERE prescript_lab IN (73, 10,2)
AND order_date_processed='$today'
AND order_status NOT IN ('cancelled', 'on hold','basket')
ORDER BY  shape_name_bk desc, order_date_processed";

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

$message.="<body><table class=\"table\">";
$message.="<tr bgcolor=\"CCCCCC\">
	<td align=\"center\" width=\"150\">Date de commande</td>
	<td align=\"center\">HBC Order #</td>
	<td align=\"center\">Fabriquant</td>
	<td align=\"center\">Nom du fichier de trace</td>
	<td align=\"center\">MyUpload</td>

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
	
		
switch($listItem[prescript_lab]){
case '2':  	$Fabriquant = "HKO";				break;	
case '3':  	$Fabriquant = "Saint-Catharines";	break;	
case '4':  	$Fabriquant = "GKB";				break;	
case '10': 	$Fabriquant = "Swisscoat";			break;	
case '73': 	$Fabriquant = "KNR";				break;	
}			

			
	$message.="
	<tr>
		<td align=\"center\">$listItem[order_date_processed]</td>
		<td align=\"center\">$listItem[order_num]</td>
		<td align=\"center\">$Fabriquant</td>
		<td align=\"center\">$listItem[shape_name_bk]</td>
		<td align=\"center\">$listItem[myupload]</td>
		<td align=\"center\">$listItem[result_copy_ftp_swiss]</td>
		<td align=\"center\">$listItem[shape_copied_swiss_ftp]</td>
		<td align=\"center\">$listItem[order_status]</td>
	</tr>";

		
}//END WHILE

$message.="</table>";
$to_address = array('rapports@direct-lens.com');
$curTime	  = date("m-d-Y");	
$from_address ='donotreply@entrepotdelalunette.com';
$subject      = "Rapport des Shapes HBC: $today";

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
		//log_email("REPORT: Entrepot de le lunette 88433: commandes en cours",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
		echo '<div class="alert alert-success" role="alert"><strong>Email Sent Sucessfully to:'. $EmailEnvoyerA.'</strong></div>';
	}else{
		//log_email("REPORT: Entrepot de le lunette 88433: commandes en cours",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
		echo '<div class="alert alert-danger" role="alert"><strong>Error while sending the email to: '. $EmailEnvoyerA.'</strong></div>';
	}	

	

echo $message;

*/
?>
