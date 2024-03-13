<?php


function normalize_prescription_values(&$row){

	//This function normalizes a value in an array, resolving dashes and u-hats into '0'
	$transferEye = function($suffix,$unused, $prefix) use (&$row){
		$key = $prefix . $suffix;
		if (isset($row[$key])){
			$val = $row[$key];
			//If 0-length, -, or u^ set to "0"
			if ((strlen($val)<1) || ($val == '-') || ($val == chr(251)))
				$row[$key] = "0";
		}else{
			$row[$key] = "0";
		}
	};


	$suffixes = array('pd','sphere','cyl','pr_ax2','pr_ax','axis','add','height','pd_near','pr_io','pr_ud');

	array_walk($suffixes,$transferEye,"le_");
	array_walk($suffixes,$transferEye,"re_");

	//If neither or only left eye specified, clear right eye values
	if (empty($row['eye']) || strcasecmp($row['eye'],"L.E.")){
		foreach($suffixes as $key)
			$row["re_".$key] = "0";
	}
	//If neither or only right eye specified, clear left eye values
	if (empty($row['eye']) || strcasecmp($row['eye'],"R.E.")){
		foreach($suffixes as $key)
			$row["le_".$key] = "0";
	}
}

function add_engraving(&$row){
	$r = q_array("select engraving from extra_product_orders WHERE order_num='$row[orderNumber]' AND category='Engraving'"); //Get ENGRAVING
	if ($r) $row['engraving'] = $r['engraving'];
	 //todo: may need to insert empty elements if no match
}

function add_tint(&$row){

	$r = q_array("select tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$row[orderNumber]' AND category='Tint'"); //Get TINT
	if ($r) {
		$tintMap = array(
			'tint' => 'tint',
			'tint_color' => 'tintColor',
			'from_perc' => 'tintFrom',
			'to_perc' => 'tintTo'
			);
		map_fields($tintMap,$r,$row);

	} //todo: may need to insert empty elements if no match
				
}

function add_follow_lens_shape(&$row){
	//Is there a point to this requery? Why can't we just use the original query?
	$r = q_array("Select myupload from orders  where order_num ='$row[orderNumber]'");

	$row['followLensShape'] = $r['myupload'];
	//This doesn't seem right, is there no 'false' value for the column?
	if (!empty($r['myupload']))
		$row['followLensShapeYesNo'] = "Yes"; //todo, should move to true/false when possible
	else
		$row['followLensShapeYesNo'] = "No";
				
}

function add_edging(&$row){
	$r = q_array("select * from extra_product_orders WHERE order_num='$row[orderNumber]' AND category='Edging'"); //Get EDGING
	if ($r){
		$edgingMap = array(
			'supplier' => 'supplier',
			'shapeModel' => 'model',
			'frameModel' => 'temple_model_num',
			'frameType' => 'frame_type',
			'jobType' => 'job_type',
			'color' => 'color',
			'orderType' => 'order_type',
			//'eyeSize' => 'eye_size',
			//'bridge' => 'bridge',
			'temple' => 'temple');
		map_fields_inv($edgingMap,$r,$row);

	}
}

function add_product_codes(&$row){
	$r = q_array("select product_code,color_code from exclusive WHERE primary_key='$row[productId]'"); //Get product codes
	if ($r){
		$row['colorCode']=$r['color_code'];
		$row['productCode']=$r['product_code'];
	}
}


function add_ifc_product_codes(&$row){
	$r = q_array("select product_code,color_code from ifc_exclusive WHERE primary_key='$row[productId]'"); //Get product codes
	if ($r){
		$row['colorCode']=$r['color_code'];
		$row['productCode']=$r['product_code'];
	}
}



function map_order_status($status){
	$status = trim($status);
	switch($status){
		case 'processing':				return "Confirmed";			break;
		case 'job started':				return "In Production";		break;
		case 'filled':					return "Shipped";			break;
		default: return ucwords($status);
	}
}

