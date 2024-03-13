<?php 
header('Content-type: text/html; charset=UTF-8');
/*
ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//error_reporting(E_WARNING);

$date1   	= $_REQUEST[date1];
$date2     	= $_REQUEST[date2];

//DATES HARD CODÉS MANUELLE

$date1        = "2022-04-01";
$date2        = "2022-04-07";


include('../connexion_hbc.inc.php');
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

$LargeurColspanTableau =12;	//Nombre de TD dans le tableau
$WidthTableau = "60%";		//Pixels

//Prepare email 
//Paramètres communs
$from_address='donotreply@entrepotdelalunette.com';

$message="<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
<style type='text/css'>
<!--
.TextSize {
	font-size: 10pt;
	font-family: Arial, Helvetica, sans-serif;
}
-->
</style></head>
<body>
<table width='$WidthTableau' border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">
	<tr>
		<th  colspan=\"$LargeurColspanTableau\"><h3>HBO Sales Between $date1-$date2 [Without Swiss]</h3></th>
	</tr>
	<tr bgcolor=\"CCCCCC\">
		<th align=\"center\">Account</th>
		<th align=\"center\">Order #</th>
		<th align=\"center\">Redo Order #</th>
		<th align=\"center\">Optipro Order #</th>
		<th align=\"center\">Supplier</th>
		<th align=\"center\">Date Processed</th>
		<th align=\"center\">Date Shipped</th>
		<th bgcolor=\"#FFFF8B\" align=\"center\"><b>Total (HBC)</b></th>
	</tr>";
	
	/*
	<th bgcolor=\"#FFFF8B\" align=\"center\">Lenses (HBC)</th>	
		<th bgcolor=\"#FFFF8B\" align=\"center\">Extras (HBC)</th>
	*/

