<?php
/*
//RAPPORT :HBC 88444-Mayfair (BC)
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
include("../../sec_connect.inc.php");
include('../../phpmailer_email_functions.inc.php');
require_once('../../class.ses.php');
$time_start = microtime(true);
$today      = date("Y-m-d");

//$today=date("2017-02-27");

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
</head>
<body>
<table class=\"table\" width=\"30%\">";


//#88444
$rptQuery1   = "SELECT * FROM orders 
WHERE user_id IN ('88444')
AND (order_num = -1 OR order_date_processed = '$today')
AND order_num_optipro <> '' AND redo_order_num is  null
AND order_status <> 'cancelled'
ORDER BY  order_num_optipro";
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery1 . '<br>';	

$rptResult1 = mysqli_query($con,$rptQuery1)		or die  ('I cannot select items because 1: ' . mysqli_error($con));
$ordersnum1 = mysqli_num_rows($rptResult1);
	
if ($ordersnum1!=0){

	$message.="<tr><th>HBC 88444-Mayfair (BC)</th></tr>
	<tr bgcolor=\"CCCCCC\">
		<td align=\"center\">Order Number</td>
		<td align=\"center\"># Optipro</td>
		<td align=\"center\">Compte</td>
		<td align=\"center\">Patient</td>
		<td align=\"center\">Produit</td>
		<td align=\"center\">Produit Optipro</td>
	</tr>";
					
	while ($listItem1=mysqli_fetch_array($rptResult1,MYSQLI_ASSOC)){			
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
			
	$message.="
	<tr>
		<td align=\"center\">$listItem1[order_num]</td>
		<td align=\"center\">$listItem1[order_num_optipro]</td>
		<td align=\"center\">$listItem1[user_id]</td>
		<td align=\"center\">$listItem1[order_patient_first] $listItem1[order_patient_last]</td>
		<td align=\"center\">$listItem1[order_product_name]</td>
		<td align=\"center\">$listItem1[nom_produit_optipro]</td>
	</tr>";	
	}//END WHILE
}//End IF
//FIN #88444
$message.="
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>";	

$message .= "<br><br></table>";
$to_address = array('rapports@direct-lens.com');
//$to_address = array('rapports@direct-lens.com');
$curTime      = date("m-d-Y");	
$from_address = 'donotreply@entrepotdelalunette.com';
$subject      = "Optipro Report for HBC Store #88444-Mayfair: ".$today;

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

echo $message;

?>