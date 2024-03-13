<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='/labAdmin'>here</a> to login.";
	exit();
}

include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
include("lab_confirmation_func.inc.php");
include("fax_lab_confirm_func.inc.php");
include("../includes/calc_functions.inc.php");
include("../includes/est_ship_date_functions.inc.php");
include("../sales/salesmath.php");


if ($_POST['printit']=="false"){
		$sendPrices="false";
		$printit=false;}
	else{
		$sendPrices="true";
		$printit=true;}


$orderQuery="select * from orders WHERE order_num='$_GET[order_num]' limit 1"; //get order's user id and additional discount
$orderResult=mysql_query($orderQuery)	or die  ('I cannot select items because: ' . mysql_error());
$orderData=mysql_fetch_array($orderResult);
mysql_query("SET CHARACTER SET UTF8");
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
            		<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo 'Print Order';?></font></b></td>
            		</tr>
			<tr>
			  <td height="208">
			<table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField3">
			<?php echo '';?>
			<tr>
					  
			
			</tr>
			<tr>
			  <td width="20%" rowspan="2" align="right" valign="middle"><div class="formField2"><?php echo $adm_ordernumber2_txt;?></div></td>
			  <td width="18%" rowspan="2" valign="middle"><span class="formField2"><?php echo $_GET[order_num];?></span>
			  <?php if ($orderData[redo_order_num]!=0) print "R (".$orderData[redo_order_num].")";?></td>
			  <td colspan="2" align="center" valign="middle" bgcolor="#999999">
              <form action="display_order.php" method="post" name="confirmForm3" id="confirmForm3">
                  <input name="resend_confirm3_p" type="button" id="resend_confirm3_p" value="<?php echo $btn_printorder_txt;?>" class="formField3" onClick="this.form.target='_blank'; this.form.printit.value=true; this.form.submit();"> 
                  <!-- "  document.getElementById('printit_id') -->
                  <input name="printit" type="hidden" id="printit_id" value="false" />
                  <input name="from_send_order_lab_manual" type="hidden" id="from_send_order_lab_manual" value="true">
                  <input name="order_num" type="hidden" id="order_num" value="<?php echo $_GET[order_num];?>">
				  <input name="po_num" type="hidden" id="po_num" value="<?php echo $_GET[po_num];?>">
			    </form></td>
			  </tr>
			
		
			</table>
			</td></tr>
			<tr><td>
			 
			 <?php
			$order_num=$_GET[order_num];	
			$lab_pkey=$_SESSION["lab_pkey"];
			?>
	</td>
  </tr>
</table>
 &nbsp;<br>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
