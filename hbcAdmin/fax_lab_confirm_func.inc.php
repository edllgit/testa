<?php 
require_once(__DIR__.'/../constants/url.constant.php');


function sendFaxStockConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$userData,$faxNum, $printit = false){//STOCK BULK AND TRAY CONFIRMATION

	$bg_query="select bg_name from buying_groups WHERE primary_key='$userData[buying_group]'";//GET BUYING GROUP NAME
	$bg_result=mysql_query($bg_query)
		or die  ('I cannot select items because: ' . mysql_error());
	$bgData=mysql_fetch_array($bg_result);

	$headers = "From:".$fromAddress."\r\n";
	$mime_boundary=md5(time()); 
	$headers .= 'MIME-Version: 1.0\r\n'; 
	$headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\""."\r\n"; 
					
	$headers .= "--".$mime_boundary."\r\n"; 
	$headers .=	"Content-Type: text/html; charset=\"iso-8859-1\"\r\n";
	$headers .= "Content-Transfer-Encoding: 8bit"."\r\n"; 
						
	$subject ="fax -html ".$faxNum;
	$message='<html xmlns="http://www.w3.org/1999/xhtml"><head><base href="'.constant('DIRECT_LENS_URL').'/processOrder.php">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ORDER</title>';
	if ($printit) $message .= "<script type='text/javascript'>window.onload = function() {window.print(); window.close();}</script>";
$message .= '</head>';
	
		$message.='<br><br><table width="650" border="1" align="center" cellpadding="5" cellspacing="0"><tr><td align="left">';
	$message.='<div >REVISED ORDER</div></td><td><div >Your Direct-Lens Order #:'.$orderNum.'</div></td></tr></table><br>';
	
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
	$message.='</strong> </td></tr></table><br>';
	
	$message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0"><tr ><td colspan="2" bgcolor="#FFFFFF" >BILLING ADDRESS </td></tr><tr ><td width="130" align="right"">Address 1:</td><td width="520"><strong>';
	$message.=$listItem[bill_address1];
	$message.='</strong></td></tr><tr ><td width="130" align="right" class="formCellNosides">Address 2:</td><td width="520" ><strong>';
$message.=$listItem[bill_address2];
$message.='</strong></td></tr><tr ><td align="right">City:</td><td width="520"><strong>';
$message.=$listItem[bill_city];
$message.='</strong></td></tr><tr ><td align="right">State:</td><td width="520"><strong>';
$message.=$listItem[bill_state];
$message.='</strong> </td></tr><tr ><td align="right" >Postal Code:  </td><td><strong>';
$message.=$listItem[bill_zip];
$message.='</strong></td></tr><tr><td align="right">Country:</td><td><strong>';
$message.=$listItem[bill_country];
$message.='</strong></td></tr></table><br>';//END of Address Section


//BEGIN STOCK BY TRAY SECTION
$query2="SELECT * from orders WHERE user_id='$user_id' and order_num='$orderNum' and order_product_type='stock_tray' ORDER by order_item_date,primary_key";//SELECT ALL OPEN STOCK ORDERS

			$result2=mysql_query($query2)	or die  ('I cannot select items because: ' . mysql_error());
			$stockusercount=mysql_num_rows($result2);
			if ($stockusercount != 0){
			
			 $message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0"><tr ><td bgcolor="#FFFFFF" >STOCK ITEMS - BY TRAY</td></tr></table><br>';
			
					while ($listItem=mysql_fetch_array($result2)){
						$order_shipping_method=$listItem[order_shipping_method];
						$currency=$listItem[currency];
						$additional_dsc=$listItem[additional_dsc];
						$extra_product=$listItem[extra_product];
						$extra_product_price=$listItem[extra_product_price];
					
						$discount_type=$listItem[discount_type];
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
            </table><br>';	
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
						
						//END RE SECTION
											}//END IF
					} //END WHILE
			}// END OF STOCK BY TRAY SECTION


