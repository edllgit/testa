<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
require_once(__DIR__.'/../constants/url.constant.php');

//ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
//require_once('../class.ses.php');

//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

function getUserData($order_num){
	include "../sec_connectEDLL.inc.php";
	$query="SELECT user_id FROM orders WHERE order_num=$order_num";
	$result=mysqli_query($con,$query) or die  ('I cannot select items because g4534: ' . mysqli_error($con));
		
	$orderItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	$user_id=$orderItem[user_id];

	$query="SELECT * FROM accounts WHERE user_id='$user_id'";
	$result=mysqli_query($con,$query) or die  ('I cannot select items because cy564: ' . mysqli_error($con));
		
	$userData=mysqli_fetch_array($result,MYSQLI_ASSOC);

	return $userData;
}

function sendStockConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$userData,$printit){//STOCK BULK AND TRAY CONFIRMATION

//$send_to_address="directl-config@interpage.net";

if ($printit) {
$sendPrices = true;
}
else{
$sendPrices = false;
}


	$bg_query="SELECT bg_name FROM buying_groups WHERE primary_key='$userData[buying_group]'";//GET BUYING GROUP NAME
	$bg_result=mysqli_query($con,$bg_query)or die  ('I cannot select items because g56424: ' . mysqli_error($con));
	$bgData=mysqli_fetch_array($bg_result,MYSQLI_ASSOC);

	
// include Barcode39 class
include "../barcodes/Barcode39.php";
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
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
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

	$message.='<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img width="250" src="logos/'.$logo_file.'"/><td align="center"><img src="'.$dl_logo_file.'" width="200" height="60" /></td></td><td align="right"><img src="'.$barcode.'" width="190" height="50" />';//ABSOLUTE PATH TO THE BARCODE IMAGE
	
	if (($pl_text<>"AIT Lens Club") && ($listItem[main_lab] <> 50)){
	$message.='<p>GST:&nbsp;808437370 RT 0001&nbsp;<br>&nbsp;&nbsp;QST:&nbsp;1227025278 TQ 0001</p>';
	}
	
	$message.='</td></tr></table>';
	
		$message.='<table width="650" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td align="left">';
	$message.='<td><div class="header2">Your'.$dl_text.' Order #:'.$orderNum.'</div></td></tr></table>';
	
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
$query2="SELECT * from orders WHERE user_id='$user_id' and order_num='$orderNum' and order_product_type='stock_tray' ORDER by order_item_date,primary_key";//SELECT ALL OPEN STOCK ORDERS

			$result2=mysqli_query($con,$query2) or die  ('I cannot select items because: ' . mysqli_error($con));
			$stockusercount=mysqli_num_rows($result2);
			if ($stockusercount != 0){
			
			 $message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox"><tr ><td bgcolor="#000099" class="tableHead">STOCK ITEMS - BY TRAY</td></tr></table>';
			
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
				$message.='</b><br>Subtotal:';
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
						
						//END RE SECTION
											}//END IF
					} //END WHILE
			}// END OF STOCK BY TRAY SECTION


//BEGIN STOCK BY BULK SECTION
$query2="SELECT * from orders WHERE user_id='$user_id' and order_num='$orderNum' and order_product_type='stock' ORDER by order_item_date";//SELECT ALL OPEN STOCK ORDERS

			$result2=mysqli_query($con,$query2)		or die  ('I cannot select items because: ' . mysqli_error($con));
			$stockusercount=mysqli_num_rows($result2);
			if ($stockusercount != 0){
			
			 $message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox"><tr ><td bgcolor="#000099" class="tableHead">STOCK ITEMS - BULK</td></tr></table>';
			
					while ($listItem=mysqli_fetch_array($result2,MYSQLI_ASSOC)){
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
					
					
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="7" bgcolor="#D7E1FF" class="tableSubHead">Product  ';
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
	
	
	if ($sendPrices=="true"){
	$message.=$listItem[order_product_price];
	}else{
	$message.='n/a';
	}


	$message.='</b><br>Subtotal:$';
	
	if ($sendPrices=="true"){
	$message.=$itemSubtotal;
	}else{
	$message.='n/a';
	}

	$message.='</td></tr></table>';
					} //END OF WHILE
			}// END OF STOCK BY BULK SECTION

//BEGINNING OF TOTALS SECTION


$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr >';
$message.='<td align="left" class="formCellNosides">Shipping:';
$message.=$order_shipping_method;
$message.='</td>';
$message.='<td align="right" valign="middle" class="formCellNosidesRA">';
$message.=$order_shipping_cost;
$message.='</td></tr>';

if ($extra_product_price!=0){//INCLUDE EXTRA ITEM
		$message.="<tr >
            <td width=\"524\" align=\"left\"  class=\"formCellNosides\">Additional Item:&nbsp;".$extra_product;
		$message.="<td width=\"100\" align=\"right\" valign=\"middle\" class=\"formCellNosidesRA\">&nbsp;".$extra_product_price."</td> </tr>";
		}
			  
 if ($additional_dsc!=0){
	$message.="<tr >";
    $message.="<td width=\"524\" align=\"left\"  class=\"formCellNosides\">Additional Discount (".$discount_type.$additional_dsc.")</td>";
    $message.="<td width=\"100\" align=\"right\" valign=\"middle\" class=\"formCellNosidesRA\"><b>-";
	
	if($discount_type=="$"){
					$totalDiscount=money_format('%.2n',$additional_dsc);
					}
	else if ($discount_type=="%"){
					$totalDiscount=money_format('%.2n',$totalPriceDsc*($additional_dsc/100));
				}
	$message.=$totalDiscount; 
	$message.= "</b></td></tr>";
			  
			  }
			  
$message.='<tr ><td width="524" align="left" class="Subheader">Order Total with All Discounts</td>';
$message.='<td width="100" align="right" valign="middle" class="total"><b>$';
				
$totalPriceDsc=money_format('%.2n',$totalPriceDsc+$order_shipping_cost-$totalDiscount+$extra_product_price);

if ($sendPrices=="true"){
$message.=$totalPriceDsc." ".$currency; 
}else{
$message.='n/a';
}


$message.='</b></td></tr><tr><td  style="font-size:8px;" class="Subheader">Effective on April 1st 2013:
<b>1.5% charge on invoice not paid within 30 days following the date of statement
</b></td></tr></table>';
$message.="</body></html>";
if ($printit) {
	echo $message;
	die();
}
else{
	$send_to_address = str_split($send_to_address,50);
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	}
}//END OF FUNCTION



function sendPrescriptionLabConfirmation($userId,$orderNum,$userData,$product_name){
		
	$lab_id=$userData[main_lab];
	$query6="SELECT lab_email, logo_file from labs WHERE primary_key='$lab_id'";//LOOK UP MAIN LAB EMAIL ADDRESS
	$result6=mysqli_query($con,$query6) or die  ('I cannot select items because h588: ' . mysqli_error($con));
	$listItemML=mysqli_fetch_array($con,$result6);
		
	//sendPrescriptionConfirmation($listItemML[lab_email],$listItemML[logo_file],$orderNum,$listItemML[lab_email],$userData[user_id],$userData,"true",false);//ALWAYS SEND CONFIRMATIOn TO MAIN LAB
		
	$query3="SELECT collection FROM exclusive WHERE product_name='$product_name'";
	$result3=mysqli_query($con,$query3)		or die ('Could not update because h11658: ' . mysqli_error($con));
	$listItem2=mysqli_fetch_array($result3,MYSQLI_ASSOC);
	$collection=$listItem2[collection];

			
}//END FUNCTION

function sendPrescriptionConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$userData,$sendPrices,$printit){//PRESCRIPTION CONFIRMATION
include "../sec_connectEDLL.inc.php";

$bg_query="SELECT bg_name FROM buying_groups WHERE primary_key='$userData[buying_group]'";//GET BUYING GROUP NAME
$bg_result=mysqli_query($con,$bg_query)		or die  ('I cannot select items because: ' . mysqli_error($con));
$bgData=mysqli_fetch_array($bg_result,MYSQLI_ASSOC);

// include Barcode39 class
include "../barcodes/Barcode39.php";
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
	$result=mysqli_query($con,$query)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$usercount=mysqli_num_rows($result);
	$Product_line = $listItem[product_line];
	
	if ($listItem['product_line']=="directlens")
		{$dl_logo_file="aucun";
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
		{$dl_logo_file=constant('DIRECT_LENS_URL')."/safety/design_images/safety-logo.jpg";
		$pl_text="SAFE";}
	elseif (($listItem['product_line']=="safety") && (strtolower($listItem['language'])=="english"))
		{$dl_logo_file=constant('DIRECT_LENS_URL')."/safety/design_images/safety-logo-en.jpg";
		$pl_text="SAFE";}
	elseif($listItem['product_line']=="eye-recommend") 
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/direct-lens/logos/direct-lens_logo.gif";
		$pl_text="Direct-Lens Prestige";}
	else
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/direct-lens/logos/lensnet_logo.gif";
		$pl_text="LensNet";}
		
		
	if (($listItem['main_lab']== 66) || ($listItem['main_lab']== 67)){  //Main lab des entrepots
		$CompteEdll = 'oui';
		//Ne pas afficher de logo en attendant celui de Sara
		$dl_logo_file = "https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/ifcopticclub.ca/design_images/edll_2018.png";
	}else{
		$CompteEdll = 'non';
	}	
	
		if ($user_id=='moncton'){//Logo OVG car exporté vers RDL
			$dl_logo_file = "https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/ifcopticclub.ca/design_images/EDLL_GROptiquevision_logo2_out-02.png";
		}
		
		if ($user_id=='edmundston'){//Logo OVG car exporté vers RDL
			$dl_logo_file = "https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/ifcopticclub.ca/design_images/EDLL_GROptiquevision_logo2_out-02.png";
		}
		
		if ($user_id=='sorel'){//Logo OVG car exporté vers RDL
			$dl_logo_file = "https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/ifcopticclub.ca/design_images/EDLL_GROptiquevision_logo2_out-02.png";
		}
		
		if ($user_id=='vaudreuil'){//Logo OVG car exporté vers RDL
			$dl_logo_file = "https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/ifcopticclub.ca/design_images/EDLL_GROptiquevision_logo2_out-02.png";
		}
			
		
		
		
	$headers = "From:".$fromAddress."\r\n";
	$mime_boundary=md5(time()); 
	$headers .= 'MIME-Version: 1.0'."\r\n"; 
	$headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\""."\r\n"; 
					
	$headers .= "--".$mime_boundary."\r\n"; 
	$headers .=	"Content-Type: 	text/html; charset=\"iso-8859-1\"\r\n";
	$headers .= "Content-Transfer-Encoding: 8bit"."\r\n"; 
						
	$subject = $pl_text." Prescription Order Confirmation (REVISED) - Order Number:$orderNum";
	$message='<html xmlns="http://www.w3.org/1999/xhtml"><head><base href="'.constant('DIRECT_LENS_URL').'/processOrder.php">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ORDER</title>';
	if ($printit) $message .= "<script type='text/javascript'>window.onload = function() {window.print(); }</script>";
$message .= '</head>';
	$message.="<link href=\"".constant('DIRECT_LENS_URL')."/dl.css\" rel=\"stylesheet\" type=\"text/css\" />";

	$message.='<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr>';
	
	
	if (($dl_logo_file <> "nologo") && ($CompteEdll <> 'oui'))
	$message.='<td align="left"><img src="logos/'.$logo_file.'"/></td>';
	
	
	if ($dl_logo_file <> "aucun"){
		$message.= '<td align="center"><img src="'.$dl_logo_file.'" width="200" height="60" /></td>';
	}
	
	$message.='<td align="right"><img src="'.$barcode.'" width="190" height="50" />';//ABSOLUTE PATH TO THE BARCODE IMAGE
	
	if ($CompteEdll == 'oui'){//Afficher les numéros de taxes de l'entrepot
	$message.='<p>TPS/TVH:&nbsp;830136776 RT0001&nbsp;<br>&nbsp;&nbsp;TVQ:&nbsp;1220985128 TQ0001</p>';
	}elseif(($pl_text<>"AIT Lens Club") && ($listItem[main_lab] <> 50) && ($pl_text<>"SAFE")){
	$message.='<p>GST:&nbsp;808437370 RT 0001&nbsp;<br>&nbsp;&nbsp;QST:&nbsp;1227025278 TQ 0001</p>';
	}
	
	$message.='</td></tr></table>';
	
	$query="SELECT po_num, order_date_processed, order_date_shipped, redo_order_num, order_num_optipro,order_num_opticbox, prescript_lab, patient_ref_num 
	FROM orders WHERE order_num='$orderNum'"; //GET ORDER PO NUMBER AND REDO ORDER NUMBER
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
		
	$Patientrefnum = $listItem[patient_ref_num];
	$message.='<table width="650" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td align="left">';
	$message.='<td><div class="header2">Your '.$pl_text.' Order #:'.$orderNum;
	
	if ($listItem[redo_order_num]!=0){
	$message.="R (".$listItem[redo_order_num].")";	
	}
	
	if ($listItem[order_num_optipro] <> '')
	$message.="# Optipro: " . $listItem[order_num_optipro];
	
	if ($listItem[order_num_opticbox] <> '')
	$message.="# Optic-Box: " . $listItem[order_num_opticbox];


	switch($listItem[prescript_lab]){
		case 10: $LabFabrique = "SWISS";  break;
		case 25: $LabFabrique = "Central Lab";    break;
		case 69: $LabFabrique = "Essilor #1 Lab";    break;
		case 3:  $LabFabrique = "Dlab";    break;
		case 21: $LabFabrique = "N/A";    break;
		default: $LabFabrique = '' ; 
	}


	$message.='</div></td></tr></table>';
	
		$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr><td align="left" class="formCellNosides">PO Order #: ';
		$message.=$listItem[po_num] .  '&nbsp;&nbsp;&nbsp;&nbsp; Order Date2:'. $listItem[order_date_processed];
		if ($listItem['order_date_shipped'] != '0000-00-00')
		{
		$message.= '&nbsp;&nbsp;&nbsp;&nbsp; Shipping Date:'. $listItem[order_date_shipped] ;
		} 
		if ($LabFabrique != ''){
		$message.= '&nbsp;&nbsp;LAB: <b>'. $LabFabrique. '</b>' ;	
		}
		
		
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
			
			//BEGIN PRESCRIPTION SECTION
			
