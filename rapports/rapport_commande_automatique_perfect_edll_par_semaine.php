<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");//Fichier de DataBase:EDLL
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

/*
CONTEXTE: 
RAPPORT QUI ROULE une fois par semaine   et qui inclus toutes les montures vendu par le fournisseur PERFECT 
qui ont été commandés durant cette semaine (Les magasins EDLL)

CE RAPPORT N'EST PAS ENVOYÉ A PERECT, IL SERT UNIQUEMENT POUR DANIEL ET JEAN, AFIN DE LEUR MONTRER LES VENTES EFFECTUÉES PAR LES MAGASINS EDLL.

INFORMATIONS PERTINENTES:
1-Concerne quelles comptes:Tous les comptes des magasins EDLL
2-Courriel ou sera envoyé ce rapport: Daniel et Jean, copie Charles
3-Numéro de compte à utiliser: Numéro de magasin EDLL, mai,il faut remplacer le premier 8 par un 9. Ex: 88440 = 98440. Ex 2: 88444 = 98444.
4-Collections EDLL qui seront évaluées à chaque fois que ce rapport sera lancé:   
	a)Esprit-CA
    c)Elle-CA
    c)Charmant-CA
    d)Strellson-CA
5-Faut-il exclure les reprises de ce rapport :OUI. Car dans la grande majorité du temps, faire une reprise n'implique pas de commander une nouvelle monture.
6-Quantitée minimale 5 atteindre avant de lancer la commande ? AUCUNE. 
7-Désire-t-on que le rapport soit envoyé même s'il ne contient aucune commande ? (Afin de valider par exemple que ce n'est pas le résultat d'un bug qui aurait empêché sa génération)
8-Fréquence de génération: Une fois par semaine, le samedi soir à 23h55, concernant les ventes de la semaine. (heure locale du Québec car certains magasins dans l'ouest ferment à  21h = minuit pour nous )
*/

//Date du rapport
$ladatedhier  = mktime(0,0,0,date("m"),date("d")-6,date("Y"));
$Ilya6jours     	  = date("Y-m-d", $ladatedhier);


$ladate  	= mktime(0,0,0,date("m"),date("d"),date("Y"));
$aujourdhui = date("Y-m-d", $ladate);
//$hier ="2019-06-05";


//DATES HARD CODÉS (LORSQUE NÉCESSAIRE)
/*
$Ilya6jours = '2020-12-27';
$aujourdhui = '2021-01-02';
*/


$ComptesAExclure=" AND user_id NOT IN ('BSG','eyeviewsafe','GARAGEMP','garantieatoutcasser','redo_supplier_quebec','redo_supplier_stc','redo_supplier_stc_ca',
'redoifc','redoqc','redosafety','St.Catharines','villeshannon')";

$CollectionsAEvaluer =" AND supplier IN ('ELLE-ECA','ELLE','ESPRIT-ECA','ESPRIT','RIPCURL')";

//1- Les jobs exclusive qui en théorie ont utilisé un frame par commande
$rptQuery="SELECT * FROM orders, extra_product_orders 
WHERE orders.order_num = extra_product_orders.order_num 
AND order_product_type = 'exclusive'
AND order_from IN ('ifcclubca') AND order_status not in ('cancelled','basket','on hold') 
AND extra_product_orders.category IN ('Frame') 
$ComptesAExclure
$CollectionsAEvaluer
AND order_date_processed BETWEEN '$Ilya6jours' AND '$aujourdhui'  
AND redo_order_num IS NULL
GROUP BY orders.order_num ORDER BY order_date_processed";


