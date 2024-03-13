<?php

function delete_frame_collection($pkey)
{
	
$i_file_name = "../frames_images/" . $_GET[i].".jpg";
		if (is_file($i_file_name))
			unlink ($i_file_name);
$i_file_name = "../frames_images/" . $_GET[i]."TN.jpg";
		if (is_file($i_file_name))
			unlink ($i_file_name);
				
$query="delete from frames_collections where frames_collections_id='$pkey'";
$result=mysql_query($query)
		or die ("Could not delete collection");
	
return true;
}

function create_frame_collection()
{
	
if ($_FILES['image_name']['tmp_name'] != ""){ /* if admin has selected a file to upload */
	$collection_image=date("U");
	addProdPhoto($collection_image,"large");
	addProdPhoto($collection_image."TN","small");
}

$collection_name=$_POST[collection_name];
$collection_description=$_POST[collection_description];
$price_US=$_POST[price_US];
$price_CA=$_POST[price_CA];
$price_EUR=$_POST[price_EUR];

$US150=$_POST[US150];
$CA150=$_POST[CA150];
$EUR150=$_POST[EUR150];

$US153=$_POST[US153];
$CA153=$_POST[CA153];
$EUR153=$_POST[EUR153];

$US156=$_POST[US156];
$CA156=$_POST[CA156];
$EUR156=$_POST[EUR156];

$US160=$_POST[US160];
$CA160=$_POST[CA160];
$EUR160=$_POST[EUR160];

$US167=$_POST[US167];
$CA167=$_POST[CA167];
$EUR167=$_POST[EUR167];

$US170=$_POST[US170];
$CA170=$_POST[CA170];
$EUR170=$_POST[EUR170];

$US174=$_POST[US174];
$CA174=$_POST[CA174];
$EUR174=$_POST[EUR174];

$itemFlag=FALSE;
for ($i=1;$i<=$_POST[collection_count];$i++){
	If ($_POST[collection][$i]!=""){
		if ($itemFlag){
			$collectionString.=";".$_POST[collection][$i];
		}
		else{
			$collectionString.=$_POST[collection][$i];//FIRST ITEM
			$itemFlag=TRUE;
		}
	}
}
$itemFlag=FALSE;
for ($i=1;$i<=$_POST[color_count];$i++){
	If ($_POST[color][$i]!=""){
		if ($itemFlag){
			$colorString.=";".$_POST[color][$i];
			$colorCodeString.=";".$_POST[collection_code][$i];
		}
		else{
			$colorCodeString.=$_POST[collection_code][$i];
			$colorString.=$_POST[color][$i];//FIRST ITEM
			$itemFlag=TRUE;
		}
	}
}

If ($_POST[frame_collection_status]=="active"){
	$frame_collection_status="active";}
else{
	$frame_collection_status="inactive";}

	$query="INSERT into frames_collections (collection_name,collection_description,price_US,price_CA,price_EUR,

US150,
CA150,
EUR150,

US153,
CA153,
EUR153,

US156,
CA156,
EUR156,

US160,
CA160,
EUR160,

US167,
CA167,
EUR167,

US170,
CA170,
EUR170,

US174,
CA174,
EUR174,

avail_colors,color_collection_code,avail_prescript_collections,frame_collection_status,collection_image) VALUES ('$collection_name','$collection_description','$price_US','$price_CA','$price_EUR',

'$US150',
'$CA150',
'$EUR150',

'$US153',
'$CA153',
'$EUR153',

'$US156',
'$CA156',
'$EUR156',

'$US160',
'$CA160',
'$EUR160',

'$US167',
'$CA167',
'$EUR167',

'$US170',
'$CA170',
'$EUR170',

'$US174',
'$CA174',
'$EUR174',

'$colorString','$colorCodeString','$collectionString','$frame_collection_status','$collection_image')";

	$result=mysql_query($query)
		or die ("Could not create new collection because " . mysql_error()  );

}

