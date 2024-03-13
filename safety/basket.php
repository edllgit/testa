<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//Démarrer la session
session_start();
//Inclusions
require_once(__DIR__.'/../constants/aws.constant.php');
include "../sec_connectEDLL.inc.php";
include "config.inc.php";
include "../includes/getlang.php";
include_once('includes/dl_order_functions.inc.php');
include_once('includes/dl_ex_prod_functions.inc.php');
?>
<?php
if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");

mysqli_query($con,"SET CHARACTER SET UTF8");

if ($_POST[delete]=="true"){//DELETE EITHER BULK STOCK OR PRESCRIPTION
	deleteOrderItem($_POST[pkey]);
	$_POST[delete]=="false";
	}
	
if ($_POST[delete_tray]=="true"){//DELETE TRAY ITEMS
	deleteTrayOrderItem($_POST[tray_num]);
	$_POST[delete_tray]=="false";
	}
	
	if ($_POST[delete_frame_tray]=="true"){//DELETE TRAY ITEMS
	deleteFrameTrayOrderItem($_POST[tray_num]);
	$_POST[delete_frame_tray]=="false";
}


if ($_POST[update_quantity]=="true"){//UPDATE EITHER BULK STOCK OR PRESCRIPTION QUANTITY
	$pkey=$_POST[pkey];
	$quantity=$_POST[quantity];
	$query="UPDATE orders SET order_quantity='$quantity' WHERE primary_key='$pkey'";
	$result=mysqli_query($con,$query) or die ('Could not update because: ' . mysqli_error($con));
	
	$_POST[update_quantity]=="false";
	}		
if ($_POST[apply_coupon]=="true"){//APPLY COUPON DISCOUNT

	$pkey=$_POST[pkey];
	$coupon_code=$_POST[coupon_code];
	applyCouponCode($pkey,$coupon_code);
	
	$_POST[apply_coupon]=="false";
	}
if ($_POST[remove_coupon]=="true"){//REMOVE COUPON DISCOUNT

	$pkey=$_POST[pkey];
	removeCouponCode($pkey,$coupon_code);
	
	$_POST[remove_coupon]=="false";
	}
	
//SECTION PAYMENTS
if ($_POST[apply_payment]=="true"){//APPLY PAYMENT 

	$pkey           = $_POST[pkey];
	$payment_amount = $_POST[payment_amount];
	$paid_at        = $_POST[paid_at];
	//echo '<br>pkey:'. $pkey;
	//echo '<br>payment_amount:'. $payment_amount;
	//echo '<br>paid_at:'. $paid_at. '<br>';
	applyPayment($pkey,$payment_amount,$paid_at);
	$_POST[apply_payment]=="false";
	}
	
	
	
	
if ($_POST[from_tray_form]=="true"){//FROM STOCK FORM
	
	for ($i=1;$i<$_SESSION["COUNT"]+1;$i++){
		
			if ($_SESSION["TRAY_REF"][$i]!=""){
			
					catchOrderDataStock();
					$order_product_id=$_SESSION["RE"][$i];
					$order_quantity="1";
					$tray_ref=$_SESSION["TRAY_REF"][$i];
					$pkey=addStockTrayItem($order_product_id,$order_quantity,"R.E.",$tray_ref);
		
					catchOrderDataStock();
					$order_product_id=$_SESSION["LE"][$i];
					$order_quantity="1";
					$pkey=addStockTrayItem($order_product_id,$order_quantity,"L.E.",$tray_ref);
					
					$_SESSION["RE"][$i]="";
					$_SESSION["LE"][$i]="";
					$_SESSION["TRAY_REF"][$i]="";}
					
			}//END OF FOR LOOP
		
	$_SESSION["ITEM_NUMBER"]=0;
	$_SESSION["COUNT"]=0;
	$_POST[from_tray_form]="false";
	}


if ($_POST[fromBulkAdd]=="true"){//FROM STOCK BULK FORM
	
	$PosRowCount=$_POST[PosRowCount];
	$NegRowCount=$_POST[NegRowCount];
	$ColCount=$_POST[ColCount];
	
	if ($_SESSION[sessionUserData][purchase_unit]=="single")
		$unit=1;
		else $unit=2;
	
	for($Rows=1;$Rows<=$PosRowCount;$Rows++){//POSITIVE SPHERES
		for($Cols=1;$Cols<=$ColCount;$Cols++){
			
			if ($_POST[PosQuantity][$Rows][$Cols]!=""){
				catchOrderDataStock();
				//echo "<br> Quant:".$_POST[NegQuantity][$Rows][$Cols]." key:".$_POST[NegKey][$Rows][$Cols];
				$order_product_id=$_POST[PosKey][$Rows][$Cols];
				$order_quantity=$_POST[PosQuantity][$Rows][$Cols]*$unit;
				
				
				$pkey=addStockItem($order_product_id,$order_quantity);
			}
		}
	}
	
	for($Rows=1;$Rows<=$NegRowCount;$Rows++){//NEGATIVE SPHERES
		for($Cols=1;$Cols<=$ColCount;$Cols++){
			
			if ($_POST[NegQuantity][$Rows][$Cols]!=""){
				catchOrderDataStock();
				//echo "<br> Quant:".$_POST[NegQuantity][$Rows][$Cols]." key:".$_POST[NegKey][$Rows][$Cols];
				$order_product_id=$_POST[NegKey][$Rows][$Cols];
				$order_quantity=$_POST[NegQuantity][$Rows][$Cols]*$unit;
				$pkey=addStockItem($order_product_id,$order_quantity);
			}
		}
	}
	
$_POST[fromBulkAdd]="false";
}

