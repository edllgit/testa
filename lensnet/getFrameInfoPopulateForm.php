
<?php

require('../Connections/sec_connect.inc.php');

  $query="SELECT frames_collections.avail_colors, frames_collections.color_collection_code,frames.model_num,frames.frames_collections_id as fc_id FROM  frames
 	LEFT JOIN (frames_collections) ON (frames.frames_collections_id = frames_collections.frames_collections_id) 
	WHERE frames.frames_id='$_GET[FRAME_ID]'"; 
	
	$result=mysql_query($query)
		or die ("Could not select items because".mysql_error());
		
	$collectionItem=mysql_fetch_array($result);
	
	$colors=array();
	$colors=explode(";",$collectionItem[avail_colors]);
	
	$collection_codes=array();
	$collection_codes=explode(";",$collectionItem[color_collection_code]);
	
	$colorNum=count($colors);

if($colorNum>5){
echo "<div id=\"templeScrollBox\">";
}
	
echo "<table width=\"580\" cellpadding\"0\" cellspacing=\"0\" class=\"frameText\">";
for ($i=0;$i<$colorNum;$i++){
	
		$templeQuery="SELECT * FROM frames_colors WHERE frame_color='$colors[$i]' AND collection_code='$collection_codes[$i]'";
		$templeResult=mysql_query($templeQuery)		or die ("Could not select items because".mysql_error());
		$templeItem=mysql_fetch_array($templeResult);
		
		echo "<tr><td align=\"left\"><input type=\"radio\" name=\"temple_model_num\" id=\"temple\" value=\"$templeItem[temple_model_num]\"";
		
		if($i==0){
			echo " checked=\"checked\"";}
		
		echo "/></td><td><b>$colors[$i]<br>Model:</b> $templeItem[temple_model_num]</td>";
		
		echo "<td align=\"right\"><img src=\"../frames_images/".$templeItem[frames_colors_image].".jpg\"/></td>";
		echo "<tr>";
	
 }
 echo "</table></div>";
 
 echo "<input type=\"hidden\" name=\"frames_id\" id=\"frames_id\" value=\"$_GET[FRAME_ID]\"/>";
	 
	 ?>