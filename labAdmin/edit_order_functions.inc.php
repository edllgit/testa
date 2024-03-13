<?php 

function updatePrescription($pkey,$product_id,$eye){//PRESCRIPTION ORDER UPDATE
include "../sec_connectEDLL.inc.php";

$queryVerifierOrigine 	= "SELECT order_from FROM orders WHERE primary_key=$pkey";
$ResultVerifierOrigine	= mysqli_query($con,$queryVerifierOrigine)	or die  ('I cannot select items because b2: ' . mysqli_error($con));
$DataVerifierOrigine	= mysqli_fetch_array($ResultVerifierOrigine,MYSQLI_ASSOC);

if ($DataVerifierOrigine[order_from]=='ifcclubca')
	$tabletoUse = 'ifc_ca_exclusive';
elseif ($DataVerifierOrigine[order_from]=='safety')
	$tabletoUse = 'safety_exclusive';
else 
	$tabletoUse = 'exclusive';

	$prodQuery="SELECT * from $tabletoUse WHERE primary_key=$product_id";//GET ORDER INFO
	$prodResult=mysqli_query($con,$prodQuery)	or die  ('I cannot select items because a1: ' . mysqli_error($con));
	$prodItem=mysqli_fetch_array($prodResult,MYSQLI_ASSOC);

	$order_patient_first=$_POST[order_patient_first];
	$order_patient_last=$_POST[order_patient_last];
	$patient_ref_num=$_POST[patient_ref_num];
	$salesperson_id=$_POST[salesperson_id];
	$order_quantity=$_POST[order_quantity];
	$optical_center=$_POST[optical_center];
	$frame_a=$_POST[frame_a];
	$frame_b=$_POST[frame_b];
	$frame_ed=$_POST[frame_ed];
	$frame_dbl=$_POST[frame_dbl];
	$frame_type=$_POST[frame_type];
	$internal_note=$_POST[INTERNAL_NOTE];
	$tray_num=$_POST[tray_num];
	$re_sphere=$_POST[re_sphere];
	$re_cyl=$_POST[re_cyl];
	$re_axis=$_POST[re_axis];
	$re_add=$_POST[re_add];
	$re_pr_ax=$_POST[re_pr_ax];
	$re_pr_ax2=$_POST[re_pr_ax2];
	$re_pr_io=$_POST[RE_PR_IO];
	$re_pr_ud=$_POST[RE_PR_UD];
	$re_pd=$_POST[re_pd];
	$re_pd_near=$_POST[re_pd_near];
	$re_height=$_POST[re_height];
	
	$le_sphere=$_POST[le_sphere];
	$le_cyl=$_POST[le_cyl];
	$le_axis=$_POST[le_axis];
	$le_add=$_POST[le_add];
	$le_pr_ax=$_POST[le_pr_ax];
	$le_pr_ax2=$_POST[le_pr_ax2];
	$le_pr_io=$_POST[LE_PR_IO];
	$le_pr_ud=$_POST[LE_PR_UD];
	$le_pd=$_POST[le_pd];
	$le_pd_near=$_POST[le_pd_near];
	$le_height=$_POST[le_height];
	$re_ct=$_POST[RE_CT];
	$le_ct=$_POST[LE_CT];
	$re_et=$_POST[RE_ET];
	$le_et=$_POST[LE_ET];
	
	$base_curve=$_POST[base_curve];
	
	$PT=$_POST[PT];
	$PA=$_POST[PA];
	$vertex=$_POST[vertex];
	
	$special_instructions=$_POST[special_instructions];
	$sphere_min=$prodItem[sphere_min];//CALCULATE OVER RANGE FEE
	$sphere_max=$prodItem[sphere_max];
	$cyl_min=$prodItem[cyl_min];
	

$Update="yes";


$query="UPDATE orders SET ";
$query.="order_patient_first='$order_patient_first',";
$query.="optical_center='$optical_center',";
$query.="order_patient_last='$order_patient_last',";
$query.="patient_ref_num='$patient_ref_num',";
$query.="salesperson_id='$salesperson_id',";
$query.="order_quantity='$order_quantity',";
$query.="frame_a='$frame_a',";
$query.="frame_b='$frame_b',";
$query.="frame_ed='$frame_ed',";
$query.="frame_dbl='$frame_dbl',";
$query.="frame_type='$frame_type',";
$query.="tray_num='$tray_num',";
$query.="re_sphere='$re_sphere',";
$query.="re_cyl='$re_cyl',";
$query.="re_axis='$re_axis',";
$query.="re_add='$re_add',";
$query.="re_pr_ax='$re_pr_ax',";
$query.="re_pr_ax2='$re_pr_ax2',";
$query.="re_pr_io='$re_pr_io',";
$query.="re_pr_ud='$re_pr_ud',";
$query.="re_pd='$re_pd',";
$query.="re_pd_near='$re_pd_near',";
$query.="re_height='$re_height',";
$query.="base_curve='$base_curve',";


$query.="le_sphere='$le_sphere',";
$query.="le_cyl='$le_cyl',";
$query.="le_axis='$le_axis',";
$query.="le_add='$le_add',";
$query.="le_pr_ax='$le_pr_ax',";
$query.="le_pr_ax2='$le_pr_ax2',";
$query.="le_pr_io='$le_pr_io',";
$query.="le_pr_ud='$le_pr_ud',";
$query.="le_pd='$le_pd',";
$query.="le_pd_near='$le_pd_near',";
$query.="le_height='$le_height',";
$query.="re_ct='$re_ct',";
$query.="le_ct='$le_ct',";
$query.="re_et='$re_et',";
$query.="le_et='$le_et',";
$query.="PT='$PT',";
$query.="PA='$PA',";
$query.="vertex='$vertex',";

$query.="special_instructions='$special_instructions',";
$query.="internal_note='$internal_note'";
//$query.="extra_product='$extra_product',";
//$query.="extra_product_price='$extra_product_price'";

//$query.=",order_over_range_fee='$over_range'";

$query.=" WHERE primary_key=$pkey";	 

//echo '<br><br>Query:' . $query;
//exit();

//Validate employee password
$RedoPassword 			= $_POST[redo_password];
$queryRedoAccess  	    = "SELECT * FROM access_redo WHERE password='$RedoPassword'";
$resultRedoAccess 	    = mysqli_query($con,$queryRedoAccess)	or die ( "Query failed: " . mysqli_error($con));	
$NbrResultatRedoAccess  = mysqli_num_rows($resultRedoAccess);

if (($Update=="yes") && ($NbrResultatRedoAccess == 1)){
	$DataRedoAccess =  mysqli_fetch_array($resultRedoAccess,MYSQLI_ASSOC);
	//SAUVEGARDER LA MISE A JOUR
	$queryOrderNum    = "SELECT order_num FROM ORDERS where primary_key =  $pkey";
	$resultOrderNum   = mysqli_query($con,$queryOrderNum)	or die ( "Query failed: " . mysqli_error($con));	
	$DataOrderNum     = mysqli_fetch_array($resultOrderNum,MYSQLI_ASSOC);
	$OrderNumber      = $DataOrderNum[order_num];
	$todayDate 		  = date("Y-m-d g:i a");// current date
	$currentTime 	  = time($todayDate); //Change date into time
	$timeAfterOneHour = $currentTime-((60*60)*4);
	$datecomplete	  = date("Y-m-d H:i:s",$timeAfterOneHour);
	$ip 		  	  = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
	$update_ip2   	  = $_SERVER['HTTP_X_FORWARDED_FOR'];
	$queryHistory     = "SELECT Count(*) as NbrEdition FROM status_history WHERE order_num = $OrderNumber AND  order_status like '%Order Edited%'";
	$ResultHistory    = mysqli_query($con,$queryHistory)	or die  ('I cannot select items because c3: ' . mysqli_error($con));
	$DataHistory      = mysqli_fetch_array($ResultHistory,MYSQLI_ASSOC);
	$IterationEdition = $DataHistory[NbrEdition] + 1;
	//eNREGISTRER DANS L'HISTORIQUE DE STATUS 
	$queryHistory = "INSERT INTO status_history (order_num,     update_time,     update_type, order_status,    update_ip,    update_ip2,     redo_approved_by)
										VALUES ($OrderNumber, '$datecomplete',   'manual',    'Order Edited #$IterationEdition',   '$ip',       '$update_ip2',  '$DataRedoAccess[name]')";
	$result=mysqli_query($con,$queryHistory)	or die ( "Query failed: " . mysqli_error($con));	
	//FAIRE LA MISE A JOUR		
	$result = mysqli_query($con,$query)		or die ( "Query failed: " . mysqli_error($con) );
}elseif($NbrResultatRedoAccess == 0){
	echo '<div align="center" class="alert alert-danger" role="alert"><strong>Error: Employee Password is invalid</strong></div>';	
}
		
		
		
$Query="SELECT * from orders WHERE primary_key='$pkey'";//GET ORDER INFO
$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because d4: ' . mysqli_error($con));
$Item=mysqli_fetch_array($Result,MYSQLI_ASSOC);

 
updateExtraProducts($Item[order_num],$frame_a, $frame_b,$frame_ed,$frame_dbl,$frame_type);
		
$gTotal=calculateTotal($Item[order_num]);
addOrderTotal($Item[order_num],$gTotal);	

}

