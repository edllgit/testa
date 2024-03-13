<?php
function delete_product($pkey)
{
include "../sec_connectEDLL.inc.php";
$query="delete from prices where primary_key='$pkey'";
$result=mysqli_query($con,$query) or die ("Could not delete product");
return true;
}

function edit_product($pkey)
{
include "../sec_connectEDLL.inc.php";
$product_name=$_POST[product_name];
$price=$_POST[price];
$price_can=$_POST[price_can];
$description=$_POST[description];
$abbe=$_POST[abbe];
$density=$_POST[density];
$cost=$_POST[cost];
$stock_collections_id=$_POST[stock_collections_id];

$query="update prices set stock_collections_id='$stock_collections_id',price='$price',price_can='$price_can',description='$description',abbe='$abbe',density='$density',cost='$cost' where primary_key='$pkey'";
	
$result=mysqli_query($con,$query) or die ("Could not edit product");
return true;
}



function create_product()
{
include "../sec_connectEDLL.inc.php";
$product_name=$_POST[product_name];
$price=$_POST[price];
$price_can=$_POST[price_can];
$description=$_POST[description];
$abbe=$_POST[abbe];
$density=$_POST[density];
$cost=$_POST[cost];
$stock_collections_id=$_POST[stock_collections_id];

	$query="insert into prices (product_name,stock_collections_id, price,price_can,description,abbe,density, cost) values ('$product_name','$stock_collections_id','$price','$price_can','$description','$abbe','$density', '$cost')";

	
	$result=mysqli_query($con,$query) or die ("Could not create new product because " . mysqli_error($con));
	
	$next_increment = 0;//GET THE PRIMARY KEY OF THE JUST CREATED PRODUCT
	$query="SHOW TABLE STATUS LIKE 'prices'";
	$Result=mysqli_query($con,$query) or die ( "Query failed: " . mysqli_error($con)  );

	$row = mysqli_fetch_array($Result,MYSQLI_ASSOC);
	$next_increment=$row['Auto_increment'];

	$pkey=$next_increment-1;

return ($pkey);
}



function create_exclusive_product()
{
include "../sec_connectEDLL.inc.php";
$min_height = $_POST[min_height];
$max_height = $_POST[max_height];
$product_name=$_POST[product_name];
$product_code=$_POST[product_code];
$color_code=$_POST[color_code];

$price=$_POST[price];
$price_can=$_POST[price_can];
$e_lab_us_price=$_POST[e_lab_us_price];
$e_lab_can_price=$_POST[e_lab_can_price];
$collection=$_POST[collection];
$description=$_POST[description];
$manufacturer=$_POST[manufacturer];
$abbe=$_POST[abbe];
$density=$_POST[density];
$index_v=$_POST[index_v];
$coating=$_POST[coating];
$photo=$_POST[photo];
$polar=$_POST[polar];
$lens_category=$_POST[lens_category];

$sphere_max=$_POST[sphere_max];
$sphere_min=$_POST[sphere_min];
$sphere_over_max=$_POST[sphere_over_max];
$sphere_over_min=$_POST[sphere_over_min];
$cyl_max=$_POST[cyl_max];
$cyl_min=$_POST[cyl_min];
$cyl_over_min=$_POST[cyl_over_min];
$add_max=$_POST[add_max];
$add_min=$_POST[add_min];

If ($_POST[prod_status]=="active"){
	$prod_status="active";}
else{
	$prod_status="inactive";}
	

	$query="insert into exclusive (product_name,collection,product_code,color_code,price,price_can,e_lab_us_price,e_lab_can_price,description,manufacturer,abbe,density,index_v,coating,photo,polar,sphere_max,sphere_min,sphere_over_max,sphere_over_min,cyl_max,cyl_min,cyl_over_min,add_max,add_min,prod_status, min_height, max_height, lens_category) values ('$product_name','$collection','$product_code','$color_code','$price','$price_can','$e_lab_us_price','$e_lab_can_price','$description','$manufacturer','$abbe','$density','$index_v','$coating','$photo','$polar','$sphere_max','$sphere_min','$sphere_over_max','$sphere_over_min','$cyl_max','$cyl_min','$cyl_over_min','$add_max','$add_min','$prod_status', $min_height, $max_height, '$lens_category')";
	$result=mysqli_query($con,$query) or die ( "Query failed: " . mysqli_error($con)  );
	
	$next_increment = 0;//GET THE PRIMARY KEY OF THE JUST CREATED PRODUCT
	$query="SHOW TABLE STATUS LIKE 'exclusive'";
	$Result=mysqli_query($con,$query) or die ( "Query failed: " . mysqli_error($con) );

	$row = mysqli_fetch_array($Result,MYSQLI_ASSOC);
	$next_increment=$row['Auto_increment'];

	$pkey=$next_increment-1;

return ($pkey);
}



