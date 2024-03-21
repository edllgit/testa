<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../../sec_connectEDLL.inc.php");
include('../../phpmailer_email_functions.inc.php');
require_once('../../class.ses.php');

//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$time_start = microtime(true);     

//Date du rapport
$ladatedhier  = mktime(0,0,0,date("m"),date("d")-6,date("Y"));
$Ilya6jours     	  = date("Y-m-d", $ladatedhier);

$ladate  	= mktime(0,0,0,date("m"),date("d"),date("Y"));
$aujourdhui = date("Y-m-d", $ladate);


$Ilya6jours = "2021-04-01";
$aujourdhui = "2021-08-20";
	
if ($_REQUEST['email'] == 'no'){
	$SendEmail = 'no';
}elseif($_REQUEST['email'] == 'admin'){
	$SendEmail = 'no';
	$SendAdmin = 'yes';
}else{
	$SendEmail = 'yes';
}
if ($_REQUEST[debug]=='yes'){
	$Debug = 'yes';
}

$count    = 0;
$message  = "";
			
$totalFirstTimeOrder        = 0;
$MontanttotalFirstTimeOrder = 0;
$totalRedos                 = 0;
$MontanttotalRedos          = 0;
//Période sélectionnée
$CompteurReprisesGlobal 	= 0;	
$CompteurValeurReprisesGlobal 	= 0;
$CompteurVentesGlobal 	= 0;	
//An Dernier
$CompteurReprisesGlobal_An_Dernier 			= 0;	
$CompteurValeurReprisesGlobalAn_Dernier 	= 0;
$CompteurVentesGlobalAn_Dernier 			= 0;

//Période de comparaison
$CompteurReprisesGlobal_Comparaison 		= 0;	
$CompteurValeurReprisesGlobal_Comparaison 	= 0;
$CompteurVentesGlobalComparaison 			= 0;
	
