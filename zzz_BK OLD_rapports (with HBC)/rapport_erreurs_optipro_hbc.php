<?php 
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
*/
require_once(__DIR__.'/../constants/aws.constant.php');
require_once(__DIR__.'/../constants/ftp.constant.php');
require_once(__DIR__.'/../constants/url.constant.php');
include("../connexion_hbc.inc.php");
//include("admin_functions.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
session_start();

$tomorrow  = mktime(0,0,0,date("m"),date("d"),date("Y"));
$ajd 	   = date("Y-m-d", $tomorrow);

//Search errors of the day   
		   $rptQuery="SELECT * FROM erreurs_optipro 
		   WHERE  detail NOT LIKE '%a deja ete importee pour ce client%'
		   AND user_id NOT IN ('test')
		   AND order_num_optipro <> 0
		   AND active=1
		   ORDER BY  user_id, order_num_optipro, nombre_notification_succursale desc"; 
		  // echo $rptQuery;


if ($_REQUEST[delete_id] <> ''){
	//0-Aller chercher le user id concerné par l'erreur
	$queryUserid  = "SELECT user_id, order_num_optipro from erreurs_optipro WHERE erreur_id = $_REQUEST[delete_id]";
	$resultUserID = mysqli_query($con,$queryUserid) or die  ('I cannot select items because 222: ' . mysqli_error($con));
	$DataUserID   = mysqli_fetch_array($resultUserID,MYSQLI_ASSOC);
	$UserID       = $DataUserID[user_id];
	$LorderNumOptipro  = $DataUserID[order_num_optipro];
	
	switch($UserID){
		case '88431':     	$User_ID_IN = "('88431')";     break;
		case '88434':  	 	$User_ID_IN = "('88434')";     break;
		case '88435':  	 	$User_ID_IN = "('88435')";     break;
		
		case '88416':  	 	$User_ID_IN = "('88416')";     break;
		case '88438':  	 	$User_ID_IN = "('88438')";     break;
		case '88439':  	 	$User_ID_IN = "('88439')";     break;
		case '88444':  	 	$User_ID_IN = "('88444')";     break;
		
		case '88433':  	 	$User_ID_IN = "('88433')";     break;
		case '88409':  	 	$User_ID_IN = "('88409')";     break;
		case '88403':  	 	$User_ID_IN = "('88403')";     break;
		
		case '88408':  	 	$User_ID_IN = "('88408')";     break;
		case '88414':  	 	$User_ID_IN = "('88414')";     break;
		case '88440':  	 	$User_ID_IN = "('88440')";     break;
		
		case '88666':  	 	$User_ID_IN = "('88666')";     break;
	}	
		
	//1-Vérifier si la commande a été 'transféré avec succès'
	$queryValiderPasser  = "SELECT count(order_num) as NbMatch FROM orders WHERE order_status <> 'cancelled' AND user_id IN $User_ID_IN AND order_num_optipro = $LorderNumOptipro";
	echo '<br>'. $queryValiderPasser;
	$resultValiderPasser = mysqli_query($con,$queryValiderPasser) or die  ('I cannot select items because 2222: ' . mysqli_error($con));
	//$CountValiderPasser  = mysqli_num_rows($resultValiderPasser);
	$DataValiderPasser   = mysqli_fetch_array($resultValiderPasser,MYSQLI_ASSOC);
	$NbrMatch = $DataValiderPasser[NbMatch];
	echo '<br>NB MATCH:'. $NbrMatch;
	
	if ($NbrMatch == 1){
		//Signifie que la commande a été correctement transmise, 
		//ON DOIT EFFACER TOUTES LES ERREURS ID LIÉ A CETTE COMMANDE	
		$queryErreurIDs = "SELECT erreur_id FROM erreurs_optipro WHERE order_num_optipro = $LorderNumOptipro AND erreur_id <> $_REQUEST[delete_id]  AND user_id IN  $User_ID_IN ";
		echo '<br>'. $queryErreurIDs;
		$resultErreurIDs = mysqli_query($con,$queryErreurIDs) or die  ('I cannot select items because 33: ' . mysqli_error($con));
		while ($DataErreurIDs=mysqli_fetch_array($resultErreurIDs,MYSQLI_ASSOC)){
			echo '<br><br>Autre ID a effacer:'.	$DataErreurIDs[erreur_id];
			//$queryDelete  = "DELETE FROM erreurs_optipro WHERE erreur_id = $DataErreurIDs[erreur_id]";
			$queryDelete  = "UPDATE erreurs_optipro SET active=0 WHERE erreur_id = $DataErreurIDs[erreur_id]";
			echo '<br>'. $queryDelete;
			//NE PAS RÉELLEMENT EFFACER LE TEMPS DES TESTS DONC EN COMMENTAIRE
			$resultDelete = mysqli_query($con,$queryDelete) or die  ('I cannot delete  items because 5: ' . mysqli_error($con));
		}//End While
	}else{
	echo '<br>Aucun match, la commande n\'a pas été transféré. Donc, aucun autre ID a effacer. ';	
	}//End IF
		
		
		//Effacer le tuple
		$queryDetail  = "SELECT * FROM erreurs_optipro WHERE erreur_id = $_REQUEST[delete_id] ORDER BY user_id, order_num_optipro";
		echo '<br>'. $queryDetail;
		$resultDetail = mysqli_query($con,$queryDetail) or die  ('I cannot select items because 4: ' . mysqli_error($con));
		$DataDetail   = mysqli_fetch_array($resultDetail,MYSQLI_ASSOC);
		
		echo '<br>ID A EFFACER: ' . $_REQUEST[delete_id];
		//$queryDelete  = "DELETE FROM erreurs_optipro WHERE erreur_id = $_REQUEST[delete_id]";
		$queryDelete  = "UPDATE erreurs_optipro SET active=0 WHERE erreur_id = $_REQUEST[delete_id]";
		echo '<br>'. $queryDelete;
		$resultDelete = mysqli_query($con,$queryDelete) or die  ('I cannot delete  items because 5: ' . mysqli_error($con));
		echo '<br>Tuple Effacé..Redirection en cours';
		
		//Rediriger à la date ou la commande a été effacée
		if  ($DataDetail[date]<>''){
			header("Location: rapport_erreurs_optipro_hbc.php");
			exit();	
		} 
}//End IF There is an ID to delete





