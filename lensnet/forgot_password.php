<?php 
//AFFICHER LES ERREURS

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Démarrer la session
session_start();
//Inclusions
require_once(__DIR__.'/../constants/aws.constant.php');
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');

$product_line = 'lensnetclub';
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
$resultPassword=mysqli_query($con,$queryPassword) or die ("Could not find account ");
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
$subject = "Votre compte Lensnetclub";
$message="<html>";
		$message.="<head><style type='text/css'>
		<!--
		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

$message.="<body>Bonjour, <br>
Lensnetclub.com<br>
Voici votre nom d'usager: $DataPassword[user_id]<br>
Voici votre mot de passe: $DataPassword[password]<br><br>Veuillez les conserver.<br>
Merci, l'&eacute;quipe de Lensnetclub.com";
$message.="</body></html>";
}else{
//ENGLISH EMAIL 1 ACCOUNT
$subject = "Your Lensnetclub Account password";
$message="<html>";
$message.="<head><style type='text/css'>
<!--
.TextSize {
font-size: 8pt;
font-family: Arial, Helvetica, sans-serif;
}
-->
</style></head>";
$message.="<body>Hi, <br>
Lensnetclub.com<br>
Here is your username: $DataPassword[user_id]<br>
Here is your password: $DataPassword[password]<br><br>Please keep them.<br>
Thanks, Lensnetclub.com  Team";
$message.="</body></html>";
}

$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$response=office365_mail($to_address, $from_address, $subject, null, $message);
//Redirect the customer
header('Location: pwd_info_sent.php'); 
exit();
}







//MANY accounts are found, we send the email with the username and password to the customer
if ($CompteurCompte > 1){
$send_to_address = str_split($leEmail,100);
	
if ($mylang=='lang_french'){
//FRENCH EMAIL MANY ACCOUNTS
$subject = "Votre compte Lensnetclub.com";
$message="<html>";
$message.="<head><style type='text/css'>
<!--
	.TextSize {
	font-size: 8pt;
	font-family: Arial, Helvetica, sans-serif;
}
-->
</style></head>";
$message.="<body>Bonjour,  Nous avons trouv&eacute;s plusieurs comptes correspondants &agrave; cette adresse email<br><br> Lensnetclub.com<br>";
$queryACCOUNTS = "SELECT user_id, password from accounts WHERE product_line = '$product_line' AND email = '" .  $leEmail . "'";
$resultACCOUNTS=mysqli_query($con,$queryACCOUNTS) or die ("Could not find account ");
while($DataACCOUNTS=mysqli_fetch_assoc($resultACCOUNTS,MYSQLI_ASSOC)){//step through each accounts	
$message.="Voici votre nom d'usager: $DataACCOUNTS[user_id]<br>
Voici votre mot de passe: $DataACCOUNTS[password]<br><br>";
}

$message.="Veuillez les conserver.<br>
Merci, l'&eacute;quipe de Lensnetclub.com";
$message.="</body></html>";
}else{
//ENGLISH EMAIL MANY ACCOUNTS
$subject = "Your Lensnetclub.com Account password";
$message="<html>";
$message.="<head><style type='text/css'>
<!--
	.TextSize {
	font-size: 8pt;
	font-family: Arial, Helvetica, sans-serif;
}
-->
</style></head>";
$message.="<body>Hi, We found many accounts with this email address <br><br> Lensnetclub.com<br>";
$queryACCOUNTS = "SELECT user_id, password from accounts WHERE product_line = '$product_line' AND email = '" .  $leEmail . "'";
$resultACCOUNTS=mysqli_query($con,$queryACCOUNTS)			or die ("Could not find account ");
	while($DataACCOUNTS=mysqli_fetch_assoc($resultACCOUNTS,MYSQLI_ASSOC)){//step through each accounts	
	$message.="Here is your username: $DataACCOUNTS[user_id]<br>
	Here is your password: $DataACCOUNTS[password]<br><br>";
	}
$message.="Please keep them.<br>";
$message.="Thanks, Lensnetclub.com Team";
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Lensnet Club</title>

<!--[if !IE]>-->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.dropshadow.js"></script>
<script type="text/javascript">
      window.onload = function()  
      {
        $("#container").dropShadow({left:6, top:6, blur:5, opacity:0.7});
        $(".formBox").dropShadow({left:6, top:6, blur:5, opacity:0.7});      
      }
    </script>
<!--<![endif]-->
    
<script language="javascript">
function ChangeLang(mylang){
		var date = new Date();
		date.setTime(date.getTime()+(30*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
		document.cookie = "mylang="+mylang+expires+"; path=/";
		window.location = "index.php";
}

</script>
<link href="ln.css" rel="stylesheet" type="text/css" />
<link href="ln_pt.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../includes/formvalidator.js"></script>
<script type="text/javascript">
<!--
/* This script and many more are available free online at
The JavaScript Source :: http://javascript.internet.com
Created by: Amit Wadhwa :: http://amitwadhwa.fcpages.com/javascript.com/formvalidator.html */

function check(formname, submitbutton) {
  var errors = '';
  errors += checkText(formname, 'user_id', 'Login');
  errors += checkText(formname, 'password', 'Password');
  //errors += checkRadio(formname, 'Question1', 'Question 1');
  //errors += checkText(formname, 'Question1_explain', 'Explain Question 1');
  //errors += checkSelect(formname, 'Country', 'Country Of Residence');
  //errors += checkText(formname, 'age', 'Age Of Person');
  //errors += checkNum(formname, 'age', 'Age Of Person');
  checkThisForm(formname, submitbutton, errors);
}
//-->
</script>

</head>

<body>
<div id="container">
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ln-masthead.jpg" width="1050" height="165" alt="LensNet Club"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNavHome.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn"><form action="forgot_password.php" method="post" name="loginForm" id="loginForm"><div class="header">
		  	<?php if ($mylang=='lang_french'){  
                echo 'Oubli&eacute; votre mot de passe';
				}else{
				echo 'Forgot your password';
				}?> 
		  </div>

		    <br />
		    <br />
		    <table width="750" border="0" align="center" cellpadding="5" cellspacing="0"  class="formBox">
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
           
              <tr valign="bottom">
                <td colspan="4" align="center"  class="formCellNosides"><div align="center">
      	 <input name="login" type="submit" class="formText" value="<?php echo $btn_submit_txt;?>" onClick="check('loginForm', this.name);">
	      	  
      	</div></td>
              </tr>
            </table>
	    <p>&nbsp;</p>
		    <p>&nbsp;</p>
		    <p>&nbsp;</p>
</form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
<link rel="shortcut icon" href="favicon.ico"/>
</body>
</html>