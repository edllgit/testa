<?php 

echo '<br>passe';
function deleteOrderItem($pkey){
	$query="delete from orders where primary_key='$pkey'";//DELETE orders from ORDERS table
	$result=mysql_query($query)
		or die ("Could not delete product");
		
	$query="delete from extra_product_orders where order_id='$pkey'";//DELETE linked items from EXTRA_PRODUCT_ORDERS table
	$result=mysql_query($query)
		or die ("Could not delete product");
		
	$query="delete from additional_discounts where orders_id='$pkey'";//DELETE entry in additional discounts table
	$result=mysql_query($query)
		or die ("Could not delete additional discount entry");

}

function deleteTrayOrderItem($tray_num){
	$query="delete from orders where tray_num='$tray_num' and order_num =-1  and order_status='basket' and tray_num <> ''";
	//echo '<br>Query non executer : '.  $query;
	$result=mysql_query($query) 		or die ("Could not delete items");

}


function deleteFrameTrayOrderItem($tray_num){
	
	$query="delete from orders where tray_num='$tray_num'  and order_num =-1 and order_status='basket' and tray_num <> ''";
	//echo '<br>Query execute : '.  $query;
	$result=mysql_query($query)		or die ("Could not delete items");
}



function addStockTrayItem($product_id,$quantity,$eye,$tray_ref){//ADD STOCK TRAY ITEM TO ORDERS TABLE

$query="(select * from products,prices WHERE products.primary_key='$product_id' AND products.product_name=prices.product_name)";
$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
		
$listItem=mysql_fetch_array($result);

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
$discountResult=mysql_query($discountQuery)
	or die  ('I cannot select items because: ' . mysql_error());
$discountItem=mysql_fetch_array($discountResult);


$MainLabQuery="SELECT main_lab from accounts WHERE user_id='$user_id'";
$MainLabResult=mysql_query($MainLabQuery)	or die  ('I cannot select items because: ' . mysql_error());
$MainLabData=mysql_fetch_array($MainLabResult);

if ($MainLabData[main_lab] == 47){//47 = AIT = made by Somo 
$PrescriptLab = 52;
}else{
$PrescriptLab = 3;
}

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
 
$query.="(user_id	,order_num,order_item_number,tray_num,eye,order_date_processed,order_item_date,order_quantity,order_patient_first,order_patient_last,salesperson_id,order_product_name,	order_product_id,order_product_index,order_product_material,order_product_price,order_product_discount,order_product_type,order_product_coating,order_product_photo,order_product_polar,order_status,re_sphere	,le_sphere,re_cyl,le_cyl,re_add,le_add,re_axis,le_axis,re_pr_ax,le_pr_ax,re_pd,re_pd_near,re_height,le_pd,le_pd_near,le_height,frame_a,frame_b,frame_ed,frame_dbl,frame_type,currency,global_dsc,infocus_dsc,precision_dsc,innovative_dsc,visionpro_dsc,visionpropoly_dsc,generation_dsc,truehd_dsc,prescript_lab, lab) values (";



$user_id=$_SESSION["sessionUser_Id"];
$query.="'$user_id',";

$order_num="-1"; 
$query.="'$order_num',";

$order_item_number=addslashes($_SESSION['ITEM_NUM']);
$query.="'$order_item_number',";

$query.="'$tray_ref',";

$query.="'$eye',";

$order_date_processed="0000-00-00"; 
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

$query.="'$PrescriptLab',";


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
	
$MainLabQuery="SELECT main_lab from accounts WHERE user_id='$user_id'";
$MainLabResult=mysql_query($MainLabQuery)	or die  ('I cannot select items because: ' . mysql_error());
$MainLabData=mysql_fetch_array($MainLabResult);

if ($MainLabData[main_lab] == 47){//47 = AIT = made by Somo 
$PrescriptLab = 52;
}else{
$PrescriptLab = 3;
}
	
$discountQuery="SELECT discount from stock_discounts WHERE user_id='$user_id' AND product_name='$order_product_name'";
//GET PRODUCT DISCOUNT IF ANY
$discountResult=mysql_query($discountQuery)	or die  ('I cannot select items because: ' . mysql_error());
$discountItem=mysql_fetch_array($discountResult);
//TEST CHARLES 2013-04-26
//$discountItem[discount]=0;//Hard code 5% rebate on bulk order//Remis a 0 le 2013-09-12

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
 
$query.="(user_id	,order_num,order_item_number,order_date_processed,order_item_date,order_quantity,order_patient_first,order_patient_last,salesperson_id,order_product_name,	order_product_id,order_product_index,order_product_material,order_product_price,order_product_discount,order_product_type,order_product_coating,order_product_photo,order_product_polar,order_status,re_sphere	,le_sphere,re_cyl,le_cyl,re_add,le_add,re_axis,le_axis,re_pr_ax,le_pr_ax,re_pd,re_pd_near,re_height,le_pd,le_pd_near,le_height,frame_a,frame_b,frame_ed,frame_dbl,frame_type,currency,global_dsc,infocus_dsc,precision_dsc,innovative_dsc,visionpro_dsc,visionpropoly_dsc,generation_dsc,truehd_dsc, prescript_lab, lab) values (";

$user_id=$_SESSION["sessionUser_Id"];
$query.="'$user_id',";

$order_num="-1"; 
$query.="'$order_num',";

$order_item_number=addslashes($_SESSION['ITEM_NUM']);
$query.="'$order_item_number',";

$order_date_processed="0000-00-00"; 
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

$query.="'$PrescriptLab',";

$lab=$_SESSION["sessionUserData"]["main_lab"];
$query.="'$lab')";


//echo '<br>Query: ' . $query . '<br>';

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


$query="(select * from exclusive WHERE primary_key='$product_id')";
$result=mysql_query($query)		or die  ('I cannot select items because: ' . mysql_error());	
$listItem=mysql_fetch_array($result);


$queryUserId="SELECT product_line,oneyear_type,oneyear_dt,oneyear_ar_credit,oneyear_ar_credit_used  FROM ACCOUNTS WHERE user_id = '". $_SESSION["sessionUser_Id"]. "'";
$resultUserId=mysql_query($queryUserId)		or die  ('I cannot select items because: ' . mysql_error());
$DataUserId=mysql_fetch_array($resultUserId);

	 
	 
		 

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
			
			
			if (($_SESSION['PrescrData']['WARRANTY']== 'gold') &&  ($_SESSION['PrescrData']['EYE']=="Both")){
			$order_product_price = $order_product_price + 20;
			}
			
			if (($_SESSION['PrescrData']['WARRANTY']== 'gold') &&  ($_SESSION['PrescrData']['EYE']=="L.E.")){
			$order_product_price = $order_product_price + 10;
			}
			
			if (($_SESSION['PrescrData']['WARRANTY']== 'gold') &&  ($_SESSION['PrescrData']['EYE']=="R.E.")){
			$order_product_price = $order_product_price + 10;
			}
			
			

	
	//$global_dsc=$_SESSION["sessionUserData"]["global_dsc"]/100;
	//$order_product_discount=$order_product_price-($order_product_price*$global_dsc);
	
	$lab_id=$_SESSION["sessionUserData"]["main_lab"];//GET Presciprtion Lab
	$labQuery="select * from labs where primary_key ='$lab_id'";
	$labResult=mysql_query($labQuery)
		or die ("Could not find account");
	$labItem = mysql_fetch_array($labResult);
	
	$order_product_discount=$order_product_price;
	
	if ($listItem[collection]=="Precision"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["precision_dsc"]/100);
		$prescript_lab=$labItem[precision_vp_lab];
	}
	
	if ($listItem[collection]=="Innovation"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["innovation_dsc"]/100);
		$prescript_lab=$labItem[innovation_lab];	
	}
	
	if ($listItem[collection]=="Universal 2"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["universal_2_dsc"]/100);
		$prescript_lab=$labItem[universal_2_lab];	
	}
	
	if ($listItem[collection]=="Universal 2 174"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["universal_2_174_dsc"]/100);
		$prescript_lab=$labItem[universal_2_174_lab];	
	}
	
	if ($listItem[collection]=="Versano AIT"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["versano_ait_dsc"]/100);
		$prescript_lab=$labItem[versano_ait_lab];	
	}
	
	if ($listItem[collection]=="Avner"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["avner_dsc"]/100);
		$prescript_lab=$labItem[avner_lab];	
	}
	
	if ($listItem[collection]=="Versano"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["versano_dsc"]/100);
		$prescript_lab=$labItem[versano_lab];	
	}
	
	if ($listItem[collection]=="Ifree Goyette"){
	$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["ifree_goyette_dsc"]/100);
	$prescript_lab=$labItem[ifree_goyette_lab];	
	}
	
	if ($listItem[collection]=="ER Swiss"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["er_swiss_dsc"]/100);
		$prescript_lab=$labItem[er_swiss_lab];	
	}
	
	if ($listItem[collection]=="ER CSC"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["er_csc_dsc"]/100);
		$prescript_lab=$labItem[er_csc_lab];	
	}
	
	if ($listItem[collection]=="ER Versano"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["er_versano_dsc"]/100);
		$prescript_lab=$labItem[er_versano_lab];	
	}
	
	if ($listItem[collection]=="ER Crystal"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["er_crystal_dsc"]/100);
		$prescript_lab=$labItem[er_crystal_lab];	
	}
	
	if ($listItem[collection]=="ER HKO"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["er_hko_dsc"]/100);
		$prescript_lab=$labItem[er_hko_lab];	
	}
	
	if ($listItem[collection]=="ER TR"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["er_tr_dsc"]/100);
		$prescript_lab=$labItem[er_tr_lab];	
	}
	
	if ($listItem[collection]=="ER STC"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["er_stc_dsc"]/100);
		$prescript_lab=$labItem[er_stc_lab];	
	}
	
	
	if ($listItem[collection]=="STC Extra Charges"){
		$prescript_lab=3;
	}
	
	if ($listItem[collection]=="Entrepot DL"){
		$prescript_lab=$labItem[entrepot_dl_lab];	
	}
	
	if ($listItem[collection]=="NURBS sunglasses"){
		$prescript_lab=25;	
	}
	

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
	
	if ($listItem[collection]=="Eco Visionease"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eco_visionease_dsc"]/100);
		$prescript_lab=$labItem[eco_visionease_lab];
	}
	
	if ($listItem[collection]=="Easy Fit HD"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["easy_fit_dsc"]/100);
		$prescript_lab=$labItem[easy_fit_lab];
	}
	
	if ($listItem[collection]=="Other"){
		$prescript_lab=$labItem[other_lab];
	}
	
	if ($listItem[collection]=="STC DL"){
		$prescript_lab=$labItem[stc_dl_lab];
	}
	
	if ($listItem[collection]=="CSC DL"){
		$prescript_lab=$labItem[csc_dl_lab];
	}
	
	if ($listItem[collection]=="VOT DL"){
		$prescript_lab=$labItem[vot_dl_lab];
	}
	
	if ($listItem[collection]=="Vision Eco"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["visioneco_dsc"]/100);
		$prescript_lab=$labItem[visioneco_lab];
	}
	
	if ($listItem[collection]=="Eco AIT"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eco_AIT_dsc"]/100);
		$prescript_lab=$labItem[eco_AIT_lab];
	}
	
	if ($listItem[collection]=="Eagle Extra Charges"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eagle_extra_charges_dsc"]/100);
		$prescript_lab=$labItem[eagle_extra_charges_lab];
	}
	
	if ($listItem[collection]=="Optimize IFC"){
		$prescript_lab=57;
	}
	
	
	if ($listItem[collection]=="Revolution"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["revolution_dsc"]/100);
		$prescript_lab=$labItem[revolution_lab];
	}
	
	if ($listItem[collection]=="FF BY IOT"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["ff_by_iot_dsc"]/100);
		$prescript_lab=$labItem[ff_by_iot_lab];
	}
	
	if ($listItem[collection]=="Goyette Swiss"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["goyette_swiss_dsc"]/100);
		$prescript_lab=$labItem[goyette_swiss_lab];
	}
	
	if ($listItem[collection]=="Goyette Crystal"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["goyette_crystal_dsc"]/100);
		$prescript_lab=$labItem[goyette_crystal_lab];
	}
	
	if ($listItem[collection]=="Horizon"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["horizon_dsc"]/100);
		$prescript_lab=$labItem[horizon_lab];
	}
	
	
	if ($listItem[collection]=="Fit"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["fit_dsc"]/100);
		$prescript_lab=$labItem[fit_lab];
	}
	
	
	if ($listItem[collection]=="Optimize"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["optimize_dsc"]/100);
		$prescript_lab=$labItem[optimize_lab];
	}
	
	if ($listItem[collection]=="Optimize 2"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["optimize_2_dsc"]/100);
		$prescript_lab=$labItem[optimize_2_lab];
	}
	
	if ($listItem[collection]=="Optimize 3"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["optimize_3_dsc"]/100);
		$prescript_lab=$labItem[optimize_3_lab];
	}
	
	if ($listItem[collection]=="Optimize 4"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["optimize_4_dsc"]/100);
		$prescript_lab=$labItem[optimize_4_lab];
	}
	
	if ($listItem[collection]=="Inf 3d"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["inf_3d_dsc"]/100);
		$prescript_lab=$labItem[inf_3d_lab];
	}
	
	
	if ($listItem[collection]=="Crystal"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["crystal_dsc"]/100);
		$prescript_lab=$labItem[crystal_lab];
	}
	
	if ($listItem[collection]=="DL Somo"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["dl_somo_dsc"]/100);
		$prescript_lab=$labItem[dl_somo_lab];
	}
	
	if ($listItem[collection]=="Eyelation US"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eyelation_us_dsc"]/100);
		$prescript_lab=$labItem[eyelation_us_lab];
	}
	
	
	
	if ($listItem[collection]=="Eyelation STC"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eyelation_stc_dsc"]/100);
		$prescript_lab=$labItem[eyelation_stc_lab];
	}
	
	
	
	if ($listItem[collection]=="Eyelation US HKO"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eyelation_us_hko_dsc"]/100);
		$prescript_lab=$labItem[eyelation_us_hko_lab];
	}
	
	if ($listItem[collection]=="HD Premier Choix"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["hd_premier_choix_dsc"]/100);
		$prescript_lab=$labItem[hd_premier_choix_lab];
	}
	
	
	if ($listItem[collection]=="Essilor 2"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["essilor_2_dsc"]/100);
		$prescript_lab=$labItem[essilor_2_lab];
	}
	
	
	if ($listItem[collection]=="Private Hko"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["private_hko_dsc"]/100);
		$prescript_lab=$labItem[private_hko_lab];
	}
	
	
	
	if ($listItem[collection]=="Divers"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["divers_dsc"]/100);
		$prescript_lab=$labItem[divers_lab];
	}
	
	
	if ($listItem[collection]=="Axial Grm"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["axial_grm_dsc"]/100);
		$prescript_lab=$labItem[axial_grm_lab];
	}
	
	if ($listItem[collection]=="Axial Mini somo"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["axial_mini_somo"]/100);
		$prescript_lab=$labItem[axial_mini_somo_lab];
	}
	
	if ($listItem[collection]=="Axial Mini hko"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["axial_mini_hko_dsc"]/100);
		$prescript_lab=$labItem[axial_mini_hko_lab];
	}
	
	
	if ($listItem[collection]=="ODM Swiss"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["odm_swiss_dsc"]/100);
		$prescript_lab=$labItem[odm_swiss_lab];
	}
	
	if ($listItem[collection]=="ODM Crystal"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["odm_crystal_dsc"]/100);
		$prescript_lab=$labItem[odm_crystal_lab];
	}
	
	if ($listItem[collection]=="ODM HKO"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["odm_hko_dsc"]/100);
		$prescript_lab=$labItem[odm_hko_lab];
	}
	
	
	if ($listItem[collection]=="Axial Mini somo"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["axial_mini_somo_dsc"]/100);
		$prescript_lab=$labItem[axial_mini_somo_lab];
	}
	
	
	
	if ($listItem[collection]=="Generation Grm"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["generation_grm_dsc"]/100);
		$prescript_lab=$labItem[generation_grm_lab];
	}

	
	
	if ($listItem[collection]=="Image"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["image_dsc"]/100);
		$prescript_lab=$labItem[image_lab];
	}
	
	if ($listItem[collection]=="Divers"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["divers_dsc"]/100);
		$prescript_lab=$labItem[divers_lab];
	}
	
		
	if ($listItem[collection]=="Identity"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["identity_dsc"]/100);
		$prescript_lab=$labItem[identity_lab];
	}
		
	
	if ($listItem[collection]=="Eco Eagle"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eco_eagle_dsc"]/100);
		$prescript_lab=$labItem[eco_eagle_lab];
	}
		
	
	
	if ($listItem[collection]=="Verres"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["verres_dsc"]/100);
		$prescript_lab=$labItem[verres_lab];
	}
	
	
	
	if ($listItem[collection]=="Private Collection"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["private_collection_dsc"]/100);
		$prescript_lab=$labItem[private_collection_lab];
	}
	
	if ($listItem[collection]=="Ovation"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["ovation_dsc"]/100);
		$prescript_lab=$labItem[ovation_lab];
	}
	
	
	
	
	if ($listItem[collection]=="Innovation FF 159"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["innovation_ff_159_dsc"]/100);
		$prescript_lab=$labItem[innovation_ff_159_lab];
	}
	
	
	if ($listItem[collection]=="Innovation FF HD 159"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["innovation_ff_hd_159_dsc"]/100);
		$prescript_lab=$labItem[innovation_ff_hd_159_lab];
	}
	
	
	if ($listItem[collection]=="Younger Prog"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["younger_prog_dsc"]/100);
		$prescript_lab=$labItem[younger_lab];
	}
	
	if ($listItem[collection]=="Nesp"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["nesp_dsc"]/100);
		$prescript_lab=$labItem[nesp_lab];
	}
	
	if ($listItem[collection]=="Private AIT 1"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["private_ait_1_dsc"]/100);
		$prescript_lab=$labItem[private_ait_1_lab];
	}
	
	if ($listItem[collection]=="Private AIT 2"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["private_ait_2_dsc"]/100);
		$prescript_lab=$labItem[private_ait_2_lab];
	}
	
	
	if ($listItem[collection]=="Nesp 2"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["nesp_2_dsc"]/100);
		$prescript_lab=$labItem[nesp_2_lab];
	}
	
	if ($listItem[collection]=="Az2ph2"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["az2ph2_dsc"]/100);
		$prescript_lab=$labItem[az2ph2_lab];
	}
	
	
	
	if ($listItem[collection]=="Private Grm 1"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["private_grm_1_dsc"]/100);
		$prescript_lab=$labItem[private_grm_1_lab];
	}
	
	
	
	if ($listItem[collection]=="Innovative Plus"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["innovative_plus_dsc"]/100);
		$prescript_lab=$labItem[innovative_plus_lab];
	}
	
	
	
	if ($listItem[collection]=="Selection Rx"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["selection_rx_dsc"]/100);
		$prescript_lab=$labItem[selection_rx_lab];
	}
	
	
	
	if ($listItem[collection]=="Private Grm 2"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["private_grm_2_dsc"]/100);
		$prescript_lab=$labItem[private_grm_2_lab];
	}
	
	
	if ($listItem[collection]=="Private Grm 3"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["private_grm_3_dsc"]/100);
		$prescript_lab=$labItem[private_grm_3_lab];
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
	
	if ($listItem[collection]=="Eco 10"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eco_10_dsc"]/100);
		$prescript_lab=$labItem[eco_10_lab];
	}
	
	
	//LNC 2017
	if ($listItem[collection]=="Innovative 1"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["innovative_1_dsc"]/100);
		$prescript_lab=$labItem[innovative_1_lab];
	}
	
	if ($listItem[collection]=="Innovative 2"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["innovative_1_dsc"]/100);
		$prescript_lab=$labItem[innovative_1_lab];
	}
	
	if ($listItem[collection]=="Innovative 3"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["innovative_1_dsc"]/100);
		$prescript_lab=$labItem[innovative_1_lab];
	}
	
	if ($listItem[collection]=="LNC GKB"){
		$prescript_lab = 69;//GKB
	}
	
	if ($listItem[collection]=="LNC STC"){
		$prescript_lab = 3;//Saint-Catharines
	}
	
	if ($listItem[collection]=="LNC HKO"){
		$prescript_lab = 25;//HKO
	}
	
	if ($listItem[collection]=="LNC SWISS"){
		$prescript_lab = 10;//Swiss
	}
	
	
	
	
	
	
	if ($listItem[collection]=="Innovative SVFT"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["innovative_svft_dsc"]/100);
		$prescript_lab=69;
	}
	
	if ($listItem[collection]=="Innovative SVFT HKO"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["innovative_svft_hko_dsc"]/100);
		$prescript_lab=25;
	}
	
	if ($listItem[collection]=="Innovative SVFT STC"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["innovative_svft_stc_dsc"]/100);
		$prescript_lab=3;
	}
	
	
	
	if ($listItem[collection]=="Eco Essilor"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eco_essilor_dsc"]/100);
		$prescript_lab=$labItem[eco_essilor_lab];
	}
	
	
	if ($listItem[collection]=="Eco Conant"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eco_conant_dsc"]/100);
		$prescript_lab=$labItem[eco_conant_lab];
	}
	
	
	if ($listItem[collection]=="Eco OR"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["eco_or_dsc"]/100);
		$prescript_lab=$labItem[eco_or_lab];
	}
	
	
	
	if ($listItem[collection]=="Den 1"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["den_1_dsc"]/100);
		$prescript_lab=$labItem[den_1_lab];
	}
	
	if ($listItem[collection]=="Den 2"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["den_2_dsc"]/100);
		$prescript_lab=$labItem[den_2_lab];
	}
	
	if ($listItem[collection]=="Den 3"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["den_3_dsc"]/100);
		$prescript_lab=$labItem[den_3_lab];
	}
	
	if ($listItem[collection]=="Den 4"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["den_4_dsc"]/100);
		$prescript_lab=$labItem[den_4_lab];
	}
	
	if ($listItem[collection]=="Den 5"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["den_5_dsc"]/100);
		$prescript_lab=$labItem[den_5_lab];
	}
	
	
	if ($listItem[collection]=="Bbg 1"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["bbg_1_dsc"]/100);
		$prescript_lab=$labItem[bbg_1_lab];
	}

	
	if ($listItem[collection]=="Bbg 2"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["bbg_2_dsc"]/100);
		$prescript_lab=$labItem[bbg_2_lab];
	}
	
	if ($listItem[collection]=="Bbg 3"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["bbg_3_dsc"]/100);
		$prescript_lab=$labItem[bbg_3_lab];
	}
	
	if ($listItem[collection]=="Bbg 4"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["bbg_4_dsc"]/100);
		$prescript_lab=$labItem[bbg_4_lab];
	}
	
	if ($listItem[collection]=="Bbg 5"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["bbg_5_dsc"]/100);
		$prescript_lab=$labItem[bbg_5_lab];
	}
	
	
	if ($listItem[collection]=="Bbg 6"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["bbg_6_dsc"]/100);
		$prescript_lab=$labItem[bbg_6_lab];
	}
	
	
	if ($listItem[collection]=="Bbg 7"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["bbg_7_dsc"]/100);
		$prescript_lab=$labItem[bbg_7_lab];
	}
	
	
		if ($listItem[collection]=="Bbg 8"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["bbg_8_dsc"]/100);
		$prescript_lab=$labItem[bbg_8_lab];
	}
	
	
	if ($listItem[collection]=="Bbg 9"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["bbg_9_dsc"]/100);
		$prescript_lab=$labItem[bbg_9_lab];
	}
	
	if ($listItem[collection]=="Bbg 10"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["bbg_10_dsc"]/100);
		$prescript_lab=$labItem[bbg_10_lab];
	}
	
	if ($listItem[collection]=="Bbg 11"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["bbg_11_dsc"]/100);
		$prescript_lab=$labItem[bbg_11_lab];
	}
	
	if ($listItem[collection]=="Bbg 12"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["bbg_12_dsc"]/100);
		$prescript_lab=$labItem[bbg_12_lab];
	}

	if ($listItem[collection]=="IFC Simple"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["ifc_simple_dsc"]/100);
		$prescript_lab=$labItem[ifc_simple_lab];
	}
	

	if ($listItem[collection]=="IFC US Express Eagle"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["ifc_us_express_eagle_dsc"]/100);
		$prescript_lab=$labItem[ifc_us_express_eagle_lab];
	}
	
	if ($listItem[collection]=="IFC US Younger Eagle"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["ifc_us_younger_eagle_dsc"]/100);
		$prescript_lab=$labItem[ifc_us_younger_eagle_lab];
	}
		
	
	
	if ($listItem[collection]=="Svision"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["svision_dsc"]/100);
		$prescript_lab=$labItem[svision_lab];
	}
	
	if ($listItem[collection]=="Svision 2"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["svision_2_dsc"]/100);
		$prescript_lab=$labItem[svision_2_lab];
	}
	
	if ($listItem[collection]=="Svision 3"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["svision_3_dsc"]/100);
		$prescript_lab=$labItem[svision_3_lab];
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
	
	if ($listItem[collection]=="Innovation FF HD 2"){
		$order_product_discount=$order_product_discount-($order_product_price*$_SESSION["sessionUserData"]["innovation_ff_hd_2_dsc"]/100);
		$prescript_lab=$labItem[innovation_ff_hd_2_lab];
	}
	
	


