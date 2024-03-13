<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
require('../Connections/sec_connect.inc.php');
include "../includes/getlang.php";

session_start();
if($_SESSION["sessionUser_Id"]=="")
header("Location:loginfail.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $sitename;?></title>



   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}
.select1 {width:100px}
-->
</style>

<script language="JavaScript" type="text/javascript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>


</head>


<body>
<div id="container">
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ifc-masthead.jpg" width="1050" height="175" alt="MyBBGClub"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  	<?php   
	if ($_SESSION['account_type']=='restricted')
	{
	include("includes/sideNavRestricted.inc.php");
	}else{
	include("includes/sideNav.inc.php");
	}
	?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">
		     <div class="loginText">
		       <div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
		       <?php echo $_SESSION["sessionUser_Id"];?></div>
		     <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header">
            <?php 
			 if ($mylang == 'lang_french')
			{
			echo 'Historique de commande';
			}else{
			echo 'Order History';
			}
			?>
             
             
             </div></td><td><div id="headerGraphic"></div></td></tr></table>
	        <div class="Subheader">
            <?php 
			 if ($mylang == 'lang_french')
			{
			echo 'Commande #';
			}else{
			echo 'Order #';
			}
			?>
            
	  <?php echo $_GET[order_num];
			if ($_GET['redo']!=0) echo "R (".$_GET[redo].")";
			?></div>
			<div class="plainText"> <?php //echo $_GET[po_num];?></div>
			<?php
			
			$order_num=$_GET[order_num];
			
			$totalTrayQuant=0;
			$totalBulkQuant=0;
			$prescrQuantity=0;
					
			$user_id=$_SESSION["sessionUser_Id"];
			
			//STOCK TRAY SECTION
			
						$query="SELECT * from orders WHERE user_id='$user_id' and order_num='$order_num' and order_product_type='stock_tray' ORDER by order_item_date,primary_key";//SELECT ALL OPEN TRAY STOCK ORDERS
			$result=mysql_query($query)
					or die  ('I cannot select items because: ' . mysql_error().$query);
			$stocktraycount=mysql_num_rows($result);
			
			if ($stocktraycount != 0){
			
				echo "<table width=\"770\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formBox\">
<tr ><td colspan=\"8\" bgcolor=\"#17A2D2\" class=\"tableHead\">STOCK ITEMS - BY TRAY</td>
  </tr></table>";
					
						while ($listItem=mysql_fetch_array($result)){
						$order_date_processed=$listItem[order_date_processed];/* get the date the order was placed */
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
						include("includes/stockTrayHistoryLE.inc.php");
						$totalPrice=$totalPrice+$itemSubtotal;
						$totalStockPrice=$totalStockPrice+$itemSubtotal;
						
						$totalPriceDsc=$totalPriceDsc+$itemSubtotalDsc;
						$totalStockPriceDsc=$totalStockPriceDsc+$itemSubtotalDsc;
						$itemSubtotalDsc=0;
						$itemSubtotal=0;}
					else{
						include("includes/stockTrayHistoryRE.inc.php");
						}
					} 
			
			
			}//End tray display section
		
			//STOCK BULK SECTION
			
			$query2="SELECT * from orders WHERE user_id='$user_id' and order_num='$order_num' and order_product_type='stock' ORDER by order_item_date";//SELECT ALL OPEN STOCK ORDERS
			$result2=mysql_query($query2)
					or die  ('I cannot select items because: ' . mysql_error().$query2);
			$stockusercount=mysql_num_rows($result2);
			if ($stockusercount != 0){
			
			 echo '<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td bgcolor="#17A2D2" class="tableHead">STOCK ITEMS - BULK</td>
              </tr>
            </table>';
					
					while ($listItem=mysql_fetch_array($result2)){
					$order_date_processed=$listItem[order_date_processed];/* get the date the order was placed */
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
						include("includes/stockHistory.inc.php");
					} 
			}
			//PRESCRIPTION SECTION
						$query="SELECT * from orders WHERE user_id='$user_id' and order_num='$order_num' and order_product_type='exclusive' ORDER by order_item_date";//SELECT ALL OPEN PRESCRIPTION ORDERS
			$result=mysql_query($query)
					or die  ('I cannot select items because: ' . mysql_error().$query);
			$usercount=mysql_num_rows($result);
			if ($usercount != 0){
			
			 echo '<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td bgcolor="#17A2D2" class="tableHead">PRESCRIPTION ITEMS</td>
              </tr>
            </table>';
					
					while ($listItem=mysql_fetch_array($result)){
						
					$bl_query="SELECT * from additional_discounts WHERE orders_id='$listItem[primary_key]'";//GET BUYING LEVEL DISCOUNT
					$bl_result=mysql_query($bl_query)
						or die  ('I cannot select bl items because: ' . mysql_error().$bl_query);
					$bl_listItem=mysql_fetch_array($bl_result);
					$buying_level_dsc=$bl_listItem[buying_level_discount];
				
					$e_query="SELECT * from extra_product_orders WHERE order_id='$listItem[primary_key]'";//GET EXTRA PRODUCT PRICES
					$e_result=mysql_query($e_query)
						or die  ('I cannot select ep items because: ' . mysql_error().$e_query);
					$e_usercount=mysql_num_rows($e_result);
					$e_total_price=0;
					$e_products_string="";
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
								$e_products_string.="<br />Taillage: ".$e_listItem[price];
								
								$e_order_string_edging="<b>Type: </b>".$e_listItem[frame_type]." ";
								$e_order_string_edging.="<b>Job Type: </b>";
								
								if (($e_listItem[job_type] == 'Edge and Mount') && ($mylang == 'lang_french')){
								$e_order_string_frame.= 'Taillé-Monté ';
								}else{
								$e_order_string_frame.= $e_listItem[job_type] . ' ';
								}
								
								
								if ($mylang == 'lang_french'){
								$e_order_string_edging.="<b>Type de commande: </b>Monture ".$e_listItem[order_type]."<br>";
								}else{
								$e_order_string_edging.="<b>Order type: </b>Frame ".$e_listItem[order_type]."<br>";
								}
								
								if ($mylang == 'lang_french'){
								$e_order_string_edging.="<b>Grandeur: A:</b>".$e_listItem[ep_frame_a]." ";
								$e_order_string_edging.="<b>B: </b>".$e_listItem[ep_frame_b]." ";
								$e_order_string_edging.="<b>ED: </b>".$e_listItem[ep_frame_ed]." ";
								$e_order_string_edging.="<b>DBL: </b>".$e_listItem[ep_frame_dbl]." ";
								$e_order_string_edging.="<b>Branche: </b>".$e_listItem[temple]."<br>";
								}else{
								$e_order_string_edging.="<b>Frame: A:</b>".$e_listItem[ep_frame_a]." ";
								$e_order_string_edging.="<b>B: </b>".$e_listItem[ep_frame_b]." ";
								$e_order_string_edging.="<b>ED: </b>".$e_listItem[ep_frame_ed]." ";
								$e_order_string_edging.="<b>DBL: </b>".$e_listItem[ep_frame_dbl]." ";
								$e_order_string_edging.="<b>Temple: </b>".$e_listItem[temple]."<br>";
								}
								
								
								if ($mylang == 'lang_french'){
								$e_order_string_edging.="<b>Fournisseur: </b>".$e_listItem[supplier]." ";
								$e_order_string_edging.="<b>Modèle: </b>".$e_listItem[model]." ";
								$e_order_string_edging.="<b>Couleur: </b>".$e_listItem[color]."<br>";
								}else{
								$e_order_string_edging.="<b>Supplier: </b>".$e_listItem[supplier]." ";
								$e_order_string_edging.="<b>Model: </b>".$e_listItem[model]." ";
								$e_order_string_edging.="<b>Color: </b>".$e_listItem[color]."<br>";
								}
								
								
								}

							if ($e_listItem[category]=="Engraving"){
								
								$e_products_string.="<br />Engraving: ".$e_listItem[price];
								
								$e_order_string_engraving="<b>Engraving: </b>".$e_listItem[engraving]." ";}
							if ($e_listItem[category]=="Tint"){
								
								$e_products_string.="<br />Teinte: ".$e_listItem[price];
								
								$e_order_string_tint="<b>Tint: </b>".$e_listItem[tint].": ";
								if ($e_listItem[tint]=="Solid")
									{ $e_order_string_tint.= $e_listItem[from_perc]."% <b>Couleur:</b> ".$e_listItem[tint_color];}
								else
									{$e_order_string_tint.= $e_listItem[from_perc]."% to ".$e_listItem[to_perc] ."% <b>Couleur:</b> ".$e_listItem[tint_color];}
								}//END IF TINT
							if ($e_listItem[category]=="Prism"){
								$e_products_string.="<br />Prisms: ".$e_listItem[price];
								}
							if ($e_listItem[category]=="Frame"){
								$e_products_string.="<br />Monture: ".$e_listItem[price];
								$e_products_string.="<br />Index élevé: ".$e_listItem[high_index_addition];
								$e_order_string_frame="<b>Type: </b>".$e_listItem[frame_type]." ";
								
								
								$e_order_string_frame.="<b>Job Type: </b>";
								
								if (($e_listItem[job_type] == 'Edge and Mount')&& ($mylang == 'lang_french')){
								$e_order_string_frame.= 'Taillé-Monté ';
								}else{
								$e_order_string_frame.= $e_listItem[job_type] . ' ';
								}							
								
								if ($mylang == 'lang_french'){
								$e_order_string_frame.="<b>Type de commande: </b> ";
								}else{
								$e_order_string_frame.="<b>Order Type:</b> ";
								}
								
								
								if (($e_listItem[order_type] == 'Provide') && ($mylang =='lang_french')){
								$e_order_string_frame.= 'Fournis  ';
								}else{
								$e_order_string_frame.= $e_listItem[order_type]. ' ';
								}
								
								if ($mylang == 'lang_french'){
								$e_order_string_frame.="<b>Grandeur: A:</b>".$e_listItem[ep_frame_a]." ";
								$e_order_string_frame.="<b>B: </b>".$e_listItem[ep_frame_b]." ";
								$e_order_string_frame.="<b>ED: </b>".$e_listItem[ep_frame_ed]." ";
								$e_order_string_frame.="<b>DBL: </b>".$e_listItem[ep_frame_dbl]." ";
								$e_order_string_frame.="<b>Branche: </b>".$e_listItem[temple]."<br>";
								$e_order_string_frame.="<b>Fournisseur: </b>".$e_listItem[supplier]." ";
								$e_order_string_frame.="<b>Modèle: </b>".$e_listItem[model]." ";
								$e_order_string_frame.="<b>Modèle de monture: </b>".$e_listItem[temple_model_num]." ";
								$e_order_string_frame.="<b>Couleur: </b>".$e_listItem[color]."<br>";
								}else{
								$e_order_string_frame.="<b>Frame: A:</b>".$e_listItem[ep_frame_a]." ";
								$e_order_string_frame.="<b>B: </b>".$e_listItem[ep_frame_b]." ";
								$e_order_string_frame.="<b>ED: </b>".$e_listItem[ep_frame_ed]." ";
								$e_order_string_frame.="<b>DBL: </b>".$e_listItem[ep_frame_dbl]." ";
								$e_order_string_frame.="<b>Temple: </b>".$e_listItem[temple]."<br>";
								$e_order_string_frame.="<b>Supplier: </b>".$e_listItem[supplier]." ";
								$e_order_string_frame.="<b>Model: </b>".$e_listItem[model]." ";
								$e_order_string_frame.="<b>Frame Model: </b>".$e_listItem[temple_model_num]." ";
								$queryEnColor = "SELECT color_en FROM ifc_frames_french WHERE color='". $e_listItem[color]. "' LIMIT 0,1";
								$resultEnColor=mysql_query($queryEnColor)or die  ('I cannot select items because: ' . mysql_error().$queryEnColor);
								$DataEnColor=mysql_fetch_array($resultEnColor);
								$e_order_string_frame.="<b>Color: </b>".$DataEnColor[color_en]."<br>";
								}
								
								
							}//END IF FRAME
						}//END WHILE
						$e_total_price=money_format('%.2n',$e_total_price);
					}//END IF E USERCOUNT
						
						
					$order_date_processed=$listItem[order_date_processed];/* get the date the order was placed */
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
						include("includes/prescrOrderHistory.inc.php");
					} //END WHILE listItem
			}//END OF USERCOUNT
			
			if (($usercount==0)&($stockusercount==0)&($stocktraycount==0)){
			echo '<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td bgcolor="#17A2D2" class="tableHead">Your Basket is Currently Empty</td>
              </tr>
            </table>';
				}
			?>
				
				<?php if (($usercount!=0)||($stockusercount!=0)||($stocktraycount!=0))
					include("includes/displayHistoryFooter.inc.php");
				
				if(($_GET[pmt_status]==0) && ($_SESSION['account_type']=='normal'))
					echo "<form action=\"https://direct-lens.com/ifcopticclubca/getCreditInfo.php\" method=\"post\" name=\"ccform\"><input name=\"total_cost\" type=\"hidden\" value=\"$totalPriceDsc\" /><input name=\"order_shipping_cost\" type=\"hidden\" value=\"$order_shipping_cost\" /><input name=\"order_date_processed\" type=\"hidden\" value=\"$order_date_processed\" /><input name=\"order_num\" type=\"hidden\" value=\"$_GET[order_num]\" />";
					
								
			echo "</form>";
				?>
				<div class="Subheader">
		<a href="order_history.php">
      <?php   if ($mylang == 'lang_french')
	{
	echo 'Retour a la liste des commandes';
	}else{
	echo 'Back to order list';
	}?>
        </a></div>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->


</body>
</html>
<?php
mysql_free_result($languages);

mysql_free_result($languagetext);
?>