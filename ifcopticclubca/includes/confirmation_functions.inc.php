<?php
require_once(__DIR__.'/../../constants/aws.constant.php');
require_once(__DIR__.'/../../constants/url.constant.php');

function createBarcode($order_num){
	return constant('DIRECT_LENS_URL')."/barcodes/".$order_num.".gif";
}

function sendStockConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id){//STOCK BULK AND TRAY CONFIRMATION
	
	include "../sec_connectEDLL.inc.php";


	$barcode=createBarcode($orderNum);
	
	$query="select * from accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$result=mysqli_query($con,$query)	or die  ('I cannot select items because: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$usercount=mysqli_num_rows($result);
	
	if ($listItem['product_line']=="directlens")
		{$dl_logo_file="http://www.direct-lens.com/direct-lens/logos/direct-lens_logo.gif";
		$pl_text="Direct-Lens";}
	elseif ($listItem['product_line']=="mybbgclub")
		{$dl_logo_file="http://www.direct-lens.com/direct-lens/logos/bbgclub_logo.gif";
		$pl_text="MyBBGClub";}
	elseif ($listItem['product_line']=="aitlensclub")
		{$dl_logo_file="http://www.direct-lens.com/direct-lens/logos/aitclub_logo.gif";
		$pl_text="AIT Lens Club";}
	elseif ($listItem['product_line']=="ifcclub")
		{$dl_logo_file="http://www.direct-lens.com/direct-lens/logos/ifc_club_logo.gif";
		$pl_text="IFC Optic CLUB";}
	elseif ($listItem['product_line']=="ifcclubca")
		{$dl_logo_file="http://www.direct-lens.com/ifcopticclubca/design_images/ifc-ca-large.jpg";
		$pl_text="IFC Optic CLUB.ca";}
	else
		{$dl_logo_file="http://www.direct-lens.com/direct-lens/logos/lensnet_logo.gif";
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
	$message.="<link href=\"http://www.direct-lens.com/dl.css\" rel=\"stylesheet\" type=\"text/css\" />";
	
$message.='<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img src="logos/'.$logo_file.'"/><td align="center"><img src="'.$dl_logo_file.'" width="200" height="60" /></td></td><td align="right"><img src="'.$barcode.'" width="190" height="50" /></td></tr></table>';//ABSOLUTE PATH TO THE BARCODE IMAGE
	
		$message.='<table width="650" border="0" align="center" cellpadding="0" cellspacing="0"><tr>';
	$message.='<td><div class="header2">Your '.$pl_text.' Order #:'.$orderNum.'</div></td></tr></table>';
	
	$query="select po_num from orders WHERE order_num='$orderNum'"; //GET ORDER PO NUMBER
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
		$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr><td align="left" class="formCellNosides">PO Order #: ';
		$message.=$listItem[po_num];
		$message.='</td></tr></table>';
	
	$query="select * from accounts WHERE user_id='$user_id'"; //ACCOUNT INFO SECTION
		$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$usercount=mysqli_num_rows($result);
	
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#17A2D2" class="tableHead">ACCOUNT INFORMATION </td></tr>';
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
	
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#17A2D2" class="tableHead">BILLING ADDRESS </td></tr><tr ><td width="130" align="right" class="formCellNosides">Address 1:</td><td width="520" class="formCellNosides"><strong>';
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
			
			 $message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox"><tr ><td bgcolor="#17A2D2" class="tableHead">STOCK ITEMS - BY TRAY</td></tr></table>';
			
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
                <td align="right" valign="top" class="formCellNosidesRA"><b>';
				$message.=$listItem[order_product_price];
				$message.='$</b><br>Subtotal: ';
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
                <td colspan="7" bgcolor="#D5EEF7" class="tableSubHead">Tray Reference - ';
				$message.=$listItem[tray_num];
				$message.='</td>
            
                <td bgcolor="#D5EEF7"  class="formCellNosidesRA" >&nbsp;</td>
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
                <td align="right" valign="top" class="formCellNosidesRA"><b>';
				$message.=$listItem[order_product_price];
				$message.='$</b></td>
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
			
			 $message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox"><tr ><td bgcolor="#17A2D2" class="tableHead">STOCK ITEMS - BULK</td></tr></table>';
			
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
					
					
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="7" bgcolor="#D5EEF7" class="tableSubHead">Article -- ';
	$message.=$listItem[order_product_name];
	 $message.='</td></tr><tr >';
	 $message.='<td width="77" bgcolor="#E5E5E5" class="formCellNosides"><strong>Material</strong></td>';
	 $message.='<td width="53" bgcolor="#E5E5E5" class="formCellNosides"><strong>Index</strong></td>';
	 $message.='<td width="74" align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Traitement</strong></td>';
	 $message.='<td width="69" align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Sph&egrave;re</strong></td>';
	 $message.='<td width="129" align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Cylindre</strong></td>';
	$message.='<td align="center" bgcolor="#E5E5E5" class="formCellNosidesCenter"><strong>Quantit&eacute;</strong></td>';
	$message.='<td align="center" bgcolor="#E5E5E5" class="formCellNosidesRA"><strong>Prix</strong></td>';
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
	$message.='</td><td align="right" valign="top" class="formCellNosidesRA"><b>';
	$message.=$listItem[order_product_price];
	$message.='$</b><br>Subtotal:';
	$message.=$itemSubtotal;
	$message.='$</td></tr></table>';
					} 
			}// END OF STOCK BY BULK SECTION

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
$message.=$order_shipping_cost;
$message.='</td></tr>';
$message.='<tr ><td width="524" align="left" class="Subheader">Order Total with Your Account Discount</td>';
$message.='<td width="100" align="right" valign="middle" class="total"><b>';
				
	$totalPriceDsc=money_format('%.2n',$totalPriceDsc+$order_shipping_cost);
	$message.=$totalPriceDsc."$ ".$currency; 
			
	$message.='</b></td></tr><tr><td class="Subheader">Effective on April 1st 2013:
<b>1,5% charges on invoice not paid within 30 days following the receipt of statement
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

function sendPrescriptionConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$sendPrices){//PRESCRIPTION CONFIRMATION

	include "../sec_connectEDLL.inc.php";
	
	$barcode=createBarcode($orderNum);
	
	$query="select * from accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$result=mysqli_query($con,$query)	or die  ('I cannot select items because: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$usercount=mysqli_num_rows($result);
	
	if ($listItem['product_line']=="directlens")
		{$dl_logo_file="http://www.direct-lens.com/direct-lens/logos/direct-lens_logo.gif";
		$pl_text="Direct-Lens";}
	elseif ($listItem['product_line']=="mybbgclub")
		{$dl_logo_file="http://www.direct-lens.com/direct-lens/logos/bbgclub_logo.gif";
		$pl_text="MyBBGClub";}
	elseif ($listItem['product_line']=="aitlensclub")
		{$dl_logo_file="http://www.direct-lens.com/direct-lens/logos/aitclub_logo.gif";
		$pl_text="AIT Lens Club";}
	elseif ($listItem['product_line']=="ifcclub")
		{$dl_logo_file="http://www.direct-lens.com/direct-lens/logos/ifc_club_logo.gif";
		$pl_text="IFC Optic CLUB";}
	elseif ($listItem['product_line']=="ifcclubca")
		{$dl_logo_file="http://www.direct-lens.com/ifcopticclubca/design_images/ifc-ca-large.jpg";
		$pl_text="IFC Optic CLUB.ca";}
	else
		{$dl_logo_file="http://www.direct-lens.com/direct-lens/logos/lensnet_logo.gif";
		$pl_text="LensNet Club";}
		
	$headers = "From:".$fromAddress."\r\n";
	$mime_boundary=md5(time()); 
	$headers .= 'MIME-Version: 1.0'."\r\n"; 
	$headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\""."\r\n"; 
					
	$headers .= "--".$mime_boundary."\r\n"; 
	$headers .=	"Content-Type: 	text/html; charset=\"iso-8859-1\"\r\n";
	$headers .= "Content-Transfer-Encoding: 8bit"."\r\n"; 
						
	$subject = $pl_text." Confirmation de la commande :$orderNum";
	$message='<html xmlns="http://www.w3.org/1999/xhtml"><head><base href="'.constant('DIRECT_LENS_URL').'/processOrder.php">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ORDER</title>
</head>';
	$message.="<link href=\"http://www.direct-lens.com/dl.css\" rel=\"stylesheet\" type=\"text/css\" />";
$message.='<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img src="logos/'.$logo_file.'"/><td align="center"><img src="'.$dl_logo_file.'" width="200" height="60" /></td></td><td align="right"><img src="'.$barcode.'" width="190" height="50" /></td></tr></table>';//ABSOLUTE PATH TO THE BARCODE IMAGE
	
		$message.='<table width="650" border="0" align="center" cellpadding="0" cellspacing="0"><tr>';
	$message.='<td><div class="header2">Vos commandes Ifc Club:'.$orderNum.'</div></td></tr></table>';
	
	$query="select po_num from orders WHERE order_num='$orderNum'"; //GET ORDER PO NUMBER
		$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
		$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr><td align="left" class="formCellNosides">N de commande: ';
		$message.=$listItem[po_num];
		$message.='</td></tr></table>';
	
	$query="select * from accounts WHERE user_id='$user_id'"; //ACCOUNT INFO SECTION
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$usercount=mysqli_num_rows($result);
	
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#17A2D2" class="tableHead">Information Compte Client</td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Nom et Pr&eacute;nom : </td>';
    $message.='<td width="520" class="formCellNosides"><strong>';
	$message.=$listItem[title].' '.$listItem[first_name].' '.$listItem[last_name];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">No de client : </td>';
    $message.='<td width="520" class="formCellNosides"><strong>';
	$message.=$listItem[account_num];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Nom du magasin : </td><td width="520" class="formCellNosides"><strong>';
	$message.=$listItem[company];
	$message.='</strong> </td></tr>';
	$message.='</table>';
	
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#17A2D2" class="tableHead">Adresse de facturation </td></tr><tr ><td width="130" align="right" class="formCellNosides">Adresse 1 : </td><td width="520" class="formCellNosides"><strong>';
	$message.=$listItem[bill_address1];
	$message.='</strong></td></tr><tr ><td width="130" align="right" class="formCellNosides">Adresse 2 : </td><td width="520" class="formCellNosides"><strong>';
$message.=$listItem[bill_address2];
$message.='</strong></td></tr><tr ><td align="right" class="formCellNosides">Ville : </td><td width="520" class="formCellNosides"><strong>';
$message.=$listItem[bill_city];
$message.='</strong></td></tr><tr ><td align="right" class="formCellNosides">Etat/Province : </td><td width="520" class="formCellNosides"><strong>';
$message.=$listItem[bill_state];
$message.='</strong> </td></tr><tr ><td align="right" class="formCellNosides">Code Postal :  </td><td class="formCellNosides"><strong>';
$message.=$listItem[bill_zip];
$message.='</strong></td></tr><tr><td align="right" class="formCellNosides">Pays : </td><td class="formCellNosides"><strong>';
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
                <td bgcolor="#17A2D2" class="tableHead">Article</td>
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
					//$e_order_string_edging.="<b>Grandeur: A:</b>".$listItem[frame_a]." ";
					//$e_order_string_edging.="<b>B: </b>".$listItem[frame_b]." ";
					//$e_order_string_edging.="<b>ED: </b>".$listItem[frame_ed]." ";
					//$e_order_string_edging.="<b>DBL: </b>".$listItem[frame_dbl]." ";	
				
					if ($e_usercount !=0){
						while ($e_listItem=mysqli_fetch_array($e_result,MYSQLI_ASSOC)){
						$e_total_price=$e_total_price+$e_listItem[price]+$e_listItem[high_index_addition];
						if ($e_listItem[category]=="Edging"){
								$e_products_string.="<br />Taillage: ".$e_listItem[price];
								$e_products_string_na.="<br />Taillage: n/a";
								
								$e_order_string_edging="<b>Type: </b>".$e_listItem[frame_type]." ";
								$e_order_string_edging.="<b>Type de verre: </b>";
								if ($e_listItem[job_type] == 'Edge and Mount'){
								$e_order_string_edging .=" Taill&eacute;-Mont&eacute; ";
								}else{
								$e_order_string_edging  .=$e_listItem[job_type]." ";
								}
							
								//$e_order_string_edging.="<b>Type de commande: </b> ".$e_listItem[order_type]."<br>";
								
								//$e_order_string_edging.="<br><b>Grandeur : A:</b>".$e_listItem[ep_frame_a]." ";
								//$e_order_string_edging.="<b>B : </b>".$e_listItem[ep_frame_b]." ";
								//$e_order_string_edging.="<b>ED : </b>".$e_listItem[ep_frame_ed]." ";
								//$e_order_string_edging.="<b>DBL : </b>".$e_listItem[ep_frame_dbl]." ";
								//$e_order_string_edging.="<b>Branche : </b>".$e_listItem[temple]."<br>";
								
								$e_order_string_edging.="<b> Collection : </b>".$e_listItem[supplier]." ";
								$e_order_string_edging.="<b>Forme de la monture : </b>".$e_listItem[model]." ";
								$e_order_string_edging.="<b>Couleur : </b>";
								if ($e_listItem[color] == 'Brown'){
								$e_order_string_edging.='Brun'."<br>";
								}
								if ($e_listItem[color] == 'Grey'){
								$e_order_string_edging.='Gris'."<br>";
								}
								
								if ($e_listItem[color] == 'G-15'){
								$e_order_string_edging.='G-15'."<br>";
								}

								
								}

							if ($e_listItem[category]=="Engraving"){
								
								$e_products_string.="<br />Marquage : ".$e_listItem[price];
								$e_products_string_na.="<br />Marquage : n/a";
								
								$e_order_string_engraving="<b>Engraving: </b>".$e_listItem[engraving]." ";}
							if ($e_listItem[category]=="Tint"){
								
								$e_products_string.="<br />Teinte : ".$e_listItem[price];
								$e_products_string_na.="<br />Teinte : n/a";
								
								$e_order_string_tint="<b>Teinte : </b>";
								
								if ($e_listItem[tint] == "Solid"){
								$e_order_string_tint.="<b>Unie : </b>";
								}
								
								if ($e_listItem[tint] == "Solid 60"){
								$e_order_string_tint.="<b>CAT 2 (60%): </b>";
								}
								
								if ($e_listItem[tint] == "Solid 80"){
								$e_order_string_tint.="<b>CAT 3 (82%): </b>";
								}
								
								
								if ($e_listItem[tint_color] =='Grey'){
								$e_listItem[tint_color]='Gris';
								}
								
								if ($e_listItem[tint_color] =='Brown'){
								$e_listItem[tint_color]='Brun';
								}
								
								if ($e_listItem[tint]=="Solid")
									{ $e_order_string_tint.= $e_listItem[from_perc]."% <b>Couleur : </b> ".$e_listItem[tint_color];}
								else
									{$e_order_string_tint.= $e_listItem[from_perc]."% A ".$e_listItem[to_perc] ."% <b>Couleur :</b> ".$e_listItem[tint_color];}
								}//END IF TINT
							if ($e_listItem[category]=="Prisme"){
								$e_products_string.="<br />Prisme : ".$e_listItem[price];
								$e_products_string_na.="<br />Prisme : n/a";
								}
							if ($e_listItem[category]=="Frame"){
								$e_products_string.="<br />Monture ".$e_listItem[price];
								$e_products_string.="<br />Index eleve : ".$e_listItem[high_index_addition];
								
								$e_products_string_na.="<br />Monture : n/a";
								$e_products_string_na.="<br />Index eleve : n/a";
								
								$e_order_string_frame="<b>Type: </b>".$e_listItem[frame_type]." ";
								$e_order_string_frame.="<b>Type de verre : </b>";
								if ($e_listItem[job_type] == 'Edge and Mount'){
								$e_order_string_frame .= 'Taill&eacute;-Mont&eacute;';
								}; 
								//$e_order_string_frame.="<b>Type de commande : </b>Frame ".$e_listItem[order_type]."<br>";
							//	$e_order_string_frame.="<br><b>Grandeur : A :</b>".$e_listItem[ep_frame_a]." ";
							//	$e_order_string_frame.="<b>B : </b>".$e_listItem[ep_frame_b]." ";
							//	$e_order_string_frame.="<b>ED : </b>".$e_listItem[ep_frame_ed]." ";
							//	$e_order_string_frame.="<b>DBL : </b>".$e_listItem[ep_frame_dbl]." ";
							//	$e_order_string_frame.="<b>Branche : </b>".$e_listItem[temple]."<br>";
								$e_order_string_frame.="<b> Collection : </b>".$e_listItem[supplier]." ";
								$e_order_string_frame.="<b>Forme de la monture : </b>".$e_listItem[model]." ";
								$e_order_string_frame.="<b>Num&eacute;ro de r&eacute;f&eacute;rence : </b>".$e_listItem[temple_model_num]." ";
								$e_order_string_frame.="<b>Couleur : </b>".$e_listItem[color]."<br>";
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
                <td colspan="6" bgcolor="#D5EEF7" class="tableSubHead">';
				
				 $queryProduit = "SELECT frame_type from orders WHERE order_num =  '" . $orderNum . "'";
				 $resultProduit=mysqli_query($con,$queryProduit)	or die ("Could not select items");
				 $DataProduit=mysqli_fetch_array($resultProduit,MYSQLI_ASSOC);
				 
				 if (strlen($DataProduit[frame_type])< 3){
				 $Commande = "Produit Verres ";
				 }else{
				 $Commande = "Produit Pack Montage ";
				 }

				$message .= $Commande . ' ' ;
				$message.=$listItem[order_product_name];
				$message.='</td>
                <td bgcolor="#D5EEF7" class="tableSubHead">&nbsp;</td>
                <td bgcolor="#D5EEF7"  class="formCellNosidesRA" >&nbsp;</td>
  </tr>
              <tr >
                <td bgcolor="#FFFFFF" class="formCellNosidesRA"><strong>Traitement : </strong></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides">'.$listItem[order_product_coating].'</td>
                <td align="center" bgcolor="#FFFFFF"  class="formCellNosidesRA"><strong>Photochromique : </strong></td>
				<td align="center" bgcolor="#FFFFFF" class="formCellNosides">';
				
				if ( $listItem[order_product_photo]=='None'){
				 $message.= 'Non';
				}else{
				$message.=  $listItem[order_product_photo];
				}
              
			   
                 $message.='</td><td align="center" bgcolor="#FFFFFF" class="formCellNosidesRA"><strong>Polaris&eacute;:</strong></td>
                <td colspan="3" align="center" bgcolor="#FFFFFF" class="formCellNosides">';
				if ($listItem[order_product_polar] == 'None'){
				$message.='Non';
				}else{
				$message.=$listItem[order_product_polar];
				}
				
				$message.='</td>
              </tr>
              <tr >
                <td bgcolor="#FFFFFF" class="formCellNosidesRA"><strong>Nom de Famille : </strong></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides">';
				$message.=$listItem[order_patient_first].'&nbsp;'.$listItem[order_patient_last].'</td>';
				
				
				$message.='<td bgcolor="#FFFFFF" class="formCellNosidesRA"><b>N de p&eacute;niche : <b></td>';
				$message.='<td align="center" bgcolor="#FFFFFF" class="formCellNosides">'.$listItem[patient_ref_num].'</td>';
				
				$message.='<td align="center" bgcolor="#FFFFFF" class="formCellNosidesRA"><strong>Vendeur : </strong> </td>
                <td colspan="3" align="center" bgcolor="#FFFFFF" class="formCellNosides">';
				$message.=$listItem[salesperson_id];
				$message.='</td>
              </tr>
              <tr >
                <td bgcolor="#E5E5E5" class="formCellNosides">&nbsp;</td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Sph&egrave;re</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Cylindre</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Axes</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Addition</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Prisme</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosidesCenter"><strong>Quantit&eacute;</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosidesRA"><strong>Prix</strong></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides"></td>
                <td align="center" class="formCellNosides">';
				
				if ($listItem[re_sphere] <> '?')$message .= $listItem[re_sphere];

				$message.='</td>
                <td align="center" class="formCellNosides">';
				if ($listItem[re_cyl] <> '?')$message .= $listItem[re_cyl];
				$message.='</td>
                <td align="center" class="formCellNosides">';
				if ($listItem[re_axis] <> '?')$message .= $listItem[re_axis];
				$message.='</td>
                <td align="center" class="formCellNosides">';
				if ($listItem[re_add] <> '?')$message .= $listItem[re_add]; 
				$message.='</td>
                <td align="center" class="formCellNosides">';
				
				if ($listItem[re_pr_ax] <> '?')$message .= $listItem[re_pr_ax]; 
				$message .='&nbsp;';
				
				if ($listItem[re_pr_io] <> '?')$message .= $listItem[re_pr_io]; 
				$message .='&nbsp;&nbsp;';
				
				if ($listItem[re_pr_ax2] <> '?')$message .= $listItem[re_pr_ax2]; 
				$message .='&nbsp;';
				
				if ($listItem[re_pr_ud] <> '?')$message .= $listItem[re_pr_ud]; 
								
				$message.='</td>
                <td rowspan="7" align="center" valign="top" class="formCellNosidesCenter">';
				$message.=$listItem[order_quantity];
				$message.='</td>
                <td rowspan="7" align="right" valign="top" class="formCellNosidesRA"><b>';
				
				if ($sendPrices=="true"){
					$message.=$listItem[order_product_price]."$";}
				else{
					$message.="n/a";}
				$message.='</b>';
				
				if ($sendPrices=="true"){
				
					if ($over_range!=0){
						$message.= '<br> Indice &eacute;lev&eacute;: '.$over_range;}
						if ($e_total_price!=0){
						$message.= "<br>".$e_products_string;}
					if ($coupon_dsc!=0){
						$message.= '<br> Coupon : -'. $coupon_dsc;}
					//$message.='<br>Sous-total: '.$itemSubtotal;
					}
				else{
					if ($over_range!=0){
						$message.= '<br> Indice &eacute;lev&eacute;: n/a';}
					if ($e_total_price!=0){
						$message.= "<br>".$e_products_string_na;}
					if ($coupon_dsc!=0){
						$message.= '<br> Coupon : n/a';}
					//$message.='<br>Sous-total:  n/a';
				}//END Send Prices Conditional
				
				if ($e_order_string_frame!=""){
					$e_order_string_edging=$e_order_string_frame;}
					
				$message.='</td>
              </tr>
              <tr >
                <td colspan="6" align="left" class="formCellNosides">';
				
				if ($listItem[re_pd] <> '?')$message .= $listItem[re_pd];
				 
			   if ($listItem[eye] <> 'L.E.')
				$message .= '<strong>PD : </strong> ';
				
				if (($listItem[re_pd_near] <> '?') && ($listItem[eye] <> 'L.E.'))$message .= $listItem[re_pd_near]; 
				 
				 if ($listItem[eye] <> 'L.E.')
				 $message .='&nbsp;&nbsp;&nbsp;<strong>Hauteur OD : </strong>';
				 
				 if ($listItem[re_height] <> '?')$message .= $listItem[re_height];  
				  $message .=' </td>
                </tr>
              <tr >
                <td align="right" class="formCellNosides">
                  </td>
                <td align="center" class="formCellNosides">';
				
			if ($listItem[le_sphere] <> '?')$message .= $listItem[le_sphere];
			
				$message .='</td>
                <td align="center" class="formCellNosides">';
				
			if ($listItem[le_cyl] <> '?')$message .= $listItem[le_cyl];

				$message .=' </td>
                <td align="center" class="formCellNosides">';
				
			if ($listItem[le_axis] <> '?')$message .= $listItem[le_axis]; 	
				
				$message .=' </td>
                <td align="center" class="formCellNosides">';
				
			if ($listItem[le_add] <> '?')$message .= $listItem[le_add]; 
				
				$message .=' </td>
                <td align="center" class="formCellNosides">';
				
				if ($listItem[le_pr_ax] <> '?')$message .= $listItem[le_pr_ax];
				
				$message .='&nbsp;';
				
				if ($listItem[le_pr_io] <> '?')$message .= $listItem[le_pr_io]; 
				
				$message .='&nbsp;&nbsp;';
				
				if ($listItem[le_pr_ax2] <> '?')$message .= $listItem[le_pr_ax2]; 
				
				$message .='&nbsp;';
				
				if ($listItem[le_pr_ud] <> '?')$message .= $listItem[le_pr_ud];  	
				
				$message .='</td>
                </tr>
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides">';
				
				if ($listItem[eye] <> 'R.E.'){
				$message .= '<strong> PG : </strong>';
				}
				
				if (($listItem[le_pd] <> '?') && ($listItem[eye] <> 'R.E.'))$message .= $listItem[le_pd];  	
				$message .=' ';
				
				if (($listItem[le_pd_near] <> '?') && ($listItem[eye] <> 'R.E.'))$message .= $listItem[le_pd_near];  			
				
				
				if ($listItem[eye] <> 'R.E.'){
				$message .=' &nbsp;&nbsp;&nbsp;<strong>Hauteur OG:</strong> ';}
				
				if (($listItem[le_height] <> '?') && ($listItem[eye] <> 'R.E.'))$message .= $listItem[le_height];  		
				$message .=' </td>
                </tr>
				
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>Monture:
                    &nbsp; </strong>'.$e_order_string_edging.' </td>
              </tr>
			  <tr>
			    <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>Autre : </strong> 
				'.$e_order_string_engraving.$e_order_string_tint.'
				</td>
				</tr>
			    <tr >
                <td colspan="8" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>Instructions sp&eacute;ciales : &nbsp;</strong>'.$listItem[special_instructions].'</td>
              </tr>
			     <tr >
                <td colspan="8" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>Note interne : &nbsp;</strong>'.$listItem[internal_note].'</td>
              </tr>
            </table>';
							
					} //END WHILE
			}//END IF USERCOUNT IF CONDITIONAL
			
			//END PRESCRIPTION SECTIOn

//BEGINNING OF TOTALS SECTION



$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">';
$message.='<td align="right" valign="middle" class="formCellNosidesRA">';

$message.='<tr ><td width="524" align="left" class="Subheader">TOTAL COMMANDE</td>';
$message.='<td width="100" align="right" valign="middle" class="total"><b>';
				
$totalPriceDsc=money_format('%.2n',$totalPriceDsc+$order_shipping_cost);
	
if ($sendPrices=="true"){
	$message.=$totalPriceDsc."$ ".$currency; }
else{
	$message.='n/a'; }
	$message.='</b></td></tr><tr><td class="Subheader">En vigueur 1/04/2013:
<b>Frais de 1,5% sur factures impayées 30 jours après la réception du relevé mensuel</b></td></tr></table>';
	$message.="</body></html>";
	//$inviteSent = mail("$send_to_address", "$subject", "$message", "$headers");
	
//New SES email code
$to_address = str_split($send_to_address,100);
$from_address='donotreply@entrepotdelalunette.com';
$curTime= date("m-d-Y");	
$response=office365_mail($to_address, $from_address, $subject, null, $message);
	
	
//echo "<br><br><br>TO ADDRESS=".$send_to_address." HEADERS=". $headers." CODE=".$inviteSent." SUBJECT=".$subject;
}




//------------English version of the confirmation email

function sendPrescriptionConfirmationEn($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$sendPrices){//PRESCRIPTION CONFIRMATION

	include "../sec_connectEDLL.inc.php";

	$barcode=createBarcode($orderNum);
	
	$query="SELECT * FROM accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$result=mysqli_query($con,$query)	or die  ('I cannot select items because: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$usercount=mysqli_num_rows($result);
	
	if ($listItem['product_line']=="directlens")
		{$dl_logo_file="http://www.direct-lens.com/direct-lens/logos/direct-lens_logo.gif";
		$pl_text="Direct-Lens";}
	elseif ($listItem['product_line']=="mybbgclub")
		{$dl_logo_file="http://www.direct-lens.com/direct-lens/logos/bbgclub_logo.gif";
		$pl_text="MyBBGClub";}
	elseif ($listItem['product_line']=="aitlensclub")
		{$dl_logo_file="http://www.direct-lens.com/direct-lens/logos/aitclub_logo.gif";
		$pl_text="AIT Lens Club";}
	elseif ($listItem['product_line']=="ifcclub")
		{$dl_logo_file="http://www.direct-lens.com/direct-lens/logos/ifc_club_logo.gif";
		$pl_text="IFC Optic CLUB";}
	elseif ($listItem['product_line']=="ifcclubca")
		{$dl_logo_file="http://www.direct-lens.com/ifcopticclubca/design_images/ifc-ca-large.jpg";
		$pl_text="IFC Optic CLUB.ca";}
	else
		{$dl_logo_file="http://www.direct-lens.com/direct-lens/logos/lensnet_logo.gif";
		$pl_text="LensNet Club";}
		
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
	$message.="<link href=\"http://www.direct-lens.com/dl.css\" rel=\"stylesheet\" type=\"text/css\" />";
$message.='<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img src="logos/'.$logo_file.'"/><td align="center"><img src="'.$dl_logo_file.'" width="200" height="60" /></td></td><td align="right"><img src="'.$barcode.'" width="190" height="50" /></td></tr></table>';//ABSOLUTE PATH TO THE BARCODE IMAGE
	
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
			
	$message.='</b></td></tr><tr><td class="Subheader">Effective on April 1st 2013:
<b>1,5% charges on invoice not paid within 30 days following the receipt of statement
</b></td></tr></table>';
			
	$message.="</body></html>";
	
//New SES email code
$to_address = str_split($send_to_address,100);
$from_address='donotreply@entrepotdelalunette.com';
$curTime= date("m-d-Y");	
$response=office365_mail($to_address, $from_address, $subject, null, $message);
}





function sendFrameStockConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id){//STOCK BULK AND TRAY CONFIRMATION

	include "../sec_connectEDLL.inc.php";
	
	$sendPrices = true;

	$bg_query="SELECT bg_name FROM buying_groups WHERE primary_key='$userData[buying_group]'";//GET BUYING GROUP NAME
	$bg_result=mysqli_query($con,$bg_query)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$bgData=mysqli_fetch_array($bg_result,MYSQLI_ASSOC);

	$barcode="barcodes/".$orderNum.".gif";
	
	$query="select * from accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
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
	else
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/direct-lens/logos/lensnet_logo.gif";
		$pl_text="LensNet Club";}

	$headers = "From:".$fromAddress."\r\n";
	$mime_boundary=md5(time()); 
	$headers.='MIME-Version: 1.0'."\r\n"; 
	$headers.="Content-Type: multipart/related; boundary=\"".$mime_boundary."\""."\r\n"; 
					
	$headers.="--".$mime_boundary."\r\n"; 
	$headers.="Content-Type: text/html; charset=\"iso-8859-1\"\r\n";
	$headers.="Content-Transfer-Encoding: 8bit"."\r\n"; 
	
	$subject = $pl_text." Frame Stock Order Confirmation - Order Number:$orderNum";
	//$subject="fax -html 5026356154";
	$message='<html xmlns="http://www.w3.org/1999/xhtml"><head><base href="'.constant('DIRECT_LENS_URL').'/processOrder.php">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ORDER</title>';
	if ($printit) $message .= "<script type='text/javascript'>window.onload = function() {window.print();}</script>";
