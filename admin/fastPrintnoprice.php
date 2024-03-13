<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//INCLUSIONS
require_once(__DIR__.'/../constants/aws.constant.php');
require_once(__DIR__.'/../constants/url.constant.php');
include "../sec_connectEDLL.inc.php";
include("admin_functions.inc.php");
//include('../phpmailer_email_functions.inc.php');

session_start();

$Displayprice="true";


function sendPrescriptionConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$userData,$sendPrices,$printit){//PRESCRIPTION CONFIRMATION
include "../sec_connectEDLL.inc.php";
$bg_query="select bg_name from buying_groups WHERE primary_key='$userData[buying_group]'";//GET BUYING GROUP NAME
$bg_result=mysqli_query($con,$bg_query)		or die  ('I cannot select items because: ' . mysqli_error($con));
$bgData=mysqli_fetch_array($bg_result,MYSQLI_ASSOC);

$EstEyelation = 'no';

$querySwissEdgingBarcode  = "SELECT swiss_edging_barcode FROM swiss_edging_barcodes WHERE order_num = $orderNum";
$resultSwissEdgingBarcode = mysqli_query($con,$querySwissEdgingBarcode)		or die  ('I cannot select items because 3: ' . mysqli_error($con));
$NombredeResultat 		  = mysqli_num_rows($resultSwissEdgingBarcode);
//Si on a un Swiss Edging Barcode
if ($NombredeResultat > 0)
{
	$DataSwissEdgingBarcode   = mysqli_fetch_array($resultSwissEdgingBarcode,MYSQLI_ASSOC);
	$SwissEdgingBarcode 	  =  $DataSwissEdgingBarcode[swiss_edging_barcode];
	// include Barcode39 class
	include "../barcodes/Barcode39.php";
	// set object
	$bc = new Barcode39($SwissEdgingBarcode);
	// set text size
	$bc->barcode_text_size = 5;
	// set barcode bar thickness (thick bars)
	$bc->barcode_bar_thick = 4;
	// set barcode bar thickness (thin bars)
	$bc->barcode_bar_thin = 2;
	// save barcode GIF file
	$bc->draw("$SwissEdgingBarcode".".gif");
	$barcode= constant('DIRECT_LENS_URL')."/admin/".$SwissEdgingBarcode.".gif";

}//End if there is a swiss edging barcode	
	
// include Barcode39 class
//require "../barcodes/Barcode39.php";
// set object
$bc2 = new Barcode39($orderNum);
// set text size
$bc2->barcode_text_size = 5;
// set barcode bar thickness (thick bars)
$bc2->barcode_bar_thick = 4;
// set barcode bar thickness (thin bars)
$bc2->barcode_bar_thin = 2;
// save barcode GIF file
//$bc->draw("$orderNum". "1"."gif");
$bc2->draw("$orderNum".".gif");	
	
$barcodeOrderNum= constant('DIRECT_LENS_URL')."/admin/".$orderNum.".gif";	

	

	$query="select * from accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$result=mysqli_query($con,$query)		or die  ('I cannot select items because 3: ' . mysqli_error($con));
		
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
		$pl_text="IFC Optical CLUB";}
	elseif ($listItem['product_line']=="ifcclubca")
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/ifcopticclub.ca/design_images/ifc-ca-large.jpg";
		$pl_text="IFC.ca";}
	elseif ($listItem['product_line']=="ifcclubus")
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/ifcopticclub.ca/design_images/ifc-ca-large.jpg";
		$pl_text="IFC Optic CLUB.us";}
	elseif (($listItem['product_line']=="safety") && (strtolower($listItem['language'])=="french"))
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/safety/images/LOGO_SAFE_FR.jpg";
		$pl_text="SAFE";}
	elseif (($listItem['product_line']=="safety") && (strtolower($listItem['language'])=="english"))
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/safety/images/LOGO_SAFE_EN.jpg";
		$pl_text="SAFE";}
	elseif ($listItem['product_line']=="eye-recommend")
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/direct-lens/logos/direct-lens_logo.gif";
		$pl_text="Direct-Lens Prestige";}
	else
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/direct-lens/logos/lensnet_logo.gif";
		$pl_text="LensNet";}
		
		
	
		if (($listItem['main_lab']== 66) || ($listItem['main_lab']== 67)){  //Main lab des entrepots
			$CompteEdll = 'oui';
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


//if ($printit) $message .= "<script type='text/javascript'>window.onload = function() {window.print();window.close();}</script>";
$message .= '</head>';

	$message.="<link href=\"/dl.css\" rel=\"stylesheet\" type=\"text/css\" />";

	$message.='<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr>';
	
	if (($dl_logo_file <> "nologo") && ($CompteEdll <> 'oui'))
	$message.='<td align="left"><img src="../logos/'.$logo_file.'"/></td>';
	
	//if ($dl_logo_file == "https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/ifcopticclub.ca/design_images/edll_2018.png"){
		$message.= '<td align="center"><img src="'.$barcodeOrderNum.'" width="190" height="50" /><img src="'.$dl_logo_file.'" width="150"/></td>';
	//}
	
	$message .='</td><td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="'.$barcode.'" width="190" height="50" /><p>GST:&nbsp;808437370 RT 0001&nbsp;<br>&nbsp;&nbsp;QST:&nbsp;1227025278 TQ 0001</p></td></tr></table>';//ABSOLUTE PATH TO THE BARCODE IMAGE
	
	$query="select po_num, order_date_processed, order_date_shipped, redo_order_num,  order_patient_first ,	order_patient_last  from orders WHERE order_num='$orderNum'"; //GET ORDER PO NUMBER AND REDO ORDER NUMBER
	$result=mysqli_query($con,$query) or die  ('I cannot select items because 4: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	
	if ($EstEyelation == 'no')
	$referenceGrm = $listItem[order_patient_first]	 . $listItem[order_patient_last]; 
		
		
	$message.='<table width="650" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td align="left">';
	$message.='<td><div class="header2">Your '.$pl_text.' Order #:'.$orderNum;
	
	if ($listItem[redo_order_num]!=0){
	$message.="R (".$listItem[redo_order_num].")";	
	}
	
	
	//SI LE prescript lab est KNR (73), afficher la référence KNR ici!
	$queryVerifierPrescriptLab 		= "SELECT knr_ref_num, prescript_lab FROM orders WHERE order_num = $orderNum";
	//echo $queryVerifierPrescriptLab ;
	$ResultVerifierPrescriptLab 	= mysqli_query($con,$queryVerifierPrescriptLab)		or die  ('I cannot select items because 3: ' . mysqli_error($con));
	$DataPrescriptLab   		  	= mysqli_fetch_array($ResultVerifierPrescriptLab,MYSQLI_ASSOC); 

	if (($DataPrescriptLab[knr_ref_num]<>'')&&($DataPrescriptLab[prescript_lab]==73)){
	//Le laboratoire qui fabrique est KNR, afficher la référence KNR
	$message.='  <font  size="-1" color="#000000"> <b>KNR Ref #:'.	$DataPrescriptLab[knr_ref_num] . '</b></font>';
	}
	
	if ($EstEyelation == 'no')
	$message.="</div></td></tr></table>";
	else
	$message.="</div></td></tr></table>";
	
		$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr><td align="left" class="formCellNosides">PO Order #: ';
		$message.=$listItem[po_num] .  '&nbsp;&nbsp;&nbsp;&nbsp; Order Date:'. $listItem[order_date_processed];
		$date_processed=$listItem[order_date_processed];
		if ($listItem['order_date_shipped'] != '0000-00-00')
		{
		$message.= '&nbsp;&nbsp;&nbsp;&nbsp; Shipping Date:'. $listItem[order_date_shipped] ;
		} 
		$message.='</td></tr></table>';
	
	$query="select * from accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$result=mysqli_query($con,$query) or die  ('I cannot select items because 5: ' . mysqli_error($con));
		
	$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$usercount=mysqli_num_rows($result);
	
	
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#D7E1FF" class="formCellNosides">ACCOUNT INFORMATION </td></tr>';
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
	
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#D7E1FF" class="formCellNosides">SHIPPING ADDRESS </td></tr><tr ><td width="130" align="right" class="formCellNosides">Address 1:</td><td width="520" class="formCellNosides"><strong>';
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
			
$query="SELECT * from orders WHERE user_id='$user_id' and order_num='$orderNum' and order_product_type='exclusive' ORDER by order_item_date";//SELECT ALL OPEN PRESCRIPTION ORDERS
			$result=mysqli_query($con,$query) or die  ('I cannot select items because 6: ' . mysqli_error($con).$query);
			$usercount=mysqli_num_rows($result);
			if ($usercount != 0){
								
					while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
						
					$bl_query="SELECT * from additional_discounts WHERE orders_id='$listItem[primary_key]'";//GET BUYING LEVEL DISCOUNT
					$bl_result=mysqli_query($con,$bl_query)or die  ('I cannot select bl items because: ' . mysqli_error($con).$bl_query);
					$bl_listItem=mysqli_fetch_array($bl_result,MYSQLI_ASSOC);
					$buying_level_dsc=$bl_listItem[buying_level_discount];
						
					$e_query="SELECT * from extra_product_orders WHERE order_id='$listItem[primary_key]'";//GET EXTRA PRODUCT PRICES
					$e_result=mysqli_query($con,$e_query)				or die  ('I cannot select items because 7: ' . mysqli_error($con));
					$e_usercount=mysqli_num_rows($e_result);
					$e_total_price=0;
					$e_products_string="";
					$e_products_string_na="";
					$e_order_string_engraving="";
					$e_order_string_tint="";
					$e_order_string_edging="";
					$e_order_string_frame="";
					$e_order_string_Large_frame="";
					//COLLECT FRAME DATA from orders table into string to be overwritten if there is edging data
					$e_order_string_edging="<b>Type: </b>".$listItem[frame_type]." ";
					$e_order_string_edging.="<b>Eye: A:</b>".$listItem[frame_a]." ";
					$e_order_string_edging.="<b>B: </b>".$listItem[frame_b]." ";
					$e_order_string_edging.="<b>ED: </b>".$listItem[frame_ed]." ";
					$e_order_string_edging.="<b>DBL: </b>".$listItem[frame_dbl]." ";
				
					if ($e_usercount !=0){
				while ($e_listItem=mysqli_fetch_array($e_result,MYSQLI_ASSOC)){
						//$e_total_price=$e_total_price+$e_listItem[price]+$e_listItem[high_index_addition];
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
								
								
						if ($e_listItem[category]=="Mirror"){	
								$e_products_string.="<br />Mirror: ".$e_listItem[price];
								$e_products_string_na.="<br />Mirror: n/a";
								$e_order_string_tint.="<b>Mirror: </b>";
								$DetailMirror .="<b>Mirror</b>: ".  $e_listItem['tint_color'] ;
							}//End Mirror
							
						if ($e_listItem[category]=="Large frame"){
								//$e_order_string_Large_frame.="<br />Large frame fee: ".$e_listItem[price];
								}
								
							if ($e_listItem[category]=="Cylinder Over Range"){
								//$e_products_string.="<br />Cylinder Over Range: ".$e_listItem[price];
								$e_products_string_na.="<br />Cylinder Over Range: n/a";
								$e_order_string_tint="<b>Cylinder Over Range: </b>";
							}//END Dust bar
			

							if ($e_listItem[category]=="Engraving"){
								
								//$e_products_string.="<br />Engraving: ".$e_listItem[price];
								$e_products_string_na.="<br />Engraving: n/a";
								
								$e_order_string_engraving="<b>Engraving: </b>".$e_listItem[engraving]." ";}
							if ($e_listItem[category]=="Tint"){
								
								//$e_products_string.="<br />Tint: ".$e_listItem[price];
								$e_products_string_na.="<br />Tint: n/a";
								
								$e_order_string_tint="<b>Tint: </b>".$e_listItem[tint].": ";
								if ($e_listItem[tint]=="Solid")
									{ $e_order_string_tint.= $e_listItem[from_perc]."% <b>Color:</b> ".$e_listItem[tint_color];}
								else
									{$e_order_string_tint.= $e_listItem[from_perc]."% to ".$e_listItem[to_perc] ."% <b>Color:</b> ".$e_listItem[tint_color];}
								}//END IF TINT
							if ($e_listItem[category]=="Prism"){
								//$e_products_string.="<br />Prism: ".$e_listItem[price];
								$e_products_string_na.="<br />Prism: n/a";
								}
							if ($e_listItem[category]=="Frame"){
								//$e_products_string.="<br />Frame: ".$e_listItem[price];
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
						//$e_total_price=money_format('%.2n',$e_total_price);
					}//END EXTRA PRODUCT SECTION
					
					//$order_shipping_method=$listItem[order_shipping_method];
					//$currency=$listItem[currency];
					//$additional_dsc=$listItem[additional_dsc];
					//$discount_type=$listItem[discount_type];
					//$extra_product=$listItem[extra_product];
					//$extra_product_price=$listItem[extra_product_price];
					//$order_shipping_cost=$listItem[order_shipping_cost];
					//$itemSubtotal=0;
					//$over_range=$listItem[order_over_range_fee];
					//$coupon_dsc=$listItem[coupon_dsc];
					//Charles
					//$itemSubtotal=$listItem[order_quantity]*($listItem[order_product_price]+$e_total_price)+$over_range+$extra_product_price-$coupon_dsc;
					//$itemSubtotal=$listItem[order_quantity]*($listItem[order_product_price]+$e_total_price)+$over_range-$coupon_dsc;
					//$totalPrice=$totalPrice+$itemSubtotal;
					
					//$itemSubtotalDsc=$listItem[order_quantity]*($listItem[order_product_discount]+$e_total_price)+$over_range+$extra_product_price-$coupon_dsc+$buying_level_dsc;
					
					//$totalPriceDsc=$totalPriceDsc+$itemSubtotalDsc;
					
					//$prescrQuantity=$prescrQuantity+$listItem[order_quantity];
					//$itemSubtotal=money_format('%.2n',$itemSubtotal);
					
						
					$queryProduit = "Select order_product_id, order_from from orders where order_num = $orderNum";
					$resultProduit  =mysqli_query($con,$queryProduit)			or die ("Could not find order num");
					$listItemProduit=mysqli_fetch_array($resultProduit,MYSQLI_ASSOC);
					$PK_Produit = $listItemProduit['order_product_id'];
					$Order_From = $listItemProduit['order_from'];
				
			
					$queryProductCode = "SELECT product_code  FROM exclusive WHERE primary_key  = $PK_Produit";
					
					$resultProductCode  =mysqli_query($con,$queryProductCode)			or die ("Could not find product code");
					$listItemProductCode=mysqli_fetch_array($resultProductCode,MYSQLI_ASSOC);
					$Product_Code = $listItemProductCode['product_code'];
					
					
					
					$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
         <tr >
                <td colspan="6" bgcolor="#D7E1FF" class="formCellNosides">Product - ';
				$message.=$listItem[order_product_name] . '<br>Product&nbsp;Code:<b>' . $Product_Code . '</b>' ;
				
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
				$message.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>EYE</b>:&nbsp;$listItem[eye]</td>";
            $message.='  </tr>
              <tr >
                <td bgcolor="#FFFFFF" class="formCellNosidesRA"><b>Patient:<b></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides">';
				$message.=$listItem[order_patient_first].'&nbsp;'.$listItem[order_patient_last].'</td>';
				
				$message.='<td bgcolor="#FFFFFF" class="formCellNosidesRA"><b>Tray Num:<b></td>';
				$message.='<td align="center" bgcolor="#FFFFFF" class="formCellNosides">'.$listItem[tray_num].'</td>';
				$traynum = $listItem[tray_num];
				
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
              </tr>';
			  
			  
			   if  (($listItem[eye]=='Both') ||  ($listItem[eye]=='R.E.'))
				 { 
					 $message.=' <tr >
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
					$message.='</td>';
				}else{
					 $message.=' <tr >
					<td align="right" class="formCellNosides">R.E.</td>
					<td align="center" class="formCellNosides">-</td>
					<td align="center" class="formCellNosides">-</td>
					<td align="center" class="formCellNosides">-</td>
					<td align="center" class="formCellNosides">-</td>
					<td align="center" class="formCellNosides">';
					$message.="-&nbsp;-&nbsp;&nbsp;-&nbsp;-";
					$message.='</td>';
				}
				
				
				
               $message.= '<td rowspan="8" align="center" valign="top" class="formCellNosidesCenter">';
				$message.=$listItem[order_quantity];
				$message.='</td>
                <td rowspan="8" align="right" valign="top" class="formCellNosidesRA"><b>';
				
				
			   $queryEntryFee  = "select entry_fee FROM orders WHERE order_num  = " . $listItem[order_num];
			   $resultEntryFee = mysqli_query($con,$queryEntryFee)	or die ("Could not select items");
			   $DataEntryFee   = mysqli_fetch_array($resultEntryFee,MYSQLI_ASSOC);
			   
			   $queryWarranty  = "select warranty FROM orders WHERE order_num  = " . $listItem[order_num];
			   $resultWarranty = mysqli_query($con,$queryWarranty)	or die ("Could not select items");
			   $DataWarranty   = mysqli_fetch_array($resultWarranty,MYSQLI_ASSOC);
				
			
			    $message.='n/a';
				$message.='</b>';
				

				
				if ($e_order_string_frame!=""){
					$e_order_string_edging=$e_order_string_frame;}
					
				

				$message.='</td>
              </tr>
              <tr >
                <td colspan="6" align="left" class="formCellNosides"><strong>Dist.
                    PD:</strong>';
					if ($listItem[eye]=='Both') $message.= $listItem[re_pd];
					if ($listItem[eye]=='R.E.') $message.= $listItem[re_pd];
					if ($listItem[eye]=='L.E.') $message.= ' -';
					$message.='&nbsp;&nbsp;&nbsp;<strong>Near
                    PD:</strong>';
					
					if ($listItem[eye]=='Both') $message.= $listItem[re_pd_near];
					if ($listItem[eye]=='R.E.') $message.= $listItem[re_pd_near];
					if ($listItem[eye]=='L.E.') $message.= ' -';
					  
					$message.= '&nbsp;&nbsp;&nbsp;<strong>Height:</strong>';
					if ($listItem[eye]=='Both') $message.= $listItem[re_height];
					if ($listItem[eye]=='R.E.') $message.= $listItem[re_height];
					if ($listItem[eye]=='L.E.') $message.= ' -';
 
					$message.= ' </td>
                </tr>';
             
			 
			 
			 if  (($listItem[eye]=='Both') ||  ($listItem[eye]=='L.E.'))
			 { 
				$message.=	'<tr>
                <td align="right" class="formCellNosides">
                  L.E.</td>
                <td align="center" class="formCellNosides">'.$listItem[le_sphere].' </td>
                <td align="center" class="formCellNosides">'.$listItem[le_cyl].' </td>
                <td align="center" class="formCellNosides">'.$listItem[le_axis].'</td>
                <td align="center" class="formCellNosides">'.$listItem[le_add].' </td>
                <td align="center" class="formCellNosides">'.$listItem[le_pr_ax] ."&nbsp;".$listItem[le_pr_io]."&nbsp;&nbsp;".$listItem[le_pr_ax2]."&nbsp;".$listItem[le_pr_ud].'</td>
             </tr>';
				}else{
				$message.=	'<tr>
                <td align="right" class="formCellNosides">
                  L.E.</td>
                <td align="center" class="formCellNosides">-</td>
                <td align="center" class="formCellNosides">-</td>
                <td align="center" class="formCellNosides">-</td>
                <td align="center" class="formCellNosides">-</td>
                <td align="center" class="formCellNosides">-</td>
             </tr>';
				}
				

				
            $message.='<tr >
                <td colspan="6" align="left" class="formCellNosides"><strong>Dist.
                    PD:</strong>';
					if ($listItem[eye]=='Both') $message.= $listItem[le_pd];
					if ($listItem[eye]=='L.E.') $message.= $listItem[le_pd];
					if ($listItem[eye]=='R.E.') $message.= ' -';
					$message.='&nbsp;&nbsp;&nbsp;<strong>Near
                    PD:</strong>';
					
					if ($listItem[eye]=='Both') $message.= $listItem[le_pd_near];
					if ($listItem[eye]=='L.E.') $message.= $listItem[le_pd_near];
					if ($listItem[eye]=='R.E.') $message.= ' -';
					  
					$message.= '&nbsp;&nbsp;&nbsp;<strong>Height:</strong>';
					if ($listItem[eye]=='Both') $message.= $listItem[le_height];
					if ($listItem[eye]=='L.E.') $message.= $listItem[le_height];
					if ($listItem[eye]=='R.E.') $message.= ' -';
 
					$message.= ' </td>
                </tr>
				
					<tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>PT: </strong>'.$listItem[PT].' &nbsp;&nbsp;&nbsp;<strong>PA: </strong>'.$listItem[PA].' &nbsp;&nbsp;&nbsp;<strong>Vertex:</strong> '.$listItem[vertex].' </td>
                </tr>';
				
				$message.='<tr><td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>Thickness: </strong>';
				
				//.$listItem[PT].' &nbsp;&nbsp;&nbsp;<strong>PA: </strong>'.$listItem[PA].' &nbsp;&nbsp;&nbsp;<strong>Vertex:</strong> '.$listItem[vertex].' </td>
              
			   if ($listItem['RE_CT'] <> '') 
			   	   $message.=  'RE:' .$listItem['RE_CT'] . ' CT' . '&nbsp;&nbsp;LE:' .$listItem['LE_CT'] . ' CT';  
			   elseif($listItem['RE_ET'] <> '') 
				   $message.=  'RE:'. $listItem['RE_ET'] . ' ET' . '&nbsp;&nbsp;LE:' .$listItem['LE_ET'] . ' ET';  
			  
			   

				
				     $message .='</tr><tr>
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>FRAME:
				
				  &nbsp; </strong>'.$e_order_string_edging. '</td>
                  
              </tr>
			  
              <tr>
			    <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>OTHER:</strong> 
				'.$e_order_string_engraving.$e_order_string_tint. ' ' . $DetailMirror .'  <b>'.$listItem[UV400].'</b>
				</td>
				</tr>';
					 $message.='<tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>Manufacturing Instructions:</b>&nbsp;'.$listItem[special_instructions].' </td>
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
			 
			 
			 
			 
	$queryProductLine="select product_line from accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$resultProductline=mysqli_query($con,$queryProductLine)		or die  ('I cannot select items because 8: ' . mysqli_error($con));
	$DataProductline=mysqli_fetch_array($resultProductline,MYSQLI_ASSOC);
			 
			if (($DataProductline[product_line]=='lensnetclub') || ($DataProductline[product_line]=='aitlensclub')){
			 
				   $message.='<tr><td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>Extra Warranty :</b>';//;.$listItem[warranty];

				switch($DataWarranty[warranty])
				{
					
					case '0': 	$message .= 'Year (No warranty purchased)</td> </tr>';			  break;
					case '1': 	$message .= 'Year (price included in product price)</td> </tr>';  break;
					case '2': 	$message .= 'Years (price included in product price)</td> </tr>'; break;
					case 'gold':$message .= 'Gold Warranty</td> </tr>';		  break;
					case '': 	$message .= '&nbsp;</td> </tr>';							      break;
				}
				
	 
			 }//End if (($DataProductline[product_line]=='lensnetclub') || ($DataProductline[product_line]=='aitlensclub')){
			  
			  
              
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

	
		$message.="<tr >
            <td width=\"524\" align=\"left\"  class=\"formCellNosides\">Additional Item:&nbsp;".$extra_product;
		$message.="<td width=\"100\" align=\"right\" valign=\"middle\" class=\"formCellNosidesRA\">&nbsp;";
		
		if ($sendPrices=="true"){
			$message.=$extra_product_price;}
		else{
			$message.='n/a';
		}
		
		$message.="</td> </tr>";
		

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
			  
$message.='<tr ><td width="524" align="left" class="formCellNosides">Order Total with All Discounts</td>';
$message.='<td width="100" align="right" valign="middle" class="total"><b>';
$message.='n/a';


	$message.='</table>';		
	$message.="</body></html>";

	echo $message;

}//End function




function sendStockConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$userData,$printit){//STOCK BULK AND TRAY CONFIRMATION
include "../sec_connectEDLL.inc.php";
//$send_to_address="directl-config@interpage.net";

if ($printit) {
$sendPrices = true;
}
else{
$sendPrices = false;
}


	$bg_query="select bg_name from buying_groups WHERE primary_key='$userData[buying_group]'";//GET BUYING GROUP NAME
	$bg_result=mysqli_query($con,$bg_query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$bgData=mysqli_fetch_array($bg_result,MYSQLI_ASSOC);

	$barcode= constant('DIRECT_LENS_URL')."/barcodes/".$orderNum.".gif";
	
	$query="select * from accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
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
		$pl_text="IFC.ca";}
	elseif ($listItem['product_line']=="ifcclubus")
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/ifcopticclub.ca/design_images/ifc-ca-large.jpg";
		$pl_text="IFC Optic CLUB.us";}
	elseif ($listItem['product_line']=="eye-recommend")
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/direct-lens/logos/direct-lens_logo.gif";
		$pl_text="IFC Optic CLUB.us";}
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
//if ($printit) $message .= "<script type='text/javascript'>window.onload = function() {window.print();window.close();}</script>";
$message .= '</head>';
	$message.='<link href="/dl.css" rel="stylesheet" type="text/css" />';

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

			$result2=mysqli_query($con,$query2)or die  ('I cannot select items because: ' . mysqli_error($con));
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
						
						//$totalPrice=$totalPrice+$itemSubtotal;
						//$totalStockPrice=$totalStockPrice+$itemSubtotal;
						
						//$totalPriceDsc=$totalPriceDsc+$itemSubtotalDsc;
						//$totalStockPriceDsc=$totalStockPriceDsc+$itemSubtotalDsc;
						//$itemSubtotalDsc=0;
						
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

			$result2=mysqli_query($con,$query2) or die  ('I cannot select items because: ' . mysqli_error($con));
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


$querySI  = "SELECT special_instructions FROM orders WHERE order_num = $orderNum";
$resultSI = mysqli_query($con,$querySI)		or die  ('I cannot select items because 9: ' . mysqli_error($con));
$DataSI=mysqli_fetch_array($resultSI,MYSQLI_ASSOC);

$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr>';
$message.='<td align="left" class="formCellNosides">Manufacturing Instructions</td>';
$message.='<td align="left" class="formCellNosidesRA">';
$message.=$DataSI[special_instructions];
$message.='</td></tr></table>';

$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr>';
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


$message.='</b></td></tr><tr><td class="Subheader">Effective on April 1st 2013:
<b>1.5% charge on invoice not paid within 30 days following the date of statement';
$message.='</b></td></tr></table>';
$message.="</body></html>";
if ($printit) {
	echo $message;
	die();
}
}//END OF FUNCTION




function sendFrameStockConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$userData,$printit){//STOCK BULK AND TRAY CONFIRMATION
include "../sec_connectEDLL.inc.php";
if ($printit) {
$sendPrices = true;
}
else{
$sendPrices = false;
}

	$bg_query="select bg_name from buying_groups WHERE primary_key='$userData[buying_group]'";//GET BUYING GROUP NAME
	$bg_result=mysqli_query($con,$bg_query)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$bgData=mysqli_fetch_array($bg_result,MYSQLI_ASSOC);

	$barcode= constant('DIRECT_LENS_URL')."/barcodes/".$orderNum.".gif";
	
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
	if ($printit) $message .= "<script type='text/javascript'>window.onload = function() {window.print();window.close();}</script>";
$message .= '</head>';
	$message.='<link href="/dl.css" rel="stylesheet" type="text/css" />';

	$message.='<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img width="250" src="logos/'.$logo_file.'"/><td align="center"><img src="'.$dl_logo_file.'" width="200" height="60" /></td></td><td align="right"><img src="'.$barcode.'" width="190" height="50" /><p>GST:&nbsp;808437370 RT 0001&nbsp;<br>&nbsp;&nbsp;QST:&nbsp;1227025278 TQ 0001</p></td></tr></table>';//ABSOLUTE PATH TO THE BARCODE IMAGE
	
		$message.='<table width="650" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td align="left">';
	$message.='<td><div class="header2">Your'.$dl_text.' Order #:'.$orderNum.'</div></td></tr></table>';
	
	$query="select po_num from orders WHERE order_num='$orderNum'"; //GET ORDER PO NUMBER
		$result=mysqli_query($con,$query)		or die  ('I cannot select items because: ' . mysqli_error($con));
		
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


$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr>';
$message.='<td align="left" class="formCellNosides"><b>SubTotal</b>:';
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

//if ($sendPrices=="true"){
//$message.=$TotalaPayer." ".$currency; 
//}else{
$message.='n/a';
//}


$message.='</b></td></tr><tr><td class="Subheader">Effective on April 1st 2013:
<b>1.5% charge on invoice not paid within 30 days following the date of statement';
$message.='</b></td></tr></table>';
$message.="</body></html>";
if ($printit) {
	echo $message;
	die();
}
}//END OF FUNCTION










