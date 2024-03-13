<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
include"config.inc.php";
global $drawme;
$prod_table="safety_frames_french";
require_once "../upload/phpuploader/include_phpuploader.php"; ?> 
<?php 
session_start();

$_SESSION['svFormVars']="";//RESET FORM VARS
$_SESSION['prFormVars']="";

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");	
	require('../Connections/sec_connect.inc.php');
	 
	//Hard codé le lab st-catharines
	$LabInventaire = 3;
	$where_clause=" WHERE active='1' ";
	
	//Temporairement on ne liera pas l'inventaire avec aucun lab, a mettre a jour quand la décision sera connu
	$where_clause.=" AND frame_selling_price <> 0 ";						
	mysql_query("SET CHARACTER SET UTF8");
	
	$query="SELECT  * FROM safety_frames_french  ".$where_clause." ORDER BY model, color"; 
	$result=mysql_query($query)		or die ("Could not select products because ".mysql_error()." query=".$query);
	$prodCount=mysql_num_rows($result);
	//echo $query;	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $sitename;?></title>

<script type="text/javascript" src=" https://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.js"></script>
<script src="js/popup.js" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript">
	
function validate(theForm){
	
	if(theForm.safety_frames_id.value==""){//Validate that a  frame model  is selected
		
	<?php  if ($mylang == 'lang_french') {  ?>
            alert("<?php echo 'Vous devez sélectionner un modèle de monture';?>");
    <?php  }else{ ?>
           alert("<?php echo 'You need to select a frame model';?>");
    <?php  } ?>   
	theForm.safety_frames_id.focus();
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
<script type="text/javascript">

function slideSwitch() {
    var $active = $('#slideshow IMG.active');

    if ( $active.length == 0 ) $active = $('#slideshow IMG:last');

    // use this to pull the images in the order they appear in the markup
    var $next =  $active.next().length ? $active.next()
        : $('#slideshow IMG:first');

    $active.addClass('last-active');

    $next.css({opacity: 0.0})
        .addClass('active')
        .animate({opacity: 1.0}, 100, function() {
            $active.removeClass('active last-active');
        });
}

$(function() {
    setInterval( "slideSwitch()", 500 );
});

</script>

<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />

<style type="text/css">

body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}
#slideshow {
	position:relative;
	width:550px;
	height:250px;
}
#slideshow IMG {
	position:absolute;
	top:10;
	left:10;
	z-index:8;
	opacity:0.0;
}
#slideshow IMG.active {
    z-index:10;
    opacity:1.0;
}

#slideshow IMG.last-active {
    z-index:9;
}
</style>

<script language="JavaScript" type="text/javascript">

<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>

<link href="products.css" rel="stylesheet" type="text/css" />
<link href="css/popup.css" rel="stylesheet" type="text/css" />
</head>


<body>
<div id="backgroundPopup"></div>
<div id="popupForm">
	<a id="popupFormClose">x</a>    
    <div id="returnMessage">x</div>		   
</div>

   
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  	<?php include("includes/sideNav.inc.php");	?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">
	<div class="loginText">
		<?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?> 
	</div>
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    	<tr>
        	<td>
            	<div id="headerBox" class="header" style="width:400px">            	
                <?php if ($mylang == 'lang_french'){?>
                    Choisissez vos montures
                <?php }else {?>
                    Choose Your Frames
                <?php }?>                
                </div>
            </td>
         </tr>
    </table>	      
    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
        <tr>
            <td width="57%" bgcolor="#ee7e32" class="tableHead"> 
                <?php echo $prodCount;?>                 
                <?php if ($mylang == 'lang_french'){?>
                    Montures disponibles
                <?php }else {?>
                    Frames Available
                <?php }?>                    
            </td>
            <td width="43%" bgcolor="#ee7e32" class="tableHead">&nbsp;</td>
        </tr>
    </table>
