<?php 
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

$date1   = date("Y-m-d");
$date2  	= date("Y-m-d");

$date1   = $_POST[date1];
$date2   = $_POST[date2];

//Date du rapport
$ilya6jours 	= mktime(0,0,0,date("m"),date("d")-15,date("Y"));
$date1 = date("Y/m/d", $ilya6jours);

$ajd 			= mktime(0,0,0,date("m"),date("d")-2,date("Y"));
$date2   = date("Y/m/d", $ajd);


//DATES HARD CODÉS MANUELLE
/*
$date1    = "2019-09-04";
$date2    = "2019-09-15";
*/

include('../connexion_hbc.inc.php');
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

echo '<br>date1: '. $date1;
echo '<br>date2: '. $date2;
//$GrandTotalpourTouslesHBC = 0;
$LargeurColspanTableau 	= 13;	//Nombre de TD dans le tableau
$WidthTableau 			= 1330;	//Pixels

//HBC.CA PRODUCTION PART
//Prepare email 
$message="<html>
<head><style type='text/css'>
<!--
.TextSize {
	font-size: 10pt;
	font-family: Arial, Helvetica, sans-serif;
}
-->
</style></head>
<body>";			

$message.= "
<table width='$WidthTableau' border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">
	<tr>
		<th colspan=\"$LargeurColspanTableau\"><h3>HBC Incentive Report Between $date1 and $date2</h3></th>
	</tr>";
	
//Paramètres communs
$from_address='donotreply@entrepotdelalunette.com';


$message.=CalculerIncentiveHBC($date1,$date2);

echo $message;	

//Envoie du rapport COMPLET DE TOUS LES MAGASINS par courriel à Daniel, Karine, Amina et moi.

$subject ="Global HBC Incentive Report between $date1 and $date2 [All Stores]";
//$Report_Email	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');//A COMMENTER	


$Report_Email	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','gbruneau@entrepotdelalunette.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com',
'dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com');

$to_address		= $Report_Email;
$from_address='donotreply@entrepotdelalunette.com';
echo 'Envoie du rapport en cours..<br>';
$response=office365_mail($to_address, $from_address, $subject, null, $message);
echo '<br>message sent';

echo 'resultat' . $response;
echo "<br><br>success: " . $to_address ;
	$currentAcct = " ";
	$currentCompany=" ";


?>







<?php 

