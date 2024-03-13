<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("edit_order_functions.inc.php");
include("../Connections/sec_connect.inc.php");
include("../includes/calc_functions.inc.php");



if ($_POST[re_pkey]<> '')
$pkey=$_POST[re_pkey];


if ($_POST[update_bulk_stock]=="true"){
	$pkey=$_POST[pkey];
	$message="<b><font color=\"#FF0000\" size=\"1\" face=\"Helvetica, sans-serif, Arial\"> - ORDER UPDATED</font></b>";
	$_POST[update_bulk_stock]="";

$queryOrdNum    = "SELECT order_num FROM orders WHERE primary_key = " . $pkey;
$resultOrderNum = mysql_query($queryOrdNum)		or die ( "Query failed: " . mysql_error().$queryOrdNum );
$DataOrderNum   = mysql_fetch_array($resultOrderNum);


	if ($_POST[special_instructions] <> '0')
	{
		//If there is a tray, and it is different from 0, we update it on the whole order	
		$special_instructions=$_POST[special_instructions];
	    if ($DataOrderNum[order_num] <> '')
		{
			$queryUpdateTray="UPDATE orders SET ";
			$queryUpdateTray.="special_instructions ='$special_instructions'";
			$queryUpdateTray.=" WHERE order_num=$DataOrderNum[order_num]";
			$result=mysql_query($queryUpdateTray)		or die ( "Query failed: " . mysql_error());
	    }
					
	}//End if there is a tray num
				
}


$orderQuery="select * from orders WHERE primary_key='$pkey'"; //get order's user id
$orderResult=mysql_query($orderQuery)	or die  ('I cannot select items because: ' . mysql_error());
$orderData=mysql_fetch_array($orderResult);
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<script src="formFunctionsBulkStock.js" type="text/javascript"></script>
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
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">EDIT
            		      BULK STOCK  ORDER</font></b><?php echo $message;?></td>
       		  </tr>
			<tr><td>
			<form action="editFrameOrder.php" method="post" enctype="multipart/form-data" name="editForm" id="editForm"><table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField2">
			<tr><td width="15%" align="right" class="formField2">Order Number:</td>
			  <td width="33%" align="left"><?php echo $orderData[order_num];?></td>
			  <td width="31%">&nbsp;</td>
			  <td width="21%">&nbsp;</td>
			</tr>
            
            <tr><td width="15%" align="right" class="formField2">Pkey:</td>
			  <td width="33%" align="left"><?php echo $orderData[primary_key];?></td>
			  <td width="31%">&nbsp;</td>
			  <td width="21%">&nbsp;</td>
			</tr>
			
              
              <tr>
			  <td align="right" bgcolor="#DDDDDD" class="formField2">Special Instructions:</td>
			  <td align="left" bgcolor="#DDDDDD"><input name="special_instructions" type="text" class="formField2" id="special_instructions" value="<?php echo $orderData[special_instructions];?>" size="60" maxlength="60"></td>
			  <td bgcolor="#DDDDDD" >&nbsp;</td>
			  <td bgcolor="#DDDDDD" >&nbsp;</td>
			  </tr>
              

			<tr>
			  <td align="right" bgcolor="#FFFFFF">&nbsp;</td>
			  <td colspan="2" align="center" bgcolor="#FFFFFF"><input name="Submit" type="submit" class="formField2" value="Update">
			  <input name="update_bulk_stock" type="hidden" id="update_bulk_stock" value="true">
			  <input name="pkey" type="hidden" id="pkey" value="<?php echo $_POST[re_pkey];?>"></td>
			  <td bgcolor="#FFFFFF">&nbsp;</td>
			  </tr>
			</table>
			</form>
			</td></tr>
			<tr><td><div class="formField">
		<a href="display_order.php?order_num=<?php echo $orderData[order_num];?>&po_num=<?php echo $orderData[po_num];?>">Back to Order</a>
	</div></td>
  </tr>
</table>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>