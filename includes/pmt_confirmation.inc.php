<?php

function sendPmtConfirmEmail($total_cost, $first_name, $last_name, $order_num, $email){/* sends the emails */
	$QueryProductLine   = "SELECT order_from FROM orders WHERE order_num = $order_num";
	$ResultProductLine  = mysql_query($QueryProductLine)		or die  ('I cannot select items because: ' . mysql_error());
	$DataProductLine	= mysql_fetch_array($ResultProductLine);
	$ProductLine = 	     $DataProductLine[product_line];
	
	//$send_to_address = array('rapports@direct-lens.com');
	$send_to_address = array('rapports@direct-lens.com');
	$to_address 	= $send_to_address;
	$from_address 	= "donotreply@entrepotdelalunette.com";
	$subject 		= $ProductLine . " Online Payment for Order #$order_num: ".$curTime;
	$message		= $first_name . " " .$last_name. ", has made an online payment to $ProductLine in the amount of $" .$total_cost. "\r\n";
	$message.= "The order number is $order_num.\r\n";
	$curTime 		= date("m-d-Y");	
	
	//Send the email
	$response 		= office365_mail($to_address, $from_address, $subject, null, $message);
	$headers = "From: " . $email . "\r\n";
	$headers .=	"Content-Type: 	text/plain; charset=\"iso-8859-1\"\r\n";
	//$emailSent = mail("rco.daniel@gmail.com", "LensnetClub Order No $order_num", "$message", "$headers");	
	return $headers;
}

function sendPmtConfirmEmailSES($total_cost, $first_name, $last_name, $order_num, $email){/* sends the emails */
	$QueryProductLine   = "SELECT order_from FROM orders WHERE order_num = $order_num";
	$ResultProductLine  = mysql_query($QueryProductLine)		or die  ('I cannot select items because: ' . mysql_error());
	$DataProductLine	= mysql_fetch_array($ResultProductLine);
	$ProductLine = 	     $DataProductLine[product_line];
	
	//$send_to_address = array('rapports@direct-lens.com');
	$send_to_address = array('rapports@direct-lens.com');
	$to_address 	= $send_to_address;
	$from_address 	= "donotreply@entrepotdelalunette.com";
	$subject 		= $ProductLine . " Online Payment for Order #$order_num: ".$curTime;
	$message		= $first_name . " " .$last_name. ", has made an online payment to $ProductLine in the amount of $" .$total_cost. "\r\n";
	$message.= "The order number is $order_num.\r\n";
	$curTime 		= date("m-d-Y");	
	
	//Send the email
	$response 		= office365_mail($to_address, $from_address, $subject, null, $message);
	$headers = "From: " . $email . "\r\n";
	$headers .=	"Content-Type: 	text/plain; charset=\"iso-8859-1\"\r\n";
	//$emailSent = mail("rco.daniel@gmail.com", "LensnetClub Order No $order_num", "$message", "$headers");	
	return $headers;
}


?>