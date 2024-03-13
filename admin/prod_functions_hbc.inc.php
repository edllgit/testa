<?php

function edit_hbc_exclusive_product($pkey){
include "../connexion_hbc.inc.php";
$min_height=$_POST[min_height];
$max_height=$_POST[max_height];
$product_name=$_POST[product_name];
$old_product_name=$_POST[old_product_name];
$product_code=$_POST[product_code];
$collection=$_POST[collection];
$price=$_POST[price];
$price_can=$_POST[price_can];
$index_v=$_POST[index_v];
$coating=$_POST[coating];
$photo=$_POST[photo];
$polar=$_POST[polar];
$sphere_max=$_POST[sphere_max];
$sphere_min=$_POST[sphere_min];
$sphere_over_max=$_POST[sphere_over_max];
$sphere_over_min=$_POST[sphere_over_min];
$cyl_max=$_POST[cyl_max];
$cyl_min=$_POST[cyl_min];
$cyl_over_min=$_POST[cyl_over_min];
$add_max=$_POST[add_max];
$add_min=$_POST[add_min];
$lens_category = $_POST[lens_category];
$cost_us = $_POST[cost_us];


if ($_POST[prod_status]=="active"){
	$prod_status="active";}
else{
	$prod_status="inactive";}

	$query="UPDATE ifc_ca_exclusive set min_height = $min_height, max_height = $max_height, product_name='$product_name', collection='$collection', product_code='$product_code', price='$price', price_can='$price_can',   index_v='$index_v', coating='$coating', photo='$photo', polar='$polar',sphere_max='$sphere_max', cost_us='$cost_us',sphere_min='$sphere_min',sphere_over_max='$sphere_over_max',sphere_over_min='$sphere_over_min',cyl_max='$cyl_max',cyl_min='$cyl_min',cyl_over_min='$cyl_over_min',add_max='$add_max',add_min='$add_min',prod_status='$prod_status',  lens_category = '$lens_category' WHERE primary_key='$pkey'";
	//echo '<br>'.$query;
	$result=mysqli_query($con,$query)		or die ("Could not edit product".  $query . mysqli_error($con));
		
return true;
}

?>