function update_frame_collection($pkey)
{
	

	
	if ($_FILES['image_name']['tmp_name'] != ""){ // if admin has selected a file to upload

	$i_file_name = "../frames_images/" . $_POST[current_image_name].".jpg";
			if (is_file($i_file_name))
				unlink ($i_file_name);
	$i_file_name = "../frames_images/" . $_POST[current_image_name]."TN.jpg";
			if (is_file($i_file_name))
				unlink ($i_file_name);

	$collection_image=date("U");
		
	addProdPhoto($collection_image,"large");
	addProdPhoto($collection_image."TN","small");
	}
		
else{
	$collection_image=$_POST[current_image_name];}
	
if ($_POST[remove_image]=="Yes"){
	$collection_image="";
	$i_file_name = "../frames_images/" . $_POST[current_image_name].".jpg";
			if (is_file($i_file_name))
				unlink ($i_file_name);
	$i_file_name = "../frames_images/" . $_POST[current_image_name]."TN.jpg";
			if (is_file($i_file_name))
				unlink ($i_file_name);
	}
	
$collection_name=$_POST[collection_name];
$collection_description=$_POST[collection_description];
$price_US=$_POST[price_US];
$price_CA=$_POST[price_CA];
$price_EUR=$_POST[price_EUR];

$US150=$_POST[US150];
$CA150=$_POST[CA150];
$EUR150=$_POST[EUR150];

$US153=$_POST[US153];
$CA153=$_POST[CA153];
$EUR153=$_POST[EUR153];

$US156=$_POST[US156];
$CA156=$_POST[CA156];
$EUR156=$_POST[EUR156];

$US160=$_POST[US160];
$CA160=$_POST[CA160];
$EUR160=$_POST[EUR160];

$US167=$_POST[US167];
$CA167=$_POST[CA167];
$EUR167=$_POST[EUR167];

$US170=$_POST[US170];
$CA170=$_POST[CA170];
$EUR170=$_POST[EUR170];

$US174=$_POST[US174];
$CA174=$_POST[CA174];
$EUR174=$_POST[EUR174];

$itemFlag=FALSE;
for ($i=1;$i<=$_POST[collection_count];$i++){
	If ($_POST[collection][$i]!=""){
		if ($itemFlag){
			$collectionString.=";".$_POST[collection][$i];
		}
		else{
			$collectionString.=$_POST[collection][$i];//FIRST ITEM
			$itemFlag=TRUE;
		}
	}
}
$itemFlag=FALSE;
for ($i=1;$i<=$_POST[color_count];$i++){
	If ($_POST[color][$i]!=""){
		if ($itemFlag){
			$colorString.=";".$_POST[color][$i];
			$colorCodeString.=";".$_POST[collection_code][$i];
		}
		else{
			$colorCodeString.=$_POST[collection_code][$i];
			$colorString.=$_POST[color][$i];//FIRST ITEM
			$itemFlag=TRUE;
		}
	}
}


If ($_POST[frame_collection_status]=="active"){
	$frame_collection_status="active";}
else{
	$frame_collection_status="inactive";}

	$query="UPDATE frames_collections SET
	collection_name='$collection_name',
	collection_description='$collection_description',
	price_US='$price_US',
	price_CA='$price_CA',
	price_EUR='$price_EUR',
	
	US150='$US150',
	CA150='$CA150',
	EUR150='$EUR150',
	
	US153='$US153',
	CA153='$CA153',
	EUR153='$EUR153',
	
	US156='$US156',
	CA156='$CA156',
	EUR156='$EUR156',
	
	US160='$US160',
	CA160='$CA160',
	EUR160='$EUR160',
	
	US167='$US167',
	CA167='$CA167',
	EUR167='$EUR167',
	
	US170='$US170',
	CA170='$CA170',
	EUR170='$EUR170',
	
	US174='$US174',
	CA174='$CA174',
	EUR174='$EUR174',
	
	avail_colors='$colorString',
	color_collection_code='$colorCodeString',
	avail_prescript_collections='$collectionString',
	frame_collection_status='$frame_collection_status',
	collection_image='$collection_image'
	WHERE frames_collections_id='$pkey'";

	$result=mysql_query($query)		or die ("Could not update collection because " . mysql_error() );
}


function delete_frame($pkey)
{
$i_file_name = "../frames_images/" . $_GET[i].".jpg";
		if (is_file($i_file_name))
			unlink ($i_file_name);
$i_file_name = "../frames_images/" . $_GET[i]."TN.jpg";
		if (is_file($i_file_name))
			unlink ($i_file_name);
			
$query="delete from frames where frames_id='$pkey'";
$result=mysql_query($query)
		or die ("Could not delete frame");
	
return true;
}

