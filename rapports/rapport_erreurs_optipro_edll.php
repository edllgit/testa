<?php 
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
//ini_set('display_errors', '0');
require_once(__DIR__.'/../constants/aws.constant.php');
require_once(__DIR__.'/../constants/ftp.constant.php');
require_once(__DIR__.'/../constants/url.constant.php');
require_once('../sec_connectEDLL.inc.php'); 
require_once ('../phpmailer_email_functions.inc.php'); 


//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();


//include "../getlang.php";
session_start();
//include("admin_functions.inc.php");
$tomorrow  = mktime(0,0,0,date("m"),date("d"),date("Y"));
$ajd 	   = date("Y-m-d", $tomorrow);

//Search errors of the day   
		   $rptQuery="SELECT * FROM erreurs_optipro 
		   WHERE  detail NOT LIKE '%a deja ete importee pour ce client%'
		   AND user_id NOT IN ('test','','griffe')
		   AND order_num_optipro <> 0
		   AND active =1
		   ORDER BY  order_num_optipro, nombre_notification_succursale desc"; 
		   

if ($_REQUEST[delete_id] <> ''){
	//0-Aller chercher le user id concerné par l'erreur
	$queryUserid  = "SELECT user_id, order_num_optipro from erreurs_optipro WHERE erreur_id = $_REQUEST[delete_id]";
	$resultUserID = mysqli_query($con,$queryUserid) or die  ('I cannot select items because 222: ' . mysqli_error($con));
	$DataUserID   = mysqli_fetch_array($resultUserID,MYSQLI_ASSOC);
	$UserID       = $DataUserID[user_id];
	$LorderNumOptipro  = $DataUserID[order_num_optipro];
	switch($UserID){
		case 'granby':      	case 'granbysafe':      		$User_ID_IN = " ('granby','granbysafe') ";	       		break;
		case 'levis': 	    	case 'levissafe':      	 		$User_ID_IN = " ('levis','levissafe') ";	       		break;
		case 'chicoutimi':  	case 'chicoutimisafe': 	 		$User_ID_IN = " ('chicoutimi','chicoutimisafe') "; 		break;
		case 'entrepotifc': 	case 'entrepotsafe':   	 		$User_ID_IN = " ('entrepotifc','entrepotsafe') ";  		break;
		case 'entrepotdr':  	case 'safedr':         	 		$User_ID_IN = " ('entrepotdr','safedr') ";         		break;
		case 'laval': 			case 'lavalsafe':      	 		$User_ID_IN = " ('laval','lavalsafe') ";           		break;
		case 'terrebonne':  	case 'terrebonnesafe': 	 		$User_ID_IN = " ('terrebonne','terrebonnesafe') "; 		break;
		case 'sherbrooke':  	case 'sherbrookesafe': 	 		$User_ID_IN = " ('sherbrooke','sherbrookesafe') ";		break;
		case 'longueuil':   	case 'longueuilsafe':  	 		$User_ID_IN = " ('longueuil','longueuilsafe') ";   		break;
		case 'entrepotquebec': 	case 'quebecsafe': 				$User_ID_IN = " ('entrepotquebec','quebecsafe') "; 		break;	
		case 'warehousehal':  	case 'warehousehalsafe':		$User_ID_IN = " ('warehousehal','warehousehalsafe') ";	break;	
		//case 'montreal':  		case 'montrealsafe':			$User_ID_IN = " ('montreal','montrealsafe') ";			break;
		case 'gatineau':  		case 'gatineausafe':			$User_ID_IN = " ('gatineau','gatineausafe') ";			break;	
		case 'stjerome':  		case 'stjeromesafe':			$User_ID_IN = " ('stjerome','stjeromesafe') ";			break;	
		case 'edmundston':  	case 'edmundstonsafe':			$User_ID_IN = " ('edmundston','edmundstonsafe') ";		break;	
		case 'vaudreuil':  		case 'vaudreuilsafe':			$User_ID_IN = " ('vaudreuil','vaudreuilsafe') ";		break;	
		case 'sorel':  			case 'sorelsafe':				$User_ID_IN = " ('sorel','sorelsafe') ";				break;	
		case 'moncton':  		case 'monctonsafe':				$User_ID_IN = " ('moncton','monctonsafe') ";			break;	
		case 'fredericton':  	case 'frederictonsafe':			$User_ID_IN = " ('fredericton','frederictonsafe') ";	break;	
		case '88666':  											$User_ID_IN = " ('88666') ";							break;
		case 'stjohn':  		case 'stjohnsafe':				$User_ID_IN = " ('stjohn','stjohnsafe') ";				break;
		//case 'halifax':  	case 'warehousehalsafe':		$User_ID_IN = " ('warehousehal','warehousehalsafe') ";	break;
	
	}	
		
	//1-Vérifier si la commande a été 'transféré avec succès'
	$queryValiderPasser  = "SELECT count(order_num) as NbMatch FROM orders WHERE order_status <> 'cancelled' AND user_id IN $User_ID_IN AND order_num_optipro = $LorderNumOptipro";
	echo '<br>'. $queryValiderPasser;
	$resultValiderPasser = mysqli_query($con, $queryValiderPasser) or die  ('I cannot select items because 2222: ' . mysqli_error($con));
	//$CountValiderPasser  = mysql_num_rows($resultValiderPasser);
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
			//echo '<br>'. $queryDelete;
			$resultDelete = mysqli_query($con,$queryDelete) or die  ('I cannot delete  items because 5: ' . mysqli_error($con));
		}//End While
	}else{
	echo '<br>Aucun match, la commande n\'a pas été transféré. Donc, aucun autre ID a effacer. ';	
	}//End IF
		
		
		//Effacer le tuple
		$queryDetail  = "SELECT * FROM erreurs_optipro WHERE erreur_id = $_REQUEST[delete_id] ORDER BY user_id, order_num_optipro";
		//echo '<br>'. $queryDetail;
		$resultDetail = mysqli_query($con,$queryDetail) or die  ('I cannot select items because 4: ' . mysqli_error($con));
		$DataDetail   = mysqli_fetch_array($resultDetail,MYSQLI_ASSOC);
		
		//echo '<br>ID A EFFACER: ' . $_REQUEST[delete_id];
		//$queryDelete  = "DELETE FROM erreurs_optipro WHERE erreur_id = $_REQUEST[delete_id]";
		$queryDelete  = "UPDATE erreurs_optipro SET active=0 WHERE erreur_id = $_REQUEST[delete_id]";
		//echo '<br>'. $queryDelete;
		$resultDelete = mysqli_query($con,$queryDelete) or die  ('I cannot delete  items because 5: ' . mysqli_error($con));
		echo '<br>Tuple Effacé..Redirection en cours';
		
		//Rediriger à la date ou la commande a été effacée
		if  ($DataDetail[date]<>''){
			header("Location: rapport_erreurs_optipro_edll.php");
			exit();	
		} 
}//End IF There is an ID to delete