function create_ifcca_exclusive_product()
{
include "../sec_connectEDLL.inc.php";	
$min_height = $_POST[min_height];
$max_height = $_POST[max_height];
$product_name=$_POST[product_name];
$product_code=$_POST[product_code];
$color_code=$_POST[color_code];

$price=$_POST[price];
$price_can=$_POST[price_can];
$e_lab_us_price=$_POST[e_lab_us_price];
$e_lab_can_price=$_POST[e_lab_can_price];
$collection=$_POST[collection];
$description=$_POST[description];
$manufacturer=$_POST[manufacturer];
$abbe=$_POST[abbe];
$density=$_POST[density];
$index_v=$_POST[index_v];
$coating=$_POST[coating];
$photo=$_POST[photo];
$polar=$_POST[polar];
$lens_category=$_POST[lens_category];

$sphere_max=$_POST[sphere_max];
$sphere_min=$_POST[sphere_min];
$sphere_over_max=$_POST[sphere_over_max];
$sphere_over_min=$_POST[sphere_over_min];
$cyl_max=$_POST[cyl_max];
$cyl_min=$_POST[cyl_min];
$cyl_over_min=$_POST[cyl_over_min];
$add_max=$_POST[add_max];
$add_min=$_POST[add_min];

If ($_POST[prod_status]=="active"){
	$prod_status="active";}
else{
	$prod_status="inactive";}
	

	$query="insert into ifc_ca_exclusive (product_name,collection,product_code,color_code,price,price_can,e_lab_us_price,e_lab_can_price,description,manufacturer,abbe,density,index_v,coating,photo,polar,sphere_max,sphere_min,sphere_over_max,sphere_over_min,cyl_max,cyl_min,cyl_over_min,add_max,add_min,prod_status, min_height, max_height, lens_category) values ('$product_name','$collection','$product_code','$color_code','$price','$price_can','$e_lab_us_price','$e_lab_can_price','$description','$manufacturer','$abbe','$density','$index_v','$coating','$photo','$polar','$sphere_max','$sphere_min','$sphere_over_max','$sphere_over_min','$cyl_max','$cyl_min','$cyl_over_min','$add_max','$add_min','$prod_status', $min_height, $max_height, '$lens_category')";
	$result=mysqli_query($con,$query)		or die ( "Query failed: " . mysqli_error($con)  );
	
	$next_increment = 0;//GET THE PRIMARY KEY OF THE JUST CREATED PRODUCT
	$query="SHOW TABLE STATUS LIKE 'exclusive'";
	$Result=mysqli_query($query) or die ( "Query failed: " . mysqli_error($con) );

	$row = mysqli_fetch_array($Result,MYSQLI_ASSOC);
	$next_increment=$row['Auto_increment'];

	$pkey=$next_increment-1;

return ($pkey);
}



