<?php 
//header('Content-type: text/html; charset=UTF-8');
//header('Content-type: text/html; charset=latin1_swedish_ci');

/*ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

//error_reporting(E_WARNING);



//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();


$datedujour  		= mktime(0,0,0,date("m"),date("d"),date("Y"));
$aujourdhui     	= date("Y/m/d", $datedujour);

echo '<br>Date du jour:'. $aujourdhui;

//Ajout pour transformer ce rapport bi-mensuel en rapport mensuel
$MoisEnCours 	= date("m", $datedujour);

 echo '<br>Mois en cours:'. $MoisEnCours;
 
$MoisEnCours=$MoisEnCours-1;//Pour sélectionner le mois qui vient de se terminer
$AnneeEnCours  = date("Y", $datedujour); 

if ($MoisEnCours==0){
	$MoisEnCours=12;	
	//$AnneeEnCours = $AnneeEnCours-1;
}

echo '<br>Année en cours:'. $AnneeEnCours;
switch($MoisEnCours){
		case 1:	$AnneeEnCours = $AnneeEnCours  ;	$JourDebut=$AnneeEnCours."-01-01";	$JourFin=$AnneeEnCours."-01-31";		break; //Janvier 
		case 2: $AnneeEnCours = $AnneeEnCours  ;	$JourDebut=$AnneeEnCours."-02-01";	$JourFin=$AnneeEnCours."-02-29";		break; //Février
		case 3: $AnneeEnCours = $AnneeEnCours  ;	$JourDebut=$AnneeEnCours."-03-01";	$JourFin=$AnneeEnCours."-03-31";		break; //Mars
		case 4: $AnneeEnCours = $AnneeEnCours  ;	$JourDebut=$AnneeEnCours."-04-01";	$JourFin=$AnneeEnCours."-04-30";		break; //Avril
		case 5: $AnneeEnCours = $AnneeEnCours  ;	$JourDebut=$AnneeEnCours."-05-01";	$JourFin=$AnneeEnCours."-05-31";		break; //Mai
		case 6: $AnneeEnCours = $AnneeEnCours  ;	$JourDebut=$AnneeEnCours."-06-01";	$JourFin=$AnneeEnCours."-06-30";		break; //Juin
		case 7: $AnneeEnCours = $AnneeEnCours  ;	$JourDebut=$AnneeEnCours."-07-01";	$JourFin=$AnneeEnCours."-07-31";		break; //Juillet
		case 8: $AnneeEnCours = $AnneeEnCours  ;	$JourDebut=$AnneeEnCours."-08-01";	$JourFin=$AnneeEnCours."-08-31";		break; //Août
		case 9: $AnneeEnCours = $AnneeEnCours  ;	$JourDebut=$AnneeEnCours."-09-01";	$JourFin=$AnneeEnCours."-09-30";		break; //Septembre
		case 10:$AnneeEnCours = $AnneeEnCours  ;	$JourDebut=$AnneeEnCours."-10-01";	$JourFin=$AnneeEnCours."-10-31";		break; //Octobre
		case 11:$AnneeEnCours = $AnneeEnCours  ;	$JourDebut=$AnneeEnCours."-11-01";	$JourFin=$AnneeEnCours."-11-30";		break; //Novembre
		case 12:$AnneeEnCours = $AnneeEnCours-1;	$JourDebut=$AnneeEnCours."-12-01";	$JourFin=$AnneeEnCours."-12-31";		break; //Décembre	
}

echo '<br>Année en cours:'. $AnneeEnCours;
echo '<br>Mois en cours:'. $MoisEnCours;

echo '<br>JourDebut:'. $JourDebut;
echo '<br>JourFin:'. $JourFin.'<br><br>';
include('../sec_connectEDLL.inc.php');
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

$LargeurColspanTableau =10;	//Nombre de TD dans le tableau
$WidthTableau = "100%";		//Pixels

//EDLL.CA PRODUCTION PART

//Prepare email 

//Paramètres communs
$from_address='donotreply@entrepotdelalunette.com';

for ($i = 1; $i <= 18 ; $i++) {
		//echo '<br> Magasin: '. $i;
		switch($i){
			case  1:  $Userid =  " ('chicoutimi')";    	$Partie = 'Chicoutimi';	 	
			$send_to_address = array('rapports@direct-lens.com');
			ob_start();
			break;
			
			
			case  2:  $Userid =  " ('levis')";    		$Partie = 'Lévis';			
			$send_to_address = array('rapports@direct-lens.com');
			ob_start();
			break;
			
			
			case  3:  $Userid =  " ('entrepotquebec')"; $Partie = 'Québec';			
			$send_to_address = array('rapports@direct-lens.com');
			ob_start();
			break;
			
			
			case  4:  $Userid =  " ('entrepotdr')";    	$Partie = 'Drummondville';	
			$send_to_address = array('rapports@direct-lens.com');
			ob_start();
			break;
			
			
			case  5:  $Userid =  " ('granby')";    		$Partie = 'Granby';	  		
			$send_to_address = array('rapports@direct-lens.com');
			ob_start();
			break;
			
			
			case  6:  $Userid =  " ('sherbrooke')";    	$Partie = 'Sherbrooke';	  	
			$send_to_address = array('rapports@direct-lens.com');
			ob_start();
			break;
			
			
			case  7:  $Userid =  " ('entrepotifc')";    $Partie = 'Trois-Rivières';	$send_to_address = array('rapports@direct-lens.com');
			ob_start();
			break;
			
			
			case  8:  $Userid =  " ('gatineau')";		$Partie = 'Gatineau';	 	$send_to_address = array('rapports@direct-lens.com');
			ob_start();
			break;
			
			
			case  9:  $Userid =  " ('laval')";    		$Partie = 'Laval';	  		$send_to_address = array('rapports@direct-lens.com');
			ob_start();
			break;
			
			
			case  10: $Userid =  " ('longueuil')";    	$Partie = 'Longueuil';	  	$send_to_address = array('rapports@direct-lens.com');
			ob_start();
			break;
			
			
			case  11: $Userid =  " ('stjerome')";    	$Partie = 'Saint-Jérôme';	$send_to_address = array('rapports@direct-lens.com');
			ob_start();
			break;
			
			
			case  12: $Userid =  " ('terrebonne')";    	$Partie = 'Terrebonne';		$send_to_address = array('rapports@direct-lens.com');
			ob_start();
			break;
			
			
			case  13: $Userid =  " ('edmundston')";    	$Partie = 'Edmundston';		$send_to_address = array('rapports@direct-lens.com');
			ob_start();
			break;
			
			
			case  14: $Userid =  " ('warehousehal')";   $Partie = 'Halifax';		$send_to_address = array('rapports@direct-lens.com');
			ob_start();
			break;

			case  15: $Userid =  " ('fredericton')";   $Partie = 'fredericton';		$send_to_address = array('rapports@direct-lens.com');
			ob_start();
			break;

			case  16: $Userid =  " ('88666')";   $Partie = '#88666-GR';		       $send_to_address = array('rapports@direct-lens.com');
			ob_start();
			break;

			case  17: $Userid =  " ('stjohn')";   $Partie = 'stjohn';		       $send_to_address = array('rapports@direct-lens.com');
			ob_start();

			case  18: $Userid =  " ('dartmouth')";   $Partie = 'dartmouth';		       $send_to_address = array('rapports@direct-lens.com');
			ob_start();
			break;
		}//End Switch
		
	$TotalIfreePlusAdvance  = 0;//Initialise le compteur de bonus Ifree_Plus_Advance
	$TotalProgressifAdvance = 0;//Initialise le compteur de bonus Progressif_Advance	
	

		
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
		<th  colspan=\"$LargeurColspanTableau\"><h3>Rapport Incitatifs $Partie $JourDebut-$JourFin </h3></th>
	</tr>";
	
	

	$store	 			= $Userid;		
	$StoreDescription	= $Partie;
	$message_Halifax.= "<tr valign=\"middle\"><th bgcolor=\"#EFE9E7\" colspan=\"$LargeurColspanTableau\"><h3>Succursale:$StoreDescription</h3></th></tr>";
	$message_Halifax.=CalculerIncentiveEDLL($store,$StoreDescription,$JourDebut,$JourFin);
	$message_Halifax.="</table>";
	//$queryNumberOrders="SELECT COUNT(order_num) FROM orders WHERE user_id in $store AND )";

	echo $message;	

	//Envoie du rapport COMPLET DE TOUS LES MAGASINS par courriel à Daniel, Karine, Amina et moi.
	$subject ="Rapport Global Incitatif $Partie pour la période [$JourDebut-$JourFin]";

	
	//$to_address		= $Report_Email;
	$to_address		= $send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	//echo 'Envoie du rapport en cours..<br>';
	//$response=office365_mail($to_address, $from_address, $subject, null, $message_Halifax);
	//echo '<br>message sent';

	//echo 'resultat'  . $response;
	//echo "<br><br>success: " . $to_address ;
		$currentAcct = "  ";
		$currentCompany=" ";

	$message_Admin	.= $message_Halifax;	 

	
	$Report_Email	= array('rapports@direct-lens.com','dbeaulieu@direct-lens.com','gbruneau@entrepotdelalunette.com','lbouthillier@entrepotdelalunette.com','dbeaulieu@direct-lens.com');//LIVE
	//$Report_Email	= array('fdjibrilla@entrepotdelalunette.com');//LIVE
	$to_address		= $Report_Email;
	$response=office365_mail($to_address, $from_address, $subject, null, $message_Admin);
	
	echo '<br>'.$message_Admin.'<br>';
	
	//*******************************

	
	// Générer le contenu HTML du rapport
	$contenuHtml = ob_get_clean();

	// Créez un nom de fichier unique avec un horodatage
	$date = new DateTime();
	$timestamp = $date->format('Y-m-d_H-i-s');
	$nomFichier = 'r_advance_mensuel'.$Userid . $timestamp;

	// Enregistrez le contenu HTML dans un fichier
	$cheminFichierHtml = 'C:/All_Rapports_EDLL/MONTURE/' . $nomFichier . '.html';
	file_put_contents($cheminFichierHtml, $contenuHtml);

	// Enregistrez le rapport Excel
	/*$writer = new Xlsx($spreadsheet);
	$cheminFichierExcel = 'C:/All_Rapports_EDLL/MONTURE/' . $nomFichier . '.xlsx';
	$writer->save($cheminFichierExcel);*/
	

	echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';
	//echo 'Rapport sauvegardé au format Excel (XLSX) : ' . $cheminFichierExcel . '<br>';

	
	//*************************************

	
	
	
	$message_Admin="";
	
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
	include('../sec_connectEDLL.inc.php');
	echo "<br><br>Fonction CalculerIncentiveEDLL()<br>";
	
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
	$TotalIfreePlusAdvance  = 0;//Initialise le compteur de bonus Ifree_Plus_Advance
	$TotalProgressifAdvance = 0;//Initialise le compteur de bonus Progressif_Advance
	
	$message.= "
		<tr>
			<th align=\"center\">Employé</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">Vente de produit Ifree Plus Advance</th>
			<th bgcolor=\"#e0c1b3\" align=\"center\">Vente de produit Progressif Advance</th>
			<th bgcolor=\"#DAE0F2\" align=\"center\">Total</th>
		</tr>";
	
	
			
		
	while ($DataDistinctEmployees = mysqli_fetch_array($resultDistinctEmployees,MYSQLI_ASSOC)){
		$TotalIncentiveForthisEmployee=0;//Initialise cette variable qui cummule le total par employé
		
			
			
			
			//Bonus A: 
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
			
			//echo '<br> $queryIncentiveA:'. $queryIncentiveA;
			
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
			
			//echo '<br> $queryIncentiveB:'. $queryIncentiveB;
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
			

		//Afficher les résultats
		$message.= "<tr><td align=\"center\">". $DataDistinctEmployees[salesperson_id]. "</td>";
		
						
		$TotalBonusA = $ResultatValeurBonusCourrantA;
		$TotalBonusB = $ResultatValeurBonusCourrantB;
		//Ifree Plus Advance
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintA . " commandes x $ValeurBonusA$ ="."$ResultatValeurBonusCourrantA$</td>";
		
		
		$message.= 		"<td bgcolor=\"#e0c1b3\" align=\"center\">". $Nbr_Bonus_AtteintB . " commandes x $ValeurBonusB$ ="."$ResultatValeurBonusCourrantB$				
		</td>";
		

		
		//$SommedesBonusMagasinActuel = $SommedesBonusMagasinActuel + $TotalIfreePlusAdvance  + $TotalProgressifAdvance;
		$SommedesBonusMagasinActuel = $TotalIfreePlusAdvance  + $TotalProgressifAdvance;
		//echo '<br><br>$SommedesBonusMagasinActuel:' . $SommedesBonusMagasinActuel;
		$TotalIncentiveForthisEmployee = number_format($TotalIncentiveForthisEmployee, 2);
		$message.= "<th bgcolor=\"#DAE0F2\"  align=\"center\">". $TotalIncentiveForthisEmployee ."$</th>";
		
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
	
	$SommedesBonusMagasinActuel = money_format('%.2n',$SommedesBonusMagasinActuel);
	$TotalIfreePlusAdvance = money_format('%.2n',$TotalIfreePlusAdvance);
	$TotalProgressifAdvance = money_format('%.2n',$TotalProgressifAdvance);

	
   $message.= "<tr>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\" align=\"right\">TOTAL POUR $Description_Magasin :</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$TotalIfreePlusAdvance$</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$TotalProgressifAdvance$</th>
			<th bgcolor=\"#EFE9AE\" colspan=\"1\">$SommedesBonusMagasinActuel$</th>
		</tr>";
	

	

	//echo  $message;
	return $message;
		

	
}//END FUNCTION CalculerIncentiveEDLL
		
$time_start  = microtime(true);	

echo 'Rapport générés et envoyés aux courriels programmés, si vous ne l\'avez pas reçu, svp créez un ticket'. $to_address;

?>
