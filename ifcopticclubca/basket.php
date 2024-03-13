<?php 
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
include "../includes/getlang.php";
include "config.inc.php";

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");

include_once('includes/dl_order_functions.inc.php');
include_once('includes/dl_ex_prod_functions.inc.php');

mysqli_query($con,"SET CHARACTER SET UTF8");

if ($_POST[delete]=="true"){//DELETE PRESCRIPTION
	deleteOrderItem($_POST[pkey]);
	$_POST[delete]=="false";
	}
	
	
if ($_POST[appliquercoupon]=="true"){//APPLY COUPON CODE 'VALIDATION EDLL
	$pkey=$_POST[pkey];
	$coupon_code='valide';
	applyCouponCode($pkey,$coupon_code);
	//rediriger vers la page php qui fera fermer la fenetre
	header("Location:close_page.php");
}	
	
	
	
if ($_POST[delete_tray]=="true"){//DELETE TRAY ITEMS
	deleteTrayOrderItem($_POST[tray_num]);
	$_POST[delete_tray]=="false";
	}
	
if ($_POST[delete_frame_tray]=="true"){//DELETE TRAY ITEMS
	deleteFrameTrayOrderItem($_POST[tray_num]);
	$_POST[delete_frame_tray]=="false";
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
	
	
if ($_POST[fromPrescription]=="true"){//FROM PRESCRIPTION FORM
	$order_product_id=$_POST[product_id];
	$order_quantity=$_POST[quantity];
	$pkey=addPrescriptionItem($order_product_id,$order_quantity);
	addOrderToAdditionalDiscounts($pkey,$_SESSION['sessionUserData']['buying_level']);
	addExtraProducts($pkey);
	$_POST[fromPrescription]="false";
	
	//Remove the Rx from the session variable
	$_SESSION['PrescrData']["EDGE_POLISH"]		    = '';
	$_SESSION['PrescrData']["nwd"]		   			= '';
	$_SESSION['PrescrData']["authorized_by"]		= '';
	$_SESSION['PrescrData']['MIRROR']				= '';
	$_SESSION['PrescrData']['CORRIDOR']				= '';
	$_SESSION['PrescrData']['BASE_CURVE']			= '';
	$_SESSION['PrescrData']['TRAY_NUM']				= '';
	$_SESSION['PrescrData']['REFERENCE_PROMO']		= '';
	$_SESSION['PrescrData']['EYE']			    	= '';
	$_SESSION['PrescrData']['LAST_NAME']	   	 	= '';
	$_SESSION['PrescrData']['FIRST_NAME']	   	 	= '';
	$_SESSION['PrescrData']['PATIENT_REF_NUM'] 	 	= '';
	$_SESSION['PrescrData']['SALESPERSON_ID']  	 	= '';
	$_SESSION['PrescrData']['RE_SPH_NUM']			= '';
	$_SESSION['PrescrData']['RE_SPH_DEC']			= '';
	$_SESSION['PrescrData']['RE_CYL_NUM']			= '';
	$_SESSION['PrescrData']['RE_CYL_DEC']			= '';
	$_SESSION['PrescrData']['RE_SPHERE']			= '';
	$_SESSION['PrescrData']['RE_CYL']				= '';
	$_SESSION['PrescrData']['RE_AXIS']				= '';
	$_SESSION['PrescrData']['RE_ADD']				= '';
	$_SESSION['PrescrData']['WARRANTY']				= '';
	$_SESSION['PrescrData']['LE_CYL']				= '';
	$_SESSION['PrescrData']['LE_SPH_NUM']			= '';
	$_SESSION['PrescrData']['LE_SPH_DEC']			= '';
	$_SESSION['PrescrData']['LE_CYL_NUM']			= '';
	$_SESSION['PrescrData']['LE_CYL_DEC']			= '';
	$_SESSION['PrescrData']['LE_AXIS']				= '';
	$_SESSION['PrescrData']['LE_ADD']				= '';
	$_SESSION['PrescrData']['RE_PR_AX']				= '';
	$_SESSION['PrescrData']['RE_PR_AX2']			= '';
	$_SESSION['PrescrData']['RE_PR_IO']				= '';
	$_SESSION['PrescrData']['RE_PR_UD']				= '';
	$_SESSION['PrescrData']['LE_SPHERE']			= '';
	$_SESSION['PrescrData']['LE_PR_AX']				= '';
	$_SESSION['PrescrData']['LE_PR_AX2']			= '';
	$_SESSION['PrescrData']['LE_PR_IO']				= '';
	$_SESSION['PrescrData']['LE_PR_UD']				= '';
	$_SESSION['PrescrData']['LE_PR_AX']				= '';
	$_SESSION['PrescrData']['LE_PR_AX2']			= '';
	$_SESSION['PrescrData']['RE_PR_AX2']			= '';
	$_SESSION['PrescrData']['RE_PR_AX']				= '';
	$_SESSION['PrescrData']['LE_PD']				= '';
	$_SESSION['PrescrData']['LE_PD_NEAR']			= '';
	$_SESSION['PrescrData']['LE_HEIGHT']			= '';
	$_SESSION['PrescrData']['RE_PD']				= '';
	$_SESSION['PrescrData']['RE_PD_NEAR']			= '';
	$_SESSION['PrescrData']['RE_HEIGHT']			= '';
	$_SESSION['PrescrData']['COATING']				= '';
	$_SESSION['PrescrData']['INDEX']				= '';
	$_SESSION['PrescrData']['PHOTO']				= '';
	$_SESSION['PrescrData']['POLAR']				= '';
	$_SESSION['PrescrData']['FRAME_A']				= '';
	$_SESSION['PrescrData']['FRAME_B']				= '';
	$_SESSION['PrescrData']['FRAME_ED']				= '';
	$_SESSION['PrescrData']['FRAME_DBL']			= '';
	$_SESSION['PrescrData']['FRAME_TYPE']			= '';
	$_SESSION['PrescrData']['ENGRAVING']			= '';
	$_SESSION['PrescrData']['TINT']					= '';
	$_SESSION['PrescrData']['TINT_COLOR']			= '';
	$_SESSION['PrescrData']['FROM_PERC']			= '';
	$_SESSION['PrescrData']['TO_PERC']				= '';
	$_SESSION['PrescrData']['JOB_TYPE']				= '';
	$_SESSION['PrescrData']['ORDER_TYPE']			= '';
	$_SESSION['PrescrData']['SUPPLIER']				= '';
	$_SESSION['PrescrData']['FRAME_MODEL']			= '';
	$_SESSION['PrescrData']['COLOR']				= '';
	$_SESSION['PrescrData']['ORDER_TYPE']			= '';
	$_SESSION['PrescrData']['TEMPLE']				= '';
	$_SESSION['PrescrData']['TEMPLE_MODEL']			= '';
	$_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'] = '';
	$_SESSION['PrescrData']['INTERNAL_NOTE']		= '';
	$_SESSION['PrescrData']['VERTEX']				= '';
	$_SESSION['PrescrData']['PT']					= '';
	$_SESSION['PrescrData']['PA']					= '';
	$_SESSION['PrescrData']['OPTICAL_CENTER']		= '';
	$_SESSION['PrescrData']['CUSHION']                = '';
	$_SESSION['PrescrData']['CUSHION_SELLING_PRICE']  = '';
	$_SESSION['PrescrData']['DUST_BAR']               = '';
	$_SESSION['PrescrData']['DUST_BAR_SELLING_PRICE'] = '';
	$_SESSION['PrescrData']['RE_CT']                  = '';
	$_SESSION['PrescrData']['LE_CT']                  = '';
	$_SESSION['PrescrData']['RE_ET']                  = '';
	$_SESSION['PrescrData']['LE_ET']                  = '';
	
	
	if ($_SESSION['PrescrData']['myupload']){////////////////////set up for xml upload
		$_SESSION['PrescrData']['mypkey'] = $pkey;
	}
	$_SESSION['PrescrData']['myupload']='';
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
}
</script>
   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />

</head>

<body>
<div id="container">
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ifc-masthead.jpg" width="1050" height="175" alt="MyBBGClub"/>
</div>
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
			$prescrQuantity=0;	
			$user_id=$_SESSION["sessionUser_Id"];
		
			$query="SELECT * from orders WHERE user_id='$user_id' and order_status='basket' and order_product_type='exclusive' ORDER by order_item_date";//SELECT ALL OPEN PRESCRIPTION ORDERS
			
			//echo $query;
			$result=mysqli_query($con,$query) or die  ($lbl_error1_txt . mysqli_error($con).$query);
			$usercount=mysqli_num_rows($result);
			if ($usercount != 0){
			
			 echo '<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
              
              </tr>
            </table>';
					
					$CompteurJobBasket = 0;
					while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){//LOOP through prescription basket items
					$CompteurJobBasket = $CompteurJobBasket+1;
						
					$e_query="SELECT * from extra_product_orders WHERE order_id='$listItem[primary_key]'";//GET EXTRA PRODUCT PRICES
					$e_result=mysqli_query($con,$e_query) or die  ($lbl_error1_txt . mysqli_error($con).$e_query);
					$e_usercount=mysqli_num_rows($e_result);
					$e_total_price=0;
					$e_order_string_engraving="";
					$e_order_string_tint="";
					$e_order_string_edging="";
					$e_order_string_frame="";
					
					//COLLECT FRAME DATA from orders table into string to be overwritten if there is edging data
					$e_order_string_edging="<b>".$lbl_type_bulk."</b>";
		
					if ($e_usercount !=0){
						while ($e_listItem=mysqli_fetch_array($e_result,MYSQLI_ASSOC)){
							$e_total_price=$e_total_price+$e_listItem[price];
						
						
							if ($e_listItem[category]=="Edging"){
								
								if (($e_listItem[job_type] == 'Edge and Mount') &&  ($mylang == 'lang_french')){
								$e_order_string_edging.= "Taillé et monté ";
								}	
								$e_order_string_edging.=$e_listItem[frame_type];							
								$e_order_string_edging.= '&nbsp;&nbsp;<b>Job type</b>:'. $e_listItem[job_type] .' '. $lbl_frame_txt." ".$e_listItem[order_type]."<br>";
								$e_order_string_edging.="<b>".$lbl_eyea_txt."</b>".$e_listItem[ep_frame_a]." ";
								$e_order_string_edging.="<b>".$lbl_b_txt." </b>".$e_listItem[ep_frame_b]." ";
								$e_order_string_edging.="<b>".$lbl_ed_txt_pl." </b>".$e_listItem[ep_frame_ed]." ";
								$e_order_string_edging.="<b>".$lbl_dbl_txt_pl." </b>".$e_listItem[ep_frame_dbl]." ";
								$e_order_string_edging.="<b>".$lbl_temple_txt_pl." </b>".$e_listItem[temple]."<br>";
								$e_order_string_edging.="<b>".$lbl_supplier_txt_pl." </b>".$e_listItem[supplier]." ";
								$e_order_string_edging.="<b>".$lbl_shapemodel_txt_pl." </b>".$e_listItem[model].' '. $e_listItem[temple_model_num]." ";
								$e_order_string_edging.="<b>".$lbl_color_txt_pl." </b>".$e_listItem[color]."<br>";
								
							}//Fin edging
								
								
							$e_order_string_mirror = '';
							if ($e_listItem[category]=="Mirror"){
								$e_order_string_mirror.= ': ' . $e_listItem[tint_color]."<br>";								
							}//Fin Mirror	
								
							//DEUT FRAME	
							if ($e_listItem[category]=="Frame"){
								$e_order_string_frame="<b>".$lbl_type_txt_pl." </b>";
								$e_order_string_frame.=   $e_listItem['frame_type'] . ' ';					
								$e_order_string_frame.="<b>".$lbl_jobtype_txt_pl." </b>";
								if (($e_listItem[job_type] == 'Edge and Mount')&&  ($mylang == 'lang_french')){
								$e_order_string_frame.= "Taillé et monté ";
								}
								
								$e_order_string_frame.=$e_listItem[order_type];
								$e_order_string_frame.="<br>";
								
		
								$queryModel = "SELECT temple_model_num from extra_product_orders WHERE order_num = $order_num ";
							
								
								if ($mylang == 'lang_french'){
								$e_order_string_frame.="<b>".$lbl_color_txt_pl." </b>".$e_listItem[color]."<br>";
								}else{
								$queryCouleurEn = "SELECT color_en from ifc_frames_french WHERE color= '". $e_listItem[color] . "' OR color_en= '". $e_listItem[color] . "'  LIMIT 0,1";
								$resultCouleurEN=mysqli_query($con,$queryCouleurEn)		or die ('Could not update because: ' . mysqli_error($con));
								$DataCouleurEn=mysqli_fetch_array($resultCouleurEN,MYSQLI_ASSOC);
								$e_order_string_frame.="<b>".$lbl_color_txt_pl." </b>".$DataCouleurEn[color_en]."<br>";
								}
								
							}//Fin FRAME

							
							
							/*if ($e_listItem[category]=="Engraving"){
								$e_order_string_engraving="<b>".$lbl_engrave_txt_pl." </b>".$e_listItem[engraving]." ";}*/
							
							
							if ($e_listItem[category]=="Tint"){
								$e_order_string_tint="<b>".$lbl_tint_txt_pl." </b>";
								
								
								
								if (($e_listItem[tint]=="Gradient")     && ($mylang == 'lang_french')){
									$e_order_string_tint .= 'Dégradé ';
								}elseif(($e_listItem[tint]=="Gradient") && ($mylang <> 'lang_french')){
									$e_order_string_tint .= 'Gradient ';	
								}
								
							
								
								if (($e_listItem[tint]=="Solid")     && ($mylang == 'lang_french')){
									$e_order_string_tint .= 'Unie ';
								}elseif(($e_listItem[tint]=="Solid") && ($mylang <> 'lang_french')){
									$e_order_string_tint .= 'Solid ';	
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
								
								if (($e_listItem[tint_color] == 'Brown')&& ($mylang == 'lang_english'))  {
								$CouleurFr = 'Brown';
								}
								
								if (( $e_listItem[tint_color] == 'G-15') && ($mylang == 'lang_english'))  {
								$CouleurFr = 'G-15';
								}
								
								
								if ($e_listItem[tint]=="Solid")
									{ 
									$e_order_string_tint.= ' ' .$e_listItem[from_perc]."% <b>".$lbl_color_txt_pl."</b> ".$e_listItem[tint_color];
									}
								else
									{
									$e_order_string_tint.= ' ' . $e_listItem[from_perc]."% to ".$e_listItem[to_perc] ."% <b>".$lbl_color_txt_pl."</b> ".$e_listItem[tint_color];
									}
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
			
			if (($usercount==0)&($stockusercount==0)&($stocktraycount==0)&($stockFrametraycount == 0)){
			echo '<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td bgcolor="#17A2D2" class="tableHead">'.$lbl_empty_basket.'</td>
              </tr>
            </table>';
				}
			?>
				
				<?php if (($usercount!=0)||($stockusercount!=0)||($stocktraycount!=0)||($stockFrametraycount!=0))
					include("includes/basketForm.inc.php");
				
				?>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->

</body>
</html>