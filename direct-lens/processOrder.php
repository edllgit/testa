<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//Démarrer la session
session_start();
//Inclusions
require_once(__DIR__.'/../constants/aws.constant.php');
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
include "includes/order_functions.inc.php";
include('includes/dl_process_order_functions.inc.php');
include('includes/confirmation_functions.inc.php');
include('includes/confirmation_functions_lab.inc.php');
//include('includes/fax_confirm_functions.inc.php');
//include('includes/fax_confirm_functions_lab.inc.php');
include("includes/calc_functions.inc.php");
include("includes/est_ship_date_functions.inc.php");
include ("includes/pmt_confirmation.inc.php");
include('includes/phpmailer_email_functions.inc.php');
require_once('includes/class.ses.php');

if($_SESSION["sessionUser_Id"]=="")
header("Location:loginfail.php");




foreach ($_POST as $k => $v)
	$_SESSION[$k] = $v;
	
//create Master Order ID
if($_SESSION["Master_Order_ID"]=="")
	$_SESSION["Master_Order_ID"]=getNewMasterOrderID();

if($_POST['payOrder'] != ""){//process credit card payment BEFORE processing order(s)
	header("Location:getOrderCreditInfo.php?frompage=process_order");
	exit();
}

$totalShippingStock=$_SESSION[totalShippingStock];
$totalShippingRX=$_SESSION[totalShippingRX];
$po_num=$_SESSION[po_num];


//Lenses Stock orders
if ($_SESSION[stock_quantity]!=0){

	$orderNum=getNewOrderNum();
	
	
	$order_shipping_method="Stock Shipping";
	addOrderNumShiptoOrder($_SESSION["sessionUser_Id"],$orderNum,$totalShippingStock,$order_shipping_method,$po_num);
	
	$main_lab_id=$_SESSION["sessionUserData"]["main_lab"];
	$query="SELECT lab_email,logo_file,fax_notify,fax from labs WHERE primary_key='$main_lab_id'";//LOOK UP MAIN LAB EMAIL ADDRESS
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
		$faxNumArray= str_split($listItem[fax]);
		$numCount=count($faxNumArray);
		
		$faxNum="";
		for ($i=0;$i<$numCount;$i++){
			if (is_numeric($faxNumArray[$i])) {
				$faxNum.=$faxNumArray[$i];
			}
		}
	
		}
	
	//sendStockConfirmation($listItem[lab_email],$listItem[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"]);//SEND EMAIL TO MAIN LAB
	
	sendStockConfirmation($listItem[lab_email],$listItem[logo_file],$orderNum,$_SESSION["sessionUserData"]["email"],$_SESSION["sessionUser_Id"]);
	//SEND EMAIL TO CUSTOMER
	
	$gTotal=calculateTotal($orderNum);//ADD TOTAL TO ORDER TABLE
	addOrderTotal($orderNum,$gTotal);
//	if($_POST[payOrder] == "Place Order and Charge My Credit Card")
//		$addPmtMarker = add_Pmt_Marker($_SESSION["sessionUser_Id"], $orderNum);
	if($_SESSION["Master_Order_ID_Paid"]){
		$addPmtMarker = add_Pmt_Marker($_SESSION["sessionUser_Id"], $orderNum, $gTotal);
		$addOrderRef = add_Order_Ref($_SESSION["Master_Order_ID"], $orderNum);
		
				
		$discAmount=bcmul(.02, $gTotal, 2);
		$subAmount2 = bcsub($gTotal, $discAmount, 2);
		$amount=bcadd($subAmount2, $shipCost, 2);
	
		$msg=sendPmtConfirmEmail($gTotal, $_SESSION['sessionUserData']['first_name'], $_SESSION['sessionUserData']['last_name'], $orderNum, $_SESSION['sessionUserData']['email']);//SEND PMT CONFIRMATION
		
	}
	
	//update the lab inventory for product being ordered
	//include_once '../labAdmin/inc.functions.php';
	//modifyInventory($orderNum);
	}
	
	
	

	//Frames Stock orders
