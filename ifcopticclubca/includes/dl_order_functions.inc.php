<?php 

function deleteOrderItem($pkey){
	include "../sec_connectEDLL.inc.php";
	
	$query="delete from orders where primary_key='$pkey'";//DELETE orders from ORDERS table
	$result=mysqli_query($con,$query)		or die ("Could not delete product" . $query);
		
	$query="delete from extra_product_orders where order_id='$pkey'";//DELETE linked items from EXTRA_PRODUCT_ORDERS table
	$result=mysqli_query($con,$query)		or die ("Could not delete product". $query);
		
	$query="delete from additional_discounts where orders_id='$pkey'";//DELETE entry in additional discounts table
	$result=mysqli_query($con,$query)		or die ("Could not delete additional discount entry". $query);

}

function deleteTrayOrderItem($tray_num){
	$query="delete from orders where tray_num='$tray_num' and order_status='basket'";
	$result=mysqli_query($con,$query)		or die ("Could not delete items");

}


function deleteFrameTrayOrderItem($tray_num){
	
	$query="delete from orders where tray_num='$tray_num'  and order_num =-1 and order_status='basket' and order_product_type='frame_stock_tray' and tray_num <> ''";
	//echo '<br>Query execute : '.  $query;
	$result=mysqli_query($con,$query)		or die ("Could not delete items");
}


function addStockTrayItem($product_id,$quantity,$eye,$tray_ref){//ADD STOCK TRAY ITEM TO ORDERS TABLE

$query="(select * from products,prices WHERE products.primary_key='$product_id' AND products.product_name=prices.product_name)";
$result=mysqli_query($con,$query)		or die  ('I cannot select items because: ' . mysqli_error($con));
		
$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);

$order_product_name=$listItem[product_name]; 

if ($_SESSION["sessionUserData"]["currency"]=="US"){
	$order_product_price=$listItem[price]; }
else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
	$order_product_price=$listItem[price_can]; }
else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
	$order_product_price=$listItem[price_eur]; }
	
$user_id=$_SESSION["sessionUser_Id"];
	
$discountQuery="SELECT discount from stock_discounts WHERE user_id='$user_id' AND product_name='$order_product_name'";
//GET PRODUCT DISCOUNT IF ANY
$discountResult=mysqli_query($con,$discountQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$discountItem=mysqli_fetch_array($discountResult,MYSQLI_ASSOC);

if ($discountItem[discount]!=0){
	$order_product_discount=$order_product_price-($order_product_price*($discountItem[discount]/100));}
else {	
	$order_product_discount=$order_product_price;}
	

	if ($listItem[products.coating]=="UC")
			$order_product_coating="Uncoated";
		else if ($listItem[products.coating]=="AR")
			$order_product_coating="Anti-Reflective";
		else if ($listItem[products.coating]=="SR")
			$order_product_coating="Scratch-Resistant";
		else if ($listItem[products.coating]=="SR AR")
			$order_product_coating="Scratch Resistant and Anti-Reflective";
		else $order_product_coating=$listItem[coating_brand];
		
		switch ($listItem[material]) {
		case "GL":
    	$order_product_material="Glass";
    		break;
		case "GH":
   			$order_product_material="Glass (High Index)";
   			 break;
		case "PL":
   			$order_product_material="Plastic";
   			 break;
		case "PH":
  		  $order_product_material="Plastic (High Index)";
   			 break;
		case "PY":
    		$order_product_material="Polycarbonate";
   			 break;
			 }

$order_product_index=$listItem[d_index]; 
		
$order_product_photo=""; 
$order_product_polar=""; 

$re_sphere=$listItem[sph_base]; 
$le_sphere=$listItem[sph_base]; 

$re_cyl=$listItem[cyl_add];
$le_cyl=$listItem[cyl_add];  

$query="insert into orders ";
 
$query.="(user_id	,order_num,order_item_number,tray_num,eye,order_date_processed,order_item_date,order_quantity,order_patient_first,order_patient_last,salesperson_id,order_product_name,	order_product_id,order_product_index,order_product_material,order_product_price,order_product_discount,order_product_type,order_product_coating,order_product_photo,order_product_polar,order_status,re_sphere	,le_sphere,re_cyl,le_cyl,re_add,le_add,re_axis,le_axis,re_pr_ax,le_pr_ax,re_pd,re_pd_near,re_height,le_pd,le_pd_near,le_height,frame_a,frame_b,frame_ed,frame_dbl,frame_type,currency,global_dsc,infocus_dsc,precision_dsc,innovative_dsc,visionpro_dsc,visionpropoly_dsc,generation_dsc,truehd_dsc,lab) values (";



$user_id=$_SESSION["sessionUser_Id"];
$query.="'$user_id',";

$order_num="-1"; 
$query.="'$order_num',";

$order_item_number=addslashes($_SESSION['ITEM_NUM']);
$query.="'$order_item_number',";

$query.="'$tray_ref',";

$query.="'$eye',";


$order_date_processed = date("Y-m-d");
$query.="'$order_date_processed',";

$order_item_date=date("Y-m-d");
$query.="'$order_item_date',";

$order_quantity=$quantity;
$query.="'$order_quantity',";

$order_patient_first="";
$query.="'$order_patient_first',";

$order_patient_last="";
$query.="'$order_patient_last',";

$salesperson_id="";
$query.="'$salesperson_id',";

$query.="'$order_product_name',";

$order_product_id=$product_id; 
$query.="'$order_product_id',";

$query.="'$order_product_index',";

$query.="'$order_product_material',";

$query.="'$order_product_price',";

$query.="'$order_product_discount',";

$order_product_type="stock_tray"; 
$query.="'$order_product_type',";

$query.="'$order_product_coating',";

$query.="'$order_product_photo',";

$query.="'$order_product_polar',";

$order_status="basket"; 
$query.="'$order_status',";

$query.="'$re_sphere',";

$query.="'$le_sphere',";

$query.="'$re_cyl',";

$query.="'$le_cyl',";

$re_add="";
$query.="'$re_add',";

$le_add="";
$query.="'$le_add',";

$re_axis="";
$query.="'$re_axis',";

$le_axis="";
$query.="'$le_axis',";

$re_pr_ax="";
$query.="'$re_pr_ax',";

$le_pr_ax="";
$query.="'$le_pr_ax',";

$re_pd="";
$query.="'$re_pd',";

$re_pd_near="";
$query.="'$re_pd_near',";

$re_height="";
$query.="'$re_height',";

$le_pd="";
$query.="'$le_pd',";

$le_pd_near="";
$query.="'$le_pd_near',";

$le_height="";
$query.="'$le_height',";

$frame_a="";
$query.="'$frame_a',";

$frame_b="";
$query.="'$frame_b',";

$frame_ed="";
$query.="'$frame_ed',";
 
$frame_dbl="";
$query.="'$frame_dbl',";

$frame_type="";
$query.="'$frame_type',";

$currency=$_SESSION["sessionUserData"]["currency"];
$query.="'$currency',";

$global_dsc=$_SESSION["sessionUserData"]["global_dsc"];
$query.="'$global_dsc',";

$infocus_dsc=$_SESSION["sessionUserData"]["infocus_dsc"];
$query.="'$infocus_dsc',";

$precision_vp_dsc=$_SESSION["sessionUserData"]["precision_dsc"];
$query.="'$precision_dsc',";

$innovative_dsc=$_SESSION["sessionUserData"]["innovative_dsc"];
$query.="'$innovative_dsc',";

$visionpro_dsc=$_SESSION["sessionUserData"]["visionpro_dsc"];
$query.="'$visionpro_dsc',";

$visionpropoly_dsc=$_SESSION["sessionUserData"]["visionpropoly_dsc"];
$query.="'$visionpropoly_dsc',";

$generation_dsc=$_SESSION["sessionUserData"]["generation_dsc"];
$query.="'$visionpropoly_dsc',";

$truehd_dsc=$_SESSION["sessionUserData"]["truehd_dsc"];
$query.="'$visionpropoly_dsc',";

$lab=$_SESSION["sessionUserData"]["main_lab"];
$query.="'$lab')";

$result=mysqli_query($con,$query)		or die ( "Query failed: " . mysqli_error($con) . "<br/>" . $query );


	
	$next_increment = 0;//GET THE PRIMARY KEY OF THE JUST CREATED PRODUCT
	$query="SHOW TABLE STATUS LIKE 'orders'";
	$Result=mysqli_query($con,$query) or die ( "Query failed: " . mysqli_error($con) . "<br/>" . $query );

	$row = mysqli_fetch_array($Result,MYSQLI_ASSOC);
	$next_increment=$row['Auto_increment'];

	$pkey=$next_increment-1;
	

	
	return $pkey;
}

