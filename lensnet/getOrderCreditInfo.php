<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
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

$uniqid = rand(100000, 999999);//for refresh test only
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>LensNet Club</title>


<!--[if !IE]>-->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.dropshadow.js"></script>
<script type="text/javascript">
      window.onload = function()  
      {
        $("#container").dropShadow({left:6, top:6, blur:5, opacity:0.7});
		     
      }
    </script>
<!--<![endif]-->
   
<link href="ln.css" rel="stylesheet" type="text/css" />
<link href="ln_pt.css" rel="stylesheet" type="text/css" />

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
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ln-masthead.jpg" width="1050" height="165" alt="LensNet Club"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
<?php   
include("includes/sideNav.inc.php");
?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">
<form action="reviewOrderCreditInfo.php" method="post" name="pmtForm" id="pmtForm" onSubmit="return formCheck(this);">
		  	<div class="header">
		  	Confirm your Informations</div><div class="loginText">User: 
			<?php 
			if ($_SESSION["sessionUser_Id"]!=""){
			echo $_SESSION["sessionUser_Id"];}
			else{
			echo "not logged in";}?></div>
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="4" bgcolor="#000099" class="tableHead">&nbsp;</td>
                </tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Master Order ID
					</div></td>
              		<td align="left" class="formCellNosides"><span class="Subheader"><?php echo "$_SESSION[Master_Order_ID]"; ?></span>
              			<input type="hidden" name="Master_Order_ID" value="<?php echo "$_SESSION[Master_Order_ID]"; ?>">
              		<td align="left" nowrap class="formCellNosides"><div align="right">
              		Order Amount minus Shipping
              		</div></td>
              		<td align="left" class="formCellNosides"><span class="Subheader">$<?php echo "$item_total"; ?></span></td>
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
                <td align="left" class="formCellNosides"><input name="first_name" type="text" id="first_name" size="20" value=""></td>
                <td align="left" nowrap class="formCellNosides"><div align="right">
                	Last Name 
                	</div></td>
              	<td align="left" class="formCellNosides"><input name="last_name" type="text" id="last_name" size="20" value=""></td>
              </tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Phone
					</div></td>
              	<td align="left" class="formCellNosides"><input name="phone" type="text" id="phone" size="20" value=""></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Other Phone
              		</div></td>
              	<td align="left" class="formCellNosides"><input name="other_phone" type="text" id="other_phone" size="20" value=""></td>
              	</tr>
              <tr>
              	<td align="left"  class="formCellNosides"><div align="right">
              		Email
              		</div></td>
              	<td colspan="3" align="left" class="formCellNosides"><input name="email" type="text" id="email" size="50" value=""></td>
              	</tr>
              <tr bgcolor="#000099">
					<td height="30" colspan="4" align="left"  class="formCellNosides"><div align="center" class="tableHead">Billing Address	</div></td>	
					</tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Address 1
					</div></td>
              	<td align="left" class="formCellNosides"><input name="address1" type="text" id="address1" size="20" value=""></td>
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
					<option value = "USA" <?php if($_SESSION["sessionUserData"]["bill_country"]=="US") print " selected"; ?>>United
					States</option>
				</select></td>
              </tr>
              
                <tr bgcolor="#000099">
					<td height="30" colspan="4" align="left"  class="formCellNosides"><div align="center" class="tableHead">Credit Card Details</div></td>	
				</tr>
                
                <tr>
					<td align="left"  class="formCellNosides">
                    	<div align="right">Credit Card Number</div>
                    </td>
                    
              	<td align="left" class="formCellNosides">
               		<input name="cc_num" type="text" id="cc_num" size="16" max="16" value="">
                </td>
                
             
                <td align="left" nowrap class="formCellNosides">
                	<div align="right">Expiration Date</div>
                </td>
              	<td align="left" class="formCellNosides">
                <select id="exp_date_mo" name="exp_date_mo">
					<option value="01">January</option>
					<option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
				</select>
                
                 <select id="exp_date_yr" name="exp_date_yr">
					<option value="14">2014</option>
					<option value="15">2015</option>
                    <option value="16">2016</option>
                    <option value="17">2017</option>
                    <option value="18">2018</option>
                    <option value="19">2019</option>
                    <option value="20">2020</option>
                    <option value="21">2021</option>
                    <option value="22">2022</option>
                    <option value="23">2023</option>
                    <option value="24">2024</option>
                    <option value="25">2025</option>
				</select></td>
                
               

          	</tr>
            
            <tr>
             <td align="left" class="formCellNosides"><div align="right">CVC Number</div></td>
            <td><input name="cvc_num" type="text" id="cvc_num" size="5" max="5" value=""></td>
            </tr>

            </table>
		    <div align="center" style="margin:11px">
	      	  <p><input type="hidden" name="uniqid" id="uniqid" value="<?php print "$uniqid"; ?>" />

		      		<input name="Reset" type="reset" class="formText" value="Reset">
		      		&nbsp;
		      		<input name="submitPmt" type="submit" class="formText" value="Continue">
	      	  </p>
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