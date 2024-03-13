<?php

function export_order($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)		or die  ('I cannot select items because: ' . mysql_error());

	while ($orderItem=mysql_fetch_array($Result)){

		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "Surfacing";	    	break;
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
						case 'information in hand':		$order_status = "Info in Hand";		    break;
						case 'delay issue 6':			$lorder_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$lorder_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Redo";				    break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";			break;
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
				
				
		//$VerifIfShape = "Select job_type from extra_product_orders  where order_num ='$orderItem[order_num]' and category = 'Edging' ";
		$VerifIfShape = "Select myupload from orders  where order_num ='$orderItem[order_num]'";
		$VerifResult=mysql_query($VerifIfShape)or die  ('I cannot select items because: ' . mysql_error());
		$DataVerif=mysql_fetch_array($VerifResult);
		$TheShape = $DataVerif['myupload'];
		
		if ($TheShape <> "") {
		$TheShape ="Yes";
		echo  '<br> une shape attaché: Oui';
		}else{
		$TheShape ="No";
		echo  '<br> une shape attaché: Non';
		}
				
				
		$EdgeQuery="select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Edging'"; //Get EDGING
		$EdgeResult=mysql_query($EdgeQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EdgeItem=mysql_fetch_array($EdgeResult);
			$usercount=mysql_num_rows($EdgeResult);
			if ($usercount!=0){
			
			
				//$frame_type=$EdgeItem[frame_type];
				switch ($EdgeItem[frame_type]) {
				
				case 'Nylon Groove':
				$frame_type="Nylon Groove";
				break;
				
				case 'Metal':
				$frame_type="Metal";
				break;
			
				case 'Drill & Notch':
				$frame_type="Drill & Notch";
				break;
				
				case 'Plastic':
				$frame_type="Plastic";
				break;
				
				case 'Metal Groove':
				$frame_type="Metal Groove";
				break;
				
				case 'Drill and Notch':
				$frame_type="Drill and Notch";
				break;

				case 'Edge Polish':
				$frame_type="Edge Polish";
				break;
					
				case 'Métal            ':
				$frame_type="Metal";
				break;
				
				case 'Fil Nylon        ':
				$frame_type="Nylon Groove";
				break;
				
				case 'Percé       ':
				$frame_type="Drill and Notch";
				break;
				
				case 'Fil Métal           ':
				$frame_type="Metal Groove";
				break;
				
				case 'Plastique':
				$frame_type="Plastic";
				break;
				
								
				default: 
				$frame_type= $EdgeItem[frame_type];
				break;
				}
				
				

				
				

				switch ($EdgeItem[job_type]) {
				
				case 'Taillé-monté                  ':
				$job_type="Edge and Mount";
				break;
				
				case 'Non-taillé      ':
				$job_type="Uncut";
				break;
				
				case 'Edge and Mount':
				$job_type="Edge and Mount";
				break;
	
				case 'Uncut':
				$job_type="Uncut";
				break;
				
				default: 
				$job_type= $EdgeItem[job_type];
				break;
				}
			
				$supplier=$EdgeItem[supplier];
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
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
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
	
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		//$orderItem["order_patient_first"]="xxx";//XXX out certain fields
		//$orderItem["order_patient_last"]="xxx";
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
		$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'
		. $orderItem["patient_ref_num"]	.   '","'.$accNum. '","'.$TheShape.'"'.chr(13);
		
		
		
			}
	
	
	
	return $outputstring;
			
			
	
}



function export_order_HKO($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());
	
	while ($orderItem=mysql_fetch_array($Result)){

		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "In Production";		break;
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
						case 'delay issue 6':			$lorder_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$lorder_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Re-do";				break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";				break;
		}
		
		//Modification request by HKO  2010-08-11
		switch($orderItem["order_product_coating"]){
			case 'Dream AR':				$shipping_code = "NA108";			break;
			case 'DH2':						$shipping_code = "NA108";			break;
			case 'Smart AR':				$shipping_code = "NA108";			break;
			case 'Hard Coat':				$shipping_code = "NA108";			break;
			case 'ITO AR':					$shipping_code = "NA108";			break;
			case 'Aqua Dream AR':			$shipping_code = "NA108";			break;
			case 'Uncoated':				$shipping_code = "NA108";			break;
			case 'MultiClear AR':			$shipping_code = "NA108";			break;
			case 'Smart AR':				$shipping_code = "NA108";			break;
			case 'Multiclear AR':			$shipping_code = "NA108";			break;
			case 'Uncoated':				$shipping_code = "NA108";			break;			
		}
		
		
		//code qui modifie le shipping code si le main lab est lens net USA
		if ($orderItem[lab]=='32'){
		$shipping_code = "NA310";
		}
		
		
	
		switch($orderItem["order_product_coating"]){
						case 'Dream AR':				$orderItem["order_product_coating"] = "HC";		
						break;
						
						case 'DH2':						$orderItem["order_product_coating"] = "HC";	
						break;
						
						case 'Smart AR':				$orderItem["order_product_coating"] = "CR+G";			
						break;
						
						case 'Hard Coat':				$orderItem["order_product_coating"] = "HC";					
						break;
						
						case 'ITO AR':					$orderItem["order_product_coating"] = "CR+ETC";				
						break;
						
						
						case 'MultiClear AR':			$orderItem["order_product_coating"] = "GL+G";			
						break;
						
						case 'Uncoated':				$orderItem["order_product_coating"] = " ";			
						break;
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
			
			//$shipping_code=$ShipItem[shipping_code];
			
			
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
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
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
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
	
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		//$orderItem["order_patient_first"]="xxx";//XXX out certain fields
		//$orderItem["order_patient_last"]="xxx";
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
		$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'.$orderItem["patient_ref_num"].'","'.$accNum.'"'.chr(13);
			}
			
	return $outputstring;
			
}









