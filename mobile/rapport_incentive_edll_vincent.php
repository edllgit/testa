<?php 
ini_set('max_execution_time', 0);
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

$date1       = date("Y-m-d");
$date2       = date("Y-m-d");

$date1   	= $_POST[date1];
$date2     	= $_POST[date2];

/*
//DATES HARD CODÉS MANUELLE
$date1        = "2019-07-25";
$date2        = "2019-08-25";
*/

 
include('../sec_connectEDLL.inc.php');
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

$LargeurColspanTableau =13;	//Nombre de TD dans le tableau
$WidthTableau = 1330;		//Pixels

//EDLL.CA PRODUCTION PART

//Prepare email 
$message="<html>
<head>
<style type='text/css'>
<!--
.TextSize {
	font-size: 10pt;
	font-family: Arial, Helvetica, sans-serif;
}
-->
</style>
</head>
<body>
<table width='$WidthTableau' border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">
<tr>
	<th  colspan=\"$LargeurColspanTableau\"><h3>Rapport Incitatifs EDLL $date1-$date2 DM: Gaelle Fidélia</h3></th>
</tr>";


$message_VincentHoutart="<html>
<head><style type='text/css'>
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
		<th  colspan=\"$LargeurColspanTableau\"><h3>Rapport Incitatifs EDLL $date1-$date2 DM: Vincent Houtart</h3></th>
	</tr>";

//Paramètres communs
$from_address='donotreply@entrepotdelalunette.com';


//DÉBUT PARTIE VINCENT HOUTART
//Vincent's Stores 	
$message_Chicoutimi 	= $message_VincentHoutart;//Magasin de Vincent
$message_Levis			= $message_VincentHoutart;//Magasin de Vincent
$message_Quebec 		= $message_VincentHoutart;//Magasin de Vincent
$message_Drummondville 	= $message_VincentHoutart;//Magasin de Vincent
$message_Granby 		= $message_VincentHoutart;//Magasin de Vincent
$message_Sherbrooke   	= $message_VincentHoutart;//Magasin de Vincent
$message_TroisRivieres  = $message_VincentHoutart;//Magasin de Vincent


//#1er:Début magasin EDLL-Chicoutimi
$store				= "('chicoutimi','chicoutimisafe')";
$StoreDescription	= "Entrepot de la lunette Chicoutimi";
//$store_category 	= "A";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Magasin:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$message_VincentHoutart.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message_VincentHoutart.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message_Chicoutimi.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message_Chicoutimi.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//ENVOIE DU COURRIEL A CHICOUTIMI
$subject ="EDLL Rapport Incitatifs Chicoutimi: $date1-$date2]";
$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//TEST
//$to_address	= array('dbeaulieu@direct-lens.com','chicoutimi@entrepotdelalunette.com');//LIVE
echo '<br><br>Envoie du rapport de Chicoutimi en cours..<br>';
echo 'Contenu du rapport:<br>'. $message_Chicoutimi;
$response=office365_mail($to_address, $from_address, $subject, null, $message_Chicoutimi);
echo '<br>Rapport Incitatif de Chicoutimi envoyé avec Succès!<br>';
//Fin EDLL-Chicoutimi



//#2:Début magasin EDLL-Lévis
$store				= "('levis','levissafe')";
$StoreDescription	= "Entrepot de la lunette Lévis";
//$store_category 	= "A";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Magasin:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$message_VincentHoutart.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message_VincentHoutart.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message_Levis.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message_Levis.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//ENVOIE DU COURRIEL A LEVIS
$subject ="EDLL Rapport Incitatifs Lévis: [$date1-$date2]";
$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//TEST
//$to_address	= array('dbeaulieu@direct-lens.com','levis@entrepotdelalunette.com');//LIVE
echo '<br><br>Envoie du rapport de Lévis en cours..<br>';
echo 'Contenu du rapport:<br>'. $message_Levis;
$response=office365_mail($to_address, $from_address, $subject, null, $message_Levis);
echo '<br>Rapport Incitatif de Lévis envoyé avec Succès!<br>';
//Fin EDLL-Lévis



