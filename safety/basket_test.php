<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";
include "config.inc.php";

session_start();

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");

require('../Connections/sec_connect.inc.php');
include_once('../includes/dl_order_functions.inc.php');
include_once('../includes/dl_ex_prod_functions.inc.php');

if ($_POST[delete]=="true"){//DELETE EITHER BULK STOCK OR PRESCRIPTION
	deleteOrderItem($_POST[pkey]);
	$_POST[delete]=="false";
	}
	
if ($_POST[delete_tray]=="true"){//DELETE TRAY ITEMS
	deleteTrayOrderItem($_POST[tray_num]);
	$_POST[delete_tray]=="false";
	}

if ($_POST[update_quantity]=="true"){//UPDATE EITHER BULK STOCK OR PRESCRIPTION QUANTITY
	$pkey=$_POST[pkey];
	$quantity=$_POST[quantity];
	$query="UPDATE orders SET order_quantity='$quantity' WHERE primary_key='$pkey'";
	$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
	
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
				//print "<br> Quant:".$_POST[NegQuantity][$Rows][$Cols]." key:".$_POST[NegKey][$Rows][$Cols];
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
				//print "<br> Quant:".$_POST[NegQuantity][$Rows][$Cols]." key:".$_POST[NegKey][$Rows][$Cols];
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
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php print $sitename;?></title>

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
	
	validateCoupon('../checkCoupon.php',theForm.coupon_code.value,theForm.pkey.value,theForm.orderTotal.value,TEXT_VAR_ID);
	
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
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">	     
<div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $lbl_titlemastbasket_txt;?></div></td><td><div id="headerGraphic"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/basket_graphic.gif" alt="prescription search" width="445" height="40" /></div></td></tr></table>
<div class="Subheader"><?php echo $lbl_yourorder_txt;?></div>
			
			<?php
			
			$totalTrayQuant=0;
			$totalBulkQuant=0;
			$prescrQuantity=0;
					
			$user_id=$_SESSION["sessionUser_Id"];
			
			//STOCK TRAY SECTION
			
						$query="SELECT * from orders WHERE user_id='$user_id' and order_status='basket' and order_product_type='stock_tray' ORDER by order_item_date,primary_key";//SELECT ALL OPEN TRAY STOCK ORDERS
			$result=mysql_query($query)
					or die  ($lbl_error1_txt . mysql_error().$query);
			$stocktraycount=mysql_num_rows($result);
			
			if ($stocktraycount != 0){
			
				print "<table width=\"770\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formBox\">
<tr ><td colspan=\"8\" bgcolor=\"#17A2D2\" class=\"tableHead\">".$lbl_stockitemstray_txt."</td></tr></table>";
					
						while ($listItem=mysql_fetch_array($result)){
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
			//STOCK BULK SECTION
			
			$query2="SELECT * from orders WHERE user_id='$user_id' and order_status='basket' and order_product_type='stock' ORDER by order_item_date";//SELECT ALL OPEN STOCK ORDERS
			$result2=mysql_query($query2)
					or die  ($lbl_error1_txt . mysql_error().$query2);
			$stockusercount=mysql_num_rows($result2);
			if ($stockusercount != 0){
			
			 print '<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td bgcolor="#17A2D2" class="tableHead">'.$lbl_stockbulk_txt.'</td>
              </tr>
            </table>';
					
					while ($listItem=mysql_fetch_array($result2)){
					$itemSubtotal=0;
					$itemSubtotal=$listItem[order_quantity]*$listItem[order_product_price];
					$totalPrice=$totalPrice+$itemSubtotal;
					$totalStockPrice=$totalStockPrice+$itemSubtotal;
					
					$itemSubtotalDsc=$listItem[order_quantity]*$listItem[order_product_discount];
					$totalPriceDsc=$totalPriceDsc+$itemSubtotalDsc;
					$totalStockPriceDsc=$totalStockPriceDsc+$itemSubtotalDsc;
					
					$totalBulkQuant=$totalBulkQuant+$listItem[order_quantity];
					$itemSubtotal=money_format('%.2n',$itemSubtotal);
						include("includes/stockBasket.inc.php");
					} 
			}
			
			$query="SELECT * from orders WHERE user_id='$user_id' and order_status='basket' and order_product_type='exclusive' ORDER by order_item_date";//SELECT ALL OPEN PRESCRIPTION ORDERS
			$result=mysql_query($query)
					or die  ($lbl_error1_txt . mysql_error().$query);
			$usercount=mysql_num_rows($result);
			if ($usercount != 0){
			
			 print '<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td bgcolor="#17A2D2" class="tableHead">'.$lbl_presitems_txt.'</td>
              </tr>
            </table>';
					
					while ($listItem=mysql_fetch_array($result)){//LOOP through prescription basket items
						
					$e_query="SELECT * from extra_product_orders WHERE order_id='$listItem[primary_key]'";//GET EXTRA PRODUCT PRICES
					$e_result=mysql_query($e_query)
						or die  ($lbl_error1_txt . mysql_error().$e_query);
					$e_usercount=mysql_num_rows($e_result);
					$e_total_price=0;
					$e_order_string_engraving="";
					$e_order_string_tint="";
					$e_order_string_edging="";
					$e_order_string_frame="";
					
					//COLLECT FRAME DATA from orders table into string to be overwritten if there is edging data
					$e_order_string_edging="<b>".$lbl_type_bulk."</b>".$listItem[frame_type]." ";
					$e_order_string_edging.="<b>".$lbl_eyea_txt." </b>".$listItem[frame_a]." ";
					$e_order_string_edging.="<b>".$lbl_b_txt." </b>".$listItem[frame_b]." ";
					$e_order_string_edging.="<b>".$lbl_ed_txt_pl." </b>".$listItem[frame_ed]." ";
					$e_order_string_edging.="<b>".$lbl_dbl_txt_pl." </b>".$listItem[frame_dbl]." ";	
				
					if ($e_usercount !=0){
						while ($e_listItem=mysql_fetch_array($e_result)){
							$e_total_price=$e_total_price+$e_listItem[price]+$e_listItem[high_index_addition];
							if ($e_listItem[category]=="Edging"){
								
								$e_order_string_edging="<b>".$lbl_type_bulk." </b>".$e_listItem[frame_type]." ";
								$e_order_string_edging.="<b>".$lbl_jobtype_txt_pl." </b>".$e_listItem[job_type]." ";
								$e_order_string_edging.="<b>".$lbl_ordertype_txt." </b>".$lbl_frame_txt." ".$e_listItem[order_type]."<br>";
								
								$e_order_string_edging.="<b>".$lbl_eyea_txt."</b>".$e_listItem[ep_frame_a]." ";
								$e_order_string_edging.="<b>".$lbl_b_txt." </b>".$e_listItem[ep_frame_b]." ";
								$e_order_string_edging.="<b>".$lbl_ed_txt_pl." </b>".$e_listItem[ep_frame_ed]." ";
								$e_order_string_edging.="<b>".$lbl_dbl_txt_pl." </b>".$e_listItem[ep_frame_dbl]." ";
								$e_order_string_edging.="<b>".$lbl_temple_txt_pl." </b>".$e_listItem[temple]."<br>";
								
								$e_order_string_edging.="<b>".$lbl_supplier_txt_pl." </b>".$e_listItem[supplier]." ";
								$e_order_string_edging.="<b>".$lbl_shapemodel_txt_pl." </b>".$e_listItem[model]." ";
								$e_order_string_edging.="<b>".$lbl_color_txt_pl." </b>".$e_listItem[color]."<br>";
				
								}
							if ($e_listItem[category]=="Frame"){
								$e_order_string_frame="<b>".$lbl_type_txt_pl." </b>".$e_listItem[frame_type]." ";
								$e_order_string_frame.="<b>".$lbl_jobtype_txt_pl." </b>".$e_listItem[job_type]." ";
								$e_order_string_frame.="<b>".$lbl_ordertype_txt." </b>Frame ".$e_listItem[order_type]."<br>";
								
								$e_order_string_frame.="<b>".$lbl_eyea_txt."</b>".$e_listItem[ep_frame_a]." ";
								$e_order_string_frame.="<b>".$lbl_b_txt." </b>".$e_listItem[ep_frame_b]." ";
								$e_order_string_frame.="<b>".$lbl_ed_txt_pl." </b>".$e_listItem[ep_frame_ed]." ";
								$e_order_string_frame.="<b>".$lbl_dbl_txt_pl." </b>".$e_listItem[ep_frame_dbl]." ";
								$e_order_string_frame.="<b>".$lbl_temple_txt_pl." </b>".$e_listItem[temple]."<br>";
								
								$e_order_string_frame.="<b>".$lbl_supplier_txt_pl." </b>".$e_listItem[supplier]." ";
								$e_order_string_frame.="<b>".$lbl_shapemodel_txt_pl." </b>".$e_listItem[model]." ";
								$e_order_string_frame.="<b>".$lbl_framemodel_txt_pl." </b>".$e_listItem[temple_model_num]." ";
								$e_order_string_frame.="<b>".$lbl_color_txt_pl." </b>".$e_listItem[color]."<br>";
								
							}
							
							if ($e_listItem[category]=="Engraving"){
								$e_order_string_engraving="<b>".$lbl_engrave_txt_pl." </b>".$e_listItem[engraving]." ";}
							if ($e_listItem[category]=="Tint"){
								$e_order_string_tint="<b>".$lbl_tint_txt_pl." </b>".$e_listItem[tint].": ";
								if ($e_listItem[tint]=="Solid")
									{ $e_order_string_tint.= $e_listItem[from_perc]."% <b>".$lbl_color_txt_pl."</b> ".$e_listItem[tint_color];}
								else
									{$e_order_string_tint.= $e_listItem[from_perc]."% to ".$e_listItem[to_perc] ."% <b>".$lbl_color_txt_pl."</b> ".$e_listItem[tint_color];}
								}//END IF TINT
						}//END WHILE
						$e_total_price=money_format('%.2n',$e_total_price);
					}
					$itemSubtotal=0;
					$over_range=$listItem[order_over_range_fee];
					$coupon_dsc=$listItem[coupon_dsc];
					$itemSubtotal=$listItem[order_quantity]*($listItem[order_product_price]+$e_total_price)+$over_range-$coupon_dsc;//UNDISCOUNTED PRICE
					$totalPrice=$totalPrice+$itemSubtotal;//TOTAL WITHOUT DISCOUNT
					
					$itemSubtotalDsc=$listItem[order_quantity]*($listItem[order_product_discount]+$e_total_price)+$over_range-$coupon_dsc;//DISCOUNTED PRICE
					$totalPriceDsc=$totalPriceDsc+$itemSubtotalDsc;//TOTAL WITH DISCOUNT
					$prescrNumber=$prescrNumber+1;
					
					$prescrQuantity=$prescrQuantity+$listItem[order_quantity];//TOTAL PRECRIPTION QUANTITY
					$itemSubtotal=money_format('%.2n',$itemSubtotal);
						include("includes/prescrBasket.inc.php");
					} 
			}
			
			if (($usercount==0)&($stockusercount==0)&($stocktraycount==0)){
			print '<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td bgcolor="#17A2D2" class="tableHead">'.$lbl_empty_basket.'</td>
              </tr>
            </table>';
				}
			?>
				
				<?php if (($usercount!=0)||($stockusercount!=0)||($stocktraycount!=0))
					include("includes/basketForm_test.inc.php");
				
				?>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footerBox">
  
</div>
</div><!--END containter-->
</body>
</html>
<?php
mysql_free_result($languages);

mysql_free_result($languagetext);
?>