//BEGIN STOCK BY BULK SECTION
$query2="SELECT * from orders WHERE user_id='$user_id' and order_num='$orderNum' and order_product_type='stock' ORDER by order_item_date";//SELECT ALL OPEN STOCK ORDERS

			$result2=mysql_query($query2)	or die  ('I cannot select items because: ' . mysql_error());
			$stockusercount=mysql_num_rows($result2);
			if ($stockusercount != 0){
			
			 $message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0" class="formBox"><tr ><td bgcolor="#FFFFFF">STOCK ITEMS - BULK</td></tr></table><br>';
			
					while ($listItem=mysql_fetch_array($result2)){
					$order_shipping_method=$listItem[order_shipping_method];
					$currency=$listItem[currency];
					$additional_dsc=$listItem[additional_dsc];
					$discount_type=$listItem[discount_type];
					$extra_product=$listItem[extra_product];
					$extra_product_price=$listItem[extra_product_price];
					
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
					
					
	$message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="7" bgcolor="#FFFFFF">Product - ';
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
					} //END OF WHILE
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

if ($extra_product_price!=0){//INCLUDE EXTRA ITEM
		$message.="<tr >
            <td width=\"524\" align=\"left\">Additional Item:&nbsp;".$extra_product;
		$message.="<td width=\"100\" align=\"right\" valign=\"middle\" >&nbsp;".$extra_product_price."</td> </tr>";
		}

if ($additional_dsc!=0){
	$message.="<tr >";
    $message.="<td width=\"524\" align=\"left\" >Additional Discount (".$discount_type.$additional_dsc.")</td>";
    $message.="<td width=\"100\" align=\"right\" valign=\"middle\"><b>-";
	
	if($discount_type=="$"){
					$totalDiscount=money_format('%.2n',$additional_dsc);
				}
	else if ($discount_type=="%"){
					$totalDiscount=money_format('%.2n',$totalPriceDsc*($additional_dsc/100));
				}
	$message.=$totalDiscount; 
	$message.= "</b></td></tr>";
			  
			  }
			  
$message.='<tr ><td width="524" align="left">Order Total with All Discounts</td>';
$message.='<td width="100" align="right" valign="middle"><b>$';
				
$totalPriceDsc=money_format('%.2n',$totalPriceDsc+$order_shipping_cost-$totalDiscount+$extra_product_price);
$message.=$totalPriceDsc." ".$currency; 
$message.='</b></td></tr></table><br>';
$message.="</body></html>";

if ($printit) {
	echo $message;
	die();
}
else $inviteSent = mail("$send_to_address", "$subject", "$message", "$headers");
}//END OF FUNCTION