function map_coating($coating){
	$coating = trim($coating);
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
function map_frame_type($frame){
	$frame = trim($frame);
	switch($frame){				
		case 'Métal': return "Metal"; break;
		case 'Fil Nylon': return "Nylon Groove"; break;
		case 'Percé': return "Drill and Notch"; break;
		case 'Fil Métal': return "Metal Groove"; break;
		case 'Plastique': return "Plastic"; break;		
		default: return $frame; break;
	}
	
}

function is_HKO_coating($name){
	$names= array('Dream AR','DH2','Smart AR','Hard Coat','ITO AR','Aqua Dream AR','MultiClear AR','Smart AR','Uncoated');

	foreach($names as $val)
		if (strcasecmp($name, $val) == 0) return true;
	return false;
}

//Query functions
function get_basic_order_row($order_key){
	//Get order, account, and lab info
	$o = q_array("SELECT orders.*,accounts.*,labs.lab_name,plabs.lab_name as plab_name FROM
	 orders LEFT OUTER JOIN accounts ON accounts.user_id=orders.user_id 
	 LEFT OUTER JOIN labs ON labs.primary_key = orders.lab
	 LEFT OUTER JOIN labs as plabs ON plabs.primary_key = orders.prescript_lab
	 WHERE orders.primary_key='$order_key'");

	if (empty($o)) die("Failed to find order by pkey $order_key");

	//Copy fields using name map
	$row = array();
	//Copy order fields
	$row = map_fields_inv(Schemas::$xmlToOrders, $o, $row);
	//Copy account fields
	$row = map_fields_inv(Schemas::$xmlToAccounts ,$o,$row);
	//Copy lab names
	$row['labName'] = $o['lab_name'];
	$row['pLabName'] = $o['plab_name'];


	//Escape certain fields for no documented reason
	$row['specialInstructions'] = addslashes($row['specialInstructions']);
	$row['additionalItem'] = addslashes($row['additionalItem']);

	return $row;
}


function get_normal_order($order_key){
	$row = get_basic_order_row($order_key);

	add_product_codes($row);
	add_engraving($row);
	add_tint($row);
	add_follow_lens_shape($row);
	add_edging($row);		
	normalize_prescription_values($row); 
	return $row;
}

function filter_normal(&$row){
	$row = get_normal_order($row['primary_key']);

	$row['orderStatus'] = map_order_status($row['orderStatus'] );
	set_fields($row,array('productPrice','productDiscountPrice','couponDiscount','orderTotal'),'xxx');
	$row['followLensShape'] = $row['followLensShapeYesNo'];
	$row['shippingCode'] = $row['accountShippingCode'];
	return $row;
}

function filter_hko(&$row){
	$row = get_normal_order($row['primary_key']);

	$row['orderStatus'] = map_order_status($row['orderStatus'] );
	$row['followLensShape'] = $row['followLensShapeYesNo'];

	set_fields($row,array('productPrice','productDiscountPrice','couponDiscount','orderTotal'),'xxx');

	//We calculate shipping instead of using the DB value
	//Modification request by HKO  2010-08-11
	if (is_HKO_coating($row['productCoating'])) $row['shippingCode'] = "NA108";	


	//code qui modifie le shipping code si le main lab est lens net USA o AITLENSCLUB
	if ($row['labNumber']=='32' || $row['labNumber']=='47') $row['shippingCode'] = "NA310";

	//Just this function has this mapping - may be a mistake
	if ($row['productCoating']=='Dream AR') $row['productCoating']= "CR+ETC";
	
	$row['productCoating'] = map_coating($row['productCoating']);
	return $row;
}

function filter_conant(&$o){
	$row = get_normal_order($o['primary_key']);

	$row['orderStatus'] = map_order_status($row['orderStatus'] );
	set_fields($row,array('productPrice','productDiscountPrice','couponDiscount','orderTotal'),'xxx');

	//use Account shipping code
	$row['shippingCode'] = $row['accountShippingCode'];
	//Modification request by HKO  2010-08-11
	if (is_HKO_coating($row['productCoating'])) $row['shippingCode'] = "NA108";	

	$row['productCoating'] = map_coating($row['productCoating']);

	if ($row['labNumber']==37)
		add_ifc_product_codes($row);
	
	if (isset($row["frameType"]) && $row["frameType"]=="Plastique") $row["frameType"] = "Plastic";
	$row['followLensShape'] = $row['followLensShapeYesNo'];

	return $row;
}


function filter_dlab(&$original){
	$row = get_normal_order($original['primary_key']);
	
	$row['orderStatus'] = map_order_status($row['orderStatus'] );
	set_fields($row,array('productPrice','productDiscountPrice','couponDiscount','orderTotal'),'xxx');
	$row['productCoating'] = map_coating($row['productCoating']);

	$row['shippingCode'] = $row['accountShippingCode'];

	if ($row['followLensShape'] =="") $row['followLensShape'] = "none";		
	return $row;
}



//use with stockSchema
function filter_stock_dlab(&$row){
	$row = get_normal_order($row['primary_key']);

	$row['orderStatus'] = map_order_status($row['orderStatus'] );
	set_fields($row,array('productPrice','productDiscountPrice','couponDiscount','orderTotal'),'xxx');
	$row['productCoating'] = map_coating($row['productCoating']);



	//Replace order item number
	$opc=q_array("select right_opc, left_opc from products WHERE primary_key ='$row[productId]'");
	$row['orderItemNumber'] = $opc['right_opc'];

	//Use shape_name_bk field
	if ($row['shapeNameBk'] =="") {
		$row['followLensShape'] = "none";
	}else{
		$$row['followLensShape'] = $row['shapeNameBk'];
	}

	return $row;	
}


function filter_conant_ifc(&$row){
	$row = get_normal_order($row['primary_key']);

	$row['orderStatus'] = map_order_status($row['orderStatus'] );
	set_fields($row,array('productPrice','productDiscountPrice','couponDiscount','orderTotal'),'xxx');
	//Modification request by HKO  2010-08-11
	if (is_HKO_coating($row['productCoating'])) $shipping_code = "NA108";	
	$row['productCoating'] = map_coating($row['productCoating']);

	//IFC product code mgmt
	if ($row['labNumber']==37) add_ifc_product_codes($row);
	

	//Custom tint adjustments
	if (isset($row['tint'])){
		if ($row['tint'] == 'Brun')
			$row['tintColor'] = 'Brown';
						
		if ($row['tint']  == 'Solid 60'){
			$row['tint'] ='Solid';
			$row['tintFrom'] =60;
			$row['tintTo'] =60;
			$row['tintColor']  = 	$row['tintColor'] . '-' .  $row['tintFrom']  . '%';
		}
		
		if ($row['tint']  == 'Solid 80'){
			$row['tint'] ='Solid';
			$row['tintFrom']=82;
			$row['tintTo']=82;
			$row['tintColor']  = 	$row['tintColor'] . '-' .  $row['tintFrom']  . '%';
		}
	}
	

	//Custom frame queries
				
	$edge=q_array("select * from extra_product_orders WHERE order_num='$row[orderNumber]' AND category='Edging_Frame'"); //Get EDGING
	if ($edge){
		$row['frameType'] = $edge['frame_type'];
		$row['supplier'] = $edge['supplier'];
		$row['shapeModel'] = $edge['model'];
		$row['jobType'] = $edge['job_type'];
		$row['orderType'] = $edge['order_type'];
		$row['temple'] = $edge['temple'];

		$model = q_array("select * from ifc_frames_french WHERE (code ='$edge[temple_model_num]' OR  model ='$edge[temple_model_num]') AND color ='$edge[color]' "); 
		$row['frameModel'] = $model['upc'];
		$row['color'] = $model['color_code'];
	}
			
	if ($row['frameType']=="Plastique") $row['frameType'] = "Plastic";
	
	
				
	//TODO-port value conversion
	$row['re_sphere_pos'] = $row['re_sphere'];
	$row['le_sphere_pos'] = $row['le_sphere'];
	$row['re_cyl_pos'] = $row['re_cyl'];
	$row['le_cyl_pos'] = $row['le_cyl'];
	$row['re_axis_pos'] = $row['re_axis'];
	$row['le_axis_pos'] = $row['le_axis'];
	
			
	//European conversion for Conant
	if ($row['re_cyl'] <> '0'){
		$row['re_sphere'] = $row['re_sphere'] + $row['re_cyl'];
		if ($row['re_sphere'] > 0) $row['re_sphere'] = "+".$row['re_sphere'];
		$row['re_cyl'] ="-".ABS($row['re_cyl']);
		$row['re_axis'] = $row['re_axis'] + 90;
		if ($row['re_axis'] > 180) $row['re_axis'] = $row['re_axis'] -180;
	}	

	if ($row['le_cyl'] <> '0'){
		$row['le_sphere'] = $row['le_sphere'] + $row['le_cyl'];
		if ($row['le_sphere'] > 0) $row['le_sphere'] = "+".$row['le_sphere'];
		$row['le_cyl'] ="-".ABS($row['le_cyl']);
		$row['le_axis'] = $row['le_axis'] + 90;
		if ($row['le_axis'] > 180) $row['le_axis'] = $row['le_axis'] -180;
	}	
	return $row;		
}






function export_order_IFC($order_num){
/*
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
	
		$queryFrm = "SELECT  temple_model_num   FROM extra_product_orders WHERE category = 'Frame' and order_num  =". $orderItem["order_num"] ;
		$resultFrm=mysql_query($queryFrm)	or die  ('I cannot select items because: ' . mysql_error());
		$NbrResult = mysql_num_rows($resultFrm);
		if ($NbrResult > 0){
		$DataFrm=mysql_fetch_array($resultFrm);
		
		
		$QueryProductCode = "SELECT  product_code   FROM ifc_exclusive WHERE primary_key = ". $orderItem["order_product_id"] ;
		echo $QueryProductCode . '<br>';
		$resultProductCode=mysql_query($QueryProductCode)	or die  ('I cannot select items because: ' . mysql_error());
		$NbrdeResult = mysql_num_rows($resultProductCode);
		if ($NbrdeResult > 0){
		$DataProductCode=mysql_fetch_array($resultProductCode);
		}
		
		
		}
		
		
		//$outputstring.= $orderItem["order_num"].','.$orderItem["product_code"].','.$orderItem["patient_ref_num"] .','.$realTotal  . "\r\n";
		$outputstring.= $accItem["company"]  .','.$orderItem["order_product_name"]  .','.$DataProductCode["product_code"] .','.$DataFrm["temple_model_num"] .','.$orderItem["order_quantity"] .','.$orderItem["order_patient_first"] 
		. ' ' .$orderItem["order_patient_last"] .','.$orderItem["order_num"] .','.$orderItem["order_date_processed"]  .','.$realTotal  . "\r\n";
		

					


	return $pairs;*/
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




function get_credit_row($order_num){
	$r = q_array("select memo_credits.*,accounts.account_num from memo_credits, accounts WHERE accounts.user_id=accounts.mcred_acct_user_id AND mcred_order_num='$order_num'"); //Get credit Data
	$r[mcred_amount]  = ($r[mcred_cred_type] <> 'debit') ? '-'.$r[mcred_abs_amount] : $r[mcred_abs_amount];
	return $r;
}

function export_credit_SOI($order_num){
	
	$r = get_credit_row($order_num);

	$schema = array(
		'account_num','Account#',
		'mcred_order_num','Order#',
		'mcred_date','Date',
		'mcred_amount','Amount'
		);
	
	return apply_csv_schema($r,$schema);

/*
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
	return $outputstring;	*/
}




function export_credit_Conso($order_num){
	$r = get_credit_row($order_num);

		$schema = array(
			'mcred_memo_num','Memo#',
			'mcred_date','Date',
			'mcred_date','Date',
			'mcred_amount','Amount',
			'null','Unused',
			'account_num','Account#',
			'mcred_acct_user_id','User#',
		);
	
	return apply_csv_schema($r,$schema);


	/*	 if ($orderItem["mcred_cred_type"] =="debit"){
		$outputstring = "";		
		$outputstring.=  $orderItem["mcred_memo_num"]. ',' . $orderItem["mcred_date"]. ','.$orderItem["mcred_date"].',' . $orderItem["mcred_abs_amount"] . ',' . " " . ',' . $accNum . ',' . $orderItem["mcred_acct_user_id"]  .  "\r\n"   ;
		}else{
		$outputstring = "";		
		$outputstring.=  $orderItem["mcred_memo_num"]. ',' . $orderItem["mcred_date"]. ','.$orderItem["mcred_date"].',-' . $orderItem["mcred_abs_amount"] . ',' . " " . ',' . $accNum . ',' . $orderItem["mcred_acct_user_id"]  .  "\r\n" ;
		}
		
			}	
	return $outputstring;		*/
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