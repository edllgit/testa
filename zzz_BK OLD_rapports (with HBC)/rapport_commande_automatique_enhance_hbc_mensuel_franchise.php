<?php
/*
//Afficher toutes les erreurs/avertissements
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
include("../sec_connect.inc.php");//Fichier de DataBase:HBC
include('../connexion_hbc.inc.php');
include('../phpmailer_email_functions.inc.php');

/*
CONTEXTE: 

FRÉQUENCE D'EXÉCUTION DE CE RAPPORT: à déterminer

Inclus les montures de la collection: 'ENHANCE' vendu par les magasins HBC SI ET UNIQUEMENT SI ELLES SONT COMMANDÉS AVEC UN PRODUIT 'PACKAGE ENHANCE' dans Optipro.
Model	Color
3904	TORTOISE
3904	BLACK
3907	FADEBLACK
3907	BROWNFADE
3913	NAVY
3913	BURGUNDY
3943	SMOKE
3943	BLACKFADE
3945	GREYSMOKE
3945	BROWN
3984	BROWN
3984	BLACK
3985	BLUE
3985	BLACK
3985	BLUE
3985	BLACK
3986	BROWN
3986	BLACK
3991	BLUEAMBER
3991	BLACK
4002	BURGUNDY
4002	BLUE
4011	GOLD
4011	GUNMETAL
4029	NAVY
4029	BLACK
4042	GUNMETAL
4042	BLACK
4046	BLACK MIX
4046	PURPLE MIX
4048	BLUE CRYSTAL
4048	BROWN CRYSTAL
4055	BLACK FADE
4055	PURPLE
4062	BURGUNDY
4062	BLACK
4079	PURPLE
4079	BLUE
4080	PURPLE MIX
4080	BROWN GOLD MIX

*NOUVEAUX DU 4 MARS 2020****
4082	GUNMETAL
4082	GOLD
*FIN NOUVEAUTÉS 4 MARS 2020


4093	PURPLE
4093	BLUE
4100	BROWN MARBLE
4100	GRANITE
4108	BLACK
4108	COFFEE
4126	BURGUNDY FADE
4126	BLACKCRYSTAL
4130	BLUE TORTOISE
4130	BURGUNDY TORTOSE
4131	BURGUNDY
4131	BLUE
4132	BLUE CRYSTAL
4132	BLACK CRYSTAL
4137	GREY CRYSTAL
4137	CRYSTAL
4138	BROWNFADE
4138	BLACKFADE
4139	BLACK
4139	GREYMATT
4150	TORTOISE
4150	BLACK
4200	BLACK
4200	CRYSTAL

//Liste recu d'Émilie par courriel le 26 Février 2020 + MAJ ticket #1593 le 4 Mars 2020
Inclure uniquement les montures suivante (La combinaison Modele + couleur doit être la bonne pour que la monture fasse partie de ce rapport)
(Les montures ENHANCE qui sont installées en magasin RESTERONT en magasin. Elles ne quitteront JAMAIS pour le laboratoire)


INFORMATIONS PERTINENTES:
1-Concerne quelles comptes:Tous les comptes des magasins HBC actifs
2-Courriel ou sera envoyé ce rapport: Emilie, Stéfany
3-Collection(s) HBC qui seront évaluées à chaque fois que ce rapport sera lancé:   
	a)ENHANCE

5-Faut-il exclure les reprises de ce rapport :OUI. Car dans la grande majorité du temps, faire une reprise n'implique pas de commander une nouvelle monture.<br>

6-Quantitée minimale avant de lancer la commande NON

7-Désire-t-on que le rapport soit envoyé même s'il ne contient aucune commande ? OUI (Afin de valider par exemple que ce n'est pas le résultat d'un bug qui aurait empêché sa génération)

8-Fréquence de génération: Chaque matin à 7h30, concernant les ventes de la veille. (heure locale du Québec car certains magasins dans l'ouest ferment à  21h = minuit pour nous )

//Date du rapport
$ladatedhier  = mktime(0,0,0,date("m"),date("d")-6,date("Y"));
$Ilya6jours     	  = date("Y-m-d", $ladatedhier);


$datedujour  		= mktime(0,0,0,date("m"),date("d"),date("Y"));
$aujourdhui     	= date("Y/m/d", $datedujour);

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

$CollectionsAEvaluer =" AND supplier IN ('ENHANCE')";


//TODO CONDITION A RAJOUTER LORSQUE LES PRODUITS ENHANCE SERONT CRÉÉS DANS OPTIPRO
//AND orders.nom_produit_optipro like '%ENHANCE%'

//1- Les jobs exclusive qui en théorie ont utilisé un frame par commande
$rptQuery="SELECT * FROM orders, extra_product_orders 
WHERE orders.order_num = extra_product_orders.order_num 
AND order_product_type = 'exclusive'
AND order_status not in ('cancelled','basket','on hold') 
AND extra_product_orders.category IN ('Frame') 
$CollectionsAEvaluer
AND user_id IN ('88433','88438','88439')
AND redo_order_num is null
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
		$Collection = 'ENHANCE';	

		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

			$ModeleMonture = $DataFrame[temple_model_num];
			
			$AjouterAuRapport='non';//Initialisation de la variable
			switch ($DataFrame[temple_model_num]){
				case '3904': if(($DataFrame[color]=='TORTOISE') 	|| ($DataFrame[color]=='BLACK')) 			{$AjouterAuRapport='oui';	$CompteurResultat+=1;} break;
				case '3907': if(($DataFrame[color]=='FADEBLACK')	|| ($DataFrame[color]=='BROWNFADE'))		{$AjouterAuRapport='oui';	$CompteurResultat+=1;} break;
				case '3913': if(($DataFrame[color]=='NAVY') 		|| ($DataFrame[color]=='BURGUNDY')) 		{$AjouterAuRapport='oui';	$CompteurResultat+=1;} break;
				case '3943': if(($DataFrame[color]=='SMOKE') 		|| ($DataFrame[color]=='BLACKFADE'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				case '3945': if(($DataFrame[color]=='GREYSMOKE') 	|| ($DataFrame[color]=='BROWN'))			{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				case '3984': if(($DataFrame[color]=='BROWN') 		|| ($DataFrame[color]=='BLACK'))			{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				case '3985': if(($DataFrame[color]=='BLUE') 		|| ($DataFrame[color]=='BLACK'))			{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				case '3986': if(($DataFrame[color]=='BROWN') 		|| ($DataFrame[color]=='BLACK'))			{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				case '3991': if(($DataFrame[color]=='BLUEAMBER') 	|| ($DataFrame[color]=='BLACK'))			{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				case '4002': if(($DataFrame[color]=='BURGUNDY') 	|| ($DataFrame[color]=='BLUE'))				{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				case '4011': if(($DataFrame[color]=='GUNMETAL') 	|| ($DataFrame[color]=='GOLD'))				{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				case '4029': if(($DataFrame[color]=='NAVY') 		|| ($DataFrame[color]=='BLACK'))			{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				case '4042': if(($DataFrame[color]=='GUNMETAL') 	|| ($DataFrame[color]=='BLACK'))			{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				case '4046': if(($DataFrame[color]=='PURPLE MIX') 	|| ($DataFrame[color]=='BLACK MIX'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				case '4055': if(($DataFrame[color]=='BLACK FADE')	|| ($DataFrame[color]=='PURPLE'))			{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				case '4062': if(($DataFrame[color]=='BURGUNDY')		|| ($DataFrame[color]=='BLACK'))			{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				case '4079': if(($DataFrame[color]=='PURPLE')		|| ($DataFrame[color]=='BLUE'))				{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				
				//Ajouté le 4 Mars 2020
				case '4082': if(($DataFrame[color]=='GUNMETAL')		|| ($DataFrame[color]=='GOLD'))				{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				
				case '4093': if(($DataFrame[color]=='PURPLE')		|| ($DataFrame[color]=='BLUE'))				{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				case '4100': if(($DataFrame[color]=='BROWN MARBLE')	|| ($DataFrame[color]=='GRANITE'))			{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				case '4108': if(($DataFrame[color]=='BLACK')		|| ($DataFrame[color]=='COFFEE'))			{$AjouterAuRapport='oui';	$CompteurResultat+=1;} break;
				case '4131': if(($DataFrame[color]=='BURGUNDY')		|| ($DataFrame[color]=='BLUE'))				{$AjouterAuRapport='oui';	$CompteurResultat+=1;} break;
				case '4137': if(($DataFrame[color]=='GREY CRYSTAL')	|| ($DataFrame[color]=='CRYSTAL'))			{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				case '4138': if(($DataFrame[color]=='BROWNFADE')	|| ($DataFrame[color]=='BLACKFADE'))		{$AjouterAuRapport='oui';	$CompteurResultat+=1;} break;
				case '4139': if(($DataFrame[color]=='GREYMATT')		|| ($DataFrame[color]=='BLACK'))			{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				case '4150': if(($DataFrame[color]=='TORTOISE')		|| ($DataFrame[color]=='BLACK'))			{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				case '4200': if(($DataFrame[color]=='BLACK')		|| ($DataFrame[color]=='CRYSTAL'))			{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				
				//CAS PARTICULIERS CAR LE NOM DE LA COULEUR > 12 CARACTÈRES (Car présentement, je reçois uniquement les 12 premiers caractères)
				case '4132': if(($DataFrame[color]=='BLACK CRYSTAL')	|| ($DataFrame[color]=='BLUE CRYSTAL') 	|| ($DataFrame[color]=='BLACK CRYSTA'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				case '4126': if(($DataFrame[color]=='BURGUNDY FADE')	|| ($DataFrame[color]=='BLACKCRYSTAL') 	|| ($DataFrame[color]=='BURGUNDY FAD'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				case '4130': if(($DataFrame[color]=='BURGUNDY TORTOSE')	|| ($DataFrame[color]=='BLUE TORTOISE') || ($DataFrame[color]=='BURGUNDY TOR') 	|| ($DataFrame[color]=='BLUE TORTOIS'))	{$AjouterAuRapport='oui';	$CompteurResultat+=1;} break;
				case '4048': if(($DataFrame[color]=='BROWN CRYSTAL')	|| ($DataFrame[color]=='BLUE CRYSTAL') 	|| ($DataFrame[color]=='BROWN CRYSTA'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
				case '4080': if(($DataFrame[color]=='BROWN GOLD MIX')	|| ($DataFrame[color]=='PURPLE MIX') || ($DataFrame[color]=='BROWN GOLD M'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} break;
			}//END SWITCH
			
			if ($AjouterAuRapport=='oui'){
					$message.="	<tr bgcolor=\"$bgcolor\">
								<td align=\"center\">$listItem[order_date_processed]</td>
								<td align=\"center\">$listItem[user_id]</td>
								<td align=\"center\">$listItem[order_num]</td>
								<td align=\"center\">$Collection</td>
								<td align=\"center\">$ModeleMonture</td>
								<td align=\"center\">$DataFrame[color]</td>
								<td align=\"center\">$DataFrame[ep_frame_a]-$DataFrame[ep_frame_dbl]</td>";
				  $message.="</tr>";
			}//END IF
		}//END WHILE

		if ($ordersnum>0){
			$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $CompteurResultat</td></tr></table>";
		}

		if ($ordersnum==0){
			$message.="No Frame in the collection ENHANCE has been sold in this period.";
		}
echo '<br><br>' . $message;

//SEND EMAIL
//$send_to_address = array('rapports@direct-lens.com');//TEST

//TODO AJOUTER LE COURRIEL DE QUI??  COMME DESTINATAIRE DE CE RAPPORT, AFIN QUE KUBIK PUISSE RENFLOUER LES MONTURES VENDUS DE L'INVENTAIRE DE ???
$send_to_address = array('rapports@direct-lens.com');



echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="ENHANCE HBC Monthly Franchise [Frames sold Report] AND order_date_processed BETWEEN '$AnneeEnCours-$JourDebut'-'$AnneeEnCours-$JourFin'";
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