if ($_REQUEST[aviser_id] <> ''){
	
	//Aviser la succursale de cette erreur par courriel
	$queryDetail  = "SELECT * FROM erreurs_optipro WHERE erreur_id = $_REQUEST[aviser_id] ORDER BY user_id, order_num_optipro";
	echo '<br>'. $queryDetail .'<br>';
	$resultDetail = mysqli_query($con,$queryDetail) or die  ('I cannot select items because 6: ' . mysqli_error($con));
	$DataDetail   = mysqli_fetch_array($resultDetail,MYSQLI_ASSOC);
	$UserID = $DataDetail[user_id];
	switch($UserID){
		case '88403':  	 	$Succursale = "HBC Store #88403: Bloor St.";       	$EmailSuccursale = "dbeaulieu@direct-lens.com";         	break;
		case '88408':  	 	$Succursale = "HBC Store #88408: Oshawa";         	$EmailSuccursale = "dbeaulieu@direct-lens.com";         	break;
		case '88409':  	 	$Succursale = "HBC Store #88409: Eglinton";        	$EmailSuccursale = "dbeaulieu@direct-lens.com";         break;
		case '88414':  	 	$Succursale = "HBC Store #88414: Yorkdale";        	$EmailSuccursale = "dbeaulieu@direct-lens.com";         break;
		case '88416':  	 	$Succursale = "HBC Store #88416: Vancouver DTN";   	$EmailSuccursale = "dbeaulieu@direct-lens.com";     break;
		case '88431':     	$Succursale = "HBC Store #88431: Calgary DTN";    	$EmailSuccursale = "dbeaulieu@direct-lens.com";      	break;
		case '88433':  	 	$Succursale = "HBC Store #88433: Polo Park";       	$EmailSuccursale = "dbeaulieu@direct-lens.com";         break;
		case '88434':  	 	$Succursale = "HBC Store #88434: Market Mall";     	$EmailSuccursale = "dbeaulieu@direct-lens.com";       break;
		case '88435':  	 	$Succursale = "HBC Store #88435: West Edmonton";   	$EmailSuccursale = "dbeaulieu@direct-lens.com";     break;
		case '88438':  	 	$Succursale = "HBC Store #88438: Metrotown";       	$EmailSuccursale = "dbeaulieu@direct-lens.com";        break;
		case '88439':  	 	$Succursale = "HBC Store #88439: Langley";         	$EmailSuccursale = "dbeaulieu@direct-lens.com";         	break;
		case '88440':  	 	$Succursale = "HBC Store #88440: Rideau ";         	$EmailSuccursale = "dbeaulieu@direct-lens.com";        	break;
		case '88444':  	 	$Succursale = "HBC Store #88444: Mayfair";         	$EmailSuccursale = "dbeaulieu@direct-lens.com";         	break;
		case '88666':  	 	$Succursale = "Griffé Lunetier #88666"; 			$EmailSuccursale = "trois-rivieres@griffelunetier.com";     break;
	}
	
	/*echo '<br><b>Succursale</b>:' .  $Succursale. 	
	     '<br><b>Email</b>: '     . $EmailSuccursale	
	   . '<br><b>Num commande optipro</b>:' .$DataDetail[order_num_optipro]	
	   . '<br><b>Produit demandé</b> :' .$DataDetail[produit_optipro]
	   . '<br><b>Detail</b>:' .$DataDetail[detail]
	   . '<br><b>ID</b>:' .$DataDetail[erreur_id]  
	   . '<br><b>Nbr nbotification</b>:' .$DataDetail[nombre_notification_succursale] ;  */
	$NombrePrecedentNotification = $DataDetail[nombre_notification_succursale];

	
	//Préparer le courriel a envoyer a la succursale
	//POUR LES HBC, le email doit être envoyé en Anglais
	$message="";
	$message="<html>
	<head><style type='text/css'>
	<!--
	.TextSize {
	font-size: 8pt;
	font-family: Arial, Helvetica, sans-serif;
	}
	-->
	</style></head>
	<body>
	<p>Hi  $Succursale, <br><br> There is a problem with your Optipro Invoice <b>#$DataDetail[order_num_optipro]</b>.<br><br>
	The problem is the following:<i><b>$DataDetail[detail]</b></i>.<br> <br>Thanks to do the necessary updates, save and re-export the order.<br>
	Please do not reply to this email directly, since it won't be received.
	<br><br>
	Have a nice day.
	</p>";	
	

		
		
	if ($UserID=='88666'){//GRIFFÉ
	//Courriel en francais pour Griffé
	$message="<html>
	<head><style type='text/css'>
	<!--
	.TextSize {
	font-size: 8pt;
	font-family: Arial, Helvetica, sans-serif;
	}
	-->
	</style></head>
	<body>
		<p>Bonjour  $Succursale, <br><br> Il y a un probleme avec votre facture Optipro <b>#$DataDetail[order_num_optipro]</b>.<br><br>
		Le problème est le suivant:<i><b>$DataDetail[detail]</b></i>.<br> <br>Merci de faire les corrections nécessaires, sauvegarder et ré-exporter votre commande.<br>
		 Ne pas répondre à ce courriel directement, puisque personne ne recevra votre réponse. Si vous avez des questions, contactez directement Charles ou transférez-lui ce courriel avec vos questions.
		<br><br>
		P.S. Si vous avez déja réussi à transférer cette commande, ne tenez pas compte de ce courriel.<br>
		</p>";	
	}
	

	echo '<br>Message:'. $message;
	
	
	//Send EMAIL
			

	$send_to_address[] = $EmailSuccursale;	
	$curTime      = date("m-d-Y");	
	$to_address   = $send_to_address;
	$from_address = 'donotreply@entrepotdelalunette.com';
	$subject      = "Optipro Error # $DataDetail[order_num_optipro] $Succursale";
	$response     = office365_mail($to_address, $from_address, $subject, null, $message);
	
	//Avant d'envoyer le courriel, on valide que la commande n'as pas été transféré avec succès
	$queryValiderPasser  = "SELECT * FROM orders WHERE order_status<>'cancelled' AND user_id = '$DataDetail[user_id]' AND order_num_optipro = $DataDetail[order_num_optipro]";
	$resultValiderPasser = mysqli_query($con,$queryValiderPasser) or die  ('I cannot select items because 7: ' . mysqli_error($con));
	$CountValiderPasser  = mysqli_num_rows($resultValiderPasser);
	$DataValiderPasser   = mysqli_fetch_array($resultValiderPasser,MYSQLI_ASSOC);
	if ($CountValiderPasser > 0){
		$TransfertReussi = 'oui';
	}else{
		$TransfertReussi = 'non';	
	}
	
	if ($TransfertReussi == 'non'){//La commande n'as pas encore été transféré dans Ifc/Safe
		if ($response){
			//TODO Enregistrer la notification à la succursale
			$NouveauNombreNotification   = $NombrePrecedentNotification + 1;
			//echo '<br>Nouveau nombre de notification a enregistrer'. $NouveauNombreNotification;
			$Datedujour      		 = date("Y-m-d");	
			$queryUpdateNotification = "UPDATE erreurs_optipro SET nombre_notification_succursale = $NouveauNombreNotification, date_derniere_notification = '$Datedujour' WHERE erreur_id =  $DataDetail[erreur_id] ";
			$resultUpdate            = mysqli_query($con,$queryUpdateNotification) or die  ('I cannot update items because: ' . mysqli_error($con));
			//echo '<br>query:'. $queryUpdateNotification;
			echo '<br>Courriel envoyé..Redirection en cours';
			header("Location: rapport_erreurs_optipro_hbc.php");
			exit();	
		}else{
			echo '<br>Erreur durant l\'envoie du courriel..';	
		}
	}else{
	//Redirection vers optopro_today tout en avisant que la commande est déja transféré.
	//echo 'Cette commande a déja été transféré avec succès, il est donc inutile d\'aviser la succursale. ';
	//exit();	
	header("Location: rapport_erreurs_optipro_hbc.php?message=dejatransfere&order_num_optipro=$DataDetail[order_num_optipro]&acct=$DataDetail[user_id]");
	exit();	
	}//End if Transfert n'a pas été réussi
	

}//End IF There is an ID to advise




