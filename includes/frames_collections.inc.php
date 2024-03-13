<?php
$query="SELECT * FROM frames_collections WHERE frame_collection_status='active'";
$result=mysql_query($query)
		or die ("Could not find collections");
$collectionCount=mysql_num_rows($result);
$rows=floor($collectionCount/3);
$extra=$collectionCount%3;

while ($collectionsItems=mysql_fetch_array($result)){
		$count++;
		
		if(($count/3)<=$rows){
			if ((($count+2)%3)==0){
				echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" ><tr>";
				echo "<td  width=\"33%\" align=\"center\">";
				
				echo "<table width=\"100%\" height=\"100%\"><tr><td  align=\"center\" valign=\"middle\">";
				echo "<img  id=\"collectionBox\" name=\"collectionBox$count\" onClick=\"fetchFramesInfo('$collectionsItems[frames_collections_id]','frameBox','visibility','visible','overflow','visible','height','auto')\" onMouseOver=\"fetchCollectionInfo('$collectionsItems[frames_collections_id]','collectionBox$count','border','2px solid #d08')\" onMouseOut=\"unhighlightBox('collectionBox$count','border','2px solid #000098')\" src=\"http://www.direct-lens.com/frames_images/$collectionsItems[collection_image]TN.jpg\"/>";
				echo "</td></tr></table>";
				//echo "</div>";
				
				echo  "<div class=\"collectionName\">$collectionsItems[collection_name]</div></td>";
			}
			if ((($count+2)%3)==1){
				
				echo "<td  width=\"33%\" align=\"center\">";
				
				echo "<table width=\"100%\" height=\"100%\"><tr><td  align=\"center\" valign=\"middle\">";
				echo "<img  id=\"collectionBox\" name=\"collectionBox$count\" onClick=\"fetchFramesInfo('$collectionsItems[frames_collections_id]','frameBox','visibility','visible','overflow','visible','height','auto')\" onMouseOver=\"fetchCollectionInfo('$collectionsItems[frames_collections_id]','collectionBox$count','border','2px solid #d08')\" onMouseOut=\"unhighlightBox('collectionBox$count','border','2px solid #000098')\" src=\"http://www.direct-lens.com/frames_images/$collectionsItems[collection_image]TN.jpg\"/>";
				echo "</td></tr></table>";
				echo "</div>";
				echo  "<div class=\"collectionName\">$collectionsItems[collection_name]</div></td>";
			}
			if ((($count+2)%3)==2){
				echo "<td  width=\"33%\" align=\"center\">";
			
				
				echo "<table width=\"100%\" height=\"100%\"><tr><td  align=\"center\" valign=\"middle\">";
				echo "<img  id=\"collectionBox\" name=\"collectionBox$count\" onClick=\"fetchFramesInfo('$collectionsItems[frames_collections_id]','frameBox','visibility','visible','overflow','visible','height','auto')\" onMouseOver=\"fetchCollectionInfo('$collectionsItems[frames_collections_id]','collectionBox$count','border','2px solid #d08')\" onMouseOut=\"unhighlightBox('collectionBox$count','border','2px solid #000098')\" src=\"http://www.direct-lens.com/frames_images/$collectionsItems[collection_image]TN.jpg\"/>";
				echo "</td></tr></table>";
				echo "</div>";
				echo  "<div class=\"collectionName\">$collectionsItems[collection_name]</div></td>";
				
				echo "</tr></table>";
			}
		}
		else if ($extra==2){
				if (($count%2)==0){
				echo "<table cellpadding=\"5\" cellspacing=\"0\" ><tr>";
				
				echo "<td align=\"center\">";
	
				echo "<table width=\"100%\" height=\"100%\"><tr><td  align=\"center\" valign=\"middle\">";
				echo "<img  id=\"collectionBox\" name=\"collectionBox$count\" onClick=\"fetchFramesInfo('$collectionsItems[frames_collections_id]','frameBox','visibility','visible','overflow','visible','height','auto')\" onMouseOver=\"fetchCollectionInfo('$collectionsItems[frames_collections_id]','collectionBox$count','border','2px solid #d08')\" onMouseOut=\"unhighlightBox('collectionBox$count','border','2px solid #000098')\" src=\"http://www.direct-lens.com/frames_images/$collectionsItems[collection_image]TN.jpg\"/>";
				echo "</td></tr></table>";
				echo "</div>";
				echo  "<div class=\"collectionName\">$collectionsItems[collection_name]</div></td>";
			}
			if (($count%2)==1){
				echo "<td align=\"center\">";
			
				echo "<table width=\"100%\" height=\"100%\"><tr><td  align=\"center\" valign=\"middle\">";
				echo "<img  id=\"collectionBox\" name=\"collectionBox$count\" onClick=\"fetchFramesInfo('$collectionsItems[frames_collections_id]','frameBox','visibility','visible','overflow','visible','height','auto')\" onMouseOver=\"fetchCollectionInfo('$collectionsItems[frames_collections_id]','collectionBox$count','border','2px solid #d08')\" onMouseOut=\"unhighlightBox('collectionBox$count','border','2px solid #000098')\" src=\"http://www.direct-lens.com/frames_images/$collectionsItems[collection_image]TN.jpg\"/>";
				echo "</td></tr></table>";
				echo "</div>";
				echo  "<div class=\"collectionName\">$collectionsItems[collection_name]</div></td>";
				
				echo "</tr></table>";
			}
		}
		else{
				echo "<table cellpadding=\"5\" cellspacing=\"0\"><tr>";
			
				echo "<td align=\"center\">";
	
				echo "<table width=\"100%\" height=\"100%\"><tr><td  align=\"center\" valign=\"middle\">";				
				echo "<img   id=\"collectionBox\" name=\"collectionBox$count\"  onClick=\"fetchFramesInfo('$collectionsItems[frames_collections_id]','frameBox','visibility','visible','overflow','visible','height','auto')\" onMouseOver=\"fetchCollectionInfo('$collectionsItems[frames_collections_id]','collectionBox$count','border','2px solid #d08')\" onMouseOut=\"unhighlightBox('collectionBox$count','border','2px solid #000098')\" src=\"http://www.direct-lens.com/frames_images/$collectionsItems[collection_image]TN.jpg\"/>";
				echo "</td></tr></table>";
				echo "</div>";
			echo  "<div class=\"collectionName\">$collectionsItems[collection_name]</div></td>";
			
			echo  "</tr></table>";
		}
										   
}//END WHILE

?>
