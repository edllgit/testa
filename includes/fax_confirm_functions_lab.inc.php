<?php
require_once(__DIR__.'/../constants/url.constant.php');

function sendFaxStockConfirmationLab($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$faxNum){//STOCK BULK AND TRAY CONFIRMATION

	$headers = "From:".$fromAddress."\r\n";
	$mime_boundary=md5(time()); 
	$headers .= 'MIME-Version: 1.0'."\r\n"; 
	$headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\""."\r\n"; 
					
	$headers .= "--".$mime_boundary."\r\n"; 
	$headers .=	"Content-Type: text/html; charset=\"iso-8859-1\"\r\n";
	$headers .= "Content-Transfer-Encoding: 8bit"."\r\n"; 
						
	$subject ="fax -html ".$faxNum;
	$message='<html xmlns="http://www.w3.org/1999/xhtml"><head><base href="'.constant('DIRECT_LENS_URL').'/processOrder.php">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ORDER</title>
</head>';
	
		$message.='<br><br><table width="650" border="1" align="center" cellpadding="5" cellspacing="0"><tr>';
	$message.='<td><div>Your Direct-Lens Order #:'.$orderNum.'</div></td></tr></table>';
	
	$query="select po_num from orders WHERE order_num='$orderNum'"; //GET ORDER PO NUMBER
		$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
		
	$listItem=mysql_fetch_array($result);
	
		$message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0"><tr><td align="left">PO Order #: ';
		$message.=$listItem[po_num];
		$message.='</td></tr></table><br>';
	
	$query="select * from accounts WHERE user_id='$user_id'"; //ACCOUNT INFO SECTION
		$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
		
	$listItem=mysql_fetch_array($result);
	$usercount=mysql_num_rows($result);
	
	$message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0"><tr ><td colspan="2" bgcolor="#FFFFFF">ACCOUNT INFORMATION </td></tr>';
	$message.='<tr ><td align="right">Name on Account :</td>';
    $message.='<td width="520"><strong>';
	$message.=$listItem[title].' '.$listItem[first_name].' '.$listItem[last_name];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right">Account Number :</td>';
    $message.='<td width="520"><strong>';
	$message.=$listItem[account_num];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right">Company:</td><td width="520"><strong>';
	$message.=$listItem[company];
	$message.='</strong> </td></tr><tr ><td align="right">Buying Group: </td><td><strong>';
	$message.=$_SESSION["sessionUserData"]["bg_name"];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Shipping Code: </td><td class="formCellNosides"><strong>';
	$message.=$_SESSION["sessionUserData"]["shipping_code"];
	$message.='</strong></td></tr>';
	$message.='</table><br>';
	
	$message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0"><tr ><td colspan="2" bgcolor="#FFFFFF">BILLING ADDRESS </td></tr><tr ><td width="130" align="right">Address 1:</td><td width="520"><strong>';
	$message.=$listItem[bill_address1];
	$message.='</strong></td></tr><tr ><td width="130" align="right">Address 2:</td><td width="520"><strong>';
$message.=$listItem[bill_address2];
$message.='</strong></td></tr><tr ><td align="right">City:</td><td width="520"><strong>';
$message.=$listItem[bill_city];
$message.='</strong></td></tr><tr ><td align="right">State:</td><td width="520"><strong>';
$message.=$listItem[bill_state];
$message.='</strong> </td></tr><tr ><td align="right">Postal Code:  </td><td><strong>';
$message.=$listItem[bill_zip];
$message.='</strong></td></tr><tr><td align="right">Country:</td><td><strong>';
$message.=$listItem[bill_country];
$message.='</strong></td></tr></table><br>';//END of Address Section

$totalTrayQuant=0;
$totalBulkQuant=0;
$prescrQuantity=0;

//BEGIN STOCK BY TRAY SECTION
$query2="SELECT * from orders WHERE user_id='$user_id' and order_num='$orderNum' and order_product_type='stock_tray' ORDER by order_item_date,primary_key";//SELECT ALL OPEN STOCK ORDERS

			$result2=mysql_query($query2)
					or die  ('I cannot select items because: ' . mysql_error().$query2);
			$stockusercount=mysql_num_rows($result2);
			if ($stockusercount != 0){
			
			 $message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0"><tr ><td bgcolor="#FFFFFF">STOCK ITEMS � BY TRAY</td></tr></table><br>';
			
				while ($listItem=mysql_fetch_array($result2)){
					$order_shipping_method=$listItem[order_shipping_method];
					$currency=$listItem[currency];
					$order_shipping_cost=$listItem[order_shipping_cost];
					$counter++;
					$itemSubtotal=$itemSubtotal+$listItem[order_product_price];
					
					$itemSubtotalDsc=$itemSubtotalDsc+$listItem[order_product_discount];
					
					$totalTrayQuant=$totalTrayQuant+$listItem[order_quantity];
					if ($counter%2==0){
					$itemSubtotal=money_format('%.2n',$itemSubtotal);
						//BEGIN LE SECTION
						
						$message.='<tr>
                <td align="right" valign="top">';
				$message.=$listItem[eye];
				$message.='</td>
                <td align="center"  valign="top">';
				$message.=$listItem[order_product_name];
				$message.='</td>
                <td align="center" valign="top">';
				$message.=$listItem[order_product_material];
				$message.='</td>
                <td align="center" valign="top">';
				$message.=$listItem[order_product_index];
				$message.='</td>
                <td align="center" valign="top">';
				$message.=$listItem[order_product_coating];
				$message.='</td>
                <td align="center" valign="top">';
				$message.=$listItem[re_sphere];
				$message.='</td>
                <td align="center" valign="top">';
				$message.=$listItem[re_cyl];
				$message.='</td>
                <td align="right" valign="top"><b>$';
				$message.=$listItem[order_product_price];
				$message.='</b><br>Subtotal: ';
				$message.=$itemSubtotal;
				$message.='</td>
              </tr>
            </table>';
						
						//END LE SECTION
						
						$totalPrice=$totalPrice+$itemSubtotal;
						$totalStockPrice=$totalStockPrice+$itemSubtotal;
						
						$totalPriceDsc=$totalPriceDsc+$itemSubtotalDsc;
						$totalStockPriceDsc=$totalStockPriceDsc+$itemSubtotalDsc;
						$itemSubtotalDsc=0;
						
						$itemSubtotal=0;}
					else{
						//BEGIN RE SECTION
						
						$message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0">
         <tr >
                <td colspan="7" bgcolor="#FFFFFF">Tray Reference - ';
				$message.=$listItem[tray_num];
				$message.='</td>
            
                <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
              <tr >
                <td bgcolor="#FFFFFF">&nbsp;</td>
                <td  align="center" bgcolor="#FFFFFF"><strong>Product</strong></td>
                <td  align="center" bgcolor="#FFFFFF"><strong>Material</strong></td>
                <td align="center" bgcolor="#FFFFFF"><strong>Index</strong></td>
                <td align="center" bgcolor="#FFFFFF"><strong>Coating</strong></td>
                <td align="center" bgcolor="#FFFFFF"><strong>Sphere</strong></td>
                <td align="center" bgcolor="#FFFFFF"><strong>Cylinder</strong></td>
                <td align="center" bgcolor="#FFFFFF"><strong>Price</strong></td>
              </tr>
              <tr>
                <td align="right">';
				$message.=$listItem[eye];
				$message.='</td>
                <td align="center">';
				$message.=$listItem[order_product_name];
				$message.='</td>
                <td align="center">';
				$message.=$listItem[order_product_material];
				$message.='</td>
                <td align="center">';
				$message.=$listItem[order_product_index];
				$message.='</td>
                <td align="center">';
				$message.=$listItem[order_product_coating];
				$message.='</td>
                <td align="center">';
				$message.=$listItem[re_sphere];
				$message.='</td>
                <td align="center">';
				$message.=$listItem[re_cyl];
				$message.='</td>
                <td align="right" valign="top"><b>$';
				$message.=$listItem[order_product_price];
				$message.='</b></td>
              </tr>';
						
						//END RE SECTIOn
											}//END IF
					} //END WHILE
			}// END OF STOCK BY TRAY SECTION


//BEGIN STOCK BY BULK SECTION
$query2="SELECT * from orders WHERE user_id='$user_id' and order_num='$orderNum' and order_product_type='stock' ORDER by order_item_date";//SELECT ALL OPEN STOCK ORDERS

			$result2=mysql_query($query2)
					or die  ('I cannot select items because: ' . mysql_error().$query2);
			$stockusercount=mysql_num_rows($result2);
			if ($stockusercount != 0){
			
			 $message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0" class="formBox"><tr ><td bgcolor="#FFFFFF">STOCK ITEMS � BULK</td></tr></table><br>';
			
				while ($listItem=mysql_fetch_array($result2)){
					$order_shipping_method=$listItem[order_shipping_method];
					$currency=$listItem[currency];
					$order_shipping_cost=$listItem[order_shipping_cost];
					$itemSubtotal=0;
					$itemSubtotal=$listItem[order_quantity]*$listItem[order_product_price];
					$totalPrice=$totalPrice+$itemSubtotal;
					$totalStockPrice=$totalStockPrice+$itemSubtotal;
					
					$itemSubtotalDsc=$listItem[order_quantity]*$listItem[order_product_discount];
					$totalPriceDsc=$totalPriceDsc+$itemSubtotalDsc;
					$totalStockPriceDsc=$totalStockPriceDsc+$itemSubtotalDsc;
					
					$totalBulkQuant=$totalBulkQuant+$listItem[order_quantity];
					$itemSubtotal=money_format('%.2n',$itemSubtotal);
					
					
	$message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0"><tr ><td colspan="7" bgcolor="#FFFFFF" >Product - ';
	$message.=$listItem[order_product_name];
	 $message.='</td></tr><tr >';
	 $message.='<td width="77" bgcolor="#FFFFFF"><strong>Material</strong></td>';
	 $message.='<td width="53" bgcolor="#FFFFFF"><strong>Index</strong></td>';
	 $message.='<td width="74" align="center" bgcolor="#FFFFFF"><strong>Coating</strong></td>';
	 $message.='<td width="69" align="center" bgcolor="#FFFFFF"><strong>Sphere</strong></td>';
	 $message.='<td width="129" align="center" bgcolor="#FFFFFF"><strong>Cylinder</strong></td>';
	$message.='<td align="center" bgcolor="#FFFFFF"><strong>Quantity</strong></td>';
	$message.='<td align="center" bgcolor="#FFFFFF"><strong>Price</strong></td>';
	$message.='</tr><tr><td align="right">';
	$message.=$listItem[order_product_material];
	$message.='</td><td align="right">';
	$message.=$listItem[order_product_index];
	$message.='</td><td align="center">';
	$message.=$listItem[order_product_coating];
	$message.='</td><td align="center">';
	$message.=$listItem[re_sphere];
	$message.='</td><td align="center">';
	$message.=$listItem[re_cyl];
	$message.='</td><td align="center" valign="top">';
	$message.=$listItem[order_quantity];
	$message.='</td><td align="right" valign="top"><b>$';
	$message.=$listItem[order_product_price];
	$message.='</b><br>Subtotal:$';
	$message.=$itemSubtotal;
	$message.='</td></tr></table><br>';
					} 
			}// END OF STOCK BY BULK SECTION

//BEGINNING OF TOTALS SECTION


$message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0"><tr >';
$message.='<td align="left">Total Quantites</td>';
$message.='<td align="left">Stock by Tray:';
$message.=$totalTrayQuant;
$message.='&nbsp;&nbsp;&nbsp;&nbsp;Stock by Bulk:';
$message.=$totalBulkQuant;
$message.='&nbsp;&nbsp;&nbsp;&nbsp;Prescription:';
$message.=$prescrQuantity;
$message.='</td></tr></table><br>';

  $message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0"><tr >';
$message.='<td align="left">Shipping:';
$message.=$order_shipping_method;
$message.='</td>';
$message.='<td align="right" valign="middle">';
$message.=$order_shipping_cost;
$message.='</td></tr>';
$message.='<tr ><td width="524" align="left">Order Total with Your Account Discount</td>';
$message.='<td width="100" align="right" valign="middle"><b>$';
				
				$totalPriceDsc=money_format('%.2n',$totalPriceDsc+$order_shipping_cost);
	$message.=$totalPriceDsc." ".$currency; 
			
	$message.='</b></td></tr></table><br>';
			
	$message.="</body></html>";

	$inviteSent = mail("$send_to_address", "$subject", "$message", "$headers");
}