/*


if ($_REQUEST[aviser_direction_id] <> ''){

	//Aviser la direction de cette erreur par courriel
	$queryDetail    = "SELECT * FROM erreurs_optipro WHERE erreur_id = $_REQUEST[aviser_direction_id] ORDER BY user_id, order_num_optipro";
	//echo '<br>'. $queryDetail .'<br>';
	$resultDetail   = mysqli_query($con,$queryDetail) or die  ('I cannot select items because 6: ' . mysqli_error($con));
	$DataDetail     = mysqli_fetch_array($resultDetail,MYSQLI_ASSOC);

	$NombreNotificationDirection = $DataDetail[nombre_notification_direction];
	$EmailDirection = "dbeaulieu@direct-lens.com";//TODO CHANGER POUR EMAIL DE KASSANDRA APRES MES TESTS

	switch($DataDetail[user_id]){
		case 'granby':      case 'granbysafe':      $Succursale = "Granby";         break;    
		case 'levis': 	    case 'levissafe':       $Succursale = "Lévis";          break;        
		case 'chicoutimi':  case 'chicoutimisafe':  $Succursale = "Chicoutimi";     break;   
		case 'entrepotquebec':  case 'quebecsafe': $Succursale = "Québec";          break;
		case 'entrepotifc': case 'entrepotsafe':    $Succursale = "Trois-Rivières"; break;
		case 'entrepotdr':  case 'safedr':          $Succursale = "Drummondville";  break;
		case 'laval': 		case 'lavalsafe':       $Succursale = "Laval"; 			break;
		case 'terrebonne':  case 'terrebonnesafe':  $Succursale = "Terrebonne"; 	break;
		case 'sherbrooke':  case 'sherbrookesafe':  $Succursale = "Sherbrooke"; 	break;
		case 'longueuil':   case 'longueuilsafe':   $Succursale = "Longueuil"; 		break;
	}
	

	//Préparer le courriel a envoyer a la succursale
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
	
	$message.='<body>';
	$message.="
	<p>Bonjour, <br><br>Il y a un problème avec la facture Optipro <b>#$DataDetail[order_num_optipro]</b> de<b> $Succursale</b>.<br><br>
	Le problème est le suivant: <i><b>$DataDetail[detail]</b></i>.<br> <br>La succursale a été avisé du problème au moins trois fois par courriel.<br>
	<br>Merci de ne pas répondre à ce courriel directement, puisque personne ne recevra votre réponse. Si vous avez des questions, contactez directement Charles ou transférez-lui ce courriel avec votre interrogation.
	<br><br>
	Bonne journée.
	</p>";	
	
	//echo '<br>'.$message;
	
		
	//Send EMAIL	
	//$send_to_address = array('rapports@direct-lens.com');	
	$send_to_address[] = $EmailDirection;	
	$curTime      = date("m-d-Y");	
	$to_address   = $send_to_address;
	$from_address = 'donotreply@entrepotdelalunette.com';
	$subject      = "Erreur Optipro $DataDetail[order_num_optipro] $Succursale : Avis à la direction";
	$response     = office365_mail($to_address, $from_address, $subject, null, $message);
	
	//Avant d'envoyer le courriel, on valide que la commande n'as pas été transféré avec succès
	$queryValiderPasser  = "SELECT * FROM orders WHERE order_status<>'cancelled' AND user_id = '$DataDetail[user_id]' AND order_num_optipro = $DataDetail[order_num_optipro]";
	$resultValiderPasser = mysqli_query($con,$queryValiderPasser) or die  ('I cannot select items because 7: ' . mysqli_error($con));
	$CountValiderPasser  = mysqli_num_rows($resultValiderPasser);
	$DataValiderPasser   = mysqli_fetch_array($resultValiderPasser,MYSQLI_ASSOC);
	if ($CountValiderPasser > 0){
		$TransfertReussi = 'oui';
	}else{
		$TransfertReussi = 'non';	
	}
	
	if ($TransfertReussi == 'non'){//La commande n'as pas encore été transféré dans Ifc/Safe
		if (($response) && ($NombreNotificationDirection==0)){
			//TODO Enregistrer la notification à la direction
			$NouveauNombreNotificationDirection   = $listItem[nombre_notification_direction] + 1;
			//echo '<br>Nouveau nombre de notification a enregistrer'. $NouveauNombreNotification;
			$Datedujour      		 = date("Y-m-d");	
			$queryUpdateNotificationDirection = "UPDATE erreurs_optipro SET nombre_notification_direction = $NouveauNombreNotificationDirection, date_derniere_notification_direction='$Datedujour'  WHERE erreur_id =  $DataDetail[erreur_id] ";
			$resultUpdate            = mysqli_query($con,$queryUpdateNotificationDirection) or die  ('I cannot update items because: ' . mysqli_error($con));
			echo '<br>query:'. $queryUpdateNotification;
			echo '<br>Courriel envoyé..Redirection en cours';
			header("Location: rapport_erreurs_optipro_hbc.php");
			exit();	
			echo '<h3>Envoie à la direction..réussie</h>';
		}else{
			echo '<br>Erreur durant l\'envoie du courriel..';	
		}
	}else{
	header("Location: rapport_erreurs_optipro_hbc.php?message=dejatransfere&order_num_optipro=$DataDetail[order_num_optipro]&acct=$DataDetail[user_id]");
	exit();	
	}//End if Transfert n'a pas été réussi
	

}//End IF There is an ID to advise

*/



//Partie HBC
$ftp_server_HBC = constant("FTP_WINDOWS_VM");
$ftp_user_HBC   = constant("FTP_USER_OPTIPRO_HBC");

$ftp_pass_HBC   = constant("FTP_PASSWORD_OPTIPRO_HBC");

// set up a connection or die
$conn_id_HBC = ftp_connect($ftp_server_HBC) or die("Couldn't connect to $ftp_server_HBC"); 
// try to login
if (@ftp_login($conn_id_HBC, $ftp_user_HBC, $ftp_pass_HBC)) {
}else {
	echo 'Probleme de connexion';
}
ftp_pasv($conn_id_HBC,true);
ftp_chdir($conn_id_HBC,"Optipro");
$directory=ftp_pwd($conn_id_HBC);
$contents=ftp_nlist($conn_id_HBC, ".");
$Compteur_HBC = 0;
foreach ($contents as $value) {//FIND NEWEST FILE
	$Compteur_HBC += 1;
}
//On soustrait les 2 dossiers pour connaitre le nombre exact  de csv en attente d'importation
$Compteur_HBC = $Compteur_HBC -3;
//Fin de la partie HBC


?>
<html>
<head>
<title>Recherche parmis les erreurs d'importation Optipro</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="charles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.2.js"></script>
<meta http-equiv="refresh" content="295"><!--Refresh every 300 seconds -->
</head>
<?php
$Aleatoire =  rand(1, 15);
switch($Aleatoire){
	case 1:  $BGCOL = 'style="background-color:#FCFCC7"'; break;
	case 2:  $BGCOL = 'style="background-color:#FCFCC7"'; break;
	case 3:  $BGCOL = 'style="background-color:#FCFCC7"'; break;
	case 4:  $BGCOL = 'style="background-color:#CDD5F9"'; break;
	case 5:  $BGCOL = 'style="background-color:#CDD5F9"'; break;
	case 6:  $BGCOL = 'style="background-color:#CDD5F9"'; break;
	case 7:  $BGCOL = 'style="background-color:#EBA4A5"'; break;
	case 8:  $BGCOL = 'style="background-color:#EBA4A5"'; break;
	case 9:  $BGCOL = 'style="background-color:#EBA4A5"'; break;
	case 10: $BGCOL = 'style="background-color:#FCFCC7"'; break;
	case 11: $BGCOL = 'style="background-color:#FCFCC7"'; break;
	case 12: $BGCOL = 'style="background-color:#FCFCC7"'; break;
	case 13: $BGCOL = 'style="background-color:#F5BDEE"'; break;
	case 14: $BGCOL = 'style="background-color:#F5BDEE"'; break;
	case 15: $BGCOL = 'style="background-color:#F5BDEE"'; break;
}
?>
<body <?php echo $BGCOL; ?>>
<form  method="post" name="optipro_today" id="optipro_today" action="rapport_erreurs_optipro_hbc.php">
    <div align="center">
     	<p align="center"><h3>Recherche parmis les erreurs d'importation Optipro</h3></p>
    
    
    <?php 
	if ($_REQUEST[message] <> ''){
		
		
		switch($_REQUEST[acct]){
			case '88431':     	$Succ = "Calgary DTN #88431";    	break;
			case '88434':  	 	$Succ  = "Market Mall #88434";     	break;
			case '88435':  	 	$Succ = "West Edmonton #88435";   	break;
			case '88416':  	 	$Succ = "Vancouver DTN #88416";   	break;
			case '88438':  	 	$Succ = "Metrotown #88438";       	break;
			case '88439':  	 	$Succ = "Langley #88439";        	break;
			case '88444':  	 	$Succ = "Mayfair #88444";         	break;
			case '88433':  	 	$Succ = "Polo Park #88433";      	break;
			case '88409':  	 	$Succ = "Eglinton #88409";       	break;
			case '88403':  	 	$Succ = "Bloor St. #88403";       	break;
			case '88408':  	 	$Succ = "Oshawa #88408";         	break;
			case '88414':  	 	$Succ = "Yorkdale #88414";       	break;
			case '88440':  	 	$Succ = "Rideau #88440";         	break;
			case '88666':  	 	$Succ = "Griffe Lunetier #88666";   break;
		}
		
		switch($_REQUEST[message]){
		case 'dejatransfere': 
		echo '<div style="width:750px;background-color:#E6F18F;"><font color="#F50004">La commande #'.$_REQUEST[order_num_optipro].' de '. $Succ . ' a déja été transféré avec succès, il est donc inutile d\'aviser la succursale.</font></div>';   break;
		}//End Switch
		
	}//End if On a un message a afficher
	?>
        <input name="submit" type="submit" id="submit" value="Voir les erreurs d'aujourd'hui" class="formField"><input name="rpt_search" type="hidden" id="rpt_search" value="search orders" class="formField">
        <label for="filter">Filtre</label>
		<input type="text" name="filter" value="" id="filter" />
		
		 <br><br>
		<a href="../scripts/script_effacer_erreurs_optipro_hbc_corrigees.php">Effacer TOUTES les erreurs corrigées</a>
    </div>
</form>


