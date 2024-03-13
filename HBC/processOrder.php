<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//Inclusions
require_once(__DIR__.'/../constants/aws.constant.php');
include "../connexion_hbc.inc.php";
include "../includes/getlang.php";
include "config.inc.php";
include_once('includes/dl_order_functions.inc.php');
include_once('includes/dl_ex_prod_functions.inc.php');
include('includes/hbc_process_order_functions.inc.php');
include('includes/phpmailer_email_functions.inc.php');
?>
<?php
session_start();
//echo '<br>$_SESSION[frame_stock_quantity]: '.$_SESSION[frame_stock_quantity];
if($_SESSION["sessionUser_Id"]=="")

	header("Location:loginfail.php");

foreach ($_POST as $k => $v)
	$_SESSION[$k] = $v;
	

$totalShippingStock	= $_SESSION[totalShippingStock];
$totalShippingRX	= $_SESSION[totalShippingRX];
$po_num				= $_SESSION[po_num];

$order_shipping_method="RX Shipping";
addOrderNumShiptoOrderExclusive($_SESSION["sessionUser_Id"],$totalShippingRX,$order_shipping_method,$po_num);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $sitename;?></title>

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
   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />

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
		       <div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
		       
		     <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $adm_orderconf_txt;?> </div></td><td><div id="headerGraphic"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/order_graphic.gif" alt="prescription search" width="445" height="40" /></div></td></tr></table>
<p>&nbsp;</p>
			 <div class="Subheader">
			   <div><?php echo $adm_thanks_txt;?></div>
			   <br /><br />
			   <div class="tableSubHead"><?php echo $adm_submitted_txt_ifcfr;?></div>
			 </div>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
</body>
</html>
