<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";
include "config.inc.php";
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php print $sitename;?></title>
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="favicon.ico"/>
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

<script>
function reloadpage(){
    document.getElementById("stock").submit();
}
</script>

<script type="text/javascript" src="../includes/formvalidator.js"></script>
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
      <div class="loginText">
        <div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
        </div><form action="validate_frames.php" method="post" name="stock" id="stock"  onSubmit="return validate(this)">
      	<div class="header"> 
	<?php  if ($mylang == 'lang_french') {  ?>
           Votre Commande de montures 
    <?php  }else{ ?>
            Your Frames Order
    <?php  } ?>    </div>
        <?php 
	if (isset($_POST[quantity_ordered]))
	{
		// Affichage des couples clé / valeur
		foreach($_POST[quantity_ordered] as $cle => $valeur)
		{
			if ($valeur > 0){//Si une quantité a été commandée, on affiche le detail
				//echo  '<br/>' ,$cle ,' : ', $valeur ,'<br/>';
				$UserId         	  = $_SESSION["sessionUser_Id"];
				$QueryLab       	  = "SELECT main_lab, currency FROM accounts WHERE user_id = '$UserId'";
				$ResultLab      	  = mysql_query($QueryLab)		or die ("Erreur durant la selection");
				$DataLab        	  = mysql_fetch_array($ResultLab);	
				$QueryProduct     	  = "SELECT * FROM ifc_frames_french WHERE ifc_frames_id = $cle";
				$ResultProduct     	  = mysql_query($QueryProduct)		or die ("Erreur durant la selection");
				$DataProduct          = mysql_fetch_array($ResultProduct);	
				$Quantity 			  = $valeur;
				$OrderNum        	  = -1;
				$ProductName		  = $DataProduct[misc_unknown_purpose] . ' #'  . $DataProduct[code] . ' A:'  . $DataProduct[frame_a];
				$FrameType  		  = $DataProduct[material_en];
				$Material 			  = $DataProduct[material_en]; 
			    $ProductID            = $DataProduct[ifc_frames_id]; 
				if ($_POST['TRAY_NUM'] <> ''){
				$TrayNum        	  =  $_POST['TRAY_NUM'] ;
				}else{
				$today		     	  = mktime(0,0,0,date("m"),date("d"),date("Y"));
				$DateduJour   	      = date("Y-m-d", $today);
				$TrayNum              = $UserId  . '_'. $DateduJour;
				}
				
				$Lab             	  = $DataLab[main_lab];
				$PrescriptLab    	  = 21;
				$Eye 			 	  = 'Both';
				$OrderItemNumber 	  = $cle;//ID du frame commandé	
				$today		     	  = mktime(0,0,0,date("m"),date("d"),date("Y"));
				$DateProcessed   	  = date("Y-m-d", $today);
				$DateShipped     	  = "0000-00-00";
				$OrderItemDate   	  = $DateProcessed;
				$ShippingCost		  = 0;
				$OrderType 			  = "frame_stock_tray";
				$OrderStatus 		  = "pre-basket";
				$ShippingMethod 	  = "Stock Shipping";
				$OrderFrom 			  = "ifcclubca";
				$CustomerIP		 	  = $_SERVER['REMOTE_ADDR'];
				if ($_SESSION["CompteEntrepot"]   == 'yes'){
				$StockPrice 	      = $DataProduct['stock_price_entrepot'];
				//Base: prix stock, Si le client commande au moins 10 montures, on lui charge le prix escompté
				}elseif($Quantity >= 10){  
				$StockPrice 	 	  = $DataProduct['stock_price_with_discount'];//
				}else{
				$StockPrice 	 	  = $DataProduct['stock_price'];//
				}
				$OrderProductDiscount = $StockPrice;
				$OrderTotal 		  = $Quantity * $StockPrice;
				$Currency 			  = 'CA';

				$queryInsert = "INSERT INTO orders (user_id,order_num,tray_num,lab,prescript_lab,
				eye,order_item_number,order_date_processed,order_date_shipped,order_item_date,order_quantity, 
				order_product_name,order_product_id, order_product_material, order_product_price,order_product_discount,
				order_shipping_cost,order_shipping_method, order_product_type, order_status,order_total, currency, ip, order_from, frame_type)
				VALUES ('$UserId', $OrderNum, '$TrayNum', $Lab, $PrescriptLab, '$Eye', $OrderItemNumber, '$DateProcessed', '$DateShipped', '$OrderItemDate', $Quantity,                '$ProductName','$ProductID', '$Material','$StockPrice','$OrderProductDiscount', '$ShippingCost', '$ShippingMethod', '$OrderType' , '$OrderStatus',                '$OrderTotal' , '$Currency', '$CustomerIP', '$OrderFrom','$FrameType')";
				
				//echo '<br><br>'. $queryInsert;
				$ResultInsert    = mysql_query($queryInsert)		or die ("Erreur durant la selection<br><br>". $queryInsert .'<br><br>' . mysql_error());
				
			}//End if a quantity of this frame has been ordered
		}//end for each
	}//End IF value isset
	
	 if ($_POST[from_validate_form]=="yes")//IF FORM IS POSTED
	   {

			//Case Delete
			if ($_POST[DeleteSelected]=='Delete Selected Frames'){
				foreach($_POST[PktoDelete] as $cle => $valeur)
				{	
					if ($cle <> ''){
					$queryDelete = "DELETE FROM ORDERS WHERE primary_key = $cle";
					$resultDelete    = mysql_query($queryDelete)		or die ("Erreur durant la maj<br><br>". $queryDelete .'<br>' . mysql_error());
					}
				}
				
			}
			
			   
			//Case Order more frames
			if ($_POST[RedirectFrameOrder]=='Add More Frames to my Order'){
				header("Location:stock_frames2.php");
				exit();
			}
			
			
			//Case Confirm Order
			if ($_POST[ConfirmOrder]=='Add Frames to Basket'){
			$UserId         	  = $_SESSION["sessionUser_Id"];
					if ($UserId <> ''){
						$queryConfirm  = "UPDATE ORDERS  SET order_status = 'basket' WHERE order_status = 'pre-basket' and user_id = '$UserId'";
						$resultConfirm = mysql_query($queryConfirm)		or die ("Erreur durant la maj<br><br>". $queryConfirm .'<br>' . mysql_error());
						//Redirection vers le basket
						header("Location:basket.php");
						exit();
					}
				
			}
			
			
			
			
			//Case update
			if ($_POST[UpdateQuantities]=='Update Quantities'){
				foreach($_POST[Quantity] as $cle => $valeur)
					{	
						if (($cle <> '') && ($valeur <> '') && ($valeur > 0)){
						//TODO METTRE LE PRIX DE LA COMMANDE A JOUR PAS JUSTE LA QUANTITÉE !  order_product_price 
						$queryFramePrice   = "SELECT order_product_price FROM orders WHERE primary_key = $cle ";
						$resultFramePrice  = mysql_query($queryFramePrice)		or die ("Erreur durant la maj<br><br>". $queryFramePrice .'<br>' . mysql_error());
						$DataFramePrice    = mysql_fetch_array($resultFramePrice);
						$FramePrice 	   = $DataFramePrice[order_product_price];
						
						$NewTotal    = $FramePrice * $valeur;
						$queryUpdate = "UPDATE ORDERS SET 
						order_quantity         = '$valeur' ,
						order_total            = '$NewTotal'
						WHERE primary_key      = $cle";
						$resultUpdate    = mysql_query($queryUpdate)		or die ("Erreur durant la maj<br><br>". $queryDelete .'<br>' . mysql_error());
						}elseif(($valeur == 0) && ($cle <> '')){
						$queryDelete = "DELETE FROM ORDERS WHERE primary_key = $cle";
						$resultDelete    = mysql_query($queryDelete)		or die ("Erreur durant la maj<br><br>". $queryDelete .'<br>' . mysql_error());
						}
					}
				}
	   }
	
	
	
	?>
	  
       
      <div style="margin:5px 20px; font-family:Verdana, Geneva, sans-serif;font-size:12px;">
       
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">

              <tr >
                <td colspan="2" align="right" bgcolor="#000099" class="formCellNosides">&nbsp;</td>
                </tr>
            </table>
            
    
 
    <table width="770" border="1" align="center" cellpadding="3" cellspacing="0"  >
	<tr>
        <th>ID</th>
        <th>Reference</th>
    	<th>Frame</th>
        <th>
		<?php if ($_SESSION["CompteEntrepot"] == 'yes') 
		echo 'Price Entrepot';
		else
		echo 'Price';?>
        </th>
        <th>Quantity</th>
        <th>Subtotal</th>
        <th>Select to delete</th>
    </tr>

