<?php

if (!$_POST[createProduct] == "Create Product"){
	$query="select * from exclusive where primary_key = '$pkey'";
	$prodResult=mysql_query($query)
		or die ("Could not find product");
	$productData=mysql_fetch_array($prodResult);

}
?>
<link href="admin.css" rel="stylesheet" type="text/css" />

  <form name="form4" method="post" action="update_exclusive_product.php"  enctype="multipart/form-data">
    <table width="100%" border="0" cellpadding="2" cellspacing="0">
      <tr bgcolor="#000000">
        <td colspan="6" align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial"><?php print "$heading"; ?></font></b></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td width="16%" align="right" nowrap="nowrap"><p> <font size="1" face="Arial, Helvetica, sans-serif">Product
          Name:</font></p></td>
        <td colspan="3" align="left" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1">
          <input name="product_name" type="text" class="formText" id="product_make" value="<?php print "$productData[product_name]"; ?>" size="75" />
        </font></td>
        <td align="right" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">Exclusive
            Collection:</font></td>
        <td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><select name="collection" class="formText" id="collection">
                  <option value="Other" selected="selected">Select a Collection</option>
  <?php
  $query="select collection_name from liste_collection_info ORDER BY collection_name asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
 while ($listItem=mysql_fetch_array($result)){
  
  print "<option value=\"$listItem[collection_name]\"";
  
 if ($productData[collection]=="$listItem[collection_name]") 
 print "selected=\"selected\"";
 print ">";
 $name=stripslashes($listItem[collection_name]);
 print "$name</option>";}?>
        </select></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Helvetica, sans-serif, Arial">Manufacturer:</font></td>
        <td width="38%" colspan="3" align="left" bgcolor="#FFFFFF"><select name="manufacturer" class="formText" id="manufacturer">
  <?php
  $query="select manufacturer from exclusive group by manufacturer asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
 while ($listItem=mysql_fetch_array($result)){
  
  print "<option value=\"$listItem[manufacturer]\"";
  
 if ($productData[manufacturer]=="$listItem[manufacturer]") 
 print "selected=\"selected\"";
 print ">";
 $name=stripslashes($listItem[manufacturer]);
 print "$name</option>";}?>
        </select></td>
        <td width="18%" align="right" valign="middle" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">ABBE:</font></td>
        <td width="28%" align="left" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1">
          <input name="abbe" type="text" class="formText" id="product_model" value="<?php print "$productData[abbe]"; ?>" size="10" />
        </font></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">Index:</font></td>
        <td colspan="3" align="left" bgcolor="#DDDDDD"><font size="1">
          <input name="index_v" type="text" class="formText" id="product_model" value="<?php print "$productData[index_v]"; ?>" size="10" />
        </font></td>
        <td align="right" valign="middle" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">Density:</font></td>
        <td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1">
          <input name="density" type="text" class="formText" id="weight" value="<?php print "$productData[density]"; ?>" size="10" />
        </font></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF"><font size="2">Lens category:</font></td>
        <td colspan="3" align="left" nowrap="nowrap" bgcolor="#FFFFFF">

<?php  
$queryLens = "Select lens_category from exclusive where primary_key = $productData[primary_key]";
$resultLens=mysql_query($queryLens)		or die ("Could not select items");
$DataLens=mysql_fetch_array($resultLens);
$lenscatego = "$DataLens[lens_category]"; 
?>
 
