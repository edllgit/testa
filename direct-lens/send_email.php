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
include('includes/phpmailer_email_functions.inc.php');
require_once('includes/class.ses.php');



$Referer 		 = $_SERVER['HTTP_REFERER'];
$email  		 = $_POST["contact_email"];
$name   		 = $_POST["contact_name"];
$customermessage = $_POST["contact_message"];
$ip    = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$ip2   = $_SERVER['HTTP_X_FORWARDED_FOR'];



$message = "Email :   $email<br>
			Name :    $name<br>
			Message : $customermessage<br><br>
			IP : $ip <br>
			IP 2 : $ip2 <br>"	; 
		

$redirection = "location:".constant('DIRECT_LENS_URL')."/direct-lens/contact.php?&e=y#contact";
$send_to_address = array('rapports@direct-lens.com');


$curTime 		= date("m-d-Y");	
$to_address 	= $send_to_address;
$from_address 	= "donotreply@entrepotdelalunette.com";
$subject 		= "Contact Form - From Direct-Lens: ".$curTime;


//IF all required fields are filled, We send the email
if(($email <> '') && ($name <> '') && ($customermessage <> '')){
//$response 		= office365_mail($to_address, $from_address, $subject, null, $message);
}

header("$redirection");
?>

