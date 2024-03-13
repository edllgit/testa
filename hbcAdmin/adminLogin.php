<?php
//AFFICHER LES ERREURS

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Inclusions
include "../connexion_hbc.inc.php";
include "../includes/getlang.php";

session_start();


$ip		  = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$level 	  = 'Lab admin';
$datetime = date("Y-m-d G i:s");
$ip2 	  = $_SERVER['HTTP_X_FORWARDED_FOR']; 
$provient_de  = $_SERVER['HTTP_REFERER']; //L'adresse de la page (si elle existe) qui a conduit le client à la page courante. 
$browser      = $_SERVER['HTTP_USER_AGENT'];//Chaîne qui décrit le client HTML utilisé pour voir la page courante. Par exemple : Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586). 	
//Logger la tentative de login
$queryInsert  = "INSERT INTO login_attempt(username, password, datetime, ip, ip2, level, provient_de, browser)
VALUES ('$_POST[username_hbc]', '$_POST[password_hbc]', '$datetime', '$ip', '$ip2', '$level', '$provient_de', '$browser')";
$resultInsert = mysqli_query($con,$queryInsert)		or die ("Could not insert" . mysqli_error($con));
		
$query		= "SELECT username, password, lab_primary_key, id FROM access WHERE username = '$_POST[username_hbc]' AND password = '$_POST[password_hbc]'";
$result 	= mysqli_query($con,$query)		or die ("Could not find user");
$usercount 	= mysqli_num_rows($result);

if ($usercount == 0){ /* user id and/or password are not valid */
	echo "Username or password is invalid. Remember both are case sensitive. Click your browser's back button to try again.";
exit();
}else{
/* user id and password are valid and a match -- fetch user data  */
$labAdminData = mysqli_fetch_array($result,MYSQLI_ASSOC);

	$compUser	= strcmp($_POST[username_hbc], $labAdminData[username]);/* check that login user id is case-sensitive to user id in db*/
	$compPW		= strcmp($_POST[password_hbc], $labAdminData[password]);/* check that login password is case-sensitive to password in db*/
	if ($compUser != 0 or $compPW != 0){
		echo "Username or password is invalid. Remember both are case sensitive. Click your browser's back button to try again.";
		exit();
	}
}//End IF	

$id 	 = $labAdminData[id];
$mainLab = $labAdminData[lab_primary_key];

	$query  = "SELECT * FROM labs WHERE primary_key = $mainLab";
	$result = mysqli_query($con,$query)		or die ("Could not find lab account");
	$_SESSION["labAdminData"] = mysqli_fetch_array($result,MYSQLI_ASSOC);
	$_SESSION["accessid"]	  = $id;
	$_SESSION["lab_pkey"]	  = $_SESSION["labAdminData"]["primary_key"];
	session_write_close();
	header("Location: ../hbcAdmin/adminHome.php");/* go to admin home page */
?>
