<?php
require_once(__DIR__.'/../../constants/aws.constant.php');
require_once(__DIR__.'/../../constants/url.constant.php');

function createBarcode($order_num){
	return constant('DIRECT_LENS_URL')."/barcodes/".$order_num.".gif";
}

function sendStockConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id){//STOCK BULK AND TRAY CONFIRMATION
	include "../sec_connectEDLL.inc.php";
	$barcode=createBarcode($orderNum);
	
	$query="SELECT * FROM accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$usercount=mysqli_num_rows($result);
	
	if ($listItem['product_line']=="directlens")
		{$dl_logo_file= constant('DIRECT_LENS_URL')."/direct-lens/logos/direct-lens_logo.gif";
		$pl_text="Direct-Lens";}
	elseif ($listItem['product_line']=="mybbgclub")
		{$dl_logo_file= constant('DIRECT_LENS_URL')."/direct-lens/logos/bbgclub_logo.gif";
		$pl_text="MyBBGClub";}
	elseif ($listItem['product_line']=="aitlensclub")
		{$dl_logo_file= constant('DIRECT_LENS_URL')."/direct-lens/logos/aitclub_logo.gif";
		$pl_text="AIT Lens Club";}
	elseif ($listItem['product_line']=="ifcclubus")
		{$dl_logo_file= constant('DIRECT_LENS_URL')."/ifcopticclub.ca/design_images/ifc-ca-large.jpg";
		$pl_text="IFC Optic CLUB.us";}
	elseif($listItem['product_line']=="eye-recommend") 
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/direct-lens/logos/direct-lens_logo.gif";
		$pl_text="Direct-Lens";}
	elseif($listItem['product_line']=="eye-recommend") 
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/direct-lens/logos/direct-lens_logo.gif";
		$pl_text="Direct-Lens";}
	else
		{$dl_logo_file= constant('DIRECT_LENS_URL')."/direct-lens/logos/lensnet_logo.gif";
		$pl_text="LensNet Club";}
		

	$headers = "From:".$fromAddress."\r\n";
	$mime_boundary=md5(time()); 
	$headers .= 'MIME-Version: 1.0'."\r\n"; 
	$headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\""."\r\n"; 
					
	$headers .= "--".$mime_boundary."\r\n"; 
	$headers .=	"Content-Type: text/html; charset=\"iso-8859-1\"\r\n";
	$headers .= "Content-Transfer-Encoding: 8bit"."\r\n"; 
						
	$subject = "$pl_text Stock Order Confirmation - Order Number:$orderNum";
	$message='<html xmlns="http://www.w3.org/1999/xhtml"><head><base href="'.constant('DIRECT_LENS_URL').'/processOrder.php">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ORDER</title>
</head>';
	$message.="<link href=\"".constant('DIRECT_LENS_URL')."/dl.css\" rel=\"stylesheet\" type=\"text/css\" />";
	
$message.='<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img src="'.constant('DIRECT_LENS_URL').'/direct-lens/logos/'.$logo_file.'"/><td align="center"><img src="'.$dl_logo_file.'" width="200" height="60" /></td></td><td align="right"><img src="'.$barcode.'" width="190" height="50" />';//ABSOLUTE PATH TO THE BARCODE IMAGE


if (($pl_text<>"AIT Lens Club") && ($listItem[main_lab] <> 50)){
	$message.='<p>GST:&nbsp;808437370 RT 0001&nbsp;<br>&nbsp;&nbsp;QST:&nbsp;1227025278 TQ 0001</p>';
	}
	
	$message.='</td></tr></table>';
	
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
	$message.='</strong> </td></tr><tr ><td align="right" class="formCellNosides">Buying Group: </td><td class="formCellNosides"><strong>';
	$message.=$_SESSION["sessionUserData"]["bg_name"];
	$message.='</strong></td></tr></table>';
	
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

//BEGIN STOCK BY TRAY SECTION
$query2="SELECT * from orders WHERE user_id='$user_id' and order_num='$orderNum' and order_product_type='stock_tray' ORDER by order_item_date,primary_key";//SELECT ALL OPEN STOCK ORDERS

			$result2=mysqli_query($con,$query2) or die  ('I cannot select items because: ' . mysqli_error($con).$query2);
			$stockusercount=mysqli_num_rows($result2);
			if ($stockusercount != 0){
			
			 $message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox"><tr ><td bgcolor="#000099" class="tableHead">STOCK ITEMS - BY TRAY</td></tr></table>';
			
									while ($listItem=mysqli_fetch_array($result2,MYSQLI_ASSOC)){
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
                <td align="right" valign="top" class="formCellNosides">';
				$message.=$listItem[eye];
				$message.='</td>
                <td align="center"  valign="top" class="formCellNosides">';
				$message.=$listItem[order_product_name];
				$message.='</td>
                <td align="center" valign="top" class="formCellNosides">';
				$message.=$listItem[order_product_material];
				$message.='</td>
                <td align="center" valign="top" class="formCellNosides">';
				$message.=$listItem[order_product_index];
				$message.='</td>
                <td align="center" valign="top" class="formCellNosides">';
				$message.=$listItem[order_product_coating];
				$message.='</td>
                <td align="center" valign="top" class="formCellNosides">';
				$message.=$listItem[re_sphere];
				$message.='</td>
                <td align="center" valign="top" class="formCellNosides">';
				$message.=$listItem[re_cyl];
				$message.='</td>
                <td align="right" valign="top" class="formCellNosidesRA"><b>$';
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
						
						$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
         <tr >
                <td colspan="7" bgcolor="#D7E1FF" class="tableSubHead">Tray Reference - ';
				$message.=$listItem[tray_num];
				$message.='</td>
            
                <td bgcolor="#D7E1FF"  class="formCellNosidesRA" >&nbsp;</td>
  </tr>
              <tr >
                <td bgcolor="#E5E5E5" class="formCellNosides">&nbsp;</td>
                <td  align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Product</strong></td>
                <td  align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Material</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Index</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Coating</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Sphere</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Cylinder</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosidesRA"><strong>Price</strong></td>
              </tr>
              <tr>
                <td align="right" class="formCellNosides">';
				$message.=$listItem[eye];
				$message.='</td>
                <td align="center" class="formCellNosides">';
				$message.=$listItem[order_product_name];
				$message.='</td>
                <td align="center" class="formCellNosides">';
				$message.=$listItem[order_product_material];
				$message.='</td>
                <td align="center" class="formCellNosides">';
				$message.=$listItem[order_product_index];
				$message.='</td>
                <td align="center" class="formCellNosides">';
				$message.=$listItem[order_product_coating];
				$message.='</td>
                <td align="center" class="formCellNosides">';
				$message.=$listItem[re_sphere];
				$message.='</td>
                <td align="center" class="formCellNosides">';
				$message.=$listItem[re_cyl];
				$message.='</td>
                <td align="right" valign="top" class="formCellNosidesRA"><b>$';
				$message.=$listItem[order_product_price];
				$message.='</b></td>
              </tr>';
						
						//END RE SECTIOn
											}//END IF
					} //END WHILE
			}// END OF STOCK BY TRAY SECTION