function create_exclusive_productIFC()
{
include "../sec_connectEDLL.inc.php";	
$min_height = $_POST[min_height];
$max_height = $_POST[max_height];
$product_name=$_POST[product_name];
$product_code=$_POST[product_code];
$color_code=$_POST[color_code];

$price=$_POST[price];
$price_can=$_POST[price_can];
$e_lab_us_price=$_POST[e_lab_us_price];
$e_lab_can_price=$_POST[e_lab_can_price];
$collection=$_POST[collection];
$description=$_POST[description];
$manufacturer=$_POST[manufacturer];
$abbe=$_POST[abbe];
$density=$_POST[density];
$index_v=$_POST[index_v];
$coating=$_POST[coating];
$photo=$_POST[photo];
$polar=$_POST[polar];
$lens_category=$_POST[lens_category];

$sphere_max=$_POST[sphere_max];
$sphere_min=$_POST[sphere_min];
$sphere_over_max=$_POST[sphere_over_max];
$sphere_over_min=$_POST[sphere_over_min];
$cyl_max=$_POST[cyl_max];
$cyl_min=$_POST[cyl_min];
$cyl_over_min=$_POST[cyl_over_min];
$add_max=$_POST[add_max];
$add_min=$_POST[add_min];

If ($_POST[prod_status]=="active"){
	$prod_status="active";}
else{
	$prod_status="inactive";}
	

	$query="insert into ifc_exclusive (product_name,collection,product_code,color_code,price,price_can,e_lab_us_price,e_lab_can_price,description,manufacturer,abbe,density,index_v,coating,photo,polar,sphere_max,sphere_min,sphere_over_max,sphere_over_min,cyl_max,cyl_min,cyl_over_min,add_max,add_min,prod_status, min_height, max_height, lens_category) values ('$product_name','$collection','$product_code','$color_code','$price','$price_can','$e_lab_us_price','$e_lab_can_price','$description','$manufacturer','$abbe','$density','$index_v','$coating','$photo','$polar','$sphere_max','$sphere_min','$sphere_over_max','$sphere_over_min','$cyl_max','$cyl_min','$cyl_over_min','$add_max','$add_min','$prod_status', $min_height, $max_height, '$lens_category')";
	$result=mysqli_query($con,$query) or die ( "Query failed: " . mysqli_error($con)  );
	
	$next_increment = 0;//GET THE PRIMARY KEY OF THE JUST CREATED PRODUCT
	$query="SHOW TABLE STATUS LIKE 'exclusive'";
	$Result=mysqli_query($con,$query) or die ( "Query failed: " . mysqli_error($con) );
	$row = mysqli_fetch_array($Result,MYSQLI_ASSOC);
	$next_increment=$row['Auto_increment'];

	$pkey=$next_increment-1;

return ($pkey);
}

function delete_exclusive_product($pkey)
{
include "../sec_connectEDLL.inc.php";	
$query="delete from exclusive where primary_key='$pkey'";
$result=mysqli_query($con,$query) or die ("Could not delete product");
return true;
}

function edit_exclusive_product($pkey)
{
include "../sec_connectEDLL.inc.php";	
$cost = $_POST[cost];
$real_manufacturer = $_POST[real_manufacturer];
$optipoints_bonus =$_POST[optipoints_bonus];
$min_height=$_POST[min_height];
$max_height=$_POST[max_height];
$product_name=$_POST[product_name];
$old_product_name=$_POST[old_product_name];
$product_code=$_POST[product_code];
$color_code=$_POST[color_code];
$collection=$_POST[collection];
$price=$_POST[price];
$price_can=$_POST[price_can];
$description=$_POST[description];
$manufacturer=$_POST[manufacturer];
$abbe=$_POST[abbe];
$density=$_POST[density];
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
$tintable = $_POST[tintable];

if ($optipoints_bonus == '')
{
$optipoints_bonus = 0;
}

if ($tintable <> "yes")
{
$tintable = "no";
}

if ($_POST[prod_status]=="active"){
	$prod_status="active";}
else{
	$prod_status="inactive";}

	$query="update exclusive set optipoints_bonus = $optipoints_bonus, min_height = $min_height, max_height = $max_height, product_name='$product_name', collection='$collection', product_code='$product_code', color_code='$color_code', price='$price', price_can='$price_can', cost = '$cost', real_manufacturer='$real_manufacturer',description='$description', manufacturer='$manufacturer', abbe='$abbe', density='$density', index_v='$index_v', coating='$coating', photo='$photo', polar='$polar',sphere_max='$sphere_max',sphere_min='$sphere_min',sphere_over_max='$sphere_over_max',sphere_over_min='$sphere_over_min',cyl_max='$cyl_max',cyl_min='$cyl_min',cyl_over_min='$cyl_over_min',add_max='$add_max',add_min='$add_min',prod_status='$prod_status', lens_category = '$lens_category', tintable = '$tintable' where primary_key='$pkey'";
	$result=mysqli_query($con,$query)		or die ("Could not edit product".  $query . mysqli_error($con) );
return true;
}




