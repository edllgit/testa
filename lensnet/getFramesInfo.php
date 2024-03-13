<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
?>
<?php
session_start();
require('../Connections/sec_connect.inc.php');


if ($_GET[COLLECTION_ID]!=""){
	$query="SELECT * FROM frames_collections WHERE frames_collections_id='$_GET[COLLECTION_ID]'";
	$result=mysql_query($query)
		or die ("Could not find collection");
	
	$collectionItem=mysql_fetch_array($result);
	
	echo "<div class=\"collectionNameFrameBox\">$collectionItem[collection_name] Collection</div>";
	
	$query="select * from frames where frame_status='active' AND frames_collections_id='$_GET[COLLECTION_ID]'";
	$result=mysql_query($query)
		or die ("Could not find collection");
}
else if ($_GET[FRAME_ID]!=""){
		$query="select * from frames where frame_status='active' AND frames_id='$_GET[FRAME_ID]'";
	$result=mysql_query($query)		or die ("Could not find collection");
		
}
	echo "<table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";

while ($frameItem=mysql_fetch_array($result)){
	echo "<tr><td>";
	//USE MODEL NUMBER FOR name identifier
	echo "<div id=\"frameTableRow\" name=\"$frameItem[model_num]\" onClick=\"populateExtrasForm('$frameItem[frames_id]','extrasBox','visibility','visible','overflow','visible','height','auto')\" )\"  onMouseOver=\"highlightBox('$frameItem[model_num]','border','2px solid #000098','div')\" onMouseOut=\"setProp('$frameItem[model_num]','border','2px solid #CCC','div')\">";
	
	echo "<table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" >";
	echo "<tr><td>";
	echo "<div class=\"frameModelText\"><b>".$lbl_model_txt_frames."</b>".$frameItem[model_num]."</div>";
	echo "<div class=\"frameText\"><b>".$lbl_rimstyle_txt_frames."</b>".$frameItem[rim_style]."</div>";

	
	echo "<div class=\"frameText\"><b>".$lbl_type_txt_frames."</b>".$frameItem[type]."</div>";
	echo "<div class=\"frameText\"><b>".$lbl_size_txt_frames."</b>".$frameItem[frame_A]."-".$frameItem[frame_DBL]."</div>";
	 
	
	echo "<td><td>";
	echo "<div class=\"frameDescriptionText\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
	//echo "<div class=\"frameDescriptionText\">".$frameItem[description]."</div>";
	
	
	echo "</td><td align=\'right\' width=\'200\">";
	echo "<div align=\"center\"><img src=\"../frames_images/".$frameItem[frame_image]."TN.jpg\" id=\"frameTableImage\" name=\"$frameItem[frame_image]\" onClick=\"populateExtrasForm('$frameItem[frames_id]','extrasBox','visibility','visible','overflow','visible','height','auto')\" )\"  onMouseOver=\"highlightFrameBox('$frameItem[frames_id]','$frameItem[frame_image]','border','2px solid #000098')\" onMouseOut=\"unhighlightFrameBox('$frameItem[frame_image]','border','2px solid #CCC','img')\"/></div>";
	echo "</td></tr></table></div>";
	
	echo "</td></tr>";
}

	echo "</table>";

?>
