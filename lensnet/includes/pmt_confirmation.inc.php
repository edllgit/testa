<?php

function sendPmtConfirmEmail($total_cost, $first_name, $last_name, $order_num, $email){/* sends the emails */
	$email="dbeaulieu@direct-lens.com";
	$message=$first_name . " " .$last_name. ", has made an online payment to LensNetClub in the amount of $" .$total_cost. "\r\n";
	$message.="The order number is $order_num.\r\n";
	$headers = "From: " . $email . "\r\n";
	$Headers .= "bcc: dbeaulieu@direct-lens.com\n";
	$headers .=	"Content-Type: 	text/plain; charset=\"iso-8859-1\"\r\n";
	//$emailSent = mail("dbeaulieu@direct-lens.com", "Order No $order_num", "$message", "$headers");
	$emailSent = mail("rco.daniel@gmail.com", "LensnetClub Order No $order_num", "$message", "$headers");
	$emailSent = mail("dbeaulieu@direct-lens.com", "LensnetClub Order No $order_num", "$message", "$headers");
	return $headers;
}

?>