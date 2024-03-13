<?php

print "<table width=\"650\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formBox\">";
print "<tr><td colspan=\"8\" bgcolor=\"#D7E1FF\"><div class=\"plainText\" >".$lbl_trayref_txt." ".$_SESSION["TRAY_REF"][$i]."</div></td>";
print " <td align=\"center\"  class=\"formCell\"  bgcolor=\"#D7E1FF\">";
print " <form id=\"".$_SESSION["COUNT"]."\" name=\"".$_SESSION["COUNT"]."\" method=\"post\" action=\"stock.php\">";
print " <input name=\"Submit\" type=\"submit\" class=\"formText\"value=\"".$btn_remove_txt."\" />";
print " <input name=\"deleteTrayItem\" type=\"hidden\" value=\"true\" />";
print "<input name=\"tray_ref\" type=\"hidden\" value=\"".$i."\" /></td></tr></form>";

print " <tr><td align=\"center\"  class=\"formCell\">&nbsp;</td>";
print "<td align=\"center\"  class=\"formCell\">".$lbl_manufacturer_txt_stock."</td>";
print "<td align=\"center\"  class=\"formCell\">".$lbl_productname_txt_stock."</td>";
print "<td align=\"center\" class=\"formCell\">".$lbl_material_txt_stock."</td>";
print "<td align=\"center\" class=\"formCell\">".$lbl_coating_txt_stock."</td>";
print "<td align=\"center\" class=\"formCell\">".$lbl_index_txt_stock."</td>";
print "<td align=\"center\" class=\"formCell\">".$lbl_sphere_txt_stock."</td>";
print "<td align=\"center\" class=\"formCell\">".$lbl_cylinder_txt_stock."</td>";
print "<td align=\"center\" class=\"formCell\">".$lbl_price_txt." (".$_SESSION["sessionUserData"]["currency"].")</td></tr>";

	$RE=$_SESSION["RE"][$i];
	
	$query="select price,price_can,price_eur,prices.product_name,products.mfg,products.product_name,products.primary_key,material,filter,coating,d_index,sph_base,cyl_add from products,prices WHERE products.product_name=prices.product_name and products.primary_key='$RE'"; //TEAM LEADERS SECTION

		$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
		print "<tr>";
		
		$listItem=mysql_fetch_array($result);
		
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
			print "R.E.</td>";
		
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
			print "</td></tr>";
			
			$LE=$_SESSION["LE"][$i];
	
			$query="select price,price_can,price_eur,prices.product_name,products.mfg,products.product_name,products.primary_key,material,filter,coating,d_index,sph_base,cyl_add from products,prices WHERE products.product_name=prices.product_name and products.primary_key='$LE'"; //TEAM LEADERS SECTION

		$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
		print "<tr>";
		
		$listItem=mysql_fetch_array($result);
		
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
			print "L.E.</td>";
		
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
			print "</td></tr></table>";
			
?>

			
			
			