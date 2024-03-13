<?php
//AFFICHER LES ERREURS

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Inclusions
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";

session_start();
//include("Connections/sec_connect.inc.php");
/*$login    = anti_injection($_POST[username_test],$_POST[password_test]); // on lance la fonction anti injection
$password = $login['pass']; // on recupère le pass
$pseudo   = $login['user']; // on recupère le pseudo
*/


$ip= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$level = 'Main admin';
$datetime = date("Y-m-d G i:s");
$provient_de  = $_SERVER['HTTP_REFERER']; //L'adresse de la page (si elle existe) qui a conduit le client à la page courante. 
$browser      = $_SERVER['HTTP_USER_AGENT'];//Chaîne qui décrit le client HTML utilisé pour voir la page courante. Par exemple : Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586). 	
$ip2 = $_SERVER['HTTP_X_FORWARDED_FOR'];
	
	$queryInsert="INSERT INTO login_attempt(username, password, datetime, ip, ip2, level, provient_de, browser) VALUES ('$_POST[username_test]', '$_POST[password_test]', '$datetime', '$ip', '$ip2', '$level','$provient_de', '$browser')";
	$resultInsert=mysqli_query($con,$queryInsert)		or die ("Could not insert" . mysqli_error($con));



	$query="SELECT username, password, id FROM access_admin WHERE username = '$_POST[username_test]' AND password = '$_POST[password_test]'";
	$result=mysqli_query($con,$query)		or die ("Could not find user");
	$usercount=mysqli_num_rows($result);
	if ($usercount == 0){ /* user id and/or password are not valid */
	echo "Username or password is invalid. Remember both are case sensitive. Click your browser's back button to try again.";
	exit();
	}else{/* user id and password are valid and a match -- fetch user data  */
	
	$adminData=mysqli_fetch_array($result,MYSQLI_ASSOC);

	$_SESSION[adminData][username] = $adminData['username'];
	$compUser=strcmp($_POST[username_test], $adminData[username]);/* check that login user id is case-sensitive to user id in db*/
	$compPW=strcmp($_POST[password_test], $adminData[password]);/* check that login password is case-sensitive to password in db*/
	if ($compUser != 0 or $compPW != 0){
		echo "Username or password is invalid. Remember both are case sensitive. Click your browser's back button to try again.";
		exit();
		}
	}
$id = $adminData[id];
//$mainLab= $labAdminData[lab_primary_key];
$_SESSION["access_admin_id"]=$id;
	
header("Location:report.php");
exit();


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
} // ##########
?>
