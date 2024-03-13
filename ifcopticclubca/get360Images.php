<?php
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";
$sql="SELECT code FROM ifc_frames WHERE ifc_frames_id='$_POST[pid]'";
$result=mysql_query($sql)	or die  ('ERROR: ' . mysql_error());
$userCount=mysql_num_rows($result);

$listItem=mysql_fetch_array($result);

//$productImage="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/ifcopticclub/prod_images/".$listItem['code']."/images/". $listItem['code']."_01.jpg";
$productImage="http://www.direct-lens.com/ifcopticclub/prod_images/".$listItem['code']."/images/". $listItem['code']."_01.jpg";

$image = $productImage;
$handle = @fopen("$image", "r");
if(strpos($handle, "Resource id") !== false)
{

	list($width, $height) = getimagesize($productImage);

	$widthScale=550/$width;
	$height=floor($height*$widthScale);
	
	echo '<div id="slideshow" style="height:'.$height.'px">';
	
	for ($i=1;$i<21;$i++){
		
		if ($i<10){
		
			$productImage="http://www.direct-lens.com/ifcopticclub/prod_images/".$listItem['code']."/images/". $listItem['code']."_0".$i.".jpg";
		}
		else{
			$productImage="http://www.direct-lens.com/ifcopticclub/prod_images/".$listItem['code']."/images/". $listItem['code']."_".$i.".jpg";
		}
	
		echo  '<img width="550" height="'.$height.'" src="'.$productImage.'" />';
	}// END FOR

	echo '</div>';
}
else
{

		if ($mylang == 'lang_french') {
		echo "<DIV id=\"no-preview-box\"> PAS DISPONIBLE POUR CE PRODUIT</DIV>";	
		}else{
		echo "<DIV id=\"no-preview-box\"> NOT AVAILABLE FOR THIS PRODUCT</DIV>";	
		}
	
}
		



?>