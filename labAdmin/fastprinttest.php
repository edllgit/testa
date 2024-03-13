<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
require_once(__DIR__.'/../constants/url.constant.php');
include("../Connections/sec_connect.inc.php");

if ($_POST[pr]=="yes")
{
$Displayprice="true";
}else{
$Displayprice="false";
}


function sendPrescriptionConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$userData,$sendPrices,$printit){//PRESCRIPTION CONFIRMATION

	$bg_query="select bg_name from buying_groups WHERE primary_key='$userData[buying_group]'";//GET BUYING GROUP NAME
	$bg_result=mysql_query($bg_query)		or die  ('I cannot select items because: ' . mysql_error());
	$bgData=mysql_fetch_array($bg_result);

	$barcode="../barcodes/".$orderNum.".gif";
	
	$query="select * from accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$result=mysql_query($query)		or die  ('I cannot select items because 3: ' . mysql_error());
		
	$listItem=mysql_fetch_array($result);
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
		$pl_text="IFC Optical CLUB";}
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
$message .= '<style type="text/css" media="print">
        @page 
        {
            size: auto;   /* auto is the initial value */
            margin: 0mm;  /* this affects the margin in the printer settings */
        }

        body 
        {
            background-color:#FFFFFF; 
            border: solid 1px black ;
            margin: 0px;  /* this affects the margin on the content before sending to printer */
       }
    </style></head>';
	$message.="<link href=\"http://www.direct-lens.com/dl.css\" rel=\"stylesheet\" type=\"text/css\" />";

	$message.='<table width="530" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img width="100" src="../logos/'.$logo_file.'"/><td align="center"><img src="'.$dl_logo_file.'" width="100" height="30" /></td></td><td align="right"><img src="'.$barcode.'" width="127" height="34" /></td></tr></table>';//ABSOLUTE PATH TO THE BARCODE IMAGE
	
	$query="select po_num, order_date_processed, order_date_shipped, redo_order_num from orders WHERE order_num='$orderNum'"; //GET ORDER PO NUMBER AND REDO ORDER NUMBER
	$result=mysql_query($query)		or die  ('I cannot select items because 4: ' . mysql_error());
	$listItem=mysql_fetch_array($result);
		
	$message.='<table width="530" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td align="left">';
	$message.='<td><div class="Subheader">Your '.$pl_text.' Order #:'.$orderNum;
	
	if ($listItem[redo_order_num]!=0){
	$message.="R (".$listItem[redo_order_num].")";	
	}
	
	$message.='</div></td></tr></table>';
	
		$message.='<table width="530" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr><td align="left" class="formCellNosides">PO Order #: ';
		$message.=$listItem[po_num] .  '&nbsp;&nbsp;&nbsp;&nbsp; Order Date:'. $listItem[order_date_processed];
		if ($listItem['order_date_shipped'] != '0000-00-00')
		{
		$message.= '&nbsp;&nbsp;&nbsp;&nbsp; Shipping Date:'. $listItem[order_date_shipped] ;
		} 
		$message.='</td></tr></table>';
	
	$query="select * from accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$result=mysql_query($query)
		or die  ('I cannot select items because 5: ' . mysql_error());
		
	$listItem=mysql_fetch_array($result);
	$usercount=mysql_num_rows($result);
	
	
	$message.='<table width="530" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#000099" class="tableHead">ACCOUNT INFORMATION </td></tr>';
	$message.='<tr ><td  width="115" align="right" class="formCellNosides">Name on Account :</td>';
    $message.='<td width="530" class="formCellNosides"><strong>';
	$message.=$listItem[title].' '.$listItem[first_name].' '.$listItem[last_name];
	$message.='</strong></td></tr>';
	$message.='<tr ><td width="115" align="right" class="formCellNosides">Account # :</td>';
    $message.='<td width="530"  class="formCellNosides"><strong>';
	$message.=$listItem[account_num];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Company:</td><td width="530" class="formCellNosides"><strong>';
	$message.=$listItem[company];
	$message.='</strong> </td></tr><tr ><td align="right" class="formCellNosides">Buying Group: </td><td class="formCellNosides"><strong>';
	$message.=$bgData[bg_name];
	$message.='</strong></td></tr></table>';
	
	$message.='<table width="530" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
	<tr>
		<td colspan="2" bgcolor="#000099" class="tableHead">SHIPPING ADDRESS </td>
	</tr>
	<tr>
		<td width="530" class="formCellNosides"><strong>';
	$message.=$listItem[company] 	   . '<br>' ;
	$message.=$listItem[ship_address1] . '<br>';
	$message.=$listItem[ship_city] 	   . ' , ' . $listItem[ship_state]. '<br>';
	$message.= strtoupper($listItem[ship_zip])      . '<br>';
