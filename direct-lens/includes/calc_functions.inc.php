<?php 

function getExtraProdTotal($order_num){
	include "../sec_connectEDLL.inc.php";	
	$e_query="SELECT * from extra_product_orders WHERE order_num='$order_num'";//GET EXTRA PRODUCT PRICES
	$e_result=mysqli_query($con,$e_query)		or die  ('I cannot select items because: ' . mysqli_error($con).$e_query);
	$e_usercount=mysqli_num_rows($e_result);
	$e_total_price=0;
	
	if ($e_usercount !=0){
		while ($e_listItem=mysqli_fetch_array($e_result,MYSQLI_ASSOC)){
				$e_total_price=$e_total_price+$e_listItem[price]+$e_listItem[high_index_addition];
				
		}//END WHILE
	}//END IF
	$e_total_price=money_format('%.2n',$e_total_price);
	return $e_total_price;
}

function calculateTotal($order_num){
	include "../sec_connectEDLL.inc.php";
	$query="SELECT * FROM orders 
	LEFT JOIN (additional_discounts) ON (orders.primary_key=additional_discounts.orders_id) 
	WHERE orders.order_num='$order_num'";
		
	$result=mysqli_query($con,$query) or die ("Could not select item because". mysqli_error($con));
		
	$orderTotal=0;
	$itemSubtotal=0;
		
	while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		$total_quantity=$listItem[order_quantity];
		
		if ($listItem[order_product_type]=="exclusive"){
			
				$buying_level_dsc=$listItem[buying_level_discount];
		
				$additional_dsc=$listItem[additional_dsc];
				$discount_type=$listItem[discount_type];
				$extra_product_price=$listItem[extra_product_price];
				$over_range=$listItem[order_over_range_fee];
				$coupon_dsc=$listItem[coupon_dsc];
				
				$itemSubtotal=$listItem[order_quantity]*$listItem[order_product_discount]+$over_range-$coupon_dsc+$buying_level_dsc;
				$itemSubtotal=money_format('%.2n',$itemSubtotal);
				$orderTotal=$orderTotal+$itemSubtotal;
			}
		else if ($listItem[order_product_type]=="stock_tray")
		{
				$additional_dsc=$listItem[additional_dsc];
				$discount_type=$listItem[discount_type];
				$extra_product_price=$listItem[extra_product_price];
				
				$itemSubtotal=$itemSubtotal+$listItem[order_product_discount];
				$itemSubtotal=money_format('%.2n',$itemSubtotal);
				$orderTotal=$itemSubtotal;
		}
		else if ($listItem[order_product_type]=="stock")
		{
				$additional_dsc=$listItem[additional_dsc];
				$discount_type=$listItem[discount_type];
				$extra_product_price=$listItem[extra_product_price];
				
				$itemSubtotal=$listItem[order_quantity]*$listItem[order_product_discount];
				
				$itemSubtotal=money_format('%.2n',$itemSubtotal);
				$orderTotal=$orderTotal+$itemSubtotal;
		}
		else if ($listItem[order_product_type]=="frame_stock_tray")
		{
				$additional_dsc=$listItem[additional_dsc];
				$discount_type=$listItem[discount_type];
				$extra_product_price=$listItem[extra_product_price];
				
				$itemSubtotal=$itemSubtotal+ $listItem[order_quantity] * $listItem[order_product_discount];
				$itemSubtotal=money_format('%.2n',$itemSubtotal);
				$orderTotal=$itemSubtotal;
		}
	} //END WHILE
	
	 if ($additional_dsc!=0){
			if ($discount_type=="$"){
					$totalDiscount=$additional_dsc;
				}
			else if ($discount_type=="%"){
					$totalDiscount=money_format('%.2n',$orderTotal*($additional_dsc/100));
				}
			  
		}
		
	$e_total_price=getExtraProdTotal($order_num); //GET EXTRA PRODUCTS TOTAL
	$e_total_price=$e_total_price*$total_quantity;
	$orderTotal=$orderTotal+$extra_product_price-$totalDiscount+$e_total_price;
	
	$orderTotal=money_format('%.2n',$orderTotal);
return $orderTotal;

}


function calculateTotalV2($order_num){
	include "../sec_connectEDLL.inc.php";
	$query="SELECT * FROM orders 
	WHERE orders.order_num='$order_num'";
	$result=mysqli_query($con,$query)	or die ("Could not select item because". mysqli_error($con));
		
	$orderTotal=0;
	$itemSubtotal=0;
		
	while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		$total_quantity=1;
		
		if ($listItem[order_product_type]=="exclusive"){
			
				$additional_dsc=$listItem[additional_dsc];
				$discount_type=$listItem[discount_type];
				$extra_product_price=$listItem[extra_product_price];
				$over_range=$listItem[order_over_range_fee];
				$coupon_dsc=$listItem[coupon_dsc];
				
				$itemSubtotal=$total_quantity*$listItem[order_product_discount]+$over_range-$coupon_dsc;
				$itemSubtotal=money_format('%.2n',$itemSubtotal);
				$itemSubtotalBK = $itemSubtotal;
				$orderTotal=$orderTotal+$itemSubtotal;
				//echo "<br>Order total = Ordertotal($orderTotal) +itemsoustotal($$itemSubtotalBK)<br>";
			}
	} //END WHILE
	//echo "<br>Order total apres le while ($orderTotal)";
	
	 if ($additional_dsc!=0){
			if ($discount_type=="$"){
					$totalDiscount=$additional_dsc;
				}
			else if ($discount_type=="%"){
					$totalDiscount=money_format('%.2n',$orderTotal*($additional_dsc/100));
				}
			  
		}
	

	$e_total_price=getExtraProdTotal($order_num); //GET EXTRA PRODUCTS TOTAL
	$e_total_price=$e_total_price*$total_quantity;
	$orderTotal=$orderTotal+$extra_product_price-$totalDiscount+$e_total_price;
	
	$orderTotal=money_format('%.2n',$orderTotal);
return $orderTotal;

}





function addOrderTotal($order_num,$total){
	include "../sec_connectEDLL.inc.php";
	$query="UPDATE orders SET order_total='$total' WHERE order_num='$order_num'";
	$result=mysqli_query($con,$query) or die ("Could not select item");
		
}



?>