if ($_POST[fromStock]=="true"){//FROM STOCK FORM
	
	for ($i=0; $i<$_POST[itemCount]+1; $i++){
		if ($_POST[checkbox][$i]=="true"){
		
		catchOrderDataStock();
		$order_product_id=$_POST[pkey][$i];
		$order_quantity=$_POST[quantity][$i];
		$pkey=addStockItem($order_product_id,$order_quantity);
		}
	}
	
	$_POST[fromStock]="false";
	}
	
if ($_POST[fromPrescription]=="true"){//FROM PRESCRIPTION FORM
	$order_product_id=$_POST[product_id];
	$order_quantity=$_POST[quantity];
	$pkey=addPrescriptionItem($order_product_id,$order_quantity);
	addOrderToAdditionalDiscounts($pkey,$_SESSION['sessionUserData']['buying_level']);
	addExtraProducts($pkey);
	$_POST[fromPrescription]="false";
	
	if ($_SESSION['PrescrData']['myupload']){////////////////////set up for xml upload
		$_SESSION['PrescrData']['mypkey'] = $pkey;
	}
	//Remove the Rx from the session variable
	$_SESSION['PrescrData']["authorized_by"]		= '';
	$_SESSION['PrescrData'] = '';
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $sitename;?></title>

<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}

-->
</style>
<script src="../couponFunctions.js" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript">
function test_coupon(theForm)
{
	if (theForm.coupon_code.value== ""){
    	alert("Please enter a coupon code.");
    	theForm.coupon_code.focus();
   	 return (false);
  	}
	
	var TEXT_VAR_ID="textVar"+theForm.pkey.value;
	
	var TEXT_VAR_VALUE=eval("theForm.textVar"+theForm.pkey.value+".value");
	
	validateCoupon('checkCoupon.php',theForm.coupon_code.value,theForm.pkey.value,theForm.orderTotal.value,TEXT_VAR_ID);
	
	TEXT_VAR_VALUE=eval("theForm.textVar"+theForm.pkey.value+".value");
	
	if (TEXT_VAR_VALUE=="valid"){
		return (true);}
	else{
		alert("We're sorry, the coupon code you entered "+TEXT_VAR_VALUE);
		return (false);
		}
}
function validate(theForm)
{


}
function validate_quantity(theForm)
{
	var num=parseFloat(theForm.quantity.value);
	if (isNaN(num)) {
	
		 alert("Please enter a numeric value in the quantity field.");
    	theForm.quantity.focus();
    	return (false);
	}
  if (theForm.quantity.value=="0")
  {
    alert("0 quantity is not a valid value. Please click the Remove button if you wish to eliminate this item.");
    theForm.quantity.focus();
    return (false);
  }
}
</script>
   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />

</head>

<body>
<div id="container"> 
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">
<div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $lbl_titlemastbasket_txt;?></div></td><td><div id="headerGraphic">
 
  <?php if ($mylang == 'lang_french'){ ?>
 <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/basket_graphic.gif" alt="prescription search" width="445" height="40" />
   <?php }else{ ?>
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/basket_graphic.gif" alt="prescription search" width="445" height="40" />
  <?php } ?>
  </div></td></tr></table>