//BEGIN STOCK BY BULK SECTION
$query2="SELECT * from orders WHERE user_id='$user_id' and order_num='$orderNum' and order_product_type='stock' ORDER by order_item_date";//SELECT ALL OPEN STOCK ORDERS

			$result2=mysqli_query($con,$query2) or die  ('I cannot select items because: ' . mysqli_error($con).$query2);
			$stockusercount=mysqli_num_rows($result2);
			if ($stockusercount != 0){
			
			 $message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox"><tr ><td bgcolor="#000099" class="tableHead">STOCK ITEMS - BULK</td></tr></table>';
			
					while ($listItem=mysqli_fetch_array($result2,MYSQLI_ASSOC)){
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
					
					
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="7" bgcolor="#D7E1FF" class="tableSubHead">Product -- ';
	$message.=$listItem[order_product_name];
	 $message.='</td></tr><tr >';
	 $message.='<td width="77" bgcolor="#E5E5E5" class="formCellNosides"><strong>Material</strong></td>';
	 $message.='<td width="53" bgcolor="#E5E5E5" class="formCellNosides"><strong>Index</strong></td>';
	 $message.='<td width="74" align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Coating</strong></td>';
	 $message.='<td width="69" align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Sphere</strong></td>';
	 $message.='<td width="129" align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Cylinder</strong></td>';
	$message.='<td align="center" bgcolor="#E5E5E5" class="formCellNosidesCenter"><strong>Quantity</strong></td>';
	$message.='<td align="center" bgcolor="#E5E5E5" class="formCellNosidesRA"><strong>Price</strong></td>';
	$message.='</tr><tr><td align="right" class="formCellNosides">';
	$message.=$listItem[order_product_material];
	$message.='</td><td align="right" class="formCellNosides">';
	$message.=$listItem[order_product_index];
	$message.='</td><td align="center" class="formCellNosides">';
	$message.=$listItem[order_product_coating];
	$message.='</td><td align="center" class="formCellNosides">';
	$message.=$listItem[re_sphere];
	$message.='</td><td align="center" class="formCellNosides">';
	$message.=$listItem[re_cyl];
	$message.='</td><td align="center" valign="top" class="formCellNosidesCenter">';
	$message.=$listItem[order_quantity];
	$message.='</td><td align="right" valign="top" class="formCellNosidesRA"><b>$';
	$message.=$listItem[order_product_price];
	$message.='</b><br>Subtotal:$';
	$message.=$itemSubtotal;
	$message.='</td></tr></table>';
					} 
			}// END OF STOCK BY BULK SECTION

//BEGINNING OF TOTALS SECTION


$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr >';
$message.='<td align="left" class="formCellNosides">Shipping:';
$message.=$order_shipping_method;
$message.='</td>';
$message.='<td align="right" valign="middle" class="formCellNosidesRA">';
$message.=$order_shipping_cost;
$message.='</td></tr>';
$message.='<tr ><td width="524" align="left" class="Subheader">Order Total with Your Account Discount</td>';
$message.='<td width="100" align="right" valign="middle" class="total"><b>$';
				
				$totalPriceDsc=money_format('%.2n',$totalPriceDsc+$order_shipping_cost);
	$message.=$totalPriceDsc." ".$currency; 
			
	$message.='</b></td></tr><tr><td style="font-size:8px;"  class="Subheader">Effective on April 1st 2013:
<b>1.5% charge on invoice not paid within 30 days following the date of statement
</b></td></tr></table>';
			
	$message.="</body></html>";

	//$inviteSent = mail("$send_to_address", "$subject", "$message", "$headers");
	
	//New SES email code
	$to_address = str_split($send_to_address,100);
	$from_address='donotreply@entrepotdelalunette.com';
	$curTime= date("m-d-Y");	
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	
	
	//Send a copy to the administration
	$CopyAdmin = "dbeaulieu@direct-lens.com";
	$to_address = str_split($CopyAdmin,100);
	$from_address='donotreply@entrepotdelalunette.com';
	$curTime= date("m-d-Y");	
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	
	/*//Send a copy to the stock_tr
	$CopyAdmin = "stock_tr@direct-lens.com";
	$to_address = str_split($CopyAdmin,100);
	$from_address='donotreply@entrepotdelalunette.com';
	$curTime= date("m-d-Y");	
	$response=office365_mail($to_address, $from_address, $subject, null, $message);*/
	
	//Send a copy to the stock_sct
	$CopyAdmin = "stock_sct@direct-lens.com";
	$to_address = str_split($CopyAdmin,100);
	$from_address='donotreply@entrepotdelalunette.com';
	$curTime= date("m-d-Y");	
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	
}

//*********************************************//