function sendFaxPrescriptionLabConfirmation($userId,$orderNum,$userData,$product_name,$faxNumML){
		
	$lab_id=$userData[main_lab];
	$query6="SELECT lab_email, logo_file from labs WHERE primary_key='$lab_id'";//LOOK UP MAIN LAB EMAIL ADDRESS
	$result6=mysql_query($query6)
			or die  ('I cannot select items because: ' . mysql_error());
	$listItemML=mysql_fetch_array($result6);
		
	sendFaxPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$userData[user_id],$userData,"true",$faxNumML);//ALWAYS SEND CONFIRMATIOn TO MAIN LAB
		
		
	$query3="SELECT collection FROM exclusive WHERE product_name='$product_name'";
	$result3=mysql_query($query3)
		or die ('Could not update because: ' . mysql_error());
		
	$listItem2=mysql_fetch_array($result3);
	$collection=$listItem2[collection];
		
	if ($collection=="My World"){
			
		$lab_id=$userData[main_lab];
		$query5="SELECT lab_email,fax from labs WHERE primary_key=(SELECT innovative_lab from labs WHERE primary_key='$lab_id')";//LOOK UP INNOVATIVE LAB EMAIL ADDRESS
		$result5=mysql_query($query5)
			or die  ('I cannot select items because: ' . mysql_error());
		$listItem=mysql_fetch_array($result5);
		
		$faxNumArray= str_split($listItem[fax]);//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
		$numCount=count($faxNumArray);
		$faxNum="";
		for ($i=0;$i<$numCount;$i++){
			if (is_numeric($faxNumArray[$i])) {
				$faxNum.=$faxNumArray[$i];
			}
		}
		
		sendFaxPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$userData[user_id],$userData,"true",$faxNum);// SEND CONFIRMATIOn TO INNOVATIVE LAB
			}
			
	if (($collection=="Precision")||($collection=="Vision Pro")){
					
		$lab_id=$userData[main_lab];
		$query5="SELECT lab_email,fax from labs WHERE primary_key=(SELECT precision_vp_lab from labs WHERE primary_key='$lab_id')";//LOOK UP INNOVATIVE LAB EMAIL ADDRESS
		$result5=mysql_query($query5)
				or die  ('I cannot select items because: ' . mysql_error());
		$listItem=mysql_fetch_array($result5);
		
		$faxNumArray= str_split($listItem[fax]);//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
		$numCount=count($faxNumArray);
		$faxNum="";
		for ($i=0;$i<$numCount;$i++){
			if (is_numeric($faxNumArray[$i])) {
				$faxNum.=$faxNumArray[$i];
			}
		}
		
		if ($collection=="Precision"){
			sendFaxPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$userData[user_id],$userData,"false",$faxNum);}
		else{
			sendFaxPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$userData[user_id],$userData,"true",$faxNum);}// SEND CONFIRMATIOn TO PRECISION LAB
			}
			
	if ($collection=="Infocus"){
			
		$lab_id=$userData[main_lab];
		$query5="SELECT lab_email,fax from labs WHERE primary_key=(SELECT infocus_lab from labs WHERE primary_key='$lab_id')";//LOOK UP INFOCUS LAB EMAIL ADDRESS
		$result5=mysql_query($query5)
				or die  ('I cannot select items because: ' . mysql_error());
		$listItem=mysql_fetch_array($result5);
		
		$faxNumArray= str_split($listItem[fax]);//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
		$numCount=count($faxNumArray);
		$faxNum="";
		for ($i=0;$i<$numCount;$i++){
			if (is_numeric($faxNumArray[$i])) {
				$faxNum.=$faxNumArray[$i];
			}
		}
		
		sendFaxPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$userData[user_id],$userData,"true",$faxNum);// SEND CONFIRMATIOn TO INFOCUS LAB
			}
			
	if ($collection=="Vision Pro Poly"){
			
		$lab_id=$userData[main_lab];
		$query5="SELECT lab_email,fax from labs WHERE primary_key=(SELECT visionpropoly_lab from labs WHERE primary_key='$lab_id')";//LOOK UP INNOVATIVE LAB EMAIL ADDRESS
		$result5=mysql_query($query5)
				or die  ('I cannot select items because: ' . mysql_error());
		$listItem=mysql_fetch_array($result5);
		
		$faxNumArray= str_split($listItem[fax]);//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
		$numCount=count($faxNumArray);
		$faxNum="";
		for ($i=0;$i<$numCount;$i++){
			if (is_numeric($faxNumArray[$i])) {
				$faxNum.=$faxNumArray[$i];
			}
		}
		
		sendFaxPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$userData[user_id],$userData,"true",$faxNum);// SEND CONFIRMATIOn TO VISION PRO LAB
			}//
			
		if ($collection=="Generation"){
			
		$lab_id=$userData[main_lab];
		$query5="SELECT lab_email,fax from labs WHERE primary_key=(SELECT generation_lab from labs WHERE primary_key='$lab_id')";//LOOK UP INNOVATIVE LAB EMAIL ADDRESS
		$result5=mysql_query($query5)
				or die  ('I cannot select items because: ' . mysql_error());
		$listItem=mysql_fetch_array($result5);
		
		$faxNumArray= str_split($listItem[fax]);//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
		$numCount=count($faxNumArray);
		$faxNum="";
		for ($i=0;$i<$numCount;$i++){
			if (is_numeric($faxNumArray[$i])) {
				$faxNum.=$faxNumArray[$i];
			}
		}
		
		sendFaxPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$userData[user_id],$userData,"true",$faxNum);// SEND CONFIRMATIOn TO GENERATION LAB
			}//
			
				if ($collection=="TrueHD"){
			
		$lab_id=$userData[main_lab];
		$query5="SELECT lab_email,fax from labs WHERE primary_key=(SELECT truehd_lab from labs WHERE primary_key='$lab_id')";//LOOK UP TRUE HD LAB EMAIL ADDRESS
		$result5=mysql_query($query5)
				or die  ('I cannot select items because: ' . mysql_error());
		$listItem=mysql_fetch_array($result5);
		
		$faxNumArray= str_split($listItem[fax]);//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
		$numCount=count($faxNumArray);
		$faxNum="";
		for ($i=0;$i<$numCount;$i++){
			if (is_numeric($faxNumArray[$i])) {
				$faxNum.=$faxNumArray[$i];
			}
		}
		
		sendFaxPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$userData[user_id],$userData,"true",$faxNum);// SEND CONFIRMATIOn TO TrueHD LAB
			}//
			
	if ($collection=="Easy Fit HD"){
			
		$lab_id=$userData[main_lab];
		$query5="SELECT lab_email,fax from labs WHERE primary_key=(SELECT easy_fit_lab from labs WHERE primary_key='$lab_id')";//LOOK UP EASY FIT LAB EMAIL ADDRESS
		$result5=mysql_query($query5)
				or die  ('I cannot select items because: ' . mysql_error());
		$listItem=mysql_fetch_array($result5);
		
		$faxNumArray= str_split($listItem[fax]);//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
		$numCount=count($faxNumArray);
		$faxNum="";
		for ($i=0;$i<$numCount;$i++){
			if (is_numeric($faxNumArray[$i])) {
				$faxNum.=$faxNumArray[$i];
			}
		}
		
		sendFaxPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$userData[user_id],$userData,"true",$faxNum);// SEND CONFIRMATIOn TO EASY FIT LAB
			}//
			
	if ($collection=="Other"){
			
		$lab_id=$userData[main_lab];
		$query5="SELECT lab_email,fax from labs WHERE primary_key=(SELECT other_lab from labs WHERE primary_key='$lab_id')";//LOOK UP INNOVATIVE LAB EMAIL ADDRESS
		$result5=mysql_query($query5)
				or die  ('I cannot select items because: ' . mysql_error());
		$listItem=mysql_fetch_array($result5);
		
		$faxNumArray= str_split($listItem[fax]);//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
		$numCount=count($faxNumArray);
		$faxNum="";
		for ($i=0;$i<$numCount;$i++){
			if (is_numeric($faxNumArray[$i])) {
				$faxNum.=$faxNumArray[$i];
			}
		}
		
		sendFaxPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$userData[user_id],$userData,"true",$faxNum);// SEND CONFIRMATIOn TO OTHER LAB
			}//
			
				if ($collection=="Private 1"){
			
		$lab_id=$userData[main_lab];
		$query5="SELECT lab_email,fax from labs WHERE primary_key=(SELECT private_1_lab from labs WHERE primary_key='$lab_id')";//LOOK UP PRIVATE 1 LAB EMAIL ADDRESS
		$result5=mysql_query($query5)
				or die  ('I cannot select items because: ' . mysql_error());
		$listItem=mysql_fetch_array($result5);
		
		$faxNumArray= str_split($listItem[fax]);//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
		$numCount=count($faxNumArray);
		$faxNum="";
		for ($i=0;$i<$numCount;$i++){
			if (is_numeric($faxNumArray[$i])) {
				$faxNum.=$faxNumArray[$i];
			}
		}
		
		sendFaxPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$userData[user_id],$userData,"true",$faxNum);// SEND CONFIRMATIOn TO PRIVATE 1 LAB
			}//
			
				if ($collection=="Private 2"){
			
		$lab_id=$userData[main_lab];
		$query5="SELECT lab_email,fax from labs WHERE primary_key=(SELECT private_2_lab from labs WHERE primary_key='$lab_id')";//LOOK UP PRIVATE 2 LAB EMAIL ADDRESS
		$result5=mysql_query($query5)
				or die  ('I cannot select items because: ' . mysql_error());
		$listItem=mysql_fetch_array($result5);
		
		$faxNumArray= str_split($listItem[fax]);//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
		$numCount=count($faxNumArray);
		$faxNum="";
		for ($i=0;$i<$numCount;$i++){
			if (is_numeric($faxNumArray[$i])) {
				$faxNum.=$faxNumArray[$i];
			}
		}
		
		sendFaxPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$userData[user_id],$userData,"true",$faxNum);// SEND CONFIRMATIOn TO PRIVATE 2 LAB
			}//
			
				if ($collection=="Private 3"){
			
		$lab_id=$userData[main_lab];
		$query5="SELECT lab_email,fax from labs WHERE primary_key=(SELECT private_3_lab from labs WHERE primary_key='$lab_id')";//LOOK UP EASY FIT LAB EMAIL ADDRESS
		$result5=mysql_query($query5)
				or die  ('I cannot select items because: ' . mysql_error());
		$listItem=mysql_fetch_array($result5);
		
		$faxNumArray= str_split($listItem[fax]);//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
		$numCount=count($faxNumArray);
		$faxNum="";
		for ($i=0;$i<$numCount;$i++){
			if (is_numeric($faxNumArray[$i])) {
				$faxNum.=$faxNumArray[$i];
			}
		}
		
		sendFaxPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$userData[user_id],$userData,"true",$faxNum);// SEND CONFIRMATIOn TO PRIVATE 3 LAB
			}//
			
				if ($collection=="Glass"){
			
		$lab_id=$userData[main_lab];
		$query5="SELECT lab_email,fax from labs WHERE primary_key=(SELECT glass_lab from labs WHERE primary_key='$lab_id')";//LOOK UP GLASS LAB EMAIL ADDRESS
		$result5=mysql_query($query5)
				or die  ('I cannot select items because: ' . mysql_error());
		$listItem=mysql_fetch_array($result5);
		
		$faxNumArray= str_split($listItem[fax]);//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
		$numCount=count($faxNumArray);
		$faxNum="";
		for ($i=0;$i<$numCount;$i++){
			if (is_numeric($faxNumArray[$i])) {
				$faxNum.=$faxNumArray[$i];
			}
		}
		
		sendFaxPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$userData[user_id],$userData,"true",$faxNum);// SEND CONFIRMATIOn TO GLASS LAB
			}//
			
				if ($collection=="Eco"){
			
		$lab_id=$userData[main_lab];
		$query5="SELECT lab_email,fax from labs WHERE primary_key=(SELECT eco_lab from labs WHERE primary_key='$lab_id')";//LOOK UP ECO LAB EMAIL ADDRESS
		$result5=mysql_query($query5)
				or die  ('I cannot select items because: ' . mysql_error());
		$listItem=mysql_fetch_array($result5);
		
		$faxNumArray= str_split($listItem[fax]);//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
		$numCount=count($faxNumArray);
		$faxNum="";
		for ($i=0;$i<$numCount;$i++){
			if (is_numeric($faxNumArray[$i])) {
				$faxNum.=$faxNumArray[$i];
			}
		}
		
		sendFaxPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,"directl-config@interpage.net",$userData[user_id],$userData,"true",$faxNum);// SEND CONFIRMATIOn TO ECO LAB
			}//
			
			
}//END FUNCTION

function sendFaxPrescriptionConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$userData,$sendPrices,$faxNum, $printit = false){//PRESCRIPTION CONFIRMATION
	$bg_query="select bg_name from buying_groups WHERE primary_key='$userData[buying_group]'";//GET BUYING GROUP NAME
	$bg_result=mysql_query($bg_query)
		or die  ('I cannot select items because: ' . mysql_error());
	$bgData=mysql_fetch_array($bg_result);

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
<title>ORDER</title>';
	if ($printit) $message .= "<script type='text/javascript'>window.onload = function() {window.print(); window.close();}</script>";
$message .= '</head>';

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
	
		$message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr><td align="left" class="formCellNosides">PO Order #: ';
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
	$message.='</strong> </td></tr>';
	$message.='<tr><td align="right">Shipping Code: </td><td><strong>';
	$message.=$listItem[shipping_code];
	$message.='</strong></td></tr>';
	$message.='</table><br>';
	
	$message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0"><tr ><td colspan="2" bgcolor="#FFFFFF" >BILLING ADDRESS </td></tr><tr ><td width="130" align="right">Address 1:</td><td width="520"><strong>';
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
			
			//BEGIN PRESCRIPTION SECTION
			
$query="SELECT * from orders WHERE user_id='$user_id' and order_num='$orderNum' and order_product_type='exclusive' ORDER by order_item_date";//SELECT ALL OPEN PRESCRIPTION ORDERS
			$result=mysql_query($query)		or die  ('I cannot select items because: ' . mysql_error());
			$usercount=mysql_num_rows($result);
			if ($usercount != 0){
			
			 $message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0">
              <tr >
                <td bgcolor="#FFFFFF">PRESCRIPTION ITEMS</td>
              </tr>
            </table><br>';
					
					while ($listItem=mysql_fetch_array($result)){
						
								$bl_query="SELECT * from additional_discounts WHERE orders_id='$listItem[primary_key]'";//GET BUYING LEVEL DISCOUNT
					$bl_result=mysql_query($bl_query)	or die  ('I cannot select bl items because: ' . mysql_error());
					$bl_listItem=mysql_fetch_array($bl_result);
					$buying_level_dsc=$bl_listItem[buying_level_discount];
					
						$e_query="SELECT * from extra_product_orders WHERE order_id='$listItem[primary_key]'";//GET EXTRA PRODUCT PRICES
					$e_result=mysql_query($e_query)		or die  ('I cannot select items because: ' . mysql_error());
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
					$additional_dsc=$listItem[additional_dsc];
					$discount_type=$listItem[discount_type];
					$extra_product=$listItem[extra_product];
					$extra_product_price=$listItem[extra_product_price];
					
					$order_shipping_cost=$listItem[order_shipping_cost];
					$itemSubtotal=0;
					$over_range=$listItem[order_over_range_fee];
					$coupon_dsc=$listItem[coupon_dsc];
					$itemSubtotal=$listItem[order_quantity]*($listItem[order_product_price]+$e_total_price)+$over_range+$extra_product_price-$coupon_dsc;
					$totalPrice=$totalPrice+$itemSubtotal;
					
					$itemSubtotalDsc=$listItem[order_quantity]*($listItem[order_product_discount]+$e_total_price)+$over_range+$extra_product_price-$coupon_dsc+$buying_level_dsc;
					
					$totalPriceDsc=$totalPriceDsc+$itemSubtotalDsc;
					
					$prescrQuantity=$prescrQuantity+$listItem[order_quantity];
					$itemSubtotal=money_format('%.2n',$itemSubtotal);
					
					$message.='<table width="650" border="1" align="center" cellpadding="3" cellspacing="0">
         <tr >
                <td colspan="6" bgcolor="#FFFFFF">Product - ';
				$message.=$listItem[order_product_name];
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
				$message.=$listItem[re_pr_ax]."&nbsp;".$listItem[re_pr_io]."&nbsp;&nbsp;".$listItem[re_pr_ax2]."&nbsp;".$listItem[re_pr_ud];
				$message.='</td>
                <td rowspan="8" align="center" valign="top">';
				$message.=$listItem[order_quantity];
				$message.='</td>
                <td rowspan="8" align="right" valign="top"><b>$';
				
				if ($sendPrices=="true"){
					$message.=$listItem[order_product_price];}
				else{
					$message.='n/a';
				}
				
				$message.='</b>';
				
				if ($sendPrices=="true"){
					if ($over_range!=0){
						$message.= '<br> Over range: '. $over_range;}
					if ($listItem[extra_product_price]!=0){
						//$message.= "<br>Extra item: ".$listItem[extra_product_price];
						}
			if ($e_total_price!=0){
						$message.= "<br>".$e_products_string;}
					if ($listItem[coupon_dsc]!=0){
						$message.= "<br>Coupon Dicount: -".$listItem[coupon_dsc];}
					$message.='<br>Subtotal: '.$itemSubtotal;
				}
				else{
					if ($over_range!=0){
						$message.= '<br> Over range: n/a';}
						
					if ($listItem[extra_product_price]!=0){
						$message.= " <br>Extra item: n/a";}	
					if ($e_total_price!=0){
						$message.= "<br>".$e_products_string_na;}
					if ($listItem[coupon_dsc]!=0){
						$message.= "<br>Coupon Dicount: n/a";}
					$message.='<br>Subtotal: n/a';
				}
				
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
                <td align="center">'.$listItem[le_pr_ax]."&nbsp;".$listItem[le_pr_io]."&nbsp;&nbsp;".$listItem[le_pr_ax2]."&nbsp;".$listItem[le_pr_ud].'</td>
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
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>FRAME:
                    &nbsp; </strong>'.$e_order_string_edging.' </td>
              </tr>
              <tr>
			    <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>OTHER:</strong> 
				'.$e_order_string_engraving.$e_order_string_tint.'
				</td>
				</tr>';
					  $message.='<tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF"><b>Special Instructions:</b>&nbsp;'.$listItem[special_instructions].' </td>
              </tr>';
              
            $message.='</table><br>';
						
						
						
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
	$message.='n/a';
}

$message.='</td></tr>';

if (($extra_product_price!=0)&&($sendPrices=="true")){//INCLUDE EXTRA ITEM
		$message.="<tr >
            <td width=\"524\" align=\"left\">Additional Item:&nbsp;".$extra_product;
		$message.="<td width=\"100\" align=\"right\" valign=\"middle\">&nbsp;".$extra_product_price."</td> </tr>";
		}

if (($additional_dsc!=0)&&($sendPrices=="true")){
	$message.="<tr >";
    $message.="<td width=\"524\" align=\"left\" >Additional Discount (".$discount_type.$additional_dsc.")</td>";
    $message.="<td width=\"100\" align=\"right\" valign=\"middle\"><b>-";
	
	if($discount_type=="$"){
					$totalDiscount=money_format('%.2n',$additional_dsc);
				}
	else if ($discount_type=="%"){
					$totalDiscount=money_format('%.2n',$totalPriceDsc*($additional_dsc/100));
				}
	$message.=$totalDiscount; 
	$message.= "</b></td></tr>";
			  
			  }
			  
$message.='<tr ><td width="524" align="left">Order Total with All Discounts</td>';
$message.='<td width="100" align="right" valign="middle"><b>$';
				
$totalPriceDsc=money_format('%.2n',$totalPriceDsc+$order_shipping_cost-$totalDiscount+$extra_product_price);

if ($sendPrices=="true"){				
	$message.=$totalPriceDsc." ".$currency; }
else{
	$message.='n/a';
}
			
	$message.='</b></td></tr></table><br>';
			
	$message.="</body></html>";

	if ($printit) {
	echo $message;
	die();
}
else $inviteSent = mail("$send_to_address", "$subject", "$message", "$headers");
}

?>