if ($_REQUEST[aviser_id] <> ''){
	//Aviser la succursale de cette erreur par courriel
	$queryDetail  = "SELECT * FROM erreurs_optipro WHERE erreur_id = $_REQUEST[aviser_id] ORDER BY user_id, order_num_optipro";
	echo '<br>'. $queryDetail .'<br>';
	$resultDetail = mysqli_query($con,$queryDetail) or die  ('I cannot select items because 6a: ' . mysqli_error($con));
	$DataDetail   = mysqli_fetch_array($resultDetail,MYSQLI_ASSOC);
	
	switch($DataDetail[user_id]){
		case 'granby':      	case 'granbysafe':	      	$Succursale = "Granby";         $EmailSuccursale = "granby@entrepotdelalunette.com";         	break;
		case 'levis': 	    	case 'levissafe':       	$Succursale = "Lévis";          $EmailSuccursale = "levis@entrepotdelalunette.com";          	break;
		case 'chicoutimi':  	case 'chicoutimisafe':  	$Succursale = "Chicoutimi";     $EmailSuccursale = "chicoutimi@entrepotdelalunette.com";     	break;
		case 'entrepotquebec':  case 'quebecsafe':   		$Succursale = "Québec";         $EmailSuccursale = "quebec@entrepotdelalunette.com";    	 	break;
		case 'entrepotifc': 	case 'entrepotsafe':    	$Succursale = "Trois-Rivières"; $EmailSuccursale = "trois-rivieres@entrepotdelalunette.com"; 	break;
		case 'entrepotdr':  	case 'safedr':          	$Succursale = "Drummondville";  $EmailSuccursale = "drummondville@entrepotdelalunette.com";  	break;
		case 'laval': 			case 'lavalsafe':       	$Succursale = "Laval"; 			$EmailSuccursale = "laval@entrepotdelalunette.com";          	break;
		case 'terrebonne':  	case 'terrebonnesafe':  	$Succursale = "Terrebonne"; 	$EmailSuccursale = "terrebonne@entrepotdelalunette.com";     	break;
		case 'sherbrooke':  	case 'sherbrookesafe':  	$Succursale = "Sherbrooke"; 	$EmailSuccursale = "sherbrooke@entrepotdelalunette.com";     	break;
		case 'longueuil':   	case 'longueuilsafe':   	$Succursale = "Longueuil"; 		$EmailSuccursale = "longueuil@entrepotdelalunette.com";      	break;
		case 'warehousehal':	case 'warehousehalsafe':   	$Succursale = "Halifax"; 		$EmailSuccursale = "halifax@opticalwarehouse.ca";     		 	break;
		//case 'montreal':		case 'montrealsafe':   		$Succursale = "Montréal"; 		$EmailSuccursale = "dbeaulieu@direct-lens.com"; break;
		case 'gatineau':		case 'gatineausafe':   		$Succursale = "Gatineau"; 		$EmailSuccursale = "gatineau@entrepotdelalunette.com";  		break;
		case 'stjerome':		case 'stjeromesafe':   		$Succursale = "St-Jérôme"; 		$EmailSuccursale = "st-jerome@entrepotdelalunette.com";  		break;
		case 'edmundston':		case 'edmundstonsafe':   	$Succursale = "Edmundston"; 	$EmailSuccursale = "edmundston@entrepotdelalunette.com";  		break;
		case 'vaudreuil':		case 'vaudreuilsafe':   	$Succursale = "Vaudreuil"; 		$EmailSuccursale = "vaudreuil@entrepotdelalunette.com";  		break;
		case 'sorel':			case 'sorelsafe':			$Succursale = "Sorel"; 			$EmailSuccursale = "sorel-tracy@entrepotdelalunette.com";  		break;
		case 'moncton':			case 'monctonsafe':			$Succursale = "Moncton"; 		$EmailSuccursale = "moncton@entrepotdelalunette.com";  			break;
		case 'fredericton':		case 'frederictonsafe':		$Succursale = "Fredericton"; 	$EmailSuccursale = "fredericton@entrepotdelalunette.com";  		break;
		case 'stjohn':		    case 'stjohnsafe':		    $Succursale = "St John"; 	    $EmailSuccursale = "st-John@opticalwarehouse.ca";  		        break;
		case '88666':										$Succursale = "Griffé lunetier #88666"; 		$EmailSuccursale = "trois-rivieres@griffelunetier.com";  		break;
	}
	
	
	/*	switch($DataDetail[user_id]){
		case 'granby':      	case 'granbysafe':	      	$Succursale = "Granby";         $EmailSuccursale = "fdjibrilla@entrepotdelalunette.com";         	break;
		case 'levis': 	    	case 'levissafe':       	$Succursale = "Lévis";          $EmailSuccursale = "fdjibrilla@entrepotdelalunette.com";          	break;
		case 'chicoutimi':  	case 'chicoutimisafe':  	$Succursale = "Chicoutimi";     $EmailSuccursale = "fdjibrilla@entrepotdelalunette.com";     	break;
		case 'entrepotquebec':  case 'quebecsafe':   		$Succursale = "Québec";         $EmailSuccursale = "fdjibrilla@entrepotdelalunette.com";    	 	break;
		case 'entrepotifc': 	case 'entrepotsafe':    	$Succursale = "Trois-Rivières"; $EmailSuccursale = "fdjibrilla@entrepotdelalunette.com"; 	break;
		case 'entrepotdr':  	case 'safedr':          	$Succursale = "Drummondville";  $EmailSuccursale = "fdjibrilla@entrepotdelalunette.com";  	break;
		case 'laval': 			case 'lavalsafe':       	$Succursale = "Laval"; 			$EmailSuccursale = "fdjibrilla@entrepotdelalunette.com";          	break;
		case 'terrebonne':  	case 'terrebonnesafe':  	$Succursale = "Terrebonne"; 	$EmailSuccursale = "fdjibrilla@entrepotdelalunette.com";     	break;
		case 'sherbrooke':  	case 'sherbrookesafe':  	$Succursale = "Sherbrooke"; 	$EmailSuccursale = "fdjibrilla@entrepotdelalunette.com";     	break;
		case 'longueuil':   	case 'longueuilsafe':   	$Succursale = "Longueuil"; 		$EmailSuccursale = "fdjibrilla@entrepotdelalunette.com";      	break;
		case 'warehousehal':	case 'warehousehalsafe':   	$Succursale = "Halifax"; 		$EmailSuccursale = "fdjibrilla@entrepotdelalunette.com";     		 	break;
		case 'montreal':		case 'montrealsafe':   		$Succursale = "Montréal"; 		$EmailSuccursale = "fdjibrilla@entrepotdelalunette.com"; break;
		case 'gatineau':		case 'gatineausafe':   		$Succursale = "Gatineau"; 		$EmailSuccursale = "fdjibrilla@entrepotdelalunette.com";  		break;
		case 'stjerome':		case 'stjeromesafe':   		$Succursale = "St-Jérôme"; 		$EmailSuccursale = "fdjibrilla@entrepotdelalunette.com";  		break;
		case 'edmundston':		case 'edmundstonsafe':   	$Succursale = "Edmundston"; 	$EmailSuccursale = "fdjibrilla@entrepotdelalunette.com";  		break;
		case 'vaudreuil':		case 'vaudreuilsafe':   	$Succursale = "Vaudreuil"; 		$EmailSuccursale = "fdjibrilla@entrepotdelalunette.com";  		break;
		case 'sorel':			case 'sorelsafe':			$Succursale = "Sorel"; 			$EmailSuccursale = "fdjibrilla@entrepotdelalunette.com";  		break;
		case 'moncton':			case 'monctonsafe':			$Succursale = "Moncton"; 		$EmailSuccursale = "fdjibrilla@entrepotdelalunette.com";  			break;
		case 'fredericton':		case 'frederictonsafe':		$Succursale = "Fredericton"; 	$EmailSuccursale = "fdjibrilla@entrepotdelalunette.com";  		break;
	}*/
	
	echo '<br><b>Succursale</b>:' .  $Succursale. 	
	     '<br><b>Email</b>: '     . $EmailSuccursale	
	   . '<br><b>Num commande optipro</b>:' .$DataDetail[order_num_optipro]	
	   . '<br><b>Produit demandé</b> :' .$DataDetail[produit_optipro]
	   . '<br><b>Detail</b>:' .$DataDetail[detail]
	   . '<br><b>ID</b>:' .$DataDetail[erreur_id]  
	   . '<br><b>Nbr nbotification</b>:' .$DataDetail[nombre_notification_succursale] ;  
	$NombrePrecedentNotification = $DataDetail[nombre_notification_succursale];
	
	
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
	</style>
	<!-- Bootstrap core CSS -->
	<link href=\"http://www.direct-lens.com/bootstrap/css/bootstrap.min.css\" rel=\"stylesheet\">
	<!-- Custom styles for this template -->
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src=\"https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js\"></script>
	<script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
	<![endif]--></head>";
	$message.='<body>';
	
	if ($Succursale <> "Halifax"){
	$message.="
		<p>Bonjour $Succursale, <br>il y a un problème avec votre facture Optipro <b>#$DataDetail[order_num_optipro]</b>.<br><br>
		Le problème est le suivant:<i><b>$DataDetail[detail]</b></i>.<br> <br>Merci de faire la correction nécessaire, sauvegarder puis ré-exporter la commande.<br>
		Ne pas répondre à ce courriel directement, puisque personne ne recevra votre réponse. Si vous avez des questions, contactez directement Charles ou transférez-lui ce courriel avec votre interrogation.
		<br><br>
		P.S. Si vous avez déja réussi à transférer cette commande, ne tenez pas compte de ce courriel.<br>
		Bonne journée.
		</p>";	
	}else{
	//La succursale étant Halifax, le email doit être envoyé en Anglais
		$message.="
		<p>Hi  $Succursale, <br> There is a problem with your Optipro Invoice <b>#$DataDetail[order_num_optipro]</b>.<br><br>
		The problem is the following:<i><b>$DataDetail[detail]</b></i>.<br> <br>Thanks to do the necessary updates, save and re-export the order.<br>
		Please do not reply to this email directly, since it won't be received. Ne pas répondre à ce courriel directement, puisque personne ne recevra votre réponse. Si vous avez des questions, contactez directement Charles ou transférez-lui ce courriel avec votre interrogation.
		<br><br>
		P.S. Si vous avez déja réussi à transférer cette commande, ne tenez pas compte de ce courriel.<br>
		Bonne journée.
		</p>";		
	}
	//Send EMAIL
			

	$send_to_address[] = $EmailSuccursale;	
	$curTime      = date("m-d-Y");	
	$to_address   = $send_to_address;
	$from_address = 'donotreply@entrepotdelalunette.com';
	$subject      = "Erreur Optipro $DataDetail[order_num_optipro] $Succursale";
	$response     = office365_mail($to_address, $from_address, $subject, null, $message);
	
	//Avant d'envoyer le courriel, on valide que la commande n'as pas été transféré avec succès
	$queryValiderPasser  = "SELECT * FROM orders WHERE order_status<>'cancelled' AND user_id = '$DataDetail[user_id]' AND order_num_optipro = $DataDetail[order_num_optipro]";
	echo '<br>'.$queryValiderPasser ;
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
			header("Location: rapport_erreurs_optipro_edll.php");
			exit();	
		}else{
			echo '<br>Erreur durant l\'envoie du courriel..';	
		}
	}else{
	//Redirection vers optopro_today tout en avisant que la commande est déja transféré.
	//echo 'Cette commande a déja été transféré avec succès, il est donc inutile d\'aviser la succursale. ';
	//exit();	
	header("Location: rapport_erreurs_optipro_edll.php?message=dejatransfere&order_num_optipro=$DataDetail[order_num_optipro]&acct=$DataDetail[user_id]");
	exit();	
	}//End if Transfert n'a pas été réussi
	

}//End IF There is an ID to advise




