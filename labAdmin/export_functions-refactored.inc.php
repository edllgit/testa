<?php

//This maps XML fields to columns in the primary query (mostly the orders table)
//Comments document fields pulled from other tables
$xmlToOrdersTable = array(
		'orderKey' => 'primary_key',
		'orderNumber' => 'order_num',
		'userId' => 'user_id',
		'purchaseOrder' => 'po_num',
		'trayNumber' => 'tray_num',
		//labName
		//pLabName
		'labNumber' => 'lab',
		'pLabNumber' => 'prescript_lab'
		'eye' => 'eye',
		'orderItemNumber' => 'order_item_number',
		'orderDateProcessed' => 'order_date_processed',
		'orderDateShipped' => 'order_date_shipped',
		'orderDate' => 'order_item_date',
		'orderQty' => 'order_quantity',
		'patientFirstName' => 'order_patient_first',
		'patientLastName' => 'order_patient_last',
		'salespersonId' => 'salesperson_id',
		//productCode
		//colorCode
		'productName' => 'order_product_name',
		'productId' => 'order_product_id',
		'productIndex' => 'order_product_index',
		'productMaterial' => 'order_product_material',
		'productPrice' => 'order_product_discount',
		'productDiscountPrice' => 'order_product_discount',
		'shippingCost' => 'order_shipping_cost',
		'shippingMethod' => 'order_shipping_method',
		'overRangeFee' => 'order_over_range_fee',
		'productType' => 'order_product_type',
		'productCoating' => 'order_product_coating',
		'productPhoto' => 'order_product_photo',
		'productPolar' => 'order_product_polar',
		'orderStatus' => 'order_status',
		'orderTotal' => 'order_total',
		're_sphere' => 're_sphere',
		'le_sphere' => 'le_sphere',
		're_cyl' => 're_cyl',
		'le_cyl' => 'le_cyl',
		're_add' => 're_add',
		'le_add' => 'le_add',
		're_axis' => 're_axis',
		'le_axis' => 'le_axis',
		're_pr_ax' => 're_pr_ax',
		'le_pr_ax' => 'le_pr_ax',
		're_pr_ax2' => 're_pr_ax2',
		'le_pr_ax2' => 'le_pr_ax2',
		're_pr_io' => 're_pr_io',
		're_pr_ud' => 're_pr_ud',
		'le_pr_io' => 'le_pr_io',
		'le_pr_ud' => 'le_pr_ud',
		're_pd' => 're_pd',
		're_pd_near' => 're_pd_near',
		're_height' => 're_height',
		'le_pd' => 'le_pd',
		'le_pd_near' => 'le_pd_near',
		'le_height' => 'le_height',
		'PT' => 'PT',
		'PA' => 'PA',
		'vertex' => 'vertex',
		'frame_a' => 'frame_a',
		'frame_b' => 'frame_b',
		'frame_ed' => 'frame_ed',
		'frame_dbl' => 'frame_dbl',
		'frame_type' => 'frame_type',
		'currency' => 'currency', 
		'specialInstructions' => 'special_instructions',
		'globalDiscount' => 'gloabl_dsc',
		'infocusDiscount' => 'infocus_dsc',
		'precisionDiscount' => 'precision_dsc',
		'innovativeDiscount' => 'innovative_dsc',
		'visionProDiscount' => 'visionpro_dsc',
		'visionProPolyDiscount' => 'visionpropoly_dsc',
		'additionalDiscount' => 'additional_dsc',
		'additionalDiscountType' => 'discount_type',
		'additionalItem' => 'extra_product',
		'additionalItemPrice' => 'extra_product_price',
		'couponDiscount' => 'coupon_dsc',
		'shippingCode' => 'shipping_code', 
		/*
		engraving
		tint
		tint color
		from perc
		to prec
		job type
		supplier
		shape model
		frame model
		color
		order type
		eye size
		bridge
		temple */
		'patientReferenceNumber' => 'patient_ref_num',

		/*accountNumber
		followLensShape
		accountShippingCode

		*/
		'shapeNameBk' => 'shape_name_bk',
	);

