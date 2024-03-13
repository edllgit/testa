<?php

//Afficher toutes les erreurs/avertissements


error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
//include("../sec_connect.inc.php");//Fichier de DataBase:HBC
include('../sec_connectEDLL.inc.php');
include('../phpmailer_email_functions.inc.php');

/*
CONTEXTE: 

FRÉQUENCE D'EXÉCUTION DE CE RAPPORT:chaque jour

Inclus les montures de la collection: 'ENHANCE' vendu par les magasins EDLL
MODEL	COLOR
3902	BLACK
3902	TORTOISE
3906	BLUEFADE
3906	BLACK
3907	FADEBLACK
3907	BROWNFADE
3984	BROWN
3984	BLACK
3985	BLUE
3985	BLACK
3986	BROWN
3986	BLACK
3991	BLACK
3991	BLUEMABER
4011	GUNMETAL
4011	BROWN
4011	GOLD
4042	GUNMETAL
4042	BLACK
4048	BLUE CRYSTAL
4048	BROWN CRYSTAL
4055	LILAC
4055	TEAL
4055	BLACKFADE
4055	PURPLE
4062	BLACK
4062	BURGUNDY
4078	BROWN
4078	RED
4078	PURPLE
4080	PURPLE MIX
4080	BLUE MIX
4080	RED NAVY
4080	BROWN GOLD MIX
4082	GUNMETAL
4082	GOLD
4082	BURGUNDY
4082	BLUE
4093	BLUE
4093	PURPLE
4100	BROWN MARBLE
4100	GRANITE
4108	GUNMETAL
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
4137	CRYSTAL
4137	GREY CRYSTAL
4138	BROWNFADE
4138	BLACKFADE
4139	BLACK
4139	GREYMATT
4150	BLACK
4150	TORTOISE
4200	BLACK
4200	CRYSTAL

AJOUT 10 NOVEMBRE 2020
4055 lilacfade

//Liste recu d'Émilie par courriel le 9 Septembre 2020 Ticket #2195.

Inclure uniquement les montures suivante (La combinaison Modele + couleur doit être la bonne pour que la monture fasse partie de ce rapport)

INFORMATIONS PERTINENTES:
1-Concerne quelles comptes:Tous les comptes des magasins EDLL actifs
2-Courriel ou sera envoyé ce rapport: Emilie, Stéfany
3-Collection(s) HBC qui seront évaluées à chaque fois que ce rapport sera lancé:   
	a)ENHANCE

5-Faut-il exclure les reprises de ce rapport :OUI. Car dans la grande majorité du temps, faire une reprise n'implique pas de commander une nouvelle monture.<br>

6-Quantitée minimale avant de lancer la commande NON

7-Désire-t-on que le rapport soit envoyé même s'il ne contient aucune commande ? OUI (Afin de valider par exemple que ce n'est pas le résultat d'un bug qui aurait empêché sa génération)

8-Fréquence de génération: Chaque matin à 7h30, concernant les ventes de la veille.
*/

//Date du rapport = la veille
$ilya6jours  		= mktime(0,0,0,date("m"),date("d")-1,date("Y"));
$hier     			= date("Y/m/d", $ilya6jours);

//$hier="2020-11-21";

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
AND redo_order_num is null
AND order_date_processed BETWEEN '$hier' AND '$hier' 
GROUP BY orders.order_num ORDER BY user_id";

/*
$rptQuery="SELECT * FROM orders, extra_product_orders 
WHERE orders.order_num = extra_product_orders.order_num 
AND order_product_type = 'exclusive'
AND order_status not in ('cancelled','basket','on hold') 
AND extra_product_orders.category IN ('Frame') 
$CollectionsAEvaluer
AND order_date_processed BETWEEN '2020-08-02' AND '2020-08-04' 
GROUP BY orders.order_num ORDER BY user_id";
*/

echo '<br>requete:'. $rptQuery;


$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);
	
