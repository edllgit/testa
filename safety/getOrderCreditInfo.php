<?php 

require('../Connections/sec_connect.inc.php');
include "../includes/getlang.php";

session_start();
if($_SESSION["sessionUser_Id"]=="")
header("Location:loginfail.php");
?><?php
session_start();

$result=mysql_query("SELECT curdate()");/* get today's date */
	$today=mysql_result($result,0,0);

$user_id = $_SESSION["sessionUser_Id"];
	
if($_GET[frompage]=="process_order"){

}

$result=mysql_query("SELECT DATE_ADD('$_SESSION[order_date_processed]', interval 1 month)");/* add 1 month to order_processed_date */
$duedate=mysql_result($result,0,0);

$result=mysql_query("SELECT DATE_SUB('$duedate', interval 15 day)");/* add 15 days to order_processed_date */
$discountdate_15=mysql_result($result,0,0);

$result=mysql_query("SELECT DATE_SUB('$duedate', interval 10 day)");/* add 10 days to order_processed_date */
$discountdate_10=mysql_result($result,0,0);

$item_total=bcsub($_SESSION["currentTotal"], $_SESSION["order_shipping_cost"], 2);

/*
if($discountdate_15 >= $today){
	$discountamt=bcmul('.02', $item_total, 2);
	$pass_disc=".02";
	$discount = "2%";
}
elseif($discountdate_10 >= $today){
	$discountamt=bcmul('.01', $item_total, 2);
	$pass_disc=".01";
	$discount = "1%";
}
*/
//REVISED FUNCTIONS TO TEMPORARILY ELIMINATE DISCOUNT

if($discountdate_15 >= $today){
	$discountamt=bcmul('.00', $item_total, 2);
	$pass_disc=".00";
	$discount = "0%";
}
elseif($discountdate_10 >= $today){
	$discountamt=bcmul('.00', $item_total, 2);
	$pass_disc=".00";
	$discount = "0%";
}
$discounted_total_cost = bcsub($_SESSION["currentTotal"], $discountamt, 2);

$uniqid = rand(100000, 999999);//for refresh test only
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
<form action="reviewOrderCreditInfo.php" method="post" name="pmtForm" id="pmtForm" onSubmit="return formCheck(this);">
		  	<div class="header">
		  	Adresse de livraison</div>
		  	<div class="loginText">Usager :
