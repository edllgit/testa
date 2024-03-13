<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
require_once(__DIR__.'/../constants/aws.constant.php');
require_once(__DIR__.'/../constants/url.constant.php');
include("admin_functions.inc.php");
include("edit_order_functions.inc.php");
include("redo_order_functions.inc.php");
include("../Connections/sec_connect.inc.php");
include("../includes/calc_functions.inc.php");
include("../includes/est_ship_date_functions.inc.php");


function sendPrescriptionConfirmation($fromAddress,$logo_file,$orderNum,$send_to_address,$user_id,$userData,$sendPrices,$printit,$mylang){//PRESCRIPTION CONFIRMATION

	$message .= "<link href=\"http://www.direct-lens.com/dl.css\" rel=\"stylesheet\" type=\"text/css\" />";
	$query    = "SELECT po_num, order_date_processed, order_date_shipped, redo_order_num, order_num_optipro, order_num_opticbox, prescript_lab, patient_ref_num from orders WHERE order_num='$orderNum'"; //GET ORDER & REDO ORDER #
	$result   = mysql_query($query)		or die  ('I cannot select items because 4: ' . mysql_error());
	$listItem = mysql_fetch_array($result);
		
	$Patientrefnum = $listItem[patient_ref_num];
	$message      .='<table width="475" border="1" align="center" class="formbox" cellpadding="0" cellspacing="0">
					 	<tr><td align="center"
					 		<td bgcolor="#CCE76E" colspan="3">
								<h3>';
	

	//Afficher le numéro de commande Direct-Lens/Ifc
	if ($mylang == 'lang_french'){$message.= "Commande Originale ";}else{$message.= "Original Order ";}
    $message.=' #'.$orderNum; 

	//Afficher la référence Optipro
	if ($listItem[order_num_optipro] <> '')
	$message.="# Optipro: " . $listItem[order_num_optipro];
	
	//Afficher la référence Optic-Box
	if ($listItem[order_num_opticbox] <> '')
	$message.="# Optic-Box: " . $listItem[order_num_opticbox];

	//Afficher quel est le  lab qui fabrique la commande
	switch($listItem[prescript_lab]){
		case 10: $LabFabrique = "SWISS"; 			break;
		case 25: $LabFabrique = "Central Lab";    	break;
		case 69: $LabFabrique = "Essilor #1 Lab";   break;
		case 3:  $LabFabrique = "Dlab";    			break;
		case 21: $LabFabrique = "N/A";    			break;
		default: $LabFabrique = '' ; 
	}

					$message.='</h3></td></tr>';
	
	$message.='
	<tr>
		<td align="left" class="formCellNosides">';
		$message.=' Order Date:<b>'. $listItem[order_date_processed] .'</b>';
		if ($listItem['order_date_shipped'] != '0000-00-00')
		{
			$message.= '&nbsp;&nbsp;&nbsp;&nbsp; Shipping Date:<b>'. $listItem[order_date_shipped].'</b>' ;
		} 
		if ($LabFabrique != ''){
			$message.= '&nbsp;&nbsp;LAB: <b>'. $LabFabrique. '</b>' ;	
		}
		
	$message.='
		</td>
	</tr>';
	
	$query     = "SELECT * from accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$result    = mysql_query($query)		or die  ('I cannot select items because 5: ' . mysql_error());
	$listItem  = mysql_fetch_array($result);
	$usercount = mysql_num_rows($result);
	

			//BEGIN PRESCRIPTION SECTION
			$query    = "SELECT * from orders WHERE  order_num='$orderNum' and order_product_type='exclusive' ORDER by order_item_date";//SELECT ALL OPEN PRESCRIPTION ORDERS
			$result   = mysql_query($query) or die  ('I cannot select items because 6: ' . mysql_error().$query);
			$usercount= mysql_num_rows($result);
			
	if ($usercount != 0){
								
					while ($listItem=mysql_fetch_array($result)){
					$OrderPrimaryKey = $listItem[primary_key];

					$bl_query="SELECT * from additional_discounts WHERE orders_id='$listItem[primary_key]'";//GET BUYING LEVEL DISCOUNT
					$bl_result=mysql_query($bl_query)						or die  ('I cannot select bl items because: ' . mysql_error().$bl_query);
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
					$e_order_string_Large_frame="";
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
								
								
								$e_products_string.="<br />Edging: ".$e_listItem[price];						
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
								
													
							if ($e_listItem[category]=="Frame"){
								$e_products_string.="<br />Frame: ".$e_listItem[price];								
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
					
					
					$currency=$listItem[currency];
					$additional_dsc=$listItem[additional_dsc];
					$discount_type=$listItem[discount_type];
					$extra_product=$listItem[extra_product];
					$extra_product_price=$listItem[extra_product_price];
					$order_shipping_cost=$listItem[order_shipping_cost];
					$itemSubtotal=0;
					$coupon_dsc=$listItem[coupon_dsc];				
					$itemSubtotal=$listItem[order_quantity]*($listItem[order_product_price]+$e_total_price)+$over_range-$coupon_dsc- $MontantDejaPaye + $PrestigeRebateforExtra -$DifferenceOverRange  ;
					$totalPrice=$totalPrice+$itemSubtotal;
					$itemSubtotalDsc=$listItem[order_quantity]*($listItem[order_product_discount]+$e_total_price)+$over_range+$extra_product_price-$coupon_dsc+$buying_level_dsc- $MontantDejaPaye - $DifferenceOverRange;
					$RabaisSurFacture =money_format('%.2n', $listItem[order_product_price]  - $listItem[order_product_discount]);
					$totalPriceDsc=$totalPriceDsc+$itemSubtotalDsc;
					$prescrQuantity=$prescrQuantity+$listItem[order_quantity];
					$itemSubtotal=money_format('%.2n',$itemSubtotal);
					
						
					$queryProduit = "SELECT order_product_id, order_from from orders where order_num = $orderNum";
					$resultProduit  =mysql_query($queryProduit)			or die ("Could not find order num");
					$listItemProduit=mysql_fetch_array($resultProduit);
					$PK_Produit = $listItemProduit['order_product_id'];
					$Order_From = $listItemProduit['order_from'];
					
					if ($Order_From=='ifcclubca')
						$queryProductCode = "SELECT product_code  FROM ifc_ca_exclusive WHERE primary_key  = $PK_Produit";
			        elseif($Order_From=='safety')
						$queryProductCode = "SELECT product_code  FROM safety_exclusive WHERE primary_key  = $PK_Produit";
					else
						$queryProductCode = "SELECT product_code  FROM exclusive WHERE primary_key  = $PK_Produit";
					$resultProductCode  =mysql_query($queryProductCode)			or die ("Could not find product code");
					$listItemProductCode=mysql_fetch_array($resultProductCode);
					$Product_Code = $listItemProductCode['product_code'];
					
					//echo '<br>'. $queryProductCode;
					
				$message.='<table width="475" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
				<tr>
                <td colspan="6" bgcolor="#D7E1FF" class="tableSubHead">Product - ';
				$message.=$listItem[order_product_name] . '<br>Product Code:<b>' . $Product_Code . '</b><br>EYE:<b>' .  $listItem[eye] . '</b>';
					
				
						
				 if ($mylang == 'lang_french'){$Libelle_Polarized= "Polarisé:";}else{$Libelle_Polarized= "Polarized:";}	

						
				$message.='</td>                <td bgcolor="#D7E1FF" class="tableSubHead">&nbsp;</td>
                <td bgcolor="#D7E1FF"  class="formCellNosidesRA" >&nbsp;</td>
				</tr>
                <tr>
					<td align="center" bgcolor="#FFFFFF" class="formCellNosidesRA"><strong>'.$Libelle_Polarized.'</strong></td>
					<td align="center" bgcolor="#FFFFFF" class="formCellNosides">'.$listItem[order_product_polar].'</td>
					<td align="center" bgcolor="#FFFFFF" class="formCellNosidesRA"><strong>Photochromatic:</strong></td>
					<td align="center" bgcolor="#FFFFFF" class="formCellNosides">'.$listItem[order_product_photo].'</td>
					<td  align="center" bgcolor="#FFFFFF" class="formCellNosides" colspan="2">&nbsp;</td>
				</tr>';
               
             
						
					
				$message.=' <tr>
								<td bgcolor="#FFFFFF" class="formCellNosidesRA"><strong>Coating:</strong></td>
								<td align="center" bgcolor="#FFFFFF" class="formCellNosides">'.$listItem[order_product_coating].'</td>
								
								<td bgcolor="#FFFFFF" class="formCellNosidesRA"><b>Ref Number:<b></td>
								<td align="center" bgcolor="#FFFFFF" class="formCellNosides">'.$listItem[patient_ref_num].'</td>
								
								<td  align="center" bgcolor="#FFFFFF" class="formCellNosides" colspan="2">&nbsp;</td>
							</tr>';
				 		    					
						
						
						
						
                $message.='
                <tr>
                <td bgcolor="#FFFFFF" class="formCellNosidesRA"><b>Patient:<b></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides">';
				$message.=$listItem[order_patient_first].'&nbsp;'.$listItem[order_patient_last].'</td>';
				
				$message.='<td bgcolor="#FFFFFF" class="formCellNosidesRA"><b>Tray Num:<b></td>';
				$message.='<td align="center" bgcolor="#FFFFFF" class="formCellNosides">'.$listItem[tray_num].'</td> <td  align="center" bgcolor="#FFFFFF" class="formCellNosides" colspan="2">&nbsp;</td>
				</tr>';
				$message.='
               
               <tr>
                <td bgcolor="#E5E5E5" class="formCellNosides">&nbsp;</td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Sphere</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Cylinder</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Axis</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Addition</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Prism</strong></td>
              </tr>';
			  
			  
	
	
			  
			   if  (($listItem[eye]=='Both') ||  ($listItem[eye]=='R.E.'))
				 { 
					 $message.=' <tr>
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
					 $message.=' <tr>
					<td align="right" class="formCellNosides">R.E.</td>
					<td align="center" class="formCellNosides">-</td>
					<td align="center" class="formCellNosides">-</td>
					<td align="center" class="formCellNosides">-</td>
					<td align="center" class="formCellNosides">-</td>
					<td align="center" class="formCellNosides">';
					$message.="-&nbsp;-&nbsp;&nbsp;-&nbsp;-";
					$message.='</td>';
				}
				
				

				
				if ($e_order_string_frame!=""){
					$e_order_string_edging=$e_order_string_frame;}
					

				$message.='
              </tr>
              <tr>
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
				

				
            $message.='<tr>
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
				
					<tr>
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>PT: </strong>'.$listItem[PT].' &nbsp;&nbsp;&nbsp;<strong>PA: </strong>'.$listItem[PA].' &nbsp;&nbsp;&nbsp;<strong>Vertex:</strong> '.$listItem[vertex].' </td>
                </tr>';
				

  

				$message.='<tr><td colspan="3" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>Thickness: </strong>';
			 	$message.=  'RE CT:' 		         . $listItem['RE_CT']   . '&nbsp;&nbsp;LE CT:' . $listItem['LE_CT'] ;  
				$message.=  '&nbsp;&nbsp;&nbsp;RE ET:'. $listItem['RE_ET']   . '&nbsp;&nbsp;LE ET:' . $listItem['LE_ET'] ;  
			    $message.='</td><td class="formCellNosides"><b>O.C:</b></td><td class="formCellNosides">'.$listItem['optical_center'].'&nbsp;mm</td></tr>
				

				<tr>
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>FRAME:
				
				  &nbsp; </strong>'.$e_order_string_edging. '</td>
                  
              </tr>
			  
              <tr>
			    <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>OTHER:</strong> 
				'.$e_order_string_engraving.$e_order_string_tint.' '.$DetailMirror.'
				</td>
				</tr>';
	$message.= '<tr>
               		<td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>Manufacturing Instructions:</b>&nbsp;'.$listItem[special_instructions].' </td>
              	</tr>
				<tr>
					<td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>Internal Note:</b>&nbsp;'.$listItem[internal_note].' </td>
				</tr>';
			  
			  if ($listItem[redo_reason_id] <> 0){
			  $queryRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = " . $listItem[redo_reason_id];
			  $resultRedoReason = mysql_query($queryRedoReason)		or die  ('I cannot select items because 8: ' . mysql_error());
			  $DataRedoReason   = mysql_fetch_array($resultRedoReason);
			  $RedoReason   =  $DataRedoReason[redo_reason_en];
			   $message.='<tr>
              <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>Redo Reason:</b>&nbsp;'. $RedoReason.' </td>
              </tr>';
			  }
			  
	 
	$queryProductLine="SELECT product_line FROM accounts WHERE user_id='$user_id'"; //GET ACCOUNT INFO
	$resultProductline=mysql_query($queryProductLine)		or die  ('I cannot select items because 8: ' . mysql_error());
	$DataProductline=mysql_fetch_array($resultProductline);
			 
    $message.='</table>';
						
					} //END WHILE
			}//END IF USERCOUNT IS CONDITIONAL
			
			//END PRESCRIPTION SECTIOn

//BEGINNING OF TOTALS SECTION
			
	$message.="</body></html>";

	echo $message;

}//End function


$pkey=$_GET[pkey];


if ($_POST[update_redo]=="true"){
	$pkey=$_POST[pkey];
	$message="<b><font color=\"#FF0000\" size=\"1\" face=\"Helvetica, sans-serif, Arial\"> - REDO ORDER UPDATED</font></b>";
	$_POST[update_redo]="";

	updateRedoOrder($_POST[pkey]);
}


if ($_POST[update_eye]=="true"){//ON doit mettre l'oeil a jour et recharger la page de reprise
	$eye=$_POST['order_eye'];
	//echo '<br>Passe dans update de loeil';
	$pkey=$_POST[pkey];
	$message="<b><font color=\"#FF0000\" size=\"1\" face=\"Helvetica, sans-serif, Arial\"> - EYE UPDATED</font></b>";
	$_POST[update_eye]="";
	if (($pkey<>'') && (strlen($pkey)==6)){
		$queryUpdateEye = "UPDATE orders set eye = '$eye' WHERE primary_key = $pkey";
		//echo '<br>query:'. $queryUpdateEye;
		$resultUpdateEye=mysql_query($queryUpdateEye)	or die  ('I cannot select items because 1: ' . mysql_error());
	}
	
	// apres avoir sauvegarder, rediriger vers la page avec la clé primaire ?
	
}


$prescrQuery="select * from orders WHERE primary_key='$pkey'"; //get order's user id
//echo  '<br>Query:'. $prescrQuery;
$prescrResult=mysql_query($prescrQuery)	or die  ('I cannot select items because 1: ' . mysql_error());
$listItem=mysql_fetch_array($prescrResult);



$queryTable = "SELECT order_from from orders  WHERE  order_num = $listItem[redo_order_num]";
$resultTable=mysql_query($queryTable)	or die  ('I cannot select items because 2: ' . mysql_error());
$DataTable=mysql_fetch_array($resultTable);

$OrderFrom = $DataTable[order_from];

if ($OrderFrom =='ifcclubca'){
	$TabletoUse = 'ifc_ca_exclusive';
}elseif($OrderFrom =='safety'){
	$TabletoUse = 'safety_exclusive';
}else{
	$TabletoUse = 'exclusive';
}

$prodQuery="select * from $TabletoUse WHERE primary_key='$listItem[order_product_id]'"; //get product info

$prodResult=mysql_query($prodQuery)	or die  ('I cannot select items because 3: ' . mysql_error());
//echo '<br>query  ' .$prodQuery ;
$listProd=mysql_fetch_array($prodResult);

//CREAT OR UPDATE EST SHIPPING DATE

$new_est_ship_date=calculateEstShipDate($listItem['order_date_processed'],$listItem['order_product_id']);
addNewEstShipDate($new_est_ship_date,$listItem['primary_key'],$listItem['order_num'],$listItem['order_date_processed']);


 

?>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../favicon.ico">
    <title>Direct Lens Admin Area</title>
    <!-- Bootstrap core CSS -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>
<script type="text/javascript">
window.onload = function() {
document.getElementById("redo_password").onblur = function() {
var xmlhttp;
var redo_password=document.getElementById("redo_password");
if (redo_password.value != "")
{
if (window.XMLHttpRequest)
  {
  xmlhttp=new XMLHttpRequest();
  }
else
  {
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("status").innerHTML=xmlhttp.responseText;
		if (xmlhttp.responseText == "<span style=\"color:red;\">Le mot de passe que vous avez saisi est incorrect</span>"){
			document.getElementById("Submitbtn").disabled=true;
		}else{
			document.getElementById("Submitbtn").disabled=false;
		}
    }
  };
xmlhttp.open("POST","do_check.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send("redo_password="+encodeURIComponent(redo_password.value));
document.getElementById("status").innerHTML="Vérification en cours...";
}
};
};
</script> 
<link href="admin.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript">

function checkProduct(theForm)
{
	if(theForm.product_id.value==""){//NO Prod
		alert("NO PRODUCT SELECTED");
		return false;
	}
	
	if(theForm.redo_reason_id.value==""){//NO reason has been selected
		alert("YOU NEED TO SELECT A REASON FOR THIS RE-DO");
		return false;
	}
	
	if(theForm.redo_origin.value==""){//l'origine de la reprise n'a pas été sélectionné
		alert("YOU NEED TO SELECT THE ORIGIN OF THE REDO");
		return false;
	}
	
	
	
	if((theForm.redo_reason_id.value=="29")&&(theForm.redo_detail.value=="")){//Redo reason selected: Other | Reason to Explain: the explanation has to be mandatory
		alert("Veuillez expliquer la raison de cette reprise. Please explain why you selected 'Other | reason to explain'");
		theForm.redo_detail.focus();
		return false;
	}
	
	if((theForm.redo_reason_id.value=="65")&&(theForm.redo_detail.value=="")){//Redo reason selected: GARANTIE A TOUT CASSER: the explanation has to be mandatory
		alert("Veuillez entrer le numéro de votre garantie a tout casser. Please type the reference of your Warranty");
		theForm.redo_detail.focus();
		return false;
	}
	
	if(theForm.redo_password.value==""){//NO reason has been selected
		alert("YOU NEED TO TYPE YOUR EMPLOYEE PASSWORD TO SAVE THIS RE-DO");
		return false;
	}
	
	//Teinte Solide
	if((theForm.tint.value=="Solid")&&(theForm.from_perc.value=="")){
		alert("Vous devez choisir les pourcentage de la teinte");
		theForm.from_perc.focus();
		return (false);
	}
	
	
	//Teinte Dégradée
	if((theForm.tint.value=="Gradient")&&(theForm.from_perc.value=="")){
		alert("Vous devez choisir les pourcentage de la teinte");
		theForm.from_perc.focus();
		return (false);
	}
	
	if((theForm.tint.value=="Gradient")&&(theForm.to_perc.value=="")){
		alert("Vous devez choisir les pourcentage de la teinte");
		theForm.to_perc.focus();
		return (false);
	}		


	if((theForm.tint.value=="Solid")&&(theForm.tint_color.value=="None")){
		alert("Vous devez choisir la couleur de la teinte");
		theForm.tint_color.focus();
		return (false);
	}
	
	if((theForm.tint.value=="Gradient")&&(theForm.tint_color.value=="None")){
		alert("Vous devez choisir la couleur de la teinte");
		theForm.tint_color.focus();
		return (false);
	}		
		
	
	if((theForm.job_type.value=="remote edging") && (formShape.btnupload.value!='Uploaded') && (theForm.shape_name_bk_field.value=='')) {//JOB REMOTE EDGING et Aucune shape attachée+ aucune attachée dans le passé: erreur
		alert("The shape is mandatory for Remote Edging orders") // EST AFFICHÉ SI LA COMMANDE EST UN REMOTE EDGING;
		return false;
	}
	
	
	
	
}
</script>


</head>
<body>
<input style="display:none" type="password" name="foilautofill"/>
  <table border="0" cellspacing="0" cellpadding="0" width="100%">
  	<tr valign="top">
  		<td width="20%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="80%">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField3">
            	<tr bgcolor="#000000">
            		<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">EDIT
           		           REDO PRESCRIPTION ORDER</font></b><?php echo $message;?></td>
       		  </tr>

              <table width="100%" border="0" align="center" cellpadding="3" cellspacing="0"  class="formField2"> 
			<tr><td>
        
            <form action="re-doEditV2.php" method="post" enctype="multipart/form-data" name="changeEye" id="changeEye">
            <tr>
             <td bgcolor="#F0EDEE" align="left" colspan="4"><strong><?php  if ($mylang == 'lang_french'){echo "0-SI NÉCESSAIRE, DÉBUTER PAR CHANGER l'OEIL (puis cliquer sur Update):";}else{echo "0- If necessary, start by change the selection of the eye you want to order and click on Update";}?>
				 </strong></td>
             </tr>
             <tr>
                <td bgcolor="#F0EDEE" colspan="4" align="left">
                     &nbsp; <input name="order_eye" type="radio" value="Both"<?php if ($listItem[eye]=="Both") echo " checked"?> />
                     <?php  if ($mylang == 'lang_french'){echo "Les deux";}else{echo "Both";}?>&nbsp;
                     <input name="order_eye" type="radio" value="R.E."<?php if ($listItem[eye]=="R.E.") echo " checked"?>/>
                     <?php  if ($mylang == 'lang_french'){echo "Droit Seulement";}else{echo "Right Only";}?>&nbsp;
                     <input name="order_eye" type="radio" value="L.E."<?php if ($listItem[eye]=="L.E.") echo " checked"?>/>
                     <?php  if ($mylang == 'lang_french'){echo "Gauche Seulement";}else{echo "Left Only";}?>&nbsp;
					 <input name="SubmitEyeChange" type="submit" id="SubmitEyeChange" class="formField2" value="Update"></td>
                    
            </tr>    
             <input name="update_eye" type="hidden" value="true"> 
             <input name="pkey" type="hidden" value="<?php echo $pkey;?>"></td>    
            </form>
            
           <tr><td>&nbsp;</td></tr>
            
            
        
            
			<form action="re-doEditV2.php" method="post" enctype="multipart/form-data" name="redoForm" id="redoForm" onSubmit="return checkProduct(this)">
			 
                <tr>
					<td width="20%"  colspan="1" align="center" bgcolor="#CCE76E">
						<h6>
							<b><?php  if ($mylang == 'lang_french'){echo "Commande Originale:</b> #". $listItem[redo_order_num];}else{echo "Original&nbsp;Order&nbsp;Num: #</b>". $listItem[redo_order_num];}?>
						</h6>
					</td>
					<td  width="20%" colspan="3" align="center" bgcolor="#E48E8F">
                	<h6><b><?php  if ($mylang == 'lang_french'){echo "Cette Reprise:";}else{echo "This&nbsp;Order&nbsp;Num:";}?></b>
                    <?php echo '#'. $listItem[order_num] ?></h6>
					</td>
                </tr>
                
				<tr><td>&nbsp;</td></tr>
                 
                 
                  <tr>
                  	<td align="left" bgcolor="#DBD7D7"><strong>
                    <?php  if ($mylang == 'lang_french'){
					echo "<strong> 1- QUEL INDICE VOULEZ-VOUS&nbsp; </strong>";
					}else {
					echo "<strong> 1- WHICH INDEX DO YOU WANT </strong>";
					} ?>
                    </strong></td>
                    <td colspan="3" align="center" bgcolor="#DBD7D7"><strong>TRANSITIONS</strong></td>
                    <td colspan="2" align="center" bgcolor="#DBD7D7"><strong><?php if ($mylang == 'lang_french'){echo "POLARISÉ";}else{echo "POLARIZED";}?></strong></td>
                  </tr>
                  
                  <tr>
                  <td width="9%" bgcolor="#FFFFFF">
                    <select class="form-control" name="order_product_index" OnChange="document.redoForm.submit();" >
                      <?php
 				$query="select index_v from $TabletoUse group by index_v asc"; /* select all openings */
				$result=mysql_query($query)					or die ("Could not select items");
 				while ($listItemIndex=mysql_fetch_array($result)){
				echo "<option value=\"$listItemIndex[index_v]\"";
				if ($listItemIndex[index_v]==$listItem[order_product_index]) echo "selected=\"selected\"";
				echo ">";
 				$name=stripslashes($listItemIndex[index_v]);
				echo "$name</option>";}?>
                    </select>
                </td>
                <td colspan="3">
                <select name="order_product_photo"  OnChange="document.redoForm.submit();" class="form-control" >
                      <option value="None" <?php if ($listItem[order_product_photo]=="None") echo "selected=\"selected\"";?>>None</option>
                      <?php
  				$query="select photo from $TabletoUse group by photo asc"; /* select all openings */
				$result=mysql_query($query)
					or die ("Could not select items");
 				while ($listItemPhoto=mysql_fetch_array($result)){
 				if ($listItemPhoto[photo]!="None"){
				echo "<option value=\"$listItemPhoto[photo]\"";
				if ($listItemPhoto[photo]==$listItem[order_product_photo]) echo "selected=\"selected\"";
				echo ">";
				$name=stripslashes($listItemPhoto[photo]);
				echo "$name</option>";}}?>
                    </select>
                 </td>
                 <td colspan="3">
                 <select name="order_product_polar"  OnChange="document.redoForm.submit();"   class="form-control">
                      <option value="None" <?php if ($listItem[order_product_polar]=="None") echo "selected=\"selected\"";?>>None</option>
                      <?php
  				$query="select polar from $TabletoUse group by polar asc"; /* select all openings */
				$result=mysql_query($query)
					or die ("Could not select items");
 				while ($listItemPolar=mysql_fetch_array($result)){
 				if ($listItemPolar[polar]!="None"){
 				echo "<option value=\"$listItemPolar[polar]\"";
				if ($listItemPolar[polar]==$listItem[order_product_polar]) echo "selected=\"selected\"";
				echo ">";
				$name=stripslashes($listItemPolar[polar]);
				echo "$name</option>";}}?>
                    </select>
                 </td>
                </tr>
                
                 <tr><td>&nbsp;</td></tr>
                
                 
                 
     		  <tr>
                	<td bgcolor="#DBD7D7" colspan="8"><strong>
                    <?php  if ($mylang == 'lang_french'){
					echo "<strong> 2- SÉLECTIONNER LA CIRCONSTANCE DE LA REPRISE&nbsp; </strong>";
					}else {
					echo "<strong> 2- SELECT THE ORIGIN OF THE REDO:</strong>";
					} ?>
                    </strong></td>
                </tr>
                
                <tr>
                <td colspan="2">
                <select name="redo_origin" id="redo_origin"  class="form-control" >
                      <option value="" <?php if ($listItem[redo_origin]=="") echo "selected=\"selected\"";?>>&nbsp;</option>
                      <option value="retour_client" <?php if ($listItem[redo_origin]=="retour_client") echo "selected=\"selected\"";?>> 
				    <?php  if ($mylang == 'lang_french'){
					echo "Retour du client (Garantie)";
					}else {
					echo "Customer return (Warranty)</strong>";
					} ?></option>
                      <option value="reception_commande_entrepot" <?php if ($listItem[redo_origin]=="reception_commande_entrepot") echo "selected=\"selected\"";?>>
                      <?php  if ($mylang == 'lang_french'){
					echo "Réception d'une commande";
					}else {
					echo "On lenses reception</strong>";
					} ?>
                    </option>
                    
                    <option value="lab" <?php if ($listItem[redo_origin]=="lab") echo "selected=\"selected\"";?>>
                      <?php  if ($mylang == 'lang_french'){
					echo "<strong>Labo STC</strong>";
					}else {
					echo "<strong>STC LAB</strong>";
					} ?>
                    </option>
                    </select>
                </td>
                </tr>
                 
                 <tr><td>&nbsp;</td></tr>
                 
                 <tr>
                	<td bgcolor="#DBD7D7" colspan="8"><strong>
                    <?php  if ($mylang == 'lang_french'){
					echo "<strong>*3- SÉLECTIONNER VOTRE PRODUIT:</strong>";
					}else {
					echo "<strong> *3- SELECT THE PRODUCT: </strong>";
					} ?>
                    </strong></td>
                </tr>
                
                <tr>
                <td align="left" colspan="3" bgcolor="#555555"> <?php

				$queryPkClient="select primary_key from accounts where user_id = '$listItem[user_id]' "; 
				$resultPkClient=mysql_query($queryPkClient)			or die ("Could not select items");	  
				$DataPkClient=mysql_fetch_array($resultPkClient);
				$Primary_Key_Acct = $DataPkClient[primary_key];
				$collections=array();
				$collections=explode(";",$F_listItem[avail_prescript_collections]);
				$collectionNum=count($collections);
				if ($collectionNum!=0){
					$collectionString=" AND (collection='".$collections[0]."' ";
						for($i=1;$i<$collectionNum;$i++){
							$collectionString.=" OR collection='".$collections[$i]."' ";
						}
					$collectionString.=") ";
				}//END collectionNum
	
					$prodQuery="select * from acct_collections LEFT JOIN liste_collection_info on (liste_collection_info.liste_collection_id = acct_collections.collection_id) 
LEFT JOIN $TabletoUse on (liste_collection_info.collection_name = $TabletoUse.collection) WHERE prod_status='active'  
 AND acct_collections.acct_id = '$Primary_Key_Acct'
  AND $TabletoUse.index_v = ". $listItem[order_product_index] . " AND $TabletoUse.photo = '". $listItem[order_product_photo] ."'AND $TabletoUse.polar = '". $listItem[order_product_polar] ."' order by $TabletoUse.product_name"; /* select all openings */

//echo 	'<br> Charles: '. $prodQuery . '<br>';
					
					
					
					$prodResult=mysql_query($prodQuery)	or die('Could not select items because: ' . mysql_error());
						//echo $listItem[order_product_id];
						?>
                      
                      <select  class="form-control" name="product_id"  id="product_id">
                        <option  value="">PRODUCT NO LONGER AVAILABLE</option>
		        <?php while ($listProducts=mysql_fetch_array($prodResult)){
					
					if (($listProducts[min_height] <> 0) && ($listProducts[max_height] <> 0))
					$Fittint_Heights = ' Heights: '. stripslashes($listProducts[min_height]). '-' .stripslashes($listProducts[max_height]);
					else
					$Fittint_Heights = '';
					
					if ($listProducts[corridor] <> '')
					$Corridor  = ' Corridor: '. stripslashes($listProducts[corridor]) . ' ';
					else
					$Corridor  = '';
					
					$name=stripslashes($listProducts[product_name]);
					if ($listProducts[primary_key]==$listItem[order_product_id]) {
					echo "<option value=\"$listProducts[primary_key]\" selected >" .  $name . $Fittint_Heights  . $Corridor. "</option>";
					}else{
					echo "<option value=\"$listProducts[primary_key]\" >" . $name  . $Fittint_Heights . $Corridor. "</option>";
					}	
					
				}?>
		        </select></td>
                <td> 
                    &nbsp;&nbsp;&nbsp;<strong>Coating:&nbsp;&nbsp;</strong><?php echo $listItem[order_product_coating]; ?>
                </td>

                </tr>
                 
                 
                 
                           
                  <tr><td>&nbsp;</td></tr>
                 
                 
               
                  <input name="po_num" type="hidden" class="formField2" value="<?php echo $listItem[po_num];?>" size="20"></td>
                
                
                  <tr>
                  	<td colspan="8" align="left" bgcolor="#DBD7D7"><b>
                    <?php  if ($mylang == 'lang_french'){
					echo "<strong> *4- SELECTIONNER LA RAISON DE LA REPRISE:&nbsp; </strong>";
					}else {
					echo "<strong> *4- SELECT THE REDO REASON:</strong>";
					} ?>
                   </b></td>
                  </tr>
                  
                  <tr>
                  <td colspan="3" align="left"  bgcolor="#FF0000">  
                  <select name="redo_reason_id" class="form-control"   id="redo_reason_id"
                  <?php if ($listItem[redo_reason_id]<> '0') echo ' disabled'; ?>
                  >
                   
<?php
  		$queryRedo="select * from redo_reasons   	  ORDER by redo_reason_number"; /* select all openings */
		$resultRedo=mysql_query($queryRedo)			or die ("Could not select items");
		
		 if ($mylang == 'lang_french'){
			echo "<option value=\"\">IMPORTANT, SELECTIONNER UNE RAISON POUR CE REDO</option>";
			}else {
			echo "<option value=\"\">IMPORTANT, SELECT AN REASON FOR THIS REDO</option>";
			}
		
		
		 while ($DataRedoReason=mysql_fetch_array($resultRedo)){
			 echo "<option value=\"$DataRedoReason[redo_reason_id]\"";
			 
			 if ($listItem[redo_reason_id]== $DataRedoReason[redo_reason_id])
			 echo " selected ";
			 echo ">";
		 if ($mylang == 'lang_french'){
			$name=stripslashes($DataRedoReason[redo_reason_fr]);
			echo "$name&nbsp;($DataRedoReason[rebate_percentage])</option>";
			}else {
			$name=stripslashes($DataRedoReason[redo_reason_en]);
			echo "$name&nbsp;($DataRedoReason[rebate_percentage])</option>";
			}
		
		 }
			?>
 </select>
</td>
                </tr>
                
                
                
                               
                
               
                 <tr><td>&nbsp;</td></tr>
                  <tr>
                    <td colspan="8" bgcolor="#DBD7D7"><?php  if ($mylang == 'lang_french'){
						echo "<strong> *5-RÉFÉRENCE DE VOTRE GTC:</strong>";
						}else {
						echo "<strong> *5-REFERENCE OF YOUR  GTC:</strong>";
						} ?>
                    </td>
                  </tr>
                  
                  
                <tr>   
                <td colspan="3">  <input  class="form-control"   name="redo_detail" title="Mandatory if the redo reason if Other Reason to explain" alt="Mandatory if the redo reason if Other Reason to explain"  type="text" class="formField2" id="redo_detail" value="<?php echo $listItem[redo_detail];?>" size="50" maxlength="50" max="50"></td>
                </tr>
                
                <tr><td>&nbsp;</td></tr>
                  
                <tr align="center">
                	<td align="left"  bgcolor="#DBD7D7" colspan="8">
                    <?php  if ($mylang == 'lang_french'){
					echo "<strong>*6- VALIDATION DE SÉCURITÉ:&nbsp; </strong>";
					}else {
					echo "<strong>*6- SECURITY VALIDATION: &nbsp;</strong>";
					} ?></td>
                    </tr>
                    
                    <tr>
                    <td  colspan="6" align="left">
					<?php  if ($mylang == 'lang_french'){
					echo "<b>Entrer votre mot de passe d'employé</b>";
					}else {
					echo "<b>Type your employee password</b>";
					} ?>
                    <input  name="redo_password" title="Please Call Charles if you don't have an employee password" alt="Please Call Charles if you don't have an employee password"  type="password" class="formField2" id="redo_password" value="" size="6" maxlength="6" max="6">
                    <input name="validate_pwd" type="button" id="validate_pwd" autocomplete="off" class="formField2" value="Valider">
                     <span id="status"></span>
                     </td>
                </tr>
          </table>
		
				
			  	
				<div width="25%" align="left" style="position: absolute;margin-top: 10px;">
				<?php 
				sendPrescriptionConfirmation($fromAddress,$logo_file,$listItem[redo_order_num],$send_to_address,$user_id,$userData,true,false,$mylang); 
				?>
				</div>	
		
		<br>		
        <table width="60%" border="10" align="right" cellpadding="3" cellspacing="0"  class="formField2">   
		
			<tr align="left">
				<td bgcolor="#E48E8F" colspan="10" align="center">
					
						<h3>
							<?php if ($mylang == 'lang_french'){echo " Cette Reprise";}else{echo "Redo Order";}?>
							<?php echo ' #'. $listItem[order_num];?>
						</h3>
					
				</td>
			</tr>
			 

			<tr>
            	<td colspan="10" bgcolor="#AAAAAA"><strong><?php  if ($mylang == 'lang_french'){echo "OEIL";}else{echo "EYE";}?></strong></td>
            </tr>
			
				<tr>
				   <td bgcolor="#DDDDDD" colspan="10" align="left">
				   <?php
					   //Deux Yeux
					  if (($mylang == 'lang_french') && ($listItem[eye]=='Both'))
					  echo "Les deux";
						  
					  if (($mylang <> 'lang_french') && ($listItem[eye]=='Both'))
					  echo "Both";
					   
					  //Gauche Seulement
					  if (($mylang == 'lang_french') && ($listItem[eye]=='L.E.'))
					  echo "Gauche seulement";
					   
					  if (($mylang <> 'lang_french') && ($listItem[eye]=='L.E.'))
					  echo "L.E.";
					   
					  //Droit Seulement 
					  if (($mylang == 'lang_french') && ($listItem[eye]=='R.E.'))
					  echo "Droit seulement";
					   
					  if (($mylang <> 'lang_french') && ($listItem[eye]=='R.E.'))
					  echo "R.E.";
					   
					   ?></td>
				   <input name="eye" type="hidden" value="<?php  echo $listItem[eye];?>">
               </tr>
               
			 
				<tr><td colspan="10">&nbsp;</td></tr>  
				  
 				<tr>
                  <td colspan="10" bgcolor="#AAAAAA"><strong><?php  if ($mylang == 'lang_french'){echo "INFO PATIENT";}else{echo "PATIENT";}?></strong></td>
                </tr>

              <tr>
					<td colspan="10"  bgcolor="#DDDDDD">
						
					<strong><?php  if ($mylang == 'lang_french'){echo "Prénom";}else{echo "First Name";}?>:</strong>
					<input name="order_patient_first" type="text" class="formField2" id="order_patient_first" value="<?php echo $listItem[order_patient_first];?>" size="12">&nbsp;&nbsp;
						
					<strong><?php  if ($mylang == 'lang_french'){echo "Nom de famille";}else{echo "Last Name";}?>:</strong>
					<input name="order_patient_last" type="text" class="formField2" id="order_patient_last" value="<?php echo $listItem[order_patient_last];?>" size="12">&nbsp;&nbsp;
						
					<strong><?php  if ($mylang == 'lang_french'){echo "Numéro Référence";}else{echo "Ref Number";}?>:</strong>
					<input name="patient_ref_num" type="text" class="formField2" id="patient_ref_num" value="<?php echo $listItem[patient_ref_num];?>" size="5">&nbsp;&nbsp;
						
					<strong><?php  if ($mylang == 'lang_french'){echo "Cabaret";}else{echo "Tray Num";}?>:</strong>
					<input name="tray_num" type="text" class="formField2" id="tray_num" value="<?php echo $listItem[tray_num];?>" size="6">&nbsp;
						
					</td>
			  </tr>
			
				<tr><td colspan="10">&nbsp;</td></tr>  
                
                <tr>
                  <td width="5%"  align="center" bgcolor="#aaaaaa">&nbsp;</td>
                  <td width="7%"  align="center" bgcolor="#aaaaaa"><strong>Sphere</strong></td>
                  <td width="7%"  align="center" bgcolor="#aaaaaa"><strong>Cylinder</strong></td>
                  <td width="7%"  align="center" bgcolor="#aaaaaa"><strong>Axis</strong></td>
                  <td width="9%"  align="center" bgcolor="#aaaaaa"><strong>Addition</strong></td>
                  <td width="18%" align="center" bgcolor="#aaaaaa" colspan="5" ><strong>Prism</strong></td>
                </tr>
			
			
			
                <tr>
					  <td align="right" bgcolor="#DDDDDD"><strong>R.E.</strong></td>

					  <td align="center" bgcolor="#DDDDDD">
						  <select name="re_sphere" id="re_sphere">
						  <?php
						  $min = -15.75;
						  $max = 14.75;
						   if ($listItem[eye]!="L.E."){
								for ($i=$max;$i>=$min;$i=$i-.25){
										if($i<=0){
											$val=$i;
											$val=money_format('%.2n',$val);
										}else{
											$val=$i;
											$val=money_format('%.2n',$i);
											$val="+".$val;}
									echo "<option value=\"$val\"";
									if ($listItem[re_sphere]==$i) echo "selected=\"selected\"";
										echo">$val</option>";
								}//End For

							}//END L.E. CONDITIONAL
						  else{

						  echo "<option value=\"-\" selected=\"selected\">-</option>";
						  } ?>
							</select>
					  </td>

					  <td align="center" bgcolor="#DDDDDD">
						  <select name="re_cyl"  id="re_cyl">
					  <?php
					  $min= -8.75;
					  $max= 6.75;
					   if ($listItem[eye]!="L.E."){
							for ($i=$max;$i>=$min;$i=$i-.25){
								if($i<=0){
								$val=$i;
								$val=money_format('%.2n',$val);}
							else{
								$val=$i;
								$val=money_format('%.2n',$i);
								$val="+".$val;}
							echo "<option value=\"$val\"";

							if ($listItem[re_cyl]==$i) echo "selected=\"selected\"";

							echo">$val</option>";
						 } //End For  
					  }//END L.E. CONDITIONAL
					  else{
					  echo "<option value=\"-\" selected=\"selected\">-</option>";
					  } ?>
						</select>
					</td>


					  <td width="150" align="center" bgcolor="#DDDDDD"><input name="re_axis" type="text" class="formField2" id="re_axis" value="<?php if ($listItem[re_axis]>0) echo $listItem[re_axis];?>" size="4" maxlength="4"></td>


					 <td align="center" bgcolor="#DDDDDD">
						  <select name="re_add"  id="re_add">
					  <?php

					  $max=4.0;
					  $min=0;


					   if ($listItem[eye]!="L.E."){

						 for ($i=$max;$i>=$min;$i=$i-.25){
							if($i<=0){
								$val=$i;
								$val=money_format('%.2n',$val);
								}
							else{
								$val=$i;
								$val=money_format('%.2n',$i);
								$val="+".$val;}
							echo "<option value=\"$val\"";

							if ($listItem[re_add]==$i) echo "selected=\"selected\"";

							echo">$val</option>";
							}
					   }//END L.E. CONDITIONAL
					  else{

					  echo "<option value=\"-\" selected=\"selected\">-</option>";
					  } ?>
						</select>
					</td>


					<td align="center" bgcolor="#DDDDDD" colspan="5">
						<input name="RE_PR_IO"  type="radio" value="In"   <?php if ($listItem[re_pr_io]=='In') echo 'checked="checked"';?>/>In&nbsp;
						<input name="RE_PR_IO"  type="radio" value="Out"  <?php if ($listItem[re_pr_io]=='Out') echo 'checked="checked"';?> />Out 
						<input name="RE_PR_IO"  type="radio" value="None" <?php if ($listItem[re_pr_io]=='None') echo 'checked="checked"';?> />None&nbsp;&nbsp;
						<input name="re_pr_ax"  type="text"  value="<?php	if ($listItem[re_pr_ax]>0) echo $listItem[re_pr_ax];?>" id="re_pr_ax"  size="2" maxlength="4"><br>
						<input name="RE_PR_UD"  type="radio" value="Up"   <?php if ($listItem[re_pr_ud]=='Up') echo 'checked="checked"';?>/>Up&nbsp;
						<input name="RE_PR_UD"  type="radio" value="Down" <?php if ($listItem[re_pr_ud]=='Down') echo 'checked="checked"';?>/>Down 
						<input name="RE_PR_UD"  type="radio" value="None" <?php if ($listItem[re_pr_ud]=='None') echo 'checked="checked"';?>/>None
						<input name="re_pr_ax2" type="text"  value="<?php	if ($listItem[re_pr_ax2]>0) echo $listItem[re_pr_ax2];?>" id="re_pr_ax2"  size="2" maxlength="4">
					</td>
	
       	     </tr>
			
			
			
			
                <tr>
					<td colspan="10" align="left">
					
					<strong>Dist.PD:</strong>
                    <input name="re_pd" type="text" class="formField2" id="re_pd"            value="<?php if ($listItem[re_pd]>0)      echo $listItem[re_pd];?>"      size="4" maxlength="4">&nbsp;&nbsp;&nbsp;
					
					<strong>Near PD:</strong>
                    <input name="re_pd_near" type="text" class="formField2" id="re_pd_near"  value="<?php if ($listItem[re_pd_near]>0) echo $listItem[re_pd_near];?>" size="4" maxlength="4"> &nbsp;&nbsp;&nbsp;
					
					<strong>Height:</strong>
                    <input name="re_height" type="text" class="formField2" id="re_height"    value="<?php if ($listItem[re_height]>0)  echo $listItem[re_height];?>"  size="4" maxlength="4">
					</td>
                </tr>
			
			
                <tr>
                  <td align="right" bgcolor="#DDDDDD"><strong>L.E.</strong></td>
					
                  <td align="center" bgcolor="#DDDDDD">
					  <select name="le_sphere"  id="le_sphere">
					  <?php
					  $min = -15.75;
					  $max = 14.75;
					  if ($listItem[eye]!="R.E."){
						 for ($i=$max;$i>=$min;$i=$i-.25){
							if($i<=0){
								$val=$i;
								$val=money_format('%.2n',$val);
							}else{
								$val=$i;
								$val=money_format('%.2n',$i);
								$val="+".$val;}
							echo "<option value=\"$val\"";
							if ($listItem[le_sphere]==$i) echo "selected=\"selected\"";
							echo">$val</option>";} 
					  }//END R.E. CONDITIONAL
					  else{
					  echo "<option value=\"-\" selected=\"selected\">-</option>";
					  }?>
                    </select>
				</td>
					
					
                  <td align="center" bgcolor="#DDDDDD">
						  <select name="le_cyl" id="le_cyl">
						  <?php
						  $min = -8.75;
						  $max = 6.75;
						   if ($listItem[eye]!="R.E."){
								for ($i=$max;$i>=$min;$i=$i-.25){
									if($i<=0){
										$val=$i;
										$val=money_format('%.2n',$val);}
								else{
									$val=$i;
									$val=money_format('%.2n',$i);
									$val="+".$val;}
								echo "<option value=\"$val\"";
								if ($listItem[le_cyl]==$i) echo "selected=\"selected\"";
								echo">$val</option>";}
						  }//END R.E. CONDITIONAL
						  else{
						  echo "<option value=\"-\" selected=\"selected\">-</option>";
						  }
						   ?>
						</select>
				 </td>
					
                  <td align="center" bgcolor="#DDDDDD"><input name="le_axis" type="text" class="formField2" id="le_axis" value="<?php if ($listItem[le_axis]>0) echo $listItem[le_axis];?>"  size="4" maxlength="4"></td>
					
                  <td align="center" bgcolor="#DDDDDD">
					  <select name="le_add" id="le_add">
						  <?php
						  $max = 4.0;
						  $min = 0;

						   if ($listItem[eye]!="R.E."){
								for ($i=$max;$i>=$min;$i=$i-.25){
									if($i<=0){
									$val=$i;
									$val=money_format('%.2n',$val);
								}else{
									$val=$i;
									$val=money_format('%.2n',$i);
									$val="+".$val;}
								echo "<option value=\"$val\"";
								if ($listItem[le_add]==$i) echo "selected=\"selected\"";
								echo">$val</option>";}
						   }//END R.E. CONDITIONAL
						  else{
						  echo "<option value=\"-\" selected=\"selected\">-</option>";
						  }?>
                    </select>
				</td>
					
					
                <td align="right" bgcolor="#DDDDDD" colspan="5">
                    <input name="LE_PR_IO" type="radio" value="In"   <?php if ($listItem[le_pr_io]=='In')   echo 'checked="checked"';?>/>In&nbsp;
  					<input name="LE_PR_IO" type="radio" value="Out"  <?php if ($listItem[le_pr_io]=='Out')  echo 'checked="checked"';?>/>Out 
                    <input name="LE_PR_IO" type="radio" value="None" <?php if ($listItem[le_pr_io]=='None') echo 'checked="checked"';?>/>None 
					<input name="le_pr_ax" type="text"  class="formField2" id="le_pr_ax" value="<?php if ($listItem[le_pr_ax]>0) echo $listItem[le_pr_ax];?>" size="2" maxlength="4"><br>
                   
					<input name="LE_PR_UD" type="radio" value="Up" <?php if ($listItem[le_pr_ud]=='Up') echo 'checked="checked"';?>/>Up&nbsp;
					<input name="LE_PR_UD" type="radio" value="Down" <?php if ($listItem[le_pr_ud]=='Down') echo 'checked="checked"';?>/>Down 
                    <input name="LE_PR_UD" type="radio" value="None" <?php if ($listItem[le_pr_ud]=='None') echo 'checked="checked"';?>/>None&nbsp; 
					<input name="le_pr_ax2" type="text" class="formField2" id="le_pr_ax2" value="<?php	if ($listItem[le_pr_ax2]>0) echo $listItem[le_pr_ax2];?>" size="2" maxlength="4">
				</td>
			
			</tr>
			

                <tr>
					<td colspan="10" align="left" bgcolor="#FFFFFF">
						<strong>Dist.PD:</strong><input name="le_pd"      type="text" class="formField2" id="le_pd"      size="4" maxlength="4" value="<?php echo $listItem[le_pd];?>" >&nbsp;&nbsp;&nbsp;
						<strong>Near PD:</strong><input name="le_pd_near" type="text" class="formField2" id="le_pd_near" value="<?php echo $listItem[le_pd_near];?>" size="4" maxlength="4">&nbsp;&nbsp;&nbsp;
						<strong>Height:</strong> <input name="le_height"  type="text" class="formField2" id="le_height"  size="4" maxlength="4" value="<?php if ($listItem[le_height]>0) echo $listItem[le_height];?>">
					</td>
                </tr>
                 
                <tr><td colspan="10">&nbsp;</td></tr>
                
						
			
				<tr>
                	<td colspan="10" bgcolor="#AAAAAA"><strong><?php  if ($mylang == 'lang_french'){echo "MESURES INDIVIDUALISÉS";}else{echo "INDIVIDUALIZED SPECIFICATIONS";}?></strong></td>
                </tr>
			
            <tr>
         		<td colspan="10" align="left" bgcolor="#DDDDDD">
					<strong>PT:</strong><input name="PT" type="text" class="formField2" id="PT" value="<?php echo $listItem[PT];?>" size="4" maxlength="4">&nbsp;&nbsp;&nbsp;
					<strong>PA:</strong><input name="PA" type="text" class="formField2" id="PA" value="<?php echo $listItem[PA];?>" size="4" maxlength="4">&nbsp;&nbsp;&nbsp;
					<strong>Vertex:</strong><input name="vertex" type="text" class="formField2" id="vertex" value="<?php echo $listItem[vertex];?>" size="4" maxlength="4">
				</td>
         	 </tr>
                
                 <tr><td colspan="10">&nbsp;</td></tr>
                
                               
        		 <tr>
                	<td colspan="10" bgcolor="#AAAAAA"><strong><?php  if ($mylang == 'lang_french'){echo "ÉPAISSEURS SPÉCIALES";}else{echo "SPECIAL THICKNESS";}?></strong></td>
                 </tr>
			 
              	<tr>
					<td bgcolor="#DDDDDD"  align="left" colspan="10">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						R.E. <b>CT</b>&nbsp;&nbsp;<input name="RE_CT" type="text" class="formText" value="<?php echo $listItem[RE_CT];?>" id="RE_CT" size="4" maxlength="6">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					    L.E. <b>CT</b>&nbsp;&nbsp;<input name="LE_CT" type="text" class="formText" value="<?php echo $listItem[LE_CT];?>" id="LE_CT" size="4" maxlength="6">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					
						R.E. <b>ET</b>&nbsp;&nbsp;<input name="RE_ET" type="text" class="formText" value="<?php echo $listItem[RE_ET];?>" id="RE_ET" size="4" maxlength="6">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						L.E. <b>ET</b>&nbsp;&nbsp;<input name="LE_ET" type="text" class="formText" value="<?php echo $listItem[LE_ET];?>" id="LE_ET" size="4" maxlength="6">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
					</td>
                </tr>
                
 
                   <tr><td colspan="10">&nbsp;</td></tr>
                
					  
			
	<?php
	//Ne pas afficher  la courbure de base de l'instruction spéciale:
    $special_instructions = $listItem[special_instructions];
	$PositionBaseCurve    = strpos($special_instructions,'BASE CURVE');
	$PositionPoint25      = strpos($special_instructions,'.25');
	$PositionPoint50      = strpos($special_instructions,'.50');
	$PositionPoint75      = strpos($special_instructions,'.75');
					  
	//echo '<br>Position base curve:'. $PositionBaseCurve;
	if ($PositionBaseCurve <> false ){
		//On doit mettre la base curve dans le champ Base_curve	
		$PositionBase = strpos($special_instructions,'BASE');
		//echo '<br>Special instruction:<br>'. $special_instructions;
		//echo '<br>PositionBase:'. $PositionBase;
		$CaracteresASupprimer = $PositionBase + 12;
		
		//if (($PositionPoint25 <> false) || ($PositionPoint50 <> false) || ($PositionPoint75 <> false)){//Signifie qu'on a un .25, .50 ou .75
		//$CaracteresASupprimer = $CaracteresASupprimer+2;
		
		if ($PositionPoint25 <> false ){
			$CaracteresASupprimer = $CaracteresASupprimer+3;	
		}
		if ($PositionPoint50 <> false ){
			$CaracteresASupprimer = $CaracteresASupprimer+3;	
		}
		if ($PositionPoint75 <> false ){
			$CaracteresASupprimer = $CaracteresASupprimer+3;	
		}
		
		$ElementaSupprimer =  substr($special_instructions,$PositionBase,$CaracteresASupprimer);
		//echo '<br>Element a supprimer:'. $ElementaSupprimer;
		$special_instructions = str_replace($ElementaSupprimer,'',$special_instructions);
		//echo '<br>Apres suppression: '. $special_instructions;
	}//End If there is a base curve	  
	?>
			
			
			
			 <tr>
                	<td colspan="10" bgcolor="#AAAAAA">
						<strong><?php  if ($mylang == 'lang_french'){echo "INSTRUCTIONS POUR FABRICATION(Doit être en ANGLAIS)";}else{echo "SPECIAL INSTRUCTIONS FOR LAB( Must be in ENGLISH)";}?></strong>
				 	</td>
             </tr>
			
             
			<tr>
				<td colspan="10" align="left" bgcolor="#DDDDDD">
					<b><?php  if ($mylang == 'lang_french'){echo "Instruction Spéciales:";}else{echo "Special Instructions:";}?></b>
					<input name="special_instructions" type="text" class="formField2" id="special_instructions" value="<?php echo $special_instructions;?>" size="60">			
				</td>
            </tr>
                
			
			    <tr><td colspan="10">&nbsp;</td></tr>
                  
			
			     <tr>
                	<td colspan="10" bgcolor="#AAAAAA">
						<strong><?php  if ($mylang == 'lang_french'){echo "NOTE INTERNE";}else{echo "INTERNAL NOTE";}?></strong>
					</td>
                </tr>
			
                <tr>
                	<td colspan="10" align="left" bgcolor="#DDDDDD">
					<b><?php  if ($mylang == 'lang_french'){echo "Note Interne:";}else{echo "Internal Note:";}?></b> <input name="internal_note" type="text" class="formField2" id="internal_note" value="<?php echo $listItem[internal_note];?>" size="40">
			    	</td>
                </tr>
			 
			 
                <tr><td colspan="10">&nbsp;</td></tr>
                
			
			
			    <tr>
                	<td colspan="10" bgcolor="#AAAAAA">
						<strong><?php  if ($mylang == 'lang_french'){echo "ITEM EXTRA (ADMINISTRATION SEULEMENT)";}else{echo "ADDITIONAL ITEM (MANAGEMENT ONLY)";}?></strong>
					</td>
                </tr>
			
                <tr>
					<td align="left" bgcolor="#DDDDDD" colspan="10">
						<b>Additional Item:</b>
						<input name="extra_product" type="text" class="formField2" id="extra_product" value="<?php echo $listItem[extra_product];?>" size="30">
						<b>&nbsp;&nbsp;&nbsp;Amount:$</b>
						<input name="extra_product_price" type="text" class="formField2" <?php if ($manage_additional_discount=='no'){ echo 'disabled="disabled"';}?> id="extra_product_price" 
							   value="<?php echo $listItem[extra_product_price];?>" size="4">
					</td>
                </tr>
					  
				 <tr><td colspan="10">&nbsp;</td></tr>
					  
				
			
			
			
			
			<tr>
           		<td colspan="3" bgcolor="#AAAAAA" align="center">
					<strong><?php  if ($mylang == 'lang_french'){echo "TYPE DE COMMANDE";}else{echo "JOB TYPE";}?></strong>
				</td>
				
				<td colspan="3" bgcolor="#AAAAAA" align="center">
					<strong><?php  if ($mylang == 'lang_french'){echo "CENTRE OPTIQUE";}else{echo "OPTICAL CENTER";}?></strong>
				</td>
				
				<td colspan="4" bgcolor="#AAAAAA" align="center">
					<strong><?php  if ($mylang == 'lang_french'){echo "COURBURE DE BASE";}else{echo "BASE CURVE";}?></strong>
				</td>
            </tr>
			
			
			<tr>
                	 <td colspan="3" align="center" bgcolor="#DDDDDD">
						 <?php 
						 $query        = "SELECT job_type FROM extra_product_orders WHERE category='Edging' AND order_num='$listItem[order_num]'";
						 $edgingResult = mysql_query($query)	or die  ('I cannot select items because: ' . mysql_error());
						 $edgingItem   = mysql_fetch_array($edgingResult);
						 ?>
							 <select name="job_type" >
								 <option value="Uncut">Uncut</option>
								 <option value="Edge and Mount" <?php if ($edgingItem[job_type]=="Edge and Mount") echo "selected=\"selected\"";?>>Edge and Mount</option>
								 <option value="remote edging"  <?php if ($edgingItem[job_type]=="remote edging")  echo "selected=\"selected\"";?>>Remote Edging</option>
							 </select>
					 </td>
				
				
					<td colspan="3" align="center" bgcolor="#DDDDDD">
						<b>O.C:</b><input name="optical_center" type="text" class="formField2" id="optical_center" value="<?php echo $listItem[optical_center];?>" size="6" maxlength="6">
					</td>
                 
					<td colspan="4" bgcolor="#DDDDDD" align="center">
							 <select name="base_curve" class="formText" id="base_curve">
								<option selected="selected" value="" >None/Aucune</option>
								<option value="0.50" <?php if ($listItem[base_curve]==0.50) echo ' selected="selected"';?>>0.50</option>
								<option value="0.75" <?php if ($listItem[base_curve]==0.75) echo ' selected="selected"';?>>0.75</option>
								<option value="1"    <?php if ($listItem[base_curve]==1)    echo ' selected="selected"';?>>1</option>
								<option value="1.25" <?php if ($listItem[base_curve]==1.25) echo ' selected="selected"';?>>1.25</option>
								<option value="1.50" <?php if ($listItem[base_curve]==1.50) echo ' selected="selected"';?>>1.50</option>
								<option value="1.75" <?php if ($listItem[base_curve]==1.75) echo ' selected="selected"';?>>1.75</option>	
								<option value="2"    <?php if ($listItem[base_curve]==2)    echo ' selected="selected"';?>>2</option>
								<option value="2.25" <?php if ($listItem[base_curve]==2.25) echo ' selected="selected"';?>>2.25</option>
								<option value="2.50" <?php if ($listItem[base_curve]==2.50) echo ' selected="selected"';?>>2.50</option>
								<option value="2.75" <?php if ($listItem[base_curve]==2.75) echo ' selected="selected"';?>>2.75</option>
								<option value="3"    <?php if ($listItem[base_curve]==3)    echo ' selected="selected"';?>>3</option>
								<option value="3.25" <?php if ($listItem[base_curve]==3.25) echo ' selected="selected"';?>>3.25</option>
								<option value="3.50" <?php if ($listItem[base_curve]==3.50) echo ' selected="selected"';?>>3.50</option>
								<option value="3.75" <?php if ($listItem[base_curve]==3.75) echo ' selected="selected"';?>>3.75</option>
								<option value="4" 	 <?php if ($listItem[base_curve]==4)    echo ' selected="selected"';?>>4</option>
								<option value="4.25" <?php if ($listItem[base_curve]==4.25) echo ' selected="selected"';?>>4.25</option>
								<option value="4.50" <?php if ($listItem[base_curve]==4.50) echo ' selected="selected"';?>>4.50</option>
								<option value="4.75" <?php if ($listItem[base_curve]==4.75) echo ' selected="selected"';?>>4.75</option>
								<option value="5" 	 <?php if ($listItem[base_curve]==5)    echo ' selected="selected"';?>>5</option>
								<option value="5.25" <?php if ($listItem[base_curve]==5.25) echo ' selected="selected"';?>>5.25</option>
								<option value="5.50" <?php if ($listItem[base_curve]==5.50) echo ' selected="selected"';?>>5.50</option>
								<option value="5.75" <?php if ($listItem[base_curve]==5.75) echo ' selected="selected"';?>>5.75</option>
								<option value="6" 	 <?php if ($listItem[base_curve]==6   ) echo ' selected="selected"';?>>6</option>
								<option value="6.25" <?php if ($listItem[base_curve]==6.25) echo ' selected="selected"';?>>6.25</option>
								<option value="6.50" <?php if ($listItem[base_curve]==6.50) echo ' selected="selected"';?>>6.50</option>
								<option value="6.75" <?php if ($listItem[base_curve]==6.75) echo ' selected="selected"';?>>6.75</option>
								<option value="7" 	 <?php if ($listItem[base_curve]==7)    echo ' selected="selected"';?>>7</option>
								<option value="7.25" <?php if ($listItem[base_curve]==7.25) echo ' selected="selected"';?>>7.25</option>
								<option value="7.50" <?php if ($listItem[base_curve]==7.50) echo ' selected="selected"';?>>7.50</option>
								<option value="7.75" <?php if ($listItem[base_curve]==7.75) echo ' selected="selected"';?>>7.75</option>
								<option value="8" 	 <?php if ($listItem[base_curve]==8)    echo ' selected="selected"';?>>8</option>   
								<option value="8.25" <?php if ($listItem[base_curve]==8.25) echo ' selected="selected"';?>>8.25</option>
								<option value="8.50" <?php if ($listItem[base_curve]==8.50) echo ' selected="selected"';?>>8.50</option>
								<option value="8.75" <?php if ($listItem[base_curve]==8.75) echo ' selected="selected"';?>>8.75</option>
								<option value="9" 	 <?php if ($listItem[base_curve]==9)    echo ' selected="selected"';?>>9</option> 
								<option value="9.25" <?php if ($listItem[base_curve]==9.25) echo ' selected="selected"';?>>9.25</option>
								<option value="9.50" <?php if ($listItem[base_curve]==9.50) echo ' selected="selected"';?>>9.50</option>
								<option value="9.75" <?php if ($listItem[base_curve]==9.75) echo ' selected="selected"';?>>9.75</option>
								<option value="10" 	 <?php if ($listItem[base_curve]==10)   echo ' selected="selected"';?>>10</option> 
							</select>	 
					 
					</td> 
					 
					 
                </tr>  
					  
					  
                
                 <tr><td colspan="10">&nbsp;</td></tr>
                
                <tr>
                  <td colspan="10" bgcolor="#E08AC5"><strong>EXTRAS</strong></td>
                </tr>
					  
				 
			    <tr>
					<td colspan="10" bgcolor="#AAAAAA">
						<strong><?php  if ($mylang == 'lang_french'){echo "MIROIR";}else{echo "MIRROR";}?></strong>
					</td>
                </tr>  
					  
				<tr>
					<td align="left" colspan="10" bgcolor="#DDDDDD">
						<?php 
						$query="SELECT tint_color FROM extra_product_orders WHERE category='Mirror' AND order_num='$listItem[order_num]'";
						$MirrorResult=mysql_query($query)							or die  ('I cannot select items because: ' . mysql_error());  
						$MirrorItem=mysql_fetch_array($MirrorResult);
						?> 

						<select name="mirror" id="mirror">
							<option value="None" 	  			<?php if ($MirrorItem[tint_color]=="None")   				echo "selected=\"selected\"";?>>None</option>
							<option disabled="disabled">SWISS MIRRORS</option> 
							<option value="Aston"  	    	    <?php if ($MirrorItem[tint_color]=="Aston")					echo "selected=\"selected\"";?>>Aston</option>
							<option value="Balloon Blue"  	    <?php if ($MirrorItem[tint_color]=="Balloon Blue")			echo "selected=\"selected\"";?>>Balloon Blue</option>
							<option value="Canyon"  	    	<?php if ($MirrorItem[tint_color]=="Canyon")				echo "selected=\"selected\"";?>>Canyon</option>
							<option value="Dona"  	    		<?php if ($MirrorItem[tint_color]=="Dona")					echo "selected=\"selected\"";?>>Dona</option>
							<option value="Ocean Flash"  	    <?php if ($MirrorItem[tint_color]=="Ocean Flash")			echo "selected=\"selected\"";?>>Ocean Flash</option>
							<option value="Pasha Silver"  	    <?php if ($MirrorItem[tint_color]=="Pasha Silver")			echo "selected=\"selected\"";?>>Pasha Silver</option>
							<option value="Pink Panther"  	    <?php if ($MirrorItem[tint_color]=="Pink Panther")			echo "selected=\"selected\"";?>>Pink Panther</option>
							<option value="Sahara"  	   		<?php if ($MirrorItem[tint_color]=="Sahara")				echo "selected=\"selected\"";?>>Sahara</option>
							<option value="Tank"  	    		<?php if ($MirrorItem[tint_color]=="Tank")					echo "selected=\"selected\"";?>>Tank</option>
							<option value="Pine Green"  	    <?php if ($MirrorItem[tint_color]=="Pine Green")			echo "selected=\"selected\"";?>>Pine Green</option>
							<option disabled="disabled">ESSILOR MIRROIRS</option>  	
							<option value="Gold"  	    		<?php if ($MirrorItem[tint_color]=="Gold")					echo "selected=\"selected\"";?>>Gold</option>
							<option value="Green"  	    	    <?php if ($MirrorItem[tint_color]=="Green")					echo "selected=\"selected\"";?>>Green</option>
							<option value="Ocean Blue"  	    <?php if ($MirrorItem[tint_color]=="Ocean Blue")			echo "selected=\"selected\"";?>>Ocean Blue</option>
							<option value="Red"  	    		<?php if ($MirrorItem[tint_color]=="Red")					echo "selected=\"selected\"";?>>Red</option>
							<option value="Silver"  	   	 	<?php if ($MirrorItem[tint_color]=="Silver")				echo "selected=\"selected\"";?>>Silver</option>
							<option value="Yellow"  	   		<?php if ($MirrorItem[tint_color]=="Yellow")				echo "selected=\"selected\"";?>>Yellow</option>
						</select> 
					</td>
                </tr>	  
					  

                
                <tr><td colspan="10">&nbsp;</td></tr>
                
                
			
			
			<tr>
				<td colspan="10" bgcolor="#AAAAAA">
					<strong><?php  if ($mylang == 'lang_french'){echo "TEINTE";}else{echo "TINT";}?></strong>
				</td>
            </tr> 
			
			<tr>
					
                <td align="left" colspan="10" bgcolor="#DDDDDD">
					
              
					<?php 
					$query      = "SELECT tint,tint_color,from_perc,to_perc FROM extra_product_orders WHERE category='Tint' AND order_num='$listItem[order_num]'";
					$tintResult = mysql_query($query) or die  ('I cannot select items because: ' . mysql_error());
					$tintItem   = mysql_fetch_array($tintResult);
					?>
					<select name="tint" id="tint">
						<option value="None" >None</option>
						<option value="Solid" <?php if (strtolower($tintItem[tint])=="solid") echo "selected=\"selected\"";?>>Solid</option>
						<option value="Gradient" <?php if (strtolower($tintItem[tint])=="gradient") echo "selected=\"selected\"";?>>Gradient</option>
					</select>
               
					From <input name="from_perc" id="from_perc" type="text" class="formField2" value="<?php echo $tintItem[from_perc];?>" size="2" maxlength="4" />&nbsp;% 
					To   <input name="to_perc"   id="to_perc"   type="text" class="formField2" value="<?php echo $tintItem[to_perc];?>"   size="2" maxlength="4">&nbsp;%  

					<b>Tint Color:</b>

				
						<select name="tint_color" id="tint_color">
							<option value="None">None</option>
							<option value="Brown" 			<?php if ($tintItem[tint_color]=="Brown")  			echo "selected=\"selected\"";?>>Brown</option>
							<option value="Grey"  			<?php if ($tintItem[tint_color]=="Grey")   			echo "selected=\"selected\"";?>>Grey</option>
							<option value="Blue"  			<?php if ($tintItem[tint_color]=="Blue")   			echo "selected=\"selected\"";?>>Blue</option>
							<option value="Green" 			<?php if ($tintItem[tint_color]=="Green")  			echo "selected=\"selected\"";?>>Green</option>
							<option value="aston"   		<?php if ($tintItem[tint_color]=="aston")  			echo "selected=\"selected\"";?>>Aston</option> 
							<option value="RAV"   			<?php if ($tintItem[tint_color]=="RAV")  			echo "selected=\"selected\"";?>>RAV</option>
							<option value="SW007"     		<?php if ($tintItem[tint_color]=="SW007")  			echo "selected=\"selected\"";?>>SW007</option>
							<option value="SW025/85"  		<?php if ($tintItem[tint_color]=="SW025/85")		echo "selected=\"selected\"";?>>SW025/85</option> 
							<option value="SW016"     		<?php if ($tintItem[tint_color]=="SW016")  			echo "selected=\"selected\"";?>>SW016</option>  
							<option value="SW027/85"  		<?php if ($tintItem[tint_color]=="SW027/85")  		echo "selected=\"selected\"";?>>SW027/85</option>
							<option value="G-15"      		<?php if ($tintItem[tint_color]=="G-15")  			echo "selected=\"selected\"";?>>G-15</option>
							<option value="Yellow"    		<?php if ($tintItem[tint_color]=="Yellow")  		echo "selected=\"selected\"";?>>Yellow</option>
							<option value="SW010"    		<?php if ($tintItem[tint_color]=="SW010")  			echo "selected=\"selected\"";?>>SW010</option>
							<option value="SW025"    		<?php if ($tintItem[tint_color]=="SW025")  			echo "selected=\"selected\"";?>>SW025</option>
							<option value="SW032"    		<?php if ($tintItem[tint_color]=="SW032")  			echo "selected=\"selected\"";?>>SW032</option> 
							<option value="SW036"    		<?php if ($tintItem[tint_color]=="SW036")  			echo "selected=\"selected\"";?>>SW036</option> 
							<option value="SW046"    		<?php if ($tintItem[tint_color]=="SW046")  			echo "selected=\"selected\"";?>>SW046</option>
							<option value="Shooter Yellow"  <?php if ($tintItem[tint_color]=="Shooter Yellow")  echo "selected=\"selected\"";?>>Shooter Yellow</option> 
							<option value="Black Grey"      <?php if ($tintItem[tint_color]=="Black Grey") 	 	echo "selected=\"selected\"";?>>Black Grey</option> 
							<option value="Sky Rose"   	    <?php if ($tintItem[tint_color]=="Sky Rose")  		echo "selected=\"selected\"";?>>Sky Rose</option>
							<option value="Orange"  		<?php if ($tintItem[tint_color]=="Orange")  		echo "selected=\"selected\"";?>>Orange</option>
							<option value="TEN"   			<?php if ($tintItem[tint_color]=="TEN")  			echo "selected=\"selected\"";?>>TEN</option> 
							<option value="TANK"   			<?php if ($tintItem[tint_color]=="TANK")  			echo "selected=\"selected\"";?>>TANK</option> 
							<option value="GOL"   			<?php if ($tintItem[tint_color]=="GOL")  			echo "selected=\"selected\"";?>>GOL</option> 
							<option value="SW054"   		<?php if ($tintItem[tint_color]=="SW054") 			echo "selected=\"selected\"";?>>SW054</option>
							<option value="AZU"   			<?php if ($tintItem[tint_color]=="AZU") 			echo "selected=\"selected\"";?>>AZU</option>
							<option value="SW028"   	    <?php if ($tintItem[tint_color]=="SW028")  			echo "selected=\"selected\"";?>>SW028</option>
							<option value="SW030/50"   		<?php if ($tintItem[tint_color]=="SW030/50") 	 	echo "selected=\"selected\"";?>>SW030/50</option>
							<option value="SW035"   		<?php if ($tintItem[tint_color]=="SW035")  			echo "selected=\"selected\"";?>>SW035</option>
							<option value="SW051"   		<?php if ($tintItem[tint_color]=="SW051")  			echo "selected=\"selected\"";?>>SW051</option>
							<option value="SW062"   		<?php if ($tintItem[tint_color]=="SW062")  			echo "selected=\"selected\"";?>>SW062</option> 
							<option value="SW004"  			<?php if ($tintItem[tint_color]=="SW004")  			echo "selected=\"selected\"";?>>SW004</option>
							<option value="Blue (Rav)"   	<?php if ($tintItem[tint_color]=="Blue (Rav)")  	echo "selected=\"selected\"";?>>Blue (Rav)</option> 
							<option value="Pine Green"      <?php if ($tintItem[tint_color]=="Pine Green")  	echo "selected=\"selected\"";?>>Pine Green</option> 
							<option value="Orange Blaze"    <?php if ($tintItem[tint_color]=="Orange Blaze")  	echo "selected=\"selected\"";?>>Orange Blaze</option>
							<option value="SW034"   		<?php if ($tintItem[tint_color]=="SW034")  			echo "selected=\"selected\"";?>>SW034</option> 
							<option value="SW023"   		<?php if ($tintItem[tint_color]=="SW023")  			echo "selected=\"selected\"";?>>SW023</option> 
							<option value="SW015"  			<?php if ($tintItem[tint_color]=="SW015")  			echo "selected=\"selected\"";?>>SW015</option>
							<option value="Serengetti"  	<?php if ($tintItem[tint_color]=="Serengetti")		echo "selected=\"selected\"";?>>Serengetti</option>
						</select>
				
					
				</td>
					
          	</tr>
                
                 <tr><td colspan="10">&nbsp;</td></tr>
                
                 
			
			<tr>
				<td colspan="10" bgcolor="#AAAAAA">
					<strong><?php  if ($mylang == 'lang_french'){echo "MONTURE";}else{echo "FRAME";}?></strong>
				</td>
            </tr> 
			
			<tr>
				<td colspan="10"  align="left" bgcolor="#DDDDDD">
					  <?php if  ($frameItem[ep_frame_a]=='') {

					  $QueryFrameOrder = "SELECT frame_a,frame_b,frame_ed,frame_dbl FROM orders WHERE order_num ='$listItem[order_num]'";
					  $FrameOrderResult=mysql_query($QueryFrameOrder)		or die  ('I cannot select items because: ' . mysql_error());
					  $DataFrame=mysql_fetch_array($FrameOrderResult);


					  $Frame_A   = $DataFrame['frame_a'];
					  $Frame_B   = $DataFrame['frame_b'];
					  $Frame_ED  = $DataFrame['frame_ed'];
					  $Frame_DBL = $DataFrame['frame_dbl'];
					   }else {
					  $Frame_A   = $frameItem['ep_frame_a'];
					  $Frame_B   = $frameItem['ep_frame_b'];
					  $Frame_ED  = $frameItem['ep_frame_ed'];
					  $Frame_DBL = $frameItem['ep_frame_dbl'];
					   }

					  ?>
					  <b>Type:</b>
					<select name="frame_type" id="frame_type" >
						<option value="Nylon Groove"   <?php if ($listItem['frame_type']=="Nylon Groove")    echo "selected=\"selected\"";?>>Nylon Groove</option>
						<option value="Metal Groove"   <?php if ($listItem['frame_type']=="Metal Groove")    echo "selected=\"selected\"";?>>Metal Groove</option>
						<option value="Plastic"	       <?php if ($listItem['frame_type']=="Plastic")         echo "selected=\"selected\"";?>>Plastic</option>
						<option value="Metal"          <?php if ($listItem['frame_type']=="Metal")           echo "selected=\"selected\"";?>>Metal</option>
						<option value="Edge Polish"    <?php if ($listItem['frame_type']=="Edge Polish")     echo "selected=\"selected\"";?>>Edge Polish</option>
						<option value="Drill and Notch"<?php if ($listItem['frame_type']=="Drill and Notch") echo "selected=\"selected\"";?>>Drill and Notch</option>
					</select> 
			


					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>A:</b> <input name="frame_a" type="text" class="formField2" id="frame_a" value="<?php echo $Frame_A;?>" size="4" maxlength="4" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

					<b>B:</b>
					<input name="frame_b" type="text" class="formField2" id="frame_b" value="<?php echo $Frame_B;?>" size="2" maxlength="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

					 <b>ED:</b>
					<input name="frame_ed" type="text" class="formField2" id="frame_ed" value="<?php echo $Frame_ED ;?>" size="2" maxlength="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

					<b>DBL:</b>
					<input name="frame_dbl" type="text" class="formField2" id="frame_dbl" value="<?php echo $Frame_DBL;?>" size="2" maxlength="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	
			</td>
		</tr>	
			
			
 <tr>         
	<td colspan="10" align="left" bgcolor="#DDDDDD">
	<b><?php if($mylang == 'lang_french'){echo "Fournisseur";}else{echo "Supplier";}?></b>:<input name="supplier" type="text" class="formField2" value="<?php echo $frameItem[supplier];?>" size="10"/>&nbsp;&nbsp;&nbsp;
	<b><?php if($mylang == 'lang_french'){echo "Modèle de monture";}else{echo "Frame Model";}?></b>:<input type="text" name="temple_model_num" size="10" id="temple_model_num" value="<?php echo $frameItem[temple_model_num];?>"/>&nbsp;&nbsp;&nbsp;
	<b><?php if($mylang == 'lang_french'){echo "Couleur";}else{echo "Color";}?></b>:<input type="text" name="color" id="color" size="10" value="<?php echo $frameItem[color];?>"/>&nbsp;&nbsp;&nbsp;
	<?php  
					 $query="SELECT * FROM extra_product_orders WHERE category='Frame' AND order_num='$listItem[order_num]'";
					 $frameResult=mysql_query($query)							or die  ('I cannot select items because: ' . mysql_error());
					 $frameItem=mysql_fetch_array($frameResult);
					 ?>
					 <select name="order_type" id="order_type">
						<option value="None">None</option>
						<option value="Provide"  <?php if ($frameItem[order_type]=="Provide")   echo "selected=\"selected\"";?>>Provide</option>
						<option value="To Follow"<?php if ($frameItem[order_type]=="To Follow") echo "selected=\"selected\"";?>>To Follow</option>
					 </select>				
				 
	 </td>							
</tr>
			 
	
			 
		 <tr><td colspan="10">&nbsp;</td></tr>	 
			 

                
              <tr>
			  	  <td colspan="10" align="center" bgcolor="#AAAAAA"><input name="Cancel" type="button" class="formField2" id="Cancel" value="Cancel" onClick="window.open('report.php', '_top')">			    
					  &nbsp;&nbsp;
					  <input name="Submitbtn" type="submit" id="Submitbtn" class="formField2" value="Update" disabled>
					  <input name="update_redo" type="hidden" value="true">
					  <input name="order_num" type="hidden" value="<?php echo $listItem[order_num] ?>">
					  <input name="user_id" type="hidden" value="<?php echo $listItem[user_id];?>">
					  <input name="pkey" type="hidden" value="<?php echo $pkey;?>"></td>
					  <input type="hidden" name="shape_name_bk_field" id="shape_name_bk_field" value="<?php echo $listItem[shape_name_bk];?>">
				  </td>
              </tr>

			</form>
                        
                  
        
                  
            <form method="post"  enctype="multipart/form-data" action="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/" name="formShape" id="formShape" target="_blank">
          
      
          
			<?php          	
            //Code pour uploader sur S3
            if (!class_exists('S3')) require_once '../s3/S3.php';
                
            // AWS access info
			// TODO - Determine if S3 Bucket is still used, refactor to remove keys - Currently no user exists in AWS with these keys
            if (!defined('awsAccessKey')) define('awsAccessKey', constant('AWS_S3_USER_ACCESS_KEY'));
            if (!defined('awsSecretKey')) define('awsSecretKey', constant('AWS_S3_USER_SECRET_KEY'));
            
            // Check for CURL
            if (!extension_loaded('curl') && !@dl(PHP_SHLIB_SUFFIX == 'so' ? 'curl.so' : 'php_curl.dll'))
                exit("\nERROR: CURL extension not loaded\n\n");
            
            // Pointless without your keys!
            if (awsAccessKey == 'change-this' || awsSecretKey == 'change-this')
                exit("\nERROR: AWS access information required\n\nPlease edit the following lines in this file:\n\n".
				// TODO - Determine if S3 Bucket is still used, refactor to remove keys - Currently no user exists in AWS with these keys
                "define('awsAccessKey', constant('AWS_S3_USER_ACCESS_KEY'));\ndefine('awsSecretKey', constant('AWS_S3_USER_SECRET_KEY'));\n\n");
            
            S3::setAuth(awsAccessKey, awsSecretKey);
            
            //Dans quel Bucket Uploader ces fichiers
            $bucket = 'direct-lens-public';
            $path = 'Shapes/'; // Dans quel dossier
            
            $lifetime = 3600; // Period for which the parameters are valid
            $maxFileSize = (1024 * 1024 * 50); // 50 MB
            
            $metaHeaders = array('uid' => 123);
            $requestHeaders = array(
                'Content-Type'        => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename=${filename}'
            );
            
            $sucess_action_redirect= constant('DIRECT_LENS_URL').'/labAdmin/close_page.php?filename='. $requestHeaders[Content-Disposition];//Page qui se ferme automatiquement
            
            $params = S3::getHttpUploadPostParams(
                $bucket,
                $path,
                S3::ACL_PUBLIC_READ,
                $lifetime,
                $maxFileSize,
                $sucess_action_redirect, // Or a URL to redirect to on success
                $metaHeaders,
                $requestHeaders,
                false // False since we're not using flash
            );
            
            foreach ($params as $p => $v)
                echo "        <input type=\"hidden\" name=\"{$p}\" value=\"{$v}\" />\n";
            ?>
          
                   
                <tr align="center">
                	<td align="left"  bgcolor="#DBD7D7" colspan="8">
                    <?php  if ($mylang == 'lang_french'){
					echo "<strong>ATTACHER UNE FORME:&nbsp;</strong>";
					}else {
					echo "<strong>ATTACH A SHAPE:&nbsp;</strong>";
					} ?></td>
                    </tr>
                  
                  <tr>
                      <td align="left" colspan="8"><?php
                        if ($mylang == 'lang_french'){
                       		echo "Forme présentement attachée:";
                        }else {
                        	echo "Shape currently attached:";
                        }
                       ?>
                       
                      <b><?php if ($listItem['shape_name_bk'] <> '')  echo $listItem['shape_name_bk']; else echo 'None'; ?></b></td>
                  </tr>
                  
                <?php 
				if ($listItem['shape_name_bk'] == ''){
				?>
                <tr>
                   <td align="left">&nbsp;
                   <input type="file"   name="file"       id="file"      onclick="btnupload.disabled=false;btnupload.value='Upload'"  size="40">&nbsp;
                   </td>
                   
                   <td align="left" colspan="7">&nbsp;
                   <input type="submit" name="btnupload" disabled id="btnupload" value="Upload"   onclick="this.disabled=true;this.value='Uploaded';this.form.submit();"/>
                   <input type="hidden" name="order_num"  id="order_num" value="<?php echo $listItem[order_num] ?>"  >
                   </td>
               </tr>
                <?php 
				}
				?>
               
 
                    
              </form>  
             </table>
            
			</td></tr>
			
</table></td>
    </tr>
</table>
  <p>&nbsp;</p>
</body>
</html>