//Maps XML fields to columns in the accounts table
$xmlToAccountsTable = array(
	'accountNumber' => 'account_num',
	'accountShippingCode' => 'shipping_code',
	'company' => 'company',
	'ship_address1' => 'ship_address1',
	'ship_address2' =>	'ship_address2',
	'ship_city' =>'ship_city',
	'ship_state' =>'ship_state' ,
	'ship_zip' =>'ship_zip',
	'ship_country' =>'ship_country',
	'depot_number' =>'depot_number',
	'bill_to' =>'bill_to',
	);




//Multi-purpose schemas to follow -these filter and sort XML keys for xml output, and map xml->csv for csv output
$defaultSchema = array('orderKey','LINE ID',
		'orderNumber' ,'ORDER NUMBER',
		'userId','USER ID',
		'purchaseOrder', 'PO NUMBER',
		'trayNumber' ,'TRAY REFERENCE NUMBER',
		'labName' ,'MAIN LAB NAME',
		'pLabName' ,'PRESCRIPTION LAB NAME',
		'eye' , 'EYE',
		'orderItemNumber','ORDER ITEM NUMBER',
		'orderDateProcessed' ,'ORDER DATE PLACED',
		'orderDateShipped' , 'ORDER DATE SHIPPED',
		'orderDate' , 'ORDER DATE BASKET',
		'orderQty' ,'ORDER QUANTITY',
		'patientFirstName','PATIENT FIRST NAME',
		'patientLastName', 'PATIENT LAST NAME',
		'salespersonId','SALES PERSON ID',
		'productCode','PRODUCT CODE',
		'colorCode','COLOR CODE',
		'productName', 'PRODUCT NAME',
		'productId', 'ORDER PRODUCT ID',
		'productIndex','ORDER PRODUCT INDEX',
		'productMaterial','ORDER PRODUCT MATERIAL',
		'productPrice','ORDER PRODUCT PRICE',
		'productDiscountPrice','ORDER PRODUCT DISCOUNT PRICE',
		'shippingCost','ORDER SHIPPING COST',
		'shippingMethod','ORDER SHIPPING METHOD',
		'overRangeFee','ORDER OVER RANGE FEE',
		'productType','ORDER PRODUCT TYPE',
		'productCoating','ORDER PRODUCT COATING',
		'productPhoto','ORDER PRODUCT PHOTO',
		'productPolar','ORDER PRODUCT POLAR',
		'orderStatus','ORDER STATUS',
		'orderTotal','ORDER TOTAL',
		're_sphere','RE SPHERE',
		'le_sphere','LE SPHERE',
		're_cyl','RE CYLINDER',
		'le_cyl','LE CYLINDER',
		're_add','RE ADDITION',
		'le_add','LE ADDITION',
		're_axis','RE AXIS',
		'le_axis','LE AXIS',
		're_pr_ax','RE PR AX',
		'le_pr_ax','LE PR AX',
		're_pr_ax2','RE PR AX2',
		'le_pr_ax2','LE PR AX2',
		're_pr_io','RE_PR_IO',
		're_pr_ud','RE_PR_UD',
		'le_pr_io','LE_PR_IO',
		'le_pr_ud','LE_PR_UD',
		're_pd','RE PD',
		're_pd_near','RE PD NEAR',
		're_height','RE HEIGHT',
		'le_pd','LE PD',
		'le_pd_near','LE PD NEAR',
		'le_height','LE HEIGHT',
		'PT','PT',
		'PA','PA',
		'vertex','VERTEX',
		'frame_a','FRAME A',
		'frame_b','FRAME B',
		'frame_ed','FRAME ED',
		'frame_dbl','FRAME DBL',
		'frame_type','FRAME TYPE',
		'currency', 'CURRENCY',
		'specialInstructions','SPECIAL INSTRUCTIONS',
		'globalDiscount','GLOBAL DISCOUNT',
		'infocusDiscount','INFOCUS DISCOUNT',
		'precisionDiscount','PRECISION DISCOUNT',
		'innovativeDiscount','MY WORLD DISCOUNT',
		'visionProDiscount','VISION PRO DISCOUNT',
		'visionProPolyDiscount','VISION PRO POLY DISCOUNT',
		'additionalDiscount','ADDITIONAL DISCOUNT",'
		'additionalDiscountType','ADDITIONAL DISCOUNT TYPE',
		'additionalItem', 'ADDITIONAL ITEM',
		'additionalItemPrice', 'ADDITIONAL ITEM PRICE',
		'couponDiscount','COUPON DSC',
		'shippingCode','SHIPPING CODE',
		'engraving','ENGRAVING',
		'tint','TINT',
		'tintColor','TINT COLOR',
		'tintFrom','FROM PERC',
		'tintTo','TO PERC',
		'jobType','JOB TYPE',
		'supplier','SUPPLIER',
		'shapeModel','SHAPE MODEL',
		'frameModel','FRAME MODEL',
		'color','COLOR',
		'orderType','ORDER TYPE',
		'eyeSize','EYE SIZE',
		'bridge','BRIDGE',
		'temple','TEMPLE',
		'patientReferenceNumber','PATIENT REF NUM',
		'accountNumber','ACCOUNT NUM',
		'followLensShape','FOLLOW LENS SHAPE');