<?php 
			if ($_SESSION["sessionUser_Id"]!=""){
			print $_SESSION["sessionUser_Id"];}
			else{
			print "not logged in";}?></div>
  <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
      <tr >
                <td colspan="4" bgcolor="#17A2D2" class="tableHead">&nbsp;</td>
                </tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
					    ID de la Commande
				</div></td>
              		<td align="left" class="formCellNosides"><span class="Subheader"><?php print "$_SESSION[Master_Order_ID]"; ?></span>
              			<input type="hidden" name="Master_Order_ID" value="<?php print "$_SESSION[Master_Order_ID]"; ?>">
              		<td align="left" nowrap class="formCellNosides"><div align="right">
              		Commande sans la livraison
              		</div></td>
              		<td align="left" class="formCellNosides"><span class="Subheader">$<?php print "$item_total"; ?></span></td>
              </tr>
              <tr>
              	<td align="left"  class="formCellNosides"><div align="right">
              		Total du montant de la commande</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader">$<?php print $_SESSION["currentTotal"] . " " . $_SESSION["sessionUserData"]["currency"]; ?></span>
              		<input type="hidden" name="currency" value="<?php print $_SESSION["sessionUserData"]["currency"]; ?>"></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Rabais de paiment rapide
              		</div></td>
              	<td align="left" class="formCellNosides"><?php if(!$discountamt) print "Not Eligible"; else print "<span class=\"Subheader\">\$$discountamt</span> ($discount disc)"; ?>
              		<input type="hidden" name="pass_disc" value="<?php print "$pass_disc"; ?>"></td>
              </tr>
              <tr>
              	<td align="left"  class="formCellNosides"><div align="right">
              		Total du montant chargé
              		</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader">$<?php print "$discounted_total_cost " . " " . $_SESSION["sessionUserData"]["currency"]; ?>
              		<input type="hidden" name="total_cost" value="<?php print "$discounted_total_cost"; ?>">
              	</span></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Frais de livraison
              		</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader"><?php $shipping=money_format('%.2n',$_SESSION["order_shipping_cost"]); print "\$$shipping"; ?></span></td>
              	</tr>
              <tr>
                <td align="left"  class="formCellNosides"><div align="right">
                	Prénom 
                	</div></td>
                <td align="left" class="formCellNosides"><input name="first_name" type="text" id="first_name" size="20" value="<?php print $_SESSION["sessionUserData"]["first_name"]; ?>"></td>
                <td align="left" nowrap class="formCellNosides"><div align="right">
                	Nom de famille
                	</div></td>
              	<td align="left" class="formCellNosides"><input name="last_name" type="text" id="last_name" size="20" value="<?php print $_SESSION["sessionUserData"]["last_name"]; ?>"></td>
              </tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Téléphone
					</div></td>
              	<td align="left" class="formCellNosides"><input name="phone" type="text" id="phone" size="20" value="<?php print $_SESSION["sessionUserData"]["phone"]; ?>"></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Autre téléphonne
              		</div></td>
              	<td align="left" class="formCellNosides"><input name="other_phone" type="text" id="other_phone" size="20" value="<?php print $_SESSION["sessionUserData"]["other_phone"]; ?>"></td>
              	</tr>
              <tr>
              	<td align="left"  class="formCellNosides"><div align="right">
              		Mail
              		</div></td>
              	<td colspan="3" align="left" class="formCellNosides"><input name="email" type="text" id="email" size="50" value="<?php print $_SESSION["sessionUserData"]["email"]; ?>"></td>
              	</tr>
              <tr bgcolor="#17A2D2">
					<td height="30" colspan="4" align="left"  class="formCellNosides"><div align="center" class="tableHead">Adresse de livraison</div></td>	
					</tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Adresse 1
					</div></td>
              	<td align="left" class="formCellNosides"><input name="address1" type="text" id="address1" size="20" value="<?php print $_SESSION["sessionUserData"]["bill_address1"]; ?>"></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Adresse 2
           		</div></td>
              	<td align="left" class="formCellNosides"><input name="address2" type="text" id="address2" size="20" value="<?php print $_SESSION["sessionUserData"]["bill_address2"]; ?>"></td>
              	</tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Ville</div></td>
              	<td align="left" class="formCellNosides"><input name="city" type="text" id="city" size="20" value="<?php print $_SESSION["sessionUserData"]["bill_city"]; ?>"></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Région
              	</div>              	</td>
              	<td align="left" class="formCellNosides"><input type="text" id="state" size="20" name="state"/>
</td>
              </tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Zip/Code
					</div></td>
              	<td align="left" class="formCellNosides"><input name="zip" type="text" id="zip" size="20" value="<?php print $_SESSION["sessionUserData"]["bill_zip"]; ?>"></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Pays
              		</div></td>
              	<td align="left" class="formCellNosides"><select name = "country" id="country">

					<option value = "FR" <?php if($_SESSION["sessionUserData"]["bill_country"]=="FR") print " selected"; ?>>France</option>
				</select></td>
              </tr>
            </table>
		    <div align="center" style="margin:11px">
	      	  <p><input type="hidden" name="uniqid" id="uniqid" value="<?php print "$uniqid"; ?>" />

		      		<input name="Reset" type="reset" class="formText" value="Annuler">
		      		&nbsp;
		      		<input name="submitPmt" type="submit" class="formText" value="Continuer">
	      	  </p>
      	</div>
	  </form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
 <?php include("footer.inc.php"); ?>
</div><!--END containter-->
</body>
</html>