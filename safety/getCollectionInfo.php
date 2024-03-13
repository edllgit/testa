<?php 
include "Connections/directlens.php";
include "includes/getlang.php";
?>
<?php

session_start();
require('Connections/sec_connect.inc.php');

	$query="select * from frames_collections where frame_collection_status='active' AND frames_collections_id='$_GET[COLLECTION_ID]'";
	$result=mysql_query($query)		or die ("Could not find collection");

$row=mysql_fetch_array($result);

$colors=array();
$colors=explode(";",$row[avail_colors]);
$colorNum=count($colors);

$prescript_collections=array();
$prescript_collections=explode(";",$row[avail_prescript_collections]);
$prescript_collectionsNum=count($prescript_collections);
	
	echo "<div class=\"collectionTitleText\">".$row[collection_name]."</div>";
	echo "<div align=\"center\"><img src=\"http://www.direct-lens.com/frames_images/".$row[collection_image].".jpg\"/><br/></div>";
	echo "<div class=\"collectionDescriptionText\">".$row[collection_description]."</div>";
	
	//print "<div class=\"collectionColorsText\"><b>Available Colors:</b> ";
	//	for($i=0;$i<$colorNum-2;$i++){
			//print $colors[$i].", ";
			
		//}
	//echo $colors[$colorNum-1]."</div>";
	
		echo "<div class=\"collectionCollectionsText\"><b>".$lbl_availcollect_frames." </b>";
		for($i=0;$i<$$prescript_collectionsNum-2;$i++){
			echo $prescript_collections[$i].", ";
			
		}
	echo $prescript_collections[$prescript_collectionsNum-1]."</div>";
	
	if ($_SESSION["sessionUserData"]["currency"]=="CA"){
		echo "<div class=\"collectionLabelText\">".$lbl_price_frames." <span class=\"collectionPriceText\">$".$row[price_CA]."</span> CAN</div>";
		echo "<div class=\"collectionLabelText\">".$lbl_indexdis_frames."</div>";
	}
		if ($_SESSION["sessionUserData"]["currency"]=="US"){
		echo "<div class=\"collectionLabelText\">".$lbl_price_frames." <span class=\"collectionPriceText\">$".$row[price_US]."</span> US</div>";	
		echo "<div class=\"collectionLabelText\">".$lbl_indexdis_frames."</div>";
	}
		if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
	echo "<div class=\"collectionLabelText\">".$lbl_price_frames." <span class=\"collectionPriceText\">$".$row[price_EUR]."</span> EURO</div>";
		echo "<div class=\"collectionLabelText\">".$lbl_indexdis_frames."</div>";
	}

?>