$query="SELECT * FROM orders WHERE user_id='$user_id' and order_num='$orderNum' and order_product_type='exclusive' ORDER by order_item_date";//SELECT ALL OPEN PRESCRIPTION ORDERS
			$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
			$usercount=mysqli_num_rows($result);
			if ($usercount != 0){
			
					
					while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
					$OrderPrimaryKey = $listItem[primary_key];
					$bl_query="SELECT * from additional_discounts WHERE orders_id='$listItem[primary_key]'";//GET BUYING LEVEL DISCOUNT
					$bl_result=mysqli_query($con,$bl_query) or die  ('I cannot select bl items because: ' . mysqli_error($con).$bl_query);
					$bl_listItem=mysqli_fetch_array($bl_result,MYSQLI_ASSOC);
					$buying_level_dsc=$bl_listItem[buying_level_discount];
						
					$e_query="SELECT * from extra_product_orders WHERE order_id='$listItem[primary_key]'";//GET EXTRA PRODUCT PRICES
					$e_result=mysqli_query($con,$e_query) or die  ('I cannot select items because: ' . mysqli_error($con));
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
								
								$e_products_string.="<br>Edging: ".$e_listItem[price];
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

							if ($e_listItem[category]=="Engraving"){
								$e_products_string.="Engraving: ".$e_listItem[price];
								$e_products_string_na.="Engraving: n/a";
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
								

									
							if ($e_listItem[category]=="Removable Side Shield"){
								$e_products_string.="<br />Removable Side Shield: ".$e_listItem[price];
								$e_products_string_na.="<br />Removable Side Shield: n/a";
								//$e_order_string_tint="<b>Removable Side Shield: </b>".$e_listItem[tint].": ";
							}//END Side shield
					
							if ($e_listItem[category]=="Iimpact"){
								$e_products_string.="<br />Iimpact: ".$e_listItem[price];
								$e_products_string_na.="<br />Iimpact: n/a";
								//$e_order_string_tint="<b>Removable Side Shield: </b>".$e_listItem[tint].": ";
							}//END Iimpact from Swiss
					
							if ($e_listItem[category]=="Side Shield"){
								$e_products_string.="<br />Side Shield: ".$e_listItem[price];
								$e_products_string_na.="<br />Side Shield: n/a";
								//$e_order_string_tint="<b>Removable Side Shield: </b>".$e_listItem[tint].": ";
							}//END Side shield
							
							if ($e_listItem[category]=="Cushion"){
								$e_products_string.="<br />Cushion: ".$e_listItem[price];
								$e_products_string_na.="<br />Cushion: n/a";
								//$e_order_string_tint="<b>Cushion: </b>";
							}//END Cushion
							
							if ($e_listItem[category]=="Dust Bar"){
								$e_products_string.="<br />Dust Bar: ".$e_listItem[price];
								$e_products_string_na.="<br />Dust Bar: n/a";
								//$e_order_string_tint="<b>Dust Bar: </b>";
							}//END Dust bar
							
							if ($e_listItem[category]=="Cylinder Over Range"){
								$e_products_string.="<br />Cylinder Over Range: ".$e_listItem[price];
								$e_products_string_na.="<br />Cylinder Over Range: n/a";
								//$e_order_string_tint="<b>Dust Bar: </b>";
							}//END Cylinder Over Range
									
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
							
							if ($e_listItem[category]=="High Addition"){
								if (($Product_line=='eye-recommend') && ($e_listItem[price] > 0)){
									$ListPrice = 20.00; 
									$PrestigeRebateforExtra = $PrestigeRebateforExtra + ($ListPrice - $e_listItem[price]);
									$e_listItem[price] 	    = money_format('%.2n',$ListPrice);
								}
								$e_products_string.="<br />High Addition: ".$e_listItem[price];
								$e_products_string_na.="<br />High Addition: n/a";
								//$e_order_string_tint="<b>High Addition: </b>";
							}//End High Addition
							
							if ($e_listItem[category]=="High Cylinder"){
								if (($Product_line=='eye-recommend') && ($e_listItem[price] > 0)){
									$ListPrice = 10.00; 
									$PrestigeRebateforExtra = $PrestigeRebateforExtra + ($ListPrice - $e_listItem[price]);
									$e_listItem[price] 	    = money_format('%.2n',$ListPrice);
								}	
								$e_products_string    .= "<br />High Cylinder: "   . $e_listItem[price];
								$e_products_string_na .= "<br />High Cylinder: n/a";
								//$e_order_string_tint   = "<b>High Cylinder: </b>";
							}//End High Cylinder
							
							
							if ($e_listItem[category]=="Special Size"){
								if (($Product_line=='eye-recommend') && ($e_listItem[price] > 0)){
									$ListPrice = 25.00; 
									$PrestigeRebateforExtra = $PrestigeRebateforExtra + ($ListPrice - $e_listItem[price]);
									$e_listItem[price] 	    = money_format('%.2n',$ListPrice);
								}	
								$e_products_string.="<br />Special Size: ".$e_listItem[price];
								$e_products_string_na.="<br />Special Size: n/a";
								//$e_order_string_tint="<b>Special Size: </b>";
							}//End Special Size
							
							if ($e_listItem[category]=="Special_Base"){	
								if (($Product_line=='eye-recommend') && ($e_listItem[price] > 0)){
									$ListPrice = 20.00; 
									$PrestigeRebateforExtra = $PrestigeRebateforExtra + ($ListPrice - $e_listItem[price]);
									$e_listItem[price] 	    = money_format('%.2n',$ListPrice);
								}
								$e_products_string.="<br />Special Base: ".$e_listItem[price];
								$e_products_string_na.="<br />Special Base: n/a";
								//$e_order_string_tint="<b>Special Base: </b>";
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
								//$e_order_string_tint="<b>Top Urgent: </b>";
							}
							
							if ($e_listItem[category]=="Large Frame"){	
								$e_products_string.="<br />Large Frame: ".$e_listItem[price];
								$e_products_string_na.="<br />Large Frame: n/a";
								//$e_order_string_tint="<b>Large Frame: </b>";
							}
							
							if ($e_listItem[category]=="Frame"){
								$e_products_string.="<br />Frame: ".$e_listItem[price];
								
								
								$e_products_string_na.="<br />Frame: n/a";
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
					
					
					$itemSubtotal=$listItem[order_quantity]*($listItem[order_product_price]+$e_total_price)+$over_range-$coupon_dsc- $MontantDejaPaye  + $PrestigeRebateforExtra -$DifferenceOverRange ;
					$totalPrice=$totalPrice+$itemSubtotal;
					
					$itemSubtotalDsc=$listItem[order_quantity]*($listItem[order_product_discount]+$e_total_price)+$over_range+$extra_product_price-$coupon_dsc+$buying_level_dsc- $MontantDejaPaye - $DifferenceOverRange;
					
					$totalPriceDsc=$totalPriceDsc+$itemSubtotalDsc;
					
					$prescrQuantity=$prescrQuantity+$listItem[order_quantity];
					$itemSubtotal=money_format('%.2n',$itemSubtotal);
					$RabaisSurFacture =money_format('%.2n', $listItem[order_product_price]  - $listItem[order_product_discount]);
						
					$queryProduit = "SELECT order_product_id, order_from FROM orders where order_num = $orderNum";
					$resultProduit  =mysqli_query($con,$queryProduit)			or die ("Could not find order num");
					$listItemProduit=mysqli_fetch_array($resultProduit,MYSQLI_ASSOC);
					$PK_Produit = $listItemProduit['order_product_id'];
					$order_from = $listItemProduit['order_from'];
					
					if ($order_from=='ifcclubca')
					$queryProductCode = "SELECT product_code  FROM ifc_ca_exclusive WHERE primary_key  = $PK_Produit";
			        elseif($order_from=='safety')
					$queryProductCode = "SELECT product_code  FROM safety_exclusive WHERE primary_key  = $PK_Produit";
					else
					$queryProductCode = "SELECT product_code  from exclusive where primary_key  = $PK_Produit";
					$resultProductCode  =mysqli_query($con,$queryProductCode) or die ("Could not find product code");
					$listItemProductCode=mysqli_fetch_array($resultProductCode,MYSQLI_ASSOC);
					$Product_Code = $listItemProductCode['product_code'];
					
					
					
					$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
         <tr >
                <td colspan="6" bgcolor="#D7E1FF" class="tableSubHead">Product - ';
				$message.=$listItem[order_product_name] . '     <br>Product Code:<b>' . $Product_Code . '</b>' ;
				
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
                <td bgcolor="#FFFFFF" class="formCellNosidesRA"><b>Patient:<b></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides">';
				$message.=$listItem[order_patient_first].'&nbsp;'.$listItem[order_patient_last].'</td>';
				
				$message.='<td bgcolor="#FFFFFF" class="formCellNosidesRA"><b>Tray Num:<b></td>';
				$message.='<td align="center" bgcolor="#FFFFFF" class="formCellNosides">'.$listItem[tray_num].'</td>';
				
				$message.='<td bgcolor="#FFFFFF" class="formCellNosidesRA"><b>Ref Number:<b></td>';
				$message.='<td align="center" bgcolor="#FFFFFF" class="formCellNosides">'.$listItem[patient_ref_num].'</td>';
				
				$message.='<td align="center" bgcolor="#FFFFFF" class="formCellNosidesRA"><b>Salesperson ID:<b> </td>
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
				$message.=$listItem[re_pr_ax]."&nbsp;".$listItem[re_pr_io]."&nbsp;&nbsp;".$listItem[re_pr_ax2]."&nbsp;".$listItem[re_pr_ud];
				$message.='</td>
                <td rowspan="8" align="center" valign="top" class="formCellNosidesCenter">';
				$message.=$listItem[order_quantity];
				$message.='</td>
                <td rowspan="8" align="right" valign="top" class="formCellNosidesRA"><b>$';
				
				
				
			   $queryEntryFee  = "SELECT entry_fee FROM orders WHERE order_num  = " . $listItem[order_num];
			   $resultEntryFee = mysqli_query($con,$queryEntryFee)	or die ("Could not select items b6");
			   $DataEntryFee   = mysqli_fetch_array($resultEntryFee,MYSQLI_ASSOC);
			   
			   $queryWarranty  = "SELECT warranty FROM orders WHERE order_num  = " . $listItem[order_num];
			   $resultWarranty = mysqli_query($con,$queryWarranty)	or die ("Could not select items c8");
			   $DataWarranty   = mysqli_fetch_array($resultWarranty,MYSQLI_ASSOC);
			  

				
				if ($sendPrices=="true"){
					$message.=$listItem[order_product_price] .'<br>';}
				else{
					$message.='n/a';
				}
				
				$message.='</b>';
				
				
				if ($sendPrices=="true"){
						if ($DataEntryFee[entry_fee] == 2) 
						{
						$message.= '<br><b>Data Entry Fees</b> 2$(inc. in product price)';
						}  
						
						if ($DataWarranty[warranty] == 1) 
						{
						$message.= '<br><b>Warranty Fees</b> 6$(inc. in product price)';
						}  
						
						if ($DataWarranty[warranty] == 2) 
						{
						$message.= '<br><b>Warranty Fees</b> 10$(inc. in product price)';
						} 
				} 
				
				
				
				if ($sendPrices=="true"){
					if ($SpecialBasePrice<>0){
						$message.= '<br> Special Base: '. $SpecialBasePrice;}
						
					if ($HighAddition<>0){
						$message.= '<br> High Addition: '. $HighAddition;}
									
					if ($over_range!=0){
						$message.= '<br> Over range: '. $over_range;}	
						

					if ($e_total_price!=0){
						$message.= $e_products_string;}
					
					if ($listItem[coupon_dsc]!=0){
						$message.= "<br>Coupon Discount: -".$listItem[coupon_dsc];}
							 
			 $queryTopUrgent = "SELECT * FROM extra_product_orders WHERE category = 'Top urgent' AND order_num =  " . $listItem[order_num];
			 $resultTopUrgent=mysqli_query($con,$queryTopUrgent)	or die ("Could not select items A9");
			 $countTopUrgent=mysqli_num_rows($resultTopUrgent);
			 $DataUrgent=mysqli_fetch_array($resultTopUrgent,MYSQLI_ASSOC);
			if  ($countTopUrgent > 0)
			$message.= "<br>Top Urgent:".  $DataUrgent['price']. "<br>";
			
			if ($MontantDejaPaye <> 0 ){
			$message.= "<br><br><b>Paid by tiers: -". $MontantDejaPaye.'</b>';			
			}
			
			$RabaisSurFacture += $PrestigeRebateforExtra ;//Ajouter le rabais qu'on donne sur les extras au rabais accordé sur les verres
				
					$message.='<br>Subtotal: '.$itemSubtotal;
					if ($RabaisSurFacture > 0)
					$message.= '<br>Rebate:'.  money_format('%.2n',$RabaisSurFacture);;
				}
				else{
					if ($over_range!=0){
						$message.= '<br> Over range: n/a';}
						
					if ($listItem[extra_product_price]!=0){
						//$message.= "<br>Extra item: n/a";
						}
					if ($e_total_price!=0){
						$message.= "<br>".$e_products_string_na;}
					if ($listItem[coupon_dsc]!=0){
						$message.= "<br>Coupon Discount: n/a";}
					$message.='<br>Subtotal: n/a';
				}
				
				
				if ($e_order_string_frame!=""){
					$e_order_string_edging=$e_order_string_frame;}
				
				$message.='</td>
              </tr>

              <tr >
                <td colspan="6" align="left" class="formCellNosides"><strong>Dist.
                    PD:</strong>'.$listItem[re_pd].'&nbsp;'.  $listItem[re_pd_near].'&nbsp;&nbsp;&nbsp;<strong>Height:</strong>'. $listItem[re_height].' </td>
                </tr>
              <tr >
                <td align="right" class="formCellNosides">
                  L.E.</td>
                <td align="center" class="formCellNosides">'.$listItem[le_sphere].' </td>
                <td align="center" class="formCellNosides">'.$listItem[le_cyl].' </td>
                <td align="center" class="formCellNosides">'.$listItem[le_axis].'</td>
                <td align="center" class="formCellNosides">'.$listItem[le_add].' </td>
                <td align="center" class="formCellNosides">'.$listItem[le_pr_ax] ."&nbsp;".$listItem[le_pr_io]."&nbsp;&nbsp;".$listItem[le_pr_ax2]."&nbsp;".$listItem[le_pr_ud].'</td>
                </tr>
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>Dist.
                    PD: </strong>'.$listItem[le_pd].'&nbsp;'.$listItem[le_pd_near].' &nbsp;&nbsp;&nbsp;<strong>Height:</strong> '.$listItem[le_height].' </td>
                </tr>
							<tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>PT: </strong>'.$listItem[PT].' &nbsp;&nbsp;&nbsp;<strong>PA: </strong>'.$listItem[PA].' &nbsp;&nbsp;&nbsp;<strong>Vertex:</strong> '.$listItem[vertex].'&nbsp;&nbsp;&nbsp;<strong>B.C: </strong>'.$listItem[base_curve].'</td>
                </tr>';
				
  				


				$message.='<tr><td colspan="3" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>Thickness: </strong>';
			 	$message.=  'RE CT:' 		         . $listItem['RE_CT']   . '&nbsp;&nbsp;LE CT:' . $listItem['LE_CT'] ;  
				$message.=  '&nbsp;&nbsp;&nbsp;RE ET:'. $listItem['RE_ET']   . '&nbsp;&nbsp;LE ET:' . $listItem['LE_ET'] ;  
			    $message.='</td><td class="formCellNosides"><b>O.C:</b></td><td class="formCellNosides">'.$listItem['optical_center'].'&nbsp;mm</td></tr>
				<tr>
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>FRAME:
                    &nbsp; </strong>'.$e_order_string_edging.' </td>
              </tr>';
			  
			 $message.=" <tr>
                <td colspan=\"6\" align=\"left\" bgcolor=\"#FFFFFF\" class=\"formCellNosides\"><strong>".'CODE SOURCE MONTURE:</strong>'. $listItem[code_source_monture] . '</td>
              </tr>' ;
						
				
			  
			  $message.='<tr>
			   <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>OTHER:</strong> 
				'.$e_order_string_engraving.$e_order_string_tint.$DetailMirror.' <b>'.$listItem[UV400].'</b>
				</td>
				</tr>';
					 $message.='<tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>Special Instructions:</b>&nbsp;'.$listItem[special_instructions].' </td>
              </tr>';
			  
			   if ($listItem[redo_reason_id] <> 0){
			  $queryRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = " . $listItem[redo_reason_id];
			  $resultRedoReason = mysqli_query($con,$queryRedoReason)		or die  ('I cannot select items because 8: ' . mysqli_error($con));
			  $DataRedoReason   = mysqli_fetch_array($resultRedoReason,MYSQLI_ASSOC);
			  $RedoReason   =  $DataRedoReason[redo_reason_en];
			   $message.='<tr >
               <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>Redo Reason:</b>&nbsp;'. $RedoReason.' </td>
              </tr>';
			  }
			  
			 
			 
			 
			 
			 
	$queryProductLine="SELECT product_line FROM accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$resultProductline=mysqli_query($con,$queryProductLine)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataProductline=mysqli_fetch_array($resultProductline,MYSQLI_ASSOC);
			 
			if (($DataProductline[product_line]=='lensnetclub') || ($DataProductline[product_line]=='aitlensclub')){
			 
				   $message.='<tr >
					<td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>Extra Warranty :</b>&nbsp;'.$listItem[warranty];
				
						if ($listItem[warranty] == 0){
						$message .= 'Year (No warranty purchased)</td> </tr>';
					  }elseif($listItem[warranty] == 1){
						$message .= 'Year (price included in product price)</td> </tr>';
					  }else{
						$message .= 'Years (price included in product price)</td> </tr>';
					  }
			  }
			  
			  
              
            $message.='</table>';
						
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
	$message.='n/a';
}

$message.='</td></tr>';

	if ($extra_product_price!=0){//INCLUDE EXTRA ITEM
		$message.="<tr >
            <td width=\"524\" align=\"left\"  class=\"formCellNosides\">Additional Item:&nbsp;".$extra_product;
		$message.="<td width=\"100\" align=\"right\" valign=\"middle\" class=\"formCellNosidesRA\">&nbsp;";
		
		if ($sendPrices=="true"){
			$message.=$extra_product_price;}
		else{
			$message.='n/a';
		}
		
		$message.="</td> </tr>";
		}

if (($additional_dsc!=0)&&($sendPrices=="true")){
	$message.="<tr >";
    $message.="<td width=\"524\" align=\"left\"  class=\"formCellNosides\">Additional Discount (".$discount_type.$additional_dsc.")</td>";
    $message.="<td width=\"100\" align=\"right\" valign=\"middle\" class=\"formCellNosidesRA\"><b>-";
	
	if($discount_type=="$"){
					$totalDiscount=money_format('%.2n',$additional_dsc);
					}
	else if ($discount_type=="%"){
					$totalDiscount=money_format('%.2n',$totalPriceDsc*($additional_dsc/100));
				}
	$message.=$totalDiscount; 
	$message.= "</b></td></tr>";
			  
			  }
			  
$message.='<tr ><td width="524" align="left" class="Subheader">Order Total with All Discounts</td>';
$message.='<td width="100" align="right" valign="middle" class="total"><b>$';
				
//$totalPriceDsc=money_format('%.2n',$totalPriceDsc+$order_shipping_cost-$totalDiscount+$extra_product_price);
$totalPriceDsc=money_format('%.2n',$totalPriceDsc+$order_shipping_cost-$totalDiscount);
if ($sendPrices=="true"){				
	$message.=$totalPriceDsc." ".$currency; }
else{
	$message.='n/a';
}
			
	$message.='</b></td></tr><tr><td  style="font-size:8px;" class="Subheader">Effective on April 1st 2013:
<b>1.5% charge on invoice not paid within 30 days following the date of statement
</b></td></tr></table>';




	
	//Ajout message Dispensing fee/Honoraire du professionnel
	$queryDispensingFeeSV  = "SELECT * from extra_product_orders WHERE order_id='$OrderPrimaryKey' AND category = 'Dispensing Fee SV'";//GET EXTRA PRODUCT PRICES
	$resultDispensingFeeSV = mysqli_query($con,$queryDispensingFeeSV)				or die  ('I cannot select items because 7: ' . mysqli_error($con));
	$countDispensingSv     = mysqli_num_rows($resultDispensingFeeSV);
	if ($countDispensingSv != 0){
	$DataDispensingFeeSV = mysqli_fetch_array($resultDispensingFeeSV,MYSQLI_ASSOC);	
	
	/*$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr>';
	$message.='<td align="left" class="formCellNosides">Dispensing Fee Single Vision: 20.00$';
	$message.='</td></tr></table>';*/

	}
	
	
	//Ajout message Dispensing fee/Honoraire du professionnel BIFOCAL
	$queryDispensingFeeSV  = "SELECT * from extra_product_orders WHERE order_id='$OrderPrimaryKey' AND category = 'Dispensing Fee Bifocal'";//GET EXTRA PRODUCT PRICES
	$resultDispensingFeeSV = mysqli_query($con,$queryDispensingFeeSV)				or die  ('I cannot select items because 7: ' . mysqli_error($con));
	$countDispensingSv     = mysqli_num_rows($resultDispensingFeeSV);
	if ($countDispensingSv != 0){
	$DataDispensingFeeSV = mysqli_fetch_array($resultDispensingFeeSV,MYSQLI_ASSOC);	
	
	/*$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr>';
	$message.='<td align="left" class="formCellNosides">Dispensing Fee Bifocal: 25.00$';
	$message.='</td></tr></table>';*/

	}
	
	//Ajout message Dispensing fee/Honoraire du professionnel
	$queryDispensingFeeProg  = "SELECT * from extra_product_orders WHERE order_id='$OrderPrimaryKey' AND category = 'Dispensing Fee Progressive'";//GET EXTRA PRODUCT PRICES
	$resultDispensingFeeProg = mysqli_query($con,$queryDispensingFeeProg)				or die  ('I cannot select items because 7: ' . mysqli_error($con));
	$countDispensingProg     = mysqli_num_rows($resultDispensingFeeProg);
	if ($countDispensingProg != 0){
	$DataDispensingFeeProg = mysqli_fetch_array($resultDispensingFeeProg,MYSQLI_ASSOC);	
	/*$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr>';
	$message.='<td align="left" class="formCellNosides">Dispensing Fee Progressive: 30.00$';
	$message.='</td></tr></table>';*/
	}
	
			
	$message.="</body></html>";
if ($printit) {
	echo $message;
	die();
}
else {
	//$inviteSent = mail("$send_to_address", "$subject", "$message", "$headers");
	$send_to_address = str_split($send_to_address,50);
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	}
}

function sendPrescriptionConfirmationLab($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$userData,$sendPrices,$printit){//PRESCRIPTION CONFIRMATION
	$bg_query="select bg_name from buying_groups WHERE primary_key='$userData[buying_group]'";//GET BUYING GROUP NAME
	$bg_result=mysqli_query($con,$bg_query)or die  ('I cannot select items because: ' . mysqli_error($con));
	$bgData=mysqli_fetch_array($bg_result,MYSQLI_ASSOC);

	
// include Barcode39 class
include "../barcodes/Barcode39.php";
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
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$usercount=mysql_num_rows($result);
	
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
	elseif (($listItem['product_line']=="safety") && (strtolower($listItem['language'])=="english"))
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/safety/images/LOGO_SAFE_EN.jpg";
		$pl_text="SAFE";}
	elseif (($listItem['product_line']=="safety") && (strtolower($listItem['language'])=="french"))
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/safety/images/LOGO_SAFE_FR.jpg";
		$pl_text="SAFE";}
	else
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/direct-lens/logos/lensnet_logo.gif";
		$pl_text="LensNet";}

	//$headers = "From:".$fromAddress."\r\n";
	$headers = "From:orders@direct-lens.com\r\n";
	$mime_boundary=md5(time()); 
	$headers .= 'MIME-Version: 1.0'."\r\n"; 
	$headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\""."\r\n"; 
					
	$headers .= "--".$mime_boundary."\r\n"; 
	$headers .=	"Content-Type: 	text/html; charset=\"iso-8859-1\"\r\n";
	$headers .= "Content-Transfer-Encoding: 8bit"."\r\n"; 
						
	$subject = $pl_text." Prescription Order Confirmation (REVISED) - Order Number:$orderNum";
	$message='<html xmlns="http://www.w3.org/1999/xhtml"><head><base href="'.constant('DIRECT_LENS_URL').'/processOrder.php">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ORDER</title>';
	if ($printit) $message .= "<script type='text/javascript'>window.onload = function() {window.print(); window.close();}</script>";
