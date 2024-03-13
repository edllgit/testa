<?php
/*
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../sec_connect.inc.php");//Fichier de DataBase:HBC/Griffé
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

/*
CONTEXTE: 
RAPPORT QUI ROULE une fois par semaine   et qui inclus toutes les montures vendu par le fournisseur PERFECT 
qui ont été commandés durant cette semaine (Les magasins HBC ou Griffé Trois-Rivières)

CE RAPPORT N'EST PAS ENVOYÉ A PERECT, IL SERT UNIQUEMENT POUR DANIEL ET JEAN, AFIN DE LEUR MONTRER LES VENTES EFFECTUÉES PAR LES MAGASINS HBC.



INFORMATIONS PERTINENTES:
1-Concerne quelles comptes:Tous les comptes des magasins HBC + Griffé Trois-Rivières.
2-Courriel ou sera envoyé ce rapport: Daniel et Jean, copie Charles
3-Numéro de compte à utiliser: Numéro de magasin HBC, mai,il faut remplacer le premier 8 par un 9. Ex: 88440 = 98440. Ex 2: 88444 = 98444.
4-Collections HBC/Griffé qui seront évaluées à chaque fois que ce rapport sera lancé:   
	a)Esprit-CA
    c)Elle-CA
    c)Charmant-CA
    d)Strellson-CA
5-Faut-il exclure les reprises de ce rapport :OUI. Car dans la grande majorité du temps, faire une reprise n'implique pas de commander une nouvelle monture.
6-Quantitée minimale 5 atteindre avant de lancer la commande ? AUCUNE. 
7-Désire-t-on que le rapport soit envoyé même s'il ne contient aucune commande ? (Afin de valider par exemple que ce n'est pas le résultat d'un bug qui aurait empêché sa génération)
8-Fréquence de génération: Une fois par semaine, le samedi soir à 23h55, concernant les ventes de la semaine. (heure locale du Québec car certains magasins dans l'ouest ferment à  21h = minuit pour nous )
*

//Date du rapport
$ladatedhier  = mktime(0,0,0,date("m"),date("d")-6,date("Y"));
$Ilya6jours     	  = date("Y-m-d", $ladatedhier);


$datedujour  		= mktime(0,0,0,date("m"),date("d"),date("Y"));
$aujourdhui     	= date("Y/m/d", $datedujour);


//A RECOMMENTER
/*
$Ilya6jours= "2022-04-01";
$aujourdhui= "2022-04-30";
*

echo '<br>Date du jour:'. $aujourdhui;

//Ajout pour transformer ce rapport bi-mensuel en rapport mensuel
$MoisEnCours 	= date("m", $datedujour);
 
$MoisEnCours=$MoisEnCours-1;//Pour sélectionner le mois qui vient de se terminer
$AnneeEnCours  = date("Y", $datedujour); 
if ($MoisEnCours==0){
	$MoisEnCours=12;	
	//$AnneeEnCours = $AnneeEnCours-1;
}
switch($MoisEnCours){
		case 1:	$JourDebut="01-01";	$JourFin="01-31";	$AnneeEnCours = $AnneeEnCours  ;	break; //Janvier 
		case 2: $JourDebut="02-01";	$JourFin="02-29";	$AnneeEnCours = $AnneeEnCours  ;	break; //Février
		case 3: $JourDebut="03-01";	$JourFin="03-31";	$AnneeEnCours = $AnneeEnCours  ;	break; //Mars
		case 4: $JourDebut="04-01";	$JourFin="04-30";	$AnneeEnCours = $AnneeEnCours  ;	break; //Avril
		case 5: $JourDebut="05-01";	$JourFin="05-31";	$AnneeEnCours = $AnneeEnCours  ;	break; //Mai
		case 6: $JourDebut="06-01";	$JourFin="06-30";	$AnneeEnCours = $AnneeEnCours  ;	break; //Juin
		case 7: $JourDebut="07-01";	$JourFin="07-31";	$AnneeEnCours = $AnneeEnCours  ;	break; //Juillet
		case 8: $JourDebut="08-01";	$JourFin="08-31";	$AnneeEnCours = $AnneeEnCours  ;	break; //Août
		case 9: $JourDebut="09-01";	$JourFin="09-30";	$AnneeEnCours = $AnneeEnCours  ;	break; //Septembre
		case 10:$JourDebut="10-01";	$JourFin="10-31";	$AnneeEnCours = $AnneeEnCours  ;	break; //Octobre
		case 11:$JourDebut="11-01";	$JourFin="11-30";	$AnneeEnCours = $AnneeEnCours  ;	break; //Novembre
		case 12:$JourDebut="12-01";	$JourFin="12-31";	$AnneeEnCours = $AnneeEnCours-1;	break; //Décembre	
}


echo '<br>Mois en cours:'. $MoisEnCours;
echo '<br>JourDebut:'. $JourDebut;
echo '<br>JourFin:'. $JourFin.'<br><br>';
$CollectionsAEvaluer =" AND supplier IN ('ELLE-CA','ESPRIT-CA','CHARMANT-CA','STRELLSON-CA')";

//1- Les jobs exclusive qui en théorie ont utilisé un frame par commande
$rptQuery="SELECT * FROM orders, extra_product_orders 
WHERE orders.order_num = extra_product_orders.order_num 
AND order_product_type = 'exclusive'
AND user_id IN ('88433','88438','88439')
AND order_from IN ('hbc') AND order_status not in ('cancelled','basket','on hold') 
AND extra_product_orders.category IN ('Frame') 
AND orders.user_id NOT IN ('redo_hbc')  $CollectionsAEvaluer
AND order_date_processed BETWEEN '$AnneeEnCours-$JourDebut' AND '$AnneeEnCours-$JourFin'
AND orders.redo_order_num IS NULL
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
						</tr>";
		}		

		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
		//Removable side shield	
		$queryFrame  = "SELECT order_num, frame_type, supplier, model, temple_model_num, ep_frame_a,ep_frame_dbl,ep_frame_b, color FROM extra_product_orders WHERE order_num = $listItem[order_num] AND category in ('Frame','Edging','Edging_Frame')";
		$resultFrame = mysqli_query($con,$queryFrame)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataFrame   = mysqli_fetch_array($resultFrame,MYSQLI_ASSOC);	
			
			switch($listItem[user_id]){
				//Les HBC
				case '88403':	$AccountNumberWithPerfect = '98403';  		break;	
				case '88408': 	$AccountNumberWithPerfect = '98408';  		break;	
				case '88409':   $AccountNumberWithPerfect = '98409';  		break;	
				case '88411':   $AccountNumberWithPerfect = '98411';  		break;		
				case '88413':  	$AccountNumberWithPerfect = '98413';  		break;	
				case '88414':   $AccountNumberWithPerfect = '98414';  		break;	
				case '88416':   $AccountNumberWithPerfect = '98416';  		break;	
				case '88431':   $AccountNumberWithPerfect = '98431';  		break;	
				case '88433':   $AccountNumberWithPerfect = '98433';  		break;	
				case '88434':   $AccountNumberWithPerfect = '98434';  		break;	
				case '88435':   $AccountNumberWithPerfect = '98435';  		break;	
				case '88438':   $AccountNumberWithPerfect = '98438';  		break;	
				case '88439':  	$AccountNumberWithPerfect = '98439';  		break;		
				case '88440':   $AccountNumberWithPerfect = '98440';  		break;	
				case '88444':   $AccountNumberWithPerfect = '98444';  		break;		
				//Griffé T-R			 	
				case '88666':	$AccountNumberWithPerfect = '98200';  		break;	//Courriel Stefany 3 Juin 2019 16h29
			}
		
			switch($DataFrame[supplier]){
				case 'ELLE-CA': 	 	$Collection = 'ELLE';		break;	
				case 'ESPRIT-CA': 		$Collection = 'ESPRIT';		break;	
				case 'CHARMANT-CA': 	$Collection = 'CHARMANT';	break;	
				case 'STRELLSON-CA': 	$Collection = 'STRELLSON';	break;
				case 'RIPCURL': 		$Collection = 'RIPCURL';	break;
			}


		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";


			$message.="	<tr bgcolor=\"$bgcolor\">
							<td align=\"center\">$listItem[order_date_processed]</td>
							<td align=\"center\"><strong>HBC</strong> #$AccountNumberWithPerfect</td>
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
			$message.="No Frame in these 4 collections (ELLE,ESPRIT,CHARMANT,STRELLSON) have been ordered yesterday.";
		}
echo '<br><br>' . $message;



//SEND EMAIL



//$send_to_address = array('rapports@direct-lens.com');//TEST
//$send_to_address = array('rapports@direct-lens.com');//LIVE
$send_to_address = array('rapports@direct-lens.com');//LIVE

echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Perfect Optical HBC Frames order(s) Between '$AnneeEnCours-$JourDebut' AND '$AnneeEnCours-$JourFin'";
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
	
*/
?>
