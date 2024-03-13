<?php
/*
//Afficher toutes les erreurs/avertissements
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*

//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
include("../sec_connect.inc.php");//Fichier de DataBase:HBC
include('../connexion_hbc.inc.php');
include('../phpmailer_email_functions.inc.php');

/*
CONTEXTE: 

FRÉQUENCE D'EXÉCUTION DE CE RAPPORT: Voir point #8 ci dessous.

Inclus les montures de la collection: 'GEN-Y' vendu par les magasins HBC SI ET UNIQUEMENT SI ELLES SONT COMMANDÉS AVEC UN PRODUIT 'PACKAGE GEN-Y' dans Optipro. ET ONT LE CODE DE MONTURE V1

(Les montures GEN-Y qui sont installées en magasin RESTERONT en magasin. Elles ne quitteront JAMAIS pour le laboratoire)


INFORMATIONS PERTINENTES:
1-Concerne quelles comptes:Tous les comptes des magasins HBC actifs
2-Courriel ou sera envoyé ce rapport: Emilie, Stéfany
3-Collection(s) HBC qui seront évaluées à chaque fois que ce rapport sera lancé:   
	a)GEN-Y

5-Faut-il exclure les reprises de ce rapport :OUI. Car dans la grande majorité du temps, faire une reprise n'implique pas de commander une nouvelle monture.<br>

6-Quantitée minimale avant de lancer la commande NON

7-Désire-t-on que le rapport soit envoyé même s'il ne contient aucune commande ? OUI (Afin de valider par exemple que ce n'est pas le résultat d'un bug qui aurait empêché sa génération)

8-Fréquence de génération: Chaque matin à 7h30, concernant les ventes de la veille. (heure locale du Québec car certains magasins dans l'ouest ferment à  21h = minuit pour nous )
*

//Date du rapport = la veille
$ilya6jours  		= mktime(0,0,0,date("m"),date("d")-6,date("Y"));
$dateilya6jours     = date("Y/m/d", $ilya6jours);

$cejour  			= mktime(0,0,0,date("m"),date("d")-0,date("Y"));
$ajd     			= date("Y/m/d", $cejour);


//A RECOMMENTER
/*
$dateilya6jours   = "2022-04-01";
$ajd 			  = "2022-04-30";
*
$CollectionsAEvaluer =" AND supplier IN ('GEN-Y')";


//TODO CONDITION A RAJOUTER LORSQUE LES PRODUITS GEN-Y SERONT CRÉÉS DANS OPTIPRO
//AND orders.nom_produit_optipro like '%GEN-Y%'

//1- Les jobs exclusive qui en théorie ont utilisé un frame par commande
$rptQuery="SELECT * FROM orders, extra_product_orders 
WHERE orders.order_num = extra_product_orders.order_num 
AND order_product_type = 'exclusive'
AND order_status not in ('cancelled','basket','on hold') 
AND extra_product_orders.category IN ('Frame') 
$CollectionsAEvaluer
AND  redo_order_num is null
AND order_date_processed BETWEEN '$dateilya6jours' AND '$ajd' 
AND orders.code_source_monture='V1'
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

		$CompteurResultat = 0;
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
		$CompteurResultat +=1;
		$queryFrame  = "SELECT order_num, frame_type, supplier, model, temple_model_num, ep_frame_a,ep_frame_dbl,ep_frame_b, color FROM extra_product_orders 
		WHERE order_num = $listItem[order_num] AND category in ('Frame','Edging','Edging_Frame')";
		$resultFrame = mysqli_query($con,$queryFrame)		or die  ('I cannot select items because: ' . mysqli_error($con));

		$DataFrame   = mysqli_fetch_array($resultFrame,MYSQLI_ASSOC);	
		$Collection = 'GEN-Y';	

		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

			$ModeleMonture = $DataFrame[temple_model_num];
			
			$AjouterAuRapport='non';//Initialisation de la variable
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
			$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $CompteurResultat</td></tr></table>";
		}

		if ($ordersnum==0){
			$message.="No Frame in the collection GEN-Y has been sold in this period.";
		}
echo '<br><br>' . $message;



//TODO AJOUTER LE COURRIEL DE QUI??  COMME DESTINATAIRE DE CE RAPPORT, AFIN QUE KUBIK PUISSE RENFLOUER LES MONTURES VENDUS DE L'INVENTAIRE DE ???
$send_to_address=array('dbeaulieu@direct-lens.com','ebaillargeon@entrepotdelalunette.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','thahn@direct-lens.com',

$send_to_address = array('rapports@direct-lens.com');

//SEND EMAIL
//$send_to_address = array('rapports@direct-lens.com');//TEST

echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="GEN-Y HBC [Frames sold Report]: $dateilya6jours -->$ajd";
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
