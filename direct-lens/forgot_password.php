<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//Démarrer la session
session_start();
//Inclusions
require_once(__DIR__.'/../constants/aws.constant.php');
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
include('includes/phpmailer_email_functions.inc.php');
require_once('includes/class.ses.php');

$product_line = 'directlens';

if (isset($_POST[email])){
//A email has been submited
$leEmail = mysqli_real_escape_string($con,$_POST[email]);


//If Email is empty, we redirectto a page with the message to contact us because the email cannot be found in the system
if ($leEmail == ''){
header('Location: pwd_contact.php'); 
exit();
} 


//Here the email is not empty, we check if it matches if only one customer account
$queryPassword = "SELECT user_id, password from accounts WHERE product_line = '$product_line' AND email = '" .  $leEmail . "'";
$resultPassword=mysqli_query($con,$queryPassword)			or die ("Could not find account ");
$CompteurCompte=mysqli_num_rows($resultPassword);
$DataPassword=mysqli_fetch_array($resultPassword,MYSQLI_ASSOC);


//No accounts are found, we redirect
if ($CompteurCompte == 0){
header('Location: pwd_contact.php'); 
exit();
}

//ONLY 1 account is found, we send the email with the username and password to the customer
if ($CompteurCompte == 1){
$send_to_address = str_split($leEmail,100);

	
if ($mylang=='lang_french'){
//FRENCH EMAIL 1 ACCOUNT
$subject = "Votre compte Direct-lens.com";
$message="<html>";
$message.="<head></head>";

$message.="<body>Bonjour, <br>
Direct-lens.com<br>
Voici votre nom d'usager: $DataPassword[user_id]<br>
Voici votre mot de passe: $DataPassword[password]<br><br>Veuillez les conserver.<br>
Merci, l'&eacute;quipe de direct-lens.com";
$message.="</body></html>";
}else{
//ENGLISH EMAIL 1 ACCOUNT
$subject = "Your Direct-lens.com Account password";
$message="<html>";
$message.="<head></head>";

$message.="<body>Hi, <br>
Direct-lens.com<br>
Here is your username: $DataPassword[user_id]<br>
Here is your password: $DataPassword[password]<br><br>Please keep them.<br>
Thanks, Direct-lens.com Team";
$message.="</body></html>";
}
//We send the email
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$response=office365_mail($to_address, $from_address, $subject, null, $message);
header('Location: pwd_info_sent.php'); 
exit();
}


//MANY accounts are found, we send the email with the username and password to the customer
if ($CompteurCompte > 1){
$send_to_address = str_split($leEmail,100);
	
if ($mylang=='lang_french'){
//FRENCH EMAIL MANY ACCOUNTS
$subject = "Votre compte Direct-lens.com";
$message="<html>";
$message.="<head></head>";
$message.="<body>Bonjour,  Nous avons trouv&eacute;s plusieurs comptes correspondants &agrave; cette adresse email<br><br> Direct-lens.com<br>";
$queryACCOUNTS = "SELECT user_id, password from accounts WHERE product_line = '$product_line' AND email = '" .  $leEmail . "'";
$resultACCOUNTS=mysqli_query($con,$queryACCOUNTS) or die ("Could not find account ");
while($DataACCOUNTS=mysqli_fetch_assoc($resultACCOUNTS,MYSQLI_ASSOC)){//step through each accounts	
$message.="Voici votre nom d'usager: $DataACCOUNTS[user_id]<br>
Voici votre mot de passe: $DataACCOUNTS[password]<br><br>";
}

$message.="Veuillez les conserver.<br>
Merci, l'&eacute;quipe de direct-lens.com";
$message.="</body></html>";
}else{
//ENGLISH EMAIL MANY ACCOUNTS
$subject = "Your Direct-lens.com Account password";
$message="<html>";
$message.="<head></head>";
$message.="<body>Hi, We found many accounts with this email address <br><br> Direct-lens.com<br>";
$queryACCOUNTS = "SELECT user_id, password from accounts WHERE product_line = '$product_line' AND email = '" .  $leEmail . "'";
$resultACCOUNTS=mysqli_query($con,$queryACCOUNTS) or die ("Could not find account ");
	while($DataACCOUNTS=mysqli_fetch_assoc($resultACCOUNTS,MYSQLI_ASSOC)){//step through each accounts	
	$message.="Here is your username: $DataACCOUNTS[user_id]<br>
	Here is your password: $DataACCOUNTS[password]<br><br>";
	}
$message.="Please keep them.<br>";
$message.="Thanks, Direct-lens.com Team";
$message.="</body></html>";
}

//Send the mail
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$response=office365_mail($to_address, $from_address, $subject, null, $message);
//Redirect the customer
header('Location: pwd_info_sent.php'); 
exit();
}


}//End if email is submitted
 ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Direct-Lens</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}
.select1 {width:100px}
-->
</style>
<link href="dl.css" rel="stylesheet" type="text/css">
</head>

<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><table width="917" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/top_piece.jpg" alt="Direct Lens" width="917" height="158"></td>
      </tr>
      <tr>
        <td background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/mid_sect_bg.jpg">
		<table width="900" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="215" valign="top"> <div id="leftColumn">
	<?php 	include("includes/sideNav.inc.php");?>
        </div></td>
    <td width="500" valign="top"><form action="forgot_password.php" method="post" name="forgot_pwd" id="forgot_pwd"><div class="header">
		  	 <?php if ($mylang=='lang_french'){  
                echo 'Oubli&eacute; votre mot de passe';
				}else{
				echo 'Forgot your password';
				}?>
		  </div>
		    <table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="4" bgcolor="#000099" class="tableHead">&nbsp;</td>
                </tr>
              <tr>
				<td align="left"  class="formCellNosides"><div align="left"> 
				<?php if ($mylang=='lang_french'){  
                echo 'SVP Entrer votre adresse email';
				}else{
				echo 'Please type your email address';
				}?>&nbsp;&nbsp;&nbsp;<input name="email" type="text" id="email" size="20"></div></td>
              	</tr>
            </table>
		    <div align="center" style="margin:11px">
		      	<p>
		      	<input name="login" type="submit" class="formText" value="<?php echo $btn_submit_txt;?>">
		      	</p>
		      	</div>
                
		  </form></td></tr></table>
		           </td>
      </tr>
      <tr>
        <td background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/bot_piece_bg.jpg"><p>&nbsp;</p><br>
          </td>
      </tr>
    </table>
	</td>
  </tr>
</table>

</body>
</html>