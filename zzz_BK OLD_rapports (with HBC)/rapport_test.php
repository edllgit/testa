<?php
//Afficher toutes les erreurs/avertissements
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
include("../sec_connect.inc.php");//Fichier de DataBase:HBC
include('../sec_connectEDLL.inc.php');
include('../phpmailer_email_functions.inc.php');

/*
CONTEXTE: 

FRÉQUENCE D'EXÉCUTION DE CE RAPPORT: à déterminer

Inclus les montures de la collection: 'GrosFilley' vendu par les magasins EDLL 


INFORMATIONS PERTINENTES:
1-Concerne quelles comptes:Tous les comptes des magasins EDLL actifs
2-Courriel ou sera envoyé ce rapport: Émilie Seulement (pour la version EDLL)
3-Collection(s) HBC qui seront évaluées à chaque fois que ce rapport sera lancé:   
	a)AZZARO
	b)CHANTAL THOMASS
	c)TEHIA

Qui font partie des combinaisons Modele +  couleurs suivantes  SEULEMENT:

COLLECTION		MODÈLE		COULEUR
AZZARO			AZ30155		1
AZZARO			AZ30155		2
AZZARO			AZ30155		3
AZZARO			AZ30158		3
AZZARO			AZ30237		1
AZZARO			AZ30241		2
AZZARO			AZ30254		1
AZZARO			AZ30255		2
AZZARO			AZ30256		1
AZZARO			AZ30257		2
AZZARO			AZ30257		3
AZZARO			AZ30260		2
AZZARO			AZ30263		1
AZZARO			AZ30264		2
AZZARO			AZ30273		2
AZZARO			AZ30279		3
AZZARO			AZ31038		2
AZZARO			AZ31042		1
AZZARO			AZ31042		2
AZZARO			AZ31052		1
AZZARO			AZ31056		1
AZZARO			AZ31062		1
AZZARO			AZ31063		1
AZZARO			AZ31070		1
CHANTAL THOMASS	CT14072		2
CHANTAL THOMASS	CT14075		1
CHANTAL THOMASS	CT14077		3
CHANTAL THOMASS	CT14088		2
CHANTAL THOMASS	CT14089		1
CHANTAL THOMASS	CT14096		1
CHANTAL THOMASS	CT14097		1
CHANTAL THOMASS	CT14113		1
CHANTAL THOMASS	CT14116		2
CHANTAL THOMASS	CT14120		3
CHANTAL THOMASS	CT14124		2
CHANTAL THOMASS	CT14129		2
TEHIA			T50054		2
TEHIA			T50054		4
TEHIA			T50071		2
TEHIA			T50072		3
TEHIA			T50076		1
TEHIA			T50077		3
TEHIA			T50079		3
TEHIA			T50084		1
TEHIA			T50084		3
TEHIA			T50085		1
TEHIA			T50086		2
TEHIA			T50086		3


4-Faut-il exclure les reprises de ce rapport :OUI. Car dans la grande majorité du temps, faire une reprise n'implique pas de commander une nouvelle monture.<br>

5-Quantitée minimale avant de lancer la commande ???  Que se passe-t-il si quantitée non atteinte ?

6-Désire-t-on que le rapport soit envoyé même s'il ne contient aucune commande ? OUI (Afin de valider par exemple que ce n'est pas le résultat d'un bug qui aurait empêché sa génération)

7-Fréquence de génération: 1 fois par jour: Chaque matin à 1h AM, concernant les ventes de la veille. (heure locale du Québec car certains magasins dans l'ouest ferment à  21h = minuit pour nous )
*/

$CollectionsAEvaluer =" AND supplier IN ('AZZARO','CHANTAL THOMASS','TEHIA')";

$datedujour  		= mktime(0,0,0,date("m"),date("d"),date("Y"));
$aujourdhui     	= date("Y/m/d", $datedujour);


echo '<br>Date du jour:'. $aujourdhui;

//Ajout pour transformer ce rapport bi-mensuel en rapport mensuel
$MoisEnCours 	= date("m", $datedujour);
 
$MoisEnCours=$MoisEnCours-1;//Pour sélectionner le mois qui vient de se terminer
$AnneeEnCours  = date("Y", $datedujour); 
if ($MoisEnCours==0){
	$MoisEnCours=12;	
	$AnneeEnCours = $AnneeEnCours-1;
}
switch($MoisEnCours){
		case 1:	$JourDebut="01-01";	$JourFin="01-31";	$AnneeEnCours = $AnneeEnCours-1; 	break; //Janvier 
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
		case 12:$JourDebut="12-01";	$JourFin="12-31";	$AnneeEnCours = $AnneeEnCours  ;	break; //Décembre	
}


echo '<br>Mois en cours:'. $MoisEnCours;
echo '<br>JourDebut:'. $JourDebut;
echo '<br>JourFin:'. $JourFin.'<br><br>';

//1- Les jobs exclusive qui en théorie ont utilisé un frame par commande
$rptQuery="SELECT * FROM orders, extra_product_orders 
WHERE orders.order_num = extra_product_orders.order_num 
AND order_product_type = 'exclusive'
AND orders.code_source_monture = 'V1'
AND order_status not in ('cancelled','basket','on hold') 
AND extra_product_orders.category IN ('Frame') 
AND  redo_order_num is null
$CollectionsAEvaluer
AND order_date_processed BETWEEN '$AnneeEnCours-$JourDebut' AND '$AnneeEnCours-$JourFin' 
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
			
			$AjouterAuRapport='non';//Initialisation de la variable
			$CompteurResultat+=1;
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

echo '<br><br>' . $message;

//SEND EMAIL
//$send_to_address = array('rapports@direct-lens.com');//LIVE
$send_to_address = array('rapports@direct-lens.com');//TEST

echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="[Frames sold Report] Grosfilley Mensuel EDLL--> ['$AnneeEnCours-$JourDebut' AND '$AnneeEnCours-$JourFin']";
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