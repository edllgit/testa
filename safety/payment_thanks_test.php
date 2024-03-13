<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
include"config.inc.php";
?><?php
session_start();
if($_SESSION["sessionUser_Id"]=="")

	header("Location:loginfail.php");

require('../Connections/sec_connect.inc.php');
unset($pmtMessage);

function sendEmail(){/* sends the emails */
	$message="Thank you for payment of $" . $_POST["total_cost"] . " to Direct Lens. We appreciate your business.\r\n";
	$headers = "From: orders@direct-lens.com\r\n";
	$headers .=	"Content-Type: 	text/plain; charset=\"iso-8859-1\"\r\n";
	$emailSent = mail("$_POST[email]", "Your direct-lens.com Order No $_POST[order_num] Payment Confirmation", "$message", "$headers");

	$message=$_POST["first_name"] . " " . $_POST["last_name"] . ", Order No " . $_POST["order_num"] . ", has made an online payment to Direct Lens in the amount of $" . $_POST[total_cost] . "\r\n";
	$message.="The order number is $_POST[order_num].\r\n";
	$headers = "From: " . $_POST[email] . "\r\n";
	$Headers .= "bcc: dbeaulieu@direct-lens.com\n";
	$headers .=	"Content-Type: 	text/plain; charset=\"iso-8859-1\"\r\n";
	$emailSent = mail("rco.daniel@gmail.com", "Order No $_POST[order_num]", "$message", "$headers");
	return true;
}
if($_SESSION["uniqid"]!=$_POST["uniqid"]){//check if this is a page refresh
	// select proper payment processor based on currency type
//	if(($_POST["currency"] == "US")&&(!isset($transData["approved"]))){
//		include ("processors/pfpro_settings.inc.php");
//		include ("processors/pfpro_functions.inc.php");
//		$transData=process_card();
//		if((is_array($transData))&&($transData["approved"])){
//			$transData["transAuthCode"]=$transData["AUTHCODE"];
//			$transData["transResultCode"]=$transData["RESULT"];
//			$transData["transRespReasonCode"]="";
//			$transData["transApprovalCode"]="Paypal";
//			$transData["transTransID"]=$transData["PNREF"];
//		}
//	}
//	elseif(($_POST["currency"])&&($_POST["currency"] != "US")&&(!isset($transData["approved"]))){
		include ("../processors/globalpay_settings.inc.php");
		include ("../processors/globalpay_functions.inc.php");


//		FOR LIVE CARD PROCESSING:  UNCOMMENT LINE BELOW AND COMMENT 2ND & 3RD LINES
		$transData=showCreditCardSaleResponse(); //PROCESS CREDIT CARD FUNCTION!!!
//		$transData=array();
//		$transData["approved"]=true;
		
		
		
		
		if((is_array($transData))&&($transData["approved"])){
			$transData["transAuthCode"]=$transData["AuthCode"];
			$transData["transResultCode"]=$transData["Result"];
			$transData["transRespReasonCode"]="";
			$transData["transApprovalCode"]="Global";
			$transData["transTransID"]=$transData["PNRef"];
			if($_POST["Master_Order_ID"]!="")
				$_SESSION["Master_Order_ID_Paid"]=true;//for orders using immediate credit card payment
		}
//	}
	$_SESSION["uniqid"]=$_POST["uniqid"];//set session uniqid to prevent refresh
}

