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

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");
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
        </div><form action="stock_frames2.php" method="post" name="stock" id="stock"  onSubmit="return validate(this)">
      	<div class="header"> 
	<?php  if ($mylang == 'lang_french') {  ?>
            Commande de Montures  
    <?php  }else{ ?>
            Order Frames 
    <?php  } ?>    </div>
       
       
       
      <div style="margin:5px 20px; font-family:Verdana, Geneva, sans-serif;font-size:12px;">
	  <?php 
	  $AfficherPageCommande = true;
	  $queryBasket = "SELECT COUNT(*) as nbrResult FROM orders WHERE order_num = -1 AND user_id = '".  $_SESSION["sessionUser_Id"] . "' AND order_product_type NOT IN ('frame_stock_tray')";
	  $ResultBasket=mysqli_query($con,$queryBasket)		or die ("Erreur durant le chargement des modeles disponibles");
	  $DataBasket=mysqli_fetch_array($ResultBasket,MYSQLI_ASSOC);
			
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
      
		    <table width="700" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">

              <tr >
                <td colspan="2" align="right" bgcolor="#000099" class="formCellNosides">&nbsp;</td>
                </tr>
                
                <tr>
                	<td>
                    <p align="center">
                    <?php
                    if ($mylang == 'lang_french') {  
            			echo 'Filtrer par collection:';
     				}else{
           				echo 'Filter by collection:';
    				} 
					?>
                	 &nbsp;&nbsp;&nbsp;&nbsp;
                     <select name="filter_collection" id="filter_collection" class="formText" style="margin-right:5px; margin-bottom:3px" onchange="reloadpage()">
					 <option value="">
					 <?php if ($mylang == 'lang_french') {  
            			   	   echo '<p>Toutes les collections</p>';
     						}else{
           					   echo '<p>All collections</p>';
    						}?>
                     </option>
					 </p>
                    
                     
                     
                    <?php 
					 	if ($_SESSION["CompteEntrepot"] == 'yes')
						$filtreEntrepot = "  display_entrepot ='yes'";	
						else
						$filtreEntrepot = "  display_on_ifcca ='yes'";  
						
						$sqlCollection    = "SELECT distinct misc_unknown_purpose FROM ifc_frames_french WHERE $filtreEntrepot AND misc_unknown_purpose <> 'AUTRES'  ORDER BY misc_unknown_purpose";
						$resultCollection = mysqli_query($con,$sqlCollection)	or die ("ERROR:".mysqli_error($con)." sql=".$sql);
                        while ($DataCollection=mysqli_fetch_array($resultCollection,MYSQLI_ASSOC)){
							echo "<option value=\"$DataCollection[misc_unknown_purpose]\"";
							if ($_POST[filter_collection] == $DataCollection[misc_unknown_purpose])
							echo ' selected="selected" ';
							echo ">$DataCollection[misc_unknown_purpose]</option>";	
						}//End While
                     ?>
                     </select>
                    </td>
                </tr>
            </table>
            
    </form>
    
    <form action="validate_frames.php" method="post" name="stock" id="stock"  onSubmit="return validate(this)">
    <?php 

if ((isset($_POST[filter_collection])) && ($_POST[filter_collection] <> ''))
$FiltreCollection = "AND misc_unknown_purpose = '" . $_POST[filter_collection] . "'";
else
$FiltreCollection = ' AND 1=1 ';
				
	if ($mylang == 'lang_french') {  
   		$query="SELECT ifc_frames_id, model, collection, misc_unknown_purpose, boxing, frame_a, stock_price, stock_price_entrepot, color, color_en FROM ifc_frames_french WHERE ifc_frames_id not in (463,464,465,501) AND active=1 AND              frame_on_sale <> 'yes'  AND $filtreEntrepot $FiltreCollection AND ifc_frames_id not in (3173,1673,1892)   ORDER BY misc_unknown_purpose, model ";
    }else{ 
   	    $query="SELECT ifc_frames_id, model, collection, misc_unknown_purpose, boxing, frame_a, stock_price,stock_price_entrepot, color, color_en FROM ifc_frames_french WHERE ifc_frames_id not in (463,464,465,501) AND active=1    AND 	    	frame_on_sale <> 'yes' AND  $filtreEntrepot $FiltreCollection AND ifc_frames_id not in (3173,1673,1892)    ORDER BY misc_unknown_purpose, model ";
	} 
   $result=mysqli_query($con,$query)		or die ("Erreur durant le chargement des modeles disponibles");
   $usercount=mysqli_num_rows($result);				
   ?>
   
    <table width="700" border="1" align="center" cellpadding="3" cellspacing="0" >
	 <tr >
                <td align="center" width="95" align="right" class="formCellNosides">
                <p align="center">
	<?php  if ($mylang == 'lang_french') {  ?>
            Référence
    <?php  }else{ ?>
            Reference
    <?php  } ?> 
&nbsp;&nbsp;&nbsp;<input name="TRAY_NUM"  type="text" id="TRAY_NUM" value="<?php echo $_POST[TRAY];?>" size="10"></p></td>
                </tr>
    </table>
     <table width="700" border="1" align="center" cellpadding="3" cellspacing="0" >
    <tr>
    	<th width="70">ID</th>
        <th width="170">Collection</th>
        <th width="150">Model</th>
        <th width="100">Color</th>
        <th width="100">Color EN</th>
        <th width="100">Frame A</th>
        <th width="100"><?php if ($_SESSION["CompteEntrepot"] == 'yes') 
		echo 'Price Entrepot';
		else
		echo 'Price';?></th>
       <?php /*?> <th >Select</th><?php */?>
        <th width="50">Qty</th>
    </tr>

<?php
$count = 0;
while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
	
if ($_SESSION["CompteEntrepot"] =='yes'){
	$FramePrice = $listItem[stock_price_entrepot];
}else{
	$FramePrice = $listItem[stock_price];
}

$count++;
if (($count%2)==0)
$BgColor = ' bgcolor="#E5E5E5" ';
else 
$BgColor = ' bgcolor="#FFFFFF" ';
	
if ($FramePrice=='0.00')
$BgColor=' bgcolor="#FF0509"';
				
	

echo '<tr>';
echo '<td  '  .  $BgColor  . ' align="center">' . 	$listItem[ifc_frames_id]        . '</td>';
echo '<td  '  .  $BgColor  . ' align="center">' . 	$listItem[misc_unknown_purpose] . '</td>';
echo '<td  '  .  $BgColor  . ' align="center">' . 	$listItem[model]                . '</td>';
echo '<td  '  .  $BgColor  . ' align="center">' . 	$listItem[color]                . '</td>';
echo '<td  '  .  $BgColor  . ' align="center">' . 	$listItem[color_en]             . '</td>';
echo '<td  '  .  $BgColor  . ' align="center">' . 	$listItem[frame_a]              . '</td>';
echo '<td  '  .  $BgColor  . ' align="center">' . 	$FramePrice                     . '$</td>';
echo '<td  '  .  $BgColor  . ' align="center">';
?>
<input type="text" name="quantity_ordered[<?php echo $listItem[ifc_frames_id]; ?>]" value="" size="2" id="quantity_ordered"  title="Quantity" <?php if ($FramePrice=='0.00'){ echo ' disabled ';} ?> />
<?php
echo '</td>';
}
?>

    
     </table>
    

		    <div align="center" style="margin:11px">&nbsp;
		      <input name="Submitbtn" type="submit" id="Submitbtn" class="formText"  value="<?php echo $btn_submit_txt;?>" tabindex="1">
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
	
?></td>
            
        </div>
       </div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->

</body>
</html>