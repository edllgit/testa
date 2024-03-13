<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";
include "config.inc.php";

session_start();

if($_SESSION["sessionUser_Id"]=="")

	header("Location:loginfail.php");

require('../Connections/sec_connect.inc.php');
include('../includes/dl_process_order_functions_test.inc.php');
include('includes/confirmation_functions.inc.php');
include('../includes/confirmation_functions_lab.inc.php');
include('../includes/fax_confirm_functions.inc.php');
include('../includes/fax_confirm_functions_lab.inc.php');
include("../includes/calc_functions.inc.php");
include("../includes/est_ship_date_functions.inc.php");
include ("../includes/order_functions.inc.php");

foreach ($_POST as $k => $v)
	$_SESSION[$k] = $v;
	
//create Master Order ID
if($_SESSION["Master_Order_ID"]=="")
	$_SESSION["Master_Order_ID"]=getNewMasterOrderID();

if($_POST['payOrder'] == "Place Order and Charge My Credit Card"){//process credit card payment BEFORE processing order(s)
	header("Location:getOrderCreditInfo_test.php?frompage=process_order");
	exit();
}

$totalShippingStock=$_SESSION[totalShippingStock];
$totalShippingRX=$_SESSION[totalShippingRX];
$po_num=$_SESSION[po_num];

if ($_SESSION[stock_quantity]!=0){

	$orderNum=getNewOrderNum();
	
	
	$order_shipping_method="Stock Shipping";
	addOrderNumShiptoOrder($_SESSION["sessionUser_Id"],$orderNum,$totalShippingStock,$order_shipping_method,$po_num);
	
	$main_lab_id=$_SESSION["sessionUserData"]["main_lab"];
	$query="SELECT lab_email,logo_file,fax_notify,fax from labs WHERE primary_key='$main_lab_id'";//LOOK UP MAIN LAB EMAIL ADDRESS
		$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
	$listItem=mysql_fetch_array($result);
	
	if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
		$faxNumArray= str_split($listItem[fax]);
		$numCount=count($faxNumArray);
		
		$faxNum="";
		for ($i=0;$i<$numCount;$i++){
			if (is_numeric($faxNumArray[$i])) {
				$faxNum.=$faxNumArray[$i];
			}
		}
	sendFaxStockConfirmation($listItem[lab_email],$listItem[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],$faxNum);//SEND FAX TO MAIN LAB
		}
	
	sendStockConfirmation($listItem[lab_email],$listItem[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"]);//SEND EMAIL TO MAIN LAB
	sendStockConfirmation($listItem[lab_email],$listItem[logo_file],$orderNum,$_SESSION["sessionUserData"]["email"],$_SESSION["sessionUser_Id"]);//SEND EMAIL TO CUSTOMER
	
	$gTotal=calculateTotal($orderNum);//ADD TOTAL TO ORDER TABLE
	addOrderTotal($orderNum,$gTotal);
//	if($_POST[payOrder] == "Place Order and Charge My Credit Card")
//		$addPmtMarker = add_Pmt_Marker($_SESSION["sessionUser_Id"], $orderNum);
	if($_SESSION["Master_Order_ID_Paid"]){
		$addPmtMarker = add_Pmt_Marker($_SESSION["sessionUser_Id"], $orderNum, $gTotal);
		$addOrderRef = add_Order_Ref($_SESSION["Master_Order_ID"], $orderNum);
	}
	
	//update the lab inventory for product being ordered
	include_once 'labAdmin/inc.functions.php';
	modifyInventory($orderNum);
	}


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
		       <div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
		       <?php print $_SESSION["sessionUser_Id"];?></div>
		     <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $adm_orderconf_txt;?> </div></td><td><div id="headerGraphic"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/order_graphic.gif" alt="prescription search" width="445" height="40" /></div></td></tr></table>
<p>&nbsp;</p>
			 <div class="Subheader">
			   <div><?php echo $adm_thanks_txt;?></div>
			   <br /><br />
			   <div class="tableSubHead"><?php echo $adm_submitted_txt;?></div>
			   
			 </div>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footerBox">
  
</div>
</div><!--END containter-->
</body>
</html>