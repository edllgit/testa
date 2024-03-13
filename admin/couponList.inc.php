 <?php 
 	$query="select * from coupon_codes";
	$catResult=mysqli_query($con,$query)		or die ( "Query failed: " . mysqli_error($con));
	?>
	<div id="displayBox"><table width="100%" border="0" cellpadding="2" cellspacing="0">
            	<tr bgcolor="#000000">
            		<td colspan="7" align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">Current Coupon Codes </font></b></td>
       		  </tr>
            	<tr bgcolor="#DDDDDD">
            		<td  align="center" nowrap><p><font size="1" face="Arial, Helvetica, sans-serif"><b>CODE</b></font></p></td>
            		<td  align="center" nowrap><font size="1" face="Arial, Helvetica, sans-serif"><b>TYPE</b></font></td>
            		<td  align="center" nowrap><font size="1" face="Arial, Helvetica, sans-serif"><b>End
            		      Date </b></font></td>
            		<td  align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>Amt.</b></font></td>
            		<td  align="center"><b><font size="1" face="Arial, Helvetica, sans-serif">Select By</font></b></td><td></td>
            		<td  align="center"><font size="1" face="Arial, Helvetica, sans-serif"><b>Edit/Delete Code</b></font></td>
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


					echo "<tr bgcolor=\"$bgcolor\">
						  <td><font size=\"1\" face=\"Arial, Helvetica, sans-serif\">$catData[code]</td>
						  <td><font size=\"1\" face=\"Arial, Helvetica, sans-serif\">$catData[type]</td>
						  <td><font size=\"1\" face=\"Arial, Helvetica, sans-serif\">$end_date</td>
						  <td><font size=\"1\" face=\"Arial, Helvetica, sans-serif\">$catData[amount]</td>
						  <td><font size=\"1\" face=\"Arial, Helvetica, sans-serif\">$catData[select_by]</td>
						  <td><font size=\"1\" face=\"Arial, Helvetica, sans-serif\">$catData[collection]$catData[product_name]$catData[coating]$catData[system]</td>
						  <td align=\"center\"><font size=\"1\" face=\"Arial, Helvetica, sans-serif\"><a href=\"newCoupon.php?pkey=$catData[primary_key]&edit=true\">Edit</a>
						  &nbsp;<a href=\"newCoupon.php?pkey=$catData[primary_key]&delete=true\">Delete</a></td>
						  </tr>";
				}?>
				</table></div>
