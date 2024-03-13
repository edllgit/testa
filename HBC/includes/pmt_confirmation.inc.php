<?php

function sendPmtConfirmEmail($total_cost, $first_name, $last_name, $order_num, $email){/* sends the emails */
	$email="dbeaulieu@direct-lens.com";
	$message=$first_name . " " .$last_name. ", has made an online payment to AIT Lens CLub in the amount of $" .$total_cost. "\r\n";
	$message.="The order number is $order_num.\r\n";
	$headers = "From: " . $email . "\r\n";
	$headers .=	"Content-Type: 	text/plain; charset=\"iso-8859-1\"\r\n";
	//$emailSent = mail("dbeaulieu@direct-lens.com", "Order No $order_num", "$message", "$headers");
	$emailSent = mail("dbeaulieu@direct-lens.com", "Order No $order_num", "$message", "$headers");
	return $headers;
}

?>