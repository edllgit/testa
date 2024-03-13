<?php

//Creates and configures an Exporter instance
function e_config($comment, $filter,$schema, $query){
	$e = new Exporter();
	$e->comment = $comment;
	$e->query = $query;
	$e->filter = $filter;
	$e->schema = $schema;
	return $e;
}


function get_feeds($date){
	$today = $date;

	//Define named feeds, and configure their filter, schema, and initial query
	return array(
		'conant_ifc' 	=> e_config('Conant IFC (Shanghai)', 'filter_conant_ifc', Schemas::$euro, 

			"SELECT orders.primary_key from orders
			LEFT JOIN (exclusive) ON (orders.order_product_id = exclusive.primary_key) 
			WHERE order_status='processing' AND order_product_type='exclusive' AND orders.prescript_lab = 30  AND lab = 37 AND order_date_processed='$today' ORDER by order_num, primary_key"),


		'conant_ifc_ca'	=> e_config('Conant IFC CA (Shanghai)','filter_conant_ifc', Schemas::$euro,

			"SELECT orders.primary_key  from orders
			LEFT JOIN (exclusive) ON (orders.order_product_id = exclusive.primary_key) 
			WHERE order_status='processing' AND order_product_type='exclusive' AND orders.prescript_lab = 30  AND user_id in (SELECT user_id from accounts WHERE product_line = 'ifcclubca') AND order_date_processed='$today' ORDER by order_num"),


		'conant' 		=> e_config("Conant (Shanghai)", 'filter_conant', Schemas::$default,

			"SELECT orders.primary_key  from orders
			LEFT JOIN (exclusive) ON (orders.order_product_id = exclusive.primary_key) 
			WHERE order_status='processing' AND order_product_type='exclusive' AND orders.prescript_lab = 30 and lab <> 37 AND lab <> 47 and user_id NOT IN (SELECT user_id from accounts WHERE product_line = 'ifcclubca') AND 
			order_date_processed='$today' ORDER by order_num"),


		'conant_ait'	=> e_config('Conant Ait (Shanghai)','filter_conant',Schemas::$default,

			"SELECT orders.primary_key  from orders
			LEFT JOIN (exclusive) ON (orders.order_product_id = exclusive.primary_key) 
			WHERE order_status='processing' AND order_product_type='exclusive' AND orders.prescript_lab = 30  AND lab = 47 AND order_date_processed='$today' ORDER by order_num"),

		'dr' 			=> e_config('DR', 'filter_dlab', Schemas::$default,

			"SELECT distinct orders.* from orders
			WHERE   orders.prescript_lab = 22 and orders.order_status='processing' AND orders.order_date_processed='$today' ORDER by orders.order_num"),

		'easyfit' 		=> e_config('EasyFit', 'filter_hko', Schemas::$default,

			"SELECT orders.primary_key from orders
			LEFT JOIN (exclusive) ON (orders.order_product_id = exclusive.primary_key) 
			HERE order_status='processing' AND order_product_type='exclusive' AND orders.prescript_lab = 25 AND order_date_processed='$today' ORDER by order_num"),

		'precision' 	=> e_config('Precision', 'filter_normal', Schemas::$default,
			
			"SELECT orders.primary_key  from orders 	WHERE order_status = 'processing' AND order_product_type='exclusive' AND prescript_lab = 10 AND order_date_processed='$today' ORDER by order_num"),


		'sct' => e_config('SCT', 'filter_dlab', Schemas::$default,
			
			"SELECT distinct orders.* from orders
			WHERE   orders.prescript_lab = 3 and orders.order_status='processing' AND orders.order_date_processed='$today' ORDER by orders.order_num"),

		'somo'=> e_config('SOMO', 'filter_dlab', Schemas::$default,
			
			"SELECT distinct orders.primary_key  from orders
			WHERE   orders.prescript_lab = 35 AND orders.order_date_processed='$today' ORDER by orders.order_num"),

		'vot' => e_config('VOT', 'filter_dlab', Schemas::$default,
			
			"SELECT distinct orders.primary_key  from orders
			WHERE   orders.prescript_lab = 1 and orders.order_status='processing' AND orders.order_date_processed='$today' ORDER by orders.order_num"),

		'somo_stock' => e_config('SOMO Stock Data', 'filter_stock_dlab',Schemas::$stock,
			
			"SELECT distinct(primary_key) from orders WHERE   orders.order_product_type IN('stock_tray','stock') AND orders.prescript_lab = 52 AND orders.order_date_processed='$today' ORDER by orders.order_num"),

	);
}
?>