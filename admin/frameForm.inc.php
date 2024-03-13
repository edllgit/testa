<?php 
include "../sec_connectEDLL.inc.php";
?>
<form name="form4" method="post" action="newFrame.php"  enctype="multipart/form-data">
    
    <table width="100%" border="1" cellpadding="2" cellspacing="0" class="formText">
      <tr bgcolor="#000000">
        <td colspan="2" align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">Frames</font></b></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD" style="color:#CF0909"><b>Frame Collection</b>:</td>
        <td align="left" bgcolor="#DDDDDD"><select name="frames_collections_id" id="frames_collections_id"          onChange="getPricesEntrepot();getStockPrices();getDiscountedPrices();">
          <option value="" selected="selected">Select a Collection</option>
          <?php 
$query="SELECT distinct misc_unknown_purpose FROM ifc_frames_french ORDER BY collection"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listItem[misc_unknown_purpose]\"";
echo ">";$name=stripslashes($listItem[misc_unknown_purpose]);echo "$name</option>";}?>        </select>
 
</td>
</tr>
      
      
     

      <tr bgcolor="#FFFFFF">
        <td align="right" nowrap="nowrap" style="color:#CF0909"><b>Model (Example:1143P_CA7) </b>:</td>
        <td align="left" nowrap="nowrap">
          <input name="model_num" type="text" id="model_num" value="" size="15" />
          <span id="status"></span></div>
        </td>
      </tr>
      
       <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD">Color:</td>
        <td align="left" bgcolor="#DDDDDD"><input name="color" type="text" class="formText" id="color" value="" size="15" /></td>
      </tr>
      
       <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF">Color EN:</td>
        <td align="left" bgcolor="#FFFFFF"><input name="color_en" type="text" class="formText" id="color_en" value="" size="15" /></td>
      </tr>
      
       <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD">Color Code:</td>
        <td align="left" bgcolor="#DDDDDD"><input name="color_code" type="text" class="formText" id="color_code" value="" size="15" /></td>
      </tr>
      
      
     <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF">Type:</td>
        <td align="left" bgcolor="#FFFFFF"><select name="type" id="type">
          <?php 
$query="SELECT distinct type FROM ifc_frames_french ORDER BY type"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listItem[type]\"";
echo ">";$name=stripslashes($listItem[type]);echo "$name</option>";}?>        </select></td>
      </tr>
      
       <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD">Type EN:</td>
               <td align="left" bgcolor="#DDDDDD">
               <select name="type_en" id="type_en">
          <?php 
$query="SELECT distinct type_en FROM ifc_frames_french ORDER BY type_en"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listItem[type_en]\"";
echo ">";$name=stripslashes($listItem[type_en]);echo "$name</option>";}?>        
</select></td>
      </tr>
      
      <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF">Gender:</td>
       <td align="left" bgcolor="#FFFFFF">
               <select name="gender" id="gender">
          <?php 
$query="SELECT distinct gender FROM ifc_frames_french ORDER BY gender"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo '<option value="'. $listItem[gender]. '"';
echo ">";$name=stripslashes($listItem[gender]);echo $name . "</option>";}?>        
</select></td>
      </tr>
      
       <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD">Gender EN:</td>
         <td align="left" bgcolor="#DDDDDD">
               <select name="gender_en" id="gender">
          <?php 
$query="SELECT distinct gender_en FROM ifc_frames_french ORDER BY gender_en"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listItem[gender_en]\"";
echo ">";$name=stripslashes($listItem[gender_en]);echo "$name</option>";}?>        
</select></td>
      </tr>
      
      <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF">Material:</td>
        <td align="left" bgcolor="#FFFFFF">
               <select name="material" id="material">
          <?php 
$query="SELECT distinct material FROM ifc_frames_french ORDER BY material"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listItem[material]\"";
echo ">";$name=stripslashes($listItem[material]);echo "$name</option>";}?>        
</select></td>
      </tr>
      
      <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD">Material EN:</td>
        <td align="left" bgcolor="#DDDDDD">
               <select name="material_en" id="material_en">
          <?php 
$query="SELECT distinct material_en FROM ifc_frames_french ORDER BY material_en"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listItem[material_en]\"";
echo ">";$name=stripslashes($listItem[material_en]);echo "$name</option>";}?>        
</select></td>
      </tr>
      
      <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF">Mounting:</td>
        <td align="left" bgcolor="#FFFFFF">
               <select name="mounting" id="mounting">
          <?php 