function CalculerIncentiveHBC($date1,$date2){
	/*
	Fonction avec 2 paramètres:
	1-Date de début
	2-Date de fin
	*/
	
	/* Cette fonction reçoit le numéro de magasin et affichera la liste des employés ainsi que les bonus qui leur est dû pour chaque 'Promo' évaluée	*/
	include('../connexion_hbc.inc.php');
	//echo "<br><br>Fonction CalculerIncentiveHBC()<br>";
	
	$SelectedStoreTotal=0;//Initialiser le total par magasin
	
	//Passer les différents employés qui ont fait des ventes durant la période évaluée
	$queryDistinctEmployeesHBC = 
	"SELECT distinct salesperson_id FROM orders
	WHERE order_date_processed BETWEEN '$date1' AND '$date2' 
	AND salesperson_id<>''	
	AND salesperson_id NOT IN ('21hbc-admin-all-access','Comptabilité Accounting','kl')
	AND salesperson_id NOT LIKE '%accounting%'
	AND user_id <> '88666'
	ORDER BY salesperson_id";
	
	echo $queryDistinctEmployeesHBC.'<br>';
	$resultDistinctEmployees = mysqli_query($con, $queryDistinctEmployeesHBC) or die ('I cannot select items because #2g: '. $queryDistinctEmployeesHBC . mysqli_error($con));
	
	
	$message.= "
		<tr>
			<th align=\"center\">&nbsp;</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\" colspan=\"5\">PROGRESSIVE</th>
			<th bgcolor=\"#B15E6C\" align=\"center\" colspan=\"4\">OTHER</th>
			<th bgcolor=\"#DAE0F2\" align=\"center\" colspan=\"2\">TOTAL</th>
		</tr>";
	
	
	$message.= "
		<tr>
			<th align=\"center\">Employee</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">HD IOT(A)</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">Precision Advance(B)</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">iFree(C)</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">Maxiwide(D)</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">Office HD(E)</th>
			<th bgcolor=\"#B15E6C\" align=\"center\">ABC Warranty(F)</th>
			<th bgcolor=\"#B15E6C\" align=\"center\">AR+ETC(G)</th>
			<th bgcolor=\"#B15E6C\" align=\"center\">XLR(H)</th>
			<th bgcolor=\"#B15E6C\" align=\"center\">Trans/Polar(I)</th>
			<th bgcolor=\"#DAE0F2\" align=\"center\">Total(N)</th>
			<th bgcolor=\"#DAE0F2\" align=\"center\">60% of Total(O)</th>
		</tr>";
	
	
	while ($DataDistinctEmployees = mysqli_fetch_array($resultDistinctEmployees,MYSQLI_ASSOC)){
		$TotalIncentiveForthisEmployee=0;//Initialise cette variable qui cummule le total par employé
		
	
		
		//Promo A: HD IOT 	
			$Description_BonusA 	= "HD IOT (1$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusA 		 	= 1;					//Définir la valeur de ce bonus: x$/Commande
			$NomProduitPourBonusA 	= " order_product_name LIKE '%HD IOT%' "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerA	 	= " 1=1 "; 			//Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveA 	= "SELECT count(order_num) as Nbr_Bonus_AtteintA FROM orders
			WHERE redo_order_num IS NULL 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusA
			AND $Coating_A_FiltrerA 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveA 	= mysqli_query($con, $queryIncentiveA)	or die ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveA . mysqli_error($con));
			$DataIncentiveA 	= mysqli_fetch_array($resultIncentiveA,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintA = $DataIncentiveA[Nbr_Bonus_AtteintA];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantA = $ValeurBonusA * $Nbr_Bonus_AtteintA;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantA;
		//Fin partie Promo A
		
		
		//Promo B Precision Advance
			$Description_BonusB 	= "PRECISION ADVANCE (2$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusB 		 	= 2;							//Définir la valeur de ce bonus: x$/Commande
			$NomProduitPourBonusB 	= " order_product_name LIKE '%Precision Advance%' "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerB	 	= " 1=1 "; 					//Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveB 	= "SELECT count(order_num) as Nbr_Bonus_AtteintB FROM orders
			WHERE redo_order_num IS NULL 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusB
			AND $Coating_A_FiltrerB
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveB 	= mysqli_query($con, $queryIncentiveB)	or die ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveB . mysqli_error($con));
			$DataIncentiveB 	= mysqli_fetch_array($resultIncentiveB,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintB = $DataIncentiveB[Nbr_Bonus_AtteintB];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantB = $ValeurBonusB * $Nbr_Bonus_AtteintB;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantB;
		//Fin partie Promo B
		
		
		//Promo C: iFree
			$Description_BonusC 	= "iFree (3$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusC 		 	= 3;					//Définir la valeur de ce bonus: x$/Commande
			$NomProduitPourBonusC 	= "order_product_name like '%ifree%'"; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerC 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveC 	= "SELECT count(order_num) as Nbr_Bonus_AtteintC FROM orders
			WHERE redo_order_num IS NULL 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusC
			AND $Coating_A_FiltrerC 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveC 	= mysqli_query($con, $queryIncentiveC)	or die ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveC . mysqli_error($con));
			$DataIncentiveC 	= mysqli_fetch_array($resultIncentiveC,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintC = $DataIncentiveC[Nbr_Bonus_AtteintC];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantC = $ValeurBonusC * $Nbr_Bonus_AtteintC;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantC;
		//Fin partie Promo C
		
		
		//Promo D: Maxiwide
			$Description_BonusD 	= "Maxiwide (4$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusD 		 	= 4;					//Définir la valeur de ce bonus: x$/Commande
			$NomProduitPourBonusD 	= "order_product_name like '%maxiwide%'"; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerD 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveD 	= "SELECT count(order_num) as Nbr_Bonus_AtteintD FROM orders
			WHERE redo_order_num IS NULL 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusD
			AND $Coating_A_FiltrerD
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveD 	= mysqli_query($con, $queryIncentiveD)	or die ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveD . mysqli_error($con));
			$DataIncentiveD 	= mysqli_fetch_array($resultIncentiveD,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintD = $DataIncentiveD[Nbr_Bonus_AtteintD];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantD = $ValeurBonusD * $Nbr_Bonus_AtteintD;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantD;
		//Fin partie Promo F

		
		

		//Promo E: Office HD
			$Description_BonusE 	= "Office HD (2$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusE 		 	= 2;					//Définir la valeur de ce bonus: x$/Commande
			$NomProduitPourBonusE 	= "order_product_name like '%i-Office%'"; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerE 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveE 	= "SELECT count(order_num) as Nbr_Bonus_AtteintE FROM orders
			WHERE redo_order_num IS NULL 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusE
			AND $Coating_A_FiltrerE 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveE 	= mysqli_query($con, $queryIncentiveE)	or die ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveE . mysqli_error($con));
			$DataIncentiveE 	= mysqli_fetch_array($resultIncentiveE,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintE = $DataIncentiveE[Nbr_Bonus_AtteintE];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantE = $ValeurBonusE * $Nbr_Bonus_AtteintE;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantE;
		//Fin partie Promo E	
		
	
		
		
		
		
		
		//Promo F:ABC Warranty
			$Description_BonusF 	= "ABC Warranty (2$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusF 		 	= 2;						//Définir la valeur de ce bonus: x$/Commande
			$NomProduitPourBonusF 	= " Warranty like '%Extended Warranty%' "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerF 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveF 	= "SELECT count(order_num) as Nbr_Bonus_AtteintF FROM orders
			WHERE redo_order_num IS NULL 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusF
			AND $Coating_A_FiltrerF 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveF 	= mysqli_query($con, $queryIncentiveF)	or die ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveF . mysqli_error($con));
			$DataIncentiveF 	= mysqli_fetch_array($resultIncentiveF,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintF = $DataIncentiveF[Nbr_Bonus_AtteintF];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantF = $ValeurBonusF * $Nbr_Bonus_AtteintF;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantF;
			//Fin partie Promo F
		
		
		
			//Promo G:AR+ETC
			$Description_BonusG 	= "AR+ETC (2$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusG 		 	= 2;						//Définir la valeur de ce bonus: x$/Commande
			$NomProduitPourBonusG 	= " (order_product_name like '%AR+ETC%' OR order_product_coating='SPC' OR order_product_coating='SPC Backside' OR order_product_name like '%AR Backside%' OR order_product_name like '%StressFree%' ) "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerG 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveG 	= "SELECT count(order_num) as Nbr_Bonus_AtteintG FROM orders
			WHERE redo_order_num IS NULL 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusG
			AND $Coating_A_FiltrerG 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
		
			//echo '<br>Query:'. $queryIncentiveG.'<br>';
			$resultIncentiveG 	= mysqli_query($con, $queryIncentiveG)	or die ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveG . mysqli_error($con));
			$DataIncentiveG 	= mysqli_fetch_array($resultIncentiveG,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintG = $DataIncentiveG[Nbr_Bonus_AtteintG];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantG = $ValeurBonusG * $Nbr_Bonus_AtteintG;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantG;
			//Fin partie Promo G
		
		
		
			//Promo H:XLR
			$Description_BonusH 	= "XLR (3$/job)"; 			//Description de ce qui donne droit au bonus
			$ValeurBonusH 		 	= 3;						//Définir la valeur de ce bonus: x$/Commande
			$NomProduitPourBonusH 	= " (order_product_name like '%Maxivue%' OR order_product_name like '%XLR%' OR order_product_coating in ('MaxiVue2','MaxiVue2 Backside')) "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerH 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveH 	= "SELECT count(order_num) as Nbr_Bonus_AtteintH FROM orders
			WHERE redo_order_num IS NULL 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusH
			AND $Coating_A_FiltrerH 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			//echo $queryIncentiveH. '<br><br>';
			$resultIncentiveH 	= mysqli_query($con, $queryIncentiveH)	or die ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveH . mysqli_error($con));
			$DataIncentiveH 	= mysqli_fetch_array($resultIncentiveH,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintH = $DataIncentiveH[Nbr_Bonus_AtteintH];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantH = $ValeurBonusH * $Nbr_Bonus_AtteintH;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantH;
			//Fin partie Promo H
		
		
		
		
			//Promo I:Transitions/Polarized
			$Description_BonusI 	= "Transitions/Polarized (3$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusI		 	= 3;								//Définir la valeur de ce bonus: x$/Commande
			$NomProduitPourBonusI 	= " (order_product_name like '%transitions%' OR order_product_name like '%polarized%') "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerI 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveI 	= "SELECT count(order_num) as Nbr_Bonus_AtteintI FROM orders
			WHERE redo_order_num IS NULL 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusI
			AND $Coating_A_FiltrerI
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveI 	= mysqli_query($con, $queryIncentiveI)	or die ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveI . mysqli_error($con));
			$DataIncentiveI 	= mysqli_fetch_array($resultIncentiveI,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintI = $DataIncentiveI[Nbr_Bonus_AtteintI];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantI = $ValeurBonusI * $Nbr_Bonus_AtteintI;	
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
		
		
					//PROG:Precision Advance
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintB . " x $ValeurBonusB$ ="."$ResultatValeurBonusCourrantB$</td>";
		
		
					//PROG:iFree
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintC . " x $ValeurBonusC$ ="."$ResultatValeurBonusCourrantC$</td>";
		
					
		
					//PROG:Maxiwide
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintD . " x $ValeurBonusD$ ="."$ResultatValeurBonusCourrantD$</td>";
		
		
					
					//OFFICE:Office HD
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintE . " x $ValeurBonusE$ ="."$ResultatValeurBonusCourrantE$</td>";
		
		
	
					//OTHER:ABC Warranty
		$message.= 		"<td bgcolor=\"#B15E6C\" align=\"center\">". $Nbr_Bonus_AtteintF . " x $ValeurBonusF$ ="."$ResultatValeurBonusCourrantF$</td>";
		
		
				  //OTHER:AR+ETC
		$message.= 		"<td bgcolor=\"#B15E6C\" align=\"center\">". $Nbr_Bonus_AtteintG . " x $ValeurBonusG$ ="."$ResultatValeurBonusCourrantG$</td>";
		
		
		  			//OTHER:XLR
		$message.= 		"<td bgcolor=\"#B15E6C\" align=\"center\">". $Nbr_Bonus_AtteintH . " x $ValeurBonusH$ ="."$ResultatValeurBonusCourrantH$</td>";
		
					//OTHER:Transitions/Polarized
		$message.= 		"<td bgcolor=\"#B15E6C\" align=\"center\">". $Nbr_Bonus_AtteintI . " x $ValeurBonusI$ ="."$ResultatValeurBonusCourrantI$</td>";
		

		$TotalIncentiveForthisEmployee = number_format($TotalIncentiveForthisEmployee, 2);
		$message.= "<th bgcolor=\"#DAE0F2\" align=\"center\">". $TotalIncentiveForthisEmployee ."$</th>";
		
		
		$SoixantePourcentTotalForthisEmployee=0.6*$TotalIncentiveForthisEmployee;
		$SoixantePourcentTotalForthisEmployee = number_format($SoixantePourcentTotalForthisEmployee, 2);
		$message.= "<th bgcolor=\"#DAE0F2\" align=\"center\">$SoixantePourcentTotalForthisEmployee$</th></tr>";	//Emplacement pour entrer le % à la main
	}//End While
	
	$SoixantePourcentTotalforStore = 0.6 * $SelectedStoreTotal;
	//Formatter les données
	$SoixantePourcentTotalforStore = number_format($SoixantePourcentTotalforStore, 2);
	$SelectedStoreTotal = number_format($SelectedStoreTotal, 2);
  $message.= "<tr>
			<th bgcolor=\"#EFE9AE\" colspan=\"10\" align=\"right\">TOTAL</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$SelectedStoreTotal$</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$SoixantePourcentTotalforStore$</th>
		</tr>";
	
	
	
	$GrandTotalpourTouslesHBC = $GrandTotalpourTouslesHBC + $SelectedStoreTotal;
	
	$message.= "<tr>
			<th colspan=\"16\" align=\"right\">&nbsp;</th>
		</tr>";
	return $message;
}//END FUNCTION CalculerIncentiveHBC



