//FOR pour parcourir les Succursales
for ($i = 1; $i <= 20; $i++) {
   // echo '<br>'. $i;	
		
//Nouvelle partie
switch($i){
	case  1: $Userid =  " orders.user_id IN ('entrepotifc','entrepotsafe')";      $Compagnie = 'Trois-Rivieres'; 	$Succ = 'Trois-Rivieres';	
	//$send_to_address = array('rapports@direct-lens.com'); 
	$send_to_address = array('rapports@direct-lens.com');	 ob_start(); break;
	
	case  2: $Userid =  " orders.user_id IN ('entrepotdr','safedr')";        	  $Compagnie = 'Drummondville';		$Succ = 'Drummondville';	
	//$send_to_address = array('rapports@direct-lens.com'); 		
	$send_to_address = array('rapports@direct-lens.com'); ob_start();	break;
	
	case  3: $Userid =  " orders.user_id IN ('warehousehal','warehousehalsafe')"; $Compagnie = 'Halifax'; 			$Succ = 'Halifax'; 	  		
	//$send_to_address = array('rapports@direct-lens.com'); 				
	$send_to_address = array('rapports@direct-lens.com'); ob_start();	break;
	
	case  4: $Userid =  " orders.user_id IN ('laval','lavalsafe')"; 			  $Compagnie = 'Laval';	  			$Succ = 'Laval';   			
	//$send_to_address = array('rapports@direct-lens.com'); 				
	$send_to_address = array('rapports@direct-lens.com'); ob_start();	break;
	
	case  5: $Userid =  " orders.user_id IN ('terrebonne','terrebonnesafe')"; 	  $Compagnie = 'Terrebonne'; 	  	$Succ = 'Terrebonne';		
	//$send_to_address = array('rapports@direct-lens.com'); 		
	$send_to_address = array('rapports@direct-lens.com'); ob_start();	break;
	
	case  6: $Userid =  " orders.user_id IN ('sherbrooke','sherbrookesafe')";     $Compagnie = 'Sherbrooke'; 		$Succ = 'Sherbrooke'; 		
	//$send_to_address = array('rapports@direct-lens.com'); 		
	$send_to_address = array('rapports@direct-lens.com'); ob_start();	break;
	
	case  7: $Userid =  " orders.user_id IN ('chicoutimi','chicoutimisafe')";     $Compagnie = 'Chicoutimi';		$Succ = 'Chicoutimi'; 		
	//$send_to_address = array('rapports@direct-lens.com'); 		
	$send_to_address = array('rapports@direct-lens.com'); ob_start();	break;
	
	case  8: $Userid =  " orders.user_id IN ('levis','levissafe')"; 			  $Compagnie = 'Lévis';      		$Succ = 'Lévis'; 			
	//$send_to_address = array('rapports@direct-lens.com');
	$send_to_address = array('rapports@direct-lens.com'); ob_start();	break;
	
	case  9: $Userid =  " orders.user_id IN ('longueuil','longueuilsafe')";       $Compagnie = 'Longueuil';  		$Succ = 'Longueuil'; 		
	//$send_to_address = array('rapports@direct-lens.com'); 			
	$send_to_address = array('rapports@direct-lens.com'); ob_start();	break;
	
	case 10: $Userid =  " orders.user_id IN ('granby','granbysafe')";             $Compagnie = 'Granby';    		$Succ = 'Granby'; 		    
	//$send_to_address = array('rapports@direct-lens.com');   			
	$send_to_address = array('rapports@direct-lens.com'); ob_start();	break;
	
	case 11: $Userid =  " orders.user_id IN ('entrepotquebec','quebecsafe')";     $Compagnie = 'Quebec';  			$Succ = 'Quebec'; 			
	//$send_to_address = array('rapports@direct-lens.com'); 			
	$send_to_address = array('rapports@direct-lens.com'); ob_start();	break;
	
	case 12: $Userid =  " orders.user_id IN ('gatineau','gatineausafe')";         $Compagnie = 'Gatineau';  		$Succ = 'Gatineau'; 		
	//$send_to_address = array('rapports@direct-lens.com'); 			
	$send_to_address = array('rapports@direct-lens.com'); ob_start();	break; 
	
	case 13: $Userid =  " orders.user_id IN ('stjerome','stjeromesafe')";         $Compagnie = 'St-Jérôme';  		$Succ = 'St-Jérôme'; 		
	//$send_to_address = array('rapports@direct-lens.com');
	$send_to_address = array('rapports@direct-lens.com'); ob_start();	break;
	
	case 14: $Userid =  " orders.user_id IN ('edmundston','edmundstonsafe')";     $Compagnie = 'Edmundston';		$Succ = 'Edmundston'; 		
	//$send_to_address = array('rapports@direct-lens.com'); 		
	$send_to_address = array('rapports@direct-lens.com'); ob_start();	break;	
	
	case 15: $Userid =  " orders.user_id IN ('vaudreuil','vaudreuilsafe')";     $Compagnie = 'Vaudreuil';		$Succ = 'Vaudreuil'; 		
	//$send_to_address = array('rapports@direct-lens.com'); 		
	$send_to_address = array('rapports@direct-lens.com');	 ob_start(); break;	
	
	case 16: $Userid =  " orders.user_id IN ('sorel','sorelsafe')";     		$Compagnie = 'Sorel-Tracy';		$Succ = 'Sorel-Tracy'; 		
	//$send_to_address = array('rapports@direct-lens.com'); 		
	$send_to_address = array('rapports@direct-lens.com'); ob_start();	break;	
	
	case 17: $Userid =  " orders.user_id IN ('88666')";     					  $Compagnie = 'Griffé TR';			$Succ = 'Griffé TR'; 		
	//$send_to_address = array('rapports@direct-lens.com'); 	
	$send_to_address = array('rapports@direct-lens.com');	 ob_start(); break;

	
	case 18: $Userid =  " orders.user_id IN ('moncton','monctonsafe')";    		$Compagnie = 'Moncton';		$Succ = 'Moncton'; 		
	$send_to_address = array('rapports@direct-lens.com'); 		ob_start();		break;	
	
	case 19: $Userid =  " orders.user_id IN ('fredericton','frederictonsafe')";    		$Compagnie = 'Fredericton';		$Succ = 'Fredericton'; 		
	$send_to_address = array('rapports@direct-lens.com'); 			ob_start(); 	break;	

	case 20: $Userid =  " orders.user_id IN ('stjohn','stjohnsafe')";    		$Compagnie = 'St-John';		$Succ = 'St-John'; 		
	$send_to_address = array('rapports@direct-lens.com'); 			ob_start(); 	break;	

}//End Switch

echo '<br>Courriel envoyé vers :'. var_dump($send_to_address);
$send_to_address = array('rapports@direct-lens.com');


	if ($Userid ==  " orders.user_id IN ('88666')"){
			//echo '<br>Partie Griffe TR. Cnnexion DB HBO en cours..';
			//Connexion DB HBO pour info reprises Griffé
			include("../../connexion_hbc.inc.php");
			//echo 'connecté';
	}//END IF

	//REMETTRE EN COMMENTAIRE
	/*$AnneeEnCours="2022";
	$JourDebut = "04-01";
	$JourFin = "04-30";
	*/
	
	$QueryReprise100Pourcent ="SELECT * FROM ORDERS 	WHERE redo_order_num IS NOT NULL
	AND $Userid
	AND redo_reason_id IN (2,3,4,54,31,69,46,53,52,63,57,16,40,39,77,78,79,80,81,82,83,84,86,87,88,92,93,94,97)
	AND order_date_processed BETWEEN '$Ilya6jours' AND '$aujourdhui'";	

	echo '<br>QueryReprise100Pourcent<br>' .     $QueryReprise100Pourcent . '<br>';
	$ResultReprise100Pourcent  	= mysqli_query($con,$QueryReprise100Pourcent)		or die  ('I cannot select items 1 because: ' . mysqli_error($con));
	
	
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
	
	
$message.="<body>
<table class=\"table\" border=\"1\">

<thead>
	<td align=\"center\" bgcolor=\"#20639B\" colspan=\"4\"><b>Dates sélectionnées: [$Ilya6jours -$aujourdhui]</b></td></thead>";

	$message.="<thead>
	<td  bgcolor=\"#F6D55C\" align=\"center\"><b>Reprise</b></td>	
	<td  bgcolor=\"#F6D55C\" align=\"center\"><b>Patient</b></td>
	<td  bgcolor=\"#F6D55C\" align=\"left\"><b>Produit</b></td>
	<td  bgcolor=\"#F6D55C\" align=\"center\"><b>Crédit émis</b></td>";
$message.="</thead>";


while ($DataReprise100Pourcent     = mysqli_fetch_array($ResultReprise100Pourcent,MYSQLI_ASSOC)){
	
	
	//Vérifier si un crédit a été émis pour cette reprise, si c'est le cas, on ne l'affiche pas.
	$queryCreditEmis	= "SELECT * FROM memo_credits WHERE mcred_order_num= $DataReprise100Pourcent[order_num]";
	echo $queryCreditEmis.'<br>'; 
	$ResultCreditEmis  	= mysqli_query($con,$queryCreditEmis)		or die  ('I cannot select items 11 because: ' . mysqli_error($con));
	$DataCreditemis     = mysqli_fetch_array($ResultCreditEmis,MYSQLI_ASSOC);
	$NombreCreditEmis = mysqli_num_rows($ResultCreditEmis);
	
	//Ne pas afficher les reprises pour lesquels un crédit a déja été émis
	if ($NombreCreditEmis==0){
	$message.="<tr>
					<td  bgcolor=\"#F6D55C\" align=\"center\">$DataReprise100Pourcent[order_num]<b></b></td>	
					<td  bgcolor=\"#F6D55C\" align=\"center\">$DataReprise100Pourcent[order_patient_first] $DataReprise100Pourcent[order_patient_last]</td>
					<td  bgcolor=\"#F6D55C\" align=\"left\">$DataReprise100Pourcent[order_product_name]</td>
					<td  bgcolor=\"#F6D55C\" align=\"center\">$DataCreditemis[mcred_memo_num]</td>
			</tr>";
	}//END IF
	$message.="";
	
	
}//END WHILE

	$message.="<tr><td colspan=\"9\" bgcolor=\"#cccccc\" align=\"left\"><b>*N.B.</b>Tant que ces verres n'auront pas été reçu, aucun crédit ne sera émis. :<br><br>
	TRAITEMENT | Rayures<br>";
	
	$message.="</td>
	</tr></table><br><br>";


	$curTime      = date("m-d-Y");	
	$from_address = 'donotreply@entrepotdelalunette.com';
	$subject      = "$Compagnie: Vos verres en attente pour émission de crédits:  [$Ilya6jours -$aujourdhui]";
	//SEND EMAIL
	if ($SendEmail == 'yes'){
		$response=office365_mail($send_to_address, $from_address, $subject, null, $message);//Version de test, envoyé uniquement sur mon courriel cdumais@edll
		//$response=office365_mail($send_to_address, $from_address, $subject, null, $message);//Pour envoyer officiellement le rapport aux Succursales
		echo '<br>Envoie du message pour  :    ' . $Compagnie.'<br><br>'; 	
	}
	
	
	echo $message;
	// Générer le contenu HTML du rapport
	$contenuHtml = ob_get_clean();

	// Créez un nom de fichier unique avec un horodatage
	$date = new DateTime();
	$timestamp = $date->format('Y-m-d_H-i-s');
	$nomFichier = 'r_reprise_hebdo_par_magasin'.$Succ. $timestamp;

	// Enregistrez le contenu HTML dans un fichier
	$cheminFichierHtml = 'C:/All_Rapports_EDLL/general/REPRISE/Semaine/#Reprise_Semaine_par_Magasin/' . $nomFichier . '.html';
	file_put_contents($cheminFichierHtml, $contenuHtml);

	echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';
	
	
	echo $message;
	//Effacer le contenu du message
   $message="";


}//END FOR

exit();

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

	
if($response){ 
	echo '<div class="alert alert-success" role="alert"><strong>Email Sent Sucessfully to:'. $EmailEnvoyerA.'</strong></div>';
}else{
	echo '<div class="alert alert-danger" role="alert"><strong>Error while sending the email to: '. $EmailEnvoyerA.'</strong></div>';
}	


echo $message;
 		
?>
   <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="..fichierquejenaipas../ie10-viewport-bug-workaround.js"></script>
  </body>
</html>