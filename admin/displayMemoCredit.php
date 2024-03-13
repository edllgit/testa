<?php
session_start();
if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
$lab_pkey=$_SESSION["lab_pkey"];
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
include("../includes/calc_functions.inc.php");

//var_dump($_POST);

if($_POST[issueMemo]=="Issue Memo"){
	$mcred_memo_num=$_POST[lastMemoNum];
	if($_POST[mcred_disc_type]=="percent")
		$mcred_abs_amount=money_format('%.2n',$_POST[prev_order_total]*($_POST[mcred_amount]/100));
	else
		$mcred_abs_amount=money_format('%.2n',$_POST[mcred_amount]);
	$memo_test=issue_memo_credit($mcred_abs_amount);
	if($memo_test==true){
		$heading="MEMO CREDIT CREATED";

		if($_POST[mcred_cred_type]=="credit"){
			$new_order_total=money_format('%.2n',$_POST[prev_order_total] - $mcred_abs_amount);
			$new_additional_dsc=money_format('%.2n',$_POST[prev_additional_dsc] - $mcred_abs_amount);
		}else{
			$new_order_total=money_format('%.2n',$_POST[prev_order_total] + $mcred_abs_amount);
			$new_additional_dsc=money_format('%.2n',$_POST[prev_additional_dsc] + $mcred_abs_amount);
		}
	}
}
elseif($_GET[mcred_memo_num]){
	$mcred_memo_num=$_GET[mcred_memo_num];
}
$memoQuery="SELECT * from memo_credits_rebilling
			LEFT JOIN (orders) ON (memo_credits_rebilling.mcred_order_num = orders.order_num) 
			LEFT JOIN (memo_codes) ON (memo_credits_rebilling.mcred_memo_code = memo_codes.memo_code) 
			WHERE memo_credits_rebilling.mcred_memo_num='$mcred_memo_num' "; 
	//echo '<br><br>'. $memoQuery;		
			//find memo credit
$memoResult=mysql_query($memoQuery)
	or die  ('I cannot select items because: ' . mysql_error());
		
$memoData=mysql_fetch_array($memoResult);
if($memoData[mcred_cred_type]=="credit")
	$mcred_abs_amount = "- ";
else
	$mcred_abs_amount = "";
$mcred_abs_amount .= $memoData[mcred_abs_amount]; 
$timestamp=strtotime($memoData[mcred_date]);
$mcred_date=date("m/d/Y", $timestamp);

$orderQuery="select user_id, patient_ref_num, order_patient_first, order_patient_last from orders WHERE order_num='$memoData[mcred_order_num]' limit 1"; //get order's user id and additional discount

$orderResult=mysql_query($orderQuery)	or die  ('I cannot select items because: ' . mysql_error());
		
$orderData=mysql_fetch_array($orderResult);


$userQuery="select * from accounts WHERE user_id='$orderData[user_id]'"; //find user's data
$userResult=mysql_query($userQuery)
	or die  ('I cannot select items because: ' . mysql_error());
		
$userData=mysql_fetch_array($userResult);
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>

</head>