<?php
$queryFrames   = "SELECT * FROM orders WHERE user_id = '". $_SESSION["sessionUser_Id"]. "' and order_num = -1 and order_status='pre-basket'";
$resultFrames  =  mysql_query($queryFrames)		or die ("Erreur durant la selection");
$count = 0;
$FramesTotalAmount  =  0;
$NumberofFrames     =  0;  
while ($DataPreBasket = mysql_fetch_array($resultFrames)){
$FramesTotalAmount    = $FramesTotalAmount + $DataPreBasket[order_total]  ; 
$NumberofFrames       = $NumberofFrames    + $DataPreBasket[order_quantity];

if ($_SESSION["CompteEntrepot"] =='yes'){
	$FramePrice = $listItem[stock_price_entrepot];		
}else{
	$FramePrice = $listItem[stock_price];
}


$count++;
if (($count%2)==0)
$Alerte=' bgcolor="#E5E5E5"';
else 
$Alerte=' bgcolor="#FFFFFF"';
				
echo '<tr>';
echo '<td'. $Alerte .' align="center">'   . $DataPreBasket[primary_key] . "</td>";
echo '<td'. $Alerte .' align="center">'   . $DataPreBasket[tray_num] . "</td>";
echo '<td width="315"'. $Alerte .' align="center">'   . $DataPreBasket[order_product_name] . "</td>";

if ($mylang == 'lang_french') { 
echo '<td'. $Alerte .' align="center">'   . $DataPreBasket[order_product_price]. '$</td>';
}else{
echo '<td'. $Alerte .' align="center">$'   . $DataPreBasket[order_product_price]. '</td>';
}
	
echo '<td align="center"'. $Alerte .' align="center"><input align="absmiddle"  type="text" maxlength="2"  size="2" name="Quantity['.$DataPreBasket[primary_key].']" id="Quantity['.$DataPreBasket[primary_key].'" value="'.$DataPreBasket[order_quantity] . '">' . '</td>';

if ($mylang == 'lang_french') { 
echo '<td'. $Alerte .' align="center">'   . $DataPreBasket[order_total]. '$</td>';
}else{
echo '<td'. $Alerte .' align="center">$'   . $DataPreBasket[order_total]. '</td>';
}

echo '<td'. $Alerte. ' align="center"><input type="checkbox"';
echo '  value="'.$DataPreBasket[primary_key].'"  id="PktoDelete['.$DataPreBasket[primary_key].']" name="PktoDelete['.$DataPreBasket[primary_key].']" </td></tr>';


}//END WHILE 