function create_frame()// n'est plus utilisé, laissé comme référence
{
	
if ($_FILES['image_name']['tmp_name'] != ""){ /* if admin has selected a file to upload */
	$frame_image=date("U");
	addProdPhoto($frame_image,"large");
	
	addProdPhoto($frame_image."TN","small");
	}

$frames_collections_id=$_POST[frames_collections_id];
$model_num=$_POST[model_num];
$rim_style=$_POST[rim_style];
$type=$_POST[type];
$code=$_POST[code];
$description=$_POST[description];
$frame_A=$_POST[frame_A];
$frame_B=$_POST[frame_B];
$frame_ED=$_POST[frame_ED];
$frame_DBL=$_POST[frame_DBL];

If ($_POST[frame_status]=="active"){
	$frame_status="active";}
else{
	$frame_status="inactive";}

	$query="INSERT into frames (frames_collections_id,model_num,rim_style,type,code,description,frame_A,frame_B,frame_ED,frame_DBL,frame_status,frame_image) VALUES ('$frames_collections_id','$model_num','$rim_style','$type','$code','$description','$frame_A','$frame_B','$frame_ED','$frame_DBL','$frame_status','$frame_image')";
	
	$result=mysql_query($query)
		or die ("Could not create new frame because " . mysql_error()  );

}

