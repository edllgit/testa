<?php
$edit_products				= "yes";

if ($_SESSION["access_admin_id"] <> ""){
	$queryAccess = "Select * from access_admin where id=" . $_SESSION["access_admin_id"];
	$resultAccess=mysql_query($queryAccess)		or die ('Error'. mysql_error());
	$AccessData=mysql_fetch_array($resultAccess);	
	mysql_free_result($resultAccess);
$edit_products			= $AccessData[edit_products];
}	
?>


<?php
$query2="select * from ifc_ca_exclusive where prod_status='active'  order by  product_name";
//echo $query2;
$catResult2=mysql_query($query2)	or die ( "Query failed: " . mysql_error());
?>
   <table width="100%" border="0" cellpadding="2" cellspacing="0">
            	<tr bgcolor="#000000">
            		<td colspan="14" align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">DIRECT
            		      LENS  EXCLUSIVE PRODUCT LIST </font></b></td>
       		  </tr></table><div id="displayBox">
            	 <table width="100%" border="0" cellpadding="2" cellspacing="0"><tr bgcolor="#DDDDDD">
            	<!--	<td nowrap><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=manufacturer'>Manufacturer</a></font></td>-->
           		  <td nowrap><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=collection'>Collection</a></font></td>
            		<td nowrap><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=product_name'>Product
              Name</a></font></td>
           		  <td><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=index_v'>Index</a></font></td>
           		  <td><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=coating'>Coating</a></font></td>
           		  <td><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=photo'>Photo</a></font></td>
           		  <td><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=polar'>Polar</a></font></td>
            		<td><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=price'>Price USA </a></font></td>
            		<td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=price_can'>Price CA</a></font></td>
            		<td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=price_eur'>Price EUR</a></font></td>	
                    
            		<td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=e_lab_us_price'>E-Lab<br />Price US</a></font></td>
            		<td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=e_lab_can_price'>E-Lab<br />Price CA</a></font></td>	
                    
                    <td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=prod_status'>Status</a></font></td>
            		<td><font size="1" face="Arial, Helvetica, sans-serif">Edit/Delete</font></td>
            	</tr>	
            	<?php
				while($catData2=mysql_fetch_array($catResult)){
					$count++;
					if (($count%2)==0)
   						$bgcolor="#DDDDDD";
					else 
						$bgcolor="#FFFFFF";

					//echo "<tr bgcolor=\"$bgcolor\"><td><font size=\"1\" face=\"Arial, Helvetica, sans-serif\">$catData2[manufacturer]</td>";
					 echo "<tr bgcolor=\"$bgcolor\"><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[collection]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[product_name]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[index_v]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[coating]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[photo]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[polar]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[price]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[price_can]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[price_eur]</td><td><font sizse=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[e_lab_us_price]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[e_lab_can_price]</td><td align=\"center\"><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[prod_status]</td><td align=\"center\"><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">";
					 
					   if ($edit_products == "yes")
					echo " <a href=\"update_exclusive_product.php?pkey=$catData2[primary_key]\">Edit</a>";
					
				echo " </td></tr>";
				}
				mysql_free_result($catResult);
				?>
				</table>
                
                
                
         <br /><br /><br />  <br /><br /><br /><br /><br /><br />     
                        
           
 <table width="100%" border="0" cellpadding="2" cellspacing="0">
            	<tr bgcolor="#000000">
            		<td colspan="14" align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">IFC.CA  EXCLUSIVE PRODUCT LIST </font></b></td>
       		  </tr></table><div id="displayBox">
            	 <table width="100%" border="0" cellpadding="2" cellspacing="0"><tr bgcolor="#DDDDDD">
            	<!--	<td nowrap><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=manufacturer'>Manufacturer</a></font></td>-->
           		  <td nowrap><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=collection'>Collection</a></font></td>
            		<td nowrap><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=product_name'>Product
              Name</a></font></td>
           		  <td><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=index_v'>Index</a></font></td>
           		  <td><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=coating'>Coating</a></font></td>
           		  <td><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=photo'>Photo</a></font></td>
           		  <td><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=polar'>Polar</a></font></td>
            		<td><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=price'>Price USA </a></font></td>
            		<td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=price_can'>Price CA</a></font></td>
            		<td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=price_eur'>Price EUR</a></font></td>	
                    
            		<td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=e_lab_us_price'>E-Lab<br />Price US</a></font></td>
            		<td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=e_lab_can_price'>E-Lab<br />Price CA</a></font></td>	
                    
                    <td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif"><a href='getCategory.php?sort_by=prod_status'>Status</a></font></td>
            		<td><font size="1" face="Arial, Helvetica, sans-serif">Edit/Delete</font></td>
            	</tr>	
            	<?php
				while($catData2=mysql_fetch_array($catResult2)){
					$count++;
					if (($count%2)==0)
   						$bgcolor="#DDDDDD";
					else 
						$bgcolor="#FFFFFF";

					//echo "<tr bgcolor=\"$bgcolor\"><td><font size=\"1\" face=\"Arial, Helvetica, sans-serif\">$catData2[manufacturer]</td>";
					 echo "<tr bgcolor=\"$bgcolor\"><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[collection]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[product_name]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[index_v]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[coating]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[photo]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[polar]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[price]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[price_can]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[price_eur]</td><td><font sizse=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[e_lab_us_price]</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[e_lab_can_price]</td><td align=\"center\"><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[prod_status]</td><td align=\"center\"><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">";
					 
					   if ($edit_products == "yes")
					echo " <a href=\"update_exclusive_product_ifc.php?pkey=$catData2[primary_key]\">Edit</a>";
					
				echo " </td></tr>";
				}
				mysql_free_result($catResult);
				?>
				</table>     
                
                
                
                
                </div>
                
                
                
        
             