function sendStockConfirmationNoPrices($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id){//STOCK BULK AND TRAY CONFIRMATION
	include "../sec_connectEDLL.inc.php";
	$barcode=createBarcode($orderNum);
	
	$query="SELECTR * FROM accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$result=mysqli_query($con,$query)	or die  ('I cannot select items because: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$usercount=mysqli_num_rows($result);
	
	if ($listItem['product_line']=="directlens")
		{$dl_logo_file= constant('DIRECT_LENS_URL')."/direct-lens/logos/direct-lens_logo.gif";
		$pl_text="Direct-Lens";}
	elseif ($listItem['product_line']=="mybbgclub")
		{$dl_logo_file= constant('DIRECT_LENS_URL')."/direct-lens/logos/bbgclub_logo.gif";
		$pl_text="MyBBGClub";}
	elseif ($listItem['product_line']=="aitlensclub")
		{$dl_logo_file= constant('DIRECT_LENS_URL')."/direct-lens/logos/aitclub_logo.gif";
		$pl_text="AIT Lens Club";}
	elseif ($listItem['product_line']=="ifcclubus")
		{$dl_logo_file= constant('DIRECT_LENS_URL')."/ifcopticclub.ca/design_images/ifc-ca-large.jpg";
		$pl_text="IFC Optic CLUB.us";}
	else
		{$dl_logo_file= constant('DIRECT_LENS_URL')."/direct-lens/logos/lensnet_logo.gif";
		$pl_text="LensNet Club";}
		

	$headers = "From:".$fromAddress."\r\n";
	$mime_boundary=md5(time()); 
	$headers .= 'MIME-Version: 1.0'."\r\n"; 
	$headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\""."\r\n"; 
					
	$headers .= "--".$mime_boundary."\r\n"; 
	$headers .=	"Content-Type: text/html; charset=\"iso-8859-1\"\r\n";
	$headers .= "Content-Transfer-Encoding: 8bit"."\r\n"; 
						
	$subject = "$pl_text Stock Order Confirmation - Order Number:$orderNum";
	$message='<html xmlns="http://www.w3.org/1999/xhtml"><head><base href="'.constant('DIRECT_LENS_URL').'/processOrder.php">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ORDER</title>
</head>';
	$message.="<link href=\"".constant('DIRECT_LENS_URL')."/dl.css\" rel=\"stylesheet\" type=\"text/css\" />";
	
$message.='<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img src="'.constant('DIRECT_LENS_URL').'/direct-lens/logos/'.$logo_file.'"/><td align="center"><img src="'.$dl_logo_file.'" width="200" height="60" /></td></td><td align="right"><img src="'.$barcode.'" width="190" height="50" />';//ABSOLUTE PATH TO THE BARCODE IMAGE


if (($pl_text<>"AIT Lens Club") && ($listItem[main_lab] <> 50)){
	$message.='<p>GST:&nbsp;808437370 RT 0001&nbsp;<br>&nbsp;&nbsp;QST:&nbsp;1227025278 TQ 0001</p>';
	}
	
	$message.='</td></tr></table>';
	
	$message.='<table width="650" border="0" align="center" cellpadding="0" cellspacing="0"><tr>';
	$message.='<td><div class="header2">Your '.$pl_text.' Order #:'.$orderNum.'</div></td></tr></table>';
	
	$query="SELECT po_num from orders WHERE order_num='$orderNum'"; //GET ORDER PO NUMBER
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
	$message.='</strong> </td></tr><tr ><td align="right" class="formCellNosides">Buying Group: </td><td class="formCellNosides"><strong>';
	$message.=$_SESSION["sessionUserData"]["bg_name"];
	$message.='</strong></td></tr></table>';
	
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

//BEGIN STOCK BY TRAY SECTION
$query2="SELECT * from orders WHERE user_id='$user_id' and order_num='$orderNum' and order_product_type='stock_tray' ORDER by order_item_date,primary_key";//SELECT ALL OPEN STOCK ORDERS

			$result2=mysqli_query($con,$query2) or die  ('I cannot select items because: ' . mysqli_error($con).$query2);
			$stockusercount=mysqli_num_rows($result2);
			if ($stockusercount != 0){
			
			 $message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox"><tr ><td bgcolor="#000099" class="tableHead">STOCK ITEMS - BY TRAY</td></tr></table>';
			
									while ($listItem=mysqli_fetch_array($result2,MYSQLI_ASSOC)){
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
                <td align="right" valign="top" class="formCellNosides">';
				$message.=$listItem[eye];
				$message.='</td>
                <td align="center"  valign="top" class="formCellNosides">';
				$message.=$listItem[order_product_name];
				$message.='</td>
                <td align="center" valign="top" class="formCellNosides">';
				$message.=$listItem[order_product_material];
				$message.='</td>
                <td align="center" valign="top" class="formCellNosides">';
				$message.=$listItem[order_product_index];
				$message.='</td>
                <td align="center" valign="top" class="formCellNosides">';
				$message.=$listItem[order_product_coating];
				$message.='</td>
                <td align="center" valign="top" class="formCellNosides">';
				$message.=$listItem[re_sphere];
				$message.='</td>
                <td align="center" valign="top" class="formCellNosides">';
				$message.=$listItem[re_cyl];
				$message.='</td>
                <td align="right" valign="top" class="formCellNosidesRA"><b>$';
				//$message.=$listItem[order_product_price];
				$message.='</b><br>Subtotal: ';
				//$message.=$itemSubtotal;
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
						
						$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
         <tr >
                <td colspan="7" bgcolor="#D7E1FF" class="tableSubHead">Tray Reference - ';
				$message.=$listItem[tray_num];
				$message.='</td>
            
                <td bgcolor="#D7E1FF"  class="formCellNosidesRA" >&nbsp;</td>
  </tr>
              <tr >
                <td bgcolor="#E5E5E5" class="formCellNosides">&nbsp;</td>
                <td  align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Product</strong></td>
                <td  align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Material</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Index</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Coating</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Sphere</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Cylinder</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosidesRA"><strong>Price</strong></td>
              </tr>
              <tr>
                <td align="right" class="formCellNosides">';
				$message.=$listItem[eye];
				$message.='</td>
                <td align="center" class="formCellNosides">';
				$message.=$listItem[order_product_name];
				$message.='</td>
                <td align="center" class="formCellNosides">';
				$message.=$listItem[order_product_material];
				$message.='</td>
                <td align="center" class="formCellNosides">';
				$message.=$listItem[order_product_index];
				$message.='</td>
                <td align="center" class="formCellNosides">';
				$message.=$listItem[order_product_coating];
				$message.='</td>
                <td align="center" class="formCellNosides">';
				$message.=$listItem[re_sphere];
				$message.='</td>
                <td align="center" class="formCellNosides">';
				$message.=$listItem[re_cyl];
				$message.='</td>
                <td align="right" valign="top" class="formCellNosidesRA"><b>$';
				//$message.=$listItem[order_product_price];
				$message.='</b></td>
              </tr>';
						
						//END RE SECTIOn
											}//END IF
					} //END WHILE
			}// END OF STOCK BY TRAY SECTION

//BEGIN STOCK BY BULK SECTION
$query2="SELECT * from orders WHERE user_id='$user_id' and order_num='$orderNum' and order_product_type='stock' ORDER by order_item_date";//SELECT ALL OPEN STOCK ORDERS

			$result2=mysqli_query($con,$query2) or die  ('I cannot select items because: ' . mysqli_error($con).$query2);
			$stockusercount=mysqli_num_rows($result2);
			if ($stockusercount != 0){
			
			 $message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox"><tr ><td bgcolor="#000099" class="tableHead">STOCK ITEMS - BULK</td></tr></table>';
			
									while ($listItem=mysqli_fetch_array($result2,MYSQLI_ASSOC)){
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
					
					
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="7" bgcolor="#D7E1FF" class="tableSubHead">Product -- ';
	$message.=$listItem[order_product_name];
	 $message.='</td></tr><tr >';
	 $message.='<td width="77" bgcolor="#E5E5E5" class="formCellNosides"><strong>Material</strong></td>';
	 $message.='<td width="53" bgcolor="#E5E5E5" class="formCellNosides"><strong>Index</strong></td>';
	 $message.='<td width="74" align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Coating</strong></td>';
	 $message.='<td width="69" align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Sphere</strong></td>';
	 $message.='<td width="129" align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Cylinder</strong></td>';
	$message.='<td align="center" bgcolor="#E5E5E5" class="formCellNosidesCenter"><strong>Quantity</strong></td>';
	$message.='<td align="center" bgcolor="#E5E5E5" class="formCellNosidesRA"><strong>Price</strong></td>';
	$message.='</tr><tr><td align="right" class="formCellNosides">';
	$message.=$listItem[order_product_material];
	$message.='</td><td align="right" class="formCellNosides">';
	$message.=$listItem[order_product_index];
	$message.='</td><td align="center" class="formCellNosides">';
	$message.=$listItem[order_product_coating];
	$message.='</td><td align="center" class="formCellNosides">';
	$message.=$listItem[re_sphere];
	$message.='</td><td align="center" class="formCellNosides">';
	$message.=$listItem[re_cyl];
	$message.='</td><td align="center" valign="top" class="formCellNosidesCenter">';
	$message.=$listItem[order_quantity];
	$message.='</td><td align="right" valign="top" class="formCellNosidesRA"><b>$';
	//$message.=$listItem[order_product_price];
	$message.='</b><br>Subtotal:$';
	//$message.=$itemSubtotal;
	$message.='</td></tr></table>';
					} 
			}// END OF STOCK BY BULK SECTION