function update_frame($pkey)
{
	
$misc_unknown_purpose        = mysql_real_escape_string($_POST["frames_collections_id"]);//"ARROW"
$collection  				 = mysql_real_escape_string($_POST["frames_collections_id"]);//"ARROW"
$model 				 		 = mysql_real_escape_string($_POST["model_num"]);
$active 			 		 = mysql_real_escape_string($_POST["frame_collection_status"]);//"active" 
$upc 				  		 = $model;
$code 				  		 = $model;
$color               		 = mysql_real_escape_string($_POST["color"]);
$color_en            		 = mysql_real_escape_string($_POST["color_en"]);
$color_code          		 = mysql_real_escape_string($_POST["color_code"]);
$type          		 		 = mysql_real_escape_string($_POST["type"]);//"Plastic" 
$type_en           	 		 = mysql_real_escape_string($_POST["type_en"]);//"Optical" 
$frame_a              		 = mysql_real_escape_string($_POST["frame_A"]);
$frame_b             		 = mysql_real_escape_string($_POST["frame_B"]);
$frame_ed             		 = mysql_real_escape_string($_POST["frame_ED"]);
$frame_dbl            		 = mysql_real_escape_string($_POST["frame_DBL"]); 
$frame_shape            	 = mysql_real_escape_string($_POST["frame_shape"]); 
$frame_shape_en            	 = mysql_real_escape_string($_POST["frame_shape_en"]); 
$branches_material           = mysql_real_escape_string($_POST["branches_material"]); 
$branches_material_en        = mysql_real_escape_string($_POST["branches_material_en"]); 
$gender               		 = mysql_real_escape_string($_POST["gender"]); 
$gender_en           		 = mysql_real_escape_string($_POST["gender_en"]); 
$material            		 = mysql_real_escape_string($_POST["material"]); 
$material_en           		 = mysql_real_escape_string($_POST["material_en"]); 
$boxing              		 = mysql_real_escape_string($_POST["boxing"]); 
$mounting             		 = mysql_real_escape_string($_POST["mounting"]); 
$mounting_en             	 = mysql_real_escape_string($_POST["mounting_en"]); 
$stock_price          		 = mysql_real_escape_string($_POST["stock_price"]); 
$stock_price_entrepot 		 = mysql_real_escape_string($_POST["stock_price_entrepot"]); 
$stock_price_with_discount   = mysql_real_escape_string($_POST["stock_price_with_discount"]); 
$display_milano_package_rep  = mysql_real_escape_string($_POST["display_milano_package_rep"]);   
$display_milano_package_other= mysql_real_escape_string($_POST["display_milano_package_other"]);  

//Checkboxes
if ($_POST["frame_on_sale"]=='active')
$frame_on_sale = 'yes';
else
$frame_on_sale = 'no';

if ($_POST["frame_status"]=='active')
$active = '1';
else
$active = '0';

if ($_POST["available_at_supplier"]=='active')
$available_at_supplier = 'yes';
else
$available_at_supplier = 'no';

if ($_POST["display_on_ifcca"]=='active')
$display_on_ifcca = 'yes';
else
$display_on_ifcca = 'no';

if ($_POST["display_milano6769Canada"]=='active')
$display_milano6769Canada = 'yes';
else
$display_milano6769Canada = 'no';

if ($_POST["display_entrepot"]=='active')
$display_entrepot = 'yes';
else
$display_entrepot = 'no';

if ($_POST["shape_dispo_entrepot"]=='active')
$shape_dispo_entrepot = 'yes';
else
$shape_dispo_entrepot = 'no';


if ($_POST[frame_status]=="active"){
	$frame_status="active";}
else{
	$frame_status="inactive";}
	
if ($_POST["display_milano_package_rep"]=='active')
$display_milano_package_rep = 'yes';
else
$display_milano_package_rep = 'no';

if ($_POST["display_milano_package_other"]=='active')
$display_milano_package_other = 'yes';
else
$display_milano_package_other = 'no';	

$query="UPDATE  ifc_frames_french SET 	misc_unknown_purpose      = '$misc_unknown_purpose',	 
	                                        collection           	  = '$collection', 	 
											model                	  = '$model', 	
	                                        upc			 		 	  = '$upc',	 
											code 				 	  = '$code',
											color_code 			 	  = '$color_code', 
											display_milano_package_other = '$display_milano_package_other',
											display_milano_package_rep   = '$display_milano_package_rep',
											color 			     	  = '$color',
											type		        	  = '$type',
											gender			    	  = '$gender',
											material             	  = '$material',
											mounting 				  = '$mounting',
											boxing 					  = '$boxing',
											active 					  = '$active',
											color_en 				  = '$color_en',
											type_en 				  = '$type_en',
											gender_en 				  = '$gender_en',
											material_en 			  = '$material_en',
											stock_price 		 	  = '$stock_price',
											stock_price_entrepot 	  = '$stock_price_entrepot',
											stock_price_with_discount = '$stock_price_with_discount',
											available_at_supplier 	  = '$available_at_supplier',
											frame_a 			 	  = '$frame_a',
											frame_b 				  = '$frame_b',
											frame_ed				  = '$frame_ed',
											frame_dbl                 = '$frame_dbl',
											frame_on_sale             = '$frame_on_sale',
											display_on_ifcca          = '$display_on_ifcca',
											display_entrepot          = '$display_entrepot',
											display_milano6769Canada  = '$display_milano6769Canada',
											shape_dispo_entrepot      = '$shape_dispo_entrepot',
											frame_shape               = '$frame_shape', 
											frame_shape_en            = '$frame_shape_en',
 											branches_material         = '$branches_material',
											branches_material_en      = '$branches_material_en',
											mounting_en               = '$mounting_en'
											WHERE 		ifc_frames_id =  $pkey";

	
	$result=mysql_query($query)		or die ("Could not create new frame because " . mysql_error()  );
}
function delete_color($pkey)
{
$i_file_name = "../frames_images/" . $_GET[i].".jpg";
		if (is_file($i_file_name))
			unlink ($i_file_name);
$i_file_name = "../frames_images/" . $_GET[i]."TN.jpg";
		if (is_file($i_file_name))
			unlink ($i_file_name);
			
$query="delete from frames_colors where frames_colors_id='$pkey'";
$result=mysql_query($query)
		or die ("Could not delete frame color");
	
return true;
}

function create_color()
{
	
if ($_FILES['image_name']['tmp_name'] != ""){ /* if admin has selected a file to upload */
	$frames_colors_image=date("U");
	addProdPhoto($frames_colors_image,"large_temple");
	
	addProdPhoto($frames_colors_image."TN","small");
	}

$frame_color=$_POST[frame_color];
$collection_code=$_POST[collection_code];
$temple_model_num=$_POST[temple_model_num];


	$query="INSERT into frames_colors (frame_color, collection_code, temple_model_num, frames_colors_image) VALUES ('$frame_color','$collection_code','$temple_model_num','$frames_colors_image')";
	
	$result=mysql_query($query)
		or die ("Could not create new frame color because " . mysql_error()  );

}