function addStockItem($product_id,$quantity){//ADD STOCK ITEM TO ORDERS DB

$query="(select * from products,prices WHERE products.primary_key='$product_id' AND products.product_name=prices.product_name)";
$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
		
$listItem=mysql_fetch_array($result);

$order_product_name=$listItem[product_name]; 
if ($_SESSION["sessionUserData"]["currency"]=="US"){
	$order_product_price=$listItem[price];}
else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
	$order_product_price=$listItem[price_can];}
else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
	$order_product_price=$listItem[price_eur];}
	
	$user_id=$_SESSION["sessionUser_Id"];
	
$discountQuery="SELECT discount from stock_discounts WHERE user_id='$user_id' AND product_name='$order_product_name'";
//GET PRODUCT DISCOUNT IF ANY
$discountResult=mysql_query($discountQuery)
	or die  ('I cannot select items because: ' . mysql_error());
$discountItem=mysql_fetch_array($discountResult);

if ($discountItem[discount]!=0){
	$order_product_discount=$order_product_price-($order_product_price*($discountItem[discount]/100));}
else {	
	$order_product_discount=$order_product_price;}

	if ($listItem[products.coating]=="UC")
			$order_product_coating="Uncoated";
		else if ($listItem[products.coating]=="AR")
			$order_product_coating="Anti-Reflective";
		else if ($listItem[products.coating]=="SR")
			$order_product_coating="Scratch-Resistant";
		else if ($listItem[products.coating]=="SR AR")
			$order_product_coating="Scratch Resistant and Anti-Reflective";
		else $order_product_coating=$listItem[coating_brand];
		
		switch ($listItem[material]) {
		case "GL":
    	$order_product_material="Glass";
    		break;
		case "GH":
   			$order_product_material="Glass (High Index)";
   			 break;
		case "PL":
   			$order_product_material="Plastic";
   			 break;
		case "PH":
  		  $order_product_material="Plastic (High Index)";
   			 break;
		case "PY":
    		$order_product_material="Polycarbonate";
   			 break;
			 }

$order_product_index=$listItem[d_index]; 
		
$order_product_photo=""; 
$order_product_polar=""; 

$re_sphere=$listItem[sph_base]; 
$le_sphere=$listItem[sph_base]; 

$re_cyl=$listItem[cyl_add];
$le_cyl=$listItem[cyl_add];  

$query="insert into orders ";
 
$query.="(user_id	,order_num,order_item_number,order_date_processed,order_item_date,order_quantity,order_patient_first,order_patient_last,salesperson_id,order_product_name,	order_product_id,order_product_index,order_product_material,order_product_price,order_product_discount,order_product_type,order_product_coating,order_product_photo,order_product_polar,order_status,re_sphere	,le_sphere,re_cyl,le_cyl,re_add,le_add,re_axis,le_axis,re_pr_ax,le_pr_ax,re_pd,re_pd_near,re_height,le_pd,le_pd_near,le_height,frame_a,frame_b,frame_ed,frame_dbl,frame_type,currency,global_dsc,infocus_dsc,precision_dsc,innovative_dsc,visionpro_dsc,visionpropoly_dsc,generation_dsc,truehd_dsc,lab) values (";

$user_id=$_SESSION["sessionUser_Id"];
$query.="'$user_id',";

$order_num="-1"; 
$query.="'$order_num',";

$order_item_number=addslashes($_SESSION['ITEM_NUM']);
$query.="'$order_item_number',";

$order_date_processed = date("Y-m-d");
$query.="'$order_date_processed',";

$order_item_date=date("Y-m-d");
$query.="'$order_item_date',";

$order_quantity=$quantity;
$query.="'$order_quantity',";

$order_patient_first="";
$query.="'$order_patient_first',";

$order_patient_last="";
$query.="'$order_patient_last',";

$salesperson_id="";
$query.="'$salesperson_id',";

$query.="'$order_product_name',";

$order_product_id=$product_id; 
$query.="'$order_product_id',";

$query.="'$order_product_index',";

$query.="'$order_product_material',";

$query.="'$order_product_price',";

$query.="'$order_product_discount',";

$order_product_type="stock"; 
$query.="'$order_product_type',";

$query.="'$order_product_coating',";

$query.="'$order_product_photo',";

$query.="'$order_product_polar',";

$order_status="basket"; 
$query.="'$order_status',";

$query.="'$re_sphere',";

$query.="'$le_sphere',";

$query.="'$re_cyl',";

$query.="'$le_cyl',";

$re_add="";
$query.="'$re_add',";

$le_add="";
$query.="'$le_add',";

$re_axis="";
$query.="'$re_axis',";

$le_axis="";
$query.="'$le_axis',";

$re_pr_ax="";
$query.="'$re_pr_ax',";

$le_pr_ax="";
$query.="'$le_pr_ax',";

$re_pd="";
$query.="'$re_pd',";

$re_pd_near="";
$query.="'$re_pd_near',";

$re_height="";
$query.="'$re_height',";

$le_pd="";
$query.="'$le_pd',";

$le_pd_near="";
$query.="'$le_pd_near',";

$le_height="";
$query.="'$le_height',";

$frame_a="";
$query.="'$frame_a',";

$frame_b="";
$query.="'$frame_b',";

$frame_ed="";
$query.="'$frame_ed',";
 
$frame_dbl="";
$query.="'$frame_dbl',";

$frame_type="";
$query.="'$frame_type',";

$currency=$_SESSION["sessionUserData"]["currency"];
$query.="'$currency',";

$global_dsc=$_SESSION["sessionUserData"]["global_dsc"];
$query.="'$global_dsc',";

$infocus_dsc=$_SESSION["sessionUserData"]["infocus_dsc"];
$query.="'$infocus_dsc',";

$precision_dsc=$_SESSION["sessionUserData"]["precision_dsc"];
$query.="'$precision_dsc',";

$innovative_dsc=$_SESSION["sessionUserData"]["innovative_dsc"];
$query.="'$innovative_dsc',";

$visionpropoly_dsc=$_SESSION["sessionUserData"]["visionpro_dsc"];
$query.="'$visionpro_dsc',";

$visionpropoly_dsc=$_SESSION["sessionUserData"]["visionpropoly_dsc"];
$query.="'$visionpropoly_dsc',";

$visionpropoly_dsc=$_SESSION["sessionUserData"]["visionpropoly_dsc"];
$query.="'$generation_dsc',";

$visionpropoly_dsc=$_SESSION["sessionUserData"]["visionpropoly_dsc"];
$query.="'$truehd_dsc',";

$lab=$_SESSION["sessionUserData"]["main_lab"];
$query.="'$lab')";


$result=mysql_query($query)
		or die ( "Query failed: " . mysql_error() . "<br/>" . $query );
	
	$next_increment = 0;//GET THE PRIMARY KEY OF THE JUST CREATED PRODUCT
	$query="SHOW TABLE STATUS LIKE 'orders'";
	$Result=mysql_query($query) or die ( "Query failed: " . mysql_error() . "<br/>" . $query );

	$row = mysql_fetch_array($Result);
	$next_increment=$row['Auto_increment'];

	$pkey=$next_increment-1;
	
	return $pkey;
}


function addPrescriptionItem($product_id,$quantity){//ADD PRESCRIPTION ITEM TO ORDERS DB
include "../sec_connectEDLL.inc.php";
$query="(select * from ifc_ca_exclusive WHERE primary_key='$product_id')";
$result=mysqli_query($con,$query)		or die  ('I cannot select items because: ' . mysqli_error($con));	
$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);

if($_SESSION["sessionUser_Id"]=="warehousestc")
$order_product_name=$listItem[product_name_en]; 
else
$order_product_name=$listItem[product_name]; 

if ($_SESSION["sessionUserData"]["currency"]=="US"){
	$order_product_price=$listItem[price];}
else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
	$order_product_price=$listItem[price_can];}
else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
	$order_product_price=$listItem[price_eur];}
	