for ($i = 1; $i <= 14 ; $i++) {
		//echo '<br> Magasin: '. $i;
		switch($i){
			case   1:  $Userid =  "88403";  $Partie = '88403-Polo Park';		$send_to_address = array('rapports@direct-lens.com');break;
			case   2:  $Userid =  "88408";  $Partie = '88408-Metrotown';		$send_to_address = array('rapports@direct-lens.com');break;
			case   3:  $Userid =  "88409"; 	$Partie = '88409-Eglinton';			$send_to_address = array('rapports@direct-lens.com');break;
			case   4:  $Userid =  "88411"; 	$Partie = '88411-Sherway';			$send_to_address = array('rapports@direct-lens.com');break;
			case   5:  $Userid =  "88414"; 	$Partie = '88414-Yorkdale';			$send_to_address = array('rapports@direct-lens.com');break;
			case   6:  $Userid =  "88416"; 	$Partie = '88416-Vancouver DTN';	$send_to_address = array('rapports@direct-lens.com');break;
			case   7:  $Userid =  "88431"; 	$Partie = '88431-Calgary DTN';		$send_to_address = array('rapports@direct-lens.com');break;
			case   8:  $Userid =  "88433"; 	$Partie = '88433-Polo Park';		$send_to_address = array('rapports@direct-lens.com');break;
			case   9:  $Userid =  "88434"; 	$Partie = '88434-Market Mall';		$send_to_address = array('rapports@direct-lens.com');break;
			case  10:  $Userid =  "88435"; 	$Partie = '88435-West Edmonton';	$send_to_address = array('rapports@direct-lens.com');break;
			case  11:  $Userid =  "88438"; 	$Partie = '88438-Metrotown';		$send_to_address = array('rapports@direct-lens.com');break;
			case  12:  $Userid =  "88439"; 	$Partie = '88439-Langley';			$send_to_address = array('rapports@direct-lens.com');break;
			case  13:  $Userid =  "88440"; 	$Partie = '88440-Rideau';			$send_to_address = array('rapports@direct-lens.com');break;
			case  14:  $Userid =  "88444"; 	$Partie = '88444-Mayfair';			$send_to_address = array('rapports@direct-lens.com');break;	
		}//End Switch
	
	$rptQuery="SELECT * FROM orders 
	WHERE user_id='$Userid'
	AND order_date_processed BETWEEN '$date1' and '$date2'
	AND prescript_lab<>10
	AND order_status not in ('on hold','cancelled')
	ORDER BY user_id, order_date_processed";
	//echo '<br>'.$rptQuery.'<br>';
	
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items 1because: ' . mysqli_error($con));
	
	$CumulTotalIFC=0;
	$CumulTotalSubLicense=0;
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	
	$rptPrixSublicense="SELECT price_sublicensee, price_can  FROM ifc_ca_exclusive 
	WHERE primary_key='$listItem[order_product_id]'";
	//echo '<br>'.$rptPrixSublicense.'<br>';
	$resultSubLicense=mysqli_query($con,$rptPrixSublicense)		or die  ('I cannot select items 1because: ' . mysqli_error($con));
	$DataSubLicense=mysqli_fetch_array($resultSubLicense,MYSQLI_ASSOC);
	
	//Aller chercher la valeur total de tous les extras ajoutés dans une commande
	$queryExtrasCosts = "SELECT SUM(price) as TotalExtra FROM extra_product_orders WHERE order_num=$listItem[order_num]";
	$resultExtraCost=mysqli_query($con,$queryExtrasCosts)		or die  ('I cannot select items 1because: ' . mysqli_error($con));
	$DataExtraCosts=mysqli_fetch_array($resultExtraCost,MYSQLI_ASSOC);
	
	
	//Aller chercher la valeur total de tous les extras ajoutés dans une commande
	$querySupplier = "SELECT  lab_name  FROM labs WHERE primary_key= $listItem[prescript_lab]";
	$resltSupplier=mysqli_query($con,$querySupplier)		or die  ('I cannot select items 1because: ' . mysqli_error($con));
	$DataSupplier=mysqli_fetch_array($resltSupplier,MYSQLI_ASSOC);
	
	$TotalSublicense = $DataSubLicense[price_sublicensee] + $DataExtraCosts[TotalExtra];
	$TotalSublicense = money_format('%.2n',$TotalSublicense);
	
	$PrixTotalHBC = $listItem[order_total];
	$PrixTotalHBC = money_format('%.2n',$PrixTotalHBC);
	
		$message.="<tr>
				<td align=\"center\">$listItem[user_id]</td>
				<td align=\"center\">$listItem[order_num]</td>
				<td align=\"center\">$listItem[redo_order_num]</td>
				<td align=\"center\">$listItem[order_num_optipro]</td>
				<td align=\"center\">$DataSupplier[lab_name]</td>
				<td align=\"center\">$listItem[order_date_processed]</td>
				<td align=\"center\">$listItem[order_date_shipped]</td>
				<td bgcolor=\"#FFFF8B\" align=\"center\"><b>$PrixTotalHBC$</b></td>
			</tr>";
			

		$CumulTotalIFC +=$listItem[order_total] ;	
		$CumulTotalSubLicense +=$TotalSublicense;	
			
	}//END WHILE
	
	
			
}//Fin du For

	//Envoie du rapport COMPLET DE TOUS LES MAGASINS par courriel à Daniel, Karine, Amina et moi.
	$subject ="HBO Sales [No Swiss] [$date1-$date2]";
	
	$to_address		= $Report_Email;
	$from_address='donotreply@entrepotdelalunette.com';
	
	//$Report_Email	= array('dbeaulieu@direct-lens.com','ehandfield@entrepotdelalunette.com','riazzolino@opticalvisiongroup.com');//LIVE
	$Report_Email	= array('dbeaulieu@direct-lens.com');//LIVE
	$to_address		= $Report_Email;

//Afficher le résultat du rapport
echo $message;
//Copie Admin
$response=office365_mail($to_address, $from_address, $subject, null, $message);

//echo $message_Admin;
$time_start  = microtime(true);	
//echo '<br>Rapport généré/envoyé aux courriels programmés, si vous ne l\'avez pas reçu, svp créez un ticket<br><br>';

?>
