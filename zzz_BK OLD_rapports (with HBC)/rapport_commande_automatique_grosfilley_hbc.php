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

FRÉQUENCE D'EXÉCUTION DE CE RAPPORT: à déterminer

Inclus les montures de la collection: 'GrosFilley' vendu par les magasins HBC 


INFORMATIONS PERTINENTES:
1-Concerne quelles comptes:Tous les comptes des magasins HBC  actifs
2-Courriel ou sera envoyé ce rapport: Émilie Seulement
3-Collection(s) HBC qui seront évaluées à chaque fois que ce rapport sera lancé: 
	a)Azzaro
	b)Azzaro Paris
	c)Chantal Thomass
	d)Charriol
	e)Loris Azzaro


Qui font partie des combinaisons Modele +  couleurs suivantes  SEULEMENT:

COLLECTION	MODÈLE	COULEUR
Azzaro	AZ30155	3	56-18-140
Azzaro	AZ30155	1	56-18-140
Azzaro	AZ30155	2	56-18-140		
Azzaro	AZ30158	3	54-18-140		
Azzaro	AZ30169	3	53-17-135		
Azzaro	AZ30193	1	52-16-135
Azzaro	AZ30193	3	52-16-135			
Azzaro	AZ30205	3	53-17-135			
Azzaro	AZ30220	3	53-15-140			
Azzaro	AZ30222	1	53-18-140
Azzaro	AZ30222	3	53-18-140		
Azzaro	AZ31042	2	55-17-140
Azzaro	AZ31042	1	55-17-140			
Azzaro	AZ31054	2	56-17-140
Azzaro	AZ31054	1	56-17-140			
Azzaro	AZ31069	1	56-19-145
Azzaro	AZ31069	2	56-19-145			
Azzaro	AZ31073	2	57-18-140

Azzaro Paris	AZ30258	2	52-16-140
Azzaro Paris	AZ30250	2	54-16-135
Azzaro Paris	AZ30257	3	54-17-135
Azzaro Paris	AZ31049	3	55-17-140
Azzaro Paris	AZ30252	2	54-16-135
Azzaro Paris	AZ30257	2	54-17-135

Chantal Thomass	CT14087	2	54-15-135
Chantal Thomass	CT14043	1	51-16-135
Chantal Thomass	CT14091	3	52-16-140
Chantal Thomass	CT14116	2	53-16-140
Chantal Thomass	CT14073	1	54-14-140
Chantal Thomass	CT14129	2	49-20-135
Chantal Thomass	CT14076	1	53-17-140
Chantal Thomass	CT14029	1	50-17-135
Chantal Thomass	CT14043	21	51-16-135
Chantal Thomass	CT14029	2	50-17-135
Chantal Thomass	CT14072	2	54-16-140
Chantal Thomass	CT14087	3	54-15-135

Charriol	PC75005	4	57-18-140
Charriol	PC75013	4	57-18-140
Charriol	PC75009	1	57-16-140
Charriol	PC75019	3	56-17-140
Charriol	PC75005	2	57-18-140
Charriol	PC75006	3	56-16-140
Charriol	PC75025	3	56-18-140
Charriol	PC75011	2	56-17-140
Charriol	PC75003	2	55-17-140
Charriol	PC75002	3	57-18-135
Charriol	PC75011	3	56-17-140
Charriol    PC75008 3   56-18-140

Loris Azzaro	AZ35042	3	54-15-135
Loris Azzaro	AZ35061	1	52-16-135
Loris Azzaro	AZ35061	2	52-16-135
Loris Azzaro	AZ35035	1	54-16-135
Loris Azzaro	AZ35045	1	52-16-135
Loris Azzaro	AZ35029	2	53-16-140
Loris Azzaro	AZ35051	1	53-18-140
Loris Azzaro	AZ35035	2	54-16-135



4-Faut-il exclure les reprises de ce rapport :OUI. Car dans la grande majorité du temps, faire une reprise n'implique pas de commander une nouvelle monture.<br>

5-Quantitée minimale avant de lancer la commande ???  Que se passe-t-il si quantitée non atteinte ?

6-Désire-t-on que le rapport soit envoyé même s'il ne contient aucune commande ? OUI (Afin de valider par exemple que ce n'est pas le résultat d'un bug qui aurait empêché sa génération)

7-Fréquence de génération: 1 fois par jour: Chaque matin à 1h AM, concernant les ventes de la veille. (heure locale du Québec car certains magasins dans l'ouest ferment à  21h = minuit pour nous )
*

//Date du rapport
$ilya6jours  		= mktime(0,0,0,date("m"),date("d")-1,date("Y"));
$hier     			= date("Y/m/d", $ilya6jours);
$CollectionsAEvaluer =" AND supplier IN ('Azzaro','Azzaro Paris','Chantal Thomass','Charriol','Loris Azzaro')";


//$hier  ="2020-07-08";

//1- Les jobs exclusive qui en théorie ont utilisé un frame par commande
$rptQuery="SELECT * FROM orders, extra_product_orders 
WHERE orders.order_num = extra_product_orders.order_num 
AND order_product_type = 'exclusive'
AND orders.code_source_monture = 'V1'
AND order_status not in ('cancelled','basket','on hold') 
AND extra_product_orders.category IN ('Frame') 
AND  redo_order_num is null
$CollectionsAEvaluer
AND order_date_processed = '$hier'
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
								<td align=\"center\">$DataFrame[ep_frame_a]-$DataFrame[ep_frame_dbl]</td>";
				  $message.="</tr>";
		}//END WHILE

		if ($ordersnum>0){
			$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Nombre de commande(s): $CompteurResultat</td></tr></table>";
		}//END IF
		
		if ($ordersnum==0){
			$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">None of theses frames have been ordered</td></tr></table>";
		}

		
echo '<br><br>' . $message;

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');//LIVE
$send_to_address = array('rapports@direct-lens.com');//LIVE
//$send_to_address = array('rapports@direct-lens.com');//TEST



echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="[Frames sold Report] Grosfiley HBO-->  $hier ";

	
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