if ($_SESSION["sessionUserData"]["e_lab"]=="E-Lab US"){
	$order_product_price=$listItem[e_lab_us_price];}	
else if ($_SESSION["sessionUserData"]["e_lab"]=="E-Lab CAN"){
	$order_product_price=$listItem[e_lab_can_price];}
	
$BackupPACKAGE 		 = $_SESSION['PACKAGE'];
$user_id       		 = $_SESSION["sessionUser_Id"];	
$order_product_price = $listItem['price_can'];

$_SESSION['PACKAGE']="";//EMPTY SO DOESN'T CAUSE TROUBLE LATER
	
if (($_SESSION['PrescrData']['EYE']=="R.E.")||($_SESSION['PrescrData']['EYE']=="L.E.")){
	$order_product_price=money_format('%.2n',$order_product_price/2);
		}
		
			
			//WARRANTY
			if (($_SESSION['PrescrData']['WARRANTY']== 1) &&  ($_SESSION['PrescrData']['EYE']=="Both")){
			$order_product_price = $order_product_price + 6;
			}
			
			if (($_SESSION['PrescrData']['WARRANTY']== 1) &&  ($_SESSION['PrescrData']['EYE']=="R.E.")){
			$order_product_price = $order_product_price + 3;
			}
			
			if (($_SESSION['PrescrData']['WARRANTY']== 1) &&  ($_SESSION['PrescrData']['EYE']=="L.E.")){
			$order_product_price = $order_product_price + 3;
			}
			
				
						
			if (($_SESSION['PrescrData']['WARRANTY']== 2) &&  ($_SESSION['PrescrData']['EYE']=="Both")){
			$order_product_price = $order_product_price + 10;
			}
			
			if (($_SESSION['PrescrData']['WARRANTY']== 2) &&  ($_SESSION['PrescrData']['EYE']=="R.E.")){
			$order_product_price = $order_product_price + 5;
			}
			
			if (($_SESSION['PrescrData']['WARRANTY']== 2) &&  ($_SESSION['PrescrData']['EYE']=="L.E.")){
			$order_product_price = $order_product_price + 5;
			}
			

			
			if (($_SESSION['PrescrData']['WARRANTY']== 'extension') &&  ($_SESSION['PrescrData']['EYE']=="Both")){
			$order_product_price = $order_product_price + 40;
			}
			
			if (($_SESSION['PrescrData']['WARRANTY']== 'extension') &&  ($_SESSION['PrescrData']['EYE']=="L.E.")){
			$order_product_price = $order_product_price + 20;
			}
			
			if (($_SESSION['PrescrData']['WARRANTY']== 'extension') &&  ($_SESSION['PrescrData']['EYE']=="R.E.")){
			$order_product_price = $order_product_price + 20;
			}
			
			
	
	//$global_dsc=$_SESSION["sessionUserData"]["global_dsc"]/100;
	//$order_product_discount=$order_product_price-($order_product_price*$global_dsc);
	
	$lab_id=$_SESSION["sessionUserData"]["main_lab"];//GET Presciprtion Lab
	$labQuery="select * from labs where primary_key ='$lab_id'";
	$labResult=mysqli_query($con,$labQuery)	or die ("Could not find account");
	$labItem = mysqli_fetch_array($labResult,MYSQLI_ASSOC);
	
	
	
	$order_product_discount=$order_product_price;
	
	if ($listItem[collection]=="Entrepot SV"){
		$prescript_lab=$labItem[entrepot_sv_lab];	
	}
	
	if ($listItem[collection]=="Entrepot STC"){
		$prescript_lab = 3;	
	}
	
	if ($listItem[collection]=="Entrepot KNR"){
		$prescript_lab = 73;	
	}
	
	if ($listItem[collection]=="Entrepot HKO"){
		$prescript_lab=$labItem[entrepot_hko_lab];	
	}
	
	if ($listItem[collection]=="Precision"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["precision_dsc"]/100);
		$prescript_lab=$labItem[precision_vp_lab];
	}
	
	if ($listItem[collection]=="Innovation"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["innovation_dsc"]/100);
		$prescript_lab=$labItem[innovation_lab];	
	}
	
	
	//IFC.ca COLLECTIONS
	
	

	if ($listItem[collection]=="FT IFC"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["ft_ifc_dsc"]/100);
		$prescript_lab=$labItem[ft_ifc_lab];	
	}
	
	if ($listItem[collection]=="IFC Crystal"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["ifc_crystal_dsc"]/100);
		$prescript_lab=$labItem[ifc_crystal_lab];	
	}
	
	if ($listItem[collection]=="IFC SteCath"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["ifc_stecath_dsc"]/100);
		$prescript_lab=$labItem[ifc_stecath_lab];	
	}
	
	if ($listItem[collection]=="IFC Swiss"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["ifc_swiss_dsc"]/100);
		$prescript_lab=$labItem[ifc_swiss_lab];	
	}
	
	if ($listItem[collection]=="SV IFC"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["sv_ifc_dsc"]/100);
		$prescript_lab=$labItem[sv_ifc_lab];	
	}
	
	if ($listItem[collection]=="NURBS sunglasses"){
		$prescript_lab=25;	
	}
	
	//Entrepot collection
	if ($listItem[collection]=="Entrepot SV"){
		$prescript_lab=$labItem[entrepot_sv_lab];	
	}
	
	if ($listItem[collection]=="Entrepot Sky"){
		$prescript_lab=$labItem[entrepot_sky_lab];	
	}
	
	if ($listItem[collection]=="Entrepot FT"){
		$prescript_lab=$labItem[entrepot_ft_lab];	
	}
	
	if ($listItem[collection]=="Entrepot Swiss"){
		$prescript_lab=$labItem[entrepot_swiss_lab];	
	}
	
	if ($listItem[collection]=="Entrepot CSC"){
		$prescript_lab=$labItem[entrepot_csc_lab];	
	}
	
	if ($listItem[collection]=="Entrepot Crystal"){
		$prescript_lab=$labItem[entrepot_crystal_lab];	
	}
	
	if ($listItem[collection]=="Entrepot DL"){
		$prescript_lab=$labItem[entrepot_dl_lab];	
	}
	
	if ($listItem[collection]=="Entrepot Promo"){
		$prescript_lab=$labItem[entrepot_promo_lab];	
	}
	//End Entrepot collection
	
	
	//END IFC.ca COLLECTIONS
	
	
	
	


	if ($listItem[collection]=="Vision Pro"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["visionpro_dsc"]/100);
		$prescript_lab=$labItem[precision_vp_lab];
	}
	
	if ($listItem[collection]=="Infocus"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["infocus_dsc"]/100);
		$prescript_lab=$labItem[infocus_lab];
	}
	
	if ($listItem[collection]=="My World"){// USED TO BE INNOVATIVE
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["innovative_dsc"]/100);
		$prescript_lab=$labItem[innovative_lab];
	}
	
	if ($listItem[collection]=="Vision Pro Poly"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["visionpropoly_dsc"]/100);
		$prescript_lab=$labItem[visionpropoly_lab];
	}
	
	if ($listItem[collection]=="Generation"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["generation_dsc"]/100);
		$prescript_lab=$labItem[generation_lab];
	}
	
	if ($listItem[collection]=="TrueHD"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["truehd_dsc"]/100);
		$prescript_lab=$labItem[truehd_lab];
	}
	
	if ($listItem[collection]=="Easy Fit HD"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["easy_fit_dsc"]/100);
		$prescript_lab=$labItem[easy_fit_lab];
	}
	

	
	
	if ($listItem[collection]=="Vision Eco"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["visioneco_dsc"]/100);
		$prescript_lab=$labItem[visioneco_lab];
	}
	
	if ($listItem[collection]=="Nesp"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["nesp_dsc"]/100);
		$prescript_lab=$labItem[nesp_lab];
	}
	
	if ($listItem[collection]=="Optimize IFC"){
		$prescript_lab=57;
	}
	
	
	if ($listItem[collection]=="Private 1"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["private_1_dsc"]/100);
		$prescript_lab=$labItem[private_1_lab];
	}
	
	if ($listItem[collection]=="Private 2"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["private_2_dsc"]/100);
		$prescript_lab=$labItem[private_2_lab];
	}
	
	if ($listItem[collection]=="Private 3"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["private_3_dsc"]/100);
		$prescript_lab=$labItem[private_3_lab];
	}
	
	if ($listItem[collection]=="Private 4"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["private_4_dsc"]/100);
		$prescript_lab=$labItem[private_4_lab];
	}
	
	if ($listItem[collection]=="Private 5"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["private_5_dsc"]/100);
		$prescript_lab=$labItem[private_5_lab];
	}
	
	if ($listItem[collection]=="Private 6"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["private_6_dsc"]/100);
		$prescript_lab=$labItem[private_6_lab];
	}
	
	if ($listItem[collection]=="Private 7"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["private_7_dsc"]/100);
		$prescript_lab=$labItem[private_7_lab];
	}
	
	if ($listItem[collection]=="Private 8"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["private_8_dsc"]/100);
		$prescript_lab=$labItem[private_8_lab];
	}
	
	
	if ($listItem[collection]=="ClearI"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["cleari_dsc"]/100);
		$prescript_lab=$labItem[cleari_lab];
	}
	
	
	
	if ($listItem[collection]=="Eco 1"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eco_1_dsc"]/100);
		$prescript_lab=$labItem[eco_1_lab];
	}
	
	if ($listItem[collection]=="Eco 2"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eco_2_dsc"]/100);
		$prescript_lab=$labItem[eco_2_lab];
	}
	
	if ($listItem[collection]=="Eco 3"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eco_3_dsc"]/100);
		$prescript_lab=$labItem[eco_3_lab];
	}
	
	if ($listItem[collection]=="Eco 4"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eco_4_dsc"]/100);
		$prescript_lab=$labItem[eco_4_lab];
	}
	
	if ($listItem[collection]=="Eco 5"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eco_5_dsc"]/100);
		$prescript_lab=$labItem[eco_5_lab];
	}
	
	if ($listItem[collection]=="Eco 6"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eco_6_dsc"]/100);
		$prescript_lab=$labItem[eco_6_lab];
	}
	
	if ($listItem[collection]=="Eco 7"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eco_7_dsc"]/100);
		$prescript_lab=$labItem[eco_7_lab];
	}
	
	if ($listItem[collection]=="Eco 8"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eco_8_dsc"]/100);
		$prescript_lab=$labItem[eco_8_lab];
	}
	
	if ($listItem[collection]=="Eco 9"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eco_9_dsc"]/100);
		$prescript_lab=$labItem[eco_9_lab];
	}
	
	if ($listItem[collection]=="Eco Conant"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eco_conant_dsc"]/100);
		$prescript_lab=$labItem[eco_conant_lab];
	}
	
	
	if ($listItem[collection]=="Eco OR"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eco_or_dsc"]/100);
		$prescript_lab=$labItem[eco_or_lab];
	}
	
	
	
	if ($listItem[collection]=="Svision"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["svision_dsc"]/100);
		$prescript_lab=$labItem[svision_lab];
	}
	
	if ($listItem[collection]=="Svision 2"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["svision_2_dsc"]/100);
		$prescript_lab=$labItem[svision_2_lab];
	}
	
	if ($listItem[collection]=="Optovision"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["optovision_dsc"]/100);
		$prescript_lab=$labItem[optovision_lab];
	}
	
	
	if ($listItem[collection]=="Conant"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["conant_dsc"]/100);
		$prescript_lab=$labItem[conant_lab];
	}
	
	
	if ($listItem[collection]=="Vot"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["vot_dsc"]/100);
		$prescript_lab=$labItem[vot_lab];
	}
	
	if ($listItem[collection]=="Glass"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["glass_dsc"]/100);
		$prescript_lab=$labItem[glass_lab];
	}
	
	if ($listItem[collection]=="Glass 2"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["glass_2_dsc"]/100);
		$prescript_lab=$labItem[glass_2_lab];
	}
	
	if ($listItem[collection]=="Glass 3"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["glass_3_dsc"]/100);
		$prescript_lab=$labItem[glass_3_lab];
	}
	
	if ($listItem[collection]=="Eco"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eco_dsc"]/100);
		$prescript_lab=$labItem[eco_lab];
	}
	
	if ($listItem[collection]=="Rodenstock"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["rodenstock_dsc"]/100);
		$prescript_lab=$labItem[rodenstock_lab];
	}
	
	if ($listItem[collection]=="Rodenstock HD"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["rodenstock_hd_dsc"]/100);
		$prescript_lab=$labItem[rodenstock_hd_lab];
	}
	
	if ($listItem[collection]=="Innovation FF"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["innovation_ff_dsc"]/100);
		$prescript_lab=$labItem[innovation_ff_lab];
	}
	
	if ($listItem[collection]=="Innovation DS"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["innovation_ds_dsc"]/100);
		$prescript_lab=$labItem[innovation_ds_lab];
	}
	
	if ($listItem[collection]=="Innovation II DS"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["innovation_ii_ds_dsc"]/100);
		$prescript_lab=$labItem[innovation_ii_ds_lab];
	}
	
	if ($listItem[collection]=="Innovation FF HD"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["innovation_ff_hd_dsc"]/100);
		$prescript_lab=$labItem[innovation_ff_hd_lab];
	}

	