<form  method="post" name="optipro_today_part2" id="optipro_today_part2" action="rapport_erreurs_optipro_hbc.php">
<?php 		
	$rptResult=mysqli_query($con,$rptQuery) or die  ('I cannot select items because 1: ' . mysqli_error($con));
	$usercount=mysqli_num_rows($rptResult);
	$rptQuery="";
	if ($usercount == 0){
	echo '<br><br><div align="center"><h3>Excellent travail, aucune erreur présentement!</h3></div>';	
	}
	if (($usercount != 0)){//some products were found
?>

	<table width="100%" border="1" align="center" cellpadding="3" cellspacing="0" >
	<thead>
	<tr>
			<th width="3%"  align="center">Date</th>
			<th width="6%"  align="center">Compte</th>
            <th width="5%"  align="center"># Optipro</th>
			<th width="5%"  align="center">Entre leur commande</th>
            <th width="5%"  align="center">Modifier</th>
			<th width="30%" align="center">Erreur</th>
            <th width="4%"  align="center">Effacer</th>
            <th width="8%" align="center">Avisé?</th>
            <th width="8%" align="center">Courriel</th>
			<th width="8%" align="center">Transféré ?</th>
			<th width="8%" align="center">Produit Demandé</th>
			<th width="8%" align="center">Traitement </th>
			<th width="8%" align="center">Photo</th>
			<th width="8%" align="center">Polar</th>
			<th width="8%" align="center">Indice</th>
			<th width="8%" align="center">Patient</th>
			<th width="5%" align="center">Modifier produit (Ifc.ca)</th>
			<th width="3%" align="center">Aviser la direction</th>
	</tr>
		
		
    </thead>
    
    <tbody>

<?php
	
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
		
		if ($listItem[order_num_optipro] <> ''){
			
		switch($listItem[user_id]){
			
			
			
			case '88403':  $UtiliseOptipro = 'Oui';	 	$LesComptes = "('88403')";     break;
			case '88408':  $UtiliseOptipro = 'Oui';	 	$LesComptes = "('88408')";     break;
			case '88414':  $UtiliseOptipro = 'Oui';	 	$LesComptes = "('88414')";     break;
			case '88416':  $UtiliseOptipro = 'Oui'; 	$LesComptes = "('88416')";     break;
			case '88431':  $UtiliseOptipro = 'Oui';   	$LesComptes = "('88431')";     break;
			case '88435':  $UtiliseOptipro = 'Oui'; 	$LesComptes = "('88435')";     break;
			case '88438':  $UtiliseOptipro = 'Oui';		$LesComptes = "('88438')";     break;
			case '88440':  $UtiliseOptipro = 'Oui';	 	$LesComptes = "('88440')";     break;
			case '88666':  $UtiliseOptipro = 'Oui';	 	$LesComptes = "('88666')";     break;
			case '88433':  $UtiliseOptipro = 'Oui';	 	$LesComptes = "('88433')";     break;
			case '88439':  $UtiliseOptipro = 'Oui'; 	$LesComptes = "('88439')";     break;
			case '88444':  $UtiliseOptipro = 'Oui';		$LesComptes = "('88444')";     break;
			case '88409':  $UtiliseOptipro = 'Oui';	 	$LesComptes = "('88409')";     break;
			case '88434':  $UtiliseOptipro = 'Oui';		$LesComptes = "('88434')";     break;
			case '': 	   $UtiliseOptipro = 'Non';		$LesComptes = "('')";     break;
		}	

			
				$queryValiderPasser  = "SELECT * FROM orders WHERE order_status<>'cancelled' AND user_id IN $LesComptes AND order_num_optipro = '$listItem[order_num_optipro]'";
				//echo '<br>'. $queryValiderPasser. '<br>';				
				$resultValiderPasser = mysqli_query($con,$queryValiderPasser) or die  ('I cannot select items because 44: ' . mysqli_error($con));
				$CountValiderPasser  = mysqli_num_rows($resultValiderPasser);
				$DataValiderPasser   = mysqli_fetch_array($resultValiderPasser,MYSQLI_ASSOC);
				if ($CountValiderPasser > 0){
					$EtatCommande = 'Oui, #' . $DataValiderPasser[order_num];	
				}else{
					$EtatCommande = 'Non';	
				}
			}else{
				$EtatCommande = 'N/D';		
			}//End IF there is an order num optipro
			
			$valeurRedirection  = "rapport_erreurs_optipro_hbc.php?delete_id=$listItem[erreur_id]";	
			$valeurEnvoyerEmail = "rapport_erreurs_optipro_hbc.php?aviser_id=$listItem[erreur_id]"; 
			$valeurEnvoyerEmailDirection = "rapport_erreurs_optipro_hbc.php?aviser_direction_id=$listItem[erreur_id]"; 
			$valeurModifier     = "edit_optipro_hbc.php?erreur_id=$listItem[erreur_id]"; 
			
			$Notif 			    = $listItem[nombre_notification_succursale] . " fois";
			if ($listItem[nombre_notification_succursale] > 0){
				$Notif  = $Notif  . '   Dernier: <b>'.$listItem[date_derniere_notification].'</b>';
			}
		
			$NotifDirection 			    = $listItem[nombre_notification_direction] . " fois";
			if ($listItem[nombre_notification_direction] > 0){
				$NotifDirection  = 'Date de l\'avis: ' . $listItem[date_derniere_notification_direction].'</b>';
			}
			if ($NotifDirection == '0 fois') 
				$NotifDirection = '';
			//echo 'notif'. $Notif;
			
			
	?>		
  
    <tr>
			
			<td align="center"><?php  echo $listItem[date]; ?></td>
			<td align="center"><?php  if ($listItem[user_id]=='88666') echo '<b>88666 Aka Griffé</b>'; else echo $listItem[user_id]; ?></td> 
           
 
             <td align="center"><?php  echo $listItem[order_num_optipro]; ?></td>
			
			 <td align="center"><?php  echo $UtiliseOptipro; ?></td>
             <td align="center"><a href="<?php echo $valeurModifier;  ?>"><img alt="Modifier cette erreur" title="Modifier cette erreur" src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/modifier.png" width="30" height="30" alt=""/></a></td>
            
           
			<td align="center"><?php  echo $listItem[detail]; ?></td>
            <td align="center"><a href="<?php echo $valeurRedirection;  ?>"><img alt="Effacer cette erreur" title="Effacer cette erreur" src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/effacer.gif" width="30" height="30" alt=""/></a></td>
           
           
           <td align="center"><?php echo $Notif; ?></td>
                      
                      
            <td align="center">
            <?php if ($CountValiderPasser == 0){ ?>
            <a href="<?php echo $valeurEnvoyerEmail;  ?>"><img alt="Envoyer l'avis a la succursale" title="Envoyer l'avis a la succursale" src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/envoyer.jpg" width="45" height="45" alt=""/></a>
            <?php }?>
            </td>
            
            
            <td align="center"> <?php if ($CountValiderPasser > 0){ echo '<b>';} ?><?php   echo $EtatCommande; ?>  <?php if ($CountValiderPasser > 0){ echo '</b>';} ?></td>
            <td align="center"><?php  echo $listItem[produit_optipro]; ?></td>
			<td align="center"><?php  echo $listItem[rx_coating]; ?></td>
			<td align="center"><?php  if($listItem[rx_photo]<>'None') echo $listItem[rx_photo]; else echo '-'; ?></td>
			<td align="center"><?php  if($listItem[rx_polar]<>'None') echo $listItem[rx_polar]; else echo '-';  ?></td>
			<td align="center"><?php  echo $listItem[rx_index_v]; ?></td>
			<td align="center"><?php  echo $listItem[rx_patient_full_name]; ?></td>
            
		<?php	
			
			if ($listItem[cle_produit] <> ''){
			echo "<td align=\"center\"><a target=\"_blank\" href=\"".constant('DIRECT_LENS_URL')."/admin/update_exclusive_product_hbc.php?pkey=". $listItem[cle_produit]. "\">Voir"."</td>";
			}else {
			echo '<td>&nbsp;</td>';	
			}
			
			
		
		
?>
			 
 			 <td align="center">
            <?php if (($listItem[nombre_notification_direction]==0) && ($Notif>2)){ ?>
              <a href="<?php echo $valeurEnvoyerEmailDirection;  ?>"><img alt="Envoyer l'avis à la direction. Nombre d'avis envoyé à date: <?php echo $listItem[nombre_notification_direction]; ?>" title="Envoyer l'avis à la direction. Nombre d'avis envoyé à date: <?php echo $listItem[nombre_notification_direction]; ?>" src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/envoyer.jpg" width="45" height="45" alt=""/></a>
            <?php }else{?>
           		  <?php echo $NotifDirection;
				}  ?>
            
            </td>	
		
			
					
		
<?php
			
			
			echo "</tr>";
	}//END WHILE
	
