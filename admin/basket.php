<?php
session_start();
if ($_SESSION[adminData][username]==""){
	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("../Connections/sec_connect.inc.php");

if ($_POST[from_coupon_form]=="add"){
	$_POST[from_coupon_form]="";

	$code=$_POST[code];
	$type=$_POST[type];
	$select_by=$_POST[select_by];
	$coating=$_POST[coating];
	
	if ($select_by=="product")
		$product_name=$_POST[product_name];
	else if ($select_by=="collection")
		$collection=$_POST[collection];
	else if ($select_by=="coating"){
		$product_name="";
		$collection="";
	}else if ($select_by=="all"){
		$product_name="";
		$collection="";
		$coating="";
	}

	$end_date=$_POST[end_date];
	$amount=$_POST[amount];
	$description = $_POST[description];
	$query="insert into coupon_codes (description, code,type,date,amount,collection,select_by,product_name, coating) values ('$description','$code','$type','$end_date','$amount','$collection','$select_by','$product_name','$coating')";
	$result=mysql_query($query)		or die ("Could not create new product because " . mysql_error() );
		
}

?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/dhtmlxcalendar.css"></link>
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/skins/dhtmlxcalendar_dhx_skyblue.css"></link>
<script src="../includes/dhtml/codebase/dhtmlxcalendar.js"></script>
<script>

</script>
<link href="admin.css" rel="stylesheet" type="text/css">
</head>

<body onLoad="doOnLoad();">
  <table border="0" cellspacing="0" cellpadding="0" width="100%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td>&nbsp;</td>
<td>
		
		 <?php 
 	$query="SELECT orders.*, lab_name, company FROM orders, labs, accounts  WHERE orders.user_id = accounts.user_id AND labs.primary_key = orders.lab AND order_num = -1 order by user_id";
	$catResult=mysql_query($query)		or die ( "Query failed: " . mysql_error());
	?>
	<div id="displayBox"><table width="100%" border="0" cellpadding="2" cellspacing="0">
            	<tr bgcolor="#000000">
            		<td colspan="8" align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">Orders in the basket</font></b></td>
       		  </tr>
            	<tr bgcolor="#DDDDDD">
            		<td  align="center" nowrap><p><font size="1" face="Arial, Helvetica, sans-serif"><b>Customer</b></font></p></td>
                    <td  align="center" nowrap><font size="1" face="Arial, Helvetica, sans-serif"><b>Date added</b></font></td>
            		<td  align="center" nowrap><font size="1" face="Arial, Helvetica, sans-serif"><b>Company</b></font></td>
            		<td  align="center" nowrap><font size="1" face="Arial, Helvetica, sans-serif"><b>Patient</b></font></td>
            		<td  align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>Patient Ref No</b></font></td>
            		<td  align="center"><b><font size="1" face="Arial, Helvetica, sans-serif">Tray Num</font></b></td>
            		<td  align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>Main Lab</b></font></td>
                    <td  align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>Product Price</b></font></td>
            	</tr>
            	<?php
				while($catData=mysql_fetch_array($catResult)){
					$count++;
					if (($count%2)==0)
   						$bgcolor="#DDDDDD";
					else 
						$bgcolor="#FFFFFF";
						
					echo "<tr bgcolor=\"$bgcolor\">
						  <td align=\"center\"><font size=\"1\" face=\"Arial, Helvetica, sans-serif\">$catData[user_id]</td>
						  <td align=\"center\"><font size=\"1\" face=\"Arial, Helvetica, sans-serif\">$catData[order_item_date]</td>
						  <td align=\"center\"><font size=\"1\" face=\"Arial, Helvetica, sans-serif\">$catData[company]</td>
						  <td align=\"center\"><font size=\"1\" face=\"Arial, Helvetica, sans-serif\">$catData[order_patient_first]&nbsp;$catData[order_patient_last]</td>
						  <td align=\"center\"><font size=\"1\" face=\"Arial, Helvetica, sans-serif\">$catData[patient_ref_num]</td>
						  <td align=\"center\"><font size=\"1\" face=\"Arial, Helvetica, sans-serif\">$catData[tray_num]</td>
						  <td align=\"center\"><font size=\"1\" face=\"Arial, Helvetica, sans-serif\">$catData[lab_name]</td>
						  <td align=\"center\"><font size=\"1\" face=\"Arial, Helvetica, sans-serif\">$catData[order_product_price]$</td>
						  </tr>";
				}?>
				</table></div>      				
</td>
</tr>
</table>
<p>&nbsp;</p>
</body>
</html>