//Pour les frames ISEE, on REDIRIGE A TR pour faire taill�-mont� par Avner	
$RedirectionTR = 'non';
$PositionProgCl   = strpos($listItem[product_name],'Progressif Classique'); 
//$PositionProgHdIndoor = strpos($listItem[product_name],'Progressif HD Indoor'); 
//$PositionProgHdOutdoor= strpos($listItem[product_name],'Progressif HD Outdoor'); 
//$PositionProghdDaily  = strpos($listItem[product_name],'Progressif HD Daily'); 

if ($PositionProgCl !== false){
$RedirectionTR = 'oui';
}

//A D�COMMENTER QUAND MARCO VOUDRA QUE CA SOIT ACTIF
if ($prescript_lab == 0)
$prescript_lab = 21;

if ($prescript_lab == '')
$prescript_lab = 21;
	
$order_product_coating=$listItem[coating]; 
$order_product_photo=$listItem[photo]; 
$order_product_polar=$listItem[polar]; 
$order_product_index=$listItem[index_v];

$user_id=$_SESSION["sessionUser_Id"];
$queryProductLine = "SELECT product_line from accounts WHERE user_id = '" . $user_id  . "'";
$ResultProductline=mysqli_query($con,$queryProductLine)		or die ("Could not find codes");
$DataProductLine=mysqli_fetch_array($ResultProductline,MYSQLI_ASSOC);
$ProductLine = $DataProductLine[product_line];

$query="insert into orders ";
 
$query.="(ip, order_from,tray_num, user_id,warranty,order_num,order_item_number,eye,order_date_processed,order_item_date,order_quantity,order_patient_first,order_patient_last,patient_ref_num,salesperson_id,order_product_name,	order_product_id,order_product_index,order_product_price,order_product_discount,order_over_range_fee,order_product_type,order_product_coating,order_product_photo,order_product_polar,order_status,re_sphere	,le_sphere,re_cyl,le_cyl,re_add,le_add,re_axis,le_axis,re_pr_ax,le_pr_ax,re_pr_ax2,le_pr_ax2,re_pr_io,re_pr_ud,le_pr_io,le_pr_ud,re_pd,re_pd_near,re_height,le_pd,le_pd_near,le_height,PT,PA,vertex,frame_a,frame_b,frame_ed,frame_dbl,frame_type,currency,special_instructions,global_dsc,private_1_dsc,private_2_dsc, private_3_dsc, private_4_dsc, private_5_dsc, eco_1_dsc, eco_2_dsc, eco_3_dsc, eco_4_dsc, eco_5_dsc, eco_6_dsc,bbg_1_dsc, bbg_2_dsc, bbg_3_dsc, bbg_4_dsc, bbg_5_dsc, bbg_6_dsc, bbg_7_dsc, bbg_8_dsc, bbg_9_dsc, bbg_10_dsc, bbg_11_dsc ,bbg_12_dsc, svision_dsc, svision_2_dsc,  infocus_dsc,precision_dsc,innovative_dsc,visionpro_dsc,visionpropoly_dsc,visioneco_dsc,generation_dsc,truehd_dsc,easy_fit_dsc,glass_dsc, glass_2_dsc, glass_3_dsc, vot_dsc, rodenstock_dsc,rodenstock_hd_dsc, innovation_dsc, innovation_ff_dsc,innovation_ds_dsc, innovation_ii_ds_dsc , prescript_lab, base_curve, internal_note, myupload, shape_name_bk, reference_promo, re_ct, le_Ct, re_et, le_et, optical_center, authorized_by, lab) values (";

