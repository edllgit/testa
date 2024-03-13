<?php


$MATERIAL=$_POST[MATERIAL];
$INDEX=$_POST[INDEX];
$COATING=$_POST[COATING];
$SPHERE=$_POST[SPHERE];
$CYLINDER=$_POST[CYLINDER];

$_SESSION["TEMP_TRAY_REF"]=$_POST[TRAY];

if ($_SESSION["sessionUserData"]["currency"]=="US"){
		$currency="price";}
else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
		$currency="price_can";}
else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
		$currency="price_eur";}

$query="select price,price_can,price_eur,prices.product_name,products.mfg,products.product_name,products.primary_key,material,filter,coating,d_index,sph_base,cyl_add from products,prices WHERE coating='$COATING' AND material='$MATERIAL' AND sph_base='$SPHERE' AND cyl_add='$CYLINDER' AND d_index='$INDEX' and type='stock' AND products.product_name=prices.product_name AND prices.".$currency."!=0 ORDER by products.product_name asc"; //TEAM LEADERS SECTION

$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
$usercount=mysql_num_rows($result);
if ($usercount != 0){

print"<form id=\"stockSearch\" name=\"stockSearch\" method=\"post\" action=\"stock.php\">";
}
print "<div class=\"plainText\">Tray Reference: ".$_SESSION["TEMP_TRAY_REF"]."</div>";
?><table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="10" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_reavailprod_txt;?>&nbsp;</td>
  </tr>
              <tr>
                <td align="center"  class="formCell"><?php echo $lbl_manufacturer_txt_stock;?>&nbsp;</td>
                <td align="center"  class="formCell"><?php echo $lbl_productname_txt_stock;?>&nbsp;</td>
                <td align="center" class="formCell"><?php echo $lbl_material_txt_stock;?>&nbsp;</td>
                <td align="center" class="formCell"><?php echo $lbl_coating_txt_stock;?>&nbsp;</td>
                <td align="center" class="formCell"><?php echo $lbl_index_txt_stock;?>&nbsp;</td>
                <td align="center" class="formCell"><?php echo $lbl_sphere_txt_stock;?>&nbsp;</td>
                <td align="center" class="formCell"><?php echo $lbl_cylinder_txt_stock;?>&nbsp;</td>
                <td align="center" class="formCell"><?php print "Price (".$_SESSION["sessionUserData"]["currency"].")";?></td>
                <td align="center" class="formCell"><?php echo $lbl_select_txt_stock;?>&nbsp;</td>
              </tr>
			  <?php

if ($usercount == 0){ /* no positions to list */
	print "<tr><td colspan=\"9\" class=\"formCell\">".$lbl_sorrynorecords_txt."</td></tr>";
	}else{
		print "<tr>";
		while ($listItem=mysql_fetch_array($result)){
		$count++;
		
		switch ($listItem[material]) {
		case "GL":
    		$material="Glass";
    		break;
		case "GH":
   			 $material="Glass (High Index)";
   			 break;
		case "PL":
   			 $material="Plastic";
   			 break;
		case "PH":
  		  $material="Plastic (High Index)";
   			 break;
		case "PY":
    		$material="Polycarbonate";
   			 break;
			 }
			 
		if ($listItem[coating]=="UC")
			$coating="Uncoated";
		else if ($listItem[coating]=="AR")
			$coating="Anti-Reflective";
		else if ($listItem[coating]=="SR")
			$coating="Scratch-Resistant";
		else if ($listItem[coating]=="SR AR")
			$coating="Scratch Resistant and Anti-Reflective";
		else $coating=$listItem[coating_brand];
		
				print "<td  align=\"center\" class=\"formCell\">";
			print $listItem[mfg];
			print "</td>";
		
			print "<td align=\"center\" class=\"formCell\">";
			print "<a href=\"#\" onClick=\"MM_openBrWindow('lens_specs_stock.php?pkey=$listItem[primary_key]','popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=350,height=280')\">";
			print $listItem[product_name];
			print "</a></td>";
			
			print "<td  align=\"center\" class=\"formCell\">";
			print $material;
			print "</td>";
			
			print "<td  align=\"center\" class=\"formCell\">";
			print $coating;
			print "</td>";
			
			print "<td  align=\"center\" class=\"formCell\">";
			print $listItem[d_index];
			print "</td>";
			
			print "<td  align=\"center\" class=\"formCell\">";
			print $listItem[sph_base];
			print "</td>";
			
			print "<td  align=\"center\" class=\"formCell\">";
			print $listItem[cyl_add];
			print "</td>";
			
			print "<td  align=\"center\" class=\"formCell\">";
			if ($_SESSION["sessionUserData"]["currency"]=="US"){
				print $listItem[price];}
			else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
				print $listItem[price_can];}
			else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
				print $listItem[price_eur];}
			print "</td>";
			
			print "<td  align=\"center\" class=\"formCell\">";
			print "<input type=\"radio\" name=\"RE_RADIO\" value=\"$listItem[primary_key]\" checked=\"checked\" / class=\"formText\"/></td></tr>";
			   }//end of while
}//end of 0 usercount
?>
            </table>
			
			<?php //BEGINNING of LEFT EYE

