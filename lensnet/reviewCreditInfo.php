<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
require_once(__DIR__.'/../constants/url.constant.php');
require('../Connections/sec_connect.inc.php');
include "../includes/getlang.php";

session_start();
if($_SESSION["sessionUser_Id"]=="")
header("Location:loginfail.php");


$result=mysql_query("SELECT curdate()");/* get today's date */
	$today=mysql_result($result,0,0);

$user_id = $_SESSION["sessionUser_Id"];
$_SESSION['order_num']=$_POST['order_num'];
	
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
$discounted_total_cost = bcsub($_POST[total_cost], $discountamt, 2);
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
<?php  	include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">

<!--
VISA TEST NUMBER 4788250000028291
https://safe.sandbox-gtpaysecure.net/securepayments/a1/cc_collection.php
SANDBOX TEST MODE-->



 <form name="form1" method="post" action="<?php echo constant('DIRECT_LENS_URL'); ?>/lensnet/CPS_script.php?frompage=customer_order_history">
 
		  	<div class="header">
		  	Review</div><div class="loginText">User: 
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
						Order Number
				</div></td>
              		<td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php echo $_SESSION['order_num']; ?></span>
              			<input type="hidden" name="order_num" value="<?php echo $_SESSION['order_num']; ?>">
              			<input type="hidden" name="user_id" value="<?php echo $_SESSION["sessionUser_Id"]; ?>"></td>
              		<td align="left" nowrap class="formCellNosides"><div align="right"> Total Amount to be Charged </div></td>
              		<td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold">$<?php echo "$discounted_total_cost " . $_SESSION["sessionUserData"]["currency"]; ?>
                        <input type="hidden" name="total_cost" value="<?php echo "$discounted_total_cost"; ?>" />
                    </span></td>
              </tr>
              <tr>
              	<td align="left"  class="formCellNosides">&nbsp;</td>
              	<td align="left" class="formCellNosides">&nbsp;</td>
              	<td align="left" nowrap class="formCellNosides">&nbsp;</td>
              	<td align="left" class="formCellNosides">&nbsp;</td>
              	</tr>
              <tr>
                <td align="left"  class="formCellNosides"><div align="right">
                	First Name 
                	</div></td>
                <td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php echo $_POST["first_name"]; ?></span></td>
                <td align="left" nowrap class="formCellNosides"><div align="right">
                	Last Name 
                	</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php echo $_POST["last_name"]; ?></span></td>
              </tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Phone
					</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php echo $_POST["phone"]; ?></span></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Other Phone
              		</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php echo $_POST["other_phone"]; ?></span></td>
              	</tr>
              <tr>
              	<td align="left"  class="formCellNosides"><div align="right">
              		Email
              		</div></td>
              	<td colspan="3" align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php echo $_POST["email"]; ?></span></td>
              	</tr>
              <tr bgcolor="#000099">
					<td height="30" colspan="4" align="left"  class="formCellNosides"><div align="center" class="tableHead">
												Billing Address						
			</div></td>	
					</tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Address 1
					</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php echo $_POST["address1"]; ?></span></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Address 2
              		</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php echo $_POST["address2"]; ?></span></td>
              	</tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						City
					</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php echo $_POST["city"]; ?></span></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		State/Province
              	</div>              	</td>
              	<td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php echo $_POST["state"]; ?></span></td>
              </tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						Zip/Postal Code
					</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php echo $_POST["zip"]; ?></span></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		Country
              		</div></td>
              	<td align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php echo $_POST["country"]; ?></span></td>
              </tr>
              
              <tr bgcolor="#000099">
					<td height="30" colspan="4" align="left"  class="formCellNosides"><div align="center" class="tableHead">Credit Card Details</div></td>	
				</tr>
                
                <tr>
					<td align="left"  class="formCellNosides">
                    	<div align="right">Credit Card Number</div>
                    </td>
                    
              	<td align="left" class="formCellNosides">
               	<?php echo substr($_POST[cc_num],0,4) .   '********' . substr($_POST[cc_num],12,4); ?>
                </td>
         
                 <td align="left" class="formCellNosides"><div align="right">CVC Number</div></td>
                 <td>****</td>
             </tr>
            
              
              
            </table>
		    <div align="center" style="margin:11px">
	      	  <p>&nbsp;
		      		<input name="submitPmt" type="submit" class="formText" value="Confirm Order">
	      	  </p>
      	</div>
        
<input type="hidden" name="cc_num"          id="cc_num" 	      value="<?php echo $_POST[cc_num]; ?>" />
<input type="hidden" name="cvc_num"         id="cvc_num" 	      value="<?php echo $_POST[cvc_num]; ?>" />
<input type="hidden" name="first_name"      id="first_name" 	  value="<?php echo $_POST["first_name"]; ?>" />
<input type="hidden" name="last_name" 	    id="last_name"   	  value="<?php echo $_POST["last_name"]; ?>" />
<input type="hidden" name="phone" 		    id="phone"   		  value="<?php echo $_POST["phone"]; ?>" />
<input type="hidden" name="other_phone"     id="other_phone"      value="<?php echo $_POST["other_phone"]; ?>" />
<input type="hidden" name="email"		    id="email"   		  value="<?php echo $_POST["email"]; ?>" />
<input type="hidden" name="address1"   	 	id="address1"         value="<?php echo $_POST["address1"]; ?>" />
<input type="hidden" name="address2"   	    id="address2"         value="<?php echo $_POST["address2"]; ?>" />
<input type="hidden" name="city" 	     	id="city"   	      value="<?php echo $_POST["city"]; ?>" />
<input type="hidden" name="state"     	 	id="state"   	      value="<?php echo $_POST["state"]; ?>" />
<input type="hidden" name="zip" 	     	id="zip"   	          value="<?php echo $_POST["zip"]; ?>" />
<input type="hidden" name="country"    	    id="country"          value="<?php echo $_POST["country"]; ?>" />
<input type="hidden" name="total_amount"    id="total_amount"     value="<?php echo $discounted_total_cost; ?>" />
<input type="hidden" name="master_order_id" id="master_order_id"  value="<?php echo $_SESSION["order_num_paid_by_cc_customer_history"]; ?>" />
<input type="hidden" name="exp_date"        id="exp_date" 	      value="<?php echo $_POST[exp_date_mo] . $_POST[exp_date_yr] ; ?>" />

</form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
</body>
</html>