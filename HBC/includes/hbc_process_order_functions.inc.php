<?php 
require_once(__DIR__.'/../../constants/url.constant.php');

function getUserEmail($user_id){
	include "../connexion_hbc.inc.php";
	$query="SELECT email FROM accounts WHERE user_id='$user_id'";
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));	
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$email=$listItem[email];

	return $email;
}

function getNewOrderNum(){
	include "../connexion_hbc.inc.php";
	$query="SELECT * FROM last_order_num WHERE primary_key='1'";
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$order_num=$listItem[last_order_num]+1;
	
	$query="UPDATE last_order_num SET last_order_num='$order_num' WHERE primary_key='1'";
	$result=mysqli_query($con,$query) or die ('Could not update because: ' . mysqli_error($con));
	$_SESSION['PrescrData']['myordnum']=$order_num; ///////////// pt 10/21/10 for xml uploader

	return $order_num;
}

function addOrderNumShiptoOrder($userId,$orderNum,$totalShipping,$order_shipping_method,$po_num){
include "../connexion_hbc.inc.php";
$order_date_processed=date("Y-m-d");
$order_status="processing";

$query="UPDATE orders SET order_status='$order_status',
						  order_date_processed='$order_date_processed',
						  order_num='$orderNum',
						  po_num='$po_num',
						  order_shipping_cost='$totalShipping',
						  order_shipping_method='$order_shipping_method'  
						  WHERE user_id='$userId' AND order_status='basket' AND order_product_type!='exclusive'";
	$result=mysqli_query($con,$query)		or die ('Could not update because: ' . mysqli_error($con));
	//echo $query;
	
}

function addOrderNumShiptoOrderExclusive($userId,$totalShipping,$order_shipping_method,$po_num){
	include "../connexion_hbc.inc.php";
	$order_date_processed=date("Y-m-d");
	$order_status="processing";

	$query="SELECT  user_id,primary_key,order_product_name,order_product_id, coupon_dsc, warranty FROM orders WHERE user_id='$userId' AND order_status='basket' AND order_product_type='exclusive'";
	$result=mysqli_query($con,$query)	or die ('Could not update because: '  . mysqli_error($con));
		
	while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		
		$orderNum=getNewOrderNum();
		$primary_key=$listItem[primary_key];

		//Insertion dans Swiss_edging_barcode
		$queryEdgingSwiss  = "INSERT INTO swiss_edging_barcodes (order_num) VALUES ($orderNum)";
		$resultEdgingSwiss = mysqli_query($con,$queryEdgingSwiss)	or die ('Could not update because: '  . mysqli_error($con));
		
		//CALCULATE ESTIMATED SHIP DATE AND WRITE TO est_ship_date table
		$est_ship_date=calculateEstShipDate($order_date_processed,$listItem[order_product_id]);
		addEstShipDate($est_ship_date,$primary_key,$orderNum,$order_date_processed);
		$query2="UPDATE orders SET order_status='$order_status',order_date_processed='$order_date_processed',order_num='$orderNum',po_num='$po_num',order_shipping_cost='$totalShipping' ,order_shipping_method='$order_shipping_method'  WHERE primary_key='$primary_key' AND order_status='basket' AND order_product_type='exclusive'";	
		$result2=mysqli_query($con,$query2)		or die ('Could not update because: ' . mysqli_error($con));

		//Code rajouté par Charles 2010-07-22
		$todayDate = date("Y-m-d g:i a");// current date
		$currentTime = time($todayDate); //Change date into time
		//Add one hour equavelent seconds 60*60
		$timeAfterOneHour = $currentTime+((60*60)*3);	
		$datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);
		$ip= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
		//UPADTE status history with the customer ip address ad the ip
		$queryStatus="INSERT INTO status_history (order_num,order_status,update_time, update_type,update_ip) VALUES($orderNum,'processing','$datecomplete','manual','$ip') ";
		$resultStatus=mysqli_query($con,$queryStatus)	or die  ('I  cannot Insert into status history because: ' . mysqli_error($con));
		$e_query="UPDATE extra_product_orders SET order_num='$orderNum' WHERE order_id='$primary_key'";//SET order_num in extra_products_order table
		$e_result=mysqli_query($con,$e_query)		or die ('Could not update because: ' . mysqli_error($con));

		$gTotal=calculateTotal($orderNum);//ADD TOTAL TO ORDER TABLE
		addOrderTotal($orderNum,$gTotal);	


		$lab_id	    = $_SESSION["sessionUserData"]["main_lab"];
		$query6		= "SELECT lab_email,logo_file,fax_notify,fax from labs WHERE primary_key='$lab_id'";//LOOK UP MAIN LAB EMAIL ADDRESS
		$result6	= mysqli_query($con,$query6) or die  ('I cannot select items because: ' . mysqli_error($con));
		$listItemML = mysqli_fetch_array($result6,MYSQLI_ASSOC);
		//sendPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItemML[lab_email],$_SESSION["sessionUser_Id"],"true");//ALWAYS SEND CONFIRMATIOn TO MAIN LAB

		//Email de confirmation à la succursale HBC
		//sendPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,$_SESSION["sessionUserData"]["email"],$_SESSION["sessionUser_Id"],"true");//ALWAYS SEND CONFIRMATIOn TO CUSTOMER
		//sendPrescriptionConfirmation('dbeaulieu@direct-lens.com',$listItemML[logo_file],$orderNum,$_SESSION["sessionUserData"]["email"],$_SESSION["sessionUser_Id"],"true");//ALWAYS SEND CONFIRMATIOn TO CUSTOMER
		
	}//END WHILE