$message .= '</head>';
	$message.="<link href=\"".constant('DIRECT_LENS_URL')."/dl.css\" rel=\"stylesheet\" type=\"text/css\" />";

	$message.='<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img src="logos/'.$logo_file.'"/><td align="center"><img src="'.$dl_logo_file.'" width="200" height="60" /></td></td><td align="right"><img src="'.$barcode.'" width="190" height="50" /><p>GST:&nbsp;808437370 RT 0001&nbsp;<br>&nbsp;&nbsp;QST:&nbsp;1227025278 TQ 0001</p></td></tr></table>';//ABSOLUTE PATH TO THE BARCODE IMAGE
	
	$query="SELECT po_num, redo_order_num FROM orders WHERE order_num='$orderNum'"; //GET ORDER PO NUMBER AND REDO ORDER NUMBER
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
		
	$message.='<table width="650" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td align="left">';
	$message.='<td><div class="header2">Your '.$pl_text.' Order #:'.$orderNum;
	
	if ($listItem[redo_order_num]!=0){
	$message.="R (".$listItem[redo_order_num].")";	
	}
	
	$message.='</div></td></tr></table>';
	
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
	$message.='xxxx';
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Account Number :</td>';
    $message.='<td width="520"  class="formCellNosides"><strong>';
	$message.=$listItem[account_num];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Company:</td><td width="520" class="formCellNosides"><strong>';
	$message.='xxxx';
	$message.='</strong> </td></tr><tr ><td align="right" class="formCellNosides">Buying Group: </td><td class="formCellNosides"><strong>';
	$message.='xxxx';
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Shipping Code: </td><td class="formCellNosides"><strong>';
	$message.=$_SESSION["sessionUserData"]["shipping_code"];
	$message.='</strong></td></tr>';
	$message.='</table>';
	
			
			//BEGIN PRESCRIPTION SECTION
			