<div> 
        <?php
		//display table of products
			if ($prodCount==0){
				echo "<table width=\"700\" border=\"0\" cellpadding=\"0\" align=\"center\">";
				echo "<tr><td align=\"center\" valign=\"middle\"><div class=\"home_features_header\">Sorry, no items found.</div></td></tr>";
				echo "</table>";
			}else{
				echo "<table width=\"700\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">";
				$count=0;
				while ($productData=mysql_fetch_array($result)){			 
					if ($count==0)
						echo "<tr valign=\"top\">";
					$count++;
					
					
					$productData[prod_tn]="http://www.direct-lens.com/safety/frames_images/lr/".$productData[image]  ;
				
					$productData['model']=stripslashes($productData['model']);
					$productData['price_us']=stripslashes($productData['price_us']);
					echo "<td width=\"33%\" align=\"center\">";

					echo "<div class=\"item-box\">";
					echo "<div class=\"product-box\"><img src=\"$productData[prod_tn]\" alt=\"$productData[model]-$productData[color_en]\" border=\"0\" title=\"$productData[model]-$productData[color_en]\" width=\"190\"></div>";
					echo "<div class=\"product-name\">$productData[upc]</div>";
					
		

		if ($productData['express']=="1"){ ?>
			<img src="http://www.direct-lens.com/safety/design_images/logo-express.gif" width="40" height="40" border="0" align="right">
<?php  } 
				if ($mylang == 'lang_french'){			
					echo "<div class=\"priceText\">TYPE : $productData[type]</div>";
					echo "<div class=\"priceText\">GENRE : $productData[gender]</div>";
					echo "<div class=\"priceText\">MATIERE : $productData[material]</div>";
					echo "<div class=\"priceText\">COULEUR : $productData[color]</div>";
					echo "<div class=\"priceText\">TAILLE : $productData[boxing]</div>";
					echo "<div class=\"priceText\" style=\"padding-top:4px\"></div>";				
                } else {
					echo "<div class=\"priceText\">TYPE: $productData[type_en]</div>";
					echo "<div class=\"priceText\">GENDER: $productData[gender_en]</div>";
					echo "<div class=\"priceText\">MATERIAL: $productData[material_en]</div>";
					echo "<div class=\"priceText\">COLOUR: $productData[color_en]</div>";
					echo "<div class=\"priceText\">SIZE: $productData[boxing]</div>";
					echo "<div class=\"priceText\" style=\"padding-top:4px\"></div>"; 
                }  		
					echo "</div>";

					echo "</td>";
					if (($count%3)==0){
						echo "</tr>";
						$count=0;
					}
				}//END WHILE
				
			if ($count==1)
				echo "<td width=\"33%\" align=\"center\">&nbsp;</td><td width=\"34%\" align=\"center\">&nbsp;</td></tr>";
			if ($count==2)
				echo "<td width=\"34%\" align=\"center\">&nbsp;</td></tr>";

			echo "</table>";
			
	
		}//END IF PROD COUNT
		