$FramesTotalAmount=money_format('%.2n',$FramesTotalAmount);
?>

<tr><td colspan="3">&nbsp;</td>
<th>Total</th>
<th align="center"><?php echo $NumberofFrames; ?></th>

<?php  if ($mylang == 'lang_french') {  ?>
          <th align="center"><?php echo $FramesTotalAmount; ?>$</th>
    <?php  }else{ ?>
           <th align="center">$<?php echo $FramesTotalAmount; ?></th>
    <?php  } ?>    </div>
       
<th>&nbsp;</th>
</tr>    
     </table>

		    <div align="right" style="margin:11px">&nbsp;
		      <input name="UpdateQuantities" type="submit" id="UpdateQuantities" class="formText"  value="<?php echo 'Update Quantities';?>" tabindex="1">
             <input name="from_validate_form" type="hidden" id="from_validate_form" value="yes">
            
             <div style="float:left;" align="left" style="margin:11px">&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="RedirectFrameOrder" type="submit" id="RedirectFrameOrder" class="formText"  value="<?php echo 'Add More Frames to my Order';?>" tabindex="1"><input name="from_validate_form" type="hidden" id="from_validate_form" value="yes">
		    </div>
            
            
              <div style="float:right;" align="right" style="margin:11px">&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="DeleteSelected" type="submit" id="DeleteSelected" class="formText"  value="<?php echo 'Delete Selected Frames';?>" tabindex="1">
		      <input name="from_validate_form" type="hidden" id="from_validate_form" value="yes">
		    </div>
		    </div>
            
            
              <div style="float:right;" align="right" style="margin:11px">&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="ConfirmOrder" type="submit" id="ConfirmOrder" class="formText"  value="<?php echo 'Add Frames to Basket';?>" tabindex="1">
		      <input name="from_validate_form" type="hidden" id="from_validate_form" value="yes">
		    </div>
            
		  </form>	

</td>
            
        </div>
       </div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
</body>
</html>