$ip= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$query.="'$ip',";

$query.="'$ProductLine',";

$TRAY_NUM = $_SESSION['PrescrData']['TRAY_NUM'];
$query.="'$TRAY_NUM',";

$query.="'$user_id',";

$warranty=$_SESSION['PrescrData']['WARRANTY']; 
$query.="'$warranty',";


$order_num="-1"; 
$query.="'$order_num',";

$order_item_number=addslashes($_SESSION['ITEM_NUM']);
$query.="'$order_item_number',";

$eye=addslashes($_SESSION['PrescrData']['EYE']);
$query.="'$eye',";

$order_date_processed = date("Y-m-d");
$query.="'$order_date_processed',";


$order_item_date  = date("Y-m-d", strtotime('-5 hours', time()));
//Compense pour le 5h de d�callage avec le B
//$order_item_date=date("Y-m-d");
$query.="'$order_item_date',";

$order_quantity=$quantity;
$query.="'$order_quantity',";

$order_patient_first=addslashes($_SESSION['PrescrData']['FIRST_NAME']);
$query.="'$order_patient_first',";

$order_patient_last=addslashes($_SESSION['PrescrData']['LAST_NAME']);
$query.="'$order_patient_last',";


$order_patient_last=addslashes($_SESSION['PrescrData']['PATIENT_REF_NUM']);
$query.="'$order_patient_last',";

$salesperson_id=addslashes($_SESSION['PrescrData']['SALESPERSON_ID']);
$query.="'$salesperson_id',";

$query.="'$order_product_name',";

$order_product_id=$product_id; 
$query.="'$order_product_id',";

$query.="'$order_product_index',";

$query.="'$order_product_price',";

$query.="'$order_product_discount',";

$order_over_range_fee=$_POST[overRange];
$query.="'$order_over_range_fee',";

$order_product_type="exclusive"; 
$query.="'$order_product_type',";

$query.="'$order_product_coating',";

$query.="'$order_product_photo',";

$query.="'$order_product_polar',";

$order_status="basket"; 
$query.="'$order_status',";

$re_sphere=addslashes($_SESSION['PrescrData']['RE_SPHERE']);
$query.="'$re_sphere',";

$le_sphere=addslashes($_SESSION['PrescrData']['LE_SPHERE']);
$query.="'$le_sphere',";

$re_cyl=addslashes($_SESSION['PrescrData']['RE_CYL']);
$query.="'$re_cyl',";

$le_cyl=addslashes($_SESSION['PrescrData']['LE_CYL']);
$query.="'$le_cyl',";

$re_add=addslashes($_SESSION['PrescrData']['RE_ADD']);
$query.="'$re_add',";

$le_add=addslashes($_SESSION['PrescrData']['LE_ADD']);
$query.="'$le_add',";

$re_axis=addslashes($_SESSION['PrescrData']['RE_AXIS']);
$query.="'$re_axis',";

$le_axis=addslashes($_SESSION['PrescrData']['LE_AXIS']);
$query.="'$le_axis',";

$re_pr_ax=addslashes($_SESSION['PrescrData']['RE_PR_AX']);
$query.="'$re_pr_ax',";

$le_pr_ax=addslashes($_SESSION['PrescrData']['LE_PR_AX']);
$query.="'$le_pr_ax',";

$re_pr_ax2=addslashes($_SESSION['PrescrData']['RE_PR_AX2']);
$query.="'$re_pr_ax2',";

$le_pr_ax2=addslashes($_SESSION['PrescrData']['LE_PR_AX2']);
$query.="'$le_pr_ax2',";

$re_pr_io=addslashes($_SESSION['PrescrData']['RE_PR_IO']);
$query.="'$re_pr_io',";

$re_pr_ud=addslashes($_SESSION['PrescrData']['RE_PR_UD']);
$query.="'$re_pr_ud',";

$le_pr_io=addslashes($_SESSION['PrescrData']['LE_PR_IO']);
$query.="'$le_pr_io',";

$le_pr_ud=addslashes($_SESSION['PrescrData']['LE_PR_UD']);
$query.="'$le_pr_ud',";

$re_pd=addslashes($_SESSION['PrescrData']['RE_PD']);
$query.="'$re_pd',";

$re_pd_near=addslashes($_SESSION['PrescrData']['RE_PD_NEAR']);
$query.="'$re_pd_near',";

$re_height=addslashes($_SESSION['PrescrData']['RE_HEIGHT']);
$query.="'$re_height',";

$le_pd=addslashes($_SESSION['PrescrData']['LE_PD']);
$query.="'$le_pd',";

$le_pd_near=addslashes($_SESSION['PrescrData']['LE_PD_NEAR']);
$query.="'$le_pd_near',";

$le_height=addslashes($_SESSION['PrescrData']['LE_HEIGHT']);
$query.="'$le_height',";

$PT=addslashes($_SESSION['PrescrData']['PT']);
$query.="'$PT',";

$PA=addslashes($_SESSION['PrescrData']['PA']);
$query.="'$PA',";

$vertex=addslashes($_SESSION['PrescrData']['VERTEX']);
$query.="'$vertex',";

$frame_a=addslashes($_SESSION['PrescrData']['FRAME_A']);
$query.="'$frame_a',";

$frame_b=addslashes($_SESSION['PrescrData']['FRAME_B']);
$query.="'$frame_b',";

$frame_ed=addslashes($_SESSION['PrescrData']['FRAME_ED']);
$query.="'$frame_ed',";
 
$frame_dbl=addslashes($_SESSION['PrescrData']['FRAME_DBL']);
$query.="'$frame_dbl',";

$frame_type=addslashes($_SESSION['PrescrData']['FRAME_TYPE']);
$query.="'$frame_type',";

$currency=$_SESSION["sessionUserData"]["currency"];
$query.="'$currency',";

