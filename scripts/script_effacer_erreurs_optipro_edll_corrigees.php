<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
session_start();
require_once(__DIR__.'/../constants/url.constant.php');
require_once('../sec_connectEDLL.inc.php'); 
require_once ('../phpmailer_email_functions.inc.php'); 

$tomorrow  = mktime(0,0,0,date("m"),date("d"),date("Y"));
$ajd 	   = date("Y-m-d", $tomorrow);

//Search errors of the day   
$rptErreur="SELECT * FROM erreurs_optipro 
			WHERE  detail NOT LIKE '%a deja ete importee pour ce client%'
			AND user_id NOT IN ('test','','griffe')
			AND order_num_optipro <> 0
			AND active = 1
			ORDER BY  order_num_optipro, nombre_notification_succursale desc"; 
			
			echo '<br><br>'.$rptErreur.'<br><br>';
		   
$resultErreur = mysqli_query($con,$rptErreur) or die  ('I cannot delete  items because 5: ' . mysqli_error($con));		  

while ($DataErreurs=mysqli_fetch_array($resultErreur,MYSQLI_ASSOC)){
	
	switch($DataErreurs[user_id]){
		case 'granby':      	case 'granbysafe':      		$User_ID_IN = " user_id IN ('granby','granbysafe') ";	       		break;
		case 'levis': 	    	case 'levissafe':      	 		$User_ID_IN = " user_id IN ('levis','levissafe') ";	       			break;
		case 'chicoutimi':  	case 'chicoutimisafe': 	 		$User_ID_IN = " user_id IN ('chicoutimi','chicoutimisafe') "; 		break;
		case 'entrepotifc': 	case 'entrepotsafe':   	 		$User_ID_IN = " user_id IN ('entrepotifc','entrepotsafe') ";  		break;
		case 'entrepotdr':  	case 'safedr':         	 		$User_ID_IN = " user_id IN ('entrepotdr','safedr') ";         		break;
		case 'laval': 			case 'lavalsafe':      	 		$User_ID_IN = " user_id IN ('laval','lavalsafe') ";           		break;
		case 'terrebonne':  	case 'terrebonnesafe': 	 		$User_ID_IN = " user_id IN ('terrebonne','terrebonnesafe') "; 		break;
		case 'sherbrooke':  	case 'sherbrookesafe': 	 		$User_ID_IN = " user_id IN ('sherbrooke','sherbrookesafe') ";		break;
		case 'longueuil':   	case 'longueuilsafe':  	 		$User_ID_IN = " user_id IN ('longueuil','longueuilsafe') ";   		break;
		case 'entrepotquebec': 	case 'quebecsafe': 				$User_ID_IN = " user_id IN ('entrepotquebec','quebecsafe') "; 		break;	
		case 'warehousehal':  	case 'warehousehalsafe':		$User_ID_IN = " user_id IN ('warehousehal','warehousehalsafe') ";	break;	
		//case 'montreal':  		case 'montrealsafe':			$User_ID_IN = " user_id IN ('montreal','montrealsafe') ";			break;
		case 'gatineau':  		case 'gatineausafe':			$User_ID_IN = " user_id IN ('gatineau','gatineausafe') ";			break;	
		case 'stjerome':  		case 'stjeromesafe':			$User_ID_IN = " user_id IN ('stjerome','stjeromesafe') ";			break;
		case 'edmundston':  	case 'edmundstonsafe':			$User_ID_IN = " user_id IN ('edmundston','edmundstonsafe') ";		break;
		case 'vaudreuil':  		case 'vaudreuilsafe':			$User_ID_IN = " user_id IN ('vaudreuil','vaudreuilsafe') ";			break;
		case 'sorel':  			case 'sorelsafe':				$User_ID_IN = " user_id IN ('sorel','sorelsafe') ";					break;
		case 'moncton':  		case 'monctonsafe':				$User_ID_IN = " user_id IN ('moncton','monctonsafe') ";				break;
		case 'fredericton':  	case 'frederictonsafe':			$User_ID_IN = " user_id IN ('fredericton','frederictonsafe') ";		break;
	}	
	
	switch($DataErreurs[user_id]){
		case 'granby':      case 'granbysafe':      $Succursale = "Granby";         break;    
		case 'levis': 	    case 'levissafe':       $Succursale = "Lévis";          break;        
		case 'chicoutimi':  case 'chicoutimisafe':  $Succursale = "Chicoutimi";     break;   
		case 'entrepotquebec':  case 'quebecsafe':  $Succursale = "Québec";         break;
		case 'entrepotifc': case 'entrepotsafe':    $Succursale = "Trois-Rivières"; break;
		case 'entrepotdr':  case 'safedr':          $Succursale = "Drummondville";  break;
		case 'laval': 		case 'lavalsafe':       $Succursale = "Laval"; 			break;
		case 'terrebonne':  case 'terrebonnesafe':  $Succursale = "Terrebonne"; 	break;
		case 'sherbrooke':  case 'sherbrookesafe':  $Succursale = "Sherbrooke"; 	break;
		case 'longueuil':   case 'longueuilsafe':   $Succursale = "Longueuil"; 		break;
		case 'warehousehal':case 'warehousehalsafe':$Succursale = "Halifax"; 		break;
		//case 'montreal':   	case 'montrealsafe':	$Succursale = "Montreal"; 		break;
		case 'gatineau':   	case 'gatineausafe':	$Succursale = "Gatineau"; 		break;
		case 'stjerome':   	case 'stjeromesafe':	$Succursale = "St-Jérôme"; 		break;
		case 'edmundston':  case 'edmundstonsafe':	$Succursale = "Edmundston"; 	break;
		case 'vaudreuil':   case 'vaudreuilsafe':	$Succursale = "Vaudreuil"; 		break;
		case 'sorel':  		case 'sorelsafe':		$Succursale = "Sorel"; 			break;
		case 'moncton':  	case 'monctonsafe':		$Succursale = "Moncton"; 		break;
		case 'fredericton': case 'frederictonsafe':	$Succursale = "Fredericton"; 	break;
		
	}
	
	//$OrderNum
	//1-Vérifier si la commande a été 'transféré avec succès'
	$queryValiderPasser  = "SELECT count(order_num) as NbMatch FROM orders WHERE order_status <> 'cancelled' AND $User_ID_IN AND order_num_optipro = $DataErreurs[order_num_optipro]";
	echo '<br>-------------------------<br>'. $queryValiderPasser;
	$resultValiderPasser = mysqli_query($con, $queryValiderPasser) or die  ('I cannot select items because 2222: ' . mysqli_error($con));
	//$CountValiderPasser  = mysql_num_rows($resultValiderPasser);
	$DataValiderPasser   = mysqli_fetch_array($resultValiderPasser,MYSQLI_ASSOC);
	$NbrMatch = $DataValiderPasser[NbMatch];
	echo '<br>NB MATCH:'. $NbrMatch;
	if ($NbrMatch == 1){
		//Signifie que la commande a été correctement transmise, 
		//ON DOIT EFFACER TOUTES LES ERREURS ID LIÉ A CETTE COMMANDE	
		$queryErreurIDs = "SELECT erreur_id FROM erreurs_optipro WHERE active = 1 AND order_num_optipro = $DataErreurs[order_num_optipro]  AND $User_ID_IN ";
		echo '<br>'. $queryErreurIDs;
		$resultErreurIDs = mysqli_query($con,$queryErreurIDs) or die  ('I cannot select items because 33: ' . mysqli_error($con));
		while ($DataErreurIDs=mysqli_fetch_array($resultErreurIDs,MYSQLI_ASSOC)){
			echo '<br><br>Autre ID a effacer:'.	$DataErreurIDs[erreur_id];
			$queryDelete  = "UPDATE erreurs_optipro SET active=0 WHERE erreur_id = $DataErreurIDs[erreur_id]";
			echo '<br>'. $queryDelete;
			$resultDelete = mysqli_query($con,$queryDelete) or die  ('I cannot delete  items because 5: ' . mysqli_error($con));
		}//End While
		echo '<br><br>';
	}else{
		echo '<br><br>';
	//echo '<br>Aucun match, la commande n\'a pas été transféré. Donc, aucun autre ID a effacer. ';	
	}//End IF
				
			
			

}//End While


header("Location: ".constant('DIRECT_LENS_URL')."/rapports/rapport_erreurs_optipro_edll.php");
exit();
?>