$query="SELECT * from orders WHERE user_id='$user_id' and order_num='$orderNum' and order_product_type='exclusive' ORDER by order_item_date";//SELECT ALL OPEN PRESCRIPTION ORDERS
			$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
			$usercount=mysqli_num_rows($result);
			if ($usercount != 0){
								
					while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
					$entry_fee = $listItem[entry_fee];
					$bl_query="SELECT * FROM additional_discounts WHERE orders_id='$listItem[primary_key]'";//GET BUYING LEVEL DISCOUNT
					$bl_result=mysqli_query($con,$bl_query)		or die  ('I cannot select bl items because: ' . mysqli_error($con));
					$bl_listItem=mysqli_fetch_array($con,$bl_result);
					$buying_level_dsc=$bl_listItem[buying_level_discount];
						
					$e_query="SELECT * from extra_product_orders WHERE order_id='$listItem[primary_key]'";//GET EXTRA PRODUCT PRICES
					$e_result=mysqli_query($con,$e_query)		or die  ('I cannot select items because: ' . mysqli_error($con));
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
								
							//Ajout message Dispensing fee/Honoraire du professionnel SV
							/*if ($e_listItem[category]=="Dispensing Fee SV"){
							   $e_products_string.="<br />Dispensing Fee SV: ".$e_listItem[temple]. '.00';
							   $e_products_string_na.="<br />Dispensing Fee SV: n/a";
							}//END IF TINT	*/
							   
							//Ajout message Dispensing fee/Honoraire du professionnel BIFOCAL
							/*if ($e_listItem[category]=="Dispensing Fee Bifocal"){
							   $e_products_string.="<br />Dispensing Fee Bifocal: ".$e_listItem[temple]. '.00';
								$e_products_string_na.="<br />Dispensing Fee Bifocal: n/a";
							}//END IF TINT	*/
							
							//Ajout message Dispensing fee/Honoraire du professionnel PROGRESSIF
							/*if ($e_listItem[category]=="Dispensing Fee Progressive"){
							   $e_products_string.="<br />Dispensing Fee Progressive: ".$e_listItem[temple]. '.00';
								$e_products_string_na.="<br />Dispensing Fee Progressive: n/a";
							}//END IF TINT	*/	
								
								
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
					$itemSubtotal=$listItem[order_quantity]*($listItem[order_product_price]+$e_total_price)+$over_range+$coupon_dsc-$MontantDejaPaye;
					$totalPrice=$totalPrice+$itemSubtotal;
					
					$itemSubtotalDsc=$listItem[order_quantity]*($listItem[order_product_discount]+$e_total_price)+$over_range+$extra_product_price-$coupon_dsc+$buying_level_dsc-$MontantDejaPaye;
					
					$totalPriceDsc=$totalPriceDsc+$itemSubtotalDsc;
					
					$prescrQuantity=$prescrQuantity+$listItem[order_quantity];
					$itemSubtotal=money_format('%.2n',$itemSubtotal);
					
					$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
         <tr >
                <td colspan="6" bgcolor="#D7E1FF" class="tableSubHead">Product - ';
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
                <td bgcolor="#FFFFFF" class="formCellNosidesRA"><b>Patient:<b></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides">';
				$message.=$listItem[order_patient_first].'&nbsp;'.$listItem[order_patient_last].'</td>';
				
				$message.='<td bgcolor="#FFFFFF" class="formCellNosidesRA"><b>Ref Number:<b></td>';
				$message.='<td align="center" bgcolor="#FFFFFF" class="formCellNosides">'.$listItem[patient_ref_num].'</td>';
				
				$message.='<td align="center" bgcolor="#FFFFFF" class="formCellNosidesRA"><b>Salesperson ID:<b> </td>
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
				$message.=$listItem[re_pr_ax]."&nbsp;".$listItem[re_pr_io]."&nbsp;&nbsp;".$listItem[re_pr_ax2]."&nbsp;".$listItem[re_pr_ud];
				$message.='</td>
                <td rowspan="7" align="center" valign="top" class="formCellNosidesCenter">';
				$message.=$listItem[order_quantity];
				$message.='</td>
                <td rowspan="7" align="right" valign="top" class="formCellNosidesRA"><b>$';
				
				if ($sendPrices=="true"){
					$message.=$listItem[order_product_price];
					
					if ($entry_fee<>0){
								$message.="<br />Entry Fee: ".$entry_fee;
								//$e_products_string_na.="<br />Entry Fee: n/a";
						}
				
					
					}
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
						$message.= "<br>Coupon Discount: -".$listItem[coupon_dsc];}
					$message.='<br>Subtotal: '.$itemSubtotal;
				}
				else{
						if ($entry_fee<>0){
						$message.="<br />Entry Fee: n/a";
						}
				
				
					if ($over_range!=0){
						$message.= '<br> Over range: n/a';}
						
					if ($listItem[extra_product_price]!=0){
						//$message.= "<br>Extra item: n/a";
						}
					if ($e_total_price!=0){
						$message.= "<br>".$e_products_string_na;}
					if ($listItem[coupon_dsc]!=0){
						$message.= "<br>Coupon Discount: n/a";}
						
						
						if ($MontantDejaPaye <> 0 ){
			$message.= "<br><br><b>Paid by tiers: -". $MontantDejaPaye.'</b>';			
			}
			
					$message.='<br>Subtotal: n/a';
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
                <td align="center" class="formCellNosides">'.$listItem[le_axis].'</td>
                <td align="center" class="formCellNosides">'.$listItem[le_add].' </td>
                <td align="center" class="formCellNosides">'.$listItem[le_pr_ax] ."&nbsp;".$listItem[le_pr_io]."&nbsp;&nbsp;".$listItem[le_pr_ax2]."&nbsp;".$listItem[le_pr_ud].'</td>
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
              </tr>';
					 $message.='<tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>Special Instructions:</b>&nbsp;'.$listItem[special_instructions].' </td>
              </tr>';
			  
			   $message.='<tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>Extra warranty:</b>&nbsp;'.$listItem[warranty].'year(s) </td>
              </tr>';
					
            $message.='</table>';
						
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
	$message.='n/a';
}

