<?php
//AFFICHER LES ERREURS

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


//Inclusions
include "../connexion_hbc.inc.php";
include "../includes/getlang.php";
include "config.inc.php";
//include('includes/phpmailer_email_functions.inc.php');
//require_once('includes/class.ses.php');


function login_to_hbc($user_test, $password_test){/* check user_id and password on login */
include "../connexion_hbc.inc.php";
$ip		      = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$level   	  = 'HBC Optipro Order Validation Website';
$datetime 	  = date("Y-m-d G i:s");
$provient_de  = $_SERVER['HTTP_REFERER']; //L'adresse de la page (si elle existe) qui a conduit le client à la page courante. 
$browser      = $_SERVER['HTTP_USER_AGENT'];//Chaîne qui décrit le client HTML utilisé pour voir la page courante. Par exemple : Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586). 	
$ip2 		  = $_SERVER['HTTP_X_FORWARDED_FOR'];	
	
$queryInsert="INSERT INTO login_attempt(username, password, datetime, ip, ip2, level, provient_de, browser) 
VALUES ('$user_test', '$password_test', '$datetime', '$ip', '$ip2', '$level', '$provient_de', '$browser')";
$resultInsert 	= mysqli_query($con,$queryInsert)		or die ("Could not insert" . $queryInsert . mysqli_error($con));

$query     		= "SELECT * FROM accounts WHERE product_line = 'hbc'  AND user_id = '$user_test' AND password = '$password_test'";
$result    		= mysqli_query($con,$query)		or die ("Could not find account");
$usercount 		= mysqli_num_rows($result);

	if ($usercount == 0){	
		header("Location:loginfail.php");
		exit();
	}else{
		/* user id and password are valid and a match -- fetch user data  */
		$sessionUserData=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	$date1 = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
	$ajd   = date("Y/m/d", $date1);
		
	//We save the connexion as this customer last login
	$QueryLastLogin  = "UPDATE accounts SET last_connexion = '$ajd' WHERE  user_id = '$user_test'";
	$resultLastLogin = mysqli_query($con,$QueryLastLogin)		or die ("Could not find account");

	$compPW=strcmp($password_test, $sessionUserData[password]);/* check that login password is case-sensitive to password in db*/
	if ($compPW != 0){
		header("Location:loginfail.php");
		exit();
		}
	if ($sessionUserData[approved] != "approved"){
		header("Location:loginpending.php");
		exit();
		}
	}
	return($sessionUserData);
}



function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysqli_query($con,$queryMail)		or die  ('I cannot Send email because: ' . mysqli_error($con));	
}


?>