<?php
function addProdPhoto($image_name,$image_size) //ADD IMAGE FUNCTION
{
	$fileToUpload=($_FILES['image_name']['tmp_name']);
	$upload_file_path="../frames_images/";
	$upload_file_path .= ($_FILES['image_name']['name']);
	if(!copy($fileToUpload, $upload_file_path)){
		echo "<center><font color=\"red\">File was not uploaded because. $fileToUpload</font></center>";
		exit();
	}
$file_name = $upload_file_path;
if(is_file($file_name)){
	$main_name = $image_name . ".jpg";
	$new_file_name = "../frames_images/" . $main_name; /* product main image name */
	
	if($image_size=="small"){
		resize_image_with_marg($file_name, $new_file_name, 200, 100);}
	else if($image_size=="large"){
	resize_image_with_marg($file_name, $new_file_name, 400, 110);}
	else if($image_size=="large_temple"){
	resize_image($file_name, $new_file_name, 400, 110);}
	else{
	resize_image_with_marg($file_name, $new_file_name, 200, 100);}
	
	unlink($file_name);
}
}

function resize_image_with_marg($file_name,$new_file_name,$dest_width,$dest_height)
	{

// Figure percentage to scale and new sizes
list($width, $height) = getimagesize($file_name);
$width_fact=$dest_width/$width;
$height_fact=$dest_height/$height;

//if ($width_fact<$height_fact) 
	//{$percent=$width_fact;}
//else
//	{$percent=$height_fact;}

$percent=$width_fact;

$newwidth = $width * $percent;
$newheight = $height * $percent;

if ($newheight<$dest_height){

$destY=($dest_height-$newheight)/2;
}
else{
$destY=0;
}

// Load

$buffer = imagecreatetruecolor($newwidth, $newheight);
$source = imagecreatefromjpeg($file_name);

// Resize
imagecopyresampled($buffer, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

$thumb_buffer = imagecreatefromjpeg("blank.jpg");
$thumb = imagecreatetruecolor($dest_width, $dest_height);

imagecopyresampled($thumb, $thumb_buffer, 0, 0, 0, 0, $dest_width, $dest_height, $dest_width, $dest_height);

imagecopy($thumb, $buffer, 0,$destY, 0, 0, $newwidth, $newheight);

// Output
//header('Content-type: image/jpeg');

imageinterlace($thumb,1);
imagejpeg($thumb,$new_file_name,100);
imagedestroy($thumb);
}

function resize_image($file_name,$new_file_name,$dest_width,$dest_height) // RESIZE IMAGE FUNCTION
{

// Figure percentage to scale and new sizes
list($width, $height) = getimagesize($file_name);
$width_fact=$dest_width/$width;
$height_fact=$dest_height/$height;

if ($width_fact<$height_fact) 
	{$percent=$width_fact;}
else
	{$percent=$height_fact;}

$newwidth = $width * $percent;
$newheight = $height * $percent;

// Load
$thumb = imagecreatetruecolor($newwidth, $newheight);
$source = imagecreatefromjpeg($file_name);

// Resize
imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

// Output
//header('Content-type: image/jpeg');

imageinterlace($thumb,1);
imagejpeg($thumb,$new_file_name);
imagedestroy($thumb);
}
?>