?>

                       </td></tr>
               </table>
             </div>
             
             
             
             
      <?php //////////// ?>       
             
             
		<form action="plano.php" method="post" name="stock" id="stock"  onSubmit="return validate(this)">
      	<div class="header"> 
	<?php  if ($mylang == 'lang_french') {  ?>
            Commande de Montures Plano 
    <?php  }else{ ?>
            Order Plano Frames 
    <?php  } ?>    </div>
       
       
       
      <div style="margin:5px 20px; font-family:Verdana, Geneva, sans-serif;font-size:12px;">
		   
      <?php 
	  $AfficherPageCommande = true;
	  $queryBasket = "SELECT COUNT(*) as nbrResult FROM orders WHERE order_num = -1 AND user_id = '".  $_SESSION["sessionUser_Id"] . "' AND order_product_type NOT IN ('frame_stock_tray')";
	       // echo   $queryBasket;
	        $ResultBasket=mysql_query($queryBasket)		or die ("Erreur durant le chargement des modeles disponibles");
		    $DataBasket=mysql_fetch_array($ResultBasket);
			//echo 'Element dans le basket autre que frames stock: '.  $DataBasket[nbrResult];
			
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
				<td align="left"  class="formCell"><select name="safety_frames_id" size="10" class="formText" id="safety_frames_id">
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
 			if ($mylang == 'lang_french') {  
            	$query="SELECT safety_frames_id, model, collection, misc_unknown_purpose, boxing,  color as    color,  frame_selling_price  FROM safety_frames_french WHERE  active=1    ORDER BY misc_unknown_purpose, model ";
     		}else{ 
            	$query="SELECT safety_frames_id, model, collection, misc_unknown_purpose, boxing,  color_en as color,  frame_selling_price  FROM safety_frames_french WHERE  active=1    ORDER BY misc_unknown_purpose, model ";
		    } 
			//echo 'query:'.	$query	;
					
 					
					 $result=mysql_query($query)		or die ("Erreur durant le chargement des modeles disponibles".mysql_error());
					 $usercount=mysql_num_rows($result);
 					
					 while ($listItem=mysql_fetch_array($result)){
					
					 $misc_unknown_purpose 		= $listItem[misc_unknown_purpose];
					 $model 			   		= $listItem[model];
					 $boxing 			   		= $listItem[boxing];
					 $color 			  		= $listItem[color];
					 $stock_price 		  	    = $listItem[frame_selling_price];
										   					 
					
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
					

					 echo "<option value=\"$listItem[safety_frames_id]\">";echo $misc_unknown_purpose . $model . $stock_price .' '. $color. $boxing."</option>";
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
		if ($_POST['safety_frames_id'] ==''){
		    if ($mylang == 'lang_french') {  
            	echo 'Erreur: Vous devez selectionner un modèle de monture';
				exit();
     		}else{
           		echo 'Error: You need to select a frame model';
				exit();
    		} 
		}
	
	$UserId          = $_SESSION["sessionUser_Id"];
	$QueryLab        = "SELECT main_lab, currency FROM accounts WHERE user_id = '$UserId'";
	$ResultLab       = mysql_query($QueryLab)		or die ("Erreur durant la selection");
	$DataLab         = mysql_fetch_array($ResultLab);
		
	$OrderNum       	  = -1;
	$TrayNum         	  = $_POST['TRAY'] ;
	$Lab             	  = $DataLab[main_lab];
	$PrescriptLab    	  = 21;
	$Eye 			 	  = 'Both';
	$OrderItemNumber 	  = $_POST["safety_frames_id"];//ID du frame commandé	
	$today		     	  = mktime(0,0,0,date("m"),date("d"),date("Y"));
	$DateProcessed   	  = date("Y-m-d", $today);
	$DateShipped     	  = "0000-00-00";
	$OrderItemDate   	  = $DateProcessed;
	$Quantity	    	  = $_POST['Quantity'];
	
	$QueryProdName   	  = "SELECT  misc_unknown_purpose, 	collection,  code, material_en, frame_selling_price, boxing  FROM safety_frames_french WHERE safety_frames_id = $OrderItemNumber";
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
	//if (($Quantity + $FramesDansleBasket) >=10){
	//$StockPrice 	 	  = $DataProdName['stock_price_with_discount']*1.05;//taxes 5%
	//}else{
	//$StockPrice 	 	  = $DataProdName['frame_selling_price']*1.05;//taxes 5%	
	$StockPrice 	 	  = $DataProdName['frame_selling_price'];
	//}
	
	$OrderProductDiscount = $StockPrice;
	$ShippingCost		  = 0;
	$ShippingMethod 	  = "Stock Shipping";
	$OrderType 			  = "frame_stock_tray";
	$OrderStatus 		  = "basket";
	$OrderTotal 		  = $Quantity * $StockPrice;
	$Currency 			  = $DataLab[currency];
	$CustomerIP		 	  = $_SERVER['REMOTE_ADDR'];
	$OrderFrom 			  = "safety";
	
	//Code pour insérer dans la base de donnée le/les frames qui ont été sélectionnés
	$queryInsert = "INSERT INTO orders (user_id,order_num,tray_num,lab,prescript_lab,
eye,order_item_number,order_date_processed,order_date_shipped,order_item_date,order_quantity, 
order_product_name,order_product_id, order_product_material, order_product_price,order_product_discount,
order_shipping_cost,order_shipping_method, order_product_type, order_status,order_total, currency, ip, order_from, frame_type)
VALUES ('$UserId', $OrderNum, '$TrayNum', $Lab, $PrescriptLab, '$Eye', $ProductID, '$DateProcessed', '$DateShipped', '$OrderItemDate', $Quantity, '$ProductName','$ProductID', '$Material','$StockPrice','$OrderProductDiscount', '$ShippingCost', '$ShippingMethod', '$OrderType' , '$OrderStatus', '$OrderTotal' , '$Currency', '$CustomerIP', '$OrderFrom','$FrameType')";

//echo 'insert: ' .$queryInsert;

$ResultInsert=mysql_query($queryInsert)		or die ("Could not select items");
//echo '<br>Ajouté au panier avec succes';

?>



<?php //Redirection selon ce que le client a choisit ?>
<?php  if ($_POST[redirection] == 'basket'){  ?>
<form id="redirect" action="basket.php" method="get">
	
</form>
<script>
    document.getElementById('redirect').submit();
</script>
<?php }else{  ?>
<form id="redirect" action="plano.php?a=s" method="get">
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
 <?php include("footer.inc.php"); ?>
</div><!--END containter-->



</body>
</html>
<?php
mysql_free_result($languages);
mysql_free_result($languagetext);
?>