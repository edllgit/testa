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
//	BELOW IS FROM BEFORE 8/11/2011 WHEN ORDER PROCESSING OCCURED BEFORE CREDIT CARD PROCESSING
//	$ordersQuery="SELECT * from payments WHERE pmt_date='$today' AND user_id = '$user_id' AND pmt_marker = 'pending' order by order_num";//find user's open orders for today
//	$ordersResult=mysql_query($ordersQuery)
//		or die  ('I cannot select items because: ' . mysql_error());
//	$i=1;
//	while($orderNums = mysql_fetch_array($ordersResult)){//find user's order number array
//		$order_numbers[$i][order_num]=$orderNums[order_num];
//		$orderCount=$i;
//		$i++;
//	}
//	for ($i = 1; $i <= $orderCount; $i++){//get order data
//		if($display_order_num!="")
//			$display_order_num.=", ";
//		$order_num = $order_numbers[$i][order_num];
//		$display_order_num.=$order_num;
//		$orderDataQuery="SELECT user_id, order_num, order_total, order_shipping_cost from orders WHERE order_num='$order_num' AND user_id = '$user_id' group by order_num";//find user's order data for today
//		$orderDataResult=mysql_query($orderDataQuery)
//			or die  ('I cannot select items because: ' . mysql_error());
//		$orderData=mysql_fetch_array($orderDataResult);
//		$order_numbers[$i][order_total]=$orderData[order_total];
//		$all_items_total=bcadd($all_items_total, $orderData[order_total], 2);
//		$order_numbers[$i][order_shipping_cost]=$orderData[order_shipping_cost];
//		$all_shipping_total=bcadd($all_shipping_total, $orderData[order_shipping_cost], 2);
//	}
//	$_POST[order_num]=$display_order_num;
//	$_POST[total_cost]=bcadd($all_shipping_total, $all_items_total, 2);
//	$_POST[order_shipping_cost]=$all_shipping_total;
//	$_POST[order_date_processed]=$today;
//	$_SESSION["order_numbers"]=$order_numbers;
//	$_SESSION["orderCount"]=$orderCount;
}

$result=mysql_query("SELECT DATE_ADD('$_SESSION[order_date_processed]', interval 1 month)");/* add 1 month to order_processed_date */
$duedate=mysql_result($result,0,0);

$result=mysql_query("SELECT DATE_SUB('$duedate', interval 15 day)");/* add 15 days to order_processed_date */
$discountdate_15=mysql_result($result,0,0);

$result=mysql_query("SELECT DATE_SUB('$duedate', interval 10 day)");/* add 10 days to order_processed_date */
$discountdate_10=mysql_result($result,0,0);

