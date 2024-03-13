<?php 

require('../Connections/sec_connect.inc.php');
include "../includes/getlang.php";

session_start();
if($_SESSION["sessionUser_Id"]=="")
header("Location:loginfail.php");

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

<!--
VISA TEST NUMBER 4788250000028291
https://safe.sandbox-gtpaysecure.net/securepayments/a1/cc_collection.php
SANDBOX TEST MODE-->

<!--
https://vision.gtpaysecure.net/securepayments/a1/cc_collection.php  US
https://visionca.gtpaysecure.net/securepayments/a1/cc_collection.php  CA
LIVE MODE-->

<?php if ($_SESSION["sessionUserData"]["currency"]=="US"){?>

 <form name="form1" method="post" action="https://vision.gtpaysecure.net/securepayments/a1/cc_collection.php">

<?php }else if ($_SESSION["sessionUserData"]["currency"]=="CA"){?>

 <form name="form1" method="post" action="https://visionca.gtpaysecure.net/securepayments/a1/cc_collection.php">

<?php }?>



		  	<div class="header">
		  	Révision</div>
		  	<div class="loginText">Usager: 
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
					<td width="165" align="left"  class="formCellNosides"><div align="right">
						 ID de la commande
				</div></td>
              		<td width="229" align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php print "$_SESSION[Master_Order_ID]"; ?></span>
              
              		<td width="149" align="left" nowrap class="formCellNosides"><div align="right">Total du montant chargé</div></td>
              		<td width="201" align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold">$<?php print "$discounted_total_cost " . " " . $_SESSION["sessionUserData"]["currency"]; ?>
                   </span></td>
              </tr>
              <tr>
              	<td align="left"  class="formCellNosides">&nbsp;</td>
              	<td align="left" class="formCellNosides">&nbsp;</td>
              	<td align="left" nowrap class="formCellNosides">&nbsp;</td>
              	<td align="left" class="formCellNosides">&nbsp;</td>
           	  </tr>
              <tr>
                <td align="left"  class="formCellNosides"><div align="right">Prénom</div></td>
                <td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php print $_SESSION["sessionUserData"]["first_name"]; ?></span></td>
                <td align="left" nowrap class="formCellNosides"><div align="right">
                	Nom de famille
                	</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php print $_SESSION["sessionUserData"]["last_name"]; ?></span></td>
              </tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Téléphone
					</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php print $_SESSION["sessionUserData"]["phone"]; ?></span></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Autre téléphone</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php print $_SESSION["sessionUserData"]["other_phone"]; ?></span></td>
              	</tr>
              <tr>
              	<td align="left"  class="formCellNosides"><div align="right">
              		Mail
              		</div></td>
              	<td colspan="3" align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php print $_SESSION["sessionUserData"]["email"]; ?></span></td>
              	</tr>
              <tr bgcolor="#000099">
					<td height="30" colspan="4" align="left" bgcolor="#17A2D2"  class="formCellNosides"><div align="center" class="tableHead">
												Billing Address						
		</div></td>	
					</tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Adresse 1
					</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php print $_SESSION["sessionUserData"]["bill_address1"]; ?></span></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Adresse 2
              		</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php print $_SESSION["sessionUserData"]["bill_address2"]; ?></span></td>
              	</tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Ville
					</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php print $_SESSION["sessionUserData"]["bill_city"]; ?></span></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">Région</div>              	</td>
              	<td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php print $_SESSION["sessionUserData"]["bill_state"]; ?></span></td>
              </tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Zip/Postal Code
					</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php print $_SESSION["sessionUserData"]["bill_zip"]; ?></span></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Pays</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php print $_SESSION["sessionUserData"]["bill_country"]; ?></span></td>
              </tr>
            
            </table>
		    <div align="center" style="margin:11px">
      	  <p>
      	    &nbsp;
	      		  <input name="submitPmt" type="submit" class="formText" value="Continuer">
	      	  </p>
      	</div>
        
        <!--

TEST CREDENTIALS

gt482766135SB
0ad1475c4ea83e631f1cdda64b3fd06e

LIVE CREDENTIALS CA

gt1768180
b4cc30197cf237daec64ddfd929c6cd1

LIVE CREDENTIALS US

gt188417645
c0fac2bda68d9ac56af9e089453300dc

-->

<?php if ($_SESSION["sessionUserData"]["currency"]=="US"){?>

<input type="hidden" name="CRESecureID" value="gt188417645" />
<input type="hidden" name="CRESecureAPIToken" value="c0fac2bda68d9ac56af9e089453300dc" />

<?php }else if ($_SESSION["sessionUserData"]["currency"]=="CA"){?>

<input type="hidden" name="CRESecureID" value="gt1768180" />
<input type="hidden" name="CRESecureAPIToken" value="b4cc30197cf237daec64ddfd929c6cd1" />

<?php }?>

<input type="hidden" name="return_url" value="https://www.aitlensclub/aitlensclub/payment_thanks.php" />
<input type="hidden" name="content_template_url" value="https://www.aitlensclub.com/aitlensclub/payment.php" />
<input type="hidden" name="total_amt" value="<?php print "$discounted_total_cost"; ?>" />
<input type="hidden" name="customer_id" value="<?php print $_SESSION["sessionUser_Id"];?>" />
<input type="hidden" name="lang" value="en_US" />
<input type="hidden" name="allowed_types" value="Visa|MasterCard|American Express|Discover" />

<input type="hidden" name="sess_id" value="<?php print "$_SESSION[Master_Order_ID]"; ?>" />
<input type="hidden" name="sess_name" value="<?php print "$_SESSION[Master_Order_ID]"; ?>" />

	  </form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
 <?php include("footer.inc.php"); ?>
</div><!--END containter-->
</body>
</html>
