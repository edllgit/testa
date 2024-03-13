
<?php

 /* no trays to list */


print "<table width=\"770\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formBox\">";
print "<tr><td colspan=\"8\" bgcolor=\"#D5EEF7\"><div class=\"plainText\" >Tray Reference: ".$_SESSION["TRAY_REF"][$i]."</div></td>";
print " <td align=\"center\"  class=\"formCell\"  bgcolor=\"#D5EEF7\">";
print " <form id=\"".$_SESSION["COUNT"]."\" name=\"".$_SESSION["COUNT"]."\" method=\"post\" action=\"stock.php\">";
print " <input name=\"Submit\" type=\"submit\" class=\"formText\"value=\"Remove\" />";
print " <input name=\"deleteTrayItem\" type=\"hidden\" value=\"true\" />";
print "<input name=\"tray_ref\" type=\"hidden\" value=\"".$i."\" /></td></tr></form>";

print " <tr><td align=\"center\"  class=\"formCell\">&nbsp;</td>";
print "<td align=\"center\"  class=\"formCell\">Manufacturer</td>";
print "<td align=\"center\"  class=\"formCell\">Product Name </td>";
print "<td align=\"center\" class=\"formCell\">Material</td>";
print "<td align=\"center\" class=\"formCell\">Coating</td>";
print "<td align=\"center\" class=\"formCell\">Index</td>";
print "<td align=\"center\" class=\"formCell\">Sphere</td>";
print "<td align=\"center\" class=\"formCell\">Cylinder</td>";
print "<td align=\"center\" class=\"formCell\">Price</td></tr>";

	$RE=$_SESSION["RE"][$i];
	
	$query="select price,prices.product_name,products.mfg,products.product_name,products.primary_key,material,filter,coating,d_index,sph_base,cyl_add from products,prices WHERE products.product_name=prices.product_name and products.primary_key='$RE'"; //TEAM LEADERS SECTION

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
			print $listItem[price];
			print "</td></tr>";
			
			$LE=$_SESSION["LE"][$i];
	
			$query="select price,prices.product_name,products.mfg,products.product_name,products.primary_key,material,filter,coating,d_index,sph_base,cyl_add from products,prices WHERE products.product_name=prices.product_name and products.primary_key='$LE'"; //TEAM LEADERS SECTION

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
			print $listItem[price];
			print "</td></tr></table>";
			
?>


			
			