function sendFaxPrescriptionConfirmationLab($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$sendPrices,$faxNum){//PRESCRIPTION CONFIRMATION

	//$headers = "From:".$fromAddress."\r\n";
	$headers = "From:orders@direct-lens.com\r\n";
	$mime_boundary=md5(time()); 
	$headers .= 'MIME-Version: 1.0'."\r\n"; 
	$headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\""."\r\n"; 
					
	$headers .= "--".$mime_boundary."\r\n"; 
	$headers .=	"Content-Type: 	text/html; charset=\"iso-8859-1\"\r\n";
	$headers .= "Content-Transfer-Encoding: 8bit"."\r\n"; 
						
	$subject ="fax -html ".$faxNum;
	$message='<html xmlns="http://www.w3.org/1999/xhtml"><head><base href="'.constant('DIRECT_LENS_URL').'/processOrder.php">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ORDER</title>
</head>';
	
	$query="select po_num, redo_order_num from orders WHERE order_num='$orderNum'"; //GET ORDER PO NUMBER AND REDO ORDER NUMBER
	$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
	$listItem=mysql_fetch_array($result);
		
	$message.='<br><br><table width="650" border="1" align="center" cellpadding="0" cellspacing="0"><tr><td align="left">';
	$message.='<td><div class="header2">Your Direct-Lens Order #:'.$orderNum;
	
	if ($listItem[redo_order_num]!=0){
	$message.="R (".$listItem[redo_order_num].")";	
	}
	
	$message.='</div></td></tr></table>';
	
	$message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0"><tr><td align="left">PO Order #: ';
	$message.=$listItem[po_num];
	$message.='</td></tr></table><br>';
	
	$query="select * from accounts WHERE user_id='$user_id'"; //ACCOUNT INFO SECTION
	$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
		
	$listItem=mysql_fetch_array($result);
	$usercount=mysql_num_rows($result);

$totalTrayQuant=0;
$totalBulkQuant=0;
$prescrQuantity=0;

	$message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0"><tr ><td colspan="2" bgcolor="#FFFFFF" >ACCOUNT INFORMATION </td></tr>';
	$message.='<tr ><td align="right">Name on Account :</td>';
    $message.='<td width="520"><strong>';
	$message.='xxxx';
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right">Account Number :</td>';
    $message.='<td width="520"><strong>';
	$message.=$listItem[account_num];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right">Company:</td><td width="520"><strong>';
	$message.='xxxx';
	$message.='</strong> </td></tr><tr ><td align="right">Buying Group: </td><td><strong>';
	$message.='xxxx';
		$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Shipping Code: </td><td class="formCellNosides"><strong>';
	$message.=$_SESSION["sessionUserData"]["shipping_code"];
	$message.='</strong></td></tr>';
	$message.='</table><br>';
			
			//BEGIN PRESCRIPTION SECTION
			
$query="SELECT * from orders WHERE user_id='$user_id' and order_num='$orderNum' and order_product_type='exclusive' ORDER by order_item_date";//SELECT ALL OPEN PRESCRIPTION ORDERS
			$result=mysql_query($query)
					or die  ('I cannot select items because: ' . mysql_error().$query);
			$usercount=mysql_num_rows($result);
			if ($usercount != 0){
			
			 $message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0">
              <tr >
                <td bgcolor="#FFFFFF">PRESCRIPTION ITEMS</td>
              </tr>
            </table><br>';
					
					while ($listItem=mysql_fetch_array($result)){
						
					$bl_query="SELECT * from additional_discounts WHERE orders_id='$listItem[primary_key]'";//GET BUYING LEVEL DISCOUNT
					$bl_result=mysql_query($bl_query)
						or die  ('I cannot select bl items because: ' . mysql_error().$bl_query);
					$bl_listItem=mysql_fetch_array($bl_result);
					$buying_level_dsc=$bl_listItem[buying_level_discount];
					
					$e_query="SELECT * from extra_product_orders WHERE order_id='$listItem[primary_key]'";//GET EXTRA PRODUCT PRICES
					$e_result=mysql_query($e_query)
						or die  ('I cannot select items because: ' . mysql_error().$e_query);
					$e_usercount=mysql_num_rows($e_result);
					$e_total_price=0;
					$e_products_string="";
					$e_products_string_na="";
					$e_order_string_engraving="";
					$e_order_string_tint="";
					$e_order_string_edging="";
					$e_order_string_frame="";
					
					//COLLECT FRAME DATA from orders table into string to be overwritten if there is edging data
					$e_order_string_edging="<b>Type: </b>".$listItem[frame_type]." ";
					$e_order_string_edging.="<b>Eye: A:</b>".$listItem[frame_a]." ";
					$e_order_string_edging.="<b>B: </b>".$listItem[frame_b]." ";
					$e_order_string_edging.="<b>ED: </b>".$listItem[frame_ed]." ";
					$e_order_string_edging.="<b>DBL: </b>".$listItem[frame_dbl]." ";	
				
					if ($e_usercount !=0){
				while ($e_listItem=mysql_fetch_array($e_result)){
						$e_total_price=$e_total_price+$e_listItem[price]+$e_listItem[high_index_addition];
						if ($e_listItem[category]=="Edging"){
								$e_products_string.="<br />Edging: ".$e_listItem[price];
								$e_products_string_na.="<br />Edging: n/a";
								
								$e_order_string_edging="<b>Type: </b>".$e_listItem[frame_type]." ";
								$e_order_string_edging.="<b>Job Type: </b>".$e_listItem[job_type]." ";
								$e_order_string_edging.="<b>Order Type: </b>Frame ".$e_listItem[order_type]."<br>";
								
								$e_order_string_edging.="<b>Eye: A:</b>".$e_listItem[ep_frame_a]." ";
								$e_order_string_edging.="<b>B: </b>".$e_listItem[ep_frame_b]." ";
								$e_order_string_edging.="<b>ED: </b>".$e_listItem[ep_frame_ed]." ";
								$e_order_string_edging.="<b>DBL: </b>".$e_listItem[ep_frame_dbl]." ";
								$e_order_string_edging.="<b>Temple: </b>".$e_listItem[temple]."<br>";
								
								$e_order_string_edging.="<b>Supplier: </b>".$e_listItem[supplier]." ";
								$e_order_string_edging.="<b>Shape Model: </b>".$e_listItem[model]." ";
								$e_order_string_edging.="<b>Color: </b>".$e_listItem[color]."<br>";
								}

							if ($e_listItem[category]=="Engraving"){
								
								$e_products_string.="<br />Engraving: ".$e_listItem[price];
								$e_products_string_na.="<br />Engraving: n/a";
								
								$e_order_string_engraving="<b>Engraving: </b>".$e_listItem[engraving]." ";}
							if ($e_listItem[category]=="Tint"){
								
								$e_products_string.="<br />Tint: ".$e_listItem[price];
								$e_products_string_na.="<br />Tint: n/a";
								
								$e_order_string_tint="<b>Tint: </b>".$e_listItem[tint].": ";
								if ($e_listItem[tint]=="Solid")
									{ $e_order_string_tint.= $e_listItem[from_perc]."% <b>Color:</b> ".$e_listItem[tint_color];}
								else
									{$e_order_string_tint.= $e_listItem[from_perc]."% to ".$e_listItem[to_perc] ."% <b>Color:</b> ".$e_listItem[tint_color];}
								}//END IF TINT
							if ($e_listItem[category]=="Prism"){
								$e_products_string.="<br />Prism: ".$e_listItem[price];
								$e_products_string_na.="<br />Prism: n/a";
								}
							if ($e_listItem[category]=="Frame"){
								$e_products_string.="<br />Frame: ".$e_listItem[price];
								$e_products_string.="<br />High Index: ".$e_listItem[high_index_addition];
								
								$e_products_string_na.="<br />Frame: n/a";
								$e_products_string_na.="<br />High Index: n/a";
								
								$e_order_string_frame="<b>Type: </b>".$e_listItem[frame_type]." ";
								$e_order_string_frame.="<b>Job Type: </b>".$e_listItem[job_type]." ";
								$e_order_string_frame.="<b>Order Type: </b>Frame ".$e_listItem[order_type]."<br>";
								$e_order_string_frame.="<b>Eye: A:</b>".$e_listItem[ep_frame_a]." ";
								$e_order_string_frame.="<b>B: </b>".$e_listItem[ep_frame_b]." ";
								$e_order_string_frame.="<b>ED: </b>".$e_listItem[ep_frame_ed]." ";
								$e_order_string_frame.="<b>DBL: </b>".$e_listItem[ep_frame_dbl]." ";
								$e_order_string_frame.="<b>Temple: </b>".$e_listItem[temple]."<br>";
								$e_order_string_frame.="<b>Supplier: </b>".$e_listItem[supplier]." ";
								$e_order_string_frame.="<b>Shape Model: </b>".$e_listItem[model]." ";
								$e_order_string_frame.="<b>Frame Model: </b>".$e_listItem[temple_model_num]." ";
								$e_order_string_frame.="<b>Color: </b>".$e_listItem[color]."<br>";
							}//END IF FRAME
						}//END WHILE
						$e_total_price=money_format('%.2n',$e_total_price);
					}//END EXTRA PRODUCT SECTION
						
					$order_shipping_method=$listItem[order_shipping_method];
					$currency=$listItem[currency];
					$order_shipping_cost=$listItem[order_shipping_cost];
					$itemSubtotal=0;
					$over_range=$listItem[order_over_range_fee];
					$coupon_dsc=$listItem[coupon_dsc];
					$itemSubtotal=$listItem[order_quantity]*($listItem[order_product_price]+$e_total_price)+$over_range-$coupon_dsc;
					$totalPrice=$totalPrice+$itemSubtotal;
					
					$itemSubtotalDsc=$listItem[order_quantity]*($listItem[order_product_discount]+$e_total_price)+$over_range-$coupon_dsc+$buying_level_dsc;
					$totalPriceDsc=$totalPriceDsc+$itemSubtotalDsc;
					
					$prescrQuantity=$prescrQuantity+$listItem[order_quantity];
					$itemSubtotal=money_format('%.2n',$itemSubtotal);
					
						
					$queryProduit = "Select order_product_id from orders where order_num = $orderNum";
					$resultProduit  =mysql_query($queryProduit)			or die ("Could not find order num");
					$listItemProduit=mysql_fetch_array($resultProduit);
					$PK_Produit = $listItemProduit['order_product_id'];
					
					$queryProductCode = "Select product_code  from exclusive where primary_key  = $PK_Produit";
					$resultProductCode  =mysql_query($queryProductCode)			or die ("Could not find product code");
					$listItemProductCode=mysql_fetch_array($resultProductCode);
					$Product_Code = $listItemProductCode['product_code'];
					
					
					
					$message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0">
         <tr >
                <td colspan="6" bgcolor="#FFFFFF">Product :: ';
				$message.=$listItem[order_product_name] ;
				$message.='</td>
                <td bgcolor="#FFFFFF">&nbsp;</td>
                <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
              <tr >
                <td bgcolor="#FFFFFF"><strong>Coating:</strong></td>
                <td align="center" bgcolor="#FFFFFF">'.$listItem[order_product_coating].'</td>
                <td align="center" bgcolor="#FFFFFF"><strong>Photochromatic:</strong></td>
                <td align="center" bgcolor="#FFFFFF">'.$listItem[order_product_photo].'</td>
                <td align="center" bgcolor="#FFFFFF"><strong>Polarized:</strong></td>
                <td colspan="3" align="center" bgcolor="#FFFFFF">';
				$message.=$listItem[order_product_polar];
				$message.='</td>
              </tr>
              <tr >
                <td bgcolor="#FFFFFF">Patient:</td>
                <td align="center" bgcolor="#FFFFFF">';
				$message.=$listItem[order_patient_first].'&nbsp;'.$listItem[order_patient_last].'</td>';
				
				$message.='<td bgcolor="#FFFFFF" class="formCellNosidesRA"><b>Ref Number:<b></td>';
				$message.='<td align="center" bgcolor="#FFFFFF" class="formCellNosides">'.$listItem[patient_ref_num].'</td>';
				
				$message.='<td align="center" bgcolor="#FFFFFF">Salesperson ID: </td>
                <td colspan="3" align="center" bgcolor="#FFFFFF">';
				$message.=$listItem[salesperson_id];
				$message.='</td>
              </tr>
              <tr >
                <td bgcolor="#FFFFFF">&nbsp;</td>
                <td align="center" bgcolor="#FFFFFF"><strong>Sphere</strong></td>
                <td align="center" bgcolor="#FFFFFF"><strong>Cylinder</strong></td>
                <td align="center" bgcolor="#FFFFFF"><strong>Axis</strong></td>
                <td align="center" bgcolor="#FFFFFF"><strong>Addition</strong></td>
                <td align="center" bgcolor="#FFFFFF"><strong>Prism</strong></td>
                <td align="center" bgcolor="#FFFFFF"><strong>Quantity</strong></td>
                <td align="center" bgcolor="#FFFFFF"><strong>Price</strong></td>
              </tr>
              <tr >
                <td align="right">R.E.</td>
                <td align="center">';
				$message.=$listItem[re_sphere];
				$message.='</td>
                <td align="center">';
				$message.=$listItem[re_cyl];
				$message.='</td>
                <td align="center">';
				$message.=$listItem[re_axis];
				$message.='</td>
                <td align="center">';
				$message.=$listItem[re_add];
				$message.='</td>
                <td align="center">';
				$message.=$listItem[re_pr_ax] .'&nbsp;'.$listItem[re_pr_io].'&nbsp;&nbsp;'.$listItem[re_pr_ax2].'&nbsp;'.$listItem[re_pr_ud];
				$message.='</td>
                <td rowspan="7" align="center" valign="top">';
				$message.=$listItem[order_quantity];
				$message.='</td>
                <td rowspan="7" align="right" valign="top"><b>$';
				
				if ($sendPrices=="true"){
					$message.=$listItem[order_product_price];}
				else{
					$message.="n/a";}
				$message.='</b>';
				
				if ($sendPrices=="true"){
				
					if ($over_range!=0){
						$message.= '<br> Over range: '. $over_range;}	
				if ($e_total_price!=0){
						$message.= "<br>".$e_products_string;}
					if ($coupon_dsc!=0){
						$message.= '<br> Coupon Discount: -'.$coupon_dsc;}
					$message.='<br>Subtotal: '.$itemSubtotal;}
				else{
					if ($over_range!=0){
						$message.= '<br> Over range: n/a';}
					if ($e_total_price!=0){
						$message.= "<br>".$e_products_string_na;}	
					if ($coupon_dsc!=0){
						$message.= '<br> Coupon Discount: n/a';}
					$message.='<br>Subtotal:  n/a';
				}//END Send Prices Conditional
				
				if ($e_order_string_frame!=""){
					$e_order_string_edging=$e_order_string_frame;}
					
				$message.='</td>
              </tr>
              <tr >
                <td colspan="6" align="left"><strong>Dist.
                    PD:</strong>'.$listItem[re_pd].'&nbsp;&nbsp;&nbsp;<strong>Near
                    PD:</strong>'.  $listItem[re_pd_near].'&nbsp;&nbsp;&nbsp;<strong>Height:</strong>'. $listItem[re_height].' </td>
                </tr>
              <tr >
                <td align="right">
                  L.E.</td>
                <td align="center">'.$listItem[le_sphere].' </td>
                <td align="center">'.$listItem[le_cyl].' </td>
                <td align="center">'.$listItem[le_axis].' </td>
                <td align="center">'.$listItem[le_add].' </td>
                <td align="center">'.$listItem[le_pr_ax] .'&nbsp;'.$listItem[le_pr_io].'&nbsp;&nbsp;'.$listItem[le_pr_ax2] .'&nbsp;'.$listItem[le_pr_ud].'</td>
                </tr>
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF"><strong>Dist.
                    PD: </strong>'.$listItem[le_pd].' &nbsp;&nbsp;&nbsp;<strong>Near
                PD: </strong>'.$listItem[le_pd_near].' &nbsp;&nbsp;&nbsp;<strong>Height:</strong> '.$listItem[le_height].' </td>
                </tr>
				<tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>PT: </strong>'.$listItem[PT].' &nbsp;&nbsp;&nbsp;<strong>PA: </strong>'.$listItem[PA].' &nbsp;&nbsp;&nbsp;<strong>Vertex:</strong> '.$listItem[vertex].' </td>
                </tr>
         <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF"><strong>FRAME:
                    &nbsp; </strong>'.$e_order_string_edging.' </td>
              </tr>
			  <tr>
			    <td colspan="6" align="left" bgcolor="#FFFFFF"><strong>OTHER:</strong> 
				'.$e_order_string_engraving.$e_order_string_tint.'
				</td>
				</tr>
			    <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF"><strong>Special Instructions:
                    &nbsp;</strong>'.$listItem[special_instructions].' </td>
              </tr>
            </table><br>';
					} //END WHILE
			}//END IF USERCOUNT IF CONDITIONAL
			//END PRESCRIPTION SECTIOn
//BEGINNING OF TOTALS SECTION

$message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0"><tr >';
$message.='<td align="left">Total Quantites</td>';
$message.='<td align="left">Stock by Tray:';
$message.=$totalTrayQuant;
$message.='&nbsp;&nbsp;&nbsp;&nbsp;Stock by Bulk:';
$message.=$totalBulkQuant;
$message.='&nbsp;&nbsp;&nbsp;&nbsp;Prescription:';
$message.=$prescrQuantity;
$message.='</td></tr></table><br>';

  $message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0"><tr >';
$message.='<td align="left">Shipping:';
$message.=$order_shipping_method;
$message.='</td>';
$message.='<td align="right" valign="middle">';

if ($sendPrices=="true"){
	$message.=$order_shipping_cost;}
else{
	$message.='n/a';}
	
$message.='</td></tr>';
$message.='<tr ><td width="524" align="left">Order Total with Your Account Discount</td>';
$message.='<td width="100" align="right" valign="middle"><b>$';
				
	$totalPriceDsc=money_format('%.2n',$totalPriceDsc+$order_shipping_cost);
	
if ($sendPrices=="true"){
	$message.=$totalPriceDsc." ".$currency; }
else{
	$message.='n/a'; }
			
	$message.='</b></td></tr></table><br>';
			
	$message.="</body></html>";

	$inviteSent = mail("$send_to_address", "$subject", "$message", "$headers");
}

?>