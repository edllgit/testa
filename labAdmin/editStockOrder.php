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

if ($_POST[update_tray_stock]=="true"){
	$message="<b><font color=\"#FF0000\" size=\"1\" face=\"Helvetica, sans-serif, Arial\"> - ORDER UPDATED</font></b>";
	$_POST[update_tray_stock]="";

	$item_order_num=updateStockTrayOrder($_POST[re_pkey],"RE");
	$item_order_num=updateStockTrayOrder($_POST[le_pkey],"LE");
	updateShippingCost($item_order_num);
}

$re_pkey=$_POST[re_pkey];
$le_pkey=$_POST[le_pkey];

$re_orderQuery="select * from orders WHERE primary_key='$re_pkey'"; //get order's user id
$re_orderResult=mysql_query($re_orderQuery)
	or die  ('I cannot select items because: ' . mysql_error());

$re_orderData=mysql_fetch_array($re_orderResult);

$le_orderQuery="select * from orders WHERE primary_key='$le_pkey'"; //get order's user id
$le_orderResult=mysql_query($le_orderQuery)
	or die  ('I cannot select items because: ' . mysql_error());

$le_orderData=mysql_fetch_array($le_orderResult);
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
           		          STOCK BY TRAY ORDER</font></b><?php echo $message;?></td>
       		  </tr>
			<tr><td>
			<form action="editStockOrder.php" method="post" enctype="multipart/form-data" name="editForm" id="editForm"><table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField2">
			<tr><td width="14%" align="right" class="formField2">Order Number:</td>
			  <td align="left"><?php echo $re_orderData[order_num];?></td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			</tr>
			<tr>
			  <td align="right" bgcolor="#DDDDDD" class="formField2">P.O. Number:</td>
			  <td align="left" bgcolor="#DDDDDD"><?php echo $re_orderData[po_num];?></td>
			  <td bgcolor="#DDDDDD" >&nbsp;</td>
			  <td bgcolor="#DDDDDD" >&nbsp;</td>
			  </tr>
			<tr>
			  <td align="right" bgcolor="#ffffff" class="formField2">Tray Ref: </td>
			  <td align="left" bgcolor="#ffffff"><input name="tray_num" type="text" class="formField2" id="tray_num" value="<?php echo $re_orderData[tray_num];?>" size="12" maxlength="30"></td>
			  <td bgcolor="#ffffff" >&nbsp;</td>
			  <td bgcolor="#ffffff" >&nbsp;</td>
			  </tr>
			<tr>
			  <td align="center" bgcolor="#666666">&nbsp;</td>
			  <td align="center" bgcolor="#666666"><div align="center"><span class="style1">Product Name</span></div></td>
			  <td bgcolor="#666666" ><div align="center" class="style1">Sphere</div></td>
			  <td bgcolor="#666666" ><div align="center" class="style1">Cylinder</div></td>
			</tr>
			<tr>
			  <td align="right">R.E.</td>
			  <td><div align="center">
			    <select name="PRODUCT" class="formText" id="PRODUCT" onChange="	fetchSphere('getSphereBulkStock.php','SPHERE','CYLINDER',this.value)">
		        <?php 
				$query="select product_name from products WHERE type='stock' group by product_name asc"; /* select all openings */
				$result=mysql_query($query)
						or die ("Could not select items");
				while ($listProducts=mysql_fetch_array($result)){
					echo "<option value=\"$listProducts[product_name]\"";
					if ($listProducts[product_name]==$re_orderData[order_product_name]) echo "selected=\"selected\"";
					echo">";
					$name=stripslashes($listProducts[product_name]);
					echo "$name</option>";}?>
		        </select>
			    </div></td>
			  <td ><div align="center">
			    <select name="SPHERE" class="formText" id="SPHERE" onChange="	fetchCylinder('getCylinderBulkStock.php','CYLINDER',PRODUCT.value,this.value)">
			            <?php 
				$query="select sph_base from products WHERE type='stock' AND product_name='$re_orderData[order_product_name]' Group by sph_base desc"; /* select all openings */
				echo $query;
				$result=mysql_query($query)
						or die ("Could not select items");
				while ($listProducts=mysql_fetch_array($result)){
					echo "<option value=\"$listProducts[sph_base]\"";
					if ($listProducts[sph_base]==$re_orderData[re_sphere]) echo "selected=\"selected\"";
					echo">";
					$name=stripslashes($listProducts[sph_base]);
					echo "$name</option>";}?>
			      </select>
			    </div></td>
			  <td  ><div align="center">
			    <select name="CYLINDER" class="formText" id="CYLINDER">
			       <?php 
				$query="select cyl_add from products WHERE type='stock' AND product_name='$re_orderData[order_product_name]' AND sph_base='$orderDate[re_cyl]' Group by cyl_add desc"; /* select all openings */
				$result=mysql_query($query)
						or die ("Could not select items");
				while ($listProducts=mysql_fetch_array($result)){
					echo "<option value=\"$listProducts[cyl_add]\"";
					if ($listProducts[cyl_add]==$re_orderData[re_cyl]) echo "selected=\"selected\"";
					echo">";
					$name=stripslashes($listProducts[cyl_add]);
					echo "$name</option>";}?>
			      </select>
			    </div></td>
			</tr>
			<tr>
			  <td align="right" bgcolor="#DDDDDD">L.E.</td>
			  <td bgcolor="#DDDDDD"><div align="center">
                  <select name="PRODUCT2" class="formText" id="PRODUCT2" onChange="	fetchSphere('getSphereBulkStock.php','SPHERE2','CYLINDER2',this.value)">
                    <?php 
				$query="select product_name from products WHERE type='stock' group by product_name asc"; /* select all openings */
				$result=mysql_query($query)
						or die ("Could not select items");
				while ($listProducts=mysql_fetch_array($result)){
					echo "<option value=\"$listProducts[product_name]\"";
					if ($listProducts[product_name]==$le_orderData[order_product_name]) echo "selected=\"selected\"";
					echo">";
					$name=stripslashes($listProducts[product_name]);
					echo "$name</option>";}?>
                                    </select>
              </div></td>
			  <td bgcolor="#DDDDDD" ><div align="center">
                  <select name="SPHERE2" class="formText" id="SPHERE2" onChange="	fetchCylinder('getCylinderBulkStock.php','CYLINDER2',PRODUCT2.value,this.value)">
                    <?php 
				$query="select sph_base from products WHERE type='stock' AND product_name='$le_orderData[order_product_name]' Group by sph_base desc"; /* select all openings */
				echo $query;
				$result=mysql_query($query)
						or die ("Could not select items");
				while ($listProducts=mysql_fetch_array($result)){
					echo "<option value=\"$listProducts[sph_base]\"";
					if ($listProducts[sph_base]==$le_orderData[re_sphere]) echo "selected=\"selected\"";
					echo">";
					$name=stripslashes($listProducts[sph_base]);
					echo "$name</option>";}?>
                                    </select>
              </div></td>
			  <td bgcolor="#DDDDDD"  ><div align="center">
                  <select name="CYLINDER2" class="formText" id="CYLINDER2">
                    <?php 
				$query="select cyl_add from products WHERE type='stock' AND product_name='$le_orderData[order_product_name]' AND sph_base='$orderDate[re_cyl]' Group by cyl_add desc"; /* select all openings */
				$result=mysql_query($query)
						or die ("Could not select items");
				while ($listProducts=mysql_fetch_array($result)){
					echo "<option value=\"$listProducts[cyl_add]\"";
					if ($listProducts[cyl_add]==$le_orderData[re_cyl]) echo "selected=\"selected\"";
					echo">";
					$name=stripslashes($listProducts[cyl_add]);
					echo "$name</option>";}?>
                                    </select>
              </div></td>
			</tr>
			<tr>
			  <td align="right" bgcolor="#FFFFFF">&nbsp;</td>
			  <td colspan="2" align="center" bgcolor="#FFFFFF"><input name="Cancel" type="button" class="formField2" id="Cancel" value="Cancel" onClick="window.open('report.php', '_top')">			    
			  &nbsp;&nbsp;
			  <input name="Submit" type="submit" class="formField2" value="Update">
			  <input name="update_tray_stock" type="hidden" id="update_tray_stock" value="true">
			  <input name="re_pkey" type="hidden" id="re_pkey" value="<?php echo $_POST[re_pkey];?>">
			  <input name="le_pkey" type="hidden" id="le_pkey" value="<?php echo $_POST[le_pkey];?>"></td>
			  <td bgcolor="#FFFFFF">&nbsp;</td>
			  </tr>
			</table>
			</form>
			</td></tr>
			<tr><td><div class="formField">
		<a href="display_order.php?order_num=<?php echo $re_orderData[order_num];?>&po_num=<?php echo $re_orderData[po_num];?>">Back to Order</a>
	</div></td>
  </tr>
</table>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