if ($_SESSION[frame_stock_quantity]!=0){

	$orderNum=getNewOrderNum();// Get new order_num for stock frames order
	//echo '<br>Order Num: '. $orderNum;
	$order_shipping_method="Frame Shipping";
	$total_shipping_frames = 1.95;
	addOrderNumShiptoFrameOrder($_SESSION["sessionUser_Id"],$orderNum,$total_shipping_frames,$order_shipping_method,$po_num);

	$main_lab_id=$_SESSION["sessionUserData"]["main_lab"];
	$query="SELECT lab_email,logo_file,fax_notify,fax from labs WHERE primary_key=21";//LOOK UP MAIN LAB EMAIL ADDRESS
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	if ($listItem[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
		$faxNumArray= str_split($listItem[fax]);
		$numCount=count($faxNumArray);
		
		$faxNum="";
		for ($i=0;$i<$numCount;$i++){
			if (is_numeric($faxNumArray[$i])) {
				$faxNum.=$faxNumArray[$i];
			}
		}
	//sendFaxStockConfirmation($listItem[lab_email],$listItem[logo_file],$orderNum,"directl-config@interpage.net",$_SESSION["sessionUser_Id"],$faxNum);//SEND FAX TO MAIN LAB
	}//End if fax notify
	sendFrameStockConfirmation($listItem[lab_email],$listItem[logo_file],$orderNum,$_SESSION["sessionUserData"]["email"],$_SESSION["sessionUser_Id"]);//SEND EMAIL TO TROIS-RIVIERES
	//sendFrameStockConfirmation($listItem[lab_email],$listItem[logo_file],$orderNum,$email,$_SESSION["sessionUser_Id"]);//SEND EMAIL TO TROIS-RIVIERES
	
	
	//sendStockConfirmation($listItem[lab_email],$listItem[logo_file],$orderNum,$listItem[lab_email],$_SESSION["sessionUser_Id"]);//SEND EMAIL TO MAIN LAB
	//sendStockConfirmation($listItem[lab_email],$listItem[logo_file],$orderNum,$_SESSION["sessionUserData"]["email"],$_SESSION["sessionUser_Id"]);//SEND EMAIL TO CUSTOMER
	
	$gTotal=calculateTotal($orderNum);//ADD TOTAL TO ORDER TABLE
	addOrderTotal($orderNum,$gTotal);

	if($_POST[payOrder] == "Place Order and Charge My Credit Card")
		$addPmtMarker = add_Pmt_Marker($_SESSION["sessionUser_Id"], $orderNum);
	if($_SESSION["Master_Order_ID_Paid"]){
		$addPmtMarker = add_Pmt_Marker($_SESSION["sessionUser_Id"], $orderNum, $gTotal);
		$addOrderRef = add_Order_Ref($_SESSION["Master_Order_ID"], $orderNum);
			
		$discAmount=bcmul(.02, $gTotal, 2);
		$subAmount2 = bcsub($gTotal, $discAmount, 2);
		$amount=bcadd($subAmount2, $shipCost, 2);
	
		$msg=sendPmtConfirmEmail($gTotal, $_SESSION['sessionUserData']['first_name'], $_SESSION['sessionUserData']['last_name'], $orderNum, $_SESSION['sessionUserData']['email']);//SEND PMT CONFIRMATION
		
	}
	
	//update the lab inventory for product being ordered
	//include_once 'labAdmin/inc.functions.php';
	//modifyInventory($orderNum);
	}//END FRAMES STOCK	
	



//Rx Orders 
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
<title>Direct-Lens &mdash; Order Confirmation</title>
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
<link href="dl.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><table width="917" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/top_piece.jpg" alt="Direct Lens" width="917" height="158" /></td>
      </tr>
      <tr>
        <td background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/mid_sect_bg_long.jpg" bgcolor="#FFFFFF">
<table width="900" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="215" valign="top"> <div id="leftColumn">
		      <?php 
	include("includes/sideNav.inc.php");
	?>
        </div></td>
    <td width="685" valign="top">
		     <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $adm_orderconf_txt;?> </div></td><td><div id="headerGraphic"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/order_graphic.gif" alt="prescription search" width="445" height="40" /></div></td></tr></table><div class="loginText">User: <?php echo $_SESSION["sessionUser_Id"];?></div>
			 <p>&nbsp;</p>
			 <div class="Subheader">
			   <div><?php echo $adm_thanks_txt;?></div>
			   <br /><br />
			   <div class="tableSubHead">
			   <?php 
			   echo $adm_submitted_txt;
			   ?></div>
			   
			 </div>
			
			</td></tr></table>
		  
		  <p>&nbsp;</p></td>
      </tr>
      <tr>
        <td background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/bot_piece_bg.jpg"><p>&nbsp;</p></br>
          </td>
      </tr>
    </table></td>
  </tr>
</table>

</body>
</html>