if ($_REQUEST[aviser_direction_id] <> ''){

	//Aviser la direction de cette erreur par courriel
	$queryDetail    = "SELECT * FROM erreurs_optipro WHERE erreur_id = $_REQUEST[aviser_direction_id]";
	//echo '<br>'. $queryDetail .'<br>';
	$resultDetail   = mysqli_query($con,$queryDetail) or die  ('I cannot select items because 6b: ' . mysqli_error($con));
	$DataDetail     = mysqli_fetch_array($resultDetail,MYSQLI_ASSOC);

	$NombreNotificationDirection = $DataDetail[nombre_notification_direction];
	$EmailDirection = "abedard@entrepotdelalunette.com";//TODO CHANGER POUR EMAIL DE KASSANDRA APRES MES TESTS
	//$EmailDirection = "fdjibrilla@entrepotdelalunette.com";
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
		case 'warehousehal':case 'warehousehalsafe':$Succursale = "Halifax"; 		break;
	//	case 'montreal':   	case 'montrealsafe':	$Succursale = "Montreal"; 		break;
		case 'gatineau':   	case 'gatineausafe':	$Succursale = "Gatineau"; 		break;
		case 'stjerome':   	case 'stjeromesafe':	$Succursale = "St-Jérôme"; 		break;
		case 'edmundston':  case 'edmundstonsafe':	$Succursale = "Edmundston"; 	break;
		case 'sorel':  		case 'sorelsafe':		$Succursale = "Sorel"; 			break;
		case 'vaudreuil':  	case 'vaudreuilsafe':   $Succursale = "Vaudreuil"; 		break;
		case 'moncton':  	case 'monctonsafe':		$Succursale = "Moncton"; 		break;
		case 'fredericton': case 'frederictonsafe':	$Succursale = "Fredericton"; 	break;
		case 'stjohn':      case 'stjohnsafe':	    $Succursale = "St John"; 		break;
		case '88666': 								$Succursale = "Griffe Lunetier #88666"; 	break;
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
	<p>Bonjour, <br><br>Bonjour Mr Bédard. Je vous transfert cette commande qui est bloqué depuis déja un bon moment. <br> Il y a un problème avec la facture Optipro <b>#$DataDetail[order_num_optipro]</b> de<b> $Succursale</b>.<br><br>
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
	echo $queryValiderPasser;
	$resultValiderPasser = mysqli_query($con,$queryValiderPasser) or die  ('I cannot select items because 7: ' . mysqli_error($con));
	$CountValiderPasser  = mysqli_num_rows($resultValiderPasser);
	$DataValiderPasser   = mysqli_fetch_array($resultValiderPasser,MYSQLI_ASSOC);
	if ($CountValiderPasser > 0){
		$TransfertReussi = 'oui';
	}else{
		$TransfertReussi = 'non';	
	}
	echo '<br>TransfertReussi:'. $TransfertReussi;
	

	
	
	if ($TransfertReussi == 'non'){//La commande n'as pas encore été transféré dans Ifc/Safe
		if (($response) && ($NombreNotificationDirection==0)){
			//TODO Enregistrer la notification à la direction
			$NouveauNombreNotificationDirection   = $listItem[nombre_notification_direction] + 1;
			//echo '<br>Nouveau nombre de notification a enregistrer'. $NouveauNombreNotification;
			$Datedujour      		 = date("Y-m-d");	
			$queryUpdateNotificationDirection = "UPDATE erreurs_optipro SET nombre_notification_direction = $NouveauNombreNotificationDirection, date_derniere_notification_direction='$Datedujour'  WHERE erreur_id =  $DataDetail[erreur_id] ";
			$resultUpdate            = mysqli_query($con,$queryUpdateNotificationDirection) or die  ('I cannot update items because: ' . mysqli_error($con));
			//echo '<br>query:'. $queryUpdateNotification;
			echo '<br>Courriel envoyé..Redirection en cours';
			header("Location: rapport_erreurs_optipro_edll.php");
			exit();	
			echo '<h3>Envoie à la direction..réussie</h>';
		}else{
			echo '<br>Erreur durant l\'envoie du courriel..';	
		}
	}else{
	header("Location: rapport_erreurs_optipro_edll.php?message=dejatransfere&order_num_optipro=$DataDetail[order_num_optipro]&acct=$DataDetail[user_id]");
	exit();	
	}//End if Transfert n'a pas été réussi
	

}//End IF There is an ID to advise



//Vérification: nombre de fichier en attente d'importation pour EDLL
//1 Connexion sur le FTP EDLL 
$ftp_server_EDLL = constant("FTP_WINDOWS_VM");
$ftp_user_EDLL   = constant("FTP_USER_OPTIPRO_EDLL");

$ftp_pass_EDLL   = constant("FTP_PASSWORD_OPTIPRO_EDLL");
// set up a connection or die
$conn_id_EDLL = ftp_connect($ftp_server_EDLL) or die("Couldn't connect to $ftp_server_EDLL"); 
// try to login
if (@ftp_login($conn_id_EDLL, $ftp_user_EDLL, $ftp_pass_EDLL)) {
}else {
	echo 'Probleme de connexion';
}
ftp_pasv($conn_id_EDLL,true);
ftp_chdir($conn_id_EDLL,"/ftp_root/Fichier recus de POS pour importation/Optipro EDLL");
$directory=ftp_pwd($conn_id_EDLL);
$contents=ftp_nlist($conn_id_EDLL, ".");
$Compteur_EDLL = 0;
foreach ($contents as $value) {//FIND NEWEST FILE
	$Compteur_EDLL += 1;
}
//On soustrait les 2 dossiers pour connaitre le nombre exact  de csv en attente d'importation
$Compteur_EDLL = $Compteur_EDLL -5;
//Fin de la partie EDLL





?>
<html>
<head>
<title>Recherche parmis les erreurs d'importation Optipro</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="charles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.2.js"></script>
<meta http-equiv="refresh" content="295"><!--Refresh every 295 seconds -->
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
<form  method="post" name="rapport_erreurs_optipro_edll" id="rapport_erreurs_optipro_edll" action="rapport_erreurs_optipro_edll.php">
    <div align="center">
     	<p align="center"><h3>Recherche parmis les erreurs d'importation Optipro</h3></p>
    
    
    <?php 
	if ($_REQUEST[message] <> ''){
		
		switch($_REQUEST[acct]){
			case 'granby':      case 'grabysafe':  			$Succ = "Granby"; 		  break;	
			case 'levis':       case 'levissafe':  			$Succ = "Lévis"; 		  break;
			case 'chicoutimi':  case 'chicoutimisafe':  	$Succ = "Chicoutimi"; 	  break;	
			case 'entrepotifc': case 'entrepotsafe':   		$Succ = "Trois-Rivières"; break;	
			case 'entrepotdr':  case 'safedr':  	    	$Succ = "Drummondville";  break;
			case 'laval': 		case 'lavalsafe':       	$Succ = "Laval"; 		  break;
			case 'terrebonne':  case 'terrebonne':      	$Succ = "Terrebonne";     break;
			case 'sherbrooke':  case 'sherbrookesafe':  	$Succ = "Sherbrooke";     break;
			case 'longueuil':   case 'longueuilsafe':   	$Succ = "Longueuil";      break;
			case 'entrepotquebec':    case 'quebecsafe':  	$Succ = "Quebec";    	  break;
			case 'warehousehal':  case 'warehousehalsafe':	$Succ = "Halifax";    	  break;
		//	case 'montreal':  	case 'montrealsafe':		$Succ = "Montréal";       break;
			case 'gatineau':  	case 'gatineausafe':		$Succ = "Gatineau";       break;
			case 'stjerome':  	case 'stjeromesafe':		$Succ = "St-Jérôme";      break;
			case 'edmundston':  case 'edmundstonsafe':		$Succ = "Edmundston";     break;
			case 'moncton':  	case 'monctonsafe':			$Succ = "Moncton";     	  break;
			case 'sorel':  		case 'sorelsafe':			$Succ = "Sorel";     	  break;
			case 'vaudreuil':  	case 'vaudreuilsafe':		$Succ = "Vaudreuil";      break;
			case 'fredericton': case 'frederictonsafe':		$Succ = "Fredericton";    break;
			case 'stjohn':      case 'stjohnsafe':		    $Succ = "St John";        break;
			case '88666': 									$Succ = "Griffe lunetier #88666";    break;
		}
		
		switch($_REQUEST[message]){
		case 'dejatransfere': 
		echo '<div style="width:750px;background-color:#E6F18F;"><font color="#F50004">La commande #'.$_REQUEST[order_num_optipro].' de '. $Succ . ' a déja été transféré avec succès, il est donc inutile d\'aviser la succursale.</font></div>';   break;
		}//End Switch
		
	}//End if On a un message a afficher
	?>
        <input name="submit" type="submit" id="submit" value="Voir les erreurs d'aujourd'hui" class="formField"><input name="rpt_search" type="hidden" id="rpt_search" value="search orders" class="formField">
        <br><br>
		<a href="../scripts/script_effacer_erreurs_optipro_edll_corrigees.php">Effacer TOUTES les erreurs corrigées</a>
    </div>
</form>


<form  method="post" name="rapport_erreurs_optipro_edll_part2" id="rapport_erreurs_optipro_edll_part2" action="rapport_erreurs_optipro_edll.php">
<?php 		
	$rptResult=mysqli_query($con,$rptQuery) or die  ('I cannot select items because 1a: ' . mysqli_error($con));
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
			<th width="6%"  align="center">Date</th>
			<th width="6%"  align="center">Compte</th>
            <th width="5%"  align="center"># Optipro</th>
            <th width="5%"  align="center">Modifier</th>
			<th width="30%" align="center">Erreur</th>
            <th width="4%"  align="center">Effacer</th>
            <th width="10%" align="center">Succursale avisé?</th>
            <th width="8%" align="center">Courriel</th>
			<th width="8%" align="center">Transféré ?</th>
			<th width="14%" align="center">Produit Demandé</th>
			<th width="7%" align="center">Modifier produit (Ifc.ca)</th>
			<th width="7%" align="center">Modifier produit (SAFE)</th>
			<th width="5%" align="center">Aviser la direction</th>
	</tr>
		
		
    </thead>
    
    <tbody>

