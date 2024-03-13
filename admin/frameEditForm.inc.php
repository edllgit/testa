<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
require_once(__DIR__.'/../constants/url.constant.php');
?>
<form name="form4" method="post" action="newFrame.php"  enctype="multipart/form-data">
    <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formText">
      <tr bgcolor="#000000">
        <td colspan="2" align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">Frames</font></b></td>
      </tr>
      
       <tr bgcolor="#FFFFFF">
        <td align="right" nowrap="nowrap">ID:</td>
        <td align="left" nowrap="nowrap"><b><?php echo  $frameItem[ifc_frames_id]; ?></b></td>
      </tr>

      
      <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD" style="color:#CF0909"><b>Frame Collection</b>:</td>
        <td align="left" bgcolor="#DDDDDD"><select name="frames_collections_id" id="frames_collections_id">
          <option value="" selected="selected">Select a Collection</option>
          <?php 
$query="SELECT distinct misc_unknown_purpose FROM ifc_frames_french ORDER BY collection"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listItem[misc_unknown_purpose]\"";
if ($frameItem[collection]==$listItem[misc_unknown_purpose]){echo " selected";}
echo ">";$name=stripslashes($listItem[misc_unknown_purpose]);echo "$name</option>";}?>
        </select></td>
      </tr>
       

      <tr bgcolor="#FFFFFF">
        <td align="right" nowrap="nowrap" style="color:#CF0909"><b>Model</b>:</td>
        <td align="left" nowrap="nowrap">
          <input name="model_num" type="text" id="model_num" value="<?php echo $frameItem[model];?>" size="15" />
        </td>
      </tr>
      
        <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap"  style="color:#CF0909"><b>UPC</b>:</td>
        <td align="left" nowrap="nowrap">
          <input name="upc" type="text" id="upc" value="<?php echo $frameItem[upc];?>" size="15" /></td>
      </tr>
      
      
      <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF"  style="color:#CF0909"><b>Code</b>:</td>
        <td align="left" bgcolor="#FFFFFF"><input name="code" type="text" class="formText" id="code" value="<?php echo $frameItem[code];?>" size="15" /></td>
      </tr>
      
       <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD">Color:</td>
        <td align="left" bgcolor="#DDDDDD"><input name="color" type="text" class="formText" id="color" value="<?php echo $frameItem[color];?>" size="15" /></td>
      </tr>
      
       <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF">Color EN:</td>
        <td align="left" bgcolor="#FFFFFF"><input name="color_en" type="text" class="formText" id="color_en" value="<?php echo $frameItem[color_en];?>" size="15" /></td>
      </tr>
      
       <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD">Color Code:</td>
        <td align="left" bgcolor="#DDDDDD"><input name="color_code" type="text" class="formText" id="color_code" value="<?php echo $frameItem[color_code];?>" size="15" /></td>
      </tr>
      
      
      
            
        <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF">Type:</td>
        <td align="left" bgcolor="#FFFFFF"><select name="type" id="type">
          <?php 
$query="SELECT distinct type FROM ifc_frames_french ORDER BY type"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listItem[type]\"";
if ($listItem[type] == $frameItem[type]) echo ' selected';
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
if ($listItem[type_en] == $frameItem[type_en]) echo ' selected';
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
if ($listItem[gender] == $frameItem[gender]) echo ' selected';
echo ">";$name=stripslashes($listItem[gender]);echo $name . "</option>";}?>        
</select></td>
      </tr>
      
      
      
       <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD">Gender EN:</td>
         <td align="left" bgcolor="#DDDDDD">
               <select name="gender_en" id="gender_en">
          <?php 
$query="SELECT distinct gender_en FROM ifc_frames_french ORDER BY gender_en"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listItem[gender_en]\"";
if ($listItem[gender_en] == $frameItem[gender_en]) echo ' selected';
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
if ($listItem[material] == $frameItem[material]) echo ' selected';
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
if ($listItem[material_en] == $frameItem[material_en]) echo ' selected';
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
if ($listItem[mounting] == $frameItem[mounting]) echo ' selected';
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
if ($listItem[mounting_en] == $frameItem[mounting_en]) echo ' selected';
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
if ($listItem[frame_shape] == $frameItem[frame_shape]) echo ' selected';
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
if ($listItem[frame_shape_en] == $frameItem[frame_shape_en]) echo ' selected';
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
if ($listItem[branches_material] == $frameItem[branches_material]) echo ' selected';
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
if ($listItem[branches_material_en] == $frameItem[branches_material_en]) echo ' selected';
echo ">";$name=stripslashes($listItem[branches_material_en]);echo "$name</option>";}?>        
</select></td>
      </tr>

	


 	  <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF">Boxing:</td>
        <td align="left" bgcolor="#FFFFFF"><input name="boxing" type="text" class="formText" id="boxing" value="<?php echo $frameItem[boxing];?>" size="15" /></td>
      </tr>
      
      <?php 
	  $PathImage = constant('DIRECT_LENS_URL')."/ifcopticclub/prod_images/".$frameItem[image];
	  ?>
      
       <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD">Image:</td>
        <td align="left" bgcolor="#DDDDDD"><img width="250" src="<?php echo $PathImage;?>"></td>
      </tr>
      
      
   

     <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF">&nbsp;</td>
        <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
      </tr>

     <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD"><b>Price</b>:</td>
         <td align="left" bgcolor="#DDDDDD">
               <select name="stock_price" id="stock_price">
          <?php 
