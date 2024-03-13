<?php


function map_order_status($status){
	switch($status){
		case 'processing':				return "Confirmed";			break;
		case 'job started':				return "In Production";		break;
		case 'filled':					return "Shipped";			break;
		default: return ucwords($status);
	}
}

function map_coating($coating){
	switch($coating){
		case 'Dream AR': return "HC";	break;
		case 'DH2':		 return  "HC";		break;
		case 'Smart AR': return  "CR+G"; 	break;						
		case 'Hard Coat':return  "HC";		break;
		case 'ITO AR':	 return  "CR+ETC";	break;
		case 'MultiClear AR': 	return  "GL+G";	break;
		case 'Uncoated': 		return  " ";	break;
		case 'Nu': 		return  " ";	break;
		default: 				return  $coating;
	}

}


function if_dash($val, $alt){
	if ((strlen($val)>1) && ($val <> '-') && ($val <> 'û') ){
		return $val;
	}else{ return $alt;}
}

function q_array($query){
	$result = mysql_query($query) or die ("Failed to complete query $query\n". mysql_error());
	return mysql_fetch_array($result);
}




function export_order($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)		or die  ('I cannot select items because: ' . mysql_error());

	while ($orderItem=mysql_fetch_array($Result)){

		$order_status = map_order_status($orderItem["order_status"]);
		
		$accItem=q_array("select * from accounts WHERE user_id='$orderItem[user_id]'"); //ACCOUNT INFO SECTION

		$accNum=$accItem[account_num];
		
		$labItem=q_array("select lab_name from labs WHERE primary_key='$orderItem[lab]'"); //Get Main Lab Name
		$PlabItem=q_array("select lab_name from labs WHERE primary_key='$orderItem[prescript_lab]'"); //Get Prescription Lab Name
	
		$special_instructions=addslashes($orderItem["special_instructions"]);
		$extra_product=addslashes($orderItem["extra_product"]);
		
		
		$ProdItem=q_array("select product_code,color_code from exclusive WHERE primary_key='$orderItem[order_product_id]'"); //Get PRODUCT CODES
		
		$color_code=$ProdItem[color_code];
		$product_code=$ProdItem[product_code];
			
		$ShipItem=q_array("select shipping_code from accounts WHERE user_id='$orderItem[user_id]'"); //Get SHIPPING CODE

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


		$order_status = map_order_status($orderItem["order_status"]);


		
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
		
		//code qui modifie le shipping code si le main lab est lens net USA
		if ($orderItem[lab]=='41'){
		$shipping_code = "NA310";
		}

		//code qui modifie le shipping code si le main lab est AITLENSCLUB
		if ($orderItem[lab]=='47'){
		$shipping_code = "NA330";
		}
		
		//code qui modifie le shipping code si le main lab est Dlab Illinois
		if ($orderItem[lab]=='46'){
		$shipping_code = "NA320";
		}


		//Just this function has this mapping - may be a mistake
		if ($orderItem["order_product_coating"]=='Dream AR') {
			$orderItem["order_product_coating"] = "CR+ETC";
		}
		$orderItem["order_product_coating"] = map_coating($orderItem["order_product_coating"]);
	
	
		

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
				
				
				
				
		$EdgeQuery="select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Frame'"; //Get EDGING
		$EdgeResult=mysql_query($EdgeQuery)	or die  ('I cannot select items because: ' . mysql_error());
		$EdgeItem=mysql_fetch_array($EdgeResult);
		$usercount=mysql_num_rows($EdgeResult);
			if ($usercount!=0){
				$shape_model=$EdgeItem[model];
				$frame_model=$EdgeItem[temple_model_num];
				$color=$EdgeItem[color];
			}

	
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		//$orderItem["order_patient_first"]="xxx";//XXX out certain fields
		//$orderItem["order_patient_last"]="xxx";
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
		$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'.$orderItem["patient_ref_num"].'","'
		.'","'.$orderItem["RE_CT"].'","' .'","'.$orderItem["LE_CT"].'","' .'","'.$orderItem["RE_ET"].'","' .'","'.$orderItem["LE_ET"].'","'	
		.$accNum.'"'.chr(13);
			}
			
	return $outputstring;
			
}









