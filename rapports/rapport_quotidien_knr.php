<?php
//Afficher toutes les erreurs/avertissements
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");//Fichier de DataBase:EDLL
include('../phpmailer_email_functions.inc.php');

//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

/*
CONTEXTE: 
FRÉQUENCE D'EXÉCUTION DE CE RAPPORT: 1 fois par jour
Inclus les commandes redirigées vers le fournisseur K and R
*/

//Date du rapport


$ajd  	= mktime(0,0,0,date("m"),date("d")-1,date("Y"));
$hier   = date("Y/m/d", $ajd);

//$hier="2019-11-11";

//1- Les jobs exclusive qui en théorie ont utilisé un frame par commande
$rptQuery="SELECT * FROM orders 
WHERE order_product_type = 'exclusive'
AND order_from IN ('ifcclubca') AND order_status not in ('cancelled','basket','on hold') 
AND prescript_lab = 73
AND order_date_processed BETWEEN '$hier' AND '$hier' 
GROUP BY orders.order_num ORDER BY user_id";


echo '<br>requete:'. $rptQuery;




$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);
	

		$count=0;
		$message="";
		
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body>";

		if ($ordersnum>0){
			$message.="<table width=\"600\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">";
			$message.="<tr bgcolor=\"CCCCCC\">
							<th width=\"80\" align=\"center\">Date</th>
							<th width=\"80\" align=\"center\">Store</th>
							<th width=\"80\" align=\"center\">Order #</th>
							<th width=\"360\" align=\"center\">Product</th>
						</tr>";
		}		

		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
	
			

	


		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

			$ModeleMonture = str_replace('ONE','KK',$DataFrame[temple_model_num]);
			
			$message.="	<tr bgcolor=\"$bgcolor\">
							<td align=\"center\">$listItem[order_date_processed]</td>
							<td align=\"center\">$listItem[user_id]</td>
					   		<td align=\"center\">$listItem[order_num]</td>
							<td align=\"center\">$listItem[order_product_name]</td>";
              $message.="</tr>";
		}//END WHILE

		if ($ordersnum>0){
			$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";
		}

		if ($ordersnum==0){
			$message.="No order for K and R.";
		}
echo '<br><br>' . $message;

//SEND EMAIL
//$send_to_address = array('rapports@direct-lens.com');//TEST


$send_to_address = array('rapports@direct-lens.com');



echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Rapport Quotidien K and R: $hier";
$response=office365_mail($to_address, $from_address, $subject, null, $message);
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
	
		// Créez un nom de fichier unique avec un horodatage
		$date = new DateTime();
		$timestamp = $date->format('Y-m-d_H-i-s');

		$nomFichier = 'r_quotidien_knr_'. $timestamp;
	
		// Enregistrez le contenu HTML dans un fichier
		$cheminFichierHtml = 'C:/All_Rapports_EDLL/Fournisseur/' . $nomFichier . '.html';
		file_put_contents($cheminFichierHtml, $message);

	
		echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';

?>