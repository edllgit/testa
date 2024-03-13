<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("../Connections/sec_connect.inc.php");


$type=$_GET[category];

$query="SELECT product_inventory.*,SUM(inventory), prices.product_name as pn, prices.cost FROM product_inventory LEFT JOIN products ON (product_inventory.product_id=products.primary_key) LEFT JOIN prices ON (prices.product_name=products.product_name)
WHERE lab_id='".$_SESSION[labAdminData][primary_key]."' GROUP BY products.product_name";

$catResult=mysql_query($query)	or die ( "Query failed: " . mysql_error());
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<link href="admin_print.css" rel="stylesheet" type="text/css" media="print"/>


</head>

<body>
<table border="0" cellspacing="0" cellpadding="0" width="95%">
<tr valign="top">
	<td width="25%" class="print_zerow"><?php include_once 'adminNav.php'; ?></td>
    <td width="1" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="1" bgcolor="#000000">&nbsp;</td>
    <td width="1" bgcolor="#FFFFFF">&nbsp;</td>
	<td width="75%" class="print_fullw">
	<table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField print_hidden">
	<tr bgcolor="#000000">
		<td width="30" colspan="3" align="center">
        <b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php print($assigned_lab_name); ?> <?php echo $adm_titlemast_prodinv; ?></font></b>        </td>
	</tr>
	</table>
	<form method="get" name="product_search1" id="product_search1" action="<?php print($_SERVER['PHP_SELF']); ?>">
	<table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField print_hidden">
    <tr bgcolor="#DDDDDD">
    	<td width="5%" valign="middle" align="left" nowrap bgcolor="#DDDDDD"><div align="left"><?php echo $adm_product_txt;?></div></td>
    	<td width="5%" align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
        </tr>
    <tr bgcolor="#DDDDDD">
      <td valign="middle" align="left" nowrap bgcolor="#DDDDDD"><select name="product_name" class="formField" id="product_name" >
        <option value="ALL" selected>All Products</option>
        <?php 
				$query="SELECT product_name FROM products WHERE type='stock' GROUP BY product_name ASC"; /* select all openings */
				$result=mysql_query($query)
						or die ("Could not select items");
				while ($listProducts=mysql_fetch_array($result)){
					echo "<option value=\"$listProducts[product_name]\"";
						
					echo">";
					$name=stripslashes($listProducts[product_name]);
					echo "$name</option>";}?>
        </select></td>
      <td align="left" valign="middle" nowrap="nowrap"><input name="button" type="submit" class="formField" id="button" value="Submit"></td>
    </tr>
    </table>
	</form>




 <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td colspan="7" align="center"><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial"><b>STOCK INVENTORY VALUE</b></font></td>
       		  </tr>
            	<tr bgcolor="#DDDDDD">
                <td  align="left" bgcolor="#DDDDDD"><b>Product Name</b></td>
            		<td  align="left" bgcolor="#DDDDDD"><b>Inventory</b></td>
            		<td  align="left"><b>COST</b></td>
            		<td  align="left" bgcolor="#DDDDDD"><b>TOTAL VALUE</b></td>
            	</tr>
            	<?php
				while($catData=mysql_fetch_array($catResult)){
					$count++;
					if (($count%2)==0)
   						$bgcolor="#DDDDDD";
					else 
						$bgcolor="#FFFFFF";
						
						
						$inventValue=$catData[cost] *$catData['SUM(inventory)'];
						$inventValue=money_format('%.2n',$inventValue);
												
						$totalInventValue=$totalInventValue+$inventValue;
						$totalInventValue=money_format('%.2n',$totalInventValue);

					echo "<tr bgcolor=\"$bgcolor\"><td>$catData[pn]</td><td>".$catData['SUM(inventory)']."</td><td>$catData[cost]</td><td>$inventValue</td></tr>";
				}
				echo "<tr bgcolor=\"$bgcolor\"><td></td><td></td><td><b>TOTAL VALUE:</b></td><td><b>$totalInventValue</b></td></tr>";
				?>
				</table>








	</td>
</tr>
</table>
</body>
</html>