<div class="Subheader"><?php echo $lbl_yourorder_txt;?></div>
			
			<?php
			
			$totalTrayQuant=0;
			$totalBulkQuant=0;
			$prescrQuantity=0;
					
			$user_id=$_SESSION["sessionUser_Id"];
			
			//STOCK TRAY SECTION
			
				$query="SELECT * FROM orders WHERE user_id='$user_id' and order_status='basket' and order_product_type='stock_tray' and nom_produit_optipro like '%SECURITE%' ORDER by order_item_date,primary_key  " ;//SELECT ALL OPEN TRAY STOCK ORDERS  stock_tray
				//echo $query;
			$result=mysqli_query($con,$query) or die  ($lbl_error1_txt . mysqli_error($con).$query);
			$stocktraycount=mysqli_num_rows($result);
			
			if ($stocktraycount != 0){
			
				echo "<table width=\"770\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formBox\">
<tr ><td colspan=\"8\" bgcolor=\"#ee7e32\" class=\"tableHead\">".$lbl_stockitemstray_txt."</td></tr></table>";
					
						while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
						$counter++;
					$itemSubtotal=$itemSubtotal+$listItem[order_product_price];
					
					$itemSubtotalDsc=$itemSubtotalDsc+$listItem[order_product_discount];
					
					$totalTrayQuant=$totalTrayQuant+$listItem[order_quantity];
					if ($counter%2==0){
					$itemSubtotal=money_format('%.2n',$itemSubtotal);
						include("includes/stockTrayBasketLE.inc.php");
						
						$totalPrice=$totalPrice+$itemSubtotal;
						$totalStockPrice=$totalStockPrice+$itemSubtotal;
						
						$totalPriceDsc=$totalPriceDsc+$itemSubtotalDsc;
						$totalStockPriceDsc=$totalStockPriceDsc+$itemSubtotalDsc;
						$itemSubtotalDsc=0;
						$itemSubtotal=0;}
					else{
						include("includes/stockTrayBasketRE.inc.php");
					}
					}
			
			}//End tray display section
			
			

			$query="SELECT * from orders WHERE user_id='$user_id' and order_status='basket' and order_product_type='exclusive' ";//SELECT ALL OPEN PRESCRIPTION ORDERS
			
			//echo $query;
			$result=mysqli_query($con,$query)or die  ($lbl_error1_txt . mysqli_error($con).$query);
			$usercount=mysqli_num_rows($result);
			if ($usercount != 0){
			
			 echo '<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
              
              </tr>
            </table>';
					
					while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){//LOOP through prescription basket items
						
					$e_query="SELECT * FROM extra_product_orders WHERE order_id='$listItem[primary_key]'";//GET EXTRA PRODUCT PRICES
					$e_result=mysqli_query($con,$e_query) or die  ($lbl_error1_txt . mysqli_error($con).$e_query);
					$e_usercount=mysqli_num_rows($e_result);
					$e_total_price=0;
					$e_order_string_engraving="";
					$e_order_string_tint="";
					$e_order_string_edging="";
					$e_order_string_frame="";
					
					//COLLECT FRAME DATA from orders table into string to be overwritten if there is edging data
					//$e_order_string_edging="<b>".$lbl_type_bulk."</b>";
					if ($mylang == 'lang_english')  {
					$queryEnName = "SELECT material_en from safety_frames_french WHERE material = '" .  $_SESSION['PrescrData']['FRAME_TYPE'] . "'";
					$ResultEnName=mysqli_query($con,$queryEnName) or die  ('I cannot select items because: ' . mysqli_error($con));
					$DataEnName=mysqli_fetch_array($ResultEnName,MYSQLI_ASSOC);
					//$e_order_string_edging.= $DataEnName[material_en];
					}else{
					//$e_order_string_edging.=   $_SESSION['PrescrData']['FRAME_TYPE'];
					}
				
					if ($e_usercount !=0){
						while ($e_listItem=mysqli_fetch_array($e_result,MYSQLI_ASSOC)){
							$e_total_price=$e_total_price+$e_listItem[price]+$e_listItem[high_index_addition];
							if ($e_listItem[category]=="Edging"){								
							    $e_order_string_edging.="<b>".$lbl_eyea_txt."</b>".$e_listItem[ep_frame_a]." ";
								$e_order_string_edging.="<b>".$lbl_b_txt." </b>".$e_listItem[ep_frame_b]." ";
								$e_order_string_edging.="<b>".$lbl_ed_txt_pl." </b>".$e_listItem[ep_frame_ed]." ";
								$e_order_string_edging.="<b>".$lbl_dbl_txt_pl." </b>".$e_listItem[ep_frame_dbl]." ";
								$e_order_string_edging.="<b>".$lbl_temple_txt_pl." </b>".$e_listItem[temple]."<br>";
								$e_order_string_edging.="<b>".$lbl_supplier_txt_pl." </b>".$e_listItem[supplier]." ";
								$e_order_string_edging.="<b>".$lbl_shapemodel_txt_pl." </b>".$e_listItem[temple_model_num]." ";
								$e_order_string_edging.="<b>".$lbl_color_txt_pl." </b>".$e_listItem[color]."<br>";
								}
						
						
							if ($e_listItem[category]=="Tint"){
								$e_order_string_tint="<b>".$lbl_tint_txt_pl." </b>";
								
								if ($e_listItem[tint]=="Gradient"){
								$e_order_string_tint .= 'Dégradé';
								}
								
								if ($e_listItem[tint]=="Solid"){
								$e_order_string_tint .= 'Unie';
								}
								
								if ($e_listItem[tint]=="Solid 60"){
								$e_order_string_tint .= 'CAT 2 (60%)';
								}
								
								if ($e_listItem[tint]=="Solid 80"){
								$e_order_string_tint .= 'CAT 3 (82%)';
								}
								
								
								if (( $e_listItem[tint_color] == 'Grey') && ($mylang == 'lang_french')) {
								$CouleurFr = 'Gris';
								}
								
								if (($e_listItem[tint_color] == 'Brown')&& ($mylang == 'lang_french'))  {
								$CouleurFr = 'Brun';
								}
								
								if (($e_listItem[tint_color] == 'G-15') && ($mylang == 'lang_french'))  {
								$CouleurFr = 'G-15';
								}
								
								if (( $e_listItem[tint_color] == 'Grey') && ($mylang == 'lang_english'))  {
								$CouleurFr = 'Grey';
								}
								
								if (( $e_listItem[tint_color] == 'Gray') && ($mylang == 'lang_english'))  {
								$CouleurFr = 'Grey';
								}
								
								if (( $e_listItem[tint_color] == 'Gray') && ($mylang<>'lang_english'))  {
								$CouleurFr = 'Grey';
								}
								
								if (($e_listItem[tint_color] == 'Brown')&& ($mylang == 'lang_english'))  {
								$CouleurFr = 'Brown';
								}
								
								if (( $e_listItem[tint_color] == 'G-15') && ($mylang == 'lang_english'))  {
								$CouleurFr = 'G-15';
								}
								
								
								if ($e_listItem[tint]=="Solid")
									{ //$e_order_string_tint.= $e_listItem[from_perc]."% <b>".$lbl_color_txt_pl."</b> ".$e_listItem[tint_color];
									$e_order_string_tint.=  ' ' .$lbl_color_txt_pl. ' ' . $CouleurFr;
									}
								else
									{//$e_order_string_tint.= //$e_listItem[from_perc]."% to ".$e_listItem[to_perc] ."% <b>".$lbl_color_txt_pl."</b> ".$e_listItem[tint_color];
									$e_order_string_tint.=' ' .$lbl_color_txt_pl." ". $CouleurFr;
									}
								}//END IF TINT
						}//END WHILE
						$e_total_price=money_format('%.2n',$e_total_price);
					}
					$itemSubtotal=0;
					
					$QueryPayeParClient  = "SELECT * FROM payments_safety WHERE order_id = " . $listItem[primary_key];
					$resultPayeParClient = mysqli_query($con,$QueryPayeParClient)	or die ('Could not update because: ' . mysqli_error($con));	
					$nbrdeResultat 	     = mysqli_num_rows($resultPayeParClient);
					if ($nbrdeResultat > 0){
					$DataResultat = mysqli_fetch_array($resultPayeParClient,MYSQLI_ASSOC);
					$DejaPayeParClient = $DataResultat[payment_amount];
					}else{
					$DejaPayeParClient  = 0;
					}
					$over_range=$listItem[order_over_range_fee];
					$coupon_dsc=$listItem[coupon_dsc];
					$itemSubtotal=$listItem[order_quantity]*($listItem[order_product_price]+$e_total_price)+$over_range-$coupon_dsc -$DejaPayeParClient ;//UNDISCOUNTED PRICE
					$totalPrice=$totalPrice+$itemSubtotal;//TOTAL WITHOUT DISCOUNT
					
					$itemSubtotalDsc=$listItem[order_quantity]*($listItem[order_product_discount]+$e_total_price)+$over_range-$coupon_dsc -$DejaPayeParClient ;//DISCOUNTED PRICE
					$totalPriceDsc=$totalPriceDsc+$itemSubtotalDsc;//TOTAL WITH DISCOUNT
					$prescrNumber=$prescrNumber+1;
					
					$prescrQuantity=$prescrQuantity+$listItem[order_quantity];//TOTAL PRECRIPTION QUANTITY
					$itemSubtotal=money_format('%.2n',$itemSubtotal);
						include("includes/prescrBasket.inc.php");
					} 
			}
			
			if (($usercount==0)&($stockusercount==0)&($stocktraycount==0)&($stockFrametraycount == 0)){
			echo '<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td bgcolor="#ee7e32" class="tableHead">'.$lbl_empty_basket.'</td>
              </tr>
            </table>';
				}
			?>
				
				<?php if (($usercount!=0)||($stockusercount!=0)||($stocktraycount!=0)||($stockFrametraycount!=0))
					include("includes/basketForm.inc.php");
				
				?>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
 <?php include("footer.inc.php"); ?>
</div><!--END containter-->
</body>
</html>