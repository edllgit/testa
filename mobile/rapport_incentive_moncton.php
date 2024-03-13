<?php 
header('Content-type: text/html; charset=UTF-8');
/*
ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
include('../sec_connectEDLL.inc.php');

$date1   	= $_REQUEST[date1];
$date2     	= $_REQUEST[date2];


//DATES HARD CODÉS MANUELLE
/*
$date1        = "2022-03-01";
$date2        = "2022-10-31";
*/

include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

$LargeurColspanTableau =10;	//Nombre de TD dans le tableau
$WidthTableau = "100%";		//Pixels

//EDLL.CA PRODUCTION PART

//Prepare email 

//Paramètres communs
$from_address='donotreply@entrepotdelalunette.com';

$Userid =  " ('moncton')";  			
$send_to_address = array('rapports@direct-lens.com');
		
$TotalIfreePlusAdvance  = 0;//Initialise le compteur de bonus Ifree_Plus_Advance
$TotalMaxiwide 			= 0;//Initialise le compteur de bonus Maxiwide		
		
		
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
		<th  colspan=\"$LargeurColspanTableau\"><h3>Rapport Incitatifs Moncton $date1-$date2 </h3></th>
	</tr>";
	
	$message.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Succursale:Moncton</h3></th></tr>";
	$message.= "
		<tr>
			<th align=\"center\">Employé</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">Vente de produit Ifree Plus Advance</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">Vente de produit Maxiwide</th>
			<th bgcolor=\"#DAE0F2\" align=\"center\">Total</th>
		</tr>";
	//MONCTON

	$SelectedStoreTotal=0;//Initialiser le total par magasin
	$SommedesBonusMagasinActuel = 0;
	//Passer les différents employés qui ont fait des ventes durant la période évaluée
	$queryDistinctEmployeesofThisStore = 
	"SELECT distinct salesperson_id FROM orders
	WHERE order_date_processed BETWEEN '$date1' AND '$date2' 
	AND salesperson_id<>''	AND user_id IN ('moncton','monctonsafe')
	AND salesperson_id NOT IN ('21hbc-admin-all-access','Comptabilité Accounting','kl','entrepotqc')
	AND salesperson_id NOT LIKE '%accounting%'
	ORDER BY salesperson_id";
	
	//echo '<br>'.$queryDistinctEmployeesofThisStore.'<br>';
	
	$resultDistinctEmployees = mysqli_query($con, $queryDistinctEmployeesofThisStore) or die  ('I cannot select items because #2g: '. $queryDistinctEmployeesofThisStore . mysqli_error($con));
	
	//Initialiser le compteur ici 
	$TotalIfreePlusAdvance  = 0;//Initialise le compteur de bonus Ifree_Plus_Advance
	$TotalMaxiwide = 0;//Initialiser le compteur de Maxiwide
	
	while ($DataDistinctEmployees = mysqli_fetch_array($resultDistinctEmployees,MYSQLI_ASSOC)){
	$TotalIncentiveForthisEmployee=0;//Initialise cette variable qui cummule le total par employé
		

			//Bonus A: 
			//HORS PROMO SEULEMENT: [BEST]
			$Description_BonusA  	= "Advance (5$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusA 		 	= 5;					//Définir la valeur de ce bonus: x$/Commande
			$NomProduitPourBonusA 	= " (order_product_name like '%ifree plus advance%') "; //Nom de produit à utiliser pour le Filtre			
			$Coating_A_FiltrerA 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveA 	= "SELECT count(order_num) as Nbr_Bonus_AtteintA FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('moncton','monctonsafe')
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
			$TotalIfreePlusAdvance += $ResultatValeurBonusCourrantA;

		
		//Bonus B: 
			//HORS PROMO SEULEMENT: [BEST]
			$Description_BonusB  	= "Maxiwide (5$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusB 		 	= 5;					//Définir la valeur de ce bonus: x$/Commande
			$user_idB	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			
			$NomProduitPourBonusB 	= " (order_product_name like '%Maxiwide%') "; //Nom de produit à utiliser pour le Filtre	
			
			$Coating_A_FiltrerB 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveB 	= "SELECT count(order_num) as Nbr_Bonus_AtteintB FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('moncton','monctonsafe')
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
			//Fin partie Bonus C HORS PROMO		

		$SelectedStoreTotal    +=   $ResultatValeurBonusCourrantB;
		$TotalMaxiwide+=  $ResultatValeurBonusCourrantB;	
			
		//Afficher les résultats
		$message.= "<tr><td align=\"center\">". $DataDistinctEmployees[salesperson_id]. "</td>";
		
						
		$TotalBonusA = $ResultatValeurBonusCourrantA;
		$TotalBonusB = $ResultatValeurBonusCourrantB;
		//Ifree Plus Advance
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintA . " commandes x $ValeurBonusA$ ="."$ResultatValeurBonusCourrantA$</td>";
		
		
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintB . " commandes x $ValeurBonusB$ ="."$ResultatValeurBonusCourrantB$				
		</td>";
		

		
		//$SommedesBonusMagasinActuel = $SommedesBonusMagasinActuel + $TotalIfreePlusAdvance  + $TotalProgressifAdvance;
		$SommedesBonusMagasinActuel = $TotalIfreePlusAdvance + $TotalMaxiwide ;// + $TotalProgressifAdvance;
		//echo '<br><br>$SommedesBonusMagasinActuel:' . $SommedesBonusMagasinActuel;
		$TotalIncentiveForthisEmployee = number_format($TotalIncentiveForthisEmployee, 2);
		$message.= "<th bgcolor=\"#DAE0F2\"  align=\"center\">". $TotalIncentiveForthisEmployee ."$</th>";
		
	}//End While
	

	
	
		
		$SelectedStoreTotal = number_format($SelectedStoreTotal, 2);
	
	$queryNombreCommandePeriode = "SELECT COUNT(order_num) as NbrOriginales FROM ORDERS 
	WHERE order_date_processed BETWEEN '$date1' AND '$date2' 	AND user_id in ('moncton','monctonsafe')
	AND redo_order_num is null	";
	//echo '<br>'.$queryNombreCommandePeriode;
	$ResultNbrCommande = mysqli_query($con, $queryNombreCommandePeriode) or die 	('I cannot select items because #2g: '. $queryNbrCommande . mysqli_error($con));
	$DataNbrCommandes = mysqli_fetch_array($ResultNbrCommande,MYSQLI_ASSOC);
	
	
	$queryReprise = "SELECT COUNT(order_num) as NbrReprises FROM ORDERS 
	WHERE order_date_processed BETWEEN '$date1' AND '$date2' 	AND user_id in ('moncton','monctonsafe')
	AND redo_order_num is NOT null	";
	//echo '<br>'.$queryReprise;
	$ResultNbrReprise = mysqli_query($con, $queryReprise) or die 	('I cannot select items because #2g: '. $queryNbrCommande . mysqli_error($con));
	$DataNbrReprise = mysqli_fetch_array($ResultNbrReprise,MYSQLI_ASSOC);
	
	$SommedesBonusMagasinActuel = money_format('%.2n',$SommedesBonusMagasinActuel);
	$TotalIfreePlusAdvance = money_format('%.2n',$TotalIfreePlusAdvance);
	$TotalMaxiwide = money_format('%.2n',$TotalMaxiwide);

	
   $message.= "<tr>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\" align=\"right\">TOTAL POUR Moncton :</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$TotalIfreePlusAdvance$</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$TotalMaxiwide$</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$SommedesBonusMagasinActuel$</th>
		</tr>";
	

	$message.="</table>";

	echo $message;	

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

	$message_Admin	.= $message;	

	
$Report_Email	= array('dbeaulieu@direct-lens.com','gbruneau@entrepotdelalunette.com','lbouthillier@entrepotdelalunette.com','dbeaulieu@direct-lens.com');//LIVE
//$Report_Email	= array('dbeaulieu@direct-lens.com');//LIVE
$to_address		= $Report_Email;
	
//Copie Admin
$response=office365_mail($to_address, $from_address, 'Rapport Incentive Moncton Copie Admin', null, $message_Admin);


$time_start  = microtime(true);	


?>