$query="SELECT distinct mounting FROM ifc_frames_french ORDER BY mounting"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listItem[mounting]\"";
echo ">";$name=stripslashes($listItem[mounting]);echo "$name</option>";}?>        
</select></td>
      </tr>
      
       <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD">Mounting EN:</td>
        <td align="left" bgcolor="#DDDDDD">
               <select name="mounting_en" id="mounting_en">
          <?php 
$query="SELECT distinct mounting_en FROM ifc_frames_french ORDER BY mounting_en"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listItem[mounting_en]\"";
echo ">";$name=stripslashes($listItem[mounting_en]);echo "$name</option>";}?>        
</select></td>
      </tr>
      
      
         
      <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF">Frame Shape:</td>
        <td align="left" bgcolor="#FFFFFF">
               <select name="frame_shape" id="frame_shape">
          <?php 
$query="SELECT distinct frame_shape FROM ifc_frames_french ORDER BY frame_shape"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listItem[frame_shape]\"";
echo ">";$name=stripslashes($listItem[frame_shape]);echo "$name</option>";}?>        
</select></td>
      </tr>
      
      
       <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD">Frame Shape EN:</td>
        <td align="left" bgcolor="#DDDDDD">
               <select name="frame_shape_en" id="frame_shape_en">
          <?php 
$query="SELECT distinct frame_shape_en FROM ifc_frames_french ORDER BY frame_shape_en"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listItem[frame_shape_en]\"";
echo ">";$name=stripslashes($listItem[frame_shape_en]);echo "$name</option>";}?>        
</select></td>
      </tr>
      
      
      
      <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF">Branches Material:</td>
        <td align="left" bgcolor="#FFFFFF">
               <select name="branches_material" id="branches_material">
          <?php 
$query="SELECT distinct branches_material FROM ifc_frames_french ORDER BY branches_material"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listItem[branches_material]\"";
echo ">";$name=stripslashes($listItem[branches_material]);echo "$name</option>";}?>        
</select></td>
      </tr>
      
      
        
      <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD">Branches Material EN:</td>
        <td align="left" bgcolor="#DDDDDD">
               <select name="branches_material_en" id="branches_material_en">
          <?php 
$query="SELECT distinct branches_material_en FROM ifc_frames_french ORDER BY branches_material_en"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listItem[branches_material_en]\"";
echo ">";$name=stripslashes($listItem[branches_material_en]);echo "$name</option>";}?>        
</select></td>
      </tr>

	

 	  <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF">Boxing:</td>
        <td align="left" bgcolor="#FFFFFF"><input name="boxing" type="text" class="formText" id="boxing" value="" size="15" /></td>
      </tr>
      
    
      <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD">Frame <b>Price</b>:</td>
         <td align="left" bgcolor="#DDDDDD">
               <select name="stock_price" id="stock_price">
          <?php 
$query="SELECT distinct stock_price FROM ifc_frames_french where stock_price <> 500 ORDER BY stock_price"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listItem[stock_price]\"";
echo ">";$name=stripslashes($listItem[stock_price]);echo "$name</option>";}?>        
</select>&nbsp;&nbsp;<span id="actualStockPrices"></span></td>
      </tr>
     
      
      <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF" style="color:#CF0909"><b>Price Entrepot</b>:</td>
         <td align="left" bgcolor="#FFFFFF">
               <select name="stock_price_entrepot" id="stock_price_entrepot">
          <?php 
$query="SELECT distinct stock_price_entrepot FROM ifc_frames_french  where stock_price_entrepot <> 500 ORDER BY stock_price_entrepot"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listItem[stock_price_entrepot]\"";
echo ">";$name=stripslashes($listItem[stock_price_entrepot]);echo "$name</option>";}?>        
</select>&nbsp;&nbsp;<span id="actualPrices"></span></td>
      </tr>
      
      <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD"><b>Price with Discount</b>:</td>
        <td align="left" bgcolor="#DDDDDD">
               <select name="stock_price_with_discount" id="stock_price_with_discount">
          <?php 
