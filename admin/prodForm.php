<?php
	$query="SELECT * FROM prices where primary_key = '$pkey'";
	$prodResult=mysqli_query($con,$query)or die ("Could not find product");
	$productData=mysqli_fetch_array($prodResult,MYSQLI_ASSOC);

?>
<form name="form4" method="post" action="update_product.php"  enctype="multipart/form-data">
            
  <table width="100%" border="0" cellpadding="2" cellspacing="0">
    <tr bgcolor="#000000"> 
      <td colspan="4" align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial"><?php print "$heading"; ?></font></b></td>
    </tr>
    <tr bgcolor="#DDDDDD"> 
      <td align="right" class="formText">Product Name:</td>
      <td align="left"  class="formText"><?php echo "<b>".$productData['product_name']."</b>";?></td>
      <td align="right" nowrap="nowrap" class="formText">Collection:</td>
      <td align="left" nowrap="nowrap"><select name="stock_collections_id" class="formText" id="stock_collections_id" >
        <?php 
				$query="SELECT * FROM stock_collections"; /* select all openings */
				$result=mysqli_query($con,$query) or die ("Could not select items");
				while ($listCollection=mysqli_fetch_array($result,MYSQLI_ASSOC)){
					print "<option value=\"$listCollection[stock_collections_id]\"";
					if ($productData[stock_collections_id]==$listCollection[stock_collections_id]) print "selected=\"selected\"";
					print">";
					$name=stripslashes($listCollection['stock_collection']);
					print "$name</option>";}?>
      </select></td>
    </tr>
    <tr bgcolor="#DDDDDD">
      <td align="right" nowrap="nowrap" bgcolor="#FFFFFF" class="formText">Price
          USA:</td>
      <td align="left" bgcolor="#FFFFFF" class="formText">$
      <input name="price" type="text" id="retail_price" value="<?php print "$productData[price]"; ?>" size="10" />
      </font></td>
      <td align="right" valign="middle" nowrap="nowrap"  class="formText" bgcolor="#FFFFFF">ABBE:</td>
      <td align="left" nowrap="nowrap" bgcolor="#FFFFFF" class="formText">
        <input name="abbe" type="text" id="product_model" value="<?php print "$productData[abbe]"; ?>" size="10" /></td>
    </tr>
    <tr bgcolor="#DDDDDD">
      <td align="right" nowrap="nowrap" bgcolor="#DDDDDD" class="formText">Price
      Canada:</td>
      <td align="left" bgcolor="#DDDDDD" class="formText">
        $

      <input name="price_can" type="text" id="retail_price" value="<?php print "$productData[price_can]"; ?>" size="10" />
      </td>
      <td align="right" valign="middle"  class="formText" bgcolor="#DDDDDD">Density:</td>
      <td align="left"  class="formText" bgcolor="#DDDDDD">
        <input name="density" type="text" id="weight" value="<?php print "$productData[density]"; ?>" size="10" />
</td>
    </tr>
    <tr bgcolor="#DDDDDD">
      <td align="right"  class="formText" bgcolor="#FFFFFF">Price
      Euro:</td>
      <td align="left" bgcolor="#FFFFFF" class="formText">$
      <input name="price_eur" type="text" id="retail_price2" value="<?php print "$productData[price_eur]"; ?>" size="10" /></td>
      <td align="right" valign="middle"  class="formText" bgcolor="#FFFFFF">&nbsp;</td>
      <td align="left"  class="formText" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <tr bgcolor="#DDDDDD">
      <td align="right"  class="formText" bgcolor="#DDDDDD">Cost (USD):</td>
      <td align="left" bgcolor="#DDDDDD" class="formText">$
        <input name="cost" type="text" id="cost" value="<?php print "$productData[cost]"; ?>" size="10" /></td>
      <td align="right" valign="middle"  class="formText" bgcolor="#DDDDDD"><p>&nbsp;</p></td>
      <td align="left"  class="formText" bgcolor="#DDDDDD">&nbsp;</td>
    </tr>
    <tr bgcolor="#DDDDDD"> 
      <td align="right" valign="top"  class="formText" bgcolor="#FFFFFF">Description:</td>
      <td colspan="3" align="left" bgcolor="#FFFFFF"  class="formText">
        <textarea name="description" cols="50" rows="3" id="product_model"><?php print "$productData[description]"; ?></textarea>
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="4" align="center" bgcolor="#DDDDDD"> 
        <input type="hidden" name="pkey" value="<?php print "$productData[primary_key]"; ?>"> 
        <input type="submit" name="editProduct" id="edit" value="Update Product"> 
        &nbsp; <input name="deleteProduct" type="submit" id="delete" value="Delete Product"> 
        &nbsp; <input name="cancel" type="button" id="cancel" value="No Change" onclick="window.open('adminHome.php', '_top')"> 
      &nbsp;</td>
    </tr>
  </table>
</form>
