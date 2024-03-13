<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$time_start = microtime(true);	


//POUR ADMINISTRATEURS SEULEMENT CAR CONTIENT LES $$$
$aWeekAgo      = mktime(0,0,0,date("m"),date("d")-6,date("Y"));
$dateaweekago  = date("Y-m-d",$aWeekAgo );
$aujourdhui    = date("Y-m-d");//Aujourd'hui, journee  ou le rapport est execute Ex 19 janvier contiendra du 13 au 19 janvier 2015

//A REMETTRE EN COMMENTAIRE
/*
$dateaweekago  = "2018-04-15";
$aujourdhui    = "2018-04-21";
*/

//On débute le formulaire avant le FOR
$count    = 0;

//FOR pour parcourir les Succursales
for ($i = 1; $i <= 20; $i++) {
    echo '<br>'. $i;
	

$message  = "";
$message  = "<html>";
$message .= "
<head>
	<meta charset=\"utf-8\">
	<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
	<!-- Bootstrap core CSS -->
	<link href=\"http://www.direct-lens.com/bootstrap/css/bootstrap.min.css\" rel=\"stylesheet\">
	<!-- Custom styles for this template -->
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src=\"https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js\"></script>
	<script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
	<![endif]-->
</head>";

$message .="
<table class=\"table\" border=\"1\">
<thead>
	<th align=\"center\">Succursale</th>
	<th align=\"center\">Nombre de commandes (Originales)</th>
	<th align=\"center\">Valeur des commandes (Originales)</th>
	<th align=\"center\">Reprises</th>
	<th align=\"center\">Valeur des reprises</th>
</thead>";
	
	
switch($i){
	case  1: $Userid =  " orders.user_id IN ('entrepotifc','entrepotsafe')";      $Compagnie = 'L\'Entrepot de la lunette Trois-Rivieres';	   $Succ = 'Trois-Rivieres';    
	$send_to_address = array('rapports@direct-lens.com');

	break;
	//$send_to_address = array('rapports@direct-lens.com');break;
	
	case  2: $Userid =  " orders.user_id IN ('entrepotdr','safedr')";        	  $Compagnie = 'L\'Entrepot de la lunette Drummondville';		 $Succ = 'Drummondville';    
	$send_to_address = array('rapports@direct-lens.com'); 

	break;
	//$send_to_address = array('rapports@direct-lens.com');break;
	
	case  3: $Userid =  " orders.user_id IN ('warehousehal','warehousehalsafe')"; $Compagnie = 'Optical Warehouse Halifax'; 					$Succ = 'Halifax'; 	  
	 $send_to_address = array('rapports@direct-lens.com');

	 ;break;

	// $send_to_address = array('rapports@direct-lens.com');break;
	 
	 
	case  4: $Userid =  " orders.user_id IN ('laval','lavalsafe')"; 			  $Compagnie = 'L\'Entrepot de la lunette Laval';	  			 $Succ = 'Laval'; 			   
	$send_to_address = array('rapports@direct-lens.com'); 

	break;
	//$send_to_address = array('rapports@direct-lens.com');break;
	
	/*case  5: $Userid =  " orders.user_id IN ('montreal','montrealsafe')";         $Compagnie = 'L\'Entrepot de la lunette Montreal HBC Zone Tendance 1';   $Succ = 'MTL-ZT1 HBC'; 	
	$send_to_address = array('dbeaulieu@direct-lens.com','');
<<<<<<< HEAD
	//ob_start();
	 break; */

	
	case  6: $Userid =  " orders.user_id IN ('terrebonne','terrebonnesafe')"; 	  $Compagnie = 'L\'Entrepot de la lunette Terrebonne'; 	  $Succ = 'Terrebonne'; 			   
	$send_to_address = array('rapports@direct-lens.com'); break;
	//$send_to_address = array('rapports@direct-lens.com');break;
	
	case  7: $Userid =  " orders.user_id IN ('sherbrooke','sherbrookesafe')";     $Compagnie = 'L\'Entrepot de la lunette Sherbrooke'; 		  $Succ = 'Sherbrooke'; 		  
	 $send_to_address = array('rapports@direct-lens.com'); 

	 break;
	// $send_to_address = array('rapports@direct-lens.com');break;
	 
	case  8: $Userid =  " orders.user_id IN ('chicoutimi','chicoutimisafe')";     $Compagnie = 'L\'Entrepot de la lunette Chicoutimi';		  $Succ = 'Chicoutimi'; 	       
	$send_to_address = array('rapports@direct-lens.com'); 

	break;

	//$send_to_address = array('rapports@direct-lens.com');break;
	
	case  9: $Userid =  " orders.user_id IN ('levis','levissafe')"; 			  $Compagnie = 'L\'Entrepot de la lunette Lévis';      		  $Succ = 'Lévis'; 		   
	$send_to_address = array('rapports@direct-lens.com'); 

	break;
	//$send_to_address = array('rapports@direct-lens.com');break;
	
	case 10: $Userid =  " orders.user_id IN ('longueuil','longueuilsafe')";       $Compagnie = 'L\'Entrepot de la lunette Longueuil';  $Succ = 'Longueuil'; 	  			   
	$send_to_address = array('rapports@direct-lens.com'); 

	break;
	//$send_to_address = array('rapports@direct-lens.com');break;
	
	case 11: $Userid =  " orders.user_id IN ('granby','granbysafe')";             $Compagnie = 'L\'Entrepot de la lunette Granby';    $Succ = 'Granby'; 					   
	$send_to_address = array('rapports@direct-lens.com'); 

	break;

	//$send_to_address = array('rapports@direct-lens.com');break;
	
	case 12: $Userid =  " orders.user_id IN ('entrepotquebec','quebecsafe')";       $Compagnie = 'L\'Entrepot de la lunette Quebec';  $Succ = 'Quebec'; 	  			   
	$send_to_address = array('rapports@direct-lens.com'); 

	break;
	
	case 13: $Userid =  " orders.user_id IN ('gatineau','gatineausafe')";       $Compagnie = 'L\'Entrepot de la lunette Gatineau';  $Succ = 'Gatineau'; 	  			   
	$send_to_address = array('rapports@direct-lens.com'); 

	break;
	
	case 14: $Userid =  " orders.user_id IN ('stjerome','stjeromesafe')";       $Compagnie = 'L\'Entrepot de la lunette St-Jerome';  $Succ = 'St-Jerome'; 	  			   
	$send_to_address = array('rapports@direct-lens.com'); 

	break;
	
	case 15: $Userid =  " orders.user_id IN ('edmundston','edmundstonsafe')";       $Compagnie = 'L\'Entrepot de la lunette Edmundston';  $Succ = 'Edmundston'; 	  			   
	$send_to_address = array('rapports@direct-lens.com'); 

	break;
	
	case 16: $Userid =  " orders.user_id IN ('vaudreuil','vaudreuilsafe')";       $Compagnie = 'L\'Entrepot de la lunette Vaudreuil';  $Succ = 'Vaudreuil'; 	  			   
	$send_to_address = array('rapports@direct-lens.com'); 

	break;
	
	case 17: $Userid =  " orders.user_id IN ('sorel','sorelsafe')";       $Compagnie = 'L\'Entrepot de la lunette Sorel';  $Succ = 'Sorel'; 	  			   
	$send_to_address = array('rapports@direct-lens.com'); 

	break;
	
	case 18: $Userid =  " orders.user_id IN ('moncton','monctonsafe')";       $Compagnie = 'L\'Entrepot de la lunette Moncton';  $Succ = 'Moncton'; 	  			   
	$send_to_address = array('rapports@direct-lens.com'); 

	break;
	
	case 19: $Userid =  " orders.user_id IN ('fredericton','frederictonsafe')";       $Compagnie = 'L\'Entrepot de la lunette Fredericton';  $Succ = 'Fredericton'; 	  			   
	$send_to_address = array('rapports@direct-lens.com'); 

	break;

	case 20: $Userid =  " orders.user_id IN ('88666')";       $Compagnie = 'Griffe Trois-Rivieres';  $Succ = '#88666 Griffe'; 	  			   
	$send_to_address = array('rapports@direct-lens.com'); 

	break;
}//End Switch


$QueryNbrCommande ="SELECT count(order_num) AS NbrCommande, sum(order_total) as TotalPurchase
FROM accounts, orders 
WHERE orders.user_id = accounts.user_id
AND $Userid
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS  NULL";	
echo '<br>Query1: '.     $QueryNbrCommande . '<br>';
$ResultNbrCommande   = mysqli_query($con,$QueryNbrCommande)		or die  ('I cannot select items 1 because: ' . mysqli_error($con));
$DataNbrCommande     = mysqli_fetch_array($ResultNbrCommande,MYSQLI_ASSOC);
$NbrTotaldeCommande  = $DataNbrCommande[NbrCommande];
$MontantdesCommandes = $DataNbrCommande[TotalPurchase];


$rptQueryRedos="SELECT accounts.company, sum( order_total ) AS TotalPurchaseRedo, count(order_num) as NbrRedo
FROM accounts, orders 
WHERE orders.user_id = accounts.user_id
AND $Userid
AND order_date_processed BETWEEN '$dateaweekago' AND '$aujourdhui'
AND redo_order_num IS NOT NULL
ORDER BY TotalPurchaseRedo";	
echo '<br>Query2: '.     $rptQueryRedos . '<br>';
$rptResultRedo  = mysqli_query($con,$rptQueryRedos)		or die  ('I cannot select items 2 because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedo,MYSQLI_ASSOC);
$NombreRedos    = $DataRedos[NbrRedo];
$ValeurdesRedos = $DataRedos[TotalPurchaseRedo];


$message .="
<tr>
	<td align=\"center\">$Compagnie</td>
	<td align=\"center\">$NbrTotaldeCommande</td>
	<td align=\"center\">$MontantdesCommandes$</td>
	<td align=\"center\">$NombreRedos</td>
	<td align=\"center\">$ValeurdesRedos</td>
</tr>";

//echo '<br>send to address:';
//var_dump($send_to_address);
echo $message .'<br><br><br>';
//exit();	

	
	//SEND EMAIL
	//$send_to_address = array('rapports@direct-lens.com');			
	echo "<br>".$send_to_address;	
	echo '<br>'. $message;	
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$subject= "Rapport des directeurs: $Succ $dateaweekago-$aujourdhui";  

	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	
	echo '<br>'; 
	var_dump($send_to_address);
	echo '<br>'; 
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

			// Générer le contenu HTML du rapport


	// Créez un nom de fichier unique avec un horodatage
	$date = new DateTime();
	$timestamp = $date->format('Y-m-d_H-i-s');

	$nomFichier = 'r_semaine_vente_entrepot_directeur_'.$Succ. $timestamp;

	// Enregistrez le contenu HTML dans un fichier
	$cheminFichierHtml = 'C:/All_Rapports_EDLL/general/Vente/Semaine/' . $nomFichier . '.html';
	file_put_contents($cheminFichierHtml, $message);


	echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';
					
}//End For

$time_end = microtime(true);
$time 	  = $time_end - $time_start;
$today 	  = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$ip   			 = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$ip2   			 = $_SERVER['HTTP_X_FORWARDED_FOR'];
$ips			 = $ip  . ' ' .$ip2 ;
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page, ip) 
			VALUES('Rapport semaine vente entrepots pour les directeurs 2.0', '$time','$today','$timeplus3heures','rapport_semaine_vente_entrepots_directeur.php','$ips')"; 					
$cronResult = mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));	
?>