function update_color($pkey)
{
	
	if ($_FILES['image_name']['tmp_name'] != ""){ /* if admin has selected a file to upload */

	$i_file_name = "../frames_images/" . $_POST[current_image_name].".jpg";
			if (is_file($i_file_name))
				unlink ($i_file_name);
	$i_file_name = "../frames_images/" . $_POST[current_image_name]."TN.jpg";
			if (is_file($i_file_name))
				unlink ($i_file_name);

	$frames_colors_image=date("U");
		
	addProdPhoto($frames_colors_image,"large_temple");
	addProdPhoto($frames_colors_image."TN","small");
	}
		
else{
	$frames_colors_image=$_POST[current_image_name];}
	
if ($_POST[remove_image]=="Yes"){
	$frames_colors_image="";
	$i_file_name = "../frames_images/" . $_POST[current_image_name].".jpg";
			if (is_file($i_file_name))
				unlink ($i_file_name);
	$i_file_name = "../frames_images/" . $_POST[current_image_name]."TN.jpg";
			if (is_file($i_file_name))
				unlink ($i_file_name);
	}
	
$frame_color=$_POST[frame_color];
$collection_code=$_POST[collection_code];
$temple_model_num=$_POST[temple_model_num];

	$query="UPDATE frames_colors SET
	frame_color='$frame_color',

	collection_code='$collection_code',
	temple_model_num='$temple_model_num',
	frames_colors_image='$frames_colors_image'
	WHERE frames_colors_id='$pkey'";
	
	$result=mysql_query($query)
		or die ("Could not update frame color because " . mysql_error() );
	
}








function add_frame()// remplace la fonction create_Frame()
{

$misc_unknown_purpose        = mysql_real_escape_string($_POST["frames_collections_id"]);//"ARROW"
$collection  				 = mysql_real_escape_string($_POST["frames_collections_id"]);//"ARROW"
$model 				 		 = mysql_real_escape_string($_POST["model_num"]);
$active 			 		 = mysql_real_escape_string($_POST["frame_collection_status"]);//"active" 
$upc 				  		 = $model;
$code 				  		 = $model;
$color               		 = mysql_real_escape_string($_POST["color"]);
$color_en            		 = mysql_real_escape_string($_POST["color_en"]);
$color_code          		 = mysql_real_escape_string($_POST["color_code"]);
$type          		 		 = mysql_real_escape_string($_POST["type"]);//"Plastic" 
$type_en           	 		 = mysql_real_escape_string($_POST["type_en"]);//"Optical" 
$frame_a              		 = mysql_real_escape_string($_POST["frame_A"]);
$frame_b             		 = mysql_real_escape_string($_POST["frame_B"]);
$frame_ed             		 = mysql_real_escape_string($_POST["frame_ED"]);
$frame_dbl            		 = mysql_real_escape_string($_POST["frame_DBL"]); 
$frame_shape            	 = mysql_real_escape_string($_POST["frame_shape"]); 
$frame_shape_en            	 = mysql_real_escape_string($_POST["frame_shape_en"]); 
$branches_material           = mysql_real_escape_string($_POST["branches_material"]); 
$branches_material_en        = mysql_real_escape_string($_POST["branches_material_en"]); 
$gender               		 = mysql_real_escape_string($_POST["gender"]); 
$gender_en           		 = mysql_real_escape_string($_POST["gender_en"]); 
$material            		 = mysql_real_escape_string($_POST["material"]); 
$material_en           		 = mysql_real_escape_string($_POST["material_en"]); 
$boxing              		 = mysql_real_escape_string($_POST["boxing"]); 
$mounting             		 = mysql_real_escape_string($_POST["mounting"]); 
$mounting_en             	 = mysql_real_escape_string($_POST["mounting_en"]); 
$stock_price          		 = mysql_real_escape_string($_POST["stock_price"]); 
$stock_price_entrepot 		 = mysql_real_escape_string($_POST["stock_price_entrepot"]); 
$stock_price_with_discount   = mysql_real_escape_string($_POST["stock_price_with_discount"]); 
$display_milano_package_rep  = mysql_real_escape_string($_POST["display_milano_package_rep"]);   
$display_milano_package_other  = mysql_real_escape_string($_POST["display_milano_package_other"]);  
$Datedujour 				 = date("Y-m-d");
$ip1	 					 = $_SERVER['REMOTE_ADDR'];  
$ip2 				 		 = $_SERVER['HTTP_X_FORWARDED_FOR'];	
//Checkboxes
if ($_POST["frame_on_sale"]=='yes')
$frame_on_sale = 'yes';
else
$frame_on_sale = 'no';

if ($_POST["frame_status"]=='active')
$active = '1';
else
$active = '0';

if ($_POST["available_at_supplier"]=='yes')
$available_at_supplier = 'yes';
else
$available_at_supplier = 'no';

if ($_POST["display_on_ifcca"]=='yes')
$display_on_ifcca = 'yes';
else
$display_on_ifcca = 'no';

if ($_POST["display_milano6769Canada"]=='yes')
$display_milano6769Canada = 'yes';
else
$display_milano6769Canada = 'no';

if ($_POST["display_entrepot"]=='yes')
$display_entrepot = 'yes';
else
$display_entrepot = 'no';

if ($_POST["shape_dispo_entrepot"]=='yes')
$shape_dispo_entrepot = 'yes';
else
$shape_dispo_entrepot = 'no';

if ($_POST["display_milano_package_rep"]=='active')
$display_milano_package_rep = 'yes';
else
$display_milano_package_rep = 'no';

if ($_POST["display_milano_package_other"]=='active')
$display_milano_package_other = 'yes';
else
$display_milano_package_other = 'no';


	$query="INSERT into ifc_frames_french (
	misc_unknown_purpose,	 collection, 	 model, 	
	 upc,	 code,color_code
,color,type,gender,
material,mounting,boxing,
active,color_en,type_en,
gender_en,material_en,stock_price
,stock_price_entrepot,stock_price_with_discount,
available_at_supplier,frame_a,frame_b,
frame_ed,frame_dbl,frame_on_sale,
display_on_ifcca,display_entrepot,display_milano6769Canada,
shape_dispo_entrepot, frame_shape, frame_shape_en,
 branches_material, branches_material_en, mounting_en, date_created,ip1,ip2, display_milano_package_rep,display_milano_package_other) 