echo "</tbody></table>";

}
?>
</td>
	  </tr>
</table> 

<br>
<?php //Tableau avec job dans le basket/et confirmés ?>
<table width="56%" border="1" align="center" cellpadding="3" cellspacing="0" >
	<thead>
	<tr>
			<th align="center">Compte</th>
			<th align="center" bgcolor="#ECAAAB">Panier HBC</th>
            <th align="center" bgcolor="#C7FCC4">Optipro validées</th>
		    <th align="center" bgcolor="#C7FCC4">Total (Excluant Redos)</th>
		 	<th align="center" bgcolor="#25A0DD">Redos</th>
			
	</tr>
    </thead>

<?php

		//ALBERTA
		//Alberta-1of5:#88431 Calgary DTN
		$CompteHBC              	=  " USER_ID IN ('88431')";  
		$queryJobPanierHBC 	    	= "SELECT count(order_num) as NbrCommandeHbc FROM orders WHERE $CompteHBC AND order_num = -1 "; 
		$resultPanierHBC  	    	= mysqli_query($con,$queryJobPanierHBC) or die  ('I cannot select items because 3: ' . mysqli_error($con)); 
		$DataPanierHBC  			= mysqli_fetch_array($resultPanierHBC,MYSQLI_ASSOC);
		$NbrCommandeHBC_88431_Calgary_DTN  	= $DataPanierHBC[NbrCommandeHbc] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 21: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88431_Calgary_DTN    = $DataValiderAJD[NbrCommandeTransferer];
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 22: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88431_Calgary_DTN_OP  = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 23: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_HBC_88431_Calgary_DTN                = $DataValiderAJD[NbrCommandeTransferer] ;
		
				
		//Alberta-3of5:#88434 Market Mall
		$CompteHBC              	=  " USER_ID IN ('88434')";  
		$queryJobPanierHBC 	    	= "SELECT count(order_num) as NbrCommandeHbc FROM orders WHERE $CompteHBC AND order_num = -1 "; 
		$resultPanierHBC  	    	= mysqli_query($con,$queryJobPanierHBC) or die  ('I cannot select items because 3: ' . mysqli_error($con)); 
		$DataPanierHBC  			= mysqli_fetch_array($resultPanierHBC,MYSQLI_ASSOC);
		$NbrCommandeHBC_88434_Market_Mall  	= $DataPanierHBC[NbrCommandeHbc] ;

		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 21: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88434_Market_Mall    = $DataValiderAJD[NbrCommandeTransferer];
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 22: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88434_Market_Mall_OP  = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 23: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_HBC_88434_Market_Mall       = $DataValiderAJD[NbrCommandeTransferer] ;
		
		//Alberta-4of5:#88435 West Edmonton
		$CompteHBC              	=  " USER_ID IN ('88435')";  
		$queryJobPanierHBC 	    	= "SELECT count(order_num) as NbrCommandeHbc FROM orders WHERE $CompteHBC AND order_num = -1 "; 
		$resultPanierHBC  	    	= mysqli_query($con,$queryJobPanierHBC) or die  ('I cannot select items because 3: ' . mysqli_error($con)); 
		$DataPanierHBC  			= mysqli_fetch_array($resultPanierHBC,MYSQLI_ASSOC);
		$NbrCommandeHBC_88435_West_Edmonton  	= $DataPanierHBC[NbrCommandeHbc] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 21: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88435_West_Edmonton    = $DataValiderAJD[NbrCommandeTransferer];
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 22: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88435_West_Edmonton_OP  = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 23: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_HBC_88435_West_Edmonton       = $DataValiderAJD[NbrCommandeTransferer] ;
		
		
		
		//BRITISH COLUMBIA
		//British Columbia-1of4:#88416 Vancouver DTN
		$CompteHBC              	=  " USER_ID IN ('88416')";  
		$queryJobPanierHBC 	    	= "SELECT count(order_num) as NbrCommandeHbc FROM orders WHERE $CompteHBC AND order_num = -1 "; 
		$resultPanierHBC  	    	= mysqli_query($con,$queryJobPanierHBC) or die  ('I cannot select items because 3: ' . mysqli_error($con)); 
		$DataPanierHBC  			= mysqli_fetch_array($resultPanierHBC,MYSQLI_ASSOC);
		$NbrCommandeHBC_88416_Vancouver_DTN  	= $DataPanierHBC[NbrCommandeHbc] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 21: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88416_Vancouver_DTN    = $DataValiderAJD[NbrCommandeTransferer];
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 22: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88416_Vancouver_DTN_OP  = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 23: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_HBC_88416_Vancouver_DTN       = $DataValiderAJD[NbrCommandeTransferer] ;
		//British Columbia-2of4:#88438 Metrotown
		$CompteHBC              	=  " USER_ID IN ('88438')";  
		$queryJobPanierHBC 	    	= "SELECT count(order_num) as NbrCommandeHbc FROM orders WHERE $CompteHBC AND order_num = -1 "; 
		$resultPanierHBC  	    	= mysqli_query($con,$queryJobPanierHBC) or die  ('I cannot select items because 3: ' . mysqli_error($con)); 
		$DataPanierHBC  			= mysqli_fetch_array($resultPanierHBC,MYSQLI_ASSOC);
		$NbrCommandeHBC_88438_Metrotown  	= $DataPanierHBC[NbrCommandeHbc] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 21: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88438_Metrotown    = $DataValiderAJD[NbrCommandeTransferer];
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 22: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88438_Metrotown_OP  = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 23: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_HBC_88438_Metrotown       = $DataValiderAJD[NbrCommandeTransferer] ;
		//British Columbia-3of4:#88439 Langley
		$CompteHBC              	=  " USER_ID IN ('88439')";  
		$queryJobPanierHBC 	    	= "SELECT count(order_num) as NbrCommandeHbc FROM orders WHERE $CompteHBC AND order_num = -1 "; 
		$resultPanierHBC  	    	= mysqli_query($con,$queryJobPanierHBC) or die  ('I cannot select items because 3: ' . mysqli_error($con)); 
		$DataPanierHBC  			= mysqli_fetch_array($resultPanierHBC,MYSQLI_ASSOC);
		$NbrCommandeHBC_88439_Langley  	= $DataPanierHBC[NbrCommandeHbc] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 21: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88439_Langley    = $DataValiderAJD[NbrCommandeTransferer];
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 22: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88439_Langley_OP  = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 23: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_HBC_88439_Langley      = $DataValiderAJD[NbrCommandeTransferer] ;
		//British Columbia-4of4:#88444 Mayfaire
		$CompteHBC              	=  " USER_ID IN ('88444')";  
		$queryJobPanierHBC 	    	= "SELECT count(order_num) as NbrCommandeHbc FROM orders WHERE $CompteHBC AND order_num = -1 "; 
		$resultPanierHBC  	    	= mysqli_query($con,$queryJobPanierHBC) or die  ('I cannot select items because 3: ' . mysqli_error($con)); 
		$DataPanierHBC  			= mysqli_fetch_array($resultPanierHBC,MYSQLI_ASSOC);
		$NbrCommandeHBC_88444_Mayfair  	= $DataPanierHBC[NbrCommandeHbc] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 21: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88444_Mayfair    = $DataValiderAJD[NbrCommandeTransferer];
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 22: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88444_Mayfair_OP  = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 23: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_HBC_88444_Mayfair      = $DataValiderAJD[NbrCommandeTransferer] ;
		

		
		
		
		$CompteHBC              	=  " USER_ID IN ('88433')";  
		$queryJobPanierHBC 	    	= "SELECT count(order_num) as NbrCommandeHbc FROM orders WHERE $CompteHBC AND order_num = -1 "; 
		$resultPanierHBC  	    	= mysqli_query($con,$queryJobPanierHBC) or die  ('I cannot select items because 3: ' . mysqli_error($con)); 
		$DataPanierHBC  			= mysqli_fetch_array($resultPanierHBC,MYSQLI_ASSOC);
		$NbrCommandeHBC_88433_Polo_Park  	= $DataPanierHBC[NbrCommandeHbc] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 21: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88433_Polo_Park    = $DataValiderAJD[NbrCommandeTransferer];
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 22: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88433_Polo_Park_OP  = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 23: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_HBC_88433_Polo_Park     = $DataValiderAJD[NbrCommandeTransferer] ;
		
		//ONTARIO
		//Ontario 1of10:#88409 Eglinton
		$CompteHBC              	=  " USER_ID IN ('88409')";  
		$queryJobPanierHBC 	    	= "SELECT count(order_num) as NbrCommandeHbc FROM orders WHERE $CompteHBC AND order_num = -1 "; 
		$resultPanierHBC  	    	= mysqli_query($con,$queryJobPanierHBC) or die  ('I cannot select items because 3: ' . mysqli_error($con)); 
		$DataPanierHBC  			= mysqli_fetch_array($resultPanierHBC,MYSQLI_ASSOC);
		$NbrCommandeHBC_88409_Eglinton  	= $DataPanierHBC[NbrCommandeHbc] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 21: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88409_Eglinton    = $DataValiderAJD[NbrCommandeTransferer];
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 22: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88409_Eglinton_OP  = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 23: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_HBC_88409_Eglinton     = $DataValiderAJD[NbrCommandeTransferer] ;
		
		
		
		
		
		//Ontario4of10:#88403 Bloor St
		$CompteHBC              	=  " USER_ID IN ('88403')";  
		$queryJobPanierHBC 	    	= "SELECT count(order_num) as NbrCommandeHbc FROM orders WHERE $CompteHBC AND order_num = -1 "; 
		$resultPanierHBC  	    	= mysqli_query($con,$queryJobPanierHBC) or die  ('I cannot select items because 3: ' . mysqli_error($con)); 
		$DataPanierHBC  			= mysqli_fetch_array($resultPanierHBC,MYSQLI_ASSOC);
		$NbrCommandeHBC_88403_Bloor_St 	= $DataPanierHBC[NbrCommandeHbc] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 21: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88403_Bloor_St    = $DataValiderAJD[NbrCommandeTransferer];
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 22: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88403_Bloor_St_OP  = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 23: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_HBC_88403_Bloor_St     = $DataValiderAJD[NbrCommandeTransferer] ;
		
		//Ontario5of10:#88408 Oshawa
		$CompteHBC              	=  " USER_ID IN ('88408')";  
		$queryJobPanierHBC 	    	= "SELECT count(order_num) as NbrCommandeHbc FROM orders WHERE $CompteHBC AND order_num = -1 "; 
		$resultPanierHBC  	    	= mysqli_query($con,$queryJobPanierHBC) or die  ('I cannot select items because 3: ' . mysqli_error($con)); 
		$DataPanierHBC  			= mysqli_fetch_array($resultPanierHBC,MYSQLI_ASSOC);
		$NbrCommandeHBC_88408_Oshawa = $DataPanierHBC[NbrCommandeHbc] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 21: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88408_Oshawa    = $DataValiderAJD[NbrCommandeTransferer];
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 22: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88408_Oshawa_OP  = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 23: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_HBC_88408_Oshawa  = $DataValiderAJD[NbrCommandeTransferer] ;
		

		//Ontario6of9:#88414 Yorkdale
		$CompteHBC              	=  " USER_ID IN ('88414')";  
		$queryJobPanierHBC 	    	= "SELECT count(order_num) as NbrCommandeHbc FROM orders WHERE $CompteHBC AND order_num = -1 "; 
		$resultPanierHBC  	    	= mysqli_query($con,$queryJobPanierHBC) or die  ('I cannot select items because 3: ' . mysqli_error($con)); 
		$DataPanierHBC  			= mysqli_fetch_array($resultPanierHBC,MYSQLI_ASSOC);
		$NbrCommandeHBC_88414_Yorkdale	= $DataPanierHBC[NbrCommandeHbc] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 21: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88414_Yorkdale    = $DataValiderAJD[NbrCommandeTransferer];
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 22: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88414_Yorkdale_OP  = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 23: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_HBC_88414_Yorkdale     = $DataValiderAJD[NbrCommandeTransferer] ;
		
		
		//Ontario7of9:#88440 Rideau
		$CompteHBC              	=  " USER_ID IN ('88440')";  
		$queryJobPanierHBC 	    	= "SELECT count(order_num) as NbrCommandeHbc FROM orders WHERE $CompteHBC AND order_num = -1 "; 
		$resultPanierHBC  	    	= mysqli_query($con,$queryJobPanierHBC) or die  ('I cannot select items because 3: ' . mysqli_error($con)); 
		$DataPanierHBC  			= mysqli_fetch_array($resultPanierHBC,MYSQLI_ASSOC);
		$NbrCommandeHBC_88440_Rideau 	= $DataPanierHBC[NbrCommandeHbc] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 21: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88440_Rideau    = $DataValiderAJD[NbrCommandeTransferer];
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 22: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88440_Rideau_OP  = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 23: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_HBC_88440_Rideau     = $DataValiderAJD[NbrCommandeTransferer] ;
		
		
		
		
		
		//GRIFFÉ LUNETIER #88666
		$CompteHBC              	=  " USER_ID IN ('88666')";  
		$queryJobPanierHBC 	    	= "SELECT count(order_num) as NbrCommandeHbc FROM orders WHERE $CompteHBC AND order_num = -1 "; 
		$resultPanierHBC  	    	= mysqli_query($con,$queryJobPanierHBC) or die  ('I cannot select items because 3: ' . mysqli_error($con)); 
		$DataPanierHBC  			= mysqli_fetch_array($resultPanierHBC,MYSQLI_ASSOC);
		$NbrCommandeHBC_88666_Griffe	= $DataPanierHBC[NbrCommandeHbc] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 21: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88666_Griffe   = $DataValiderAJD[NbrCommandeTransferer];
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 22: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HBC_88666_Griffe_OP  = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteHBC) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 23: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_HBC_88666_Griffe  = $DataValiderAJD[NbrCommandeTransferer] ;
		
	//Calcul des totaux
	$TotalOptipro = $NbrCommandeValiderAJD_HBC_88403_Bloor_St_OP+$NbrCommandeValiderAJD_HBC_88408_Oshawa_OP+$NbrCommandeValiderAJD_HBC_88409_Eglinton_OP+
	+$NbrCommandeValiderAJD_HBC_88414_Yorkdale_OP+$NbrCommandeValiderAJD_HBC_88416_Vancouver_DTN_OP+$NbrCommandeValiderAJD_HBC_88431_Calgary_DTN_OP+$NbrCommandeValiderAJD_HBC_88433_Polo_Park_OP+$NbrCommandeValiderAJD_HBC_88434_Market_Mall_OP+
	$NbrCommandeValiderAJD_HBC_88435_West_Edmonton_OP+$NbrCommandeValiderAJD_HBC_88438_Metrotown_OP+$NbrCommandeValiderAJD_HBC_88439_Langley_OP+$NbrCommandeValiderAJD_HBC_88440_Rideau_OP+$NbrCommandeValiderAJD_HBC_88444_Mayfair_OP;
	
	
	
	$TotalRedos = $NbrRedos_HBC_88403_Bloor_St+$NbrRedos_HBC_88408_Oshawa+$NbrRedos_HBC_88409_Eglinton+ $NbrRedos_HBC_88414_Yorkdale 
	 +$NbrRedos_HBC_88416_Vancouver_DTN+$NbrRedos_HBC_88431_Calgary_DTN+$NbrRedos_HBC_88433_Polo_Park +$NbrRedos_HBC_88434_Market_Mall
	 +$NbrRedos_HBC_88435_West_Edmonton+$NbrRedos_HBC_88438_Metrotown+$NbrRedos_HBC_88439_Langley+$NbrRedos_HBC_88440_Rideau+$NbrRedos_HBC_88444_Mayfair;
	 
	
	$totalPanierIFC = $NbrCommandeHBC_88403_Bloor_St+$NbrCommandeHBC_88408_Oshawa+$NbrCommandeHBC_88409_Eglinton+$NbrCommandeHBC_88414_Yorkdale+
