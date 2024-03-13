<?php 
header('Content-type: text/html; charset=UTF-8');

/*
ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
include('../connexion_hbc.inc.php');//Connexion DB HBO


//error_reporting(E_WARNING);

//$date1       = date("Y-m-d");
//$date2       = date("Y-m-d");


$date1   	= $_REQUEST[date1];
$date2     	= $_REQUEST[date2];


//Date du rapport
//$ladatedhier  = mktime(0,0,0,date("m"),date("d")-6,date("Y"));
//$date1     	  = date("Y-m-d", $ladatedhier);


//$ladate  	= mktime(0,0,0,date("m"),date("d"),date("Y"));
//$date2 = date("Y-m-d", $ladate);


//DATES HARD CODÉS MANUELLE
$date1        = "2022-09-01";
$date2        = "2022-09-31";

include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

$LargeurColspanTableau =20;	//Nombre de TD dans le tableau
$WidthTableau = "100%";		//Pixels

//EDLL.CA PRODUCTION PART

//Prepare email 

//Paramètres communs
$from_address='donotreply@entrepotdelalunette.com';


		
	
$message="";

$message="<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
<style type='text/css'>
<!--
.TextSize {
	font-size: 10pt;
	font-family: Arial, Helvetica, sans-serif;
}
-->
</style></head>
<body>
<table width='$WidthTableau' border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">
	<tr>
		<th  colspan=\"$LargeurColspanTableau\"><h3>Incentive Report $date1-$date2 </h3></th>
	</tr>";
	
	$message.= "
		<tr>
			<th bgcolor=\"#C8C8C8\" align=\"center\">STORE</th>
			<th bgcolor=\"#C8C8C8\" align=\"center\">ASSOCIATE</th>
			
			<th bgcolor=\"#e0c1b3\" align=\"center\">HD-IOT(1$)</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">Precision Advance(2$)</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">iFree(3$)</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">Maxiwide(5$)</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">SV HD(2$)</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">AR-ETC(2$)</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">MaxiVue II(3$)</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">Photo(3$)</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">Polarized(3$)</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">ABC(3$)</th>
			
			<th bgcolor=\"#f3678b\" align=\"center\">TOTAL ORDERS</th>
			<th bgcolor=\"#f3678b\" align=\"center\">REDOS</th>
			<th bgcolor=\"#f3678b\" align=\"center\">REDO %</th>
			
			<th bgcolor=\"#C8C8C8\" align=\"center\">SUB-TOTAL INCENTIVE</th>
			<th bgcolor=\"#C8C8C8\" align=\"center\">REDOS</th>
			<th bgcolor=\"#C8C8C8\" align=\"center\">TOTAL INCENTIVE</th>
			<th bgcolor=\"#C8C8C8\" align=\"center\">60%</th>
			<th bgcolor=\"#C8C8C8\" align=\"center\">40%</th>
		</tr>";
	

for ($i = 1; $i <= 14 ; $i++) {
		//echo '<br> Magasin: '. $i;
		//echo '<br><br><br><br><br><br>Passe dans case :'. $i;
		switch($i){
			case  1	:$Userid =  " ('88403')";    $Partie = '88403-Bloor';	 		break;
			case  2	:$Userid =  " ('88408')";    $Partie = '88408-Oshawa';	 		break;
			case  3	:$Userid =  " ('88409')";    $Partie = '88409-Eglinton';	 	break;
			case  4	:$Userid =  " ('88411')";    $Partie = '88411-Sherway';	 		break;
			case  5	:$Userid =  " ('88414')";    $Partie = '88414-Yorkdale';	 	break;
			case  6	:$Userid =  " ('88416')";    $Partie = '88416-Vancouver DTN';	break;
			case  7	:$Userid =  " ('88431')";    $Partie = '88431-Calgary DTN';	 	break;
			case  8	:$Userid =  " ('88433')";    $Partie = '88433-Polo Park';	 	break;
			case  9	:$Userid =  " ('88434')";    $Partie = '88434-Market Mall';	 	break;
			case  10:$Userid =  " ('88435')";    $Partie = '88435-West Edmonton';	break;
			case  11:$Userid =  " ('88438')";    $Partie = '88438-Metrotown';	 	break;
			case  12:$Userid =  " ('88439')";    $Partie = '88439-Langley';	 		break;
			case  13:$Userid =  " ('88440')";    $Partie = '88440-Rideau';	 		break;
			case  14:$Userid =  " ('88444')";    $Partie = '88444-Mayfair';	 		break;
		}//End Switch
		
		
	//echo '<br><br>USER ID:'.	$Userid . '<br>';
		
	//Type de produit	
	$TotalHDIOT  			= 0;//Initialise le compteur de bonus HD IOT
	$TotalPrecisionAdvance  = 0;//Initialise le compteur de bonus Progressif_Advance	
	$TotalIFREE				= 0;//Initialise le compteur de bonus Ifree	
	$TotalMAXIWIDE			= 0;//Initialise le compteur de bonus Maxiwide
	$TotalSVHD				= 0;//Initialise le compteur de bonus SV HD
	
	//Traitements
	$TotalARETC				= 0;//Initialise le compteur de bonus pour le  traitement AR+ETC
	$TotalMAXIIVUE			= 0;//Initialise le compteur de bonus pour le  traitement Maxiivue
	
	//Option supplémentaire
	$TotalPHOTO				= 0;//Initialise le compteur de bonus pour le  traitement Photo ou Transitions
	$TotalPOLAR				= 0;//Initialise le compteur de bonus pour le  traitement Polarized
	$TotalABC_WARRANTY		= 0;//Initialise le compteur de bonus pour le de Garantie ABC
	
	$store	 			= $Userid;		
	$StoreDescription	= $Partie;
	//$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Succursale:$StoreDescription</h3></th></tr>";
	
	
	
	$message.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
	//$message.="</table>";
	//$queryNumberOrders="SELECT COUNT(order_num) FROM orders WHERE user_id in $store AND )";

	//echo $message;	

	//Envoie du rapport COMPLET DE TOUS LES MAGASINS par courriel à Daniel, Karine, Amina et moi.
	$subject ="Rapport Global Incitatif $Partie pour la période [$date1-$date2]";

	
	$to_address		= $Report_Email;
	$from_address='donotreply@entrepotdelalunette.com';
	//echo 'Envoie du rapport en cours..<br>';
	//$response=office365_mail($to_address, $from_address, $subject, null, $message);
	//echo '<br>message sent';

	//echo 'resultat'  . $response;
	//echo "<br><br>success: " . $to_address ;
		$currentAcct = "  ";
		$currentCompany=" ";

	

}//Fin du For
$message_Admin	.= $message;	

//$Report_Email	= array('dbeaulieu@direct-lens.com','gbruneau@entrepotdelalunette.com','lbouthillier@entrepotdelalunette.com','dbeaulieu@direct-lens.com');//LIVE
$Report_Email	= array('dbeaulieu@direct-lens.com');//TEST
$to_address		= $Report_Email;
	

//Copie Admin
	$response=office365_mail($to_address, $from_address, 'Rapport Incentive EDLL Copie Admin', null, $message_Admin);


echo $message_Admin;

function CalculerIncentiveEDLL($Userid_Magasin,$Description_Magasin,$date1,$date2){
	/*
	Fonction avec 4 paramètres:
	1-User id du magasin Ex:88440
	2-Description du magasin Ex: 88440-Rideau
	3-Date de début
	4-Date de fin
	*/
	
	/* Cette fonction reçoit le numéro de magasin et affichera la liste des employés ainsi que les bonus qui leur est dû pour chaque 'Promo' évaluée	*/
	include('../connexion_hbc.inc.php');//Connexion DB HBO
	//echo "<br><br>Fonction CalculerIncentiveEDLL()<br>";
	
	$SelectedStoreTotal=0;//Initialiser le total par magasin
	$SommedesBonusMagasinActuel = 0;
	//Passer les différents employés qui ont fait des ventes durant la période évaluée
	$queryDistinctEmployeesofThisStore = 
	"SELECT distinct salesperson_id FROM orders
	WHERE order_date_processed BETWEEN '$date1' AND '$date2' 
	AND salesperson_id<>''	AND user_id IN $Userid_Magasin
	AND salesperson_id NOT IN ('21hbc-admin-all-access','Comptabilité Accounting','kl','entrepotqc')
	AND salesperson_id NOT LIKE '%accounting%'
	ORDER BY salesperson_id";
	
	//echo $queryDistinctEmployeesofThisStore.'<br>';
	
	$resultDistinctEmployees = mysqli_query($con, $queryDistinctEmployeesofThisStore) or die  ('I cannot select items because #2g: '. $queryDistinctEmployeesofThisStore . mysqli_error($con));
	
	
	//Initialiser le compteur ici 
	$TotalHDIOT  			= 0;//Initialise le compteur de bonus HD IOT
	$TotalPrecisionAdvance  = 0;//Initialise le compteur de bonus Progressif_Advance	
	$TotalIFREE				= 0;//Initialise le compteur de bonus Ifree	
	$TotalMAXIWIDE			= 0;//Initialise le compteur de bonus Maxiwide
	$TotalSVHD				= 0;//Initialise le compteur de bonus SV HD
	
	//Traitements
	$TotalARETC				= 0;//Initialise le compteur de bonus pour le  traitement AR+ETC
	$TotalMAXIIVUE			= 0;//Initialise le compteur de bonus pour le  traitement Maxiivue
	
	//Option supplémentaire
	$TotalPHOTO				= 0;//Initialise le compteur de bonus pour le  traitement Photo ou Transitions
	$TotalPOLAR				= 0;//Initialise le compteur de bonus pour le  traitement Polarized
	$TotalABC_WARRANTY		= 0;//Initialise le compteur de bonus pour le de Garantie ABC
	
	
	
	
			
			
	while ($DataDistinctEmployees = mysqli_fetch_array($resultDistinctEmployees,MYSQLI_ASSOC)){
		$TotalIncentiveForthisEmployee=0;//Initialise cette variable qui cummule le total par employé
							
			//Bonus 1 HD-IOT: 
			$Description_BonusA  	= "HD-IOT (1$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusA 		 	= 1;					//Définir la valeur de ce bonus: x$/Commande
			$user_idA	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusA 	= " (order_product_name like '%HD-IOT%') "; //Nom de produit à utiliser pour le Filtre			
			$Coating_A_FiltrerA 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveA 	= "SELECT count(order_num) as Nbr_Bonus_AtteintA FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idA
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusA
			AND  $Coating_A_FiltrerA 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			
			//echo '<br> $queryIncentiveA:'. $queryIncentiveA.'<br>';
			
			$resultIncentiveA 	= mysqli_query($con, $queryIncentiveA)	or die  ('<br><br>I cannot select items because 1a: <br><br>'. $queryIncentiveA . mysqli_error($con));
			$DataIncentiveA 	= mysqli_fetch_array($resultIncentiveA,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintA = $DataIncentiveA[Nbr_Bonus_AtteintA];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantA = $ValeurBonusA  * $Nbr_Bonus_AtteintA;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantA;
			//Fin partie Bonus A HORS PROMO		
			
			$SelectedStoreTotal    += $ResultatValeurBonusCourrantA;
			$TotalHDIOT			   += $ResultatValeurBonusCourrantA;

		
		//Bonus B: 
			//Precision Advance
			$Description_BonusB  	= "Precision Advance (2$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusB 		 	= 2;					//Définir la valeur de ce bonus: x$/Commande
			$user_idB	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusB 	= " (order_product_name like '%Precision advance%') "; //Nom de produit à utiliser pour le Filtre	
			
			$Coating_A_FiltrerB 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveB 	= "SELECT count(order_num) as Nbr_Bonus_AtteintB FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idB
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusB
			AND  $Coating_A_FiltrerB 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			
			//echo '<br> $queryIncentiveB:'. $queryIncentiveB.'<br>';
			$resultIncentiveB 	= mysqli_query($con, $queryIncentiveB)	or die  ('<br><br>I cannot select items because 1b: <br><br>'. $queryIncentiveB . mysqli_error($con));
			$DataIncentiveB 	= mysqli_fetch_array($resultIncentiveB,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintB = $DataIncentiveB[Nbr_Bonus_AtteintB];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantB = $ValeurBonusB  * $Nbr_Bonus_AtteintB;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantB;
	

		$SelectedStoreTotal    +=   $ResultatValeurBonusCourrantB;
		$TotalPrecisionAdvance +=  $ResultatValeurBonusCourrantB;	
		
		
		
		
		
		
		//Bonus C: 
			//iFree
			$Description_BonusC  	= "iFree (3$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusC 		 	= 3;					//Définir la valeur de ce bonus: x$/Commande
			$user_idC	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusC 	= " (order_product_name like '%iFree%') "; //Nom de produit à utiliser pour le Filtre	
			
			$Coating_A_FiltrerC 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveC 	= "SELECT count(order_num) as Nbr_Bonus_AtteintC FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idC
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusC
			AND  $Coating_A_FiltrerC
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			
			//echo '<br> $queryIncentiveC:'. $queryIncentiveC.'<br>';
			$resultIncentiveC 	= mysqli_query($con, $queryIncentiveC)	or die  ('<br><br>I cannot select items because 1b: <br><br>'. $queryIncentiveC . mysqli_error($con));
			$DataIncentiveC 	= mysqli_fetch_array($resultIncentiveC,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintC = $DataIncentiveC[Nbr_Bonus_AtteintC];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantC = $ValeurBonusC  * $Nbr_Bonus_AtteintC;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantC;
	

		$SelectedStoreTotal     +=   $ResultatValeurBonusCourrantC;
		$TotalIFREE 			+=   $ResultatValeurBonusCourrantC;	
		
		
		
		
		
		
		
		//Bonus D: 
			//Maxiwide
			$Description_BonusD  	= "Maxiwide (5$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusD 		 	= 5;					//Définir la valeur de ce bonus: x$/Commande
			$user_idD	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusD	= " (order_product_name like '%Maiwide%') "; //Nom de produit à utiliser pour le Filtre	
			
			$Coating_A_FiltrerD 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveD 	= "SELECT count(order_num) as Nbr_Bonus_AtteintD FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idD
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusD
			AND  $Coating_A_FiltrerD
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			
			//echo '<br> $queryIncentiveD:'. $queryIncentiveD.'<br>';
			$resultIncentiveD 	= mysqli_query($con, $queryIncentiveD)	or die  ('<br><br>I cannot select items because 1b: <br><br>'. $queryIncentiveD . mysqli_error($con));
			$DataIncentiveD 	= mysqli_fetch_array($resultIncentiveD,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintD = $DataIncentiveD[Nbr_Bonus_AtteintD];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantD = $ValeurBonusD  * $Nbr_Bonus_AtteintD;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantD;
	

		$SelectedStoreTotal     +=   $ResultatValeurBonusCourrantD;
		$TotalMAXIWIDE 			+=   $ResultatValeurBonusCourrantD;	
		
		
		
		
		
		//Bonus E: 
			//SV HD
			$Description_BonusE  	= "SV HD (2$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusE 		 	= 2;					//Définir la valeur de ce bonus: x$/Commande
			$user_idE	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusE	= " (order_product_name like '%solotech%') "; //Nom de produit à utiliser pour le Filtre	
			
			$Coating_A_FiltrerE 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveE 	= "SELECT count(order_num) as Nbr_Bonus_AtteintE FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idE
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusE
			AND  $Coating_A_FiltrerE
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			
			//echo '<br> $queryIncentiveE:'. $queryIncentiveE.'<br>';
			$resultIncentiveE 	= mysqli_query($con, $queryIncentiveE)	or die  ('<br><br>I cannot select items because 1e: <br><br>'. $queryIncentiveE . mysqli_error($con));
			$DataIncentiveE 	= mysqli_fetch_array($resultIncentiveE,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintE = $DataIncentiveE[Nbr_Bonus_AtteintE];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantE = $ValeurBonusE  * $Nbr_Bonus_AtteintE;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantE;
	

		$SelectedStoreTotal     +=   $ResultatValeurBonusCourrantE;
		$TotalSVHD 				+=   $ResultatValeurBonusCourrantE;	
		
		
		
		
		
		
		
		
		//Bonus F: 
			//AR+ETC
			$Description_BonusF  	= "AR+ETC (2$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusF 		 	= 2;					//Définir la valeur de ce bonus: x$/Commande
			$user_idF	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusF	= " order_product_coating IN ('AR Backside','Dream AR','SPC','SPC Backside') "; //Nom de produit à utiliser pour le Filtre	
			
			$Coating_A_FiltrerF 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveF 	= "SELECT count(order_num) as Nbr_Bonus_AtteintF FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idF
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusF
			AND  $Coating_A_FiltrerF
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			
			//echo '<br> $queryIncentiveF:'. $queryIncentiveF.'<br>';
			$resultIncentiveF 	= mysqli_query($con, $queryIncentiveF)	or die  ('<br><br>I cannot select items because 1e: <br><br>'. $queryIncentiveF . mysqli_error($con));
			$DataIncentiveF 	= mysqli_fetch_array($resultIncentiveF,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintF = $DataIncentiveF[Nbr_Bonus_AtteintF];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantF = $ValeurBonusF  * $Nbr_Bonus_AtteintF;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantF;
	

		$SelectedStoreTotal     	+=   $ResultatValeurBonusCourrantF;
		$TotalARETC 				+=   $ResultatValeurBonusCourrantF;	
		
		
		
		
		
		//Bonus G: 
			//Maxiivue II
			$Description_BonusG  	= "Maxivue II (3$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusG 		 	= 3;					//Définir la valeur de ce bonus: x$/Commande
			$user_idG	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusG	= " order_product_coating IN ('MaxiVue2','MaxiVue2 Backside') "; //Nom de produit à utiliser pour le Filtre	
			
			$Coating_A_FiltrerG 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveG 	= "SELECT count(order_num) as Nbr_Bonus_AtteintG FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idG
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusG
			AND  $Coating_A_FiltrerG
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			
			//echo '<br> $queryIncentiveG:'. $queryIncentiveF.'<br>';
			$resultIncentiveG 	= mysqli_query($con, $queryIncentiveG)	or die  ('<br><br>I cannot select items because 1e: <br><br>'. $queryIncentiveG . mysqli_error($con));
			$DataIncentiveG 	= mysqli_fetch_array($resultIncentiveG,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintG = $DataIncentiveG[Nbr_Bonus_AtteintG];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantG = $ValeurBonusG  * $Nbr_Bonus_AtteintG;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantG;
	

		$SelectedStoreTotal     	+=   $ResultatValeurBonusCourrantG;
		$TotalARETC 				+=   $ResultatValeurBonusCourrantG;	
		
		
		
		
		
		
		//Bonus H: 
			//Photo
			$Description_BonusH  	= "Photo (3$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusH 		 	= 3;					//Définir la valeur de ce bonus: x$/Commande
			$user_idH	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusH	= " order_product_photo NOT IN ('None') "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerH 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveH 	= "SELECT count(order_num) as Nbr_Bonus_AtteintH FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idH
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusH
			AND  $Coating_A_FiltrerH
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			
			//echo '<br> $queryIncentiveH:'. $queryIncentiveF.'<br>';
			$resultIncentiveH 	= mysqli_query($con, $queryIncentiveH)	or die  ('<br><br>I cannot select items because 1e: <br><br>'. $queryIncentiveH . mysqli_error($con));
			$DataIncentiveH 	= mysqli_fetch_array($resultIncentiveH,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintH = $DataIncentiveH[Nbr_Bonus_AtteintH];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantH = $ValeurBonusH  * $Nbr_Bonus_AtteintH;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantH;
	

		$SelectedStoreTotal     	+=   $ResultatValeurBonusCourrantH;
		$TotalPHOTO 				+=   $ResultatValeurBonusCourrantH;
		
		
		
		
		
		//Bonus I: 
			//Polarized
			$Description_BonusI  	= "Polarized (3$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusI 		 	= 3;					//Définir la valeur de ce bonus: x$/Commande
			$user_idI	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusI	= " order_product_polar NOT IN ('None') "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerI 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveI 	= "SELECT count(order_num) as Nbr_Bonus_AtteintI FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idI
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusI
			AND  $Coating_A_FiltrerI
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			
			//echo '<br> $queryIncentiveI:'. $queryIncentiveF.'<br>';
			$resultIncentiveI 	= mysqli_query($con, $queryIncentiveI)	or die  ('<br><br>I cannot select items because 1e: <br><br>'. $queryIncentiveI . mysqli_error($con));
			$DataIncentiveI 	= mysqli_fetch_array($resultIncentiveI,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintI = $DataIncentiveI[Nbr_Bonus_AtteintI];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantI = $ValeurBonusI  * $Nbr_Bonus_AtteintI;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantI;
	

		$SelectedStoreTotal     	+=   $ResultatValeurBonusCourrantI;
		$TotalPOLAR 				+=   $ResultatValeurBonusCourrantI;
		
		
		
		
		
		//Bonus J: 
			//ABC WARRANTY
			$Description_BonusJ  	= "ABC (3$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusJ 		 	= 3;					//Définir la valeur de ce bonus: x$/Commande
			$user_idJ	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusJ	= " warranty like '%Extended Warranty%'"; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerJ 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveJ 	= "SELECT count(order_num) as Nbr_Bonus_AtteintJ FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idJ
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusJ
			AND  $Coating_A_FiltrerJ
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			
			//echo '<br> $queryIncentiveJ:'. $queryIncentiveJ.'<br>';
			$resultIncentiveJ 	= mysqli_query($con, $queryIncentiveJ)	or die  ('<br><br>I cannot select items because 1e: <br><br>'. $queryIncentiveJ . mysqli_error($con));
			$DataIncentiveJ 	= mysqli_fetch_array($resultIncentiveJ,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintJ = $DataIncentiveJ[Nbr_Bonus_AtteintJ];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantJ = $ValeurBonusJ  * $Nbr_Bonus_AtteintJ;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantJ;
	

		$SelectedStoreTotal     	+=   $ResultatValeurBonusCourrantJ;
		$TotalABC_WARRANTY 			+=   $ResultatValeurBonusCourrantJ;
		
	
		
		
		//K: TOTAL ORDERS 
			$user_idK	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			//La requête	
			$queryIncentiveK 	= "SELECT count(order_num) as Nbr_Bonus_AtteintK FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idK
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			
			//echo '<br> $queryIncentiveK:'. $queryIncentiveK.'<br>';
			$resultIncentiveK 	= mysqli_query($con, $queryIncentiveK)	or die  ('<br><br>I cannot select items because 1e: <br><br>'. $queryIncentiveK . mysqli_error($con));
			$DataIncentiveK 	= mysqli_fetch_array($resultIncentiveK,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintK = $DataIncentiveK[Nbr_Bonus_AtteintK];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantK = $ValeurBonusK  * $Nbr_Bonus_AtteintK;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantK;
	

		$TotalORDERS		+=   $ResultatValeurBonusCourrantK;
		
		
		
		
		
		//L: Redos
			$user_idL	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			//La requête	
			$queryIncentiveL 	= "SELECT count(order_num) as Nbr_Bonus_AtteintL FROM orders
			WHERE redo_order_num IS NOT NULL 
			AND redo_reason_id in (16,31,39,46,52,53,57,63,69)
			AND user_id IN $user_idL
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			
			//echo '<br> $queryIncentiveL:'. $queryIncentiveL.'<br>';
			$resultIncentiveL 	= mysqli_query($con, $queryIncentiveL)	or die  ('<br><br>I cannot select items because 1e: <br><br>'. $queryIncentiveL . mysqli_error($con));
			$DataIncentiveL 	= mysqli_fetch_array($resultIncentiveL,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintL = $DataIncentiveL[Nbr_Bonus_AtteintL];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantL = $ValeurBonusL  * $Nbr_Bonus_AtteintL;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantL;
	

		$TotalREDOS		+=   $ResultatValeurBonusCourrantL;
		/////////////////////
			
		//Afficher les résultats
			
		$TotalBonusA = $ResultatValeurBonusCourrantA;
		$TotalBonusB = $ResultatValeurBonusCourrantB;
		$TotalBonusC = $ResultatValeurBonusCourrantC;
		$TotalBonusD = $ResultatValeurBonusCourrantD;
		$TotalBonusE = $ResultatValeurBonusCourrantE;
		$TotalBonusF = $ResultatValeurBonusCourrantF;
		$TotalBonusG = $ResultatValeurBonusCourrantG;
		$TotalBonusH = $ResultatValeurBonusCourrantH;
		$TotalBonusI = $ResultatValeurBonusCourrantI;
		$TotalBonusJ = $ResultatValeurBonusCourrantJ;
		$TotalBonusK = $ResultatValeurBonusCourrantK;
		$TotalBonusL = $ResultatValeurBonusCourrantL;
		
		
		$message.= "<tr>
						<td bgcolor=\"#C8C8C8\" align=\"center\">$Description_Magasin</td>
						<td bgcolor=\"#C8C8C8\" align=\"center\">". $DataDistinctEmployees[salesperson_id]. "</td>";
		
		
		//Afficher les résultats
		//HD-IOT
		//$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintA . " x $ValeurBonusA$ ="."$ResultatValeurBonusCourrantA$</td>";
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintA . "</td>";
		
		//Precision Advance
		//$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintB . " x $ValeurBonusB$ ="."$ResultatValeurBonusCourrantB$</td>";
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintB . "</td>";
		
		//iFree
		//$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintC . " x $ValeurBonusC$ ="."$ResultatValeurBonusCourrantC$</td>";
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintC . "</td>";
		
		//Maxiwide
		//$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintD . " x $ValeurBonusD$ ="."$ResultatValeurBonusCourrantD$</td>";
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintD . "</td>";
		
		//SV HD
		//$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintE . " x $ValeurBonusE$ ="."$ResultatValeurBonusCourrantE$</td>";
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintE . "</td>";
		
		//AR+ETC
		//$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintF . " x $ValeurBonusF$ ="."$ResultatValeurBonusCourrantF$</td>";
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintF . "</td>";
		

		//Maxiivue II
		//$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintG . " x $ValeurBonusG$ ="."$ResultatValeurBonusCourrantG$</td>";
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintG . "</td>";
		
		//Photo/Transitions
		//$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintH . " x $ValeurBonusH$ ="."$ResultatValeurBonusCourrantH$</td>";
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintH . "</td>";
		
		//Polarized
		//$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintI . " x $ValeurBonusI$ ="."$ResultatValeurBonusCourrantI$</td>";
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintI . "</td>";
		
		//ABC WARRANTY
		//$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintJ . " x $ValeurBonusJ$ ="."$ResultatValeurBonusCourrantJ$</td>";
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintJ . "</td>";
		
		
		//TOTAL ORDERS
		$message.= 		"<td bgcolor=\"#f3678b\" align=\"center\">". $Nbr_Bonus_AtteintK . " "."</td>";
		
		
		//REDOS
		$message.= 		"<td bgcolor=\"#f3678b\" align=\"center\">". $Nbr_Bonus_AtteintL . " "."</td>";
		
		
		if (($Nbr_Bonus_AtteintL<>0)&& ($Nbr_Bonus_AtteintK<>0)){
			$PourcentageReprise = ($Nbr_Bonus_AtteintL/$Nbr_Bonus_AtteintK)*100;
		}else{
			$PourcentageReprise = 0;
		}
		//% REDOS
		$PourcentageReprise = number_format($PourcentageReprise, 2);
		$message.= 		"<td bgcolor=\"#f3678b\" align=\"center\">". $PourcentageReprise . "% "."</td>";
		
		
		//SUB-TOTAL INCENTIVE
		$message.= 		"<td bgcolor=\"#C8C8C8\" align=\"center\">CAD ". $TotalIncentiveForthisEmployee . "</td>";
		
		
		//Impact du pourcentage de reprise, 3 options: 
		//A)<3% = +20%  
		//B)3 à 5%: aucun impact 
		//C)>5%:-20%
		
		if ($PourcentageReprise<3.01){
			$CalculAAjouterAuBonus	=  0.15*$TotalIncentiveForthisEmployee;//15% de la valeur du bonus sera AJOUTÉ à celui-ci
			$CalculAAjouterAuBonus = number_format($CalculAAjouterAuBonus, 2);
			$ImpactReprise = '+ CAD '. $CalculAAjouterAuBonus;
			$TotalEmployeeMoinsReprise = $TotalIncentiveForthisEmployee + $CalculAAjouterAuBonus ;
		}elseif (($PourcentageReprise>3.01) && ($PourcentageReprise<5.01)){
			$CalculAAjouterAuBonus	=  0;
			$CalculAAjouterAuBonus = number_format($CalculAAjouterAuBonus, 2);
			$ImpactReprise = '';
			$TotalEmployeeMoinsReprise = $TotalIncentiveForthisEmployee  ;
		}elseif($PourcentageReprise>5){//END IF
			$CalculARetirerAuBonus	=  0.2*$TotalIncentiveForthisEmployee;//20% de la valeur du bonus sera RETIRÉ de ce bonus
			$CalculARetirerAuBonus = number_format($CalculARetirerAuBonus, 2);
			$ImpactReprise = '- CAD '. $CalculARetirerAuBonus;
			$TotalEmployeeMoinsReprise = $TotalIncentiveForthisEmployee - $CalculARetirerAuBonus ;
		}//END IF
		
		$CalculAAjouterAuBonus = number_format($ImpactReprise, 2);
		$message.= 		"<td bgcolor=\"#C8C8C8\" align=\"center\">". $ImpactReprise . "</td>";
		
		/*if ($CalculARetirerAuBonus>0){
			
		}else{
			$TotalEmployeeMoinsReprise = $TotalIncentiveForthisEmployee - $CalculARetirerAuBonus ;
		}*/
		$TotalEmployeeMoinsReprise = number_format($TotalEmployeeMoinsReprise, 2);
		
		
		$SoixantePourCent = 0.6 * $TotalEmployeeMoinsReprise;
		$SoixantePourCent = number_format($SoixantePourCent, 2);
		
		$QuarantePourCent = 0.4 * $TotalEmployeeMoinsReprise;
		$QuarantePourCent = number_format($QuarantePourCent, 2);
		
		$message.= 		"<td bgcolor=\"#C8C8C8\" align=\"center\">". $TotalEmployeeMoinsReprise  . "$</td>";
		$message.= 		"<td bgcolor=\"#C8C8C8\" align=\"center\">". $SoixantePourCent  . "$</td>";
		$message.= 		"<td bgcolor=\"#C8C8C8\" align=\"center\">". $QuarantePourCent  . "$</td>";
		
		//$SommedesBonusMagasinActuel = $SommedesBonusMagasinActuel + $TotalIfreePlusAdvance  + $TotalProgressifAdvance;
		//$SommedesBonusMagasinActuel = $TotalIfreePlusAdvance  + $TotalProgressifAdvance;
		//echo '<br><br>$SommedesBonusMagasinActuel:' . $SommedesBonusMagasinActuel;
		
		//$message.= "<th bgcolor=\"#DAE0F2\"  align=\"center\">". $TotalIncentiveForthisEmployee ."$</th>";
		
	}//End While
	
	
	$SelectedStoreTotal = number_format($SelectedStoreTotal, 2);

	$SommedesBonusMagasinActuel = money_format('%.2n',$SommedesBonusMagasinActuel);
	$TotalIfreePlusAdvance = money_format('%.2n',$TotalIfreePlusAdvance);
	$TotalProgressifAdvance = money_format('%.2n',$TotalProgressifAdvance);

	
 
	
	//echo  $message;
	return $message;
	
	
}//END FUNCTION CalculerIncentiveEDLL
		
$time_start  = microtime(true);	

echo 'Rapport générés et envoyés aux courriels programmés, si vous ne l\'avez pas reçu, svp créez un ticket';


?>