$stockSchema = array_merge($defaultSchema,array(
		'company','COMPANY',
		'ship_address1','SHIP ADDRESS 1',
		'ship_address2','SHIP ADDRESS 2',
		'ship_city','SHIP CITY',
		'ship_state','SHIP STATE',
		'ship_zip','SHIP ZIP',
		'ship_country','SHIP COUNTRY',
		'depot_number', 'DEPOT NUMBER',
		'bill_to','BILL TO'


	));


$euroSchema = array_merge($defaultSchema,array(
		're_sphere_pos','RE SPHERE POS',
		're_cyl_pos','RE CYLINDER POS',
		're_axis_pos','RE AXIS POS',
		'le_sphere_pos','LE SPHERE POS',
		'le_cyl_pos','LE CYLINDER POS',
		'le_axis_pos','LE AXIS POS'

	));

$ifcSchema = array(
	'company','NOM DU MAGASIN',
	//'fullName','NOM CLIENT',
	'productName','PRODUIT',

	'productCode','CODE ARTICLE'
	'templateModelNumber','REFERENCE MONTURE',
	'orderQty','QTE',
	'fullName','NOM PORTEUR',
	'orderNumber','NUMERO COMMANDE',
	'orderDateProcessed', 'DATE COMMANDE',
	'orderTotal', 'MONTANT TOTAL',

	);	


