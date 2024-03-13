<?php 
require_once(__DIR__.'/../../constants/aws.constant.php');
include "../../Connections/directlens.php";
include "../../includes/getlang.php";

session_start();
if($_SESSION["sessionUser_Id"]=="")

	header("Location:loginfail.php");

require('../../Connections/sec_connect.inc.php');


unset($pmtMessage);

$returnValues=$_GET;

	
if ($returnValues['code']=="000"){// SUCCESSFUL TRANSACTION


	$_SESSION["Master_Order_ID_Paid"]=true;//for orders using immediate credit card payment


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
	
	$_SESSION["transData"]=$transData;
	//sendEmail();
	
	header("Location:credit_card_confirm.php");
	exit();
	
	//header("Location:processOrder.php");
	//exit();

} else {// TRANSACTION FAILED
	$pmtMessage = "<div class=\"Subheader\">
				   <div class=\"tableSubHead\" style=\"font-size: 18px\"><strong>
					There was a problem with your credit card payment. Your order has not been placed. Click the back button on your browser and try again.</strong>
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
		     </tr></table><div class="loginText">User: <?php print $_SESSION["sessionUser_Id"];?></div>
			 <p>&nbsp;</p>
<?php print "$pmtMessage"; ?>			
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