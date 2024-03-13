<?php
//AFFICHER LES ERREURS

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Démarrer la session
session_start();
//Inclusions
require_once(__DIR__.'/../constants/url.constant.php');
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
include("includes/pw_functions.inc.php");


/*$login  = anti_injection($_POST[user_id],$_POST[password]); // on lance la fonction anti injection
$password = $login['pass']; // on recupère le pass
$pseudo = $login['user']; // on recupère le pseudo*/

$_SESSION["sessionUserData"]=login_to_dl($_POST[user_id], $_POST[password]);
$_SESSION["sessionUserData"]["id"]			= stripslashes($_SESSION["sessionUserData"]["primary_key"]);
$_SESSION["sessionUserData"]["company"] 	= stripslashes($_SESSION["sessionUserData"]["company"]);
$_SESSION["sessionUserData"]["first_name"]	= stripslashes($_SESSION["sessionUserData"]["first_name"]);
$_SESSION["sessionUserData"]["last_name"]	= stripslashes($_SESSION["sessionUserData"]["last_name"]);
$_SESSION["sessionUserData"]["address1"]	= stripslashes($_SESSION["sessionUserData"]["address1"]);
$_SESSION["sessionUserData"]["address2"]	= stripslashes($_SESSION["sessionUserData"]["address2"]);
$_SESSION["sessionUserData"]["city"] 	    = stripslashes($_SESSION["sessionUserData"]["city"]);
$_SESSION["sessionUserData"]["zip"]			= stripslashes($_SESSION["sessionUserData"]["zip"]);
$_SESSION["sessionUserData"]["phone"]		= stripslashes($_SESSION["sessionUserData"]["phone"]);
$_SESSION["sessionUserData"]["fax"]			= stripslashes($_SESSION["sessionUserData"]["fax"]);
$_SESSION["sessionUserData"]["email"]		= stripslashes($_SESSION["sessionUserData"]["email"]);
$_SESSION["sessionUserData"]["bg_name"]		= stripslashes($_SESSION["sessionUserData"]["bg_name"]);
$_SESSION["sessionUserData"]["global_dsc"]  = stripslashes($_SESSION["sessionUserData"]["global_dsc"]);
$_SESSION["sessionUser_Id"]					= $_SESSION["sessionUserData"]["user_id"];
$_SESSION["id"]								= $_SESSION["sessionUserData"]["id"];
$_SESSION['account_type']					= 'normal';
//Redirect to order page
header("Location:".constant('DIRECT_LENS_URL')."/lensnet/lens_cat_selection.php");
exit();


?>