if($transData["approved"]==true){// 1
	$result=mysql_query("SELECT curdate()");/* get today's date */
		$today=mysql_result($result,0,0);
	$order_num=$_POST["order_num"];
	$transData["cc_type"]=$_POST["cc_type"];
	$cclast4=substr($_POST["cc_no"], -4, 4);
	$transData["cclast4"]=$cclast4;
	$amount = $_POST["total_cost"];
	
	if(($_SESSION["order_numbers"])&&($_SESSION["orderCount"])){// 2
		$order_numbers=$_SESSION["order_numbers"];
		$orderCount=$_SESSION["orderCount"];
		for ($i = 1; $i <= $orderCount; $i++){ // 3 get order data
			$order_num=$order_numbers[$i][order_num];
			$shipCost=$order_numbers[$i][order_shipping_cost];
			$subAmount=$order_numbers[$i][order_total];
			if($_POST[pass_disc]!="")
				$discAmount=bcmul($_POST[pass_disc], $subAmount, 2);
			$subAmount2 = bcsub($subAmount, $discAmount, 2);
			$amount=bcadd($subAmount2, $shipCost, 2);
			$query="UPDATE payments SET pmt_marker='', pmt_amount='$amount', cctype='$_POST[cc_type]', cclast4='$cclast4', transResultCode='$transData[transResultCode]', transAuthCode='$transData[transAuthCode]', transApprovalCode='$transData[transApprovalCode]', transTransID='$transData[transTransID]', order_paid_in_full='y' WHERE order_num='$order_num'";
			$result=mysql_query($query)
				or die ('Could not update because: ' . mysql_error());
		}// 3 END FOR
	}// 2 END if($_SESSION["order_numbers"])
	elseif($_POST["Master_Order_ID"]==""){// 4 elseif($_POST["Master_Order_ID"]) - don't add to DB if payment is for basket orders
		$query="SELECT * from payments WHERE order_num = '$_POST[order_num]' AND user_id = '$user_id' AND pmt_marker = 'pending'";//find user's open orders
		$result=mysql_query($query)
			or die  ('I cannot select items because: ' . mysql_error());
		$orderCount=mysql_num_rows($result);
		if($orderCount==0)//if customer never tried to pay this order before
			$query="insert into payments (user_id, order_num, pmt_date, pmt_type, pmt_amount, cctype, cclast4, transResultCode, transAuthCode, transApprovalCode, transTransID, order_paid_in_full) values ('$_POST[user_id]', '$_POST[order_num]', '$today', 'credit card', '$amount', '$_POST[cc_type]', '$cclast4', '$transData[transResultCode]', '$transData[transAuthCode]', '$transData[transApprovalCode]', '$transData[transTransID]', 'y')";
		else //if customer tried to pay this order before and failed
			$query="UPDATE payments SET pmt_marker='', pmt_amount='$amount', cctype='$_POST[cc_type]', cclast4='$cclast4', transResultCode='$transData[transResultCode]', transAuthCode='$transData[transAuthCode]', transApprovalCode='$transData[transApprovalCode]', transTransID='$transData[transTransID]', order_paid_in_full='y' WHERE order_num='$order_num'";
			$result=mysql_query($query)
				or die ('Could not update because: ' . mysql_error());
	}// 4 END elseif($_POST["Master_Order_ID"])

	$_SESSION["order_numbers"]=array();
	unset($_SESSION["orderCount"]);
	if($_POST["Master_Order_ID"]!=""){//payment approved, go back and process the order(s).
		$_SESSION["transData"]=$transData;
		header("Location:processOrder_test.php");
		exit();
	}
	$transData = array();
	sendEmail();
	
	$pmtMessage = "<div class=\"Subheader\">
				   <div>Thank you. </div>
				   <br /><br />
				   <div class=\"tableSubHead\">
					Your payment has been submitted. You should receive an email confirmation
					momentarily detailing this payment. Please save it for future
					reference. 
				   </div>
				 </div>";
} else {// 1 if($transData["approved"])
	$pmtMessage = "<div class=\"Subheader\">
				   <div class=\"tableSubHead\" style=\"font-size: 20px\"><strong>
					There was a problem with your credit card payment. Your order has not been placed. Click the back button on your browser and try again.</strong>
				   </div>
				 </div>";
}// 1 END if($transData["approved"])
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php print $sitename;?></title>



   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />
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
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">
 <div class="loginText">
   <div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
   <?php print $_SESSION["sessionUser_Id"];}?></div>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header">
		     	Payment Confirmation 
		     </div></td>
		     		<td>&nbsp;</td>
		     </tr></table>
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