$MATERIAL=$_POST[MATERIAL2];
$INDEX=$_POST[INDEX2];
$COATING=$_POST[COATING2];
$SPHERE=$_POST[SPHERE2];
$CYLINDER=$_POST[CYLINDER2];

$query="select price,price_can,price_eur,prices.product_name,products.mfg,products.product_name,products.primary_key,material,filter,coating,d_index,sph_base,cyl_add from products,prices WHERE coating='$COATING' AND material='$MATERIAL' AND sph_base='$SPHERE' AND cyl_add='$CYLINDER' AND d_index='$INDEX' and type='stock' AND products.product_name=prices.product_name AND prices.".$currency."!=0 ORDER by products.product_name asc"; //TEAM LEADERS SECTION

$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
$usercount=mysql_num_rows($result);
if ($usercount != 0){


}

?><table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="10" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_leavailprod_txt;?>&nbsp;</td>
  </tr>
              <tr>
                <td align="center"  class="formCell"><?php echo $lbl_manufacturer_txt_stock;?>&nbsp;</td>
                <td align="center"  class="formCell"><?php echo $lbl_productname_txt_stock;?>&nbsp;</td>
                <td align="center" class="formCell"><?php echo $lbl_material_txt_stock;?>&nbsp;</td>
                <td align="center" class="formCell"><?php echo $lbl_coating_txt_stock;?>&nbsp;</td>
                <td align="center" class="formCell"><?php echo $lbl_index_txt_stock;?>&nbsp;</td>
                <td align="center" class="formCell"><?php echo $lbl_sphere_txt_stock;?>&nbsp;</td>
                <td align="center" class="formCell"><?php echo $lbl_cylinder_txt_stock;?>&nbsp;</td>
                <td align="center" class="formCell"><?php print "Price (".$_SESSION["sessionUserData"]["currency"].")";?></td>
                <td align="center" class="formCell"><?php echo $lbl_select_txt_stock;?>&nbsp;</td>
              </tr>
			  <?php

if ($usercount == 0){ /* no positions to list */
	print "<tr><td colspan=\"9\" class=\"formCell\">Sorry, no items found.</td></tr>";
	}else{
		print "<tr>";
		while ($listItem=mysql_fetch_array($result)){
		$count++;
		
		switch ($listItem[material]) {
		case "GL":
    		$material="Glass";
    		break;
		case "GH":
   			 $material="Glass (High Index)";
   			 break;
		case "PL":
   			 $material="Plastic";
   			 break;
		case "PH":
  		  $material="Plastic (High Index)";
   			 break;
		case "PY":
    		$material="Polycarbonate";
   			 break;
			 }
			 
		if ($listItem[coating]=="UC")
			$coating="Uncoated";
		else if ($listItem[coating]=="AR")
			$coating="Anti-Reflective";
		else if ($listItem[coating]=="SR")
			$coating="Scratch-Resistant";
		else if ($listItem[coating]=="SR AR")
			$coating="Scratch Resistant and Anti-Reflective";
		else $coating=$listItem[coating_brand];
		
				print "<td  align=\"center\" class=\"formCell\">";
			print $listItem[mfg];
			print "</td>";
		
			print "<td align=\"center\" class=\"formCell\">";
			print "<a href=\"#\" onClick=\"MM_openBrWindow('lens_specs_stock.php?pkey=$listItem[primary_key]','popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=350,height=280')\">";
			print $listItem[product_name];
			print "</a></td>";
			
			print "<td  align=\"center\" class=\"formCell\">";
			print $material;
			print "</td>";
			
			print "<td  align=\"center\" class=\"formCell\">";
			print $coating;
			print "</td>";
			
			print "<td  align=\"center\" class=\"formCell\">";
			print $listItem[d_index];
			print "</td>";
			
			print "<td  align=\"center\" class=\"formCell\">";
			print $listItem[sph_base];
			print "</td>";
			
			print "<td  align=\"center\" class=\"formCell\">";
			print $listItem[cyl_add];
			print "</td>";
			
			print "<td  align=\"center\" class=\"formCell\">";
			if ($_SESSION["sessionUserData"]["currency"]=="US"){
				print $listItem[price];}
			else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
				print $listItem[price_can];}
			else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
				print $listItem[price_eur];}
			print "</td>";
			
			print "<td  align=\"center\" class=\"formCell\">";
			print "<input type=\"radio\" name=\"LE_RADIO\" value=\"$listItem[primary_key]\" checked=\"checked\" /class=\"formText\"/></td></tr>";
			   }//end of while
}//end of 0 usercount
?>

            </table>

 <?php if ($usercount != 0){ 
 print"<div align=\"center\" style=\"margin:11px\"><input name=\"Submit\" type=\"submit\" class=\"formText\" value=\"Add Selected Items to Tray\" /><input name=\"fromTrayAdd\" type=\"hidden\" value=\"true\" /><input name=\"itemCount\" type=\"hidden\" value=\"$count\" /></div></form>";
			}
			
			?>