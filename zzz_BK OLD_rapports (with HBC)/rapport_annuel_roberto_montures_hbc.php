<?php
/*
//Afficher toutes les erreurs/avertissements
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
include('../sec_connectEDLL.inc.php');//Fichier de DataBase:HBC
include('../phpmailer_email_functions.inc.php');

/*
CONTEXTE: 

FRÉQUENCE D'EXÉCUTION DE CE RAPPORT:1 fois par mois

Inclus toutes les montures vendues durant le mois précédent par les magasins HBC 


INFORMATIONS PERTINENTES:
1-Concerne quelles comptes:Tous les comptes des magasins HBC actifs
2-Courriel ou sera envoyé ce rapport: Émilie + Roberto + Moi
3-Faut-il exclure les reprises de ce rapport :OUI. Car dans la grande majorité du temps, faire une reprise n'implique pas de commander une nouvelle monture.<br>
4-Désire-t-on que le rapport soit envoyé même s'il ne contient aucune commande ? OUI (Afin de valider par exemple que ce n'est pas le résultat d'un bug qui aurait empêché sa génération)
5-Fréquence de génération: 1 fois par mois: Concernant les ventes du mois précédent.


$datedujour  		= mktime(0,0,0,date("m"),date("d"),date("Y"));
$aujourdhui     	= date("Y/m/d", $datedujour);

echo '<br>Date du jour:'. $aujourdhui;
echo '<br>Mois en cours:'. $MoisEnCours;
echo '<br>JourDebut:'. $JourDebut;
echo '<br>JourFin:'. $JourFin.'<br><br>';

//1- Les jobs exclusive qui en théorie ont utilisé un frame par commande
$rptQuery="SELECT * FROM orders, extra_product_orders 
WHERE orders.order_num = extra_product_orders.order_num 
AND order_product_type = 'exclusive'
AND order_status not in ('cancelled','basket','on hold') 
AND extra_product_orders.category IN ('Frame') 
AND  redo_order_num is null
AND orders.user_id<>'88666'
AND order_date_processed BETWEEN '2020-11-01' AND '2020-11-25'
GROUP BY orders.order_num ORDER BY order_date_processed";


echo '<br>requete:'. $rptQuery;


echo '<br>Nombre de resultat:'. $ordersnum;

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
			$message.="<table width=\"1075\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">";
			$message.="<tr bgcolor=\"CCCCCC\">
							<th width=\"80\" align=\"center\">Date</th>
							<th width=\"80\" align=\"center\">Store</th>
							<th width=\"80\" align=\"center\">Order #</th>
							<th width=\"80\" align=\"center\">Collection</th>
							<th width=\"80\" align=\"center\">Model</th>
							<th width=\"80\" align=\"center\">Color</th>
							<th width=\"70\" align=\"center\">A-Bridge</th>
							<th align=\"center\">Lenses</th>
							<th width=\"70\" align=\"center\">Frame Source Code</th>
						</tr>";
		}		

		$CompteurResultat = 0;
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){

		$queryFrame  = "SELECT order_num, frame_type, supplier, model, temple_model_num, ep_frame_a,ep_frame_dbl,ep_frame_b, color FROM extra_product_orders 
		WHERE order_num = $listItem[order_num] AND category in ('Frame','Edging','Edging_Frame')";
		$resultFrame = mysqli_query($con,$queryFrame)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataFrame   = mysqli_fetch_array($resultFrame,MYSQLI_ASSOC);	
		$Collection = $DataFrame[supplier];

		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

			$ModeleMonture = $DataFrame[temple_model_num];
			
					$message.="	<tr bgcolor=\"$bgcolor\">
								<td align=\"center\">$listItem[order_date_processed]</td>
								<td align=\"center\">$listItem[user_id]</td>
								<td align=\"center\">$listItem[order_num]</td>
								<td align=\"center\">$Collection</td>
								<td align=\"center\">$ModeleMonture</td>
								<td align=\"center\">$DataFrame[color]</td>
								<td align=\"center\">$DataFrame[ep_frame_a]-$DataFrame[ep_frame_dbl]</td>
								<td align=\"center\">$listItem[order_product_name]</td>
								<td align=\"center\">$listItem[code_source_monture]</td>";
				  $message.="</tr>";

		}//END WHILE

		if ($ordersnum>0){
			$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Nombre de commande(s): $ordersnum</td></tr></table>";
		}//END IF

echo '<br><br>' . $message;

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');//LIVE
//$send_to_address = array('rapports@direct-lens.com');//TEST

echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="[Annual HBO frame sold Report]--> ['2020-01-01' AND '2020-11-25']";
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