<?php
function render_barcode($order_num){

	$backgroundimage="barcode.gif";
	$im=imagecreatefromgif($backgroundimage);
	$colour=imagecolorallocate($im,0,0,0);

	putenv('GDFONTPATH=' . realpath('.'));
	$string=$order_num;

	$font='FREE3OF9.TTF';
	$angle=0;
	// Add the text
	imagettftext($im,48, $angle,0, 50, $colour,$font, $string);
	imagegif($im);
}

header('Content-Type: image/gif');
header("Cache-Control: public");
header("Expires: Sat, 26 Jul 2050 05:00:00 GMT");

render_barcode($_GET['order_num']);

?>