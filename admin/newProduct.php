<?php
session_start();
if ($_SESSION[adminData][username]==""){
	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("../Connections/sec_connect.inc.php");

$dbh=mysql_connect($mysql_host, $mysql_user, $mysql_password) or die ("I cannot connect to the database because: " . mysql.error()); mysql_select_db($mysql_db);

If ($dbh==FALSE) {
echo "Connection to database has failed.";
exit();
}

$query="SELECT product_name FROM prices"; /* select all openings */
$result=mysql_query($query)
						or die ("Could not select items");
$products_array=array();
while ($productItem=mysql_fetch_array($result)){
	array_push($products_array,$productItem['product_name']);
}
					
?>
<html>
<head>
<title>Direct-Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<link href="admin.css" rel="stylesheet" type="text/css">
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
  		<td width="75%"><form action="update_product.php" method="post" enctype="multipart/form-data" name="form4">
  		  <table width="100%" border="0" cellpadding="2" cellspacing="0">
            <tr bgcolor="#000000">
              <td colspan="4" align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">DIRECT-LENS ADMIN UPDATE PRODUCT FORM</font></b></td>
            </tr>
            <tr bgcolor="#DDDDDD">
              <td align="right" nowrap><p> <font size="1" face="Arial, Helvetica, sans-serif">Product Name:</font></p></td>
              <td align="left" nowrap><select name="product_name" class="formText" id="product_name" >
                <option value="-" selected>Select a Product Name</option>
                <?php 
				$query="SELECT product_name FROM products WHERE type='stock' GROUP BY product_name ASC"; /* select all openings */
				$result=mysql_query($query)
						or die ("Could not select items");
				while ($listProducts=mysql_fetch_array($result)){
					print "<option value=\"$listProducts[product_name]\"";
					
					foreach($products_array as $v){
							if ($listProducts[product_name]==$v) print "disabled=\"disabled\"";
						}
						
					print">";
					$name=stripslashes($listProducts[product_name]);
					print "$name</option>";}?>
                                                                      </select></td>
              <td align="right" nowrap class="formText">Collection:</td>
              <td align="left" nowrap><select name="stock_collections_id" class="formText" id="stock_collections_id" >
                <?php 
				$query="SELECT * FROM stock_collections"; /* select all openings */
				$result=mysql_query($query)
						or die ("Could not select items");
				while ($listCollection=mysql_fetch_array($result)){
					print "<option value=\"$listCollection[stock_collections_id]\"";

					print">";
					$name=stripslashes($listCollection['stock_collection']);
					print "$name</option>";}?>
              </select></td>
            </tr>
            <tr bgcolor="#DDDDDD">
              <td align="right" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">Price
                  USA:</font></td>
              <td align="left" bgcolor="#FFFFFF"><font size="1">$ </font><font size="1">
              <input name="price" type="text" id="retail_price" value="" size="10" />
              </font></td>
              <td align="right" valign="middle" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">ABBE:</font></td>
              <td align="left" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1">
                <input name="abbe" type="text" id="product_model" value="" size="10" />
              </font></td>
            </tr>
            <tr bgcolor="#DDDDDD">
              <td align="right" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">Price
              Canada:</font></td>
              <td align="left" bgcolor="#DDDDDD"><font size="1">
                $
                <input name="price_can" type="text" id="retail_price" size="10" />
              </font></td>
              <td align="right" valign="middle" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">Density:</font></td>
              <td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1">
                <input name="density" type="text" id="weight" value="" size="10" />
              </font></td>
            </tr>
            <tr bgcolor="#DDDDDD">
              <td align="right" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">Price
              Euro:</font></td>
              <td align="left" bgcolor="#FFFFFF"><font size="1">
                $
                <input name="price_eur" type="text" id="retail_price2" size="10" />
              </font></td>
              <td align="right" nowrap="nowrap" bgcolor="#FFFFFF">&nbsp;</td>
              <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
            </tr>
            <tr bgcolor="#DDDDDD">
              <td align="right" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">Cost (USD):</font></td>
              <td align="left" bgcolor="#DDDDDD"><font size="1">$
                <input name="cost" type="text" id="retail_price" size="10" />
              </font></td>
              <td align="right" nowrap="nowrap" bgcolor="#DDDDDD">&nbsp;</td>
              <td align="left" bgcolor="#DDDDDD">&nbsp;</td>
            </tr>
            <tr bgcolor="#DDDDDD">
              <td align="right" valign="top" nowrap bgcolor="#FFFFFF"><p><font size="1" face="Arial, Helvetica, sans-serif">Description:</font></p></td>
              <td colspan="3" align="left" bgcolor="#FFFFFF"><font size="1">
                <textarea name="description" cols="50" rows="3" id="product_model"></textarea>
              </font></td>
            </tr>
            <tr bgcolor="#FFFFFF">
              <td colspan="4" align="center" bgcolor="#DDDDDD"> 
              <input type="submit" name="createProduct" id="edit" value="Create Product"> 
              &nbsp; <input name="cancel" type="button" id="cancel" value="Cancel" onClick="window.open('adminHome.php', '_top')">            </td>
            </tr>
          </table>
  		</form>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
