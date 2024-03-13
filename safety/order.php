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
include "../includes/order_functions.inc.php";

//Démarrer la session
session_start();

if($_SESSION["sessionUser_Id"]=="")
header("Location:loginfail.php");


$user_id=$_SESSION["sessionUser_Id"];
$result=mysqli_query($con,"SELECT curdate()");/* get today's date */
//$today=mysql_result($result,0,0);
unset($_SESSION["pmtOnly"]);


$query="SELECT * from accounts WHERE user_id='$user_id' LIMIT 1"; //TEAM LEADERS SECTIOn
$result=mysqli_query($con,$query)	or die  ('I cannot select items because: ' . mysqli_error($con));
		
$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
$usercount=mysqli_num_rows($result);


$queryNombreFrameCommandes  = "SELECT * from orders WHERE user_id = '$user_id' and order_num = -1 and order_product_type = 'frame_stock_tray'";
$resultNombreFrameCommandes = mysqli_query($con,$queryNombreFrameCommandes)		or die  ('I cannot select items because: ' . mysqli_error($con));
$CountNombreFrameCommandes  = mysqli_num_rows($resultNombreFrameCommandes);
if ($CountNombreFrameCommandes  > 0){
	$NombredeFrameCommandes = 0;
	while ($DataNombreFrameCommandes=mysqli_fetch_array($resultNombreFrameCommandes,MYSQLI_ASSOC)){
	$NombredeFrameCommandes =  $NombredeFrameCommandes + $DataNombreFrameCommandes[order_quantity];
	
	
	};	
}


//Il peut y avoir un seul tuple, mais ce tuple peut contenir plusieurs frames, il faut faire le total et le comparer ensuite
if (($NombredeFrameCommandes >0) && ($NombredeFrameCommandes < 10))
$ShippingFrameStock = 1.95;
elseif($CountBasketFrame > 9)
$ShippingFrameStock = 0;
elseif($CountBasketFrame > 0)
$ShippingFrameStock = 1.95;
elseif($CountBasketFrame == 0)
$ShippingFrameStock = 0;


// calculate current order(s) amount
$currentTotal=$_POST[totalPriceDsc]+$_POST[totalShipping];
				
// check for CREDIT LIMIT
$clQuery="SELECT * from acct_credit_limit WHERE cl_user_id='$user_id' LIMIT 1";
$clResult=mysqli_query($con,$clQuery) or die  ('I cannot select credit limit because: ' . mysqli_error($con));
$clCount=mysqli_num_rows($clResult);
if($clCount){
	$clData=mysqli_fetch_array($clResult,MYSQLI_ASSOC);
	$balanceTotal = 0;//calc_current_balance($user_id);//total amount owed including statement & memo credits to check against credit limit
	$balanceTotal = bcadd($currentTotal, $balanceTotal, 2);
	if($balanceTotal >= $clData["cl_limit_amt"])
		$_SESSION["pmtOnly"] = true;
}//END IF
$_SESSION["currentTotal"]=money_format('%.2n',$currentTotal +$ShippingFrameStock);
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
<div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $adm_plorder_txt;?></div></td><td><div id="headerGraphic">


<?php if ($mylang == 'lang_french'){ ?>
 <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/order_graphic.gif" alt="prescription search" width="445" height="40" />
   <?php }else{ ?>
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/order_graphic.gif" alt="prescription search" width="445" height="40" />
  <?php } ?>
</div></td></tr></table>
		    <form id="order" name="order" method="post" action="processOrder.php" onsubmit="document.getElementById('billOrder').disabled = 1;">
			<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="2" bgcolor="#ef802f" class="tableHead"><?php echo $adm_titlemast_acctinfo;?></td>
              </tr>
              <tr >
                <td width="130" align="right" class="formCellNosides"><?php echo $adm_nameonaccount_txt;?></td>
                <td width="520" class="formCellNosides"><strong><?php echo "$listItem[title] $listItem[first_name] $listItem[last_name]";?></strong></td>
                </tr>
              <tr >
                <td align="right" class="formCellNosides"><?php echo $adm_acctnumc_txt;?></td>
                <td class="formCellNosides"><strong><?php echo "$listItem[account_num]";?></strong></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides"><?php echo $adm_company_txt;?></td>
                <td width="520" class="formCellNosides"><strong><?php echo 
$listItem[company];?></strong> </td>
                </tr>
              <tr >
                <td align="right" class="formCellNosides"><?php echo $adm_buygrp_txt;?></td>
                <td class="formCellNosides"><strong><?php echo 
$_SESSION["sessionUserData"]["bg_name"];?></strong></td>
              </tr>
            </table>
			<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="2" bgcolor="#ef802f" class="tableHead"><?php echo $adm_shipadd_txt;?></td>
              </tr>
              <tr >
                <td width="130" align="right" class="formCellNosides"><?php echo $adm_addy1_txt;?></td>
                <td width="520" class="formCellNosides"><strong><?php echo 
