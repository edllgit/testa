<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../../sec_connectEDLL.inc.php");
include('../../phpmailer_email_functions.inc.php');
require_once('../../class.ses.php');
$time_start = microtime(true);     

$datedujour  		= mktime(0,0,0,date("m"),date("d"),date("Y"));
$aujourdhui     	= date("Y/m/d", $datedujour);

echo '<br>Date du jour:'. $aujourdhui;

//Ajout pour transformer ce rapport bi-mensuel en rapport mensuel
$MoisEnCours 	= date("m", $datedujour);

echo '<br>Mois en cours:'. $MoisEnCours;
 
$MoisEnCours=$MoisEnCours-1;//Pour sélectionner le mois qui vient de se terminer
$AnneeEnCours  = date("Y", $datedujour); 

//A RECOMMENTER , A Utiliser pour générer un mois différent uniquement
//$MoisEnCours=4;

if ($MoisEnCours==0){
	$MoisEnCours=12;	
	//$AnneeEnCours = $AnneeEnCours-1;
}

echo '<br>Année en cours:'. $AnneeEnCours;
switch($MoisEnCours){
		case 1:	$JourDebut="01-01";	$JourFin="01-31";	$AnneeEnCours = $AnneeEnCours;		break; //Janvier 
		case 2: $JourDebut="02-01";	$JourFin="02-29";	$AnneeEnCours = $AnneeEnCours;		break; //Février
		case 3: $JourDebut="03-01";	$JourFin="03-31";	$AnneeEnCours = $AnneeEnCours;		break; //Mars
		case 4: $JourDebut="04-01";	$JourFin="04-30";	$AnneeEnCours = $AnneeEnCours;		break; //Avril
		case 5: $JourDebut="05-01";	$JourFin="05-31";	$AnneeEnCours = $AnneeEnCours;		break; //Mai
		case 6: $JourDebut="06-01";	$JourFin="06-30";	$AnneeEnCours = $AnneeEnCours;		break; //Juin
		case 7: $JourDebut="07-01";	$JourFin="07-31";	$AnneeEnCours = $AnneeEnCours;		break; //Juillet
		case 8: $JourDebut="08-01";	$JourFin="08-31";	$AnneeEnCours = $AnneeEnCours;		break; //Août
		case 9: $JourDebut="09-01";	$JourFin="09-30";	$AnneeEnCours = $AnneeEnCours;		break; //Septembre
		case 10:$JourDebut="10-01";	$JourFin="10-31";	$AnneeEnCours = $AnneeEnCours;		break; //Octobre
		case 11:$JourDebut="11-01";	$JourFin="11-30";	$AnneeEnCours = $AnneeEnCours;		break; //Novembre
		case 12:$JourDebut="12-01";	$JourFin="12-31";	$AnneeEnCours = $AnneeEnCours-1;	break; //Décembre	
}

echo '<br>Année en cours:'. $AnneeEnCours;
echo '<br>Mois en cours:'. $MoisEnCours;

echo '<br>JourDebut:'. $JourDebut;
echo '<br>JourFin:'. $JourFin.'<br><br>';


$date1_AnneeEnCours  	= $AnneeEnCours.'-'.$JourDebut;
$date2_AnneeEnCours    	= $AnneeEnCours.'-'.$JourFin;

$AnneeDate1EnCours = substr($date1_AnneeEnCours,0,4);
$AnneeDate2EnCours = substr($date2_AnneeEnCours,0,4);

$AnneeDate1AnDernier = $AnneeEnCours -1;
$AnneeDate2AnDernier = $AnneeEnCours -1;

$date1_AnneeDernier  	= $AnneeDate1AnDernier .$JourDebut; 
$date2_AnneeDernier    	= $AnneeDate2AnDernier .$JourFin; 