function getUserData($order_num){
include "../sec_connectEDLL.inc.php";
	$query="SELECT user_id FROM orders WHERE order_num=$order_num";
	$result=mysqli_query($con,$query) or die  ('I cannot select items because 9: ' . mysqli_error($con));
		
	$orderItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	$user_id=$orderItem[user_id];

	$query="select * from accounts WHERE user_id='$user_id'";
	$result=mysqli_query($con,$query) or die  ('I cannot select items because 10: ' . mysqli_error($con));
		
	$userData=mysqli_fetch_array($result,MYSQLI_ASSOC);

	return $userData;
}//End function getUserData

	$orderNum = $_REQUEST[order_num];
	$queryOrder="SELECT * FROM orders WHERE order_num ='$orderNum '";
	$resultOrder=mysqli_query($con,$queryOrder)		or die  ('I cannot select items because 1: ' . mysqli_error($con));
	$DataOrder=mysqli_fetch_array($resultOrder,MYSQLI_ASSOC);
	
	$queryLab="SELECT lab_email, logo_file FROM labs WHERE primary_key='". $DataOrder[lab] . "'";
	$resultLab=mysqli_query($con,$queryLab)		or die  ('I cannot select items because 2: ' . mysqli_error($con));
	$DataLab=mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
	$fromAddress =$DataLab[lab_email]; 
	$logo_file = $DataLab[logo_file];
	$user_id = $DataOrder[user_id];
	$send_to_address="dbeaulieu@direct-lens.com";
	$userData=getUserData($orderNum);
	$order_product_type =$DataOrder[order_product_type];

 

switch($order_product_type){
	case 'exclusive':        sendPrescriptionConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$userData,$Displayprice,true);  	 break;
	case 'stock_tray':   	 sendStockConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$userData,true); 	   				     break;
	case 'stock':       	 sendStockConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$userData,true); 						 break;
	case 'stock_bulk':   	 sendStockConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$userData,true);  						 break;
	case 'frame_stock_tray': sendFrameStockConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$userData,true);  					 break;
}

?>