$listItem[ship_address1];?></strong></td>
              </tr>  <tr >
                <td width="130" align="right" class="formCellNosides"><?php echo $adm_addy2_txt;?></td>
                <td width="520" class="formCellNosides"><strong><?php echo 
$listItem[ship_address2];?></strong></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides"><?php echo $adm_city_txt;?></td>
                <td width="520" class="formCellNosides"><strong><?php echo $listItem[ship_city];?></strong></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides"><?php echo $adm_state_txt;?></td>
                <td width="520" class="formCellNosides"><strong><?php echo 
$listItem[ship_state];?></strong> </td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides"><?php echo $adm_pcode_txt;?></td>
                <td class="formCellNosides"><strong><?php echo 
$listItem[ship_zip];?></strong></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides"><?php echo $adm_cntry_txt;?></td>
                <td class="formCellNosides"><strong><?php echo 
$listItem[ship_country];?></strong></td>
              </tr>
            </table>
			<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">


              <tr >
                <td colspan="2" bgcolor="#ef802f" class="tableHead"><?php echo $adm_order_txt;?></td>
              </tr>
              <tr >
                <td align="left" class="formCellNosides"><?php echo $adm_ponumber_txt;?> 
                  <?php 
			echo $_POST[po_num]; ?>
                </td>
                <td align="right" valign="middle"  class="formCellNosidesRA">&nbsp;</td>
              </tr>
              <tr >
                <td align="left" class="formCellNosides"><?php echo $adm_subtotal_txt;?> </td>
                <td align="right" valign="middle"  class="formCellNosidesRA"> <?php 
				$total=$_POST[totalPriceDsc];
				$total=money_format('%.2n',$total);
				
				if ($mylang == 'lang_french') {
				echo  $total. '$ ';
				}else{
				echo '$ '. $total;
				}
				?>

            </td>
              </tr>
              <!--<tr >
                <td align="left" class="formCellNosides">Sales Tax</td>
                <td align="right" valign="middle"  class="formCellNosidesRA">$
                    <?php //$tax=$_POST[totalPriceDsc]*.06;
				//$tax=money_format('%.2n',$tax);
			//echo $tax; 
			?></td>
              </tr>-->
              <tr >
                <td align="left" class="formCellNosides"><?php echo $adm_shipping_txt;?></td>
                <td align="right" valign="middle"  class="formCellNosidesRA"><?php 
				
				$shipping=money_format('%.2n',$_POST[totalShipping]+$ShippingFrameStock);
				
			if ($mylang == 'lang_french') {
				echo  $shipping. '$ ';
				}else{
				echo '$ '. $shipping;
				}
				?>
           
            </td>
              </tr>
              <tr >
                <td align="left" class="Subheader"><?php echo $lbl_shopbasktotal_txt;?></td>
                <td width="100" align="right" valign="middle" class="total"><b>
				<?php 
			
			  if ($mylang == 'lang_french') {
				echo   $_SESSION["currentTotal"]. '$';
				}else{
				echo '$ '.  $_SESSION["currentTotal"];
				}
				?>
            
            </b></td>
              </tr>
            </table>
			<div align="center" style="margin:11px">
            
			<?php //if((!$listItem["credit_hold"]) && (!$_SESSION["pmtOnly"]))
			//if customer is NOT on credit hold or credit limit, display bill my account button
			echo "<input name=\"billOrder\" id=\"billOrder\" type=\"submit\" value=\"";
			if ($mylang == 'lang_french')
			{
			echo 'Page précédente';
			}else{
			echo 'Go Back';
			}

			
			 echo "\" />&nbsp;&nbsp;&nbsp;&nbsp;"; ?>
            
            
            
			<input name="payOrder" type="submit" 
			<?php if ($mylang == 'lang_french')
			{
			echo 'value="Enregistrer la commande et charger mon compte"';
			}else{
			echo 'value="Place Order and Bill My account"';
			}?> />
			<label></label>
			<input name="po_num" type="hidden" id="po_num" value="<?php echo $_POST[po_num];?>" />
			<input name="order_shipping_cost" type="hidden" id="order_shipping_cost" value="<?php echo $_POST[totalShipping];?>" />
			<input name="stock_quantity" type="hidden" id="stock_quantity" value="<?php echo $_POST[stock_quantity];?>" />
			
				  <input name="totalShippingRX" type="hidden" value="<?php echo $_POST[totalShippingRX];?>" />
				   <input name="totalShippingStock" type="hidden" value="<?php echo $_POST[totalShippingStock];?>" />
	
			  <input name="product_id" type="hidden" id="product_id" value="<?php echo $_POST[product_id];?>" />
			  <input name="quantity" type="hidden" id="quantity" value="<?php echo $_POST[quantity];?>" />
			<input name="order_date_processed" type="hidden" id="order_date_processed" value="<?php echo $today;?>" />
			</div>
	  </form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
 <?php include("footer.inc.php"); ?>
</div><!--END containter-->
</body>
</html>