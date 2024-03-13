<?php
require_once(__DIR__.'/../../constants/aws.constant.php');
require_once(__DIR__.'/../../constants/url.constant.php');
// include Barcode39 class
include "../barcodes/Barcode39.php";


function createBarcode($order_num){
	return constant('DIRECT_LENS_URL')."/barcodes/".$order_num.".gif";
}


function sendPrescriptionConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$sendPrices){//PRESCRIPTION CONFIRMATION

// set object
$bc = new Barcode39($orderNum);
// set text size
$bc->barcode_text_size = 5;
// set barcode bar thickness (thick bars)
$bc->barcode_bar_thick = 4;
// set barcode bar thickness (thin bars)
$bc->barcode_bar_thin = 2;
// save barcode GIF file
//$bc->draw("$orderNum". "1"."gif");
$bc->draw("$orderNum".".gif");
$barcode= constant('DIRECT_LENS_URL')."/labAdmin/".$orderNum.".gif";
	
	$query="SELECT * FROM accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$result=mysqli_query($con,$query)	or die  ('I cannot select items because: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$usercount=mysqli_num_rows($result);
	
	if (($listItem['product_line']=="safety") && (strtolower($listItem['language'])=="french"))
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/safety/images/LOGO_SAFE_FR.jpg";
		$pl_text="SAFE";}
	elseif (($listItem['product_line']=="safety") && (strtolower($listItem['language'])=="english"))
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/safety/images/LOGO_SAFE_EN.jpg";
		$pl_text="SAFE";}
	
		
	$headers = "From:".$fromAddress."\r\n";
	$mime_boundary=md5(time()); 
	$headers .= 'MIME-Version: 1.0'."\r\n"; 
	$headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\""."\r\n"; 
					
	$headers .= "--".$mime_boundary."\r\n"; 
	$headers .=	"Content-Type: 	text/html; charset=\"iso-8859-1\"\r\n";
	$headers .= "Content-Transfer-Encoding: 8bit"."\r\n"; 
						
	$subject = $pl_text." Prescription Order Confirmation - Order Number:$orderNum";
	$message='<html xmlns="http://www.w3.org/1999/xhtml"><head><base href="'.constant('DIRECT_LENS_URL').'/processOrder.php">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ORDER</title>
</head>';
	$message.="<link href=\"https://www.direct-lens.com/dl.css\" rel=\"stylesheet\" type=\"text/css\" />";
