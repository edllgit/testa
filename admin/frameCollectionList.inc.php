 <?php 
 	$query="select * from frames_collections";
	$catResult=mysql_query($query)		or die ( "Query failed: " . mysql_error()  );
	?>
	<table width="100%" border="0" cellpadding="2" cellspacing="0" class="formText">
            	<tr bgcolor="#000000">
            		<td colspan="3" align="center">Frames Collections</td>
       		  </tr>
            	<tr bgcolor="#DDDDDD">
            		<td  align="center" nowrap>COLLECTION NAME</td>
            		<td  align="center">Status</td><td align="right">Edit/Delete Collection</td>
           		</tr>
            	<?php
				while($catData=mysql_fetch_array($catResult)){
					$count++;
					if (($count%2)==0)
   						$bgcolor="#DDDDDD";
					else 
						$bgcolor="#FFFFFF";
						
						$date=array();
						$date= explode("-", $catData[date]);
						$end_date=$date[1]."/".$date[2]."/".$date[0];


					echo "<tr bgcolor=\"$bgcolor\"><td align=\"center\">$catData[collection_name]</td><td align=\"center\">$catData[frame_collection_status]</td><td align=\"right\"><a href=\"newFrameCollection.php?pkey=$catData[frames_collections_id]&edit=true\">Edit</a>&nbsp;<a href=\"newFrameCollection.php?pkey=$catData[frames_collections_id]&i=$catData[collection_image]&delete=true\">Delete</a></td></tr>";
				}?>
				</table>