$NbrCommandeHBC_88416_Vancouver_DTN+$NbrCommandeHBC_88431_Calgary_DTN+$NbrCommandeHBC_88433_Polo_Park+$NbrCommandeHBC_88434_Market_Mall+
$NbrCommandeHBC_88435_West_Edmonton+$NbrCommandeHBC_88438_Metrotown+$NbrCommandeHBC_88439_Langley+$NbrCommandeHBC_88440_Rideau+$NbrCommandeHBC_88444_Mayfair;
	
	
	$totalValidees = $NbrCommandeValiderAJD_HBC_88403_Bloor_St+$NbrCommandeValiderAJD_HBC_88408_Oshawa+$NbrCommandeValiderAJD_HBC_88409_Eglinton+
	+$NbrCommandeValiderAJD_HBC_88414_Yorkdale+$NbrCommandeValiderAJD_HBC_88416_Vancouver_DTN+$NbrCommandeValiderAJD_HBC_88431_Calgary_DTN+$NbrCommandeValiderAJD_HBC_88433_Polo_Park+$NbrCommandeValiderAJD_HBC_88434_Market_Mall+
	$NbrCommandeValiderAJD_HBC_88435_West_Edmonton+$NbrCommandeValiderAJD_HBC_88438_Metrotown+$NbrCommandeValiderAJD_HBC_88439_Langley+$NbrCommandeValiderAJD_HBC_88440_Rideau+$NbrCommandeValiderAJD_HBC_88444_Mayfair;
