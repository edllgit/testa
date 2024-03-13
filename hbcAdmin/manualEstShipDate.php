<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
require_once(__DIR__.'/../constants/url.constant.php');
include("../Connections/sec_connect.inc.php");
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');

if ($_GET[order_num]!="")
	$order_num=$_GET[order_num];
else
	$order_num=$_POST[order_num];

if ($_POST[update_est_ship_date]=="true"){

	if ($_POST['envoyerConfirmationCustomer']=="on"){
	$envoyerConfirmationCustomer = 'YES';
	}
	
	$date=array();
	$date= explode("/", $_POST[est_ship_date]);
	$est_ship_date=$date[2]."/".$date[0]."/".$date[1];
	
	if ($_POST[TBD]=="TRUE")
		$est_ship_date="0000-00-00";

	$query="UPDATE est_ship_date SET est_ship_date='$est_ship_date' WHERE order_num='$order_num'";
	$result=mysql_query($query)		or die  ('I cannot select items because: ' . mysql_error());
	
	$Confirmation_message="<b><font color=\"#FF0000\" size=\"1\" face=\"Helvetica, sans-serif, Arial\"> - ESTIMATED SHIPPING DATE UPDATED</font></b>";
	

//Get customer email address(es)
$emailQuery = "SELECT O.*, A.email as email FROM orders O, accounts A WHERE O.user_id = A.user_id AND O.order_num =". $order_num;
//echo '<br>'. $emailQuery;
$Emailresult=mysql_query($emailQuery) 	or die  ('I cannot select items because: ' . mysql_error());
$emailDetail=mysql_fetch_array($Emailresult);
$CustomerEmail = $emailDetail['email'];


//Get the customer language choice, to select if we send the email in English or French
$languageQuery = "SELECT language FROM accounts WHERE user_id =  (SELECT user_id FROM orders where order_num ='$order_num')";
$Languageresult=mysql_query($languageQuery)		or die  ('I cannot select items because: ' . mysql_error());
$Language=mysql_fetch_array($Languageresult);

$Customer_Language = $Language['language'];//english or french



if ($envoyerConfirmationCustomer=="YES") {

	if ($Language['language'] == "french")
	{
	//echo 'order num' .$order_num ;
	
	//Send French email to customer to notify of the date change
	$message="<html>";
	$message.="<head><style type='text/css'>
	<!--
	.TextSize {
		font-size: 10pt;
		font-family: Arial, Helvetica, sans-serif;
	}
	-->
	</style></head>";
	$message.="<body>";
	
	if ($est_ship_date == '0000-00-00') {
	$message.="<h4>Bonjour,<br><br> Ceci est un message pour vous informer que la nouvelle date d’expédition de la commande #" . $order_num . " à été repoussée de quelques jours.</h4>";
    $message.="<br>Prénom: " 		  . $emailDetail['order_patient_first'];
	$message.="<br>Nom: " 			  . $emailDetail['order_patient_last'];
	$message.="<br>Numéro de réf: "   . $emailDetail['patient_ref_num'];
	
	$message .= "<br><br>Nous nous excusons de tous les inconvénients engendrés par ce délai. Pour toute autre question, n'hésitez pas à nous contacter.<br><br>Il s'agit d'un avis automatique, veuillez ne pas répondre à ce courriel.";
		
	}else {
	$message.="<h4>Bonjour,<br><br> Ceci est un message pour vous informer que la nouvelle date d’expédition de la commande #" . $order_num . " est le " . $est_ship_date . ".</h4>";
	$message.="<br>Prénom: " 		   . $emailDetail['order_patient_first'];
	$message.="<br>Nom:  " 			   . $emailDetail['order_patient_last'];
	$message.="<br>Numéro de réf.: "   . $emailDetail['patient_ref_num'];
	$message.= "<br><br>Nous nous excusons de tous les inconvénients engendrés par ce délai. Pour toute autre question, n'hésitez pas à nous contacter.<br><br> Il s'agit d'un avis automatique, veuillez ne pas répondre à ce courriel.";
	}
	
	$message.="</body></html>";
	
	$curTime= date("H:i:s");  
	$subject ="Avis de délai";
	
	$headers = "From: donotreply@entrepotdelalunette.com\n";
	$mime_boundary=md5(time()); 
	$headers .= 'MIME-Version: 1.0'."\r\n"; 
	$headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\""."\r\n"; 
						
	$headers .= "--".$mime_boundary."\r\n"; 
	$headers .=	"Content-Type: 	text/html; charset=\"UTF8\"\r\n";
	$headers .= "Content-Transfer-Encoding: 8bit"."\r\n"; 
	// End if Customer is French
	} else{//If customer is English
	
	

	//Send English email to customer to notify of the date change
	$message="<html>";
	$message.="<head><style type='text/css'>
	<!--
	.TextSize {
		font-size: 10pt;
		font-family: Arial, Helvetica, sans-serif;
	}
	-->
	</style></head>";
	$message.="<body>";
	
	if ($est_ship_date == '0000-00-00') {
	$message.="<h4> Dear Sir / Madam, we have encountered a slight delay in your order number " . $order_num . "</h4> <br><br> If you need further information, do not hesitate to contact us.<br><br>This is an automatic notice, please do not reply to this email.";
	}else {
	$message.="<h4> Dear Sir/Madam,  we have encountered a slight delay in your order number  " . $order_num . ". Please, the new expected shipping date is " . $est_ship_date . ".</h4><br><br>If you need further information, do not hesitate to contact us.<br><br> This is an automatic notice, please do not reply to this email.";
	}
	$message.="</body></html>";
	$send_to_address= "dbeaulieu@direct-lens.com,".$emailDetail['email'];
	
	$curTime= date("H:i:s");  
	$subject ="Delay notice";
	
	$headers = "From: donotreply@entrepotdelalunette.com\n";
	$mime_boundary=md5(time()); 
	$headers .= 'MIME-Version: 1.0'."\r\n"; 
	$headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\""."\r\n"; 
						
	$headers .= "--".$mime_boundary."\r\n"; 
	$headers .=	"Content-Type: 	text/html; charset=\"UTF8\"\r\n";
	$headers .= "Content-Transfer-Encoding: 8bit"."\r\n"; 
	
	
	}//End if customer is English
	$send_to_address  = str_split($CustomerEmail,50);
	
		if ($send_to_address == "") {
		echo 'Aucune adresse courriel n\'a été entrée pour ce compte, pour envoyer une notification lors d\'un changement de date, veuillez en entrer au moins une dans le labAdmin';
		exit();
		}
	
//SEND EMAIL
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$response=office365_mail($to_address, $from_address, $subject, null, $message);

	}//end if EnvoyerConfirmationCustomer == YES
}//End if UpdateEstShipDate == true
$estQuery="select * from est_ship_date WHERE order_num='$order_num'"; //get order's user id
$estResult=mysql_query($estQuery)	or die  ('I cannot select items because: ' . mysql_error());
$listItem=mysql_fetch_array($estResult);
$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[est_ship_date]','%m/%d/%Y')");
$ship_date=mysql_result($new_result,0,0);

