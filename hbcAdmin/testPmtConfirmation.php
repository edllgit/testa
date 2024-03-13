<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";

session_start();


require('../Connections/sec_connect.inc.php');
include ("../includes/pmt_confirmation.inc.php");
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');

	
	$firstname = "Charles";	
	$lastname = "Dumais";	
	$ordernum = 1136282;
	$clientemail = "dbeaulieu@direct-lens.com";
	
	//$msg=sendPmtConfirmEmail($gTotal, 	$firstname, $lastname, $orderNum, $_SESSION['sessionUserData']['email']);//SEND PMT CONFIRMATION
	$msg=sendPmtConfirmEmailSES($gTotal, 	$firstname, $lastname, $ordernum, $clientemail);//SEND PMT CONFIRMATION		
	
	echo '<br>'. $msg;
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Lensnet Club</title>

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

</head>

<body>
<div id="container">
  <div id="masthead">
  <img src="http://www.direct-lens.com/lensnet/images/ln-masthead.jpg" width="1050" height="165" alt="LensNet Club"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php  include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">
		     <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $adm_orderconf_txt;?> </div></td><td><div id="headerGraphic"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/order_graphic.gif" alt="prescription search" width="445" height="40" /></div></td></tr></table><div class="loginText">User: <?php echo $_SESSION["sessionUser_Id"];?></div>
			 <p>&nbsp;</p>
			 <div class="Subheader">
			   <div><?php echo $adm_thanks_txt;?></div>
			   <br /><br />
			   <div class="tableSubHead"><?php echo $adm_submitted_txt;?></div>
			   
			 </div>
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