$message .= '</head>';
	$message.='<link href="http://www.direct-lens.com/dl.css" rel="stylesheet" type="text/css" />';

	$message.='<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img width="250" src="logos/'.$logo_file.'"/><td align="center"><img src="'.$dl_logo_file.'" width="200" height="60" /></td></td><td align="right"><img src="'.$barcode.'" width="190" height="50" /><p>GST:&nbsp;808437370 RT 0001&nbsp;<br>&nbsp;&nbsp;QST:&nbsp;1227025278 TQ 0001</p></td></tr></table>';//ABSOLUTE PATH TO THE BARCODE IMAGE
	
	$message.='<table width="650" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td align="left">';
	$message.='<td><div class="header2">Your'.$dl_text.' Order #:'.$orderNum.'</div></td></tr></table>';
	
	$query="select po_num from orders WHERE order_num='$orderNum'"; //GET ORDER PO NUMBER
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr><td align="left" class="formCellNosides">PO Order #: ';
	$message.=$listItem[po_num];
	$message.='</td></tr></table>';
	
	$query="select * from accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$result=mysqli_query($con,$query)		or die  ('I cannot select items because: ' . mysqli_error($con));
		
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
			$result2=mysqli_query($con,$query2)					or die  ('I cannot select items because: ' . mysqli_error($con));
			$stockusercount=mysqli_num_rows($result2);
			if ($stockusercount != 0){
			
			 $message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox"><tr><td bgcolor="#000099" class="tableHead">FRAMES STOCK ITEMS</td></tr></table>';
			
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
         <tr>
                <td colspan="7" bgcolor="#D7E1FF" class="tableSubHead">Reference - ';
				$message.=$listItem[tray_num];
				
				$SousTotal = $listItem[order_quantity] * $listItem[order_product_price];
				$SousTotal=money_format('%.2n',$SousTotal);
				
				
				$message.='</td>
            
                <td bgcolor="#D7E1FF"  class="formCellNosidesRA" >&nbsp;</td>
  </tr>
              <tr>
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
				
				$priceBeforeTaxe = $listItem[order_product_price] ;/// 1.05;
				$priceBeforeTaxe=money_format('%.2n',$priceBeforeTaxe);
				
				$message.=$priceBeforeTaxe;
				
				$SousTotalAvantTaxes = $SousTotal;// / 1.05;
				$SousTotalAvantTaxes=money_format('%.2n',$SousTotalAvantTaxes);
				
				
				$message.='</td>
                <td align="center" class="formCellNosides">&nbsp;</td>
                <td align="right" valign="top" class="formCellNosidesRA"><b>$' . $SousTotalAvantTaxes;
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


$queryTotalQty   = "SELECT SUM(order_quantity) as totalqty FROM orders WHERE order_num =  $orderNum";
$resultTotalQty  = mysqli_query($con,$queryTotalQty)		or die  ('I cannot select items because 8: ' . mysqli_error($con));
$DataProductline = mysqli_fetch_array($resultTotalQty,MYSQLI_ASSOC);
//Ajout pour  afficher la quantité totale de montures
$message.='<tr><td colspan="5" align="right" bgcolor="#FFFFFF" class="formCellNosides"><b>Total Qty:</b>&nbsp;'.$DataProductline[totalqty].' frame';

if ($DataProductline[totalqty] > 1)
$message.='s';
$message.='</td></tr>';

$message.='<tr><td align="left" class="formCellNosides"><b>Total</b>:';
$message.='</td>';
$message.='<td align="right" valign="middle" class="formCellNosidesRA">';
$message.='<b>$'.$SousTotalAvantTaxes.'</b>';
$message.='</td></tr>';

$message.='<tr><td align="left" class="formCellNosides">Shipping:';
$message.=$order_shipping_method;
$message.='</td>';
$message.='<td align="right" valign="middle" class="formCellNosidesRA">$';
$message.=$order_shipping_cost;
$message.='</td></tr>';
			  
			  
$message.='<tr><td width="524" align="left" class="Subheader">Order Total with All Discounts</td>';
$message.='<td width="100" align="right" valign="middle" class="total"><b>$';
$extra_product_price=0;	
			
$totalPriceDsc=money_format('%.2n',$totalPriceDsc+$order_shipping_cost-$totalDiscount+$extra_product_price);

if ($sendPrices=="true"){
$message.=$TotalaPayer." ".$currency; 
}else{
$message.='n/a';
}

$message.='</b></td></tr><tr><td class="Subheader">Effective on April 1st 2013:
<b>1.5% charge on invoice not paid within 30 days following the date of statement';
$message.='</b></td></tr></table>';
$message.="</body></html>";
	
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

?>