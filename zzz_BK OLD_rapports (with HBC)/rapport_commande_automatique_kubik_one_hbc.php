<?php
/*
//Afficher toutes les erreurs/avertissements
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
include("../connexion_hbc.inc.php");//Fichier de DataBase:HBC
include('../phpmailer_email_functions.inc.php');


/*
CONTEXTE: 

FRÉQUENCE D'EXÉCUTION DE CE RAPPORT: 1 fois par semaine pour débuter. Lorsque le volume le justifiera, on pourra changer pour Lundi-Mercredi-Vendredi.


Inclus les montures de la collection: KUBIK ONE vendu par les magasins HBC SI ET UNIQUEMENT SI ELLES SONT COMMANDÉS AVEC UN PRODUIT 'PACKAGE K-ONE' dans Optipro.

[HKO DOIT  être avisé de ces  ventes, car c'est eux qui enverront  les montures nécessaires à leur laboratoire.]
(Les montures KUBIK ONE qui sont installées en magasin resteront en magasin. Elles ne quitteront JAMAIS pour le laboratoire)


INFORMATIONS PERTINENTES:
1-Concerne quelles comptes:Tous les comptes des magasins HBC actifs
2-Courriel ou sera envoyé ce rapport:(Wayne): Wayneling@hkoptlens.com.hk + (Bella): bella@hkolens.com + copie à moi (courriel Entrepot de la lunette) + copie a Rx:(Attente de l'Adresse email)
3-Numéro de compte à utiliser: Aucun puisque nous laissons nos montures en consignation là-bas
4-Collections HBC qui seront évaluées à chaque fois que ce rapport sera lancé:   
	a)KUBIK ONE-CA - a)KUBIK ONE-ECA

5-Faut-il exclure les reprises de ce rapport :OUI. Car dans la grande majorité du temps, faire une reprise n'implique pas de commander une nouvelle monture.<br>

6-Quantitée minimale avant de lancer la commande ?
<br>
7-Désire-t-on que le rapport soit envoyé même s'il ne contient aucune commande ? (Afin de valider par exemple que ce n'est pas le résultat d'un bug qui aurait empêché sa génération)
8-Fréquence de génération: Chaque matin à 1h AM, concernant les ventes de la veille. (heure locale du Québec car certains magasins dans l'ouest ferment à  21h = minuit pour nous )
*

//Date du rapport
$ilya6jours  		= mktime(0,0,0,date("m"),date("d")-6,date("Y"));
$DateilYa6Jours     = date("Y/m/d", $ilya6jours);

$ajd  			= mktime(0,0,0,date("m"),date("d"),date("Y"));
$DateduJour     = date("Y/m/d", $ajd);

//A RECOMMENTER
/*
$DateilYa6Jours ="2021-04-01";
$DateduJour  	="2021-04-07";
*

$CollectionsAEvaluer =" AND supplier IN ('KUBIK ONE-CA','KUBIK ONE')";

//1- Les jobs exclusive qui en théorie ont utilisé un frame par commande
$rptQuery="SELECT * FROM orders, extra_product_orders 
WHERE orders.order_num = extra_product_orders.order_num 
AND order_product_type = 'exclusive'
AND order_status not in ('cancelled','basket','on hold') 
AND extra_product_orders.category IN ('Frame') 
$CollectionsAEvaluer
AND prescript_lab = 2
AND orders.code_source_monture='V3'
AND order_date_processed BETWEEN '$DateilYa6Jours' AND '$DateduJour' 
AND orders.nom_produit_optipro like '%KONE%'
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
			$message.="<table width=\"700\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">";
			$message.="<tr bgcolor=\"CCCCCC\">
							<th width=\"80\" align=\"center\">Date</th>
							<th width=\"80\" align=\"center\">Store</th>
							<th width=\"80\" align=\"center\">Order #</th>
							<th width=\"80\" align=\"center\">Collection</th>
							<th width=\"80\" align=\"center\">Model</th>
							<th width=\"80\" align=\"center\">Color</th>
							<th width=\"70\" align=\"center\">A-Bridge</th>
						</tr>";
		}		

		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
		//Removable side shield	
		$queryFrame  = "SELECT order_num, frame_type, supplier, model, temple_model_num, ep_frame_a,ep_frame_dbl,ep_frame_b, color FROM extra_product_orders 
		WHERE order_num = $listItem[order_num] AND category in ('Frame','Edging','Edging_Frame')";
		$resultFrame = mysqli_query($con,$queryFrame)		or die  ('I cannot select items because: ' . mysqli_error($con));

		$DataFrame   = mysqli_fetch_array($resultFrame,MYSQLI_ASSOC);	
			

		switch($DataFrame[supplier]){
			case 'KUBIK ONE-CA': 	 $Collection = 'KUBIK ONE';		break;	
			case 'KUBIK ONE-ECA': 	 $Collection = 'KUBIK ONE';		break;			
		}


		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

			$ModeleMonture = str_replace('ONE','KK',$DataFrame[temple_model_num]);
			
			$message.="	<tr bgcolor=\"$bgcolor\">
							<td align=\"center\">$listItem[order_date_processed]</td>
							<td align=\"center\">$listItem[user_id]</td>
					   		<td align=\"center\">$listItem[order_num]</td>
			 				<td align=\"center\">$Collection</td>
							<td align=\"center\">$ModeleMonture</td>
							<td align=\"center\">$DataFrame[color]</td>
							<td align=\"center\">$DataFrame[ep_frame_a]-$DataFrame[ep_frame_dbl]</td>";
              $message.="</tr>";
		}//END WHILE

		if ($ordersnum>0){
			$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";
		}

		if ($ordersnum==0){
			$message.="No Frame in the collection KUBIK ONE has been sold in this period.";
		}
echo '<br><br>' . $message;

//SEND EMAIL
//$send_to_address = array('rapports@direct-lens.com');//TEST

//TODO AJOUTER LE COURRIEL DE KUBIK COMME DESTINATAIRE DE CE RAPPORT, AFIN QUE KUBIK PUISSE RENFLOUER LES MONTURES VENDUS DE L'INVENTAIRE D'HKO
$send_to_address = array('rapports@direct-lens.com');

$send_to_address = array('rapports@direct-lens.com');

//COURRIEL DE TEST, A RECOMMENTER LORSQUE CE RAPPORT SERA MIS EN PRODUCTION
//$send_to_address = array('rapports@direct-lens.com');


echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="KUBIK ONE HBC [Frames sold Report] $DateilYa6Jours-$DateduJour";
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