//#3:Début magasin EDLL-Quebec
$store				= "('entrepotquebec','quebecsafe')";
$StoreDescription	= "Entrepot de la lunette Québec";
//$store_category 	= "A";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Magasin:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$message_VincentHoutart.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message_VincentHoutart.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message_Quebec.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message_Quebec.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//ENVOIE DU COURRIEL A QUEBEC
$subject ="EDLL Rapport Incitatifs Québec: [$date1-$date2]";
$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//TEST
//$to_address	= array('dbeaulieu@direct-lens.com','quebec@entrepotdelalunette.com');//LIVE
echo '<br><br>Envoie du rapport de Québec en cours..<br>';
echo 'Contenu du rapport:<br>'. $message_Quebec;
$response=office365_mail($to_address, $from_address, $subject, null, $message_Quebec);
echo '<br>Rapport Incitatif de Québec envoyé avec Succès!<br>';
//Fin EDLL-Québec



//#4:Début magasin EDLL-Drummondville
$store				= "('entrepotdr','safedr')";
$StoreDescription	= "Entrepot de la lunette Drummondville";
//$store_category 	= "A";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Magasin:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$message_VincentHoutart.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message_VincentHoutart.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message_Drummondville.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message_Drummondville.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//ENVOIE DU COURRIEL A DRUMMONDVILLE
$subject ="EDLL Rapport Incitatifs Drummondville: [$date1-$date2]";
$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//TEST
//$to_address	= array('dbeaulieu@direct-lens.com','drummondville@entrepotdelalunette.com');//LIVE
echo '<br><br>Envoie du rapport de Drummondville en cours..<br>';
echo 'Contenu du rapport:<br>'. $message_Drummondville;
$response=office365_mail($to_address, $from_address, $subject, null, $message_Drummondville);
echo '<br>Rapport Incitatif de Drummondville envoyé avec Succès!<br>';
//Fin EDLL-Drummondville



//#5:Début magasin EDLL-Granby
$store				= "('granby','granbysafe')";
$StoreDescription	= "Entrepot de la lunette Granby";
//$store_category 	= "A";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Magasin:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$message_VincentHoutart.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message_VincentHoutart.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message_Granby.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message_Granby.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//ENVOIE DU COURRIEL A GRANBY
$subject ="EDLL Rapport Incitatifs Granby: [$date1-$date2]";
$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//TEST
//$to_address	= array('dbeaulieu@direct-lens.com','granby@entrepotdelalunette.com');//LIVE
echo '<br><br>Envoie du rapport de Granby en cours..<br>';
echo 'Contenu du rapport:<br>'. $message_Granby;
$response=office365_mail($to_address, $from_address, $subject, null, $message_Granby);
echo '<br>Rapport Incitatif de Granby envoyé avec Succès!<br>';
//Fin EDLL-Granby



//#6:Début magasin EDLL-Sherbrooke
$store				= "('sherbrooke','sherbrookesafe')";
$StoreDescription	= "Entrepot de la lunette Sherbrooke";
//$store_category 	= "A";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Magasin:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$message_VincentHoutart.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message_VincentHoutart.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message_Sherbrooke.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message_Sherbrooke.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//ENVOIE DU COURRIEL A SHERBROOKE
$subject ="EDLL Rapport Incitatifs Sherbrooke: [$date1-$date2]";
$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//TEST
//$to_address	= array('dbeaulieu@direct-lens.com','sherbrooke@entrepotdelalunette.com');//LIVE
echo '<br><br>Envoie du rapport de Sherbrooke en cours..<br>';
echo 'Contenu du rapport:<br>'. $message_Sherbrooke;
$response=office365_mail($to_address, $from_address, $subject, null, $message_Sherbrooke);
echo '<br>Rapport Incitatif de Sherbrooke envoyé avec Succès!<br>';
//Fin EDLL-Sherbrooke



//#7:Début magasin EDLL-Trois-Rivieres
$store				= "('entrepotifc','entrepotsafe')";
$StoreDescription	= "Entrepot de la lunette Trois-Rivières";
//$store_category 	= "A";
//A)Insérer dans le string du email 'Global' envoyé a Amina, Daniel, Karine
$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Magasin:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//B)Inserer dans le string qui accumule uniquement les STATS des magasins de Johnny Chow
$message_VincentHoutart.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message_VincentHoutart.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//C)Inserer dans le string qui accumule uniquement les STATS de ce magasin
$message_TroisRivieres.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Store:$StoreDescription&nbsp;&nbsp;&nbsp;&nbsp;Category: $store_category</h3></th></tr>";
$message_TroisRivieres.=CalculerIncentiveEDLL($store,$StoreDescription,$date1,$date2);
//ENVOIE DU COURRIELA TROIS-RIVIERES
$subject ="EDLL Rapport Incitatifs Trois-Rivières: [$date1-$date2]";
$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//TEST
//$to_address	= array('dbeaulieu@direct-lens.com','trois-rivieres@entrepotdelalunette.com');//LIVE
echo '<br><br>Envoie du rapport de Trois-Rivières en cours..<br>';
echo 'Contenu du rapport:<br>'. $message_TroisRivieres;
$response=office365_mail($to_address, $from_address, $subject, null, $message_TroisRivieres);
echo '<br>Rapport Incitatif de Trois-Rivières envoyé avec Succès!<br>';
//Fin EDLL-Trois-Rivières


