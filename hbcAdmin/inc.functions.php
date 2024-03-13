<?php
/*
	Configuration / Functions File 
	These functions are include and being used on the labAdmin/inventory.php page
*/
define('RESULTS_PER_PAGE', 15);

Function modifyInventory($order_num, $action = 'increase')
{
	global $dbh;
	
	$qstring = "select lab, order_product_id, order_product_name, order_product_index, order_product_coating, re_sphere, re_cyl, order_quantity from orders where order_num='".$order_num."'";
	$records = dbFetchArray($qstring);

	if( count($records) > 0 )
	{
		$arr_product_inventory = array();
		
		foreach($records as $record)
		{
			//get notification settings
			$iNotify = dbFetchArray("select notification_email, notification_subject, notification_message, minimum_inventory from product_inventory_notification where lab_id='".$record['lab']."'");
			$iNotifyRecord = $iNotify[0];
			
			//get the product information
			$pRecords = dbFetchArray("select primary_key as product_id, product_name, d_index, coating_brand, sph_base, cyl_add from products where product_name='".$record['order_product_name']."' and d_index='".$record['order_product_index']."' and coating_brand='".$record['order_product_coating']."' and sph_base='".$record['re_sphere']."' and cyl_add='".$record['re_cyl']."'");
			$pRecord = $pRecords[0];
			
			//get the product inventory record
			$iRecords = dbFetchArray("select product_inventory_id, inventory, order_total from product_inventory where lab_id='".$record['lab']."' and product_id='".$pRecord['product_id']."'");
			$iRecord  = $iRecords[0];
			//print('<pre>');
			//print_r($iRecord);
			//
			
			//update product inventory
			//$new_inventory = $iRecord['inventory']-$record['order_quantity'];
			//$qstring = "update product_inventory set inventory='".$new_inventory."' where product_inventory_id='".$iRecord['product_inventory_id']."'";
			if( $action == 'increase' )
			{
				$new_order_total = $iRecord['order_total']+$record['order_quantity'];
				
				if( $new_order_total <= 0 ) $new_order_total = 0;
				
				$qstring = "update product_inventory set order_total='".$new_order_total."', sent=0 where product_inventory_id='".$iRecord['product_inventory_id']."'";
				mysql_query($qstring);
			}
			elseif( $action == 'reduce' )
			{
				$new_order_total = $iRecord['order_total']-$record['order_quantity'];
				$new_inventory_total = $iRecord['inventory']+$record['order_quantity'];
				
				if( $new_order_total <= 0 ) $new_order_total = 0;
				
				$qstring = "update product_inventory set order_total='".$new_order_total."', inventory='".$new_inventory_total."', sent=0 where product_inventory_id='".$iRecord['product_inventory_id']."'";
//print("select product_inventory_id, inventory, order_total from product_inventory where lab_id='".$record['lab']."' and product_id='".$pRecord['product_id']."'<br>");
//print($qstring);
//exit;
				//mysql_query($qstring);
			}
			else
			{
				$new_order_total = $iRecord['order_total'];
				
				if( $new_order_total <= 0 ) $new_order_total = 0;
				
				$qstring = "update product_inventory set order_total='".$new_order_total."', sent=0 where product_inventory_id='".$iRecord['product_inventory_id']."'";
				mysql_query($qstring);
			}
			$qstring1 = "SELECT min_inventory FROM product_inventory WHERE product_inventory_id='".$iRecord['product_inventory_id']."'";
			$tmp = dbFetchArray($qstring1);
			$tmp1 = $tmp[0];
			$iNotifyRecord['minimum_inventory'] = $tmp1['min_inventory'] != "" ? intval($tmp1['min_inventory']) : $iNotifyRecord['minimum_inventory'];
			//check notification minimum product inventory, if less than or equal to, send email			
			if( $iNotifyRecord['minimum_inventory'] >= $new_order_total )
			{
				//get product information
				$pRecords = dbFetchArray("select primary_key, product_name, coating_brand, sph_base, cyl_add from products where primary_key='".$record['order_product_id']."'");
				$pRecord = $pRecords[0];
				
				$product_info = 'Product Name: '.$pRecord['product_name'].' / Coating: '.$pRecord['coating_brand'].' / Sphere: '.$pRecord['sph_base'].' / Cylinder: '.$pRecord['cyl_add']."\n".
								'Current Inventory for this Product: '.$new_order_total;
				array_push($arr_product_inventory, $product_info);
			}
			//
		}
		
		//send email if there were products that were at or below the notification settings
		if( count($arr_product_inventory) > 0 && $action == 'increase' )
		{
			$email_to 		= $iNotifyRecord['notification_email'];
			$email_subject 	= $iNotifyRecord['notification_subject'];
			$email_message 	= $iNotifyRecord['notification_message']."\n\n";
			$email_message .= 'Minimum Product Inventory Setting: '.$iNotifyRecord['minimum_inventory']."\n\n";
			$email_message .= implode("\n\n", $arr_product_inventory);
			mail($email_to, $email_subject, $email_message, 'From: Direct-Lens.com <support@direct-lens.com>');
		}
	}
}