//BEGINNING OF TOTALS SECTION


$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr >';
$message.='<td align="left" class="formCellNosides">Shipping:';
$message.=$order_shipping_method;
$message.='</td>';
$message.='<td align="right" valign="middle" class="formCellNosidesRA">';
//$message.=$order_shipping_cost;
$message.='</td></tr>';
$message.='<tr ><td width="524" align="left" class="Subheader">Order Total with Your Account Discount</td>';
$message.='<td width="100" align="right" valign="middle" class="total"><b>$';
				
				$totalPriceDsc=money_format('%.2n',$totalPriceDsc+$order_shipping_cost);
	//$message.=$totalPriceDsc." ".$currency; 
			
	$message.='</b></td></tr><tr><td style="font-size:8px;"  class="Subheader">Effective on April 1st 2013:
<b>1.5% charge on invoice not paid within 30 days following the date of statement
</b></td></tr></table>';
			
	$message.="</body></html>";

	//$inviteSent = mail("$send_to_address", "$subject", "$message", "$headers");
	
	//New SES email code
	$to_address = str_split($send_to_address,100);
	$from_address='donotreply@entrepotdelalunette.com';
	$curTime= date("m-d-Y");	
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	
	//Send a copy to the administration
	$CopyAdmin = "dbeaulieu@direct-lens.com";
	$to_address = str_split($CopyAdmin,100);
	$from_address='donotreply@entrepotdelalunette.com';
	$curTime= date("m-d-Y");	
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	
}

//***********************************************//

function sendPrescriptionConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$sendPrices){//PRESCRIPTION CONFIRMATION
	include "../sec_connectEDLL.inc.php";
	$barcode=createBarcode($orderNum);
	
	$query="SELECT * FROM accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$result=mysqli_query($con,$query)	or die  ('I cannot select items because: ' . mysqli_error($con));	
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$usercount=mysqli_num_rows($result);
	$Product_line = $listItem[product_line];
	
	if ($listItem['product_line']=="directlens")
		{$dl_logo_file= constant('DIRECT_LENS_URL')."/direct-lens/logos/direct-lens_logo.gif";
		$pl_text="Direct-Lens";}
	elseif ($listItem['product_line']=="mybbgclub")
		{$dl_logo_file= constant('DIRECT_LENS_URL')."/direct-lens/logos/bbgclub_logo.gif";
		$pl_text="MyBBGClub";}
	elseif ($listItem['product_line']=="aitlensclub")
		{$dl_logo_file= constant('DIRECT_LENS_URL')."/direct-lens/logos/aitclub_logo.gif";
		$pl_text="AIT Lens Club";}
	elseif ($listItem['product_line']=="ifcclubus")
		{$dl_logo_file= constant('DIRECT_LENS_URL')."/ifcopticclub.ca/design_images/ifc-ca-large.jpg";
		$pl_text="IFC Optic CLUB.us";}
	elseif (($listItem['product_line']=="safety") && (strtolower($listItem['language'])=="english"))
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/safety/images/LOGO_SAFE_EN.jpg";
		$pl_text="SAFE";}
	elseif (($listItem['product_line']=="safety") && (strtolower($listItem['language'])=="french"))
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/safety/images/LOGO_SAFE_FR.jpg";
		$pl_text="SAFE";}
	elseif($listItem['product_line']=="eye-recommend") 
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/direct-lens/logos/direct-lens_logo.gif";
		$pl_text="Direct-Lens Prestige";}
	else
		{$dl_logo_file= constant('DIRECT_LENS_URL')."/direct-lens/logos/lensnet_logo.gif";
		$pl_text="LensNet Club";}
		
		
	if (($listItem['main_lab']== 66) || ($listItem['main_lab']== 67)){  //Main lab des entrepots
		$CompteEdll = 'oui';
		//Ne pas afficher de logo en attendant celui de Sara
		$dl_logo_file="nologo";
	}else{
		$CompteEdll = 'non';
	}		
		
		
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
	$message.="<link href=\"".constant('DIRECT_LENS_URL')."/dl.css\" rel=\"stylesheet\" type=\"text/css\" />";
