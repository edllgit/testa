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
	$ordersQuery="SELECT * from payments WHERE pmt_date='$today' AND user_id = '$user_id' AND pmt_marker = 'pending' order by order_num";//find user's open orders for today
	$ordersResult=mysql_query($ordersQuery)
		or die  ('I cannot select items because: ' . mysql_error());
	$i=1;
	while($orderNums = mysql_fetch_array($ordersResult)){//find user's order number array
		$order_numbers[$i][order_num]=$orderNums[order_num];
		$orderCount=$i;
		$i++;
	}
	for ($i = 1; $i <= $orderCount; $i++){//get order data
		if($display_order_num!="")
			$display_order_num.=", ";
		$order_num = $order_numbers[$i][order_num];
		$display_order_num.=$order_num;
		$orderDataQuery="SELECT user_id, order_num, order_total, order_shipping_cost from orders WHERE order_num='$order_num' AND user_id = '$user_id' group by order_num";//find user's order data for today
		$orderDataResult=mysql_query($orderDataQuery)
			or die  ('I cannot select items because: ' . mysql_error());
		$orderData=mysql_fetch_array($orderDataResult);
		$order_numbers[$i][order_total]=$orderData[order_total];
		$all_items_total=bcadd($all_items_total, $orderData[order_total], 2);
		$order_numbers[$i][order_shipping_cost]=$orderData[order_shipping_cost];
		$all_shipping_total=bcadd($all_shipping_total, $orderData[order_shipping_cost], 2);
	}
	$_POST[order_num]=$display_order_num;
	$_POST[total_cost]=bcadd($all_shipping_total, $all_items_total, 2);
	$_POST[order_shipping_cost]=$all_shipping_total;
	$_POST[order_date_processed]=$today;
	$_SESSION["order_numbers"]=$order_numbers;
	$_SESSION["orderCount"]=$orderCount;
}

$result=mysql_query("SELECT DATE_ADD('$_POST[order_date_processed]', interval 1 month)");/* add 1 month to order_processed_date */
$duedate=mysql_result($result,0,0);

$result=mysql_query("SELECT DATE_SUB('$duedate', interval 15 day)");/* add 15 days to order_processed_date */
$discountdate_15=mysql_result($result,0,0);

$result=mysql_query("SELECT DATE_SUB('$duedate', interval 10 day)");/* add 10 days to order_processed_date */
$discountdate_10=mysql_result($result,0,0);