//echo '<br><br>Nombre de résultat:'. 	$ordersnum. '<br><br>';
	

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
		//echo '<br><br>$queryFrame:' . $queryFrame. '<br>';
		$DataFrame   = mysqli_fetch_array($resultFrame,MYSQLI_ASSOC);	
		$Collection = 'ENHANCE';	

		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

			$ModeleMonture = $DataFrame[temple_model_num];
			
			$AjouterAuRapport='non';//Initialisation de la variable
			$CouleurMontureEnMajuscule = strtoupper($DataFrame[color]);
			
			switch ($DataFrame[temple_model_num]){
				case '3902': if(($CouleurMontureEnMajuscule=='TORTOISE') 	|| ($CouleurMontureEnMajuscule=='BLACK')) 		{$AjouterAuRapport='oui';	$CompteurResultat+=1;} 		break;
				case '3906': if(($CouleurMontureEnMajuscule=='BLACK')		|| ($CouleurMontureEnMajuscule=='BLUEFADE'))		{$AjouterAuRapport='oui';	$CompteurResultat+=1;} 		break;
				case '3907': if(($CouleurMontureEnMajuscule=='FADEBLACK') 	|| ($CouleurMontureEnMajuscule=='BROWNFADE')) 	{$AjouterAuRapport='oui';	$CompteurResultat+=1;} 		break;
				case '3984': if(($CouleurMontureEnMajuscule=='BROWN') 		|| ($CouleurMontureEnMajuscule=='BLACK'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		break;
				case '3985': if(($CouleurMontureEnMajuscule=='BLUE') 		|| ($CouleurMontureEnMajuscule=='BLACK'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		break;
				case '3986': if(($CouleurMontureEnMajuscule=='BROWN') 		|| ($CouleurMontureEnMajuscule=='BLACK'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		break;
				case '3991': if(($CouleurMontureEnMajuscule=='BLACK') 		|| ($CouleurMontureEnMajuscule=='BLUEAMBER'))	{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		break;
				case '4011': if(($CouleurMontureEnMajuscule=='GOLD') || ($CouleurMontureEnMajuscule=='BROWN')  || ($CouleurMontureEnMajuscule=='GUNMETAL'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		break;
				case '4042': if(($CouleurMontureEnMajuscule=='BLACK') 		|| ($CouleurMontureEnMajuscule=='GUNMETAL'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		break;
				case '4048': 
					if($CouleurMontureEnMajuscule=='BLUE CRYSTAL') 											{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		
					if(($CouleurMontureEnMajuscule=='BROWN CRYSTAL')	|| ($CouleurMontureEnMajuscule=='BROWN CRYSTA') 	|| ($CouleurMontureEnMajuscule=='BROWN CRYST'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} 
				break;
				case '4055': if(($CouleurMontureEnMajuscule=='TEAL') || ($CouleurMontureEnMajuscule=='LILAC')  || ($CouleurMontureEnMajuscule=='PURPLE')   || ($CouleurMontureEnMajuscule=='BLACKFADE')) {$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		break;
				case '4062': if(($CouleurMontureEnMajuscule=='BLACK') 		|| ($CouleurMontureEnMajuscule=='BURGUNDY'))	{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}			break;
				case '4078': if(($CouleurMontureEnMajuscule=='RED') || ($CouleurMontureEnMajuscule=='BROWN')  || ($CouleurMontureEnMajuscule=='PURPLE'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		break;

				case '4080': 
					if(($CouleurMontureEnMajuscule=='BLUE MIX') || ($CouleurMontureEnMajuscule=='RED NAVY')  || ($CouleurMontureEnMajuscule=='PURPLE MIX'))	{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		
					if(($CouleurMontureEnMajuscule=='BROWN GOLD MIX')	|| ($CouleurMontureEnMajuscule=='BROWN GOLD MI') || ($CouleurMontureEnMajuscule=='BROWN GOLD M'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} 
				break;
				case '4082': if(($CouleurMontureEnMajuscule=='GOLD') || ($CouleurMontureEnMajuscule=='BLUE')  || ($CouleurMontureEnMajuscule=='GUNMETAL')   || ($CouleurMontureEnMajuscule=='BURGUNDY')) {$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		break;
				case '4093': if(($CouleurMontureEnMajuscule=='BLUE') 		|| ($CouleurMontureEnMajuscule=='PURPLE'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		break;
				case '4100': if(($CouleurMontureEnMajuscule=='GRANITE') 		|| ($CouleurMontureEnMajuscule=='BROWN MARBLE'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}	break;
				case '4108': if(($CouleurMontureEnMajuscule=='BLACK') || ($CouleurMontureEnMajuscule=='COFFEE')  || ($CouleurMontureEnMajuscule=='GUNMETAL'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		break;
				case '4126': 
					if($CouleurMontureEnMajuscule=='BLACKCRYSTAL') 				{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		
					if(($CouleurMontureEnMajuscule=='BURGUNDY FAD'))			{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} 
				break;
				case '4130': if(($CouleurMontureEnMajuscule=='BURGUNDY TOR') || ($CouleurMontureEnMajuscule=='BLUE TORTOIS'))	{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		break;
				case '4131': if(($CouleurMontureEnMajuscule=='BLUE') 		|| ($CouleurMontureEnMajuscule=='BURGUNDY'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		break;
				
				
				case '4132':
					if( $CouleurMontureEnMajuscule=='BLUE CRYSTAL') 											{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}	
					if(($CouleurMontureEnMajuscule=='BLACK CRYSTAL')	|| ($CouleurMontureEnMajuscule=='BLACK CRYSTA'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;} 
				break;	


				
				case '4137': if(($CouleurMontureEnMajuscule=='CRYSTAL') 		|| ($CouleurMontureEnMajuscule=='GREY CRYSTAL'))	{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		break;
				case '4138': if(($CouleurMontureEnMajuscule=='BROWNFADE') 	|| ($CouleurMontureEnMajuscule=='BLACKFADE'))	{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		break;
				case '4139': if(($CouleurMontureEnMajuscule=='BLACK') 		|| ($CouleurMontureEnMajuscule=='GREYMATT'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		break;
				case '4150': if(($CouleurMontureEnMajuscule=='BLACK') 		|| ($CouleurMontureEnMajuscule=='TORTOISE'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		break;
				case '4200': if(($CouleurMontureEnMajuscule=='BLACK') 		|| ($CouleurMontureEnMajuscule=='CRYSTAL'))		{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		break;
				case '4055': if($CouleurMontureEnMajuscule=='LILACFADE') 													{$AjouterAuRapport='oui'; 	$CompteurResultat+=1;}		break;  
				
				//CAS PARTICULIERS CAR LE NOM DE LA COULEUR > 12 CARACTÈRES (Car présentement, je reçois uniquement les 12 premiers caractères)
			}//END SWITCH
			
		//	echo '<br><br>passe apres switch<br><br>';
			
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
$subject="ENHANCE EDLL [Frames sold Report] $hier";
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
