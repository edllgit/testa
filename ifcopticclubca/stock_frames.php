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

<script type="text/javascript" src="../includes/formvalidator.js"></script>
<script language="JavaScript" type="text/javascript">
	
function validate(theForm){
	
	if(theForm.ifc_frames_id.value==""){//Validate that a  frame model  is selected
		
	<?php  if ($mylang == 'lang_french') {  ?>
            alert("<?php echo 'Vous devez sélectionner un modèle de monture';?>");
    <?php  }else{ ?>
           alert("<?php echo 'You need to select a frame model';?>");
    <?php  } ?>   
	theForm.ifc_frames_id.focus();
	return (false);
	}

}
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">       
		$(document).ready(function () {     
			$("#TRAY").keyup(function (data) {              
				if ($(this).val() != "") {  
					$("#Submitbtn").removeAttr("disabled"); 
				} 
				else {  
					$("#Submitbtn").attr("disabled", "disabled"); 
				}  
			});
		});  
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
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">
      <div class="loginText">
        <div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
        </div><form action="stock_frames.php" method="post" name="stock" id="stock"  onSubmit="return validate(this)">
      	<div class="header"> 
	<?php  if ($mylang == 'lang_french') {  ?>
            Commande de Montures  
    <?php  }else{ ?>
            Order Frames 
    <?php  } ?>    </div>
       
       
       
      <?php 
	  $queryFirstOrder  = "SELECT * FROM orders WHERE  user_id = '".  $_SESSION["sessionUser_Id"] . "' AND order_product_type IN ('frame_stock_tray')";
	  $resultFirstOrder = mysql_query($queryFirstOrder)		or die ("Erreur durant le chargement des modeles disponibles");
	  $NbrFirstOrder    = mysql_num_rows($resultFirstOrder);
	  if ($NbrFirstOrder == 0){
			
			if ($mylang == 'lang_french') { 
				//echo '<p align="center"><a href="#">Profiter de l\'offre: 50% de rabais sur votre première commande de montures Free (30 montures et+)</a></p>';
			}else{ 
				//echo '<p align="center"><a href="#">Buy 30+ Free frames and get a 50% discount for this order</a></p>';
			} 	
		  
	  }
	  ?>
       
       
      <div style="margin:5px 20px; font-family:Verdana, Geneva, sans-serif;font-size:12px;">
	  <?php 
	  $AfficherPageCommande = true;
	  $queryBasket = "SELECT COUNT(*) as nbrResult FROM orders WHERE order_num = -1 AND user_id = '".  $_SESSION["sessionUser_Id"] . "' AND order_product_type NOT IN ('frame_stock_tray')";
	  $ResultBasket=mysql_query($queryBasket)		or die ("Erreur durant le chargement des modeles disponibles");
	  $DataBasket=mysql_fetch_array($ResultBasket);
			
			if ($DataBasket[nbrResult] > 0){
				$AfficherPageCommande = false;
					if ($mylang == 'lang_french') { 
						echo '<p align="center">Pour pouvoir commander des montures de stock, veuillez d\'abord terminer les commandes qui sont actuellement dans votre panier d\'achat.</p>';
					}else{ 
						echo '<p align="center">To order some stock frames, please process the orders that are already in your basket.</p>';
					} 		
			}
	    ?>
        
         