<body>
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField3">
            	<tr bgcolor="#000000">
            		<td width="33%" align="center">&nbsp;</td>
           			<td width="33%" align="center">
                    <b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">
					<?php if($heading=="") 
					echo "MEMO ORDER"; 
					else echo "$heading"; ?>
                    </font></b>
                    </td>
           			<td width="33%" align="center" nowrap><form name="formPrint" id="formPrint" method="post" action="displayMemoCreditForm.php" target="_blank">
           				<input name="order_num" type="hidden" id="order_num" value="<?php echo $memoData[mcred_order_num];?>">
           				<input name="mcred_memo_num" type="hidden" id="mcred_memo_num" value="<?php echo $mcred_memo_num;?>">
           				<input name="mcred_abs_amount" type="hidden" id="mcred_abs_amount" value="<?php echo $mcred_abs_amount;?>">
           				<input name="mcred_memo_code" type="hidden" id="mcred_memo_code" value="<?php echo $memoData[mcred_memo_code];?>">
						<input name="mc_description" type="hidden" id="mc_description" value="<?php echo $memoData[mc_description];?>">
						<input name="mcred_date" type="hidden" id="mcred_date" value="<?php echo $mcred_date;?>">
						<input name="mcred_order_total" type="hidden" id="mcred_order_total" value="<?php echo $memoData[order_total];?>">
						<input name="company" type="hidden" id="company" value="<?php echo $userData[company];?>">
						<input name="account_num" type="hidden" id="account_num" value="<?php echo $userData[account_num];?>">
						<input name="customer_email" type="hidden" id="customer_email" value="<?php echo $userData[email];?>">
						<input name="patient_ref_num" type="hidden" id="patient_ref_num" value="<?php echo $orderData[patient_ref_num];?>">
						<input name="order_patient_first" type="hidden" id="order_patient_first" value="<?php echo $orderData[order_patient_first];?>">
						<input name="order_patient_last" type="hidden" id="order_patient_last" value="<?php echo $orderData[order_patient_last];?>">
       				<input  type="submit"  name="printMemo" value="Print Memo" class="formField"></form></td>
            	</tr>
			<tr><td colspan="3">
			<form name="form1" id="form1" method="post" action="createMemoCredit.php"><table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField3">
			<?php echo $message;?>
			<tr bgcolor="#DDDDDD">
			  <td width="20%" align="right" valign="middle"><div class="formField2">Order Number:</div></td>
			  <td valign="middle" nowrap><span class="formField2"><?php echo $memoData[mcred_order_num];?></span><span class="formField2">
			  	<input name="order_num" type="hidden" id="order_num" value="<?php echo $memoData[mcred_order_num];?>">
			  	<input name="mcred_memo_num" type="hidden" id="mcred_memo_num" value="<?php echo $mcred_memo_num;?>">
			  </span></td>
			  <td width="10%" align="right" valign="middle" nowrap><div class="formField2">
				Order Total:
			</div></td>
			  		<td valign="middle" nowrap><span class="formField2">
					<?php echo $memoData[order_total]; ?>
			  			<input name="mcred_order_total" type="hidden" id="mcred_order_total" value="<?php echo $memoData[order_total];?>">
			  		</span></td>
			</tr>
			<tr>
				<td align="right" valign="middle">&nbsp;</td>
				<td valign="middle">&nbsp;</td>
				<td align="right" valign="middle" nowrap><div class="formField3">
					Customer Name:
				</div></td>
				<td align="left" valign="middle" nowrap><?php echo $userData[company];?><span class="formField2">
					<input name="company" type="hidden" id="company" value="<?php echo $userData[company];?>">
				</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="20%" align="right" valign="middle">&nbsp;</td>
				<td valign="middle">&nbsp;</td>
				<td align="right" valign="middle" nowrap><div class="formField3">
				Customer Account: 
			</div></td>
			  <td align="left" valign="middle" nowrap><?php echo $userData[account_num];?><span class="formField2">
			  	<input name="account_num" type="hidden" id="account_num" value="<?php echo $userData[account_num];?>">
			  	<input name="customer_email" type="hidden" id="customer_email" value="<?php echo $userData[email];?>">
			  </span></td>
			</tr>
			<tr>
				<td align="right" valign="middle">&nbsp;</td>
				<td valign="middle">&nbsp;</td>
				<td align="right" valign="middle" nowrap><div class="formField3">
					Patient Reference:
				</div></td>
				<td align="left" valign="middle" nowrap><?php echo $orderData[patient_ref_num];?><span class="formField2">
					<input name="patient_ref_num" type="hidden" id="patient_ref_num" value="<?php echo $orderData[patient_ref_num];?>">
				</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td align="right" valign="middle"><div class="formField2">
					 Value:
				</div></td>
				<td valign="middle">
					<span class="formField2"><?php echo $mcred_abs_amount;?>
					<input name="mcred_abs_amount" type="hidden" id="mcred_abs_amount" value="<?php echo $mcred_abs_amount;?>">
					</span></td>
				<td align="right" valign="middle" nowrap><div class="formField3">
					Patient First Name:
				</div></td>
				<td align="left" valign="middle"><?php echo $orderData[order_patient_first];?><span class="formField2">
					<input name="order_patient_first" type="hidden" id="order_patient_first" value="<?php echo $orderData[order_patient_first];?>">
				</span></td>
			</tr>
			<tr>
				<td align="right" valign="middle"><div class="formField2">
					Reason:
				</div></td>
				<td valign="middle"><?php echo $memoData[mcred_memo_code] . " - " . $memoData[mc_description]; ?>
					<span class="formField2">
					<input name="mcred_memo_code" type="hidden" id="mcred_memo_code" value="<?php echo $memoData[mcred_memo_code];?>">
					<input name="mc_description" type="hidden" id="mc_description" value="<?php echo $memoData[mc_description];?>">
					</span></td>
				<td align="right" valign="middle" nowrap><div class="formField3">
					Patient Last Name:
				</div></td>
				<td align="left" valign="middle"><?php echo $orderData[order_patient_last];?><span class="formField2">
					<input name="order_patient_last" type="hidden" id="order_patient_last" value="<?php echo $orderData[order_patient_last];?>">
				</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td align="right" valign="middle"><div class="formField2">
					Date:
				</div></td>
				<td valign="middle"><?php echo $mcred_date; ?><span class="formField2">
					<input name="mcred_date" type="hidden" id="mcred_date" value="<?php echo $mcred_date;?>">
				</span></td>
				<td align="right" valign="middle" nowrap>&nbsp;</td>
				<td align="left" valign="middle">&nbsp;</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td colspan="4" align="center" valign="middle"><input  type="submit" name="emailMemo" value="Email Memo to Customer" class="formField">
				&nbsp;&nbsp;&nbsp;
					<input type="submit" name="backToMain"  value="Cancel" class="formField"></td>
				</tr>
			</table>
			</form></td>
  </tr>
</table>

 &nbsp;<br>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