$message.='</td></tr>';

	if ($extra_product_price!=0){//INCLUDE EXTRA ITEM
		$message.="<tr >
            <td width=\"524\" align=\"left\"  class=\"formCellNosides\">Additional Item:&nbsp;".$extra_product;
		$message.="<td width=\"100\" align=\"right\" valign=\"middle\" class=\"formCellNosidesRA\">&nbsp;";
		
		if ($sendPrices=="true"){
			$message.=$extra_product_price;}
		else{
			$message.='n/a';
		}
		
		$message.="</td> </tr>";
		}

if (($additional_dsc!=0)&&($sendPrices=="true")){
	$message.="<tr >";
    $message.="<td width=\"524\" align=\"left\"  class=\"formCellNosides\">Additional Discount (".$discount_type.$additional_dsc.")</td>";
    $message.="<td width=\"100\" align=\"right\" valign=\"middle\" class=\"formCellNosidesRA\"><b>-";
	
	if($discount_type=="$"){
					$totalDiscount=money_format('%.2n',$additional_dsc);
					}
	else if ($discount_type=="%"){
					$totalDiscount=money_format('%.2n',$totalPriceDsc*($additional_dsc/100));
				}
	$message.=$totalDiscount; 
	$message.= "</b></td></tr>";
			  
			  }
			  
