<?php

class Schemas{
//This maps XML fields to columns in the primary query (mostly the orders table)
//Comments document fields pulled from other tables
	static $xmlToOrders; 
	static $xmlToAccounts;
	static $default;
	static $euro;
	static $stock;
	static $ifc;
}

Schemas::$xmlToOrders = array(
		'orderKey' => 'primary_key',
		'orderNumber' => 'order_num',
		'userId' => 'user_id',
		'purchaseOrder' => 'po_num',
		'trayNumber' => 'tray_num',
		//labName
		//pLabName
		'labNumber' => 'lab',
		'pLabNumber' => 'prescript_lab',
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
		'globalDiscount' => 'global_dsc',
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
Schemas::$xmlToAccounts = array(
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
Schemas::$default = array('orderKey','LINE ID',
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
		'additionalDiscount','ADDITIONAL DISCOUNT',
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

Schemas::$stock = array_merge(Schemas::$default,array(
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


Schemas::$euro = array_merge(Schemas::$default,array(
		're_sphere_pos','RE SPHERE POS',
		're_cyl_pos','RE CYLINDER POS',
		're_axis_pos','RE AXIS POS',
		'le_sphere_pos','LE SPHERE POS',
		'le_cyl_pos','LE CYLINDER POS',
		'le_axis_pos','LE AXIS POS'

	));

Schemas::$ifc = array(
	'company','NOM DU MAGASIN',
	//'fullName','NOM CLIENT',
	'productName','PRODUIT',

	'productCode','CODE ARTICLE',
	'templateModelNumber','REFERENCE MONTURE',
	'orderQty','QTE',
	'fullName','NOM PORTEUR',
	'orderNumber','NUMERO COMMANDE',
	'orderDateProcessed', 'DATE COMMANDE',
	'orderTotal', 'MONTANT TOTAL',

	);	

?>