?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />

</head>
<body>
  <table border="0" cellspacing="0" cellpadding="0" width="100%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField3">
            	<tr bgcolor="#000000">
            		<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">EDIT
           		           ESTIMATED SHIP DATE</font></b><?php echo $Confirmation_message;?></td>
       		  </tr>
			<tr><td>
			<form action="manualEstShipDate.php" method="post" enctype="multipart/form-data" name="editForm" id="editForm">
			  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" class="formField2">
			    <tr>
			      <td align="right" bgcolor="#FFFFFF">Manually set date:</td>
			      <td bgcolor="#FFFFFF"><label>
			        <input name="TBD" type="radio" id="radio" value="FALSE" <?php if ($listItem[est_ship_date]!="0000-00-00") echo "checked";?>>
		          </label></td>
			      <td align="right" bgcolor="#FFFFFF">Estimated Ship Date (mm/dd/yyyy):</td>
			      <td bgcolor="#FFFFFF"><input name="est_ship_date" type="text" id="est_ship_date" value="<?php echo $ship_date;?>">
				 </td>
		        </tr>
			    <tr>
			      <td align="right" bgcolor="#DDDDDD">Set to &quot;TBD&quot;:</td>
			      <td bgcolor="#DDDDDD"><input type="radio" name="TBD" id="radio2" value="TRUE" <?php if ($listItem[est_ship_date]=="0000-00-00") echo "checked";?>></td>
			      <td bgcolor="#DDDDDD">&nbsp;</td>
			      <td bgcolor="#DDDDDD">Send notification to customer<input  type="checkbox"  name="envoyerConfirmationCustomer" ></td>
		        </tr>
			    <tr>
			      <td colspan="4" align="center" bgcolor="#FFFFFF"><label>
			        <input name="order_num" type="hidden" id="order_num" value="<?php echo $order_num;?>">
			        <input name="update_est_ship_date" type="hidden" id="update_est_ship_date" value="true">
			        <input type="submit" name="button" id="button" value="Update">
		          </label></td>
		        </tr>
			    <tr>
			      <td colspan="4" align="center" bgcolor="#DDDDDD"><div>NOTE: Estimated shipping dates set here may be recalculated and over-written if the order status changes.</div></td>
		        </tr>
		      </table>
			</form>
			</td></tr>
			<tr><td><div class="formField3">
		<a href="<?php echo constant('DIRECT_LENS_URL'); ?>/labAdmin/report.php">Back to Order List</a>
	</div></td>
  </tr>
</table></td>
    </tr>
</table>
  <p>&nbsp;</p>
</body>
</html>