$item_total=bcsub($_POST[total_cost], $_POST[order_shipping_cost], 2);

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
$discounted_total_cost = bcsub($_POST[total_cost], $discountamt, 2);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $sitename;?></title>



   
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
<form action="reviewCreditInfo.php" method="post" name="pmtForm" id="pmtForm" onSubmit="return formCheck(this);"><div class="header">
		  	Make a Payment
		  </div><div class="loginText">User: 
			<?php 
			if ($_SESSION["sessionUser_Id"]!=""){
			echo $_SESSION["sessionUser_Id"];}
			else{
			echo "not logged in";}?></div>
  <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
      <tr >
                <td colspan="4" bgcolor="#17A2D2" class="tableHead">&nbsp;</td>
                </tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Order Number(s)
					</div></td>
              		<td align="left" class="formCellNosides"><span class="Subheader"><?php echo "$_POST[order_num]"; ?></span>
              			<input type="hidden" name="order_num" value="<?php echo "$_POST[order_num]"; ?>">
              			<input type="hidden" name="user_id" value="<?php echo $_SESSION["sessionUser_Id"]; ?>"></td>
              		<td align="left" nowrap class="formCellNosides"><div align="right">
              		Order Amount minus Shipping
              		</div></td>
              		<td align="left" class="formCellNosides"><span class="Subheader">$<?php echo "$item_total"; ?></span></td>
              </tr>
              <tr>
              	<td align="left"  class="formCellNosides"><div align="right">
              		Total Order Amount
              		</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader">$<?php echo "$_POST[total_cost] " . $_SESSION["sessionUserData"]["currency"]; ?></span>
              		<input type="hidden" name="currency" value="<?php echo $_SESSION["sessionUserData"]["currency"]; ?>"></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Early Payment Discount
              		</div></td>
              	<td align="left" class="formCellNosides"><?php if(!$discountamt) echo "Not Eligible"; else echo "<span class=\"Subheader\">\$$discountamt</span> ($discount disc)"; ?>
              		<input type="hidden" name="pass_disc" value="<?php echo "$pass_disc"; ?>"></td>
              </tr>
              <tr>
              	<td align="left"  class="formCellNosides"><div align="right">
              		Total Amount to be Charged
              		</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader">$<?php echo "$discounted_total_cost " . $_SESSION["sessionUserData"]["currency"]; ?>
              		<input type="hidden" name="total_cost" value="<?php echo "$discounted_total_cost"; ?>">
              	</span></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Shipping Charges
              		</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader">$<?php echo "$_POST[order_shipping_cost]"; ?></span></td>
              	</tr>
              <tr>
                <td align="left"  class="formCellNosides"><div align="right">
                	First Name 
                	</div></td>
                <td align="left" class="formCellNosides"><input name="first_name" type="text" id="first_name" size="20" value="<?php echo $_SESSION["sessionUserData"]["first_name"]; ?>"></td>
                <td align="left" nowrap class="formCellNosides"><div align="right">
                	Last Name 
                	</div></td>
              	<td align="left" class="formCellNosides"><input name="last_name" type="text" id="last_name" size="20" value="<?php echo $_SESSION["sessionUserData"]["last_name"]; ?>"></td>
              </tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Phone
					</div></td>
              	<td align="left" class="formCellNosides"><input name="phone" type="text" id="phone" size="20" value="<?php echo $_SESSION["sessionUserData"]["phone"]; ?>"></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Other Phone
              		</div></td>
              	<td align="left" class="formCellNosides"><input name="other_phone" type="text" id="other_phone" size="20" value="<?php echo $_SESSION["sessionUserData"]["other_phone"]; ?>"></td>
              	</tr>
              <tr>
              	<td align="left"  class="formCellNosides"><div align="right">
              		Email
              		</div></td>
              	<td colspan="3" align="left" class="formCellNosides"><input name="email" type="text" id="email" size="50" value="<?php echo $_SESSION["sessionUserData"]["email"]; ?>"></td>
              	</tr>
              <tr bgcolor="#17A2D2">
					<td height="30" colspan="4" align="left"  class="formCellNosides"><div align="center" class="tableHead">
												Billing Address						
			</div></td>	
					</tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Address 1
					</div></td>
              	<td align="left" class="formCellNosides"><input name="address1" type="text" id="address1" size="20" value="<?php echo $_SESSION["sessionUserData"]["bill_address1"]; ?>"></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Address 2
              		</div></td>
              	<td align="left" class="formCellNosides"><input name="address2" type="text" id="address2" size="20" value="<?php echo $_SESSION["sessionUserData"]["bill_address2"]; ?>"></td>
              	</tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						City
					</div></td>
              	<td align="left" class="formCellNosides"><input name="city" type="text" id="city" size="20" value="<?php echo $_SESSION["sessionUserData"]["bill_city"]; ?>"></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		State/Province
              	</div>              	</td>
              	<td align="left" class="formCellNosides"><select id="state" name="state">
					<option value="">Select One</option>
					<optgroup label="Canadian Provinces">
					<option value="AB" <?php if($_SESSION["sessionUserData"]["bill_state"]=="AB") echo " selected"; ?>>Alberta</option>
					<option value="BC" <?php if($_SESSION["sessionUserData"]["bill_state"]=="BC") echo " selected"; ?>>British
					Columbia</option>
					<option value="MB" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MB") echo " selected"; ?>>Manitoba</option>
					<option value="NB" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NB") echo " selected"; ?>>New
					Brunswick</option>
					<option value="NF" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NF") echo " selected"; ?>>Newfoundland</option>
					<option value="NT" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NT") echo " selected"; ?>>Northwest
					Territories</option>
					<option value="NS" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NS") echo " selected"; ?>>Nova
					Scotia</option>
					<option value="NU" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NU") echo " selected"; ?>>Nunavut</option>
					<option value="ON" <?php if($_SESSION["sessionUserData"]["bill_state"]=="ON") echo " selected"; ?>>Ontario</option>
					<option value="PE" <?php if($_SESSION["sessionUserData"]["bill_state"]=="PE") echo " selected"; ?>>Prince
					Edward Island</option>
					<option value="QC" <?php if($_SESSION["sessionUserData"]["bill_state"]=="QC") echo " selected"; ?>>Quebec</option>
					<option value="SK" <?php if($_SESSION["sessionUserData"]["bill_state"]=="SK") echo " selected"; ?>>Saskatchewan</option>
					<option value="YT" <?php if($_SESSION["sessionUserData"]["bill_state"]=="YT") echo " selected"; ?>>Yukon
					Territory</option>
					</optgroup>
					<optgroup label="U.S. States">
					<option value="AL" <?php if($_SESSION["sessionUserData"]["bill_state"]=="AL") echo " selected"; ?>>Alabama</option>
					<option value="AK" <?php if($_SESSION["sessionUserData"]["bill_state"]=="AK") echo " selected"; ?>>Alaska</option>
					<option value="AZ" <?php if($_SESSION["sessionUserData"]["bill_state"]=="AZ") echo " selected"; ?>>Arizona</option>
					<option value="AR" <?php if($_SESSION["sessionUserData"]["bill_state"]=="AR") echo " selected"; ?>>Arkansas</option>
					<option value="CA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="CA") echo " selected"; ?>>California</option>
					<option value="CO" <?php if($_SESSION["sessionUserData"]["bill_state"]=="CO") echo " selected"; ?>>Colorado</option>
					<option value="CT" <?php if($_SESSION["sessionUserData"]["bill_state"]=="CT") echo " selected"; ?>>Connecticut</option>
					<option value="DE" <?php if($_SESSION["sessionUserData"]["bill_state"]=="DE") echo " selected"; ?>>Delaware</option>
					<option value="DC" <?php if($_SESSION["sessionUserData"]["bill_state"]=="DC") echo " selected"; ?>>District
					of Columbia</option>
					<option value="FL" <?php if($_SESSION["sessionUserData"]["bill_state"]=="FL") echo " selected"; ?>>Florida</option>
					<option value="GA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="GA") echo " selected"; ?>>Georgia</option>
					<option value="HI" <?php if($_SESSION["sessionUserData"]["bill_state"]=="HI") echo " selected"; ?>>Hawaii</option>
					<option value="ID" <?php if($_SESSION["sessionUserData"]["bill_state"]=="ID") echo " selected"; ?>>Idaho</option>
					<option value="IL" <?php if($_SESSION["sessionUserData"]["bill_state"]=="IL") echo " selected"; ?>>Illinois</option>
					<option value="IN" <?php if($_SESSION["sessionUserData"]["bill_state"]=="IN") echo " selected"; ?>>Indiana</option>
					<option value="IA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="IA") echo " selected"; ?>>Iowa</option>
					<option value="KS" <?php if($_SESSION["sessionUserData"]["bill_state"]=="KS") echo " selected"; ?>>Kansas</option>
					<option value="KY" <?php if($_SESSION["sessionUserData"]["bill_state"]=="KY") echo " selected"; ?>>Kentucky</option>
					<option value="LA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="LA") echo " selected"; ?>>Louisiana</option>
					<option value="ME" <?php if($_SESSION["sessionUserData"]["bill_state"]=="ME") echo " selected"; ?>>Maine</option>
					<option value="MD" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MD") echo " selected"; ?>>Maryland</option>
					<option value="MA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MA") echo " selected"; ?>>Massachusetts</option>
					<option value="MI" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MI") echo " selected"; ?>>Michigan</option>
					<option value="MN" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MN") echo " selected"; ?>>Minnesota</option>
					<option value="MS" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MS") echo " selected"; ?>>Mississippi</option>
					<option value="MO" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MO") echo " selected"; ?>>Missouri</option>
					<option value="MT" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MT") echo " selected"; ?>>Montana</option>
					<option value="NE" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NE") echo " selected"; ?>>Nebraska</option>
					<option value="NV" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NV") echo " selected"; ?>>Nevada</option>
					<option value="NH" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NH") echo " selected"; ?>>New
					Hampshire</option>
					<option value="NJ" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NJ") echo " selected"; ?>>New
					Jersey</option>
					<option value="NM" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NM") echo " selected"; ?>>New
					Mexico</option>
					<option value="NY" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NY") echo " selected"; ?>>New
					York</option>
					<option value="NC" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NC") echo " selected"; ?>>North
					Carolina</option>
					<option value="ND" <?php if($_SESSION["sessionUserData"]["bill_state"]=="ND") echo " selected"; ?>>North
					Dakota</option>
					<option value="OH" <?php if($_SESSION["sessionUserData"]["bill_state"]=="OH") echo " selected"; ?>>Ohio</option>
					<option value="OK" <?php if($_SESSION["sessionUserData"]["bill_state"]=="OK") echo " selected"; ?>>Oklahoma</option>
					<option value="OR" <?php if($_SESSION["sessionUserData"]["bill_state"]=="OR") echo " selected"; ?>>Oregon</option>
					<option value="PA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="PA") echo " selected"; ?>>Pennsylvania</option>
					<option value="PR" <?php if($_SESSION["sessionUserData"]["bill_state"]=="PR") echo " selected"; ?>>Puerto
					Rico</option>
					<option value="RI" <?php if($_SESSION["sessionUserData"]["bill_state"]=="RI") echo " selected"; ?>>Rhode
					Island</option>
					<option value="SC" <?php if($_SESSION["sessionUserData"]["bill_state"]=="SC") echo " selected"; ?>>South
					Carolina</option>
					<option value="SD" <?php if($_SESSION["sessionUserData"]["bill_state"]=="SD") echo " selected"; ?>>South
					Dakota</option>
					<option value="TN" <?php if($_SESSION["sessionUserData"]["bill_state"]=="TN") echo " selected"; ?>>Tennessee</option>
					<option value="TX" <?php if($_SESSION["sessionUserData"]["bill_state"]=="TX") echo " selected"; ?>>Texas</option>
					<option value="UT" <?php if($_SESSION["sessionUserData"]["bill_state"]=="UT") echo " selected"; ?>>Utah</option>
					<option value="VT" <?php if($_SESSION["sessionUserData"]["bill_state"]=="VT") echo " selected"; ?>>Vermont</option>
					<option value="VA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="VA") echo " selected"; ?>>Virginia</option>
					<option value="WA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="WA") echo " selected"; ?>>Washington</option>
					<option value="WV" <?php if($_SESSION["sessionUserData"]["bill_state"]=="WV") echo " selected"; ?>>West
					Virginia</option>
					<option value="WI" <?php if($_SESSION["sessionUserData"]["bill_state"]=="WI") echo " selected"; ?>>Wisconsin</option>
					<option value="WY" <?php if($_SESSION["sessionUserData"]["bill_state"]=="WY") echo " selected"; ?>>Wyoming</option>
					</optgroup>
				</select></td>
              </tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Zip/Postal Code
					</div></td>
              	<td align="left" class="formCellNosides"><input name="zip" type="text" id="zip" size="20" value="<?php echo $_SESSION["sessionUserData"]["bill_zip"]; ?>"></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Country
              		</div></td>
              	<td align="left" class="formCellNosides"><select name = "country" id="country">
					<option value="">Select One</option>
					<option value = "CA" <?php if($_SESSION["sessionUserData"]["bill_country"]=="CA") echo " selected"; ?>>Canada</option>
					<option value = "US" <?php if($_SESSION["sessionUserData"]["bill_country"]=="US") echo " selected"; ?>>United
					States</option>
				</select></td>
              </tr>
            </table>
		    <div align="center" style="margin:11px">
	      	  <p>
		      		<input name="Reset" type="reset" class="formText" value="Reset">
		      		&nbsp;
		      		<input name="submitPmt" type="submit" class="formText" value="Submit">
	      	  </p>
      	</div>
	  </form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
 <?php include("footer.inc.php"); ?>
</div><!--END containter-->
</body>
</html>