<?php
	
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
		
		if ($listItem[order_num_optipro] <> ''){
			
			//echo '<br>User id:'. $listItem[user_id];
			switch($listItem[user_id]){
				case 'entrepotifc':     $LesComptes = "('entrepotifc','entrepotsafe') ";  	break;	
				case 'entrepotsafe':    $LesComptes = "('entrepotifc','entrepotsafe') ";  	break;
				
				case 'entrepotdr':      $LesComptes = "('entrepotdr','safedr') ";  			break;	
				case 'safedr':          $LesComptes = "('entrepotdr','safedr') ";  	    	break;	
				
				case 'longueuil':       $LesComptes = "('longueuil','longueuilsafe') ";  	break;	
				case 'longueuilsafe':   $LesComptes = "('longueuil','longueuilsafe') ";  	break;	
				
				case 'laval':           $LesComptes = "('laval','lavalsafe') ";  			break;	
				case 'lavalsafe':       $LesComptes = "('laval','lavalsafe') ";  			break;		
				
				case 'terrebonne':      $LesComptes = "('terrebonne','terrebonnesafe') "; 	break;	
				case 'terrebonnesafe':  $LesComptes = "('terrebonne','terrebonnesafe') "; 	break;
				
				case 'sherbrooke':      $LesComptes = "('sherbrooke','sherbrookesafe') "; 	break;	
				case 'sherbrookesafe':  $LesComptes = "('sherbrooke','sherbrookesafe') "; 	break;	
			
				case 'chicoutimi':      $LesComptes = "('chicoutimi','chicoutimisafe') "; 	break;	
				case 'chicoutimisafe':  $LesComptes = "('chicoutimi','chicoutimisafe') "; 	break;	
				
				case 'levis':           $LesComptes = "('levis','levissafe') "; 			break;	
				case 'levissafe':       $LesComptes = "('levis','levissafe') "; 			break;	
					
				case 'entrepotquebec':  $LesComptes = "('entrepotquebec','quebecsafe') "; 	break;	
				case 'quebecsafe':      $LesComptes = "('entrepotquebec','quebecsafe') "; 	break;
				
				case 'granby':          $LesComptes = "('granby','granbysafe') "; 		 	break;	
				case 'granbysafe':      $LesComptes = "('granby','granbysafe') "; 		 	break;
					
				case 'warehousehal':    $LesComptes = "('warehousehalsafe','warehousehal') "; 	break;
				case 'warehousehalsafe':$LesComptes = "('warehousehalsafe','warehousehal') "; 	break;
					
			//	case 'montreal':   	 	$LesComptes = "('montreal','montrealsafe') "; 	break;
			//	case 'montrealsafe':	$LesComptes = "('montreal','montrealsafe') "; 	break;	
				
				case 'gatineau':   	 	$LesComptes = "('gatineau','gatineausafe') "; 	break;
				case 'gatineausafe':	$LesComptes = "('gatineau','gatineausafe') "; 	break;	
				
				case 'stjerome':   	 	$LesComptes = "('stjerome','stjeromesafe') "; 	break;
				case 'stjeromesafe':	$LesComptes = "('stjerome','stjeromesafe') "; 	break;	
				
				case 'edmundston':   	$LesComptes = "('edmundston','edmundstonsafe') "; 	break;
				case 'edmundstonsafe':	$LesComptes = "('edmundston','edmundstonsafe') "; 	break;	
				
				case 'vaudreuil':   	$LesComptes = "('vaudreuil','vaudreuilsafe') "; 	break;
				case 'vaudreuilsafe':	$LesComptes = "('vaudreuil','vaudreuilsafe') "; 	break;	
				
				case 'sorel':   		$LesComptes = "('sorel','sorelsafe') "; 	break;
				case 'sorelsafe':		$LesComptes = "('sorel','sorelsafe') "; 	break;
				
				case 'moncton':   		$LesComptes = "('moncton','monctonsafe') "; 	break;
				case 'monctonsafe':		$LesComptes = "('moncton','monctonsafe') "; 	break;
				
				case 'fredericton':   	$LesComptes = "('fredericton','frederictonsafe') "; break;
				case 'frederictonsafe':	$LesComptes = "('fredericton','frederictonsafe') "; break;

				case 'stjohn':   	$LesComptes = "('stjohn','stjohnsafe') "; break;
				case 'stjohnsafe':	$LesComptes = "('stjohn','stjohnsafe') "; break;
				
				case '88666':   		$LesComptes = "('88666') "; break;
				
				
				//case 'halifax':    $LesComptes = "('warehousehalsafe','warehousehal') "; 	break;
				//case 'warehousehalsafe':$LesComptes = "('warehousehalsafe','warehousehal') "; 	break;
			}
			

			
			//echo '<br>Les comptes:'. $LesComptes;
			
				$queryValiderPasser  = "SELECT * FROM orders WHERE order_status<>'cancelled' AND user_id IN $LesComptes AND order_num_optipro = $listItem[order_num_optipro]";
				//echo 'LES COMPTES :1'. $LesComptes. '<br>'. $queryValiderPasser. '<br> '  ;
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
			
			$valeurRedirection  = "rapport_erreurs_optipro_edll.php?delete_id=$listItem[erreur_id]";	
			$valeurEnvoyerEmail = "rapport_erreurs_optipro_edll.php?aviser_id=$listItem[erreur_id]"; 
			$valeurEnvoyerEmailDirection = "rapport_erreurs_optipro_edll.php?aviser_direction_id=$listItem[erreur_id]"; 
			$valeurModifier     = "edit_optipro_edll.php?erreur_id=$listItem[erreur_id]"; 
			
			$Notif 			    = $listItem[nombre_notification_succursale] . " fois";
			if ($listItem[nombre_notification_succursale] > 0){
				$Notif  = $Notif  . '  <br>Dernier: <b>'.$listItem[date_derniere_notification].'</b>';
			}
		
			$NotifDirection 			    = $listItem[nombre_notification_direction] . " fois";
			if ($listItem[nombre_notification_direction] > 0){
				$NotifDirection  = 'Date de l\'avis: ' . $listItem[date_derniere_notification_direction].'</b>';
			}
			if ($NotifDirection == '0 fois') 
				$NotifDirection = '';
			//echo 'notif'. $Notif;
			
			
	?>		
    
    <tr <?php if ($CountValiderPasser > 0){ echo ' bgcolor="#6FD474"';} ?>>
			
			<td align="center"><?php  echo $listItem[date]; ?></td>
			<td align="center"><?php  echo $listItem[user_id]; ?></td>
            
             <td align="center"><?php  echo $listItem[order_num_optipro]; ?></td>
            
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
            
		<?php	
			
			if ($listItem[cle_produit] <> ''){
			echo "<td align=\"center\"><a target=\"_blank\" href=\"".constant('DIRECT_LENS_URL')."/admin/update_exclusive_product_ifc.php?pkey=". $listItem[cle_produit]. "\">Voir"."</td>";
			}else {
			echo '<td>&nbsp;</td>';	
			}
			
			if ($listItem[cle_produit] <> ''){
			echo "<td align=\"center\"><a target=\"_blank\" href=\"".constant('DIRECT_LENS_URL')."/admin/update_exclusive_product_safety.php?pkey=". $listItem[cle_produit]. "\">Voir"."</td>";
			}else{
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
<table width="80%" border="1" align="center" cellpadding="3" cellspacing="0" >
	<thead>
	<tr>
			<th align="center">Compte</th>
		
			<th align="center" bgcolor="#ECAAAB">Panier Ifc</th>
            <th align="center" bgcolor="#F4F791">Panier Safe</th>
            <th align="center" bgcolor="#C7FCC4">Optipro validées</th>
		 	<th align="center" bgcolor="#25A0DD">Redos</th>
			
	</tr>
    </thead>

<?php

	
		//Trois-Rivieres
		$CompteIFC              	=  " user_id IN ('entrepotifc')";  
		$CompteSAFE             	=  " user_id IN ('entrepotsafe')";   
		$queryJobPanierIFC 	    	= "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    	= mysqli_query($con,$queryJobPanierIFC) or die  ('I cannot select items because 3: ' . mysqli_error($con)); 
		$DataPanierIFC     			= mysqli_fetch_array($resultPanierIFC,MYSQLI_ASSOC);
		$NbrCommandeIfc_TR      	= $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     	= "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 				= mysqli_query($con,$queryJobPanierSAFE) or die  ('I cannot select items because 20: ' . mysqli_error($con));   
		$DataPanierSAFE     		= mysqli_fetch_array($resultSAFE,MYSQLI_ASSOC);
		$NbrCommandeSAFE_TR     	= $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 21: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_TR   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 22: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_TR_OP= $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 23: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_TR                = $DataValiderAJD[NbrCommandeTransferer] ;
	
	
	
		//Drummondville
		$CompteIFC              =  " user_id IN ('entrepotdr')";  
		$CompteSAFE             =  " user_id IN ('safedr')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysqli_query($con,$queryJobPanierIFC) or die  ('I cannot select items because 24: ' . mysqli_error($con)); 
		$DataPanierIFC     		= mysqli_fetch_array($resultPanierIFC,MYSQLI_ASSOC);
		$NbrCommandeIfc_DR      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysqli_query($con,$queryJobPanierSAFE) or die  ('I cannot select items because 25: ' . mysqli_error($con));   
		$DataPanierSAFE     	= mysqli_fetch_array($resultSAFE,MYSQLI_ASSOC);
		$NbrCommandeSAFE_DR     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_DR   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 26: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_DR_OP   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 27: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_DR                 = $DataValiderAJD[NbrCommandeTransferer] ;
		
		//Granby
		$CompteIFC              =  " user_id IN ('granby')";  
		$CompteSAFE             =  " user_id IN ('granbysafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysqli_query($con,$queryJobPanierIFC) or die  ('I cannot select items because 28: ' . mysqli_error($con)); 
		$DataPanierIFC     		= mysqli_fetch_array($resultPanierIFC,MYSQLI_ASSOC);
		$NbrCommandeIfc_GR      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysqli_query($con,$queryJobPanierSAFE) or die  ('I cannot select items because 29: ' . mysqli_error($con));   
		$DataPanierSAFE     	= mysqli_fetch_array($resultSAFE,MYSQLI_ASSOC);
		$NbrCommandeSAFE_GR     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 30: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_GR   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 31: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_GR_OP = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 32: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_GR                = $DataValiderAJD[NbrCommandeTransferer] ;
	
	
		//Lévis
		$CompteIFC              	=  " user_id IN ('levis')";  
		$CompteSAFE             	=  " user_id IN ('levissafe')";   
		$queryJobPanierIFC 	    	= "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    	= mysqli_query($con,$queryJobPanierIFC) or die  ('I cannot select items because 33: ' . mysqli_error($con)); 
		$DataPanierIFC     			= mysqli_fetch_array($resultPanierIFC,MYSQLI_ASSOC);
		$NbrCommandeIfc_LE      	= $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     	= "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 				= mysqli_query($con,$queryJobPanierSAFE) or die  ('I cannot select items because 34: ' . mysqli_error($con));   
		$DataPanierSAFE     		= mysqli_fetch_array($resultSAFE,MYSQLI_ASSOC);
		$NbrCommandeSAFE_LE     	= $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 35: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_LE   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 36: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_LE_OP = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 37: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_LE                = $DataValiderAJD[NbrCommandeTransferer] ;
	
		
		//Chicoutimi
		$CompteIFC              =  " user_id IN ('chicoutimi')";  
		$CompteSAFE             =  " user_id IN ('chicoutimisafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysqli_query($con,$queryJobPanierIFC) or die  ('I cannot select items because 38: ' . mysqli_error($con)); 
		$DataPanierIFC     		= mysqli_fetch_array($resultPanierIFC,MYSQLI_ASSOC);
		$NbrCommandeIfc_CH      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysqli_query($con,$queryJobPanierSAFE) or die  ('I cannot select items because 39: ' . mysqli_error($con));   
		$DataPanierSAFE     	= mysqli_fetch_array($resultSAFE,MYSQLI_ASSOC);
		$NbrCommandeSAFE_CH     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 40: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_CH   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is null  AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 41: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_CH_OP = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 42: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_CH                = $DataValiderAJD[NbrCommandeTransferer] ;
	
	
		//Laval
		$CompteIFC              =  " user_id IN ('laval')";  
		$CompteSAFE             =  " user_id IN ('lavalsafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysqli_query($con,$queryJobPanierIFC) or die  ('I cannot select items because 43: ' . mysqli_error($con)); 
		$DataPanierIFC     		= mysqli_fetch_array($resultPanierIFC,MYSQLI_ASSOC);
		$NbrCommandeIfc_LV      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysqli_query($con,$queryJobPanierSAFE) or die  ('I cannot select items because 44: ' . mysqli_error($con));   
		$DataPanierSAFE     	= mysqli_fetch_array($resultSAFE,MYSQLI_ASSOC);
		$NbrCommandeSAFE_LV     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 45: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_LV   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 46: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_LV_OP = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 47: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_LV                = $DataValiderAJD[NbrCommandeTransferer] ;
		
		
		//Terrebonne
		$CompteIFC              =  " user_id IN ('terrebonne')";  
		$CompteSAFE             =  " user_id IN ('terrebonnesafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysqli_query($con,$queryJobPanierIFC) or die  ('I cannot select items because 48: ' . mysqli_error($con)); 
		$DataPanierIFC     		= mysqli_fetch_array($resultPanierIFC,MYSQLI_ASSOC);
		$NbrCommandeIfc_TE      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysqli_query($con,$queryJobPanierSAFE) or die  ('I cannot select items because 49: ' . mysqli_error($con));   
		$DataPanierSAFE     	= mysqli_fetch_array($resultSAFE,MYSQLI_ASSOC);
		$NbrCommandeSAFE_TE     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 50: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_TB   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 51: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_TB_OP = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 52: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_TB                = $DataValiderAJD[NbrCommandeTransferer] ;
		
		//Sherbrooke
		$CompteIFC              =  " user_id IN ('sherbrooke')";  
		$CompteSAFE             =  " user_id IN ('sherbrookesafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysqli_query($con,$queryJobPanierIFC) or die  ('I cannot select items because 53: ' . mysqli_error($con)); 
		$DataPanierIFC     		= mysqli_fetch_array($resultPanierIFC,MYSQLI_ASSOC);
		$NbrCommandeIfc_SH      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysqli_query($con,$queryJobPanierSAFE) or die  ('I cannot select items because 54: ' . mysqli_error($con));   
		$DataPanierSAFE     	= mysqli_fetch_array($resultSAFE,MYSQLI_ASSOC);
		$NbrCommandeSAFE_SH     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 55: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_SH   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 56: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_SH_OP = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 57: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_SH                = $DataValiderAJD[NbrCommandeTransferer] ;
	
	
		//Edmundston
		$CompteIFC              =  " user_id IN ('edmundston')";  
		$CompteSAFE             =  " user_id IN ('edmundstonsafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysqli_query($con,$queryJobPanierIFC) or die  ('I cannot select items because 53: ' . mysqli_error($con)); 
		$DataPanierIFC     		= mysqli_fetch_array($resultPanierIFC,MYSQLI_ASSOC);
		$NbrCommandeIfc_EDM     = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysqli_query($con,$queryJobPanierSAFE) or die  ('I cannot select items because 54: ' . mysqli_error($con));   
		$DataPanierSAFE     	= mysqli_fetch_array($resultSAFE,MYSQLI_ASSOC);
		$NbrCommandeSAFE_EDM     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 55: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_EDM   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 56: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_EDM_OP = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 57: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_EDM               = $DataValiderAJD[NbrCommandeTransferer] ;
	
	
		//Moncton
		$CompteIFC              	=  " user_id IN ('moncton')";  
		$CompteSAFE             	=  " user_id IN ('monctonsafe')";   
		$queryJobPanierIFC 	    	= "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    	= mysqli_query($con,$queryJobPanierIFC) or die  ('I cannot select items because 53: ' . mysqli_error($con)); 
		$DataPanierIFC     			= mysqli_fetch_array($resultPanierIFC,MYSQLI_ASSOC);
		$NbrCommandeIfc_MONCTON     = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     	= "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 				= mysqli_query($con,$queryJobPanierSAFE) or die  ('I cannot select items because 54: ' . mysqli_error($con));   
		$DataPanierSAFE     		= mysqli_fetch_array($resultSAFE,MYSQLI_ASSOC);
		$NbrCommandeSAFE_MONCTON     	= $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 55: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_MONCTON  = $DataValiderAJD[NbrCommandeTransferer] ;
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 56: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_MONCTON_OP = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 57: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_MONCTON           = $DataValiderAJD[NbrCommandeTransferer] ;
	
	
	
		//Longueuil
		$CompteIFC              =  " user_id IN ('longueuil')";  
		$CompteSAFE             =  " user_id IN ('longueuilsafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysqli_query($con,$queryJobPanierIFC) or die  ('I cannot select items because 58: ' . mysqli_error($con)); 
		$DataPanierIFC     		= mysqli_fetch_array($resultPanierIFC,MYSQLI_ASSOC);
		$NbrCommandeIfc_LO      = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysqli_query($con,$queryJobPanierSAFE) or die  ('I cannot select items because 59: ' . mysqli_error($con));   
		$DataPanierSAFE     	= mysqli_fetch_array($resultSAFE,MYSQLI_ASSOC);
		$NbrCommandeSAFE_LO     = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 60: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_LO   = $DataValiderAJD[NbrCommandeTransferer] ;
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 61: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_LO_OP = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 62: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_LO                = $DataValiderAJD[NbrCommandeTransferer] ;
		
		//Halifax
		$CompteIFC              	=  " user_id IN ('warehousehal')";  
		$CompteSAFE             	=  " user_id IN ('warehousehalsafe')";   
		$queryJobPanierIFC 	    	= "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    	= mysqli_query($con,$queryJobPanierIFC) or die  ('I cannot select items because 63: ' . mysqli_error($con)); 
		$DataPanierIFC     			= mysqli_fetch_array($resultPanierIFC,MYSQLI_ASSOC);
		$NbrCommandeIfc_HA      	= $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     	= "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 				= mysqli_query($con,$queryJobPanierSAFE) or die  ('I cannot select items because 64: ' . mysqli_error($con));   
		$DataPanierSAFE     		= mysqli_fetch_array($resultSAFE,MYSQLI_ASSOC);
		$NbrCommandeSAFE_HA     	= $DataPanierSAFE[NbrCommandeSAFE] ;	
	    $queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 65: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HA   = $DataValiderAJD[NbrCommandeTransferer] ;

		//Commandes Ifc.ca
		$queryTotalValiderAjdIFC   		= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND redo_order_num is null AND order_date_processed = '$ajd'";
	    // echo $queryTotalValiderAjdIFC;
		$resultValiderAJDIFC   			= mysqli_query($con,$queryTotalValiderAjdIFC) or die  ('I cannot select items because 71: ' . mysqli_error($con)); 
        $DataValiderAJDIFC        		= mysqli_fetch_array($resultValiderAJDIFC,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_HA_OP = $DataValiderAJDIFC[NbrCommandeTransferer] ;
	
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 67: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_HA                = $DataValiderAJD[NbrCommandeTransferer] ;
	
	
		
	
		//Québec
		$CompteIFC              =  " user_id IN ('entrepotquebec')";  
		$CompteSAFE             =  " user_id IN ('quebecsafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysqli_query($con,$queryJobPanierIFC) or die  ('I cannot select items because 73: ' . mysqli_error($con)); 
		$DataPanierIFC     		= mysqli_fetch_array($resultPanierIFC,MYSQLI_ASSOC);
		$NbrCommandeIfc_QC     = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysqli_query($con,$queryJobPanierSAFE) or die  ('I cannot select items because 74: ' . mysqli_error($con));   
		$DataPanierSAFE     	= mysqli_fetch_array($resultSAFE,MYSQLI_ASSOC);
		$NbrCommandeSAFE_QC    = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 75: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_QC   = $DataValiderAJD[NbrCommandeTransferer] ;	
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 76: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_QC_OP = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 77: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_QC                = $DataValiderAJD[NbrCommandeTransferer] ;
		


		//Fredericton
		$CompteIFC              =  " user_id IN ('fredericton')";  
		$CompteSAFE             =  " user_id IN ('frederictonsafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysqli_query($con,$queryJobPanierIFC) or die  ('I cannot select items because 78: ' . mysqli_error($con)); 
		$DataPanierIFC     		= mysqli_fetch_array($resultPanierIFC,MYSQLI_ASSOC);
		$NbrCommandeIfc_FREDERICTON = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysqli_query($con,$queryJobPanierSAFE) or die  ('I cannot select items because 79: ' . mysqli_error($con));   
		$DataPanierSAFE     	= mysqli_fetch_array($resultSAFE,MYSQLI_ASSOC);
		$NbrCommandeSAFE_FREDERICTON    = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 80: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_FREDERICTON   = $DataValiderAJD[NbrCommandeTransferer] ;	
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 81: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_FREDERICTON_OP = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 82: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_FREDERICTON                = $DataValiderAJD[NbrCommandeTransferer] ;



		//St John
		$CompteIFC              =  " user_id IN ('stjohn')";  
		$CompteSAFE             =  " user_id IN ('stjohnsafe')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysqli_query($con,$queryJobPanierIFC) or die  ('I cannot select items because 78: ' . mysqli_error($con)); 
		$DataPanierIFC     		= mysqli_fetch_array($resultPanierIFC,MYSQLI_ASSOC);
		$NbrCommandeIfc_STJOHN = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysqli_query($con,$queryJobPanierSAFE) or die  ('I cannot select items because 79: ' . mysqli_error($con));   
		$DataPanierSAFE     	= mysqli_fetch_array($resultSAFE,MYSQLI_ASSOC);
		$NbrCommandeSAFE_STJOHN   = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 80: ' . mysqli_error($con)); 
		$DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_STJOHN  = $DataValiderAJD[NbrCommandeTransferer] ;	
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 81: ' . mysqli_error($con)); 
		$DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_STJOHN_OP = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 82: ' . mysqli_error($con)); 
		$DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_STJOHN                = $DataValiderAJD[NbrCommandeTransferer] ;

		//Griffé
		$CompteIFC              =  " user_id IN ('88666')";  
		$CompteSAFE             =  " user_id IN ('88666')";   
		$queryJobPanierIFC 	    = "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    = mysqli_query($con,$queryJobPanierIFC) or die  ('I cannot select items because 78: ' . mysqli_error($con)); 
		$DataPanierIFC     		= mysqli_fetch_array($resultPanierIFC,MYSQLI_ASSOC);
		$NbrCommandeIfc_GRIFFE  = $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     = "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 			= mysqli_query($con,$queryJobPanierSAFE) or die  ('I cannot select items because 79: ' . mysqli_error($con));   
		$DataPanierSAFE     	= mysqli_fetch_array($resultSAFE,MYSQLI_ASSOC);
		$NbrCommandeSAFE_GRIFFE = $DataPanierSAFE[NbrCommandeSAFE] ;
		$queryTotalValiderAjd   = "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   	= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 80: ' . mysqli_error($con)); 
		$DataValiderAJD        	= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_GRIFFE   = $DataValiderAJD[NbrCommandeTransferer] ;	
		//Commandes Optipro
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 81: ' . mysqli_error($con)); 
		$DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_GRIFFE_OP = $DataValiderAJD[NbrCommandeTransferer] ;
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 82: ' . mysqli_error($con)); 
		$DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_GRIFFE                = $DataValiderAJD[NbrCommandeTransferer] ;
	
	
	
	
		//Début Gatineau
		$CompteIFC              	=  " user_id IN ('gatineau')";  
		$CompteSAFE             	=  " user_id IN ('gatineausafe')";   
		$queryJobPanierIFC 	    	= "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    	= mysqli_query($con,$queryJobPanierIFC) or die  ('I cannot select items because 63: ' . mysqli_error($con)); 
		$DataPanierIFC     			= mysqli_fetch_array($resultPanierIFC,MYSQLI_ASSOC);
		$NbrCommandeIfc_GAT      	= $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     	= "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 				= mysqli_query($con,$queryJobPanierSAFE) or die  ('I cannot select items because 64: ' . mysqli_error($con));   
		$DataPanierSAFE     		= mysqli_fetch_array($resultSAFE,MYSQLI_ASSOC);
		$NbrCommandeSAFE_GAT    	= $DataPanierSAFE[NbrCommandeSAFE] ;	
	    $queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 65: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_GAT   = $DataValiderAJD[NbrCommandeTransferer] ;

		//Commandes Ifc.ca
		$queryTotalValiderAjdIFC   		= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND redo_order_num is null AND order_date_processed = '$ajd'";
	    // echo $queryTotalValiderAjdIFC;
		$resultValiderAJDIFC   			= mysqli_query($con,$queryTotalValiderAjdIFC) or die  ('I cannot select items because 71: ' . mysqli_error($con)); 
        $DataValiderAJDIFC        		= mysqli_fetch_array($resultValiderAJDIFC,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_GAT_OP   = $DataValiderAJDIFC[NbrCommandeTransferer] ;
	
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 67: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_GAT               = $DataValiderAJD[NbrCommandeTransferer] ;
		//Fin Gatineau
		
		
		
		//Début St-Jérome
		$CompteIFC              	=  " user_id IN ('stjerome')";  
		$CompteSAFE             	=  " user_id IN ('stjeromesafe')";   
		$queryJobPanierIFC 	    	= "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    	= mysqli_query($con,$queryJobPanierIFC) or die  ('I cannot select items because 63: ' . mysqli_error($con)); 
		$DataPanierIFC     			= mysqli_fetch_array($resultPanierIFC,MYSQLI_ASSOC);
		$NbrCommandeIfc_STJ      	= $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     	= "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 				= mysqli_query($con,$queryJobPanierSAFE) or die  ('I cannot select items because 64: ' . mysqli_error($con));   
		$DataPanierSAFE     		= mysqli_fetch_array($resultSAFE,MYSQLI_ASSOC);
		$NbrCommandeSAFE_STJ    	= $DataPanierSAFE[NbrCommandeSAFE] ;	
	    $queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 65: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_STJ  = $DataValiderAJD[NbrCommandeTransferer] ;

		//Commandes Ifc.ca
		$queryTotalValiderAjdIFC   		= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND redo_order_num is null AND order_date_processed = '$ajd'";
	    // echo $queryTotalValiderAjdIFC;
		$resultValiderAJDIFC   			= mysqli_query($con,$queryTotalValiderAjdIFC) or die  ('I cannot select items because 71: ' . mysqli_error($con)); 
        $DataValiderAJDIFC        		= mysqli_fetch_array($resultValiderAJDIFC,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_STJ_OP   = $DataValiderAJDIFC[NbrCommandeTransferer] ;
	
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 67: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_STJ               = $DataValiderAJD[NbrCommandeTransferer] ;
		//Fin St-Jérome
		
		
		
		
		//Début VAUDREUIL
		$CompteIFC              	=  " user_id IN ('vaudreuil')";  
		$CompteSAFE             	=  " user_id IN ('vaudreuilsafe')";   
		$queryJobPanierIFC 	    	= "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    	= mysqli_query($con,$queryJobPanierIFC) or die  ('I cannot select items because 63: ' . mysqli_error($con)); 
		$DataPanierIFC     			= mysqli_fetch_array($resultPanierIFC,MYSQLI_ASSOC);
		$NbrCommandeIfc_VAU      	= $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     	= "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 				= mysqli_query($con,$queryJobPanierSAFE) or die  ('I cannot select items because 64: ' . mysqli_error($con));   
		$DataPanierSAFE     		= mysqli_fetch_array($resultSAFE,MYSQLI_ASSOC);
		$NbrCommandeSAFE_VAU    	= $DataPanierSAFE[NbrCommandeSAFE] ;	
	    $queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 65: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_VAU  = $DataValiderAJD[NbrCommandeTransferer] ;

		//Commandes Ifc.ca
		$queryTotalValiderAjdIFC   		= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND redo_order_num is null AND order_date_processed = '$ajd'";
	    // echo $queryTotalValiderAjdIFC;
		$resultValiderAJDIFC   			= mysqli_query($con,$queryTotalValiderAjdIFC) or die  ('I cannot select items because 71: ' . mysqli_error($con)); 
        $DataValiderAJDIFC        		= mysqli_fetch_array($resultValiderAJDIFC,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_VAU_OP   = $DataValiderAJDIFC[NbrCommandeTransferer] ;
	
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 67: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_VAU               = $DataValiderAJD[NbrCommandeTransferer] ;
		//Fin Vaudreuil
		
		
		
		
		//Début SOREL
		$CompteIFC              	=  " user_id IN ('sorel')";  
		$CompteSAFE             	=  " user_id IN ('sorelsafe')";   
		$queryJobPanierIFC 	    	= "SELECT count(order_num) as NbrCommandeIfc FROM orders WHERE $CompteIFC AND order_num = -1 "; 
		$resultPanierIFC   	    	= mysqli_query($con,$queryJobPanierIFC) or die  ('I cannot select items because 63: ' . mysqli_error($con)); 
		$DataPanierIFC     			= mysqli_fetch_array($resultPanierIFC,MYSQLI_ASSOC);
		$NbrCommandeIfc_SOR      	= $DataPanierIFC[NbrCommandeIfc] ;
		$queryJobPanierSAFE     	= "SELECT count(order_num) as NbrCommandeSAFE FROM orders WHERE $CompteSAFE AND order_num = -1 "; 
		$resultSAFE 				= mysqli_query($con,$queryJobPanierSAFE) or die  ('I cannot select items because 64: ' . mysqli_error($con));   
		$DataPanierSAFE     		= mysqli_fetch_array($resultSAFE,MYSQLI_ASSOC);
		$NbrCommandeSAFE_SOR    	= $DataPanierSAFE[NbrCommandeSAFE] ;	
	    $queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE)  AND redo_order_num is null  AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 65: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_SOR  = $DataValiderAJD[NbrCommandeTransferer] ;

		//Commandes Ifc.ca
		$queryTotalValiderAjdIFC   		= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND order_num_optipro<>'' AND redo_order_num is null AND order_date_processed = '$ajd'";
	    // echo $queryTotalValiderAjdIFC;
		$resultValiderAJDIFC   			= mysqli_query($con,$queryTotalValiderAjdIFC) or die  ('I cannot select items because 71: ' . mysqli_error($con)); 
        $DataValiderAJDIFC        		= mysqli_fetch_array($resultValiderAJDIFC,MYSQLI_ASSOC);
		$NbrCommandeValiderAJD_SOR_OP   = $DataValiderAJDIFC[NbrCommandeTransferer] ;
	
		//Redos
		$queryTotalValiderAjd   	= "SELECT count(order_num) as NbrCommandeTransferer FROM orders WHERE ($CompteIFC OR $CompteSAFE) AND redo_order_num is not null AND order_date_processed = '$ajd'";
		$resultValiderAJD   		= mysqli_query($con,$queryTotalValiderAjd) or die  ('I cannot select items because 67: ' . mysqli_error($con)); 
        $DataValiderAJD        		= mysqli_fetch_array($resultValiderAJD,MYSQLI_ASSOC);
		$NbrRedos_SOR               = $DataValiderAJD[NbrCommandeTransferer] ;
		//Fin SOREL
	
		
		//TOTAUX
		$TotalOptipro = $NbrCommandeValiderAJD_TR_OP + $NbrCommandeValiderAJD_DR_OP  + $NbrCommandeValiderAJD_GR_OP + $NbrCommandeValiderAJD_LE_OP + $NbrCommandeValiderAJD_CH_OP + $NbrCommandeValiderAJD_LV_OP + $NbrCommandeValiderAJD_TB_OP + $NbrCommandeValiderAJD_SH_OP + $NbrCommandeValiderAJD_LO_OP  + $NbrCommandeValiderAJD_QC_OP + $NbrCommandeValiderAJD_MTL_OP +$NbrCommandeValiderAJD_HA_OP + $NbrCommandeValiderAJD_GAT_OP + $NbrCommandeValiderAJD_STJ_OP + $NbrCommandeValiderAJD_EDM_OP + $NbrCommandeValiderAJD_VAU_OP + $NbrCommandeValiderAJD_SOR_OP + $NbrCommandeValiderAJD_MONCTON_OP + $NbrCommandeValiderAJD_FREDERICTON_OP + $NbrCommandeValiderAJD_GRIFFE_OP + $NbrCommandeValiderAJD_STJOHN_OP ;
		
		$TotalRedos = $NbrRedos_TR + $NbrRedos_DR + $NbrRedos_GR + $NbrRedos_LE+ $NbrRedos_CH + $NbrRedos_LV+ $NbrRedos_TB + $NbrRedos_SH+ $NbrRedos_LO +  $NbrRedos_QC + $NbrRedos_HA +$NbrRedos_MTL + $NbrRedos_GAT + $NbrRedos_STJ + $NbrRedos_EDM + $NbrRedos_VAU+ $NbrRedos_SOR + $NbrRedos_MONCTON + $NbrRedos_FREDERICTON + $NbrRedos_GRIFFE + $NbrRedos_STJOHN ;
		
		$totalPanierIFC = $NbrCommandeIfc_TR +$NbrCommandeIfc_DR+$NbrCommandeIfc_GR+$NbrCommandeIfc_LE+$NbrCommandeIfc_CH+$NbrCommandeIfc_LV+$NbrCommandeIfc_TE+$NbrCommandeIfc_SH+$NbrCommandeIfc_LO+$NbrCommandeIfc_HA+$NbrCommandeIfc_QC+$NbrCommandeIfc_MTL + $NbrCommandeIfc_GAT + $NbrCommandeIfc_STJ +$NbrCommandeIfc_EDM  + $NbrCommandeIfc_VAU + $NbrCommandeIfc_SOR + $NbrCommandeIfc_MONCTON + $NbrCommandeIfc_FREDERICTON + $NbrCommandeIfc_GRIFFE + $NbrCommandeIfc_STJOHN;
		
		$totalPanierSAFE = $NbrCommandeSAFE_TR +$NbrCommandeSAFE_DR+$NbrCommandeSAFE_GR+$NbrCommandeSAFE_LE+$NbrCommandeSAFE_CH+$NbrCommandeSAFE_LV+$NbrCommandeSAFE_TE+$NbrCommandeSAFE_SH+$NbrCommandeSAFE_LO+$NbrCommandeSAFE_HA+$NbrCommandeSAFE_QC + $NbrCommandeSAFE_MTL + $NbrCommandeSAFE_GAT + $NbrCommandeSAFE_STJ + $NbrCommandeSAFE_EDM  + $NbrCommandeSAFE_VAU +  $NbrCommandeSAFE_SOR + $NbrCommandeSAFE_MONCTON + $NbrCommandeSAFE_FREDERICTON + $NbrCommandeSAFE_GRIFFE + $NbrCommandeSAFE_STJOHN;
		
		$totalValidees = $NbrCommandeValiderAJD_TR+$NbrCommandeValiderAJD_DR+$NbrCommandeValiderAJD_GR+$NbrCommandeValiderAJD_LE+$NbrCommandeValiderAJD_CH+$NbrCommandeValiderAJD_LV +$NbrCommandeValiderAJD_TB+$NbrCommandeValiderAJD_SH+$NbrCommandeValiderAJD_LO+$NbrCommandeValiderAJD_HA+$NbrCommandeValiderAJD_QC +$NbrCommandeValiderAJD_MTL + $NbrCommandeValiderAJD_GAT + $NbrCommandeValiderAJD_STJ + $NbrCommandeValiderAJD_EDM + $NbrCommandeValiderAJD_VAU +  $NbrCommandeValiderAJD_SOR + $NbrCommandeValiderAJD_MONCTON + $NbrCommandeValiderAJD_FREDERICTON + $NbrCommandeValiderAJD_GRIFFE + $NbrCommandeValiderAJD_STJOHN ;

?>	

	  <tr>
			<th align="center">Chicoutimi</th>
			<th align="center" bgcolor="#ECAAAB">
			<?php if ($NbrCommandeIfc_CH == 15){ 
					echo '<h2>'.$NbrCommandeIfc_CH.'</h2>';
				}else {
					echo $NbrCommandeIfc_CH; 
				} ?>
			</th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_CH; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_CH_OP; ?></th>
		 	<th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_CH; ?></th
	
	</tr>
		
								
    <tr>
			<th align="center">Drummondville</th>
			<th align="center" bgcolor="#ECAAAB">
			<?php if ($NbrCommandeIfc_DR == 15){ 
					echo '<h2>'.$NbrCommandeIfc_DR.'</h2>';
				}else {
					echo $NbrCommandeIfc_DR; 
				} ?>
			</th>
            
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_DR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_DR_OP; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_DR; ?></th>
	</tr>
	
		
		
	
	 <tr>
			<th align="center">Edmundston</th>
            <th align="center" bgcolor="#ECAAAB">
			<?php if ($NbrCommandeIfc_EDM == 15){ 
					echo '<h2>'.$NbrCommandeIfc_EDM.'</h2>';
				}else {
					echo $NbrCommandeIfc_EDM; 
				} ?>
			</th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_EDM; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_EDM_OP;?></th>
		 	<th align="center" bgcolor="#25A0DD"><?php echo $NbrRedos_EDM; ?></th>
	</tr>
	
	<tr>
			<th align="center">Gatineau</th>
			<th align="center" bgcolor="#ECAAAB">
			<?php if ($NbrCommandeIfc_GAT == 15){ 
					echo '<h2>'.$NbrCommandeIfc_GAT.'</h2>';
				}else {
					echo $NbrCommandeIfc_GAT; 
				} ?>
			</th>
            
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_GAT; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_GAT_OP; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_GAT; ?></th>
	</tr>
	
	
	
	
   
    <tr>
			<th align="center">Granby</th>
			<th align="center" bgcolor="#ECAAAB">
			<?php if ($NbrCommandeIfc_GR == 15){ 
					echo '<h2>'.$NbrCommandeIfc_GR.'</h2>';
				}else {
					echo $NbrCommandeIfc_GR; 
				} ?>
			</th>
             <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_GR; ?></th>
             <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_GR_OP; ?></th>
			 <th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_GR; ?></th>
	</tr>
	
	
	
	
	 <tr>
			<th align="center">Halifax</th>
            <th align="center" bgcolor="#ECAAAB">
			<?php if ($NbrCommandeIfc_HA == 15){ 
					echo '<h2>'.$NbrCommandeIfc_HA.'</h2>';
				}else {
					echo $NbrCommandeIfc_HA; 
				} ?>
			</th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_HA; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo $NbrCommandeValiderAJD_HA_OP;?></th>
		 	<th align="center" bgcolor="#25A0DD"><?php echo $NbrRedos_HA; ?></th>
	</tr>
	
			
	<tr>
			<th align="center">Laval</th>
			<th align="center" bgcolor="#ECAAAB">
			<?php if ($NbrCommandeIfc_LV == 15){ 
					echo '<h2>'.$NbrCommandeIfc_LV.'</h2>';
				}else {
					echo $NbrCommandeIfc_LV; 
				} ?>                                           
			</th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_LV; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_LV_OP; ?></th>
		 	<th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_LV; ?></th>
	
	</tr>
	
	
	
				
	<tr>
			<th align="center">Lévis</th>
            <th align="center" bgcolor="#ECAAAB">
			<?php if ($NbrCommandeIfc_LE == 15){ 
					echo '<h2>'.$NbrCommandeIfc_LE.'</h2>';
				}else {
					echo $NbrCommandeIfc_LE; 
				} ?>
			</th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_LE; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_LE_OP; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo $NbrRedos_LE; ?></th>	
	</tr>
		
		
		
     <tr>
			<th align="center">Longueuil</th>
             <th align="center" bgcolor="#ECAAAB">
			<?php if ($NbrCommandeIfc_LO == 15){ 
					echo '<h2>'.$NbrCommandeIfc_LO.'</h2>';
				}else {
					echo $NbrCommandeIfc_LO; 
				} ?>
			</th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_LO; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_LO_OP; ?></th>
		  	<th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_LO; ?></th>
	</tr>	
	
	
		<tr>
			<th align="center">Moncton</th>
            <th align="center" bgcolor="#ECAAAB">
			<?php if ($NbrCommandeIfc_MONCTON == 15){ 
					echo '<h2>'.$NbrCommandeIfc_MONCTON.'</h2>';
				}else {
					echo $NbrCommandeIfc_MONCTON; 
				} ?>
			</th>
            <th align="center" bgcolor="#F4F791"><?php echo  $NbrCommandeSAFE_MONCTON; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_MONCTON_OP; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_MONCTON; ?></th>
           
	</tr>
		
		
	<tr>
			<th align="center">Québec</th>
			<th align="center" bgcolor="#ECAAAB"><?php if ($NbrCommandeIfc_QC == 15){ 
												 			echo '<h2>'.$NbrCommandeIfc_QC.'</h2>';
												  	   }else {
												 			echo $NbrCommandeIfc_QC; } ?>
			
			
			</th>
             <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_QC; ?></th>
             <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_QC_OP; ?></th>
		  	 <th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_QC; ?></th>
		  
	</tr>	
	
	<tr>
		<th align="center">Fredericton</th>
		<th align="center" bgcolor="#ECAAAB"><?php if ($NbrCommandeIfc_FREDERICTON == 15){ 
														echo '<h2>'.$NbrCommandeIfc_FREDERICTON.'</h2>';
													}else {
														echo $NbrCommandeIfc_FREDERICTON; } ?>
		
		</th>
			<th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_FREDERICTON; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_FREDERICTON_OP; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_FREDERICTON; ?></th>
	</tr>	





	<tr>
		<th align="center">GRIFFE</th>
		<th align="center" bgcolor="#ECAAAB"><?php if ($NbrCommandeIfc_GRIFFE == 15){ 
														echo '<h2>'.$NbrCommandeIfc_GRIFFE.'</h2>';
													}else {
														echo $NbrCommandeIfc_GRIFFE; } ?>
		
		</th>
			<th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_GRIFFE; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_GRIFFE_OP; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_GRIFFE; ?></th>
	</tr>	
				
				
	
		
      <tr>
			<th align="center">Saint-Jérome</th>
			<th align="center" bgcolor="#ECAAAB"><?php if ($NbrCommandeIfc_STJ == 15){ 
												 			echo '<h2>'.$NbrCommandeIfc_STJ.'</h2>';
												  	   }else {
												 			echo $NbrCommandeIfc_STJ; } ?>
			
			
			</th>
             <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_STJ; ?></th>
             <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_STJ_OP; ?></th>
		  	 <th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_STJ; ?></th>
		  
	</tr>
	 
    		
	<tr>
		<th align="center">Saint-John</th>
		<th align="center" bgcolor="#ECAAAB"><?php if ($NbrCommandeIfc_STJOHN == 15){ 
														echo '<h2>'.$NbrCommandeIfc_STJOHN.'</h2>';
													}else {
														echo $NbrCommandeIfc_STJOHN; } ?>
		
		</th>
			<th align="center" bgcolor="#F4F791"><?php echo  $NbrCommandeSAFE_STJOHN; ?></th>
			<th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_STJOHN_OP; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_STJOHN ; ?></th>
	</tr>		
	


    
        <tr>
			<th align="center">Sherbrooke</th>
			<th align="center" bgcolor="#ECAAAB"><?php if ($NbrCommandeIfc_SH == 15){ 
												 			echo '<h2>'.$NbrCommandeIfc_SH.'</h2>';
												  	   }else {
												 			echo $NbrCommandeIfc_SH; } ?>
            </th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_SH; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_SH_OP; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_SH; ?></th>
	</tr>
	
	
	
    	
	<tr>
			<th align="center">Sorel</th>
            <th align="center" bgcolor="#ECAAAB">
			<?php if ($NbrCommandeIfc_SOR == 15){ 
					echo '<h2>'.$NbrCommandeIfc_SOR.'</h2>';
				}else {
					echo $NbrCommandeIfc_SOR; 
				} ?>
			</th>
            <th align="center" bgcolor="#F4F791"><?php echo  $NbrCommandeSAFE_SOR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_SOR_OP; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_SOR; ?></th>
           
	</tr>
	
	
		
	
	  
     <tr>
			<th align="center">Terrebonne</th>
			<th align="center" bgcolor="#ECAAAB"><?php if ($NbrCommandeIfc_TE == 15){ 
												 			echo '<h2>'.$NbrCommandeIfc_TE.'</h2>';
												  	   }else {
												 			echo $NbrCommandeIfc_TE; } ?>
            </th>
            <th align="center" bgcolor="#F4F791"><?php echo $NbrCommandeSAFE_TE; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_TB_OP; ?></th>
		 	 <th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_TB; ?></th>
		 
	</tr>
				

				
				
				
	<tr>
			<th align="center">Trois-Rivières</th>
            <th align="center" bgcolor="#ECAAAB">
			<?php if ($NbrCommandeIfc_TR == 15){ 
					echo '<h2>'.$NbrCommandeIfc_TR.'</h2>';
				}else {
					echo $NbrCommandeIfc_TR; 
				} ?>
			</th>
            <th align="center" bgcolor="#F4F791"><?php echo  $NbrCommandeSAFE_TR; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_TR_OP; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_TR; ?></th>
           
	</tr>
				
		
    	
	<tr>
			<th align="center">Vaudreuil</th>
            <th align="center" bgcolor="#ECAAAB">
			<?php if ($NbrCommandeIfc_VAU == 15){ 
					echo '<h2>'.$NbrCommandeIfc_VAU.'</h2>';
				}else {
					echo $NbrCommandeIfc_VAU; 
				} ?>
			</th>
            <th align="center" bgcolor="#F4F791"><?php echo  $NbrCommandeSAFE_VAU; ?></th>
            <th align="center" bgcolor="#C7FCC4"><?php echo  $NbrCommandeValiderAJD_VAU_OP; ?></th>
			<th align="center" bgcolor="#25A0DD"><?php echo  $NbrRedos_VAU; ?></th>
           
	</tr>
	
	
	
	

	
	 	
			
	<tr><td colspan="6">&nbsp;</td></tr>
    
    
				
		
	
	
	
  	
				
				
	
    
		
	
    	
	
	
	
	
	
	
	
	

	
    <tr>
			<th align="center">Total</th>
			<th align="center" bgcolor="#ECAAAB"><?php echo $totalPanierIFC;?></th>
            <th align="center" bgcolor="#F4F791"><?php echo $totalPanierSAFE?>&nbsp;</th>
            <th align="center" bgcolor="#C7FCC4" >Total Optipro:<?php echo $TotalOptipro?></th>
			<th align="center" bgcolor="#25A0DD">Total Redos:<?php echo $TotalRedos; ?></th>
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
        	<td colspan="2" width="20%" align="center"><h4 align="center">Importation</h4></td></td>
        </tr>
	
		
	 	<tr  align="center">
        	<td width="20%"><h3>Nombre de fichier en attente d'importation:</h3></td>
            <td width="20%"><h3><?php echo $Compteur_EDLL; ?></h3></td>
        </tr>
	
        
        <tr  align="center">
        	<td width="20%"><h5>Dernière commande importée avec succès:</h5></td>
            <td width="20%"><h5><?php echo $DataBasket[max_last_basket]; ?>   Commande #<?php echo $DataDetailBasket[order_num_optipro];?>--> <?php echo $DataDetailBasket[user_id]; ?></h5></td>
        </tr>

        <tr  align="center">
        	<td width="20%"><h5>Nombre d'importation avec succès aujourd'hui/hier </h5></td>
            <td width="20%"><h5><?php echo $DataCountBasket[NbrImportation];?> / <?php echo $DataCountBasketHier[NbrImportation];?></h5></td>

    </table>



        
    
 <?php
//PARTIE Commandes avec Shapes
$today      = date("Y-m-d");
$rptQuery   = "SELECT * FROM orders
WHERE prescript_lab IN (10,25,69,73)
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
<tr><th colspan=\"9\">Ce rapport inclus toutes les commandes avec une forme envoyées vers Swiss, HKO, KNR et GKB</th></tr>";
$message.="<tr bgcolor=\"CCCCCC\">
	<th align=\"center\" width=\"150\">Date confirmation</th>
	<th align=\"center\">EDLL Order #</th>
	<th align=\"center\">Redo #</th>
	<th align=\"center\">Prescript Lab</th>
	<th align=\"center\">Fichier de trace</th>
	<th align=\"center\">Myupload</th>
	<th align=\"center\">Trace envoyé à qui</th>
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
		$message.="
		<td align=\"center\">$listItem[myupload]</td>
		<td bgcolor=\"#D8B5B5\" align=\"center\">$listItem[shape_sent_to_who]</td>
		<td bgcolor=\"#D8B5B5\" align=\"center\">$listItem[result_copy_ftp]</td>
		<td bgcolor=\"#D8B5B5\" align=\"center\">$listItem[shape_copied_ftp]</td>
		<td bgcolor=\"#D8B5B5\" align=\"center\">$listItem[order_status]</td>
	</tr>";

		
}//END WHILE  

echo $message; ?>



<?php
//PARTIE Commandes sans Shapes
$today      = date("Y-m-d");
$rptQuerySansShape   = "SELECT * FROM orders
WHERE prescript_lab IN (10,25,69,73) AND lab  IN (66,67)
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
<tr><th colspan=\"8\">Ce rapport inclus toutes les commandes Swiss, GKB, HKO, KNR  <u>SANS</u> forme</th></tr>";
$message2.="<tr bgcolor=\"CCCCCC\">
	<th align=\"center\" width=\"150\">Date confirmation</th>
	<th align=\"center\">Compte</th>
	<th align=\"center\">Order #</th>
	<th align=\"center\">Redo #</th>
	<th align=\"center\">Prescript Lab</th>
	<th align=\"center\">Shape Name</th>
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

	// Créez un nom de fichier unique avec un horodatage
	$date = new DateTime();
	$timestamp = $date->format('Y-m-d_H-i-s');
	$nomFichier = 'r_erreur_optipro_edll_'. $Succursale.$timestamp;

	// Enregistrez le contenu HTML dans un fichier
	$cheminFichierHtml = 'C:/All_Rapports_EDLL/Others/Erreur_Optipro/' . $nomFichier . '.html';
	file_put_contents($cheminFichierHtml, $message);

	echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';

echo $message2; ?>


  <p>&nbsp;</p>
<script src="js/ajax.js"></script>
</body>
</html>