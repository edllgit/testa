<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//Inclusions
require_once(__DIR__.'/../constants/aws.constant.php');
include "../sec_connectEDLL.inc.php";
include "config.inc.php";
include "../includes/getlang.php";
//Autres inclusions
include('includes/dl_process_order_functions.inc.php');
include('includes/confirmation_functions.inc.php');
include('../includes/confirmation_functions_lab.inc.php');
//include('../includes/fax_confirm_functions.inc.php');
//include('../includes/fax_confirm_functions_lab.inc.php');
include("../includes/calc_functions.inc.php");
include("../includes/est_ship_date_functions.inc.php");
include ("../includes/order_functions.inc.php");
include ("../includes/pmt_confirmation.inc.php");
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');

//Démarrer la session
session_start();

if($_SESSION["sessionUser_Id"]=="")
header("Location:loginfail.php");


foreach ($_POST as $k => $v)
	$_SESSION[$k] = $v;
	
//create Master Order ID
if($_SESSION["Master_Order_ID"]=="")
	$_SESSION["Master_Order_ID"]=getNewMasterOrderID();

/*if($_POST['payOrder'] != ""){//process credit card payment BEFORE processing order(s)
	//header("Location:getOrderCreditInfo.php?frompage=process_order");
	exit();
}*/
if($_POST['payOrder'] == ""){//process credit card payment BEFORE processing order(s)
	header("Location:basket.php");
	exit();
}

$totalShippingStock=$_SESSION[totalShippingStock];
$totalShippingRX=$_SESSION[totalShippingRX];
$po_num=$_SESSION[po_num];

$user_id= $_SESSION["sessionUser_Id"];
$order_shipping_method="RX Shipping";
addOrderNumShiptoOrderExclusive($_SESSION["sessionUser_Id"],$totalShippingRX,$order_shipping_method,$po_num);

//zero out session vars
$_SESSION["transData"]=array();
unset($_SESSION["Master_Order_ID_Paid"]);
unset($_SESSION["Master_Order_ID"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php print $sitename;?></title>

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
		       <div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;
               <a href='logout.php'><?php echo $lbl_btn_logout;?></a></div></div>
		     <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $adm_orderconf_txt;?> </div></td><td><div id="headerGraphic"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/order_graphic.gif" alt="prescription search" width="445" height="40" /></div></td></tr></table>
<p>&nbsp;</p>
			 <div class="Subheader">
			   <div><?php echo $adm_thanks_txt;?></div>
			   <br /><br />
			   <div class="tableSubHead">
                <?php 	if ($mylang == 'lang_french') {  ?>
                    <p>Votre commande a été soumise. Vous recevrez une confirmation par courriel contenant le détail de votre commande. 
                    Veuillez la conserver comme référence.</p>            
                <?php  	}else{ ?>
					<p>Your order has been submitted. You will receive a confirmation email containing the details of your order.
                     Please keep it as a reference.</p>
                <?php 	} ?> 
               </div>
			   
			 </div>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
 <?php include("footer.inc.php"); ?>
</div><!--END containter-->
</body>
</html>