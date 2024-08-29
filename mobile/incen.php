<?php 
header('Content-type: text/html; charset=UTF-8');
/*
ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//error_reporting(E_WARNING);


$date1       = date("Y-m-d");
$date2       = date("Y-m-d");

$date1   	= $_POST[date1];
$date2     	= $_POST[date2];


//Date du rapport
$ladatedhier  = mktime(0,0,0,date("m"),date("d")-6,date("Y"));
$date1     	  = date("Y-m-d", $ladatedhier);


$ladate  	= mktime(0,0,0,date("m"),date("d"),date("Y"));
$date2 = date("Y-m-d", $ladate);


//DATES HARD CODÉS MANUELLE
//$date1        = "2023-10-10";
//$date2        = "2023-10-15";

include('../sec_connectEDLL.inc.php');
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

$LargeurColspanTableau =5;	//Nombre de TD dans le tableau
$WidthTableau = "100%";		//Pixels

//EDLL.CA PRODUCTION PART

//Prepare email 

//Paramètres communs
$from_address='donotreply@entrepotdelalunette.com';


for ($i = 1; $i <= 20 ; $i++) {
		//echo '<br> Magasin: '. $i;
		echo '<br>Passe dans case :'. $i;
		switch($i){
			case  1: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('chicoutimi')";    	$Partie = 'Chicoutimi';	 	$send_to_address = array('fdjibrilla@entrepotdelalunette.com');break;
			
			
			case  2: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('levis')";    		$Partie = 'Lévis';			$send_to_address = array('fdjibrilla@entrepotdelalunette.com');break;
			
			
			case  3:  
			//include('../sec_connectEDLL.inc.php');	
			$Userid =  " ('entrepotquebec')"; $Partie = 'Québec';			$send_to_address = array('fdjibrilla@entrepotdelalunette.com');break;
			
			
			case  4:  
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('entrepotdr')";    	$Partie = 'Drummondville';	$send_to_address = array('fdjibrilla@entrepotdelalunette.com');break;
			
			
			case  5: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('granby')";    		$Partie = 'Granby';	  		$send_to_address = array('fdjibrilla@entrepotdelalunette.com');break;
			
			
			case  6:  
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('sherbrooke')";    	$Partie = 'Sherbrooke';	  	$send_to_address = array('fdjibrilla@entrepotdelalunette.com');break;
			
			
			case  7:  
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('entrepotifc')";    $Partie = 'Trois-Rivières';	$send_to_address = array('fdjibrilla@entrepotdelalunette.com');break;
		
			case  8: 
			//include_once('../connexion_hbc.inc.php');
			$Userid =  "('88666')";    		$Partie = 'Griffé Trois-Rivières';	$send_to_address = array('fdjibrilla@entrepotdelalunette.com');
			break;
				
			case  9:  
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('gatineau')";		$Partie = 'Gatineau';	 	$send_to_address = array('fdjibrilla@entrepotdelalunette.com');break;
			
			case  10: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('laval')";    		$Partie = 'Laval';	  		$send_to_address = array('fdjibrilla@entrepotdelalunette.com');break;
		
			case  11: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('longueuil')";    	$Partie = 'Longueuil';	  	$send_to_address = array('fdjibrilla@entrepotdelalunette.com');break;
			
			case  12: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('stjerome')";    	$Partie = 'Saint-Jérôme';	$send_to_address = array('fdjibrilla@entrepotdelalunette.com');break;
			
			case  13: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('terrebonne')";    	$Partie = 'Terrebonne';		$send_to_address = array('fdjibrilla@entrepotdelalunette.com');break;
			
			case  14: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('edmundston')";    	$Partie = 'Edmundston';		$send_to_address = array('fdjibrilla@entrepotdelalunette.com');break;
			
			case  15: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('warehousehal')";   $Partie = 'Halifax';		$send_to_address = array('fdjibrilla@entrepotdelalunette.com');break;
			
			case  16: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('vaudreuil')";  	$Partie = 'Vaudreuil';		$send_to_address = array('fdjibrilla@entrepotdelalunette.com');break;
			
			case  17: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('sorel')";  		$Partie = 'Sorel';			$send_to_address = array('fdjibrilla@entrepotdelalunette.com');break;
			
			case  18:
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('moncton')";  		$Partie = 'Moncton';		$send_to_address = array('fdjibrilla@entrepotdelalunette.com');break;
			
			case  19:
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('fredericton')";  		$Partie = 'Fredericton';		$send_to_address = array('fdjibrilla@entrepotdelalunette.com');break; 

			case  20:
				//include('../sec_connectEDLL.inc.php');
				$Userid =  " ('stjohn')";  		$Partie = 'St-John';		$send_to_address = array('fdjibrilla@entrepotdelalunette.com');break; 
		}//End Switch
		
	//$TotalIfreePlusAdvance  = 0;//Initialise le compteur de bonus Ifree_Plus_Advance
	//$TotalProgressifAdvance = 0;//Initialise le compteur de bonus Progressif_Advance	
	//$TotalPrecisionAI 		= 0;//Initialise le compteur de bonus Precision AI	
	//$TotalPrecisionMaxi		= 0;//Initialise le compteur de bonus Maxiwide	
		
		
$message_Halifax="";

$message_Halifax="<html>
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
		<th  colspan=\"$LargeurColspanTableau\"><h3>Rapport Incitatifs $Partie $date1-$date2 </h3></th>
	</tr>";
	

	$store	 			= $Userid;		
	$StoreDescription	= $Partie;
	$message_Halifax.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Succursale: $StoreDescription</h3></th></tr>";
	$message_Halifax.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
	$message_Halifax.="</table>";
	//$queryNumberOrders="SELECT COUNT(order_num) FROM orders WHERE user_id in $store AND )";

	//echo $message;	

	//Envoie du rapport COMPLET DE TOUS LES MAGASINS par courriel à Daniel, Karine, Amina et moi.
	$subject ="Rapport Incentive EDLL FF $Partie pour la période [$date1-$date2]";

	$Report_Email	= array('fdjibrilla@entrepotdelalunette.com');//LIVE
	$to_address		= $Report_Email;
	$from_address='donotreply@entrepotdelalunette.com';
	echo 'Envoie du rapport en cours..<br>';
	$response=office365_mail($to_address, $from_address, $subject , null,$message_Halifax );//$message_Admin
	
	$currentAcct = "  ";
	$currentCompany=" ";

	$message_Admin	.= $message_Halifax;	

		
	//$Report_Email= array('dbeaulieu@direct-lens.com','gbruneau@entrepotdelalunette.com','lbouthillier@entrepotdelalunette.com','dbeaulieu@direct-lens.com');//LIVE
	
	
	
/*	
	
	//999999999999999999999999999999999999999
	
	$store	 			= $Userid;		
	$StoreDescription	= $Partie;
	$message_Halifax.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Succursale: $StoreDescription</h3></th></tr>";
	$message_Halifax.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
	$message_Halifax.="</table>";
	//$queryNumberOrders="SELECT COUNT(order_num) FROM orders WHERE user_id in $store AND )";

	//echo $message;	

	//Envoie du rapport COMPLET DE TOUS LES MAGASINS par courriel à Daniel, Karine, Amina et moi.
	$subject ="Rapport Global Incitatif $Partie pour la periode [$date1-$date2]";
    $Report_Email	= array('fdjibrilla@entrepotdelalunette.com');//LIVE
	//$Report_Email	= array('fdjibrilla@entrepotdelalunette.com','dbeaulieu@direct-lens.com','gbruneau@entrepotdelalunette.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');
	$to_address		= $Report_Email;
	$to_address		= $Report_Email;
	$from_address='donotreply@entrepotdelalunette.com';
	echo 'Envoie du rapport en cours..<br>';
	$response=office365_mail($to_address, $from_address, $subject, null, $message_Halifax);
	//echo '<br>message sent';

	//echo 'resultat'  . $response;
	//echo "<br><br>success: " . $to_address ;
		
		$currentCompany=" ";

	$message_Admin	.= $message_Halifax;
	//00000000000000000000000000000000000
		*/
}//Fin du For
//Copie Admin