$special_instructions=addslashes($_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS']);
$query.="'$special_instructions',";

$global_dsc=$_SESSION["sessionUserData"]["global_dsc"];
$query.="'$global_dsc',";


$private_1_dsc=$_SESSION["sessionUserData"]["private1_dsc"];
$query.="'$private1_dsc',";

$private_2_dsc=$_SESSION["sessionUserData"]["private2_dsc"];
$query.="'$private2_dsc',";

$private_3_dsc=$_SESSION["sessionUserData"]["private3_dsc"];
$query.="'$private3_dsc',";

$private_4_dsc=$_SESSION["sessionUserData"]["private4_dsc"];
$query.="'$private4_dsc',";

$private_5_dsc=$_SESSION["sessionUserData"]["private5_dsc"];
$query.="'$private5_dsc',";



$eco_1_dsc=$_SESSION["sessionUserData"]["eco_1_dsc"];
$query.="'$eco_1_dsc',";

$eco_2_dsc=$_SESSION["sessionUserData"]["eco_2_dsc"];
$query.="'$eco_2_dsc',";

$eco_3_dsc=$_SESSION["sessionUserData"]["eco_3_dsc"];
$query.="'$eco_3_dsc',";

$eco_4_dsc=$_SESSION["sessionUserData"]["eco_4_dsc"];
$query.="'$eco_4_dsc',";

$eco_5_dsc=$_SESSION["sessionUserData"]["eco_5_dsc"];
$query.="'$eco_5_dsc',";

$eco_6_dsc=$_SESSION["sessionUserData"]["eco_6_dsc"];
$query.="'$eco_6_dsc',";


$bbg_1_dsc=$_SESSION["sessionUserData"]["bbg_1_dsc"];
$query.="'$bbg_1_dsc',";

$bbg_2_dsc=$_SESSION["sessionUserData"]["bbg_2_dsc"];
$query.="'$bbg_2_dsc',";

$bbg_3_dsc=$_SESSION["sessionUserData"]["bbg_3_dsc"];
$query.="'$bbg_3_dsc',";

$bbg_4_dsc=$_SESSION["sessionUserData"]["bbg_4_dsc"];
$query.="'$bbg_4_dsc',";

$bbg_5_dsc=$_SESSION["sessionUserData"]["bbg_5_dsc"];
$query.="'$bbg_5_dsc',";

$bbg_6_dsc=$_SESSION["sessionUserData"]["bbg_6_dsc"];
$query.="'$bbg_6_dsc',";

$bbg_7_dsc=$_SESSION["sessionUserData"]["bbg_7_dsc"];
$query.="'$bbg_7_dsc',";

$bbg_8_dsc=$_SESSION["sessionUserData"]["bbg_8_dsc"];
$query.="'$bbg_8_dsc',";

$bbg_9_dsc=$_SESSION["sessionUserData"]["bbg_9_dsc"];
$query.="'$bbg_9_dsc',";

$bbg_10_dsc=$_SESSION["sessionUserData"]["bbg_10_dsc"];
$query.="'$bbg_10_dsc',";

$bbg_11_dsc=$_SESSION["sessionUserData"]["bbg_11_dsc"];
$query.="'$bbg_11_dsc',";

$bbg_12_dsc=$_SESSION["sessionUserData"]["bbg_12_dsc"];
$query.="'$bbg_12_dsc',";


$svision_dsc=$_SESSION["sessionUserData"]["svision_dsc"];
$query.="'$svision_dsc',";


$svision_2_dsc=$_SESSION["sessionUserData"]["svision_2_dsc"];
$query.="'$svision_2_dsc',";


$infocus_dsc=$_SESSION["sessionUserData"]["infocus_dsc"];
$query.="'$infocus_dsc',";

$precision_dsc=$_SESSION["sessionUserData"]["precision_dsc"];
$query.="'$precision_dsc',";

$innovative_dsc=$_SESSION["sessionUserData"]["innovative_dsc"];
$query.="'$innovative_dsc',";

$visionpro_dsc=$_SESSION["sessionUserData"]["visionpro_dsc"];
$query.="'$visionpro_dsc',";

$visionpropoly_dsc=$_SESSION["sessionUserData"]["visionpropoly_dsc"];
$query.="'$visionpropoly_dsc',";

$visioneco_dsc=$_SESSION["sessionUserData"]["visioneco_dsc"];
$query.="'$visioneco_dsc',";

$generation_dsc=$_SESSION["sessionUserData"]["generation_dsc"];
$query.="'$generation_dsc',";

$truehd_dsc=$_SESSION["sessionUserData"]["truehd_dsc"];
$query.="'$truehd_dsc',";

$easy_fit_dsc=$_SESSION["sessionUserData"]["easy_fit_dsc"];
$query.="'$easy_fit_dsc',";



$glass_dsc=$_SESSION["sessionUserData"]["glass_dsc"];
$query.="'$glass_dsc',";

$glass_2_dsc=$_SESSION["sessionUserData"]["glass_2_dsc"];
$query.="'$glass_2_dsc',";

$glass_3_dsc=$_SESSION["sessionUserData"]["glass_3_dsc"];
$query.="'$glass_3_dsc',";


$vot_dsc=$_SESSION["sessionUserData"]["vot_dsc"];
$query.="'$vot_dsc',";


$rodenstock_dsc=$_SESSION["sessionUserData"]["rodenstock_dsc"];
$query.="'$rodenstock_dsc',";



$rodenstock_hd_dsc=$_SESSION["sessionUserData"]["rodenstock_hd_dsc"];
$query.="'$rodenstock_hd_dsc',";

$innovation_dsc=$_SESSION["sessionUserData"]["innovation_dsc"];
$query.="'$innovation_dsc',";

$innovation_ff_dsc=$_SESSION["sessionUserData"]["innovation_ff_dsc"];
$query.="'$innovation_ff_dsc',";

$innovation_ff_dsc=$_SESSION["sessionUserData"]["innovation_ds_dsc"];
$query.="'$innovation_ds_dsc',";

$innovation_ii_ds_dsc=$_SESSION["sessionUserData"]["innovation_ds_dsc"];
$query.="'$innovation_ii_ds_dsc',";


$query.="'$prescript_lab',";

$BASE_CURVE=$_SESSION['PrescrData']['BASE_CURVE'] ;
$query.="'$BASE_CURVE',";


$internal_note=addslashes($_SESSION['PrescrData']['INTERNAL_NOTE']);
$query.="'$internal_note',";

///////////////////////////////////////////////////////////////// uploader

$myupload=$_SESSION['PrescrData']['myupload'];
$query.="'$myupload',";

$myupload=$_SESSION['PrescrData']['myupload'];
$query.="'$myupload',";



$reference_promo =$_SESSION['PrescrData']['REFERENCE_PROMO'];
$query.="'$reference_promo',";

$RE_CT=$_SESSION["prFormVars"]["RE_CT"];
$query.="'$RE_CT',";

$LE_CT=$_SESSION["prFormVars"]["LE_CT"];
$query.="'$LE_CT',";

$RE_ET=$_SESSION["prFormVars"]["RE_ET"];
$query.="'$RE_ET',";

$LE_ET=$_SESSION["prFormVars"]["LE_ET"];
$query.="'$LE_ET',";


$optical_center=addslashes($_SESSION['PrescrData']['OPTICAL_CENTER']);
$query.="'$optical_center',";

$authorized_by=addslashes($_SESSION['PrescrData']['authorized_by']);
$query.="'$authorized_by',";


$lab=$_SESSION["sessionUserData"]["main_lab"];
$query.="'$lab')";


$result=mysqli_query($con,$query)		or die ( "Query failed: " . mysqli_error($con) . "<br/>" . $query );
		
	//echo '<br><br>' . $query.'<br><br>';
	
	$next_increment = 0;//GET THE PRIMARY KEY OF THE JUST CREATED PRODUCT
	$query="SHOW TABLE STATUS LIKE 'orders'";
	$Result=mysqli_query($con,$query) or die ( "Query failed: " . mysqli_error($con) . "<br/>" . $query );
	$row = mysqli_fetch_array($Result,MYSQLI_ASSOC);
	$next_increment=$row['Auto_increment'];

	$pkey=$next_increment-1;

//////////////////////////////////////////////////////////////// assign an id num to the basket
	if($_SESSION['PrescrData']['mybasketid']){
		//echo "session available<br>";
		$mybasketid = $_SESSION['PrescrData']['mybasketid'];

		$result1=mysqli_query($con,"INSERT INTO upload_basket (order_id) VALUES ('$pkey');") or die ( "Query failed: " . mysqli_error($con) . "<br/>");	
	} else {
		//	echo "session not available<br>";
		$result2=mysqli_query($con,"INSERT INTO upload_basket (order_id) VALUES ('$pkey');") or die ( "Query failed: " . mysqli_error($con) . "<br/>");
		
		$next_increment2 = 0;//GET THE PRIMARY KEY OF THE JUST CREATED PRODUCT
		$query2="SHOW TABLE STATUS LIKE 'upload_basket'";
		$Result2=mysqli_query($con,$query2) or die ( "Query failed: " . mysqli_error($con) . "<br/>" . $query2 );
		$row2 = mysqli_fetch_array($Result2,MYSQLI_ASSOC);
		$next_increment2=$row2['Auto_increment'];

		$pkey2=$next_increment2-1;
				
		$mybasketid = $pkey2;
		$_SESSION['PrescrData']['mybasketid'] = $mybasketid;
	}
	//echo "basket :".$mybasketid;
	//echo "pkey :".$pkey;
	$result3=mysqli_query($con,"UPDATE upload_basket SET basket_id = '$mybasketid' WHERE order_id = '$pkey';") or die ( "Query failed: " . mysqli_error($con) . "<br/>");

	return $pkey;
}

function catchOrderDataStock (){
$TEXT="";
for ($i=1;$i<=11;$i++){
	$CHAR=rand(0,9);
	$TEXT=$TEXT.$CHAR;
	}

$_SESSION['ITEM_NUM']=$TEXT;
}