function updateExtraProducts($order_num,$ep_frame_a,$ep_frame_b,$ep_frame_ed,$ep_frame_dbl,$ep_frame_type){
include "../sec_connectEDLL.inc.php";	
$Query="SELECT * from extra_product_orders WHERE order_num='$order_num'";//GET ORDER INFO
$Result=mysqli_query($con,$Query)	or die  ('I cannot select items because fe4: ' . mysqli_error($con));
$ep_orders_count=mysqli_num_rows($Result);

if ($ep_orders_count!= 0){
	
	while ($epItem=mysqli_fetch_array($Result,MYSQLI_ASSOC)){
		$category=$epItem[category];
		$ep_order_id=$epItem[ep_order_id];
		
		
		if ($category=="Edging"){
			$updateQuery="UPDATE extra_product_orders SET frame_type='$ep_frame_type',ep_frame_a='$ep_frame_a',ep_frame_b='$ep_frame_b',ep_frame_ed='$ep_frame_ed',ep_frame_dbl='$ep_frame_dbl' WHERE ep_order_id='$ep_order_id'";
			//echo '<br>'. $updateQuery;
			
			$result=mysqli_query($con,$updateQuery)	or die ( "update failed: " . mysqli_error($con));
		}//END IF CATEGORY
				
		
		if ($category=="Frame"){
			$updateQuery="UPDATE extra_product_orders SET frame_type='$ep_frame_type',ep_frame_a='$ep_frame_a',ep_frame_b='$ep_frame_b',ep_frame_ed='$ep_frame_ed',ep_frame_dbl='$ep_frame_dbl' WHERE ep_order_id='$ep_order_id'";
			
			$result=mysqli_query($con,$updateQuery)	or die ( "update failed: " . mysqli_error($con));
		}//END IF CATEGORY
		
		
	}//END WHILE
		
}//END IF ORDER COUNT
	
}//END FUNCTION