echo 'requete:'. $rptQuery;

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
			$message.="<table width=\"610\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">";
			$message.="<tr bgcolor=\"CCCCCC\">
							<th width=\"80\" align=\"center\">Date</th>
							<th width=\"80\" align=\"center\">Account #</th>
							<th width=\"80\" align=\"center\">Order #</th>
							<th width=\"80\" align=\"center\">Collection</th>
							<th width=\"80\" align=\"center\">Model</th>
							<th width=\"80\" align=\"center\">Color</th>
							<th width=\"70\" align=\"center\">A-Bridge</th>
							<th width=\"70\" align=\"center\">Code Source</th>
						</tr>";
		}		

		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
		//Removable side shield	
		$queryFrame  = "SELECT order_num, frame_type, supplier, model, temple_model_num, ep_frame_a,ep_frame_dbl,ep_frame_b, color FROM extra_product_orders WHERE order_num = $listItem[order_num] AND category in ('Frame','Edging','Edging_Frame')";
		$resultFrame = mysqli_query($con,$queryFrame)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataFrame   = mysqli_fetch_array($resultFrame,MYSQLI_ASSOC);	
			
		
			switch($listItem[user_id]){
				//Les EDLL
				case 'gatineau': 	case 'gatineausafe':       		$AccountNumberWithPerfect = '91800';		break;
				case 'entrepotdr': 	case 'safedr':      			$AccountNumberWithPerfect = '98500';		break;
				case 'stjerome': 	case 'stjeromesafe':      		$AccountNumberWithPerfect = '91801';		break;
				case 'levis': 		case 'levissafe':      			$AccountNumberWithPerfect = '98800';		break;
				case 'terrebonne': 	case 'terrebonnesafe':      	$AccountNumberWithPerfect = '91200';		break;
				case 'granby': 		case 'granbysafe':      		$AccountNumberWithPerfect = '98600';		break;
				case 'laval':  		case 'lavalsafe':      			$AccountNumberWithPerfect = '98700';		break;
				case 'chicoutimi': 	case 'chicoutimisafe':      	$AccountNumberWithPerfect = '98400';		break;
				case 'entrepotquebec': case 'quebecsafe':      		$AccountNumberWithPerfect = '91400';		break;
				case 'longueuil': 	case 'longueuilsafe':      		$AccountNumberWithPerfect = '91100';		break;
				case 'sherbrooke': 	case 'sherbrookesafe':      	$AccountNumberWithPerfect = '98900';		break;
				case 'entrepotifc': case 'entrepotsafe':      		$AccountNumberWithPerfect = '98200';		break;
				//case 'montreal':  	case 'montrealsafe':      		$AccountNumberWithPerfect = '91600';		break;
				case 'warehousehal':case 'warehousehalsafe':    	$AccountNumberWithPerfect = '91500';		break;
				case 'edmundston':	case 'edmundstonsafe':    		$AccountNumberWithPerfect = '1900';			break;
				case 'sorel':		case 'sorelsafe':    			$AccountNumberWithPerfect = '2000';			break;
				case 'vaudreuil':	case 'vaudreuilsafe':    		$AccountNumberWithPerfect = '2100';			break;
				case 'moncton':		case 'monctonsafe':    			$AccountNumberWithPerfect = '2200';			break;
				case 'fredericton':	case 'frederictonsafe':    		$AccountNumberWithPerfect = '92300';		break;
				case '88666':	    case '88666':    		        $AccountNumberWithPerfect = '98200';		break;
			}
		
			switch($DataFrame[supplier]){
				case 'ELLE-ECA': 	 	$Collection = 'ELLE';		break;	
				case 'ESPRIT-ECA': 		$Collection = 'ESPRIT';		break;			
				case 'ELLE-CA': 	 	$Collection = 'ELLE';		break;	
				case 'ESPRIT-CA': 		$Collection = 'ESPRIT';		break;	
				case 'RIPCURL': 		$Collection = 'RIPCURL';	break;
				default: 				$Collection = $DataFrame[supplier];
			}


		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";


			$message.="	<tr bgcolor=\"$bgcolor\">
							<td align=\"center\">$listItem[order_date_processed]</td>
							<td align=\"center\"><strong>EDLL</strong> #$AccountNumberWithPerfect</td>
					   		<td align=\"center\">$listItem[order_num]</td>
			 				<td align=\"center\">$Collection</td>
							<td align=\"center\">$DataFrame[temple_model_num]</td>
							<td align=\"center\">$DataFrame[color]</td>
							<td align=\"center\">$DataFrame[ep_frame_a]-$DataFrame[ep_frame_dbl]</td>
							<td align=\"center\">$listItem[code_source_monture]</td>";
              $message.="</tr>";
		}//END WHILE

		if ($ordersnum>0){
			$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";
		}

		if ($ordersnum==0){
			$message.="No Frame in these 2 collections (ELLE,ESPRIT) have been ordered yesterday.";
		}
echo '<br><br>' . $message;

//SEND EMAIL


$send_to_address = array('rapports@direct-lens.com','monture@entrepotdelalunette.com','approvisionnement@entrepotdelalunette.com');


echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Perfect Optical EDLL Frames order(s) Between $Ilya6jours and $aujourdhui";
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

		// Générer le contenu HTML du rapport


		// Créez un nom de fichier unique avec un horodatage
		$date = new DateTime();
		$timestamp = $date->format('Y-m-d_H-i-s');

		$nomFichier = 'r_commande_perfect_semaine_'. $timestamp;
	
		// Enregistrez le contenu HTML dans un fichier
		$cheminFichierHtml = 'C:/All_Rapports_EDLL/general/Commande/Automatique/' . $nomFichier . '.html';
		file_put_contents($cheminFichierHtml, $message);

	
		echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';
	
?>