<?php  if ($AfficherPageCommande){  ?>
      
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">

              <tr >
                <td colspan="2" align="right" bgcolor="#000099" class="formCellNosides">&nbsp;</td>
                </tr>
              <tr >
                <td width="95" align="right" class="formCellNosides">* <?php  if ($mylang == 'lang_french') {  ?>
            Référence
    <?php  }else{ ?>
            Reference
    <?php  } ?>      </td>
                <td width="770" class="formCellNosides"><input name="TRAY"  type="text" id="TRAY" value="<?php echo $_POST[TRAY];?>" size="10"></td>
                </tr>
            </table>
            
            
      
            
            
              <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr>
                <td align="left" class="formCellNosides"> 
				<?php  if ($mylang == 'lang_french') {  ?>
                       Modèles
                <?php  }else{ ?>
                       Models
                <?php  } ?>  
                </td>
                <td align="left" class="formCellNosides">
				<?php  if ($mylang == 'lang_french') {  ?>
                       Quantité
                <?php  }else{ ?>
                       Quantity
                <?php  } ?> 
                </td>
              </tr>
              <tr>
				<td align="left"  class="formCell"><select name="ifc_frames_id" size="10" class="formText" id="ifc_frames_id">
                 	 <option value="" ><?php  if ($mylang == 'lang_french') {  ?>
                                               Sélectionner un modèle
                                        <?php  }else{ ?>
                                               Select a model
                                        <?php  } ?> </option>
                                        
                                         <option disabled="disabled" value="" ><?php  if ($mylang == 'lang_french') {  ?>
                                               COLLECTION&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MODÈLE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PRIX&nbsp;&nbsp;&nbsp;&nbsp;COULEUR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BOXING 
                                        <?php  }else{ ?>
                                               COLLECTION&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MODEL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                               &nbsp;&nbsp;PRICE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;COLOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BOXING
                                        <?php  } ?> </option>
                                        
                                   
          <?php
		  
		    $filtreEntrepot = " AND display_on_ifcca ='yes'";   
			if (($_SESSION["sessionUser_Id"] == 'entrepotifc')|| ($_SESSION["sessionUser_Id"] == 'entrepotframes') || ($_SESSION["sessionUser_Id"] == 'dlnfree')){
			$filtreEntrepot = " AND display_entrepot ='yes'";	
			}
			
 			if ($mylang == 'lang_french') {  
            	$query="SELECT ifc_frames_id, model, collection, misc_unknown_purpose, boxing, stock_price, stock_price_entrepot, color as color FROM ifc_frames_french WHERE ifc_frames_id not in (463,464,465,501) AND active=1 AND frame_on_sale <> 'yes'  $filtreEntrepot   ORDER BY misc_unknown_purpose, model ";
     		}else{ 
            	$query="SELECT ifc_frames_id, model, collection, misc_unknown_purpose, boxing,  stock_price,stock_price_entrepot, color_en as color FROM ifc_frames_french WHERE ifc_frames_id not in (463,464,465,501) AND active=1    AND frame_on_sale <> 'yes'  $filtreEntrepot    ORDER BY misc_unknown_purpose, model ";
		    } 
					
					
 					 //echo $query;
					 $result=mysql_query($query)		or die ("Erreur durant le chargement des modeles disponibles");
					 $usercount=mysql_num_rows($result);
 					
					 while ($listItem=mysql_fetch_array($result)){
						 
					//Si le client est l'entrepot, on oublie la quantitée commandée et on donne le prix entrepot 
				if (($_SESSION["sessionUser_Id"] == 'entrepotifc')|| ($_SESSION["sessionUser_Id"] == 'entrepotframes') || ($_SESSION["sessionUser_Id"] == 'dlnfree')){
						$stock_price 	  = $listItem[stock_price_entrepot];
					}	else{
						$stock_price 	  = $listItem[stock_price];	
					} 
					
					 $misc_unknown_purpose 		= $listItem[misc_unknown_purpose];
					 $model 			   		= $listItem[model];
					 $boxing 			   		= $listItem[boxing];
					 $color 			  		= $listItem[color];
					
										   					 
					
					 while(strlen(str_replace('&nbsp;',' ',$misc_unknown_purpose))<20){
					 	$misc_unknown_purpose = $misc_unknown_purpose . "&nbsp;";
					 }
					 echo '<br>Misc:'.$misc_unknown_purpose; 
									 
					 while(strlen(str_replace('&nbsp;',' ',$model))< 12){
					 	$model = $model . "&nbsp;";
					 }
					  echo '<br>model:'.$model; 
					  
					   while(strlen(str_replace('&nbsp;',' ',$stock_price))< 9){
					 	$stock_price = $stock_price . "&nbsp;";
					 }
					 $stock_price = '$'. $stock_price;
					 
					  while(strlen(str_replace('&nbsp;',' ',$color))< 26){
					 	$color = $color . "&nbsp;";
					 }
					 echo '<br>color:'.$color; 
					 
					 
					 
					 while(strlen(str_replace('&nbsp;',' ',$boxing))< 24){
					 	$boxing = $boxing . "&nbsp;";
					 }
					  echo '<br>boxing:'.$boxing; 
					

					 echo "<option value=\"$listItem[ifc_frames_id]\">";echo $misc_unknown_purpose . $model . $stock_price .' '. $color. $boxing."</option>";
					 }?>
                 </select></td>
                <td  class="formCellNosides">
                  <select name="Quantity" class="formText" id="Quantity">
                  	<option value="1" selected="selected" ><?php echo '1';?></option>
                    <option value="2"><?php echo '2';?></option>
                    <option value="3"><?php echo '3';?></option>
                    <option value="4"><?php echo '4';?></option>
                    <option value="5"><?php echo '5';?></option>
                    <option value="6"><?php echo '6';?></option>
                    <option value="7"><?php echo '7';?></option>
                    <option value="8"><?php echo '8';?></option>
                    <option value="9"><?php echo '9';?></option>
                    <option value="10"><?php echo '10';?></option>
                    <option value="11"><?php echo '11';?></option>
                    <option value="12"><?php echo '12';?></option>
                    <option value="13"><?php echo '13';?></option>
                    <option value="14"><?php echo '14';?></option>
                    <option value="15"><?php echo '15';?></option>
                    <option value="16"><?php echo '16';?></option>
                    <option value="17"><?php echo '17';?></option>
                    <option value="18"><?php echo '18';?></option>
                    <option value="19"><?php echo '19';?></option>
                    <option value="20"><?php echo '20';?></option>
                  </select></td>
              </tr>
              
              
              <tr>
             <td class="formCellNosides"> <?php  if ($mylang == 'lang_french') {  ?>
                 Après cet achat, voulez vous: 
                   <input type="radio" name="redirection" checked value="stock_frames" id="redirection">Commander d'autres montures  <input type="radio" name="redirection" value="basket" id="redirection">Voir votre panier d'achat 
                 <?php  }else{ ?>
                  After this order do you want to
                  <input type="radio" checked name="redirection" value="stock_frames" id="redirection">Order more frames  <input type="radio" name="redirection" value="basket" id="redirection">See your basket 
                 <?php  } ?></td> 
              </tr>
            </table>
            
            

		    <div align="center" style="margin:11px">&nbsp;
		      <input name="Submitbtn" type="submit" id="Submitbtn" class="formText" disabled="disabled"  value="<?php echo $btn_submit_txt;?>" tabindex="1">
		      <input name="from_form" type="hidden" id="from_form" value="yes">
		    </div>
		  </form>	
          
          
<?php  }  ?> 
          
          	    <?php 
	
	
	
	if (isset($_REQUEST[a])){
	 		
			if ($mylang == 'lang_french') {  
            	echo '<p>Monture '. $_REQUEST[a]. ' ajouté au panier avec succès</p>';
     		}else{
           		echo '<p>Frame '. $_REQUEST[a]. ' added to the basket sucessfully</p>';
    		} 
	}
	
	
	
	if ($_POST[from_form]=="yes"){
	
	//Validation quantity
		if (isset($_POST['Quantity'])==false){
		    if ($mylang == 'lang_french') {  
            	echo 'Erreur: Vous devez selectionner la quantité';
				exit();
     		}else{
           		echo 'Error: You need to select the quantity';
				exit();
    		} 
		}

		//Validation Modele de monture
		if ($_POST['ifc_frames_id'] ==''){
		    if ($mylang == 'lang_french') {  
            	echo 'Erreur: Vous devez selectionner un modèle de monture';
				exit();
     		}else{
           		echo 'Error: You need to select a frame model';
				exit();
    		} 
		}
	
	$UserId         	  = $_SESSION["sessionUser_Id"];
	$QueryLab       	  = "SELECT main_lab, currency FROM accounts WHERE user_id = '$UserId'";
	$ResultLab      	  = mysql_query($QueryLab)		or die ("Erreur durant la selection");
	$DataLab        	  = mysql_fetch_array($ResultLab);	
	$OrderNum        	  = -1;
	$TrayNum        	  = $_POST['TRAY'] ;
	$Lab             	  = $DataLab[main_lab];
	$PrescriptLab    	  = 21;
	$Eye 			 	  = 'Both';
	$OrderItemNumber 	  = $_POST["ifc_frames_id"];//ID du frame commandé	
	$today		     	  = mktime(0,0,0,date("m"),date("d"),date("Y"));
	$DateProcessed   	  = date("Y-m-d", $today);
	$DateShipped     	  = "0000-00-00";
	$OrderItemDate   	  = $DateProcessed;
	$Quantity	    	  = $_POST['Quantity'];
	
	$QueryProdName   	  = "SELECT  misc_unknown_purpose, 	collection,  code, material_en, stock_price, stock_price_entrepot, boxing,stock_price_with_discount  FROM ifc_frames_french WHERE ifc_frames_id = $OrderItemNumber";
	$ResultProdName  	  = mysql_query($QueryProdName)		or die ("Could not select items");
	$DataProdName    	  = mysql_fetch_array($ResultProdName);
	$ProductName		  = $DataProdName[misc_unknown_purpose] . ' #'  . $DataProdName[code] . ' '  . $DataProdName[boxing];
	$FrameType  		  = $DataProdName[material_en];
	$ProductID      	  = $OrderItemNumber;
	$Material 			  = $DataProdName[material_en]; 
	
	
	$queryFrameDansBasket  = "SELECT order_quantity  FROM ORDERS WHERE order_num = -1 and order_product_type = 'frame_stock_tray' and user_id='$UserId' ";
	$resultFrameDansBasket = mysql_query($queryFrameDansBasket)		or die ("Erreur durant la selection");
	$FramesDansleBasket = 0;
	while ($DataFrameDansBasket   = mysql_fetch_array($resultFrameDansBasket)){
	$FramesDansleBasket = $FramesDansleBasket + $DataFrameDansBasket[order_quantity];
		
	}//End While 

	
	//A la base , on prend le stock price
	//Si le client commande au moins 10 montures, on lui charge le prix escompté
	if (($Quantity + $FramesDansleBasket) >=10){
	$StockPrice 	 	  = $DataProdName['stock_price_with_discount'];//
	}else{
	$StockPrice 	 	  = $DataProdName['stock_price'];//
	}
	
	//Si le client est l'entrepot, on oublie la quantitée commandée et on donne le prix entrepot 
	if (($_SESSION["sessionUser_Id"] == 'entrepotifc')|| ($_SESSION["sessionUser_Id"] == 'entrepotframes')|| ($_SESSION["sessionUser_Id"] == 'dlnfree')){
		$StockPrice 	  = $DataProdName['stock_price_entrepot'];
	}
	
	$OrderProductDiscount = $StockPrice;
	$ShippingCost		  = 0;
	$ShippingMethod 	  = "Stock Shipping";
	$OrderType 			  = "frame_stock_tray";
	$OrderStatus 		  = "basket";
	$OrderTotal 		  = $Quantity * $StockPrice;
	$Currency 			  = $DataLab[currency];
	$CustomerIP		 	  = $_SERVER['REMOTE_ADDR'];
	$OrderFrom 			  = "ifcclubca";
	
//Code pour insérer dans la base de donnée le/les frames qui ont été sélectionnés
$queryInsert = "INSERT INTO orders (user_id,order_num,tray_num,lab,prescript_lab,
eye,order_item_number,order_date_processed,order_date_shipped,order_item_date,order_quantity, 
order_product_name,order_product_id, order_product_material, order_product_price,order_product_discount,
order_shipping_cost,order_shipping_method, order_product_type, order_status,order_total, currency, ip, order_from, frame_type)
VALUES ('$UserId', $OrderNum, '$TrayNum', $Lab, $PrescriptLab, '$Eye', $ProductID, '$DateProcessed', '$DateShipped', '$OrderItemDate', $Quantity, '$ProductName','$ProductID', '$Material','$StockPrice','$OrderProductDiscount', '$ShippingCost', '$ShippingMethod', '$OrderType' , '$OrderStatus', '$OrderTotal' , '$Currency', '$CustomerIP', '$OrderFrom','$FrameType')";
$ResultInsert=mysql_query($queryInsert)		or die ("Could not select items");
?>

<?php //Redirection selon ce que le client a choisit ?>
<?php  if ($_POST[redirection] == 'basket'){  ?>
<form id="redirect" action="basket.php" method="get">
	
</form>
<script>
    document.getElementById('redirect').submit();
</script>
<?php }else{  ?>
<form id="redirect" action="stock_frames.php?a=s" method="get">
	<input type="hidden" name="a" value="<?php  echo $ProductName;?>">
</form>
<script>
    document.getElementById('redirect').submit();
</script>
<?php }//End IF ?>
<?php	}// END IF FORM POSTED	?></td>
            
        </div>
       </div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->


</body>
</html>