function export_order_Conant($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());
	
	while ($orderItem=mysql_fetch_array($Result)){

		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "In Production";		break;
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
						case 'delay issue 6':			$lorder_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$lorder_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Re-do";				break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";				break;
		}
		
		//Modification request by HKO  2010-08-11
		switch($orderItem["order_product_coating"]){
			case 'Dream AR':				$shipping_code = "NA108";			break;
			case 'DH2':						$shipping_code = "NA108";			break;
			case 'Smart AR':				$shipping_code = "NA108";			break;
			case 'Hard Coat':				$shipping_code = "NA108";			break;
			case 'ITO AR':					$shipping_code = "NA108";			break;
			case 'Aqua Dream AR':			$shipping_code = "NA108";			break;
			case 'Uncoated    ':			$shipping_code = "NA108";			break;
			case 'MultiClear AR':			$shipping_code = "NA108";			break;
			case 'Smart AR':				$shipping_code = "NA108";			break;
			case 'Multiclear AR':			$shipping_code = "NA108";			break;
			case 'Uncoated':				$shipping_code = "NA108";			break;
						
		}
		
	
		switch($orderItem["order_product_coating"]){
						case 'Dream AR':				$orderItem["order_product_coating"] = "HC";				break;
						case 'DH2':						$orderItem["order_product_coating"] = "HC";				break;
						case 'Smart AR':				$orderItem["order_product_coating"] = "CR+G";			break;
						case 'Hard Coat':				$orderItem["order_product_coating"] = "HC";				break;
						case 'ITO AR':					$orderItem["order_product_coating"] = "CR+ETC";			break;
						case 'MultiClear AR':			$orderItem["order_product_coating"] = "GL+G";			break;
						case 'Uncoated':				$orderItem["order_product_coating"] = " ";				break;
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
			
			//$shipping_code=$ShipItem[shipping_code];
			
			
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
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
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
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
	
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		//$orderItem["order_patient_first"]="xxx";//XXX out certain fields
		//$orderItem["order_patient_last"]="xxx";
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
				
		if (strlen($orderItem["le_pd"])>1){
		$le_pd = $orderItem["le_pd"];
		}else {
		$le_pd = "";
		}

		if (strlen($orderItem["le_pd_near"])>1){
		$le_pd_near = $orderItem["le_pd_near"];
		}else {
		$le_pd_near = "";
		}

		if (strlen($orderItem["le_height"])>1){
		$le_height = $orderItem["le_height"];
		}else {
		$le_height = "";
		}

		if (strlen($orderItem["re_add"])>1){
		$re_add = $orderItem["re_add"];
		}else {
		$re_add = "";
		}	

		if (strlen($orderItem["le_add"])>1){
		$le_add = $orderItem["le_add"];
		}else {
		$le_add = "";
		}
		
		if (strlen($orderItem["re_axis"])>1){
		$re_axis = $orderItem["re_axis"];
		}else {
		$re_axis = "";
		}

		if (strlen($orderItem["le_axis"])>1){
		$le_axis = $orderItem["le_axis"];
		}else {
		$le_axis = "";
		}

		if (strlen($orderItem["re_pr_ax"])>1){
		$re_pr_ax = $orderItem["re_pr_ax"];
		}else {
		$re_pr_ax = "";
		}
			
		if (strlen($orderItem["le_pr_ax"])>1){
		$le_pr_ax = $orderItem["le_pr_ax"];
		}else {
		$le_pr_ax = "";
		}

		if (strlen($orderItem["re_pr_ax2"])>1){
		$re_pr_ax2 = $orderItem["re_pr_ax2"];
		}else {
		$re_pr_ax2 = "";
		}
		
		if (strlen($orderItem["le_pr_ax2"])>1){
		$le_pr_ax2 = $orderItem["le_pr_ax2"];
		}else {
		$le_pr_ax2 = "";
		}

		if (strlen($orderItem["re_cyl"])>1){
		$re_cyl = $orderItem["re_cyl"];
		}else {
		$re_cyl = "";
		}

		if (strlen($orderItem["le_cyl"])>1){
		$le_cyl = $orderItem["le_cyl"];
		}else {
		$le_cyl = "";
		}
				
		if (strlen($orderItem["re_sphere"])>1){
		$re_sphere = $orderItem["re_sphere"];
		}else {
		$re_sphere = "";
		}
		
		if (strlen($orderItem["le_sphere"])>1){
		$le_sphere = $orderItem["le_sphere"];
		}else {
		$le_sphere = "";
		}
		
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$re_sphere.'","'.$le_sphere.'","'.$re_cyl.'","'.$le_cyl.'","'.$re_add.'","';
		
		$outputstring.=$le_add.'","'.$re_axis.'","'.$le_axis.'","'.$re_pr_ax.'","'.$le_pr_ax.'","'.$re_pr_ax2.'","'.$le_pr_ax2.'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$le_pd.'","'.$le_pd_near .'","'.$le_height.'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'.$orderItem["patient_ref_num"].'","'.$accNum.'"'.chr(13);
			}
			
	return $outputstring;
			
}