$message.='<tr ><td width="524" align="left" class="Subheader">Order Total with All Discounts</td>';
$message.='<td width="100" align="right" valign="middle" class="total"><b>$';
				
$totalPriceDsc=money_format('%.2n',$totalPriceDsc+$order_shipping_cost-$totalDiscount+$extra_product_price);

if ($sendPrices=="true"){				
	$message.=$totalPriceDsc." ".$currency; }
else{
	$message.='n/a';
}
			
	$message.='</b></td></tr><tr><td  style="font-size:8px;" class="Subheader">Effective on April 1st 2013:
<b>1.5% charge on invoice not paid within 30 days following the date of statement
</b></td></tr></table>';
			
	$message.="</body></html>";
if ($printit) {
	echo $message;
	die();
}
else {
	
	$send_to_address = str_split($send_to_address,50);
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	}
}




function sendFrameStockConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$userData,$printit){//STOCK BULK AND TRAY CONFIRMATION

if ($printit) {
$sendPrices = true;
}
else{
$sendPrices = false;
}

$bg_query="SELECT bg_name FROM buying_groups WHERE primary_key='$userData[buying_group]'";//GET BUYING GROUP NAME
$bg_result=mysqli_query($con,$bg_query)		or die  ('I cannot select items because: ' . mysqli_error($con));
$bgData=mysqli_fetch_array($bg_result,MYSQLI_ASSOC);
	
	
//CONFIGURATION POUR LE B	
// include Barcode39 class
include "../barcodes/Barcode39.php";
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
//FIN CONFIGURATION B


//Configuration LUNARPAGES
/*include "../barcodes/Barcode39.php";
$bc = new Barcode39($orderNum);
$bc->barcode_text_size = 5;
$bc->barcode_bar_thick = 4;
$bc->barcode_bar_thin = 2;
$bc->draw("../barcodes/$orderNum".".gif");
$barcode="/barcodes/".$orderNum.".gif";*/
//FIN CONFIG LUNARPAGES
	
$query="SELECT * FROM accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
$result=mysqli_query($con,$query)		or die  ('I cannot select items because: ' . mysqli_error($con));
		
$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
$usercount=mysql_num_rows($result);
		
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
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/lensnet/logos/logo_so.png";
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
	
	$message .='<img width="250" src="logos/'.$logo_file.'"/>';

	$message .='<td align="center">';
	if ($dl_logo_file=="nologo")
	$message.='';
	else
	$message.='<img src="'.$dl_logo_file.'" width="200" height="60" />';
	
	
	$message .='</td></td><td align="right"><img src="'.$barcode.'" width="190" height="50" />';
	
	if (($dl_logo_file<>"nologo") && ($pl_text <> 'SophisTechEye'))
	$message .='<p>GST:&nbsp;808437370 RT 0001&nbsp;<br>&nbsp;&nbsp;QST:&nbsp;1227025278 TQ 0001</p>';
	
	if (($dl_logo_file<>"nologo") && ($pl_text == 'SophisTechEye'))
	$message .='<p>GST:&nbsp;809050370 RT 0001&nbsp;<br>&nbsp;&nbsp;QST:&nbsp;1221730727 TQ 0001</p>';
	
	$message .='</td></tr></table>';//ABSOLUTE PATH TO THE BARCODE IMAGE
	
		$message.='<table width="650" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td align="left">';
	$message.='<td><div class="header2">Your '.$pl_text.' Order #:'.$orderNum.'</div></td></tr></table>';
	
	$query="select po_num, order_date_processed, order_date_shipped, redo_order_num from orders WHERE order_num='$orderNum'"; //GET ORDER PO NUMBER AND REDO ORDER NUMBER
	$result=mysqli_query($con,$query)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
		$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr><td align="left" class="formCellNosides">PO Order #: ';
		$message.=$listItem[po_num] .  '&nbsp;&nbsp;&nbsp;&nbsp; Order Date:'. $listItem[order_date_processed];
		if ($listItem['order_date_shipped'] != '0000-00-00')
		{
		$message.= '&nbsp;&nbsp;&nbsp;&nbsp; Shipping Date:'. $listItem[order_date_shipped] ;
		} 
		$message.='</td></tr></table>';
	
	$query="SELECT * FROM accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
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

if ($pl_text == "Milano 6769 Canada"){
//Ajouter la section Contact pour les factures Milano6769.ca	
$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#000099" class="tableHead">CONTACT US</td></tr>
<tr><td width="130" align="right" class="formCellNosides">By Email</td><td width="520" class="formCellNosides"><strong>';
	$message.='<a href="mailto:dbeaulieu@direct-lens.com?Subject=Contact%20from%20invoice">dbeaulieu@direct-lens.com</a>';
	$message.='</strong></td></tr><tr><td width="130" align="right" class="formCellNosides">By Phone</td><td width="520" class="formCellNosides"><strong>';
	$message.='1-877-666-7692';
	$message.='</strong></td></tr><tr><td width="130" align="right" class="formCellNosides">By Fax</td><td width="520" class="formCellNosides"><strong>';
	$message.='1-877-590-3522';
	$message.='</strong></td></tr><tr><td width="130" align="right" class="formCellNosides">By Mail</td><td width="520" class="formCellNosides"><strong>';
	$message.='15 Ballantyne Avenue South, Montreal West, QC, H4X 2B1';
	$message.='</strong></td></tr></table>';//END of Address Section	
}

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
         <tr>
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
                <td width="220" align="center" class="formCellNosides">';
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
				
				//$ProductPriceBeforeTaxes = $listItem[order_product_price]/1.05;
				$ProductPriceBeforeTaxes = $listItem[order_product_price];
				$ProductPriceBeforeTaxes = money_format('%.2n',$ProductPriceBeforeTaxes);
				
				//$SousTotalBeforeTaxes = $SousTotal/1.05;
				$SousTotalBeforeTaxes = $SousTotal;
				$SousTotalBeforeTaxes = money_format('%.2n',$SousTotalBeforeTaxes);
				
				$message.=$ProductPriceBeforeTaxes;

				$message.='</td>
                <td align="center" class="formCellNosides">&nbsp;</td>
                <td align="right" valign="top" class="formCellNosidesRA"><b>$' . $SousTotalBeforeTaxes;
				//$message.=$listItem[order_product_price];
				$message.='</b></td>
              </tr>';
						
						//END RE SECTION
											
							$LeOrderNum = 	$listItem[order_num];								
																} //END WHILE
																
				    $queryTotaltoPay = "SELECT order_total, order_quantity, order_product_price  FROM orders WHERE order_num = " . $LeOrderNum;
					$resultTotaltoPay = mysqli_query($con,$queryTotaltoPay)		or die  ('I cannot select items because 9: ' . mysqli_error($con));
					$TotalaPayer = 0;
					while ($DataTotaltoPay=mysqli_fetch_array($resultTotaltoPay,MYSQLI_ASSOC)){
					$TotalaPayer = $TotalaPayer + $DataTotaltoPay[order_product_price] * $DataTotaltoPay[order_quantity] ;
					//echo '<br>Total a payer : ' . $TotalaPayer . '+'.  $DataTotaltoPay[order_product_price] *  $DataTotaltoPay[order_quantity];
					}
					
					$SousTotalBeforeTaxes =  $TotalaPayer;
					$SousTotalBeforeTaxes=money_format('%.2n',$SousTotalBeforeTaxes);
						
				    $TotalaPayer = $TotalaPayer + $order_shipping_cost;
					$TotalaPayer = $TotalaPayer-$additional_dsc+$extra_product_price;
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