$message.=$listItem[ship_country];
$message.='</strong></td></tr></table>';//END of Address Section
			
			//BEGIN PRESCRIPTION SECTION
			
$query="SELECT * from orders WHERE user_id='$user_id' and order_num='$orderNum' and order_product_type='exclusive' ORDER by order_item_date";//SELECT ALL OPEN PRESCRIPTION ORDERS
			$result=mysql_query($query)
					or die  ('I cannot select items because 6: ' . mysql_error().$query);
			$usercount=mysql_num_rows($result);
			if ($usercount != 0){
			
			 $message.='<table width="530" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="8" bgcolor="#000099" class="tableHead">PRESCRIPTION ITEMS</td>
              </tr>
            ';
					
					while ($listItem=mysql_fetch_array($result)){
						
						$bl_query="SELECT * from additional_discounts WHERE orders_id='$listItem[primary_key]'";//GET BUYING LEVEL DISCOUNT
					$bl_result=mysql_query($bl_query)
						or die  ('I cannot select bl items because: ' . mysql_error().$bl_query);
					$bl_listItem=mysql_fetch_array($bl_result);
					$buying_level_dsc=$bl_listItem[buying_level_discount];
						
						$e_query="SELECT * from extra_product_orders WHERE order_id='$listItem[primary_key]'";//GET EXTRA PRODUCT PRICES
					$e_result=mysql_query($e_query)				or die  ('I cannot select items because 7: ' . mysql_error());
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
					//Charles
					//$itemSubtotal=$listItem[order_quantity]*($listItem[order_product_price]+$e_total_price)+$over_range+$extra_product_price-$coupon_dsc;
		$itemSubtotal=$listItem[order_quantity]*($listItem[order_product_price]+$e_total_price)+$over_range-$coupon_dsc;
					$totalPrice=$totalPrice+$itemSubtotal;
					
					$itemSubtotalDsc=$listItem[order_quantity]*($listItem[order_product_discount]+$e_total_price)+$over_range+$extra_product_price-$coupon_dsc+$buying_level_dsc;
					
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
					
					
					
					$message.='
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
				
				$message.='<td width="95" bgcolor="#FFFFFF" class="formCellNosidesRA"><b>Ref Number:<b></td>';
				$message.='<td align="center" bgcolor="#FFFFFF" class="formCellNosides">'.$listItem[patient_ref_num].'</td>';
				
				$message.='<td width="105" align="center" bgcolor="#FFFFFF" class="formCellNosidesRA"><b>Salesperson&nbsp;ID:<b> </td>
                <td colspan="3" width="80" align="center" bgcolor="#FFFFFF" class="formCellNosides">';
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
                <td rowspan="8" align="right" valign="top" class="formCellNosidesRA"><b>';
				
				
			   $queryEntryFee  = "select entry_fee FROM orders WHERE order_num  = " . $listItem[order_num];
			   $resultEntryFee = mysql_query($queryEntryFee)	or die ("Could not select items");
			   $DataEntryFee   = mysql_fetch_array($resultEntryFee);
			   
			   $queryWarranty  = "select warranty FROM orders WHERE order_num  = " . $listItem[order_num];
			   $resultWarranty = mysql_query($queryWarranty)	or die ("Could not select items");
			   $DataWarranty   = mysql_fetch_array($resultWarranty);
				
				if ($sendPrices=="true"){
					$message.=$listItem[order_product_price];}
				else{
					$message.='n/a';
				}
				
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
						$message.= '<br> Over range: '. $over_range;}
					if ($listItem[extra_product_price]!=0){
						//$message.= "<br>Extra item: ".$listItem[extra_product_price];
						}
					if ($e_total_price!=0){
						$message.= "<br>".$e_products_string;}
					if ($listItem[coupon_dsc]!=0){
						$message.= "<br>Coupon Discount: -".$listItem[coupon_dsc];}
							 
			 $queryTopUrgent = "select * FROM extra_product_orders WHERE category = 'Top urgent' AND order_num =  " . $listItem[order_num];
			 $resultTopUrgent=mysql_query($queryTopUrgent)	or die ("Could not select items");
			 $countTopUrgent=mysql_num_rows($resultTopUrgent);
			 $DataUrgent=mysql_fetch_array($resultTopUrgent);
			if  ($countTopUrgent > 0)
			$message.= "<br>Top Urgent:".  $DataUrgent['price']. "<br>";
			
				
					$message.='<br>Subtotal: '.$itemSubtotal;
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
              </tr>
              <tr>
			    <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>OTHER:</strong> 
				'.$e_order_string_engraving.$e_order_string_tint.'
				</td>
				</tr>';
					 $message.='<tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>Special Instructions:</b>&nbsp;'.$listItem[special_instructions].' </td>
              </tr>';
			  
			 
			 
			 
			 
			 
	$queryProductLine="select product_line from accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$resultProductline=mysql_query($queryProductLine)		or die  ('I cannot select items because 8: ' . mysql_error());
	$DataProductline=mysql_fetch_array($resultProductline);
			 
			if ($DataProductline[product_line]=='lensnetclub'){
			 
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


$message.='<table width="530" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr >';
$message.='<td align="left" class="formCellNosides">Total Quantites</td>';
$message.='<td align="left" class="formCellNosidesRA">Stock by Tray:';
$message.=$totalTrayQuant;
$message.='&nbsp;&nbsp;&nbsp;&nbsp;Stock by Bulk:';
$message.=$totalBulkQuant;
$message.='&nbsp;&nbsp;&nbsp;&nbsp;Prescription:';
$message.=$prescrQuantity;
$message.='</td></tr></table>';

  $message.='<table width="530" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr >';
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
$message.='<td width="100" align="right" valign="middle" class="total"><b>';
				
//$totalPriceDsc=money_format('%.2n',$totalPriceDsc+$order_shipping_cost-$totalDiscount+$extra_product_price);
$totalPriceDsc=money_format('%.2n',$totalPriceDsc+$order_shipping_cost-$totalDiscount);
if ($sendPrices=="true"){				
	$message.=$totalPriceDsc." ".$currency; }
else{
	$message.='n/a';
}
			
	$message.='</b></td></tr></table>';
			
	$message.="</body></html>";

	echo $message;

}//End function




function sendStockConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$userData,$printit){//STOCK BULK AND TRAY CONFIRMATION

//$send_to_address="directl-config@interpage.net";

if ($printit) {
$sendPrices = true;
}
else{
$sendPrices = false;
}


	$bg_query="select bg_name from buying_groups WHERE primary_key='$userData[buying_group]'";//GET BUYING GROUP NAME
	$bg_result=mysql_query($bg_query)
		or die  ('I cannot select items because: ' . mysql_error());
	$bgData=mysql_fetch_array($bg_result);

	$barcode="barcodes/".$orderNum.".gif";
	
	$query="select * from accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
		
	$listItem=mysql_fetch_array($result);
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
	
	$subject = $pl_text." Stock Order Confirmation (REVISED) - Order Number:$orderNum";
	//$subject="fax -html 5026356154";
	$message='<html xmlns="http://www.w3.org/1999/xhtml"><head><base href="'.constant('DIRECT_LENS_URL').'/processOrder.php">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ORDER</title>';
	if ($printit) $message .= "<script type='text/javascript'>window.onload = function() {window.print();}</script>";
$message .= '</head>';
	$message.='<link href="http://www.direct-lens.com/dl.css" rel="stylesheet" type="text/css" />';

	$message.='<table width="530" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img width="250" src="logos/'.$logo_file.'"/><td align="center"><img src="'.$dl_logo_file.'" width="200" height="60" /></td></td><td align="right"><img src="'.$barcode.'" width="190" height="50" /></td></tr></table>';//ABSOLUTE PATH TO THE BARCODE IMAGE
	
		$message.='<table width="530" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td align="left">';
	$message.='<td><div class="header2">Your'.$dl_text.' Order #:'.$orderNum.'</div></td></tr></table>';
	
	$query="select po_num from orders WHERE order_num='$orderNum'"; //GET ORDER PO NUMBER
		$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
		
	$listItem=mysql_fetch_array($result);
	
		$message.='<table width="530" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr><td align="left" class="formCellNosides">PO Order #: ';
		$message.=$listItem[po_num];
		$message.='</td></tr></table>';
	
	$query="select * from accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
		
	$listItem=mysql_fetch_array($result);
	$usercount=mysql_num_rows($result);
	
	$message.='<table width="530" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#000099" class="tableHead">ACCOUNT INFORMATION </td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Name on Account :</td>';
    $message.='<td width="530" class="formCellNosides"><strong>';
	$message.=$listItem[title].' '.$listItem[first_name].' '.$listItem[last_name];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Account # :</td>';
    $message.='<td width="530"  class="formCellNosides"><strong>';
	$message.=$listItem[account_num];
	$message.='</strong></td></tr>';
	$message.='<tr ><td align="right" class="formCellNosides">Company:</td><td width="530" class="formCellNosides"><strong>';
	$message.=$listItem[company];
	$message.='</strong> </td></tr><tr ><td align="right" class="formCellNosides">Buying Group: </td><td class="formCellNosides"><strong>';
	$message.=$bgData[bg_name];
	$message.='</strong></td></tr></table>';
	
	$message.='<table width="530" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#000099" class="tableHead">SHIPPING ADDRESS </td></tr><tr ><td width="130" align="right" class="formCellNosides">Address 1:</td><td width="530" class="formCellNosides"><strong>';
	$message.=$listItem[ship_address1];
	$message.='</strong></td></tr><tr ><td align="right" class="formCellNosides">City:</td><td width="530" class="formCellNosides"><strong>';
$message.=$listItem[ship_city];
$message.='</strong></td></tr><tr ><td align="right" class="formCellNosides">State:</td><td width="530" class="formCellNosides"><strong>';
$message.=$listItem[ship_state];
$message.='</strong> </td></tr><tr ><td align="right" class="formCellNosides">Postal Code:  </td><td class="formCellNosides"><strong>';
$message.=$listItem[ship_zip];
$message.='</strong></td></tr><tr><td align="right" class="formCellNosides">Country:</td><td class="formCellNosides"><strong>';
$message.=$listItem[ship_country];
$message.='</strong></td></tr></table>';//END of Address Section


//BEGIN STOCK BY TRAY SECTION
$query2="SELECT * from orders WHERE user_id='$user_id' and order_num='$orderNum' and order_product_type='stock_tray' ORDER by order_item_date,primary_key";//SELECT ALL OPEN STOCK ORDERS

			$result2=mysql_query($query2)					or die  ('I cannot select items because: ' . mysql_error());
			$stockusercount=mysql_num_rows($result2);
			if ($stockusercount != 0){
			
			 $message.='<table width="530" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox"><tr ><td bgcolor="#000099" class="tableHead">STOCK ITEMS - BY TRAY</td></tr></table>';
			
									while ($listItem=mysql_fetch_array($result2)){
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
						
						$message.='<table width="530" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
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

			$result2=mysql_query($query2)		or die  ('I cannot select items because: ' . mysql_error());
			$stockusercount=mysql_num_rows($result2);
			if ($stockusercount != 0){
			
			 $message.='<table width="530" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox"><tr ><td bgcolor="#000099" class="tableHead">STOCK ITEMS - BULK</td></tr></table>';
			
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
					
					
	$message.='<table width="530" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="7" bgcolor="#D7E1FF" class="tableSubHead">Product  ';
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


$message.='<table width="530" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr >';
$message.='<td align="left" class="formCellNosides">Total Quantites</td>';
$message.='<td align="left" class="formCellNosidesRA">Stock by Tray:';
$message.=$totalTrayQuant;
$message.='&nbsp;&nbsp;&nbsp;&nbsp;Stock by Bulk:';
$message.=$totalBulkQuant;
$message.='&nbsp;&nbsp;&nbsp;&nbsp;Prescription:';
$message.=$prescrQuantity;
$message.='</td></tr></table>';

  $message.='<table width="530" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr >';
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


$message.='</b></td></tr></table>';
$message.="</body></html>";
if ($printit) {
	echo $message;
	die();
}
}//END OF FUNCTION





function getUserData($order_num){

	$query="SELECT user_id FROM orders WHERE order_num=$order_num";
	$result=mysql_query($query)
		or die  ('I cannot select items because 9: ' . mysql_error());
		
	$orderItem=mysql_fetch_array($result);
	
	$user_id=$orderItem[user_id];

	$query="select * from accounts WHERE user_id='$user_id'";
	$result=mysql_query($query)
		or die  ('I cannot select items because 10: ' . mysql_error());
		
	$userData=mysql_fetch_array($result);

	return $userData;
}//End function getUserData


	//$orderNum = $_POST[print_order_num];
	$orderNum = $_REQUEST[print_order_num];
	
	$queryOrder="select * from orders WHERE order_num ='$orderNum '";
	$resultOrder=mysql_query($queryOrder)		or die  ('I cannot select items because 1: ' . mysql_error());
	$DataOrder=mysql_fetch_array($resultOrder);
	
	$queryLab="select lab_email, logo_file from labs WHERE primary_key='". $DataOrder[lab] . "'";
	$resultLab=mysql_query($queryLab)		or die  ('I cannot select items because 2: ' . mysql_error());
	$DataLab=mysql_fetch_array($resultLab);
	$fromAddress =$DataLab[lab_email]; 
	$logo_file = $DataLab[logo_file];
	$user_id = $DataOrder[user_id];
	$send_to_address="dbeaulieu@direct-lens.com";
	$userData=getUserData($orderNum);
	$order_product_type =$DataOrder[order_product_type];


if ($order_product_type=='exclusive'){
sendPrescriptionConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$userData,$Displayprice,false);//PRESCRIPTION CONFIRMATION
}else{
sendStockConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$userData,true);
}

?>