<select name="lens_category">
<option  <?php if ($lenscatego == 'sv') echo 'selected'; ?> value="sv">Sv</option>
<option  <?php if ($lenscatego == 'glass') echo 'selected'; ?> value="glass">Glass</option>
<option  <?php if ($lenscatego == 'bifocal') echo 'selected'; ?> value="bifocal">Bi-focal</option>
<option  <?php if ($lenscatego == 'prog cl') echo 'selected'; ?> value="prog cl">Progressive Classic</option>
<option  <?php if ($lenscatego == 'prog ds') echo 'selected'; ?> value="prog ds">Progressive DS</option>
<option  <?php if ($lenscatego == 'prog ff') echo 'selected'; ?> value="prog ff">Progressive FF</option>
<option  <?php if ($lenscatego == '') echo 'selected'; ?> value="">None</option>
</select>
        </td>
        <td align="right" valign="middle" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">Product
            Status:</font></td>
        <td align="left" valign="middle" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">Active:</font>
            <input name="prod_status" type="checkbox" id="prod_status" value="active" <?php if ($productData[prod_status]=="active"){print "checked=\"checked\"";} ?>/></td>
            
           
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">Description:</font></td>
        <td  align="left" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1">
          <textarea name="description" cols="50" rows="6" class="formText" id="product_model"><?php print "$productData[description]"; ?></textarea>
        </font></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
          <td align="right" valign="middle" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">Tintable:</font>
            <input name="tintable" type="checkbox" id="tintable" value="yes" <?php if ($productData[tintable]=="yes"){print "checked=\"checked\"";} ?>/></td>
      </tr>
 

      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">Product
            Code:</font></td>
        <td align="left" bgcolor="#FFFFFF"><font size="1">
          <input name="product_code" type="text" class="formText" id="product_code" value="<?php print "$productData[product_code]"; ?>" size="30" />
        </font></td>
          <td colspan="2"  align="left" bgcolor="#FFFFFF"><font size="1">
          <font size="1" face="Arial, Helvetica, sans-serif">Opti points bonus:</font><input name="optipoints_bonus" type="text" class="formText" id="optipoints_bonus" value="<?php print "$productData[optipoints_bonus]"; ?>" size="3" />
        </font></td>
        <td align="right" valign="middle" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">Color Code:</font></td>
        <td align="left" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1">
          <input name="color_code" type="text" class="formText" id="color_code" value="<?php print "$productData[color_code]"; ?>" size="10" />
        </font></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">Price
        USA:</font></td>
        <td align="left" bgcolor="#DDDDDD"><font size="1">$
            <input name="price" type="text" class="formText" id="retail_price" value="<?php print "$productData[price]"; ?>" size="6" />
        </font></td>
        <td align="right"><font size="1" face="Arial, Helvetica, sans-serif">E-Lab Price
          USA:</font></td>
        <td align="left"><font size="1">$
          <input name="e_lab_us_price" type="text" class="formText" id="e_lab_us_price" value="<?php print "$productData[e_lab_us_price]"; ?>" size="6" />
        </font></td>
        <td align="right" valign="middle" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Helvetica, sans-serif, Arial">Coating:</font></td>
        <td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><select name="coating" class="formText" id="coating">
  <?php
  $query="select coating from exclusive group by coating asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
 while ($listItem=mysql_fetch_array($result)){
  
  print "<option value=\"$listItem[coating]\"";
  
 if ($productData[coating]=="$listItem[coating]") 
 print "selected=\"selected\"";
 print ">";
 $name=stripslashes($listItem[coating]);
 print "$name</option>";}?>
        </select></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">Price
        Canada:</font></td>
        <td align="left" bgcolor="#FFFFFF"><font size="1">$
            <input name="price_can" type="text" class="formText" id="retail_price" value="<?php print "$productData[price_can]"; ?>" size="6" />
        </font></td>
            <td align="right" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">E-Lab Price
              Canada:</font></td>
        <td align="left" bgcolor="#FFFFFF"><font size="1">$
          <input name="e_lab_can_price" type="text" class="formText" id="e_lab_can_price" value="<?php print "$productData[e_lab_can_price]"; ?>" size="6" />
        </font></td>
        <td align="right" valign="middle" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Helvetica, sans-serif, Arial">Photochromatic:</font></td>
        <td align="left" nowrap="nowrap" bgcolor="#FFFFFF"><select name="photo" class="formField" id="photo">
  <?php
  $query="select photo from exclusive group by photo asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
 while ($listItem=mysql_fetch_array($result)){
  
  print "<option value=\"$listItem[photo]\"";
  
 if (strtoupper($productData[photo])==strtoupper($listItem[photo])) 
 print "selected=\"selected\"";
 print ">";
 $name=stripslashes($listItem[photo]);
 print "$name</option>";}?>
        </select></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">Price
        Euro:</font></td>
        <td colspan="1" align="left" bgcolor="#DDDDDD"><font size="1">$
            <input name="price_eur" type="text" class="formText" id="retail_price" value="<?php print "$productData[price_eur]"; ?>" size="6" />
        </font></td>
        
         <td align="right" valign="middle" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Helvetica, sans-serif, Arial">Min Height:</font></td>
        <td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><input name="min_height" type="text" class="formText" id="min_height" value="<?php print "$productData[min_height]"; ?>" size="6" />
        
        
        <td align="right" valign="middle" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Helvetica, sans-serif, Arial">Polarization:</font></td>
        <td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><select name="polar" class="formText" id="polar">
  <?php
  $query="select polar from exclusive group by polar asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
 while ($listItem=mysql_fetch_array($result)){
  
  print "<option value=\"$listItem[polar]\"";
  
 if (strtoupper($productData[polar])==strtoupper($listItem[polar])) 
 print "selected=\"selected\"";
 print ">";
 $name=stripslashes($listItem[polar]);
 print "$name</option>";}?>
        </select></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#FFFFFF"><p><font size="1" face="Helvetica, sans-serif, Arial">Sphere
          Max: </font></p></td>
        <td colspan="1" align="left" bgcolor="#FFFFFF"><select name="sphere_max" class="formText" id="sphere_max">
          <option selected="selected">Select Sphere Max</option>
          

                  <option value="22.00" <?php if($productData[sphere_max]=='22.00') print " selected"; ?>>+22.00</option>
                  <option value="21.75" <?php if($productData[sphere_max]=='21.75') print " selected"; ?>>+21.75</option>
                  <option value="21.50" <?php if($productData[sphere_max]=='21.50') print " selected"; ?>>+21.50</option>
                  <option value="21.25" <?php if($productData[sphere_max]=='21.25') print " selected"; ?>>+21.25</option>
                  <option value="21.00" <?php if($productData[sphere_max]=='21.00') print " selected"; ?>>+21.00</option>
                  <option value="20.75" <?php if($productData[sphere_max]=='20.75') print " selected"; ?>>+20.75</option>
                  <option value="20.50" <?php if($productData[sphere_max]=='20.50') print " selected"; ?>>+20.50</option>
                  <option value="20.25" <?php if($productData[sphere_max]=='20.25') print " selected"; ?>>+20.25</option>         
                  <option value="20.00" <?php if($productData[sphere_max]=='20.00') print " selected"; ?>>+20.00</option>
                  <option value="19.75" <?php if($productData[sphere_max]=='19.75') print " selected"; ?>>+19.75</option>
                  <option value="19.50" <?php if($productData[sphere_max]=='19.50') print " selected"; ?>>+19.50</option>
                  <option value="19.25" <?php if($productData[sphere_max]=='19.25') print " selected"; ?>>+19.25</option>
                  <option value="19.00" <?php if($productData[sphere_max]=='19.00') print " selected"; ?>>+19.00</option>
                  <option value="18.75" <?php if($productData[sphere_max]=='18.75') print " selected"; ?>>+18.75</option>
                  <option value="18.50" <?php if($productData[sphere_max]=='18.50') print " selected"; ?>>+18.50</option>
                  <option value="18.25" <?php if($productData[sphere_max]=='18.25') print " selected"; ?>>+18.25</option>
                  <option value="18.00" <?php if($productData[sphere_max]=='18.00') print " selected"; ?>>+18.00</option>
                  <option value="17.75" <?php if($productData[sphere_max]=='17.75') print " selected"; ?>>+17.75</option>
                  <option value="17.50" <?php if($productData[sphere_max]=='17.50') print " selected"; ?>>+17.50</option>
                  <option value="17.25" <?php if($productData[sphere_max]=='17.25') print " selected"; ?>>+17.25</option>
                  <option value="17.00" <?php if($productData[sphere_max]=='17.00') print " selected"; ?>>+17.00</option>
                  <option value="16.75" <?php if($productData[sphere_max]=='16.75') print " selected"; ?>>+16.75</option>
                  <option value="16.50" <?php if($productData[sphere_max]=='16.50') print " selected"; ?>>+16.50</option>
                  <option value="16.25" <?php if($productData[sphere_max]=='16.25') print " selected"; ?>>+16.25</option>
                  <option value="16.00" <?php if($productData[sphere_max]=='16.00') print " selected"; ?>>+16.00</option>
                  <option value="15.75" <?php if($productData[sphere_max]=='15.75') print " selected"; ?>>+15.75</option>
                  <option value="15.50" <?php if($productData[sphere_max]=='15.50') print " selected"; ?>>+15.50</option>
                  <option value="15.25" <?php if($productData[sphere_max]=='15.25') print " selected"; ?>>+15.25</option>
                  <option value="15.00" <?php if($productData[sphere_max]=='15.00') print " selected"; ?>>+15.00</option>
                  <option value="14.75" <?php if($productData[sphere_max]=='14.75') print " selected"; ?>>+14.75</option>
                  <option value="14.50" <?php if($productData[sphere_max]=='14.50') print " selected"; ?>>+14.50</option>
                  <option value="14.25" <?php if($productData[sphere_max]=='14.25') print " selected"; ?>>+14.25</option>
                  <option value="14.00" <?php if($productData[sphere_max]=='14.00') print " selected"; ?>>+14.00</option>
                  <option value="13.75" <?php if($productData[sphere_max]=='13.75') print " selected"; ?>>+13.75</option>
                  <option value="13.50" <?php if($productData[sphere_max]=='13.50') print " selected"; ?>>+13.50</option>
                  <option value="13.25" <?php if($productData[sphere_max]=='13.25') print " selected"; ?>>+13.25</option>
                  <option value="13.00" <?php if($productData[sphere_max]=='13.00') print " selected"; ?>>+13.00</option>
                  <option value="12.75" <?php if($productData[sphere_max]=='12.75') print " selected"; ?>>+12.75</option>
                  <option value="12.50" <?php if($productData[sphere_max]=='12.50') print " selected"; ?>>+12.50</option>
                  <option value="12.25" <?php if($productData[sphere_max]=='12.25') print " selected"; ?>>+12.25</option>
                  <option value="12.00" <?php if($productData[sphere_max]=='12.00') print " selected"; ?>>+12.00</option>
                  <option value="11.75" <?php if($productData[sphere_max]=='11.75') print " selected"; ?>>+11.75</option>
                  <option value="11.50" <?php if($productData[sphere_max]=='11.50') print " selected"; ?>>+11.50</option>
                  <option value="11.25" <?php if($productData[sphere_max]=='11.25') print " selected"; ?>>+11.25</option>
                  <option value="11.00" <?php if($productData[sphere_max]=='11.00') print " selected"; ?>>+11.00</option>
                  <option value="10.75" <?php if($productData[sphere_max]=='10.75') print " selected"; ?>>+10.75</option>
                  <option value="10.50" <?php if($productData[sphere_max]=='10.50') print " selected"; ?>>+10.50</option>
                  <option value="10.25" <?php if($productData[sphere_max]=='10.25') print " selected"; ?>>+10.25</option>
                  <option value="10.00" <?php if($productData[sphere_max]=='10.00') print " selected"; ?>>+10.00</option>
                  <option value="9.75" <?php if($productData[sphere_max]=='9.75') print " selected"; ?>>+9.75</option>
                  <option value="9.50" <?php if($productData[sphere_max]=='9.50') print " selected"; ?>>+9.50</option>
                  <option value="9.25" <?php if($productData[sphere_max]=='9.25') print " selected"; ?>>+9.25</option>
                  <option value="9.00" <?php if($productData[sphere_max]=='9.00') print " selected"; ?>>+9.00</option>
                  <option value="8.75" <?php if($productData[sphere_max]=='8.75') print " selected"; ?>>+8.75</option>
                  <option value="8.50" <?php if($productData[sphere_max]=='8.50') print " selected"; ?>>+8.50</option>
                  <option value="8.25" <?php if($productData[sphere_max]=='8.25') print " selected"; ?>>+8.25</option>
                  <option value="8.00" <?php if($productData[sphere_max]=='8.00') print " selected"; ?>>+8.00</option>
                  <option value="7.75" <?php if($productData[sphere_max]=='7.75') print " selected"; ?>>+7.75</option>
                  <option value="7.50" <?php if($productData[sphere_max]=='7.50') print " selected"; ?>>+7.50</option>
                  <option value="7.25" <?php if($productData[sphere_max]=='7.25') print " selected"; ?>>+7.25</option>
                  <option value="7.00" <?php if($productData[sphere_max]=='7.00') print " selected"; ?>>+7.00</option>
                  <option value="6.75" <?php if($productData[sphere_max]=='6.75') print " selected"; ?>>+6.75</option>
                  <option value="6.50" <?php if($productData[sphere_max]=='6.50') print " selected"; ?>>+6.50</option>
                  <option value="6.25" <?php if($productData[sphere_max]=='6.25') print " selected"; ?>>+6.25</option>
                  <option value="6.00" <?php if($productData[sphere_max]=='6.00') print " selected"; ?>>+6.00</option>
                  <option value="5.75" <?php if($productData[sphere_max]=='5.75') print " selected"; ?>>+5.75</option>
                  <option value="5.50" <?php if($productData[sphere_max]=='5.50') print " selected"; ?>>+5.50</option>
                  <option value="5.25" <?php if($productData[sphere_max]=='5.25') print " selected"; ?>>+5.25</option>
                  <option value="5.00" <?php if($productData[sphere_max]=='5.00') print " selected"; ?>>+5.00</option>
                  <option value="4.75" <?php if($productData[sphere_max]=='4.75') print " selected"; ?>>+4.75</option>
                  <option value="4.50" <?php if($productData[sphere_max]=='4.50') print " selected"; ?>>+4.50</option>
                  <option value="4.25" <?php if($productData[sphere_max]=='4.25') print " selected"; ?>>+4.25</option>
                  <option value="4.00" <?php if($productData[sphere_max]=='4.00') print " selected"; ?>>+4.00</option>
                  <option value="3.75" <?php if($productData[sphere_max]=='3.75') print " selected"; ?>>+3.75</option>
                  <option value="3.50" <?php if($productData[sphere_max]=='3.50') print " selected"; ?>>+3.50</option>
                  <option value="3.25" <?php if($productData[sphere_max]=='3.25') print " selected"; ?>>+3.25</option>
                  <option value="3.00" <?php if($productData[sphere_max]=='3.00') print " selected"; ?>>+3.00</option>
                  <option value="2.75" <?php if($productData[sphere_max]=='2.75') print " selected"; ?>>+2.75</option>
                  <option value="2.50" <?php if($productData[sphere_max]=='2.50') print " selected"; ?>>+2.50</option>
                  <option value="2.25" <?php if($productData[sphere_max]=='2.25') print " selected"; ?>>+2.25</option>
                  <option value="2.00" <?php if($productData[sphere_max]=='2.00') print " selected"; ?>>+2.00</option>
                  <option value="1.75" <?php if($productData[sphere_max]=='1.75') print " selected"; ?>>+1.75</option>
                  <option value="1.50" <?php if($productData[sphere_max]=='1.50') print " selected"; ?>>+1.50</option>
                  <option value="1.25" <?php if($productData[sphere_max]=='1.25') print " selected"; ?>>+1.25</option>
                  <option value="1.00" <?php if($productData[sphere_max]=='1.00') print " selected"; ?>>+1.00</option>
                  <option value="0.75" <?php if($productData[sphere_max]=='0.75') print " selected"; ?>>+0.75</option>
                  <option value="0.50" <?php if($productData[sphere_max]=='0.50') print " selected"; ?>>+0.50</option>
                  <option value="0.25" <?php if($productData[sphere_max]=='0.25') print " selected"; ?>>+0.25</option>
                  <option value="0.00" <?php if($productData[sphere_max]=='0.00') print " selected"; ?>>+0.00</option>
                  
                   <option value="-0.25"<?php if($productData[sphere_max]=='-0.25') print " selected"; ?>>-0.25</option>
                  <option value="-0.50"<?php if($productData[sphere_max]=='-0.50') print " selected"; ?>>-0.50</option>
                  <option value="-0.75"<?php if($productData[sphere_max]=='-0.75') print " selected"; ?>>-0.75</option>
                  <option value="-1.00"<?php if($productData[sphere_max]=='-1.00') print " selected"; ?>>-1.00</option>
                  <option value="-1.25"<?php if($productData[sphere_max]=='-1.25') print " selected"; ?>>-1.25</option>
                  <option value="-1.50"<?php if($productData[sphere_max]=='-1.50') print " selected"; ?>>-1.50</option>
                  <option value="-1.75"<?php if($productData[sphere_max]=='-1.75') print " selected"; ?>>-1.75</option>
                  <option value="-2.00"<?php if($productData[sphere_max]=='-2.00') print " selected"; ?>>-2.00</option>
                  <option value="-2.25"<?php if($productData[sphere_max]=='-2.25') print " selected"; ?>>-2.25</option>
                  <option value="-2.50"<?php if($productData[sphere_max]=='-2.50') print " selected"; ?>>-2.50</option>
                  <option value="-2.75"<?php if($productData[sphere_max]=='-2.75') print " selected"; ?>>-2.75</option>
                  <option value="-3.00"<?php if($productData[sphere_max]=='-3.00') print " selected"; ?>>-3.00</option>
                  <option value="-3.25"<?php if($productData[sphere_max]=='-3.25') print " selected"; ?>>-3.25</option>
                  <option value="-3.50"<?php if($productData[sphere_max]=='-3.50') print " selected"; ?>>-3.50</option>
                  <option value="-3.75"<?php if($productData[sphere_max]=='-3.75') print " selected"; ?>>-3.75</option>
                  <option value="-4.00"<?php if($productData[sphere_max]=='-4.00') print " selected"; ?>>-4.00</option>
                  <option value="-4.25"<?php if($productData[sphere_max]=='-4.25') print " selected"; ?>>-4.25</option>
                  <option value="-4.50"<?php if($productData[sphere_max]=='-4.50') print " selected"; ?>>-4.50</option>
                  <option value="-4.75"<?php if($productData[sphere_max]=='-4.75') print " selected"; ?>>-4.75</option>
                  <option value="-5.00"<?php if($productData[sphere_max]=='-5.00') print " selected"; ?>>-5.00</option>
                  <option value="-5.25"<?php if($productData[sphere_max]=='-5.25') print " selected"; ?>>-5.25</option>
                  <option value="-5.50"<?php if($productData[sphere_max]=='-5.50') print " selected"; ?>>-5.50</option>
                  <option value="-5.75"<?php if($productData[sphere_max]=='-5.75') print " selected"; ?>>-5.75</option>
                  <option value="-6.00"<?php if($productData[sphere_max]=='-6.00') print " selected"; ?>>-6.00</option>
                  <option value="-6.25"<?php if($productData[sphere_max]=='-6.25') print " selected"; ?>>-6.25</option>
                  <option value="-6.50"<?php if($productData[sphere_max]=='-6.50') print " selected"; ?>>-6.50</option>
                  <option value="-6.75"<?php if($productData[sphere_max]=='-6.75') print " selected"; ?>>-6.75</option>
                  <option value="-7.00"<?php if($productData[sphere_max]=='-7.00') print " selected"; ?>>-7.00</option>
                  <option value="-7.25"<?php if($productData[sphere_max]=='-7.25') print " selected"; ?>>-7.25</option>
                  <option value="-7.50"<?php if($productData[sphere_max]=='-7.50') print " selected"; ?>>-7.50</option>
                  <option value="-7.75"<?php if($productData[sphere_max]=='-7.75') print " selected"; ?>>-7.75</option>
                  <option value="-8.00"<?php if($productData[sphere_max]=='-8.00') print " selected"; ?>>-8.00</option>
                  <option value="-8.25"<?php if($productData[sphere_max]=='-8.25') print " selected"; ?>>-8.25</option>
                  <option value="-8.50"<?php if($productData[sphere_max]=='-8.50') print " selected"; ?>>-8.50</option>
                  <option value="-8.75"<?php if($productData[sphere_max]=='-8.75') print " selected"; ?>>-8.75</option>
                  <option value="-9.00"<?php if($productData[sphere_max]=='-9.00') print " selected"; ?>>-9.00</option>
                  <option value="-9.25"<?php if($productData[sphere_max]=='-9.25') print " selected"; ?>>-9.25</option>
                  <option value="-9.50"<?php if($productData[sphere_max]=='-9.50') print " selected"; ?>>-9.50</option>
                  <option value="-9.75"<?php if($productData[sphere_max]=='-9.75') print " selected"; ?>>-9.75</option>
                  <option value="-10.00"<?php if($productData[sphere_max]=='-10.00') print " selected"; ?>>-10.00</option>
                  <option value="-10.25"<?php if($productData[sphere_max]=='-10.25') print " selected"; ?>>-10.25</option>
                  <option value="-10.50"<?php if($productData[sphere_max]=='-10.50') print " selected"; ?>>-10.50</option>
                  <option value="-10.75"<?php if($productData[sphere_max]=='-10.75') print " selected"; ?>>-10.75</option>
                  <option value="-11.00"<?php if($productData[sphere_max]=='-11.00') print " selected"; ?>>-11.00</option>
                  <option value="-11.25"<?php if($productData[sphere_max]=='-11.25') print " selected"; ?>>-11.25</option>
                  <option value="-11.50"<?php if($productData[sphere_max]=='-11.50') print " selected"; ?>>-11.50</option>
                  <option value="-11.75"<?php if($productData[sphere_max]=='-11.75') print " selected"; ?>>-11.75</option>
                  <option value="-12.00"<?php if($productData[sphere_max]=='-12.00') print " selected"; ?>>-12.00</option> 
                  <option value="-12.25"<?php if($productData[sphere_max]=='-12.25') print " selected"; ?>>-12.25</option>
                  <option value="-12.50"<?php if($productData[sphere_max]=='-12.50') print " selected"; ?>>-12.50</option>
                  <option value="-12.75"<?php if($productData[sphere_max]=='-12.75') print " selected"; ?>>-12.75</option>
                  <option value="-13.00"<?php if($productData[sphere_max]=='-13.00') print " selected"; ?>>-13.00</option>
                   <option value="-13.25"<?php if($productData[sphere_max]=='-13.25') print " selected"; ?>>-13.25</option>
                  <option value="-13.50"<?php if($productData[sphere_max]=='-13.50') print " selected"; ?>>-13.50</option>
                  <option value="-13.75"<?php if($productData[sphere_max]=='-13.75') print " selected"; ?>>-13.75</option>
                  <option value="-14.00"<?php if($productData[sphere_max]=='-14.00') print " selected"; ?>>-14.00</option>
                   <option value="-14.25"<?php if($productData[sphere_max]=='-14.25') print " selected"; ?>>-14.25</option>
                  <option value="-14.50"<?php if($productData[sphere_max]=='-14.50') print " selected"; ?>>-14.50</option>
                  <option value="-14.75"<?php if($productData[sphere_max]=='-14.75') print " selected"; ?>>-14.75</option>
                  <option value="-15.00"<?php if($productData[sphere_max]=='-15.00') print " selected"; ?>>-15.00</option>
                  <option value="-15.25"<?php if($productData[sphere_max]=='-15.25') print " selected"; ?>>-15.25</option>
                  <option value="-15.50"<?php if($productData[sphere_max]=='-15.50') print " selected"; ?>>-15.50</option>
                  <option value="-15.75"<?php if($productData[sphere_max]=='-15.75') print " selected"; ?>>-15.75</option>
                  <option value="-16.00"<?php if($productData[sphere_max]=='-16.00') print " selected"; ?>>-16.00</option>
                  <option value="-16.25"<?php if($productData[sphere_max]=='-16.25') print " selected"; ?>>-16.25</option>
                  <option value="-16.50"<?php if($productData[sphere_max]=='-16.50') print " selected"; ?>>-16.50</option>
                  <option value="-16.75"<?php if($productData[sphere_max]=='-16.75') print " selected"; ?>>-16.75</option>
                  <option value="-17.00"<?php if($productData[sphere_max]=='-17.00') print " selected"; ?>>-17.00</option>
                  <option value="-17.25"<?php if($productData[sphere_max]=='-17.25') print " selected"; ?>>-17.25</option>
                  <option value="-17.50"<?php if($productData[sphere_max]=='-17.50') print " selected"; ?>>-17.50</option>
                  <option value="-17.75"<?php if($productData[sphere_max]=='-17.75') print " selected"; ?>>-17.75</option>
                  <option value="-18.00"<?php if($productData[sphere_max]=='-18.00') print " selected"; ?>>-18.00</option>
                  <option value="-18.25"<?php if($productData[sphere_max]=='-18.25') print " selected"; ?>>-18.25</option>
                  <option value="-18.50"<?php if($productData[sphere_max]=='-18.50') print " selected"; ?>>-18.50</option>
                  <option value="-18.75"<?php if($productData[sphere_max]=='-18.75') print " selected"; ?>>-18.75</option>
                  <option value="-19.00"<?php if($productData[sphere_max]=='-19.00') print " selected"; ?>>-19.00</option>
                  <option value="-19.25"<?php if($productData[sphere_max]=='-19.25') print " selected"; ?>>-19.25</option>
                  <option value="-19.50"<?php if($productData[sphere_max]=='-19.50') print " selected"; ?>>-19.50</option>
                  <option value="-19.75"<?php if($productData[sphere_max]=='-19.75') print " selected"; ?>>-19.75</option>
                  <option value="-20.00"<?php if($productData[sphere_max]=='-20.00') print " selected"; ?>>-20.00</option>
                  
                  
        </select></td>
        
        
          
         <td align="right" valign="middle" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Helvetica, sans-serif, Arial">Max Height:</font></td>
        <td align="left" nowrap="nowrap" bgcolor="#FFFFFF"><input name="max_height" type="text" class="formText" id="max_height" value="<?php print "$productData[max_height]"; ?>" size="6" /
        
        </td>
        
        
        <td align="right" valign="middle" nowrap="nowrap" bgcolor="#FFFFFF"><p><font size="1" face="Helvetica, sans-serif, Arial">Sphere
          Min: </font></p></td>
        <td align="left" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">
          <select name="sphere_min" class="formText" id="sphere_min">
            <option>Select Sphere Min</option>
                 <option value="0.00"<?php if($productData[sphere_min]=='0.00') print " selected"; ?>>+0.00</option>
                  <option value="-0.25"<?php if($productData[sphere_min]=='-0.25') print " selected"; ?>>-0.25</option>
                  <option value="-0.50"<?php if($productData[sphere_min]=='-0.50') print " selected"; ?>>-0.50</option>
                  <option value="-0.75"<?php if($productData[sphere_min]=='-0.75') print " selected"; ?>>-0.75</option>
                  <option value="-1.00"<?php if($productData[sphere_min]=='-1.00') print " selected"; ?>>-1.00</option>
                  <option value="-1.25"<?php if($productData[sphere_min]=='-1.25') print " selected"; ?>>-1.25</option>
                  <option value="-1.50"<?php if($productData[sphere_min]=='-1.50') print " selected"; ?>>-1.50</option>
                  <option value="-1.75"<?php if($productData[sphere_min]=='-1.75') print " selected"; ?>>-1.75</option>
                  <option value="-2.00"<?php if($productData[sphere_min]=='-2.00') print " selected"; ?>>-2.00</option>
                  <option value="-2.25"<?php if($productData[sphere_min]=='-2.25') print " selected"; ?>>-2.25</option>
                  <option value="-2.50"<?php if($productData[sphere_min]=='-2.50') print " selected"; ?>>-2.50</option>
                  <option value="-2.75"<?php if($productData[sphere_min]=='-2.75') print " selected"; ?>>-2.75</option>
                  <option value="-3.00"<?php if($productData[sphere_min]=='-3.00') print " selected"; ?>>-3.00</option>
                  <option value="-3.25"<?php if($productData[sphere_min]=='-3.25') print " selected"; ?>>-3.25</option>
                  <option value="-3.50"<?php if($productData[sphere_min]=='-3.50') print " selected"; ?>>-3.50</option>
                  <option value="-3.75"<?php if($productData[sphere_min]=='-3.75') print " selected"; ?>>-3.75</option>
                  <option value="-4.00"<?php if($productData[sphere_min]=='-4.00') print " selected"; ?>>-4.00</option>
                  <option value="-4.25"<?php if($productData[sphere_min]=='-4.25') print " selected"; ?>>-4.25</option>
                  <option value="-4.50"<?php if($productData[sphere_min]=='-4.50') print " selected"; ?>>-4.50</option>
                  <option value="-4.75"<?php if($productData[sphere_min]=='-4.75') print " selected"; ?>>-4.75</option>
                  <option value="-5.00"<?php if($productData[sphere_min]=='-5.00') print " selected"; ?>>-5.00</option>
                  <option value="-5.25"<?php if($productData[sphere_min]=='-5.25') print " selected"; ?>>-5.25</option>
                  <option value="-5.50"<?php if($productData[sphere_min]=='-5.50') print " selected"; ?>>-5.50</option>
                  <option value="-5.75"<?php if($productData[sphere_min]=='-5.75') print " selected"; ?>>-5.75</option>
                  <option value="-6.00"<?php if($productData[sphere_min]=='-6.00') print " selected"; ?>>-6.00</option>
                  <option value="-6.25"<?php if($productData[sphere_min]=='-6.25') print " selected"; ?>>-6.25</option>
                  <option value="-6.50"<?php if($productData[sphere_min]=='-6.50') print " selected"; ?>>-6.50</option>
                  <option value="-6.75"<?php if($productData[sphere_min]=='-6.75') print " selected"; ?>>-6.75</option>
                  <option value="-7.00"<?php if($productData[sphere_min]=='-7.00') print " selected"; ?>>-7.00</option>
                  <option value="-7.25"<?php if($productData[sphere_min]=='-7.25') print " selected"; ?>>-7.25</option>
                  <option value="-7.50"<?php if($productData[sphere_min]=='-7.50') print " selected"; ?>>-7.50</option>
                  <option value="-7.75"<?php if($productData[sphere_min]=='-7.75') print " selected"; ?>>-7.75</option>
                  <option value="-8.00"<?php if($productData[sphere_min]=='-8.00') print " selected"; ?>>-8.00</option>
                  <option value="-8.25"<?php if($productData[sphere_min]=='-8.25') print " selected"; ?>>-8.25</option>
                  <option value="-8.50"<?php if($productData[sphere_min]=='-8.50') print " selected"; ?>>-8.50</option>
                  <option value="-8.75"<?php if($productData[sphere_min]=='-8.75') print " selected"; ?>>-8.75</option>
                  <option value="-9.00"<?php if($productData[sphere_min]=='-9.00') print " selected"; ?>>-9.00</option>
                  <option value="-9.25"<?php if($productData[sphere_min]=='-9.25') print " selected"; ?>>-9.25</option>
                  <option value="-9.50"<?php if($productData[sphere_min]=='-9.50') print " selected"; ?>>-9.50</option>
                  <option value="-9.75"<?php if($productData[sphere_min]=='-9.75') print " selected"; ?>>-9.75</option>
                  <option value="-10.00"<?php if($productData[sphere_min]=='-10.00') print " selected"; ?>>-10.00</option>
                  <option value="-10.25"<?php if($productData[sphere_min]=='-10.25') print " selected"; ?>>-10.25</option>
                  <option value="-10.50"<?php if($productData[sphere_min]=='-10.50') print " selected"; ?>>-10.50</option>
                  <option value="-10.75"<?php if($productData[sphere_min]=='-10.75') print " selected"; ?>>-10.75</option>
                  <option value="-11.00"<?php if($productData[sphere_min]=='-11.00') print " selected"; ?>>-11.00</option>
                  <option value="-11.25"<?php if($productData[sphere_min]=='-11.25') print " selected"; ?>>-11.25</option>
                  <option value="-11.50"<?php if($productData[sphere_min]=='-11.50') print " selected"; ?>>-11.50</option>
                  <option value="-11.75"<?php if($productData[sphere_min]=='-11.75') print " selected"; ?>>-11.75</option>
                  <option value="-12.00"<?php if($productData[sphere_min]=='-12.00') print " selected"; ?>>-12.00</option> 
                  <option value="-12.25"<?php if($productData[sphere_min]=='-12.25') print " selected"; ?>>-12.25</option>
                  <option value="-12.50"<?php if($productData[sphere_min]=='-12.50') print " selected"; ?>>-12.50</option>
                  <option value="-12.75"<?php if($productData[sphere_min]=='-12.75') print " selected"; ?>>-12.75</option>
                  <option value="-13.00"<?php if($productData[sphere_min]=='-13.00') print " selected"; ?>>-13.00</option>
                   <option value="-13.25"<?php if($productData[sphere_min]=='-13.25') print " selected"; ?>>-13.25</option>
                  <option value="-13.50"<?php if($productData[sphere_min]=='-13.50') print " selected"; ?>>-13.50</option>
                  <option value="-13.75"<?php if($productData[sphere_min]=='-13.75') print " selected"; ?>>-13.75</option>
                  <option value="-14.00"<?php if($productData[sphere_min]=='-14.00') print " selected"; ?>>-14.00</option>
                   <option value="-14.25"<?php if($productData[sphere_min]=='-14.25') print " selected"; ?>>-14.25</option>
                  <option value="-14.50"<?php if($productData[sphere_min]=='-14.50') print " selected"; ?>>-14.50</option>
                  <option value="-14.75"<?php if($productData[sphere_min]=='-14.75') print " selected"; ?>>-14.75</option>
                  <option value="-15.00"<?php if($productData[sphere_min]=='-15.00') print " selected"; ?>>-15.00</option>
          </select>
        </font></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Helvetica, sans-serif, Arial">Sphere
        Overage Max:</font></td>
        <td colspan="3" align="left" bgcolor="#DDDDDD"><select name="sphere_over_max" class="formText" id="sphere_over_max">
          <option>Select Sphere Overage Max</option>
         		
                  <option value="25.00" <?php if($productData[sphere_over_max]=='25.00') print " selected"; ?>>+25.00</option>
                  <option value="24.75" <?php if($productData[sphere_over_max]=='24.75') print " selected"; ?>>+24.75</option>
                  <option value="24.50" <?php if($productData[sphere_over_max]=='24.50') print " selected"; ?>>+24.50</option>
                  <option value="24.25" <?php if($productData[sphere_over_max]=='24.25') print " selected"; ?>>+24.25</option>
                  <option value="24.00" <?php if($productData[sphere_over_max]=='24.00') print " selected"; ?>>+24.00</option>
                  <option value="23.75" <?php if($productData[sphere_over_max]=='23.75') print " selected"; ?>>+23.75</option>
                  <option value="23.50" <?php if($productData[sphere_over_max]=='23.50') print " selected"; ?>>+23.50</option>
                  <option value="23.25" <?php if($productData[sphere_over_max]=='23.25') print " selected"; ?>>+23.25</option>
                  <option value="23.00" <?php if($productData[sphere_over_max]=='23.00') print " selected"; ?>>+23.00</option>
                  <option value="22.75" <?php if($productData[sphere_over_max]=='22.75') print " selected"; ?>>+22.75</option>
                  <option value="22.50" <?php if($productData[sphere_over_max]=='22.50') print " selected"; ?>>+22.50</option>
                  <option value="22.25" <?php if($productData[sphere_over_max]=='22.25') print " selected"; ?>>+22.25</option>
                  <option value="22.00" <?php if($productData[sphere_over_max]=='22.00') print " selected"; ?>>+22.00</option>
                  <option value="21.75" <?php if($productData[sphere_over_max]=='21.75') print " selected"; ?>>+21.75</option>
                  <option value="21.50" <?php if($productData[sphere_over_max]=='21.50') print " selected"; ?>>+21.50</option>
                  <option value="21.25" <?php if($productData[sphere_over_max]=='21.25') print " selected"; ?>>+21.25</option> 
                  <option value="21.00" <?php if($productData[sphere_over_max]=='21.00') print " selected"; ?>>+21.00</option>
                  <option value="20.75" <?php if($productData[sphere_over_max]=='20.75') print " selected"; ?>>+20.75</option>
                  <option value="20.50" <?php if($productData[sphere_over_max]=='20.50') print " selected"; ?>>+20.50</option>
                  <option value="20.25" <?php if($productData[sphere_over_max]=='20.25') print " selected"; ?>>+20.25</option>
                  <option value="20.00" <?php if($productData[sphere_over_max]=='20.00') print " selected"; ?>>+20.00</option>
                  <option value="19.75" <?php if($productData[sphere_over_max]=='19.75') print " selected"; ?>>+19.75</option>
                  <option value="19.50" <?php if($productData[sphere_over_max]=='19.50') print " selected"; ?>>+19.50</option>
                  <option value="19.25" <?php if($productData[sphere_over_max]=='19.25') print " selected"; ?>>+19.25</option>
                  <option value="19.00" <?php if($productData[sphere_over_max]=='19.00') print " selected"; ?>>+19.00</option>
                  <option value="18.75" <?php if($productData[sphere_over_max]=='18.75') print " selected"; ?>>+18.75</option>
                  <option value="18.50" <?php if($productData[sphere_over_max]=='18.50') print " selected"; ?>>+18.50</option>
                  <option value="18.25" <?php if($productData[sphere_over_max]=='18.25') print " selected"; ?>>+18.25</option>
                  <option value="18.00" <?php if($productData[sphere_over_max]=='18.00') print " selected"; ?>>+18.00</option>
                  <option value="17.75" <?php if($productData[sphere_over_max]=='17.75') print " selected"; ?>>+17.75</option>
                  <option value="17.50" <?php if($productData[sphere_over_max]=='17.50') print " selected"; ?>>+17.50</option>
                  <option value="17.25" <?php if($productData[sphere_over_max]=='17.25') print " selected"; ?>>+17.25</option>
                  <option value="17.00" <?php if($productData[sphere_over_max]=='17.00') print " selected"; ?>>+17.00</option>
                  <option value="16.75" <?php if($productData[sphere_over_max]=='16.75') print " selected"; ?>>+16.75</option>
                  <option value="16.50" <?php if($productData[sphere_over_max]=='16.50') print " selected"; ?>>+16.50</option>
                  <option value="16.25" <?php if($productData[sphere_over_max]=='16.25') print " selected"; ?>>+16.25</option>
                  <option value="16.00" <?php if($productData[sphere_over_max]=='16.00') print " selected"; ?>>+16.00</option>
                  <option value="15.75" <?php if($productData[sphere_over_max]=='15.75') print " selected"; ?>>+15.75</option>
                  <option value="15.50" <?php if($productData[sphere_over_max]=='15.50') print " selected"; ?>>+15.50</option>
                  <option value="15.25" <?php if($productData[sphere_over_max]=='15.25') print " selected"; ?>>+15.25</option>
                  <option value="15.00" <?php if($productData[sphere_over_max]=='15.00') print " selected"; ?>>+15.00</option>
                  <option value="14.75" <?php if($productData[sphere_over_max]=='14.75') print " selected"; ?>>+14.75</option>
                  <option value="14.50" <?php if($productData[sphere_over_max]=='14.50') print " selected"; ?>>+14.50</option>
                  <option value="14.25" <?php if($productData[sphere_over_max]=='14.25') print " selected"; ?>>+14.25</option>
                  <option value="14.00" <?php if($productData[sphere_over_max]=='14.00') print " selected"; ?>>+14.00</option>
                  <option value="13.75" <?php if($productData[sphere_over_max]=='13.75') print " selected"; ?>>+13.75</option>
                  <option value="13.50" <?php if($productData[sphere_over_max]=='13.50') print " selected"; ?>>+13.50</option>
                  <option value="13.25" <?php if($productData[sphere_over_max]=='13.25') print " selected"; ?>>+13.25</option>
                  <option value="13.00" <?php if($productData[sphere_over_max]=='13.00') print " selected"; ?>>+13.00</option>
                  <option value="12.75" <?php if($productData[sphere_over_max]=='12.75') print " selected"; ?>>+12.75</option>
                  <option value="12.50" <?php if($productData[sphere_over_max]=='12.50') print " selected"; ?>>+12.50</option>
                  <option value="12.25" <?php if($productData[sphere_over_max]=='12.25') print " selected"; ?>>+12.25</option>
                  <option value="12.00" <?php if($productData[sphere_over_max]=='12.00') print " selected"; ?>>+12.00</option>
                  <option value="11.75" <?php if($productData[sphere_over_max]=='11.75') print " selected"; ?>>+11.75</option>
                  <option value="11.50" <?php if($productData[sphere_over_max]=='11.50') print " selected"; ?>>+11.50</option>
                  <option value="11.25" <?php if($productData[sphere_over_max]=='11.25') print " selected"; ?>>+11.25</option>
                  <option value="11.00" <?php if($productData[sphere_over_max]=='11.00') print " selected"; ?>>+11.00</option>
                  <option value="10.75" <?php if($productData[sphere_over_max]=='10.75') print " selected"; ?>>+10.75</option>
                  <option value="10.50" <?php if($productData[sphere_over_max]=='10.50') print " selected"; ?>>+10.50</option>
                  <option value="10.25" <?php if($productData[sphere_over_max]=='10.25') print " selected"; ?>>+10.25</option>
                  <option value="10.00" <?php if($productData[sphere_over_max]=='10.00') print " selected"; ?>>+10.00</option>
                  <option value="9.75" <?php if($productData[sphere_over_max]=='9.75') print " selected"; ?>>+9.75</option>
                  <option value="9.50" <?php if($productData[sphere_over_max]=='9.50') print " selected"; ?>>+9.50</option>
                  <option value="9.25" <?php if($productData[sphere_over_max]=='9.25') print " selected"; ?>>+9.25</option>
                  <option value="9.00" <?php if($productData[sphere_over_max]=='9.00') print " selected"; ?>>+9.00</option>
                  <option value="8.75" <?php if($productData[sphere_over_max]=='8.75') print " selected"; ?>>+8.75</option>
                  <option value="8.50" <?php if($productData[sphere_over_max]=='8.50') print " selected"; ?>>+8.50</option>
                  <option value="8.25" <?php if($productData[sphere_over_max]=='8.25') print " selected"; ?>>+8.25</option>
                  <option value="8.00" <?php if($productData[sphere_over_max]=='8.00') print " selected"; ?>>+8.00</option>
                  <option value="7.75" <?php if($productData[sphere_over_max]=='7.75') print " selected"; ?>>+7.75</option>
                  <option value="7.50" <?php if($productData[sphere_over_max]=='7.50') print " selected"; ?>>+7.50</option>
                  <option value="7.25" <?php if($productData[sphere_over_max]=='7.25') print " selected"; ?>>+7.25</option>
                  <option value="7.00" <?php if($productData[sphere_over_max]=='7.00') print " selected"; ?>>+7.00</option>
                  <option value="6.75" <?php if($productData[sphere_over_max]=='6.75') print " selected"; ?>>+6.75</option>
                  <option value="6.50" <?php if($productData[sphere_over_max]=='6.50') print " selected"; ?>>+6.50</option>
                  <option value="6.25" <?php if($productData[sphere_over_max]=='6.25') print " selected"; ?>>+6.25</option>
                  <option value="6.00" <?php if($productData[sphere_over_max]=='6.00') print " selected"; ?>>+6.00</option>
                  <option value="5.75" <?php if($productData[sphere_over_max]=='5.75') print " selected"; ?>>+5.75</option>
                  <option value="5.50" <?php if($productData[sphere_over_max]=='5.50') print " selected"; ?>>+5.50</option>
                  <option value="5.25" <?php if($productData[sphere_over_max]=='5.25') print " selected"; ?>>+5.25</option>
                  <option value="5.00" <?php if($productData[sphere_over_max]=='5.00') print " selected"; ?>>+5.00</option>
                  <option value="4.75" <?php if($productData[sphere_over_max]=='4.75') print " selected"; ?>>+4.75</option>
                  <option value="4.50" <?php if($productData[sphere_over_max]=='4.50') print " selected"; ?>>+4.50</option>
                  <option value="4.25" <?php if($productData[sphere_over_max]=='4.25') print " selected"; ?>>+4.25</option>
                  <option value="4.00" <?php if($productData[sphere_over_max]=='4.00') print " selected"; ?>>+4.00</option>
                  <option value="3.75" <?php if($productData[sphere_over_max]=='3.75') print " selected"; ?>>+3.75</option>
                  <option value="3.50" <?php if($productData[sphere_over_max]=='3.50') print " selected"; ?>>+3.50</option>
                  <option value="3.25" <?php if($productData[sphere_over_max]=='3.25') print " selected"; ?>>+3.25</option>
                  <option value="3.00" <?php if($productData[sphere_over_max]=='3.00') print " selected"; ?>>+3.00</option>
                  <option value="2.75" <?php if($productData[sphere_over_max]=='2.75') print " selected"; ?>>+2.75</option>
                  <option value="2.50" <?php if($productData[sphere_over_max]=='2.50') print " selected"; ?>>+2.50</option>
                  <option value="2.25" <?php if($productData[sphere_over_max]=='2.25') print " selected"; ?>>+2.25</option>
                  <option value="2.00" <?php if($productData[sphere_over_max]=='2.00') print " selected"; ?>>+2.00</option>
                  <option value="1.75" <?php if($productData[sphere_over_max]=='1.75') print " selected"; ?>>+1.75</option>
                  <option value="1.50" <?php if($productData[sphere_over_max]=='1.50') print " selected"; ?>>+1.50</option>
                  <option value="1.25" <?php if($productData[sphere_over_max]=='1.25') print " selected"; ?>>+1.25</option>
                  <option value="1.00" <?php if($productData[sphere_over_max]=='1.00') print " selected"; ?>>+1.00</option>
                  <option value="0.75" <?php if($productData[sphere_over_max]=='0.75') print " selected"; ?>>+0.75</option>
                  <option value="0.50" <?php if($productData[sphere_over_max]=='0.50') print " selected"; ?>>+0.50</option>
                  <option value="0.25" <?php if($productData[sphere_over_max]=='0.25') print " selected"; ?>>+0.25</option>
                  <option value="0.00" <?php if($productData[sphere_over_max]=='0.00') print " selected"; ?>>+0.00</option>
                  
                   <option value="-0.25"<?php if($productData[sphere_over_max]=='-0.25') print " selected"; ?>>-0.25</option>
                  <option value="-0.50"<?php if($productData[sphere_over_max]=='-0.50') print " selected"; ?>>-0.50</option>
                  <option value="-0.75"<?php if($productData[sphere_over_max]=='-0.75') print " selected"; ?>>-0.75</option>
                  <option value="-1.00"<?php if($productData[sphere_over_max]=='-1.00') print " selected"; ?>>-1.00</option>
                  <option value="-1.25"<?php if($productData[sphere_over_max]=='-1.25') print " selected"; ?>>-1.25</option>
                  <option value="-1.50"<?php if($productData[sphere_over_max]=='-1.50') print " selected"; ?>>-1.50</option>
                  <option value="-1.75"<?php if($productData[sphere_over_max]=='-1.75') print " selected"; ?>>-1.75</option>
                  <option value="-2.00"<?php if($productData[sphere_over_max]=='-2.00') print " selected"; ?>>-2.00</option>
                  <option value="-2.25"<?php if($productData[sphere_over_max]=='-2.25') print " selected"; ?>>-2.25</option>
                  <option value="-2.50"<?php if($productData[sphere_over_max]=='-2.50') print " selected"; ?>>-2.50</option>
                  <option value="-2.75"<?php if($productData[sphere_over_max]=='-2.75') print " selected"; ?>>-2.75</option>
                  <option value="-3.00"<?php if($productData[sphere_over_max]=='-3.00') print " selected"; ?>>-3.00</option>
                  <option value="-3.25"<?php if($productData[sphere_over_max]=='-3.25') print " selected"; ?>>-3.25</option>
                  <option value="-3.50"<?php if($productData[sphere_over_max]=='-3.50') print " selected"; ?>>-3.50</option>
                  <option value="-3.75"<?php if($productData[sphere_over_max]=='-3.75') print " selected"; ?>>-3.75</option>
                  <option value="-4.00"<?php if($productData[sphere_over_max]=='-4.00') print " selected"; ?>>-4.00</option>
                  <option value="-4.25"<?php if($productData[sphere_over_max]=='-4.25') print " selected"; ?>>-4.25</option>
                  <option value="-4.50"<?php if($productData[sphere_over_max]=='-4.50') print " selected"; ?>>-4.50</option>
                  <option value="-4.75"<?php if($productData[sphere_over_max]=='-4.75') print " selected"; ?>>-4.75</option>
                  <option value="-5.00"<?php if($productData[sphere_over_max]=='-5.00') print " selected"; ?>>-5.00</option>
                  <option value="-5.25"<?php if($productData[sphere_over_max]=='-5.25') print " selected"; ?>>-5.25</option>
                  <option value="-5.50"<?php if($productData[sphere_over_max]=='-5.50') print " selected"; ?>>-5.50</option>
                  <option value="-5.75"<?php if($productData[sphere_over_max]=='-5.75') print " selected"; ?>>-5.75</option>
                  <option value="-6.00"<?php if($productData[sphere_over_max]=='-6.00') print " selected"; ?>>-6.00</option>
                  <option value="-6.25"<?php if($productData[sphere_over_max]=='-6.25') print " selected"; ?>>-6.25</option>
                  <option value="-6.50"<?php if($productData[sphere_over_max]=='-6.50') print " selected"; ?>>-6.50</option>
                  <option value="-6.75"<?php if($productData[sphere_over_max]=='-6.75') print " selected"; ?>>-6.75</option>
                  <option value="-7.00"<?php if($productData[sphere_over_max]=='-7.00') print " selected"; ?>>-7.00</option>
                  <option value="-7.25"<?php if($productData[sphere_over_max]=='-7.25') print " selected"; ?>>-7.25</option>
                  <option value="-7.50"<?php if($productData[sphere_over_max]=='-7.50') print " selected"; ?>>-7.50</option>
                  <option value="-7.75"<?php if($productData[sphere_over_max]=='-7.75') print " selected"; ?>>-7.75</option>
                  <option value="-8.00"<?php if($productData[sphere_over_max]=='-8.00') print " selected"; ?>>-8.00</option>
                  <option value="-8.25"<?php if($productData[sphere_over_max]=='-8.25') print " selected"; ?>>-8.25</option>
                  <option value="-8.50"<?php if($productData[sphere_over_max]=='-8.50') print " selected"; ?>>-8.50</option>
                  <option value="-8.75"<?php if($productData[sphere_over_max]=='-8.75') print " selected"; ?>>-8.75</option>
                  <option value="-9.00"<?php if($productData[sphere_over_max]=='-9.00') print " selected"; ?>>-9.00</option>
                  <option value="-9.25"<?php if($productData[sphere_over_max]=='-9.25') print " selected"; ?>>-9.25</option>
                  <option value="-9.50"<?php if($productData[sphere_over_max]=='-9.50') print " selected"; ?>>-9.50</option>
                  <option value="-9.75"<?php if($productData[sphere_over_max]=='-9.75') print " selected"; ?>>-9.75</option>
                  <option value="-10.00"<?php if($productData[sphere_over_max]=='-10.00') print " selected"; ?>>-10.00</option>
                  <option value="-10.25"<?php if($productData[sphere_over_max]=='-10.25') print " selected"; ?>>-10.25</option>
                  <option value="-10.50"<?php if($productData[sphere_over_max]=='-10.50') print " selected"; ?>>-10.50</option>
                  <option value="-10.75"<?php if($productData[sphere_over_max]=='-10.75') print " selected"; ?>>-10.75</option>
                  <option value="-11.00"<?php if($productData[sphere_over_max]=='-11.00') print " selected"; ?>>-11.00</option>
                  <option value="-11.25"<?php if($productData[sphere_over_max]=='-11.25') print " selected"; ?>>-11.25</option>
                  <option value="-11.50"<?php if($productData[sphere_over_max]=='-11.50') print " selected"; ?>>-11.50</option>
                  <option value="-11.75"<?php if($productData[sphere_over_max]=='-11.75') print " selected"; ?>>-11.75</option>
                  <option value="-12.00"<?php if($productData[sphere_over_max]=='-12.00') print " selected"; ?>>-12.00</option> 
                  <option value="-12.25"<?php if($productData[sphere_over_max]=='-12.25') print " selected"; ?>>-12.25</option>
                  <option value="-12.50"<?php if($productData[sphere_over_max]=='-12.50') print " selected"; ?>>-12.50</option>
                  <option value="-12.75"<?php if($productData[sphere_over_max]=='-12.75') print " selected"; ?>>-12.75</option>
                  <option value="-13.00"<?php if($productData[sphere_over_max]=='-13.00') print " selected"; ?>>-13.00</option>
                   <option value="-13.25"<?php if($productData[sphere_over_max]=='-13.25') print " selected"; ?>>-13.25</option>
                  <option value="-13.50"<?php if($productData[sphere_over_max]=='-13.50') print " selected"; ?>>-13.50</option>
                  <option value="-13.75"<?php if($productData[sphere_over_max]=='-13.75') print " selected"; ?>>-13.75</option>
                  <option value="-14.00"<?php if($productData[sphere_over_max]=='-14.00') print " selected"; ?>>-14.00</option>
                   <option value="-14.25"<?php if($productData[sphere_over_max]=='-14.25') print " selected"; ?>>-14.25</option>
                  <option value="-14.50"<?php if($productData[sphere_over_max]=='-14.50') print " selected"; ?>>-14.50</option>
                  <option value="-14.75"<?php if($productData[sphere_over_max]=='-14.75') print " selected"; ?>>-14.75</option>
                  <option value="-15.00"<?php if($productData[sphere_over_max]=='-15.00') print " selected"; ?>>-15.00</option>
                  <option value="-15.25"<?php if($productData[sphere_over_max]=='-15.25') print " selected"; ?>>-15.25</option>
                  <option value="-15.50"<?php if($productData[sphere_over_max]=='-15.50') print " selected"; ?>>-15.50</option>
                  <option value="-15.75"<?php if($productData[sphere_over_max]=='-15.75') print " selected"; ?>>-15.75</option>
                  <option value="-16.00"<?php if($productData[sphere_over_max]=='-16.00') print " selected"; ?>>-16.00</option>
                  <option value="-16.25"<?php if($productData[sphere_over_max]=='-16.25') print " selected"; ?>>-16.25</option>
                  <option value="-16.50"<?php if($productData[sphere_over_max]=='-16.50') print " selected"; ?>>-16.50</option>
                  <option value="-16.75"<?php if($productData[sphere_over_max]=='-16.75') print " selected"; ?>>-16.75</option>
                  <option value="-17.00"<?php if($productData[sphere_over_max]=='-17.00') print " selected"; ?>>-17.00</option>
                  <option value="-17.25"<?php if($productData[sphere_over_max]=='-17.25') print " selected"; ?>>-17.25</option>
                  <option value="-17.50"<?php if($productData[sphere_over_max]=='-17.50') print " selected"; ?>>-17.50</option>
                  <option value="-17.75"<?php if($productData[sphere_over_max]=='-17.75') print " selected"; ?>>-17.75</option>
                  <option value="-18.00"<?php if($productData[sphere_over_max]=='-18.00') print " selected"; ?>>-18.00</option>
                  <option value="-18.25"<?php if($productData[sphere_over_max]=='-18.25') print " selected"; ?>>-18.25</option>
                  <option value="-18.50"<?php if($productData[sphere_over_max]=='-18.50') print " selected"; ?>>-18.50</option>
                  <option value="-18.75"<?php if($productData[sphere_over_max]=='-18.75') print " selected"; ?>>-18.75</option>
                  <option value="-19.00"<?php if($productData[sphere_over_max]=='-19.00') print " selected"; ?>>-19.00</option>
                  <option value="-19.25"<?php if($productData[sphere_over_max]=='-19.25') print " selected"; ?>>-19.25</option>
                  <option value="-19.50"<?php if($productData[sphere_over_max]=='-19.50') print " selected"; ?>>-19.50</option>
                  <option value="-19.75"<?php if($productData[sphere_over_max]=='-19.75') print " selected"; ?>>-19.75</option>
                  <option value="-20.00"<?php if($productData[sphere_over_max]=='-20.00') print " selected"; ?>>-20.00</option>
                  
        </select></td>
        <td align="right" valign="middle" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Helvetica, sans-serif, Arial">Sphere Overage
        Min: </font></td>
        <td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">
          <select name="sphere_over_min" class="formText" id="sphere_over_min">
            <option >Select Sphere Overage Min</option>
            
                  <option value="0.00"<?php if($productData[sphere_over_min]=='0.00') print " selected"; ?>>+0.00</option>
                  <option value="-0.25"<?php if($productData[sphere_over_min]=='-0.25') print " selected"; ?>>-0.25</option>
                  <option value="-0.50"<?php if($productData[sphere_over_min]=='-0.50') print " selected"; ?>>-0.50</option>
                  <option value="-0.75"<?php if($productData[sphere_over_min]=='-0.75') print " selected"; ?>>-0.75</option>
                  <option value="-1.00"<?php if($productData[sphere_over_min]=='-1.00') print " selected"; ?>>-1.00</option>
                  <option value="-1.25"<?php if($productData[sphere_over_min]=='-1.25') print " selected"; ?>>-1.25</option>
                  <option value="-1.50"<?php if($productData[sphere_over_min]=='-1.50') print " selected"; ?>>-1.50</option>
                  <option value="-1.75"<?php if($productData[sphere_over_min]=='-1.75') print " selected"; ?>>-1.75</option>
                  <option value="-2.00"<?php if($productData[sphere_over_min]=='-2.00') print " selected"; ?>>-2.00</option>
                  <option value="-2.25"<?php if($productData[sphere_over_min]=='-2.25') print " selected"; ?>>-2.25</option>
                  <option value="-2.50"<?php if($productData[sphere_over_min]=='-2.50') print " selected"; ?>>-2.50</option>
                  <option value="-2.75"<?php if($productData[sphere_over_min]=='-2.75') print " selected"; ?>>-2.75</option>
                  <option value="-3.00"<?php if($productData[sphere_over_min]=='-3.00') print " selected"; ?>>-3.00</option>
                  <option value="-3.25"<?php if($productData[sphere_over_min]=='-3.25') print " selected"; ?>>-3.25</option>
                  <option value="-3.50"<?php if($productData[sphere_over_min]=='-3.50') print " selected"; ?>>-3.50</option>
                  <option value="-3.75"<?php if($productData[sphere_over_min]=='-3.75') print " selected"; ?>>-3.75</option>
                  <option value="-4.00"<?php if($productData[sphere_over_min]=='-4.00') print " selected"; ?>>-4.00</option>
                  <option value="-4.25"<?php if($productData[sphere_over_min]=='-4.25') print " selected"; ?>>-4.25</option>
                  <option value="-4.50"<?php if($productData[sphere_over_min]=='-4.50') print " selected"; ?>>-4.50</option>
                  <option value="-4.75"<?php if($productData[sphere_over_min]=='-4.75') print " selected"; ?>>-4.75</option>
                  <option value="-5.00"<?php if($productData[sphere_over_min]=='-5.00') print " selected"; ?>>-5.00</option>
                  <option value="-5.25"<?php if($productData[sphere_over_min]=='-5.25') print " selected"; ?>>-5.25</option>
                  <option value="-5.50"<?php if($productData[sphere_over_min]=='-5.50') print " selected"; ?>>-5.50</option>
                  <option value="-5.75"<?php if($productData[sphere_over_min]=='-5.75') print " selected"; ?>>-5.75</option>
                  <option value="-6.00"<?php if($productData[sphere_over_min]=='-6.00') print " selected"; ?>>-6.00</option>
                  <option value="-6.25"<?php if($productData[sphere_over_min]=='-6.25') print " selected"; ?>>-6.25</option>
                  <option value="-6.50"<?php if($productData[sphere_over_min]=='-6.50') print " selected"; ?>>-6.50</option>
                  <option value="-6.75"<?php if($productData[sphere_over_min]=='-6.75') print " selected"; ?>>-6.75</option>
                  <option value="-7.00"<?php if($productData[sphere_over_min]=='-7.00') print " selected"; ?>>-7.00</option>
                  <option value="-7.25"<?php if($productData[sphere_over_min]=='-7.25') print " selected"; ?>>-7.25</option>
                  <option value="-7.50"<?php if($productData[sphere_over_min]=='-7.50') print " selected"; ?>>-7.50</option>
                  <option value="-7.75"<?php if($productData[sphere_over_min]=='-7.75') print " selected"; ?>>-7.75</option>
                  <option value="-8.00"<?php if($productData[sphere_over_min]=='-8.00') print " selected"; ?>>-8.00</option>
                  <option value="-8.25"<?php if($productData[sphere_over_min]=='-8.25') print " selected"; ?>>-8.25</option>
                  <option value="-8.50"<?php if($productData[sphere_over_min]=='-8.50') print " selected"; ?>>-8.50</option>
                  <option value="-8.75"<?php if($productData[sphere_over_min]=='-8.75') print " selected"; ?>>-8.75</option>
                  <option value="-9.00"<?php if($productData[sphere_over_min]=='-9.00') print " selected"; ?>>-9.00</option>
                  <option value="-9.25"<?php if($productData[sphere_over_min]=='-9.25') print " selected"; ?>>-9.25</option>
                  <option value="-9.50"<?php if($productData[sphere_over_min]=='-9.50') print " selected"; ?>>-9.50</option>
                  <option value="-9.75"<?php if($productData[sphere_over_min]=='-9.75') print " selected"; ?>>-9.75</option>
                  <option value="-10.00"<?php if($productData[sphere_over_min]=='-10.00') print " selected"; ?>>-10.00</option>
                  <option value="-10.25"<?php if($productData[sphere_over_min]=='-10.25') print " selected"; ?>>-10.25</option>
                  <option value="-10.50"<?php if($productData[sphere_over_min]=='-10.50') print " selected"; ?>>-10.50</option>
                  <option value="-10.75"<?php if($productData[sphere_over_min]=='-10.75') print " selected"; ?>>-10.75</option>
                  <option value="-11.00"<?php if($productData[sphere_over_min]=='-11.00') print " selected"; ?>>-11.00</option>
                  <option value="-11.25"<?php if($productData[sphere_over_min]=='-11.25') print " selected"; ?>>-11.25</option>
                  <option value="-11.50"<?php if($productData[sphere_over_min]=='-11.50') print " selected"; ?>>-11.50</option>
                  <option value="-11.75"<?php if($productData[sphere_over_min]=='-11.75') print " selected"; ?>>-11.75</option>
                  <option value="-12.00"<?php if($productData[sphere_over_min]=='-12.00') print " selected"; ?>>-12.00</option> 
                  <option value="-12.25"<?php if($productData[sphere_over_min]=='-12.25') print " selected"; ?>>-12.25</option>
                  <option value="-12.50"<?php if($productData[sphere_over_min]=='-12.50') print " selected"; ?>>-12.50</option>
                  <option value="-12.75"<?php if($productData[sphere_over_min]=='-12.75') print " selected"; ?>>-12.75</option>
                  <option value="-13.00"<?php if($productData[sphere_over_min]=='-13.00') print " selected"; ?>>-13.00</option>
                   <option value="-13.25"<?php if($productData[sphere_over_min]=='-13.25') print " selected"; ?>>-13.25</option>
                  <option value="-13.50"<?php if($productData[sphere_over_min]=='-13.50') print " selected"; ?>>-13.50</option>
                  <option value="-13.75"<?php if($productData[sphere_over_min]=='-13.75') print " selected"; ?>>-13.75</option>
                  <option value="-14.00"<?php if($productData[sphere_over_min]=='-14.00') print " selected"; ?>>-14.00</option>
                   <option value="-14.25"<?php if($productData[sphere_over_min]=='-14.25') print " selected"; ?>>-14.25</option>
                  <option value="-14.50"<?php if($productData[sphere_over_min]=='-14.50') print " selected"; ?>>-14.50</option>
                  <option value="-14.75"<?php if($productData[sphere_over_min]=='-14.75') print " selected"; ?>>-14.75</option>
                  <option value="-15.00"<?php if($productData[sphere_over_min]=='-15.00') print " selected"; ?>>-15.00</option>
                  <option value="-15.25"<?php if($productData[sphere_over_min]=='-15.25') print " selected"; ?>>-15.25</option>
                  <option value="-15.50"<?php if($productData[sphere_over_min]=='-15.50') print " selected"; ?>>-15.50</option>
                  <option value="-15.75"<?php if($productData[sphere_over_min]=='-15.75') print " selected"; ?>>-15.75</option>
                  <option value="-16.00"<?php if($productData[sphere_over_min]=='-16.00') print " selected"; ?>>-16.00</option>
                  <option value="-16.25"<?php if($productData[sphere_over_min]=='-16.25') print " selected"; ?>>-16.25</option>
                  <option value="-16.50"<?php if($productData[sphere_over_min]=='-16.50') print " selected"; ?>>-16.50</option>
                  <option value="-16.75"<?php if($productData[sphere_over_min]=='-16.75') print " selected"; ?>>-16.75</option>
                  <option value="-17.00"<?php if($productData[sphere_over_min]=='-17.00') print " selected"; ?>>-17.00</option>
                  <option value="-17.25"<?php if($productData[sphere_over_min]=='-17.25') print " selected"; ?>>-17.25</option>
                  <option value="-17.50"<?php if($productData[sphere_over_min]=='-17.50') print " selected"; ?>>-17.50</option>
                  <option value="-17.75"<?php if($productData[sphere_over_min]=='-17.75') print " selected"; ?>>-17.75</option>
                  <option value="-18.00"<?php if($productData[sphere_over_min]=='-18.00') print " selected"; ?>>-18.00</option>
                  <option value="-18.25"<?php if($productData[sphere_over_min]=='-18.25') print " selected"; ?>>-18.25</option>
                  <option value="-18.50"<?php if($productData[sphere_over_min]=='-18.50') print " selected"; ?>>-18.50</option>
                  <option value="-18.75"<?php if($productData[sphere_over_min]=='-18.75') print " selected"; ?>>-18.75</option>
                  <option value="-19.00"<?php if($productData[sphere_over_min]=='-19.00') print " selected"; ?>>-19.00</option>
                  <option value="-19.25"<?php if($productData[sphere_over_min]=='-19.25') print " selected"; ?>>-19.25</option>
                  <option value="-19.50"<?php if($productData[sphere_over_min]=='-19.50') print " selected"; ?>>-19.50</option>
                  <option value="-19.75"<?php if($productData[sphere_over_min]=='-19.75') print " selected"; ?>>-19.75</option>
                  <option value="-20.00"<?php if($productData[sphere_over_min]=='-20.00') print " selected"; ?>>-20.00</option>
                  <option value="-20.25"<?php if($productData[sphere_over_min]=='-20.25') print " selected"; ?>>-20.25</option>
                  <option value="-20.50"<?php if($productData[sphere_over_min]=='-20.50') print " selected"; ?>>-20.50</option>
                  <option value="-20.75"<?php if($productData[sphere_over_min]=='-20.75') print " selected"; ?>>-20.75</option>
                  <option value="-21.00"<?php if($productData[sphere_over_min]=='-21.00') print " selected"; ?>>-21.00</option>
                  <option value="-21.25"<?php if($productData[sphere_over_min]=='-21.25') print " selected"; ?>>-21.25</option>
                  <option value="-21.50"<?php if($productData[sphere_over_min]=='-21.50') print " selected"; ?>>-21.50</option>
                  <option value="-21.75"<?php if($productData[sphere_over_min]=='-21.75') print " selected"; ?>>-21.75</option>
                  <option value="-22.00"<?php if($productData[sphere_over_min]=='-22.00') print " selected"; ?>>-22.00</option>
                  <option value="-22.25"<?php if($productData[sphere_over_min]=='-22.25') print " selected"; ?>>-22.25</option>
                  <option value="-22.50"<?php if($productData[sphere_over_min]=='-22.50') print " selected"; ?>>-22.50</option>
                  <option value="-22.75"<?php if($productData[sphere_over_min]=='-22.75') print " selected"; ?>>-22.75</option>
                  <option value="-23.00"<?php if($productData[sphere_over_min]=='-23.00') print " selected"; ?>>-23.00</option>
                  <option value="-23.25"<?php if($productData[sphere_over_min]=='-23.25') print " selected"; ?>>-23.25</option>
                  <option value="-23.50"<?php if($productData[sphere_over_min]=='-23.50') print " selected"; ?>>-23.50</option>
                  <option value="-23.75"<?php if($productData[sphere_over_min]=='-23.75') print " selected"; ?>>-23.75</option>
                  <option value="-24.00"<?php if($productData[sphere_over_min]=='-24.00') print " selected"; ?>>-24.00</option>
                  <option value="-24.25"<?php if($productData[sphere_over_min]=='-24.25') print " selected"; ?>>-24.25</option>
                  <option value="-24.50"<?php if($productData[sphere_over_min]=='-24.50') print " selected"; ?>>-24.50</option>
                  <option value="-24.75"<?php if($productData[sphere_over_min]=='-24.75') print " selected"; ?>>-24.75</option>
                  <option value="-25.00"<?php if($productData[sphere_over_min]=='-25.00') print " selected"; ?>>-25.00</option>
                              </select>
        </font></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#FFFFFF"><p><font size="1" face="Helvetica, sans-serif, Arial">Cylinder
          Max: </font></p></td>
        <td colspan="3" align="left" bgcolor="#FFFFFF"><select name="cyl_max" class="formText" id="cyl_max">
          <option>Select Cylinder Max</option>
          <option value="8.00"<?php if($productData[cyl_max]=='8.00') print " selected"; ?>>+8.00</option>
          <option value="7.00"<?php if($productData[cyl_max]=='7.00') print " selected"; ?>>+7.00</option>
          <option value="6.00"<?php if($productData[cyl_max]=='6.00') print " selected"; ?>>+6.00</option>
          <option value="5.00"<?php if($productData[cyl_max]=='5.00') print " selected"; ?>>+5.00</option>
          <option value="4.00"<?php if($productData[cyl_max]=='4.00') print " selected"; ?>>+4.00</option>
          <option value="3.00"<?php if($productData[cyl_max]=='3.00') print " selected"; ?>>+3.00</option>
          <option value="2.00"<?php if($productData[cyl_max]=='2.00') print " selected"; ?>>+2.00</option>
          <option value="1.00"<?php if($productData[cyl_max]=='1.00') print " selected"; ?>>+1.00</option>
          <option value="0.00"<?php if($productData[cyl_max]=='0.00') print " selected"; ?>>+0.00</option>
        </select></td>
        <td align="right" valign="middle" nowrap="nowrap" bgcolor="#FFFFFF"><p><font size="1" face="Helvetica, sans-serif, Arial">Cylinder
          Min: </font></p></td>
        <td align="left" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">
          <select name="cyl_min" class="formText" id="cyl_min">
            <option selected="selected">Select Cylinder Min</option>
            <option value="0.00"<?php if($productData[cyl_min]=='0.00') print " selected"; ?>>+0.00</option>
            <option value="-1.00"<?php if($productData[cyl_min]=='-1.00') print " selected"; ?>>-1.00</option>
            <option value="-2.00"<?php if($productData[cyl_min]=='-2.00') print " selected"; ?>>-2.00</option>
            <option value="-3.00"<?php if($productData[cyl_min]=='-3.00') print " selected"; ?>>-3.00</option>
            <option value="-4.00"<?php if($productData[cyl_min]=='-4.00') print " selected"; ?>>-4.00</option>
            <option value="-5.00"<?php if($productData[cyl_min]=='-5.00') print " selected"; ?>>-5.00</option>
            <option value="-6.00"<?php if($productData[cyl_min]=='-6.00') print " selected"; ?>>-6.00</option>
            <option value="-7.00"<?php if($productData[cyl_min]=='-7.00') print " selected"; ?>>-7.00</option>
            <option value="-8.00"<?php if($productData[cyl_min]=='-8.00') print " selected"; ?>>-8.00</option>
          </select>
        </font></td>
      </tr>
      <tr bgcolor="#DDDDDD">
         <td align="right" nowrap="nowrap" bgcolor="#DDDDDD"><p><font size="1" face="Helvetica, sans-serif, Arial">Reward Bonus:</font></p></td>
        <td colspan="3" align="left" bgcolor="#DDDDDD">&nbsp;</td>
        <td align="right" valign="middle" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Helvetica, sans-serif, Arial">Cylinder
            Overage Min:</font></td>
        <td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">
          <select name="cyl_over_min" class="formText" id="cyl_over_min">
            <option>Select Cylinder Overage Min</option>
            <option value="0.00"<?php if($productData[cyl_over_min]=='0.00') print " selected"; ?>>+0.00</option>
            <option value="-1.00"<?php if($productData[cyl_over_min]=='-1.00') print " selected"; ?>>-1.00</option>
            <option value="-2.00"<?php if($productData[cyl_over_min]=='-2.00') print " selected"; ?>>-2.00</option>
            <option value="-3.00"<?php if($productData[cyl_over_min]=='-3.00') print " selected"; ?>>-3.00</option>
            <option value="-4.00"<?php if($productData[cyl_over_min]=='-4.00') print " selected"; ?>>-4.00</option>
            <option value="-5.00"<?php if($productData[cyl_over_min]=='-5.00') print " selected"; ?>>-5.00</option>
            <option value="-6.00"<?php if($productData[cyl_over_min]=='-6.00') print " selected"; ?>>-6.00</option>
            <option value="-7.00"<?php if($productData[cyl_over_min]=='-7.00') print " selected"; ?>>-7.00</option>
            <option value="-8.00"<?php if($productData[cyl_over_min]=='-8.00') print " selected"; ?>>-8.00</option>
                                        </select>
        </font></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#FFFFFF"><p><font size="1" face="Helvetica, sans-serif, Arial">ADD
          Max: </font></p></td>
        <td colspan="3" align="left" bgcolor="#FFFFFF"><select name="add_max" class="formText" id="add_max">
          <option selected="selected">Select ADD Max</option>
          <option value="4.00"<?php if($productData[add_max]=='4.00') print " selected"; ?>>+4.00</option>
          <option value="3.75"<?php if($productData[add_max]=='3.75') print " selected"; ?>>+3.75</option>
          <option value="3.50"<?php if($productData[add_max]=='3.50') print " selected"; ?>>+3.50</option>
          <option value="3.25"<?php if($productData[add_max]=='3.25') print " selected"; ?>>+3.25</option>
          <option value="3.00"<?php if($productData[add_max]=='3.00') print " selected"; ?>>+3.00</option>
          <option value="2.75"<?php if($productData[add_max]=='2.75') print " selected"; ?>>+2.75</option>
          <option value="2.50"<?php if($productData[add_max]=='2.50') print " selected"; ?>>+2.50</option>
          <option value="2.25"<?php if($productData[add_max]=='2.25') print " selected"; ?>>+2.25</option>
          <option value="2.00"<?php if($productData[add_max]=='2.00') print " selected"; ?>>+2.00</option>
          <option value="1.75"<?php if($productData[add_max]=='1.75') print " selected"; ?>>+1.75</option>
          <option value="1.50"<?php if($productData[add_max]=='1.50') print " selected"; ?>>+1.50</option>
          <option value="1.25"<?php if($productData[add_max]=='1.25') print " selected"; ?>>+1.25</option>
          <option value="1.00"<?php if($productData[add_max]=='1.00') print " selected"; ?>>+1.00</option>
          <option value="0.75"<?php if($productData[add_max]=='0.75') print " selected"; ?>>+0.75</option>
          <option value="0.50"<?php if($productData[add_max]=='0.50') print " selected"; ?>>+0.50</option>
          <option value="0.00"<?php if($productData[add_max]=='0.00') print " selected"; ?>>+0.00</option>
        </select></td>
        <td align="right" valign="middle" nowrap="nowrap" bgcolor="#FFFFFF"><p><font size="1" face="Helvetica, sans-serif, Arial">ADD
          Min: </font></p></td>
        <td align="left" nowrap="nowrap" bgcolor="#FFFFFF"><select name="add_min" class="formText" id="add_min">
          <option selected="selected">Select ADD Min</option>
    	<option value="4.00"<?php if($productData[add_max]=='4.00') print " selected"; ?>>+4.00</option>
          <option value="3.75"<?php if($productData[add_min]=='3.75') print " selected"; ?>>+3.75</option>
          <option value="3.50"<?php if($productData[add_min]=='3.50') print " selected"; ?>>+3.50</option>
          <option value="3.25"<?php if($productData[add_min]=='3.25') print " selected"; ?>>+3.25</option>
          <option value="3.00"<?php if($productData[add_min]=='3.00') print " selected"; ?>>+3.00</option>
          <option value="2.75"<?php if($productData[add_min]=='2.75') print " selected"; ?>>+2.75</option>
          <option value="2.50"<?php if($productData[add_min]=='2.50') print " selected"; ?>>+2.50</option>
          <option value="2.25"<?php if($productData[add_min]=='2.25') print " selected"; ?>>+2.25</option>
          <option value="2.00"<?php if($productData[add_min]=='2.00') print " selected"; ?>>+2.00</option>
          <option value="1.75"<?php if($productData[add_min]=='1.75') print " selected"; ?>>+1.75</option>
          <option value="1.50"<?php if($productData[add_min]=='1.50') print " selected"; ?>>+1.50</option>
          <option value="1.25"<?php if($productData[add_min]=='1.25') print " selected"; ?>>+1.25</option>
          <option value="1.00"<?php if($productData[add_min]=='1.00') print " selected"; ?>>+1.00</option>
          <option value="0.75"<?php if($productData[add_min]=='0.75') print " selected"; ?>>+0.75</option>
          <option value="0.50"<?php if($productData[add_min]=='0.50') print " selected"; ?>>+0.50</option>
          <option value="0.00"<?php if($productData[add_min]=='0.00') print " selected"; ?>>+0.00</option>
        </select></td>
      </tr>
      <tr bgcolor="#FFFFFF">
        <td colspan="6" align="center" bgcolor="#DDDDDD"><input name="old_product_name" type="hidden" id="old_product_name" value="<?php print "$productData[product_name]"; ?>" />
          <input type="hidden" name="pkey" value="<?php print "$productData[primary_key]"; ?>" />
       <?php  if (($_POST[dupeProduct] == "Create Product and Duplicate Values for New Product")||($_POST[createProduct] == "Create Product")){
	print "<input type=\"submit\" name=\"createProduct\" id=\"edit\" value=\"Create Product\"> 
              &nbsp;
              <input type=\"submit\" name=\"dupeProduct\" id=\"dup\" value=\"Create Product and Duplicate Values for New Product\">";
}else print "<input type=\"submit\" class=\"formText\" name=\"editProduct\" id=\"edit\" value=\"Update Product\" />";?>
  &nbsp;
  <input name="deleteProduct" type="submit" class="formText" id="delete" value="Delete Product" />
  &nbsp;
  <input name="cancel" type="button" class="formText" id="cancel" onclick="window.open('adminHome.php', '_top')" value="No Change" />
  <a href="importExclusiveFile.php"><font size="1" face="Helvetica, sans-serif, Arial">&nbsp;Import
  Data File</font></a> </td>
      </tr>
    </table>
  </form>