$message.='<tr><td align="left" class="formCellNosides"><b>Total</b>:';
$message.='</td>';
$message.='<td align="right" valign="middle" class="formCellNosidesRA">';
$message.='<b>$'.$SousTotalBeforeTaxes.'</b>';
$message.='</td></tr>';

$querySP  = "SELECT special_instructions  FROM orders WHERE order_num = " . $LeOrderNum;
$resultSP = mysqli_query($con,$querySP)		or die  ('I cannot select items because 10: ' . mysqli_error($con));
$DataSP   = mysqli_fetch_array($resultSP,MYSQLI_ASSOC);
$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">';
$message.='<tr><td align="left" class="formCellNosides"><b>Special Instructions</b>:';
$message.='</td>';
$message.='<td align="right" valign="middle" class="formCellNosidesRA">';
$message.='<b>'.$DataSP[special_instructions].'</b>';
$message.='</td></tr>';

$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr>';
$message.='<td align="left" class="formCellNosides">Shipping:';
$message.=$order_shipping_method;
$message.='</td>';
$message.='<td align="right" valign="middle" class="formCellNosidesRA">$';
$message.=$order_shipping_cost;
$message.='</td></tr>';
		
if ($extra_product_price!=0){//INCLUDE EXTRA ITEM
$message.="<tr >
          <td width=\"524\" align=\"left\"  class=\"formCellNosides\">Additional Item:&nbsp;".$extra_product;
$message.="<td width=\"100\" align=\"right\" valign=\"middle\" class=\"formCellNosidesRA\">&nbsp;".$extra_product_price."</td> </tr>";
}
			  
 if ($additional_dsc!=0){
	$message.="<tr >";
    $message.="<td width=\"524\" align=\"left\"  class=\"formCellNosides\">Additional Discount (".$discount_type.$additional_dsc.")</td>";
    $message.="<td width=\"100\" align=\"right\" valign=\"middle\" class=\"formCellNosidesRA\"><b>-";
	
	if($discount_type=="$"){
					$totalDiscount=money_format('%.2n',$additional_dsc);
					}
	else if ($discount_type=="%"){
					$totalDiscount=money_format('%.2n',$totalPriceDsc*($additional_dsc/100));
				}
	$message.=$totalDiscount; 
	$message.= "</b></td></tr>";
			  
			  }
			  
$message.='<tr ><td width="524" align="left" class="Subheader">Order Total with All Discounts</td>';
$message.='<td width="100" align="right" valign="middle" class="total"><b>$';
$extra_product_price=0;	

				
$totalPriceDsc=money_format('%.2n',$totalPriceDsc+$order_shipping_cost-$totalDiscount+$extra_product_price);

//echo '<br>Total price totalPriceDsc: ' .$totalPriceDsc ;
//echo '<br>+ order_shipping_cost :' .$order_shipping_cost ;
//echo '<br> - totalDiscount :' .$totalDiscount ;
//echo '<br>extra_product_price :' .$extra_product_price ;

if ($sendPrices=="true"){
$message.=$TotalaPayer." ".$currency; 
}else{
$message.='n/a';
}


$message.='</b></td></tr><tr><td  style="font-size:8px;" class="Subheader">Effective on April 1st 2013:
<b>1.5% charge on invoice not paid within 30 days following the date of statement';
$message.='</b></td></tr></table>';
$message.="</body></html>";
if ($printit) {
	echo $message;
	die();
}
}//END OF FUNCTION

?>