<?php
// Afficher toutes les erreurs/avertissements
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

// error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
// ini_set('display_errors', '1');
include("../sec_connect.inc.php"); // Fichier de DataBase:HBC
include('../sec_connectEDLL.inc.php');
include('../phpmailer_email_functions.inc.php');

// require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$datedujour = mktime(0,0,0,date("m"),date("d"),date("Y"));
$aujourdhui = date("Y/m/d", $datedujour);



echo '<br>Date du jour:'. $aujourdhui;

echo '<br>Mois en cours:'. $MoisEnCours;

$JourFin = $aujourdhui;

$tem = date("d", $datedujour) - 7;
$JourDebut = date("Y/m/$tem", $datedujour);

//$JourDebut = '2024-07-29';
//$JourFin = '2024-08-03';

echo '<br>JourDebut:'. $JourDebut;
echo '<br>JourFin:'. $JourFin.'<br><br>';

$message = "<html>";
$message .= "<head><style type='text/css'>
    <!--
    .TextSize {
        font-size: 12pt;
        font-family: Arial, Helvetica, sans-serif;
		text-align:center;
    }
    -->
    </style></head>";
$message .= "<body>";
$message .= "<table width=\"950\" cellpadding=\"2\" border=\"1\" cellspacing=\"0\" class=\"TextSize\">";
	$message .= "<tr>
	<td bgcolor=\"#00FFFF\"><br>Nom des Succursales</td>
	<td bgcolor=\"#00FFFF\"><br>Nombre Commandes STC:</td>
	<td bgcolor=\"#00FFFF\"><br>Nombre Commandes SWISS:</td>
	<td bgcolor=\"#00FFFF\"><br>Nombre Commandes HKO:</td>
	<td bgcolor=\"#00FFFF\"><br>Nombre Commandes GKB:</td>
	<td bgcolor=\"#00FFFF\"><br>Nombre Commandes KNR:</td>
	<td bgcolor=\"#00FFFF\"><br>Nombre Commandes OVG:</td>
	<td bgcolor=\"#00FFFF\"><br>Nombre Commandes PROCREA:</td>
	</tr>";
	
	// Initialiser les variables pour les totaux
$total_STC = 0;
$total_SWISS = 0;
$total_HKO = 0;
$total_GKB = 0;
$total_KNR = 0;
$total_OVG = 0;
$total_PROCREA = 0;