$order_product_coating=$listItem[coating]; 
$order_product_photo=$listItem[photo]; 
$order_product_polar=$listItem[polar]; 
$order_product_index=$listItem[index_v];



if ($DataUserId[product_line]=='aitlensclub')
{
$_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'] = $_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'];//;. '  FDA mandatory ' ;
}


$query="insert into orders ";
 
$query.="(entry_fee, rush, ip, order_from ,user_id ,warranty, order_num,order_item_number, eye,order_date_processed, order_item_date,order_quantity, order_patient_first,order_patient_last,patient_ref_num, tray_num,salesperson_id,order_product_name,	order_product_id,order_product_index,order_product_price,order_product_discount,order_over_range_fee,order_product_type,order_product_coating,order_product_photo,order_product_polar,order_status,re_sphere	,le_sphere,re_cyl,le_cyl,re_add,le_add,re_axis,le_axis,re_pr_ax,le_pr_ax,re_pr_ax2,le_pr_ax2,re_pr_io,re_pr_ud,le_pr_io,le_pr_ud,re_pd,re_pd_near,re_height,le_pd,le_pd_near,le_height,PT,PA,vertex,frame_a,frame_b,frame_ed,frame_dbl,frame_type,currency,special_instructions,global_dsc,private_1_dsc,private_2_dsc, private_3_dsc, private_4_dsc, private_5_dsc, eco_1_dsc, eco_2_dsc, eco_3_dsc, eco_4_dsc, eco_5_dsc, eco_6_dsc,bbg_1_dsc, bbg_2_dsc, bbg_3_dsc, bbg_4_dsc, bbg_5_dsc, bbg_6_dsc, bbg_7_dsc, bbg_8_dsc, bbg_9_dsc, bbg_10_dsc, bbg_11_dsc ,bbg_12_dsc, svision_dsc, svision_2_dsc,  infocus_dsc,precision_dsc,innovative_dsc,visionpro_dsc,visionpropoly_dsc,visioneco_dsc,generation_dsc,truehd_dsc,easy_fit_dsc,glass_dsc, glass_2_dsc, glass_3_dsc, vot_dsc, younger_prog_dsc, rodenstock_dsc,rodenstock_hd_dsc, innovation_dsc, innovation_ff_dsc,innovation_ds_dsc, innovation_ii_ds_dsc, az2ph2_dsc, optimize_3_dsc, prescript_lab, base_curve, RE_CT,LE_CT,RE_ET,LE_ET, internal_note, myupload, shape_name_bk, lab) values (";

