  
 <?php 
	include "../sec_connectEDLL.inc.php";
 	$query="SELECT * FROM ifc_frames_french ORDER BY collection, model";
	$catResult=mysqli_query($con,$query)		or die ( "Query failed: " . mysqli_error($con)  );
	?>
	<table width="100%" border="0" cellpadding="2" cellspacing="0" class="formText">
            	<tr bgcolor="#000000">
            		<td colspan="7" align="center">Frames</td>
       		  </tr>
            	<tr bgcolor="#DDDDDD">
            		<td  align="center" nowrap>COLLECTION</td>
            		<td  align="center" nowrap>MODEL</td>
            		<td  align="center" nowrap>COLOR</td>
                    <td  align="center" nowrap>Frame A</td>
            		<td align="right">Edit Frame</td>
           		</tr>
            	<?php
				while($catData=mysqli_fetch_array($catResult,MYSQLI_ASSOC)){
					$count++;
					if (($count%2)==0)
   						$bgcolor="#DDDDDD";
					else 
						$bgcolor="#FFFFFF";
						
						$date=array();
						$date= explode("-", $catData[date]);
						$end_date=$date[1]."/".$date[2]."/".$date[0];
						
/*?>//Can we still order this frame from the supplier ? 
 if ($catData[available_at_supplier] == "yes")
 	$image_available_at_supplier = "http://c.direct-lens.com/images/yes.jpg" ;
 else
 	$image_available_at_supplier  = "http://c.direct-lens.com/images/no.png" ; 
	
 //Is this frame available for IFC.ca REGULAR customers ? 
 if ($catData[display_on_ifcca] == "yes")
 	$image_display_on_ifc_ca  = "http://c.direct-lens.com/images/yes.jpg" ;
 else
 	$image_display_on_ifc_ca  = "http://c.direct-lens.com/images/no.png" ; 
	
 //Is this frame available for l'entrepot ? 
 if ($catData[display_entrepot] == "yes")
 	$image_display_entrepot  = "http://c.direct-lens.com/images/yes.jpg" ;
 else
 	$image_display_entrepot  = "http://c.direct-lens.com/images/no.png" ; <?php */
 
					/*?>echo "<tr bgcolor=\"$bgcolor\">
							 <td align=\"center\">$catData[collection]</td>
							 <td align=\"center\">$catData[model]</td>
							 <td align=\"center\">$catData[color]</td>
							 <td align=\"center\">$catData[frame_a]</td>
							 <td align=\"center\"><img title=\"Available at supplier\" src=\"$image_available_at_supplier\" width=\"25\"></td>
							 <td align=\"center\"><img title=\"Active for Ifc.ca REGULAR Customers\" src=\"$image_display_on_ifc_ca\" width=\"25\"></td>
							 <td align=\"center\"><img title=\"Active for l'Entrepot de la lunette\" src=\"$image_display_entrepot\" width=\"25\"></td>
							 <td align=\"right\"><a href=\"newFrame.php?pkey=$catData[ifc_frames_id]&edit=true\">Edit</a>&nbsp;</td>
						</tr>";<?php */
						
						echo "<tr bgcolor=\"$bgcolor\">
							 <td align=\"center\">$catData[collection]</td>
							 <td align=\"center\">$catData[model]</td>
							 <td align=\"center\">$catData[color]</td>
							 <td align=\"center\">$catData[frame_a]</td>
							 <td align=\"right\"><a href=\"newFrame.php?pkey=$catData[ifc_frames_id]&edit=true\">Edit</a>&nbsp;</td>
						</tr>";
				}?>
                
                <tr><td colspan="5">&nbsp;</td></tr>
                <tr><td colspan="5">&nbsp;</td></tr>
                

				</table>