 <?php 
 	$query="select * from frames_colors";
	$catResult=mysql_query($query)		or die ( "Query failed: " . mysql_error());
	?>
	<table width="100%" border="0" cellpadding="2" cellspacing="0" class="formText">
            	<tr bgcolor="#000000">
            		<td colspan="5" align="center">Frames</td>
       		  </tr>
            	<tr bgcolor="#DDDDDD">
            		<td  align="center" nowrap>MODEL NUM</td>
            		<td  align="center" nowrap>TEMPLE COLOR</td>
            		<td  align="center" nowrap>COLLECTION CODE</td>
            		<td  align="center" nowrap>IMAGE</td>
            		<td align="right">Edit/Delete Frame</td>
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

				if ($catData[frames_colors_image]!=""){
					$has_image="yes";}
				else{$has_image="-";}

					echo "<tr bgcolor=\"$bgcolor\"><td align=\"center\">$catData[temple_model_num]</td><td align=\"center\">$catData[frame_color]</td><td align=\"center\">$catData[collection_code]</td><td align=\"center\">$has_image</td><td align=\"right\"><a href=\"newFrameColor.php?pkey=$catData[frames_colors_id]&edit=true\">Edit</a>&nbsp;<a href=\"newFrameColor.php?pkey=$catData[frames_colors_id]&i=$catData[frames_colors_image]&delete=true\">Delete</a></td></tr>";
				}?>
				</table>