//uploadfinish();



}//END FUNCTION


function add_Pmt_Marker($user_id, $order_num, $gTotal){/* Set payment marker to show order as PAID */
	include "../connexion_hbc.inc.php";	
	$transData=$_SESSION["transData"];
	$today = date("Y-m-d");
	$discAmount=bcmul(.00, $gTotal, 2);
	$subAmount2 = bcsub($gTotal, $discAmount, 2);
	$amount=bcadd($subAmount2, $shipCost, 2);
	$query="INSERT into payments (user_id, order_num, pmt_date, pmt_type, pmt_amount, cctype, cclast4, transResultCode, transAuthCode, transApprovalCode, transTransID, order_paid_in_full) values ('$user_id', '$order_num', '$today', 'credit card', '$amount', '$transData[cc_type]', '$transData[cclast4]', '$transData[transResultCode]', '$transData[transAuthCode]', '$transData[transApprovalCode]', '$transData[transTransID]', 'y')";

	$result=mysqli_query($con,$query) or die ("could not add marker " . mysqli_error($con));
		
	return true;
}

function add_Order_Ref($Master_Order_ID, $order_num){/* Set Master Order ID for this order number */
	include "../connexion_hbc.inc.php";
	$query="INSERT into order_num_master_id_ref (ref_master_id, ref_order_num) values ('$Master_Order_ID', '$order_num')";
	$result=mysqli_query($con,$query) or die ("could not add order reference " . mysqli_error($con));
		
	return true;
}



function calculateEstShipDate($order_date_processed,$order_product_id){
	include "../sec_connectEDLL.inc.php";
	$delais    = 10;
	$tomorrow  = mktime(0,0,0,date("m"),date("d")+$delais,date("Y"));
	$date1     = date("Y-m-d", $tomorrow);
	return $date1;
}





function addEstShipDate($est_ship_date,$orders_id,$order_num,$date_order_placed){
	include "../connexion_hbc.inc.php";
	$query="insert into est_ship_date (orders_id,order_num,est_ship_date,date_order_placed) values ('$orders_id', '$order_num', '$est_ship_date', '$date_order_placed')";
	$result=mysqli_query($con,$query) or die ("could not add date because ".mysqli_error($con));
	
}

function addNewEstShipDate($new_est_ship_date,$order_id,$order_num,$order_date_processed){
	include "../connexion_hbc.inc.php";
	$query="SELECT * FROM est_ship_date WHERE orders_id='$order_id'";
	$result=mysqli_query($con,$query) or die ('Could not select because: ' . mysqli_error($con));	
	$numCount=mysqli_num_rows($result);
	
	if ($numCount==0){
			$query="insert into est_ship_date (orders_id,order_num,est_ship_date,date_order_placed) values ('$order_id', '$order_num', '$new_est_ship_date', '$order_date_processed')";
			$result=mysqli_query($con,$query) or die ("could not add date because ".mysqli_error($con));
			}
	else{
			$query="UPDATE est_ship_date SET est_ship_date='$new_est_ship_date' WHERE order_num='$order_num'";
			$result=mysqli_query($con,$query) or die ("could not add date because ".mysqli_error($con));
			}
	
}


 

