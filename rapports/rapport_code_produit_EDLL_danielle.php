<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');

//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$time_start = microtime(true);
$today      = date("Y-m-d");


	
//A REMETTRE EN COMMENTAIRE
//$today=date("2014-11-06");

		
$rptQuery="SELECT DISTINCT product_code 
FROM ifc_ca_exclusive 
WHERE
prod_status = 'active'
AND product_code<>''
AND collection IN ('Entrepot KNR')";//= 3594 tuples

//1)'Entrepot Sky et PROMO: FAIT
//2)'Entrepot HKO': FAIT
//3)'Entrepot Swiss':FAIT
//4)'NURBS sunglasses':FAIT
//5)'Entrepot STC': PAS DE CODE
//6)'Entrepot FT':PAS DE CODE
//7)'Entrepot CSC':PAS DE CODE
//8)'Entrepot KNR':


echo '<br>'. $rptQuery.'<br>';

$rptResult=mysqli_query($con,$rptQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum = mysqli_num_rows($rptResult);


	
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

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
				<th align=\"center\">Clé</th>
                <th align=\"center\">Code</th>
                <th align=\"center\">Produit</th>
                <th align=\"center\">Collection</th>
				<th align=\"center\">Additions</th>
				<th align=\"center\">Hauteur Ajustement</th>
                <th align=\"center\">Corridor</th>
				<th align=\"center\">Transitions</th>
				<th align=\"center\">Polarized</th>
				</tr>";
				
		
		echo '<br>Avant le while';	
		$backup_entete_message = "<tr bgcolor=\"CCCCCC\">
				<th align=\"center\"></th>
                <th align=\"center\"></th>
                <th align=\"center\"></th>
                <th align=\"center\"></th>
				<th align=\"center\"></th>
				<th align=\"center\"></th>
                <th align=\"center\"></th>
				<th align=\"center\"></th>
				<th align=\"center\"></th>
				</tr> <tr bgcolor=\"CCCCCC\">
				<th align=\"center\"></th>
                <th align=\"center\"></th>
                <th align=\"center\"></th>
                <th align=\"center\"></th>
				<th align=\"center\"></th>
				<th align=\"center\"></th>
                <th align=\"center\"></th>
				<th align=\"center\"></th>
				<th align=\"center\"></th>
				</tr>";
		
		
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
			//Pour chaque numéro de commande, afficher les produits qui correspondent, avec les informations jugées pertinentes.
				
			/*$queryProd = "SELECT * FROM ifc_ca_Exclusive WHERE product_code='$listItem[product_code]'";
			echo '<br>QueryProd:'. $queryProd.'<br><br>';
			$resultProd=mysqli_query($con,$queryProd)	or die  ('I cannot select items because: ' . mysqli_error($con));*/
			
			$queryPK = "SELECT distinct primary_key FROM ifc_ca_Exclusive WHERE product_code='$listItem[product_code]' AND prod_status='active'";
			//echo '<br>queryPK:'. $queryPK.'<br><br>';
			$resultPK=mysqli_query($con,$queryPK)	or die  ('I cannot select items because: ' . mysqli_error($con));
			while ($DataPK=mysqli_fetch_array($resultPK,MYSQLI_ASSOC)){
				
				$queryProduit = "SELECT product_name, product_code, primary_key, collection, add_min, add_max,
				min_height, max_height, corridor, photo, polar FROM ifc_ca_Exclusive WHERE primary_key = ". 	$DataPK[primary_key];
				//echo '<br>queryProduit:'. 	$queryProduit ;
				$resultProduit =mysqli_query($con,$queryProduit)	or die  ('I cannot select items because: ' . mysqli_error($con));
				$DataProduit = mysqli_fetch_array($resultProduit,MYSQLI_ASSOC);
				
				$message.="
				<tr bgcolor=\"CCCCCC\">
					<td align=\"center\">$DataProduit[primary_key]</td>
					<td align=\"center\">$DataProduit[product_code]</td>					
					<td align=\"center\">$DataProduit[product_name]</td>
					<td align=\"center\">$DataProduit[collection]</td>
					<td align=\"center\">$DataProduit[add_min]-$DataProduit[add_max]</td>
					<td align=\"center\">$DataProduit[min_height]-$DataProduit[max_height]</td>
					<td align=\"center\">$DataProduit[corridor]</td>
					<td align=\"center\">$DataProduit[photo]</td>
					<td align=\"center\">$DataProduit[polar]</td>
				</tr> "; 
			}//End While 
			
				echo $message;
				$message.=$backup_entete_message;
				

		
			
			
		}//END WHILE
		
				
		//*******************************

		// Créez un nom de fichier unique avec un horodatage
		$date = new DateTime();
		$timestamp = $date->format('Y-m-d_H-i-s');
		$nomFichier = 'r_CODE_PRODUIT_DANIELLE'. $timestamp;

		// Enregistrez le contenu HTML dans un fichier
		$cheminFichierHtml = 'C:/All_Rapports_EDLL/Others/Code_Danielle/ ' . $nomFichier . '.html';
		file_put_contents($cheminFichierHtml, $message);

		echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';

		
		//*************************************

		
	
	//	echo '<br>Dans le while';	
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";



		


//SEND EMAIL

$curTime= date("m-d-Y");	
//$to_address=$send_to_address;

//$to_address = array('fdjibrilla@entrepotdelalunette.com');
$to_address=array('rapports@direct-lens.com','dbeaulieu@entrepotdelalunette.com');
ob_start();




$from_address='donotreply@entrepotdelalunette.com';
$subject="Mirror Coating orders to redirect";
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
	
	if($response){ 
		echo 'Reussi';
    }else{
		echo 'Echec';
	}	
	

	echo 'envoie a '.var_dump($to_address); 





?>