for ($i = 1; $i <= 19; $i++) {
    switch($i) {
        case  1: $Userid =  " orders.user_id IN ('entrepotifc','entrepotsafe')"; $Compagnie = 'L\'Entrepot de la lunette Trois-Rivieres'; $Succ = 'Trois-Rivieres'; $send_to_address = array('fdjibrilla@entrepotdelalunette.com'); break;
        case  2: $Userid =  " orders.user_id IN ('entrepotdr','safedr')"; $Compagnie = 'L\'Entrepot de la lunette Drummondville'; $Succ = 'Drummondville'; $send_to_address = array('fdjibrilla@entrepotdelalunette.com'); break;
        case  3: $Userid =  " orders.user_id IN ('warehousehal','warehousehalsafe')"; $Compagnie = 'Optical Warehouse Halifax'; $Succ = 'Halifax'; $send_to_address = array('fdjibrilla@entrepotdelalunette.com'); break;
        case  4: $Userid =  " orders.user_id IN ('laval','lavalsafe')"; $Compagnie = 'L\'Entrepot de la lunette Laval'; $Succ = 'Laval'; $send_to_address = array('fdjibrilla@entrepotdelalunette.com'); break;
        case  5: $Userid =  " orders.user_id IN ('terrebonne','terrebonnesafe')"; $Compagnie = 'L\'Entrepot de la lunette Terrebonne'; $Succ = 'Terrebonne'; $send_to_address = array('fdjibrilla@entrepotdelalunette.com'); break;
        case  6: $Userid =  " orders.user_id IN ('sherbrooke','sherbrookesafe')"; $Compagnie = 'L\'Entrepot de la lunette Sherbrooke'; $Succ = 'Sherbrooke'; $send_to_address = array('fdjibrilla@entrepotdelalunette.com'); break;
        case  7: $Userid =  " orders.user_id IN ('chicoutimi','chicoutimisafe')"; $Compagnie = 'L\'Entrepot de la lunette Chicoutimi'; $Succ = 'Chicoutimi'; $send_to_address = array('fdjibrilla@entrepotdelalunette.com'); break;
        case  8: $Userid =  " orders.user_id IN ('levis','levissafe')"; $Compagnie = 'L\'Entrepot de la lunette Lévis'; $Succ = 'Lévis'; $send_to_address = array('fdjibrilla@entrepotdelalunette.com'); break;
        case  9: $Userid =  " orders.user_id IN ('longueuil','longueuilsafe')"; $Compagnie = 'L\'Entrepot de la lunette Longueuil'; $Succ = 'Longueuil'; $send_to_address = array('fdjibrilla@entrepotdelalunette.com'); break;
        case 10: $Userid =  " orders.user_id IN ('granby','granbysafe')"; $Compagnie = 'L\'Entrepot de la lunette Granby'; $Succ = 'Granby'; $send_to_address = array('fdjibrilla@entrepotdelalunette.com'); break;
        case 11: $Userid =  " orders.user_id IN ('entrepotquebec','quebecsafe')"; $Compagnie = 'L\'Entrepot de la lunette Quebec'; $Succ = 'Quebec'; $send_to_address = array('fdjibrilla@entrepotdelalunette.com'); break;
        case 12: $Userid =  " orders.user_id IN ('gatineau','gatineausafe')"; $Compagnie = 'L\'Entrepot de la lunette Gatineau'; $Succ = 'Gatineau'; $send_to_address = array('fdjibrilla@entrepotdelalunette.com'); break;
        case 13: $Userid =  " orders.user_id IN ('stjerome','stjeromesafe')"; $Compagnie = 'L\'Entrepot de la lunette St-Jerome'; $Succ = 'St-Jerome'; $send_to_address = array('fdjibrilla@entrepotdelalunette.com'); break;
        case 14: $Userid =  " orders.user_id IN ('edmundston','edmundstonsafe')"; $Compagnie = 'L\'Entrepot de la lunette Edmundston'; $Succ = 'Edmundston'; $send_to_address = array('fdjibrilla@entrepotdelalunette.com'); break;
        case 15: $Userid =  " orders.user_id IN ('moncton','monctonsafe')"; $Compagnie = 'L\'Entrepot de la lunette Moncton'; $Succ = 'Moncton'; $send_to_address = array('fdjibrilla@entrepotdelalunette.com'); break;
        case 16: $Userid =  " orders.user_id IN ('fredericton','frederictonsafe')"; $Compagnie = 'L\'Entrepot de la lunette Fredericton'; $Succ = 'Fredericton'; $send_to_address = array('fdjibrilla@entrepotdelalunette.com'); break;
        case 17: $Userid =  " orders.user_id IN ('stjohn','stjohnsafe')"; $Compagnie = 'L\'Entrepot de la lunette St-John'; $Succ = 'St-John'; $send_to_address = array('fdjibrilla@entrepotdelalunette.com'); break;
		case 18: $Userid =  " orders.user_id IN ('sorel','sorelsafe')"; $Compagnie = 'L\'Entrepot de la lunette Sorel-tracy'; $Succ = 'Sorel-Tracy'; $send_to_address = array('fdjibrilla@entrepotdelalunette.com'); break;
		case 19: $Userid =  " orders.user_id IN ('vaudreuil','vaudreuilsafe')"; $Compagnie = 'L\'Entrepot de la lunette Vaudreuil'; $Succ = 'Vaudreuil'; $send_to_address = array('fdjibrilla@entrepotdelalunette.com'); break;
 
    }

    $queryCommandeParfournisseur_STC = "SELECT count(order_num) as Nbr_Commande_STC FROM orders 
    WHERE order_date_shipped BETWEEN '$AnneeEnCours$JourDebut' AND '$AnneeEnCours$JourFin'
    AND prescript_lab=3
    AND $Userid";
    $resultCommandeParfournisseur_STC = mysqli_query($con, $queryCommandeParfournisseur_STC) or die('I cannot select items because: ' . mysqli_error($con));
    $DataCommandeparFournisseur_STC = mysqli_fetch_array($resultCommandeParfournisseur_STC, MYSQLI_ASSOC);

    $queryCommandeParfournisseur_SWISS = "SELECT count(order_num) as Nbr_Commande_SWISS FROM orders 
    WHERE order_date_shipped BETWEEN '$AnneeEnCours$JourDebut' AND '$AnneeEnCours$JourFin'
    AND prescript_lab=10
    AND $Userid";
    $resultCommandeParfournisseur_SWISS = mysqli_query($con, $queryCommandeParfournisseur_SWISS) or die('I cannot select items because: ' . mysqli_error($con));
    $DataCommandeparFournisseur_SWISS = mysqli_fetch_array($resultCommandeParfournisseur_SWISS, MYSQLI_ASSOC);

    $queryCommandeParfournisseur_HKO = "SELECT count(order_num) as Nbr_Commande_HKO FROM orders 
    WHERE order_date_shipped BETWEEN '$AnneeEnCours$JourDebut' AND '$AnneeEnCours$JourFin'
    AND prescript_lab=25
    AND $Userid";
    $resultCommandeParfournisseur_HKO = mysqli_query($con, $queryCommandeParfournisseur_HKO) or die('I cannot select items because: ' . mysqli_error($con));
    $DataCommandeparFournisseur_HKO = mysqli_fetch_array($resultCommandeParfournisseur_HKO, MYSQLI_ASSOC);


	//OTHERS fournisseur debut
	
		
	//GKB
	$queryCommandeParfournisseur_GKB = "SELECT count(order_num) as Nbr_Commande_GKB FROM orders 
	WHERE order_date_shipped BETWEEN '$AnneeEnCours$JourDebut' AND '$AnneeEnCours$JourFin'
	AND prescript_lab=69
	AND $Userid";
	//echo '<br>Requete GKB: '. $queryCommandeParfournisseur_GKB;
	$resultCommandeParfournisseur_GKB=mysqli_query($con,$queryCommandeParfournisseur_GKB)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataCommandeparFournisseur_GKB=mysqli_fetch_array($resultCommandeParfournisseur_GKB,MYSQLI_ASSOC);


	//KNR
	$queryCommandeParfournisseur_KNR = "SELECT count(order_num) as Nbr_Commande_KNR FROM orders 
	WHERE order_date_shipped BETWEEN '$AnneeEnCours$JourDebut' AND '$AnneeEnCours$JourFin'
	AND prescript_lab=73
	AND $Userid";
	//echo '<br>Requete KNR: '. $queryCommandeParfournisseur_KNR;
	$resultCommandeParfournisseur_KNR=mysqli_query($con,$queryCommandeParfournisseur_KNR)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataCommandeparFournisseur_KNR=mysqli_fetch_array($resultCommandeParfournisseur_KNR,MYSQLI_ASSOC);


	//Right Optical
	$queryCommandeParfournisseur_OVG = "SELECT count(order_num) as Nbr_Commande_OVG FROM orders 
	WHERE order_date_shipped BETWEEN '$AnneeEnCours$JourDebut' AND '$AnneeEnCours$JourFin'
	AND prescript_lab=76
	AND $Userid";
	//echo '<br>Requete OVG: '. $queryCommandeParfournisseur_OVG;
	$resultCommandeParfournisseur_OVG=mysqli_query($con,$queryCommandeParfournisseur_OVG)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataCommandeparFournisseur_OVG=mysqli_fetch_array($resultCommandeParfournisseur_OVG,MYSQLI_ASSOC);


	//PROCREA
	$queryCommandeParfournisseur_PROCREA = "SELECT count(order_num) as Nbr_Commande_PROCREA FROM orders 
	WHERE order_date_shipped BETWEEN '$AnneeEnCours$JourDebut' AND '$AnneeEnCours$JourFin'
	AND prescript_lab=77                                                                                                                                                                                        
	AND $Userid";
	//echo '<br>Requete PROCREA: '. $queryCommandeParfournisseur_PROCREA;
	$resultCommandeParfournisseur_PROCREA=mysqli_query($con,$queryCommandeParfournisseur_PROCREA)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataCommandeparFournisseur_PROCREA=mysqli_fetch_array($resultCommandeParfournisseur_PROCREA,MYSQLI_ASSOC);
	
	
	
	
	//others fournisseur fin
	
	
	
	
	
	
	
	

    $NbrCommande_STC = $DataCommandeparFournisseur_STC["Nbr_Commande_STC"];
    $NbrCommande_SWISS = $DataCommandeparFournisseur_SWISS["Nbr_Commande_SWISS"];
    $NbrCommande_HKO = $DataCommandeparFournisseur_HKO["Nbr_Commande_HKO"];
    $NbrCommande_GKB = $DataCommandeparFournisseur_GKB["Nbr_Commande_GKB"];
	$NbrCommande_KNR = $DataCommandeparFournisseur_KNR["Nbr_Commande_KNR"];
	$NbrCommande_OVG = $DataCommandeparFournisseur_OVG["Nbr_Commande_OVG"];
	$NbrCommande_PROCREA = $DataCommandeparFournisseur_PROCREA["Nbr_Commande_PROCREA"];


    echo "<br>" . $Compagnie;
    echo "<br>Nombre Commandes STC: " . $NbrCommande_STC;
    echo "<br>Nombre Commandes SWISS: " . $NbrCommande_SWISS;
    echo "<br>Nombre Commandes HKO: " . $NbrCommande_HKO;
    echo "<br>Nombre Commandes GKB: " . $NbrCommande_GKB;
	echo "<br>Nombre Commandes KNR: " . $NbrCommande_KNR;
	echo "<br>Nombre Commandes OVG: " . $NbrCommande_OVG;
	echo "<br>Nombre Commandes PROCREA: " . $NbrCommande_PROCREA;
    echo "<br>";
	
	   // Ajouter les nombres de commandes aux totaux
    $total_STC += $NbrCommande_STC;
    $total_SWISS += $NbrCommande_SWISS;
    $total_HKO += $NbrCommande_HKO;
    $total_GKB += $NbrCommande_GKB;
    $total_KNR += $NbrCommande_KNR;
    $total_OVG += $NbrCommande_OVG;
    $total_PROCREA += $NbrCommande_PROCREA;
	
	    echo "<br>" . $Compagnie;
    echo "<br>Nombre TOTAL Commandes STC: " . $total_STC;
    echo "<br>Nombre TOTAL Commandes SWISS: " . $total_SWISS;
    echo "<br>Nombre TOTAL Commandes HKO: " . $total_HKO;
    echo "<br>Nombre TOTAL Commandes GKB: " . $total_GKB;
	echo "<br>Nombre TOTAL Commandes KNR: " . $total_KNR;
	echo "<br>Nombre TOTAL Commandes OVG: " . $total_OVG;
	echo "<br>Nombre TOTAL Commandes PROCREA: " . $total_PROCREA;
    echo "<br>";

    $message .= "<tr>
	<td bgcolor=\"#00FFFF\"><br>$Compagnie</td>
	<td bgcolor=\"#DDDDDD\">$NbrCommande_STC</td>
	<td bgcolor=\"#FFFFFF\">$NbrCommande_SWISS</td>
	<td bgcolor=\"#DDDDDD\">$NbrCommande_HKO</td>
	<td bgcolor=\"#FFFFFF\">$NbrCommande_GKB</td>
	<td bgcolor=\"#DDDDDD\">$NbrCommande_KNR</td>
	<td bgcolor=\"#FFFFFF\">$NbrCommande_OVG</td>
	<td bgcolor=\"#DDDDDD\">$NbrCommande_PROCREA</td>
	</tr>";
}


	$message .= "<tr>
	<td bgcolor=\"#00FFFF\"><br>TOTAL </td>
	<td bgcolor=\"#00FFFF\"><br>$total_STC</td>
	<td bgcolor=\"#00FFFF\"><br>$total_SWISS</td>
	<td bgcolor=\"#00FFFF\"><br>$total_HKO</td>
	<td bgcolor=\"#00FFFF\"><br>$total_GKB</td>
	<td bgcolor=\"#00FFFF\"><br>$total_KNR</td>
	<td bgcolor=\"#00FFFF\"><br>$total_OVG</td>
	<td bgcolor=\"#00FFFF\"><br>$total_PROCREA</td>
	</tr>";



$message .= "</table></body></html>";




$send_to_address = array('dbeaulieu@direct-lens.com','rapports@direct-lens.com','fdjibrilla@entrepotdelalunette.com');//LIVE
//$send_to_address = array('fdjibrilla@entrepotdelalunette.com');//TEST
echo "<br>".var_dump($send_to_address);
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Vente par Fournisseur toutes les Succursales [$AnneeEnCours-$JourDebut $AnneeEnCours-$JourFin]";
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