function updateStockBulkOrder($pkey){//STOCK BULK UPDATE
include "../sec_connectEDLL.inc.php";

$order_product_name=$_POST[PRODUCT];
$sphere=$_POST[SPHERE];
$cylinder=$_POST[CYLINDER];

$order_quantity=$_POST[quantity];

$orderQuery="SELECT * FROM orders WHERE primary_key='$pkey'";//GET ORDER INFO
$orderResult=mysqli_query($con,$orderQuery)or die  ('I cannot select items because e5: ' . mysqli_error($con));
$orderItem=mysqli_fetch_array($orderResult,MYSQLI_ASSOC);

$accountQuery="SELECT * from accounts WHERE user_id='$orderItem[user_id]'";//GET ACCOUNT INFO
$accountResult=mysqli_query($con,$accountQuery) or die  ('I cannot select items because f6: ' . mysqli_error($con));
$accountItem=mysqli_fetch_array($accountResult,MYSQLI_ASSOC);

$bgQuery="SELECT * from buying_groups WHERE primary_key='$accountItem[buying_group]'";//GET BUYING GROUP INFO
$bgResult=mysqli_query($con,$bgQuery)	or die  ('I cannot select items because g7: ' . mysqli_error($con));
$bgItem=mysqli_fetch_array($bgResult,MYSQLI_ASSOC);

$productQuery="SELECT * from products,prices WHERE products.product_name='$order_product_name' AND sph_base='$sphere' AND cyl_add='$cylinder' AND prices.product_name='$order_product_name' ";//GET PRODUCT INFO
$productResult=mysqli_query($con,$productQuery) or die  ('I cannot select items because h8: ' . mysqli_error($con));
$listItem=mysqli_fetch_array($productResult,MYSQLI_ASSOC);

if ($orderItem[currency]=="US"){
	$order_product_price=$listItem[price];}
else if ($orderItem[currency]=="CA"){
	$order_product_price=$listItem[price_can];}
else if ($orderItem[currency]=="EUR"){
	$order_product_price=$listItem[price_eur];}
	
$discountQuery="SELECT discount from stock_discounts WHERE user_id='$orderItem[user_id]' AND product_name='$order_product_name'";
//GET PRODUCT DISCOUNT IF ANY
$discountResult=mysqli_query($con,$discountQuery)	or die  ('I cannot select items because i10: ' . mysqli_error($con));
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

$re_sphere=$listItem[sph_base]; 
$le_sphere=$listItem[sph_base]; 

$re_cyl=$listItem[cyl_add];
$le_cyl=$listItem[cyl_add];  

$query="UPDATE orders SET ";
 
$query.="order_quantity='$order_quantity',";
$query.="order_product_name='$order_product_name',";
$query.="order_product_id='$listItem[primary_key]',";
$query.="order_product_index='$order_product_index',";
$query.="order_product_material='$order_product_material',";
$query.="order_product_price='$order_product_price',";
$query.="order_product_discount='$order_product_discount',";
$query.="order_product_coating='$order_product_coating',";
$query.="re_sphere='$re_sphere',";
$query.="le_sphere='$le_sphere',";
$query.="re_cyl='$re_cyl',";
$query.="le_cyl='$le_cyl'";

$query.=" WHERE primary_key=$pkey";
			 
$result=mysqli_query($con,$query) or die ( "Query failed: " . mysqli_error($con));
		
$gTotal=calculateTotal($orderItem[order_num]);
addOrderTotal($orderItem[order_num],$gTotal);	
		
return ($orderItem[order_num]);
}