VALUES ('$misc_unknown_purpose', '$collection', '$model',
 '$upc', '$code', '$color_code',
  '$color','$type','$gender',
  '$material','$mounting','$boxing',
  '$active','$color_en','$type_en',
  '$gender_en','$material_en','$stock_price',
  '$stock_price_entrepot','$stock_price_with_discount',
  '$available_at_supplier','$frame_a','$frame_b',
  '$frame_ed','$frame_dbl','$frame_on_sale',
  '$display_on_ifcca','$display_entrepot','$display_milano6769Canada',
  '$shape_dispo_entrepot','$frame_shape', '$frame_shape_en',
  '$branches_material',  '$branches_material_en','$mounting_en','$Datedujour','$ip1','$ip2','$display_milano_package_rep','$display_milano_package_other')";
	$result=mysql_query($query)		or die ("Could not create new frame because " . mysql_error()  );
	
	//echo '<br>'. $query;
//Envoie des détails de la monture créée par email a Charles
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
//Build email content

$message="";
		
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" border=\"1\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <th align=\"center\"><b>Model</b></th>
				<th align=\"center\"><b>Color</b></th>
                <th align=\"center\"><b>Price</b></th>
				<th align=\"center\"><b>Price Entrepot</b></th>
                <th align=\"center\"><b>Price Discounted</b></th>
                <th align=\"center\"><b>Active</b></th>
				<th align=\"center\"><b>Available IFC.CA</b></th>
				<th align=\"center\"><b>Available Entrepot</b></th>
				<th align=\"center\"><b>Available Milano</b></th>
				</tr>";

		$message.="<tr>
                <td align=\"center\"><b>$model</b></td>
				<td align=\"center\"><b>$color</b></td>
                <td align=\"center\"><b>$stock_price  </b></td>
				<td align=\"center\"><b>$stock_price_entrepot</b></td>
                <td align=\"center\"><b>$stock_price_with_discount</b></td>
                <td align=\"center\"><b>$active</b></td>
				<td align=\"center\"><b>$display_on_ifcca</b></td>
				<td align=\"center\"><b>$shape_dispo_entrepot</b></td>
				<td align=\"center\"><b>$display_milano6769Canada</b></td>
				</tr>";


//Send EMAIL		
$send_to_address = array('rapports@direct-lens.com');	
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject= "Nouvelle monture créée: Main admin";
$response=office365_mail($to_address, $from_address, $subject, null, $message);
}

?>
