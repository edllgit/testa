<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//Démarrer la session
session_start();
//Inclusions
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
include("inc/pw_functions.inc.php");

//$user_test = stripslashes($_POST[username]);
//$password_test = stripslashes($_POST[password]);

//$login  = anti_injection($_POST[username],$_POST[password]); // on lance la fonction anti injection
//$password = $login['pass']; // on recupère le pass
//$password=md5($password); // on met le pass en md5
//$pseudo = $login['user']; // on recupère le pseudo

$_SESSION["sessionUserData"]=login_to_dl($_POST[username], $_POST[password]);
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
header("Location:lens_cat_selection.php");
exit();


/*
function anti_injection( $user, $pass ) {
# on regarde s'il n'y a pas de commandes SQL
    $banlist = array (
        "insert", "select", "update", "delete", "distinct", "having", "truncate",
        "replace", "handler", "like", "procedure", "limit", "order by", "group by" 
        );
    if ( eregi ( "[a-zA-Z0-9]+", $user ) ) {
        $user = trim ( str_replace ( $banlist, '', strtolower ( $user ) ) );
    } else {
        $user = NULL;
    }

    # on regarde si le mot de passe est bien alphanumérique
    # on utilise strtolower() pour faire marcher str_ireplace()
    if ( eregi ( "[a-zA-Z0-9]+", $pass ) ) {
        $pass = trim ( str_replace ( $banlist, '', strtolower ( $pass ) ) );
    } else {
        $pass = NULL;
    }

    # on fait un tableau
    # s'il y a des charactères illégaux, on arrête tout
    $array = array ( 'user' => $user, 'pass' => $pass );
    if ( in_array ( NULL, $array ) ) {
        die ( 'ERREUR : Injection SQL détectée' );
    } else {
        return $array;
    }
} // ##########*/
?>