$entry_fee =$_SESSION['PrescrData']['entry_fee'];
if ($entry_fee <> ''){
$entry_fee = 2;
$order_product_price = $order_product_price+2;
$order_product_discount = $order_product_discount+2;
}
$query.="'$entry_fee',";

$rush=  $_SESSION['PrescrData']['rush']; 
$query.="'$rush',";

$ip= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$query.="'$ip',";

$query.="'$DataUserId[product_line]',";

$user_id=$_SESSION["sessionUser_Id"];
$query.="'$user_id',";

$warranty=$_SESSION['PrescrData']['WARRANTY']; 
$query.="'$warranty',";


$order_num="-1"; 
$query.="'$order_num',";

$order_item_number=addslashes($_SESSION['ITEM_NUM']);
$query.="'$order_item_number',";

$eye=addslashes($_SESSION['PrescrData']['EYE']);
$query.="'$eye',";

$order_date_processed="0000-00-00"; 
$query.="'$order_date_processed',";

$order_item_date=date("Y-m-d");
$query.="'$order_item_date',";

$order_quantity=$quantity;
$query.="'$order_quantity',";

$order_patient_first=addslashes($_SESSION['PrescrData']['FIRST_NAME']);
$query.="'$order_patient_first',";

$order_patient_last=addslashes($_SESSION['PrescrData']['LAST_NAME']);
$query.="'$order_patient_last',";

