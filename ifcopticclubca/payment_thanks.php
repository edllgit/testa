<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";

session_start();
if($_SESSION["sessionUser_Id"]=="")

	header("Location:loginfail.php");

require('../Connections/sec_connect.inc.php');


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
	header("Location:processOrder.php");
	exit();

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
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ifc-masthead.jpg" width="1050" height="175" alt="MyBBGClub"/>
</div>
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
<div id="footer1">
  
</div>
</div><!--END containter-->
</body>
</html>
<?php
mysql_free_result($languages);

mysql_free_result($languagetext);
?>