Function updateInventory($post)
{
	global $dbh, $assigned_lab_id;
	
	$inventory = $post['inventory'];
	
	if( count($inventory) > 0 )
	{
		foreach($inventory as $key => $value)
		{
			if( stristr($key, 'upd') )
			{
				$split = explode('_', $key);
				$inventory_id = $split[1];
				
				$qstring = "update product_inventory set inventory='".$value."', sent=0 where product_inventory_id='".$inventory_id."'";
			}
			else
			{
				$qstring = "insert into product_inventory (lab_id,product_id,inventory,sent) values ('".$assigned_lab_id."','".$key."','".$value."',0)";
			}
			
			mysql_query($qstring);
		}
	}
	
	header("Location: ".buildLink($_SERVER['PHP_SELF'], array(), array()));
	exit;
}

Function updateInventorySettings($post)
{
	global $dbh, $assigned_lab_id;
	
	$qstring = "select notification_id from product_inventory_notification where lab_id='".$assigned_lab_id."'";
	$records = dbFetchArray($qstring);
	
	if( count($records) > 0 )
	{
		$qstring = "update product_inventory_notification set notification_email='".$post['notification_email']."', notification_subject='".$post['notification_subject']."', 
					notification_message='".$post['notification_message']."', minimum_inventory='".$post['minimum_inventory']."', run_cron='".($post['run_cron'] ? '1' : '0')."'
					where notification_id='".$records[0]['notification_id']."'";
	}
	else
	{
		$qstring = "insert into product_inventory_notification (lab_id,notification_email,notification_subject,notification_message,minimum_inventory,run_cron) values 
					('".$assigned_lab_id."','".$post['notification_email']."','".$post['notification_subject']."','".$post['notification_message']."','".$post['minimum_inventory']."','".($post['run_cron'] ? '1' : '0')."')";
	}
			
	mysql_query($qstring);
	
	//update manufacturer to lab email notifications
	$manufacturers = $_POST['selected_lab_id'];
	
	if( count($manufacturers) > 0 )
	{
		foreach($manufacturers as $man_id => $lab_id)
		{
			$qstring = "select man_lab_id, lab_id, manufacturer_id, selected_lab_id from manufacturer_to_lab where lab_id='".$assigned_lab_id."'
						and manufacturer_id='".$man_id."'";
			$records = dbFetchArray($qstring);

			if( !empty($lab_id) )
			{				
				if( count($records) > 0 )
				{
					$qstring = "update manufacturer_to_lab set selected_lab_id='".$lab_id."'
								where lab_id='".$assigned_lab_id."' and manufacturer_id='".$man_id."'";
					mysql_query($qstring);
				}
				else
				{
					$qstring = "insert into manufacturer_to_lab (lab_id,manufacturer_id,selected_lab_id) values 
								('".$assigned_lab_id."','".$man_id."','".$lab_id."')";
					mysql_query($qstring);						
				}
			}
			else
			{
				$qstring = "delete from manufacturer_to_lab where lab_id='".$assigned_lab_id."' and manufacturer_id='".$man_id."'";
				mysql_query($qstring);
			}
		}
	}
	
	header("Location: ".buildLink($_SERVER['PHP_SELF'], array('upd'=>1), array()));
	exit;
}

Function dbFetchArray($qstring)
{
	global $dbh;
	
	$arrayRecords = array();
	$queryId = mysql_query($qstring, $dbh);
	$fieldCount = mysql_num_fields($queryId);

	$arrayCount = 0;
	while($dbRow = mysql_fetch_array( $queryId )) {
		for($fieldNum = 0; $fieldNum < $fieldCount; $fieldNum++) {
			$fieldName = mysql_field_name( $queryId, $fieldNum );
			$arrayRecords[ $arrayCount ][ $fieldName ] = $dbRow[ $fieldName ];
		}
		
		$arrayCount++;
	}
	
	return $arrayRecords;
}	

Function buildLink($Url, $addOption, $removeOption){
	if( $_GET ){ $allOptions = $_GET; }else{ $allOptions = array(); }
	
	foreach($addOption as $optKey => $optValue){ $allOptions[ $optKey ] = $optValue; }
	
	foreach($allOptions as $optKey => $optValue){
		if( !in_array($optKey, $removeOption) ){
			if( !$valSet ){
				$urlLink = $Url . '?' . $optKey . '=' . $optValue;
				$valSet = 1;
			}else{
				$urlLink .= '&' . $optKey . '=' . $optValue;
			}
		}
	}

	return ($urlLink ? $urlLink : $Url);
}
?>