$order_patient_last=addslashes($_SESSION['PrescrData']['PATIENT_REF_NUM']);
$query.="'$order_patient_last',";

$TRAY_NUM=addslashes($_SESSION['PrescrData']['TRAY_NUM']);
$query.="'$TRAY_NUM',";


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

$vot_dsc=$_SESSION["sessionUserData"]["younger_prog_dsc"];
$query.="'$younger_prog_dsc',";

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


$az2ph2_dsc=$_SESSION["sessionUserData"]["az2ph2_dsc"];
$query.="'$az2ph2_dsc',";

$optimize_3_dsc=$_SESSION["sessionUserData"]["optimize_3_dsc"];
$query.="'$optimize_3_dsc',";

$query.="'$prescript_lab',";


$base_curve=$_SESSION["PrescrData"]["base_curve"];
$query.="'$base_curve',";

$RE_CT=$_SESSION["PrescrData"]["RE_CT"];
$query.="'$RE_CT',";

$LE_CT=$_SESSION["PrescrData"]["LE_CT"];
$query.="'$LE_CT',";

$RE_ET=$_SESSION["PrescrData"]["RE_ET"];
$query.="'$RE_ET',";


$LE_ET=$_SESSION["PrescrData"]["LE_ET"];
$query.="'$LE_ET',";