function export_order_DLAB($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());
	
	while ($orderItem=mysql_fetch_array($Result)){

		switch($orderItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";			break;
						case 'order imported':			$order_status = "Order Imported";		break;
						case 'job started':				$order_status = "In Production";		break;
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
						case 'delay issue 6':			$lorder_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$lorder_status = "Waiting for Frame";	break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";	break;
						case 're-do':					$order_status = "Re-do";				break;
						case 'in transit':				$order_status = "In Transit";			break;
						case 'filled':					$order_status = "Shipped";				break;
						case 'cancelled':				$order_status = "Cancelled";				break;
		}
		
		//Modification request by HKO  2010-08-11
	/*	switch($orderItem["order_product_coating"]){
			case 'Dream AR':				$shipping_code = "NA108";			break;
			case 'DH2':						$shipping_code = "NA108";			break;
			case 'Smart AR':				$shipping_code = "NA108";			break;
			case 'Hard Coat':				$shipping_code = "NA108";			break;
			case 'ITO AR':					$shipping_code = "NA108";			break;
			case 'Aqua Dream AR':			$shipping_code = "NA108";			break;
			case 'Uncoated    ':			$shipping_code = "NA108";			break;
			case 'MultiClear AR':			$shipping_code = "NA108";			break;
			case 'Smart AR':				$shipping_code = "NA108";			break;
			case 'Multiclear AR':			$shipping_code = "NA108";			break;
			case 'Uncoated':				$shipping_code = "NA108";			break;
						
		}*/
		
		$shipping_code =$orderItem["shipping_code"];
		
	
		switch($orderItem["order_product_coating"]){
						case 'Dream AR':				$orderItem["order_product_coating"] = "HC";		
						break;
						
						case 'DH2':						$orderItem["order_product_coating"] = "HC";	
						break;
						
						case 'Smart AR':				$orderItem["order_product_coating"] = "CR+G";			
						break;
						
						case 'Hard Coat':				$orderItem["order_product_coating"] = "HC";					
						break;
						
						case 'ITO AR':					$orderItem["order_product_coating"] = "CR+ETC";				
						break;
						
						
						case 'MultiClear AR':			$orderItem["order_product_coating"] = "GL+G";			
						break;
						
						case 'Uncoated':				$orderItem["order_product_coating"] = " ";			
						break;
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
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
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
				$shape_model="";
				$frame_model="";
				$color="";
				$order_type="";
				$eye_size="";
				$bridge="";
				$temple="";
				}
	
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		
		if ($orderItem["shape_name_bk"] =="") {
		$myupload = "none";
		}else{
		$myupload = $orderItem["shape_name_bk"] ;
		}
		//$orderItem["order_patient_first"]="xxx";//XXX out certain field
		//$orderItem["order_patient_last"]="xxx";
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
		$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'.$orderItem["patient_ref_num"].'","'.$accNum.'","'.$myupload .'"'. "\r\n";
			}
			
	return $outputstring;
			
}