function export_order_Conant($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());
	
	while ($orderItem=mysql_fetch_array($Result)){


		$order_status = map_order_status($orderItem["order_status"]);


		
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
		
	
		$orderItem["order_product_coating"] = map_coating($orderItem["order_product_coating"]);
	
		

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
		
		
		if ($orderItem[lab]==37){
		$ProdQuery="select product_code,color_code from ifc_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}else{
		$ProdQuery="select product_code,color_code from exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}
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
				
		$EdgeQuery="select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Edging' OR order_num='$orderItem[order_num]' AND category='Edging_Frame' "; //Get EDGING
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
	
			if ($orderItem["frame_type"]=="Plastique")
				{
				$orderItem["frame_type"] = "Plastic";
				}
	
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		//$orderItem["order_patient_first"]="xxx";//XXX out certain fields
		//$orderItem["order_patient_last"]="xxx";
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
				
		if ($orderItem["eye"] == "Both")//Both eyes are in the Rx		
		{


						$le_pd = if_dash($orderItem["le_pd"],"");
			$re_pd = if_dash($orderItem["re_pd"],"");
			$le_pd_near = if_dash($orderItem["le_pd_near"],"");
			$re_pd_near = if_dash($orderItem["re_pd_near"],"");

			$le_height = if_dash($orderItem["le_height"],"");
			$re_height = if_dash($orderItem["re_height"],"");
			$re_add = if_dash($orderItem["re_add"],"");
			$le_add = if_dash($orderItem["le_add"],"");
			$le_axis = if_dash($orderItem["le_axis"],"");
			$re_axis = if_dash($orderItem["re_axis"],"");

			$re_pr_ax = if_dash($orderItem["re_pr_ax"],"0");
			$le_pr_ax = if_dash($orderItem["le_pr_ax"],"0");
			$re_pr_ax2 = if_dash($orderItem["re_pr_ax2"],"0");
			$le_pr_ax2 = if_dash($orderItem["le_pr_ax2"],"0");
			$re_cyl = if_dash($orderItem["re_cyl"],"0");
			$le_cyl = if_dash($orderItem["le_cyl"],"0");
			$re_sphere = if_dash($orderItem["re_sphere"],"0");
			$le_sphere = if_dash($orderItem["le_sphere"],"0");


			
		}elseif( $orderItem["eye"]=="R.E."){
		$le_pd = "0";
		$le_sphere = "0";
		$le_cyl="0";
		$le_pr_ax2 ="0";
		$le_pr_ax ="0";
		$le_axis ="0";
		$le_add ="0";
		$le_height ="0";
		$le_pd ="0";
		$le_pd_near ="0";
		
		$re_pd 	     = $orderItem["re_pd"];
		$re_sphere   = $orderItem["re_sphere"];
		$re_cyl      = $orderItem["re_cyl"];
		$re_pr_ax2   = $orderItem["re_pr_ax2"];
		$re_pr_ax    = $orderItem["re_pr_ax"];
		$re_axis     = $orderItem["re_axis"];
		$re_add      = $orderItem["re_add"];
		$re_height   = $orderItem["re_height"];
		$re_pd       = $orderItem["re_pd"];
		$re_pd_near  = $orderItem["re_pd_near"];
		
		}elseif( $orderItem["eye"]=="L.E."){
		$re_pd = "0";
		$re_sphere = "0";
		$re_cyl="0";
		$re_pr_ax2 ="0";
		$re_pr_ax ="0";
		$re_axis ="0";
		$re_add ="0";
		$re_height ="0";
		$re_pd ="0";
		$re_pd_near ="0";
		
		$le_pd 	     = $orderItem["ll_pd"];
		$le_sphere   = $orderItem["le_sphere"];
		$le_cyl      = $orderItem["le_cyl"];
		$le_pr_ax2   = $orderItem["le_pr_ax2"];
		$le_pr_ax    = $orderItem["le_pr_ax"];
		$le_axis     = $orderItem["le_axis"];
		$le_add      = $orderItem["le_add"];
		$le_height   = $orderItem["le_height"];
		$le_pd       = $orderItem["le_pd"];
		$le_pd_near  = $orderItem["le_pd_near"];
		}
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$re_sphere.'","'.$le_sphere.'","'.$re_cyl.'","'.$le_cyl.'","'.$re_add.'","';
		
		$outputstring.=$le_add.'","'.$re_axis.'","'.$le_axis.'","'.$re_pr_ax.'","'.$le_pr_ax.'","'.$re_pr_ax2.'","'.$le_pr_ax2.'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$re_pd.'","'.$re_pd_near.'","'.$re_height.'","'.$le_pd.'","'.$le_pd_near .'","'.$le_height.'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'.$orderItem["patient_ref_num"].'","'.$accNum.'"'.chr(13);
			}
			
	return $outputstring;
			
}