$query="SELECT distinct stock_price FROM ifc_frames_french where stock_price <> 500 ORDER BY stock_price"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listItem[stock_price]\"";
if ($listItem[stock_price] == $frameItem[stock_price]) echo ' selected';
echo ">";$name=stripslashes($listItem[stock_price]);echo "$name</option>";}?>        
</select></td>
      </tr>
     
      
      <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF"  style="color:#CF0909"><b>Price Entrepot</b>:</td>
         <td align="left" bgcolor="#FFFFFF">
               <select name="stock_price_entrepot" id="stock_price_entrepot">
          <?php 
$query="SELECT distinct stock_price_entrepot FROM ifc_frames_french  where stock_price_entrepot <> 500 ORDER BY stock_price_entrepot"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listItem[stock_price_entrepot]\"";
if ($listItem[stock_price_entrepot] == $frameItem[stock_price_entrepot]) echo ' selected';
echo ">";$name=stripslashes($listItem[stock_price_entrepot]);echo "$name</option>";}?>        
</select></td>
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
if ($listItem[stock_price_with_discount] == $frameItem[stock_price_with_discount]) echo ' selected';
echo ">";$name=stripslashes($listItem[stock_price_with_discount]);echo "$name</option>";}?>        
</select></td>
      </tr>
      
      <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF">&nbsp;</td>
        <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
      </tr>
      
       <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD"  style="color:#CF0909"><b>Active</b>:</td>
        <td align="left" bgcolor="#DDDDDD" ><input name="frame_status" type="checkbox" id="frame_status" value="active"  
		<?php if ($frameItem[active]> 0){echo " checked=\"checked\"";} ?> /></td>
      </tr>
      
      <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF">On Sale</td>
        <td align="left" bgcolor="#FFFFFF"><input name="frame_on_sale" type="checkbox" id="frame_on_sale" value="active" <?php if ($frameItem[frame_on_sale]=="yes"){echo " checked=\"checked\"";} ?>/></td>
      </tr>
      
      <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD">Available at <b>Supplier</b>:</td>
        <td align="left" bgcolor="#DDDDDD"><input name="available_at_supplier" type="checkbox" id="available_at_supplier" value="active" <?php if ($frameItem[available_at_supplier]=="yes"){echo " checked=\"checked\"";} ?>/></td>
      </tr>
      
      
      
       <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF">Available for <b>ifc.ca(Regular customers)</b>:</td>
        <td align="left" bgcolor="#FFFFFF"><input name="display_on_ifcca" type="checkbox" id="display_on_ifcca" value="active" <?php if ($frameItem[display_on_ifcca]=="yes"){echo " checked=\"checked\"";} ?>/></td>
      </tr>
      
   <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF">Cette monture fait partie du <b>Package Rep</b>:</td>
        <td align="left" bgcolor="#FFFFFF"><input name="display_milano_package_rep" type="checkbox" id="display_milano_package_rep" value="active" <?php if ($frameItem[display_milano_package_rep]=="yes"){echo " checked=\"checked\"";} ?>/></td>
      </tr>
      
        <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF">Cette monture fait partie du <b>Package Autres</b>:</td>
        <td align="left" bgcolor="#FFFFFF"><input name="display_milano_package_other" type="checkbox" id="display_milano_package_other" value="active" <?php if ($frameItem[display_milano_package_other]=="yes"){echo " checked=\"checked\"";} ?>/></td>
      </tr>
      

	 <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF"  style="color:#CF0909"><b>Available for Entrepot de la lunette</b>:</td>
       <td align="left" bgcolor="#FFFFFF"><input name="display_entrepot" type="checkbox" id="display_entrepot" value="active" <?php if ($frameItem[display_entrepot]=="active"){echo " checked=\"checked\"";} ?>/></td>
      </tr>

      
      <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD"><b>Shape is available</b> for remote edging:</td>
        <td align="left" bgcolor="#DDDDDD"><input name="shape_dispo_entrepot" type="checkbox" id="shape_dispo_entrepot" value="active" <?php if ($frameItem[shape_dispo_entrepot]=="yes"){echo " checked=\"checked\"";} ?>/></td>
     
      </tr>
      
       
         
      
      <tr bgcolor="#FFFFFF">
        <td colspan="2" align="center" nowrap="nowrap" bgcolor="#FFFFFF">A:
          <input name="frame_A" type="text" id="frame_A" value="<?php echo $frameItem[frame_a];?>" size="3" />
          &nbsp;&nbsp;B: 
          <input name="frame_B" type="text" id="frame_B" value="<?php echo $frameItem[frame_b];?>" size="3" />
        &nbsp;&nbsp;ED:
        <input name="frame_ED" type="text" id="frame_ED" value="<?php echo $frameItem[frame_ed];?>" size="3" />
        &nbsp;FRAME DBL:
        <input name="frame_DBL" type="text" id="frame_DBL" value="<?php echo $frameItem[frame_dbl];?>" size="3" /></td>
      </tr>
      
   
      <tr bgcolor="#FFFFFF">
        <td colspan="2" align="center" bgcolor="#BBBBBB"><input name="from_form" type="hidden" id="from_form" value="edit" />
          <input name="pkey" type="hidden" id="pkey" value="<?php echo $_GET[pkey];?>" />
          <input name="Submit" type="submit"   value="UPDATE" />
&nbsp;</td>
      </tr>
    </table>
</form>