$message.='<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img src="'.constant('DIRECT_LENS_URL').'/direct-lens/logos/'.$logo_file.'"/><td align="center"><img src="'.$dl_logo_file.'" width="200" height="60" /></td></td><td align="right"><img src="'.$barcode.'" width="190" height="50" />';//ABSOLUTE PATH TO THE BARCODE IMAGE


	if ($CompteEdll == 'oui'){//Afficher les numéros de taxes de l'entrepot
		$message.='<p>TPS/TVH:&nbsp;830136776 RT0001&nbsp;<br>&nbsp;&nbsp;TVQ:&nbsp;1220985128 TQ0001</p>';
	}elseif(($pl_text<>"AIT Lens Club") && ($listItem[main_lab] <> 50) && ($pl_text<>"SAFE")){
		$message.='<p>GST:&nbsp;808437370 RT 0001&nbsp;<br>&nbsp;&nbsp;QST:&nbsp;1227025278 TQ 0001</p>';
	}
	
	$message.='</td></tr></table>';
	
	
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
			
					
					while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
					$PrescriptLab = $listItem[prescript_lab];
					
					$bl_query="SELECT * FROM additional_discounts WHERE orders_id='$listItem[primary_key]'";//GET BUYING LEVEL DISCOUNT
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
								
								if (($Product_line=='eye-recommend') && ($e_listItem[price] > 0)){ 
									switch($listItem[frame_type]){
										case 'Plastic'     : 	$ListPrice = 16.00; break;
										case 'Metal'       : 	$ListPrice = 16.00; break;
										case 'Nylon Groove': 	$ListPrice = 20.00; break;
										case 'Metal Groove': 	$ListPrice = 25.00; break;
										case 'Drill and Notch': $ListPrice = 40.00; break;	  
										//case 'Edge Polish' : 	$ListPrice = ; break;	  	  	
									}
									$PrestigeRebateforExtra = $PrestigeRebateforExtra + ($ListPrice - $e_listItem[price]);
									$e_listItem[price] 	    = money_format('%.2n',$ListPrice);
								}//End if Eye recommend
								
								$e_products_string.="Edging: ".$e_listItem[price];
								$e_products_string_na.="Edging: n/a";
								
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

							if ($e_listItem[category]=="Large frame"){
								$e_order_string_Large_frame.="<br />Large frame fee: ".$e_listItem[price];
							}
			

							if ($e_listItem[category]=="Engraving"){
								$e_products_string.="<br />Engraving: ".$e_listItem[price];
								$e_products_string_na.="<br />Engraving: n/a";
								$e_order_string_engraving="<b>Engraving: </b>".$e_listItem[engraving]." ";
							}

								
								
								
							if ($e_listItem[category]=="Tint"){
								if (($Product_line=='eye-recommend') && ($e_listItem[price] > 0) && ($e_listItem[tint]=="Solid")){
									$ListPrice = 15.00; 
									$PrestigeRebateforExtra = $PrestigeRebateforExtra + ($ListPrice - $e_listItem[price]);
									$e_listItem[price] 	    = money_format('%.2n',$ListPrice);
								}
								
								if (($Product_line=='eye-recommend') && ($e_listItem[price] > 0) && ($e_listItem[tint]<>"Solid")){
									$ListPrice = 20.00; 
									$PrestigeRebateforExtra = $PrestigeRebateforExtra + ($ListPrice - $e_listItem[price]);
									$e_listItem[price] 	    = money_format('%.2n',$ListPrice);
								}

								$e_products_string.="<br />Tint: ".$e_listItem[price];
								$e_products_string_na.="<br />Tint: n/a";
								$e_order_string_tint="<b>Tint: </b>".$e_listItem[tint].": ";
								if ($e_listItem[tint]=="Solid")
									{ $e_order_string_tint.= $e_listItem[from_perc]."% <b>Color:</b> ".$e_listItem[tint_color];}
								else
									{$e_order_string_tint.= $e_listItem[from_perc]."% to ".$e_listItem[to_perc] ."% <b>Color:</b> ".$e_listItem[tint_color];}
							}//END TINT
							
							if ($e_listItem[category]=="High Addition"){
								if (($Product_line=='eye-recommend') && ($e_listItem[price] > 0)){
									$ListPrice = 20.00; 
									$PrestigeRebateforExtra = $PrestigeRebateforExtra + ($ListPrice - $e_listItem[price]);
									$e_listItem[price] 	    = money_format('%.2n',$ListPrice);
								}
								$e_products_string.="<br />High Addition: ".$e_listItem[price];
								$e_products_string_na.="<br />High Addition: n/a";
								$e_order_string_tint="<b>High Addition: </b>";
							}//End High Addition
							
							if ($e_listItem[category]=="High Cylinder"){
								if (($Product_line=='eye-recommend') && ($e_listItem[price] > 0)){
									$ListPrice = 10.00; 
									$PrestigeRebateforExtra = $PrestigeRebateforExtra + ($ListPrice - $e_listItem[price]);
									$e_listItem[price] 	    = money_format('%.2n',$ListPrice);
								}	
								$e_products_string    .= "<br />High Cylinder: "   . $e_listItem[price];
								$e_products_string_na .= "<br />High Cylinder: n/a";
								$e_order_string_tint   = "<b>High Cylinder: </b>";
							}//End High Cylinder
							
							
							if ($e_listItem[category]=="Cylinder Over Range"){
								$e_products_string    .= "<br />Cylinder Over Range: "   . $e_listItem[price];
								$e_products_string_na .= "<br />Cylinder Over Range: n/a";
								$e_order_string_tint   = "<b>Cylinder Over Range: </b>";
							}//End High Cylinder
							
							
							if ($e_listItem[category]=="Special Size"){
								if (($Product_line=='eye-recommend') && ($e_listItem[price] > 0)){
									$ListPrice = 25.00; 
									$PrestigeRebateforExtra = $PrestigeRebateforExtra + ($ListPrice - $e_listItem[price]);
									$e_listItem[price] 	    = money_format('%.2n',$ListPrice);
								}	
								$e_products_string.="<br />Special Size: ".$e_listItem[price];
								$e_products_string_na.="<br />Special Size: n/a";
								$e_order_string_tint="<b>Special Size: </b>";
							}//End Special Size
							
							if ($e_listItem[category]=="Special_Base"){	
								if (($Product_line=='eye-recommend') && ($e_listItem[price] > 0)){
									$ListPrice = 20.00; 
									$PrestigeRebateforExtra = $PrestigeRebateforExtra + ($ListPrice - $e_listItem[price]);
									$e_listItem[price] 	    = money_format('%.2n',$ListPrice);
								}
								$e_products_string.="<br />Special Base: ".$e_listItem[price];
								$e_products_string_na.="<br />Special Base: n/a";
								$e_order_string_tint="<b>Special Base: </b>";
							}
							
							if ($e_listItem[category]=="Mirror"){	
								$e_products_string.="<br />Mirror: ".$e_listItem[price];
								$e_products_string_na.="<br />Mirror: n/a";
								$e_order_string_tint="<b>Mirror: </b>";
								$DetailMirror ="<b>Mirror</b>: ".  $e_listItem['tint_color'] ;
							}//End Mirror
							
							
							if ($e_listItem[category]=="Top urgent"){	
								$e_products_string.="<br />Top Urgent: ".$e_listItem[price];
								$e_products_string_na.="<br />Top urgent: n/a";
								$e_order_string_tint="<b>Top Urgent: </b>";
							}
							
							if ($e_listItem[category]=="Large Frame"){	
								$e_products_string.="<br />Large Frame: ".$e_listItem[price];
								$e_products_string_na.="<br />Large Frame: n/a";
								$e_order_string_tint="<b>Large Frame: </b>";
							}
							
							
							if ($e_listItem[category]=="Prism"){
								if ($Product_line=='eye-recommend'){
									$queryPrisme  = "SELECT re_pr_ax, le_pr_ax, re_pr_ax2, le_pr_ax2 FROM orders WHERE primary_key='$listItem[primary_key]'";
									$resultPrisme = mysqli_query($con,$queryPrisme) or die  ('I cannot select Prism items because: ' . mysqli_error($con).$queryPrisme);
									$DataPrisme   = mysqli_fetch_array($resultPrisme,MYSQLI_ASSOC);
									$axe1 		  = $DataPrisme[re_pr_ax];
									$axe2 		  = $DataPrisme[le_pr_ax];
									$axe3 		  = $DataPrisme[re_pr_ax2];
									$axe4 		  = $DataPrisme[le_pr_ax2];						
									if (($axe1 > 4) || ($axe2 > 4) ||($axe3 > 4) ||($axe4 > 4)){
										//Prisme en haut de 4
										$ListPrice = 15.00; 
										$PrestigeRebateforExtra = $PrestigeRebateforExtra + ($ListPrice - $e_listItem[price]);
										$e_listItem[price] 	    = money_format('%.2n',$ListPrice);
									}else{
										//Prisme entre 0 et 4
										$ListPrice = 10.00; 
										$PrestigeRebateforExtra = $PrestigeRebateforExtra + ($ListPrice - $e_listItem[price]);
										$e_listItem[price] 	    = money_format('%.2n',$ListPrice);
									}
								}//End if Prestige
								$e_products_string.="<br />Prism: ".$e_listItem[price];
								$e_products_string_na.="<br />Prism: n/a";
							}//End Prism
								

							if ($e_listItem[category]=="Removable Side Shield"){
								$e_products_string.="<br />Removable Side Shield: ".$e_listItem[price];
								$e_products_string_na.="<br />Removable Side Shield: n/a";
								$e_order_string_tint="<b>Removable Side Shield: </b>".$e_listItem[tint].": ";
							}//END Side shield
							
							if ($e_listItem[category]=="Cushion"){
								$e_products_string.="<br />Cushion: ".$e_listItem[price];
								$e_products_string_na.="<br />Cushion: n/a";
								$e_order_string_tint="<b>Cushion: </b>";
							}//END Cushion
							
							if ($e_listItem[category]=="Dust Bar"){
								$e_products_string.="<br />Dust Bar: ".$e_listItem[price];
								$e_products_string_na.="<br />Dust Bar: n/a";
								$e_order_string_tint="<b>Dust Bar: </b>";
							}//END Dust bar
							
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
					if (($Product_line=='eye-recommend') && ($over_range > 0)){
									$ListPrice 				= 10.00; 
									$PrestigeRebateforExtra = $PrestigeRebateforExtra + ($ListPrice - $over_range);
									$DifferenceOverRange    = $ListPrice - $over_range;
									$over_range    			= $ListPrice;
									$over_range             = money_format('%.2n',$over_range);
					}	
					
					
					$coupon_dsc=$listItem[coupon_dsc];
					
					//Charles
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
					
					
					
					$itemSubtotal=$listItem[order_quantity]*($listItem[order_product_price]+$e_total_price)+$over_range-$coupon_dsc + $PrestigeRebateforExtra -$DifferenceOverRange  ;;
					$itemSubtotal=$itemSubtotal-$MontantDejaPaye;
					$totalPrice=$totalPrice+$itemSubtotal;
					
					$itemSubtotalDsc=$listItem[order_quantity]*($listItem[order_product_discount]+$e_total_price)+$over_range-$coupon_dsc+$buying_level_dsc-$MontantDejaPaye -  $DifferenceOverRange;
					$itemSubtotalDsc=$itemSubtotalDsc-$MontantDejaPaye;
					$totalPriceDsc=$totalPriceDsc+$itemSubtotalDsc;
					
					$RabaisSurFacture =money_format('%.2n', $listItem[order_product_price]  - $listItem[order_product_discount]);
					
					
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
					
					
			   $queryEntryFee  = "SELECT entry_fee FROM orders WHERE order_num  = " . $listItem[order_num];
			   $resultEntryFee = mysqli_query($con,$queryEntryFee)	or die ("Could not select items");
			   $DataEntryFee   = mysqli_fetch_array($resultEntryFee,MYSQLI_ASSOC);
			   
			   $queryWarranty  = "SELECT warranty FROM orders WHERE order_num  = " . $listItem[order_num];
			   $resultWarranty = mysqli_query($con,$queryWarranty)	or die ("Could not select items");
			   $DataWarranty   = mysqli_fetch_array($resultWarranty,MYSQLI_ASSOC);
					
				if ($sendPrices=="true"){
						if ($DataEntryFee[entry_fee] == 2) 
						{
						$message.= '<br><b>Data Entry Fee</b> 2$(inc. in product price)';
						}  
						
						if ($DataWarranty[warranty] == 1) 
						{
						$message.= '<br><b>Warranty Fee</b> 6$(inc. in product price)';
						}  
						
						if ($DataWarranty[warranty] == 2) 
						{
						$message.= '<br><b>Warranty Fee</b> 10$(inc. in product price)';
						} 
				} 

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
						
					$RabaisSurFacture += $PrestigeRebateforExtra ;//Ajouter le rabais qu'on donne sur les extras au rabais accordé sur les verres	
						
					$message.='<br>Subtotal: '.$itemSubtotal;
					if ($RabaisSurFacture > 0)
					$message.= '<br>Rebate:'.  money_format('%.2n',$RabaisSurFacture);
					}
				else{
					if ($over_range!=0){
						$message.= '<br> Over range: n/a';}
					if ($e_total_price!=0){
						$message.= "<br>".$e_products_string_na;}
					if ($coupon_dsc!=0){
						$message.= '<br> Coupon Discount: n/a';}
						
				
						
						
					$message.='<br>Subtotal:  n/a';
				}//END Send Prices Conditional
				
				
			if ($MontantDejaPaye <> 0 ){
			$message.= "<br><br><b>Paid by tiers: -". $MontantDejaPaye.'</b>';			
			}
				
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
			
	$message.='</b></td></tr><tr><td style="font-size:8px;"  class="Subheader">Effective on April 1st 2013:
<b>1.5% charge on invoice not paid within 30 days following the date of statement
</b></td></tr></table>';
			
	$message.="</body></html>";

//New SES email code
$to_address = str_split($send_to_address,100);
$from_address='donotreply@entrepotdelalunette.com';
$curTime= date("m-d-Y");	
$response=office365_mail($to_address, $from_address, $subject, null, $message);

	//Si le laboratoire est US LAB, envoyer une copie sur leur email
	if ($PrescriptLab == 58){
	$send_to_address = 'dbeaulieu@direct-lens.com';	
	$to_address = str_split($send_to_address,100);
	$from_address='donotreply@entrepotdelalunette.com';
	$curTime= date("m-d-Y");	
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	}else{
	//echo '<br>$PrescriptLab:.'. $PrescriptLab;	
	}//End IF
	
}//End Function












function sendFrameStockConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id){//STOCK BULK AND TRAY CONFIRMATION
include "../sec_connectEDLL.inc.php";
$sendPrices = true;

	$bg_query="SELECT bg_name FROM buying_groups WHERE primary_key='$userData[buying_group]'";//GET BUYING GROUP NAME
	$bg_result=mysqli_query($con,$bg_query)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$bgData=mysqli_fetch_array($bg_result,MYSQLI_ASSOC);

	$barcode="barcodes/".$orderNum.".gif";
	
	$query="SELECT * FROM accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$result=mysqli_query($con,$query)		or die  ('I cannot select items because: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$usercount=mysqli_num_rows($result);
		
	if ($listItem['product_line']=="directlens")
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/direct-lens/logos/direct-lens_logo.gif";
		$pl_text="Direct-Lens";}
	elseif ($listItem['product_line']=="mybbgclub")
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/direct-lens/logos/bbgclub_logo.gif";
		$pl_text="MyBBGClub";}
	elseif ($listItem['product_line']=="aitlensclub")
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/direct-lens/logos/aitclub_logo.gif";
		$pl_text="AIT Lens Club";}
	elseif ($listItem['product_line']=="ifcclub")
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/direct-lens/logos/ifc_club_logo.gif";
		$pl_text="IFC Optic CLUB";}
	elseif ($listItem['product_line']=="ifcclubca")
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/ifcopticclub.ca/design_images/ifc-ca-large.jpg";
		$pl_text="IFC Optic CLUB.ca";}
	elseif ($listItem['product_line']=="ifcclubus")
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/ifcopticclub.ca/design_images/ifc-ca-large.jpg";
		$pl_text="IFC Optic CLUB.us";}
	elseif (($listItem['product_line']=="safety") && (strtolower($listItem['language'])=="french"))
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/safety/images/LOGO_SAFE_FR.jpg";
		$pl_text="SAFE";}
	elseif (($listItem['product_line']=="safety") && (strtolower($listItem['language'])=="english"))
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/safety/images/LOGO_SAFE_EN.jpg";
		$pl_text="SAFE";}
	elseif ($listItem['product_line']=="milano6769") 
		{$dl_logo_file= constant('DIRECT_LENS_URL')."/logos/logoblanc.png";
		$pl_text="SophisTechEye";}
	else
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/direct-lens/logos/lensnet_logo.gif";
		$pl_text="LensNet";}

	$headers = "From:".$fromAddress."\r\n";
	$mime_boundary=md5(time()); 
	$headers.='MIME-Version: 1.0'."\r\n"; 
	$headers.="Content-Type: multipart/related; boundary=\"".$mime_boundary."\""."\r\n"; 
					
	$headers.="--".$mime_boundary."\r\n"; 
	$headers.="Content-Type: text/html; charset=\"iso-8859-1\"\r\n";
	$headers.="Content-Transfer-Encoding: 8bit"."\r\n"; 
	
	$subject = $pl_text." Stock Order Confirmation (REVISED) - Order Number:$orderNum";
	//$subject="fax -html 5026356154";
	$message='<html xmlns="http://www.w3.org/1999/xhtml"><head><base href="'.constant('DIRECT_LENS_URL').'/processOrder.php">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ORDER</title>';
	if ($printit) $message .= "<script type='text/javascript'>window.onload = function() {window.print();}</script>";