function updateStockTrayOrder($pkey,$eye){//STOCK TRAY UPDATE
include "../sec_connectEDLL.inc.php";
if ($eye=="RE"){
	$order_product_name=$_POST[PRODUCT];
	$sphere=$_POST[SPHERE];
	$cylinder=$_POST[CYLINDER];}
else if ($eye=="LE"){
	$order_product_name=$_POST[PRODUCT2];
	$sphere=$_POST[SPHERE2];
	$cylinder=$_POST[CYLINDER2];}

$tray_num=$_POST[tray_num];

$orderQuery="SELECT * FROM orders WHERE primary_key='$pkey'";//GET ORDER INFO
$orderResult=mysqli_query($con,$orderQuery) or die  ('I cannot select items because k12: ' . mysqli_error($con));
$orderItem=mysqli_fetch_array($orderResult,MYSQLI_ASSOC);

$accountQuery="SELECT * FROM accounts WHERE user_id='$orderItem[user_id]'";//GET ACCOUNT INFO
$accountResult=mysqli_query($con,$accountQuery) or die  ('I cannot select items because: ' . mysqli_error($con));
$accountItem=mysqli_fetch_array($accountResult,MYSQLI_ASSOC);

$bgQuery="SELECT * FROM buying_groups WHERE primary_key='$accountItem[buying_group]'";//GET BUYING GROUP INFO
$bgResult=mysqli_query($con,$bgQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$bgItem=mysqli_fetch_array($bgResult,MYSQLI_ASSOC);

$productQuery="SELECT * FROM products,prices WHERE products.product_name='$order_product_name' AND sph_base='$sphere' AND cyl_add='$cylinder' AND prices.product_name='$order_product_name' ";//GET PRODUCT INFO
$productResult=mysqli_query($con,$productQuery) or die  ('I cannot select items because: ' . mysqli_error($con));
$listItem=mysqli_fetch_array($productResult,MYSQLI_ASSOC);

if ($orderItem[currency]=="US"){
	$order_product_price=$listItem[price];}
else if ($orderItem[currency]=="CA"){
	$order_product_price=$listItem[price_can];}
else if ($orderItem[currency]=="EUR"){
	$order_product_price=$listItem[price_eur];}
	
$discountQuery="SELECT discount from stock_discounts WHERE user_id='$orderItem[user_id]' AND product_name='$order_product_name'";
//GET PRODUCT DISCOUNT IF ANY
$discountResult=mysqli_query($con,$discountQuery) or die  ('I cannot select items because: ' . mysqli_error($con));
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

$re_sphere=$listItem[sph_base]; 
$le_sphere=$listItem[sph_base]; 

$re_cyl=$listItem[cyl_add];
$le_cyl=$listItem[cyl_add];  

$query="UPDATE orders SET ";
 
$query.="tray_num='$tray_num',";
$query.="order_product_name='$order_product_name',";
$query.="order_product_id='$listItem[primary_key]',";
$query.="order_product_index='$order_product_index',";
$query.="order_product_material='$order_product_material',";
$query.="order_product_price='$order_product_price',";
$query.="order_product_discount='$order_product_discount',";
$query.="order_product_coating='$order_product_coating',";
$query.="re_sphere='$re_sphere',";
$query.="le_sphere='$le_sphere',";
$query.="re_cyl='$re_cyl',";
$query.="le_cyl='$le_cyl'";

$query.=" WHERE primary_key=$pkey";
			 
$result=mysqli_query($con,$query) or die ( "Query failed: " . mysqli_error($con) );
		
$gTotal=calculateTotal($orderItem[order_num]);
addOrderTotal($orderItem[order_num],$gTotal);	
		
return ($orderItem[order_num]);
}

function updateShippingCost($item_order_num){
include "../sec_connectEDLL.inc.php";
	$query="SELECT ship_chg_stock FROM orders,labs WHERE orders.order_num='$item_order_num' AND orders.lab=labs.primary_key";//GET SHIPPING COSTS
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$labItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
				
	$totalShipping=$labItem[ship_chg_stock];
	
	$updateQuery="UPDATE orders SET order_shipping_cost='$totalShipping',order_shipping_method='Stock Shipping' WHERE order_num='$item_order_num'";//UPDATE SHIPPING COST FOR ALL WITH ORDER NUMBER
		
	$updateResult=mysqli_query($con,$updateQuery) or die  ('I cannot select items because: ' . mysqli_error($con));
	
}//END FUNCTION

?>
