 <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formText">
            	<tr bgcolor="#000000">
            		<td colspan="7" align="center"><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial"><b>DIRECT LENS STOCK PRODUCT LIST</b></font></td>
       		  </tr>
            	<tr bgcolor="#DDDDDD">
                <td  align="left" bgcolor="#DDDDDD"><b>Collection</b></td>
            		<td  align="left" bgcolor="#DDDDDD"><b>Product
       		        Name</b></td>
            		<td  align="left"><b>Price
            		      US </b></td>
            		<td  align="left" bgcolor="#DDDDDD"><b>Price
            		      CA </b></td>
            		<td  align="left" bgcolor="#DDDDDD"><b>Price
       		        Euro</b></td>
            		<td  align="center"><b>Edit/Delete Product</b></td>
            	</tr>
            	<?php
				while($catData=mysqli_fetch_array($catResult,MYSQLI_ASSOC)){
					$count++;
					if (($count%2)==0)
   						$bgcolor="#DDDDDD";
					else 
						$bgcolor="#FFFFFF";

					print "<tr bgcolor=\"$bgcolor\"><td>$catData[stock_collection]</td><td>$catData[product_name]</td><td>$catData[price]</td><td>$catData[price_can]</td><td>$catData[price_eur]</td><td align=\"center\"><a href=\"update_product.php?pkey=$catData[primary_key]\">Select</a></td></tr>";
				}?>
				</table>
