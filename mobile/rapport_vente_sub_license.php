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
/*
$date1        = "2022-03-01";
$date2        = "2022-03-31";
*/

include('../connexion_hbc.inc.php');;
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

$LargeurColspanTableau =17;	//Nombre de TD dans le tableau
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
		<th  colspan=\"$LargeurColspanTableau\"><h3>HBO Sub-License Sales between $date1-$date2</h3></th>
	</tr>
	<tr bgcolor=\"CCCCCC\">
		<th align=\"center\">Account</th>
		<th align=\"center\">Order #</th>
		<th align=\"center\">Redo Order #</th>
		<th align=\"center\">Redo Reason</th>
		<th align=\"center\">Optipro Order #</th>
		<th align=\"center\">Product Code</th>
		<th align=\"center\">Rebate Code</th>
		
		<th align=\"center\">Optipro Product</th>
		<th align=\"center\">Package ?</th>
		
		<th align=\"center\">Date Processed</th>
		<th align=\"center\">Date Shipped</th>
		
		<th bgcolor=\"#FFFF8B\" align=\"center\">Lenses (HBC)</th>	
		<th bgcolor=\"#FFFF8B\" align=\"center\">Extras (HBC)</th>
		<th bgcolor=\"#FFFF8B\" align=\"center\"><b>Total (HBC)</b></th>
		
		<th bgcolor=\"#62B16E\" align=\"center\">Lenses (Sub-license)</th>	
		<th bgcolor=\"#62B16E\" align=\"center\">Extras (Sub-license)</th>	
		<th bgcolor=\"#62B16E\" align=\"center\"><b>Total(Sub-license)</b></th>		
	</tr>";

