<?php 
header('Content-type: text/html; charset=UTF-8');

echo "allo";
//ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//error_reporting(E_WARNING);

/*$date1       = date("Y-m-d");
$date2       = date("Y-m-d");

//$date1   	= $_POST[date1];
//$date2     	= $_POST[date2];


//Date du rapport
$ladatedhier  = mktime(0,0,0,date("m"),date("d")-6,date("Y"));
$date1     	  = date("Y-m-d", $ladatedhier);


$ladate  	= mktime(0,0,0,date("m"),date("d"),date("Y"));
$date2 = date("Y-m-d", $ladate);*/


//DATES HARD CODÉS MANUELLE
$date1        = "2023-03-22";
$date2        = "2023-03-28";


include('../sec_connectEDLL.inc.php');
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

$LargeurColspanTableau =10;	//Nombre de TD dans le tableau
$WidthTableau = "100%";		//Pixels

//EDLL.CA PRODUCTION PART

//Prepare email 

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
		<th  colspan=\"$LargeurColspanTableau\"><h3>Rapport Incitatifs Halifax $date1-$date2 </h3></th>
	</tr>";

//Paramètres communs
$from_address='donotreply@entrepotdelalunette.com';


for ($i = 1; $i <= 19 ; $i++) {
		//echo '<br> Magasin: '. $i;
		echo '<br>Passe dans case :'. $i;
		switch($i){
			case  1: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('chicoutimi')";    	$Partie = 'Chicoutimi';	 	$send_to_address = array('rapports@direct-lens.com');break;
			
			
			case  2: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('levis')";    		$Partie = 'Lévis';			$send_to_address = array('rapports@direct-lens.com');break;
			
			
			case  3:  
			//include('../sec_connectEDLL.inc.php');	
			$Userid =  " ('entrepotquebec')"; $Partie = 'Québec';			$send_to_address = array('rapports@direct-lens.com');break;
			
			
			case  4:  
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('entrepotdr')";    	$Partie = 'Drummondville';	$send_to_address = array('rapports@direct-lens.com');break;
			
			
			case  5: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('granby')";    		$Partie = 'Granby';	  		$send_to_address = array('rapports@direct-lens.com');break;
			
			
			case  6:  
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('sherbrooke')";    	$Partie = 'Sherbrooke';	  	$send_to_address = array('rapports@direct-lens.com');break;
			
			
			case  7:  
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('entrepotifc')";    $Partie = 'Trois-Rivières';	$send_to_address = array('rapports@direct-lens.com');break;
		
			case  8: 
			//include_once('../connexion_hbc.inc.php');
			$Userid =  "('88666')";    		$Partie = 'Griffé Trois-Rivières';	$send_to_address = array('rapports@direct-lens.com');
			break;
				
			case  9:  
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('gatineau')";		$Partie = 'Gatineau';	 	$send_to_address = array('rapports@direct-lens.com');break;
			
			case  10: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('laval')";    		$Partie = 'Laval';	  		$send_to_address = array('rapports@direct-lens.com');break;
		
			case  11: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('longueuil')";    	$Partie = 'Longueuil';	  	$send_to_address = array('rapports@direct-lens.com');break;
			
			case  12: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('stjerome')";    	$Partie = 'Saint-Jérôme';	$send_to_address = array('rapports@direct-lens.com');break;
			
			case  13: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('terrebonne')";    	$Partie = 'Terrebonne';		$send_to_address = array('rapports@direct-lens.com');break;
			
			case  14: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('edmundston')";    	$Partie = 'Edmundston';		$send_to_address = array('rapports@direct-lens.com');break;
			
			case  15: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('warehousehal')";   $Partie = 'Halifax';		$send_to_address = array('rapports@direct-lens.com');break;
			
			case  16: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('vaudreuil')";  	$Partie = 'Vaudreuil';		$send_to_address = array('rapports@direct-lens.com');break;
			
			case  17: 
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('sorel')";  		$Partie = 'Sorel';			$send_to_address = array('rapports@direct-lens.com');break;
			
			case  18:
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('moncton')";  		$Partie = 'Moncton';		$send_to_address = array('rapports@direct-lens.com');break;
			
			case  19:
			//include('../sec_connectEDLL.inc.php');
			$Userid =  " ('fredericton')";  		$Partie = 'Fredericton';		$send_to_address = array('rapports@direct-lens.com');break;
		}//End Switch
		
	//$TotalIfreePlusAdvance  = 0;//Initialise le compteur de bonus Ifree_Plus_Advance
	//$TotalProgressifAdvance = 0;//Initialise le compteur de bonus Progressif_Advance	
	//$TotalPrecisionAI 		= 0;//Initialise le compteur de bonus Precision AI	


//Début Halifax
$store				= $Userid;
$StoreDescription	= $Partie;
$message_Halifax.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Succursale:$StoreDescription</h3></th></tr>";
$message_Halifax.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//$queryNumberOrders="SELECT COUNT(order_num) FROM orders WHERE user_id in $store AND )";

echo $message;	

//Envoie du rapport COMPLET DE TOUS LES MAGASSINS par courriel à Daniel, Karine, Amina et moi.
$subject ="Rapport Global Incitatif Halifax pour la période [$date1-$date2]";

//$Report_Email	= array('dbeaulieu@direct-lens.com','gbruneau@entrepotdelalunette.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','thahn@direct-lens.com','halifax@opticalwarehouse.ca');//LIVE

$Report_Email	= array('fdjibrilla@entrepotdelalunette.com');//LIVE  

$to_address		= $Report_Email;
$from_address='donotreply@entrepotdelalunette.com';
echo 'Envoie du rapport en cours..<br>';
$response=office365_mail($to_address, $from_address, $subject, null, $message_Halifax);
echo '<br>message sent';

echo 'resultat'  . $response;
echo "<br><br>success: " . $to_address ;
	$currentAcct = "  ";
	$currentCompany=" ";


echo message_Halifax;


function CalculerIncentiveEDLL($Userid_Magasin,$Description_Magasin,$date1,$date2){
	/*
	Fonction avec 4 paramètres:
	1-User id du magasin Ex:88440
	2-Description du magasin Ex: 88440-Rideau
	3-Date de début
	4-Date de fin
	*/
	
	/* Cette fonction reçoit le numéro de magasin et affichera la liste des employés ainsi que les bonus qui leur est dû pour chaque 'Promo' évaluée	*/
	include('../sec_connectEDLL.inc.php');
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
	
	
	$message.= "
		<tr>
			<th align=\"center\">&nbsp;</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\" colspan=\"4\">PROGRESSIVE</th>
			<th bgcolor=\"#B15E6C\" align=\"center\" colspan=\"4\">OTHERS</th>
			<th bgcolor=\"#DAE0F2\" align=\"center\" >TOTAL</th>
		</tr>";
	
	
	$message.= "
		<tr>
			<th align=\"center\">Employé</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">[BETTER]<br>Ultimate /Alpha HD/<br>Precision+</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">[BEST]<br>Alpha 4D /iFree /Precision 360/<br>iAction Prog /Impression</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">[PREMIUM]<br>Maxiwide</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">[OTHER]<br>Office HD/iRelax</th>
			<th bgcolor=\"#B15E6C\" align=\"center\">Temple to Temple Warranty</th>
			<th bgcolor=\"#B15E6C\" align=\"center\">AR+ETC</th>
			<th bgcolor=\"#B15E6C\" align=\"center\">XLR/HD AR/LR</th>
			<th bgcolor=\"#B15E6C\" align=\"center\">Trans/Polar</th>
			<th bgcolor=\"#DAE0F2\" align=\"center\">Total</th>
		</tr>";
	
	
	while ($DataDistinctEmployees = mysqli_fetch_array($resultDistinctEmployees,MYSQLI_ASSOC)){
		$TotalIncentiveForthisEmployee=0;//Initialise cette variable qui cummule le total par employé
		
		
			//Bonus B 
			//HORS PROMO SEULEMENT: [BETTER]
			$Description_BonusB  	= "ALPHA HD/ULTIMATE (1$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusB 		 	= 1;							//Définir la valeur de ce bonus: x$/Commande
			$user_idB 	 		 	= $Userid_Magasin;				//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusB 	= "  "; //Nom de produit à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveB 	= "SELECT count(order_num) as Nbr_Bonus_AtteintB FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idB
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled')
			AND (order_product_name LIKE '%alpha hd%' AND order_product_name not like '%4k%')  
			OR
			redo_order_num IS NULL 
			AND user_id IN $user_idB
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled')
			AND (order_product_name LIKE '%ultimate%') 
			OR
			redo_order_num IS NULL 
			AND user_id IN $user_idB
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled')
			AND (order_product_name LIKE '%Precision 1%') 
			ORDER BY order_product_name";
			//echo $queryIncentiveB .'<br><br>';
			
			$resultIncentiveB 	= mysqli_query($con, $queryIncentiveB)	or die  ('<br><br>I cannot select items because 1b: <br><br>'. $queryIncentiveB . mysqli_error($con));
			$DataIncentiveB 	= mysqli_fetch_array($resultIncentiveB,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintB = $DataIncentiveB[Nbr_Bonus_AtteintB];		
			//Calcul du bonus total mérité  par CET Employé 
			$ResultatValeurBonusCourrantB = $ValeurBonusB  * $Nbr_Bonus_AtteintB;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantB;
			//Fin partie Bonus B HORS PROMO
			
			//PROMO SEULEMENT: [BETTER]
			$Description_BonusB_DUO  	= "ALPHA HD/ULTIMATE (1$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusB_DUO 		 	= 0.5;							//Définir la valeur de ce bonus: x$/Commande
			$user_idB_DUO 	 		 	= $Userid_Magasin;				//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusB_DUO 	= " order_product_name LIKE '%Promo duo 4k%'  "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerB_DUO	 	=  " 1=1 "; 					//Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveB_DUO 	= "SELECT count(order_num) as Nbr_Bonus_AtteintB_DUO FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idB
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusB_DUO
			AND  $Coating_A_FiltrerB_DUO
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";			
			$resultIncentiveB_DUO 	= mysqli_query($con, $queryIncentiveB_DUO)	or die  ('<br><br>I cannot select items because 1b: <br><br>'. $queryIncentiveB_DUO . mysqli_error($con));
			$DataIncentiveB_DUO 	= mysqli_fetch_array($resultIncentiveB_DUO,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintB_DUO = $DataIncentiveB_DUO[Nbr_Bonus_AtteintB_DUO];		
			//Calcul du bonus total mérité  par CET Employé 
			$ResultatValeurBonusCourrantB_DUO = $ValeurBonusB_DUO  * $Nbr_Bonus_AtteintB_DUO;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantB_DUO;
			//Fin partie Bonus B HORS PROMO
			//Fin Bonus B
 		
		
		
			//Bonus C: 
			//HORS PROMO SEULEMENT: [BEST]
			$Description_BonusC  	= "Alpha 4D/iFree/UPrecision 360/iAction Progressive/Impression (2$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusC 		 	= 2;					//Définir la valeur de ce bonus: x$/Commande
			$user_idC	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			
			$NomProduitPourBonusC 	= " (order_product_name like '%ifree%' OR order_product_name like '%alpha 4d%'
										OR order_product_name like '%impression%'  OR order_product_name like '%Individualise iAction%'   OR order_product_name like '%360%') "; //Nom de produit à utiliser pour le Filtre	
			
			$Coating_A_FiltrerC 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveC 	= "SELECT count(order_num) as Nbr_Bonus_AtteintC FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idC
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusC
			AND  $Coating_A_FiltrerC 
			AND order_product_name not like '%duo%'
			AND order_product_name not like '%promo%'
			AND order_product_name not like '%SV%'
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveC 	= mysqli_query($con, $queryIncentiveC)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveC . mysqli_error($con));
			$DataIncentiveC 	= mysqli_fetch_array($resultIncentiveC,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintC = $DataIncentiveC[Nbr_Bonus_AtteintC];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantC = $ValeurBonusC  * $Nbr_Bonus_AtteintC;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantC;
			//Fin partie Bonus C HORS PROMO		
			
			//PROMO SEULEMENT: [BEST]
			$Description_BonusC_DUO  	= "Alpha 4D/iFree/Precision 360/iAction Progressive/Impression (2$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusC_DUO 		 	= 1;					//Définir la valeur de ce bonus: x$/Commande
			$user_idC_DUO	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusC_DUO 	= " ( order_product_name like '%Promo Duo Alpha 4D%' ) "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerC_DUO 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveC_DUO 	= "SELECT count(order_num) as Nbr_Bonus_AtteintC_DUO FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idC
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusC_DUO
			AND  $Coating_A_FiltrerC_DUO 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name"; 
			$resultIncentiveC_DUO 	= mysqli_query($con, $queryIncentiveC_DUO)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveC_DUO . mysqli_error($con));
			$DataIncentiveC_DUO 	= mysqli_fetch_array($resultIncentiveC_DUO,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintC_DUO = $DataIncentiveC_DUO[Nbr_Bonus_AtteintC_DUO];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantC_DUO = $ValeurBonusC_DUO  * $Nbr_Bonus_AtteintC_DUO;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantC_DUO;
			//Fin partie Bonus C PROMO	SEULEMENT
			//FIN BONUS C
		
		
			//Bonus D: 
			//HORS PROMO SEULEMENT:[PREMIUM]
			$Description_BonusD  	= "Maxiwide (3$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusD 		 	= 3;					//Définir la valeur de ce bonus: x$/Commande
			$user_idD	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusD 	= "order_product_name like '%maxiwide%'"; //Nom de produit à utiliser pour le Filtre	
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
			$resultIncentiveD 	= mysqli_query($con, $queryIncentiveD)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveD . mysqli_error($con));
			$DataIncentiveD 	= mysqli_fetch_array($resultIncentiveD,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintD = $DataIncentiveD[Nbr_Bonus_AtteintD];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantD = $ValeurBonusD  * $Nbr_Bonus_AtteintD;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantD;
			//Fin partie Promo F HORS PROMO SEULEMENT

			//PROMO SEULEMENT:[PREMIUM]
			$Description_BonusD_DUO  	= "Maxiwide (3$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusD_DUO 		 	= 1.50;					//Définir la valeur de ce bonus: x$/Commande
			$user_idD_DUO	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusD_DUO 	= "order_product_name like '%maxiwide%' AND (order_product_name like '%duo%' OR order_product_name like '%promo%') "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerD_DUO 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveD_DUO 	= "SELECT count(order_num) as Nbr_Bonus_AtteintD_DUO FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idD 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusD_DUO
			AND  $Coating_A_FiltrerD_DUO
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveD_DUO 	= mysqli_query($con, $queryIncentiveD_DUO)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveD_DUO . mysqli_error($con));
			$DataIncentiveD_DUO 	= mysqli_fetch_array($resultIncentiveD_DUO,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintD_DUO = $DataIncentiveD_DUO[Nbr_Bonus_AtteintD_DUO];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantD_DUO = $ValeurBonusD_DUO  * $Nbr_Bonus_AtteintD_DUO;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantD_DUO;
			//Fin partie Promo D PROMO SEULEMENT
			//Fin Bonus D
		
		

			//BONUS E: 
			//HORS PROMO SEULEMENT:[OTHER= iOffice/Premium office/iRelax]
			$Description_BonusE  	= "iOffice/Premium Office/iRelax (1$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusE 		 	= 1;					//Définir la valeur de ce bonus: x$/Commande
			$user_idE	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusE 	= " (order_product_name like '%Office%' OR order_product_name like '%Premium Office%' OR order_product_name like '%iRelax%')"; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerE 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveE 	= "SELECT count(order_num) as Nbr_Bonus_AtteintE FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idE
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusE
			AND  $Coating_A_FiltrerE 
			AND order_product_name not like '%promo%'
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveE 	= mysqli_query($con, $queryIncentiveE)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveE . mysqli_error($con));
			$DataIncentiveE 	= mysqli_fetch_array($resultIncentiveE,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintE = $DataIncentiveE[Nbr_Bonus_AtteintE];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantE = $ValeurBonusE  * $Nbr_Bonus_AtteintE;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantE;
			///Fin partie Promo E HORS PROMO SEULEMENT
		
			//PROMO SEULEMENT:[OTHER=OFFICE]
			$Description_BonusE_DUO  	= "iOffice/Premium Office/iRelax (1$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusE_DUO 		 	= 0.5;					//Définir la valeur de ce bonus: x$/Commande
			$user_idE_DUO	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusE_DUO 	= "order_product_name like '%Promo Premium Office%'"; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerE_DUO 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveE_DUO 	= "SELECT count(order_num) as Nbr_Bonus_AtteintE_DUO FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idE
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusE_DUO
			AND  $Coating_A_FiltrerE_DUO 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveE_DUO 	= mysqli_query($con, $queryIncentiveE_DUO)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveE_DUO . mysqli_error($con));
			$DataIncentiveE_DUO 	= mysqli_fetch_array($resultIncentiveE_DUO,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintE_DUO = $DataIncentiveE_DUO[Nbr_Bonus_AtteintE_DUO];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantE_DUO = $ValeurBonusE_DUO  * $Nbr_Bonus_AtteintE_DUO;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantE_DUO;
			//Fin partie Promo E PROMO SEULEMENT
			//Fin BONUS E
		
		
		
		
		
			//Bonus F:
			//HORS PROMO SEULEMENT: [GTC]
			$Description_BonusF  	= "GTC (1$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusF 		 	= 1;						//Définir la valeur de ce bonus: x$/Commande
			$user_idF	 		 	= $Userid_Magasin;			//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusF 	= " warranty like '%temple to temple%' "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerF 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveF 	= "SELECT count(order_num) as Nbr_Bonus_AtteintF FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idF
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusF
			AND  $Coating_A_FiltrerF 
			AND order_product_name not like '%duo%'
			AND order_product_name not like '%promo%'
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveF 	= mysqli_query($con, $queryIncentiveF)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveF . mysqli_error($con));
			$DataIncentiveF 	= mysqli_fetch_array($resultIncentiveF,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintF = $DataIncentiveF[Nbr_Bonus_AtteintF];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantF = $ValeurBonusF  * $Nbr_Bonus_AtteintF;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantF;
			//Fin partie Promo F HORS PROMO SEULEMENT
		
			//PROMO SEULEMENT: [GTC]
			$Description_BonusF_DUO  	= "GTC (1$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusF_DUO 		 	= 0.5;						//Définir la valeur de ce bonus: x$/Commande
			$user_idF_DUO	 		 	= $Userid_Magasin;			//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusF_DUO 	= " warranty like '%Garantie%' "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerF_DUO 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveF_DUO 	= "SELECT count(order_num) as Nbr_Bonus_AtteintF_DUO FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idF
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusF_DUO
			AND  $Coating_A_FiltrerF_DUO
			AND (order_product_name  like '%duo%' OR  order_product_name  like '%promo%')
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveF_DUO 	= mysqli_query($con, $queryIncentiveF_DUO)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveF_DUO . mysqli_error($con));
			$DataIncentiveF_DUO 	= mysqli_fetch_array($resultIncentiveF_DUO,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintF_DUO = $DataIncentiveF_DUO[Nbr_Bonus_AtteintF_DUO];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantF_DUO = $ValeurBonusF_DUO  * $Nbr_Bonus_AtteintF_DUO;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantF_DUO;
			//Fin partie Promo F PROMO SEULEMENT
		
		
			//Bonus G:
			//HORS PROMO SEULEMENT: [AR+ETC]
			$Description_BonusG  	= "AR+ETC (1$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusG 		 	= 1;						//Définir la valeur de ce bonus: x$/Commande
			$user_idG	 		 	= $Userid_Magasin;			//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusG 	= "  order_product_coating IN ('SPC','Dream AR', 'ITO AR','AR Backside','StressFree','StressFree 32','StressFree Noflex','iBlu','Night Vision','Super AR') "; //Nom de produit à utiliser pour le Filtre	
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
			AND order_product_name NOT like '%promo%' 
			AND order_product_name NOT like '%duo%'
			ORDER BY order_product_name";
			$resultIncentiveG 	= mysqli_query($con, $queryIncentiveG)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveG . mysqli_error($con));
			$DataIncentiveG 	= mysqli_fetch_array($resultIncentiveG,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintG = $DataIncentiveG[Nbr_Bonus_AtteintG];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantG = $ValeurBonusG  * $Nbr_Bonus_AtteintG;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantG;
			//Fin partie Promo G HORS PROMO SEULEMENT
			
			//PROMO SEULEMENT: [AR+ETC]
			$Description_BonusG_DUO  	= "AR+ETC (1$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusG_DUO 		 	= 0.5;						//Définir la valeur de ce bonus: x$/Commande
			$user_idG_DUO	 		 	= $Userid_Magasin;			//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusG_DUO 	= "  order_product_coating IN ('SPC','Dream AR', 'ITO AR','AR Backside','StressFree','StressFree 32','StressFree Noflex','iBlu','Night Vision','Super AR') "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerG_DUO 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveG_DUO 	= "SELECT count(order_num) as Nbr_Bonus_AtteintG_DUO FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idG_DUO 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusG_DUO
			AND  $Coating_A_FiltrerG_DUO 
			AND (order_product_name  like '%promo%' OR order_product_name  like '%duo%')
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveG_DUO 	= mysqli_query($con, $queryIncentiveG_DUO)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveG_DUO . mysqli_error($con));
			$DataIncentiveG_DUO 	= mysqli_fetch_array($resultIncentiveG_DUO,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintG_DUO = $DataIncentiveG_DUO[Nbr_Bonus_AtteintG_DUO];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantG_DUO = $ValeurBonusG_DUO * $Nbr_Bonus_AtteintG_DUO;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantG_DUO;
			//Fin partie Promo G PROMO SEULEMENT
			//Fin Bonus G
		
		
		
			//Bonus H
			//HORS PROMO SEULEMENT:[XLR/HD AR]
			$Description_BonusH  	= "XLR/HD AR/LR (1$/job)"; 			//Description de ce qui donne droit au bonus
			$ValeurBonusH 		 	= 1;						//Définir la valeur de ce bonus: x$/Commande
			$user_idH	 		 	= $Userid_Magasin;			//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusH 	= " order_product_coating IN ('Xlr','Xlr Backside','HD AR','HD AR Backside','Low Reflexion','Low Reflexion Backside') "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerH 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveH 	= "SELECT count(order_num) as Nbr_Bonus_AtteintH FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idH 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusH
			AND  $Coating_A_FiltrerH 
			AND order_product_name NOT like '%promo%' 
			AND order_product_name NOT like '%duo%' 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveH 	= mysqli_query($con, $queryIncentiveH)	or die  ('<br><br>I cannot select items because 1H: <br><br>'. $queryIncentiveH . mysqli_error($con));
			$DataIncentiveH 	= mysqli_fetch_array($resultIncentiveH,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintH = $DataIncentiveH[Nbr_Bonus_AtteintH];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantH = $ValeurBonusH  * $Nbr_Bonus_AtteintH;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantH;
			//Fin partie Promo H HORS PROMO SEULEMENT
			
			//PROMO SEULEMENT:[XLR]
			$Description_BonusH_DUO  	= "XLR/HD AR/LR (1$/job)"; 			//Description de ce qui donne droit au bonus
			$ValeurBonusH_DUO 		 	= 0.50;						//Définir la valeur de ce bonus: x$/Commande
			$user_idH_DUO	 		 	= $Userid_Magasin;			//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusH_DUO 	= " order_product_coating IN ('Xlr','Xlr Backside','HD AR','HD AR Backside','Low Reflexion','Low Reflexion Backside') "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerH_DUO 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveH_DUO 	= "SELECT count(order_num) as Nbr_Bonus_AtteintH_DUO FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idH_DUO 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusH_DUO
			AND  $Coating_A_FiltrerH_DUO 
			AND (order_product_name  like '%promo%' OR order_product_name  like '%duo%') 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveH_DUO 	= mysqli_query($con, $queryIncentiveH_DUO)	or die  ('<br><br>I cannot select items because 1Hb: <br><br>'. $queryIncentiveH_DUO . mysqli_error($con));
			$DataIncentiveH_DUO 	= mysqli_fetch_array($resultIncentiveH_DUO,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintH_DUO = $DataIncentiveH_DUO[Nbr_Bonus_AtteintH_DUO];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantH_DUO = $ValeurBonusH_DUO  * $Nbr_Bonus_AtteintH_DUO;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantH_DUO;
			//Fin partie Promo H PROMO SEULEMENT
			//Fin Bonus H
		
		
			//Bonus I
			//HORS PROMO SEULEMENT:[Transitions/Polarized]
			$Description_BonusI  	= "Transitions/Polarized (2$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusI		 	= 2;								//Définir la valeur de ce bonus: x$/Commande
			$user_idI	 		 	= $Userid_Magasin;					//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusI 	= " (order_product_name like '%transitions%' OR order_product_name like '%polarized%') "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerI 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveI 	= "SELECT count(order_num) as Nbr_Bonus_AtteintI FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idI 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusI
			AND  $Coating_A_FiltrerI
			AND order_product_name not like '%duo%'
			AND order_product_name not like '%promo%'
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveI 	= mysqli_query($con, $queryIncentiveI)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveI . mysqli_error($con));
			$DataIncentiveI 	= mysqli_fetch_array($resultIncentiveI,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintI = $DataIncentiveI[Nbr_Bonus_AtteintI];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantI = $ValeurBonusI  * $Nbr_Bonus_AtteintI;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantI;
			//Fin partie Promo I HORS PROMO SEULEMENT
			
			
			//PROMO SEULEMENT:[Transitions/Polarized]
			$Description_BonusI_DUO  	= "Transitions/Polarized (2$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusI_DUO		 	= 1;								//Définir la valeur de ce bonus: x$/Commande
			$user_idI_DUO	 		 	= $Userid_Magasin;					//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusI_DUO 	= " (order_product_name like '%transitions%' OR order_product_name like '%polarized%') "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerI_DUO 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveI_DUO 	= "SELECT count(order_num) as Nbr_Bonus_AtteintI_DUO FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN $user_idI 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusI
			AND  $Coating_A_FiltrerI
			AND ( order_product_name  like '%duo%' OR order_product_name  like '%promo%')
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveI_DUO 	= mysqli_query($con, $queryIncentiveI_DUO)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveI_DUO . mysqli_error($con));
			$DataIncentiveI_DUO 	= mysqli_fetch_array($resultIncentiveI_DUO,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintI_DUO = $DataIncentiveI_DUO[Nbr_Bonus_AtteintI_DUO];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantI_DUO = $ValeurBonusI_DUO  * $Nbr_Bonus_AtteintI_DUO;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantI_DUO;
			//Fin partie Promo I PROMO SEULEMENT
			//Fin Bonus I
		echo '<br>Selected total avant calcul:'. $SelectedStoreTotal;

		$SelectedStoreTotal +=  $ResultatValeurBonusCourrantB + $ResultatValeurBonusCourrantC+ $ResultatValeurBonusCourrantD+ $ResultatValeurBonusCourrantE+
		$ResultatValeurBonusCourrantF +	$ResultatValeurBonusCourrantG +	$ResultatValeurBonusCourrantH +	$ResultatValeurBonusCourrantI;
		
		echo '<br>Selected total:'. $SelectedStoreTotal;
		
		//Afficher les résultats
		$message.= "<tr><td align=\"center\">". $DataDistinctEmployees[salesperson_id]. "</td>";
		
		
		
		
				
		$TotalBonusB = $ResultatValeurBonusCourrantB + $ResultatValeurBonusCourrantB_DUO;
		//[BETTER]:Alpha HD/ULTIMATE/PRECISION(Pas encore créé)
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\"><b>Régulier</b>: ". $Nbr_Bonus_AtteintB . " x $ValeurBonusB$ ="."$ResultatValeurBonusCourrantB$
		<br> <b>Promo</b>: "		. $Nbr_Bonus_AtteintB_DUO . " x $ValeurBonusB_DUO$ ="."$ResultatValeurBonusCourrantB_DUO$	<br>TOTAL:$TotalBonusB$					
		</td>";
		
		$TotalBonusC = $ResultatValeurBonusCourrantC + $ResultatValeurBonusCourrantC_DUO;
		//[BEST]:ALPHA 4D/IFREE/PRECISION 360(Pas encore créé)/iAction Progressive/Impression
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\"><b>Régulier</b>: ". $Nbr_Bonus_AtteintC . " x $ValeurBonusC$ ="."$ResultatValeurBonusCourrantC$
		<br> <b>Promo</b>: "		. $Nbr_Bonus_AtteintC_DUO . " x $ValeurBonusC_DUO$ ="."$ResultatValeurBonusCourrantC_DUO$		 <br>TOTAL:$TotalBonusC$					
		</td>";
		
					
		$TotalBonusD = $ResultatValeurBonusCourrantD + $ResultatValeurBonusCourrantD_DUO;
		//[PREMIUM]:Maxiwide
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\"><b>Régulier</b>: ". $Nbr_Bonus_AtteintD . " x $ValeurBonusD$ ="."$ResultatValeurBonusCourrantD$
		<br> <b>Promo</b>: "		. $Nbr_Bonus_AtteintD_DUO . " x $ValeurBonusD_DUO$ ="."$ResultatValeurBonusCourrantD_DUO$				 <br>TOTAL:$TotalBonusD$				
		</td>";
		
		
		$TotalBonusE = $ResultatValeurBonusCourrantE + $ResultatValeurBonusCourrantE_DUO;		
		//[OTHER=OFFICE]:Office HD/iRelax
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\"><b>Régulier</b>: ". $Nbr_Bonus_AtteintE . " x $ValeurBonusE$ ="."$ResultatValeurBonusCourrantE$
		<br> <b>Promo</b>: "		. $Nbr_Bonus_AtteintE_DUO . " x $ValeurBonusE_DUO$ ="."$ResultatValeurBonusCourrantE_DUO$			 <br>TOTAL:$TotalBonusE$				
		</td>";
		
		
	
		$TotalBonusF = $ResultatValeurBonusCourrantF + $ResultatValeurBonusCourrantF_DUO;	
		//[GTC]/[TTT] Temple to temple warranty
		$message.= 		"<td bgcolor=\"#B15E6C\" align=\"center\"><b>Régulier</b>: ". $Nbr_Bonus_AtteintF . " x $ValeurBonusF$ ="."$ResultatValeurBonusCourrantF$
		<br> <b>Promo</b>: "		. $Nbr_Bonus_AtteintF_DUO . " x $ValeurBonusF_DUO$ ="."$ResultatValeurBonusCourrantF_DUO$		 <br>TOTAL:$TotalBonusF$					
		</td>";
		
		
		
		$TotalBonusG = $ResultatValeurBonusCourrantG + $ResultatValeurBonusCourrantG_DUO;	
		//[AR+ETC]
		$message.= 		"<td bgcolor=\"#B15E6C\" align=\"center\"><b>Régulier</b>: ". $Nbr_Bonus_AtteintG . " x $ValeurBonusG$ ="."$ResultatValeurBonusCourrantG$
		<br> <b>Promo</b>: "		. $Nbr_Bonus_AtteintG_DUO . " x $ValeurBonusG_DUO$ ="."$ResultatValeurBonusCourrantG_DUO$			 <br>TOTAL:$TotalBonusG$				
		</td>";
		
		$TotalBonusH = $ResultatValeurBonusCourrantH + $ResultatValeurBonusCourrantH_DUO;	
		 //[XLR/HD AR]
		$message.= 		"<td bgcolor=\"#B15E6C\" align=\"center\"><b>Régulier</b>: ". $Nbr_Bonus_AtteintH . " x $ValeurBonusH$ ="."$ResultatValeurBonusCourrantH$
		<br> <b>Promo</b>: "		. $Nbr_Bonus_AtteintH_DUO . " x $ValeurBonusH_DUO$ ="."$ResultatValeurBonusCourrantH_DUO$					 <br>TOTAL:$TotalBonusH$			
		</td>";
		
		
		$TotalBonusI = $ResultatValeurBonusCourrantI + $ResultatValeurBonusCourrantI_DUO;	
		//OTHER:Transitions/Polarized
		$message.= 		"<td bgcolor=\"#B15E6C\" align=\"center\"><b>Régulier</b>: ". $Nbr_Bonus_AtteintI . " x $ValeurBonusI$ ="."$ResultatValeurBonusCourrantI$
		<br> <b>Promo</b>: "		. $Nbr_Bonus_AtteintI_DUO . " x $ValeurBonusI_DUO$ ="."$ResultatValeurBonusCourrantI_DUO$					 <br>TOTAL:$TotalBonusI$			
		</td>";
		
		
		
		$SommedesBonusMagasinActuel = $SommedesBonusMagasinActuel + $TotalBonusA+$TotalBonusB+ $TotalBonusC+$TotalBonusD+$TotalBonusE+$TotalBonusF+$TotalBonusG+$TotalBonusH+$TotalBonusI;
		echo '<br><br>$SommedesBonusMagasinActuel:' . $SommedesBonusMagasinActuel;
		$TotalIncentiveForthisEmployee = number_format($TotalIncentiveForthisEmployee, 2);
		$message.= "<th bgcolor=\"#DAE0F2\"  align=\"center\">". $TotalIncentiveForthisEmployee ."$</th>";
		
		
		//$SoixantePourcentTotalForthisEmployee=0.5*$TotalIncentiveForthisEmployee;
		//$SoixantePourcentTotalForthisEmployee = number_format($SoixantePourcentTotalForthisEmployee, 2);
		//$message.= "<th bgcolor=\"#DAE0F2\" align=\"center\">$SoixantePourcentTotalForthisEmployee$</th></tr>";	//Emplacement pour entrer le % à la main
	}//End While
	
	
	$SelectedStoreTotal = number_format($SelectedStoreTotal, 2);
	
	
	$queryNombreCommandePeriode = "SELECT COUNT(order_num) as NbrOriginales FROM ORDERS 
	WHERE order_date_processed BETWEEN '$date1' AND '$date2' 	AND user_id in ('warehousehal','warehousehalsafe')
	AND redo_order_num is null	";
	$ResultNbrCommande = mysqli_query($con, $queryNombreCommandePeriode) or die 	('I cannot select items because #2g: '. $queryNbrCommande . mysqli_error($con));
	$DataNbrCommandes = mysqli_fetch_array($ResultNbrCommande,MYSQLI_ASSOC);
	
	
	$queryReprise = "SELECT COUNT(order_num) as NbrReprises FROM ORDERS 
	WHERE order_date_processed BETWEEN '$date1' AND '$date2' 	AND user_id in ('warehousehal','warehousehalsafe')
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
	
	$message.= "<tr>
			<th colspan=\"9\" align=\"right\">Number of Original orders</th>
			<th>$DataNbrCommandes[NbrOriginales]</th>
			</tr>
			<tr>
			<th colspan=\"9\" align=\"right\">Number of redos</th>
			<th>$DataNbrReprise[NbrReprises]</th>
			</tr>
			<tr>
			<th colspan=\"9\" align=\"right\">Redo percentage</th>
			<th>$PourcentageReprise%</th>
			
		</tr>";
		
	
	
	
   $message.= "<tr>
			<th bgcolor=\"#EFE9AE\" colspan=\"9\" align=\"right\">TOTAL POUR $Description_Magasin :</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$SommedesBonusMagasinActuel$</th>
			
		</tr>";
	
	echo $message;
	return $message;
}//END FUNCTION CalculerIncentiveEDLL
		
$time_start  = microtime(true);	



?>