function export_order_SOI($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());

	while ($orderItem=mysql_fetch_array($Result)){
		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)	or die  ('I cannot select items because: ' . mysql_error());
		$accItem=mysql_fetch_array($accResult);
		$accNum=$accItem[account_num];
		$special_instructions=addslashes($orderItem["special_instructions"]);
		$extra_product=addslashes($orderItem["extra_product"]);		
		$realTotal = $orderItem["order_total"] + $orderItem["order_shipping_cost"];
		$outputstring.=$accNum. ',' . $orderItem["order_num"].','.$orderItem["order_date_shipped"].','.$realTotal  . "\r\n";
			}		
	return $outputstring;		
}



function export_order_SOI_NET($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());

	while ($orderItem=mysql_fetch_array($Result)){
		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)	or die  ('I cannot select items because: ' . mysql_error());
		$accItem=mysql_fetch_array($accResult);
		$accNum=$accItem[account_num];
		$special_instructions=addslashes($orderItem["special_instructions"]);
		$extra_product=addslashes($orderItem["extra_product"]);	
		$orderTotalMajorer = ($orderItem["order_total"] + $orderItem["order_shipping_cost"]) * 1.335;//On met ici le pourcentage a majorer 1.25 = 25% de majoration
		$orderTotalMajorer = money_format('%.2n',$orderTotalMajorer);
		$outputstring.=$accNum. ',' . $orderItem["order_num"].','.$orderItem["order_date_shipped"].','.$orderTotalMajorer . "\r\n";
			}		
	return $outputstring;		
}






function export_credit_SOI($order_num){
	$Query="select * from memo_credits WHERE mcred_order_num='$order_num'"; //Get credit Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());
	while ($orderItem=mysql_fetch_array($Result)){
		$accQuery="select * from accounts WHERE user_id='$orderItem[mcred_acct_user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)	or die  ('I cannot select items because: ' . mysql_error());
		$accItem=mysql_fetch_array($accResult);	
		$accNum=$accItem[account_num];	
		
		 if ($orderItem["mcred_cred_type"] =="debit"){
		$outputstring = "";		
		$outputstring.=$accNum. ',' . $orderItem["mcred_order_num"].','.$orderItem["mcred_date"].','.$orderItem["mcred_abs_amount"] . "\r\n";
		}else{
		$outputstring = "";		
		$outputstring.=$accNum. ',' . $orderItem["mcred_order_num"].','.$orderItem["mcred_date"].',-'.$orderItem["mcred_abs_amount"] . "\r\n";
		}
		
		
			}	
	return $outputstring;		
}