?>	




		 <?php  //#88403 Bloor St. (Ontario)  Position #1?>
	<tr>
			<th align="left">#88403 Bloor St. (ON)</th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeHBC_88403_Bloor_St;?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88403_Bloor_St_OP; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88403_Bloor_St; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo $NbrRedos_HBC_88403_Bloor_St; ?></th>
   </tr>
	
	
			
		 <?php  //#88408 Oshawa (Ontario) Position #3 ?>
	<tr>
			<th align="left">#88408 Oshawa (ON)</th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeHBC_88408_Oshawa;?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88408_Oshawa_OP; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88408_Oshawa; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo $NbrRedos_HBC_88408_Oshawa; ?></th>
   </tr>
	
		 <?php  //#88409 Eglinton (Ontario) Position #4 ?>
	<tr>
			<th align="left">#88409 Eglinton (ON)</th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeHBC_88409_Eglinton;?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88409_Eglinton_OP; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88409_Eglinton; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo $NbrRedos_HBC_88409_Eglinton; ?></th>
   </tr>
	
	
	 <?php  //#88414 Yorkdale (Ontario) Position #7	 ?>
	<tr>
			<th align="left">#88414 Yorkdale (ON)</th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeHBC_88414_Yorkdale;?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88414_Yorkdale_OP; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88414_Yorkdale; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo $NbrRedos_HBC_88414_Yorkdale; ?></th>
   </tr>
    <?php  //#88416 Vancouver DTN (British Columbia) Position #8 ?>
	<tr>
			<th align="left">#88416 Vancouver DTN (BC)</th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeHBC_88416_Vancouver_DTN;?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88416_Vancouver_DTN_OP; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88416_Vancouver_DTN; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo $NbrRedos_HBC_88416_Vancouver_DTN; ?></th>
   </tr>
	

<?php  //#88431 Calgary-DTN (Alberta) Position #11 ?>
	<tr>
			<th align="left">#88431 Calgary-DTN (AB)</th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeHBC_88431_Calgary_DTN;?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88431_Calgary_DTN_OP; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88431_Calgary_DTN; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo $NbrRedos_HBC_88431_Calgary_DTN; ?></th>
   </tr>
    
    

 
	 <?php  //#88433 Polo Park (Manitoba) Position #13 ?>
	<tr>
			<th align="left">#88433 Polo Park (MB)</th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeHBC_88433_Polo_Park;?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88433_Polo_Park_OP; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88433_Polo_Park; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo $NbrRedos_HBC_88433_Polo_Park; ?></th>
   </tr>
    
  <?php  //#88434 Market Mall (Alberta) Position #14 ?>
	<tr>
			<th align="left">#88434 Market Mall (AB)</th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeHBC_88434_Market_Mall;?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88434_Market_Mall_OP; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88434_Market_Mall; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo $NbrRedos_HBC_88434_Market_Mall; ?></th>
   </tr>
      
    <?php  //#88435 West Edmonton (Alberta) Position #15 ?>
	<tr>
			<th align="left">#88435 West Edmonton (AB)</th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeHBC_88435_West_Edmonton;?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88435_West_Edmonton_OP; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88435_West_Edmonton; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo $NbrRedos_HBC_88435_West_Edmonton; ?></th>
   </tr>
	
	<?php  //#88438 Metrotown (British Columbia) Position #16 ?>
	<tr>
			<th align="left">#88438 Metrotown (BC)</th>
			<th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeHBC_88438_Metrotown;?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88438_Metrotown_OP; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88438_Metrotown; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo $NbrRedos_HBC_88438_Metrotown; ?></th>
   </tr>
   
		 <?php  //#88439 Langley (British Columbia) Position #17 ?>
	<tr>
			<th align="left">#88439 Langley (BC)</th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeHBC_88439_Langley;?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88439_Langley_OP; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88439_Langley; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo $NbrRedos_HBC_88439_Langley; ?></th>
   </tr>
   
	 <?php  //#88440 Rideau (Ontario) Position #18 ?>
	<tr>
			<th align="left">#88440 Rideau (ON) </th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeHBC_88440_Rideau;?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88440_Rideau_OP; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88440_Rideau; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo $NbrRedos_HBC_88440_Rideau; ?></th>
   </tr>
	
   
   	 <?php  //#88444 Mayfair (British Columbia) Position #21 ?>
	<tr>
			<th align="left">#88444 Mayfair (BC)</th>
            <th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeHBC_88444_Mayfair;?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88444_Mayfair_OP; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88444_Mayfair; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo $NbrRedos_HBC_88444_Mayfair; ?></th>
   </tr>	
	
		
	

	
	
    <tr>
			<th align="center">Total</th>
			<th align="center" bgcolor="#ECAAAB"><?php echo $totalPanierIFC;?></th>
            <th align="center" bgcolor="#C7FCC4" >Total Optipro:<?php echo $TotalOptipro?></th>
		 	<th align="center" bgcolor="#C7FCC4">Grand Total validées excluant redos:<?php echo $totalValidees;?></th>
			<th align="center" bgcolor="#25A0DD">Total Redos:<?php echo $TotalRedos; ?></th>
	</tr>
    </table>
	
	<br><br>
	<table width="56%" border="1" align="center" cellpadding="3" cellspacing="0" >
		<thead>
	<tr>
			<th align="center">Compte</th>
			<th align="center" bgcolor="#ECAAAB">Panier HBC</th>
            <th align="center" bgcolor="#C7FCC4">Optipro validées</th>
		    <th align="center" bgcolor="#C7FCC4">Total (Excluant Redos)</th>
		 	<th align="center" bgcolor="#25A0DD">Redos</th>
			
	</tr>
    </thead>
	 <tr>
			<th align="center">Griffé Lunetier</th>
			<th align="center" bgcolor="#ECAAAB"><?php echo $NbrCommandeHBC_88666_Griffe;?></th>
            <th align="center" bgcolor="#C7FCC4" ><?php echo $NbrCommandeValiderAJD_HBC_88666_Griffe_OP?></th>
		 	<th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HBC_88666_Griffe;?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo $NbrRedos_HBC_88666_Griffe; ?></th>
	</tr>
	
	</table>
    
    <br>
    

    
    
    <?php  
	$queryBasket  = "SELECT max(update_time)as max_last_basket, order_primary_key  FROM status_history WHERE order_status='Basket'";
	$resultBasket = mysqli_query($con,$queryBasket) or die  ('I cannot select items because 55: ' . mysqli_error($con));
	$DataBasket   = mysqli_fetch_array($resultBasket,MYSQLI_ASSOC);	

	$queryDetailBasket  = "SELECT user_id, order_num_optipro FROM orders WHERE order_num= (SELECT order_num FROM orders  WHERE primary_key= $DataBasket[order_primary_key])";
	$resultDetailBasket = mysqli_query($con,$queryDetailBasket) or die  ('I cannot select items because 55: ' . mysqli_error($con));
	$DataDetailBasket   = mysqli_fetch_array($resultDetailBasket,MYSQLI_ASSOC);	


	$datelive    	   = mktime(0,0,0,date("m"),date("d"),date("Y"));
	$datecomplete 	   = date("Y-m-d", $datelive);

	$datehier    	   = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
	$datecompletehier  = date("Y-m-d", $datehier);

	$queryCountBasket  = "SELECT count(*) as NbrImportation FROM status_history WHERE order_status='Basket' AND update_time like '%$datecomplete%' ";
	$resultCountBasket = mysqli_query($con,$queryCountBasket) or die  ('I cannot select items because 55: ' . mysqli_error($con));
	$DataCountBasket   = mysqli_fetch_array($resultCountBasket,MYSQLI_ASSOC);	

	$queryCountBasketHier  = "SELECT count(*) as NbrImportation FROM status_history WHERE order_status='Basket' AND update_time like '%$datecompletehier%' ";
	$resultCountBasketHier = mysqli_query($con,$queryCountBasketHier) or die  ('I cannot select items because 55: ' . mysqli_error($con));
	$DataCountBasketHier   = mysqli_fetch_array($resultCountBasketHier,MYSQLI_ASSOC);	
	?>
    
    
    
    <br>
     <table align="center"  width="1000" border="1">
    	<tr align="center">
        	<td colspan="2" width="20%" align="center"><h2 align="center">Importation</h2></td></td>
        </tr>
        
		<tr  align="center">
        	<td width="20%"><h3>Nombre de fichier en attente d'importation:</h3></td>
            <td width="20%"><h3><?php echo $Compteur_HBC; ?></h3></td>
        </tr>

        <tr  align="center">
        	<td width="20%"><h3>Dernière commande importée avec succès:</h3></td>
            <td width="20%"><h3><?php echo $DataBasket[max_last_basket]; ?>   Commande #<?php echo $DataDetailBasket[order_num_optipro];?>--> <?php echo $DataDetailBasket[user_id]; ?></h3></td>
        </tr>

        <tr  align="center">
        	<td width="20%"><h3>Nombre d'importation avec succès aujourd'hui/hier </h3></td>
            <td width="20%"><h3><?php echo $DataCountBasket[NbrImportation];?> / <?php echo $DataCountBasketHier[NbrImportation];?></h3></td>

    </table>
	
	
	
 <?php
