<?php
include_once '/home/direc54/public_html/labAdmin/admin_functions.inc.php';
include_once '/home/direc54/sec_connect.inc.php';

/****
p (tbl products)
pi (tbl product_inventory)
l (tbl labs)
****/

# delete all records from 30 days past in the supplier_order_report
mysql_query("delete from supplier_order_report where unix_timestamp(date_time) <= '".time()."'");
//end delete


$arr_post = ( count($_POST['order']) > 0 ? $_POST['order'] : array() );

$lab_id = $_POST['lab_id'];

require_once '/home/direc54/public_html/labAdmin/inc.functions.php';
	
$qstring = "select i.product_inventory_id, i.lab_id, dl.lab_name, dl.lab_email, l.lab_name as selected_lab_name, m.manufacturer_id, p.primary_key as product_id, p.product_name, p.d_index, p.coating_brand, p.coating_brand, p.sph_base, p.cyl_add, p.right_opc, p.left_opc, p.mfg as manufacturer, m.email, 
			if(ml.selected_lab_id IS NULL, '', ml.selected_lab_id) as selected_lab_id, if(l.lab_email IS NULL, '', l.lab_email) as selected_lab_email, 
			i.inventory, i.order_total, (i.inventory - i.order_total) as available, pi.minimum_inventory, i.sent
			from product_inventory as i 
			left join product_inventory_notification as pi on pi.lab_id = i.lab_id
			left join products as p on p.primary_key = i.product_id
			left join manufacturer as m on upper(m.name) = upper(p.mfg)
			left join manufacturer_to_lab as ml on ml.manufacturer_id = m.manufacturer_id
			left join labs as l on l.primary_key = ml.selected_lab_id
			left join labs as dl on dl.primary_key = i.lab_id
			where ".($lab_id ? "md5(i.lab_id) = '".$lab_id."' and " : "pi.run_cron=1 and ")."i.sent != 1 and (i.inventory - i.order_total) <= pi.minimum_inventory and p.primary_key IS NOT NULL";

$qstring = "select i.product_inventory_id, i.lab_id, dl.lab_name, dl.lab_email, l.lab_name as selected_lab_name, m.manufacturer_id, p.primary_key as product_id, p.product_name, p.d_index, p.coating_brand, p.coating_brand, p.sph_base, p.cyl_add, p.right_opc, p.left_opc, p.mfg as manufacturer, m.email, 
			if(ml.selected_lab_id IS NULL, '', ml.selected_lab_id) as selected_lab_id, if(l.lab_email IS NULL, '', l.lab_email) as selected_lab_email, 
			i.inventory, i.order_total, (i.inventory - i.order_total) as available, i.min_inventory as minimum_inventory, i.sent
			from product_inventory as i 
			left join product_inventory_notification as pi on pi.lab_id = i.lab_id
			left join products as p on p.primary_key = i.product_id
			left join manufacturer as m on upper(m.name) = upper(p.mfg)
			left join manufacturer_to_lab as ml on ml.manufacturer_id = m.manufacturer_id
			left join labs as l on l.primary_key = ml.selected_lab_id
			left join labs as dl on dl.primary_key = i.lab_id
			where ".($lab_id ? "md5(i.lab_id) = '".$lab_id."' and " : "pi.run_cron=1 and ")."i.sent != 1 and (i.inventory - i.order_total) <= i.min_inventory and p.primary_key IS NOT NULL";


$products = dbFetchArray($qstring);

if( count($products) > 0 )
{
	$arr_order = array();
	foreach($products as $product)
	{
		/*
		$arr_product = array('manufacturer' => $product['manufacturer'], 'product_name' => $product['product_name'], 'right_opc' => $product['right_opc'], 'left_opc' => $product['left_opc'],
							 'order_total' => abs($product['order_total'] - $product['inventory']));
		*/
		$num_to_order = $record['minimum_inventory'] + abs($record['inventory'] - $record['order_total']);
		
		$arr_product = array('product_id' =>  $product['product_id'], 'product_inventory_id' =>  $product['product_inventory_id'], 'manufacturer' => $product['manufacturer'], 'product_name' => $product['product_name'], 'right_opc' => $product['right_opc'], 'left_opc' => $product['left_opc'],
							 'index' => $product['d_index'], 'sphere' => $product['sph_base'], 'cylinder' => $product['cyl_add'], 'coating_brand' => $product['coating_brand'],
							 'order_total' => $num_to_order);
		
		//update product inventory set sent=1
		$qstring = "update product_inventory set sent=1 where product_inventory_id = '".$product['product_inventory_id']."'";
		mysql_query($qstring);
		
		$lab_man_id = $product['lab_id'].'_'.$product['manufacturer_id'];
		if( !is_array($arr_order[$lab_man_id]['products']) )
		{
			$arr_order[$lab_man_id]['lab_id'] = $product['lab_id'];
			$arr_order[$lab_man_id]['lab_name'] = $product['lab_name'];
			$arr_order[$lab_man_id]['lab_email'] = $product['lab_email'];
			$arr_order[$lab_man_id]['send_to']  = (!empty($product['selected_lab_email']) ? $product['selected_lab_email'] : $product['email']);
			$arr_order[$lab_man_id]['products'] = array();
		}
		
		array_push($arr_order[$lab_man_id]['products'], $arr_product);
	}
	
	
	# generate emails
	$default_subject = "[lab_name] is placing a new order.";
	$default_msg = "[lab_name] would like to place an order for the following products below:\n\n";
	$line_count = 30;
	
	if( count($arr_order) > 0 )
	{
		$arr_emails = array();
		foreach($arr_order as $order)
		{
			$msg = str_replace('[lab_name]', $order['lab_name'], $default_msg);

			foreach($order['products'] as $product)
			{
				$order_total = ( array_key_exists($product['product_inventory_id'], $arr_post) ? $arr_post[ $product['product_inventory_id'] ] : $product['order_total'] );
			
				$msg .= str_repeat('-', $line_count)."\n";
				$msg .= 'Manufacturer: '.$product['manufacturer']."\n";
				$msg .= 'Product Name: '.$product['product_name']."\n";
				$msg .= 'Coating: '.$product['coating_brand']."\n";
				$msg .= 'Index: '.$product['index']."\n";
				$msg .= 'Sphere: '.$product['sphere']."\n";
				$msg .= 'Cylinder: '.$product['cylinder']."\n";
				$msg .= 'Right OPC Sku: '.$product['right_opc']."\n";
				$msg .= 'Left OPC Sku: '.$product['left_opc']."\n";
				$msg .= 'Number to Ship: '.$order_total."\n";
				
				mysql_query("update product_inventory set order_total='".$order_total."' where product_inventory_id='".$product['product_inventory_id']."'");
				
				//add to new table for reporting
				$qstring = "insert into supplier_order_report (lab_id,product_id,order_total) values ('".$order['lab_id']."','".$product['product_id']."','".$order_total."')";
				mysql_query($qstring);
			}
			
			array_push($arr_emails, array('lab_name' => $order['lab_name'], 'lab_email' => $order['lab_email'], 'email_to' => $order['send_to'], 'email_msg' => $msg.str_repeat('-', $line_count)."\n"));
		}
	}
	
	# send emails
	if( count($arr_emails) > 0 )
	{
		foreach($arr_emails as $email)
		{
			$subject = str_replace('[lab_name]', $email['lab_name'], $default_subject);
			
			mail($email['email_to'], $subject, $email['email_msg'], "From: ".$email['lab_email']);
		}
	}
}

if(!empty($lab_id))
{
	header("Location: orders_completed.php");
	exit;
}	
?>