function catchOrderData (){
	if($_POST['uploadhold'] && $_POST['uploadhold']!= "none"){
		$_SESSION['PrescrData']['myupload'] = $_POST['uploadhold'];
	}
	if($_POST['myupload']){
		$_SESSION['PrescrData']['myupload']=$_POST['myupload'];
	} 


$_SESSION['PrescrData']['EDGE_POLISH']       	 = $_POST[EDGE_POLISH];
$_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS']=strtoupper($_POST[SPECIAL_INSTRUCTIONS]);
$Position = strpos($_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'], '*EDGE POLISHED*'); 
	if ($_POST[EDGE_POLISH]=="yes") {
		if (is_numeric($Position))
		{
		}else{
		$_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'] = $_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'] . '  *EDGE POLISHED*';
		}
	}

$_SESSION['lens_category']    		 	 		 = $_POST[lens_category];
$_SESSION['PrescrData']['nwd']       		 	 = $_POST[nwd];
$_SESSION['PrescrData']['MIRROR']       		 = $_POST[MIRROR];
$_SESSION['PrescrData']['CORRIDOR']       		 = $_POST[CORRIDOR];
$_SESSION['PrescrData']['RE_CT']       			 = $_POST[RE_CT];
$_SESSION['PrescrData']['LE_CT']       			 = $_POST[LE_CT];
$_SESSION['PrescrData']['RE_ET']       			 = $_POST[RE_ET];
$_SESSION['PrescrData']['LE_ET']       			 = $_POST[LE_ET];
$_SESSION['PrescrData']['OPTICAL_CENTER']        = $_POST[OPTICAL_CENTER];
$_SESSION['PrescrData']['BASE_CURVE']  	         = $_POST[BASE_CURVE];
$_SESSION['PrescrData']['REFERENCE_PROMO']  	 = $_POST[REFERENCE_PROMO];
$_SESSION['PrescrData']['DISPENSING_FEE_SV']  	 = $_POST[DISPENSING_FEE_SV];
$_SESSION['PrescrData']['DISPENSING_FEE_PROG']	 = $_POST[DISPENSING_FEE_PROG];
$_SESSION['PrescrData']['DISPENSING_FEE_BIFOCAL']= $_POST[DISPENSING_FEE_BIFOCAL];	
$_SESSION['PrescrData']['EYE']=$_POST[EYE];
$_SESSION['PrescrData']['TRAY_NUM']=$_POST[TRAY_NUM];
$_SESSION['PrescrData']['LAST_NAME']=$_POST[LAST_NAME];
$_SESSION['PrescrData']['FIRST_NAME']=$_POST[FIRST_NAME];
$_SESSION['PrescrData']['PATIENT_REF_NUM']=strtoupper($_POST[PATIENT_REF_NUM]);
$_SESSION['PrescrData']['SALESPERSON_ID']=$_POST[SALESPERSON_ID];

$_SESSION['PrescrData']['DISPENSING_FEE_20'] = $_POST[DISPENSING_FEE_20];
$_SESSION['PrescrData']['DISPENSING_FEE_25'] = $_POST[DISPENSING_FEE_25];
$_SESSION['PrescrData']['DISPENSING_FEE_30'] = $_POST[DISPENSING_FEE_30];

$_SESSION['PrescrData']['RE_SPH_NUM']=$_POST[RE_SPH_NUM];
$_SESSION['PrescrData']['RE_SPH_DEC']=$_POST[RE_SPH_DEC];
$_SESSION['PrescrData']['RE_CYL_NUM']=$_POST[RE_CYL_NUM];
$_SESSION['PrescrData']['RE_CYL_DEC']=$_POST[RE_CYL_DEC];
$_SESSION['PrescrData']['RE_SPHERE']=$_POST[RE_SPH_NUM].$_POST[RE_SPH_DEC];
$_SESSION['PrescrData']['RE_CYL']=$_POST[RE_CYL_NUM].$_POST[RE_CYL_DEC];
$_SESSION['PrescrData']['RE_AXIS']=$_POST[RE_AXIS];
$_SESSION['PrescrData']['RE_ADD']=$_POST[RE_ADD];
$_SESSION['PrescrData']['lens_category']=$_POST[lens_category];
$_SESSION['PrescrData']['WARRANTY']=$_POST[WARRANTY];
$_SESSION['PrescrData']['RE_PR_AX']=$_POST[RE_PR_AX];
$_SESSION['PrescrData']['RE_PR_AX2']=$_POST[RE_PR_AX2];

$_SESSION['PrescrData']['RE_PR_IO']=$_POST[RE_PR_IO];
$_SESSION['PrescrData']['RE_PR_UD']=$_POST[RE_PR_UD];

if  ($_SESSION['PrescrData']['RE_PR_IO']=="None"){
$_SESSION['PrescrData']['RE_PR_AX']="";
}

if  ($_SESSION['PrescrData']['RE_PR_UD']=="None"){
$_SESSION['PrescrData']['RE_PR_AX2']="";
}

$_SESSION['PrescrData']['PT']=$_POST[PT];
$_SESSION['PrescrData']['PA']=$_POST[PA];
$_SESSION['PrescrData']['VERTEX']=$_POST[VERTEX];

$_SESSION['PrescrData']['RE_PD']=$_POST[RE_PD];
$_SESSION['PrescrData']['RE_PD_NEAR']=$_POST[RE_PD_NEAR];
$_SESSION['PrescrData']['RE_HEIGHT']=$_POST[RE_HEIGHT];

$_SESSION['PrescrData']['LE_SPHERE']=$_POST[LE_SPH_NUM].$_POST[LE_SPH_DEC];
$_SESSION['PrescrData']['LE_CYL']=$_POST[LE_CYL_NUM].$_POST[LE_CYL_DEC];
$_SESSION['PrescrData']['LE_SPH_NUM']=$_POST[LE_SPH_NUM];
$_SESSION['PrescrData']['LE_SPH_DEC']=$_POST[LE_SPH_DEC];
$_SESSION['PrescrData']['LE_CYL_NUM']=$_POST[LE_CYL_NUM];
$_SESSION['PrescrData']['LE_CYL_DEC']=$_POST[LE_CYL_DEC];
$_SESSION['PrescrData']['LE_AXIS']=$_POST[LE_AXIS];
$_SESSION['PrescrData']['LE_ADD']=$_POST[LE_ADD];
$_SESSION['PrescrData']['LE_PR_AX']=$_POST[LE_PR_AX];
$_SESSION['PrescrData']['LE_PR_AX2']=$_POST[LE_PR_AX2];
$_SESSION['PrescrData']['LE_PR_IO']=$_POST[LE_PR_IO];
$_SESSION['PrescrData']['LE_PR_UD']=$_POST[LE_PR_UD];

$_SESSION['PrescrData']['CUSHION']                     = $_POST[cushion];
$_SESSION['PrescrData']['CUSHION_SELLING_PRICE']       = $_POST[cushion_selling_price];

$_SESSION['PrescrData']['DUST_BAR']                    = $_POST[dust_bar];
$_SESSION['PrescrData']['DUST_BAR_SELLING_PRICE']      = $_POST[dust_bar_selling_price];

if  ($_SESSION['PrescrData']['LE_PR_IO']=="None"){
$_SESSION['PrescrData']['LE_PR_AX']="";
}

if  ($_SESSION['PrescrData']['LE_PR_UD']=="None"){
$_SESSION['PrescrData']['LE_PR_AX2']="";
}

$_SESSION['PrescrData']['LE_PD']=$_POST[LE_PD];
$_SESSION['PrescrData']['LE_PD_NEAR']=$_POST[LE_PD_NEAR];
$_SESSION['PrescrData']['LE_HEIGHT']=$_POST[LE_HEIGHT];

if ($_SESSION['PrescrData']['EYE']=="R.E."){
	$_SESSION['PrescrData']['LE_SPHERE']="–";
	$_SESSION['PrescrData']['LE_CYL']="–";
	$_SESSION['PrescrData']['LE_SPH_NUM']="–";
	$_SESSION['PrescrData']['LE_SPH_DEC']="–";
	$_SESSION['PrescrData']['LE_CYL_NUM']="–";
	$_SESSION['PrescrData']['LE_CYL_DEC']="–";
	$_SESSION['PrescrData']['LE_AXIS']="–";
	$_SESSION['PrescrData']['LE_ADD']="–";
	$_SESSION['PrescrData']['LE_PR_AX']="–";
	$_SESSION['PrescrData']['LE_PR_AX2']="–";
	$_SESSION['PrescrData']['LE_PR_IO']="";
	$_SESSION['PrescrData']['LE_PR_UD']="";
	$_SESSION['PrescrData']['LE_PD']="–";
	$_SESSION['PrescrData']['LE_PD_NEAR']="–";
	$_SESSION['PrescrData']['LE_HEIGHT']="–";

}

if ($_SESSION['PrescrData']['EYE']=="L.E."){
	$_SESSION['PrescrData']['RE_SPHERE']="–";
	$_SESSION['PrescrData']['RE_CYL']="–";
	$_SESSION['PrescrData']['RE_SPH_NUM']="–";
	$_SESSION['PrescrData']['RE_SPH_DEC']="–";
	$_SESSION['PrescrData']['RE_CYL_NUM']="–";
	$_SESSION['PrescrData']['RE_CYL_DEC']="–";
	$_SESSION['PrescrData']['RE_AXIS']="–";
	$_SESSION['PrescrData']['RE_ADD']="–";
	$_SESSION['PrescrData']['RE_PR_AX']="–";
	$_SESSION['PrescrData']['RE_PR_AX2']="–";
	$_SESSION['PrescrData']['RE_PR_IO']="";
	$_SESSION['PrescrData']['RE_PR_UD']="";
	$_SESSION['PrescrData']['RE_PD']="–";
	$_SESSION['PrescrData']['RE_PD_NEAR']="–";
	$_SESSION['PrescrData']['RE_HEIGHT']="–";

}

$_SESSION['PrescrData']['COATING']=$_POST[COATING];
$_SESSION['PrescrData']['INDEX']=$_POST[INDEX];
$_SESSION['PrescrData']['PHOTO']=$_POST[PHOTO];
$_SESSION['PrescrData']['POLAR']=$_POST[POLAR];

//FRAME DATA
$_SESSION['PrescrData']['FRAME_A']=$_POST[FRAME_A];
$_SESSION['PrescrData']['FRAME_B']=$_POST[FRAME_B];
$_SESSION['PrescrData']['FRAME_ED']=$_POST[FRAME_ED];
$_SESSION['PrescrData']['FRAME_DBL']=$_POST[FRAME_DBL];
$_SESSION['PrescrData']['FRAME_TYPE']=$_POST[FRAME_TYPE];

//GOES INTO extra product orders table

$_SESSION['PrescrData']['ENGRAVING']=$_POST[ENGRAVING];
$_SESSION['PrescrData']['TINT']=$_POST[TINT];
$_SESSION['PrescrData']['TINT_COLOR']=$_POST[TINT_COLOR];
$_SESSION['PrescrData']['FROM_PERC']=$_POST[FROM_PERC];
$_SESSION['PrescrData']['TO_PERC']=$_POST[TO_PERC];
$_SESSION['PrescrData']['JOB_TYPE']=$_POST[JOB_TYPE];
$_SESSION['PrescrData']['ORDER_TYPE']=$_POST[ORDER_TYPE];
$_SESSION['PrescrData']['SUPPLIER']=$_POST[SUPPLIER];
$_SESSION['PrescrData']['FRAME_MODEL']=$_POST[FRAME_MODEL];
$_SESSION['PrescrData']['COLOR']=$_POST[COLOR];
$_SESSION['PrescrData']['ORDER_TYPE']=$_POST[ORDER_TYPE];
$_SESSION['PrescrData']['TEMPLE']=$_POST[TEMPLE];
$_SESSION['PrescrData']['TEMPLE_MODEL']=$_POST[TEMPLE_MODEL];


//$_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'] =  explode("\n", $_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS']);
//preg_replace( "/\r|\n/", "", $_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'] );
    
if ($_POST[DIAMETER]!=""){$_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'].=" DIA: ".$_POST[DIAMETER]."mm";}

if ($_POST[top_urgent]=="TOP URGENT") {
$_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'] = " TOP URGENT*** " . $_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'];
$_SESSION['top_urgent'] = 'no';
}


//$Position = strpos($_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'], 'BASE'); 
	//if ($_POST[BASE_CURVE]<>"") {
	//	if (is_numeric($Position))
	//	{
	//	}else{
	//	$_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'] = 'Base Curve '.$_POST[BASE_CURVE] . "  " .  $_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'];
	//	}
	//}
	
if ($_SESSION['PrescrData']['CORRIDOR'] <> 'none'){
$Position        = strpos($_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'], 'CORRIDOR'); 
$LongeurCorridor = strlen($_SESSION['PrescrData']['CORRIDOR']);
$LongeurduCorridor = $LongeurCorridor - ($PositionUnderscore +1) ;
	if ($_POST[CORRIDOR]<>"") {
		if (is_numeric($Position))
		{
		}else{
		$PositionUnderscore = strpos($_SESSION['PrescrData']['CORRIDOR'], '_'); 	
		$_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'] = 'CORRIDOR :'. substr($_SESSION['PrescrData']['CORRIDOR'],$PositionUnderscore+1,$LongeurduCorridor) . "  " .  $_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'];
		}
	}
}
	
	
$Position2 = strpos($_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'], 'OPTICAL CENTER:'); 
	if ($_POST[OPTICAL_CENTER]<>"") {
		if (is_numeric($Position2))
		{
		}else{
		$_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'] = ' OPTICAL CENTER: '.$_POST[OPTICAL_CENTER] . "mm  " .  $_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'];
		}
	}	
	
	

$Position = strpos($_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'], 'NEAR WORKING DISTANCE'); 
	if ($_POST[nwd]<>"") {
		if (is_numeric($Position))
		{
		}else{
		$_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'] = 'NEAR WORKING DISTANCE:'.$_POST[nwd] . "cm    " .  $_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'];
		}
	}



$_SESSION['PrescrData']['INTERNAL_NOTE']=strtoupper($_POST[INTERNAL_NOTE]);
}