//PARTIE Commandes avec Shapes
$today      = date("Y-m-d");
$rptQuery   = "SELECT * FROM orders
WHERE prescript_lab IN (10,73,2,4,4)
AND order_date_processed='$today' AND shape_name_bk<>''
AND order_status NOT IN ('cancelled', 'on hold','basket')
ORDER BY prescript_lab, shape_name_bk desc";

//echo '<br>Query: <br>'. $rptQuery . '<br>';	

$rptResult = mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 1b1: . <br>'. $rptQuery . ' <br>'. mysqli_error($con));
$ordersnum = mysqli_num_rows($rptResult);
	
$count   = 0;
$message = "";		
$message="<html>
<head>
	<meta charset=\"utf-8\">
	<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
</head>";

$message.="<body><br><br>
<div align=\"center\"><table class=\"table\" border=\"1\">
<tr><th colspan=\"9\">Ce rapport inclus toutes les commandes avec une forme envoyées vers Swiss, HKO, KNR, GKB, STC</th></tr>";
$message.="<tr bgcolor=\"CCCCCC\">
	<th align=\"center\" width=\"150\">Date confirmation</th>
	<th align=\"center\">EDLL Order #</th>
	<th align=\"center\">Redo #</th>
	<th align=\"center\">Prescript Lab</th>
	<th align=\"center\">Fichier de trace</th>
	<th align=\"center\">Myupload</th>
	<th align=\"center\" width=\"150\">Resultat Shapes</th>
	<th align=\"center\">Date copie shape</th>
	<th align=\"center\">Status</th>
</tr>";
				
while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){ 			
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$QueryConfirmation   = "SELECT update_time FROM status_history
	WHERE order_num = $listItem[order_num]
	AND order_status='processing'";
	$resultConfirmation = mysqli_query($con,$QueryConfirmation)		or die  ('I cannot select items because 1c: ' . mysqli_error($con));
	$DataConfirmation   = mysqli_fetch_array($resultConfirmation,MYSQLI_ASSOC);
		
	$message.="
	<tr>
		<td align=\"center\">$DataConfirmation[update_time]</td>
		<td align=\"center\">$listItem[order_num]</td>
		<td align=\"center\">$listItem[redo_order_num]</td>
		<td align=\"center\">$listItem[prescript_lab]</td>";
		
		if ($listItem[shape_copied_ftp]=='0000-00-00 00:00:00'){
			$message.="<td align=\"center\"><b>$listItem[shape_name_bk]</b></td>";
		}else{
			$message.="<td align=\"center\">$listItem[shape_name_bk]</td>";
		}
		$message.="<td align=\"center\">$listItem[myupload]</td>
		<td bgcolor=\"#D8B5B5\" align=\"center\">$listItem[result_copy_ftp_swiss]</td>
		<td bgcolor=\"#D8B5B5\" align=\"center\">$listItem[shape_copied_swiss_ftp]</td>
		<td bgcolor=\"#D8B5B5\" align=\"center\">$listItem[order_status]</td>
	</tr>";

		
}//END WHILE  

echo $message; ?>



<?php
//PARTIE Commandes sans Shapes
$today      = date("Y-m-d");
$rptQuerySansShape   = "SELECT * FROM orders
WHERE prescript_lab IN (10,73,2,3,4) 
AND order_date_processed='$today' AND shape_name_bk=''
AND order_status NOT IN ('cancelled', 'on hold','basket')
ORDER BY prescript_lab, shape_name_bk desc";

//echo '<br><br>Query: <br>'. $rptQuerySansShape . '<br>';	

$rptResultSansShape = mysqli_query($con,$rptQuerySansShape)		or die  ('I cannot select items because 1b2: . <br>'. $rptQuerySansShape . ' <br>'. mysqli_error($con));
$ordersnum 		    = mysqli_num_rows($rptResultSansShape);
	
$count   = 0;
$message2 = "";		
$message2="<html>
<head>
	<meta charset=\"utf-8\">
	<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
</head>";

$message2.="<body>
<div align=\"center\"><table class=\"table\" border=\"1\">
<tr><th colspan=\"8\">Ce rapport inclus toutes les commandes Swiss, HKO, KNR, GKB, STC <u>SANS</u> forme</th></tr>";
$message2.="<tr bgcolor=\"CCCCCC\">
	<th align=\"center\" width=\"150\">Date confirmation</th>
	<th align=\"center\">Compte</th>
	<th align=\"center\">Order #</th>
	<th align=\"center\">Redo #</th>
	<th align=\"center\">Prescript Lab</th>
	<th align=\"center\">Shape Name BK</th>
	<th align=\"center\">Myupload</th>
	<th align=\"center\">Monture</th>
</tr>";
				
while ($listItem=mysqli_fetch_array($rptResultSansShape,MYSQLI_ASSOC)){ 	

$queryFrame  = "SELECT supplier,	temple_model_num, color  FROM extra_product_orders WHERE category='Frame' and order_num = $listItem[order_num]";
$ResultFrame = mysqli_query($con,$queryFrame)		or die  ('I cannot select items because 1b2: . <br>'. $queryFrame . ' <br>'. mysqli_error($con));
$DataFrame   = mysqli_fetch_array($ResultFrame,MYSQLI_ASSOC);


	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$QueryConfirmation   = "SELECT update_time FROM status_history
	WHERE order_num = $listItem[order_num]
	AND order_status='processing'";
	$resultConfirmation = mysqli_query($con,$QueryConfirmation)		or die  ('I cannot select items because 1c: ' . mysqli_error($con));
	$DataConfirmation   = mysqli_fetch_array($resultConfirmation,MYSQLI_ASSOC);
		
	$message2.="
	<tr>
		<td align=\"center\">$DataConfirmation[update_time]</td>
		<td align=\"center\">$listItem[user_id]</td>
		<td align=\"center\">$listItem[order_num]</td>
		<td align=\"center\">$listItem[redo_order_num]</td>
		<td align=\"center\">$listItem[prescript_lab]</td>
		<td align=\"center\">$listItem[shape_name_bk]</td>
		<td align=\"center\">$listItem[myupload]</td>
		<td align=\"center\">$DataFrame[supplier] $DataFrame[temple_model_num]  $DataFrame[color]</td>";
		
		
		$message2.="</tr>";

		
}//END WHILE  

echo $message2; ?>

	

  <p>&nbsp;</p>
<script src="js/ajax.js"></script>
</body>
</html>