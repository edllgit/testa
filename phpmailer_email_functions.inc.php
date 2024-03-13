<?php

include_once(__DIR__.'/class.phpmailer.php');
include_once(__DIR__.'/class.phpmailer_smtp.php');
include_once(__DIR__.'/class.phpmailer_exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once(__DIR__.'/constants/office365.constant.php');

function office365_mail($to_address, $from_address, $subject, $text, $html = null) {
    $mail = new PHPMailer();

    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = constant("OFFICE365_SMTP_HOST");        //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = constant("OFFICE365_SMTP_USERNAME");    //SMTP username
        $mail->Password   = constant("OFFICE365_SMTP_PASSWORD");    //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption
        $mail->Port       = constant("OFFICE365_SMTP_PORT");        //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($from_address);
        
        if(is_array($to_address)){
            foreach ($to_address as &$address) {
                $mail->addAddress($address);
            }
        }else{
            $mail->addAddress($to_address);
        }

        //Content
        $mail->Subject = $subject;
        if(is_null($html)){
            $mail->Body = $text;
        }else{
            $mail->isHTML(true); //Set email format to HTML
            $mail->Body = $html;
        }

        $mail->send();
        return 'Message has been sent';
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}