//Array utility functions
function pair_array($array){
	$n = array();
	for($i = 0, $size = count($array); $i < $size-1; $i += 2) {
		$n[$array[$i]] = $array[$i + 1];
	return $n;
}

function copy_fields(&$map, &$from, &$to){
	foreach($map as &$key => &$value)
		$to[$key] = $from[$value];
}

function q_array($query){
	$result = mysql_query($query) or die ("Failed to complete query $query\n". mysql_error());
	return mysql_fetch_array($result);
}

function set_fields(&$array, &$fields, $value){
	foreach($fields as $key)
		$array[$key] = $value;
}

function apply_xml_schema(&$data, &$schema){
	$n = array();
	for($i = 0, $size = count($schema); $i < $size-1; $i += 2) {
		$key = $schema[$i];
		$n[$key] = isset($data[$key]) ?  $data[$key] : "";
	}
	return $n;
}
function apply_csv_schema(&$data, &$schema){
	$n = array();
	for($i = 0, $size = count($schema); $i < $size-1; $i += 2) {
		$key = $schema[$i];
		$n[] = isset($data[$key]) ? $data[$key] : "";
	}
	return $n;
}
function get_header_for(&$schema){
	$n = array();
	for($i = 0, $size = count($schema); $i < $size-1; $i += 2) {
		$value = $schema[$i + 1];
		$n[] = $value;
	}
	return $n;
}

function write_xml_row(&$writer, &$values){
	$writer->startElement('row');
	foreach($values as $key, $value){
		$writer->startElement($key);
		$writer->text($value);
		$writer->endElement();
	}
	$writer->endElement();
}

class Exporter{
	public $targetUri ="php://output";
	public $query;
	public $schema;
	public $format = 'xml';
	public $includeHeaderRow = true;
	public $filter

	public function __construct(){
		$targetUri = fopen("php://output", "w"); 
	}

	public function export(){
		$csv = strcasecmp($format, 'csv') == 0;
		//$tdf = strcasecmp($format, 'tdf') == 0;
		$xml = strcasecmp($format, 'xml') == 0;

		if ($csv)
			$f = fopen($targetUri,'w');

		if ($csv  && $includeHeaderRow){
			fputcsv($f,get_header_for($schema));
		}
		if ($xml){
			$w = new XmlWriter();
			$w->openURI($targetUri);
			$w->startDocument();
			$w->startElement('rows');
		}

		//Run main query
		$result=mysql_query($query)	or die  ('I cannot select items because: ' . mysql_error());
		while ($r = mysql_fetch_array($result)){
			//Get completed row
			$r = call_user_func($filter,$r);

			//Limit to schema and serialize
			if ($csv){
				fputcsv($f,apply_csv_schema($r, $schema));
			}else if ($xml){
				write_xml_row($w,apply_xml_schema($r, $schema))
			}

		}
		if ($xml){
			$w->endDocument();
			$w->flush();
		}else{
			fclose($f);
		}

	}

}


//Query functions
function get_basic_order_row($order_key){
	//Get order, account, and lab info
	$o = q_array("SELECT orders.*,accounts.*,labs.lab_name,plabs.lab_name as plab_name FROM orders,accounts,labs,labs AS plabs WHERE
	 accounts.user_id=orders.user_id AND labs.primary_key = orders.lab AND plabs.primary_key= orders.prescript_lab AND primary_key='$order_key'")

	//Copy fields using name map
	$row = array();
	//Copy order fields
	copy_fields($xmlToOrdersTable, $o, $row);
	//Copy account fields
	copy_fields($xmlToAccountsTable,$o,$row);
	//Copy lab names
	$row[labName] = $o[lab_name];
	$row[pLabName] = $o[plab_name];

	//Escape certain fields for no documented reason
	$row[specialInstructions] = addslashes($row[specialInstructions]);
	$row[additionalItem] = addslashes($row[additionalItem]);
}


function normalize_prescription_values(&$row){

	//This function normalizes a value in an array, resolving dashes and u-hats into '0'
	$transferEye = function($suffix,$unused, $prefix) use (&$row){
		$val = $row[$prefix + $suffix];
		//If 0-length, -, or u^ set to "0"
		if ((strlen($val)<1) || ($val == '-') || ($val == chr(251)))
			$row[$prefix + $suffix] = "0";
	}


	$suffixes = array('pd','sphere','cyl','pr_ax2','pr_ax','axis','add','height','pd_near'); //,'pr_io','pr_ud');
	array_walk($suffixes,$transferEye,"le_");
	array_walk($suffixes,$transferEye,"re_");

	//If neither or only left eye specified, clear right eye values
	if ($o[eye] ==  "" || strcasecmp($o[eye],"L.E.")){
		foreach($suffixes as $key)
			$row["re_".$key] = "0";
	}
	//If neither or only right eye specified, clear left eye values
	if ($o[eye] ==  "" || strcasecmp($o[eye],"R.E.")){
		foreach($suffixes as $key)
			$row["le_".$key] = "0";
	}
}

function add_engraving(&$row){
	$r = q_array("select engraving from extra_product_orders WHERE order_num='$row[orderNumber]' AND category='Engraving'"); //Get ENGRAVING
	if ($r) $row[engraving] = $r[engraving];
	 //todo: may need to insert empty elements if no match
}

function add_tint(&$row){

	$r = q_array("select tint,tint_color,from_perc,to_perc from extra_product_orders WHERE order_num='$row[orderNumber]' AND category='Tint'"); //Get TINT
	if ($r) {
		$row[tint] = $r[tint];
		$row[tintColor] = $r[tint_color];
		$row[tintFrom] = $r[from_perc];
		$row[tintTo] = $r[to_perc];

	} //todo: may need to insert empty elements if no match
				
}

function add_follow_lens_shape(&$row){
	//Is there a point to this requery? Why can't we just use the original query?
	$r = q_array("Select myupload from orders  where order_num ='$row[orderNumber]'");

	$row[followLensShape] = $r[myupload]
	//This doesn't seem right, is there no 'false' value for the column?
	if ($r[myupload] <> "")
		$row[followLensShapeYesNo] = "Yes"; //todo, should move to true/false when possible
	else
		$row[followLensShapeYesNo] = "No";
				
}

function add_edging(&$row){
	$r = q_array("select * from extra_product_orders WHERE order_num='$row[orderNumber]' AND category='Edging'"); //Get EDGING
	if ($r){
		$row[supplier] = $r[supplier];
		$row[shapeModel] = $r[model];
		$row[frameModel] = $r[temple_model_num];
		$row[jobType] = $r[job_type];
		$row[color] = $r[color];
		$row[orderType] = $r[order_type];
		$row[eyeSize] = $r[eye_size];
		$row[bridge] = $r[bridge];
		$row[temple] = $r[temple];

	}
}

function add_product_codes(&$row){
	$r = q_array("select product_code,color_code from exclusive WHERE primary_key='$row[productId]'"); //Get product codes
	if ($r){
		$row[colorCode]=$r[color_code];
		$row[productCode]=$r[product_code];
	}
}


function add_ifc_product_codes(&$row){
	$r = q_array("select product_code,color_code from ifc_exclusive WHERE primary_key='$row[productId]'"); //Get product codes
	if ($r){
		$row[colorCode]=$r[color_code];
		$row[productCode]=$r[product_code];
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
	$row[orderStatus] = map_order_status($row[orderStatus] );
	set_fields($row,array('productPrice','productDiscountPrice','couponDiscount','orderTotal'),'xxx');
	$row[followLensShape] = $row[followLensShapeYesNo];
	$row[shippingCode] = $row[accountShippingCode];
}

function filter_HKO(&$row){
	$row[orderStatus] = map_order_status($row[orderStatus] );
	$row[followLensShape] = $row[followLensShapeYesNo];

	set_fields($row,array('productPrice','productDiscountPrice','couponDiscount','orderTotal'),'xxx');

	//We calculate shipping instead of using the DB value
	//Modification request by HKO  2010-08-11
	if (is_HKO_coating($row[productCoating])) $row[shippingCode] = "NA108";	


	//code qui modifie le shipping code si le main lab est lens net USA o AITLENSCLUB
	if ($row[labNumber]=='32' || $row[labNumber]=='47') $row[shippingCode] = "NA310";

	//Just this function has this mapping - may be a mistake
	if ($row[productCoating]=='Dream AR') $row[productCoating]= "CR+ETC";
	
	$row[productCoating] = map_coating($row[productCoating]);

}

function filter_Conant(&$row){
	$row[orderStatus] = map_order_status($row[orderStatus] );
	set_fields($row,array('productPrice','productDiscountPrice','couponDiscount','orderTotal'),'xxx');

	//use Account shipping code
	$row[shippingCode] = $row[accountShippingCode];
	//Modification request by HKO  2010-08-11
	if (is_HKO_coating($row[productCoating])) $row[shippingCode] = "NA108";	

	$row[productCoating] = map_coating($row[productCoating]);

	if ($row[labNumber]==37)
		add_ifc_product_codes($row);
	
	if ($row["frameType"]=="Plastique") $row["frameType"] = "Plastic";
	$row[followLensShape] = $row[followLensShapeYesNo];
}


function filter_DLAB(&$row){

	$row[orderStatus] = map_order_status($row[orderStatus] );
	set_fields($row,array('productPrice','productDiscountPrice','couponDiscount','orderTotal'),'xxx');
	$row[productCoating] = map_coating($row[productCoating]);

	$row[shippingCode] = $row[accountShippingCode];

	if ($row[followLensShape] =="") $row[followLensShape] = "none";		
}




function filter_stock_DLAB(&$row){

	$row[orderStatus] = map_order_status($row[orderStatus] );
	set_fields($row,array('productPrice','productDiscountPrice','couponDiscount','orderTotal'),'xxx');
	$row[productCoating] = map_coating($row[productCoating]);



	//Replace order item number
	$opc=q_array("select right_opc, left_opc from products WHERE primary_key ='$row[productId]'")
	$row[orderItemNumber] = $opc[right_opc];

	//Use shape_name_bk field
	if ($row[shapeNameBk] =="") {
		$row[followLensShape] = "none";
	}else{
		$$row[followLensShape] = $row[shapeNameBk];
	}


	//use stockSchema
			
}


function filter_Conant_IFC(&$row){
	$row = get_normal_order($row[primary_key]);

	$row[orderStatus] = map_order_status($row[orderStatus] );
	set_fields($row,array('productPrice','productDiscountPrice','couponDiscount','orderTotal'),'xxx');
	//Modification request by HKO  2010-08-11
	if (is_HKO_coating($row[productCoating])) $shipping_code = "NA108";	
	$row[productCoating] = map_coating($row[productCoating]);

	//IFC product code mgmt
	if ($row[labNumber]==37) add_ifc_product_codes($row);
	

	//Custom tint adjustments

	if ($row[tint] == 'Brun')
		$row[tintColor] = 'Brown';
					
	if ($row[tint]  == 'Solid 60'){
		$row[tint] ='Solid';
		$row[tintFrom] =60;
		$row[tintTo] =60;
		$row[tintColor]  = 	$row[tintColor] . '-' .  $row[tintFrom]  . '%';
	}
	
	if ($row[tint]  == 'Solid 80'){
		$row[tint] ='Solid';
		$row[tintFrom]=82;
		$row[tintTo]=82;
		$row[tintColor]  = 	$row[tintColor] . '-' .  $row[tintFrom]  . '%';
	}
	

	//Custom frame queries
				
	$edge=q_array("select * from extra_product_orders WHERE order_num='$row[orderNumber]' AND category='Edging_Frame'"); //Get EDGING
	if ($edge){
		$row[frameType] = $edge[frame_type];
		$row[supplier] = $edge[supplier];
		$row[shapeModel] = $edge[model];
		$row[jobType] = $edge[job_type];
		$row[orderType] = $edge[order_type];
		$row[eyeSize] = $edge[eye_size];
		$row[bridge] = $edge[bridge];
		$row[temple] = $edge[temple];

		$model = q_array("select * from ifc_frames_french WHERE (code ='$edge[temple_model_num]' OR  model ='$edge[temple_model_num]') AND color ='$edge[color]' "); 
		$row[frameModel] = $model[upc];
		$row[color] = $model[color_code];
	}
			
	if ($row[frameType]=="Plastique") $row[frameType] = "Plastic";
	
	
				
	//TODO-port value conversion
	$row[re_sphere_pos] = $row[re_sphere];
	$row[le_sphere_pos] = $row[le_sphere];
	$row[re_cyl_pos] = $row[re_cyl];
	$row[le_cyl_pos] = $row[le_cyl];
	$row[re_axis_pos] = $row[re_axis];
	$row[le_axis_pos] = $row[le_axis];
	
			
	//European conversion for Conant
	if ($row[re_cyl] <> '0'){
		$row[re_sphere] = $row[re_sphere] + $row[re_cyl]
		if ($row[re_sphere] > 0) $row[re_sphere] = "+".$row[re_sphere];
		$row[re_cyl] ="-".ABS($row[re_cyl]);
		$row[re_axis] = $row[re_axis] + 90;
		if ($row[re_axis] > 180) $row[re_axis] = $row[re_axis] -180;
	}	

	if ($row[le_cyl] <> '0'){
		$row[le_sphere] = $row[le_sphere] + $row[le_cyl]
		if ($row[le_sphere] > 0) $row[le_sphere] = "+".$row[le_sphere];
		$row[le_cyl] ="-".ABS($row[le_cyl]);
		$row[le_axis] = $row[le_axis] + 90;
		if ($row[le_axis] > 180) $row[le_axis] = $row[le_axis] -180;
	}	
	return $row;		
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
		

			}		




}

 





	return $pairs;
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