$item_total=bcsub($_SESSION["currentTotal"], $_SESSION["order_shipping_cost"], 2);

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
<form action="payment_thanks_test.php" method="post" name="pmtForm" id="pmtForm" onSubmit="return formCheck(this);">
<div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
  <div class="header">
		  	Make a Payment
		  </div>
  <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
      <tr >
                <td colspan="4" bgcolor="#17A2D2" class="tableHead">&nbsp;</td>
                </tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Master Order ID
					</div></td>
              		<td align="left" class="formCellNosides"><span class="Subheader"><?php print "$_SESSION[Master_Order_ID]"; ?></span>
              			<input type="hidden" name="Master_Order_ID" value="<?php print "$_SESSION[Master_Order_ID]"; ?>">
              			<input type="hidden" name="order_num" value="<?php print "$_SESSION[Master_Order_ID]"; ?>"><!-- for GP card processing invnum -->
              		<td align="left" nowrap class="formCellNosides"><div align="right">
              		Order Amount minus Shipping
              		</div></td>
              		<td align="left" class="formCellNosides"><span class="Subheader">$<?php print "$item_total"; ?></span></td>
              </tr>
              <tr>
              	<td align="left"  class="formCellNosides"><div align="right">
              		Total Order Amount
              		</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader">$<?php print $_SESSION["currentTotal"] . " " . $_SESSION["sessionUserData"]["currency"]; ?></span>
              		<input type="hidden" name="currency" value="<?php print $_SESSION["sessionUserData"]["currency"]; ?>"></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Early Payment Discount
              		</div></td>
              	<td align="left" class="formCellNosides"><?php if(!$discountamt) print "Not Eligible"; else print "<span class=\"Subheader\">\$$discountamt</span> ($discount disc)"; ?>
              		<input type="hidden" name="pass_disc" value="<?php print "$pass_disc"; ?>"></td>
              </tr>
              <tr>
              	<td align="left"  class="formCellNosides"><div align="right">
              		Total Amount to be Charged
              		</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader">$<?php print "$discounted_total_cost " . " " . $_SESSION["sessionUserData"]["currency"]; ?>
              		<input type="hidden" name="total_cost" value="<?php print "$discounted_total_cost"; ?>">
              	</span></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Shipping Charges
              		</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader"><?php $shipping=money_format('%.2n',$_SESSION["order_shipping_cost"]); print "\$$shipping"; ?></span></td>
              	</tr>
              <tr>
                <td align="left"  class="formCellNosides"><div align="right">
                	First Name 
                	</div></td>
                <td align="left" class="formCellNosides"><input name="first_name" type="text" id="first_name" size="20" value="<?php print $_SESSION["sessionUserData"]["first_name"]; ?>"></td>
                <td align="left" nowrap class="formCellNosides"><div align="right">
                	Last Name 
                	</div></td>
              	<td align="left" class="formCellNosides"><input name="last_name" type="text" id="last_name" size="20" value="<?php print $_SESSION["sessionUserData"]["last_name"]; ?>"></td>
              </tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Phone
					</div></td>
              	<td align="left" class="formCellNosides"><input name="phone" type="text" id="phone" size="20" value="<?php print $_SESSION["sessionUserData"]["phone"]; ?>"></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Other Phone
              		</div></td>
              	<td align="left" class="formCellNosides"><input name="other_phone" type="text" id="other_phone" size="20" value="<?php print $_SESSION["sessionUserData"]["other_phone"]; ?>"></td>
              	</tr>
              <tr>
              	<td align="left"  class="formCellNosides"><div align="right">
              		Email
              		</div></td>
              	<td colspan="3" align="left" class="formCellNosides"><input name="email" type="text" id="email" size="50" value="<?php print $_SESSION["sessionUserData"]["email"]; ?>"></td>
              	</tr>
              <tr bgcolor="#17A2D2">
					<td height="30" colspan="4" align="left"  class="formCellNosides"><div align="center" class="style1">
												Billing Address						
					</div></td>	
					</tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Address 1
					</div></td>
              	<td align="left" class="formCellNosides"><input name="address1" type="text" id="address1" size="20" value="<?php print $_SESSION["sessionUserData"]["bill_address1"]; ?>"></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Address 2
              		</div></td>
              	<td align="left" class="formCellNosides"><input name="address2" type="text" id="address2" size="20" value="<?php print $_SESSION["sessionUserData"]["bill_address2"]; ?>"></td>
              	</tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						City
					</div></td>
              	<td align="left" class="formCellNosides"><input name="city" type="text" id="city" size="20" value="<?php print $_SESSION["sessionUserData"]["bill_city"]; ?>"></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		State/Province
              	</div>              	</td>
              	<td align="left" class="formCellNosides"><select id="state" name="state">
					<option value="">Select One</option>
					<optgroup label="Canadian Provinces">
					<option value="AB" <?php if($_SESSION["sessionUserData"]["bill_state"]=="AB") print " selected"; ?>>Alberta</option>
					<option value="BC" <?php if($_SESSION["sessionUserData"]["bill_state"]=="BC") print " selected"; ?>>British
					Columbia</option>
					<option value="MB" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MB") print " selected"; ?>>Manitoba</option>
					<option value="NB" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NB") print " selected"; ?>>New
					Brunswick</option>
					<option value="NF" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NF") print " selected"; ?>>Newfoundland</option>
					<option value="NT" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NT") print " selected"; ?>>Northwest
					Territories</option>
					<option value="NS" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NS") print " selected"; ?>>Nova
					Scotia</option>
					<option value="NU" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NU") print " selected"; ?>>Nunavut</option>
					<option value="ON" <?php if($_SESSION["sessionUserData"]["bill_state"]=="ON") print " selected"; ?>>Ontario</option>
					<option value="PE" <?php if($_SESSION["sessionUserData"]["bill_state"]=="PE") print " selected"; ?>>Prince
					Edward Island</option>
					<option value="QC" <?php if($_SESSION["sessionUserData"]["bill_state"]=="QC") print " selected"; ?>>Quebec</option>
					<option value="SK" <?php if($_SESSION["sessionUserData"]["bill_state"]=="SK") print " selected"; ?>>Saskatchewan</option>
					<option value="YT" <?php if($_SESSION["sessionUserData"]["bill_state"]=="YT") print " selected"; ?>>Yukon
					Territory</option>
					</optgroup>
					<optgroup label="U.S. States">
					<option value="AL" <?php if($_SESSION["sessionUserData"]["bill_state"]=="AL") print " selected"; ?>>Alabama</option>
					<option value="AK" <?php if($_SESSION["sessionUserData"]["bill_state"]=="AK") print " selected"; ?>>Alaska</option>
					<option value="AZ" <?php if($_SESSION["sessionUserData"]["bill_state"]=="AZ") print " selected"; ?>>Arizona</option>
					<option value="AR" <?php if($_SESSION["sessionUserData"]["bill_state"]=="AR") print " selected"; ?>>Arkansas</option>
					<option value="CA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="CA") print " selected"; ?>>California</option>
					<option value="CO" <?php if($_SESSION["sessionUserData"]["bill_state"]=="CO") print " selected"; ?>>Colorado</option>
					<option value="CT" <?php if($_SESSION["sessionUserData"]["bill_state"]=="CT") print " selected"; ?>>Connecticut</option>
					<option value="DE" <?php if($_SESSION["sessionUserData"]["bill_state"]=="DE") print " selected"; ?>>Delaware</option>
					<option value="DC" <?php if($_SESSION["sessionUserData"]["bill_state"]=="DC") print " selected"; ?>>District
					of Columbia</option>
					<option value="FL" <?php if($_SESSION["sessionUserData"]["bill_state"]=="FL") print " selected"; ?>>Florida</option>
					<option value="GA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="GA") print " selected"; ?>>Georgia</option>
					<option value="HI" <?php if($_SESSION["sessionUserData"]["bill_state"]=="HI") print " selected"; ?>>Hawaii</option>
					<option value="ID" <?php if($_SESSION["sessionUserData"]["bill_state"]=="ID") print " selected"; ?>>Idaho</option>
					<option value="IL" <?php if($_SESSION["sessionUserData"]["bill_state"]=="IL") print " selected"; ?>>Illinois</option>
					<option value="IN" <?php if($_SESSION["sessionUserData"]["bill_state"]=="IN") print " selected"; ?>>Indiana</option>
					<option value="IA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="IA") print " selected"; ?>>Iowa</option>
					<option value="KS" <?php if($_SESSION["sessionUserData"]["bill_state"]=="KS") print " selected"; ?>>Kansas</option>
					<option value="KY" <?php if($_SESSION["sessionUserData"]["bill_state"]=="KY") print " selected"; ?>>Kentucky</option>
					<option value="LA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="LA") print " selected"; ?>>Louisiana</option>
					<option value="ME" <?php if($_SESSION["sessionUserData"]["bill_state"]=="ME") print " selected"; ?>>Maine</option>
					<option value="MD" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MD") print " selected"; ?>>Maryland</option>
					<option value="MA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MA") print " selected"; ?>>Massachusetts</option>
					<option value="MI" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MI") print " selected"; ?>>Michigan</option>
					<option value="MN" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MN") print " selected"; ?>>Minnesota</option>
					<option value="MS" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MS") print " selected"; ?>>Mississippi</option>
					<option value="MO" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MO") print " selected"; ?>>Missouri</option>
					<option value="MT" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MT") print " selected"; ?>>Montana</option>
					<option value="NE" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NE") print " selected"; ?>>Nebraska</option>
					<option value="NV" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NV") print " selected"; ?>>Nevada</option>
					<option value="NH" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NH") print " selected"; ?>>New
					Hampshire</option>
					<option value="NJ" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NJ") print " selected"; ?>>New
					Jersey</option>
					<option value="NM" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NM") print " selected"; ?>>New
					Mexico</option>
					<option value="NY" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NY") print " selected"; ?>>New
					York</option>
					<option value="NC" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NC") print " selected"; ?>>North
					Carolina</option>
					<option value="ND" <?php if($_SESSION["sessionUserData"]["bill_state"]=="ND") print " selected"; ?>>North
					Dakota</option>
					<option value="OH" <?php if($_SESSION["sessionUserData"]["bill_state"]=="OH") print " selected"; ?>>Ohio</option>
					<option value="OK" <?php if($_SESSION["sessionUserData"]["bill_state"]=="OK") print " selected"; ?>>Oklahoma</option>
					<option value="OR" <?php if($_SESSION["sessionUserData"]["bill_state"]=="OR") print " selected"; ?>>Oregon</option>
					<option value="PA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="PA") print " selected"; ?>>Pennsylvania</option>
					<option value="PR" <?php if($_SESSION["sessionUserData"]["bill_state"]=="PR") print " selected"; ?>>Puerto
					Rico</option>
					<option value="RI" <?php if($_SESSION["sessionUserData"]["bill_state"]=="RI") print " selected"; ?>>Rhode
					Island</option>
					<option value="SC" <?php if($_SESSION["sessionUserData"]["bill_state"]=="SC") print " selected"; ?>>South
					Carolina</option>
					<option value="SD" <?php if($_SESSION["sessionUserData"]["bill_state"]=="SD") print " selected"; ?>>South
					Dakota</option>
					<option value="TN" <?php if($_SESSION["sessionUserData"]["bill_state"]=="TN") print " selected"; ?>>Tennessee</option>
					<option value="TX" <?php if($_SESSION["sessionUserData"]["bill_state"]=="TX") print " selected"; ?>>Texas</option>
					<option value="UT" <?php if($_SESSION["sessionUserData"]["bill_state"]=="UT") print " selected"; ?>>Utah</option>
					<option value="VT" <?php if($_SESSION["sessionUserData"]["bill_state"]=="VT") print " selected"; ?>>Vermont</option>
					<option value="VA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="VA") print " selected"; ?>>Virginia</option>
					<option value="WA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="WA") print " selected"; ?>>Washington</option>
					<option value="WV" <?php if($_SESSION["sessionUserData"]["bill_state"]=="WV") print " selected"; ?>>West
					Virginia</option>
					<option value="WI" <?php if($_SESSION["sessionUserData"]["bill_state"]=="WI") print " selected"; ?>>Wisconsin</option>
					<option value="WY" <?php if($_SESSION["sessionUserData"]["bill_state"]=="WY") print " selected"; ?>>Wyoming</option>
					</optgroup>
				</select></td>
              </tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Zip/Postal Code
					</div></td>
              	<td align="left" class="formCellNosides"><input name="zip" type="text" id="zip" size="20" value="<?php print $_SESSION["sessionUserData"]["bill_zip"]; ?>"></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Country
              		</div></td>
              	<td align="left" class="formCellNosides"><select name = "country" id="country">
					<option value="">Select One</option>
					<option value = "CA" <?php if($_SESSION["sessionUserData"]["bill_country"]=="CA") print " selected"; ?>>Canada</option>
					<option value = "US" <?php if($_SESSION["sessionUserData"]["bill_country"]=="US") print " selected"; ?>>United
					States</option>
				</select></td>
              </tr>
              <tr bgcolor="#17A2D2">
					<td height="30" colspan="4" align="left"  class="formCellNosides"><div align="center">
						<span class="style1">
						Credit Card Data</span>
					</div></td>
              	</tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Card Type
					</div></td>
              	<td align="left" class="formCellNosides"><select name="cc_type" id="cc_type">
					<option value="">Credit Card Type</option>
					<option value="American Express">AMEX</option>
					<option value="Discover">Discover</option>
					<option value="MasterCard">MasterCard</option>
					<option value="VISA">VISA</option>
				</select></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Card Number
              		</div></td>
              	<td align="left" class="formCellNosides"><input name="cc_no" type="text" id="cc_no" size="20"></td>
              	</tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Exp Date
					</div></td>
              	<td align="left" class="formCellNosides"><select name="cc_month" id="cc_month">
					<option value="">Month</option>
					<option value="01">01 - Jan</option>
					<option value="02">02 - Feb</option>
					<option value="03">03 - Mar</option>
					<option value="04">04 - Apr</option>
					<option value="05">05 - May</option>
					<option value="06">06 - Jun</option>
					<option value="07">07 - Jul</option>
					<option value="08">08 - Aug</option>
					<option value="09">09 - Sep</option>
					<option value="10">10 - Oct</option>
					<option value="11">11 - Nov</option>
					<option value="12">12 - Dec</option>
				</select>
              		<select name="cc_year" id="cc_year">
						<option value="">Year</option>
                        <option value="15">2015</option>
                        <option value="16">2016</option>
                        <option value="17">2017</option>
					</select></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		CVV (<A HREF="#" onClick="window.open('cvv.htm','_blank','width=500,height=550,titlebar=no,toolbar=no,menubar=no,scrollbars=yes,resizable=no,status=no')">what's this?</A>)
              		</div></td>
              	<td align="left" class="formCellNosides"><input name="cvv" type="text" id="cvv" size="5"></td>
              	</tr>
            </table>
		    <div align="center" style="margin:11px">
		      	<p><input type="hidden" name="uniqid" id="uniqid" value="<?php print "$uniqid"; ?>" />

		      		<input name="Reset" type="reset" class="formText" value="Reset">
		      		&nbsp;
		      		<input name="submitPmt" type="submit" class="formText" value="Submit">
	      	  </p>
      	</div>
	  </form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footerBox">
  
</div>
</div><!--END containter-->
</body>
</html>
<?php
mysql_free_result($languages);

mysql_free_result($languagetext);
?>