$internal_note=$_SESSION["PrescrData"]["INTERNAL_NOTE"];
$query.="'$internal_note',";

///////////////////////////////////////////////////////////////// uploader

$myupload=$_SESSION['PrescrData']['myupload'];
$query.="'$myupload',";

$myupload=$_SESSION['PrescrData']['myupload'];
$query.="'$myupload',";

$lab=$_SESSION["sessionUserData"]["main_lab"];
$query.="'$lab')";

//echo $query;
//exit();

$result=mysql_query($query)
		or die ( "Query failed: " . mysql_error() . "<br/>" . $query );
	

	$next_increment = 0;//GET THE PRIMARY KEY OF THE JUST CREATED PRODUCT
	$query="SHOW TABLE STATUS LIKE 'orders'";
	$Result=mysql_query($query) or die ( "Query failed: " . mysql_error() . "<br/>" . $query );

	$row = mysql_fetch_array($Result);
	$next_increment=$row['Auto_increment'];

	$pkey=$next_increment-1;
	
	
	
	
$oneyear_type 			 = $DataUserId['oneyear_type']; // Promotion selected by the customer
$oneyear_dt				 = $DataUserId['oneyear_dt']; // Date until when the promo is active
$oneyear_ar_credit		 = $DataUserId['oneyear_ar_credit'];//Number of credits remaining
$oneyear_ar_credit_used	 = $DataUserId['oneyear_ar_credit_used']; //Cummulate the numbers of credits that are used



	switch ($listItem[coating]) {
		case "DH1":
    	$ApplyArRebate="no";
    	break;
		
		case "DH2":
   		$ApplyArRebate="no";
   		break;
		 
		case "Hard Coat":
   		$ApplyArRebate="no";
   		 break;
		 
		default :
  		$ApplyArRebate="yes";
  		break;
		 }
		 