//echo '<br>'.$date1_AnneeEnCours . ' ' . $date2_AnneeEnCours;



	
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
for ($i = 1; $i <= 19; $i++) {
   // echo '<br>'. $i;	
		
//Nouvelle partie
switch($i){
	case  1: $Userid =  " orders.user_id IN ('entrepotifc','entrepotsafe')";      $Compagnie = 'Trois-Rivieres'; 	$Succ = 'Trois-Rivieres';	
	$send_to_address = array('rapports@direct-lens.com'); 	break;
	
	case  2: $Userid =  " orders.user_id IN ('entrepotdr','safedr')";        	  $Compagnie = 'Drummondville';		$Succ = 'Drummondville';	
	$send_to_address = array('rapports@direct-lens.com'); 		break;
	
	case  3: $Userid =  " orders.user_id IN ('warehousehal','warehousehalsafe')"; $Compagnie = 'Halifax'; 			$Succ = 'Halifax'; 	  		
	$send_to_address = array('rapports@direct-lens.com'); 				break;
	
	case  4: $Userid =  " orders.user_id IN ('laval','lavalsafe')"; 			  $Compagnie = 'Laval';	  			$Succ = 'Laval';   			
	$send_to_address = array('rapports@direct-lens.com'); 				break;
	
	case  5: $Userid =  " orders.user_id IN ('terrebonne','terrebonnesafe')"; 	  $Compagnie = 'Terrebonne'; 	  	$Succ = 'Terrebonne';		
	$send_to_address = array('rapports@direct-lens.com'); 		break;
	
	case  6: $Userid =  " orders.user_id IN ('sherbrooke','sherbrookesafe')";     $Compagnie = 'Sherbrooke'; 		$Succ = 'Sherbrooke'; 		
	$send_to_address = array('rapports@direct-lens.com'); 		break;
	
	case  7: $Userid =  " orders.user_id IN ('chicoutimi','chicoutimisafe')";     $Compagnie = 'Chicoutimi';		$Succ = 'Chicoutimi'; 		
	$send_to_address = array('rapports@direct-lens.com'); 		break;
	
	case  8: $Userid =  " orders.user_id IN ('levis','levissafe')"; 			  $Compagnie = 'Lévis';      		$Succ = 'Lévis'; 			
	$send_to_address = array('rapports@direct-lens.com'); 				break;  
	
	case  9: $Userid =  " orders.user_id IN ('longueuil','longueuilsafe')";       $Compagnie = 'Longueuil';  		$Succ = 'Longueuil'; 		
	$send_to_address = array('rapports@direct-lens.com'); 			break; 
	
	case 10: $Userid =  " orders.user_id IN ('granby','granbysafe')";             $Compagnie = 'Granby';    		$Succ = 'Granby'; 		    
	$send_to_address = array('rapports@direct-lens.com');   			break; 
	
	case 11: $Userid =  " orders.user_id IN ('entrepotquebec','quebecsafe')";     $Compagnie = 'Quebec';  			$Succ = 'Quebec'; 			
	$send_to_address = array('rapports@direct-lens.com'); 			break;
	
	case 12: $Userid =  " orders.user_id IN ('gatineau','gatineausafe')";         $Compagnie = 'Gatineau';  		$Succ = 'Gatineau'; 		
	$send_to_address = array('rapports@direct-lens.com'); 			break; 
	
	case 13: $Userid =  " orders.user_id IN ('stjerome','stjeromesafe')";         $Compagnie = 'St-Jérôme';  		$Succ = 'St-Jérôme'; 		
	$send_to_address = array('rapports@direct-lens.com'); 			break; 
	
	case 14: $Userid =  " orders.user_id IN ('edmundston','edmundstonsafe')";     $Compagnie = 'Edmundston';		$Succ = 'Edmundston'; 		
	$send_to_address = array('rapports@direct-lens.com'); 		break;	
	
	case 15: $Userid =  " orders.user_id IN ('88666')";     					  $Compagnie = 'Griffé TR';			$Succ = 'Griffé TR'; 		
	$send_to_address = array('rapports@direct-lens.com'); 	break;
	
	case 16: $Userid =  " orders.user_id IN ('sorel','sorelsafe')";    			 $Compagnie = 'Sorel';		$Succ = 'Sorel'; 		
	$send_to_address = array('rapports@direct-lens.com'); 				break;	
	
	case 17: $Userid =  " orders.user_id IN ('vaudreuil','vaudreuilsafe')";    	$Compagnie = 'Vaudreuil';		$Succ = 'Vaudreuil'; 		
	$send_to_address = array('rapports@direct-lens.com'); 				break;	
	
	case 18: $Userid =  " orders.user_id IN ('moncton','monctonsafe')";    		$Compagnie = 'Moncton';		$Succ = 'Moncton'; 		
	$send_to_address = array('rapports@direct-lens.com'); 				break;	
	
	case 19: $Userid =  " orders.user_id IN ('fredericton','frederictonsafe')";    		$Compagnie = 'Fredericton';		$Succ = 'Fredericton'; 		
	$send_to_address = array('rapports@direct-lens.com'); 				break;	
}//End Switch

echo '<br>Courriel envoyé vers :'. var_dump($send_to_address);
//$send_to_address = array('rapports@direct-lens.com');


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
	
	//PARTIE ANNÉE EN COURS
	$QueryRepriseAnneeEnCours ="SELECT COUNT(order_num)  as NbrRepriseAnneeEnCours, sum(order_total) as ValeurReprisesAnneeEnCours
	FROM ORDERS 	WHERE redo_order_num IS NOT NULL
	AND order_status not in ('cancelled','on  hold')
	AND $Userid
	AND redo_reason_id IN (2,3,4,54,31,69,46,53,52,63,57,16,40,39,77,78,79,80,81,82,83,84,86,87,88,92,93,94,97)
	AND order_date_processed BETWEEN '$AnneeEnCours-$JourDebut' AND '$AnneeEnCours-$JourFin'";	

	//echo '<br>QueryRepriseAnneeEnCours<br>' .     $QueryRepriseAnneeEnCours . '<br>';
	$ResultRepriseAnneeEnCours  	= mysqli_query($con,$QueryRepriseAnneeEnCours)		or die  ('I cannot select items 1 because: ' . mysqli_error($con));
	$DataNbrRepriseAnneeEnCours     = mysqli_fetch_array($ResultRepriseAnneeEnCours,MYSQLI_ASSOC);
	
	$QueryVenteAnneeEnCours ="SELECT COUNT(order_num)  as NbrVenteAnneeEnCours
	FROM ORDERS 	WHERE redo_order_num IS NULL
	AND order_status not in ('cancelled','on  hold')
	AND $Userid
	AND order_date_processed BETWEEN '$AnneeEnCours-$JourDebut' AND '$AnneeEnCours-$JourFin'";	
	//echo '<br>QueryRepriseAnneeEnCours<br>' .     $QueryVenteAnneeEnCours . '<br>';
	$ResultVenteAnneeEnCours  	 = mysqli_query($con,$QueryVenteAnneeEnCours)		or die  ('I cannot select items 1 because: ' . mysqli_error($con));
	$DataNbrVenteAnneeEnCours    = mysqli_fetch_array($ResultVenteAnneeEnCours,MYSQLI_ASSOC);
	$PourcentageRepriseAnneeEnCours =  ($DataNbrRepriseAnneeEnCours[NbrRepriseAnneeEnCours]/$DataNbrVenteAnneeEnCours[NbrVenteAnneeEnCours])*100;
	$PourcentageRepriseAnneeEnCours=money_format('%.2n',$PourcentageRepriseAnneeEnCours);
	$ValeurReprisesAnneeEnCours=money_format('%.2n',$DataNbrRepriseAnneeEnCours[ValeurReprisesAnneeEnCours]);
	
	
	//PARTIE AN DERNIER
	$QueryRepriseAnDernier ="SELECT COUNT(order_num)  as NbrRepriseAnDernier, sum(order_total) as ValeurReprisesAnDernier
	FROM ORDERS
	WHERE redo_order_num IS NOT NULL 
	AND order_status not in ('cancelled','on  hold')
	AND $Userid
	AND redo_reason_id IN (2,3,4,54,31,69,46,53,52,63,57,16,40,39,77,78,79,80,81,82,83,84,86,87,88,92,93,94,97)
	AND order_date_processed BETWEEN '$AnneeDate1AnDernier-$JourDebut' AND '$AnneeDate1AnDernier-$JourFin'";	
	//echo '<br>QueryRepriseAnDernier<br>' .     $QueryRepriseAnDernier . '<br>';
	$ResultRepriseAnDernier  	= mysqli_query($con,$QueryRepriseAnDernier)		or die  ('I cannot select items 1 because: ' . mysqli_error($con));
	$DataNbrRepriseAnDernier     = mysqli_fetch_array($ResultRepriseAnDernier,MYSQLI_ASSOC);
	
	$QueryVenteAnDernier ="SELECT COUNT(order_num)  as NbrVenteAnDernier
	FROM ORDERS 	WHERE redo_order_num IS NULL 	
	AND order_status not in ('cancelled','on  hold')
	AND $Userid
	AND order_date_processed BETWEEN '$AnneeDate1AnDernier-$JourDebut' AND '$AnneeDate1AnDernier-$JourFin'";	
	//echo '<br>QueryVenteAnDernier<br>' .     $QueryVenteAnDernier . '<br>';
	$ResultVenteAnDernier  	 	 = mysqli_query($con,$QueryVenteAnDernier)		or die  ('I cannot select items 1 because: ' . mysqli_error($con));
	$DataNbrVenteAnDernier    	 = mysqli_fetch_array($ResultVenteAnDernier,MYSQLI_ASSOC);
	$PourcentageRepriseAnDernier =  ($DataNbrRepriseAnDernier[NbrRepriseAnDernier]/$DataNbrVenteAnDernier[NbrVenteAnDernier])*100;
	$PourcentageRepriseAnDernier = money_format('%.2n',$PourcentageRepriseAnDernier);
	$ValeurReprisesAnDernier	 = money_format('%.2n',$DataNbrRepriseAnDernier[ValeurReprisesAnDernier]);

	//Période sélectionnée
	$CompteurReprisesGlobal 		+= $DataNbrRepriseAnneeEnCours[NbrRepriseAnneeEnCours];
	$CompteurValeurReprisesGlobal 	+= $ValeurReprisesAnneeEnCours;
	$CompteurVentesGlobal 			+= $DataNbrVenteAnneeEnCours[NbrVenteAnneeEnCours];
	
	//An Dernier
	$CompteurReprisesGlobal_An_Dernier 		+= $DataNbrRepriseAnDernier[NbrRepriseAnDernier];
	$CompteurValeurReprisesGlobalAn_Dernier += $ValeurReprisesAnDernier;
	$CompteurVentesGlobalAn_Dernier 		+= $DataNbrVenteAnDernier[NbrVenteAnDernier];
	
	
	//Date de comparaison
	$CompteurReprisesGlobal_Comparaison  		+= $DataNbrRepriseComparaison[NbrRepriseComparaison];
	$CompteurValeurReprisesGlobal_Comparaison 	+= $ValeurReprisesComparaison;
	$CompteurVentesGlobalComparaison 			+= $DataNbrVenteComparaison[NbrVenteComparaison];
	
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
	<td colspan=\"1\" align=\"center\"></td>
	<td align=\"center\" bgcolor=\"#00ff83\" colspan=\"4\"><b>Dates sélectionnées: [$AnneeEnCours-$JourDebut - $AnneeEnCours-$JourFin]</b></td>
	<td align=\"center\" bgcolor=\"#99c7f7\" colspan=\"4\"><b>Mêmes dates, l'<b>année précédente</b>: [$AnneeDate1AnDernier-$JourDebut - $AnneeDate1AnDernier-$JourFin]</b></td>";
	

	
$message.="</thead>
<thead>
	<td align=\"center\"> </th>";
	
	$message.="<td  bgcolor=\"#00ff83\" align=\"center\"><b>Nbr Reprises*</b></td>	
	<td  bgcolor=\"#00ff83\" align=\"center\"><b>Valeur Reprises*</b></td>
	<td  bgcolor=\"#00ff83\" align=\"center\"><b>Nbr Vente</b></td>
	<td  bgcolor=\"#00ff83\" align=\"center\"><b>% Reprise</b></td>
	<td  bgcolor=\"#99c7f7\" align=\"center\"><b>Nbr reprise*</b></td>
	<td  bgcolor=\"#99c7f7\" align=\"center\"><b>Valeur Reprises*</b></td>
	<td  bgcolor=\"#99c7f7\" align=\"center\"><b>Nbr Vente</b></td>
	<td  bgcolor=\"#99c7f7\" align=\"center\"><b>% Reprise</b></td>";




$message.="</thead>";

	
	$message.="
	<tr>
		<td bgcolor=\"#c0c2ce\" align=\"right\"><b>$Succ</th>
		<td bgcolor=\"#00ff83\" align=\"center\">$DataNbrRepriseAnneeEnCours[NbrRepriseAnneeEnCours]</td>
		<td bgcolor=\"#00ff83\" align=\"center\">$ValeurReprisesAnneeEnCours$</td>
		<td bgcolor=\"#00ff83\" align=\"center\">$DataNbrVenteAnneeEnCours[NbrVenteAnneeEnCours]</td>
		<td bgcolor=\"#00ff83\" align=\"center\">$PourcentageRepriseAnneeEnCours%</td>
		<td bgcolor=\"#99c7f7\" align=\"center\">$DataNbrRepriseAnDernier[NbrRepriseAnDernier]</td>
		<td bgcolor=\"#99c7f7\" align=\"center\">$ValeurReprisesAnDernier$</td>
		<td bgcolor=\"#99c7f7\" align=\"center\">$DataNbrVenteAnDernier[NbrVenteAnDernier]</td>
		<td bgcolor=\"#99c7f7\" align=\"center\">$PourcentageRepriseAnDernier%</td>";
		
	
	
	$message.="</tr>";
	
	$message.="<td colspan=\"9\" bgcolor=\"#cccccc\" align=\"left\"><b>*N.B.</b>Les raisons de reprises incluses dans ce rapport sont les suivantes:<br><br>
	ERREUR | Manipulation employe<br>
	ERREUR | Saisie de donnee<br><br>
	NON ADAPT | Changement de corridor<br>
	NON ADAPT | Changement de monture<br>
	NON ADAPT | Changement de PD<br>
	NON ADAPT | Changement de type de produit<br>
	NON ADAPT |  Changement d'indice<br>
	NON ADAPT | Changement RX DR<br>
	NON ADAPT | Distorsion<br>
	NON ADAPT | Hauteur d'ajustement incorrecte<br><br>
	TRAITEMENT | Decollement<br>
	TRAITEMENT | Erreur de traitement<br>
	TRAITEMENT | Option solaire Défectueuse<br>
	TRAITEMENT | Rayures<br>";
	
	$message.="</td>
	</tr></table><br><br>";


	$curTime      = date("m-d-Y");	
	$from_address = 'donotreply@entrepotdelalunette.com';
	$subject      = "Rapport de reprise comparatif Période: $Compagnie [$AnneeEnCours-$JourDebut-$AnneeEnCours-$JourFin]";
	//SEND EMAIL
	if ($SendEmail == 'yes'){
		$response=office365_mail($send_to_address, $from_address, $subject, null, $message);//Version de test, envoyé uniquement sur mon courriel cdumais@edll
		//$response=office365_mail($send_to_address, $from_address, $subject, null, $message);//Pour envoyer officiellement le rapport aux Succursales
		echo '<br>Envoie du message pour  :    ' . $Compagnie.'<br><br>'; 	
	}
	
	//Effacer le contenu du message
   $message="";


}//END FOR



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