//echo $message_Admin;

function CalculerIncentiveEDLL($Userid_Magasin,$Description_Magasin,$date1,$date2){
	/*
	Fonction avec 4 paramètres:
	1-User id du magasin Ex:88440
	2-Description du magasin Ex: 88440-Rideau
	3-Date de début
	4-Date de fin
	*/
	
	/* Cette fonction reçoit le numéro de magasin et affichera la liste des employés ainsi que les bonus qui leur est dû pour chaque 'Promo' évaluée	*/
	if ($Userid_Magasin=="('88666')"){
		include('../connexion_hbc.inc.php');
		echo '<br>Inclue BD HBC<br>';
	}else{
		include('../sec_connectEDLL.inc.php');
		echo '<br>Inclue BD EDLL<br>';
	}
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
	//$TotalIfreePlusAdvance  = 0;//Initialise le compteur de bonus Ifree_Plus_Advance
	$TotalProgressifAdvance = 0;//Initialise le compteur de bonus Progressif_Advance
	$TotalPrecisionAI 		= 0;//Initialise le compteur de bonus Precision AI
	$TotalPrecisionMaxi 	= 0;//Initialise le compteur de bonus Maxiwide
	
	$message.= "
		<tr>
			<th align=\"center\">Employé</th>
			<!--th bgcolor=\"#e0c1b3\" align=\"center\">Vente d'Ifree Plus Advance</th-->
			<th bgcolor=\"#e0c1b3\" align=\"center\">Vente de Progressif Advance</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">Vente de Precision AI</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">Vente de Maxiwide</th>
			<th bgcolor=\"#DAE0F2\" align=\"center\">Total</th>
		</tr>";
	
	
			
			
	while ($DataDistinctEmployees = mysqli_fetch_array($resultDistinctEmployees,MYSQLI_ASSOC)){
		$TotalIncentiveForthisEmployee=0;//Initialise cette variable qui cummule le total par employé
		
			
			
			
	/*		//Bonus A: 
			//HORS PROMO SEULEMENT: [BEST]
			$Description_BonusA  	= "Advance (10$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusA 		 	= 10;					//Définir la valeur de ce bonus: x$/Commande
			$user_idA	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusA 	= " (order_product_name like '%ifree plus advance%') "; //Nom de produit à utiliser pour le Filtre			
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
			
			echo '<br> $queryIncentiveA:'. $queryIncentiveA.'<br>';
			
			$resultIncentiveA 	= mysqli_query($con, $queryIncentiveA)	or die  ('<br><br>I cannot select items because 1a: <br><br>'. $queryIncentiveA . mysqli_error($con));
			$DataIncentiveA 	= mysqli_fetch_array($resultIncentiveA,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintA = $DataIncentiveA[Nbr_Bonus_AtteintA];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantA = $ValeurBonusA  * $Nbr_Bonus_AtteintA;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantA;
			//Fin partie Bonus A HORS PROMO		
			
			$SelectedStoreTotal    += $ResultatValeurBonusCourrantA;
			$TotalIfreePlusAdvance += $ResultatValeurBonusCourrantA; */

		
		//Bonus B: 
			//HORS PROMO SEULEMENT: [BEST]
			$Description_BonusB  	= "Advance (10$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusB 		 	= 10;					//Définir la valeur de ce bonus: x$/Commande
			$user_idB	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			
			$NomProduitPourBonusB 	= " (order_product_name like '%Progressif advance%') "; //Nom de produit à utiliser pour le Filtre	
			
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
			
			echo '<br> $queryIncentiveB:'. $queryIncentiveB.'<br>';
			$resultIncentiveB 	= mysqli_query($con, $queryIncentiveB)	or die  ('<br><br>I cannot select items because 1b: <br><br>'. $queryIncentiveB . mysqli_error($con));
			$DataIncentiveB 	= mysqli_fetch_array($resultIncentiveB,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintB = $DataIncentiveB[Nbr_Bonus_AtteintB];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantB = $ValeurBonusB  * $Nbr_Bonus_AtteintB;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantB;
			//Fin partie Bonus C HORS PROMO		

		$SelectedStoreTotal    +=   $ResultatValeurBonusCourrantB;
		$TotalProgressifAdvance+=  $ResultatValeurBonusCourrantB;	
		
		
		
		
		
		//Bonus C: 
			$Description_BonusC  	= "Precision AI (10$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusC 		 	= 10;					//Définir la valeur de ce bonus: x$/Commande
			$user_idC	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			
			$NomProduitPourBonusC 	= " (order_product_name like '%Precision AI%') "; //Nom de produit à utiliser pour le Filtre	
			
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
			
			echo '<br> $queryIncentiveC:'. $queryIncentiveC.'<br>';
			$resultIncentiveC 	= mysqli_query($con, $queryIncentiveC)	or die  ('<br><br>I cannot select items because 1b: <br><br>'. $queryIncentiveC . mysqli_error($con));
			$DataIncentiveC 	= mysqli_fetch_array($resultIncentiveC,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintC = $DataIncentiveC[Nbr_Bonus_AtteintC];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantC = $ValeurBonusC  * $Nbr_Bonus_AtteintC;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantC;
			//Fin partie Bonus C HORS PROMO		

		$SelectedStoreTotal    +=   $ResultatValeurBonusCourrantC;
		$TotalPrecisionAI 	   +=   $ResultatValeurBonusCourrantC;	
			
	
	
	
			
		//Bonus D: 
			$Description_BonusD  	= "Maxiwide(10$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusD 		 	= 10;					//Définir la valeur de ce bonus: x$/Commande
			$user_idD	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			
			$NomProduitPourBonusD 	= " (order_product_name like '%maxiwide%') "; //Nom de produit à utiliser pour le Filtre	
			
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
			
			echo '<br> $queryIncentiveD:'. $queryIncentiveD.'<br>';
			$resultIncentiveD 	= mysqli_query($con, $queryIncentiveD)	or die  ('<br><br>I cannot select items because 1b: <br><br>'. $queryIncentiveD . mysqli_error($con));
			$DataIncentiveD 	= mysqli_fetch_array($resultIncentiveD,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintD = $DataIncentiveD[Nbr_Bonus_AtteintD];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantD = $ValeurBonusD  * $Nbr_Bonus_AtteintD;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantD;
			//Fin partie Bonus C HORS PROMO		

		$SelectedStoreTotal    +=   $ResultatValeurBonusCourrantD;
		$TotalPrecisionMaxi 	   +=   $ResultatValeurBonusCourrantD;	



	//Afficher les résultats
		$message.= "<tr><td align=\"center\">". $DataDistinctEmployees[salesperson_id]. "</td>";
				
	//	$TotalBonusA = $ResultatValeurBonusCourrantA;
		$TotalBonusB = $ResultatValeurBonusCourrantB;
		$TotalBonusC = $ResultatValeurBonusCourrantC;
		$TotalBonusD = $ResultatValeurBonusCourrantD;
		
		//Ifree Plus Advance
		//$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintA . " commandes x $ValeurBonusA$ ="."$ResultatValeurBonusCourrantA$</td>";
		
		
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintB . " commandes x $ValeurBonusB$ ="."$ResultatValeurBonusCourrantB$				
		</td>";
		
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintC . " commandes x $ValeurBonusC$ ="."$ResultatValeurBonusCourrantC$				
		</td>";
		
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintD . " commandes x $ValeurBonusD$ ="."$ResultatValeurBonusCourrantD$				
		</td>";
		

		
		//$SommedesBonusMagasinActuel = $SommedesBonusMagasinActuel + $TotalIfreePlusAdvance  + $TotalProgressifAdvance;
		$SommedesBonusMagasinActuel = $TotalIfreePlusAdvance  + $TotalProgressifAdvance + $TotalPrecisionAI+$TotalPrecisionMaxi;
		//echo '<br><br>$SommedesBonusMagasinActuel:' . $SommedesBonusMagasinActuel;
		$TotalIncentiveForthisEmployee = number_format($TotalIncentiveForthisEmployee, 2);
		$message.= "<th bgcolor=\"#DAE0F2\"  align=\"center\">". $TotalIncentiveForthisEmployee ."$</th>";
		
	}//End While
	
	
	
	
	
	
	
	
	
	
	//7777777777777777777777777777777777777777
	
		$SelectedStoreTotal = number_format($SelectedStoreTotal, 2);
	
	
	$queryNombreCommandePeriode = "SELECT COUNT(order_num) as NbrOriginales FROM ORDERS 
	WHERE order_date_processed BETWEEN '$date1' AND '$date2' 	AND user_id in $Userid_Magasin
	AND redo_order_num is null	";
	$ResultNbrCommande = mysqli_query($con, $queryNombreCommandePeriode) or die 	('I cannot select items because #1m: '. $queryNbrCommande . mysqli_error($con));
	$DataNbrCommandes = mysqli_fetch_array($ResultNbrCommande,MYSQLI_ASSOC);
	
	
	$queryReprise = "SELECT COUNT(order_num) as NbrReprises FROM ORDERS 
	WHERE order_date_processed BETWEEN '$date1' AND '$date2' 	AND user_id in $Userid_Magasin
	AND redo_order_num is NOT null	";
	$ResultNbrReprise = mysqli_query($con, $queryReprise) or die 	('I cannot select items because #1n: '. $queryNbrCommande . mysqli_error($con));
	$DataNbrReprise = mysqli_fetch_array($ResultNbrReprise,MYSQLI_ASSOC);
	
	
	$PourcentageReprise=  ($DataNbrReprise[NbrReprises]/$DataNbrCommandes[NbrOriginales]) * 100;
	$PourcentageReprise = money_format('%.2n',$PourcentageReprise);

	//Ajouter bonus si le % de reprises est inférieur a 5%, bonus de 200$ au magasin par mois, donc 50$ par semaine
	if ($PourcentageReprise<5.01){
		$SommedesBonusMagasinActuel = $SommedesBonusMagasinActuel+50;
	}//END IF
	$SommedesBonusMagasinActuel = money_format('%.2n',$SommedesBonusMagasinActuel);
	
	$message.= "<tr>
			<th colspan=\"4\" align=\"right\">Number of Original orders</th>
			<th>$DataNbrCommandes[NbrOriginales]</th>
			</tr>
			<tr>
			<th colspan=\"4\" align=\"right\">Number of redos</th>
			<th>$DataNbrReprise[NbrReprises]</th>
			</tr>
			<tr>
			<th colspan=\"4\" align=\"right\">Redo percentage</th>
			<th>$PourcentageReprise%</th>
			
		</tr>";
		
	
	
	
   $message.= "<tr>
			<th bgcolor=\"#EFE9AE\" colspan=\"4\" align=\"right\">TOTAL POUR $Description_Magasin :</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$SommedesBonusMagasinActuel$</th>
			
		</tr>";
	echo $Report_Email;
	echo $message;
	return $message;
	//777777777777777777777777777777777777777777777
	$SelectedStoreTotal = number_format($SelectedStoreTotal, 2);
	
	
	$queryNombreCommandePeriode = "SELECT COUNT(order_num) as NbrOriginales FROM ORDERS 
	WHERE order_date_processed BETWEEN '$date1' AND '$date2' 	AND user_id in $Userid_Magasin
	AND redo_order_num is null	";
	$ResultNbrCommande = mysqli_query($con, $queryNombreCommandePeriode) or die 	('I cannot select items because #2g: '. $queryNbrCommande . mysqli_error($con));
	$DataNbrCommandes = mysqli_fetch_array($ResultNbrCommande,MYSQLI_ASSOC);
	
	
	
	
	$queryReprise = "SELECT COUNT(order_num) as NbrReprises FROM ORDERS 
	WHERE order_date_processed BETWEEN '$date1' AND '$date2' 	AND user_id in $Userid_Magasin
	AND redo_order_num is NOT null	";
	$ResultNbrReprise = mysqli_query($con, $queryReprise) or die 	('I cannot select items because #2g: '. $queryNbrCommande . mysqli_error($con));
	$DataNbrReprise = mysqli_fetch_array($ResultNbrReprise,MYSQLI_ASSOC);
	
	
	
		
	$PourcentageReprise=  ($DataNbrReprise[NbrReprises]/$DataNbrCommandes[NbrOriginales]) * 100;
	$PourcentageReprise = money_format('%.2n',$PourcentageReprise);

	//Ajouter bonus si le % de reprises est inférieur a 5%, bonus de 200$ au magasin par mois, donc 50$ par semaine
	if ($PourcentageReprise<5.01){
		$SommedesBonusMagasinActuel = $SommedesBonusMagasinActuel+50;
	}//END IF
	

	
	$SommedesBonusMagasinActuel = money_format('%.2n',$SommedesBonusMagasinActuel);
	//$TotalIfreePlusAdvance = money_format('%.2n',$TotalIfreePlusAdvance);
	$TotalProgressifAdvance = money_format('%.2n',$TotalProgressifAdvance);
	$TotalPrecisionAI = money_format('%.2n',$TotalPrecisionAI);
	$TotalPrecisionMaxi = money_format('%.2n',$TotalPrecisionMaxi);
	
	
	$message.= "<tr>
			<th colspan=\"4\" align=\"right\">Number of Original orders</th>
			<th>$DataNbrCommandes[NbrOriginales]</th>
			</tr>
			<tr>
			<th colspan=\"4\" align=\"right\">Number of redos</th>
			<th>$DataNbrReprise[NbrReprises]</th>
			</tr>
			<tr>
			<th colspan=\"4\" align=\"right\">Redo percentage</th>
			<th>$PourcentageReprise%</th>
			
		</tr>";
		
	
	
	
   $message.= "<tr>
			<th bgcolor=\"#EFE9AE\" colspan=\"4\" align=\"right\">TOTAL POUR $Description_Magasin :</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$SommedesBonusMagasinActuel$</th>
			
		</tr>";
	echo $Report_Email;
	echo $message;


	
  /* $message.= "<tr>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\" align=\"right\">TOTAL POUR $Description_Magasin :</th>
			<!--th bgcolor=\"#EFE9AE\" colspan=\"1\">$TotalIfreePlusAdvance$</th-->
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$TotalProgressifAdvance$</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$TotalPrecisionAI$</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$TotalPrecisionMaxi$</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$SommedesBonusMagasinActuel$</th>
		</tr>";
	
	echo  $message; */
	return $message;
	
	
}//END FUNCTION CalculerIncentiveEDLL
		
$time_start  = microtime(true);	

echo 'Rapport générés et envoyés aux courriels programmés, si vous ne l\'avez pas reçu, svp créez un ticket';


?>
