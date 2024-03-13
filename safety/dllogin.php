<?php
//AFFICHER LES ERREURS

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//error_reporting(E_ALL & ~E_NOTICE);
error_reporting(E_ALL);

//Démarrer la session
session_start();
//Inclusions
include "../sec_connectEDLL.inc.php";
include "config.inc.php";
include "../includes/getlang.php";
include("includes/pw_functions.inc.php");

/*$login    = anti_injection($_POST[user_id],$_POST[password]); // on lance la fonction anti injection
$password = $login['pass']; // on recupère le pass
$pseudo   = $login['user']; // on recupère le pseudo
*/

$_SESSION["sessionUserData"]=login_to_dl($_POST[user_id], $_POST[password]);
$_SESSION["sessionUserData"]["id"]=stripslashes($_SESSION["sessionUserData"]["primary_key"]);
$_SESSION["sessionUserData"]["company"]=stripslashes($_SESSION["sessionUserData"]["company"]);
$_SESSION["sessionUserData"]["first_name"]=stripslashes($_SESSION["sessionUserData"]["first_name"]);
$_SESSION["sessionUserData"]["last_name"]=stripslashes($_SESSION["sessionUserData"]["last_name"]);
$_SESSION["sessionUserData"]["address1"]=stripslashes($_SESSION["sessionUserData"]["address1"]);
$_SESSION["sessionUserData"]["address2"]=stripslashes($_SESSION["sessionUserData"]["address2"]);
$_SESSION["sessionUserData"]["city"]=stripslashes($_SESSION["sessionUserData"]["city"]);
$_SESSION["sessionUserData"]["zip"]=stripslashes($_SESSION["sessionUserData"]["zip"]);
$_SESSION["sessionUserData"]["phone"]=stripslashes($_SESSION["sessionUserData"]["phone"]);
$_SESSION["sessionUserData"]["fax"]=stripslashes($_SESSION["sessionUserData"]["fax"]);
$_SESSION["sessionUserData"]["email"]=stripslashes($_SESSION["sessionUserData"]["email"]);
$_SESSION["sessionUserData"]["bg_name"]=stripslashes($_SESSION["sessionUserData"]["bg_name"]);
$_SESSION["sessionUserData"]["global_dsc"]=stripslashes($_SESSION["sessionUserData"]["global_dsc"]);
$_SESSION["sessionUser_Id"]=$_SESSION["sessionUserData"]["user_id"];
$_SESSION["product_line"]="safety";
$_SESSION["id"]=$_SESSION["sessionUserData"]["id"];
$_SESSION["CompteEntrepot"] = 'no';//Par default

//Nouveau pour  gérer les comptes entrepots 2015-03-10
switch($_SESSION["sessionUser_Id"]){//N'inclue QUE les compte SAFE
	//ENTREPOTS FRANCOPHONES
	//Trois-Rivières
	case 'entrepotsafe'	:  	$_SESSION["CompteEntrepot"] = 'yes';  break;
	//Drummondville		
	case 'safedr' :  	 	$_SESSION["CompteEntrepot"] = 'yes';  break;	
	//Laval
	case 'lavalsafe':  	 	$_SESSION["CompteEntrepot"] = 'yes';  break;
	//Terrebonne	
	case 'terrebonnesafe':  $_SESSION["CompteEntrepot"] = 'yes';  break;
	//Sherbrooke
	case 'sherbrookesafe':   $_SESSION["CompteEntrepot"] = 'yes'; break;	
	//Chicoutimi
	case 'chicoutimisafe':   $_SESSION["CompteEntrepot"] = 'yes'; break;
	//Granby
	case 'granbysafe':       $_SESSION["CompteEntrepot"] = 'yes'; break;
	//Longueuil	
	case 'longueuilsafe':    $_SESSION["CompteEntrepot"] = 'yes';  break;
	//Lévis	
	case 'levissafe':   	 $_SESSION["CompteEntrepot"] = 'yes';  break;	
	//Québec	
	case 'quebecsafe':   	 $_SESSION["CompteEntrepot"] = 'yes';  break;	
	//Montreal	
	case 'montrealsafe':   	 $_SESSION["CompteEntrepot"] = 'yes';  break;	
	//Gatineau	
	case 'gatineausafe':   	 $_SESSION["CompteEntrepot"] = 'yes';  break;	
	//St-Jérôme	
	case 'stjeromesafe':   	 $_SESSION["CompteEntrepot"] = 'yes';  break;	
	//ENTREPOTS ANGLOPHONES
	//Halifax	
	case 'warehousehalsafe': $_SESSION["CompteEntrepot"] = 'yes';  break;	
	
			
}

$_SESSION['account_type']='normal'; 
header("Location:lens_cat_selection.php");

?>