function getExtraProdTotal($order_num){
	include "../connexion_hbc.inc.php";
	$e_query="SELECT * from extra_product_orders WHERE order_num='$order_num'";//GET EXTRA PRODUCT PRICES
	$e_result=mysqli_query($con,$e_query) or die  ('I cannot select items because: ' . mysqli_error($con).$e_query);
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
include "../connexion_hbc.inc.php";
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

function addOrderTotal($order_num,$total){
	include "../connexion_hbc.inc.php";
	$query="UPDATE orders SET order_total='$total' WHERE order_num='$order_num'";
	$result=mysqli_query($con,$query) or die ("Could not select item");
		
}





function sendPrescriptionConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$sendPrices){//PRESCRIPTION CONFIRMATION
	include "../connexion_hbc.inc.php";
	$barcode=createBarcode($orderNum);
	
	$query		= "SELECT * from ACCOUNTS WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$result 	= mysqli_query($con,$query)	or die  ('I cannot select items because: ' . mysqli_error($con));	
	$listItem	= mysqli_fetch_array($result,MYSQLI_ASSOC);
	$usercount	= mysqli_num_rows($result);
	
	if ($listItem['product_line']=="hbc"){
		$pl_text="HBC ";
	}
		
	$headers = "From:".$fromAddress."\r\n";
	$mime_boundary=md5(time()); 
	$headers .= 'MIME-Version: 1.0'."\r\n"; 
	$headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\""."\r\n"; 
					
	$headers .= "--".$mime_boundary."\r\n"; 
	$headers .=	"Content-Type: 	text/html; charset=\"iso-8859-1\"\r\n";
	$headers .= "Content-Transfer-Encoding: 8bit"."\r\n"; 
						
	$subject = "HBC Prescription Order Confirmation - Order Number:$orderNum";
	$message='<html xmlns="http://www.w3.org/1999/xhtml"><head><base href="'.constant('DIRECT_LENS_URL').'/processOrder.php">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ORDER</title>
</head>';
	$message.="<link href=\"http://www.direct-lens.com/dl.css\" rel=\"stylesheet\" type=\"text/css\" />";
$message.='<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img src="logos/'.$logo_file.'"/><td align="center"></td></td><td align="right"><img src="'.$barcode.'" width="190" height="50" /></td></tr></table>';//ABSOLUTE PATH TO THE BARCODE IMAGE
	
	$message.='<table width="650" border="0" align="center" cellpadding="0" cellspacing="0"><tr>';
	$message.='<td><div class="header2">Your '.$pl_text.' Order #:'.$orderNum.'</div></td></tr></table>';
	
	$query="SELECT po_num FROM orders WHERE order_num='$orderNum'"; //GET ORDER PO NUMBER
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
		$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr><td align="left" class="formCellNosides">PO Order #: ';
		$message.=$listItem[po_num];
		$message.='</td></tr></table>';
	
	$query="SELECT * FROM accounts WHERE user_id='$user_id'"; //ACCOUNT INFO SECTION
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$usercount=mysqli_num_rows($result);
	
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#000099" class="tableHead">ACCOUNT INFORMATION </td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Account Number :</td>';
    $message.='<td width="520" class="formCellNosides"><strong>';
	$message.=$listItem[account_num];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Company:</td><td width="520" class="formCellNosides"><strong>';
	$message.=$listItem[company];
	$message.='</strong> </td></tr>';
	$message.='</table>';
	
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#000099" class="tableHead">BILLING ADDRESS </td></tr><tr ><td width="130" align="right" class="formCellNosides">Address 1:</td><td width="520" class="formCellNosides"><strong>';
	$message.=$listItem[bill_address1];
	$message.='</strong></td></tr>
	<tr><td align="right" class="formCellNosides">City:</td><td width="520" class="formCellNosides"><strong>';
$message.=$listItem[bill_city];
$message.='</strong></td></tr><tr ><td align="right" class="formCellNosides">State:</td><td width="520" class="formCellNosides"><strong>';
$message.=$listItem[bill_state];
$message.='</strong> </td></tr><tr ><td align="right" class="formCellNosides">Postal Code:  </td><td class="formCellNosides"><strong>';
$message.=$listItem[bill_zip];
$message.='</strong></td></tr><tr><td align="right" class="formCellNosides">Country:</td><td class="formCellNosides"><strong>';
$message.=$listItem[bill_country];
$message.='</strong></td></tr></table>';//END of Address Section

$totalTrayQuant=0;
$totalBulkQuant=0;
$prescrQuantity=0;
			
			//BEGIN PRESCRIPTION SECTION
			
$query="SELECT * from orders WHERE user_id='$user_id' and order_num='$orderNum' and order_product_type='exclusive' ORDER by order_item_date";//SELECT ALL OPEN PRESCRIPTION ORDERS
			$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con).$query);
			$usercount=mysqli_num_rows($result);
			if ($usercount != 0){
			
			 $message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td bgcolor="#000099" class="tableHead">PRESCRIPTION ITEMS</td>
              </tr>
            </table>';
					
					while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
					$bl_query="SELECT * from additional_discounts WHERE orders_id='$listItem[primary_key]'";//GET BUYING LEVEL DISCOUNT
					$bl_result=mysqli_query($con,$bl_query) or die  ('I cannot select bl items because: ' . mysqli_error($con).$bl_query);
					$bl_listItem=mysqli_fetch_array($bl_result,MYSQLI_ASSOC);
					$buying_level_dsc=$bl_listItem[buying_level_discount];
						
					$e_query="SELECT * from extra_product_orders WHERE order_id='$listItem[primary_key]'";//GET EXTRA PRODUCT PRICES
					$e_result=mysqli_query($con,$e_query) or die  ('I cannot select items because: ' . mysqli_error($con).$e_query);
					$e_usercount=mysqli_num_rows($e_result);
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
						while ($e_listItem=mysqli_fetch_array($e_result,MYSQLI_ASSOC)){
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
								$e_order_string_edging.="<b>Frame Model: </b>".$e_listItem[temple_model_num]." ";
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
					
					$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
         <tr >
                <td colspan="6" bgcolor="#D7E1FF" class="tableSubHead">Product --- ';
				$message.=$listItem[order_product_name];
				$message.='</td>
                <td bgcolor="#D7E1FF" class="tableSubHead">&nbsp;</td>
                <td bgcolor="#D7E1FF"  class="formCellNosidesRA" >&nbsp;</td>
  </tr>
              <tr >
                <td bgcolor="#FFFFFF" class="formCellNosidesRA"><strong>Coating:</strong></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides">'.$listItem[order_product_coating].'</td>
                <td align="center" bgcolor="#FFFFFF"  class="formCellNosidesRA"><strong>Photochromatic:</strong></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides">'.$listItem[order_product_photo].'</td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosidesRA"><strong>Polarized:</strong></td>
                <td colspan="3" align="center" bgcolor="#FFFFFF" class="formCellNosides">';
				$message.=$listItem[order_product_polar];
				$message.='</td>
              </tr>
              <tr >
                <td bgcolor="#FFFFFF" class="formCellNosidesRA"><strong>Patient:</strong></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides">';
				$message.=$listItem[order_patient_first].'&nbsp;'.$listItem[order_patient_last].'</td>';
				
				
				$message.='<td bgcolor="#FFFFFF" class="formCellNosidesRA"><b>Ref Number:<b></td>';
				$message.='<td align="center" bgcolor="#FFFFFF" class="formCellNosides">'.$listItem[patient_ref_num].'</td>';
				
				$message.='<td align="center" bgcolor="#FFFFFF" class="formCellNosidesRA"><strong>Salesperson ID:</strong> </td>
                <td colspan="3" align="center" bgcolor="#FFFFFF" class="formCellNosides">';
				$message.=$listItem[salesperson_id];
				$message.='</td>
              </tr>
              <tr >
                <td bgcolor="#E5E5E5" class="formCellNosides">&nbsp;</td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Sphere</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Cylinder</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Axis</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Addition</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Prism</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosidesCenter"><strong>Quantity</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosidesRA"><strong>Price</strong></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides">R.E.</td>
                <td align="center" class="formCellNosides">';
				$message.=$listItem[re_sphere];
				$message.='</td>
                <td align="center" class="formCellNosides">';
				$message.=$listItem[re_cyl];
				$message.='</td>
                <td align="center" class="formCellNosides">';
				$message.=$listItem[re_axis];
				$message.='</td>
                <td align="center" class="formCellNosides">';
				$message.=$listItem[re_add];
				$message.='</td>
                <td align="center" class="formCellNosides">';
				$message.=$listItem[re_pr_ax] .'&nbsp;'.$listItem[re_pr_io].'&nbsp;&nbsp;'.$listItem[re_pr_ax2] .'&nbsp;'.$listItem[re_pr_ud];
				$message.='</td>
                <td rowspan="7" align="center" valign="top" class="formCellNosidesCenter">';
				$message.=$listItem[order_quantity];
				$message.='</td>
                <td rowspan="7" align="right" valign="top" class="formCellNosidesRA"><b>$';
				
				if ($sendPrices=="true"){
					$message.=$listItem[order_product_price];}
				else{
					$message.="n/a";}
				$message.='</b>';
				
				if ($sendPrices=="true"){
				
					if ($over_range!=0){
						$message.= '<br> Over range: '.$over_range;}
						if ($e_total_price!=0){
						$message.= "<br>".$e_products_string;}
					if ($coupon_dsc!=0){
						$message.= '<br> Coupon Discount: -'. $coupon_dsc;}
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
                <td colspan="6" align="left" class="formCellNosides"><strong>Dist.
                    PD:</strong>'.$listItem[re_pd].'&nbsp;&nbsp;&nbsp;<strong>Near
                    PD:</strong>'.  $listItem[re_pd_near].'&nbsp;&nbsp;&nbsp;<strong>Height:</strong>'. $listItem[re_height].' </td>
                </tr>
              <tr >
                <td align="right" class="formCellNosides">
                  L.E.</td>
                <td align="center" class="formCellNosides">'.$listItem[le_sphere].' </td>
                <td align="center" class="formCellNosides">'.$listItem[le_cyl].' </td>
                <td align="center" class="formCellNosides">'.$listItem[le_axis].' </td>
                <td align="center" class="formCellNosides">'.$listItem[le_add].' </td>
                <td align="center" class="formCellNosides">'.$listItem[le_pr_ax] .'&nbsp;'.$listItem[le_pr_io].'&nbsp;&nbsp;'.$listItem[le_pr_ax2].'&nbsp;'.$listItem[le_pr_ud].'</td>
                </tr>
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>Dist.
                    PD: </strong>'.$listItem[le_pd].' &nbsp;&nbsp;&nbsp;<strong>Near
                PD: </strong>'.$listItem[le_pd_near].' &nbsp;&nbsp;&nbsp;<strong>Height:</strong> '.$listItem[le_height].' </td>
                </tr>
				 <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>PT: </strong>'.$listItem[PT].' &nbsp;&nbsp;&nbsp;<strong>PA: </strong>'.$listItem[PA].' &nbsp;&nbsp;&nbsp;<strong>Vertex:</strong> '.$listItem[vertex].' </td>
                </tr>
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>FRAME:
                    &nbsp; </strong>'.$e_order_string_edging.' </td>
              </tr>
			  <tr>
			    <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>OTHER:</strong> 
				'.$e_order_string_engraving.$e_order_string_tint.'
				</td>
				</tr>
			    <tr >
                <td colspan="8" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>Special Instructions:&nbsp;</strong>'.$listItem[special_instructions].'</td>
              </tr>
			     <tr >
                <td colspan="8" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>Internal Note:&nbsp;</strong>'.$listItem[internal_note].'</td>
              </tr>
            </table>';
							
					} //END WHILE
			}//END IF USERCOUNT IF CONDITIONAL
			
			//END PRESCRIPTION SECTIOn

//BEGINNING OF TOTALS SECTION


$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr >';
$message.='<td align="left" class="formCellNosides">Total Quantites</td>';
$message.='<td align="left" class="formCellNosidesRA">Stock by Tray:';
$message.=$totalTrayQuant;
$message.='&nbsp;&nbsp;&nbsp;&nbsp;Stock by Bulk:';
$message.=$totalBulkQuant;
$message.='&nbsp;&nbsp;&nbsp;&nbsp;Prescription:';
$message.=$prescrQuantity;
$message.='</td></tr></table>';

  $message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr >';
$message.='<td align="left" class="formCellNosides">Shipping:';
$message.=$order_shipping_method;
$message.='</td>';
$message.='<td align="right" valign="middle" class="formCellNosidesRA">';

if ($sendPrices=="true"){
	$message.=$order_shipping_cost;}
else{
	$message.='n/a';}
	
$message.='</td></tr>';
$message.='<tr ><td width="524" align="left" class="Subheader">Order Total with Your Account Discount</td>';
$message.='<td width="100" align="right" valign="middle" class="total"><b>$';
				
	$totalPriceDsc=money_format('%.2n',$totalPriceDsc+$order_shipping_cost);
	
if ($sendPrices=="true"){
	$message.=$totalPriceDsc." ".$currency; }
else{
	$message.='n/a'; }
			
	$message.='</b></td></tr></table>';		
	$message.="</body></html>";
	
//New SES email code
$to_address = str_split($send_to_address,100);
$from_address='donotreply@entrepotdelalunette.com';
$curTime= date("m-d-Y");	
$response=office365_mail($to_address, $from_address, $subject, null, $message);
}



function createBarcode($order_num){
	return constant('DIRECT_LENS_URL')."/barcodes/".$order_num.".gif";
}

?>



