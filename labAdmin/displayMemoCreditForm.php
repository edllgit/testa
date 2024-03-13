<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
$lab_pkey=$_SESSION["lab_pkey"];
$logo_file=$_SESSION["labAdminData"]["logo_file"];
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
<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img src="http://www.direct-lens.com/logos/<?php echo $_SESSION[labAdminData][logo_file]; ?>"/></td><td align="right"><img src="http://www.direct-lens.com/logos/direct-lens_logo.gif" width="200" height="60" /></td>
</tr></table>
	
		<table width="650" border="0" align="center" cellpadding="0" cellspacing="0"><tr>
	<td><div class="header2">Memo Credit for your Direct-Lens Order #: <?php echo $_POST[order_num]; ?></div></td></tr></table>
	
	<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#000099" class="tableHead">MEMO ORDER INFORMATION </td></tr>
	<tr ><td align="right" class="formCellNosides">Memo Order Date:</td><td width="520" class="formCellNosides"><strong>
	<?php echo $_POST[mcred_date]; ?>
	</strong></td></tr>
	<tr ><td align="right" class="formCellNosides">Order Number:</td><td width="520" class="formCellNosides"><strong>
	<?php echo $_POST[order_num]; ?>
	</strong></td></tr>
	<tr ><td align="right" class="formCellNosides">Order Total:</td><td width="520" class="formCellNosides"><strong>
	<?php echo $_POST[mcred_order_total]; ?>
	</strong></td></tr>
	<tr ><td align="right" class="formCellNosides">Customer Name: </td>
    <td width="520" class="formCellNosides"><strong>
	<?php echo $_POST[company]; ?>
	</strong></td></tr>
	<tr ><td align="right" class="formCellNosides">Customer Account: </td>
    <td width="520" class="formCellNosides"><strong>
	<?php echo $_POST[account_num]; ?>
	</strong></td></tr>
	<tr ><td align="right" class="formCellNosides" nowrap>Patient Reference Number: </td>
    <td width="520" class="formCellNosides"><strong>
	<?php echo $_POST[patient_ref_num]; ?>
	</strong></td></tr>
	<tr ><td align="right" class="formCellNosides" nowrap>Patient First Name: </td>
    <td width="520" class="formCellNosides"><strong>
	<?php echo $_POST[order_patient_first]; ?>
	</strong></td></tr>
	<tr ><td align="right" class="formCellNosides" nowrap>Patient Last Name: </td>
    <td width="520" class="formCellNosides"><strong>
	<?php echo $_POST[order_patient_last]; ?>
	</strong></td></tr>
	<tr ><td align="right" class="formCellNosides">Memo Order Number:</td><td width="520" class="formCellNosides"><strong>
	<?php echo $_POST[mcred_memo_num]; ?>
	</strong></td></tr>
	<tr ><td align="right" class="formCellNosides">Memo Order Value:</td><td width="520" class="formCellNosides"><strong>
	<?php echo $_POST[mcred_abs_amount]; ?>
	</strong></td></tr>
	<tr ><td align="right" class="formCellNosides">Reason Code:</td><td width="520" class="formCellNosides"><strong>
	<?php echo $_POST[mcred_memo_code]; ?>
	 - 
	<?php echo $_POST[mc_description]; ?>
	</strong></td></tr></table>

</body>
</html>