$message .= '</head>';
	$message.='<link href="'.constant('DIRECT_LENS_URL').'/dl.css" rel="stylesheet" type="text/css" />';

	
	$message.='<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left">';
	
	if ($dl_logo_file<> constant('DIRECT_LENS_URL')."/logos/logoblanc.png")
	$message .='<img width="250" src="logos/'.$logo_file.'"/>';

	$message .='<td align="center">';
	if ($dl_logo_file=="nologo")
	$message.='';
	else
	$message.='<img src="'.$dl_logo_file.'" width="200" height="60" />';
	
	
	$message .='</td></td><td align="right"><img src="'.$barcode.'" width="190" height="50" />';
	
	if ($dl_logo_file<>"nologo")
	$message .='<p>GST:&nbsp;808437370 RT 0001&nbsp;<br>&nbsp;&nbsp;QST:&nbsp;1227025278 TQ 0001</p>';
	
	$message .='</td></tr></table>';//ABSOLUTE PATH TO THE BARCODE IMAGE
	
		$message.='<table width="650" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td align="left">';
	$message.='<td><div class="header2">Your '.$pl_text.' Order #:'.$orderNum.'</div></td></tr></table>';
	
	$query="SELECT po_num FROM orders WHERE order_num='$orderNum'"; //GET ORDER PO NUMBER
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr><td align="left" class="formCellNosides">PO Order #: ';
	$message.=$listItem[po_num];
	$message.='</td></tr></table>';
	
	$query="SELECT * FROM accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$usercount=mysqli_num_rows($result);
	
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#000099" class="tableHead">ACCOUNT INFORMATION </td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Name on Account :</td>';
    $message.='<td width="520" class="formCellNosides"><strong>';
	$message.=$listItem[title].' '.$listItem[first_name].' '.$listItem[last_name];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Account Number :</td>';
    $message.='<td width="520"  class="formCellNosides"><strong>';
	$message.=$listItem[account_num];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Company:</td><td width="520" class="formCellNosides"><strong>';
	$message.=$listItem[company];
	$message.='</strong> </td></tr><tr ><td align="right" class="formCellNosides">Buying Group: </td><td class="formCellNosides"><strong>';
	$message.=$bgData[bg_name];
	$message.='</strong></td></tr></table>';
	
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#000099" class="tableHead">SHIPPING ADDRESS </td></tr><tr ><td width="130" align="right" class="formCellNosides">Address 1:</td><td width="520" class="formCellNosides"><strong>';
	$message.=$listItem[ship_address1];
	$message.='</strong></td></tr><tr ><td width="130" align="right" class="formCellNosides">Address 2:</td><td width="520" class="formCellNosides"><strong>';
$message.=$listItem[ship_address2];
$message.='</strong></td></tr><tr ><td align="right" class="formCellNosides">City:</td><td width="520" class="formCellNosides"><strong>';
$message.=$listItem[ship_city];
$message.='</strong></td></tr><tr ><td align="right" class="formCellNosides">State:</td><td width="520" class="formCellNosides"><strong>';
$message.=$listItem[ship_state];
$message.='</strong> </td></tr><tr ><td align="right" class="formCellNosides">Postal Code:  </td><td class="formCellNosides"><strong>';
$message.=$listItem[ship_zip];
$message.='</strong></td></tr><tr><td align="right" class="formCellNosides">Country:</td><td class="formCellNosides"><strong>';
$message.=$listItem[ship_country];
$message.='</strong></td></tr></table>';//END of Address Section