function CalculerTotauxHBC($Userid_Magasin,$date1,$date2){
	/*
	Fonction avec 4 paramètres:
	1-User id du magasin Ex:88440
	2-Description du magasin Ex: 88440-Rideau
	3-Date de début
	4-Date de fin
	*/
	
	/* Cette fonction reçoit le numéro de magasin et affichera la liste des employés ainsi que les bonus qui leur est dû pour chaque 'Promo' évaluée	*/
	include('../connexion_hbc.inc.php');
	//echo "<br><br>Fonction CalculerIncentiveHBC()<br>";
	
	$SelectedStoreTotal=0;//Initialiser le total par magasin
	
	//Passer les différents employés qui ont fait des ventes durant la période évaluée
	$queryDistinctEmployeesHBC = 
	"SELECT distinct salesperson_id FROM orders
	
	WHERE order_date_processed BETWEEN '$date1' AND '$date2' 
	AND salesperson_id<>''	AND user_id='$Userid_Magasin'
	AND salesperson_id NOT IN ('21hbc-admin-all-access','Comptabilité Accounting','kl')
	AND salesperson_id NOT LIKE '%accounting%'
	ORDER BY salesperson_id";
	
	//echo $queryDistinctEmployeesHBC.'<br>';
	
	$resultDistinctEmployees = mysqli_query($con, $queryDistinctEmployeesHBC) or die ('I cannot select items because #2g: '. $queryDistinctEmployeesHBC . mysqli_error($con));
	
	

	
	while ($DataDistinctEmployees = mysqli_fetch_array($resultDistinctEmployees,MYSQLI_ASSOC)){
		$TotalIncentiveForthisEmployee=0;//Initialise cette variable qui cummule le total par employé
		
			
		//Promo A: HD IOT 	
			$Description_BonusA 	= "HD IOT (1$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusA 		 	= 1;					//Définir la valeur de ce bonus: x$/Commande
			$user_idA 	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusA 	= " order_product_name LIKE '%HD IOT%' "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerA	 	= " 1=1 "; 			//Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveA 	= "SELECT count(order_num) as Nbr_Bonus_AtteintA FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idA') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusA
			AND $Coating_A_FiltrerA 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveA 	= mysqli_query($con, $queryIncentiveA)	or die ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveA . mysqli_error($con));
			$DataIncentiveA 	= mysqli_fetch_array($resultIncentiveA,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintA = $DataIncentiveA[Nbr_Bonus_AtteintA];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantA = $ValeurBonusA * $Nbr_Bonus_AtteintA;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantA;
		//Fin partie Promo A
		
		
		//Promo B Precision Advance
			$Description_BonusB 	= "PRECISION ADVANCE (2$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusB 		 	= 2;							//Définir la valeur de ce bonus: x$/Commande
			$user_idB 	 		 	= $Userid_Magasin;				//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusB 	= " order_product_name LIKE '%Precision Advance%' "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerB	 	= " 1=1 "; 					//Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveB 	= "SELECT count(order_num) as Nbr_Bonus_AtteintB FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idB') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusB
			AND $Coating_A_FiltrerB
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveB 	= mysqli_query($con, $queryIncentiveB)	or die ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveB . mysqli_error($con));
			$DataIncentiveB 	= mysqli_fetch_array($resultIncentiveB,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintB = $DataIncentiveB[Nbr_Bonus_AtteintB];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantB = $ValeurBonusB * $Nbr_Bonus_AtteintB;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantB;
		//Fin partie Promo B
		
		
		//Promo C: iFree
			$Description_BonusC 	= "iFree (3$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusC 		 	= 3;					//Définir la valeur de ce bonus: x$/Commande
			$user_idC	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusC 	= "order_product_name like '%ifree%'"; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerC 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveC 	= "SELECT count(order_num) as Nbr_Bonus_AtteintC FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idC') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusC
			AND $Coating_A_FiltrerC 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveC 	= mysqli_query($con, $queryIncentiveC)	or die ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveC . mysqli_error($con));
			$DataIncentiveC 	= mysqli_fetch_array($resultIncentiveC,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintC = $DataIncentiveC[Nbr_Bonus_AtteintC];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantC = $ValeurBonusC * $Nbr_Bonus_AtteintC;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantC;
		//Fin partie Promo C
		
		
		//Promo D: Maxiwide
			$Description_BonusD 	= "Maxiwide (4$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusD 		 	= 4;					//Définir la valeur de ce bonus: x$/Commande
			$user_idD	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusD 	= "order_product_name like '%maxiwide%'"; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerD 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveD 	= "SELECT count(order_num) as Nbr_Bonus_AtteintD FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idD') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusD
			AND $Coating_A_FiltrerD
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveD 	= mysqli_query($con, $queryIncentiveD)	or die ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveD . mysqli_error($con));
			$DataIncentiveD 	= mysqli_fetch_array($resultIncentiveD,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintD = $DataIncentiveD[Nbr_Bonus_AtteintD];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantD = $ValeurBonusD * $Nbr_Bonus_AtteintD;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantD;
		//Fin partie Promo F

		
		

		//Promo E: Office HD
			$Description_BonusE 	= "Office HD (2$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusE 		 	= 2;					//Définir la valeur de ce bonus: x$/Commande
			$user_idE	 		 	= $Userid_Magasin;		//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusE 	= "order_product_name like '%i-Office%'"; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerE 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveE 	= "SELECT count(order_num) as Nbr_Bonus_AtteintE FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idE') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusE
			AND $Coating_A_FiltrerE 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveE 	= mysqli_query($con, $queryIncentiveE)	or die ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveE . mysqli_error($con));
			$DataIncentiveE 	= mysqli_fetch_array($resultIncentiveE,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintE = $DataIncentiveE[Nbr_Bonus_AtteintE];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantE = $ValeurBonusE * $Nbr_Bonus_AtteintE;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantE;
		//Fin partie Promo E	
		
	
		
		
		
		
		
		//Promo F:ABC Warranty
			$Description_BonusF 	= "ABC Warranty (2$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusF 		 	= 2;						//Définir la valeur de ce bonus: x$/Commande
			$user_idF	 		 	= $Userid_Magasin;			//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusF 	= " Warranty like '%Extended Warranty%' "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerF 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveF 	= "SELECT count(order_num) as Nbr_Bonus_AtteintF FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idF') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusF
			AND $Coating_A_FiltrerF 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveF 	= mysqli_query($con, $queryIncentiveF)	or die ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveF . mysqli_error($con));
			$DataIncentiveF 	= mysqli_fetch_array($resultIncentiveF,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintF = $DataIncentiveF[Nbr_Bonus_AtteintF];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantF = $ValeurBonusF * $Nbr_Bonus_AtteintF;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantF;
			//Fin partie Promo F
		
		
		
			//Promo G:AR+ETC
			$Description_BonusG 	= "AR+ETC (2$/job)"; 	//Description de ce qui donne droit au bonus
			$ValeurBonusG 		 	= 2;						//Définir la valeur de ce bonus: x$/Commande
			$user_idG	 		 	= $Userid_Magasin;			//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusG 	= " (order_product_name like '%AR+ETC%' OR order_product_coating='SPC' OR order_product_coating='SPC Backside' OR order_product_name like '%AR Backside%' OR order_product_name like '%StressFree%' ) "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerG 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveG 	= "SELECT count(order_num) as Nbr_Bonus_AtteintG FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idG') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusG
			AND $Coating_A_FiltrerG 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
		
			//echo '<br>Query:'. $queryIncentiveG.'<br>';
			$resultIncentiveG 	= mysqli_query($con, $queryIncentiveG)	or die ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveG . mysqli_error($con));
			$DataIncentiveG 	= mysqli_fetch_array($resultIncentiveG,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintG = $DataIncentiveG[Nbr_Bonus_AtteintG];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantG = $ValeurBonusG * $Nbr_Bonus_AtteintG;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantG;
			//Fin partie Promo G
		
		
		
			//Promo H:XLR
			$Description_BonusH 	= "XLR (3$/job)"; 			//Description de ce qui donne droit au bonus
			$ValeurBonusH 		 	= 3;						//Définir la valeur de ce bonus: x$/Commande
			$user_idH	 		 	= $Userid_Magasin;			//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusH 	= " (order_product_name like '%Maxivue%' OR order_product_name like '%XLR%' OR order_product_coating in ('MaxiVue2','MaxiVue2 Backside')) "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerH 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveH 	= "SELECT count(order_num) as Nbr_Bonus_AtteintH FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idH') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusH
			AND $Coating_A_FiltrerH 
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			//echo $queryIncentiveH. '<br><br>';
			$resultIncentiveH 	= mysqli_query($con, $queryIncentiveH)	or die ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveH . mysqli_error($con));
			$DataIncentiveH 	= mysqli_fetch_array($resultIncentiveH,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintH = $DataIncentiveH[Nbr_Bonus_AtteintH];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantH = $ValeurBonusH * $Nbr_Bonus_AtteintH;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantH;
			//Fin partie Promo H
		
				
			//Promo I:Transitions/Polarized
			$Description_BonusI 	= "Transitions/Polarized (3$/job)"; //Description de ce qui donne droit au bonus
			$ValeurBonusI		 	= 3;								//Définir la valeur de ce bonus: x$/Commande
			$user_idI	 		 	= $Userid_Magasin;					//Définir le nom d'utilisateur du magasin 
			$NomProduitPourBonusI 	= " (order_product_name like '%transitions%' OR order_product_name like '%polarized%') "; //Nom de produit à utiliser pour le Filtre	
			$Coating_A_FiltrerI 	= " 1=1 "; //Nom de coating à utiliser pour le Filtre		
			//La requête	
			$queryIncentiveI 	= "SELECT count(order_num) as Nbr_Bonus_AtteintI FROM orders
			WHERE redo_order_num IS NULL 
			AND user_id IN ('$user_idI') 
			AND salesperson_id='$DataDistinctEmployees[salesperson_id]'	
			AND $NomProduitPourBonusI
			AND $Coating_A_FiltrerI
			AND order_date_processed BETWEEN '$date1' AND '$date2'
			AND order_status NOT IN ('on hold', 'cancelled') 	
			ORDER BY order_product_name";
			$resultIncentiveI 	= mysqli_query($con, $queryIncentiveI)	or die ('<br><br>I cannot select items because 1: <br><br>'. $queryIncentiveI . mysqli_error($con));
			$DataIncentiveI 	= mysqli_fetch_array($resultIncentiveI,MYSQLI_ASSOC);
			$Nbr_Bonus_AtteintI = $DataIncentiveI[Nbr_Bonus_AtteintI];		
			//Calcul du bonus total mérité par CET Employé 
			$ResultatValeurBonusCourrantI = $ValeurBonusI * $Nbr_Bonus_AtteintI;	
			//Ajouter au cummul par employé
			$TotalIncentiveForthisEmployee+= $ResultatValeurBonusCourrantI;
			//Fin partie Promo I
		
		
		$SelectedStoreTotal += $ResultatValeurBonusCourrantA + $ResultatValeurBonusCourrantB + $ResultatValeurBonusCourrantC+ $ResultatValeurBonusCourrantD+ $ResultatValeurBonusCourrantE+
		$ResultatValeurBonusCourrantF +	$ResultatValeurBonusCourrantG +	$ResultatValeurBonusCourrantH +	$ResultatValeurBonusCourrantI +	$ResultatValeurBonusCourrantJ +	$ResultatValeurBonusCourrantK 
		+$ResultatValeurBonusCourrantL +$ResultatValeurBonusCourrantM;
		

		$TotalIncentiveForthisEmployee = number_format($TotalIncentiveForthisEmployee, 2);
		$message.= "<th bgcolor=\"#DAE0F2\" align=\"center\">". $TotalIncentiveForthisEmployee ."$</th>";
		
		$SoixantePourcentTotalForthisEmployee=0.6*$TotalIncentiveForthisEmployee;
		$SoixantePourcentTotalForthisEmployee = number_format($SoixantePourcentTotalForthisEmployee, 2);
		$message.= "<th bgcolor=\"#DAE0F2\" align=\"center\">$SoixantePourcentTotalForthisEmployee$</th></tr>";	//Emplacement pour entrer le % à la main
	}//End While
	
	$SoixantePourcentTotalforStore = 0.6 * $SelectedStoreTotal;
	//Formatter les données
	$SoixantePourcentTotalforStore = number_format($SoixantePourcentTotalforStore, 2);
	$SelectedStoreTotal = number_format($SelectedStoreTotal, 2);
	$message.= "<tr>
			<th bgcolor=\"#EFE9AE\" colspan=\"10\" align=\"right\">TOTAL FOR STORE $Description_Magasin :</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$SelectedStoreTotal$</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$SoixantePourcentTotalforStore$</th>
		</tr>";
	

	return $SelectedStoreTotal;
}//END FUNCTION CalculerTotauxHBC
		
$time_start = microtime(true);	



?>
