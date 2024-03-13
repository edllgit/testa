<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";

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
	$headers .=	"Content-Type: 	text/plain; charset=\"iso-8859-1\"\r\n";
	//$emailSent = mail("dbeaulieu@direct-lens.com", "Order No $order_num", "$message", "$headers");
	$emailSent = mail("dbeaulieu@direct-lens.com", "Order No $order_num", "$message", "$headers");
	return true;
}

//••••••••••••••••••••••

$returnValues=$_GET;

if ($returnValues['ssl_result']=="0"){// SUCCESSFUL TRANSACTION

	$transData['transTransID']=$returnValues['ssl_txn_id'];
	$transData['transAuthCode']=$returnValues['ssl_approval_code'];
	$transData["transApprovalCode"]="CPS CANADA";
	$transData["transRespReasonCode"]="";
	$transData["transResultCode"]='0';
	$name=$returnValues['name'];
	$msg=$returnValues['msg'];
	
	$result=mysql_query("SELECT curdate()");/* get today's date */
	$today=mysql_result($result,0,0);
	$transData["cc_type"]=$returnValues['type'];
	
	$result=mysql_query("SELECT curdate()");/* get today's date*/ 
	$today=mysql_result($result,0,0);
	$transData["cc_type"]=$returnValues['type'];
	
	$cclast4=substr($returnValues['ssl_card_number'], -4, 4);
	$transData["cclast4"]=$cclast4;
	$amount =$returnValues['ssl_amount'];
	
	$user_id   = $_SESSION["sessionUser_Id"];
	$order_num = $_SESSION["order_num_paid_by_cc_customer_history"];
	$_SESSION["order_num_paid_by_cc_customer_history"] = '';

	$query="insert into payments (user_id, order_num, pmt_date, pmt_type, pmt_amount, cctype, cclast4, transResultCode, transAuthCode, transApprovalCode, transTransID, order_paid_in_full) values ('$user_id', '$order_num', '$today', 'credit card', '$amount', '$transData[cc_type]', '$cclast4', '$transData[transResultCode]', '$transData[transAuthCode]', '$transData[transApprovalCode]', '$transData[transTransID]', 'y')";
	
	$result=mysql_query($query)			or die ('ERROR: ' . mysql_error());
	
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
<title>LensNet Club</title>


<!--[if !IE]>-->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.dropshadow.js"></script>
<script type="text/javascript">
      window.onload = function()  
      {
        $("#container").dropShadow({left:6, top:6, blur:5, opacity:0.7});
		     
      }
    </script>
<!--<![endif]-->
   
<link href="ln.css" rel="stylesheet" type="text/css" />
<link href="ln_pt.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
<!--
function popup(url) 
{
 params  = 'width='+800;
 params += ', height='+800;
 params += ', top=0, left=0'
 params += ', fullscreen=yes';

 newwin=window.open(url,'windowname4', params);
 if (window.focus) {newwin.focus()}
 return false;
}
// -->
</script>

<SCRIPT LANGUAGE="JavaScript" SRC="includes/CalendarPopup.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="includes/date_validation.js"></SCRIPT>
<script language="JavaScript" type="text/JavaScript">
<!--

function checkAllDates(form){
		var ed=form.date_var;
		if (isDate(ed.value)==false){
			ed.focus()
			return false}
		return true
	}
//-->
</script>
    
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

</head>


<body>
<div id="container">
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ln-masthead.jpg" width="1050" height="165" alt="LensNet Club"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">
 <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header">
		     	Payment Confirmation 
		     </div></td>
		     		<td>&nbsp;</td>
		     </tr></table><div class="loginText">User: <?php echo $_SESSION["sessionUser_Id"];?></div>
			 <p>&nbsp;</p>
<?php echo "$pmtMessage"; ?>			
			</td></tr></table>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
</body>
</html>
<?php
mysql_free_result($languages);

mysql_free_result($languagetext);
?>