$query="SELECT distinct stock_price_with_discount FROM ifc_frames_french where stock_price_with_discount <> 500 ORDER BY stock_price_with_discount"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listItem[stock_price_with_discount]\"";
echo ">";$name=stripslashes($listItem[stock_price_with_discount]);echo "$name</option>";}?>        
</select>&nbsp;&nbsp;<span id="actualDiscountedPrices"></span></td>
      </tr>
      
      <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF">&nbsp;</td>
        <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
      </tr>
      
      
  
       
      <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD" style="color:#CF0909"><b>Active</b>:</td>
        <td align="left" bgcolor="#DDDDDD"><input name="frame_status" type="checkbox" id="frame_status" value="active" checked="checked" /></td>
      </tr>
      
      <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD">On Sale</td>
        <td align="left" bgcolor="#DDDDDD"><input name="frame_on_sale" type="checkbox" id="frame_on_sale" value="yes" <?php if ($frameItem[frame_on_sale]=="yes"){echo " checked=\"checked\"";} ?>/></td>
      </tr>
      
      <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF">Available at <b>Supplier</b>:</td>
        <td align="left" bgcolor="#FFFFFF"><input name="available_at_supplier" type="checkbox" id="available_at_supplier" checked="checked" value="yes" <?php if ($frameItem[available_at_supplier]=="yes"){echo " checked=\"checked\"";} ?>/></td>
      </tr>
      
      
      
       <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD">Available for <b>ifc.ca(Regular customers)</b>:</td>
        <td align="left" bgcolor="#DDDDDD"><input name="display_on_ifcca" type="checkbox" id="display_on_ifcca" value="yes" <?php if ($frameItem[display_on_ifcca]=="yes"){echo " checked=\"checked\"";} ?>/></td>
      </tr>
            
      <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF">Cette monture fait partie du <b>Package Rep</b>:</td>
        <td align="left" bgcolor="#FFFFFF"><input name="display_milano_package_rep" type="checkbox" id="display_milano_package_rep" value="active" <?php if ($frameItem[display_milano_package_rep]=="yes"){echo " checked=\"checked\"";} ?>/></td>
      </tr>
      
        <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF">Cette monture fait partie du <b>Package Autres</b>:</td>
        <td align="left" bgcolor="#FFFFFF"><input name="display_milano_package_other" type="checkbox" id="display_milano_package_other" value="active" <?php if ($frameItem[display_milano_package_other]=="yes"){echo " checked=\"checked\"";} ?>/></td>
      </tr>
      

	 <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD" style="color:#CF0909"><b>Available for Entrepot de la lunette</b>:</td>
       <td align="left" bgcolor="#DDDDDD"><input name="display_entrepot" type="checkbox" checked="checked" id="display_entrepot" value="yes" <?php if ($frameItem[display_entrepot]=="yes"){echo " checked=\"checked\"";} ?>/></td>
      </tr>

      
      <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF"><b>Shape is available</b> for remote edging:</td>
        <td align="left" bgcolor="#FFFFFF"><input name="shape_dispo_entrepot" type="checkbox" id="shape_dispo_entrepot" value="yes" <?php if ($frameItem[shape_dispo_entrepot]=="yes"){echo " checked=\"checked\"";} ?>/></td>
     
      </tr>
    
    
    
      <tr bgcolor="#DDDDDD">
        <td colspan="2" align="center" nowrap="nowrap" bgcolor="#DDDDDD">A:
          <input name="frame_A" type="text" id="frame_A" value="" size="6" />
          &nbsp;&nbsp;B: 
        
          <input name="frame_B" type="text" id="frame_B" value="" size="6" />
        &nbsp;&nbsp;ED:
        <input name="frame_ED" type="text" id="frame_ED" value="" size="6" />
        &nbsp;FRAME DBL:
        <input name="frame_DBL" type="text" id="frame_DBL" value="" size="6" /></td>
      </tr>

      <tr bgcolor="#FFFFFF">
        <td colspan="2" align="center" bgcolor="#BBBBBB"><input name="from_form" type="hidden" id="from_form" value="add" />
          <input name="color_count" type="hidden" id="color_count" value="<?php echo $count;?>" />
          <input name="collection_count" type="hidden" id="collection_count" value="<?php echo $c_count;?>" />
  <input name="Submit" type="submit" value="SUBMIT" onClick="check('form4', this.name);" />
  &nbsp;</td>
      </tr>
    </table>
    <div>
<ul id="results" class="update">
</ul>
</div>
</form>