function export_credit_Conso($order_num){
	$Query="select * from memo_credits WHERE mcred_order_num='$order_num'"; //Get credit Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());
	while ($orderItem=mysql_fetch_array($Result)){
		$accQuery="select * from accounts WHERE user_id='$orderItem[mcred_acct_user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)	or die  ('I cannot select items because: ' . mysql_error());
		$accItem=mysql_fetch_array($accResult);	
		$accNum=$accItem[account_num];	
		
		 if ($orderItem["mcred_cred_type"] =="debit"){
		$outputstring = "";		
		$outputstring.=  $orderItem["mcred_memo_num"]. ',' . $orderItem["mcred_date"]. ','.$orderItem["mcred_date"].',' . $orderItem["mcred_abs_amount"] . ',' . " " . ',' . $accNum . ',' . $orderItem["mcred_acct_user_id"]  .  "\r\n"   ;
		}else{
		$outputstring = "";		
		$outputstring.=  $orderItem["mcred_memo_num"]. ',' . $orderItem["mcred_date"]. ','.$orderItem["mcred_date"].',-' . $orderItem["mcred_abs_amount"] . ',' . " " . ',' . $accNum . ',' . $orderItem["mcred_acct_user_id"]  .  "\r\n" ;
		}
		
			}	
	return $outputstring;		
}


function export_order_Conso($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());

	while ($orderItem=mysql_fetch_array($Result)){
		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)	or die  ('I cannot select items because: ' . mysql_error());
		$accItem=mysql_fetch_array($accResult);
		$accNum=$accItem[account_num];
		//$special_instructions=addslashes($orderItem["special_instructions"]);
		//$extra_product=addslashes($orderItem["extra_product"]);		
		$outputstring.= $orderItem["order_num"].','.$orderItem["order_date_processed"] .','.$orderItem["order_date_shipped"].','.$orderItem["order_total"].','.$orderItem["patient_ref_num"] .','.$accNum.','.$orderItem["user_id"] . "\r\n";
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
$headerstring.='"PT",';
$headerstring.='"PA",';
$headerstring.='"VERTEX",';
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
$headerstring.='"SHAPE MODEL",';
$headerstring.='"FRAME MODEL",';
$headerstring.='"COLOR",';
$headerstring.='"ORDER TYPE",';
$headerstring.='"EYE SIZE",';
$headerstring.='"BRIDGE",';
$headerstring.='"TEMPLE",';
$headerstring.='"PATIENT REF NUM",';
$headerstring.='"ACCOUNT NUM",';
$headerstring.='"FOLLOW LENS SHAPE"'.chr(13);

return $headerstring;
}



function get_header_string_SOI(){//CREATE HEADER LIST

$headerstring='';
$headerstring.= chr(13);

return $headerstring;
}






















function Export_Rebilling_Admin($order_num){

	$queryCompany = "Select company from accounts where user_id = (Select user_id from orders where order_num = $order_num)";
	$ResultCompany=mysql_query($queryCompany)	or die  ('I cannot select items because: ' . mysql_error());
	$DataCompany=mysql_fetch_array($ResultCompany);
	$company = $DataCompany['company'];
	
	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());
	
	
	
	while ($orderItem=mysql_fetch_array($Result)){
	
		$outputstring.='"'.$orderItem["order_num"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$company.'","'.$labItem["lab_name"].'","'. $orderItem["order_patient_first"]. ' ' . $orderItem["order_patient_last"].'","'.$orderItem["order_product_name"].'","'.$orderItem["order_total"].'"'. "\r\n";
			}
			
	return $outputstring;
			
}






?>