//BEGIN STOCK BY TRAY SECTION
$query2="SELECT * from orders WHERE user_id='$user_id' and order_num='$orderNum' and order_product_type='frame_stock_tray' ORDER by order_item_date,primary_key";//SELECT ALL OPEN STOCK ORDERS
			$result2=mysqli_query($con,$query2) or die  ('I cannot select items because: ' . mysqli_error($con));
			$stockusercount=mysqli_num_rows($result2);
			if ($stockusercount != 0){
			
			 $message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox"><tr ><td bgcolor="#000099" class="tableHead">FRAMES STOCK ITEMS</td></tr></table>';
			
						while ($listItem=mysqli_fetch_array($result2,MYSQLI_ASSOC)){
						
						
						
						
						$order_shipping_method=$listItem[order_shipping_method];
						$currency=$listItem[currency];
						$additional_dsc=$listItem[additional_dsc];
						$discount_type=$listItem[discount_type];
						$extra_product=$listItem[extra_product];
						$extra_product_price=$listItem[extra_product_price];
						$order_shipping_cost=$listItem[order_shipping_cost];
						$counter++;
					$itemSubtotal=$itemSubtotal+$listItem[order_product_price];
					$itemSubtotalDsc=$itemSubtotalDsc+$listItem[order_product_discount];
					$totalTrayQuant=$totalTrayQuant+$listItem[order_quantity];
					$itemSubtotal=money_format('%.2n',$itemSubtotal);
				
						//BEGIN RE SECTION
						
						$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
         <tr >
                <td colspan="7" bgcolor="#D7E1FF" class="tableSubHead">Reference - ';
				$message.=$listItem[tray_num];
				
				$SousTotal = $listItem[order_quantity] * $listItem[order_product_price];
				$SousTotal=money_format('%.2n',$SousTotal);
				
				
				$message.='</td>
            
                <td bgcolor="#D7E1FF"  class="formCellNosidesRA" >&nbsp;</td>
  </tr>
              <tr >
                <td  align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Frame</strong></td>
				<td  align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Frame Type</strong></td>
                <td  align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>&nbsp;</strong></td>
				<td  align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>&nbsp;</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Qty</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Unit Price</strong></td>
				<td  align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>&nbsp;</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosidesRA"><strong>Subtotal</strong></td>
              </tr>
              <tr>
                <td width="250" align="center" class="formCellNosides">';
				$message.=$listItem[order_product_name];
				$message.='</td>
                <td align="center" class="formCellNosides">';
				$message.=$listItem[order_product_material];
				$message.='</td><td align="center" class="formCellNosides">&nbsp;</td>
                <td align="center" class="formCellNosides">&nbsp;</td>
                <td align="center" class="formCellNosides">';
				$message.=$listItem[order_quantity];
				$message.='</td>
                <td align="center" class="formCellNosides">';
				$message.=$listItem[order_product_price];
				$message.='</td>
                <td align="center" class="formCellNosides">&nbsp;</td>
                <td align="right" valign="top" class="formCellNosidesRA"><b>$' . $SousTotal;
				//$message.=$listItem[order_product_price];
				$message.='</b></td>
              </tr>';
						
						//END RE SECTION
											
							$LeOrderNum = 	$listItem[order_num];								
																} //END WHILE
																
				    $queryTotaltoPay = "SELECT order_total, order_quantity, order_product_price FROM orders WHERE order_num = " . $LeOrderNum;
					$resultTotaltoPay = mysqli_query($con,$queryTotaltoPay)		or die  ('I cannot select items because 9: ' . mysqli_error($con));
					$TotalaPayer = 0;
					while ($DataTotaltoPay=mysqli_fetch_array($resultTotaltoPay,MYSQLI_ASSOC)){
					$TotalaPayer = $TotalaPayer + $DataTotaltoPay[order_product_price] * $DataTotaltoPay[order_quantity] ;
					//echo '<br>Total a payer : ' . $TotalaPayer . '+'.  $DataTotaltoPay[order_product_price] *  $DataTotaltoPay[order_quantity];
					}
				    $TotalaPayer = $TotalaPayer + $order_shipping_cost;
					$TotalaPayer=money_format('%.2n',$TotalaPayer);
					//echo '<br><br>Total Final a payer:'. $TotalaPayer;
					//echo '<br>'. $queryTotaltoPay . '<br>' ;
			}// END OF STOCK BY TRAY SECTION


//BEGINNING OF TOTALS SECTION


$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">';

$queryTotalQty   = "SELECT SUM(order_quantity) as totalqty FROM orders WHERE order_num =  $orderNum";
$resultTotalQty  = mysqli_query($con,$queryTotalQty)		or die  ('I cannot select items because 8: ' . mysqli_error($con));
$DataProductline = mysqli_fetch_array($resultTotalQty,MYSQLI_ASSOC);
//Ajout pour  afficher la quantité totale de montures
$message.='<tr><td colspan="5" align="right" bgcolor="#FFFFFF" class="formCellNosides"><b>Total Qty:</b>&nbsp;'.$DataProductline[totalqty].' frame';

if ($DataProductline[totalqty] > 1)
$message.='s';
$message.='</td></tr>';


$message.='<tr><td align="left" class="formCellNosides"><b>SubTotal</b>:';
$message.='</td>';
$message.='<td align="right" valign="middle" class="formCellNosidesRA">';
$TotalaPayerSansShipping = $TotalaPayer-1.95;
$message.='<b>$'.$TotalaPayerSansShipping.'</b>';

$message.='</td></tr>';

$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr>';
$message.='<td align="left" class="formCellNosides">Shipping:';
$message.=$order_shipping_method;
$message.='</td>';
$message.='<td align="right" valign="middle" class="formCellNosidesRA">$';
$message.=$order_shipping_cost;
$message.='</td></tr>';
			  
			  
$message.='<tr ><td width="524" align="left" class="Subheader">Order Total with All Discounts</td>';
$message.='<td width="100" align="right" valign="middle" class="total"><b>$';
$extra_product_price=0;	
			
$totalPriceDsc=money_format('%.2n',$totalPriceDsc+$order_shipping_cost-$totalDiscount+$extra_product_price);

if ($sendPrices=="true"){
$message.=$TotalaPayer." ".$currency; 
}else{
$message.='n/a';
}


$message.='</b></td></tr><tr><td style="font-size:8px;"  class="Subheader">Effective on April 1st 2013:
<b>1.5% charge on invoice not paid within 30 days following the date of statement';
$message.='</b></td></tr></table>';
$message.="</body></html>";
	
	//New SES email code
	$to_address = str_split($send_to_address,100);
	$from_address='donotreply@entrepotdelalunette.com';
	$curTime= date("m-d-Y");	
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	
	//Send a copy to the administration
//	$CopyAdmin = "dbeaulieu@direct-lens.com";
    $CopyAdmin = "dbeaulieu@direct-lens.com";
	$to_address = str_split($CopyAdmin,100);
	$from_address='donotreply@entrepotdelalunette.com';
	$curTime= date("m-d-Y");	
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	
	
	$CopyAdmin = "dbeaulieu@direct-lens.com";
	$to_address = str_split($CopyAdmin,100);
	$from_address='donotreply@entrepotdelalunette.com';
	$curTime= date("m-d-Y");	
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	
	//Send a copy to the stock_tr
	/*$CopyAdmin = "stock_tr@direct-lens.com";
	$to_address = str_split($CopyAdmin,100);
	$from_address='donotreply@entrepotdelalunette.com';
	$curTime= date("m-d-Y");	
	$response=office365_mail($to_address, $from_address, $subject, null, $message);*/
	
	//Send a copy to the stock_sct
	$CopyAdmin = "stock_sct@direct-lens.com";
	$to_address = str_split($CopyAdmin,100);
	$from_address='donotreply@entrepotdelalunette.com';
	$curTime= date("m-d-Y");	
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
}

?>