<?php
session_start();
require_once(__DIR__.'/../constants/url.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";
$lab_pkey=$_SESSION["lab_pkey"];
$logo_file=$_SESSION["labAdminData"]["logo_file"];


if ($_REQUEST[mcred_memo_num] <> ''){
$mcred_order_num = $_REQUEST[mcred_memo_num];
}else{
echo '<p>Error: no order number has been submitted. please go back and try again.</p>';	
exit();
}


$queryDetail  = "SELECT * FROM memo_credits WHERE mcred_memo_num = '$mcred_order_num'";
$resultDetail = mysql_query($queryDetail) or die ("Could not find lab list");
$DataDetail   = mysql_fetch_array($resultDetail);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Direct-Lens &mdash; Place Order</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}

-->
</style>
<link href="../dl.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img src="<?php echo constant('DIRECT_LENS_URL'); ?>/logos/<?php echo $_SESSION[labAdminData][logo_file]; ?>"/></td><td align="right"><img src="<?php echo constant('DIRECT_LENS_URL'); ?>/logos/direct-lens_logo.gif" width="200" height="60" /></td>
</tr></table>
	
		<table width="650" border="0" align="center" cellpadding="0" cellspacing="0"><tr>
	<td><div class="header2">Memo Credit for your Direct-Lens Order #: <?php echo $_POST[order_num]; ?></div></td></tr></table>
	
	<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#000099" class="tableHead">MEMO ORDER INFORMATION </td></tr>
	<tr ><td align="right" class="formCellNosides">Memo Order Date:</td><td width="520" class="formCellNosides"><strong>
	<?php echo $DataDetail[mcred_date]; ?>
	</strong></td></tr>
	<tr ><td align="right" class="formCellNosides">Order Number:</td><td width="520" class="formCellNosides"><strong>
	<?php echo $DataDetail[order_num]; ?>
	</strong></td></tr>
	<tr ><td align="right" class="formCellNosides">Order Total:</td><td width="520" class="formCellNosides"><strong>
	<?php echo $DataDetail[mcred_order_total]; ?>
	</strong></td></tr>
	<tr ><td align="right" class="formCellNosides">Customer Name: </td>
    <td width="520" class="formCellNosides"><strong>
	<?php echo $DataDetail[company]; ?>
	</strong></td></tr>
	<tr ><td align="right" class="formCellNosides">Customer Account: </td>
    <td width="520" class="formCellNosides"><strong>
	<?php echo $DataDetail[account_num]; ?>
	</strong></td></tr>
	<tr ><td align="right" class="formCellNosides" nowrap>Patient Reference Number: </td>
    <td width="520" class="formCellNosides"><strong>
	<?php echo $DataDetail[patient_ref_num]; ?>
	</strong></td></tr>
	<tr ><td align="right" class="formCellNosides" nowrap>Patient First Name: </td>
    <td width="520" class="formCellNosides"><strong>
	<?php echo $DataDetail[order_patient_first]; ?>
	</strong></td></tr>
	<tr ><td align="right" class="formCellNosides" nowrap>Patient Last Name: </td>
    <td width="520" class="formCellNosides"><strong>
	<?php echo $DataDetail[order_patient_last]; ?>
	</strong></td></tr>
	<tr ><td align="right" class="formCellNosides">Memo Order Number:</td><td width="520" class="formCellNosides"><strong>
	<?php echo $DataDetail[mcred_memo_num]; ?>
	</strong></td></tr>
	<tr ><td align="right" class="formCellNosides">Memo Order Value:</td><td width="520" class="formCellNosides"><strong>
	<?php echo $DataDetail[mcred_abs_amount]; ?>
	</strong></td></tr>
	<tr ><td align="right" class="formCellNosides">Reason Code:</td><td width="520" class="formCellNosides"><strong>
	<?php echo $DataDetail[mcred_memo_code]; ?>
	 - 
	<?php echo $DataDetail[mc_description]; ?>
	</strong></td></tr></table>

</body>
</html>