function applyCouponCode($pkey,$coupon_code){//APPLY COUPON CODE TO ORDER TABLE AND COUPON USE TABLE
	include "../sec_connectEDLL.inc.php";

	$CouponQuery="select * from coupon_codes WHERE code='$coupon_code'";
	//echo $CouponQuery. '<br>';
	$CouponResult=mysqli_query($con,$CouponQuery) or die ("Could not find codes");
	$CouponData=mysqli_fetch_array($CouponResult,MYSQLI_ASSOC);
	
	$OrderQuery="select primary_key,user_id from orders WHERE primary_key='$pkey'";
	//echo $OrderQuery. '<br>';
	$OrderResult=mysqli_query($con,$OrderQuery) or die ("Could not find codes");
	$OrderData=mysqli_fetch_array($OrderResult,MYSQLI_ASSOC);
	
	$query="UPDATE orders SET coupon_dsc='$CouponData[amount]' WHERE primary_key='$pkey'";
	//echo $query. '<br>';
	$result=mysqli_query($con,$query) or die ('Could not update because: ' . mysqli_error($con));
		
	$coupon_date=date("Y-m-d");
		
	$query="INSERT INTO coupon_use (user_id,code,amount,use_date,order_id) values ('$OrderData[user_id]','$coupon_code','$CouponData[amount]','$coupon_date','$OrderData[primary_key]')";
	//echo $query. '<br>';
	$result=mysqli_query($con,$query)		or die ('Could not update because: ' . mysqli_error($con));

}

function removeCouponCode($pkey){//APPLY COUPON CODE TO ORDER TABLE AND COUPON USE TABLE
	include "../sec_connectEDLL.inc.php";
	$query="UPDATE orders SET coupon_dsc='0.00' WHERE primary_key='$pkey'";
	$result=mysqli_query($con,$query) or die ('Could not update because: ' . mysqli_error($con));
		
	$query="delete from coupon_use where order_id='$pkey'";
	$result=mysqli_query($con,$query) or die ("Could not delete items");

}

function addOrderToAdditionalDiscounts($order_id,$buying_level){
	include "../sec_connectEDLL.inc.php";
	$bl_query="SELECT * from buying_levels WHERE buying_level='$buying_level'";//GET BUYING LEVEL DISCOUNT
	$bl_result=mysqli_query($con,$bl_query) or die  ('I cannot select bl items because: ' . mysqli_error($con).$bl_query);
	$bl_listItem=mysqli_fetch_array($bl_result,MYSQLI_ASSOC);
	$buying_level_dsc=$bl_listItem[amount];			
	$query="INSERT INTO additional_discounts (orders_id,buying_level_discount) values ('$order_id','$buying_level_dsc')";
	$result=mysqli_query($con,$query) or die ('Could not insert bl discount because: ' . mysqli_error($con));
}

?>