$message.='<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img src="logos/'.$logo_file.'"/><td align="center"><img src="'.$dl_logo_file.'" width="200" height="60" /></td></td><td align="right"><img src="'.$barcode.'" width="190" height="50" /></td></tr></table>';//ABSOLUTE PATH TO THE BARCODE IMAGE
	
	$message.='<table width="650" border="0" align="center" cellpadding="0" cellspacing="0"><tr>';
	$message.='<td><div class="header2">Your '.$pl_text.' Order #:'.$orderNum.'</div></td></tr></table>';
	
	$query="SELECT po_num FROM orders WHERE order_num='$orderNum'"; //GET ORDER PO NUMBER
	$result=mysqli_query($con,$query)		or die  ('I cannot select items because: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$query="SELECT * FROM accounts WHERE user_id='$user_id'"; //ACCOUNT INFO SECTION
	$result=mysqli_query($con,$query)		or die  ('I cannot select items because: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$usercount=mysqli_num_rows($result);
	
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#000099" class="tableHead">ACCOUNT INFORMATION </td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Name on Account :</td>';
    $message.='<td width="520" class="formCellNosides"><strong>';
	$message.=$listItem[title].' '.$listItem[first_name].' '.$listItem[last_name];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Account Number :</td>';
    $message.='<td width="520" class="formCellNosides"><strong>';
	$message.=$listItem[account_num];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Company:</td><td width="520" class="formCellNosides"><strong>';
	$message.=$listItem[company];
	$message.='</strong> </td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Buying Group: </td><td class="formCellNosides"><strong>';
	$message.=$_SESSION["sessionUserData"]["bg_name"];
	$message.='</strong></td></tr>';
	$message.='</table>';
	
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#000099" class="tableHead">BILLING ADDRESS </td></tr><tr ><td width="130" align="right" class="formCellNosides">Address 1:</td><td width="520" class="formCellNosides"><strong>';
	$message.=$listItem[bill_address1];
	$message.='</strong></td></tr><tr ><td width="130" align="right" class="formCellNosides">Address 2:</td><td width="520" class="formCellNosides"><strong>';
$message.=$listItem[bill_address2];
$message.='</strong></td></tr><tr ><td align="right" class="formCellNosides">City:</td><td width="520" class="formCellNosides"><strong>';
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
					$bl_query="SELECT * FROM additional_discounts WHERE orders_id='$listItem[primary_key]'";//GET BUYING LEVEL DISCOUNT
					$bl_result=mysqli_query($con,$bl_query)			or die  ('I cannot select bl items because: ' . mysqli_error($con).$bl_query);
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
									
					if ($e_usercount !=0){
						while ($e_listItem=mysqli_fetch_array($e_result,MYSQLI_ASSOC)){
						$e_total_price=$e_total_price+$e_listItem[price]+$e_listItem[high_index_addition];
						if ($e_listItem[category]=="Edging"){
								
																
								$e_order_string_edging.="<b>Eye: A:</b>".$e_listItem[ep_frame_a]." ";
								$e_order_string_edging.="<b>B: </b>".$e_listItem[ep_frame_b]." ";
								$e_order_string_edging.="<b>ED: </b>".$e_listItem[ep_frame_ed]." ";
								$e_order_string_edging.="<b>DBL: </b>".$e_listItem[ep_frame_dbl]." ";
								$e_order_string_edging.="<b>Temple: </b>".$e_listItem[temple]."<br>";
								
								$e_order_string_edging.="<b>Supplier: </b>".$e_listItem[supplier]." ";
								$e_order_string_edging.="<b>Shape Model: </b>".$e_listItem[temple_model_num]." ";
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
								
							if ($e_listItem[category]=="Removable Side Shield"){
								$e_products_string.="<br />Removable Side Shield: ".$e_listItem[price];
								$e_products_string_na.="<br />Removable Side Shield: n/a";
								$e_order_string_tint="<b>Removable Side Shield: </b>".$e_listItem[tint].": ";
							}//END IF removable side shield	
							
							if ($e_listItem[category]=="Cushion"){
								$e_products_string.="<br />Cushion: ".$e_listItem[price];
								$e_products_string_na.="<br />Cushion: n/a";
								$e_order_string_tint="<b>Cushion: </b>";
							}//END IF Cushion	
							
							if ($e_listItem[category]=="Dust Bar"){
								$e_products_string.="<br />Dust Bar: ".$e_listItem[price];
								$e_products_string_na.="<br />Dust Bar: n/a";
								$e_order_string_tint="<b>Dust Bar: </b>";
							}//END IF Dust bar	
							
							
							if ($e_listItem[category]=="Frame"){
								$e_products_string.="<br />Frame: ".$e_listItem[price];
								$e_products_string.="<br />High Index: ".$e_listItem[high_index_addition];
								
								$e_products_string_na.="<br />Frame: n/a";
								$e_products_string_na.="<br />High Index: n/a";
								
								
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
					
					$queryAlreadyPaid   = "SELECT * FROM payments_safety WHERE order_id = (SELECT primary_key FROM orders WHERE order_num = $orderNum LIMIT 0,1)";
					$resultAlreadyPaid  = mysqli_query($con,$queryAlreadyPaid) or die ("Could not find order num");
					$nombreResultat     = mysqli_num_rows($resultAlreadyPaid);
					
					if ($nombreResultat > 0 ){
						$DataAlreadyPaid = mysqli_fetch_array($resultAlreadyPaid,MYSQLI_ASSOC);
						$MontantDejaPaye = $DataAlreadyPaid[payment_amount];		
						//echo 'Montantdejapayé'. $MontantDejaPaye;			
					}else{
					$MontantDejaPaye = 0;		
					}
					
					
					$itemSubtotal=$listItem[order_quantity]*($listItem[order_product_price]+$e_total_price)+$over_range-$coupon_dsc;
					$itemSubtotal = $itemSubtotal-$MontantDejaPaye;
					$totalPrice=$totalPrice+$itemSubtotal;
					
					$itemSubtotalDsc=$listItem[order_quantity]*($listItem[order_product_discount]+$e_total_price)+$over_range-$coupon_dsc+$buying_level_dsc;
					$itemSubtotalDsc = $itemSubtotalDsc-$MontantDejaPaye;
					$totalPriceDsc=$totalPriceDsc+$itemSubtotalDsc;
					
					$prescrQuantity=$prescrQuantity+$listItem[order_quantity];
					$itemSubtotal=money_format('%.2n',$itemSubtotal);
					
					$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
         <tr >
                <td colspan="6" bgcolor="#D7E1FF" class="tableSubHead">Product ';
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
						
						
					if ($MontantDejaPaye <> 0 ){
					$message.= "<br><br><b>Paid by tiers: -". $MontantDejaPaye.'</b>';			
					}	
						
						
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
            </table>';
							
					} //END WHILE
			}//END IF USERCOUNT IF CONDITIONAL
			
			//END PRESCRIPTION SECTIOn
			



//BEGINNING OF TOTALS SECTION
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
	
	
$queryDispensingFee  = "SELECT * FROM extra_product_orders WHERE (category = 'Dispensing Fee Progressive' OR category = 'Dispensing Fee SV)'  AND order_id='$listItem[primary_key]'"	;
$resultDispensingFee = mysqli_query($con,$e_query)	or die  ('I cannot select items because: ' . mysqli_error($con).$queryDispensingFee);
$NbrResultat =mysqli_num_rows($resultDispensingFee);
/*
if ($NbrResultat  > 0)
{
	$DataDispensingFee   = mysqli_fetch_array($resultDispensingFee,MYSQLI_ASSOC);
	if ($DataDispensingFee[temple]==20){
		//Debut code Dispensing fee // 2013-12-20
		$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr >';
		$message.='<td align="left" class="formCellNosides">DISPENSING FEE: 20.00$</td>';
		$message.='<td align="right" valign="middle" class="formCellNosidesRA">';
		$message.='</td>';
		$message.='</td></tr></table>';
	}elseif($DataDispensingFee[temple]==30){
		$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr >';
		$message.='<td align="left" class="formCellNosides">DISPENSING FEE: 30.00$</td>';
		$message.='<td align="right" valign="middle" class="formCellNosidesRA">';
		$message.='</td>';
		$message.='</td></tr></table>';
	}
}//End if NbrResultat > 0*/
	
	
			
	$message.="</body></html>";
	
//New SES email code
$to_address = str_split($send_to_address,100);
$from_address='donotreply@entrepotdelalunette.com';
$curTime= date("m-d-Y");	
$response=office365_mail($to_address, $from_address, $subject, null, $message);
}




//------------English version of the confirmation email

function sendPrescriptionConfirmationEn($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$sendPrices){//PRESCRIPTION CONFIRMATION

// set object
$bc = new Barcode39($orderNum);
// set text size
$bc->barcode_text_size = 5;
// set barcode bar thickness (thick bars)
$bc->barcode_bar_thick = 4;
// set barcode bar thickness (thin bars)
$bc->barcode_bar_thin = 2;
// save barcode GIF file
//$bc->draw("$orderNum". "1"."gif");
$bc->draw("$orderNum".".gif");
$barcode= constant('DIRECT_LENS_URL')."/labAdmin/".$orderNum.".gif";
	
	$query="SELECT * FROM accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$result=mysqli_query($con,$query)	or die  ('I cannot select items because: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$usercount=mysqli_num_rows($result);
	
	if (($listItem['product_line']=="safety") && (strtolower($listItem['language'])=="french"))
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/safety/images/LOGO_SAFE_FR.jpg";
		$pl_text="SAFE";}
	elseif (($listItem['product_line']=="safety") && (strtolower($listItem['language'])=="english"))
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/safety/images/LOGO_SAFE_EN.jpg";
		$pl_text="SAFE";}
		
	$headers = "From:".$fromAddress."\r\n";
	$mime_boundary=md5(time()); 
	$headers .= 'MIME-Version: 1.0'."\r\n"; 
	$headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\""."\r\n"; 
					
	$headers .= "--".$mime_boundary."\r\n"; 
	$headers .=	"Content-Type: 	text/html; charset=\"iso-8859-1\"\r\n";
	$headers .= "Content-Transfer-Encoding: 8bit"."\r\n"; 
						
	$subject = $pl_text." Prescription Order Confirmation - Order Number:$orderNum";
	$message='<html xmlns="http://www.w3.org/1999/xhtml"><head><base href="'.constant('DIRECT_LENS_URL').'/processOrder.php">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ORDER</title>
</head>';
	$message.="<link href=\"https://www.direct-lens.com/dl.css\" rel=\"stylesheet\" type=\"text/css\" />";
$message.='<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img src="logos/'.$logo_file.'"/><td align="center"><img src="'.$dl_logo_file.'" width="200" height="60" /></td></td><td align="right"><img src="'.$barcode.'" width="190" height="50" /></td></tr></table>';//ABSOLUTE PATH TO THE BARCODE IMAGE
	
	$message.='<table width="650" border="0" align="center" cellpadding="0" cellspacing="0"><tr>';
	$message.='<td><div class="header2">Your '.$pl_text.' Order #:'.$orderNum.'</div></td></tr></table>';
	
	$query="SELECT po_num FROM orders WHERE order_num='$orderNum'"; //GET ORDER PO NUMBER
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	$query="SELECT * FROM accounts WHERE user_id='$user_id'"; //ACCOUNT INFO SECTION
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$usercount=mysqli_num_rows($result);
	
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#000099" class="tableHead">ACCOUNT INFORMATION </td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Name on Account :</td>';
    $message.='<td width="520" class="formCellNosides"><strong>';
	$message.=$listItem[title].' '.$listItem[first_name].' '.$listItem[last_name];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Account Number :</td>';
    $message.='<td width="520" class="formCellNosides"><strong>';
	$message.=$listItem[account_num];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Company:</td><td width="520" class="formCellNosides"><strong>';
	$message.=$listItem[company];
	$message.='</strong> </td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Buying Group: </td><td class="formCellNosides"><strong>';
	$message.=$_SESSION["sessionUserData"]["bg_name"];
	$message.='</strong></td></tr>';
	$message.='</table>';
	
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#000099" class="tableHead">BILLING ADDRESS </td></tr><tr ><td width="130" align="right" class="formCellNosides">Address 1:</td><td width="520" class="formCellNosides"><strong>';
	$message.=$listItem[bill_address1];
	$message.='</strong></td></tr><tr ><td width="130" align="right" class="formCellNosides">Address 2:</td><td width="520" class="formCellNosides"><strong>';
$message.=$listItem[bill_address2];
$message.='</strong></td></tr><tr ><td align="right" class="formCellNosides">City:</td><td width="520" class="formCellNosides"><strong>';
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
			
$query="SELECT * FROM orders WHERE user_id='$user_id' and order_num='$orderNum' and order_product_type='exclusive' ORDER by order_item_date";//SELECT ALL OPEN PRESCRIPTION ORDERS
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
					$bl_result=mysqli_query($con,$bl_query)		or die  ('I cannot select bl items because: ' . mysqli_error($con).$bl_query);
					$bl_listItem=mysqli_fetch_array($bl_result,MYSQLI_ASSOC);
					$buying_level_dsc=$bl_listItem[buying_level_discount];
						
					$e_query="SELECT * FROM extra_product_orders WHERE order_id='$listItem[primary_key]'";//GET EXTRA PRODUCT PRICES
					$e_result=mysqli_query($con,$e_query)	or die  ('I cannot select items because: ' . mysqli_error($con).$e_query);
					$e_usercount=mysqli_num_rows($e_result);
					$e_total_price=0;
					$e_products_string="";
					$e_products_string_na="";
					$e_order_string_engraving="";
					$e_order_string_tint="";
					$e_order_string_edging="";
					$e_order_string_frame="";
					
					//COLLECT FRAME DATA from orders table into string to be overwritten if there is edging data
									
					if ($e_usercount !=0){
						while ($e_listItem=mysqli_fetch_array($e_result,MYSQLI_ASSOC)){
						$e_total_price=$e_total_price+$e_listItem[price]+$e_listItem[high_index_addition];
						if ($e_listItem[category]=="Edging"){
								
																
								$e_order_string_edging.="<b>Eye: A:</b>".$e_listItem[ep_frame_a]." ";
								$e_order_string_edging.="<b>B: </b>".$e_listItem[ep_frame_b]." ";
								$e_order_string_edging.="<b>ED: </b>".$e_listItem[ep_frame_ed]." ";
								$e_order_string_edging.="<b>DBL: </b>".$e_listItem[ep_frame_dbl]." ";
								$e_order_string_edging.="<b>Temple: </b>".$e_listItem[temple]."<br>";
								
								$e_order_string_edging.="<b>Supplier: </b>".$e_listItem[supplier]." ";
								$e_order_string_edging.="<b>Shape Model: </b>".$e_listItem[temple_model_num]." ";
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
								
							if ($e_listItem[category]=="Removable Side Shield"){
								$e_products_string.="<br />Removable Side Shield: ".$e_listItem[price];
								$e_products_string_na.="<br />Removable Side Shield: n/a";
								$e_order_string_tint="<b>Removable Side Shield: </b>".$e_listItem[tint].": ";
							}//END IF removable side shield	
							
							if ($e_listItem[category]=="Cushion"){
								$e_products_string.="<br />Cushion: ".$e_listItem[price];
								$e_products_string_na.="<br />Cushion: n/a";
								$e_order_string_tint="<b>Cushion: </b>";
							}//END IF Cushion	
							
							if ($e_listItem[category]=="Dust Bar"){
								$e_products_string.="<br />Dust Bar: ".$e_listItem[price];
								$e_products_string_na.="<br />Dust Bar: n/a";
								$e_order_string_tint="<b>Dust Bar: </b>";
							}//END IF Dust bar	
							
							/*//Ajout message Dispensing fee/Honoraire du professionnel SV
							if ($e_listItem[category]=="Dispensing Fee SV"){
							   $e_products_string.="<br />Dispensing Fee SV: ".$e_listItem[temple]. '.00';
								$e_products_string_na.="<br />Dispensing Fee SV: n/a";
							}//END IF TINT	*/
							   
							/*//Ajout message Dispensing fee/Honoraire du professionnel BIFOCAL
							if ($e_listItem[category]=="Dispensing Fee Bifocal"){
							   $e_products_string.="<br />Dispensing Fee Bifocal: ".$e_listItem[temple]. '.00';
								$e_products_string_na.="<br />Dispensing Fee Bifocal: n/a";
							}//END IF TINT*/	
							
							/*//Ajout message Dispensing fee/Honoraire du professionnel PROGRESSIF
							if ($e_listItem[category]=="Dispensing Fee Progressive"){
							   $e_products_string.="<br />Dispensing Fee Progressive: ".$e_listItem[temple]. '.00';
								$e_products_string_na.="<br />Dispensing Fee Progressive: n/a";
							}//END IF TINT	*/
							
							
							if ($e_listItem[category]=="Frame"){
								$e_products_string.="<br />Frame: ".$e_listItem[price];
								$e_products_string.="<br />High Index: ".$e_listItem[high_index_addition];
								
								$e_products_string_na.="<br />Frame: n/a";
								$e_products_string_na.="<br />High Index: n/a";
								
								
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
					
					$queryAlreadyPaid   = "SELECT * FROM payments_safety WHERE order_id = (SELECT primary_key FROM orders WHERE order_num = $orderNum LIMIT 0,1)";
					$resultAlreadyPaid  = mysqli_query($con,$queryAlreadyPaid) or die ("Could not find order num");
					$nombreResultat     = mysqli_num_rows($resultAlreadyPaid);
					
					if ($nombreResultat > 0 ){
						$DataAlreadyPaid = mysqli_fetch_array($resultAlreadyPaid,MYSQLI_ASSOC);
						$MontantDejaPaye = $DataAlreadyPaid[payment_amount];		
						//echo 'Montantdejapayé'. $MontantDejaPaye;			
					}else{
					$MontantDejaPaye = 0;		
					}
					
					
					
					$itemSubtotal=$listItem[order_quantity]*($listItem[order_product_price]+$e_total_price)+$over_range-$coupon_dsc-$MontantDejaPaye;
					$totalPrice=$totalPrice+$itemSubtotal;
					
					$itemSubtotalDsc=$listItem[order_quantity]*($listItem[order_product_discount]+$e_total_price)+$over_range-$coupon_dsc+$buying_level_dsc-$MontantDejaPaye;
					$totalPriceDsc=$totalPriceDsc+$itemSubtotalDsc;
					
					$prescrQuantity=$prescrQuantity+$listItem[order_quantity];
					$itemSubtotal=money_format('%.2n',$itemSubtotal);
					
					$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
         <tr >
                <td colspan="6" bgcolor="#D7E1FF" class="tableSubHead">Product ';
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
					if ($MontantDejaPaye <> 0 ){
					$message.= "<br><br><b>Paid by tiers: -". $MontantDejaPaye.'</b>';			
					}
					$message.='<br>Subtotal: '.$itemSubtotal;}
				else{
					if ($over_range!=0){
						$message.= '<br> Over range: n/a';}
					if ($e_total_price!=0){
						$message.= "<br>".$e_products_string_na;}
					if ($coupon_dsc!=0){
						$message.= '<br> Coupon Discount: n/a';}
						
						if ($MontantDejaPaye <> 0 ){
			$message.= "<br><br><b>Paid by tiers: -". $MontantDejaPaye.'</b>';			
			}
			
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
            </table>';
							
					} //END WHILE
			}//END IF USERCOUNT IF CONDITIONAL
			
			//END PRESCRIPTION SECTIOn

//BEGINNING OF TOTALS SECTION
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



?>