if ($ApplyArRebate=='yes')//The product bought qualify to the AR rebate
{
	
		if ($oneyear_dt	 <> '0000-00-00') //Verify if there is a date until when the promo is active
		{
	
		$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
		$datecomplete = date("Y-m-d", $today);
		
			
				if ($oneyear_dt >=$datecomplete)
				{
				
					if ($oneyear_ar_credit > 0)
					{
					
					
						$availableForPromo2ndPair = 'no';
						//We need to check if the product is available for the 2nd pair at 1$ promotion by checking the index and the collection
						if (($listItem[collection] =='Innovation FF') && ($order_product_index == '1.50') && ($ApplyArRebate=='yes')){
						$availableForPromo2ndPair = 'yes';
						}
						
						if (($listItem[collection] =='Innovation DS') && ($order_product_index == '1.50') && ($ApplyArRebate=='yes')){
						$availableForPromo2ndPair = 'yes';
						}
						
						if (($listItem[collection] =='Innovation II DS') && ($order_product_index == '1.50') && ($ApplyArRebate=='yes')){
						$availableForPromo2ndPair = 'yes';
						}
						
						if (($listItem[collection] =='Innovation FF HD') && ($order_product_index == '1.50') && ($ApplyArRebate=='yes')){
						$availableForPromo2ndPair = 'yes';
						}
						
						if (($listItem[collection] =='Den 4') && ($order_product_index == '1.50') && ($ApplyArRebate=='yes')){
						$availableForPromo2ndPair = 'yes';
						}
						
						if (($listItem[collection] =='Bbg 3') && ($order_product_index == '1.50') && ($ApplyArRebate=='yes')){
						$availableForPromo2ndPair = 'yes';
						}
						
						if (($listItem[collection] =='Bbg 8') && ($order_product_index == '1.50') && ($ApplyArRebate=='yes')){
						$availableForPromo2ndPair = 'yes';
						}
						
						if (($listItem[collection] =='Innovative Plus') && ($order_product_index == '1.50') && ($ApplyArRebate=='yes')){
						$availableForPromo2ndPair = 'yes';
						}
						
						if (($listItem[collection] =='Innovation FF 159') && ($order_product_index == '1.59') && ($ApplyArRebate=='yes')){
						$availableForPromo2ndPair = 'yes';
						}
						
						if (($listItem[collection] =='Innovation FF HD 159') && ($order_product_index == '1.59') && ($ApplyArRebate=='yes')){
						$availableForPromo2ndPair = 'yes';
						}
					
					
							//If this product is available for promo 2nd pair, we do not apply the coupon code for AR
							if ($availableForPromo2ndPair == 'no'){
							//All the validations are done, We apply the rebate
							applyCouponCode($pkey,'PromoAR54218');
							
							//We substract 1 rebate from the remaining
							$ArRemaining = $oneyear_ar_credit-1;
							$queryUpdateRebate = "UPDATE ACCOUNTS SET oneyear_ar_credit = ". $ArRemaining . " WHERE user_id = '" . $_SESSION["sessionUser_Id"] . "'";
							$result=mysql_query($queryUpdateRebate)		or die ( "Query failed: " . mysql_error() . "<br/>" . $queryUpdateRebate );
							
							//We add 1 to the credits counter
							$ArUsed = $oneyear_ar_credit_used+1;
							$queryUpdateCounter = "UPDATE ACCOUNTS SET oneyear_ar_credit_used = ". $ArUsed . " WHERE user_id = '" . $_SESSION["sessionUser_Id"] . "'";
							$resultCounter=mysql_query($queryUpdateCounter)		or die ( "Query failed: " . mysql_error() . "<br/>" . $queryUpdateCounter );
							}//End if availableforPromo2ndPair
							
					}//End if $oneyear_ar_credit > 0
				
				}//end if $oneyear_dt >=$datecomplete
			
				
		}//End if Oneyear_dt not empty
	

}	
	
	
	