function export_order_DLAB($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());
	
	while ($orderItem=mysql_fetch_array($Result)){

		$order_status = map_order_status($orderItem["order_status"]);


		
		$shipping_code =$orderItem["shipping_code"];
		
	
		$orderItem["order_product_coating"] = map_coating($orderItem["order_product_coating"]);
	
		

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
		
		
		if ($orderItem["myupload"] =="") {
		$myupload = "none";
		}else{
		$myupload = $orderItem["myupload"] ;
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



function export_order_IFC($order_num){

	$Query="select orders.*, ifc_exclusive.product_code from orders, ifc_exclusive WHERE orders.order_product_id = ifc_exclusive.primary_key  AND order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());

	while ($orderItem=mysql_fetch_array($Result)){
		$accQuery="select * from accounts WHERE user_id='$orderItem[user_id]'"; //ACCOUNT INFO SECTION
		$accResult=mysql_query($accQuery)	or die  ('I cannot select items because: ' . mysql_error());
		$accItem=mysql_fetch_array($accResult);
		$accNum=$accItem[account_num];
		$special_instructions=addslashes($orderItem["special_instructions"]);
		$extra_product=addslashes($orderItem["extra_product"]);		
		$realTotal = $orderItem["order_total"] + $orderItem["order_shipping_cost"];
	



		$queryFrm = "SELECT  temple_model_num   FROM extra_product_orders WHERE category = 'Frame' and order_num =".$orderItem["order_num"] ;

		$resultFrm=mysql_query($queryFrm)	or die  ('I cannot select items because: ' . mysql_error());
		$NbrResult = mysql_num_rows($resultFrm);
		if ($NbrResult > 0){
		$DataFrm=mysql_fetch_array($resultFrm);
		}
		
		$QueryProductCode = "SELECT  product_code   FROM ifc_ca_exclusive WHERE primary_key = ". $orderItem["primary_key"] ;
		$resultProductCode=mysql_query($QueryProductCode)	or die  ('I cannot select items because: ' . mysql_error());
		$NbrdeResult = mysql_num_rows($resultProductCode);
		if ($NbrdeResult > 0){
		$DataProductCode=mysql_fetch_array($resultProductCode);
		
		}
		
		
		//$outputstring.= $orderItem["order_num"].','.$orderItem["product_code"].','.$orderItem["patient_ref_num"] .','.$realTotal  . "\r\n";
		$outputstring.= $accItem["company"].','.$orderItem["first_name"] . ' ' .$orderItem["last_name"]     
	    .','.$orderItem["order_product_name"]  .','.$DataProductCode["product_code"] .','.$DataFrm["temple_model_num"] .','.$orderItem["order_quantity"] .','.$orderItem["order_patient_first"] 
		. ' ' .$orderItem["order_patient_last"] .','.$orderItem["order_num"] .','.$orderItem["order_date_processed"]  .','.$realTotal  . "\r\n";
		
//accounts.Account_num, (code client)
//accounts.first_name accounts.last_name, (nom du client)
 //			(Référence complète de la monture)
//orders.order_quantity, (Quantité)
 //orders.order_patient_first 	orders.order_patient_last , (Nom du porteur)
//orders.order_num, (numéro de commande)
//orders.order_date_processed, (date de la commande)
//orders.order_total )Montant total de la commande)
		
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
		$outputstring.= $orderItem["order_num"].','.$orderItem["order_date_processed"] .','.$orderItem["order_date_shipped"].','.$orderItem["order_total"].','.$orderItem["order_patient_first"] . ' '   . $orderItem["order_patient_last"] .','.$accNum.','.$orderItem["user_id"] . "\r\n";
			}		
	return $outputstring;		
}




function get_IFC_header_string(){//CREATE HEADER LIST

$headerstring='"NOM DU MAGASIN",';
$headerstring.='"NOM CLIENT",';
$headerstring.='"PRODUIT",';
$headerstring.='"CODE ARTICLE,';
$headerstring.='"REFERENCE MONTURE",';
$headerstring.='"QTE",';
$headerstring.='"NOM PORTEUR",';
$headerstring.='"NUMERO COMMANDE",';
$headerstring.='"DATE COMMANDE",';
$headerstring.='"MONTANT TOTAL",'.chr(13);

return $headerstring;
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









function get_header_stringIFC(){//CREATE HEADER LIST

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
$headerstring.='"FOLLOW LENS SHAPE",';
$headerstring.='"RE SPHERE POS",';
$headerstring.='"RE CYLINDER POS",';
$headerstring.='"RE AXIS POS",';
$headerstring.='"LE SPHERE POS",';
$headerstring.='"LE CYLINDER POS",';
$headerstring.='"LE AXIS POS"'.chr(13);


return $headerstring;
}



function get_header_string_SOI(){//CREATE HEADER LIST

$headerstring='';
$headerstring.= chr(13);

return $headerstring;
}







function get_header_string_HKO(){//CREATE HEADER LIST

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
$headerstring.='"RE CT",';
$headerstring.='"LE CT",';
$headerstring.='"RE ET",';
$headerstring.='"LE ET",';
$headerstring.='"ACCOUNT NUM",';
$headerstring.='"FOLLOW LENS SHAPE"'.chr(13);

return $headerstring;
}
























function Export_Rebilling_Admin($order_num){


	
	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());
	
	
	
	while ($orderItem=mysql_fetch_array($Result)){
	
	
		$queryElabPrice = "Select e_lab_can_price as theprice from exclusive where primary_key =" . $orderItem[order_product_id];
		$rptElabPrice=mysql_query($queryElabPrice);
		$DataElabPrice=mysql_fetch_array($rptElabPrice);
		$elabPrice = $DataElabPrice['theprice'];
		if ($orderItem[eye] != "Both") {
		$elabPrice = $elabPrice/2;
		}
	
	
		$queryCompany = "Select company from accounts where user_id = (Select user_id from orders where order_num = $order_num LIMIT 0,1) ";
		$ResultCompany=mysql_query($queryCompany)	or die  ('I cannot select items because: ' . mysql_error());
		$DataCompany=mysql_fetch_array($ResultCompany);
		$company = $DataCompany['company'];
	
		$outputstring.='"'.$orderItem["order_num"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$company.'","'.$orderItem["patient_ref_num"].'","'. $orderItem["order_patient_first"]. ' ' . $orderItem["order_patient_last"].'","'.$orderItem["order_product_name"].'","'.$orderItem["order_total"].'","'.$elabPrice.'"'. "\r\n";
			}
			
	return $outputstring;
			
}









function export_order_Stock_DLAB($primary_key){

	$Query="select * from orders WHERE primary_key='$primary_key' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());
	
	while ($orderItem=mysql_fetch_array($Result)){

		$order_status = map_order_status($orderItem["order_status"]);

		


		$QueryCodeOpc="select right_opc, left_opc from products WHERE primary_key ='$orderItem[order_product_id]'"; //ACCOUNT INFO SECTION
		$ResultCodeOpc=mysql_query($QueryCodeOpc)	or die  ('I cannot select items because: ' . mysql_error());
		$DataOpc=mysql_fetch_array($ResultCodeOpc);
	    $Stock_OPC =$DataOpc[right_opc];
		$orderItem["order_item_number"] = $Stock_OPC;

		
		$shipping_code =$orderItem["shipping_code"];

		$orderItem["order_product_coating"] = map_coating($orderItem["order_product_coating"]);
	
		

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
	
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$Stock_OPC.'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		echo $Stock_OPC . '<br>';
		
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
		
		$accItem[company]= str_replace(',',' ',$accItem[company]);
		$accItem[ship_address1]= str_replace(',',' ',$accItem[ship_address1]);
		$accItem[ship_address2]= str_replace(',',' ',$accItem[ship_address2]);
		$accItem[ship_city]= str_replace(',',' ',$accItem[ship_city]);
		$accItem[ship_zip]= str_replace(',',' ',$accItem[ship_zip]);
		$accItem[ship_country]= str_replace(',',' ',$accItem[ship_country]);
		
		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$orderItem["order_item_number"].'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$orderItem["re_sphere"].'","'.$orderItem["le_sphere"].'","'.$orderItem["re_cyl"].'","'.$orderItem["le_cyl"].'","'.$orderItem["re_add"].'","';
		
	/*	$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'.$orderItem["patient_ref_num"].'","'.$accNum .'","'.$myupload .'"'. "\r\n";*/
$outputstring.=$orderItem["le_add"].'","'.$orderItem["re_axis"].'","'.$orderItem["le_axis"].'","'.$orderItem["re_pr_ax"].'","'.$orderItem["le_pr_ax"].'","'.$orderItem["re_pr_ax2"].'","'.$orderItem["le_pr_ax2"].'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$orderItem["re_pd"].'","'.$orderItem["re_pd_near"].'","'.$orderItem["re_height"].'","'.$orderItem["le_pd"].'","'.$orderItem["le_pd_near"].'","'.$orderItem["le_height"].'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'.$orderItem["patient_ref_num"].'","'.$accNum .'","'.$myupload .'","'. $accItem[company].'","'. $accItem[ship_address1] .'","'. $accItem[ship_address2] .'","'. $accItem[ship_city] .'","'. $accItem[ship_state] .'","'. $accItem[ship_zip].'","'. $accItem[ship_country] .'","'. $accItem[depot_number] .'","'. $accItem[bill_to] .'"' . "\r\n";
	
			}
			
	return $outputstring;
			
}
















function export_order_Conant_IFC($order_num){

	$Query="select * from orders WHERE order_num='$order_num' ORDER by primary_key"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());
	
	while ($orderItem=mysql_fetch_array($Result)){

		$order_status = map_order_status($orderItem["order_status"]);

		
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
		
	
		$orderItem["order_product_coating"] = map_coating($orderItem["order_product_coating"]);
		

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
		
		
		if ($orderItem[lab]==37){
		$ProdQuery="select product_code,color_code from ifc_exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}else{
		$ProdQuery="select product_code,color_code from exclusive WHERE primary_key='$orderItem[order_product_id]'"; //Get PRODUCT CODES
		}
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
				$to_perc=$TintItem[to_perc];
				
					
					if ($tint_color == 'Brun'){
					$tint_color = 'Brown';
					}
					
					if ($tint_color == 'Grey'){
					$tint_color = 'Grey';
					}
					
									
					if ($tint == 'Solid 60'){
					$tint='Solid';
					$from_perc=60;
					$to_perc=60;
					$tint_color = 	$tint_color . '-' .  $from_perc  . '%';
					}
					
					if ($tint == 'Solid 80'){
					$tint='Solid';
					$from_perc=82;
					$to_perc=82;
					$tint_color = 	$tint_color . '-' .  $from_perc . '%' ;
					}
					
}
			else{
				$tint="";
				$tint_color="";
				$from_perc="";
				$to_perc="";}
				
		$EdgeQuery="select * from extra_product_orders WHERE order_num='$orderItem[order_num]' AND category='Edging_Frame'"; //Get EDGING
		$EdgeResult=mysql_query($EdgeQuery)
		or die  ('I cannot select items because: ' . mysql_error());
			$EdgeItem=mysql_fetch_array($EdgeResult);
			$usercount=mysql_num_rows($EdgeResult);
			if ($usercount!=0){
				$frame_type=$EdgeItem[frame_type];
				
				
				
		$FrameModelQuery="select * from ifc_frames_french 
		WHERE code ='$EdgeItem[temple_model_num]' AND color ='$EdgeItem[color]'
		OR model   ='$EdgeItem[temple_model_num]' AND color ='$EdgeItem[color]' "; //Get Frame details
		echo '<br>'. $FrameModelQuery;
		$FrameModelResult=mysql_query($FrameModelQuery)		or die  ('I cannot select items because: ' . mysql_error());
		$DataFrameModel=mysql_fetch_array($FrameModelResult);
		$frame_model = $DataFrameModel[upc];
		$color=$DataFrameModel[color_code];
		echo 'Frame model : ' . $frame_model. '  Color Code:'   . $color .  '<br>';		
				$job_type=$EdgeItem[job_type];
				$supplier=$EdgeItem[supplier];
				$shape_model=$EdgeItem[model];
				//$frame_model=$EdgeItem[temple_model_num];
				//$color=$EdgeItem[color];
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
	
			if ($orderItem["frame_type"]=="Plastique")
				{
				$orderItem["frame_type"] = "Plastic";
				}
	
		$outputstring.='"'.$orderItem["primary_key"].'","'.$orderItem["order_num"].'","'.$orderItem["user_id"].'","'.$orderItem["po_num"].'","'.$orderItem["tray_num"].'","'.$labItem["lab_name"].'","'.$PlabItem["lab_name"].'","'.$orderItem["eye"].'","'.$orderItem["order_item_number"].'","'.$orderItem["order_date_processed"].'","'.$orderItem["order_date_shipped"].'","'.$orderItem["order_item_date"].'","'.$orderItem["order_quantity"].'","';
		
		//$orderItem["order_patient_first"]="xxx";//XXX out certain fields
		//$orderItem["order_patient_last"]="xxx";
		$orderItem["order_product_price"]="xxx";
		$orderItem["order_product_discount"]="xxx";
		$orderItem["coupon_dsc"]="xxx";
		$orderItem["order_total"]="xxx";
		
				
		if ($orderItem["eye"] == "Both")//Both eyes are in the Rx		
		{

			$le_pd = if_dash($orderItem["le_pd"],"");
			$re_pd = if_dash($orderItem["re_pd"],"");
			$le_pd_near = if_dash($orderItem["le_pd_near"],"");
			$re_pd_near = if_dash($orderItem["re_pd_near"],"");

			$le_height = if_dash($orderItem["le_height"],"");
			$re_height = if_dash($orderItem["re_height"],"");
			$re_add = if_dash($orderItem["re_add"],"");
			$le_add = if_dash($orderItem["le_add"],"");
			$le_axis = if_dash($orderItem["le_axis"],"");
			$re_axis = if_dash($orderItem["re_axis"],"");

			$re_pr_ax = if_dash($orderItem["re_pr_ax"],"0");
			$le_pr_ax = if_dash($orderItem["le_pr_ax"],"0");
			$re_pr_ax2 = if_dash($orderItem["re_pr_ax2"],"0");
			$le_pr_ax2 = if_dash($orderItem["le_pr_ax2"],"0");
			$re_cyl = if_dash($orderItem["re_cyl"],"0");
			$le_cyl = if_dash($orderItem["le_cyl"],"0");
			$re_sphere = if_dash($orderItem["re_sphere"],"0");
			$le_sphere = if_dash($orderItem["le_sphere"],"0");

		}elseif( $orderItem["eye"]=="R.E."){
		$le_pd = "0";
		$le_sphere = "0";
		$le_cyl="0";
		$le_pr_ax2 ="0";
		$le_pr_ax ="0";
		$le_axis ="0";
		$le_add ="0";
		$le_height ="0";
		$le_pd ="0";
		$le_pd_near ="0";
		
		$re_pd 	     = $orderItem["re_pd"];
		$re_sphere   = $orderItem["re_sphere"];
		$re_cyl      = $orderItem["re_cyl"];
		$re_pr_ax2   = $orderItem["re_pr_ax2"];
		$re_pr_ax    = $orderItem["re_pr_ax"];
		$re_axis     = $orderItem["re_axis"];
		$re_add      = $orderItem["re_add"];
		$re_height   = $orderItem["re_height"];
		$re_pd       = $orderItem["re_pd"];
		$re_pd_near  = $orderItem["re_pd_near"];
		
		}elseif( $orderItem["eye"]=="L.E."){
		$re_pd = "0";
		$re_sphere = "0";
		$re_cyl="0";
		$re_pr_ax2 ="0";
		$re_pr_ax ="0";
		$re_axis ="0";
		$re_add ="0";
		$re_height ="0";
		$re_pd ="0";
		$re_pd_near ="0";
		
		$le_pd 	     = $orderItem["ll_pd"];
		$le_sphere   = $orderItem["le_sphere"];
		$le_cyl      = $orderItem["le_cyl"];
		$le_pr_ax2   = $orderItem["le_pr_ax2"];
		$le_pr_ax    = $orderItem["le_pr_ax"];
		$le_axis     = $orderItem["le_axis"];
		$le_add      = $orderItem["le_add"];
		$le_height   = $orderItem["le_height"];
		$le_pd       = $orderItem["le_pd"];
		$le_pd_near  = $orderItem["le_pd_near"];
		}
		
		
		$re_sphere_pos = $re_sphere ;
		$le_sphere_pos = $le_sphere ;
		$re_cyl_pos = $re_cyl ;
		$le_cyl_pos = $le_cyl ;
		$re_axis_pos = $re_axis;
		$le_axis_pos = $le_axis;
		
		
//European conversion for Conant
	
	if ( $re_cyl <> '0'){
	$re_sphere  = $re_sphere+$re_cyl;
	if ($re_sphere>0) $re_sphere="+".$re_sphere;
	$re_cyl ="-".ABS($re_cyl);
	$re_axis=$re_axis+90;
	if ($re_axis>180) $re_axis=$re_axis-180;
	}


	if ( $le_cyl <> '0'){
	$le_sphere  = $le_sphere+$le_cyl;
	if ($le_sphere>0) $le_sphere="+".$le_sphere;
	$le_cyl="-".ABS($le_cyl);
	$le_axis=$le_axis+90;
	if ($le_axis>180) $le_axis=$le_axis-180;
	}


		
	$outputstring.=$orderItem["order_patient_first"].'","'.$orderItem["order_patient_last"].'","'.$orderItem["salesperson_id"].'","'.$product_code.'","'.$color_code.'","'.$orderItem["order_product_name"].'","'.$orderItem["order_product_id"].'","'.$orderItem["order_product_index"].'","'.$orderItem["order_product_material"].'","'.$orderItem["order_product_price"].'","'.$orderItem["order_product_discount"].'","'.$orderItem["order_shipping_cost"].'","'.$orderItem["order_shipping_method"].'","'.$orderItem["order_over_range_fee"].'","'.$orderItem["order_product_type"].'","'.$orderItem["order_product_coating"].'","'.$orderItem["order_product_photo"].'","'.$orderItem["order_product_polar"].'","'.$order_status.'","'.$orderItem["order_total"].'","'.$re_sphere.'","'.$le_sphere.'","'.$re_cyl.'","'.$le_cyl.'","'.$re_add.'","';
		
		$outputstring.=$le_add.'","'.$re_axis.'","'.$le_axis.'","'.$re_pr_ax.'","'.$le_pr_ax.'","'.$re_pr_ax2.'","'.$le_pr_ax2.'","'.$orderItem["re_pr_io"].'","'.$orderItem["re_pr_ud"].'","'.$orderItem["le_pr_io"].'","'.$orderItem["le_pr_ud"].'","'.$re_pd.'","'.$re_pd_near.'","'.$re_height.'","'.$le_pd.'","'.$le_pd_near .'","'.$le_height.'","'.$orderItem["PT"].'","'.$orderItem["PA"].'","'.$orderItem["vertex"].'","'.$orderItem["frame_a"].'","'.$orderItem["frame_b"].'","'.$orderItem["frame_ed"].'","'.$orderItem["frame_dbl"].'","'.$orderItem["frame_type"].'","'.$orderItem["currency"].'","'.$special_instructions.'","'.$orderItem["global_dsc"].'","'.$orderItem["infocus_dsc"].'","'.$orderItem["precision_dsc"].'","'.$orderItem["innovative_dsc"].'","'.$orderItem["visionpro_dsc"].'","'.$orderItem["visionpropoly_dsc"].'","'.$orderItem["additional_dsc"].'","'.$orderItem["discount_type"].'","'.$extra_product.'","'.$orderItem["extra_product_price"].'","'.$orderItem["coupon_dsc"].'","'.$shipping_code.'","'.$engraving.'","'.$tint.'","'.$tint_color.'","'.$from_perc.'","'.$to_perc.'","'.$job_type.'","'.$supplier.'","'.$shape_model.'","'.$frame_model.'","'.$color.'","'.$order_type.'","'.$eye_size.'","'.$bridge.'","'.$temple.'","'
		.$orderItem["patient_ref_num"].'","' .$accNum  . '","' . ' ' . '","'  . $re_sphere_pos .'","' . $re_cyl_pos  .'","' . $re_axis_pos .  '","' . $le_sphere_pos .'","' . $le_cyl_pos  .'","' . $le_axis_pos 	.'"'.chr(13);
			}
			
	return $outputstring;
			
}








function Export_Inventory_IFC($lab_id){

	
	$Query="SELECT ifc_frames_french.*, product_inventory_ifc.product_inventory_id, product_inventory_ifc.min_inventory, product_inventory_ifc.inventory, product_inventory_ifc.last_updated, product_inventory_ifc.product_id
		FROM ifc_frames_french 
		LEFT JOIN product_inventory_ifc ON (product_inventory_ifc.product_id=ifc_frames_french.ifc_frames_id && product_inventory_ifc.lab_id='$lab_id' ) 
		ORDER BY code"; //Get Order Data
	$Result=mysql_query($Query)	or die  ('I cannot select items because: ' . mysql_error());
	
	
	
	while ($orderItem=mysql_fetch_array($Result)){
	
		$outputstring.='"'.$orderItem["upc"].'","'.$orderItem["code"].'","'.$orderItem["type"].'","'.$orderItem["color"].'","'. $orderItem["collection"] .'","' . $orderItem["inventory"].'","'.$orderItem["min_inventory"].'","'.$orderItem["last_updated"].'"'. "\r\n";
			}
			
	return $outputstring;
			
}


?>