for ($i = 1; $i <= 3 ; $i++) {
		//echo '<br> Magasin: '. $i;
		switch($i){
			case  1:  $Userid =  "88433";   $Partie = '88433-Polo Park';		$send_to_address = array('rapports@direct-lens.com');break;
			case  2:  $Userid =  "88438";   $Partie = '88438-Metrotown';		$send_to_address = array('rapports@direct-lens.com');break;
			case  3:  $Userid =  "88439"; 	$Partie = '88439-Langley';			$send_to_address = array('rapports@direct-lens.com');break;
		}//End Switch
	
	$rptQuery="SELECT * FROM orders 
	WHERE user_id='$Userid'
	AND order_date_shipped BETWEEN '$date1' and '$date2'
	ORDER BY user_id, order_date_shipped";
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
	
	
	//Aller chercher product code
	$queryProductode = "SELECT product_code FROM ifc_ca_exclusive WHERE primary_key=$listItem[order_product_id]";
	//echo '<br>'.$queryProductode;
	$resultProductCode=mysqli_query($con,$queryProductode)		or die  ('I cannot select items 1because: ' . mysqli_error($con));
	$DataProductCode=mysqli_fetch_array($resultProductCode,MYSQLI_ASSOC);
	
	
	
	//Aller chercher raison de reprise
	if ($listItem[redo_reason_id]<>0){
		$queryRedoReason = "SELECT * FROM redo_reasons WHERE redo_reason_id=$listItem[redo_reason_id]";
		//echo '<br>'.$queryRedoReason;
		$ResultDataReason = mysqli_query($con,$queryRedoReason)		or die  ('I cannot select items 1because: ' . mysqli_error($con));
		$DataRedoReason   = mysqli_fetch_array($ResultDataReason,MYSQLI_ASSOC);
	}else{
	$DataRedoReason = "";	
	}
	
	
	$TotalSublicense = $DataSubLicense[price_sublicensee] + $DataExtraCosts[TotalExtra];
	$TotalSublicense = money_format('%.2n',$TotalSublicense);
	
	$PrixTotalHBC = $DataSubLicense[price_can] + $DataExtraCosts[TotalExtra];
	$PrixTotalHBC = money_format('%.2n',$PrixTotalHBC);
	
	if ($listItem[code_remise_optipro]=='A-2NDPAIR-FREE'){
			$CodeRemiseOptipro='A-2NDPAIR-FREE';
	}else{
			$CodeRemiseOptipro='';
	}//END IF
	
	
	if ($listItem[code_remise_optipro]=='A-2NDPAIR-FREE'){
			$CodeRemiseOptipro='A-2NDPAIR-FREE';
	}elseif($listItem[code_remise_optipro]=='A-2NDPAIR-LENSES-FREE'){
			$CodeRemiseOptipro='A-2NDPAIR-LENSES-FREE';
	}elseif($listItem[code_remise_optipro]=='A-2NDPAIR-FR-$100OFF'){
			$CodeRemiseOptipro='A-2NDPAIR-FR-$100OFF';
	}else{
			$CodeRemiseOptipro='';	
	}//END IF
	
	
	
	//Recherche si le produit est un package a partir du champ nom_produit_optipro
	/*$Recherche_Package = strpos($listItem[nom_produit_optipro],'PACKAGE');
	if ($Recherche_Package===false){
		$EstunPackage='';
	}else{
		$EstunPackage='YES';
	}*/
	
	$EstunPackage='';//Initialisation
	if ($listItem[code_remise_optipro]=='B-PACK-PROG'){
		$EstunPackage='YES';	
	}
	if ($listItem[code_remise_optipro]=='B-PACK-PROG-PHOTO'){
		$EstunPackage='YES';	
	}
	if ($listItem[code_remise_optipro]=='B-PACK-PROG-POL'){
		$EstunPackage='YES';	
	}	
	if ($listItem[code_remise_optipro]=='B-PACK-PROG-TINT'){
		$EstunPackage='YES';	
	}
	if ($listItem[code_remise_optipro]=='B-PACK-SV-RX'){
		$EstunPackage='YES';	
	}
	if ($listItem[code_remise_optipro]=='B-PACK-SV-RX-SUN'){
		$EstunPackage='YES';	
	}
	if ($listItem[code_remise_optipro]=='B-PACK-SV-STOCK'){
		$EstunPackage='YES';	
	}
	if ($listItem[code_remise_optipro]=='B-PACK-SV-STOCK-SUN'){
		$EstunPackage='YES';	
	}
	if ($listItem[code_remise_optipro]=='B-PACKSV-RX'){
		$EstunPackage='YES';	
	}
	if ($listItem[code_remise_optipro]=='B-PACKPROG'){
		$EstunPackage='YES';	
	}
	
		
	
	//Demande Roberto, dans le cas ou un des 3 codes rabais '2NDPAIR-FREE' est utilisé, afficher le TOTAL_OPTIPRO au lieu du prix des verres Sub License
	
$message.="<tr>
				<td align=\"center\">$listItem[user_id]</td>
				<td align=\"center\">$listItem[order_num]</td>
				<td align=\"center\">$listItem[redo_order_num]</td>
				<td align=\"center\">$DataRedoReason[redo_reason_en]</td>
				<td align=\"center\">$listItem[order_num_optipro]</td>
				<td align=\"center\">$DataProductCode[product_code]</td>
				<td align=\"center\">$CodeRemiseOptipro</td>
				<td align=\"center\">$listItem[nom_produit_optipro]</td>
				<td align=\"center\">$EstunPackage</td>

				<td align=\"center\">$listItem[order_date_processed]</td>
				<td align=\"center\">$listItem[order_date_shipped]</td>				
				<td bgcolor=\"#FFFF8B\" align=\"center\">$DataSubLicense[price_can]$</td>
				<td bgcolor=\"#FFFF8B\" align=\"center\">$DataExtraCosts[TotalExtra]$</td>
				<td bgcolor=\"#FFFF8B\" align=\"center\"><b>$PrixTotalHBC$</b></td>";
				
	if (($CodeRemiseOptipro=="")&&($EstunPackage<>'YES')){
		$message.=" <td  bgcolor=\"#62B16E\" align=\"center\">$DataSubLicense[price_sublicensee]$</td>
					<td  bgcolor=\"#62B16E\" align=\"center\">$DataExtraCosts[TotalExtra]$</td>
					<td  bgcolor=\"#62B16E\" align=\"center\"><b>$TotalSublicense$</b></td>
			</tr>";
			$CumulTotalIFC +=$DataSubLicense[price_can] +$DataExtraCosts[TotalExtra] ;	
			$CumulTotalSubLicense +=$TotalSublicense;	
	}elseif($CodeRemiseOptipro<>""){//C'EST UN BOGO, ON AFFICHE 40% DU TOTAL OPTIPRO A LA PLACE DU PRIX DES VERRES
		$QuarantePourcent = 0.4*$listItem[TOTAL_OPTIPRO]; 
		$QuarantePourcent=money_format('%.2n',$QuarantePourcent);
			$message.="<td  bgcolor=\"#62B16E\" align=\"center\">$QuarantePourcent$</td>
				<td  bgcolor=\"#62B16E\" align=\"center\">0.00$</td>
				<td  bgcolor=\"#62B16E\" align=\"center\"><b>$QuarantePourcent$</b></td>
			</tr>";	
			$CumulTotalIFC += $DataSubLicense[price_can] + $DataExtraCosts[TotalExtra];	
			$CumulTotalSubLicense +=$QuarantePourcent;					
	}elseif($EstunPackage=='YES'){//C'EST UN PACKAGE, on doit afficher 40% du total optipro - monture optipro
		$QuarantePourcent = 0.4* ($listItem[TOTAL_OPTIPRO]- $listItem[TOTAL_MONTURE_OPTIPRO]); 
		$QuarantePourcent=money_format('%.2n',$QuarantePourcent);
			$message.="<td  bgcolor=\"#62B16E\" align=\"center\">$QuarantePourcent$</td>
				<td  bgcolor=\"#62B16E\" align=\"center\">0.00$</td>
				<td  bgcolor=\"#62B16E\" align=\"center\"><b>$QuarantePourcent$</b></td>
			</tr>";	
			$CumulTotalIFC += $DataSubLicense[price_can] + $DataExtraCosts[TotalExtra];	
			$CumulTotalSubLicense +=$QuarantePourcent;					
	}//END IF//END IF
			
			
		
			
	}//END WHILE
	
	
	$CumulTotalIFC=money_format('%.2n',$CumulTotalIFC);
	$CumulTotalSubLicense=money_format('%.2n',$CumulTotalSubLicense);
	
	
	
	//TOTAUX
	$message.="<tr>
				<th align=\"center\"></th>
				<th align=\"center\"></th>
				<th align=\"center\"></th>
				<th align=\"center\"></th>
				<th align=\"center\"></th>
				<th align=\"center\"></th>
				<th align=\"center\"></th>
				<th align=\"center\"></th>
				<th align=\"center\"></th>
				<th align=\"center\"></th>
				<th align=\"center\">TOTAL:</th>
				<th align=\"center\"><b>$CumulTotalIFC$</b></th>
				<th align=\"center\"></th>
				<th align=\"center\">TOTAL:</th>
				<th align=\"center\"><b>$CumulTotalSubLicense$</b></th>
				
			</tr>";
		
}//Fin du For

	//Envoie du rapport 
	$subject ="HBO Sub-License Sales between [$date1-$date2]";
	
	$to_address		= $Report_Email;
	$from_address='donotreply@entrepotdelalunette.com';
	
	$Report_Email	= array('dbeaulieu@direct-lens.com','ehandfield@entrepotdelalunette.com','riazzolino@opticalvisiongroup.com');//LIVE
	//$Report_Email	= array('dbeaulieu@direct-lens.com');//LIVE
	$to_address		= $Report_Email;

//Afficher le résultat du rapport
echo $message;
//Copie Admin
$response=office365_mail($to_address, $from_address, $subject, null, $message);

//echo $message_Admin;
$time_start  = microtime(true);	
echo '<br>Rapport a été généré/envoyé aux courriels programmés, si vous ne l\'avez pas reçu, svp créez un ticket<br><br>';

?>
