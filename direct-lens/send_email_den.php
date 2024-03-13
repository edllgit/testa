<?php
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');

$DenAcctNum 	 	   = $_POST['den_account_num'];
$email 	 	  		   = $_POST['email'];
$AccountNum 	 	   = $_POST['account_num'];
$password  			   = $_POST["password"];
$title  			   = $_POST["title"];
$first_name  		   = $_POST["first_name"];
$last_name  		   = $_POST["last_name"];
$business_name  	   = $_POST["business_name"];
$username              = $_POST["username"];
$purchase_unit         = $_POST["purchase_unit"];
$PasswordConfirmation  = $_POST["password_confirmation"];
$edging_equipment      = $_POST["edging_equipment"];
$ip   				   = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$ip2   				   = $_SERVER['HTTP_X_FORWARDED_FOR'];


$message = "Title :   					$title 					<br>
			First name :   				$first_name 			<br>
			Last name :   				$last_name 			    <br>
		    Business Name:              $business_name          <br>
			Email :						$email 					<br>			
		    Eye Recommend Member # :   	$DenAcctNum				<br>
			Purchase Unit:          	$purchase_unit          <br>
			Username :   				$username			    <br>
			Password   :   				$password  				<br>
			Password Confirmation   :   $PasswordConfirmation  	<br>
			Have their own edging equipment: $edging_equipment  <br>
			IP : 						$ip 					<br>
			IP 2 : 						$ip2 					<br>"; 
	
//Si on a pas le numéro Eye Recommend, seulement moi recevrai le courriel		
if ($DenAcctNum == '')
$send_to_address = array('rapports@direct-lens.com');
else
$send_to_address = array('rapports@direct-lens.com');

$curTime 		= date("m-d-Y");	
$to_address 	= $send_to_address;
$from_address 	= "donotreply@entrepotdelalunette.com";
$subject 		= "Eye Recommend New Account Request: ".$curTime;
//Send the email
$response 		= office365_mail($to_address, $from_address, $subject, null, $message);
//Redirect the customer  to confirm the account opening
header("location:openaccountthanksden.php");
?>


