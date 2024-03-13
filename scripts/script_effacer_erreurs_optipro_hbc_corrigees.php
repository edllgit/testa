<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
session_start();
require_once(__DIR__.'/../constants/url.constant.php');
include("../connexion_hbc.inc.php");
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
		case '88403':      	    $User_ID_IN = " user_id IN ('88403') ";	 break; 	
		case '88408':      	    $User_ID_IN = " user_id IN ('88408') ";	 break;    
		case '88409':      	    $User_ID_IN = " user_id IN ('88409') ";	 break;       	
		case '88411':      	    $User_ID_IN = " user_id IN ('88411') ";	 break;   
		case '88414':      	    $User_ID_IN = " user_id IN ('88414') ";	 break;       	
		case '88416':      	    $User_ID_IN = " user_id IN ('88416') ";	 break;    
		case '88431':      	    $User_ID_IN = " user_id IN ('88431') ";	 break;       	
		case '88433':      	    $User_ID_IN = " user_id IN ('88433') ";	 break;       	
		case '88434':      	    $User_ID_IN = " user_id IN ('88434') ";	 break;      
		case '88435':      	    $User_ID_IN = " user_id IN ('88435') ";	 break;       	
		case '88438':      	    $User_ID_IN = " user_id IN ('88438') ";	 break;   
		case '88439':      	    $User_ID_IN = " user_id IN ('88439') ";	 break;   
		case '88440':      	    $User_ID_IN = " user_id IN ('88440') ";	 break;   
		case '88444':      	    $User_ID_IN = " user_id IN ('88444') ";	 break;       	
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
			//echo '<br>'. $queryDelete;
			$resultDelete = mysqli_query($con,$queryDelete) or die  ('I cannot delete  items because 5: ' . mysqli_error($con));
		}//End While
		echo '<br><br>';
	}else{
		echo '<br><br>';
	//echo '<br>Aucun match, la commande n\'a pas été transféré. Donc, aucun autre ID a effacer. ';	
	}//End IF
				
			
			

}//End While



header("Location: ".constant('DIRECT_LENS_URL')."/rapports/rapport_erreurs_optipro_hbc.php");
exit();
?>
