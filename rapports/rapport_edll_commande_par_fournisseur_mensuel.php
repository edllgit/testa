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

//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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

echo '<br>Année en cours:'. $AnneeEnCours;
echo '<br>Mois en cours:'. $MoisEnCours;

echo '<br>JourDebut:'. $JourDebut;
echo '<br>JourFin:'. $JourFin.'<br><br>';



//Boucle For qui passe les différents magasins pour générer le tableau correspondant à ce magasin. 1 courriel par magasin.


for ($i = 1; $i <= 19; $i++) {


//echo '<br><br>Valeur de I:'. $i ;

switch($i){
	case  1: $Userid =  " orders.user_id IN ('entrepotifc','entrepotsafe')";      $Compagnie = 'L\'Entrepot de la lunette Trois-Rivieres';	   $Succ = 'Trois-Rivieres';    
	//$send_to_address = array('rapports@direct-lens.com');break;
	$send_to_address = array('rapports@direct-lens.com');

	break;


	case  2: $Userid =  " orders.user_id IN ('entrepotdr','safedr')";        	  $Compagnie = 'L\'Entrepot de la lunette Drummondville';		 $Succ = 'Drummondville';    
	//$send_to_address = array('rapports@direct-lens.com'); break;
	$send_to_address = array('rapports@direct-lens.com');

	break;
	
	case  3: $Userid =  " orders.user_id IN ('warehousehal','warehousehalsafe')"; $Compagnie = 'Optical Warehouse Halifax'; 					$Succ = 'Halifax'; 	  
	 //$send_to_address = array('rapports@direct-lens.com');break;
	$send_to_address = array('rapports@direct-lens.com');

	break;
	 
	case  4: $Userid =  " orders.user_id IN ('laval','lavalsafe')"; 			  $Compagnie = 'L\'Entrepot de la lunette Laval';	  			 $Succ = 'Laval'; 			   
	//$send_to_address = array('rapports@direct-lens.com'); break;
	$send_to_address = array('rapports@direct-lens.com');

	break;
	
	/*case  5: $Userid =  " orders.user_id IN ('montreal','montrealsafe')";         $Compagnie = 'L\'Entrepot de la lunette Montreal HBC Zone Tendance 1';   $Succ = 'MTL-ZT1 HBC'; 	
	//$send_to_address = array('dbeaulieu@direct-lens.com',''); break;
	$send_to_address = array('rapports@direct-lens.com');
<<<<<<< HEAD
	//ob_start();
	break;*/

	
	case  6: $Userid =  " orders.user_id IN ('terrebonne','terrebonnesafe')"; 	  $Compagnie = 'L\'Entrepot de la lunette Terrebonne'; 	  $Succ = 'Terrebonne'; 			   
	//$send_to_address = array('rapports@direct-lens.com'); break;
	$send_to_address = array('rapports@direct-lens.com');

	break;
	
	case  7: $Userid =  " orders.user_id IN ('sherbrooke','sherbrookesafe')";     $Compagnie = 'L\'Entrepot de la lunette Sherbrooke'; 		  $Succ = 'Sherbrooke'; 		  
	//$send_to_address = array('rapports@direct-lens.com'); break;
	$send_to_address = array('rapports@direct-lens.com');

	break;
	 
	case  8: $Userid =  " orders.user_id IN ('chicoutimi','chicoutimisafe')";     $Compagnie = 'L\'Entrepot de la lunette Chicoutimi';		  $Succ = 'Chicoutimi'; 	       
	//$send_to_address = array('rapports@direct-lens.com'); break;
	$send_to_address = array('rapports@direct-lens.com');

	break;
	
	case  9: $Userid =  " orders.user_id IN ('levis','levissafe')"; 			  $Compagnie = 'L\'Entrepot de la lunette Lévis';      		  $Succ = 'Lévis'; 		   
	//$send_to_address = array('rapports@direct-lens.com'); break;
	$send_to_address = array('rapports@direct-lens.com');

	break;
	
	case 10: $Userid =  " orders.user_id IN ('longueuil','longueuilsafe')";       $Compagnie = 'L\'Entrepot de la lunette Longueuil';  $Succ = 'Longueuil'; 	  			   
	//$send_to_address = array('rapports@direct-lens.com'); break;
	$send_to_address = array('rapports@direct-lens.com');

	break;
	
	case 11: $Userid =  " orders.user_id IN ('granby','granbysafe')";             $Compagnie = 'L\'Entrepot de la lunette Granby';    $Succ = 'Granby'; 					   
	//$send_to_address = array('rapports@direct-lens.com'); break;
	$send_to_address = array('rapports@direct-lens.com');

	break;
	
	case 12: $Userid =  " orders.user_id IN ('entrepotquebec','quebecsafe')";       $Compagnie = 'L\'Entrepot de la lunette Quebec';  $Succ = 'Quebec'; 	  			   
	//$send_to_address = array('rapports@direct-lens.com'); break;
	$send_to_address = array('rapports@direct-lens.com');

	break;
	
	case 13: $Userid =  " orders.user_id IN ('gatineau','gatineausafe')";       $Compagnie = 'L\'Entrepot de la lunette Gatineau';  $Succ = 'Gatineau'; 	  			   
	//$send_to_address = array('rapports@direct-lens.com'); break;
	$send_to_address = array('rapports@direct-lens.com');

	break;

	case 14: $Userid =  " orders.user_id IN ('stjerome','stjeromesafe')";       $Compagnie = 'L\'Entrepot de la lunette St-Jerome';  $Succ = 'St-Jerome'; 	  			   
	//$send_to_address = array('rapports@direct-lens.com'); break;
	$send_to_address = array('rapports@direct-lens.com');

	break;

	case 15: $Userid =  " orders.user_id IN ('edmundston','edmundstonsafe')";       $Compagnie = 'L\'Entrepot de la lunette Edmundston';  $Succ = 'Edmundston'; 	  			   
	//$send_to_address = array('rapports@direct-lens.com'); break;
	$send_to_address = array('rapports@direct-lens.com');

	//ob_start();
	break;
	
	case 16: $Userid =  " orders.user_id IN ('moncton','monctonsafe')";       $Compagnie = 'L\'Entrepot de la lunette Moncton';  $Succ = 'Moncton'; 	  			   
	//$send_to_address = array('rapports@direct-lens.com'); break;
	$send_to_address = array('rapports@direct-lens.com');
	//ob_start();
	break;
	
	case 17: $Userid =  " orders.user_id IN ('fredericton','frederictonsafe')";       $Compagnie = 'L\'Entrepot de la lunette Fredericton';  $Succ = 'Fredericton'; 	  			   
	//$send_to_address = array('rapports@direct-lens.com'); break;
	$send_to_address = array('rapports@direct-lens.com');
	//ob_start();

	break;
	
	case 18: $Userid =  " orders.user_id IN ('stjohn','stjohnsafe')";       $Compagnie = 'L\'Entrepot de la lunette St-John';  $Succ = 'St-John'; 	  			   
	//$send_to_address = array('rapports@direct-lens.com'); break;
	$send_to_address = array('rapports@direct-lens.com');
	//ob_start();

	break;

	case 19: $Userid =  " orders.user_id IN ('88666')";       $Compagnie = 'Griffe Trois-rivieres';  $Succ = 'Griffe lunetier #88666'; 	  			   
	//$send_to_address = array('rapports@direct-lens.com'); break;
	$send_to_address = array('rapports@direct-lens.com');
	//ob_start();

	break;
}//End Switch

$queryCommandeParfournisseur_STC = "SELECT count(order_num) as Nbr_Commande_STC FROM orders 
WHERE order_date_shipped BETWEEN '$AnneeEnCours-$JourDebut' AND '$AnneeEnCours-$JourFin'
AND prescript_lab=3
AND $Userid";

//SAINT-CATHARINES
//echo '<br>Requete STC: '. $queryCommandeParfournisseur_STC;
$resultCommandeParfournisseur_STC=mysqli_query($con,$queryCommandeParfournisseur_STC)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCommandeparFournisseur_STC=mysqli_fetch_array($resultCommandeParfournisseur_STC,MYSQLI_ASSOC);



//SWISSCOAT
$queryCommandeParfournisseur_SWISS = "SELECT count(order_num) as Nbr_Commande_SWISS FROM orders 
WHERE order_date_shipped BETWEEN '$AnneeEnCours-$JourDebut' AND '$AnneeEnCours-$JourFin'
AND prescript_lab=10
AND $Userid";
//echo '<br>Requete SWISS: '. $queryCommandeParfournisseur_SWISS;
$resultCommandeParfournisseur_SWISS=mysqli_query($con,$queryCommandeParfournisseur_SWISS)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCommandeparFournisseur_SWISS=mysqli_fetch_array($resultCommandeParfournisseur_SWISS,MYSQLI_ASSOC);


//HKO
$queryCommandeParfournisseur_HKO = "SELECT count(order_num) as Nbr_Commande_HKO FROM orders 
WHERE order_date_shipped BETWEEN '$AnneeEnCours-$JourDebut' AND '$AnneeEnCours-$JourFin'
AND prescript_lab=25
AND $Userid";
//echo '<br>Requete HKO: '. $queryCommandeParfournisseur_HKO;
$resultCommandeParfournisseur_HKO=mysqli_query($con,$queryCommandeParfournisseur_HKO)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCommandeparFournisseur_HKO=mysqli_fetch_array($resultCommandeParfournisseur_HKO,MYSQLI_ASSOC);


//GKB
$queryCommandeParfournisseur_GKB = "SELECT count(order_num) as Nbr_Commande_GKB FROM orders 
WHERE order_date_shipped BETWEEN '$AnneeEnCours-$JourDebut' AND '$AnneeEnCours-$JourFin'
AND prescript_lab=69
AND $Userid";
//echo '<br>Requete GKB: '. $queryCommandeParfournisseur_GKB;
$resultCommandeParfournisseur_GKB=mysqli_query($con,$queryCommandeParfournisseur_GKB)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCommandeparFournisseur_GKB=mysqli_fetch_array($resultCommandeParfournisseur_GKB,MYSQLI_ASSOC);


//KNR
$queryCommandeParfournisseur_KNR = "SELECT count(order_num) as Nbr_Commande_KNR FROM orders 
WHERE order_date_shipped BETWEEN '$AnneeEnCours-$JourDebut' AND '$AnneeEnCours-$JourFin'
AND prescript_lab=73
AND $Userid";
//echo '<br>Requete KNR: '. $queryCommandeParfournisseur_KNR;
$resultCommandeParfournisseur_KNR=mysqli_query($con,$queryCommandeParfournisseur_KNR)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCommandeparFournisseur_KNR=mysqli_fetch_array($resultCommandeParfournisseur_KNR,MYSQLI_ASSOC);

//OVG LAB
$queryCommandeParfournisseur_OVG = "SELECT count(order_num) as Nbr_Commande_OVG FROM orders 
WHERE order_date_shipped BETWEEN '$AnneeEnCours-$JourDebut' AND '$AnneeEnCours-$JourFin'
AND prescript_lab=76
AND $Userid";
//echo '<br>Requete OVG: '. $queryCommandeParfournisseur_OVG;
$resultCommandeParfournisseur_OVG=mysqli_query($con,$queryCommandeParfournisseur_OVG)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCommandeparFournisseur_OVG=mysqli_fetch_array($resultCommandeParfournisseur_OVG,MYSQLI_ASSOC);


//PROCREA
$queryCommandeParfournisseur_PROCREA = "SELECT count(order_num) as Nbr_Commande_PROCREA FROM orders 
WHERE order_date_shipped BETWEEN '$AnneeEnCours-$JourDebut' AND '$AnneeEnCours-$JourFin'
AND prescript_lab=77
AND $Userid";
//echo '<br>Requete PROCREA: '. $queryCommandeParfournisseur_PROCREA;
$resultCommandeParfournisseur_PROCREA=mysqli_query($con,$queryCommandeParfournisseur_PROCREA)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCommandeparFournisseur_PROCREA=mysqli_fetch_array($resultCommandeParfournisseur_PROCREA,MYSQLI_ASSOC);


//Fabriquer le tableau des résultats
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
		$message.="<table width=\"950\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">
		<tr align=\"center\"><td align=\"centre\" colspan=\"6\">$Compagnie</tr>";
		$message.="<tr bgcolor=\"CCCCCC\">
							<th width=\"80\" align=\"center\">Saint-Catharines</th>
							<th width=\"80\" align=\"center\">Swisscoat</th>
							<th width=\"80\" align=\"center\">Central Lab</th>
							<th width=\"80\" align=\"center\">Essilor Lab</th>
							<th width=\"80\" align=\"center\">Can Lab</th>
							<th width=\"80\" align=\"center\">OVG Lab</th>
							<th width=\"80\" align=\"center\">PROCREA Lab</th>
							<th width=\"80\" align=\"center\">TOTAL</th>
					</tr>";

$Total = 0;
$Total = $DataCommandeparFournisseur_STC[Nbr_Commande_STC] + $DataCommandeparFournisseur_SWISS[Nbr_Commande_SWISS] + $DataCommandeparFournisseur_HKO[Nbr_Commande_HKO] + $DataCommandeparFournisseur_GKB[Nbr_Commande_GKB] +
 $DataCommandeparFournisseur_KNR[Nbr_Commande_KNR] + $DataCommandeparFournisseur_OVG[Nbr_Commande_OVG] + $DataCommandeparFournisseur_PROCREA[Nbr_Commande_PROCREA];

		$message.="<tr bgcolor=\"CCCCCC\">
							<th width=\"80\" align=\"center\">$DataCommandeparFournisseur_STC[Nbr_Commande_STC]</th>
							<th width=\"80\" align=\"center\">$DataCommandeparFournisseur_SWISS[Nbr_Commande_SWISS]</th>
							<th width=\"80\" align=\"center\">$DataCommandeparFournisseur_HKO[Nbr_Commande_HKO]</th>
							<th width=\"80\" align=\"center\">$DataCommandeparFournisseur_GKB[Nbr_Commande_GKB]</th>
							<th width=\"80\" align=\"center\">$DataCommandeparFournisseur_KNR[Nbr_Commande_KNR]</th>
							<th width=\"80\" align=\"center\">$DataCommandeparFournisseur_OVG[Nbr_Commande_OVG]</th>
							<th width=\"80\" align=\"center\">$DataCommandeparFournisseur_PROCREA[Nbr_Commande_PROCREA]</th>
							<th width=\"80\" align=\"center\">$Total</th>
					</tr>";


echo '<br><br>'. $message;




	// Créez un nom de fichier unique avec un horodatage
	$date = new DateTime();
	$timestamp = $date->format('Y-m-d_H-i-s');

	$nomFichier = 'r_commande_par_fournisseur_mensuel_'. $Succ. $timestamp;

	// Enregistrez le contenu HTML dans un fichier
	$cheminFichierHtml = 'C:/All_Rapports_EDLL/Fournisseur/Mensuel/' . $nomFichier . '.html';
	file_put_contents($cheminFichierHtml, $message);


	echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';


	
$send_to_address = array('dbeaulieu@direct-lens.com','rapports@direct-lens.com','fdjibrilla@entrepotdelalunette.com');//LIVE
//$send_to_address = array('rapports@direct-lens.com');//TEST
echo "<br>".var_dump($send_to_address);
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Vente par Fournisseur $Compagnie [$AnneeEnCours-$JourDebut $AnneeEnCours-$JourFin]";
$response=office365_mail($to_address, $from_address, $subject, null, $message);

}//END FOR


exit();




//SEND EMAIL



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