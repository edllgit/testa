<?php

function export_order($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)
		or die  ('I cannot select items because: ' . mysql_error());

	while ($orderItem=mysql_fetch_array($Result)){

		switch($orderItem["order_status"]){
						case 'cancelled':				$order_status = "Cancelled";			break;
						case 'processing':				$order_status = "Confirmed";			break;
						case 'on hold':				    $order_status = "On Hold";				break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'job started':				$order_status = "Surfacing";			break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 're-do':					$order_status = "Redo";					break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for lens':		$order_status = "Waiting for Lens";		break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;	
						case 'scanned shape to swiss':	$order_status = "Scanned shape to Swiss";break;
						case 'waiting for frame store':	$order_status = "Waiting for Frame Store";	break;
						case 'waiting for frame ho/supplier':$order_status = "Waiting for Frame Head Office/Supplier";	break;
		}
		
		
	
		
		
		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$accItem=mysql_fetch_array($accResult);
			
		$accNum=$accItem[account_num];
		
		$labQuery="select lab_name from labs WHERE primary_key='$orderItem[lab]'"; //Get Main Lab Name
		$labResult=mysql_query($labQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$labItem=mysql_fetch_array($labResult);
			
		$PlabQuery="select lab_name from labs WHERE primary_key='$orderItem[prescript_lab]'"; //Get Prescription Lab Name
		$PlabResult=mysql_query($PlabQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$PlabItem=mysql_fetch_array($PlabResult);
			
		$special_instructions=addslashes($orderItem["special_instructions"]);
		$extra_product=addslashes($orderItem["extra_product"]);
		
		$ProdQuery="select product_code,color_code from exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		$ProdResult=mysql_query($ProdQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$ProdItem=mysql_fetch_array($ProdResult);
			
			$color_code=$ProdItem[color_code];
			$product_code=$ProdItem[product_code];
			
		$ShipQuery="select shipping_code from accounts WHERE user_id='$orderItem[user_id]'"; //Get SHIPPING CODE
		$ShipResult=mysql_query($ShipQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$ShipItem=mysql_fetch_array($ShipResult);
			
			$shipping_code=$ShipItem[shipping_code];
			
			
			$EngrQuery="select engraving from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Engraving'"; //Get ENGRAVING
		$EngrResult=mysql_query($EngrQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EngrItem=mysql_fetch_array($EngrResult);
			$usercount=mysql_num_rows($EngrResult);
			if ($usercount!=0){
				$engraving=$EngrItem[engraving];}
			else{
			$engraving="";}
			
			$TintQuery="select tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Tint'"; //Get TINT
		$TintResult=mysql_query($TintQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$TintItem=mysql_fetch_array($TintResult);
			$usercount=mysql_num_rows($TintResult);
			if ($usercount!=0){
				$tint=$TintItem[tint];
				$tint_color=$TintItem[tint_color];
				$from_perc=$TintItem[from_perc];
				$to_perc=$TintItem[to_perc];}
			else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";}
				
		$EdgeQuery="select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Edging'"; //Get EDGING
		$EdgeResult=mysql_query($EdgeQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EdgeItem=mysql_fetch_array($EdgeResult);
			$usercount=mysql_num_rows($EdgeResult);
			if ($usercount!=0){
				$frame_type=$EdgeItem[frame_type];
				$job_type=$EdgeItem[job_type];
				$supplier=$EdgeItem[supplier];
				$model=$EdgeItem[model];
				$color=$EdgeItem[color];
				$order_type=$EdgeItem[order_type];
				$eye_size=$EdgeItem[eye_size];
				$bridge=$EdgeItem[bridge];
				$temple=$EdgeItem[temple];
				}
			else{
				$frame_type="";
				$job_type="";
				$supplier="";
				$model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
	
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		//$orderItem["order_patient_first"]="xxx";//XXX out certain fields
		//$orderItem["order_patient_last"]="xxx";
		//$orderItem["order_product_price"]="xxx";
		//$orderItem["order_product_discount"]="xxx";
		//$orderItem["coupon_dsc"]="xxx";
		//$orderItem["order_total"]="xxx";
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
		$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'.$orderItem["patient_ref_num"].'","'.$accNum.'"'.chr(13);
			}
			
	return $outputstring;
			
}















function export_re_billing($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)
		or die  ('I cannot select items because: ' . mysql_error());

	while ($orderItem=mysql_fetch_array($Result)){

		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "Surfacing";			break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Redo";					break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";		    break;
		}
		
		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$accItem=mysql_fetch_array($accResult);
			
		$accNum=$accItem[account_num];
		
		$labQuery="select lab_name from labs WHERE primary_key='$orderItem[lab]'"; //Get Main Lab Name
		$labResult=mysql_query($labQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$labItem=mysql_fetch_array($labResult);
			
		$PlabQuery="select lab_name from labs WHERE primary_key='$orderItem[prescript_lab]'"; //Get Prescription Lab Name
		$PlabResult=mysql_query($PlabQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$PlabItem=mysql_fetch_array($PlabResult);
			
		$special_instructions=addslashes($orderItem["special_instructions"]);
		$extra_product=addslashes($orderItem["extra_product"]);
		
		$ProdQuery="select product_code,color_code from exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		$ProdResult=mysql_query($ProdQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$ProdItem=mysql_fetch_array($ProdResult);
			
			$color_code=$ProdItem[color_code];
			$product_code=$ProdItem[product_code];
			
		$ShipQuery="select shipping_code from accounts WHERE user_id='$orderItem[user_id]'"; //Get SHIPPING CODE
		$ShipResult=mysql_query($ShipQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$ShipItem=mysql_fetch_array($ShipResult);
			
			$shipping_code=$ShipItem[shipping_code];
			
			
			$EngrQuery="select engraving from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Engraving'"; //Get ENGRAVING
		$EngrResult=mysql_query($EngrQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EngrItem=mysql_fetch_array($EngrResult);
			$usercount=mysql_num_rows($EngrResult);
			if ($usercount!=0){
				$engraving=$EngrItem[engraving];}
			else{
			$engraving="";}
			
			$TintQuery="select tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Tint'"; //Get TINT
		$TintResult=mysql_query($TintQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$TintItem=mysql_fetch_array($TintResult);
			$usercount=mysql_num_rows($TintResult);
			if ($usercount!=0){
				$tint=$TintItem[tint];
				$tint_color=$TintItem[tint_color];
				$from_perc=$TintItem[from_perc];
				$to_perc=$TintItem[to_perc];}
			else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";}
				
		$EdgeQuery="select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Edging'"; //Get EDGING
		$EdgeResult=mysql_query($EdgeQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EdgeItem=mysql_fetch_array($EdgeResult);
			$usercount=mysql_num_rows($EdgeResult);
			if ($usercount!=0){
				$frame_type=$EdgeItem[frame_type];
				$job_type=$EdgeItem[job_type];
				$supplier=$EdgeItem[supplier];
				$model=$EdgeItem[model];
				$color=$EdgeItem[color];
				$order_type=$EdgeItem[order_type];
				$eye_size=$EdgeItem[eye_size];
				$bridge=$EdgeItem[bridge];
				$temple=$EdgeItem[temple];
				}
			else{
				$frame_type="";
				$job_type="";
				$supplier="";
				$model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
	
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
		$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'.$orderItem["patient_ref_num"].'","'.$accNum.'"'.chr(13);
			}
			
	return $outputstring;
			
}






function get_header_string(){//CREATE HEADER LIST

$headerstring='"LINE ID",';
$headerstring.='"ORDER NUMBER",';
$headerstring.='"USER ID",';
$headerstring.='"PO NUMBER",';
$headerstring.='"TRAY REFERENCE NUMBER",';
$headerstring.='"MAIN LAB NAME",';
$headerstring.='"PRESCRIPTION LAB NAME",';
$headerstring.='"EYE",';
$headerstring.='"ORDER ITEM NUMBER",';
$headerstring.='"ORDER DATE PLACED",';
$headerstring.='"ORDER DATE SHIPPED",';
$headerstring.='"ORDER DATE BASKET",';
$headerstring.='"ORDER QUANTITY",';
$headerstring.='"PATIENT FIRST NAME",';
$headerstring.='"PATIENT LAST NAME",';
$headerstring.='"SALES PERSON ID",';
$headerstring.='"PRODUCT CODE",';
$headerstring.='"COLOR CODE",';
$headerstring.='"PRODUCT NAME",';
$headerstring.='"ORDER PRODUCT ID",';
$headerstring.='"ORDER PRODUCT INDEX",';
$headerstring.='"ORDER PRODUCT MATERIAL",';
$headerstring.='"ORDER PRODUCT PRICE",';
$headerstring.='"ORDER PRODUCT DISCOUNT PRICE",';
$headerstring.='"ORDER SHIPPING COST",';
$headerstring.='"ORDER SHIPPING METHOD",';
$headerstring.='"ORDER OVER RANGE FEE",';
$headerstring.='"ORDER PRODUCT TYPE",';
$headerstring.='"ORDER PRODUCT COATING",';
$headerstring.='"ORDER PRODUCT PHOTO",';
$headerstring.='"ORDER PRODUCT POLAR",';
$headerstring.='"ORDER STATUS",';
$headerstring.='"ORDER TOTAL",';
$headerstring.='"RE SPHERE",';
$headerstring.='"LE SPHERE",';
$headerstring.='"RE CYLINDER",';
$headerstring.='"LE CYLINDER",';
$headerstring.='"RE ADDITION",';
$headerstring.='"LE ADDITION",';
$headerstring.='"RE AXIS",';
$headerstring.='"LE AXIS",';
$headerstring.='"RE PR AX",';
$headerstring.='"LE PR AX",';
$headerstring.='"RE PR AX2",';
$headerstring.='"LE PR AX2",';
$headerstring.='"RE_PR_IO",';
$headerstring.='"RE_PR_UD",';
$headerstring.='"LE_PR_IO",';
$headerstring.='"LE_PR_UD",';
$headerstring.='"RE PD",';
$headerstring.='"RE PD NEAR",';
$headerstring.='"RE HEIGHT",';
$headerstring.='"LE PD",';
$headerstring.='"LE PD NEAR",';
$headerstring.='"LE HEIGHT",';
$headerstring.='"FRAME A",';
$headerstring.='"FRAME B",';
$headerstring.='"FRAME ED",';
$headerstring.='"FRAME DBL",';
$headerstring.='"FRAME TYPE",';
$headerstring.='"CURRENCY",';
$headerstring.='"SPECIAL INSTRUCTIONS",';
$headerstring.='"GLOBAL DISCOUNT",';
$headerstring.='"INFOCUS DISCOUNT",';
$headerstring.='"PRECISION DISCOUNT",';
$headerstring.='"MY WORLD DISCOUNT",';
$headerstring.='"VISION PRO DISCOUNT",';
$headerstring.='"VISION PRO POLY DISCOUNT",';
$headerstring.='"ADDITIONAL DISCOUNT",';
$headerstring.='"ADDITIONAL DISCOUNT TYPE",';
$headerstring.='"ADDITIONAL ITEM",';
$headerstring.='"ADDITIONAL ITEM PRICE",';
$headerstring.='"COUPON DSC",';

$headerstring.='"SHIPPING CODE",';
$headerstring.='"ENGRAVING",';
$headerstring.='"TINT",';
$headerstring.='"TINT COLOR",';
$headerstring.='"FROM PERC",';
$headerstring.='"TO PERC",';
$headerstring.='"JOB TYPE",';
$headerstring.='"SUPPLIER",';
$headerstring.='"MODEL",';
$headerstring.='"COLOR",';
$headerstring.='"ORDER TYPE",';
$headerstring.='"EYE SIZE",';
$headerstring.='"BRIDGE",';
$headerstring.='"TEMPLE",';
$headerstring.='"PATIENT REF NUM",';
$headerstring.='"ACCOUNT NUM"'.chr(13);

return $headerstring;
}






function get_header_string_labadmin(){//CREATE HEADER LIST

$headerstring.='"ORDER NUMBER",';
$headerstring.='"USER ID",';
$headerstring.='"MAIN LAB NAME",';
$headerstring.='"PRESCRIPTION LAB NAME",';
$headerstring.='"EYE",';
$headerstring.='"ORDER DATE PLACED",';
$headerstring.='"ORDER DATE SHIPPED",';
$headerstring.='"ORDER QUANTITY",';
$headerstring.='"PATIENT FIRST NAME",';
$headerstring.='"PATIENT LAST NAME",';
$headerstring.='"PRODUCT NAME",';
$headerstring.='"ORDER PRODUCT PRICE",';
$headerstring.='"ORDER PRODUCT DISCOUNT PRICE",';
$headerstring.='"ORDER SHIPPING COST",';
$headerstring.='"ORDER PRODUCT TYPE",';
$headerstring.='"ORDER PRODUCT COATING",';
$headerstring.='"ORDER PRODUCT PHOTO",';
$headerstring.='"ORDER PRODUCT POLAR",';
$headerstring.='"ORDER STATUS",';
$headerstring.='"ORDER TOTAL",';
$headerstring.='"RE SPHERE",';
$headerstring.='"LE SPHERE",';
$headerstring.='"RE CYLINDER",';
$headerstring.='"LE CYLINDER",';
$headerstring.='"RE ADDITION",';
$headerstring.='"LE ADDITION",';
$headerstring.='"RE AXIS",';
$headerstring.='"LE AXIS",';
$headerstring.='"RE PD",';
$headerstring.='"RE PD NEAR",';
$headerstring.='"RE HEIGHT",';
$headerstring.='"LE PD",';
$headerstring.='"LE PD NEAR",';
$headerstring.='"LE HEIGHT",';
$headerstring.='"FRAME A",';
$headerstring.='"FRAME B",';
$headerstring.='"FRAME ED",';
$headerstring.='"FRAME DBL",';
$headerstring.='"FRAME TYPE",';
$headerstring.='"SPECIAL INSTRUCTIONS",';
$headerstring.='"JOB TYPE"'.chr(13);

return $headerstring;
}









function export_order_labadmin($order_num){

	$Query="select * from orders WHERE order_num='$order_num' GROUP BY order_num ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)
		or die  ('I cannot select items because: ' . mysql_error());

	while ($orderItem=mysql_fetch_array($Result)){

		switch($orderItem["order_status"]){
						case 'cancelled':				$order_status = "Cancelled";			break;
						case 'processing':				$order_status = "Confirmed";			break;
						case 'on hold':				    $order_status = "On Hold";				break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";		break;
						case 'in coating':				$order_status = "In Coating";			break;
						case 'in mounting':				$order_status = "In Mounting";			break;
						case 'in edging':				$order_status = "In Edging";			break;
						case 'job started':				$order_status = "Surfacing";			break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'order completed':			$order_status = "Order Completed";		break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 're-do':					$order_status = "Redo";					break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";	break;
						case 'waiting for lens':		$order_status = "Waiting for Lens";		break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;	
						case 'scanned shape to swiss':	$order_status = "Scanned shape to Swiss";break;
		}
		
		
	
		
		
		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$accItem=mysql_fetch_array($accResult);
			
		$accNum=$accItem[account_num];
		
		$labQuery="select lab_name from labs WHERE primary_key='$orderItem[lab]'"; //Get Main Lab Name
		$labResult=mysql_query($labQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$labItem=mysql_fetch_array($labResult);
			
		$PlabQuery="select lab_name from labs WHERE primary_key='$orderItem[prescript_lab]'"; //Get Prescription Lab Name
		$PlabResult=mysql_query($PlabQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$PlabItem=mysql_fetch_array($PlabResult);
			
		$special_instructions=addslashes($orderItem["special_instructions"]);
		$extra_product=addslashes($orderItem["extra_product"]);
		
		$ProdQuery="select product_code,color_code from exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		$ProdResult=mysql_query($ProdQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$ProdItem=mysql_fetch_array($ProdResult);
			
			$color_code=$ProdItem[color_code];
			$product_code=$ProdItem[product_code];
			
		$ShipQuery="select shipping_code from accounts WHERE user_id='$orderItem[user_id]'"; //Get SHIPPING CODE
		$ShipResult=mysql_query($ShipQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$ShipItem=mysql_fetch_array($ShipResult);
			
			$shipping_code=$ShipItem[shipping_code];
			
			
			$EngrQuery="select engraving from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Engraving'"; //Get ENGRAVING
		$EngrResult=mysql_query($EngrQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EngrItem=mysql_fetch_array($EngrResult);
			$usercount=mysql_num_rows($EngrResult);
			if ($usercount!=0){
				$engraving=$EngrItem[engraving];}
			else{
			$engraving="";}
			
			$TintQuery="select tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Tint'"; //Get TINT
		$TintResult=mysql_query($TintQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$TintItem=mysql_fetch_array($TintResult);
			$usercount=mysql_num_rows($TintResult);
			if ($usercount!=0){
				$tint=$TintItem[tint];
				$tint_color=$TintItem[tint_color];
				$from_perc=$TintItem[from_perc];
				$to_perc=$TintItem[to_perc];}
			else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";}
				
		$EdgeQuery="select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Edging'"; //Get EDGING
		$EdgeResult=mysql_query($EdgeQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EdgeItem=mysql_fetch_array($EdgeResult);
			$usercount=mysql_num_rows($EdgeResult);
			if ($usercount!=0){
				$frame_type=$EdgeItem[frame_type];
				$job_type=$EdgeItem[job_type];
				$supplier=$EdgeItem[supplier];
				$model=$EdgeItem[model];
				$color=$EdgeItem[color];
				$order_type=$EdgeItem[order_type];
				$eye_size=$EdgeItem[eye_size];
				$bridge=$EdgeItem[bridge];
				$temple=$EdgeItem[temple];
				}
			else{
				$frame_type="";
				$job_type="";
				$supplier="";
				$model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
	
		$outputstring.='"'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_quantity"].'","';
		
		
	$outputstring.=
	$orderItem["order_patient_first"].
	'","'.
	$orderItem["order_patient_last"].
	'","'.
	$orderItem["order_product_name"].
	'","'.
	$orderItem["order_product_price"].
	'","'.
	$orderItem["order_product_discount"].
	'","'.
	$orderItem["order_shipping_cost"].
	'","'.
	$orderItem["order_product_type"].
	'","'.
	$orderItem["order_product_coating"].
	'","'.
	$orderItem["order_product_photo"].
	'","'.
	$orderItem["order_product_polar"].
	'","'.
	$order_status.
	'","'.
	$orderItem["order_total"].
	'","'.
	$orderItem["re_sphere"].
	'","'.
	$orderItem["le_sphere"].
	'","'.
	$orderItem["re_cyl"].
	'","'.
	$orderItem["le_cyl"].
	'","'.
	$orderItem["re_add"].
	'","';
		
		
	$outputstring.=
	$orderItem["le_add"].
	'","'.
	$orderItem["re_axis"].
	'","'.
	$orderItem["le_axis"].
	'","'.
	$orderItem["re_pd"].
	'","'.
	$orderItem["re_pd_near"].
	'","'.
	$orderItem["re_height"].
	'","'.
	$orderItem["le_pd"].
	'","'.
	$orderItem["le_pd_near"].
	'","'.
	$orderItem["le_height"].
	'","'.
	$orderItem["frame_a"].
	'","'.
	$orderItem["frame_b"].
	'","'.
	$orderItem["frame_ed"].
	'","'.
	$orderItem["frame_dbl"].
	'","'.
	$orderItem["frame_type"].
	'","'.
	$special_instructions.
	'","'.
	$job_type.
	'"'. 
	chr(13);
			}
			
	return $outputstring;
			
}

?>