//////////////////////////////////////////////////////////////// assign an id num to the basket
	if($_SESSION['PrescrData']['mybasketid']){
		//echo "session available<br>";
		$mybasketid = $_SESSION['PrescrData']['mybasketid'];
		$result1=mysql_query("INSERT INTO upload_basket (order_id) VALUES ('$pkey');")
		or die ( "Query failed: " . mysql_error() . "<br/>");
		
	} else {
	//	echo "session not available<br>";
		$result2=mysql_query("INSERT INTO upload_basket (order_id) VALUES ('$pkey');")
		or die ( "Query failed: " . mysql_error() . "<br/>");
		
	$next_increment2 = 0;//GET THE PRIMARY KEY OF THE JUST CREATED PRODUCT
	$query2="SHOW TABLE STATUS LIKE 'upload_basket'";
	$Result2=mysql_query($query2) or die ( "Query failed: " . mysql_error() . "<br/>" . $query2 );

	$row2 = mysql_fetch_array($Result2);
	$next_increment2=$row2['Auto_increment'];

	$pkey2=$next_increment2-1;
			
	$mybasketid = $pkey2;
	$_SESSION['PrescrData']['mybasketid'] = $mybasketid;
	}
//		echo "basket :".$mybasketid;
//		echo "pkey :".$pkey;
		$result3=mysql_query("UPDATE upload_basket SET basket_id = '$mybasketid' WHERE order_id = '$pkey';")
		or die ( "Query failed: " . mysql_error() . "<br/>");

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

$_SESSION['PrescrData']['EDGE_POLISH']=$_POST[EDGE_POLISH];
$_SESSION['PrescrData']['LENS_CATEGORY']=$_POST[lens_category];
$_SESSION['PrescrData']['base_curve']=$_POST[base_curve];
$_SESSION['PrescrData']['entry_fee']=$_POST[entry_fee];	
	
$_SESSION['PrescrData']['RE_CT'] = $_POST[RE_CT];	
$_SESSION['PrescrData']['LE_CT'] = $_POST[LE_CT];	
$_SESSION['PrescrData']['RE_ET'] = $_POST[RE_ET];	
$_SESSION['PrescrData']['LE_ET'] = $_POST[LE_ET];	
	
$_SESSION['PrescrData']['rush']=$_POST[rush];	
	
$_SESSION['PrescrData']['EYE']=$_POST[EYE];

$_SESSION['PrescrData']['TRAY_NUM']=$_POST[TRAY_NUM];

$_SESSION['PrescrData']['LAST_NAME']=$_POST[LAST_NAME];
$_SESSION['PrescrData']['FIRST_NAME']=$_POST[FIRST_NAME];
$_SESSION['PrescrData']['PATIENT_REF_NUM']=strtoupper($_POST[PATIENT_REF_NUM]);
$_SESSION['PrescrData']['SALESPERSON_ID']=$_POST[SALESPERSON_ID];

$_SESSION['PrescrData']['RE_SPH_NUM']=$_POST[RE_SPH_NUM];
$_SESSION['PrescrData']['RE_SPH_DEC']=$_POST[RE_SPH_DEC];
$_SESSION['PrescrData']['RE_CYL_NUM']=$_POST[RE_CYL_NUM];
$_SESSION['PrescrData']['RE_CYL_DEC']=$_POST[RE_CYL_DEC];
$_SESSION['PrescrData']['RE_SPHERE']=$_POST[RE_SPH_NUM].$_POST[RE_SPH_DEC];
$_SESSION['PrescrData']['RE_CYL']=$_POST[RE_CYL_NUM].$_POST[RE_CYL_DEC];
$_SESSION['PrescrData']['RE_AXIS']=$_POST[RE_AXIS];
$_SESSION['PrescrData']['RE_ADD']=$_POST[RE_ADD];

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

$user_id=$_SESSION["sessionUser_Id"];
$queryProductLine = "SELECT product_line from accounts WHERE user_id = '" . $user_id  . "'";
$ResultProductline=mysql_query($queryProductLine)		or die ("Could not find codes");
$DataProductLine=mysql_fetch_array($ResultProductline);


$_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS']=mysql_real_escape_string(strtoupper($_POST[SPECIAL_INSTRUCTIONS]));


//$Position = strpos($_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'], 'BASE'); 
	//if ($_POST[base_curve]<>"") {
	//	if (is_numeric($Position))
	//	{
	//	}else{
	//	$_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'] = 'Base Curve '.$_POST[base_curve] . " " .  $_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'];
	//	}
		
	/*$_SESSION['PrescrData']['INTERNAL_NOTE']=mysql_real_escape_string(strtoupper($_POST[INTERNAL_NOTE]));*/
	//}


}

function applyCouponCode($pkey,$coupon_code){//APPLY COUPON CODE TO ORDER TABLE AND COUPON USE TABLE

	$CouponQuery="select * from coupon_codes WHERE code='$coupon_code'";
	$CouponResult=mysql_query($CouponQuery)
		or die ("Could not find codes");
	$CouponData=mysql_fetch_array($CouponResult);
	
	$OrderQuery="select primary_key,user_id, eye, order_product_price from orders WHERE primary_key='$pkey'";
	$OrderResult=mysql_query($OrderQuery)
			or die ("Could not find codes");
	$OrderData=mysql_fetch_array($OrderResult);
	
	if ($OrderData[eye] <> 'Both'){//Order contains only one eye
	$CouponData[amount] = $CouponData[amount]/2;
	}
	
	$query="UPDATE orders SET coupon_dsc='$CouponData[amount]' WHERE primary_key='$pkey'";
	$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		
	$coupon_date=date("Y-m-d");
		
	$query="INSERT INTO coupon_use (user_id,code,amount,use_date,order_id) values ('$OrderData[user_id]','$coupon_code','$CouponData[amount]','$coupon_date','$OrderData[primary_key]')";
	$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());

}

function removeCouponCode($pkey){//APPLY COUPON CODE TO ORDER TABLE AND COUPON USE TABLE
	
	$query="UPDATE orders SET coupon_dsc='0.00' WHERE primary_key='$pkey'";
	$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		
	$query="delete from coupon_use where order_id='$pkey'";
	$result=mysql_query($query)
		or die ("Could not delete items");

}

function addOrderToAdditionalDiscounts($order_id,$buying_level){
	
	$bl_query="SELECT * from buying_levels WHERE buying_level='$buying_level'";//GET BUYING LEVEL DISCOUNT
	$bl_result=mysql_query($bl_query)
				or die  ('I cannot select bl items because: ' . mysql_error().$bl_query);
	$bl_listItem=mysql_fetch_array($bl_result);
	$buying_level_dsc=$bl_listItem[amount];
				
		$query="INSERT INTO additional_discounts (orders_id,buying_level_discount) values ('$order_id','$buying_level_dsc')";
	$result=mysql_query($query)		or die ('Could not insert bl discount because: ' . mysql_error());
}





