<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";
include "../includes/order_functions.inc.php";
include_once('includes/dl_order_functions.inc.php');

session_start();

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");

require('../Connections/sec_connect.inc.php');

$user_id=$_SESSION["sessionUser_Id"];
$result=mysql_query("SELECT curdate()");/* get today's date */
	$today=mysql_result($result,0,0);
unset($_SESSION["pmtOnly"]);

$query="SELECT * from accounts WHERE user_id='$user_id' LIMIT 1"; //TEAM LEADERS SECTIOn
$result=mysql_query($query)		or die  ('I cannot select items because: ' . mysql_error());
$listItem=mysql_fetch_array($result);
$usercount=mysql_num_rows($result);

// calculate current order(s) amount
$currentTotal=$_POST[totalPriceDsc]+$_POST[totalShipping];
			
		//var_dump($_POST);
		//exit();	
			
			
if ($_POST[ApplyCoupon]=='Valider ces commandes')
{
//Appliquer les coupons codes sur ces commandes 	
echo 'Application des coupons sur ces commandes en cours..Veuillez patienter..';

$ArrayCouponaAppliquer = $_POST[AppliquerCoupon];
	$Compteur = 0;
	foreach ($ArrayCouponaAppliquer as $value) {//FIND NEWEST FILE
		$Compteur += 1;
		//$time=ftp_mdtm($conn_id,$value);
		echo '<br>'.  $value  .'<br>' ;
		
	}//End Foreach




}//Fin appliquer les coupons			
			
				
// check for CREDIT LIMIT
$clQuery="SELECT * from acct_credit_limit WHERE cl_user_id='$user_id' LIMIT 1";
$clResult=mysql_query($clQuery)		or die  ('I cannot select credit limit because: ' . mysql_error());
$clCount=mysql_num_rows($clResult);
if($clCount){
	$clData=mysql_fetch_array($clResult);
	$balanceTotal = calc_current_balance($user_id);//total amount owed including statement & memo credits to check against credit limit
	$balanceTotal = bcadd($currentTotal, $balanceTotal, 2);
	if($balanceTotal >= $clData["cl_limit_amt"])
		$_SESSION["pmtOnly"] = true;
}//END IF
$_SESSION["currentTotal"]=money_format('%.2n',$currentTotal);
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
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ifc-masthead.jpg" width="1050" height="175" alt="MyBBGClub"/>
</div>
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
                <td colspan="2" bgcolor="#17A2D2" class="tableHead"><?php echo $adm_titlemast_acctinfo;?></td>
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
                <td colspan="2" bgcolor="#17A2D2" class="tableHead"><?php echo $adm_shipadd_txt;?></td>
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
                <td colspan="2" bgcolor="#17A2D2" class="tableHead"><?php echo $adm_order_txt;?></td>
              </tr>
              <tr >
                <td align="left" class="formCellNosides"><?php echo $adm_ponumber_txt;?> 
                  <?php 
			echo $_POST[po_num]; ?>
                </td>
                <td align="right" valign="middle"  class="formCellNosidesRA">&nbsp;</td>
              </tr>
              
              
              
                <?php  if ($NombredeFrameCommandes > 0){ ?>
               <tr >
                <td align="left" class="formCellNosides"><?php echo 'Total before taxes';?></td>
                <td width="100" align="right" valign="middle" class="formCellNosidesRA"><?php echo $CustomerCurrency; ?>
				<?php 
				$TaxesAmount = 0;//($_POST[totalPriceDsc]/1.05)*.05;
				$TotalBeforeTaxes=money_format('%.2n',$_POST[totalPriceDsc]-$TaxesAmount);
			
				if ($mylang == 'lang_french') {
				echo  $TotalBeforeTaxes. '$ ';
				}else{
				echo '$'. $TotalBeforeTaxes;
				}?></td>
              </tr>
              <?php } ?>
              
              
                  
              <?php /*?><?php  if ($NombredeFrameCommandes > 0){ ?>
               <tr >
                <td align="left" class="formCellNosides"><?php echo 'Taxes';?></td>
                <td width="100" align="right" valign="middle" class="formCellNosidesRA"><?php echo $CustomerCurrency; ?>
				<?php 
				$Taxes = ($TotalBeforeTaxes + $shipping )*.05;
				$Taxes=money_format('%.2n',$Taxes);
				
				if ($mylang == 'lang_french') {
				echo  $Taxes. '$ ';
				}else{
				echo '$'. $Taxes;
				}
			 ?></td>
              </tr>
              <?php } ?><?php */?>
              
              
              
              
               <tr >
                <td align="left" class="formCellNosides"><?php echo $adm_shipping_txt;?></td>
                <td align="right" valign="middle"  class="formCellNosidesRA"><?php 
				
				$shipping=money_format('%.2n',$_POST[totalShipping]+$ShippingFrameStock);
				
			if ($mylang == 'lang_french') {
				echo  $shipping. '$';
				}else{
				echo '$'. $shipping;
				}
				?>
           
            </td>
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
             
             
              <tr >
                <td align="left" class="Subheader"><?php echo $lbl_shopbasktotal_txt;?></td>
                <td width="100" align="right" valign="middle" class="total"><b>
				<?php 
			$_SESSION["currentTotal"] = $_SESSION["currentTotal"] + $ShippingFrameStock;
			$CURRENTTOTAL =  money_format('%.2n',$_SESSION["currentTotal"]);
			  if ($mylang == 'lang_french') {
				echo   $CURRENTTOTAL. '$';
				}else{
				echo '$ '.  $CURRENTTOTAL;
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
			echo 'value="Enregistrer la commande et charger mon compte';
			}else{
			echo 'value="Place Order and Bill My account';
			}?>" />
			<label></label>
			<input name="po_num" type="hidden" id="po_num" value="<?php echo $_POST[po_num];?>" />
			<input name="order_shipping_cost" type="hidden" id="order_shipping_cost" value="<?php echo $_POST[totalShipping];?>" />
			<input name="stock_quantity" type="hidden" id="stock_quantity" value="<?php echo $_POST[stock_quantity];?>" />
			<input name="frame_stock_quantity" type="hidden" id="frame_stock_quantity" value="<?php echo $_POST[frame_stock_quantity];?>" />
				  <input name="totalShippingRX" type="hidden" value="<?php echo $_POST[totalShippingRX];?>" />
				   <input name="totalShippingStock" type="hidden" value="<?php echo $_POST[totalShippingStock];?>" />
	
			  <input name="product_id" type="hidden" id="product_id" value="<?php echo $_POST[product_id];?>" />
			  <input name="quantity" type="hidden" id="quantity" value="<?php echo $_POST[quantity];?>" />
			<input name="order_date_processed" type="hidden" id="order_date_processed" value="<?php echo $today;?>" />
			</div>
	  </form>
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