//Envoie RAPPORT GLOBAL A VINCENT HOUTART
$subject ="Rapport Incitatif EDLL [SOMMAIRE VINCENT] Between $date1-$date2";
$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//TEST
//$to_address	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//LIVE
echo '<br><br>Envoie du rapport Vincent Houtart  en cours..<br>';
echo 'Contenu du rapport:<br>'. $message_VincentHoutart;
$response=office365_mail($to_address, $from_address, $subject, null, $message_VincentHoutart);
echo '<br>Rapport pour Vincent Houtart:Envoyé avec succès!<br>';









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
			<th bgcolor=\"#e0c1b3\" align=\"center\" colspan=\"5\">PROGRESSIF</th>
			<th bgcolor=\"#B15E6C\" align=\"center\" colspan=\"4\">AUTRES</th>
			<th bgcolor=\"#DAE0F2\" align=\"center\" colspan=\"2\">TOTAL</th>
		</tr>";
	
	
	$message.= "
		<tr>
			<th align=\"center\">Employé</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">HD IOT(A)</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">Ultimate/Alpha(B)</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">iFree(C)</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">Maxiwide(D)</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">Office HD(E)</th>
			<th bgcolor=\"#B15E6C\" align=\"center\">GTC(F)</th>
			<th bgcolor=\"#B15E6C\" align=\"center\">AR+ETC(G)</th>
			<th bgcolor=\"#B15E6C\" align=\"center\">XLR(H)</th>
			<th bgcolor=\"#B15E6C\" align=\"center\">Trans/Polar(I)</th>
			<th bgcolor=\"#DAE0F2\" align=\"center\">Total(J)</th>
			<th bgcolor=\"#DAE0F2\" align=\"center\">50% du Total(K)</th>
		</tr>";
	
	
	while ($DataDistinctEmployees = mysqli_fetch_array($resultDistinctEmployees,MYSQLI_ASSOC)){
		$TotalIncentiveForthisEmployee=0;//Initialise cette variable qui cummule le total par employé
		
		
		
			
		//Promo A: HD IOT 	
			$Description_BonusA  	= "HD IOT (1$/job)";  	//Description de ce qui donne droit au bonus
			$ValeurBonusA 		 	= 1;					//Définir la valeur de ce bonus: x$/Commande
			$user_idA 	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusA 	= " order_product_name LIKE '%HD IOT%' "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerA	 	=  " 1=1 "; 			//Nom de coating à utiliser pour le Filtre		
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
			$resultIncentiveA 	= mysqli_query($con, $queryIncentiveA)	or die  ('<br><br>I cannot select items because 1a: <br><br>'. $queryIncentiveA . mysqli_error($con));
			$DataIncentiveA 	= mysqli_fetch_array($resultIncentiveA,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintA = $DataIncentiveA[Nbr_Bonus_AtteintA];		
			//Calcul du bonus total mérité  par CET Employé 
			$ResultatValeurBonusCourrantA = $ValeurBonusA  * $Nbr_Bonus_AtteintA;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantA;
		//Fin partie Promo A
		
		
		//Promo B Ultimate/Alpha
			$Description_BonusB  	= "ULTIMATE/ALPHA (2$/job)"; 			//Description de ce qui donne droit au bonus
			$ValeurBonusB 		 	= 2;							//Définir la valeur de ce bonus: x$/Commande
			$user_idB 	 		 	= $Userid_Magasin;				//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusB 	= " (order_product_name LIKE '%Ultimate%' OR order_product_name like '%ALPHA%' and  order_product_name not like '%promo%') "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerB	 	=  " 1=1 "; 					//Nom de coating à utiliser pour le Filtre		
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
			$resultIncentiveB 	= mysqli_query($con, $queryIncentiveB)	or die  ('<br><br>I cannot select items because 1b: <br><br>'. $queryIncentiveB . mysqli_error($con));
			$DataIncentiveB 	= mysqli_fetch_array($resultIncentiveB,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintB = $DataIncentiveB[Nbr_Bonus_AtteintB];		
			//Calcul du bonus total mérité  par CET Employé 
			$ResultatValeurBonusCourrantB = $ValeurBonusB  * $Nbr_Bonus_AtteintB;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantB;
		//Fin partie Promo B
		
		
		//Promo C: iFree
			$Description_BonusC  	= "iFree (3$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusC 		 	= 3;					//Définir la valeur de ce bonus: x$/Commande
			$user_idC	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusC 	= "order_product_name like '%ifree%'"; //Nom de produit à utiliser pour le Filtre	
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
			$resultIncentiveC 	= mysqli_query($con, $queryIncentiveC)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveC . mysqli_error($con));
			$DataIncentiveC 	= mysqli_fetch_array($resultIncentiveC,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintC = $DataIncentiveC[Nbr_Bonus_AtteintC];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantC = $ValeurBonusC  * $Nbr_Bonus_AtteintC;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantC;
		//Fin partie Promo C
		
		
		//Promo D: Maxiwide
			$Description_BonusD  	= "Maxiwide (4$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusD 		 	= 4;					//Définir la valeur de ce bonus: x$/Commande
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
		//Fin partie Promo F

		
		

		//Promo E: Office HD
			$Description_BonusE  	= "Office HD (2$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusE 		 	= 2;					//Définir la valeur de ce bonus: x$/Commande
			$user_idE	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusE 	= "order_product_name like '%iOffice%'"; //Nom de produit à utiliser pour le Filtre	
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
			$resultIncentiveE 	= mysqli_query($con, $queryIncentiveE)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveE . mysqli_error($con));
			$DataIncentiveE 	= mysqli_fetch_array($resultIncentiveE,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintE = $DataIncentiveE[Nbr_Bonus_AtteintE];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantE = $ValeurBonusE  * $Nbr_Bonus_AtteintE;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantE;
		//Fin partie Promo E	
		
	
		
		
		
		
		
		//Promo F:GTC
			$Description_BonusF  	= "GTC (2$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusF 		 	= 2;						//Définir la valeur de ce bonus: x$/Commande
			$user_idF	 		 	= $Userid_Magasin;			//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusF 	= " warranty like '%Garantie%' "; //Nom de produit à utiliser pour le Filtre	
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
			
			//echo "<br><br>Query: ".$queryIncentiveF;
			$resultIncentiveF 	= mysqli_query($con, $queryIncentiveF)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveF . mysqli_error($con));
			$DataIncentiveF 	= mysqli_fetch_array($resultIncentiveF,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintF = $DataIncentiveF[Nbr_Bonus_AtteintF];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantF = $ValeurBonusF  * $Nbr_Bonus_AtteintF;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantF;
			//Fin partie Promo F
		
		
		
			//Promo G:AR+ETC
			$Description_BonusG  	= "AR+ETC (2$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusG 		 	= 2;						//Définir la valeur de ce bonus: x$/Commande
			$user_idG	 		 	= $Userid_Magasin;			//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusG 	= "  order_product_coating IN ('SPC','Dream AR', 'ITO AR','AR Backside','StressFree','StressFree 32','StressFree Noflex','iBlu','Night Vision') "; //Nom de produit à utiliser pour le Filtre	
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
		
			//echo '<br>Query:'. $queryIncentiveG.'<br>';
			$resultIncentiveG 	= mysqli_query($con, $queryIncentiveG)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveG . mysqli_error($con));
			$DataIncentiveG 	= mysqli_fetch_array($resultIncentiveG,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintG = $DataIncentiveG[Nbr_Bonus_AtteintG];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantG = $ValeurBonusG  * $Nbr_Bonus_AtteintG;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantG;
			//Fin partie Promo G
		
		
		
			//Promo H:XLR
			$Description_BonusH  	= "XLR (3$/job)"; 			//Description de ce qui donne droit au bonus
			$ValeurBonusH 		 	= 3;						//Définir la valeur de ce bonus: x$/Commande
			$user_idH	 		 	= $Userid_Magasin;			//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusH 	= " order_product_coating IN ('Xlr','Xlr Backside','HD AR','HD AR Backside') "; //Nom de produit à utiliser pour le Filtre	
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
			//echo $queryIncentiveH. '<br><br>';
			$resultIncentiveH 	= mysqli_query($con, $queryIncentiveH)	or die  ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveH . mysqli_error($con));
			$DataIncentiveH 	= mysqli_fetch_array($resultIncentiveH,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintH = $DataIncentiveH[Nbr_Bonus_AtteintH];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantH = $ValeurBonusH  * $Nbr_Bonus_AtteintH;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantH;
			//Fin partie Promo H
		
		
		
		
			//Promo I:Transitions/Polarized
			$Description_BonusI  	= "Transitions/Polarized (3$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusI		 	= 3;								//Définir la valeur de ce bonus: x$/Commande
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
			//Fin partie Promo I
		
		
		$SelectedStoreTotal += $ResultatValeurBonusCourrantA + $ResultatValeurBonusCourrantB + $ResultatValeurBonusCourrantC+ $ResultatValeurBonusCourrantD+ $ResultatValeurBonusCourrantE+
		$ResultatValeurBonusCourrantF +	$ResultatValeurBonusCourrantG +	$ResultatValeurBonusCourrantH +	$ResultatValeurBonusCourrantI +	$ResultatValeurBonusCourrantJ +	$ResultatValeurBonusCourrantK 
		+$ResultatValeurBonusCourrantL +$ResultatValeurBonusCourrantM;
		
		
		//Afficher les résultats
		$message.= "<tr>
					<td align=\"center\">". $DataDistinctEmployees[salesperson_id]. "</td>";
		
		
				
		
					//PROG:HD IOT
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintA . " x $ValeurBonusA$ ="."$ResultatValeurBonusCourrantA$</td>";
		
		
					//PROG:Ultimate
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintB . " x $ValeurBonusB$ ="."$ResultatValeurBonusCourrantB$</td>";
		
		
					//PROG:iFree
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintC . " x $ValeurBonusC$ ="."$ResultatValeurBonusCourrantC$</td>";
		
					
		
					//PROG:Maxiwide
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintD . " x $ValeurBonusD$ ="."$ResultatValeurBonusCourrantD$</td>";
		
		
					
					//OFFICE:Office HD
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintE . " x $ValeurBonusE$ ="."$ResultatValeurBonusCourrantE$</td>";
		
		
	
					//OTHER:ABC Warranty
		$message.= 		"<td  bgcolor=\"#B15E6C\" align=\"center\">". $Nbr_Bonus_AtteintF . " x $ValeurBonusF$ ="."$ResultatValeurBonusCourrantF$</td>";
		
		
				   //OTHER:AR+ETC
		$message.= 		"<td  bgcolor=\"#B15E6C\" align=\"center\">". $Nbr_Bonus_AtteintG . " x $ValeurBonusG$ ="."$ResultatValeurBonusCourrantG$</td>";
		
		
		   			//OTHER:XLR
		$message.= 		"<td  bgcolor=\"#B15E6C\" align=\"center\">". $Nbr_Bonus_AtteintH . " x $ValeurBonusH$ ="."$ResultatValeurBonusCourrantH$</td>";
		
					//OTHER:Transitions/Polarized
		$message.= 		"<td  bgcolor=\"#B15E6C\" align=\"center\">". $Nbr_Bonus_AtteintI . " x $ValeurBonusI$ ="."$ResultatValeurBonusCourrantI$</td>";
		

		$TotalIncentiveForthisEmployee = number_format($TotalIncentiveForthisEmployee, 2);
		$message.= "<th bgcolor=\"#DAE0F2\"  align=\"center\">". $TotalIncentiveForthisEmployee ."$</th>";
		
		
		$SoixantePourcentTotalForthisEmployee=0.5*$TotalIncentiveForthisEmployee;
		$SoixantePourcentTotalForthisEmployee = number_format($SoixantePourcentTotalForthisEmployee, 2);
		$message.= "<th bgcolor=\"#DAE0F2\" align=\"center\">$SoixantePourcentTotalForthisEmployee$</th></tr>";	//Emplacement pour entrer le % à la main
	}//End While
	
	$SoixantePourcentTotalforStore = 0.5 * $SelectedStoreTotal;
	//Formatter les données
	$SoixantePourcentTotalforStore = number_format($SoixantePourcentTotalforStore, 2);
	$SelectedStoreTotal = number_format($SelectedStoreTotal, 2);
   $message.= "<tr>
			<th bgcolor=\"#EFE9AE\" colspan=\"10\" align=\"right\">TOTAL POUR $Description_Magasin :</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$SelectedStoreTotal$</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$SoixantePourcentTotalforStore$</th>
		</tr>";
	
	$message.= "<tr>
			<th colspan=\"16\" align=\"right\">&nbsp;</th>
		</tr>";
	return $message;
}//END FUNCTION CalculerIncentiveEDLL
		
$time_start  = microtime(true);	



?>
