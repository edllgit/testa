<?php
//Afficher toutes les erreurs/avertissements
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");//Fichier de DataBase:EDLL
include('../phpmailer_email_functions.inc.php');

/*
CONTEXTE: 
RAPPORT QUI ROULE CHAQUE JOUR et qui inclus toutes les montures vendu par le fournisseur PERFECT 
qui ont été commandés durant la journée par (Les magasins Entrepot de la lunette)

Perfect doit être avisé de ces ventes [car c'est PERFECT qui enverra  les montures vendues au laboratoire.]
(Les montures de perfect qui sont installées en magasin y restent, elles ne quittent jamais pour le laboratoire)


INFORMATIONS PERTINENTES:
1-Concerne quelles comptes:Tous les comptes des magasins EDLL
2-Courriel ou sera envoyé ce rapport: info@perfectoptical.ca
3-Numéro de compte à utiliser: Numéro de magasin HBC, mai,il faut remplacer le premier 8 par un 9. Ex: 88440 = 98440. Ex 2: 88444 = 98444.
4-Collections EDLL qui seront évaluées à chaque fois que ce rapport sera lancé:   
	a)Esprit-ECA
    b)Elle-ECA
	c)ELLE-CA
	d)Esprit-CA

5-Faut-il exclure les reprises de ce rapport :OUI. Car dans la grande majorité du temps, faire une reprise n'implique pas de commander une nouvelle monture.
6-Quantitée minimale 5 atteindre avant de lancer la commande ? AUCUNE. 
7-Désire-t-on que le rapport soit envoyé même s'il ne contient aucune commande ? (Afin de valider par exemple que ce n'est pas le résultat d'un bug qui aurait empêché sa génération)
8-Fréquence de génération: Chaque matin à 1h AM, concernant les ventes de la veille. (heure locale du Québec car certains magasins dans l'ouest ferment à  21h = minuit pour nous )

9- LES COMPTES DE QUÉBEC SERONT EXCLUS DE CE RAPPORT , DEMANDÉ PAR STÉFANY LE 1er octobre 2019 car QC font leur taillage. :
*/

//Date du rapport
$ladatedhier  = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
$hier     	  = date("Y-m-d", $ladatedhier);

//A RECOMMENTER
//$hier="2021-04-06";

$ComptesAExclure=" AND user_id NOT IN ('BSG','eyeviewsafe','GARAGEMP','garantieatoutcasser','redo_supplier_quebec','redo_supplier_stc','redo_supplier_stc_ca',
'redoifc','redoqc','redosafety','St.Catharines','villeshannon')";

$CollectionsAEvaluer =" AND supplier IN ('ELLE-ECA','ELLE','ESPRIT-ECA','ESPRIT','RIPCURL')";

//1- Les jobs exclusive qui en théorie ont utilisé un frame par commande
$rptQuery="SELECT * FROM orders, extra_product_orders 
WHERE orders.order_num = extra_product_orders.order_num 
AND order_product_type = 'exclusive'
AND order_from IN ('ifcclubca') 
AND order_status not in ('cancelled','basket','on hold') 
AND extra_product_orders.category IN ('Frame') 
$ComptesAExclure
$CollectionsAEvaluer
AND orders.code_source_monture='V'
AND order_date_processed = '$hier'  
AND orders.redo_order_num IS NULL
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
			$message.="<table width=\"610\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">";
			$message.="<tr bgcolor=\"CCCCCC\">
							<th width=\"80\" align=\"center\">Date</th>
							<th width=\"80\" align=\"center\">Account #</th>
							<th width=\"80\" align=\"center\">Order #</th>
							<th width=\"80\" align=\"center\">Collection</th>
							<th width=\"80\" align=\"center\">Model</th>
							<th width=\"80\" align=\"center\">Color</th>
							<th width=\"70\" align=\"center\">A-Bridge</th>
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
				case 'montreal':  	case 'montrealsafe':      		$AccountNumberWithPerfect = '91600';		break;
				case 'warehousehal':case 'warehousehalsafe':    	$AccountNumberWithPerfect = '91500';		break;
				case 'edmundston':	case 'edmundstonsafe':    		$AccountNumberWithPerfect = '1900';			break;
				case 'sorel':		case 'sorelsafe':    			$AccountNumberWithPerfect = '2000';			break;
				case 'vaudreuil':	case 'vaudreuilsafe':    		$AccountNumberWithPerfect = '2100';			break;
				case 'moncton':		case 'monctonsafe':    			$AccountNumberWithPerfect = '2200';			break;
				case 'fredericton':	case 'frederictonsafe':    		$AccountNumberWithPerfect = '92300';		break;
				
			}
		

			
			switch($DataFrame[supplier]){
				case 'ELLE-ECA': 	 	$Collection = 'ELLE';		break;	
				case 'ESPRIT-ECA': 		$Collection = 'ESPRIT';		break;			
				case 'ELLE-CA': 	 	$Collection = 'ELLE';		break;	
				case 'ESPRIT-CA': 		$Collection = 'ESPRIT';		break;	
				case 'RIPCURL': 		$Collection = 'RIPCURL';	break;
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
							<td align=\"center\">$DataFrame[ep_frame_a]-$DataFrame[ep_frame_dbl]</td>";
              $message.="</tr>";
		}//END WHILE

		if ($ordersnum>0){
			$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";
		}

		if ($ordersnum==0){
			$message.="No Frame in these collections ('ELLE-ECA','ELLE','ESPRIT-ECA','ESPRIT','RIPCURL') have been ordered yesterday.";
		}
echo '<br><br>' . $message;

//SEND EMAIL
//$send_to_address = array('rapports@direct-lens.com');//TEST
$send_to_address = array('rapports@direct-lens.com');//LIVE
$send_to_address = array('rapports@direct-lens.com');//LIVE
$send_to_address = array('info@perfectoptical.ca','monture@entrepotdelalunette.com','renouvellement@entrepotdelalunette.com');

echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Perfect Optical EDLL Frames order(s) of the day: $hier";
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
	

?>