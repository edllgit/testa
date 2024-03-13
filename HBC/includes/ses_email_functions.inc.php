<?php
require_once(__DIR__.'/../../constants/aws.constant.php');
require_once('class.ses.php');

function office365_mail($to_address, $from_address, $subject, $text, $html=null){
	
	$aws_access_key = constant("AWS_SES_USER_ACCESS_KEY");
	$aws_secret_key = constant("AWS_SES_USER_SECRET_KEY");

	$ses = new SimpleEmailService($aws_access_key, $aws_secret_key);
	$ses->enableVerifyPeer(false);

	$m = new SimpleEmailServiceMessage();
	$m->addTo($to_address);
	$m->setFrom($from_address);
	$m->setSubject($subject);
	$m->setMessageFromString($text,$html);

	$response=$ses->sendEmail($m);

	return $response;
}
	
?>