function catchOrderDataFastOrder (){

$_SESSION['PrescrData']['LENS_CATEGORY']=$_POST[lens_category];
$_SESSION['PrescrData']['base_curve']=$_POST[base_curve];
$_SESSION['PrescrData']['entry_fee']=$_POST[entry_fee];		
$_SESSION['PrescrData']['RE_CT'] = $_POST[RE_CT];	
$_SESSION['PrescrData']['LE_CT'] = $_POST[LE_CT];	
$_SESSION['PrescrData']['RE_ET'] = $_POST[RE_ET];	
$_SESSION['PrescrData']['LE_ET'] = $_POST[LE_ET];		
$_SESSION['PrescrData']['rush']=$_POST[rush];	
$_SESSION['PrescrData']['EYE']=$_POST[EYE];
$_SESSION['PrescrData']['TRAY_NUM']=$_POST[TRAY_NUM];
$_SESSION['PrescrData']['LAST_NAME']=$_POST[LAST_NAME];
$_SESSION['PrescrData']['FIRST_NAME']=$_POST[FIRST_NAME];
$_SESSION['PrescrData']['PATIENT_REF_NUM']=strtoupper($_POST[PATIENT_REF_NUM]);
$_SESSION['PrescrData']['SALESPERSON_ID']=$_POST[SALESPERSON_ID];

//In case the customer input with comma instead of dot
$_POST[RE_SPH] = str_replace(',','.',$_POST[RE_SPH]);
$_POST[LE_SPH] = str_replace(',','.',$_POST[LE_SPH]);
$_POST[RE_CYL] = str_replace(',','.',$_POST[RE_CYL]);
$_POST[LE_CYL] = str_replace(',','.',$_POST[LE_CYL]);

$_SESSION['PrescrData']['RE_SPHERE'] = $_POST[RE_SPH];
$PositionPointRE = strpos($_SESSION['PrescrData']['RE_SPHERE'],'.');
$_SESSION['PrescrData']['RE_SPH_NUM'] = substr($_SESSION['PrescrData']['RE_SPHERE'],0,$PositionPointRE);
$_SESSION['PrescrData']['RE_SPH_DEC'] = substr($_SESSION['PrescrData']['RE_SPHERE'],$PositionPointRE,3);

$_SESSION['PrescrData']['LE_SPHERE'] = $_POST[LE_SPH];
$PositionPointLE = strpos($_SESSION['PrescrData']['LE_SPHERE'],'.');
$_SESSION['PrescrData']['LE_SPH_NUM'] = substr($_SESSION['PrescrData']['LE_SPHERE'],0,$PositionPointLE);
$_SESSION['PrescrData']['LE_SPH_DEC'] = substr($_SESSION['PrescrData']['LE_SPHERE'],$PositionPointLE,3);

$_SESSION['PrescrData']['RE_CYL']     = $_POST[RE_CYL];
$PositionPointRECYL = strpos($_SESSION['PrescrData']['RE_CYL'],'.');
$_SESSION['PrescrData']['RE_CYL_NUM'] = substr($_SESSION['PrescrData']['RE_CYL'],0,$PositionPointRECYL);
$_SESSION['PrescrData']['RE_CYL_DEC'] = substr($_SESSION['PrescrData']['RE_CYL'],$PositionPointRECYL,3);

$_SESSION['PrescrData']['LE_CYL']     = $_POST[LE_CYL];
$PositionPointLECYL = strpos($_SESSION['PrescrData']['LE_CYL'],'.');
$_SESSION['PrescrData']['LE_CYL_NUM'] = substr($_SESSION['PrescrData']['LE_CYL'],0,$PositionPointLECYL);
$_SESSION['PrescrData']['LE_CYL_DEC'] = substr($_SESSION['PrescrData']['LE_CYL'],$PositionPointLECYL,3);

$_SESSION['PrescrData']['RE_AXIS']   = $_POST[RE_AXIS];
$_SESSION['PrescrData']['RE_ADD']    = $_POST[RE_ADD];

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

/*$_SESSION['PrescrData']['LE_SPH_NUM'] = $_POST[LE_SPH_NUM];
$_SESSION['PrescrData']['LE_SPH_DEC'] = $_POST[LE_SPH_DEC];
$_SESSION['PrescrData']['LE_CYL_NUM']=$_POST[LE_CYL_NUM];
$_SESSION['PrescrData']['LE_CYL_DEC']=$_POST[LE_CYL_DEC];*/
$_SESSION['PrescrData']['LE_SPHERE']  = $_POST[LE_SPH];
$_SESSION['PrescrData']['LE_CYL']     = $_POST[LE_CYL];
$_SESSION['PrescrData']['LE_AXIS']=$_POST[LE_AXIS];
$_SESSION['PrescrData']['LE_ADD']=$_POST[LE_ADD];
$_SESSION['PrescrData']['LE_PR_AX']=$_POST[LE_PR_AX];
$_SESSION['PrescrData']['LE_PR_AX2']=$_POST[LE_PR_AX2];
$_SESSION['PrescrData']['LE_PR_IO']=$_POST[LE_PR_IO];
$_SESSION['PrescrData']['LE_PR_UD']=$_POST[LE_PR_UD];

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

$user_id=$_SESSION["sessionUser_Id"];
$queryProductLine = "SELECT product_line from accounts WHERE user_id = '" . $user_id  . "'";
$ResultProductline=mysql_query($queryProductLine)		or die ("Could not find codes");
$DataProductLine=mysql_fetch_array($ResultProductline);


$_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS']=mysql_real_escape_string(strtoupper($_POST[SPECIAL_INSTRUCTIONS]));


//$Position = strpos($_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'], 'BASE'); 
//	if ($_POST[base_curve]<>"") {
	//	if (is_numeric($Position))
	//	{
	//	}else{
	//	$_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'] = 'Base Curve '.$_POST[base_curve] . " " .  $_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'];
	//	}
		
//	}

}

?>
