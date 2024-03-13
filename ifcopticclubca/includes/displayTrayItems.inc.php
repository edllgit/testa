<?php

echo "<table width=\"770\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formBox\">";
echo "<tr><td colspan=\"8\" bgcolor=\"#D5EEF7\"><div class=\"plainText\" >".$lbl_trayref_txt." ".$_SESSION["TRAY_REF"][$i]."</div></td>";
echo " <td align=\"center\"  class=\"formCell\"  bgcolor=\"#D5EEF7\">";
echo " <form id=\"".$_SESSION["COUNT"]."\" name=\"".$_SESSION["COUNT"]."\" method=\"post\" action=\"stock.php\">";
echo " <input name=\"Submit\" type=\"submit\" class=\"formText\"value=\"".$btn_remove_txt."\" />";
echo " <input name=\"deleteTrayItem\" type=\"hidden\" value=\"true\" />";
echo "<input name=\"tray_ref\" type=\"hidden\" value=\"".$i."\" /></td></tr></form>";

echo " <tr><td align=\"center\"  class=\"formCell\">&nbsp;</td>";
echo "<td align=\"center\"  class=\"formCell\">".$lbl_manufacturer_txt_stock."</td>";
echo "<td align=\"center\"  class=\"formCell\">".$lbl_productname_txt_stock."</td>";
echo "<td align=\"center\" class=\"formCell\">".$lbl_material_txt_stock."</td>";
echo "<td align=\"center\" class=\"formCell\">".$lbl_coating_txt_stock."</td>";
echo "<td align=\"center\" class=\"formCell\">".$lbl_index_txt_stock."</td>";
echo "<td align=\"center\" class=\"formCell\">".$lbl_sphere_txt_stock."</td>";
echo "<td align=\"center\" class=\"formCell\">".$lbl_cylinder_txt_stock."</td>";
echo "<td align=\"center\" class=\"formCell\">".$lbl_price_txt." (".$_SESSION["sessionUserData"]["currency"].")</td></tr>";

	$RE=$_SESSION["RE"][$i];
	
	$query="select price,price_can,price_eur,prices.product_name,products.mfg,products.product_name,products.primary_key,material,filter,coating,d_index,sph_base,cyl_add from products,prices WHERE products.product_name=prices.product_name and products.primary_key='$RE'"; //TEAM LEADERS SECTION

		$result=mysql_query($query)		or die  ('I cannot select items because: ' . mysql_error());
		echo "<tr>";
		
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
		
			echo "<td  align=\"center\" class=\"formCell\">";
			echo "R.E.</td>";
		
			echo "<td  align=\"center\" class=\"formCell\">";
			echo $listItem[mfg];
			echo "</td>";
		
			echo "<td align=\"center\" class=\"formCell\">";
			echo "<a href=\"#\" onClick=\"MM_openBrWindow('lens_specs_stock.php?pkey=$listItem[primary_key]','popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=350,height=280')\">";
			echo $listItem[product_name];
			echo "</a></td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			echo $material;
			echo "</td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			echo $coating;
			echo "</td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			echo $listItem[d_index];
			echo "</td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			echo $listItem[sph_base];
			echo "</td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			echo $listItem[cyl_add];
			echo "</td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			
			if ($_SESSION["sessionUserData"]["currency"]=="US"){
				echo $listItem[price];}
			else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
				echo $listItem[price_can];}
			else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
				echo $listItem[price_eur];}
			echo "</td></tr>";
			
			$LE=$_SESSION["LE"][$i];
	
			$query="select price,price_can,price_eur,prices.product_name,products.mfg,products.product_name,products.primary_key,material,filter,coating,d_index,sph_base,cyl_add from products,prices WHERE products.product_name=prices.product_name and products.primary_key='$LE'"; //TEAM LEADERS SECTION

		$result=mysql_query($query)		or die  ('I cannot select items because: ' . mysql_error());
		echo "<tr>";
		
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
		
			echo "<td  align=\"center\" class=\"formCell\">";
			echo "L.E.</td>";
		
			echo "<td  align=\"center\" class=\"formCell\">";
			echo $listItem[mfg];
			echo "</td>";
		
			echo "<td align=\"center\" class=\"formCell\">";
			echo "<a href=\"#\" onClick=\"MM_openBrWindow('lens_specs_stock.php?pkey=$listItem[primary_key]','popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=350,height=280')\">";
			echo $listItem[product_name];
			echo "</a></td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			echo $material;
			echo "</td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			echo $coating;
			echo "</td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			echo $listItem[d_index];
			echo "</td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			echo $listItem[sph_base];
			echo "</td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			echo $listItem[cyl_add];
			echo "</td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			if ($_SESSION["sessionUserData"]["currency"]=="US"){
				echo $listItem[price];}
			else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
				echo $listItem[price_can];}
			else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
				echo $listItem[price_eur];}
			echo "</td></tr></table>";
			
?>

			
			
			