function edit_ifcca_exclusive_product($pkey)
{
include "../sec_connectEDLL.inc.php";	
$optipoints_bonus =$_POST[optipoints_bonus];
$min_height=$_POST[min_height];
$max_height=$_POST[max_height];
$product_name=$_POST[product_name];
$old_product_name=$_POST[old_product_name];
$product_code=$_POST[product_code];
$color_code=$_POST[color_code];
$collection=$_POST[collection];
$price=$_POST[price];
$price_can=$_POST[price_can];
$description=$_POST[description];
$manufacturer=$_POST[manufacturer];
$abbe=$_POST[abbe];
$density=$_POST[density];
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
$tintable = $_POST[tintable];
$cost_us = $_POST[cost_us];
$real_manufacturer = $_POST[real_manufacturer];
$corridor = $_POST[corridor];

if ($optipoints_bonus == '')
{
$optipoints_bonus = 0;
}

if ($tintable <> "yes")
{
$tintable = "no";
}



if ($_POST[prod_status]=="active"){
	$prod_status="active";}
else{
	$prod_status="inactive";}

	$query="update ifc_ca_exclusive set optipoints_bonus = $optipoints_bonus, min_height = $min_height, max_height = $max_height, product_name='$product_name', collection='$collection', product_code='$product_code', color_code='$color_code', price='$price', price_can='$price_can', description='$description', manufacturer='$manufacturer', abbe='$abbe', density='$density', index_v='$index_v', coating='$coating', photo='$photo', polar='$polar',sphere_max='$sphere_max', cost_us='$cost_us',sphere_min='$sphere_min',sphere_over_max='$sphere_over_max',sphere_over_min='$sphere_over_min',cyl_max='$cyl_max',cyl_min='$cyl_min',cyl_over_min='$cyl_over_min',add_max='$add_max',add_min='$add_min',prod_status='$prod_status',  lens_category = '$lens_category',  corridor = '$corridor', tintable = '$tintable', real_manufacturer='$real_manufacturer' where primary_key='$pkey'";
	//echo '<br>'.$query;
	$result=mysqli_query($con,$query)		or die ("Could not edit product".  $query . mysqli_error($con) );
		
return true;
}



function edit_safety_exclusive_product($pkey)
{
include "../sec_connectEDLL.inc.php";	
$optipoints_bonus =$_POST[optipoints_bonus];
$min_height=$_POST[min_height];
$max_height=$_POST[max_height];
$product_name=$_POST[product_name];
$old_product_name=$_POST[old_product_name];
$product_code=$_POST[product_code];
$color_code=$_POST[color_code];
$collection=$_POST[collection];
$price=$_POST[price];
$price_discounted=$_POST[price_discounted];
$price_interco=$_POST[price_interco];
$description=$_POST[description];
$manufacturer=$_POST[manufacturer];
$abbe=$_POST[abbe];
$density=$_POST[density];
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
$tintable = $_POST[tintable];


if ($optipoints_bonus == '')
{
$optipoints_bonus = 0;
}

if ($tintable <> "yes")
{
$tintable = "no";
}



if ($_POST[prod_status]=="active"){
	$prod_status="active";}
else{
	$prod_status="inactive";}

	$query="update safety_exclusive set optipoints_bonus = $optipoints_bonus, min_height = $min_height, max_height = $max_height, product_name='$product_name', collection='$collection', product_code='$product_code', color_code='$color_code', price='$price', price_discounted='$price_discounted', price_interco='$price_interco',description='$description', manufacturer='$manufacturer', abbe='$abbe', density='$density', index_v='$index_v', coating='$coating', photo='$photo', polar='$polar',sphere_max='$sphere_max',sphere_min='$sphere_min',sphere_over_max='$sphere_over_max',sphere_over_min='$sphere_over_min',cyl_max='$cyl_max',cyl_min='$cyl_min',cyl_over_min='$cyl_over_min',add_max='$add_max',add_min='$add_min',prod_status='$prod_status',  lens_category = '$lens_category', tintable = '$tintable' where primary_key='$pkey'";
	$result=mysqli_query($con,$query)		or die ("Could not edit product".  $query . mysqli_error($con) );
		
return true;
}

?>
