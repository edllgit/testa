<?php
$edit_products				= "yes";

if ($_SESSION["access_admin_id"] <> ""){
	$queryAccess = "Select * from access_admin where id=" . $_SESSION["access_admin_id"];
	$resultAccess=mysql_query($queryAccess)		or die ('Error'. mysql_error());
	$AccessData=mysql_fetch_array($resultAccess);	
$edit_products			= $AccessData[edit_products];
}	
?>

 <table width="100%" border="0" cellpadding="2" cellspacing="0">
            	<tr bgcolor="#000000">
            		<td colspan="14" align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">DIRECT
            		      LENS  EXCLUSIVE PRODUCT LIST </font></b></td>
       		  </tr></table><div id="displayBox">
            	 <table width="100%" border="0" cellpadding="2" cellspacing="0"><tr bgcolor="#DDDDDD">
            	<!--	<td nowrap><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=manufacturer'>Manufacturer</a></font></td>-->
           		  <td nowrap><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategorym.php?sort_by=collection'>Collection</a></font></td>
            		<td nowrap><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategorym.php?sort_by=product_name'>Product
              Name</a></font></td>
           		  <td><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategorym.php?sort_by=index_v'>Index</a></font></td>
           		  <td><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategorym.php?sort_by=coating'>Coating</a></font></td>
           		  <td><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategorym.php?sort_by=photo'>Photo</a></font></td>
           		  <td><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategorym.php?sort_by=polar'>Polar</a></font></td>
            		<td><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategorym.php?sort_by=price'>Price USA </a></font></td>
            		<td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategorym.php?sort_by=price_can'>Price CA</a></font></td>
            		<td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategorym.php?sort_by=price_eur'>Price EUR</a></font></td>	
                    
            		<td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategorym.php?sort_by=e_lab_us_price'>E-Lab<br />Price US</a></font></td>
            		<td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategorym.php?sort_by=e_lab_can_price'>E-Lab<br />Price CA</a></font></td>	
                    

            		<td><font size="1" face="Arial, Helvetica, sans-serif">Edit/Delete</font></td>
            	</tr>	
            	<?php
				while($catData=mysql_fetch_array($catResult)){
					$count++;
					if (($count%2)==0)
   						$bgcolor="#DDDDDD";
					else 
						$bgcolor="#FFFFFF";

					//echo "<tr bgcolor=\"$bgcolor\"><td><font size=\"1\" face=\"Arial, Helvetica, sans-serif\">$catData[manufacturer]</td>";
					 echo "<tr bgcolor=\"$bgcolor\"><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[collection]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[product_name]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[index_v]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[coating]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[photo]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[polar]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[price]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[price_can]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[price_eur]</td><td><font sizse=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[e_lab_us_price]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[e_lab_can_price]</td><td align=\"center\"><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">";
					 
					   if ($edit_products == "yes")
					echo " <a href=\"update_exclusive_productm.php?pkey=$catData[primary_key]\">Edit</a>";
					
				echo " </td></tr>";
				}
				?>
				</table></div>
