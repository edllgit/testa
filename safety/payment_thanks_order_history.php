<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
?><?php
session_start();
if($_SESSION["sessionUser_Id"]=="")

	header("Location:loginfail.php");

require('../Connections/sec_connect.inc.php');

unset($pmtMessage);

//•••••••••••••••••••••

function sendEmail($total_cost, $first_name, $last_name, $order_num, $email){/* sends the emails */
	if ($email=="") $email="dbeaulieu@direct-lens.com";
	$message="Thank you for payment of $" .$total_cost. " to LensNetClub. We appreciate your business.\r\n";
	$headers = "From: dbeaulieu@direct-lens.com\r\n";
	$headers .=	"Content-Type: 	text/plain; charset=\"iso-8859-1\"\r\n";
	$emailSent = mail("$email", "Your LensNetClub Order No".$order_num." Payment Confirmation", "$message", "$headers");

	$message=$first_name . " " .$last_name. ", has made an online payment to LensNetClub in the amount of $" .$total_cost. "\r\n";
	$message.="The order number is $order_num.\r\n";
	$headers = "From: " . $email . "\r\n";
	$Headers .= "bcc: dbeaulieu@direct-lens.com\n";
	$headers .=	"Content-Type: 	text/plain; charset=\"iso-8859-1\"\r\n";
	//$emailSent = mail("dbeaulieu@direct-lens.com", "Order No $order_num", "$message", "$headers");
	$emailSent = mail("rco.daniel@gmail.com", "Order No $order_num", "$message", "$headers");
	return true;
}

//••••••••••••••••••••••

$returnValues=$_GET;

if ($returnValues['code']=="000"){// SUCCESSFUL TRANSACTION

	$transData['transTransID']=$returnValues['TxnGUID'];
	$transData['transAuthCode']=$returnValues['ApprovalCode'];
	$transData["transApprovalCode"]="Global Transport";
	$transData["transRespReasonCode"]="";
	$transData["transResultCode"]='0';
	$name=$returnValues['name'];
	$msg=$returnValues['msg'];
	
	$result=mysql_query("SELECT curdate()");/* get today's date */
	$today=mysql_result($result,0,0);
	$transData["cc_type"]=$returnValues['type'];
	
	$cclast4=substr($returnValues['mPAN'], -4, 4);
	$transData["cclast4"]=$cclast4;
	$amount =$returnValues['total_amt'];
	$user_id=$_SESSION["sessionUser_Id"];
	$order_num=$returnValues['order_num'];
	
	$query="insert into payments (user_id, order_num, pmt_date, pmt_type, pmt_amount, cctype, cclast4, transResultCode, transAuthCode, transApprovalCode, transTransID, order_paid_in_full) values ('$user_id', '$order_num', '$today', 'credit card', '$amount', '$transData[cc_type]', '$cclast4', '$transData[transResultCode]', '$transData[transAuthCode]', '$transData[transApprovalCode]', '$transData[transTransID]', 'y')";
	
	$result=mysql_query($query)
			or die ('ERROR: ' . mysql_error());
	
	sendEmail($amount, $_SESSION['sessionUserData']['first_name'], $_SESSION['sessionUserData']['last_name'], $order_num, $_SESSION['sessionUserData']['email']);
	
	$pmtMessage = "<div class=\"Subheader\">
				   <div class=\"tableSubHead\" style=\"font-size: 18px\"><strong>
					Thank you for your payment.</strong>
				   </div>
				 </div>";

} else {// TRANSACTION FAILED
	$pmtMessage = "<div class=\"Subheader\">
				   <div class=\"tableSubHead\" style=\"font-size: 18px\"><strong>
					There was a problem with your credit card payment. No payment was made.</strong>
				   </div>
				 </div>";
}// END IF SUCCESSFUL


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php print $sitename;?></title>



   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}

-->
</style>

<style type="text/css">
<!--
.select1 {width:100px}
-->
</style>

<script language="JavaScript" type="text/javascript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>

</head>


<body>
<div id="container"> 
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  	<?php   
	if ($_SESSION['account_type']=='restricted')
	{
	include("includes/sideNavRestricted.inc.php");
	}else{
	include("includes/sideNav.inc.php");
	}
	?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">
 <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header">
		     	Payment Confirmation 
		     </div></td>
		     		<td>&nbsp;</td>
		     </tr></table><div class="loginText">User: <?php print $_SESSION["sessionUser_Id"];?></div>
			 <p>&nbsp;</p>
<?php print "$pmtMessage"; ?>			
			</td></tr></table>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
 <?php include("footer.inc.php"); ?>
</div><!--END containter-->
</body>
</html>
<?php
mysql_free_result($languages);

mysql_free_result($languagetext);
?>